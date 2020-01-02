<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminat\Http\Response;
use App\Producto;

class ProductoController extends Controller
{
    public function index(){
        $productos = Producto::all()->load('negocios')->load('categorias');
        $data = array(
            'status' => 'success',
            'code' => 200,
            'producto' => $productos
        );

        return response()->json($data, $data['code']);
    }

    public function create(Request $request){
        $json = $request->input('json', null);
        $params = json_decode($json, true);

        if($params){

            $params['tipo_producto'] = (isset($params['tipo_producto']) ? $params['tipo_producto'] : null);

            $validate = \Validator::make($params, [
                'nombre' => 'required',
                'descripcion' => 'required',
                'estado' => 'required|numeric',
                'categoria' => 'required|numeric'
            ]);
    
            if($validate->fails()){
                $data = array(
                    'status' => 'error',
                    'code' => 500,
                    'error' => $validate->errors()
                );
            }else{
                $producto = new Producto();
                $producto->nombre = $params['nombre'];
                $producto->descripcion = $params['descripcion'];
                $producto->estado = $params['estado'];
                $producto->categoria = $params['categoria'];
                $producto->tipo_producto = $params['tipo_producto'];
    
                $producto->save();

                $data = array(
                    'status' => 'sucess',
                    'code' => 200,
                    'message' => 'Datos almacenados correctamente',
                    'producto' => $params
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

    public function update(Request $request, $id){
        $json = $request->input('json', null);
        $params = json_decode($json, true);

        if($params){
            $validate = \Validator::make($params, [
                'nombre' => 'required',
                'descripcion' => 'required',
                'estado' => 'required|numeric',
                'categoria' => 'required|numeric'
            ]);

            if($validate->fails()){
                $data = array(
                    'status' => 'error',
                    'code' => 500,
                    'error' => $validate->errors()
                );
            }else{
                unset($params['codigo']);
                unset($params['created_at']);

                $params['tipo_producto'] = (isset($params['tipo_producto']) ? $params['tipo_producto'] : null);

                $updated = Producto::where('codigo', $id)->update($params);

                if($updated){
                    $data = array(
                        'status' => 'sucess',
                        'code' => 200,
                        'message' => 'Datos actualizados correctamente',
                        'producto' => $params
                    );
                }else{
                    $data = array(
                        'status' => 'error',
                        'code' => 400,
                        'message' => 'Error al actualizar el producto'
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

    public function destroy($codigo, $nit = null){
        if($nit){
            $producto = Producto::where('codigo', $codigo)->first();
            $producto->negocios()->detach($nit);
        }
        if($producto){
            Producto::where('codigo', $codigo)->delete();
            $data = array(
                'status' => 'success',
                'code' => 200,
                'producto' => $producto
            );
        }else{
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'Producto no encontrado'
            );
        }
        return response()->json($data, $data['code']);
    }
}