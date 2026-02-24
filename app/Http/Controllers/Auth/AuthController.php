<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function dashboard() {
        return view('dashboard');
    }

    public function login(LoginRequest $request)
    {
        $key = Str::lower($request->input('correo')) . '|' . $request->ip();

        // Verificar si el usuario ha excedido los intentos de inicio de sesión
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'correo' => "Demasiados intentos de inicio de sesión. Inténtalo de nuevo en $seconds segundos."
            ])->withInput();
        }

        //Consultar a vista view_usuarios

        $user = User::where('email', $request->correo)->first();
        if (!$user) {
            RateLimiter::hit($key, 60); // incrementar el contador de fallos
            return back()->withErrors([
                'correo' => 'Las credenciales proporcionadas no son correctas.',
            ])->withInput($request->only('correo'));
        }

        // Verificar password con bcrypt
        if (!Hash::check($request->password, $user->password)) {
            RateLimiter::hit($key, 60); // incrementar el contador de fallos
            return back()->withErrors([
                'password' => 'Contraseña incorrecta.'
            ])->withInput();
        }

        // Login exitoso: reiniciar contador de intentos
        RateLimiter::clear($key);

        // Iniciar sesión
        Auth::login($user);

        // Regenerar sesión por seguridad
        $request->session()->regenerate();

        // Redirigir a la ruta deseada después del login
        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
