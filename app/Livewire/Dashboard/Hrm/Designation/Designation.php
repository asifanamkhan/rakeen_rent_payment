<?php

namespace App\Livewire\Dashboard\Hrm\Designation;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Designation extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $create_title = 'Create new designation';
    public $update_title = 'Update designation';
    public $create_event = 'designation-create';
    public $update_event = 'designation-update';
    public $search;
    public $pagination = 10;


    #[Computed]
    public function resultDesignation()
    {
        $designations = DB::table('HRM_DESIGNATION_INFO');


        if ($this->search) {
            $designations
                ->where(DB::raw('lower(desig_name)'), 'like', '%' . strtolower($this->search) . '%')
                ;
        }

        return $designations->orderBy('desig_id', 'DESC')
            ->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[On('refresh-designation')]
    public function refreshDesignation(){
        $this->resultDesignation();
    }
    public function render()
    {
        return view('livewire.dashboard.hrm.designation.designation')->title('Designation');;
    }
}