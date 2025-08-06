import { defineConfig } from 'vite';
import legacy from '@vitejs/plugin-legacy';

export default defineConfig({
    plugins: [
        legacy({
            targets: ['defaults', 'not IE 11']
        })
    ],
    publicDir: false, // Disable public directory copying to avoid conflicts
    build: {
        outDir: 'dist',
        manifest: true,
        rollupOptions: {
            input: {
                admin: 'resources/js/admin.js',
                editor: 'resources/js/editor.js',
                frontend: 'resources/js/frontend.js',
                style: 'resources/scss/style.scss'
            },
            output: {
                entryFileNames: 'js/[name].[hash].js',
                chunkFileNames: 'js/[name].[hash].js',
                assetFileNames: (assetInfo) => {
                    const info = assetInfo.name.split('.');
                    const ext = info[info.length - 1];
                    if (/png|jpe?g|svg|gif|tiff|bmp|ico/i.test(ext)) {
                        return `images/[name].[hash][extname]`;
                    }
                    if (/css/i.test(ext)) {
                        return `css/[name].[hash][extname]`;
                    }
                    return `assets/[name].[hash][extname]`;
                }
            }
        }
    },
    server: {
        watch: {
            usePolling: true
        }
    }
});
