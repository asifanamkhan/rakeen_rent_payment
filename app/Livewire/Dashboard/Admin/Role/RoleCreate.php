<?php

namespace App\Livewire\Dashboard\Admin\Role;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;


class RoleCreate extends Component
{
    public $role_name;
    public $role_desc;
    public $modules;
    public $module;
    public $sub_modules = [];
    public $singleCheck = [];
    public $lineCheck = [];
    public $allCheck = [];

    public $module_dtl_ids;
    public $brearcums = true;

    public function mount()
    {

    }

    public function allModules()
    {
        $this->modules = DB::table('USR_MODULE_MST as m')
            ->get();
    }

    public function upLineCheck($id)
    {
        if ($this->lineCheck[$id]['all'] == true) {
            $this->singleCheck[$id]['view'] = true;
            $this->singleCheck[$id]['read'] = true;
            $this->singleCheck[$id]['write'] = true;
            $this->singleCheck[$id]['edit'] = true;
        } else {
            $this->singleCheck[$id]['view'] = false;
            $this->singleCheck[$id]['read'] = false;
            $this->singleCheck[$id]['write'] = false;
            $this->singleCheck[$id]['edit'] = false;
        }
    }

    public function updatedAllCheck($value)
    {

        if ($value) {
            foreach ($this->module_dtl_ids as $module_dtl_id) {
                $this->lineCheck[$module_dtl_id]['all'] = true;
                $this->upLineCheck($module_dtl_id);
            }
        } else {
            foreach ($this->module_dtl_ids as $module_dtl_id) {
                $this->lineCheck[$module_dtl_id]['all'] = false;
                $this->upLineCheck($module_dtl_id);
            }
        }
    }
    public function module_change()
    {
        $this->sub_modules = DB::table('USR_MODULE_DTL as m')
            ->where('module_mst_id', $this->module)
            ->get();

        $this->module_dtl_ids = $this->sub_modules->pluck('module_dtl_id')->toArray();
        // dd($this->module_dtl_ids);
    }

    public function save()
    {
        $this->validate([
            'role_name' => 'required|unique:USR_ROLE_MASTER',
            'module' => 'required'
        ]);

        DB::beginTransaction();
        try {

            $id = DB::table('USR_ROLE_MASTER')->insertGetId([
                'role_name' => $this->role_name,
                'role_desc' => $this->role_desc,
                'user_id' => Auth::user()->id,
            ], 'role_id');

            foreach ($this->singleCheck as $key => $value) {
                DB::table('USR_ROLE_DETAIL')->insert([
                    'role_id' => $id,
                    'module_dtl_id' => $key,
                    'visible_flag' => @$value['view'] ?? 0,
                    'write_flag' => @$value['write'] ?? 0,
                    'edit_flag' => @$value['edit'] ?? 0,
                    'read_flag' => @$value['read'] ?? 0,
                ]);
            }


            DB::commit();

            $this->resetExcept('brearcums');
            $this->allModules();
            session()->flash('status', 'New role created successfully');

            if($this->brearcums == false){
                $this->dispatch('refresh-roles-user-create');
            }

        } catch (\Exception $exception) {
            DB::rollback();
            session()->flash('error', $exception);
        }
    }


    #[On('roleModalEvent')]
    public function breadcumsEvent(){
        $this->brearcums = false;
    }
    public function render()
    {
        $this->allModules();
        return view('livewire.dashboard.admin.role.role-create')->title('Role create');
    }
}