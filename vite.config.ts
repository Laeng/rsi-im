import { defineConfig } from 'vite'
import tailwindcss from 'tailwindcss'
import autoprefixer from 'autoprefixer'
import laravel from 'vite-plugin-laravel'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    server: {
        watch: {
            usePolling: true
        },
        host: '0.0.0.0'
    },
	plugins: [
		//inertia(),
		vue(),
		laravel({
			postcss: [
				tailwindcss(),
				autoprefixer(),
			],
		}),
	],
})
