require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import VueAxios from 'vue-axios';
import axios from 'axios';
Vue.use(VueAxios, axios);


//Vue.use(Autocomplete)

Vue.component('auto-complete', require('./components/Suggest.vue'));

//const router = new VueRouter({ mode: 'history'});
const app = new Vue().$mount('#app');
//const app = new Vue(Vue.util.extend({ router })).$mount('#app');
