<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SecurityController extends Controller
{
    public function showChangePassword()
    {
        return view('auth.force-change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'], // Valida contra el hash de la DB
            'password' => ['required', 'confirmed', 'min:6', 'different:current_password'],
        ], [
            'current_password.current_password' => 'La contraseña temporal ingresada es incorrecta.',
            'password.different' => 'La nueva contraseña debe ser diferente a la contraseña temporal.',
            'password.confirmed' => 'La nueva contraseña y su confirmación no coinciden.',
            'password.min' => 'La nueva contraseña debe tener al menos 6 caracteres.'
        ]);

        $user = Auth::user();

        // Actualizamos contraseña y desactivamos el flag de cambio obligatorio
        $user->update([
            'password' => Hash::make($request->password),
            'require_password_change' => false,
        ]);

        return redirect()->route('users.profile')
            ->with('status', 'Contraseña actualizada correctamente. Ya puede navegar libremente.');
    }
}
