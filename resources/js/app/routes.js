import Vue from 'vue';
import VueRouter from 'vue-router';
import NProgress from 'nprogress';
Vue.use(VueRouter);

export const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/home',
            name: 'home',
            component: require('./views/Home').default,
            meta: {
                breadcrumb: [
                    { text: 'Home', disabled: true, to:'/home' }
                ]
            }
        },
        {
            path: '/files/:id',
            name: 'file',
            component: require('./views/File').default,
            meta: {
                breadcrumb: [
                    { text: 'Home', disabled: false, to:'/home' },
                    { text: 'File', disabled: true, to:'/file/id' }
                ]
            }
        }
    ]
});

router.beforeResolve((to, from, next) => {
    if (to.name) {
        NProgress.start()
    }
    next()
});

router.afterEach((to, from) => {
    NProgress.done()
});
