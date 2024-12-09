import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/app.css',
        'resources/js/app.js',
      ],
      // Removed refresh for production
    }),
  ],
  build: {
    // Example of production optimizations
    outDir: 'public/build', // Ensure this points to the correct output directory
    manifest: true, // Generate the manifest file for Laravel to find the assets
    rollupOptions: {
      // Additional optimizations or code-splitting can go here
    },
  },
});
