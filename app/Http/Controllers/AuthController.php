<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'password' => 'required'
        ]);

        try {
	    $credential = $request->only('name', 'password');
            $token = $this->jwt->attempt($credential);

            if ($token) {
                return response()->json(compact('token'));
            } else {
                return $this->jwt_error(401,'invalid_credentials','Credenciales invalidas');
            }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return $this->jwt_error(500,'token_expired','Token expirado');
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return $this->jwt_error(500,'token_invalid','Token invalido');
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return $this->jwt_error(500,'token_absent',$e->getMessage());
        }
    }

    public function me() {
        $user = Auth::guard('api')->user();
        if($user)
        {
            return response()->json($user);
        } else
        {
            return $this->jwt_error(401,'token_invalid','Token invalido');
        }
    }

    public function logout() {
        Auth::logout();
        return response()->json(['message' => 'Sesion finalizada']);
    }

    public function refresh()
    {
        $token = Auth::refresh();
        return response()->json(compact('token'));
    }

    public function generateHash() {
        $password = Input::get('password');
        return [
            'hash' => Hash::make($password)
        ];
    }

    private function jwt_error($code,$error,$message){
        $output = compact('code','error','message');
        return response()->json($output, $code);
    }
}
