<?php

namespace App\Livewire\Dashboard\Admin\Role;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RoleDetails extends Component
{
    public $roleDetail;
    public $role_permissions;
    public $role_id;

    public function mount($role_id)
    {
        $this->role_id = $role_id;

        $this->roleDetail = DB::table('USR_ROLE_MASTER')
            ->where('role_id', $this->role_id)
            ->first();

        $this->role_permissions = DB::table('USR_ROLE_DETAIL')
            ->where('role_id', $this->role_id)
            ->leftJoin('USR_MODULE_DTL', 'USR_ROLE_DETAIL.module_dtl_id', '=', 'USR_MODULE_DTL.module_dtl_id')
            ->leftJoin('USR_MODULE_MST', 'USR_MODULE_DTL.module_mst_id', '=', 'USR_MODULE_MST.module_mst_id')
            ->orderBy('USR_MODULE_DTL.module_mst_id', 'ASC')
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard.admin.role.role-details')->title('Role details');
    }
}
