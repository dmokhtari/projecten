import Vue from 'vue';
import Vuetify from 'vuetify';
import nl from 'vuetify/es5/locale/nl';
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';

Vue.component('font-awesome-icon', FontAwesomeIcon);
library.add(fas);
library.add(far);

Vue.use(Vuetify);
export default new Vuetify({
    icons: {
        iconfont: 'mdi',
    },
    lang: {
        locales: { nl },
        current: 'nl'
    },
    theme: {
        themes: {
            light: {
                primary: '#D70096',
                secondary: '#24126e',
                accent: '#7a7fa3'
            }
        }
    }
})
