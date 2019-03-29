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
            component: require('./views/Dashboard').default,
            meta: {
                breadcrumb: [
                    { text: 'Home', disabled: true, to:'/admin/dashboard' },
                ]
            }
        },
        {
            path: '/admin/files',
            name: 'files',
            component: require('./views/files/Files').default,
            meta: {
                breadcrumb: [
                    { text: 'Home', disabled: false, to:'/admin/dashboard' },
                    { text: 'Files', disabled: true, to:'/admin/files' }
                ]
            }
        },
        {
            path: '/admin/files/:id',
            name: 'file',
            component: require('./views/files/File').default,
            meta: {
                breadcrumb: [
                    { text: 'Home', disabled: false, to:'/admin/dashboard' },
                    { text: 'Files', disabled: false, to:'/admin/files' },
                    { text: 'File', disabled: true, to:'/admin/files/id' }
                ]
            }
        },
        {
            path: '/admin/modules',
            name: 'modules',
            component: require('./views/modules/Modules').default,
            meta: {
                breadcrumb: [
                    { text: 'Home', disabled: false, to:'/admin/dashboard' },
                    { text: 'Modules', disabled: true, to:'/admin/modules' }
                ]
            }
        },
        {
            path: '/admin/modules/:id',
            name: 'module',
            component: require('./views/modules/Module').default,
            meta: {
                breadcrumb: [
                    { text: 'Home', disabled: false, to:'/admin/dashboard' },
                    { text: 'Modules', disabled: false, to:'/admin/modules' },
                    { text: 'Module', disabled: true, to:'/admin/modules/id' }
                ]
            }
        },
        {
            path: '/admin/elements',
            name: 'elements',
            component: require('./views/elements/Elements').default,
            meta: {
                breadcrumb: [
                    { text: 'Home', disabled: false, to:'/admin/dashboard' },
                    { text: 'Elements', disabled: true, to:'/admin/elements' },
                ]
            }
        },
        {
            path: '/admin/elements/create',
            name: 'addElement',
            component: require('./views/elements/CreateElement').default,
            meta: {
                breadcrumb: [
                    { text: 'Home', disabled: false, to:'/admin/dashboard' },
                    { text: 'Elements', disabled: false, to:'/admin/elements' },
                    { text: 'Aanmaken', disabled: true, to:'/admin/elements/create' },
                ]
            }
        },
        {
            path: '/admin/elements/:id',
            name: 'editElement',
            component: require('./views/elements/EditElement').default,
            meta: {
                breadcrumb: [
                    { text: 'Home', disabled: false, to:'/admin/dashboard' },
                    { text: 'Elements', disabled: false, to:'/admin/elements' },
                    { text: 'Wijzigen', disabled: true, to:'/admin/elements/element' },
                ]
            }
        },
        {
            path: '/admin/users',
            name: 'users',
            component: require('./views/users/Users').default,
            meta: {
                breadcrumb: [
                    { text: 'Home', disabled: false, to:'/admin/dashboard' },
                    { text: 'Gebruikers', disabled: true, to:'/admin/users' },
                ]
            }
        },
        {
            path: '/admin/settings/icons',
            name: 'iconSettings',
            component: require('./views/settings/Icons').default,
            meta: {
                breadcrumb: [
                    { text: 'Home', disabled: false, to:'/admin/dashboard' },
                    { text: 'Icons', disabled: true, to:'/admin/settings/icons' },
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
})

router.afterEach((to, from) => {
    NProgress.done()
})
