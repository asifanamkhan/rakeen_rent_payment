<?php

namespace App\Livewire\Dashboard\Hrm\Designation;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class DesignationForm extends Component
{
    public $desig_name;
    public $designation;
    public $editForm = false;

    public function store()
    {

        $validate = $this->validate(
            [
                'desig_name' => 'required',
            ],
            [
                'desig_name' => 'The designation name field is required.'
            ]
        );

        DB::table('HRM_DESIGNATION_INFO')->insert(
            [
                'desig_name' => $this->desig_name,
                'user_name' => Auth::user()->name
            ]
        );

        $this->dispatch('refresh-designation');
        session()->flash('status', 'Designation create successfully');

        $this->reset();
    }

    #[On('create-designation-modal')]
    public function refresh()
    {
        $this->reset();
    }

    #[On('designation-edit-modal')]
    public function edit($id)
    {
        $this->editForm = true;
        $this->designation = DB::table('HRM_DESIGNATION_INFO')
            ->where('desig_id', $id)->first();

        $this->desig_name = $this->designation->desig_name;
    }

    public function update()
    {
        $validate = $this->validate([
            'desig_name' => 'required',
        ]);

        DB::table('HRM_DESIGNATION_INFO')
            ->where('desig_id', $this->designation->desig_id)
            ->update($validate);

        $this->dispatch('refresh-designation');
        session()->flash('status', 'Designation updated successfully');
    }
    public function render()
    {
        return view('livewire.dashboard.hrm.designation.designation-form');
    }
}
