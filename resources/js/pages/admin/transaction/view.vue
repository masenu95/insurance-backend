<template>
    <v-app>
        <div id="main_content" :class="mobile?'offcanvas-active':''">
    <!-- Start Main top header -->
<admin-header v-on:childToParent="menuclick"></admin-header>
    <!-- Start Rightbar setting panel -->
<!-- Start Main leftbar navigation -->
       <sidebar-left link="transaction"></sidebar-left>
    <!-- Start project content area -->
    <!-- Start project content area -->
    <div class="page">
        <!-- Start Page header -->
        <top-nav></top-nav>
        <!-- Start Page title and tab -->
        <div class="section-body">
            <div class="container-fluid">
            <!-- Start Main top header -->

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="header-action">
                                <h1 class="page-title">Transaction</h1>

                            </div>
                            <ul class="nav nav-tabs page-header-tab">

                                <li class="nav-item"><a class="nav-link active">Details</a></li>

                            </ul>
                        </div>
                    </div>
                </div>

                <div class="section-body mt-4">
                    <div class="container-fluid">
                        <div class="tab-content">

                            <div class="tab-pane active">
                                <div class="row">
                                    <div class="col-xl-12 col-md-12">


                                        <div class="card">

                                            <div class="card-body">
                                                <div class="">
                                                    <!-- Account page navigation-->
                                                    <nav class="nav nav-borders">
                                                        <button class="nav-link  ms-0" :class="tab=='customer'?'active':''" @click.prevent="tab='customer'">Customer</button>
                                                        <button class="nav-link" :class="tab=='transaction'?'active':''" @click.prevent="tab='transaction'">Transaction</button>
                                                        <button class="nav-link" :class="tab=='payment'?'active':''" @click.prevent="tab='payment'">Payment </button>
                                                        <button class="nav-link" :class="tab=='motor'?'active':''" @click.prevent="tab='motor'">Motor</button>
                                                        <button class="nav-link" :class="tab=='document'?'active':''" @click.prevent="tab='document'">Documents</button>
                                                    </nav>
                                                    <hr class="mt-0 mb-4">
                                                    <div class="row">

                                                        <div :class="tab=='customer'?'col-xl-12':'col-xl-12'">
                                                            <!-- Account details card-->
                                                            <div class="card mb-4" :class="tab=='customer'?'active show':'hide'">
                                                                <div class="card-header">Account Details</div>
                                                                <div class="card-body">
                                                                    <form>
                                                                        <!-- Form Group (username)-->

                                                                        <!-- Form Row-->
                                                                        <div class="row gx-3 mb-3">
                                                                            <!-- Form Group (first name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Full Name</label>
                                                                                <div class="description">{{transaction.customer.full_name}}</div>
                                                                            </div>
                                                                            <!-- Form Group (last name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Email</label>
                                                                                <div class="description">{{transaction.customer.email}}</div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Phone</label>
                                                                                <div class="description">255{{transaction.customer.phone_number}}</div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row gx-3 mb-3">
                                                                            <!-- Form Group (first name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Postal Address</label>
                                                                                <div class="description">{{transaction.customer.postal_address}}</div>
                                                                            </div>
                                                                            <!-- Form Group (last name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">ID Type</label>
                                                                                <div class="description">{{transaction.customer.id_type}}</div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Id Number</label>
                                                                                <div class="description">{{transaction.customer.id_number}}</div>
                                                                            </div>
                                                                        </div>
                                                                        <!-- Form Row        -->

                                                                        <!-- Form Group (email address)-->
                                                                        <div class="row gx-3 mb-3">
                                                                            <!-- Form Group (first name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Region</label>
                                                                                <div class="description">{{transaction.customer.rname}}</div>
                                                                            </div>
                                                                            <!-- Form Group (last name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">District</label>
                                                                                <div class="description">{{transaction.customer.dname}}</div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Street</label>
                                                                                <div class="description">{{transaction.customer.street}}</div>
                                                                            </div>

                                                                        </div>
                                                                        <div class="row gx-3 mb-3">
                                                                            <!-- Form Group (first name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Customer Type</label>
                                                                                <div class="description" v-if="transaction.customer.customer_type == 1">Individual</div>
                                                                                <div class="description" v-else>Cooperate</div>
                                                                            </div>

                                                                        </div>


                                                                        <!-- Form Row-->

                                                                    </form>
                                                                </div>
                                                            </div>

                                                            <div class="card mb-4" :class="tab=='transaction'?'active show':'hide'" role="tabpanel">
                                                                <div class="table-responsive">
                                                                    <div class="card-header">Transaction Details</div>
                                                                <div class="card-body">
                                                                    <form>
                                                                        <!-- Form Group (username)-->

                                                                        <!-- Form Row-->
                                                                        <div class="row gx-3 mb-3">
                                                                            <!-- Form Group (first name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Request ID</label>
                                                                                <div class="description">{{transaction.transaction.request_id}}</div>
                                                                            </div>
                                                                            <!-- Form Group (last name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Start Date</label>
                                                                                <div class="description">{{transaction.transaction.covernote_start_date}}</div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">End Date</label>
                                                                                <div class="description">{{transaction.transaction.covernote_end_date}}</div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row gx-3 mb-3">
                                                                            <!-- Form Group (first name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Last Insurer</label>
                                                                                <div class="description">{{transaction.transaction.last}}</div>
                                                                            </div>
                                                                            <!-- Form Group (last name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Last Expiry Date</label>
                                                                                <div class="description">{{transaction.transaction.covernote_start_date}}</div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">First Loss Payee</label>
                                                                                <div class="description">{{transaction.transaction.covernote_end_date}}</div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row gx-3 mb-3">
                                                                            <!-- Form Group (first name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Insurance Coverage</label>
                                                                                <div class="description">{{transaction.transaction.last}}</div>
                                                                            </div>
                                                                            <!-- Form Group (last name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Covernote Type</label>
                                                                                <div class="description">New</div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Covernote Number</label>
                                                                                <div class="description">{{transaction.transaction.covernote_number}}</div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row gx-3 mb-3">
                                                                            <!-- Form Group (first name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Previous Covernote</label>
                                                                                <div class="description">{{transaction.transaction.last}}</div>
                                                                            </div>
                                                                            <!-- Form Group (last name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Currency Code</label>
                                                                                <div class="description">TZS</div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">End Date</label>
                                                                                <div class="description">{{transaction.transaction.covernote_end_date}}</div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row gx-3 mb-3">
                                                                            <!-- Form Group (first name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Commission Paid</label>
                                                                                <div class="description">{{transaction.transaction.commission_paid|formatNumber}}</div>
                                                                            </div>
                                                                            <!-- Form Group (last name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Commission Rate</label>
                                                                                <div class="description">{{transaction.transaction.commission_rate}}</div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Sum Insured</label>
                                                                                <div class="description">{{transaction.transaction.sum_insured|formatNumber}}</div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row gx-3 mb-3">
                                                                            <!-- Form Group (first name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Premium Excluding Tax</label>
                                                                                <div class="description">{{transaction.transaction.total_premium_excluding_tax|formatNumber}}</div>
                                                                            </div>
                                                                            <!-- Form Group (last name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Premium Including Tax</label>
                                                                                <div class="description">{{transaction.transaction.total_premium_including_tax|formatNumber}}</div>
                                                                            </div>



                                                                        </div>
                                                                        <!-- Form Row        -->

                                                                        <!-- Form Group (email address)-->



                                                                        <!-- Form Row-->

                                                                    </form>
                                                                </div>
                                                                </div>

                                                            </div>
                                                            <div class="card mb-4" :class="tab=='payment'?'active show':'hide'" role="tabpanel">
                                                                <div class="row gx-3 mb-3">
                                                                            <!-- Form Group (first name)-->

                                                                            <!-- Form Group (last name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Amount</label>
                                                                                <div class="description">{{transaction.transaction.total_premium_including_tax|formatNumber}}</div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Status</label>
                                                                                <div class="description">{{transaction.transaction.status}}</div>
                                                                            </div>
                                                                        </div>
                                                            </div>
                                                            <div class="card mb-4" :class="tab=='motor'?'active show':'hide'">
                                                                <div class="row gx-3 mb-3">
                                                                            <!-- Form Group (first name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Owner Name</label>
                                                                                <div class="description">{{transaction.transaction.owner_name}}</div>
                                                                            </div>
                                                                            <!-- Form Group (last name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Owner Category</label>
                                                                                <div class="description"><span v-if="transaction.transaction.owner_category == '1'">Sole </span><span v-else>Cooperate</span></div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Owner Address</label>
                                                                                <div class="description">{{transaction.transaction.owner_address}}</div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row gx-3 mb-3">
                                                                            <!-- Form Group (first name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Year of Manufacture</label>
                                                                                <div class="description">{{transaction.transaction.year_Of_manufacture}}</div>
                                                                            </div>
                                                                            <!-- Form Group (last name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Registration Number</label>
                                                                                <div class="description">{{transaction.transaction.registration_number }}</div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Chassis Number</label>
                                                                                <div class="description">{{transaction.transaction.chassis_number}}</div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row gx-3 mb-3">
                                                                            <!-- Form Group (first name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Motor Usage</label>
                                                                                <div class="description"><span v-if="transaction.transaction.motor_usage">Private</span><span v-else>Commercial</span></div>
                                                                            </div>
                                                                            <!-- Form Group (last name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Motor Category</label>
                                                                                <div class="description"><span v-if="transaction.transaction.registration_number == '1'">Private</span><span v-else>Cooperate</span></div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Make</label>
                                                                                <div class="description">{{transaction.transaction.make}}</div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row gx-3 mb-3">
                                                                            <!-- Form Group (first name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Model</label>
                                                                                <div class="description">{{transaction.transaction.model}}</div>
                                                                            </div>
                                                                            <!-- Form Group (last name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Model Number</label>
                                                                                <div class="description">{{transaction.transaction.model_number}}</div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Make</label>
                                                                                <div class="description">{{transaction.transaction.body_type}}</div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row gx-3 mb-3">
                                                                            <!-- Form Group (first name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Color</label>
                                                                                <div class="description">{{transaction.transaction.color}}</div>
                                                                            </div>
                                                                            <!-- Form Group (last name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Engine Number</label>
                                                                                <div class="description">{{transaction.transaction.engine_number}}</div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Engine Capacity</label>
                                                                                <div class="description">{{transaction.transaction.engine_capacity}}</div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row gx-3 mb-3">
                                                                            <!-- Form Group (first name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Fuel Used</label>
                                                                                <div class="description">{{transaction.transaction.fuel_used}}</div>
                                                                            </div>
                                                                            <!-- Form Group (last name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Number of Axles</label>
                                                                                <div class="description">{{transaction.transaction.axles_number}}</div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Axles Distance</label>
                                                                                <div class="description">{{transaction.transaction.axles_distance}}</div>
                                                                            </div>
                                                                        </div>

                                                            </div>

                                                            <div class="card mb-4 " :class="tab=='document'?'active show':'hide'">


                                                                <div class="row gx-3 mb-3">
                                                                            <!-- Form Group (first name)-->
                                                                            <div class="col-md-4" v-for="document in transaction.documents" :key="document">
                                                                                <label class="title-detail">{{document.name}}</label>
                                                                                <div class="description">
                                                                                        <img :src="host+document.url" style="height:200px;width: 200px ;">
                                                                                </div>
                                                                                <div>
                                                                                    Created at: {{ document.time }}<br>
                                                                                    Location: {{ document.location }}
                                                                                </div>
                                                                            </div>

                                                                        </div>


                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

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
    import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css';
    export default {
        name: 'WorkspaceJsonBorrower',

        data() {
            return {
                id:"",
                tab: "customer",
                host:window.location.protocol + "//" + window.location.host,
                loading:true,
                mobile:false,
                transaction:[],
                }

        },

        async beforeMount() {
            const id = this.$route.params.id;
            this.id = id;

            const res = await axios.get('../../api/transaction-info/'+this.id);
             this.transaction = res.data;



            this.loading = false;

        },

        methods: {
            menuclick(value) {
                this.mobile = value
            },

        },

        components: {

            vueDropzone: vue2Dropzone,

        },
    };
    </script>


    <style lang="css">
    .approved {
        color: rgb(5, 128, 5) !important;
    }

    .img-account-customer {
        height: 10rem;
    }

    .rounded-circle {
        border-radius: 50% !important;
    }

    .card {
        box-shadow: 0 0.15rem 1.75rem 0 rgb(33 40 50 / 15%);
    }

    .card .card-header {
        font-weight: 500;
    }

    .card-header:first-child {
        border-radius: 0.35rem 0.35rem 0 0;
    }

    .card-header {
        padding: 1rem 1.35rem;
        margin-bottom: 0;
        background-color: rgba(33, 40, 50, 0.03);
        border-bottom: 1px solid rgba(33, 40, 50, 0.125);
    }

    .form-control,
    .dataTable-input {
        display: block;
        width: 100%;
        padding: 0.875rem 1.125rem;
        font-size: 0.875rem;
        font-weight: 400;
        line-height: 1;
        color: #69707a;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #c5ccd6;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border-radius: 0.35rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .nav-borders .nav-link.active {
        color: #0061f2;
        border-bottom-color: #0061f2;
    }

    .nav-borders .nav-link {
        color: #69707a;
        border-bottom-width: 0.125rem;
        border-bottom-style: solid;
        border-bottom-color: transparent;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        padding-left: 0;
        padding-right: 0;
        margin-left: 1rem;
        margin-right: 1rem;
    }

    .title-detail {
        text-transform: capitalize;
    }

    .hide {
        display: none;
    }
    </style>
