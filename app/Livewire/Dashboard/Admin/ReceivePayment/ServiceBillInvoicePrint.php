<?php

namespace App\Livewire\Dashboard\Admin\ReceivePayment;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class ServiceBillInvoicePrint extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $product_id = '';
    public $bill_month = '';
    public $bill_type = '';
    public $pagination = 10;

    public function mount()
    {
        $this->bill_month = '';
    }

    #[Computed]
    public function resultBills()
    {
        $bills = DB::table('VW_SRV_APARTMENT_BILL_INFO')
            ->where('status', 'UNPAID');

        if ($this->product_id) {
            $bills->where('product_id', $this->product_id);
        }

        if ($this->bill_month) {
            $bills->where('bill_month', 'like', $this->bill_month . '%');
        }


        if ($this->search) {
            $bills->where(function ($query) {
                $query->orWhere(DB::raw('lower(product_id)'), 'like', '%' . strtolower($this->search) . '%')
                    ->orWhere(DB::raw('lower(customer_name)'), 'like', '%' . strtolower($this->search) . '%')
                    ->orWhere(DB::raw('lower(auto_bill_no)'), 'like', '%' . strtolower($this->search) . '%');
            });
        }

        return $bills->orderBy('bill_month', 'DESC')
            ->orderBy('product_id', 'ASC')
            ->paginate($this->pagination);
    }

    public function search_bills()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['product_id', 'bill_month', 'bill_type']);
        $this->resetPage();
    }

    public function render()
    {
        $apartments = DB::table('SRV_APARTMENT_INFO')
            ->orderBy('product_id', 'ASC')
            ->get();

        return view('livewire.dashboard.admin.receive-payment.service-bill-invoice-print', [
            'products' => $apartments
        ]);
    }
}
