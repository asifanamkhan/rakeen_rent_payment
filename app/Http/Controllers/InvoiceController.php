<?php

namespace App\Http\Controllers;


use App\Service\GeneratePdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Service\Excel as ServiceExcel;

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
                'p.product_type',
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

        $sql = DB::table('VW_DUE_REPORT');

        if ($productId) {
            $sql->where('product_id', $productId);
        }

        if ($fromMonth) {
            $start = date('Y-m-01', strtotime($fromMonth . '-01'));
            $sql->where('bill_month', '>=', $start);
        }

        if ($toMonth) {
            $end = date('Y-m-t', strtotime($toMonth . '-01'));
            $sql->where('bill_month', '<=', $end);
        }

        $rows = $sql->orderBy('product_id', 'ASC')->get();

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

        $sql = DB::table('VW_DUE_REPORT');

        if ($productId) {
            $sql->where('product_id', $productId);
        }

        if ($fromMonth) {
            $start = date('Y-m-01', strtotime($fromMonth . '-01'));
            $sql->where('bill_month', '>=', $start);
        }

        if ($toMonth) {
            $end = date('Y-m-t', strtotime($toMonth . '-01'));
            $sql->where('bill_month', '<=', $end);
        }

        $rows = $sql->orderBy('product_id', 'ASC')->get();

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

    public function service_due_report_csv(Request $request)
    {
        $productId = $request->input('product_id');
        $fromMonth = $request->input('from_month');
        $toMonth = $request->input('to_month');

        $sql = DB::table('VW_DUE_REPORT');

        if ($productId) {
            $sql->where('product_id', $productId);
        }

        if ($fromMonth) {
            $start = date('Y-m-01', strtotime($fromMonth . '-01'));
            $sql->where('bill_month', '>=', $start);
        }

        if ($toMonth) {
            $end = date('Y-m-t', strtotime($toMonth . '-01'));
            $sql->where('bill_month', '<=', $end);
        }

        $rows = $sql->orderBy('product_id', 'ASC')->get();

        // Generate CSV content
        $filename = 'service-due-report-' . date('Y-m-d-H-i-s') . '.csv';

        // Start building CSV content
        $csv_content = [];

        // Title row
        $csv_content[] = ['SERVICE DUE REPORT '];

        // Filter information
        $filter_text = 'Generated: ' . date('F d, Y g:i A');
        if (!empty($request->input('product_id'))) {
            $filter_text .= ' | Product ID: ' . $request->input('product_id');
        }
        if (!empty($request->input('from_month'))) {
            $filter_text .= ' | From: ' . $request->input('from_month');
        }
        if (!empty($request->input('to_month'))) {
            $filter_text .= ' | To: ' . $request->input('to_month');
        }
        $csv_content[] = [$filter_text];
        $csv_content[] = []; // Empty row

        // Headers
        $csv_content[] = [
            'SL',
            'Apartment',
            'Customer',
            'Prev. Dues',
            'Monthly Bills Details',
            'Monthly Total',
            'Total Due'
        ];

        // Process data
        $total_opening = 0;
        $total_monthly = 0;
        $grand_total = 0;

        foreach ($rows as $index => $row) {
            $total_opening += $row->opening ?? 0;
            $total_monthly += $row->total_unpaid_amount ?? 0;
            $grand_total += ($row->opening ?? 0) + ($row->total_unpaid_amount ?? 0);

            $month_details = ($row->unpaid_months_with_amounts == ' - ') ? 'CLEARED' : $row->unpaid_months_with_amounts;
            $month_total = $row->total_unpaid_amount;

            $csv_content[] = [
                $index + 1,
                $row->product_id . ' (' . $row->product_type . ')',
                $row->customer_name . ' (' . $row->customer_id . ')',
                number_format($row->opening ?? 0, 0, '.', ','),
                $month_details,
                number_format($month_total, 0, '.', ','),
                number_format(abs(($row->opening ?? 0) + ($row->total_unpaid_amount ?? 0)), 0, '.', ',')
            ];
        }

        // Add total row
        $csv_content[] = []; // Empty row
        $csv_content[] = [
            '',
            '',
            'GRAND TOTAL',
            number_format($total_opening, 0, '.', ','),
            'All Monthly Bills',
            number_format($total_monthly, 0, '.', ','),
            number_format(abs($grand_total), 0, '.', ',')
        ];

        // Convert array to CSV string
        $output = '';
        foreach ($csv_content as $row) {
            $output .= '"' . implode('","', $row) . '"' . "\n";
        }

        return ServiceExcel::export($output, $filename);
    }

    // app/Http/Controllers/InvoiceController.php

    public function service_due_summary_report_csv(Request $request)
    {
        $productId = $request->input('product_id');
        $fromMonth = $request->input('from_month');
        $toMonth = $request->input('to_month');

        $sql = DB::table('VW_DUE_REPORT');

        if ($productId) {
            $sql->where('product_id', $productId);
        }

        if ($fromMonth) {
            $start = date('Y-m-01', strtotime($fromMonth . '-01'));
            $sql->where('bill_month', '>=', $start);
        }

        if ($toMonth) {
            $end = date('Y-m-t', strtotime($toMonth . '-01'));
            $sql->where('bill_month', '<=', $end);
        }

        $rows = $sql->orderBy('product_id', 'ASC')->get();

        // Generate CSV content
        $filename = 'service-due-report-' . date('Y-m-d-H-i-s') . '.csv';

        // Start building CSV content
        $csv_content = [];

        // Title row
        $csv_content[] = ['SERVICE DUE REPORT '];

        // Filter information
        $filter_text = 'Generated: ' . date('F d, Y g:i A');
        if (!empty($request->input('product_id'))) {
            $filter_text .= ' | Product ID: ' . $request->input('product_id');
        }
        if (!empty($request->input('from_month'))) {
            $filter_text .= ' | From: ' . $request->input('from_month');
        }
        if (!empty($request->input('to_month'))) {
            $filter_text .= ' | To: ' . $request->input('to_month');
        }
        $csv_content[] = [$filter_text];
        $csv_content[] = []; // Empty row

        // Headers
        $csv_content[] = [
            'SL',
            'Apartment',
            'Customer',
            'Prev. Dues',
            'Monthly Dues',
            'Total Due'
        ];

        // Process data
        $total_opening = 0;
        $total_monthly = 0;
        $grand_total = 0;

        foreach ($rows as $index => $row) {
            $total_opening += $row->opening ?? 0;
            $total_monthly += $row->total_unpaid_amount ?? 0;
            $grand_total += ($row->opening ?? 0) + ($row->total_unpaid_amount ?? 0);


            $month_total = $row->total_unpaid_amount;

            $csv_content[] = [
                $index + 1,
                $row->product_id . ' (' . $row->product_type . ')',
                $row->customer_name . ' (' . $row->customer_id . ')',
                number_format($row->opening ?? 0, 0, '.', ','),
                number_format($month_total, 0, '.', ','),
                number_format(abs(($row->opening ?? 0) + ($row->total_unpaid_amount ?? 0)), 0, '.', ',')
            ];
        }

        // Add total row
        $csv_content[] = []; // Empty row
        $csv_content[] = [
            '',
            '',
            'GRAND TOTAL',
            number_format($total_opening, 0, '.', ','),
            number_format($total_monthly, 0, '.', ','),
            number_format(abs($grand_total), 0, '.', ',')
        ];

        // Convert array to CSV string
        $output = '';
        foreach ($csv_content as $row) {
            $output .= '"' . implode('","', $row) . '"' . "\n";
        }

        return ServiceExcel::export($output, $filename);
    }

    // app/Http/Controllers/InvoiceController.php

    public function service_payment_report_excel(Request $request)
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
                'p.product_type',
                'p.customer_id',
                'p.customer_name',
                'p.service_name',
                'p.auto_receipt_no',
                'p.paid_amount',
                'p.payment_date',
            ]);


        // Generate Excel content
        $filename = 'service-payment-report-' . date('Y-m-d-H-i-s') . '.csv';

        // Start building Excel content
        $excel_content = [];

        // Title row
        $excel_content[] = ['SERVICE PAYMENT REPORT '];

        // Filter information
        $filter_text = 'Generated: ' . date('F d, Y g:i A');
        if (!empty($request->input('product_id'))) {
            $filter_text .= ' | Product ID: ' . $request->input('product_id');
        }
        if (!empty($request->input('from_month'))) {
            $filter_text .= ' | From: ' . $request->input('from_month');
        }
        if (!empty($request->input('to_month'))) {
            $filter_text .= ' | To: ' . $request->input('to_month');
        }
        if (!empty($request->input('service_type'))) {
            $filter_text .= ' | Service Type: ' . $request->input('service_type');
        }
        $excel_content[] = [$filter_text];
        $excel_content[] = []; // Empty row

        // Headers
        $csv_content[] = [
            'SL',
            'Bill Month',
            'Product ID',
            'Product Type',
            'Customer ID',
            'Customer Name',
            'Service Name',
            'Auto Receipt No',
            'Paid Amount',
            'Payment Date',
        ];
    $total_opening = 0;
        // Data rows
        foreach ($rows as $key => $row) {
            $total_opening += $row->paid_amount;
            $csv_content[] = [
                $key+1,
                date('M Y', strtotime($row->bill_month)),
                $row->product_id,
                $row->product_type,
                $row->customer_id,
                $row->customer_name,
                $row->service_name,
                $row->auto_receipt_no,
                $row->paid_amount,
                date('d M Y', strtotime($row->payment_date)),
            ];
        }

        // Add total row
        $csv_content[] = []; // Empty row
        $csv_content[] = [
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            'TOTAL',
            number_format($total_opening, 0, '.', ','),
        ];

        // Convert array to CSV string
        $output = '';
        foreach ($csv_content as $row) {
            $output .= '"' . implode('","', $row) . '"' . "\n";
        }

        return ServiceExcel::export($output, $filename);
    }
}