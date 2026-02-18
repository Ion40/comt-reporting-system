<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class AppStart extends Command
{
    protected $signature = 'app:start {--build : Fuerza build de frontend}';
    protected $description = 'Inicia la aplicación según el entorno (dev / prod)';

    public function handle()
    {
        $env = app()->environment();
        $host = env('DEV_SERVER_HOST', '127.0.0.1');
        $port = env('DEV_SERVER_PORT', '8000');

        $this->info("🚀 Iniciando aplicación en entorno: {$env}");
        $this->info("");
        $processes = [];

        /* ===========================
         | FRONTEND
         |===========================*/
        if ($env === 'local') {
            $this->info('🟡 Frontend: npm run dev');
            $processes[] = new Process(['npm', 'run', 'dev']);
        } else {
            if ($this->option('build')) {
                $this->info('🟢 Frontend: npm run build');
                $this->runBlocking(['npm', 'run', 'build']);
            }
        }

        /* ===========================
         | REVERB (WebSockets)
         |===========================*/
        $this->info('🟢 Reverb iniciado');
        $processes[] = new Process(['php', 'artisan', 'reverb:start', '--port', env('REVERB_PORT'), '--host', '0.0.0.0']);

        /* ===========================
         | SERVIDOR HTTP (SOLO DEV)
         |===========================*/
        if ($env === 'local') {
            $this->info("🟢 Laravel server iniciado:");
            $this->line("🌐 URL: [http://{$host}:{$port}]");

            $processes[] = new Process(['php', 'artisan', 'serve', '--host', $host, '--port', $port]);
        } else {
            $this->warn('⚠️ Producción: Usa Nginx / Apache + PHP-FPM');
        }

        /* ===========================
         | EJECUTAR PROCESOS
         |===========================*/
        foreach ($processes as $process) {
            $process->setTimeout(null);
            $process->start(function ($type, $buffer) {
                echo $buffer;
            });
        }

        $this->info('✅ Aplicación en ejecución');
        $this->info('⛔ CTRL+C para detener');

        while (true) {
            sleep(1);
        }
    }

    /**
     * Ejecuta procesos bloqueantes (ej: npm run build)
     */
    private function runBlocking(array $command)
    {
        $process = new Process($command);
        $process->setTimeout(null);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        if (!$process->isSuccessful()) {
            $this->error('❌ Error ejecutando: ' . implode(' ', $command));
            exit(1);
        }
    }
}
