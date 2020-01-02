<?php

namespace App\Http\Middleware;

use Closure;
//Para que el middleware funcione hay que registrarlo en la configuración de laravel (kernel)
class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $jwtAuth = new \JwtAuth();
        $jwt = $request->header('Authorization');
        $checkToken = $jwtAuth->checkToken($jwt);

        if($checkToken){
            return $next($request);
        }else{
            $data = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'El usuario no está identificado'
            );
            return response()->json($data, $data['code']);
        }

        
    }
}
