import 'tinymce/tinymce'
import 'tinymce/skins/ui/oxide/skin.min.css'
import 'tinymce/skins/ui/oxide/content.min.css'
import '@tinymce/tinymce-vue'

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'

import 'startup-ui/dist/index.css';

createInertiaApp({
    title: (title) => `${title} - Моя коллекция книг`,
    resolve: (name) => {
        const pages = import.meta.glob('../views/pages/**/*.vue', { eager: true });
        return pages[`../views/pages/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
});