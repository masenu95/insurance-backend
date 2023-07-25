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

    {
        path: '/phone-lock',
        component: require('./pages/phonelock.vue').default,
        meta: {
            title: 'Phone Lock|BimaKwik',
        },
        props: true,
    },


     //authentication route
     {
        path: '/success-transaction/:id',
        component: require('./pages/success.vue').default,
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

    {
        path: '/asset-payment',
        component: require('./pages/payment/index.vue').default,
        meta: {
            title: 'Asset Payment|BimaKwik',
        },
        props: true,
    },

    {
        path: '/asset-payment/:id',
        component: require('./pages/payment/payment.vue').default,
        meta: {
            title: 'Asset Payment|BimaKwik',
        },
        props: true,
    },
    {
        path: '/password-reset',
        component: require('./pages/auth/forgot-password.vue').default,
        meta: {
            title: 'Password Reset|BimaKwik',
        },
        props: true,
    },

    {
        path: '/borrower-registration',
        component: require('./pages/auth/borrower-register.vue').default,
        meta: {
            title: 'Borrower Registration|BimaKwik',
        },
        props: true,
    },

    {
        path: '/investor-registration',
        component: require('./pages/auth/investor-register.vue').default,
        meta: {
            title: 'Investor Registration|BimaKwik',
        },
        props: true,
    },

    {
        path: '/term-investor',
        component: require('./pages/auth/term-investor.vue').default,
        meta: {
            title: 'Investor Registration|BimaKwik',
        },
        props: true,
    },

    {
        path: '/term-borrower',
        component: require('./pages/auth/term-borrower.vue').default,
        meta: {
            title: 'Borrower Registration|BimaKwik',
        },
        props: true,
    },
    {
        path: '/otp/:id',
        component: require('./pages/auth/otp.vue').default,
        meta: {
            title: 'OTP Verifiction|BimaKwik',
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
        path: '/fees',
        component: require('./pages/admin/fees.vue').default,
        meta: {
            title: 'Fees|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },


    {
        path: '/admin-customers',
        component: require('./pages/admin/asset/index.vue').default,
        meta: {
            title: 'Asset Customers|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },

    {
        path: '/admin-customer/:id',
        component: require('./pages/admin/asset/view.vue').default,
        meta: {
            title: 'Asset Customers|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },

    {
        path: '/admin-device-request',
        component: require('./pages/admin/request/index.vue').default,
        meta: {
            title: 'Device Request|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },

    {
        path: '/admin-device-invoices',
        component: require('./pages/admin/deviceInvoice/index.vue').default,
        meta: {
            title: 'Device Invoice|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },

    {
        path: '/admin-device-generate',
        component: require('./pages/admin/deviceInvoice/generate.vue').default,
        meta: {
            title: 'Device Invoice|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },

    {
        path: '/admin-device-invoice/:id',
        component: require('./pages/admin/deviceInvoice/view.vue').default,
        meta: {
            title: 'Device Invoice|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },


    {
        path: '/admin-customer-create',
        component: require('./pages/admin/asset/create.vue').default,
        meta: {
            title: 'Add Asset Customers|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },

    {
        path:'/asset-customer-edit/:id',
        component:require('./pages/admin/asset/edit.vue').default,
        meta: {
            title: 'Edit Asset Customers|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },


    {
        path: '/admin-categories',
        component: require('./pages/admin/category/index.vue').default,
        meta: {
            title: 'Asset Category|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },

    {
        path: '/admin-category-create',
        component: require('./pages/admin/category/create.vue').default,
        meta: {
            title: 'Add Category|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },


    {
        path: '/admin-category-edit/:id',
        component: require('./pages/admin/category/edit.vue').default,
        meta: {
            title: 'Edit Category|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },


    {
        path: '/admin-devices',
        component: require('./pages/admin/device/index.vue').default,
        meta: {
            title: 'Device|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },

    {
        path: '/admin-device-create',
        component: require('./pages/admin/device/create.vue').default,
        meta: {
            title: 'Add Device|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },


    {
        path: '/admin-device-edit/:id',
        component: require('./pages/admin/device/edit.vue').default,
        meta: {
            title: 'Edit Device|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },
    //bank
    {
        path: '/banks',
        component: require('./pages/admin/bank/index.vue').default,
        meta: {
            title: 'Bank|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },

    {
        path: '/bank-create',
        component: require('./pages/admin/bank/create.vue').default,
        meta: {
            title: 'Bank Create|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },
    {
        path: '/bank-edit/:id',
        component: require('./pages/admin/bank/edit.vue').default,
        meta: {
            title: 'Bank Edit|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },


    //sector
    {
        path: '/sectors',
        component: require('./pages/admin/sector/index.vue').default,
        meta: {
            title: 'Sectors|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },
    {
        path: '/sector-create',
        component: require('./pages/admin/sector/create.vue').default,
        meta: {
            title: 'Sectors|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },
    {
        path: '/sector-edit/:id',
        component: require('./pages/admin/sector/edit.vue').default,
        meta: {
            title: 'Sectors|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },

    {
        path: '/companies',
        component: require('./pages/admin/company.vue').default,
        meta: {
            title: 'Paying companies|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },
    {
        path: '/process',
        component: require('./pages/admin/process.vue').default,
        meta: {
            title: 'Process|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },
    {
        path: '/staff',
        component: require('./pages/admin/staff.vue').default,
        meta: {
            title: 'Staff|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },



    {
        path: '/flow/:id',
        component: require('./pages/admin/workflow.vue').default,
        meta: {
            title: 'Workflow|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },
    //=====================================investor
    {
        path: '/investors',
        component: require('./pages/admin/investor/index.vue').default,
        meta: {
            title: 'Investor|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },
    {
        path: '/investor/:id',
        component: require('./pages/admin/investor/view.vue').default,
        meta: {
            title: 'Investor|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },


    //================================borrower
    {
        path: '/borrower',
        component: require('./pages/admin/borrower/index.vue').default,
        meta: {
            title: 'Borrower|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },
    {
        path: '/borrower/:id',
        component: require('./pages/admin/borrower/view.vue').default,
        meta: {
            title: 'Borrower|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },

    {
        path: '/invoice',
        component: require('./pages/admin/invoice/index.vue').default,
        meta: {
            title: 'Invoice|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },


    {
        path: '/invoice/:id',
        component: require('./pages/admin/invoice/view.vue').default,
        meta: {
            title: 'Invoice|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },

      {
        path: '/disbursement',
        component: require('./pages/admin/disbursement/index.vue').default,
        meta: {
            title: 'Disbursement|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },


    {
        path: '/disbursement/:id',
        component: require('./pages/admin/disbursement/view.vue').default,
        meta: {
            title: 'Disbursement|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },


    {
        path: '/repay',
        component: require('./pages/admin/repay/index.vue').default,
        meta: {
            title: 'Repay|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },


    {
        path: '/repay/:id',
        component: require('./pages/admin/repay/view.vue').default,
        meta: {
            title: 'Repay|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },

    {
        path: '/device-repay',
        component: require('./pages/admin/deviceRepay/index.vue').default,
        meta: {
            title: 'Bid Repay|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },


    {
        path: '/device-repay/:id',
        component: require('./pages/admin/deviceRepay/view.vue').default,
        meta: {
            title: 'Repay|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },


    {
        path: '/matured',
        component: require('./pages/admin/matured/index.vue').default,
        meta: {
            title: 'Matured|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },


    {
        path: '/matured/:id',
        component: require('./pages/admin/matured/view.vue').default,
        meta: {
            title: 'Matured|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },


    {
        path: '/invoices',
        component: require('./pages/admin/invoice.vue').default,
        meta: {
            title: 'Invoice|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },
    {
        path: '/admin-transactions',
        component: require('./pages/admin/transactions.vue').default,
        meta: {
            title: 'Transactions|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },

    {
        path: '/admin-bids',
        component: require('./pages/admin/bid.vue').default,
        meta: {
            title: 'Bids|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },

    {
        path: '/admin-device-bids',
        component: require('./pages/admin/device-bid/bid.vue').default,
        meta: {
            title: 'Bids|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },






    {
        path: '/admin-device-matured',
        component: require('./pages/admin/device-matured/bid.vue').default,
        meta: {
            title: 'Bids|BimaKwik',
            requiresAuth: true,
            isAdmin: true,
        },
        props: true,
    },


    {
        path:'/transactions',
        component:require('./pages/transactions.vue').default,
        meta:{
            title:'Transactions',
            requiresAuth:true,
            isBoth:true,
        },
        props:true,
    },






//==================================basic route=========================

    {
        path: '/loader',
        component: require('./components/loading.vue').default,
        meta: {
            title: 'Create Claim|ME',
            requiresAuth: true,
            isAdmin: true,
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
