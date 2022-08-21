//import axios from 'axios';
import InertiaProgress from "@/scripts/progress";
//import lodash from 'lodash';

import { createApp, h } from 'vue';
import { createI18n } from 'vue-i18n';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { resolvePageComponent } from 'vite-plugin-laravel/inertia';

declare global {
    interface Window {
        route: any;
    }
}

//axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';
const i18n = createI18n({
    //locale: 'ko',
    fallbackLocale: 'en',

})

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
	resolve: (name) => resolvePageComponent(name, import.meta.glob('../views/pages/**/*.vue')),
	setup({ el, app, props, plugin }) {
        const vue = createApp({ render: () => h(app, props) });

        vue.use(plugin).use(i18n).mixin({ methods: { route: window.route } }).mount(el);
        //vue.provide('lodash', lodash);
        //vue.provide('axios', axios);
    },
})

InertiaProgress.init({color: '#0ea5e9'});
