import InertiaProgress from "@/scripts/progress";
import VueCookies from 'vue-cookies'

import {createApp, h, watch} from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { resolvePageComponent } from 'vite-plugin-laravel/inertia';
import {loadLocaleMessages, setI18nLanguage, setupI18n} from "@/scripts/i18n";

declare global {
    interface Window {
        route: any;
    }
}

//axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(name, import.meta.glob('../views/pages/**/*.vue')),
    setup({el, app, props, plugin}) {
        const vue = createApp({render: () => h(app, props)});
        const i18n = setupI18n({
            availableLocales: ['en', 'ko'],
            locale: props.initialPage.props.locale as string,
            fallbackLocale: 'en',
        })
        vue.use(plugin).use(i18n).use(VueCookies).mixin({methods: {route: window.route}}).mount(el);


        loadLocaleMessages(i18n, props.initialPage.props.locale as string).then();


    },
}).then();

InertiaProgress.init({color: '#0ea5e9'});
