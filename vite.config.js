import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        outDir: 'public/build',
        assetsDir: 'assets',
        manifest: true,
        rollupOptions: {
            input: {
                app: 'resources/js/app.js',
                appcss: 'resources/css/app.css',
            },
        },
        emptyOutDir: true,
    },
    server: {
        host: '127.0.0.1',
        port: 5173,
    },
});
