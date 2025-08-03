<?php

namespace App\Livewire\Dashboard\Hrm\Supplier;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;

class SupplierForm extends Component
{
    use WithFileUploads;

    public function render()
    {
        return view('livewire.dashboard.hrm.supplier.supplier-form');
    }
}
