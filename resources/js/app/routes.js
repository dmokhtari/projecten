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
            path: '/bestanden/:id',
            name: 'studentFile',
            component: require('./views/File').default,
            meta: {
                breadcrumb: [
                    { text: 'Home', disabled: false, to:'/home' },
                    { text: 'Bestand', name: 'Bestanden', disabled: true, to:'/bestanden/id' }
                ]
            }
        },
        {
            path: '/bestanden/:fileId/modules/:moduleId',
            name: 'studentModule',
            component: require('./views/Module').default,
            meta: {
                breadcrumb: [
                    { text: 'Home', disabled: false, to:'/home' },
                    { text: 'Bestand', name: 'Bestanden', disabled: false, to:'/bestanden/id' },
                    { text: 'Module', name: 'Modules', disabled: true, to:'/modules/id' }
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
