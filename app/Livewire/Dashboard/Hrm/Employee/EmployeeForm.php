<?php

namespace App\Livewire\Dashboard\Hrm\Employee;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmployeeForm extends Component
{
    use WithFileUploads;

    public $departments, $designations;
    public $state = [];
    public $editForm = [];

    public function mount()
    {
        $this->departmentAll();
        $this->designationtAll();
    }

    public function departmentAll()
    {
        $this->departments = DB::table('HRM_DEPARTMENT_INFO')
            ->get();
    }
    public function designationtAll()
    {
        $this->designations = DB::table('HRM_DESIGNATION_INFO')
            ->get();
    }

    public function render()
    {

        return view('livewire.dashboard.hrm.employee.employee-form');
    }
}