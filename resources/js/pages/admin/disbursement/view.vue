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
        <v-dialog v-model="dialog" persistent>
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
        <div id="main_content" :class="mobile?'offcanvas-active':''">
            <!-- Start Main top header -->
            <admin-header v-on:childToParent="menuclick"></admin-header>
            <!-- Start Rightbar setting panel -->

            <!-- Start Main leftbar navigation -->
            <sidebar-left link="disbursement"></sidebar-left>
            <!-- Start project content area -->
            <!-- Start project content area -->
            <div class="page">
                <!-- Start Page header -->
                <top-nav></top-nav>
                <!-- Start Page title and tab -->
                <div class="section-body">
                    <div class="container-fluid">
                        <div class="d-flex justify-content-between align-items-center ">
                            <div class="header-action">
                                <h1 class="page-title">Disbursement</h1>

                            </div>
                            <ul class="nav nav-tabs page-header-tab">
                                <li class="nav-item"><a class="nav-link " :class="active=='all'?'active':''" @click.prevent="active='all'">List View</a></li>

                                <li class="nav-item"><a class="nav-link" :class="active=='detail'?'active':''">Detail</a></li>
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
                                                        <h4 class="header-title mt-0 mb-4">Invoice Number</h4>
                                                        <div class="widget-chart-1">
                                                            <div class="widget-detail-1 text-end">
                                                                <h2 class="fw-normal pt-2 mb-1">{{invoice.invoice.invoice_number}}</h2>
                                                                <p class="text-muted mb-1" style="color:#fff !important">Invoice Number</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-xl-4 col-md-4 col-sm-12 col-12">
                                                <div class="card" style="color:#fff;background-color:  rgb(160, 228, 203) !important;">
                                                    <div class="card-body">
                                                        <h4 class="header-title mt-0 mb-4">Available Amount</h4>
                                                        <div class="widget-chart-1">
                                                            <div class="widget-detail-1 text-end">
                                                                <h2 class="fw-normal pt-2 mb-1">{{invoice.invoice.available |formatNumber}}</h2>
                                                                <p class="text-muted mb-1" style="color:#fff !important">Available amount</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-xl-4 col-md-4 col-sm-12 col-12">
                                                <div class="card" style="background-color: rgb(232, 223, 202) !important;">
                                                    <div class="card-body">
                                                        <h4 class="header-title mt-0 mb-4">Bid amount</h4>
                                                        <div class="widget-chart-1">
                                                            <div class="widget-detail-1 text-end">
                                                                <h2 class="fw-normal pt-2 mb-1">{{amounts |formatNumber}}</h2>
                                                                <p class="text-muted mb-1" style="color:#fff !important">Bid Amount</p>
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
                                                        <button class="nav-link  ms-0" :class="tab=='about'?'active':''" @click.prevent="tab='about'">Invoice Detail</button>
                                                        <button class="nav-link" :class="tab=='bid'?'active':''" @click.prevent="tab='bid'">Bids</button>
                                                        <button class="nav-link" :class="tab=='transaction'?'active':''" @click.prevent="tab='transaction'">Transaction</button>
                                                        <button class="nav-link" :class="tab=='notification'?'active':''" @click.prevent="tab='notification'">Notification</button>
                                                    </nav>
                                                    <hr class="mt-0 mb-4">
                                                    <div class="row">
                                                        <div class="col-xl-4">
                                                            <!-- Profile picture card-->
                                                            <div class="card mb-4 mb-xl-0">
                                                                <div class="card-header">Invoice File</div>
                                                                <div class="card-body text-center">
                                                                    <i class="fas fa-file-pdf icon-style"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-8">
                                                            <!-- Account details card-->
                                                            <div class="card mb-4" :class="tab=='about'?'active show':'hide'">
                                                                <div class="card-header">Invoice Details</div>
                                                                <div class="card-body">
                                                                    <form>
                                                                        <!-- Form Group (username)-->

                                                                        <!-- Form Row-->
                                                                        <div class="row gx-3 mb-3">
                                                                            <!-- Form Group (first name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Invoice Number</label>
                                                                                <div class="description">{{invoice.invoice.invoice_number}}</div>
                                                                            </div>
                                                                            <!-- Form Group (last name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Owning Company</label>
                                                                                <div class="description">{{invoice.invoice.company.name}}</div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Borrower</label>
                                                                                <div class="description">{{invoice.invoice.borrower.user.name}}</div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row gx-3 mb-3">
                                                                            <!-- Form Group (first name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Invoice Date</label>
                                                                                <div class="description">{{invoice.invoice.invoice_date|formatDate}}</div>
                                                                            </div>
                                                                            <!-- Form Group (last name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Maturity Date</label>
                                                                                <div class="description">{{invoice.invoice.maturity_date|formatDate}}</div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Amount</label>
                                                                                <div class="description">{{invoice.invoice.currency}} {{invoice.invoice.amount|formatNumber}}</div>
                                                                            </div>
                                                                        </div>
                                                                        <!-- Form Row        -->

                                                                        <!-- Form Group (email address)-->
                                                                        <div class="row gx-3 mb-3">
                                                                            <!-- Form Group (first name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Percentage discount</label>
                                                                                <div class="description">{{invoice.invoice.discount_perc}}</div>
                                                                            </div>
                                                                            <!-- Form Group (last name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Discount amount</label>
                                                                                <div class="description">{{invoice.invoice.currency}} {{(invoice.invoice.amount - invoice.invoice.discount_amount)|formatNumber}}</div>
                                                                            </div>

                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Amount after discount</label>
                                                                                <div class="description">{{invoice.invoice.currency}} {{invoice.invoice.discount_amount|formatNumber}}</div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="row gx-3 mb-3">
                                                                            <!-- Form Group (first name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Rate</label>
                                                                                <div class="description">{{invoice.invoice.rate}}</div>
                                                                            </div>
                                                                            <!-- Form Group (last name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Directors</label>
                                                                                <div class="description">{{invoice.invoice.directors}}</div>
                                                                            </div>

                                                                        </div>
                                                                        <div class="row gx-3 mb-3">
                                                                            <!-- Form Group (first name)-->
                                                                            <div class="col-md-4">
                                                                                <label class="title-detail">Comments</label>
                                                                                <div class="description">{{invoice.invoice.comment}}</div>
                                                                            </div>
                                                                            <!-- Form Group (last name)-->

                                                                        </div>
                                                                        <!-- Form Row-->

                                                                    </form>
                                                                </div>
                                                            </div>

                                                            <div class="card mb-4" :class="tab=='bid'?'active show':'hide'" role="tabpanel">
                                                                <div class="table-responsive">
                                                                    <ul class="header-dropdown">
                                                                        <li>
                                                                            <download-excel class="btn btn-info" :data="bids" :fields="label" worksheet="My Worksheet" name="Bids.xls" style="color:#fff"><i class="fas fa-cloud-download-alt" style="color:#fff"></i> &nbsp;Download Excel
                                                                            </download-excel>
                                                                        </li>
                                                                    </ul>
                                                                    <v-data-table :headers="bidHeaders" :items="bids" :items-per-page="10" :search="search" class="elevation-1">
                                                                        <template #item.index="{ item }">
                                                                            {{ bids.indexOf(item) + 1 }}
                                                                        </template>
                                                                        <template v-slot:item.controls="props">

                                                                        </template>

                                                                        <template v-slot:item.stat="props">
                                                                            <span class="badge alert-success" v-if="props.item.status=='ACTIVE'">{{props.item.status}}</span>
                                                                            <span class="badge alert-danger" v-else>{{props.item.status}}</span>
                                                                        </template>

                                                                        <template v-slot:item.join="props">
                                                                            {{props.item.created_at | formatDate}}
                                                                        </template>

                                                                        <template v-slot:item.maturity="props">
                                                                            {{props.item.invoice.maturity_date | formatDate}}
                                                                        </template>
                                                                        <template v-slot:item.remain="props">
                                                                            {{daysCalc(props.item.invoice.maturity_date )| formatNumber}}
                                                                        </template>

                                                                        <template v-slot:item.invested="props">

                                                                            {{props.item.amount | formatNumber}}
                                                                        </template>

                                                                        <template v-slot:item.availabl="props">

                                                                            {{props.item.invoice.available | formatNumber}}
                                                                        </template>
                                                                        <template v-slot:item.profits="props">

                                                                            {{props.item.profit | formatNumber}}
                                                                        </template>
                                                                        <template v-slot:item.gprofits="props">

                                                                            {{parseInt(props.item.profit)+parseInt(props.item.withholding_amount) | formatNumber}}
                                                                        </template>
                                                                        <template v-slot:item.payout="props">

                                                                            {{props.item.payout_amount | formatNumber}}
                                                                        </template>
                                                                        <template v-slot:item.withholdingAmount="props">

                                                                            {{props.item.withholding_amount | formatNumber}}
                                                                        </template>

                                                                        <template v-slot:item.payoutAfterTax="props">

                                                                            {{props.item.payout_after_tax| formatNumber}}
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
                                                                    <div class="profile-desc-item pull-right"><span class="badge alert-secondary">{{invoice.previous_stage}}</span></div>
                                                                </li>

                                                                <li class="list-group-item">
                                                                    <b>Current Stage</b>
                                                                    <div class="profile-desc-item pull-right"><span class="badge alert-success">{{invoice.current_stage}}</span></div>
                                                                </li>

                                                                <li class="list-group-item">
                                                                    <b>Next Stage</b>
                                                                    <div class="profile-desc-item pull-right"><span class="badge alert-danger">{{invoice.next_stage}}</span></div>
                                                                </li>

                                                                <li class="list-group-item">
                                                                    <b>Status</b>
                                                                    <div class="profile-desc-item pull-right"><span class="badge alert-primary">{{invoice.status}}</span></div>
                                                                </li>

                                                                <li class="list-group-item" v-if="invoice.status != 'ACTIVE'">
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

                                            <div class="card-footer text-center">
                                                <div class="row">
                                                    <button class="col-md-12 col-sm-12 col-12 btn btn-primary" @click.prevent="approve" v-if="invoice.status == 'PENDING'">

                                                        <div>Approve</div>
                                                    </button>

                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <!-- <h6>Bids</h6>
                            <ul class="header-dropdown">
                                        <li>
                                         <download-excel
                                              class="btn btn-info"
                                              :data="invoice.invoice.bids"
                                              :fields="label"
                                              worksheet="My Worksheet"
                                              name="Bids.xls"
                                              style="color:#fff"
                                          ><i class="fas fa-cloud-download-alt" style="color:#fff"></i> &nbsp;Download Excel
                                          </download-excel></li></ul>
                            <v-data-table
                            :headers="bidHeaders"
                            :items="invoice.invoice.bids"
                            :items-per-page="10"
                            :search="search"
                            class="elevation-1"
                          >
                            <template #item.index="{ item }" >
                              {{ invoice.invoice.bids.indexOf(item) + 1 }}
                            </template>
                            <template v-slot:item.controls="props" >

                                      </template>

                            <template v-slot:item.stat="props">
                              <span
                                class="badge alert-success"
                                v-if="props.item.status=='ACTIVE'"
                                >{{props.item.status}}</span
                              >
                              <span
                                class="badge alert-danger"
                                v-else
                                >{{props.item.status}}</span
                              >
                            </template>

                            <template v-slot:item.join="props">
                              {{props.item.created_at | formatDate}}
                            </template>

                            <template v-slot:item.maturity="props">
                              {{props.item.invoice.invoice.maturity_date | formatDate}}
                            </template>
                            <template v-slot:item.remain="props">
                              {{daysCalc(props.item.invoice.invoice.maturity_date )| formatNumber}}
                            </template>

                            <template v-slot:item.invested="props">

                              {{props.item.amount | formatNumber}}
                            </template>

                            <template v-slot:item.availabl="props">

                              {{props.item.invoice.invoice.available | formatNumber}}
                            </template>
                            <template v-slot:item.profits="props">

                              {{props.item.profit | formatNumber}}
                            </template>
                            <template v-slot:item.gprofits="props">

                              {{parseInt(props.item.profit)+parseInt(props.item.withholding_amount) | formatNumber}}
                            </template>
                            <template v-slot:item.payout="props">

                              {{props.item.payout_amount | formatNumber}}
                            </template>
                            <template v-slot:item.withholdingAmount="props">

                              {{props.item.withholding_amount | formatNumber}}
                            </template>

                            <template v-slot:item.payoutAfterTax="props">

                              {{props.item.payout_after_tax| formatNumber}}
                            </template>

                          </v-data-table>-->
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

        name: 'WorkspaceJsonBank',

        data() {
            return {
                tab: "about",
                option: "reverse",
                load: false,
                mobile: false,
                loading: false,
                invoice: [],
                bids: [],
                disbusmentStage: [],
                dialog:false,
                search: "",
                active: "all",
                title:"",
                int: 1,
                progress: 0,
                id: null,
                previusStage: null,
                nextStage: null,
                process: [],
                processes: [],
                display: false,
                flow: [],
                transactions: [],
                amounts: 0,
                label: {
                    "Invoice number": "invoice.invoice.invoice_number",
                    "Owning company": "company.name",
                    "Borrower": "invoice.invoice.borrower.user.name",
                    "investor": "investor.user.name",
                    "Invoice Date": "invoice.invoice.invoice_date",
                    "Status": "status",

                    "profit": "profit",
                    "Payout": "payout_amount",
                    "withholding amount": "withholding_amount",
                    "Payout After Tax": "payout_after_tax"
                },

                bidHeaders: [{
                        value: 'index',
                        text: '#',
                        sortable: false,
                    },
                    {
                        text: 'Invoice number',
                        value: 'invoice.invoice_number',
                    },
                    {
                        text: 'Bid number',
                        value: 'bid_no',
                    },

                    {
                        text: 'TIN',
                        value: 'TIN',
                    },

                    {
                        text: 'Investor',
                        value: 'investor.user.name',
                    },
                    {
                        text: 'Invested Amount',
                        value: 'invested',
                    },
                    {
                        text: 'Avaiable',
                        value: 'availabl',
                    },
                    {
                        text: 'Bid rate(%)',
                        value: 'interest_rate',
                    },
                    {
                        text: 'Profit',
                        value: 'gprofits',
                    },
                    {
                        text: 'withholding tax',
                        value: 'withholdingAmount',
                    },
                    {
                        text: 'Net Profit',
                        value: 'profits',
                    },

                    {
                        text: 'Payout',
                        value: 'payout',
                    },

                    {
                        text: 'Payout After Tax',
                        value: 'payoutAfterTax',
                    },
                    {
                        text: 'Maturity Date',
                        value: 'maturity',
                    },
                    {
                        text: 'Remain days',
                        value: 'remain',
                    },

                    {
                        text: 'Created At',
                        value: 'join'
                    },
                    {
                        text: 'Status',
                        value: "stat"
                    },

                ],
                reverseForm: {
                    id: "",
                    reason: ""
                },
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
            };
        },

        async beforeMount() {
            this.loading = true;
            const id = this.$route.params.id;
            this.id = id;
            const res = await axios.get('../../api/disbursement/' + id);




            this.load = true;
            this.active = "detail";

            const result = res.data;
            this.invoice = result;

            const bid = await axios.get('../../api/bid-by-invoice/' + this.invoice.invoice.id);
            this.bids = bid.data;

            this.load = false;
            this.loading = false;

        },

        mounted() {

        },

        methods: {
            async filterData() {
                this.loading = true;
                const response = await axios.post('../../api/invoicefilter', this.filter);

                this.invoices = response.data;
                this.loading = false;

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

            menuclick(value) {
                this.mobile = value
            },
            async show(uid) {

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
                        title: 'Are you sure you want to approve disbursement of invoice no: ' + this.invoice.invoice.invoice_number,
                        text: "You won't be able to revert this action!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#000',
                        confirmButtonText: 'Yes, confirm !'
                    });
                    if (result.isConfirmed) {
                        const response = await axios.get('../../../api/disburse-approve/' + this.invoice.id + '/' + ' approved');
                        if (response.status == 200) {
                            this.loading = false;

                            this.$swal(
                                'Confirmed Refresh browser!',
                                response.data,
                                'success'
                            );
                            this.$router.push('../../disbursement');

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

            },
            async save() {
                this.loading = true;
                if (this.action == 'reverse') {

                    const response = await axios.post('../api/disburse-reverse', this.reverseForm);
                    this.loading = false;
                    if (response.status == 200) {

                        this.$swal(
                            'Confirmed Refresh browser!',
                            response.data,
                            'success'
                        )
                        this.$router.push('../../disbursement');
                    }

                } else if (this.action == 'reject') {

                    const response = await axios.post('../api/disburse-reject', this.reverseForm);
                    this.loading = false;
                    if (response.status == 200) {

                        this.$swal(
                            'Confirmed Refresh browser!',
                            response.data,
                            'success'
                        )
                        this.$router.push('../../disbursement');
                    }

                }

                this.dialog = false;
            },
            close() {
                // Close the dialog without saving
                this.reverseForm.reason = "";
                this.dialog = false;
            },

            async reverse() {

                try {

                    var result = await this.$swal({
                        title: 'Are you sure you want to reverse ' + this.invoice.invoice.invoice_number,
                        text: "You won't be able to revert this action!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#000',
                        confirmButtonText: 'Yes, confirm !'
                    });
                    if (result.isConfirmed) {
                        this.title = "Reverse " + this.invoice.invoice.invoice_number;
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
                        title: 'Are you sure you want to reject ' + this.invoice.invoice.invoice_number,
                        text: "You won't be able to revert this action!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#000',
                        confirmButtonText: 'Yes, confirm !'
                    });
                    if (result.isConfirmed) {
                        this.title = "Reject " + this.invoice.invoice.invoice_number;
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
            async removeItems(id, index) {
                var result = await this.$swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this invoice",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                });
                if (result.isConfirmed) {
                    if (response.status == 200) {

                        this.$swal(
                            'Deleted!',
                            response.data,
                            'success'
                        )
                        const res = await axios.get('../../api/invoices');
                        this.invoices = res.data;
                    }

                }

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

    <style lang="scss" scoped>

    </style>
