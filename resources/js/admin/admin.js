
import './../bootstrap';
import Vue from 'vue';
import { router } from './routes';
import MetaTags from './../mixins/metaTags';
Vue.mixin(MetaTags);

window.Vue = Vue;
window.eventHub = new Vue();

import Dashboard from './views/Dashboard';


const app = new Vue({
    el: '#admin',
    components: {
        Dashboard
    },
    router
});
