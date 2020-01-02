<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\tblUsuario;
use Hamcrest\Type\IsObject;

class JwtAuth{

    public $key;

    public function __construct()
    {
        $this->key = 'asdreds_22545*..sffjeenss  __4456';
    }

    public function singup($email, $password, $getToken = null){
        //Buscar si existe el usuario con sus credenciales
        $user = tblUsuario::where([
            'email' => $email
            ])->first();
        $pwd = password_verify($password, $user->clave);
        //Comprobar si son correctas
            $signup = false;
            if(is_object($user) && $pwd){
                $signup = true;
            }
        //Generar el token con los datos del  usuario identificado
            if($signup){
                $token = array(
                    'sub'       =>      $user->docid,
                    'email'     =>      $user->email,
                    'nombre'    =>      $user->nombres,
                    'apellidos' =>      $user->apellidos,
                    'iat'       =>      time(),
                    'exp'       =>      time() + (7 * 24 * 60 * 60)
                );

                $jwt = JWT::encode($token, $this->key, 'HS256');
                $decoded = JWT::decode($jwt, $this->key, ['HS256']);
                //Devolver los datos decodificados o el token en función de un parámetro
                if(is_null($getToken)){
                    $data = $jwt;
                }else{
                    $data = $decoded;
                }
            }else{
                $data = array(
                    'status' => 'error',
                    'message' => 'Login incorrecto.'
                );
            }
        
        return $data;
    }

    public function checkToken($jwt, $getIdentity = false){
        $auth = false;
        try{
            $jwt = str_replace('"', '', $jwt);
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);
        }catch(\UnexpectedValueException $e){
            $auth = false;
        }catch(\DomainException $e){
            $auth = false;
        }

        if(!empty($decoded) && is_object($decoded) && isset($decoded->sub)){
            $auth = true;
        }

        if($getIdentity){
            return $decoded;
        }

        return $auth;
    }
    
}