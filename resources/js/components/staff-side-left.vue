<template>
    <div id="left-sidebar" class="sidebar">
        <h5 class="brand-name">BimaKwik</h5>
        <ul class="nav nav-tabs">

            <li class="nav-item">
                <router-link class="nav-link" :class="tab=='invoice'?'active':''" to="#" @click.native="tabChange('invoice')">Invoice</router-link>
            </li>
            <li class="nav-item">
                <router-link class="nav-link" :class="tab=='device'?'active':''" to="#" @click.native="tabChange('device')">AFM</router-link>
            </li>
        </ul>
        <div class="tab-content mt-3">
            <div class="tab-pane fade" :class="tab=='invoice'?'show active':''" id="menu-admin" role="tabpanel">
                <nav class="sidebar-nav">
                    <ul :class="gridmenu?'metismenu grid':'metismenu'">
                        <li :class="this.link=='home'?'active':''">
                            <router-link to="../staff-home"><i class="fa fa-dashboard"></i><span>Dashboard</span></router-link>
                        </li>
                        <li :class="this.link=='borrower'?'active':''">
                            <router-link to="../staff-borrower"><i class="fa fa-bank"></i><span>Borrowers</span></router-link>
                        </li>
                        <li :class="this.link=='investor'?'active':''">
                            <router-link to="../staff-investor"><i class="fa fa-industry"></i><span>Investors</span></router-link>
                        </li>
                        <li :class="this.link=='invoice'?'active':''">
                            <router-link to="../staff-invoice"><i class="fa fa-building"></i><span>Invoice</span></router-link>
                        </li>
                        <li :class="this.link=='disbursement'?'active':''">
                            <router-link to="../staff-disbursement"><i class="fa fa-building"></i><span>Invoice Disbursement</span></router-link>
                        </li>
                        <li :class="this.link=='matured'?'active':''">
                            <router-link to="../staff-matured"><i class="fa fa-building"></i><span>Matured Invoice</span></router-link>
                        </li>
                        <li :class="this.link=='repay'?'active':''">
                            <router-link to="../staff-repay"><i class="fa fa-building"></i><span>Bid Repay</span></router-link>
                        </li>
                        <li :class="this.link=='fees'?'active':''">
                            <router-link to="../staff-fees"><i class="fa fa-building"></i><span>Fees</span></router-link>
                        </li>
                        <li :class="this.link=='transaction'?'active':''">
                            <router-link to="../staff-transaction"><i class="fas fa-coins"></i><span>Transactions</span></router-link>
                        </li>
                        <li :class="this.link=='bid'?'active':''">
                            <router-link to="../staff-bids"><i class="fa fa-users"></i><span>Bids</span></router-link>
                        </li>

                    </ul>
                </nav>
            </div>
            <div class="tab-pane fade" :class="tab=='device'?'show active':''" id="menu-admin" role="tabpanel">
                <nav class="sidebar-nav">
                    <ul :class="gridmenu?'metismenu grid':'metismenu'">
                        <li :class="this.link=='home'?'active':''">
                            <router-link to="../staff-home"><i class="fa fa-dashboard"></i><span>Dashboard</span></router-link>
                        </li>


                        <li :class="this.link=='request'?'active':''">
                            <router-link to="../staff-device-request"><i class="fas fa-coins"></i><span> Request</span></router-link>
                        </li>
                        <li :class="this.link=='device-invoice'?'active':''">
                            <router-link to="../staff-device-invoices"><i class="fas fa-coins"></i><span> Invoices</span></router-link>
                        </li>
                        <li :class="this.link=='device-bid'?'active':''">
                            <router-link to="../staff-device-bids"><i class="fas fa-coins"></i><span> Bids</span></router-link>
                        </li>
                        <li :class="this.link=='monthly'?'active':''">
                            <router-link to="../staff-device-matured"><i class="fas fa-coins"></i><span>Monthly matured</span></router-link>
                        </li>
                        <!--<li :class="this.link=='deviceRepay'?'active':''"><router-link to="../staff-device-repay"><i class="fas fa-coins"></i><span> Repay Invoice</span></router-link></li>
                               -->
                        <li :class="this.link=='customer'?'active':''">
                            <router-link to="../staff-customers"><i class="fas fa-coins"></i><span></span>Customers</router-link>
                        </li>
                    </ul>
                </nav>
            </div>

        </div>

    </div>
</template>

<script>
export default {
    name: 'WorkspaceJsonSidebarLeft',

    data() {
        return {
            search: "",
            search: "",
            gridmenu: false,
            user: [],
            tab: 'invoice',
            staff: {
                role: {
                    name: 'staff'
                }
            },
        };
    },
    async created() {
        this.user = JSON.parse(localStorage.getItem('user'));
        var grid = localStorage.getItem('grid');
        console.log()
        if (JSON.parse(grid)) {
            this.gridmenu = JSON.parse(grid);
        }
        var tab = localStorage.getItem('tab');
        if (JSON.parse(tab)) {
            this.tab = JSON.parse(tab);
        }
        const staff = await axios.get('../../api/active-staff');
        this.staff = staff.data;

    },

    mounted() {

    },
    props: ['link'],

    methods: {
        async grid() {
            await localStorage.setItem('grid', JSON.stringify(!this.gridmenu))
            this.gridmenu = !this.gridmenu
        },
        async tabChange(tab) {
            await localStorage.setItem('tab', JSON.stringify(tab))
            this.tab = tab
        }
    },

};
</script>

<style lang="css" scoped>
a {
    color: aliceblue;
}
</style>
