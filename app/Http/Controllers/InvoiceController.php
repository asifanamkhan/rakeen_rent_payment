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
            ->leftJoin('SALES_CUSTOMER_INFO as c', function ($join) {
                $join->on('b.cus_id', '=', 'c.cus_id');
            })
            ->first([
                'b.*',
                'c.email_id',
                'c.cell_no',
                'c.add_present',
            ]);
        $data = [
            'bill' => $bill
        ];
        // dd($bill);
        $html = view()->make('livewire.dashboard.reports.invoice.service-bill-invoice', $data)->render();
        $pdf_data = [
            'html' => $html,
            'filename' => 'customer-info.pdf',
        ];
        GeneratePdf::generate($pdf_data);

    }

}
