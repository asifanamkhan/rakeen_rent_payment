<?php

namespace App\Livewire\Dashboard\Admin\ReceivePayment;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceDueReportSummary extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $product_id = '';
    public $from_month = '';
    public $to_month = '';
    public $pagination = 10;

    public function mount(): void
    {
        $this->from_month = '';
        $this->to_month = '';
    }

    #[Computed]
    public function dues()
    {
        $due = DB::table('VW_DUE_REPORT');
        if ($this->product_id) {
            $due->where('product_id', $this->product_id);
        }

        if ($this->from_month) {
            $start = date('Y-m-01', strtotime($this->from_month . '-01'));
            $due->where('bill_month', '>=', $start);
        }

        if ($this->to_month) {
            $end = date('Y-m-t', strtotime($this->to_month . '-01'));
            $due->where('bill_month', '<=', $end);
        }

        $perPage = $this->pagination == 'all'
        ? $due->count()
        : $this->pagination;

        return $due
            ->orderBy('product_id', 'ASC')
            ->paginate($perPage);
    }

    public function search_report(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['product_id', 'from_month', 'to_month']);
        $this->resetPage();
    }

    public function render()
    {
        $apartments = DB::table('SRV_APARTMENT_INFO')
            ->orderBy('product_id', 'ASC')
            ->get();

        return view('livewire.dashboard.admin.receive-payment.service-due-report-summary', [
            'products' => $apartments,
        ]);
    }
}