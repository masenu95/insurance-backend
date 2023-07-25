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

    <v-dialog v-model="dialog" persistent max-width="500">
        <v-card>
            <v-card-title>
                <span class="headline">{{ title }}</span>
            </v-card-title>
            <v-card-text>
                <label>Reason</label>
                <v-spacer></v-spacer>
                <v-textarea v-model="reverseForm.reason" dense outlined></v-textarea>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="save">Save</v-btn>
                <v-btn color="primary" @click="close">Close</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>

    <v-dialog v-model="approveForm" persistent max-width="500">
        <v-card>
            <form style="padding:20px">
                <div class="col-md-12 col-sm-12">
                    <div class="form-group" style="text-align:start;text-transform: capitalize !important;">
                        <label>Score</label>
                        <select class="form-control" v-model="form.score" required>
                            <option v-for="int in 4" :key="int" :value="int">{{int}}</option>

                        </select>
                        <div class="validation-error" v-if="errors.score">
                            {{errors.score[0]}}
                        </div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-12">
                        <label>Upload document</label>
                        <vue-dropzone :options="dropzoneOptions" v-on:vdropzone-success="uploadSuccess" id="upload"></vue-dropzone>
                    </div>
                </div>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="saveApprove">Save</v-btn>
                    <v-btn color="primary" @click="closeApprove">Close</v-btn>
                </v-card-actions>
            </form>
        </v-card>

    </v-dialog>

    <div id="main_content" :class="mobile?'offcanvas-active':''">
        <!-- Start Main top header -->
        <admin-header v-on:childToParent="menuclick"></admin-header>

        <!-- Start Theme panel do not add in project -->

        <!-- Start Quick menu with more functio -->

        <!-- Start Main leftbar navigation -->
        <sidebar-left link="borrower"></sidebar-left>
        <!-- Start project content area -->
        <div class="page">
            <!-- Start Page header -->
            <top-nav></top-nav>
            <!-- Start Page title and tab -->
            <div class="section-body">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="header-action">
                            <h1 class="page-title">Borrowers</h1>

                        </div>
                        <ul class="nav nav-tabs page-header-tab">
                            <li class="nav-item">
                                <router-link to="../../borrower" class="nav-link">Borrowers</router-link>
                            </li>
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
                                    <div class="row">
                                        <div class="col-lg-4 col-xl-4 col-md-4 col-sm-12 col-12">
                                            <div class="card" style="color:#fff;background-color: rgb(120, 149, 178)  !important;">
                                                <div class="card-body">
                                                    <h4 class="header-title mt-0 mb-4">Borrowing Limit</h4>
                                                    <div class="widget-chart-1">
                                                        <div class="widget-detail-1 text-end">
                                                            <h2 class="fw-normal pt-2 mb-1">{{borrower.borrow_limit |formatNumber}}</h2>
                                                            <p class="text-muted mb-1" style="color:#fff !important">Borrowing Limit</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-xl-4 col-md-4 col-sm-12 col-12">
                                            <div class="card" style="color:#fff;background-color:  rgb(160, 228, 203) !important;">
                                                <div class="card-body">
                                                    <h4 class="header-title mt-0 mb-4">Wallet Amount</h4>
                                                    <div class="widget-chart-1">
                                                        <div class="widget-detail-1 text-end">
                                                            <h2 class="fw-normal pt-2 mb-1">{{balance |formatNumber}}</h2>
                                                            <p class="text-muted mb-1" style="color:#fff !important">Wallet</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-xl-4 col-md-4 col-sm-12 col-12">
                                            <div class="card" style="background-color: rgb(232, 223, 202) !important;">
                                                <div class="card-body">
                                                    <h4 class="header-title mt-0 mb-4">Debt</h4>
                                                    <div class="widget-chart-1">
                                                        <div class="widget-detail-1 text-end">
                                                            <h2 class="fw-normal pt-2 mb-1">{{debt |formatNumber}}</h2>
                                                            <p class="text-muted mb-1" style="color:#fff !important">Debt</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">

                                        <div class="card-body">
                                            <div class="">
                                                <!-- Account page navigation-->
                                                <nav class="nav nav-borders">
                                                    <button class="nav-link  ms-0" :class="tab=='profile'?'active':''" @click.prevent="tab='profile'">Profile</button>
                                                    <button class="nav-link" :class="tab=='invoice'?'active':''" @click.prevent="tab='invoice'">Invoice</button>
                                                    <button class="nav-link" :class="tab=='matured'?'active':''" @click.prevent="tab='matured'">Matured Invoice</button>
                                                    <button class="nav-link" :class="tab=='transaction'?'active':''" @click.prevent="tab='transaction'">Transaction</button>
                                                    <button class="nav-link" :class="tab=='notification'?'active':''" @click.prevent="tab='notification'">Notification</button>
                                                </nav>
                                                <hr class="mt-0 mb-4">
                                                <div class="row">
                                                    <div :class="tab=='profile'?'col-xl-4':'hide'">
                                                        <!-- Profile picture card-->
                                                        <a v-if="borrower.card" :href="url+JSON.parse(borrower.card).url">
                                                            <div class="card mb-4 mb-xl-0">
                                                                <div class="card-header">Id Card</div>
                                                                <div class="card-body text-center">
                                                                    <i class="fas fa-file-pdf icon-style"></i>
                                                                </div>
                                                            </div>
                                                        </a>
                                                        <br>
                                                          <a  v-if="borrower.regCertificate" :href="url+JSON.parse(borrower.regCertificate).url">
                                                            <div class="card mb-4 mb-xl-0">
                                                                <div class="card-header">Registration Certificate</div>
                                                                <div class="card-body text-center">
                                                                    <i class="fas fa-file-pdf icon-style"></i>
                                                                </div>
                                                            </div>
                                                        </a>
                                                        <br>
                                                        <a  v-if="borrower.mdId" :href="url+JSON.parse(borrower.mdId).url">
                                                            <div class="card mb-4 mb-xl-0">
                                                                <div class="card-header">Md ID </div>
                                                                <div class="card-body text-center">
                                                                    <i class="fas fa-file-pdf icon-style"></i>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div :class="tab=='profile'?'col-xl-8':'col-xl-12'">
                                                        <!-- Account details card-->
                                                        <div class="card mb-4" :class="tab=='profile'?'active show':'hide'">
                                                            <div class="card-header">Account Details</div>
                                                            <div class="card-body">
                                                                <form>
                                                                    <!-- Form Group (username)-->

                                                                    <!-- Form Row-->
                                                                    <div class="row gx-3 mb-3">
                                                                        <!-- Form Group (first name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Full Name</label>
                                                                            <div class="description">{{borrower.user.name}}</div>
                                                                        </div>
                                                                        <!-- Form Group (last name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Email</label>
                                                                            <div class="description">{{borrower.user.email}}</div>
                                                                        </div>

                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Phone</label>
                                                                            <div class="description">{{borrower.user.phone}}</div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row gx-3 mb-3">
                                                                        <!-- Form Group (first name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Physical Address</label>
                                                                            <div class="description">{{borrower.physical}}</div>
                                                                        </div>
                                                                        <!-- Form Group (last name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">ID Type</label>
                                                                            <div class="description">{{borrower.id_type}}</div>
                                                                        </div>

                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Id Number</label>
                                                                            <div class="description">{{borrower.id_no}}</div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- Form Row        -->

                                                                    <!-- Form Group (email address)-->
                                                                    <div class="row gx-3 mb-3">
                                                                        <!-- Form Group (first name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Region</label>
                                                                            <div class="description">{{borrower.region}}</div>
                                                                        </div>
                                                                        <!-- Form Group (last name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">District</label>
                                                                            <div class="description">{{borrower.district}}</div>
                                                                        </div>

                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Ward</label>
                                                                            <div class="description">{{borrower.ward}}</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row gx-3 mb-3">
                                                                        <!-- Form Group (first name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Street</label>
                                                                            <div class="description">{{borrower.street}}</div>
                                                                        </div>
                                                                        <!-- Form Group (last name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Bank</label>
                                                                            <div class="description">{{borrower.bankname}}</div>
                                                                        </div>

                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Account Number</label>
                                                                            <div class="description">{{borrower.bankno}}</div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row gx-3 mb-3">
                                                                        <!-- Form Group (first name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">TIN</label>
                                                                            <div class="description">{{borrower.tin}}</div>
                                                                        </div>
                                                                        <!-- Form Group (last name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Directors</label>
                                                                            <div class="description">{{borrower.directors}}</div>
                                                                        </div>

                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Name of Md</label>
                                                                            <div class="description">{{borrower.mdName}}</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row gx-3 mb-3">
                                                                        <!-- Form Group (first name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Comments</label>
                                                                            <div class="description">{{borrower.comment}}</div>
                                                                        </div>
                                                                        <!-- Form Group (last name)-->

                                                                    </div>
                                                                    <!-- Form Row-->

                                                                </form>
                                                            </div>
                                                        </div>

                                                        <div class="card mb-4" :class="tab=='invoice'?'active show':'hide'" role="tabpanel">
                                                            <div class="table-responsive">
                                                                <v-data-table :headers="invoiceHeaders" :items="invoices" :items-per-page="10" class="elevation-1">

                                                                    <template v-slot:item.join="props">
                                                                        {{props.item.created_at | formatDate}}
                                                                    </template>

                                                                    <template v-slot:item.amou="props">
                                                                        {{props.item.amount | formatNumber}}
                                                                    </template>
                                                                    <template v-slot:item.dis="props">
                                                                        {{props.item.discount_amount | formatNumber}}
                                                                    </template>
                                                                    <template v-slot:item.tradingfee="props">
                                                                        {{props.item.trading_fee}}%
                                                                    </template>
                                                                    <template v-slot:item.tradingfeeAmount="props">
                                                                        {{props.item.trading_fee_amount | formatNumber}}
                                                                    </template>
                                                                    <template v-slot:item.processingfee="props">
                                                                        {{props.item.proccessing_fee | formatNumber}}
                                                                    </template>
                                                                </v-data-table>
                                                            </div>

                                                        </div>
                                                        <div class="card mb-4" :class="tab=='matured'?'active show':'hide'" role="tabpanel">
                                                            <div class="table-responsive">
                                                                <v-data-table :headers="invoiceHeaders" :items="invoicesMatured" :items-per-page="10" class="elevation-1">

                                                                    <template v-slot:item.join="props">
                                                                        {{props.item.created_at | formatDate}}
                                                                    </template>

                                                                    <template v-slot:item.amou="props">
                                                                        {{props.item.amount | formatNumber}}
                                                                    </template>
                                                                    <template v-slot:item.dis="props">
                                                                        {{props.item.discount_amount | formatNumber}}
                                                                    </template>
                                                                    <template v-slot:item.tradingfee="props">
                                                                        {{props.item.trading_fee}}%
                                                                    </template>
                                                                    <template v-slot:item.tradingfeeAmount="props">
                                                                        {{props.item.trading_fee_amount | formatNumber}}
                                                                    </template>
                                                                    <template v-slot:item.processingfee="props">
                                                                        {{props.item.proccessing_fee | formatNumber}}
                                                                    </template>
                                                                </v-data-table>
                                                            </div>
                                                        </div>
                                                        <div class="card mb-4" :class="tab=='transaction'?'active show':'hide'">
                                                            <ul class="header-dropdown">
                                                                <li>
                                                                    <download-excel class="btn btn-info" :data="transactions" :fields="label" worksheet="My Worksheet" name="Transactions.xls" style="color:#fff"><i class="fas fa-cloud-download-alt" style="color:#fff"></i> &nbsp;Download Excel
                                                                    </download-excel>
                                                                </li>
                                                            </ul>
                                                            <div class="table-responsive">
                                                                <v-data-table :headers="transHeaders" :items="transactions" :items-per-page="10" class="elevation-1">
                                                                    <template #item.index="{ item }">
                                                                        {{ transactions.indexOf(item) + 1 }}
                                                                    </template>

                                                                    <template v-slot:item.amou="props">
                                                                        {{props.item.amount | formatNumber}}
                                                                    </template>

                                                                    <template v-slot:item.bala="props">
                                                                        {{props.item.balance | formatNumber}}
                                                                    </template>

                                                                    <template v-slot:item.join="props">
                                                                        {{props.item.created_at | formatDate}}
                                                                    </template>
                                                                    <template v-slot:item.stat="props">

                                                                        <span class="badge alert-success" v-if="props.item.status=='ACTIVE'">{{props.item.status}}</span>
                                                                        <span class="badge alert-danger" v-else>{{props.item.status}}</span>

                                                                    </template>

                                                                </v-data-table>
                                                            </div>
                                                        </div>

                                                        <div class="card mb-4" :class="tab=='notification'?'active show':'hide'">

                                                            <li class="list-group-item">
                                                                <b>Previous Stage</b>
                                                                <div class="profile-desc-item pull-right"><span class="badge alert-secondary">{{borrower.previous_stage}}</span></div>
                                                            </li>

                                                            <li class="list-group-item">
                                                                <b>Current Stage</b>
                                                                <div class="profile-desc-item pull-right"><span class="badge alert-success">{{borrower.current_stage}}</span></div>
                                                            </li>

                                                            <li class="list-group-item">
                                                                <b>Next Stage</b>
                                                                <div class="profile-desc-item pull-right"><span class="badge alert-danger">{{borrower.next_stage}}</span></div>
                                                            </li>

                                                            <li class="list-group-item">
                                                                <b>Status</b>
                                                                <div class="profile-desc-item pull-right"><span class="badge alert-primary">{{borrower.user.status}}</span></div>
                                                            </li>

                                                            <li class="list-group-item" v-if="user.role == 'Admin' &&  borrower.user.status != 'ACTIVE'">
                                                                <b>Action</b>
                                                                <div class="profile-desc-item pull-right">

                                                                    <form class="row g-3" @submit.prevent="actionSubmit">

                                                                        <div class="col-auto">
                                                                            <select class="form-control" v-model="option">
                                                                                <option value="accept">Accept</option>
                                                                                <option value="reject">Reject</option>
                                                                                <option value="reverse">Reverse</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-auto">
                                                                            <button type="submit" class="btn btn-confirm mb-3">Submit</button>
                                                                        </div>
                                                                    </form>

                                                                </div>
                                                            </li>
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
            option: "accept",
            title: "",
            borrower: [],
            loading: false,
            process: [],
            mobile: false,
            processes: [],
            approveForm: false,
            approveform:{
                id: "",
                reason: ""
            },
            action: "",
            dialog: false,
            processTrans: [],

            processesTrans: [],
            form: {
                score: 1,
                card: [],
                id: "",
                name: ""
            },
            reverseForm: {
                id: "",
                reason: ""
            },
            tab: "profile",
            debt: 0,
            staff: [],
            flow: [],
            errors: [],
            active: 'all',
            current: [],
            invoices: [],
            invoicesMatured: [],
            transactions: [],
            progress: 0,
            display: false,
            balance: 0,
            previusStage: null,
            nextStage: null,
            dropzoneOptions: {
                url: '../../api/uploadCard',
                thumbnailWidth: 200,
                addRemoveLinks: true,
                dictDefaultMessage: "<i class='fa fa-cloud-upload upload-icon'></i></i>Drag and drop a file here or click"
            },
            invoiceHeaders: [{
                    value: 'id',
                    text: 'ID',
                    sortable: false,
                },
                {
                    text: 'Invoice number',
                    value: 'invoice_number',
                },
                {
                    text: 'Owning company',
                    value: 'company.name',
                },
                {
                    text: 'Borrower',
                    value: 'borrower.user.name',
                },
                {
                    text: 'invoice Date',
                    value: 'invoice_date',
                },
                {
                    text: 'Maturity Date',
                    value: 'maturity_date',
                },
                {
                    text: "Model",
                    value: "model"
                },
                {
                    text: 'Amount',
                    value: 'amou',
                },
                {
                    text: 'Discounted Amount',
                    value: 'dis',
                },
                {
                    text: 'Trading fee',
                    value: 'trading_fee',
                },
                {
                    text: 'Trading fee amount',
                    value: 'tradingfeeAmount',
                },

                {
                    text: 'Proccessing fee',
                    value: 'processingfee',
                },
                {
                    text: "Invoice Status",
                    value: "inStatus"
                },

                {
                    text: 'Created At',
                    value: 'join'
                },
                {
                    text: 'Status',
                    value: "status"
                },

                {
                    text: "Action",
                    value: "controls",
                    sortable: false
                }
            ],

            transHeaders: [{
                    value: 'index',
                    text: '#',
                    sortable: false,
                },
                {
                    text: 'Transaction Id',
                    value: 'transactionId',
                },
                {
                    text: 'Mobile',
                    value: 'mobile',
                },
                {
                    text: 'Bank',
                    value: 'bank',
                },
                {
                    text: 'Reference',
                    value: 'reference',
                },
                {
                    text: 'Service',
                    value: 'service',
                },
                {
                    text: 'Channel',
                    value: 'channel',
                },
                {
                    text: 'Status',
                    value: 'stat',
                },
                {
                    text: 'Amount',
                    value: 'amou',
                },

                {
                    text: 'Balance',
                    value: 'bala',
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
                "Channel": "channel",
            },

        };
    },

    async beforeMount() {
        const id = this.$route.params.id;
        this.id = id;
        this.form.id = id;
        this.reverseForm.id = id;
        this.approveform.id = id;
        this.loading = true;

        this.user = JSON.parse(localStorage.getItem('user'));
        const response = await axios.get('../../api/borrower/' + id);

        const result = response.data;
        this.borrower = result;

        const balance = await axios.get('../api/user-balance/' + result.user_id);
        this.balance = balance.data;

        const debt = await axios.get('../../api/borrower-debt/' + result.id);
        this.debt = debt.data;

        this.progress = ((result.stage - 1) / this.processes.length) * 100;

        const respo = await axios.get('../api/borrower-invoice/' + result.id);
        this.invoices = respo.data;

        const respon = await axios.get('../api/borrower-invoice-matured/' + result.id);
        this.invoicesMatured = respon.data;

        const res = await axios.get('../../api/transaction-user/' + result.user_id);
        this.transactions = res.data;

        this.loading = false;

    },

    methods: {
        menuclick(value) {
            this.mobile = value
        },
        uploadSuccess(file, response) {
            this.form.card = response;

        },

        async actionSubmit() {
            if (this.option == "accept") {

                this.approve();

            } else if (this.option == "reject") {
                this.reject();
            } else if (this.option == 'reverse') {
                this.reverse();
            }

        },

        async approve() {

            try {

                var result = await this.$swal({
                    title: 'Are you sure you want to approve ' + this.borrower.user.name,
                    text: "You won't be able to revert this action!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#000',
                    confirmButtonText: 'Yes, confirm !'
                });
                if (result.isConfirmed) {
                    this.title = "Approve " + this.borrower.user.name;
                    this.action = 'approve'
                    this.dialog = true;
                   /* this.loading = true;
                    const response = await axios.get('../api/borrower-approved/' + this.borrower.id + '/' + this.staff.role.name + ' approved');
                    this.loading = false;
                    if (response.status == 200) {

                        this.$swal(
                            'Confirmed Refresh browser!',
                            response.data,
                            'success'
                        )
                        this.$router.push('../../borrower');
                    }*/

                }

            } catch (e) {
                console.log(e);
                this.$toast.open({
                    message: 'Internal server error',
                    type: 'error',
                    duration: 5000,
                    position: 'bottom-right'
                    // all of other options may go here
                });

                this.loading = false;
            }

        },

        async save() {
            this.loading = true;
            if (this.action == 'reverse') {

                const response = await axios.post('../api/borrower-reverse', this.reverseForm);
                this.loading = false;
                if (response.status == 200) {

                    this.$swal(
                        'Confirmed Refresh browser!',
                        response.data,
                        'success'
                    )
                    this.$router.push('../../borrower');
                }

            } else if (this.action == 'reject') {

                const response = await axios.post('../api/borrower-reject', this.reverseForm);
                this.loading = false;
                if (response.status == 200) {

                    this.$swal(
                        'Confirmed Refresh browser!',
                        response.data,
                        'success'
                    )
                    this.$router.push('../../borrower');
                }

            }else if(this.action == 'approve'){
                this.approveform.name ='Admin approved';
                const response = await axios.post('../api/borrower-approve', this.approveform);
                this.loading = false;
                if (response.status == 200) {

                    this.$swal(
                        'Confirmed Refresh browser!',
                        response.data,
                        'success'
                    )
                    this.$router.push('../../borrower');
                }

            }

            this.dialog = false;
        },
        close() {
            // Close the dialog without saving
            this.reverseForm.reason = "";
            this.dialog = false;
        },

        async saveApprove() {
            this.loading = true;
            this.approveSubmit();

            this.dialog = false;
        },
        closeApprove() {
            // Close the dialog without saving

            this.approveForm = false;
        },

        async reverse() {

            try {

                var result = await this.$swal({
                    title: 'Are you sure you want to reverse ' + this.borrower.user.name,
                    text: "You won't be able to revert this action!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#000',
                    confirmButtonText: 'Yes, confirm !'
                });
                if (result.isConfirmed) {
                    this.title = "Reverse " + this.borrower.user.name;
                    this.action = 'reverse'
                    this.dialog = true;
                    /**/

                }

            } catch (e) {
                console.log(e);
                this.$toast.open({
                    message: 'Internal server error',
                    type: 'error',
                    duration: 5000,
                    position: 'bottom-right'
                    // all of other options may go here
                });

                this.loading = false;
            }

        },

        async reject() {

            try {

                var result = await this.$swal({
                    title: 'Are you sure you want to reject ' + this.borrower.user.name,
                    text: "You won't be able to revert this action!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#000',
                    confirmButtonText: 'Yes, confirm !'
                });
                if (result.isConfirmed) {
                    this.title = "Reject " + this.borrower.user.name;
                    this.action = 'reject'
                    this.dialog = true;
                    /**/

                }

            } catch (e) {
                console.log(e);
                this.$toast.open({
                    message: 'Internal server error',
                    type: 'error',
                    duration: 5000,
                    position: 'bottom-right'
                    // all of other options may go here
                });

                this.loading = false;
            }

        },
        async filterData() {
            this.loading = true;
            const response = await axios.post('api/borrower-filter', this.filter);

            this.borrowers = response.data;
            this.loading = false;

        },

        async approveSubmit() {
            try {

                var result = await this.$swal({
                    title: 'Are you sure you want to approve ' + this.borrower.user.name,
                    text: "You won't be able to revert this action!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#000',
                    confirmButtonText: 'Yes, confirm !'
                });
                if (result.isConfirmed) {
                    const response = await axios.post('../api/borrower-approvedForm', this.form);
                    if (response.status == 200) {

                        this.loading = false;

                        this.$swal(
                            'Confirmed Refresh browser!',
                            response.data,
                            'success'
                        )
                        this.$router.push('../../borrower');
                    }

                }

            } catch (e) {
                console.log(e);
                this.$toast.open({
                    message: 'Internal server error',
                    type: 'error',
                    duration: 5000,
                    position: 'bottom-right'
                    // all of other options may go here
                });

                this.loading = false;
            }
        }

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

.img-account-profile {
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
