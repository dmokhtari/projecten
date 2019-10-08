
import '../shared/bootstrap';
import Vue from 'vue';
import { router } from './routes';
import vuetify from './../shared/plugins/vuetify';
import MetaTags from './../shared/mixins/metaTags';
Vue.mixin(MetaTags);

// remove production tip
Vue.config.productionTip = false;
window.eventHub = new Vue();

import Admin from './views/Admin';

// console.log('my this', router)
// Add a 401 response interceptor
window.axios.interceptors.response.use(function (response) {
    return response;
}, function (error) {
    if (401 === error.response.status) {
        eventHub.$emit('show-message', 'error', 'Uw sessie is verlopen. U wordt naar de login pagina gestuurd!')
        setTimeout(() => {
            window.location.href = '/login'
        }, 4000)
    } else if(404 === error.response.status) {
        return router.push('404')
    } else {
        return Promise.reject(error);
    }
});

const app = new Vue({
    el: '#admin',
    vuetify,
    components: { Admin },
    router,
    data() {
        return {
            fileColor: ''
        }
    }
});
