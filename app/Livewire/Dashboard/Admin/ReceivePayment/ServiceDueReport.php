<?php

namespace App\Livewire\Dashboard\Admin\ReceivePayment;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceDueReport extends Component
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

        if ($this->product_id) {
            $sql .= " AND b.PRODUCT_ID = :product_id";
            $bindings['product_id'] = $this->product_id;
        }

        if ($this->from_month) {
            $start = date('Y-m-01', strtotime($this->from_month . '-01'));
            $sql .= " AND TRUNC(b.bill_month, 'MM') >= TO_DATE(:start_month, 'YYYY-MM-DD')";
            $bindings['start_month'] = $start;
        }

        if ($this->to_month) {
            $end = date('Y-m-t', strtotime($this->to_month . '-01'));
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

        $bills = DB::select($sql, $bindings);
        // dd($bills);
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

        return view('livewire.dashboard.admin.receive-payment.service-due-report', [
            'products' => $apartments,
        ]);
    }
}
