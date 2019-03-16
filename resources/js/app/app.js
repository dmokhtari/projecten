
import '../shared/bootstrap';
import Vue from 'vue';
import { router } from './routes';
import MetaTags from '../shared/mixins/metaTags';
Vue.mixin(MetaTags);

window.Vue = Vue;
window.eventHub = new Vue();

Vue.component('login-component', require('./components/LoginComponent.vue').default);
Vue.component('reset-password-component', require('./components/ResetPasswordComponent.vue').default);
import App from './views/App';
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    components: {
        App
    },
    router
});
