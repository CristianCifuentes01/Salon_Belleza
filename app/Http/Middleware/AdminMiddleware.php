<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Verificar que el usuario autenticado sea administrador.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}
