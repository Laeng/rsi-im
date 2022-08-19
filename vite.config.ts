import { defineConfig } from 'vite'
import tailwindcss from 'tailwindcss'
import autoprefixer from 'autoprefixer'
import laravel from 'vite-plugin-laravel'
import vue from '@vitejs/plugin-vue'
import { resolve, dirname } from 'node:path'
import { fileURLToPath } from 'url'
import VueI18nPlugin from '@intlify/unplugin-vue-i18n/vite'

export default defineConfig({
    server: {
        watch: {
            usePolling: true
        },
        host: '0.0.0.0'
    },
	plugins: [
		vue(),
		laravel({
			postcss: [
                tailwindcss(),
				autoprefixer()
			],
		}),
        VueI18nPlugin({
            /* options */
            // locale messages resource pre-compile option
            include: resolve(dirname(fileURLToPath(import.meta.url)), './resources/scripts/locales/**'),
        }),
	],
})
