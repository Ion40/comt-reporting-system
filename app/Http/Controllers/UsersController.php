<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // 1. Iniciamos la consulta (sin ejecutar el get())
            $query = User::leftJoin('estados', 'users.id_estado', '=', 'estados.id')
                ->select([
                    'users.*',
                    'estados.nombre as estado_nombre' // Alias para mostrar el nombre
                ]);


            // 2. Devolvemos la respuesta procesada por el motor de DataTables
            return DataTables::of($query)
                ->addColumn('status', function ($user) {
                    // Mapeo de colores según el nombre o ID del estado
                    $estadoNom = $user->estado_nombre ?? 'N/A';

                    $color = match(trim(strtoupper($estadoNom))) {
                        'ACTIVO', 'APROBADO' => 'success',
                        'INACTIVO', 'ANULADO', 'RECHAZADO' => 'danger',
                        'REPORTE EN USO'     => 'info',
                        'REPORTE INACTIVO'   => 'dark',
                        default              => 'secondary',
                    };

                    return '<span class="badge bg-' . $color . '-subtle text-' . $color . ' fs-12 p-1">' . $estadoNom . '</span>';
                })
                ->addColumn('actions', function ($user) {
                    return '
                        <div class="hstack gap-1 justify-content-center">
                            <!--<a href="#" class="btn btn-soft-primary btn-icon btn-sm rounded-circle"><i class="ti ti-eye"></i></a>-->
                            <button href="javascript:void(0)" data-iduser="' . $user->id . '" class="btn btn-soft-primary btn-icon btn-sm rounded-circle btn-edit">
                                <i class="ti ti-edit fs-16 btn-edit" data-iduser="' . $user->id . '"></i>
                             </button>
                            <a href="javascript:void(0)" data-iduser="' . $user->id . '" class="btn btn-soft-danger btn-icon btn-sm rounded-circle btn-delete">
                                <i class="ti ti-trash fs-16 btn-delete" data-iduser="' . $user->id . '"></i>
                             </a>
                        </div>';
                })
                ->editColumn("nombre", function ($user) {
                    return '
                        <div class="d-flex align-items-center gap-2">
                            ' . $this->renderAvatar($user->nombre, $user->id) . '
                            <div>
                                <h6 class="fs-14 mb-0">' . $user->nombre . '</h6>
                            </div>
                        </div>';
                })
                ->editColumn("email", function ($user) {
                    return '
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar-sm">
                            <div class="h-100 w-100 rounded-circle bg-secondary-subtle text-secondary d-flex align-items-center justify-content-center border">
                                <i class="ti ti-mail fs-16"></i>
                            </div>
                        </div>
                        <span class="fs-">' . $user->email . '</span>
                    </div>';
                })
                ->editColumn('created_at', function ($user) {
                    return $user->created_at ? $user->created_at->format('d M Y') : '---';
                })
                ->editColumn('updated_at', function ($user) {
                    return $user->updated_at ? $user->updated_at->format('d M Y') : '---';
                })
                ->rawColumns(['nombre', 'email', 'status', 'actions']) // Renderizar HTML
                ->make(true);
        }

        return view('users.index');
    }

    public function userProfile(){
        return view('users.user-profile');
    }

    private function renderAvatar($text, $id, $showIcon = false)
    {
        // 1. Paleta de colores (hemos quitado success/danger y sumado purple)
        $colors = ['primary', 'info', 'warning', 'success', 'danger', 'secondary'];

        // OPCIÓN A: Totalmente al azar (cambia cada vez)
        // $colorClass = $colors[array_rand($colors)];

        // OPCIÓN B: Azar determinista (aleatorio pero constante por usuario - RECOMENDADO)
        $colorClass = $colors[$id % count($colors)];
        //$colorClass = $colors[0];

        // 2. Lógica de iniciales (Toma las primeras letras de las dos primeras palabras)
        $words = explode(' ', trim($text));
        $initials = '';
        if (count($words) >= 2) {
            $initials = substr($words[0], 0, 1) . substr($words[1], 0, 1);
        } else {
            $initials = substr($text ?? 'U', 0, 2);
        }
        $initials = Str::upper($initials);

        $content = $showIcon
            ? '<i class="ti ti-mail fs-16"></i>'
            : $initials;

        return '
        <div class="avatar-sm flex-shrink-0">
            <div class="h-100 w-100 rounded-circle bg-' . $colorClass . ' text-white d-flex align-items-center justify-content-center fw-bold shadow-sm" style="font-size: 0.75rem;">
                ' . $content . '
            </div>
        </div>';
    }

    public function storeOrUpdate(Request $request)
    {
        try {
            $id_user_input = $request->input('id_user_input');
            $user_name_input = $request->input('user_name_input');
            $user_mail_input = $request->input('user_mail_input');
            $password_input = $request->input('password_input');

            //validar si ya existe un correo igual
            if (!$id_user_input) {
                $exists = DB::table("users")
                    ->where("email", "=", $user_mail_input)
                    ->exists();

                if ($exists) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El correo que intentas registrar ya existe',
                        'errors' => ['user_mail_input' => ['El correo que intentas registrar ya existe']]
                    ], 422);
                }
            }

            //ejecutar guardado o actualizado dentro de una transacción
            $result = DB::transaction(function () use ($password_input, $id_user_input, $user_name_input, $user_mail_input) {
                $data = [
                    'nombre' => $user_name_input,
                    'email' => $user_mail_input,
                ];

                if ($id_user_input) {
                    //ACTUALIZAR
                    $data['updated_at'] = now();
                    DB::table("users")->where("id", $id_user_input)->update($data);
                    $message = "Usuario actualizado correctamente";
                } else {
                    $data["id_estado"] = 1;
                    $data["require_password_change"] = true;
                    $data['created_at'] = now();
                    //insertar contraseña
                    $data['password'] = Hash::make($password_input);
                    DB::table("users")->insert($data);
                    $message = "Usuario registrado correctamente";
                }

                return $message;
            });

            return response()->json([
                'success' => true,
                'message' => $result,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error en el servidor: '.$e->getMessage(),
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
