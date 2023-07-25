<template>
<v-app>
    <v-dialog v-model="loading" :scrim="false" persistent width="auto">
        <v-card color="primary">
            <v-card-text style="color:#fff">
                <div style="padding-bottom: 5px;">Please wait</div>
                <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
            </v-card-text>
        </v-card>
    </v-dialog>
    <div id="main_content" :class="mobile?'offcanvas-active':''">
        <!-- Start Main top header -->
        <admin-header v-on:childToParent="menuclick"></admin-header>
        <!-- Start Rightbar setting panel -->

        <!-- Start Main leftbar navigation -->
        <investor-sidebar-left link="transactions" v-if="user.role=='Investor'"></investor-sidebar-left>
        <borrower-sidebar-left link="transactions" v-if="user.role=='Borrower'"></borrower-sidebar-left>
        <!-- Start project content area -->
        <!-- Start project content area -->
        <div class="page">
            <!-- Start Page header -->
            <top-nav></top-nav>
            <!-- Start Page title and tab -->
            <div class="section-body">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="header-action">
                            <h1 class="page-title">Transactions</h1>
                            <ol class="breadcrumb page-breadcrumb">
                                <li class="breadcrumb-item">
                                    <router-link to="#">BimaKwik</router-link>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Transactions
                                </li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <div class="section-body mt-4">
                <div class="container-fluid">
                    <div class="tab-content">
                        <div class="tab-pane" :class="active=='all'?'active':''">
                            <div class="filter" style="padding:30px 15px">
                                <div class="">

                                    <h4><span>All Transactions</span>
                                        <article class="filter-range-date">
                                            <form @submit.prevent="filterData">
                                                <section class="form-col3-left">
                                                    <h5>From Date</h5>
                                                    <input type="date" v-model="filter.min" placeholder="From date" class="form-control" required>
                                                </section>
                                                <input type="hidden" name="filter" value="filter">
                                                <section class="form-col3-mid">
                                                    <h5>To Date</h5>
                                                    <input type="date" v-model="filter.max" placeholder="To date" class="form-control" required>
                                                </section>
                                                <section class="form-col3-right"><button type="submit" class="btn btn-secondary " style="margin-top: 25px !important; background-color:#152C58; border:1px solid #f4f6f6; border-radius: 6px; color:#fff">Filter</button></section>
                                            </form>
                                        </article>
                                    </h4>

                                </div>

                            </div>
                            <div class="card" style="padding:20px 15px">
                                <div class="row">
                                        <div class="col-6">

                                            <div class="input-group mb-3" style="width:300px">
                                                <input type="text" class="form-control" placeholder="Search" v-model="search">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <ul class="header-dropdown" style="float:right">
                                                <li>
                                                    <download-excel class="btn btn-info excel-green" :data="all" :fields="label" title="export excel" worksheet="My Worksheet" name="transactions.xls" style="color:#fff"><i class="fas fa-file-excel" style="color:#fff"></i>&nbsp; Excel
                                                    </download-excel>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                <div class="table-responsive">
                                    <v-data-table :headers="headers" :items="all" :items-per-page="10" :search="search" class="elevation-1">
                                        <template #item.index="{ item }">
                                            {{ all.indexOf(item) + 1 }}
                                        </template>

                                        <template v-slot:item.amou="props">
                                            {{props.item.amount | formatNumber}}
                                        </template>

                                        <template v-slot:item.join="props">
                                            {{props.item.created_at | formatDate}}
                                        </template>
                                        <template v-slot:item.stat="props">
                                            <span class="badge alert-success" v-if="props.item.status=='ACTIVE'">{{props.item.status}}</span>
                                            <span class="badge alert-danger" v-else>{{props.item.status}}</span>
                                        </template>
                                        <template v-slot:item.actn="props">
                                            <span class="badge alert-success" v-if="props.item.action=='1'">Top Up</span>
                                            <span class="badge alert-danger" v-else>Withdraw</span>
                                        </template>
                                    </v-data-table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Start main footer -->
            <footer-component></footer-component>
        </div>
    </div>

</v-app>
</template>

<script>
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css';
export default {

    data() {
        return {
            search: "",
            all: [],
            success: [],
            pending: [],

            wallet: [],
            search: "",
            balance: 0,
            loading: false,

            mobile: false,

            active: "all",
            int: 1,

            filter: {
               min:"",
               max:"",
            },
            headers: [{
                    value: 'index',
                    text: '#',
                    sortable: false,
                },
                {
                    text: 'customer',
                    value: 'customer.name',
                },
                {
                    text: 'Type',
                    value: 'insurance_type.name',
                },


                {
                    text: 'Created At',
                    value: 'join'
                },

            ],

            label: {
                "Transaction Id": "transactionId",
                "Mobile": "mobile",
                "Bank": "bank",
                "Name": "user.name",
                "Reference": "reference",
                "Service": "service",

                "Amount": "amount",
                "Balance":"balance",
                "Channel": "channel",
            },

        };
    },

    async beforeMount() {
        this.loading = true;
        this.user = JSON.parse(localStorage.getItem('user'));

        const res = await axios.get('api/transactions');
        this.all = res.data;
        const result = await axios.get('api/my-balance');
        this.balance = result.data;
        this.loading = false;
    },

    mounted() {

    },

    methods: {
        menuclick(value) {
            this.mobile = value
        },

        completed() {
            this.loading = true;
            this.active = "success";

            const result = this.all.filter(({
                status
            }) => status === 'ACTIVE');
            this.success = result;

            this.loading = false;
        },
        incomplete() {
            this.loading = true;
            this.active = "pending";

            const result = this.all.filter(({
                status
            }) => status === 'PENDING');
            this.pending = result;

            this.loading = false;
        },
        async filterData() {
            this.loading = true;
            const response = await axios.post('api/user-trans-filter', this.filter);

            this.all = response.data;
            this.loading = false;

        },

    },
    components: {

        vueDropzone: vue2Dropzone,

    },
    watch: {
        $route: {
            immediate: true,
            handler(to, from) {
                document.title = to.meta.title || 'Some Default Title';
            }
        },
    }

};
</script>

<style lang="scss" scoped></style>
