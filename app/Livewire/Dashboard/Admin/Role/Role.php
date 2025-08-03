<?php

namespace App\Livewire\Dashboard\Admin\Role;


use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Role extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search;
    public $pagination = 10;


    #[Computed]
    public function resultRole()
    {
        $roles = DB::table('USR_ROLE_MASTER');


        if ($this->search) {
            $roles
                ->where(DB::raw('lower(role_name)'), 'like', '%' . strtolower($this->search) . '%')
                ->orWhere(DB::raw('lower(role_desc)'), 'like', '%' . strtolower($this->search) . '%');
        }

        return $roles->orderBy('role_id', 'DESC')
            ->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        return view('livewire.dashboard.admin.role.role')
            ->title('Roles');;
    }
}
