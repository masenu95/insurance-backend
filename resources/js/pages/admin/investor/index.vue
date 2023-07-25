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

            <!-- Start Theme panel do not add in project -->

            <!-- Start Quick menu with more functio -->

            <!-- Start Main leftbar navigation -->
            <sidebar-left link="investor"></sidebar-left>
            <!-- Start project content area -->
            <div class="page">
                <!-- Start Page header -->
                <top-nav></top-nav>
                <!-- Start Page title and tab -->
                <div class="section-body">
                    <div class="container-fluid">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="header-action">
                                <h1 class="page-title">Investors</h1>
                                <ol class="breadcrumb page-breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">BimaKwik</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Investors</li>
                                </ol>
                            </div>
                            <ul class="nav nav-tabs page-header-tab">
                                <li class="nav-item"><a class="nav-link" :class="active=='all'?'active':''" @click.prevent="active='all'">Investors</a></li>
                                <li class="nav-item"><a class="nav-link" :class="active=='detail'?'active':''">Details</a></li>

                            </ul>
                        </div>
                    </div>
                </div>

                <div class="section-body mt-4">
                    <div class="container-fluid">
                        <div class="tab-content">
                            <div class="tab-pane active">
                                <div class="filter" style="padding:30px 15px">
                                    <div class="">

                                        <h4><span>All Investors</span>
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
                                                    <section class="form-col3-right"><button type="submit" class="btn btn-secondary btn-sm"  style="margin-top: 25px !important; background-color:#1976d border:1px solid #f4f6f6; border-radius: 6px; color:#fff">Filter</button></section>
                                                </form>
                                            </article>
                                        </h4>

                                    </div>

                                </div>
                                <div class="card" style="padding:20px 15px">

                                    <div class="table-responsive">

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
                                                        <download-excel class="btn btn-info excel-green" :data="investors" :fields="label" title="export excel" worksheet="My Worksheet" name="Borrower.xls" style="color:#fff"><i class="fas fa-file-excel" style="color:#fff"></i>&nbsp; Excel
                                                        </download-excel>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <v-data-table :headers="headers" :items="investors" :search="search" :items-per-page="10" style="padding: 20px 10px;" class="elevation-1">
                                            <template #item.index="{ item }">
                                                {{ investors.indexOf(item) + 1 }}
                                            </template>

                                            <template v-slot:item.controls="props">
                                                <router-link type="button" :to="'investor/'+props.item.id" class="btn btn-icon btn-sm" title="show">
                                                    <i class="fe fe-maximize"></i></router-link>
                                            </template>

                                            <template v-slot:item.stat="props">
                                                <span class="badge alert-success" v-if="props.item.user.status=='ACTIVE'">{{props.item.user.status}}</span>

                                                <span class="badge alert-danger" v-else>{{props.item.user.status}}</span>
                                            </template>

                                            <template v-slot:item.join="props">
                                                {{props.item.created_at | formatDate}}
                                            </template>
                                            <template v-slot:item.bal="props">
                                                {{props.item.balance | formatNumber}}
                                            </template>
                                        </v-data-table>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <footer-component></footer-component>
            </div>
        </div>

    </v-app>
    </template>

    <script>
    export default {
        name: 'WorkspaceJsonInvestor',

        data() {
            return {

                search: "",
                investors: [],

                mobile: false,

                active: 'all',

                loading: false,

                display: false,

                filter: {
                    name: "",
                    phone: "",
                    email: "",
                    status: ""
                },

                headers: [{
                        value: 'index',
                        text: '#',
                        sortable: false,
                    },
                    {
                        text: 'Name',
                        value: 'user.name',
                    },
                    {
                        text: 'Email',
                        value: 'user.email',
                    },
                    {
                        text: 'Phone',
                        value: 'user.phone',
                    },
                    {
                        text: 'Wallet',
                        value: 'bal',
                    },
                    {
                        text: 'Physical Address',
                        value: 'physical',
                    },
                    {
                        text: 'Monthly Icome',
                        value: 'month_income',
                    },
                    {
                        text: 'Role',
                        value: 'user.role',
                    },
                    {
                        text: 'Id type',
                        value: 'id_type',
                    },
                    {
                        text: 'Id no',
                        value: 'id_no',
                    },
                    {
                        text: 'District',
                        value: 'district',
                    },
                    {
                        text: 'Ward',
                        value: 'ward',
                    },
                    {
                        text: 'Street',
                        value: 'street',
                    },
                    {
                        text: 'TIN',
                        value: 'tin',
                    },
                    {
                        text: 'Bank name',
                        value: 'bankname',
                    },
                    {
                        text: 'Account Number',
                        value: 'bankno',
                    },
                    {
                        text: 'Directors',
                        value: 'directors',
                    },

                    {
                        text: 'Created At',
                        value: 'join'
                    },
                    {
                        text: 'Status',
                        value: "stat"
                    },

                    {
                        text: "Action",
                        value: "controls",
                        sortable: false
                    }
                ],

                label: {
                    "Name": "user.name",
                    "Email": "user.email",
                    "Phone": "user.phone",
                    "Id Type": "id_type",
                    "Id Number": "id_no",
                    "Physical Address": "physical",
                    "Monthly Icome": "month_income",
                    "Id Type": "id_type",
                    "Id no": "id_no",
                    "District": "district",
                    "Ward": "ward",
                    "Street": "street",
                    "TIN": "tin",
                    "Bank Name": "bankname",
                    "Account Number": "bankno",
                    "Directors": "directors",

                },
            };
        },

        async beforeMount() {
            this.loading = true;
            this.user = JSON.parse(localStorage.getItem('user'));
            const response = await axios.get('api/investor');

            this.investors = response.data;

            this.loading = false;

        },

        methods: {
            menuclick(value) {
                this.mobile = value
            },

            daysCalc(maturity) {
                const date1 = new Date(maturity);
                const date2 = Date.now();
                const diffTime = Math.abs(date2 - date1);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                if (date1 >= date2) {
                    return diffDays;
                } else {
                    return diffDays * -1;
                }

            },

            async filterData() {
                this.loading = true;
                const response = await axios.post('api/investor-filter', this.filter);

                this.investors = response.data;
                this.loading = false;

            },

        },
    };
    </script>

    <style lang="css">
    .approved {
        color: rgb(5, 128, 5) !important;
    }
    </style>
