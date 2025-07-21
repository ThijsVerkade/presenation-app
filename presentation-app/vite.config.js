import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    test: {
        environment: 'jsdom',
        globals: true,
        coverage: {
            reporter: ['text', 'json', 'html'],
            exclude: [
                'node_modules/**',
                'vendor/**',
                'resources/js/stories/**',
                'public/**',
                '**/*.config.js',
                '.storybook/**',
                'js/tests/**'
            ]
        }
    },
    define: {
        'process.env': process.env
    },
    plugins: [
        laravel({
            input: ['resources/js/app.ts', 'resources/sass/app.scss'],
            refresh: true
        }),
        vue({
            template: {
                transformAssetUrls: {
                    // The Vue plugin will re-write asset URLs, when referenced
                    // in Single File Components, to point to the Laravel web
                    // server. Setting this to `null` allows the Laravel plugin
                    // to instead re-write asset URLs to point to the Vite
                    // server instead.
                    base: null,

                    // The Vue plugin will parse absolute URLs and treat them
                    // as absolute paths to files on disk. Setting this to
                    // `false` will leave absolute URLs un-touched so they can
                    // reference assets in the public directory as expected.
                    includeAbsolute: false
                }
            }
        })
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources'),
            '@i18n': path.resolve(__dirname, 'resources/i18n'),
            '@base': path.resolve(__dirname, 'resources/js'),
            '@layouts': path.resolve(__dirname, 'resources/js/layouts'),
            '@pages': path.resolve(__dirname, 'resources/js/pages'),
            '@components': path.resolve(__dirname, 'resources/js/components'),
            '@helpers': path.resolve(__dirname, 'resources/js/helpers'),
            '@stores': path.resolve(__dirname, 'resources/js/stores'),
            '@types': path.resolve(__dirname, 'resources/js/types'),
            '@modules': path.resolve(__dirname, 'resources/js/modules'),
            '@interfaces': path.resolve(__dirname, 'resources/js/types'),
            '@composables': path.resolve(__dirname, 'resources/js/composables'),
            '@apps': path.resolve(__dirname, 'resources/js/modules/apps')
        }
    },
    css: {
        preprocessorOptions: {
            scss: {
                api: 'modern-compiler' // or "modern"
            }
        }
    }
});
