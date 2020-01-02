<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Negocio;
use Mockery\Undefined;

class NegocioController extends Controller
{
    public function all(){
        $negocio = Negocio::all()->load('users')->load('productos');

        $data = array(
            'status' => 'success',
            'code' => 200,
            'negocio' => $negocio
        );

        return response()->json($data, $data['code']);
    }

    public function checkToken($request){
        $jwtAuth = new \JwtAuth();
        $jwt = $request->header('Authorization');
        try{
            $user = $jwtAuth->checkToken($jwt, true);
        }catch(Undefined $e){
            $data = array(
                'status' => 'error',
                'code' => 500,
                'error' => $e
            );
        }
        return $user;
    }

    public function create(Request $request){
        $json = $request->input('json', false);
        $params = json_decode($json, true);
        
        $params['descripcion'] = isset($params['descripcion']) ? $params['descripcion'] : null;
        $params['ciudad'] = empty($params['ciudad'] == '') ? $params['ciudad'] : null;

        if(!empty($params)){
            $validator = \Validator::make($params, [
                'nit' => 'required|numeric',
                'nombre' => 'required|string|max:100',
                'direccion' => 'required',
                'celular' => 'required|numeric',
                'tel_fijo' => 'required|numeric',
                'correo' => 'required|email',
                'administrador' => 'required|numeric',
                'descripcion' => 'nullable'
            ]);
            if($validator->fails()){
                $data = array(
                    'status' => 'error',
                    'code' => 500,
                    'error' => $validator->errors()
                );
            }else{
                $negocio = new Negocio();
                $negocio->nit = $params['nit'];
                $negocio->nombre = $params['nombre'];
                $negocio->direccion = $params['direccion'];
                $negocio->celular = $params['celular'];
                $negocio->tel_fijo = $params['tel_fijo'];
                $negocio->correo = $params['correo'];
                $negocio->administrador = $params['administrador'];
                $negocio->ciudad = $params['ciudad'];
                $negocio->descripcion = $params['descripcion'];
    
                $negocio->save();
    
                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Datos almacenados correctamente',
                    'negocio' => $negocio
                );
            }
        }else{
            $data = array(
                'status' => 'error',
                'code' => 500,
                'message' => 'Error al guardar los datos'
            );
        }
        return response()->json($data, $data['code']);
    }

    public function update(Request $request, $nit)
    {
        $json = $request->input('json', null);
        $params = json_decode($json, true);

        if(!empty($params)){
            $validate = \Validator::make($params, [
                'nombre' => 'required|string|max:100',
                'direccion' => 'required',
                'celular' => 'required|numeric',
                'tel_fijo' => 'required|numeric',
                'correo' => 'required|email',
                'administrador' => 'required|numeric',
                'descripcion' => 'nullable'
            ]);
    
            if($validate->fails()){
                $data = array(
                    'status' => 'error',
                    'code' => 500,
                    'error' => $validate->errors()
                );
            }else{
                unset($params['nit']);
                unset($params['created_at']);   

                $params['descripcion'] = isset($params['descripcion']) ? $params['descripcion'] : null;
                $params['ciudad'] = empty($params['ciudad'] == '') ? $params['ciudad'] : null;
                
                $negocio_update = Negocio::where('nit', $nit)->update($params);
    
                if($negocio_update){
                    $data = array(
                        'status' => 'success',
                        'code' => 200,
                        'negocio' => $params
                    );
                }else{
                    $data = array(
                        'status' => 'error',
                        'code' => 404,
                        'message' => 'Error al actualizar los datos'
                    );
                }
            }
        }

        return response()->json($data, $data['code']);
    }
    
    public function destroy($nit){
        $negocio = Negocio::where('nit', $nit)->first();
        if(!empty($negocio)){
            $negocio->delete();

            $data = array(
                'status' => 'success',
                'code' => 200,
                'message' => 'Datos Eliminados correctamente'
            );
        }else{
            $data = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'Error al eliminar los datos'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function getOne($nit){
        $negocio = Negocio::where('nit', $nit)->first();
        if(!empty($negocio)){
            $negocio->load('users');
            $data = array(
                'status' => 'success',
                'code' => 200,
                'negocio' => $negocio
            );
        }else{
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'Negocio no encontrado'
            );
        }

        return response()->json($data, $data['code']);
    }
}
