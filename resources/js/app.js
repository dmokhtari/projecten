
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
import Vue from 'vue';
import { router } from './routes';
import MetaTags from './mixins/metaTags';
Vue.mixin(MetaTags);

window.Vue = Vue;
window.eventHub = new Vue();

Vue.component('login-component', require('./components/LoginComponent.vue').default);
Vue.component('reset-password-component', require('./components/ResetPasswordComponent.vue').default);
import Home from './views/Home';
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    components: {
        Home
    },
    router
});
