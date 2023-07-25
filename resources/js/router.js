import Vue from 'vue';
import VueRouter from 'vue-router'

Vue.use(VueRouter)
const routes = [

    {
        path: '/',
        component: require('./pages/auth/loginPage.vue').default,
        meta: {
            title: 'Login|BimaKwik',
        },
        props: true,
    },



     //authentication route
     {
        path: '/admin-transactions',
        component: require('./pages/admin/invoice/index.vue').default,
        meta: {
            title: 'Success|BimaKwik',
        },
        props: true,
    },

    //authentication route
    {
        path: '/login',
        component: require('./pages/auth/loginPage.vue').default,
        meta: {
            title: 'Login|BimaKwik',
        },
        props: true,
    },


    //admin route
    {
        path: '/admin-home',
        component: require('./pages/admin/home.vue').default,
        meta: {
            title: 'Home|BimaKwik',
            requiresAuth: true,
        },
        props: true,
    },







    {
        path: '*',
        component: require('./pages/404.vue').default,
        meta: {
            title: 'Whoops 404|ME',

        }

    },

]



export const router = new VueRouter({
    mode: "history",
    routes,
})
