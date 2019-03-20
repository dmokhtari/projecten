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
            path: '/admin/files',
            name: 'files',
            component: require('./views/files/Files').default
        },
        {
            path: '/admin/files/:id',
            name: 'file',
            component: require('./views/files/File').default
        },
        {
            path: '/admin/modules',
            name: 'modules',
            component: require('./views/modules/Modules').default
        },
        {
            path: '/admin/modules/:id',
            name: 'module',
            component: require('./views/modules/Module').default
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
