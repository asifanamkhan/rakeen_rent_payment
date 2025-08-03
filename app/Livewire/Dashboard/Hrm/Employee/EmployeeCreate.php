<?php

namespace App\Livewire\Dashboard\Hrm\Employee;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

class EmployeeCreate extends Component
{
    use WithFileUploads;

    public $state = [];
    public $emp_types,$p_category,$p_type;
    public $editForm = '';

    public function save()
    {

        Validator::make($this->state, [
            'p_name' => 'required',
            'phone' => 'required',
            'email' => 'email|nullable',
            'status' => 'required|numeric',
            'p_type' => 'required|numeric',
            'p_catagory' => 'required|numeric',

        ])->validate();


        if (@$this->state['photo']) {
            $this->state['photo'] = $this->state['photo']->store('upload/emp');
        }

        DB::table('HRM_EMPLOYEE_INFO')->insert($this->state);

        session()->flash('status', 'New Employee create successfully. You can find it at emp list');

        $this->reset();
        $this->state['photo'] = '';
    }

    public function mount(){
        
    }

    public function render()
    {
        return view('livewire.dashboard.hrm.employee.employee-create');
    }
}