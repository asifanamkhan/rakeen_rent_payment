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
    public $products, $payment_methods;
    public $payment_details = [];

    public function mount($product_id = null)
    {
        $this->productsAll();
        $this->payMode();
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

    public function payMode()
    {
        return $this->payment_methods = DB::table('ACC_PAYMENT_MODE')
            ->orderBy('p_mode_id', 'DESC')
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
            ->orderBy('bill_month', 'ASC')
            ->get();

        // Total monthly bill
        $this->payment_details['monthly_total'] =
            DB::table('SRV_APARTMENT_BILL')
            ->where('apartment_id', $apartmentId)
            ->where('status', '!=', 'PAID')
            ->sum('tot_bill_amt');

        // Already Paid Bills
        $this->payment_details['paid_total'] =
            DB::table('SRV_APARTMENT_BILL')
                ->where('apartment_id', $apartmentId)
                ->where('status', '!=', 'PAID')
                ->sum('paid_amount');

        $due_bill = $this->payment_details['monthly_total'] - $this->payment_details['paid_total'];

        $this->payment_details['opening'] = DB::table('SRV_PAYMENT_RECEIPT')
            ->where('apartment_id', $apartmentId)
            ->where('status', 'OP')
            ->sum('paid_amount');

        $this->state['total_bill'] = $this->payment_details['opening'] + $due_bill;

        $this->state['due'] = $this->state['total_bill'];

        $this->calculation();
    }


    public function calculation()
    {
        if((float)$this->state['service_payment_amount'] <= 0){
            $this->state['due'] = $this->state['total_bill'];
        }
        elseif(((float)$this->state['service_payment_amount'] > 0) && ((float)$this->state['service_payment_amount'] > $this->state['total_bill'])){
            $this->state['service_payment_amount'] = $this->state['total_bill'];
            $this->state['due'] = 0;
            session()->flash('error', 'Payment amount can not be greater than total bill amount.');
        }else{
            $this->state['due'] = $this->state['total_bill'] - $this->state['service_payment_amount'];
        }
    }

    #[On('save_form')]
    public function save()
    {

        if (!$this->state['service_payment_amount'] || $this->state['service_payment_amount'] == 0 || ($this->state['service_payment_amount'] > $this->state['total_bill'])) {
            session()->flash('warning', 'Please enter correct service payment amount.');
            return false;
        }

        $auto_id = DB::table('SRV_PAYMENT_RECEIPT')->insertGetId([
            'apartment_id' => $this->state['product_id'],
            'bill_month' => date('Y-m-01'),
            'payment_date' => $this->state['payment_date'],
            'paid_amount' => $this->state['service_payment_amount'],
            'paid_by' => Auth::user()->name,
            'service_type' => 1,
            'money_receipt_no' => $this->state['money_receipt'] ?? 0,
            'status' => 1,
            'payment_mode' => $this->state['p_mode_id'],
        ], 'receipt_id');

        $rest_amount = $this->state['service_payment_amount'];

        // First, pay monthly bills in chronological order
        if (count($this->payment_details['monthly']) > 0) {
            foreach ($this->payment_details['monthly'] as $monthly) {
                if ($rest_amount <= 0) {
                    break; // No more payment amount left
                }

                // Calculate how much is already paid for this bill
                $already_paid = $monthly->paid_amount ?? 0;
                $remaining_bill = $monthly->tot_bill_amt - $already_paid;

                if ($remaining_bill > 0) {
                    if ($rest_amount >= $remaining_bill) {
                        // Pay the full remaining bill
                        $payment_for_this_bill = $remaining_bill;
                        $new_paid_amount = $monthly->tot_bill_amt;
                        $status = 'PAID';
                    } else {
                        // Partial payment
                        $payment_for_this_bill = $rest_amount;
                        $new_paid_amount = $already_paid + $rest_amount;
                        $status = 'UNPAID';
                    }

                    DB::table('SRV_APARTMENT_BILL')
                        ->where('bill_id', $monthly->bill_id)
                        ->update([
                            'paid_amount' => $new_paid_amount,
                            'status' => $status,
                            'payment_date' => $this->state['payment_date'],
                            'auto_receipt_no' => $auto_id
                        ]);

                    $rest_amount -= $payment_for_this_bill;
                }
            }
        }

        // After paying monthly bills, handle opening balance if there's remaining payment
        if ($rest_amount > 0 && $this->payment_details['opening'] > 0) {
            $current_opening = $this->payment_details['opening'];

            if ($rest_amount >= $current_opening) {
                // Payment covers full opening balance
                $new_opening = 0;
            } else {
                // Partial payment against opening balance
                $new_opening = $current_opening - $rest_amount;
            }

            DB::table('SRV_PAYMENT_RECEIPT')
                ->where('apartment_id', $this->state['product_id'])
                ->where('service_type', 1)
                ->where('status', 'OP')
                ->update([
                    'paid_amount' => $new_opening,
                ]);
        }

        session()->flash('status', 'Payment Success');
        return $this->redirect(route('add-service-payment','new'), navigate: true);
    }

    public function render()
    {
        return view('livewire.dashboard.admin.receive-payment.service.add-service-payment');
    }
}
