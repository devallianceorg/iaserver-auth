<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Spatie\Permission\Models\Permission;

class PermissionCrud extends Controller
{
    public function __construct()
    {
    }

    public function all() {
        return Permission::all();
    }

    public function add() {
        $name = Input::get('name');
        $item = Permission::create(['name' => $name]);
        return $item;
    }

    public function delete() {
        $id = Input::get('id');
        $item = Permission::findById($id);
        $item->delete();
        return $item;
    }

}
