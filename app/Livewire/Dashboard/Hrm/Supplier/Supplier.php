<?php

namespace App\Livewire\Dashboard\Hrm\Supplier;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Supplier extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $pagination = 10;

    #[Computed]
    public function resultSupplier()
    {
        $suppliers = DB::table('INV_SUPPLIER_INFO');


        if ($this->search) {
            $suppliers
                ->where(DB::raw('lower(p_name)'), 'like', '%' . strtolower($this->search) . '%')
                ;
        }

        return $suppliers->orderBy('p_code', 'DESC')
            ->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.dashboard.hrm.supplier.supplier')->title('Supplier');
    }
}