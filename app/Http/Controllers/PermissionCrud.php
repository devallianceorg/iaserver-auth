<?php

namespace App\Http\Controllers;

use App\Permisos;
use Illuminate\Support\Facades\Input;

class PermissionCrud extends Controller
{
    public function __construct()
    {
    }

    public function all() {
        return Permisos::paginate();
    }

    public function add() {
        $name = Input::get('name');
        $item = Permisos::create(['name' => $name]);
        return $item;
    }

    public function delete() {
        $id = Input::get('id');
        $item = Permisos::findById($id);
        $item->delete();
        return $item;
    }

}
