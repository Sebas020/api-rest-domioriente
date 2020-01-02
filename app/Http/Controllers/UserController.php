<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\tblUsuario;

class UserController extends Controller
{
	public function index()
	{
		$users = tblUsuario::all()->load('negocio');

		return response()->json([
			'code' => 200,
			'status' => 'success',
			'Users' => $users
		]);
	}

	public function register(Request $request)
	{
		//Recoger los datos
		$json = $request->input('json', null);
		$params = json_decode($json); //convertir en objeto
		$params_array = json_decode($json, true); //convertir en array

		
		if (!empty($params) && !empty($params_array)) {
			//Limpiar datos
			$params_array = array_map('trim', $params_array);

			//validar datos
			$validate = \Validator::make($params_array, [
				'nombres' 	=> 'required',
				'apellidos' => 'required',
				'email'		=> 'required|email|unique:tbl_usuarios',
				'clave'	=> 'required'
			]);

			if ($validate->fails()) {
				//Validación fallida
				$data = array(
					'status' => 'error',
					'code' 	=> 404,
					'message' => 'El usuario no se ha creado',
					'errors' =>	$validate->errors()
				);
			} else {
				//Validación pasada correctamente
				//Cifrar la contraseña
				$pwd = password_hash($params->clave, PASSWORD_BCRYPT, ['cost' => 4]);

				$user = new tblUsuario();
				$user->tipo_usuario = 2;
				$user->docid = $params_array['docid'];
				$user->nombres = $params_array['nombres'];
				$user->apellidos = $params_array['apellidos'];
				$user->email = $params_array['email'];
				$user->clave = $pwd;

				//Guardar el usuario
				$user->save();

				$data = array(
					'status' => 'success',
					'code' 	=> 200,
					'message' => 'El usuario se ha creado correctamente'
				);
			}
		}else{
			$data = array(
				'status' => 'error',
				'code' 	=> 404,
				'message' => 'Los datos enviados no son correctos'
			);
		}
		return response()->json($data, $data['code']);
	}

	public function login(Request $request){
		
		$jwt = new \JwtAuth();
		$json = $request->input('json', null);

		$params = json_decode($json);
		$params_array = json_decode($json, true);

		if(isset($params_array)){
			$validate = \Validator::make($params_array,[
				'email' => 'required|email',
				'clave' => 'required'
			]);

			if($validate->fails()){
				$signup = array(
					'status' => 'error',
					'code' 	=> 404,
					'message' => 'Datos ingresados son incorrectos',
					'errors' =>	$validate->errors()
				);
			}else{
				$signup = $jwt->singup($params->email, $params->clave, true);
				if(!empty($params->gettoken)){
					$token = $jwt->singup($params->email, $params->clave);
					$signup = array(
						'status' => 'success',
						'code' 	=> 200,
						'token' => $token
					);
				}
			}
		}else{
			$signup = array(
				'status' => 'error',
				'code' 	=> 404,
				'message' => 'Los datos no se han llenado correctamente'
			);
		}

		return response()->json($signup);
	}

	public function update(Request $request){
		$jwtAuth = new \JwtAuth();
		$token = $request->header('Authorization', false);
		$chekToken = $jwtAuth->checkToken($token);
		
		$json = $request->input('json', null);
		$params = json_decode($json);
		$params_array = json_decode($json, true);

		if($chekToken && !empty($params_array)){
			$identity = $jwtAuth->checkToken($token, true);
			//var_dump($identity);die();
			$validate = \Validator::make($params_array, [
				'nombres' => 'required|alpha',
				'apellidos' => 'required|alpha',
				'email'		=> 'required|email|unique:tbl_usuarios',
				'celular' => 'required|max:10',
				'tel_fijo' => 'required|max:7',
				'fecha_nacimiento' => 'nullable|date',
				'direccion' => 'required',
				'contacto' => 'required'
			]);

			if($validate->fails()){
				$updated = array(
					'status' => 'error',
					'code' 	=> 404,
					'message' => 'Datos ingresados son incorrectos',
					'errors' =>	$validate->errors()
				);
			}else{
				unset($params_array['docid']);
				unset($params_array['foto']);
				unset($params_array['created_at']);
				unset($params_array['remember_token']);
				$user = tblUsuario::where('docid', $identity->sub)->update($params_array);

				if($user){
					$updated = array(
						'status' => 'success',
						'code' 	=> 200,
						'message' => 'Datos actualizados correctamente',
						'updated' =>	$params_array
					);
				}
			}
		}
		return response()->json($updated, 200);
	}

	public function getUser(Request $request){
		$jwtAuth = new \JwtAuth();
		$token = $request->header('Authorization', false);
		$chekToken = $jwtAuth->checkToken($token, true);

		if(!empty($chekToken)){
			$user = tblUsuario::where('docid', $chekToken->sub)->first();
			if(is_object($user)){
				$data = array(
					'status' => 'success',
					'code' 	=> 200,
					'user' =>	$user
				);
			}
		}else{
			$data = array(
				'status' => 'error',
				'code' 	=> 404,
				'message' => 'Usuario no encontado'
			);
		}
		return response()->json($data, $data['code']);
	}

	public function getAll(Request $request){
		$jwtAuth = new \JwtAuth;
		$jwt = $request->header('Authorization', false);
		$chekToken = $jwtAuth->checkToken($jwt);

		if(!empty($chekToken)){
			$users = tblUsuario::all();
			$data = array(
				'status' => 'success',
				'code' => 200,
				'users' => $users
			);
		}else{
			$data = array(
				'status' => 'eror',
				'code' => 404,
				'message' => 'No se encontraron usuarios'
			);
		}

		return response()->json($data, $data['code']);

	}
}