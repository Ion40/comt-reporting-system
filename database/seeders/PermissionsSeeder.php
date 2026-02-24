<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'Visualizar'],
            ['name' => 'Crear'],
            ['name' => 'Actualizar'],
            ['name' => 'Eliminar'],
            ['name' => 'Imprimir'],
            ['name' => 'Anular'],
            ['name' => 'Asignar'],
            ['name' => 'Reimprimir']
        ];

        foreach ($permissions as $name) {
            // Verificar si el estado ya existe
            $exists = DB::table('permissions')->where('name', $name)->exists();

            if (!$exists) {
                DB::table('permissions')->insert(['name' => $name]);
            }
        }
    }
}
