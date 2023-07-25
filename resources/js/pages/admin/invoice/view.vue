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
    <div id="main_content" :class="mobile?'offcanvas-active':''">
        <!-- Start Main top header -->
        <admin-header v-on:childToParent="menuclick"></admin-header>
        <!-- Start Rightbar setting panel -->

        <!-- Start Main leftbar navigation -->
        <sidebar-left link="invoice"></sidebar-left>
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
                            <h1 class="page-title">Invoice</h1>

                        </div>
                        <ul class="nav nav-tabs page-header-tab">
                            <li class="nav-item">
                                <router-link to="../../invoice" class="nav-link " :class="active=='all'?'active':''">List View</router-link>
                            </li>

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
                                                            <h2 class="fw-normal pt-2 mb-1">{{invoice.invoice_number}}</h2>
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
                                                            <h2 class="fw-normal pt-2 mb-1">{{invoice.available |formatNumber}}</h2>
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

                                                    <div :class="tab=='profile'?'col-xl-4':'hide'">
                                                        <!-- Profile picture card-->
                                                        <a :href="url+JSON.parse(invoice.images).url">
                                                            <div class="card mb-4 mb-xl-0">
                                                                <div class="card-header">Invoice File</div>
                                                                <div class="card-body text-center">
                                                                    <i class="fas fa-file-pdf icon-style"></i>
                                                                </div>
                                                            </div>
                                                        </a>
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
                                                                            <div class="description">{{invoice.invoice_number}}</div>
                                                                        </div>
                                                                        <!-- Form Group (last name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Owning Company</label>
                                                                            <div class="description">{{invoice.company.name}}</div>
                                                                        </div>

                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Borrower</label>
                                                                            <div class="description">{{invoice.borrower.user.name}}</div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row gx-3 mb-3">
                                                                        <!-- Form Group (first name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Invoice Date</label>
                                                                            <div class="description">{{invoice.invoice_date|formatDate}}</div>
                                                                        </div>
                                                                        <!-- Form Group (last name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Maturity Date</label>
                                                                            <div class="description">{{invoice.maturity_date|formatDate}}</div>
                                                                        </div>

                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Amount</label>
                                                                            <div class="description">{{invoice.currency}} {{invoice.amount|formatNumber}}</div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- Form Row        -->

                                                                    <!-- Form Group (email address)-->
                                                                    <div class="row gx-3 mb-3">
                                                                        <!-- Form Group (first name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Percentage discount</label>
                                                                            <div class="description">{{invoice.discount_perc}}</div>
                                                                        </div>
                                                                        <!-- Form Group (last name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Discount amount</label>
                                                                            <div class="description">{{invoice.currency}} {{(invoice.amount - invoice.discount_amount)|formatNumber}}</div>
                                                                        </div>

                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Amount after discount</label>
                                                                            <div class="description">{{invoice.currency}} {{invoice.discount_amount|formatNumber}}</div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row gx-3 mb-3">
                                                                        <!-- Form Group (first name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Rate</label>
                                                                            <div class="description">{{invoice.rate}}</div>
                                                                        </div>
                                                                        <!-- Form Group (last name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Directors</label>
                                                                            <div class="description">{{invoice.directors}}</div>
                                                                        </div>

                                                                    </div>
                                                                    <div class="row gx-3 mb-3">
                                                                        <!-- Form Group (first name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Comments</label>
                                                                            <div class="description">{{invoice.comment}}</div>
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
                                                                        <download-excel class="btn btn-info" :data="invoice.bids" :fields="label" worksheet="My Worksheet" name="Bids.xls" style="color:#fff"><i class="fas fa-cloud-download-alt" style="color:#fff"></i> &nbsp;Download Excel
                                                                        </download-excel>
                                                                    </li>
                                                                </ul>
                                                                <v-data-table :headers="bidHeaders" :items="invoice.bids" :items-per-page="10" :search="search" class="elevation-1">
                                                                    <template #item.index="{ item }">
                                                                        {{ invoice.bids.indexOf(item) + 1 }}
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
                                                                        <span v-if="daysCalc(props.item.invoice.maturity_date ) >= 0">
                                                                            {{daysCalc(props.item.invoice.maturity_date )| formatNumber}}
                                                                        </span>
                                                                        <span class="badge alert-danger" v-else>Matured</span>
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

                                                            <li class="list-group-item" v-if="invoice.invoice_status !='ACTIVE'">
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

                                        <!--<div class="card-header">
                                                <h3 class="card-title">About</h3>

                                            </div>
                                            <div class="card-body">
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" :style="'width:'+progress+'%'" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">{{Math.round(progress)+'%'}}</div>
                                                </div>

                                                <ul class="list-group">
                                                    <li class="list-group-item">
                                                        <b>Invoice Number </b>
                                                        <div class="profile-desc-item pull-right">{{invoice.invoice_number.toUpperCase()}}</div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <b>Owning Company</b>
                                                        <div class="profile-desc-item pull-right">{{invoice.company.name.toUpperCase()}}</div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <b>Borrower</b>
                                                        <div class="profile-desc-item pull-right">{{invoice.borrower.user.name.toUpperCase()}} </div>
                                                    </li>

                                                    <li class="list-group-item">
                                                        <b>Invoice Date</b>
                                                        <div class="profile-desc-item pull-right">{{invoice.invoice_date|formatDate}}</div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <b>Maturity date </b>
                                                        <div class="profile-desc-item pull-right">{{invoice.maturity_date|formatDate}}</div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <b>Amount</b>
                                                        <div class="profile-desc-item pull-right">{{invoice.currency}} {{invoice.amount|formatNumber}}</div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <b>Percentage discount </b>
                                                        <div class="profile-desc-item pull-right">{{invoice.discount_perc}}</div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <b>Discount amount</b>
                                                        <div class="profile-desc-item pull-right">{{invoice.currency}} {{(invoice.amount - invoice.discount_amount)|formatNumber}}</div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <b>Amount after discount</b>
                                                        <div class="profile-desc-item pull-right">{{invoice.currency}} {{invoice.discount_amount|formatNumber}}</div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <b>Rate</b>
                                                        <div class="profile-desc-item pull-right">{{invoice.interest_rate}}</div>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <b>Previous Stage</b>
                                                        <div class="profile-desc-item pull-right"><span class="badge alert-secondary">{{previusStage.role.name}}</span></div>
                                                    </li>

                                                    <li class="list-group-item">
                                                        <b>Current Stage</b>
                                                        <div class="profile-desc-item pull-right"><span class="badge alert-success">{{current.role.name}}</span></div>
                                                    </li>

                                                    <li class="list-group-item">
                                                        <b>Next Stage</b>
                                                        <div class="profile-desc-item pull-right"><span class="badge alert-danger">{{nextStage.role.name}}</span></div>
                                                    </li>

                                                    <li class="list-group-item">
                                                        <b>Invoice Status</b>
                                                        <div class="profile-desc-item pull-right">
                                                            <span v-if="invoice.invoice_status =='ACTIVE'" class="badge alert-success">ACTIVE</span>
                                                            <span v-else class="badge alert-danger">{{invoice.invoice_status}}</span>
                                                        </div>
                                                    </li>

                                                    <li class="list-group-item">
                                                        <b>Status</b>
                                                        <div class="profile-desc-item pull-right"><span class="badge alert-primary">{{invoice.status}}</span></div>
                                                    </li>

                                                    <li class="list-group-item">
                                                        <b>View</b>
                                                        <div class="profile-desc-item pull-right">

                                                            <a :href="JSON.parse(invoice.images).url" class="badge alert-info"> View Invoice</a>
                                                        </div>
                                                    </li>

                                                </ul>

                                            </div>-->
                                        <div class="card-footer text-center" v-if="(invoice.status =='CLOSED'||invoice.status =='CLOSE') && invoice.invoice_status=='ACTIVE'">
                                            <div class="row">
                                                <button class="col-md-12 col-sm-12 col-12 btn btn-primary" @click.prevent="disburseInvoice">

                                                    <div>Disburse invoice</div>
                                                </button>

                                            </div>
                                        </div>
                                        <div class="card-footer text-center" v-if="invoice.status =='DISBURSED'||invoice.status =='PAID' && invoice.invoice_status=='ACTIVE'">
                                            <div class="row">
                                                <button class="col-md-12 col-sm-12 col-12 btn btn-primary" @click.prevent="repayInvoice">

                                                    <div>REPAY INVESTOR</div>
                                                </button>

                                            </div>
                                        </div>
                                        <!--<div class="card-footer text-center">
                                                <div class="row">
                                                    <button class="col-md-12 col-sm-12 col-12 btn btn-primary" @click.prevent="approve" v-if="invoice.stage == this.process.stage">

                                                        <div>Approve</div>
                                                    </button>

                                                </div>-->
                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>
        <!-- Start main footer -->
        <footer-component></footer-component>
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
            option: "accept",
            url: window.location.protocol + "//" + window.location.host,
            title: "",
            tab: "about",
            loading: false,
            mobile: false,
            loading: false,
            action: "",
            dialog: false,
            invoice: [],
            disbusmentStage: [],
            reverseForm: {
                id: "",
                reason: ""
            },
            search: "",
            active: "all",
            int: 1,
            form: {
                invoice_number: "",
                invoice_date: "",
                company: "",
                maturity_date: "",
                amount: "",
                currency: "TZS",
                model: "OPEN",
                image: "",
                percent: 80,
                final: 0,

            },
            formEdit: {
                invoice_number: "",
                invoice_date: "",
                company: "",
                maturity_date: "",
                amount: "",
                currency: "TZS",
                model: "OPEN",
                image: "",
                percent: 80,
                final: 0,
                _method: 'patch'

            },
            dropzoneOptions: {
                url: '../../../../api/uploadInvoice',
                thumbnailWidth: 200,
                addRemoveLinks: true,
                dictDefaultMessage: "<i class='fa fa-cloud-upload upload-icon'></i></i>Drag and drop a file here or click"
            },
            companies: [],
            errors: [],
            errorsEdit: [],
            progress: 0,
            id: null,
            previusStage: null,
            nextStage: null,
            process: [],
            processes: [],
            display: false,

            flow: [],

            label: {
                "Invoice number": "invoice.invoice_number",
                "Owning company": "company.name",
                "Borrower": "invoice.borrower.user.name",
                "investor": "investor.user.name",
                "Invoice Date": "invoice.invoice_date",
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
        };
    },

    async beforeMount() {
        this.loading = true;
        const id = this.$route.params.id;
        this.id = id;
        const res = await axios.get('../../api/invoices/' + id);

        this.active = "detail";

        const result = res.data;
        this.invoice = result;

        this.reverseForm.id = id;

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
        async disburseInvoice() {

            try {

                var result = await this.$swal({
                    title: 'Are you sure you want to initiate disbursement ' + this.invoice.invoice_number,
                    text: "You won't be able to revert this action!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#000',
                    confirmButtonText: 'Yes, confirm !'
                });
                if (result.isConfirmed) {
                    const response = await axios.post('../../../api/disbursement', {
                        'invoice_id': this.invoice.id,
                    });
                    if (response.status == 200) {
                        this.loading = false;

                        this.$swal(
                            'Confirmed Refresh browser!',
                            response.data,
                            'success'
                        );
                        this.$router.push('../../invoice');

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

        async repayInvoice() {

            try {

                var result = await this.$swal({
                    title: 'Are you sure you want to initiate bid repayment  ' + this.invoice.invoice_number,
                    text: "You won't be able to revert this action!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#000',
                    confirmButtonText: 'Yes, confirm !'
                });
                if (result.isConfirmed) {
                    const response = await axios.post('../../../api/bid-repay', {
                        'invoice_id': this.invoice.id,
                    });
                    if (response.status == 200) {
                        this.loading = false;

                        this.$swal(
                            'Confirmed Refresh browser!',
                            response.data,
                            'success'
                        );
                        this.$router.push('../../invoice');

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
        async approve() {

            try {

                var result = await this.$swal({
                    title: 'Are you sure you want to approve ' + this.invoice.invoice_number,
                    text: "You won't be able to revert this action!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#000',
                    confirmButtonText: 'Yes, confirm !'
                });
                if (result.isConfirmed) {
                    this.loading = true;
                    const response = await axios.get('../../../api/invoice-approved/' + this.invoice.id + '/' + ' approved');
                    if (response.status == 200) {
                        this.loading = false;

                        this.$swal(
                            'Confirmed Refresh browser!',
                            response.data,
                            'success'
                        );
                        this.$router.push('../../invoice');

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

                const response = await axios.post('../api/invoice-reverse', this.reverseForm);
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

                const response = await axios.post('../api/invoice-reject', this.reverseForm);
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

        async reverse() {

            try {

                var result = await this.$swal({
                    title: 'Are you sure you want to reverse ' + this.invoice.invoice_number,
                    text: "You won't be able to revert this action!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#000',
                    confirmButtonText: 'Yes, confirm !'
                });
                if (result.isConfirmed) {
                    this.title = "Reverse " + this.invoice.invoice_number;
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
                    title: 'Are you sure you want to reject ' + this.invoice.invoice_number,
                    text: "You won't be able to revert this action!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#000',
                    confirmButtonText: 'Yes, confirm !'
                });
                if (result.isConfirmed) {
                    this.title = "Reject " + this.invoice.invoice_number;
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
                var response = await axios.delete('../../../../api/invoices/' + id);
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
