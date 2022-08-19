import axios from 'axios';
import InertiaProgress from "@/scripts/progress";
import lodash from 'lodash';

import { createApp, h } from 'vue';
import { createI18n } from 'vue-i18n';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { resolvePageComponent } from 'vite-plugin-laravel/inertia';

declare global {
    interface Window {
        _: any,
        axios: any,
        route: any;
    }
}

window._ = lodash;
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';
const i18n = createI18n({
    //locale: 'ko',
    fallbackLocale: 'en',

})

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
	resolve: (name) => resolvePageComponent(name, import.meta.glob('../views/pages/**/*.vue')),
	setup({ el, app, props, plugin }) {
        createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(i18n)
            .mixin({ methods: { route: window.route } })
            .mount(el);
	},
})

InertiaProgress.init({color: '#4f46e5'});
