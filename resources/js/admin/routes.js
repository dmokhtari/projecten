import Vue from 'vue';
import VueRouter from 'vue-router';
import NProgress from 'nprogress';
Vue.use(VueRouter);

export const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/admin/dashboard',
            name: 'adminDashboard',
            component: require('./views/Dashboard').default
        },
        {
            path: '/admin/modules',
            name: 'modules',
            component: require('./views/Modules').default
        },
        {
            path: '/admin/modules/:id',
            name: 'module',
            component: require('./views/Module').default
        },
        {
            path: '/admin/users',
            name: 'users',
            component: require('./views/Users').default
        }
    ]
});

router.beforeResolve((to, from, next) => {
    if (to.name) {
        NProgress.start()
    }
    next()
})

router.afterEach((to, from) => {
    NProgress.done()
})
