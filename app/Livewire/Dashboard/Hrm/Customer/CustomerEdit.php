<?php

namespace App\Livewire\Dashboard\Hrm\Customer;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;

class CustomerEdit extends Component
{
    use WithFileUploads;

    public $customer_id;
    public $editForm = true;
    public $customer_types, $customer_type;
    public $state = [];

    public function update()
    {
        $this->state['customer_type'] = $this->customer_type;

        Validator::make($this->state, [
            'customer_name' => 'required',
            'phone_no' => 'required',
            'email' => 'email|nullable',
            'status' => 'required|numeric',
            'customer_type' => 'required|numeric',

        ])->validate();

        if (@$this->state['photo']) {
            $this->state['photo'] = $this->state['photo']->store('upload');
        } else {
            $this->state['photo'] = $this->state['old_photo'];
        }

        unset($this->state['old_photo']);


        DB::table('INV_CUSTOMER_INFO')
            ->where('customer_id', $this->customer_id)
            ->update($this->state);

        session()->flash('status', 'customer information updated successfully.');

        $this->state['old_photo'] = $this->state['photo'];
        $this->state['photo'] = '';
        $this->resetValidation();
    }

    public function mount($customer_id)
    {
        $this->customer_id = $customer_id;
        $customer = (array)DB::table('INV_CUSTOMER_INFO')
            ->where('customer_id', $this->customer_id)
            ->first();

        if ($customer['birth_date']) {
            $customer['birth_date'] = Carbon::parse($customer['birth_date'])->toDateString();
        }

        $this->customer_type = $customer['customer_type'];

        $this->state = $customer;
        $this->state['old_photo'] = $customer['photo'];
        $this->state['photo'] = '';
    }


    public function category_type()
    {
        $this->customer_types = DB::table('INV_CUSTOMER_TYPE')
            ->orderBy('customer_type_code', 'DESC')
            ->get();
    }

    public function render()
    {
        $this->category_type();
        return view('livewire.dashboard.hrm.customer.customer-edit')->title('Customer edit');
    }
}