<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class HomeCtrl extends Controller
{
    public function index(Request $request) {
        $service= 'iaserver-auth';
        $status= 'online';

        $motor= app()->version();
        $server_time = \Carbon\Carbon::now();

        // Obtener user segun id obtenida a travez de KONG
        /*
        $kong_id = $request->headers->get('x-consumer-custom-id');
        $kong = [
            'user_id' => $kong_id,
            'user' => User::find($kong_id)->first()
        ];
        */
        return compact('service','status','server_time');
    }
}
