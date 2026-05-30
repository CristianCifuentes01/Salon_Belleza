<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken() ?: $request->header('X-API-TOKEN') ?: $request->query('api_token');

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token de API requerido.',
            ], 401);
        }

        $user = User::where('api_token', $token)->first();

        if (!$user || !$user->confirmado) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token de API invalido o usuario inactivo.',
            ], 401);
        }

        Auth::setUser($user);

        return $next($request);
    }
}
