import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0', // Permite conexiones externas al servidor de Vite
        hmr: {
            host: '192.168.1.203', // La IP de tu PC
        },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
