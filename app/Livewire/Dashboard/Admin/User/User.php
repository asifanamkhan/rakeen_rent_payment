<?php

namespace App\Livewire\Dashboard\Admin\User;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class User extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';


    public $search;
    public $pagination = 10;


    #[Computed]
    public function resultUser()
    {
        $users = DB::table('USR_USERS_INFO');


        if ($this->search) {
            $users
                ->where(DB::raw('lower(name)'), 'like', '%' . strtolower($this->search) . '%');
        }

        return $users->orderBy('id', 'DESC')
            ->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        return view('livewire.dashboard.admin.user.user')
            ->title('Users');
    }
}
