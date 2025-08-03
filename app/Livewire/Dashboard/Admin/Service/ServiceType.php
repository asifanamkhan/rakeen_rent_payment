<?php

namespace App\Livewire\Dashboard\Admin\Service;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class ServiceType extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';


    public $search;
    public $pagination = 10;


    #[Computed]
    public function resultServiceTypes()
    {
        $servie_types = DB::table('SRV_SERVICE_TYPE');


        if ($this->search) {
            $servie_types
                ->orwhere(DB::raw('lower(SERVICE_NAME)'), 'like', '%' . strtolower($this->search) . '%')
                ->orwhere(DB::raw('lower(DESCRIPTION)'), 'like', '%' . strtolower($this->search) . '%')
                ->orwhere(DB::raw('lower(STATUS)'), 'like', '%' . strtolower($this->search) . '%');

        }

        return $servie_types->orderBy('SERVICE_CODE', 'ASC')
            ->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        return view('livewire.dashboard.admin.service.service-type');
    }
}
