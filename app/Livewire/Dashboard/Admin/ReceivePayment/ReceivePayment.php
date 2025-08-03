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


    public $search;
    public $pagination = 10;


    #[Computed]
    public function resultPayments()
    {
        $apartments = DB::table('SRV_APARTMENT_BILL');


        if ($this->search) {
            $apartments
                ->orwhere(DB::raw('lower(BILL_ID)'), 'like', '%' . strtolower($this->search) . '%')
                ->orwhere(DB::raw('lower(APARTMENT_ID)'), 'like', '%' . strtolower($this->search) . '%')
                ->orwhere(DB::raw('lower(BILL_MONTH)'), 'like', '%' . strtolower($this->search) . '%')
                ->orwhere(DB::raw('lower(AUTO_BILL_NO)'), 'like', '%' . strtolower($this->search) . '%')
                ->orwhere(DB::raw('lower(STATUS)'), 'like', '%' . strtolower($this->search) . '%');

        }

        return $apartments
            ->orderBy('BILL_MONTH', 'ASC')
            ->orderBy('APARTMENT_ID', 'ASC')
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
