<?php

namespace App\Livewire\Dashboard\Admin\Module;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Module extends Component
{
    use Withpagination;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $submoduleEvent = 'submodule-event';
    public $pagination = 10;


    #[Computed]
    public function resultModule()
    {
        $modules = DB::table('USR_MODULE_MST');


        if ($this->search) {
            $modules->where(DB::raw('lower(module_name)'), 'like', '%' . strtolower($this->search) . '%');
        }

        return $resultModule = $modules->orderBy('module_mst_id', 'DESC')
            ->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    // public function updatingPagination()
    // {
    //     $this->resetPage();
    // }
    public function render()
    {
        return view('livewire.dashboard.admin.module.module')->title('Modules');;
    }
}
