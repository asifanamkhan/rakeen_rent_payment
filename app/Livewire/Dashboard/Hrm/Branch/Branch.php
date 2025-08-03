<?php

namespace App\Livewire\Dashboard\Hrm\Branch;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Branch extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $create_title = 'Create new branch ';
    public $update_title = 'Update branch';
    public $create_event = 'branch-create';
    public $update_event = 'branch-update';
    public $search;
    public $pagination = 10;


    #[Computed]
    public function resultBranch()
    {
        $branchs = DB::table('INV_BRANCH_INFO');


        if ($this->search) {
            $branchs
                ->where(DB::raw('lower(branch_name)'), 'like', '%' . strtolower($this->search) . '%')
                ;
        }

        return $branchs->orderBy('branch_id', 'DESC')
            ->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[On('refresh-branches')]
    public function refreshBranch(){
        $this->resultBranch();
    }


    public function render()
    {
        return view('livewire.dashboard.hrm.branch.branch')->title('Branch');
    }
}