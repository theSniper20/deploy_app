import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteCommonjs } from "@originjs/vite-plugin-commonjs";

export default defineConfig({
server: {
    host: 'localhost',
    port: 5173,
  },
    plugins: [
        viteCommonjs(),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    optimizeDeps: {
        exclude: ["@cornerstonejs/dicom-image-loader"],
        include: ["dicom-parser"],
    },
});