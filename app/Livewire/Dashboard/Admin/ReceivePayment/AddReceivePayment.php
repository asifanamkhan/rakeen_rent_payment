<?php

namespace App\Livewire\Dashboard\Admin\ReceivePayment;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;

class AddReceivePayment extends Component
{
    public $state = [];
    public $products, $payment_methods;

    public function mount()
    {
        $this->productsAll();
        $this->payMode();
        $this->state['payment_date'] = date('Y-m-d');
        $this->state['amount'] = '';
        $this->state['product_id'] = '';
    }

    public function productsAll()
    {
        return $this->products = DB::table('SRV_APARTMENT_INFO')
            ->orderBy('product_id', 'DESC')
            ->get();
    }

    public function payMode()
    {
        return $this->payment_methods = DB::table('ACC_PAYMENT_MODE')
            ->orderBy('p_mode_id', 'DESC')
            ->get();
    }

    #[On('save_form')]
    public function save()
    {
        // Validate required fields
        Validator::make($this->state, [
            'product_id' => 'required',
            'payment_date' => 'required|date',
            'amount' => 'required|number|min:1',
        ]);
        // dd($this->state);
        try {
            if (!empty($this->state['amount']) && $this->state['amount'] > 0) {
                DB::table('SRV_PAYMENT_RECEIPT')->insert([
                    'apartment_id' => $this->state['product_id'],
                    'bill_month' => Carbon::parse($this->state['bill_month'])->format('Y-m-01'),
                    'payment_date' => $this->state['payment_date'],
                    'paid_amount' => $this->state['amount'],
                    'paid_by' => Auth::user()->name,
                    'service_type' => $this->state['bill_type'],
                    'payment_mode' => $this->state['p_mode_id'],
                    'status' => 1,
                ]);
            }

            session()->flash('success', 'Payment saved successfully!');

            return $this->redirect(route('add-receive-payment'), navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Error saving payment: ' . $e->getMessage());
            return false;
        }
    }

    public function render()
    {
        return view('livewire.dashboard.admin.receive-payment.add-receive-payment');
    }
}
