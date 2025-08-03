<?php

namespace App\Livewire\Dashboard\Hrm\Employee;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Employee extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';


    public $search;
    public $pagination = 10;


    #[Computed]
    public function resultEmployee()
    {
        $employees = DB::table('HRM_EMPLOYEE_INFO');


        if ($this->search) {
            $employees
                ->where(DB::raw('lower(emp_name)'), 'like', '%' . strtolower($this->search) . '%')
                ;
        }

        return $employees->orderBy('employee_id', 'DESC')
            ->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.dashboard.hrm.employee.employee')->title('Employee');;
    }
}
