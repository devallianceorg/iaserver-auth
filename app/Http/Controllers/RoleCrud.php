<?php

namespace App\Http\Controllers;

use App\Roles;
use App\User;
use Illuminate\Support\Facades\Input;

class RoleCrud extends Controller
{
    public function __construct()
    {
    }

    public function all() {
        return Roles::paginate();
    }

    public function add() {
        $name = Input::get('name');
        $role = Roles::create(['name' => $name]);
        return $role;
    }

    public function delete() {
        $id = Input::get('id');
        $item = Roles::findById($id);
        $item->delete();
        return $item;
    }

    public function view($name) {
        $role = Roles::findByName($name);
        $permission = $role->permissions;
        return $role;
    }

    public function updatePermission($name)
    {
        $mode = Input::get('mode');

        $permission = Input::get('permission');

        $role = Roles::findByName($name);

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
        $role = Roles::findByName($role);
        $user = User::where('id',$userId)->first();
        $user->assignRole($role);

        return $user;
    }
}
