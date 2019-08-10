import Vue from 'vue';
import VueRouter from 'vue-router';
import AuthHelper from '../helpers/AuthHelper';

import DashboardLayout from '../layouts/Dashboard/Dashboard.vue';
import DashboardPage from '../pages/Dashboard/Dashboard.vue';

import MainLayout from '../layouts/Main/Main.vue';
import LoginPage from '../pages/Login/Login.vue';

Vue.use(VueRouter);

const routes = [{
        path: '/dashboard',
        component: DashboardLayout,
        meta: {
            requireAuth: true,
        },
        children: [{
            path: '/',
            component: DashboardPage,
        }],
    }, {
        path: '/',
        component: MainLayout,
        meta: {
            requireAuth: false,
        },
        children: [{
            path: 'login',
            component: LoginPage,
        }],
    },
    {
        path: '*',
        redirect: '/'
    },
]

const router = new VueRouter({
    routes,
    mode: 'history',
    linkActiveClass: 'active',
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requireAuth) && !AuthHelper.getAccessToken()) {
        next(`/login`);
    } else if (!to.matched.some(record => record.meta.requireAuth) && AuthHelper.getAccessToken()) {
        next('/dashboard');
    } else {
        next();
    }
});

export default router;
