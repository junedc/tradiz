import { createRouter, createWebHistory } from 'vue-router';

import FormTemplatePage from '../pages/FormTemplatePage.vue';
import ProductsPage from '../pages/ProductsPage.vue';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            redirect: '/products',
        },
        {
            path: '/products',
            name: 'products.index',
            component: ProductsPage,
        },
        {
            path: '/template',
            name: 'template.index',
            component: FormTemplatePage,
        },
    ],
});

export default router;
