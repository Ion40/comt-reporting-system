<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Esto debe llamarse en el archivo DatabaseSeeder.php
     */

    /**
     * Si quieres crear la tabla y llenarla de una vez:
     *
     *   php artisan migrate --seed
     *
     * Si la tabla ya existe y solo quieres ejecutar el seeder:
     *
     *   php artisan db:seed --class=EstadosSeeder
     *
     */

    public function run(): void
    {
        $estados = [
            ['nombre' => 'ACTIVO'],
            ['nombre' => 'INACTIVO'],
            ['nombre' => 'ANULADO'],
            ['nombre' => 'APROBADO'],
            ['nombre' => 'RECHAZADO'],
        ];

        foreach ($estados as $nombre) {
            // Verificar si el estado ya existe
            $exists = DB::table('estados')->where('nombre', $nombre)->exists();

            if (!$exists) {
                DB::table('estados')->insert(['nombre' => $nombre]);
            }
        }
    }
}
