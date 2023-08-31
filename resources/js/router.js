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
        path: '/transactions',
        component: require('./pages/admin/transaction/index.vue').default,
        meta: {
            title: 'Transaction|BimaKwik',
        },
        props: true,
    },

    {
        path: '/success',
        component: require('./pages/admin/success/index.vue').default,
        meta: {
            title: 'Success|BimaKwik',
        },
        props: true,
    },
    {
        path: '/pending',
        component: require('./pages/admin/pending/index.vue').default,
        meta: {
            title: 'Pending|BimaKwik',
        },
        props: true,
    },

    {
        path: '/transaction/:id',
        component: require('./pages/admin/transaction/view.vue').default,
        meta: {
            title: 'Transaction|BimaKwik',
        },
        props: true,
    },
    {
        path: '/expiring',
        component: require('./pages/admin/expiring/index.vue').default,
        meta: {
            title: 'Success|BimaKwik',
        },
        props: true,
    },

    {
        path:'/admin-company',
        component:require('./pages/admin/company/index.vue').default,
        meta:{
            title:"Insurer|BimaKwik",
        },
        props:true,
    },

    {
        path: '/cancelled',
        component: require('./pages/admin/cancelled/index.vue').default,
        meta: {
            title: 'Cancel|BimaKwik',
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
