<?php

namespace App\Livewire\Dashboard\Hrm\Supplier;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

class SupplierCreate extends Component
{
    use WithFileUploads;

    public $state = [];
    public $supplier_categories,$supplier_types,$p_category,$p_type;
    public $editForm = '';

    public function save()
    {
        $this->state['p_type'] = $this->p_type;
        $this->state['p_catagory'] = $this->p_category;

        Validator::make($this->state, [
            'p_name' => 'required',
            'phone' => 'required',
            'email' => 'email|nullable',
            'status' => 'required|numeric',
            'p_type' => 'required|numeric',
            'p_catagory' => 'required|numeric',

        ])->validate();


        if (@$this->state['photo']) {
            $this->state['photo'] = $this->state['photo']->store('upload/supplier');
        }

        DB::table('INV_SUPPLIER_INFO')->insert($this->state);

        session()->flash('status', 'New Supplier create successfully. You can find it at supplier list');

        $this->reset();
        $this->state['photo'] = '';
        $this->state['p_type'] = '';
        $this->state['p_catagory'] = '';
    }

    public function category_type()
    {
        $this->supplier_categories = DB::table('INV_SUPPLIER_CATEGORY')
            ->orderBy('supplier_cat_code', 'DESC')
            ->get();

        $this->supplier_types = DB::table('INV_SUPPLIER_TYPE')
            ->orderBy('supplier_type_code', 'DESC')
            ->get();
    }


    public function render()
    {
        $this->category_type();
        return view('livewire.dashboard.hrm.supplier.supplier-create')->title('Create customer');
    }
}