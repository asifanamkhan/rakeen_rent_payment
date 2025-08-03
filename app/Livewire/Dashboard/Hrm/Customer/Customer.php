<?php

namespace App\Livewire\Dashboard\Hrm\Customer;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Customer extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $pagination = 10;

    #[Computed]
    public function resultCustomer()
    {
        $customers = DB::table('VW_SRV_CUSTOMER_INFO');

        if ($this->search) {
            $customers
                ->orwhere(DB::raw('lower(customer_name)'), 'like', '%' . strtolower($this->search) . '%')
                ->orwhere(DB::raw('lower(customer_id)'), 'like', '%' . strtolower($this->search) . '%')
                ->orwhere(DB::raw('lower(cell_no)'), 'like', '%' . strtolower($this->search) . '%')
                ->orwhere(DB::raw('lower(email_id)'), 'like', '%' . strtolower($this->search) . '%')
                ->orwhere(DB::raw('lower(booking_id)'), 'like', '%' . strtolower($this->search) . '%')
                ->orwhere(DB::raw('lower(PRODUCT_ID)'), 'like', '%' . strtolower($this->search) . '%')
                ->orwhere(DB::raw('lower(PRODUCT_TYPE)'), 'like', '%' . strtolower($this->search) . '%')
                ->orwhere(DB::raw('lower(HANDOVER_DATE)'), 'like', '%' . strtolower($this->search) . '%')
                ;
        }

        return $customers->orderBy('customer_id', 'DESC')
            ->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function render()
    {
        return view('livewire.dashboard.hrm.customer.customer')->title('Customer');
    }
}
