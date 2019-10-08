import Vue from 'vue';
import VueRouter from 'vue-router';
import NProgress from 'nprogress';
Vue.use(VueRouter);

// function page (path) {
//     return () => import(/* webpackChunkName: 'admin-group' */ `${path}`)
// }

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
                    { text: 'Bestand', name: 'Bestanden', disabled: true, to:'/admin/files' }
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
                    { text: 'Bestanden', name: 'Files', disabled: false, to:'/admin/files' },
                    { text: 'Bestand', name: 'Files', disabled: true, to:'/admin/files/id' }
                ]
            }
        },
        {
            path: '/admin/files/:fileId/modules/:moduleId',
            name: 'fileElements',
            component: require('./views/files/FileElements').default,
            meta: {
                breadcrumb: [
                    { text: 'Home', disabled: false, to:'/admin/dashboard' },
                    { text: 'Bestanden', name: 'Files', disabled: false, to:'/admin/files' },
                    { text: 'Bestanden', name: 'Files', disabled: false, to:'/admin/files/id' },
                    { text: 'Module', name: 'Modules', disabled: true, to:'/admin/modules/id' }
                ]
            }
        },
        {
            path: '/admin/files/:fileId/modules/:moduleId/elements/:elementId',
            name: 'fileSubElements',
            component: require('./views/files/FileSubElements').default,
            meta: {
                breadcrumb: [
                    { text: 'Home', disabled: false, to:'/admin/dashboard' },
                    { text: 'Bestanden', disabled: false, to:'/admin/files/id' },
                    { text: 'Module', disabled: true, to:'/admin/modules/id' },
                    { text: 'Element', disabled: true, to:'/admin/elements/id' }
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
                    { text: 'Elements', disabled: true, to:'/admin/elementen' },
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
                    { text: 'Elements', disabled: false, to:'/admin/elementen' },
                    { text: 'Aanmaken', disabled: true, to:'/admin/elementen/create' },
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
                    { text: 'Elements', disabled: false, to:'/admin/elementen' },
                    { text: 'Wijzigen', disabled: true, to:'/admin/elementen/element' },
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
            path: '/admin/users/:id',
            name: 'user',
            component: require('./views/users/User').default,
            meta: {
                breadcrumb: [
                    { text: 'Home', disabled: false, to:'/admin/dashboard' },
                    { text: 'Gebruikers', disabled: false, to:'/admin/users' },
                    { text: 'Gebruiker', disabled: true, to:'/admin/users/id' },
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
        },
        {
            path: '/admin/settings/documentation',
            name: 'documentation',
            component: require('./views/settings/Documentation').default,
            meta: {
                breadcrumb: [
                    { text: 'Home', disabled: false, to:'/admin/dashboard' },
                    { text: 'Documentatie', disabled: true, to:'/admin/documentatie' },
                ]
            }
        },
        {
            path: '/admin/profile',
            name: 'profile',
            component: require('./views/profile/profile').default,
            meta: {
                breadcrumb: [
                    { text: 'Home', disabled: false, to:'/admin/dashboard' },
                    { text: 'Profile', disabled: true, to:'/admin/profile' },
                ]
            }
        },
        {
            path: '*',
            name: '404',
            component: require('./views/404').default,
        },
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
