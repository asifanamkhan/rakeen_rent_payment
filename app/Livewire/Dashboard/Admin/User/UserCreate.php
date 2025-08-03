<?php

namespace App\Livewire\Dashboard\Admin\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\On;

class UserCreate extends Component
{
    public $users, $user, $name, $email, $phone_number, $password, $password_confirmation;
    public $roles;
    public $role = [];
    public $roleModalEvent = 'roleModalEvent';

    public function usersAll()
    {
        $this->users = DB::table('HRM_EMPLOYEE_INFO')
            ->get(['emp_name', 'email', 'mobile','employee_id']);

        return $this->users;
    }

    #[On('employee-select-user-create')]
    public function employeeSelect($id){
        $this->user = $id;
        $employee = DB::table('HRM_EMPLOYEE_INFO')
                    ->where('employee_id', $this->user)
                    ->first();

        $this->email = @$employee->email ?? '';
        $this->name = @$employee->emp_name ?? '';
        $this->phone_number = @$employee->mobile ?? '';
    }

    #[On('refresh-roles-user-create')]
    public function roleAll()
    {
        $this->roles = DB::table('USR_ROLE_MASTER')
            ->orderBy('role_id', 'DESC')
            ->get(['role_id', 'role_name']);
        return $this->roles;
    }

    public function save()
    {

        $this->validate([
            'user' => 'required',
            'email' => 'required',
            'role' => 'required|min:1',
            'password' => 'required|min:4|confirmed'
        ]);

        // DB::beginTransaction();

        // try {

            $user_id = DB::table('USR_USERS_INFO')->insertGetId([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'phone_number' => $this->phone_number,
                'roles' => json_encode($this->role),

                // 'user_status' => 1,
            ], 'id');

            // foreach ($this->role as $key => $value) {
            //     DB::table('USR_USER_ROLE')->insert([
            //         'user_id' => $user_id,
            //         'role_id' => $value,
            //     ]);
            // }

        //     DB::commit();
        //     $this->reset();
        //     session()->flash('status', 'New user created successfully');


        // } catch (\Exception $exception) {
        //     DB::rollback();
        //     session()->flash('error', $exception);
        // }

    }

    public function render()
    {
        $this->usersAll();
        $this->roleAll();
        return view('livewire.dashboard.admin.user.user-create')->title('User create');
    }
}