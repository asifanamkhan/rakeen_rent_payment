<?php

namespace App\Livewire\Dashboard\Admin\Apartment;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class ApartmentInfo extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';


    public $search;
    public $pagination = 10;


    #[Computed]
    public function resultApartments()
    {
        $apartments = DB::table('VW_SRV_APARTMENT_INFO');


        if ($this->search) {
            $apartments
                ->orwhere(DB::raw('lower(PROJECT_NAME)'), 'like', '%' . strtolower($this->search) . '%')
                ->orwhere(DB::raw('lower(TOWER_ID)'), 'like', '%' . strtolower($this->search) . '%')
                ->orwhere(DB::raw('lower(PRODUCT_ID)'), 'like', '%' . strtolower($this->search) . '%')
                ->orwhere(DB::raw('lower(PRODUCT_TYPE)'), 'like', '%' . strtolower($this->search) . '%')
                ->orwhere(DB::raw('lower(CUSTOMER_ID)'), 'like', '%' . strtolower($this->search) . '%')
                ->orwhere(DB::raw('lower(BOOKING_ID)'), 'like', '%' . strtolower($this->search) . '%')
                ->orwhere(DB::raw('lower(HANDOVER_DATE)'), 'like', '%' . strtolower($this->search) . '%');

        }

        return $apartments->orderBy('HANDOVER_DATE', 'ASC')
            ->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        return view('livewire.dashboard.admin.apartment.apartment-info');
    }
}
