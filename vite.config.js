import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

const allowedDevOrigins = [
    /^https:\/\/tradiz\.test$/,
    /^https:\/\/tradiz-vite\.test$/,
];

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        origin: 'https://tradiz-vite.test',
        hmr: {
            host: 'tradiz-vite.test',
            protocol: 'wss',
        },
        cors: {
            origin: allowedDevOrigins,
        },
        allowedHosts: ['tradiz-vite.test', 'tradiz.test'],
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
