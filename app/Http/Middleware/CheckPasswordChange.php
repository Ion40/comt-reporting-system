<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPasswordChange
{
    /**
     * Maneja una solicitud entrante.
     * * * Si el usuario está autenticado y tiene marcado 'require_password_change',
     * * lo redirige a la vista de cambio de contraseña a menos que ya esté allí.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Verificamos si el usuario debe cambiar su clave
            if ($user->require_password_change) {
                // Permitimos el acceso si la ruta es la de cambiar contraseña o cerrar sesión
                if (!$request->is('profile/change-password*') && !$request->is('logout')) {
                    return redirect()->route('password.force.change')
                        ->with('info', 'Por seguridad, debe actualizar su contraseña antes de continuar.');
                }
            }
        }
        return $next($request);
    }
}
