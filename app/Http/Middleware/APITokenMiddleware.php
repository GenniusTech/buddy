<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class APITokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('token');
        $key = '123'; // Substitua por sua chave desejada

        if ($token !== $key) {
            return response()->json(['ACESSO' => 'Acesso Negado'], 401);
        }

        return $next($request);
    }
}
