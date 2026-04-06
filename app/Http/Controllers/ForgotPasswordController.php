<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function index()
    {
        return view('auth.recover-pass');
    }

    function _comtechMailsOnly($attribute, $value, $fail) {
        if (!Str::endsWith(Str::lower($value), '@comtech.com.ni')) {
            $fail('Solo se permiten correos corporativos de Comtech.');
        }
    }

    public function sendResetToken(Request $request)
    {
        // 1. Validación inicial: Formato, existencia en DB y dominio específico
        /*$request->validate([
            'email' => [
                'required',
                'email',
                'exists:users,email',
                function ($attribute, $value, $fail) {
                    if (!str_ends_with(strtolower($value), '@comtech.com.ni')) {
                        $fail('El correo debe pertenecer al dominio @comtech.com.ni');
                    }
                },
            ]
        ], [
            'email.exists' => 'Si el correo está registrado, recibirás un enlace en breve.',
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Ingrese un formato de correo válido.'
        ]);*/

        try {
            // Buscamos al usuario antes de iniciar la transacción
            $user = User::where('email', $request->email)->first();

            // Doble verificación de seguridad
            if (!$user) {
                return response()->json([
                    'status'  => 'success',
                    //'code'    => 404,
                    'message' => 'Si el correo está registrado, recibirás un enlace en breve.'
                ], 404);
            }

            DB::beginTransaction();

            $token = Str::random(64);

            PasswordResetToken::where('email', $user->email)
                ->where('is_used', false)
                ->update(['is_used' => true]); // Invalida los intentos anteriores


            // 2. Guardar en la tabla password_reset_tokens
            PasswordResetToken::create([
                'user_id'    => $user->id,
                'email'      => $user->email,
                'token'      => $token,
                'expires_at' => now()->addMinutes(20),
                'is_used'    => false
            ]);

            // 3. Enviar el correo
            // IMPORTANTE: Asegúrate de que el orden en ResetPasswordMail($token, $user) coincida aquí
            Mail::to($user->email)->send(new ResetPasswordMail($token, $user));

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'code'    => 200,
                'message' => 'Hemos enviado un enlace a tu correo para restablecer la contraseña.'
            ]);

        } catch (\Exception $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            Log::error("Error en sendResetToken: " . $e->getMessage());

            return response()->json([
                'status'  => 'error',
                'code'    => 500,
                'message' => 'Lo sentimos, hubo un problema técnico. Por favor, intenta más tarde.'
            ], 500);
        }
    }

    public function validateToken(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email'
        ]);

        $resetRecord = PasswordResetToken::where('token', $request->token)
            ->where('email', $request->email)
            ->where('is_used', false)
            // CAMBIO: La fecha de expiración debe ser MAYOR a "ahora" para ser válido
            ->where('expires_at', '>', now())
            // CAMBIO: Aseguramos que valide el registro más reciente creado
            ->latest()
            ->first();

        if (!$resetRecord) {
            return response()->json([
                'success' => false,
                'message' => 'El token es inválido, ya fue usado o ha expirado.'
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Token válido.'
        ]);
    }

    public function resetPassword(Request $request) {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:6|confirmed'
        ]);

        // Volvemos a validar por seguridad antes de actualizar
        $resetRecord = PasswordResetToken::where('token', $request->token)
            ->where('email', $request->email)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$resetRecord) {
            return response()->json(['success' => false ,'message' => 'El token es inválido, ya fue usado o ha expirado.'], 422);
        }

        // 1. Actualizamos el usuario
        $user = User::findOrFail($resetRecord->user_id);
        $user->password = Hash::make($request->password);
        $user->save();

        // 2. Marcamos el token como USADO
        $resetRecord->is_used = true;
        $resetRecord->save();

        // Opcional: Eliminar otros tokens viejos de este usuario para limpiar la tabla
        PasswordResetToken::where('user_id', $user->id)->where('is_used', true)->delete();

        //después de guardar la nueva contraseña:
        Auth::logoutOtherDevices($request->password);

        return response()->json(['success' => true, 'message' => 'Contraseña actualizada con éxito.']);
    }
}
