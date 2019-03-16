
import '../shared/bootstrap';
import Vue from 'vue';
import { router } from './routes';
import MetaTags from '../shared/mixins/metaTags';
Vue.mixin(MetaTags);

window.Vue = Vue;
window.eventHub = new Vue();

Vue.component('message-box', require('./../shared/components/MessageBox').default);
import Admin from './views/Admin';


const app = new Vue({
    el: '#admin',
    components: { Admin },
    router
});
