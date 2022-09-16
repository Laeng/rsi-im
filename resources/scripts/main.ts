import InertiaProgress from "@/scripts/progress";
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { resolvePageComponent } from 'vite-plugin-laravel/inertia';
import {loadLocaleMessages, setupI18n} from "@/scripts/i18n";

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
        const availableLocales = ['en', 'ko'];
        const vue = createApp({render: () => h(app, props)});
        const i18n = setupI18n({
            availableLocales: availableLocales,
            locale: props.initialPage.props.locale as string,
            fallbackLocale: 'en',
        })
        vue.use(plugin);
        vue.use(i18n);
        vue.mixin({methods: {route: window.route}});
        vue.mount(el);

        availableLocales.forEach((local) => {
            loadLocaleMessages(i18n, local).then();
        });
    },
}).then();

InertiaProgress.init({color: '#0ea5e9'});
