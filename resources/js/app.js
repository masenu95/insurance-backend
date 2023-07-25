require('./bootstrap');

import Vue from 'vue';
import JsonExcel from "vue-json-excel";
// Import needed firebase modules





import moment from 'moment';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

import 'bootstrap-vue/dist/bootstrap-vue.css'

import { router } from './router';
import vuetify from './vuetify';

import { Datetime } from 'vue-datetime'

var numeral = require("numeral");
// You need a specific loader for CSS files
import 'vue-datetime/dist/vue-datetime.css'

import '@mdi/font/css/materialdesignicons.css'

import '@mdi/font/css/materialdesignicons.css'





Vue.config.productionTip = false



Vue.component('loader', require('./components/loading.vue').default);
Vue.component('ring-loader', require('vue-spinner/src/RingLoader.vue'));
Vue.component('date-picker', require('vue-datetime'));

Vue.component('admin-header',require('./components/header.vue').default);
Vue.component('sidebar-left',require('./components/sidebar-left.vue').default);
Vue.component('staff-sidebar-left',require('./components/staff-side-left.vue').default);
Vue.component('borrower-sidebar-left',require('./components/borrower-side-left.vue').default);
Vue.component('investor-sidebar-left',require('./components/investor-side-left.vue').default);
Vue.component('top-nav',require('./components/top-nav-bar.vue').default);
Vue.component('footer-component',require('./components/footer.vue').default);
Vue.component('borrower-nav',require('./components/borrower-nav-bar.vue').default);

Vue.use(Datetime)


Vue.component('datetime', Datetime);

Vue.filter("formatNumber", function(value) {
    return numeral(value).format("0,0"); // displaying other groupings/separators is possible, look at the docs
});





Vue.component("downloadExcel", JsonExcel);



const access_token = localStorage.getItem('token');

axios.defaults.headers.common['Authorization'] = `Bearer ${access_token}`;



const options = {
    confirmButtonColor: '#41b882',
    cancelButtonColor: '#ff7674',
};

Vue.use(VueSweetalert2, options);
Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(String(value)).format('DD MMM, YYYY', 'en')
    }
})


Vue.use(require('vue-chartist'), {
    messageNoData: "You have not enough data",
    classNoData: "empty"
})


import VueHtmlToPaper from 'vue-html-to-paper';

const opt = {
    name: '_blank',
    specs: [
        'fullscreen=yes',
        'titlebar=yes',
        'scrollbars=yes'
    ],
    styles: [
        'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',
        'https://unpkg.com/kidlat-css/css/kidlat.css',
        './css/app.css'
    ],
    timeout: 1000, // default timeout before the print window appears
    autoClose: true, // if false, the window will not close after printing
    // override the window title
}

import VueToast from 'vue-toast-notification';
// Import one of the available themes
//import 'vue-toast-notification/dist/theme-default.css';
import 'vue-toast-notification/dist/theme-sugar.css';

Vue.use(VueToast);

Vue.use(VueHtmlToPaper, opt);


router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (localStorage.getItem('token') == null) {
            next({
                path: '/',
                params: { nextUrl: to.fullPath }
            })
        } else {
            let user = JSON.parse(localStorage.getItem('user'))

                next()


        }
    }
     else if (to.matched.some(record => record.meta.guest)) {
        if (localStorage.getItem('token') == null) {
            next()
        } else {
            next({
                path: '/',
                params: { nextUrl: to.fullPath }
            })
        }
    } else {
        next()
    }
})






const app = new Vue({
    el: "#app",
    router,
    vuetify
});
