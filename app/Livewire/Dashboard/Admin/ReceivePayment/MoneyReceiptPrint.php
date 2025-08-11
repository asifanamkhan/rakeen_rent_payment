<?php

namespace App\Livewire\Dashboard\Admin\ReceivePayment;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class MoneyReceiptPrint extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $product_id = '';
    public $bill_month = '';
    public $payment_type = '';
    public $pagination = 10;

    public function mount()
    {
        $this->bill_month = '';
    }

    #[Computed]
    public function resultBills()
    {
        $bills = DB::table('VW_SRV_PAYMENT_INFO')
                ->where('status', 1);

        if ($this->product_id) {
            $bills->where('product_id', $this->product_id);
        }

        if ($this->bill_month) {
            $bills->where('bill_month', 'like', $this->bill_month . '%');
        }

        if ($this->payment_type) {
            $bills->where('service_name', $this->payment_type);
        }

        if ($this->search) {
            $bills->where(function($query) {
                $query->orWhere(DB::raw('lower(product_id)'), 'like', '%' . strtolower($this->search) . '%')
                      ->orWhere(DB::raw('lower(customer_name)'), 'like', '%' . strtolower($this->search) . '%')
                      ->orWhere(DB::raw('lower(auto_bill_no)'), 'like', '%' . strtolower($this->search) . '%');
            });
        }

        return $bills->orderBy('bill_month', 'DESC')
                    ->orderBy('product_id', 'ASC')
                    ->paginate($this->pagination);
    }

    public function search_payment(){
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['product_id', 'bill_month', 'payment_type']);
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
            ['id' => 3, 'name' => 'WATER']
        ];

        return view('livewire.dashboard.admin.receive-payment.money-receipt-print', [
            'products' => $apartments,
            'paymentTypes' => $paymentTypes
        ]);
    }
}
