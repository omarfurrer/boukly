/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import Vue from 'vue';
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';
import '@fortawesome/fontawesome-free/css/all.css';
import 'material-design-icons-iconfont/dist/material-design-icons.css';
import VueMq from 'vue-mq';

import router from './router/index.js';
import store from './store/index.js';
import mixin from './mixin/index.js';

import defaultTheme from './vuetify/themes/defaultTheme';
import defaultBreakpoints from './vuetify/breakpoints/defaultBreakpoints';
import fontawesomeIcons from './vuetify/icons/fontawesomeIcons';

import InfiniteLoading from 'vue-infinite-loading';

Vue.prototype.$eventHub = new Vue();

Vue.use(Vuetify, {
    theme: defaultTheme,
    iconfont: 'md',
    // iconfont: 'fa',
    // icons: fontawesomeIcons,
});

Vue.use(VueMq, {
    breakpoints: defaultBreakpoints,
});

Vue.mixin(mixin);

Vue.use(InfiniteLoading);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
    store,
});
