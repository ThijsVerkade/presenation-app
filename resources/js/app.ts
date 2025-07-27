import './bootstrap';
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import {i18n} from "./i18n";
import { ZiggyVue } from 'ziggy-js';
import tooltipDirective from './directives/v-tooltip';

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./pages/**/*.vue', { eager: true })
        return pages[`./pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18n)
            .use(ZiggyVue)
            .directive('tooltip', tooltipDirective)
            .mount(el)
    },
})
