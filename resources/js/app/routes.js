import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter);

export const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/home',
            name: 'home',
            component: require('./views/Home').default
        },
        {
            path: '/modules/:id',
            name: 'module',
            component: require('./views/Module').default
        }
    ]
});
