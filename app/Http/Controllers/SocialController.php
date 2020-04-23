<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class SocialController extends Controller
{
    public function login($provider)
    {
        // Enviamos a oauth el nombre del app
        $app = Input::get('app');

        return Socialite::with($provider)
            ->stateless()
            ->with(['state'=>$app])
            ->redirect();
    }

    public function callback($provider)
    {
        // Nombre del App retornado desde oauth
        $app = Input::get('state');

        try {
            $oauth = (object) Socialite::with($provider)
                ->stateless()
                ->user();

            $jwt = null;

            if($oauth)
            {
                // Busco correo obtenido por el provider
                $user = User::where('email',$oauth->email)->first();
                // En caso de encontrar un registro de usuario con ese email
                if($user) {
                    switch($provider) {
                        case 'google':
                            if(!$user->google_id) {
                                // Actualizar provider data
                                $user->google = $provider;
                                $user->google_id = $oauth->id;
                                $user->save();
                            }
                            break;
                    }
                } else {
                    // Si no existe en la base de datos, crea un nuevo usuario
                    $user  = new User();
                    $user ->name= $oauth->name;
                    $user ->email = $oauth->email;
                    $user ->google = $provider;
                    $user ->google_id= $oauth->id;
                    $user ->save();
                }

                // Loguea y retorna token
                $jwt = $this->jwtLogin($user);

                $token = $jwt['token'];

                return $this->redireccionar($token,$app);
            } else {
                return compact('oauth');
            }
        } catch(\Exception $ex)
        {
            return ['error' => $ex->getMessage()];
        }
    }

    private function jwtLogin(User $user)
    {
        Config::set('jwt.user', 'App\User');
        Config::set('auth.providers.users.model', \App\User::class);

        $token = null;

        try {
            $token = JWTAuth::fromUser($user);

            if ($token) {
                $response = 'success';
                $output = compact('response','token');
                return $output;
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
        $user = Auth::guard('social')->user();
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

    private function redireccionar($token,$app) {
        $url = url();
        $habilitado = false;

        if($app=='debug') {
            dd($url,$token);
        }

        if (strpos($url, 'codigoush') !== false) {
            $habilitado = true;
            switch($app)
            {
                case 'pwa':
                    $url = 'https://codigoush.com';
                    break;
                case 'lte':
                    $url = 'https://lte.codigoush.com';
                    break;
            }
        }

        if (strpos($url, 'localhost') !== false) {
            $habilitado = true;
            switch($app)
            {
                case 'lte':
                    $url = 'http://localhost:81';
                    break;
                case 'pwa':
                    $url = 'http://localhost';
                    break;
            }
        }

        if($habilitado) {
            header("Location: $url?token=$token");
        }

        $error = [
            'mensaje' => 'No fue posible redireccionar la peticion',
            'url' => $url,
            'app' => $app,
        ];

        return compact('error');
    }

    private function jwt_error($code,$error,$message){
        $output = compact('code','error','message');
        return $output;
    }
}
