<?php

namespace App\Http\Controllers;

use App\Service\CustomTcPDFHF;
use App\Service\GeneratePdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
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
}
