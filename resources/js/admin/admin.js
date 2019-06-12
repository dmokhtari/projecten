
import '../shared/bootstrap';
import Vue from 'vue';
import { router } from './routes';
import MetaTags from '../shared/mixins/metaTags';
Vue.mixin(MetaTags);

window.Vue = Vue;
window.eventHub = new Vue();

import Admin from './views/Admin';


// Add a 401 response interceptor
window.axios.interceptors.response.use(function (response) {
    return response;
}, function (error) {
    if (401 === error.response.status) {
        eventHub.$emit('show-message', 'error', 'Uw sessie is verlopen. U wordt naar de login pagina gestuurd!')
        setTimeout(() => {
            window.location.href = '/login'
        }, 4000)
    } else {
        return Promise.reject(error);
    }
});

const app = new Vue({
    el: '#admin',
    components: { Admin },
    router
});
