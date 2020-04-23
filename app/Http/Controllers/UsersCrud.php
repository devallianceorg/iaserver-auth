<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class UsersCrud extends Controller
{
    public function all() {
        $find = Input::get('find');
        if(empty($find)) {
            $users = User::paginate();
        } else {
            $users = User::where('username',$find)
                ->orWhere('role',$find)
                ->paginate();
        }
        return $users;
    }

    public function show($id) {
        return User::findOrFail($id);
    }

    public function add() {
        $add = new User();
        $add->name = Input::get('name');
        $add->email = Input::get('email');
        $add->password = Hash::make(Input::get('password'));
        $add->save();
        return $add;
    }

    public function delete() {
/*        $name = Input::get('name');
        $role = Permission::findByName($name);
        $role->delete();
        return $role;*/
    }

}
