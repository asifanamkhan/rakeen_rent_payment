<?php

namespace App\Livewire\Dashboard\Hrm\Supplier;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;

class SupplierEdit extends Component
{
    use WithFileUploads;

    public $supplier_id;
    public $editForm = true;
    public $supplier_categories, $supplier_types, $p_category, $p_type;
    public $state = [];

    public function update()
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
            $this->state['photo'] = $this->state['photo']->store('upload');
        } else {
            $this->state['photo'] = $this->state['old_photo'];
        }

        unset($this->state['old_photo']);


        DB::table('INV_SUPPLIER_INFO')
            ->where('p_code', $this->supplier_id)
            ->update($this->state);



        session()->flash('status', 'Supplier information updated successfully.');

        $this->state['old_photo'] = $this->state['photo'];
        $this->state['photo'] = '';
    }


    public function mount($supplier_id)
    {
        $this->supplier_id = $supplier_id;
        $supplier = (array)DB::table('INV_SUPPLIER_INFO')
            ->where('p_code', $this->supplier_id)
            ->first();

        $this->p_category = $supplier['p_catagory'];
        $this->p_type = $supplier['p_type'];

        $this->state = $supplier;
        $this->state['old_photo'] = $supplier['photo'];
        $this->state['photo'] = '';
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
        return view('livewire.dashboard.hrm.supplier.supplier-edit')->title('Edit customer');
    }
}
