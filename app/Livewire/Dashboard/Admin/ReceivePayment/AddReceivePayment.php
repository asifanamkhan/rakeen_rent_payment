<?php

namespace App\Livewire\Dashboard\Admin\ReceivePayment;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;

class AddReceivePayment extends Component
{

    public $state = [];
    public $products;
    public $payment_details = [];

    public function mount()
    {
        $this->productsAll();
        $this->state['payment_date'] = date('Y-M-d');
        $this->state['bill_month'] = date('Y-m');
        $this->state['total_bill'] = 0;
        $this->state['water_payment_amount'] = 0;
        $this->state['electricity_payment_amount'] = 0;
        $this->state['service_payment_amount'] = 0;

    }

    public function productsAll()
    {
        return $this->products = DB::table('SRV_APARTMENT_INFO')
                ->orderBy('product_id', 'DESC')
                ->get();

    }

    public function search_payment(){

        Validator::make($this->state, [
            'bill_type' => 'required',
            'product_id' => 'required',
        ])->validate();


        $apartmentId = $this->state['product_id'];
        $billMonth = date('Y-m-01');
        $billType = $this->state['bill_type'];

        if($billType == 1){

            $monthly = DB::table('SRV_APARTMENT_BILL')
                ->where('apartment_id', $apartmentId)
                ->where('bill_month', $billMonth)
                ->first();
            $this->payment_details['service']['monthly'] = 0;
            if($monthly){
                if($monthly->status != 'PAID'){
                    $this->payment_details['service']['monthly'] =  $monthly->tot_bill_amt;
                }
            }

            $this->payment_details['service']['opening'] = DB::table('SRV_PAYMENT_RECEIPT')
                ->where('apartment_id', $apartmentId)
                ->where('service_type', $billType)
                ->where('status' , 'OP')
                ->sum('paid_amount');

            $this->payment_details['service']['total'] = $this->payment_details['service']['monthly'] - $this->payment_details['service']['opening'];
            $this->payment_details['service']['due'] = $this->payment_details['service']['total'];

        }elseif($billType == 2){
            $this->payment_details['electricity']['monthly'] = 0;
        }else{
            $this->payment_details['water']['monthly'] = 0;
        }

        $this->calculation();

    }

    public function remove_bill($key){
        unset($this->payment_details[$key]);
        if($key == 'service'){
            $this->state['service_payment_amount'] = 0;
        }
        if($key == 'electricity'){
            $this->state['electricity_payment_amount'] = 0;
        }
        if($key == 'water'){
            $this->state['water_payment_amount'] = 0;
        }
        $this->calculation();
    }

    public function calculation(){
        if($this->state['bill_type'] == 1){
            if(((float)$this->state['service_payment_amount'] > 0) && ($this->state['service_payment_amount'] < $this->payment_details['service']['total'])){
                $this->payment_details['service']['due'] = $this->payment_details['service']['total'] - $this->state['service_payment_amount'];
            }else{
                $this->payment_details['service']['due'] = 0;
            }
        }

        $this->state['total_bill'] =
            (float)$this->state['service_payment_amount']+
            (float)$this->state['electricity_payment_amount']+
            (float)$this->state['water_payment_amount'];

    }

    #[On('save_form')]
    public function save()
    {

        if(count($this->payment_details) > 0){
            foreach($this->payment_details as $key => $value){

                if($key == 'service'){

                     if($this->state['service_payment_amount'] >= $value['monthly']){
                        $extra_amount = $this->state['service_payment_amount'] - $value['monthly'];
                        $opening = $value['opening'] + $extra_amount;

                        DB::table('SRV_APARTMENT_BILL')
                            ->where('bill_month', date('Y-m-01'))
                            ->where('bill_type', 'SERVICE')
                            ->where('apartment_id', $this->state['product_id'])
                            ->update([
                                'paid_amount' => $value['monthly'],
                                'status' => 'PAID',
                                'payment_date' => $this->state['payment_date']
                            ]);

                        DB::table('SRV_PAYMENT_RECEIPT')
                            ->where('apartment_id', $this->state['product_id'])
                            ->where('service_type', 1)
                            ->where('status' , 'OP')
                            ->update([
                                'paid_amount' => $opening,
                            ]);
                     }
                     $paid_amount = $this->state['service_payment_amount'];
                     $service_type = 1;
                }elseif($key == 'electricity'){
                    $paid_amount = $this->state['electricity_payment_amount'];
                    $service_type = 2;
                }
                else{
                    $paid_amount = $this->state['water_payment_amount'];
                    $service_type = 3;
                }
                DB::table('SRV_PAYMENT_RECEIPT')->insert([
                    'apartment_id' => $this->state['product_id'],
                    'bill_month' => date('Y-m-01'),
                    'payment_date' => $this->state['payment_date'],
                    'paid_amount' => $paid_amount,
                    'paid_by' => Auth::user()->name,
                    'service_type' => $service_type
                ]);
            }
            session()->flash('success', 'Payment Success');
            return $this->redirect(route('add-receive-payment'), navigate: true);
        }else {
            session()->flash('error', '*At least one bill item need to added');
        }
    }

    public function render()
    {
        return view('livewire.dashboard.admin.receive-payment.add-receive-payment');
    }
}
