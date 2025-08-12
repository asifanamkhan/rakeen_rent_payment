<?php

namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;

class Dashboard extends Component
{
    public $total_amount, $apartment_count, $date, $start_date, $end_date;

    public function mount()
    {
        $this->date = 3;
    }

    #[Computed]
    public function result()
    {
        $startDateCurrent = Carbon::now()->toDateString();
        $endDateCurrent = Carbon::now()->toDateString();

        $startDatePrevious = Carbon::yesterday()->toDateString();
        $endDatePrevious = Carbon::yesterday()->toDateString();

        $apartment_count = DB::table('SRV_APARTMENT_INFO')->count();
        $cs_count = DB::table('SALES_CUSTOMER_INFO')->count();
        $user_count = DB::table('USR_USERS_INFO')->count();
        $payment_received = DB::table('SRV_PAYMENT_RECEIPT')
                    ->select(
                            DB::raw("SUM(CASE WHEN service_type = 1 AND status != 'OP' THEN paid_amount ELSE 0 END ) as sr_rec"),
                            DB::raw("SUM(CASE WHEN service_type = 2 AND status != 'OP' THEN paid_amount ELSE 0 END ) as el_rec"),
                            DB::raw("SUM(CASE WHEN service_type = 3 AND status != 'OP' THEN paid_amount ELSE 0 END ) as wt_rec"),
                            DB::raw("SUM(CASE WHEN service_type = 1 AND status = 'OP' THEN paid_amount ELSE 0 END ) as sr_op"),
                        )
                        ->first();

        $service_due = DB::table('SRV_APARTMENT_BILL')->where('status','UNPAID')->sum('tot_bill_amt');


        // $query = DB::table('ACC_VOUCHER_INFO');

        // if ($this->date == 2) {
        //     $query->where('voucher_date', Carbon::now()->toDateString());
        // }
        // if ($this->date == 3) {
        //     $query->where('voucher_date', '>=', Carbon::now()->startOfWeek(Carbon::SATURDAY)->toDateString());
        //     $query->where('voucher_date', '<=', Carbon::now()->endOfWeek(Carbon::SATURDAY)->subDay()->toDateString());

        //     $startDateCurrent = Carbon::now()->startOfWeek(Carbon::SATURDAY)->toDateString();
        //     $endDateCurrent = Carbon::now()->endOfWeek(Carbon::SATURDAY)->toDateString();

        //     $startDatePrevious = Carbon::now()->subWeek()->startOfWeek(Carbon::SATURDAY)->toDateString();
        //     $endDatePrevious = Carbon::now()->subWeek()->endOfWeek(Carbon::SATURDAY)->subDay()->toDateString();

        // }
        // if ($this->date == 4) {
        //     $query->where('voucher_date', '>=', Carbon::now()->firstOfMonth()->toDateString());
        //     $query->where('voucher_date', '<=', Carbon::now()->lastOfMonth()->toDateString());

        //     $startDateCurrent = Carbon::now()->firstOfMonth()->toDateString();
        //     $endDateCurrent = Carbon::now()->lastOfMonth()->toDateString();

        //     $startDatePrevious = Carbon::now()->subMonth()->firstOfMonth()->toDateString();
        //     $endDatePrevious = Carbon::now()->subMonth()->lastOfMonth()->toDateString();
        // }
        // if ($this->date == 5) {

        //     $query->where('voucher_date', '>=', Carbon::now()->startOfYear()->toDateString());
        //     $query->where('voucher_date', '<=', Carbon::now()->endOfYear()->toDateString());

        //     $startDateCurrent = Carbon::now()->startOfYear()->toDateString();
        //     $endDateCurrent = Carbon::now()->endOfYear()->toDateString();

        //     $startDatePrevious = Carbon::now()->subYear()->startOfYear()->toDateString();
        //     $endDatePrevious = Carbon::now()->subYear()->endOfYear()->toDateString();
        // }

        // if ($this->date == 6) {
        //     // dd($this->date);
        //     if ($this->start_date) {
        //         $query->where('voucher_date', '>=', $this->start_date);
        //     }
        //     if ($this->end_date) {
        //         $query->where('voucher_date', '<=', $this->end_date);
        //     }
        // }

        // $query->select(
        //     DB::raw("SUM(CASE WHEN tran_type = 'PR' AND voucher_type = 'DR' THEN amount ELSE 0 END) as pr_total"),
        //     DB::raw("SUM(CASE WHEN tran_type = 'PR' AND voucher_type = 'CR' THEN amount ELSE 0 END) as pr_paid_total"),
        //     DB::raw("SUM(CASE WHEN tran_type = 'PRT' AND voucher_type = 'CR' THEN amount ELSE 0 END) as prt_total"),
        //     DB::raw("SUM(CASE WHEN tran_type = 'SL' AND voucher_type = 'CR' THEN amount ELSE 0 END) as sl_total"),
        //     DB::raw("SUM(CASE WHEN tran_type = 'SL' AND voucher_type = 'DR' THEN amount ELSE 0 END) as sl_paid_total"),
        //     DB::raw("SUM(CASE WHEN tran_type = 'SRT' AND voucher_type = 'DR' THEN amount ELSE 0 END) as srt_total"),
        //     DB::raw("SUM(CASE WHEN tran_type = 'EXP' AND voucher_type = 'DR' THEN amount ELSE 0 END) as exp_total"),
        // );

        return [
            // 'total' => $query->first(),
            'apartment_count' => $apartment_count,
            'cs_count' => $cs_count,
            'user_count' => $user_count,
            'payment_received' => $payment_received,
            'service_due' => $service_due,
        ];
    }

    public function salesDataUpdated(){

    }

    public function render()
    {

        return view('livewire.dashboard.dashboard');
    }
}
