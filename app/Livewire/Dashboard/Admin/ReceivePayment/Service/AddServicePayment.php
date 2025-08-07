<?php

namespace App\Livewire\Dashboard\Admin\ReceivePayment\Service;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;

class AddServicePayment extends Component
{
    public $state = [];
    public $products;
    public $payment_details = [];

    public function mount()
    {
        $this->productsAll();
        $this->state['payment_date'] = date('Y-m-d');
        $this->state['bill_month'] = date('Y-m');
        $this->state['total_bill'] = 0;
        $this->state['service_payment_amount'] = '';
        $this->state['due'] = 0;
    }

    public function productsAll()
    {
        return $this->products = DB::table('SRV_APARTMENT_INFO')
            ->orderBy('product_id', 'DESC')
            ->get();
    }

    public function search_payment()
    {

        Validator::make($this->state, [
            'product_id' => 'required',
        ])->validate();


        $apartmentId = $this->state['product_id'];

        $this->payment_details['monthly'] = DB::table('SRV_APARTMENT_BILL')
            ->where('apartment_id', $apartmentId)
            ->where('status', '!=', 'PAID')
            ->orderBy('bill_month', 'DESC')
            ->get();

        $this->payment_details['monthly_total'] =
            DB::table('SRV_APARTMENT_BILL')
            ->where('apartment_id', $apartmentId)
            ->where('status', '!=', 'PAID')
            ->sum('tot_bill_amt');

        $this->payment_details['opening'] = DB::table('SRV_PAYMENT_RECEIPT')
            ->where('apartment_id', $apartmentId)
            ->where('status', 'OP')
            ->sum('paid_amount');

        $this->state['total_bill'] =
            $this->payment_details['monthly_total']
            - $this->payment_details['opening'];

        if ($this->state['total_bill'] <= 0) {
            $this->state['total_bill'] = 0;
        }

        $this->state['due'] = $this->state['total_bill'];
        $this->calculation();
    }


    public function calculation()
    {

        if (((float)$this->state['service_payment_amount'] > 0) && ((float)$this->state['service_payment_amount'] < $this->state['total_bill'])) {
            $this->state['due'] = $this->state['total_bill'] - (float)$this->state['service_payment_amount'];
        } else {
            $this->state['due'] = 0;
        }
    }

    #[On('save_form')]
    public function save()
    {
        if(!$this->state['service_payment_amount'] || $this->state['service_payment_amount'] == 0) {
            session()->flash('warning', 'Please enter service payment amount.');
            return false;
        }

        $opening = $this->payment_details['opening'];
        $rest_amount = $this->state['service_payment_amount'];
        if (count($this->payment_details['monthly']) > 0) {
            foreach ($this->payment_details['monthly'] as $monthly) {
                if ($rest_amount >= $monthly->tot_bill_amt) {
                    DB::table('SRV_APARTMENT_BILL')
                        ->where('bill_id', $monthly->bill_id)
                        ->update([
                            'paid_amount' => $monthly->tot_bill_amt,
                            'status' => 'PAID',
                            'payment_date' => $this->state['payment_date']
                        ]);
                    $rest_amount -= $monthly->tot_bill_amt;
                    if($rest_amount <= 0)   break;
                }
            }
        }

        $opening = $this->payment_details['opening'] + $rest_amount;

        DB::table('SRV_PAYMENT_RECEIPT')
            ->where('apartment_id', $this->state['product_id'])
            ->where('service_type', 1)
            ->where('status', 'OP')
            ->update([
                'paid_amount' => $opening,
            ]);

        DB::table('SRV_PAYMENT_RECEIPT')->insert([
            'apartment_id' => $this->state['product_id'],
            'bill_month' => date('Y-m-01'),
            'payment_date' => $this->state['payment_date'],
            'paid_amount' => $this->state['service_payment_amount'],
            'paid_by' => Auth::user()->name,
            'service_type' => 1
        ]);

        session()->flash('status', 'Payment Success');
        return $this->redirect(route('add-service-payment'), navigate: true);
    }
    public function render()
    {
        return view('livewire.dashboard.admin.receive-payment.service.add-service-payment');
    }
}
