<?php

namespace App\Livewire\Dashboard\Admin\ReceivePayment;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class ReceivePayment extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';


    public $search, $status;
    public $pagination = 10;


    #[Computed]
    public function resultPayments()
    {
        $apartments = DB::table('VW_SRV_APARTMENT_BILL_INFO');


        if ($this->search) {
            $apartments
                ->orwhere(DB::raw('lower(BILL_ID)'), 'like', '%' . strtolower($this->search) . '%')
                ->orwhere(DB::raw('lower(product_id)'), 'like', '%' . strtolower($this->search) . '%')
                ->orwhere(DB::raw('lower(BILL_MONTH)'), 'like', '%' . strtolower($this->search) . '%')
                ->orwhere(DB::raw('lower(CUSTOMER_NAME)'), 'like', '%' . strtolower($this->search) . '%')
                ->orwhere(DB::raw('lower(AUTO_BILL_NO)'), 'like', '%' . strtolower($this->search) . '%');

        }

        if($this->status){
            $apartments->where('status', $this->status);
        }

        return $apartments
            ->orderBy('BILL_MONTH', 'ASC')
            ->orderBy('PRODUCT_ID', 'ASC')
            ->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        return view('livewire.dashboard.admin.receive-payment.receive-payment');
    }
}