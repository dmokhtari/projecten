
import '../shared/bootstrap';
import Vue from 'vue';
import { router } from './routes';
import vuetify from './../shared/plugins/vuetify';
import MetaTags from './../shared/mixins/metaTags';
Vue.mixin(MetaTags);

// remove production tip
Vue.config.productionTip = false;
window.eventHub = new Vue();

Vue.component('login-component', require('./components/LoginComponent.vue').default);
Vue.component('reset-password-component', require('./components/ResetPasswordComponent.vue').default);

import App from './views/App';

// Add a 401 response interceptor
window.axios.interceptors.response.use(function (response) {
    return response;
}, function (error) {
    if (401 === error.response.status) {
        eventHub.$emit('show-message', 'error', 'Uw sessie is verlopen. U wordt naar login pagina gestuurd!')
        setTimeout(() => {
            window.location.href = '/login'
        }, 4000)
    } else {
        return Promise.reject(error);
    }
});

const app = new Vue({
    el: '#app',
    vuetify,
    components: { App },
    router
});
