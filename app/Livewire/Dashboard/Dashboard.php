<?php

namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;

class Dashboard extends Component
{
    public $total_amount, $date, $start_date, $end_date;

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

        $query = DB::table('ACC_VOUCHER_INFO');

        if ($this->date == 2) {
            $query->where('voucher_date', Carbon::now()->toDateString());
        }
        if ($this->date == 3) {
            $query->where('voucher_date', '>=', Carbon::now()->startOfWeek(Carbon::SATURDAY)->toDateString());
            $query->where('voucher_date', '<=', Carbon::now()->endOfWeek(Carbon::SATURDAY)->subDay()->toDateString());

            $startDateCurrent = Carbon::now()->startOfWeek(Carbon::SATURDAY)->toDateString();
            $endDateCurrent = Carbon::now()->endOfWeek(Carbon::SATURDAY)->toDateString();

            $startDatePrevious = Carbon::now()->subWeek()->startOfWeek(Carbon::SATURDAY)->toDateString();
            $endDatePrevious = Carbon::now()->subWeek()->endOfWeek(Carbon::SATURDAY)->subDay()->toDateString();

        }
        if ($this->date == 4) {
            $query->where('voucher_date', '>=', Carbon::now()->firstOfMonth()->toDateString());
            $query->where('voucher_date', '<=', Carbon::now()->lastOfMonth()->toDateString());

            $startDateCurrent = Carbon::now()->firstOfMonth()->toDateString();
            $endDateCurrent = Carbon::now()->lastOfMonth()->toDateString();

            $startDatePrevious = Carbon::now()->subMonth()->firstOfMonth()->toDateString();
            $endDatePrevious = Carbon::now()->subMonth()->lastOfMonth()->toDateString();
        }
        if ($this->date == 5) {

            $query->where('voucher_date', '>=', Carbon::now()->startOfYear()->toDateString());
            $query->where('voucher_date', '<=', Carbon::now()->endOfYear()->toDateString());

            $startDateCurrent = Carbon::now()->startOfYear()->toDateString();
            $endDateCurrent = Carbon::now()->endOfYear()->toDateString();

            $startDatePrevious = Carbon::now()->subYear()->startOfYear()->toDateString();
            $endDatePrevious = Carbon::now()->subYear()->endOfYear()->toDateString();
        }

        if ($this->date == 6) {
            // dd($this->date);
            if ($this->start_date) {
                $query->where('voucher_date', '>=', $this->start_date);
            }
            if ($this->end_date) {
                $query->where('voucher_date', '<=', $this->end_date);
            }
        }

        $query->select(
            DB::raw("SUM(CASE WHEN tran_type = 'PR' AND voucher_type = 'DR' THEN amount ELSE 0 END) as pr_total"),
            DB::raw("SUM(CASE WHEN tran_type = 'PR' AND voucher_type = 'CR' THEN amount ELSE 0 END) as pr_paid_total"),
            DB::raw("SUM(CASE WHEN tran_type = 'PRT' AND voucher_type = 'CR' THEN amount ELSE 0 END) as prt_total"),
            DB::raw("SUM(CASE WHEN tran_type = 'SL' AND voucher_type = 'CR' THEN amount ELSE 0 END) as sl_total"),
            DB::raw("SUM(CASE WHEN tran_type = 'SL' AND voucher_type = 'DR' THEN amount ELSE 0 END) as sl_paid_total"),
            DB::raw("SUM(CASE WHEN tran_type = 'SRT' AND voucher_type = 'DR' THEN amount ELSE 0 END) as srt_total"),
            DB::raw("SUM(CASE WHEN tran_type = 'EXP' AND voucher_type = 'DR' THEN amount ELSE 0 END) as exp_total"),
        );



        $salesComparison = DB::table('VW_SALES_REPORT')
            ->select(
                'st_group_item_id', 'item_name','color_name','item_size_name',
                DB::raw("SUM(CASE WHEN sales_date BETWEEN '" . $startDateCurrent . "' AND '" . $endDateCurrent . "' THEN sales_qty ELSE 0 END) as current_sales"),
                DB::raw("SUM(CASE WHEN sales_date BETWEEN '" . $startDatePrevious . "' AND '" . $endDatePrevious . "' THEN sales_qty ELSE 0 END) as previous_sales")
            )
            ->groupBy('st_group_item_id','item_name','color_name','item_size_name')
            ->orderBy('current_sales','DESC')
            ->take(6)
            ->get();

        $salesComparison = $salesComparison->map(function($item) {
            $item->percentage_change = ($item->previous_sales != 0)
                ? (($item->current_sales - $item->previous_sales) / $item->previous_sales) * 100
                : null; // Handle case when previous_sales is zero
            return $item;
        });

        $getSalesData = DB::table('VW_SALES_REPORT')
                ->where('sales_date','>=',Carbon::now()->subDays(15)->toDateString())
                ->select(DB::raw('TRUNC(sales_date) as sales_date'), DB::raw('SUM(sales_qty) as total_sales'))
                ->groupBy(DB::raw('TRUNC(sales_date)'))
                ->orderBy(DB::raw('TRUNC(sales_date)'), 'ASC')
                ->get();

        $salesDataFormatted = $getSalesData->map(function ($item) {
            $item->sales_date = \Carbon\Carbon::parse($item->sales_date)->format('d-M-y'); // Format the date
            return $item;
        });

        return [
            'total' => $query->first(),
            'top_item' => $salesComparison,
            'salesData' => $salesDataFormatted,
        ];
    }

    public function salesDataUpdated(){

    }

    public function render()
    {

        return view('livewire.dashboard.dashboard');
    }
}