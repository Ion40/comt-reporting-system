<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        return view('permission.index');
    }

    //TODO: cambiar a controlador de Reportes nombrado segun el modulo
    public function costos_import()
    {
        return view('permission.costos_import');
    }


    //Vista de "Bajo Mantenimiento"
    public function underMaintenance()
    {
        return view('bajo-mantenimiento');
    }
}
