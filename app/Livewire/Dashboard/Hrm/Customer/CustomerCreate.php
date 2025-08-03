<?php

namespace App\Livewire\Dashboard\Hrm\Customer;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

class CustomerCreate extends Component
{
    use WithFileUploads;

    public $state = [];
    public $customer_types,$customer_type, $sale;
    public $editForm = '';

    public function save()
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
            $this->state['photo'] = $this->state['photo']->store('upload/customer');
        }

        DB::table('INV_CUSTOMER_INFO')->insert($this->state);

        session()->flash('status', 'New customers create successfully. You can find it at customers list');

        $this->reset();
        // $this->resetValidation();
        $this->state['photo'] = '';
        $this->state['customer_type'] = '';
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
        return view('livewire.dashboard.hrm.customer.customer-create')->title('Create customer');
    }
}