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

    public function mount($product_id = null)
    {
        $this->productsAll();
        $this->state['payment_date'] = date('Y-m-d');
        $this->state['bill_month'] = date('Y-m');
        $this->state['total_bill'] = 0;
        $this->state['money_receipt'] = '';
        $this->state['service_payment_amount'] = '';
        $this->state['due'] = 0;
        $this->state['product_id'] = $product_id;
        if($this->state['product_id'] != 'new'){
            $this->search_payment();
        }


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

        if (!$this->state['service_payment_amount'] || $this->state['service_payment_amount'] == 0) {
            session()->flash('warning', 'Please enter service payment amount.');
            return false;
        }

        $auto_id = DB::table('SRV_PAYMENT_RECEIPT')->insertGetId([
            'apartment_id' => $this->state['product_id'],
            'bill_month' => date('Y-m-01'),
            'payment_date' => $this->state['payment_date'],
            'paid_amount' => $this->state['service_payment_amount'],
            'paid_by' => Auth::user()->name,
            'service_type' => 1,
            'money_receipt_no' => $this->state['money_receipt'],
            'status' => 1,
        ], 'receipt_id');

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
                            'payment_date' => $this->state['payment_date'],
                            'auto_receipt_no' => $auto_id
                        ]);
                    $rest_amount -= $monthly->tot_bill_amt;
                    if ($rest_amount <= 0) break;
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

        session()->flash('status', 'Payment Success');
        return $this->redirect(route('add-service-payment','new'), navigate: true);
    }

    public function render()
    {
        return view('livewire.dashboard.admin.receive-payment.service.add-service-payment');
    }
}
