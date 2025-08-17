<?php

namespace App\Livewire\Dashboard\Admin\ReceivePayment;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceDueReportSummary extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $product_id = '';
    public $from_month = '';
    public $to_month = '';
    public $pagination = 10;

    public function mount(): void
    {
        $this->from_month = '';
        $this->to_month = '';
    }

    #[Computed]
    public function dues()
    {
        $sql = "
            SELECT
                r.APARTMENT_ID AS PRODUCT_ID,
                m.CUSTOMER_ID,
                m.CUSTOMER_NAME,
                m.PRODUCT_TYPE,
                LISTAGG(
                    TO_CHAR(b.bill_month, 'MON, YYYY') || ' - ' || b.tot_bill_amt,
                    ' | '
                ) WITHIN GROUP (ORDER BY b.bill_month) AS unpaid_months_with_amounts,
                SUM(b.tot_bill_amt) AS total_unpaid_amount,
                NVL(r.paid_amount, 0) AS paid_amount
            FROM SRV_PAYMENT_RECEIPT r
            -- master info (one row per apartment)
            LEFT JOIN (
                SELECT PRODUCT_ID, CUSTOMER_ID, CUSTOMER_NAME, PRODUCT_TYPE
                FROM VW_SRV_APARTMENT_BILL_INFO
                GROUP BY PRODUCT_ID, CUSTOMER_ID, CUSTOMER_NAME, PRODUCT_TYPE
            ) m ON m.PRODUCT_ID = r.APARTMENT_ID
            -- unpaid bills only
            LEFT JOIN VW_SRV_APARTMENT_BILL_INFO b
                ON b.PRODUCT_ID = r.APARTMENT_ID
                AND b.STATUS = 'UNPAID'
            WHERE r.STATUS = 'OP'
        ";

        $bindings = [];

        if ($this->product_id) {
            $sql .= " AND p.PRODUCT_ID = :product_id";
            $bindings['product_id'] = $this->product_id;
        }

        if ($this->from_month) {
            $start = date('Y-m-01', strtotime($this->from_month . '-01'));
            $sql .= " AND (b.bill_month IS NULL OR TRUNC(b.bill_month, 'MM') >= TO_DATE(:start_month, 'YYYY-MM-DD'))";
            $bindings['start_month'] = $start;
        }

        if ($this->to_month) {
            $end = date('Y-m-t', strtotime($this->to_month . '-01'));
            $sql .= " AND (b.bill_month IS NULL OR TRUNC(b.bill_month, 'MM') <= TO_DATE(:end_month, 'YYYY-MM-DD'))";
            $bindings['end_month'] = $end;
        }

        $sql .= "
            GROUP BY
                r.APARTMENT_ID,
                m.CUSTOMER_ID,
                m.CUSTOMER_NAME,
                m.PRODUCT_TYPE,
                r.paid_amount
            HAVING
                COUNT(b.PRODUCT_ID) > 0
                OR NVL(r.paid_amount, 0) <> 0
            ORDER BY r.APARTMENT_ID
        ";

        $bills = DB::select($sql, $bindings);
        return $bills;
    }

    public function search_report(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['product_id', 'from_month', 'to_month']);
        $this->resetPage();
    }

    public function render()
    {
        $apartments = DB::table('SRV_APARTMENT_INFO')
            ->orderBy('product_id', 'ASC')
            ->get();

        return view('livewire.dashboard.admin.receive-payment.service-due-report-summary', [
            'products' => $apartments,
        ]);
    }
}
