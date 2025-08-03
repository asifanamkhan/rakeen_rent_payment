<?php

namespace App\Livewire\Dashboard\Hrm\Department;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class DepartmentForm extends Component
{
    public $dept_name;
    public $department;
    public $editForm = false;

    public function store()
    {

        $validate = $this->validate(
            [
                'dept_name' => 'required',
            ],
            [
                'dept_name' => 'The department name field is required.'
            ]
        );

        DB::table('HRM_DEPARTMENT_INFO')->insert(
            [
                'dept_name' => $this->dept_name,
                'user_name' => Auth::user()->name
            ]
        );

        $this->dispatch('refresh-departments');
        session()->flash('status', 'Department create successfully');

        $this->reset();
    }

    #[On('create-department-modal')]
    public function refresh()
    {
        $this->reset();
    }

    #[On('department-edit-modal')]
    public function edit($id)
    {

        $this->editForm = true;
        $this->department = DB::table('HRM_DEPARTMENT_INFO')
            ->where('dept_id', $id)->first();

        $this->dept_name = $this->department->dept_name;
    }

    public function update()
    {
        $validate = $this->validate([
            'dept_name' => 'required',
        ]);

        DB::table('HRM_DEPARTMENT_INFO')
            ->where('dept_id', $this->department->dept_id)
            ->update($validate);

        $this->dispatch('refresh-departments');
        session()->flash('status', 'Department updated successfully');
    }
    public function render()
    {
        return view('livewire.dashboard.hrm.department.department-form');
    }
}
