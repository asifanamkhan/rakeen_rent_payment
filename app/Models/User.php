<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $table = 'USR_USERS_INFO';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function hasPermission($sub_module, $flag)
    {

        $submodule_access = DB::table('USR_ROLE_DETAIL')
            ->whereIn('role_id', json_decode(Auth::user()->roles))
            ->get();

        $permission_status = 0;

        foreach ($submodule_access as $access) {
            if ($access->module_dtl_id == $sub_module) {
                if ($access->$flag == 1) {
                    $permission_status = 1;
                    break;
                }
            }
        }

        if ($permission_status == 1) {
            return true;
        } else {
            return false;
        }
    }
}