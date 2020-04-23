<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Input;
use Spatie\Permission\Models\Role;

class RoleCrud extends Controller
{
    public function __construct()
    {
    }

    public function all() {
        return Role::all();
    }

    public function add() {
        $name = Input::get('name');
        $role = Role::create(['name' => $name]);
        return $role;
    }

    public function delete() {
        $id = Input::get('id');
        $item = Role::findById($id);
        $item->delete();
        return $item;
    }

    public function view($name) {
        $role = Role::findByName($name);
        $permission = $role->permissions;
        return $role;
    }

    public function updatePermission($name)
    {
        $mode = Input::get('mode');

        $permission = Input::get('permission');

        $role = Role::findByName($name);

        switch ($mode)
        {
            case 'add':
                $role->givePermissionTo($permission);
                break;
            case 'delete':
                $role->revokePermissionTo($permission);
                break;
            case 'sync':
                break;
        }

        return $role;
    }

    public function roleToUser($role,$userId) {
        $role = Role::findByName($role);
        $user = User::where('id',$userId)->first();
        $user->assignRole($role);

        return $user;
    }
}
