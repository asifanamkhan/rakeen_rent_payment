<?php

namespace App\Livewire\Dashboard\Admin\ReceivePayment;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class ServicePaymentReport extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $product_id = '';
    public $from_month = '';
    public $to_month = '';
    public $service_type = '';
    public $pagination = 10;

    public function mount(): void
    {
        $this->from_month = '';
        $this->to_month = '';
        $this->service_type = '';
    }

    #[Computed]
    public function payments()
    {
        $query = DB::table('VW_SRV_PAYMENT_INFO as p')
            ->where('p.status', 1);

        if ($this->product_id) {
            $query->where('p.product_id', $this->product_id);
        }

        if ($this->service_type) {
            // service_name values seen elsewhere: SERVICE, ELECTRICITY, WATER
            $query->where('p.service_name', $this->service_type);
        }

        if ($this->from_month) {
            $start = date('Y-m-01', strtotime($this->from_month . '-01'));
            $query->where('p.bill_month', '>=', $start);
        }

        if ($this->to_month) {
            $end = date('Y-m-t', strtotime($this->to_month . '-01'));
            $query->where('p.bill_month', '<=', $end);
        }

        return $query->orderBy('p.bill_month', 'DESC')
            ->orderBy('p.product_id', 'ASC')
            ->paginate($this->pagination);
    }

    public function search_report(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['product_id', 'from_month', 'to_month', 'service_type']);
        $this->resetPage();
    }

    public function render()
    {
        $apartments = DB::table('SRV_APARTMENT_INFO')
            ->orderBy('product_id', 'ASC')
            ->get();

        $paymentTypes = [
            ['id' => 1, 'name' => 'SERVICE'],
            ['id' => 2, 'name' => 'ELECTRICITY'],
            ['id' => 3, 'name' => 'WATER'],
        ];

        return view('livewire.dashboard.admin.receive-payment.service-payment-report', [
            'products' => $apartments,
            'paymentTypes' => $paymentTypes,
        ]);
    }
}
