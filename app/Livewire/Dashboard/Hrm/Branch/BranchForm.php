<?php

namespace App\Livewire\Dashboard\Hrm\Branch;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;


class BranchForm extends Component
{
    public $branch_name;
    public $branch;
    public $editForm = false;
    
    public function render()
    {
        return view('livewire.dashboard.hrm.branch.branch-form');
    }

    public function store(){

        $validate = $this->validate([
            'branch_name' => 'required',
        ]);

        DB::table('INV_BRANCH_INFO')->insert([
            'branch_name' => $this->branch_name,
            'user_id' => Auth::user()->id
        ]);

        $this->dispatch('refresh-branches');
        session()->flash('status', 'Branch create successfully');

        $this->reset();
    }

    #[On('create-branch-modal')]
    public function refresh(){
        $this->reset();
    }

    #[On('branch-edit-modal')]
    public function edit($id){

        $this->editForm = true;
        $this->branch = DB::table('INV_BRANCH_INFO')
            ->where('branch_id', $id)->first();

        $this->branch_name = $this->branch->branch_name;

    }

    public function update() {
        $validate = $this->validate([
            'branch_name' => 'required',
        ]);

        DB::table('INV_BRANCH_INFO')
            ->where('branch_id', $this->branch->branch_id)
            ->update($validate);

        $this->dispatch('refresh-branches');
        session()->flash('status', 'Branch updated successfully');

    }
}