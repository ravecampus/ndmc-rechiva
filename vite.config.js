import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'
import copy from "rollup-plugin-copy";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/css/app.css', 
                'resources/js/app.js'
            ],
            refresh: true,
        }),
        vue(),
        copy({
            targets: [
              {
                src: "node_modules/pspdfkit/dist/pspdfkit-lib",
                dest: "public/build/assets",
              },
            ],
            hook: "buildStart",
          }),
    ],
    build: {
        rollupOptions: {
            output:{
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        return id.toString().split('node_modules/')[1].split('/')[0].toString();
                    }
                }
            }
        }
    }
});
