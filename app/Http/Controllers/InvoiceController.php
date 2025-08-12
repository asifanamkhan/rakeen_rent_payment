<?php

namespace App\Http\Controllers;

use App\Service\CustomTcPDFHF;
use App\Service\GeneratePdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function apartment_list_pdf(Request $request)
    {
        $rows = DB::table('VW_SRV_APARTMENT_INFO')
            ->orderBy('HANDOVER_DATE', 'ASC')
            ->get([
                'PROJECT_NAME',
                'TOWER_ID',
                'PRODUCT_ID',
                'PRODUCT_TYPE',
                'CUSTOMER_ID',
                'BOOKING_ID',
                'HANDOVER_DATE',
            ]);

        $html = view()->make('livewire.dashboard.reports.invoice.apartment-list-pdf', [
            'rows' => $rows,
        ])->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'apartment-list.pdf',
        ];

        GeneratePdf::generate($pdf_data, 'APARTMENT LIST');
    }

    public function customer_list_pdf(Request $request)
    {
        $rows = DB::table('VW_SRV_CUSTOMER_INFO')
            ->orderBy('customer_id', 'DESC')
            ->get([
                'customer_name',
                'customer_id',
                'cell_no',
                'email_id',
                'booking_id',
                'PRODUCT_ID',
                'PRODUCT_TYPE',
                'HANDOVER_DATE',
            ]);

        $html = view()->make('livewire.dashboard.reports.invoice.customer-list-pdf', [
            'rows' => $rows,
        ])->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'customer-list.pdf',
        ];

        GeneratePdf::generate($pdf_data, 'CUSTOMER LIST');
    }

    public function service_payment_report_pdf(Request $request)
    {
        $productId = $request->input('product_id');
        $fromMonth = $request->input('from_month');
        $toMonth = $request->input('to_month');
        $serviceType = $request->input('service_type');

        $query = DB::table('VW_SRV_PAYMENT_INFO as p')
            ->where('p.status', 1);

        if ($productId) {
            $query->where('p.product_id', $productId);
        }

        if ($serviceType) {
            $query->where('p.service_name', $serviceType);
        }

        if ($fromMonth) {
            $start = date('Y-m-01', strtotime($fromMonth . '-01'));
            $query->where('p.bill_month', '>=', $start);
        }

        if ($toMonth) {
            $end = date('Y-m-t', strtotime($toMonth . '-01'));
            $query->where('p.bill_month', '<=', $end);
        }

        $rows = $query->orderBy('p.bill_month', 'DESC')
            ->orderBy('p.product_id', 'ASC')
            ->get([
                'p.bill_month',
                'p.product_id',
                'p.customer_id',
                'p.customer_name',
                'p.service_name',
                'p.auto_receipt_no',
                'p.paid_amount',
                'p.payment_date',
            ]);

        $filters = [
            'product_id' => $productId,
            'from_month' => $fromMonth,
            'to_month' => $toMonth,
            'service_type' => $serviceType,
        ];

        $html = view()->make('livewire.dashboard.reports.invoice.service-payment-report-pdf', [
            'rows' => $rows,
            'filters' => $filters,
        ])->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'service-payment-report.pdf',
        ];

        GeneratePdf::generate($pdf_data, 'SERVICE PAYMENT REPORT');
    }
    public function service_bill_invoice($bill_id)
    {
        $bill = DB::table('VW_SRV_APARTMENT_BILL_INFO as b')
            ->where('bill_id', $bill_id)
            ->first([
                'b.*',
            ]);
        $data = [
            'bill' => $bill
        ];

        $html = view()->make('livewire.dashboard.reports.invoice.service-bill-invoice', $data)->render();
        $pdf_data = [
            'html' => $html,
            'filename' => 'service-bill-invoice.pdf',
        ];

        GeneratePdf::generate($pdf_data, 'SERVICE BILL INVOICE');
    }

    public function money_receipt_print($receipt_id, $type)
    {

        $payment = DB::table('VW_SRV_PAYMENT_INFO as p')
            ->where('receipt_id', $receipt_id)
            ->first([
                'p.*',
            ]);

        if (!$payment) {
            abort(404, 'Payment receipt not found');
        }

        $data = [
            'bill' => $payment,
            'report_name' => 'MONEY RECEIPT',
        ];
        $report_name = 'MONEY RECEIPT';
        // if($type == 'SERVICE'){
        //     $report_name = 'SERVICE BILL INVOICE';
        // }else if($type == 'ELECTRICITY'){
        //     $report_name = 'ELECTRICITY BILL INVOICE';
        // }else if($type == 'WATER'){
        //     $report_name = 'WATER BILL INVOICE';
        // }

        $html = view()->make('livewire.dashboard.reports.invoice.money-receipt', $data)->render();
        $pdf_data = [
            'html' => $html,
            'filename' => 'money-receipt-' . $receipt_id . '.pdf',
        ];

        GeneratePdf::generate($pdf_data, $report_name);
    }

    public function service_due_report_pdf(Request $request)
    {
        $productId = $request->input('product_id');
        $fromMonth = $request->input('from_month');
        $toMonth = $request->input('to_month');

        $sql = "
            SELECT
                b.PRODUCT_ID,
                b.CUSTOMER_ID,
                b.CUSTOMER_NAME,
                b.PRODUCT_TYPE,
                LISTAGG(TO_CHAR(b.bill_month, 'MON, YYYY') || ' - ' || b.tot_bill_amt, ' | ')
                    WITHIN GROUP (ORDER BY b.bill_month) AS unpaid_months_with_amounts,
                SUM(b.tot_bill_amt) AS total_unpaid_amount,
                r.paid_amount
            FROM VW_SRV_APARTMENT_BILL_INFO b
            LEFT JOIN SRV_PAYMENT_RECEIPT r
                ON r.APARTMENT_ID = b.PRODUCT_ID
                AND r.STATUS = 'OP'
            WHERE b.STATUS = 'UNPAID'
        ";

        $bindings = [];

        if ($productId) {
            $sql .= " AND b.PRODUCT_ID = :product_id";
            $bindings['product_id'] = $productId;
        }

        if ($fromMonth) {
            $start = date('Y-m-01', strtotime($fromMonth . '-01'));
            $sql .= " AND TRUNC(b.bill_month, 'MM') >= TO_DATE(:start_month, 'YYYY-MM-DD')";
            $bindings['start_month'] = $start;
        }

        if ($toMonth) {
            $end = date('Y-m-t', strtotime($toMonth . '-01'));
            $sql .= " AND TRUNC(b.bill_month, 'MM') <= TO_DATE(:end_month, 'YYYY-MM-DD')";
            $bindings['end_month'] = $end;
        }

        $sql .= "
            GROUP BY
                b.PRODUCT_ID,
                b.CUSTOMER_ID,
                b.CUSTOMER_NAME,
                b.PRODUCT_TYPE,
                r.paid_amount
            ORDER BY b.PRODUCT_ID
        ";

        $rows = DB::select($sql, $bindings);

        $filters = [
            'product_id' => $productId,
            'from_month' => $fromMonth,
            'to_month' => $toMonth,
        ];

        $html = view()->make('livewire.dashboard.reports.invoice.service-due-report-pdf', [
            'rows' => $rows,
            'filters' => $filters,
        ])->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'service-due-report.pdf',
        ];

        GeneratePdf::generate($pdf_data, 'SERVICE DUE REPORT');
    }

    public function service_due_report_summary_pdf(Request $request)
    {
        $productId = $request->input('product_id');
        $fromMonth = $request->input('from_month');
        $toMonth = $request->input('to_month');

        $sql = "
            SELECT
                b.PRODUCT_ID,
                b.CUSTOMER_ID,
                b.CUSTOMER_NAME,
                b.PRODUCT_TYPE,
                SUM(b.tot_bill_amt) AS total_unpaid_amount,
                r.paid_amount
            FROM VW_SRV_APARTMENT_BILL_INFO b
            LEFT JOIN SRV_PAYMENT_RECEIPT r
                ON r.APARTMENT_ID = b.PRODUCT_ID
                AND r.STATUS = 'OP'
            WHERE b.STATUS = 'UNPAID'
        ";

        $bindings = [];

        if ($productId) {
            $sql .= " AND b.PRODUCT_ID = :product_id";
            $bindings['product_id'] = $productId;
        }

        if ($fromMonth) {
            $start = date('Y-m-01', strtotime($fromMonth . '-01'));
            $sql .= " AND TRUNC(b.bill_month, 'MM') >= TO_DATE(:start_month, 'YYYY-MM-DD')";
            $bindings['start_month'] = $start;
        }

        if ($toMonth) {
            $end = date('Y-m-t', strtotime($toMonth . '-01'));
            $sql .= " AND TRUNC(b.bill_month, 'MM') <= TO_DATE(:end_month, 'YYYY-MM-DD')";
            $bindings['end_month'] = $end;
        }

        $sql .= "
            GROUP BY
                b.PRODUCT_ID,
                b.CUSTOMER_ID,
                b.CUSTOMER_NAME,
                b.PRODUCT_TYPE,
                r.paid_amount
            ORDER BY b.PRODUCT_ID
        ";

        $rows = DB::select($sql, $bindings);

        $filters = [
            'product_id' => $productId,
            'from_month' => $fromMonth,
            'to_month' => $toMonth,
        ];

        $html = view()->make('livewire.dashboard.reports.invoice.service-due-report-summary-pdf', [
            'rows' => $rows,
            'filters' => $filters,
        ])->render();

        $pdf_data = [
            'html' => $html,
            'filename' => 'service-due-report-summary.pdf',
        ];

        GeneratePdf::generate($pdf_data, 'SERVICE DUE REPORT SUMMARY');
    }
}
