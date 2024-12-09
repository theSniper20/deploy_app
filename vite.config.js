import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteCommonjs } from "@originjs/vite-plugin-commonjs";

export default defineConfig({
    plugins: [
        viteCommonjs(),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
        }),
    ],

});
