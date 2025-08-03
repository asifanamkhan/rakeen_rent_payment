<?php

namespace App\Livewire\Dashboard\Admin\Module;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class SubModule extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $pagination = 10;

    public $id = null;
    public $module_name;
    public $pageLoad = false;

    #[Computed]
    public function resultSubModule()
    {
        if ($this->pageLoad) {

            $modules = DB::table('USR_MODULE_DTL');

            if (!is_null($this->id)) {

                $modules->where('module_mst_id', $this->id);
            }

            if ($this->search) {
                $modules->where(DB::raw('lower(module_dtl_name)'), 'like', '%' . strtolower($this->search) . '%');
            }

            return $modules->orderBy('module_dtl_id', 'DESC')
                ->paginate($this->pagination);
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[On('module-submodule-view-modal')]
    public function subMoudle($id)
    {
        $this->resetPage();

        $this->pageLoad = true;
        $this->id = $id;
        $module_name = DB::table('USR_MODULE_MST')
            ->where('module_mst_id', $id)->first('module_name');
        $this->module_name = $module_name->module_name;

        $this->resultSubModule();
    }

    public function render()
    {
        return view('livewire.dashboard.admin.module.sub-module');
    }
}
