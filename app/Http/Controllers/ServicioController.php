<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Servicio;

class ServicioController extends Controller
{
    public function index(){
        $servicios = Servicio::all()->load('productos')->load('user')->load('domiciliario');
        //var_dump($servicios[0]->user[0]);die();

        if(!empty($servicios)){
            $data = array(
                'status' => 'success',
                'code' => 200,
                'servicios' => $servicios
            );
        }else{
            $data = array(
                'status' => 'error',
                'code' => 404,
                'mensaje' => 'No hay servicios'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function storage(Request $request){
        $json = $request->input('json', null);
        $params = json_decode($json, true);

        if(!empty($params)){
            $validate = \Validator($params, [
                'cliente' => 'required|numeric',
                'domiciliario' => 'required|numeric',
                'fecha' => 'required|date',
                'hora' => 'required',
                'estado' => 'required|numeric'
            ]);
    
            if($validate->fails()){
                $data = array(
                    'status' => 'error',
                    'code' => 500,
                    'error' => $validate->errors()
                );
            }else{
                $servicio = new Servicio();
    
                $servicio->cliente = $params['cliente'];
                $servicio->domiciliario = $params['domiciliario'];
                $servicio->fecha = $params['fecha'];
                $servicio->hora = $params['hora'];
                $servicio->estado = $params['estado'];
    
                $servicio->save();
    
                if($servicio){
                    $data = array(
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Datos almacenados correctamente',
                        'servicio' => $servicio
                    );
                }else{
                    $data = array(
                        'status' => 'error',
                        'code' => 500,
                        'message' => 'No se pudo almacenar el servicio, intente mas tarde'
                    );
                }
            }
        }else{
            $data = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'Error al intentar enviar los datos, verifique'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function update(Request $request, $codigo){
        $json = $request->input('json', null);
        $params = json_decode($json, true);

        if(!empty($params)){
            $validate = \Validator($params, [
                'cliente' => 'required|numeric',
                'domiciliario' => 'required|numeric',
                'fecha' => 'required|date',
                'hora' => 'required',
                'estado' => 'required|numeric'
            ]);
    
            if($validate->fails()){
                $data = array(
                    'status' => 'error',
                    'code' => 500,
                    'error' => $validate->errors()
                );
            }else{
                $updated = Servicio::where('codigo', $codigo)->update($params);
    
                if($updated){
                    $data = array(
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Datos actualizados correctamente',
                        'servicio' => $updated
                    );
                }else{
                    $data = array(
                        'status' => 'error',
                        'code' => 500,
                        'message' => 'No se pudo actualizar el servicio, intente mas tarde'
                    );
                }
            }
        }else{
            $data = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'Error al enviar los datos'
            );
        }
        return response()->json($data, $data['code']);
    }

    public function destroy($codigo){
        if(isset($codigo)){
            //Borrar datos tabla pivote (pendiente)

            $deleted = Servicio::where('codigo', $codigo)->delete();

            if($deleted){
                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'Datos Eliminados correctamente'
                );
            }else{
                $data = array(
                    'status' => 'error',
                    'code' => 500,
                    'message' => 'No se pudo eliminar el servicio, intente mas tarde'
                );
            }
        }else{
            $data = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'Error al enviar los datos'
            );
        }
        return response()->json($data, $data['code']);
    }

    public function getOne($codigo){
            $servicio = Servicio::where('codigo', $codigo)->first()->load('productos')->load('user')->load('domiciliario');

            if($servicio){
                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'servicio' => $servicio
                );
            }else{
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'Servicio no encontrado'
                );
            }
        return response()->json($data, $data['code']);
    }
}
