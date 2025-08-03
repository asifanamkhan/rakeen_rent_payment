<?php

namespace App\Livewire\Dashboard\Hrm\Department;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Department extends Component
{
    use WithPagination;

    public $create_title = 'Create new department';
    public $update_title = 'Update department';
    public $create_event = 'department-create';
    public $update_event = 'department-update';
    public $search;
    public $pagination = 10;


    #[Computed]
    public function resultDepartment()
    {
        $departments = DB::table('HRM_DEPARTMENT_INFO');


        if ($this->search) {
            $departments
                ->where(DB::raw('lower(dept_name)'), 'like', '%' . strtolower($this->search) . '%')
                ;
        }

        return $departments->orderBy('dept_id', 'DESC')
            ->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[On('refresh-departments')]
    public function refreshDepartment(){
        $this->resultDepartment();
    }
    public function render()
    {
        return view('livewire.dashboard.hrm.department.department')->title('Department');;
    }
}
