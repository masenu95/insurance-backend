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

    <v-dialog v-model="dialog" persistent max-width="500px">
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
                            <div class="row">
                                <div class="col-xl-12 col-md-12">

                                    <div class="row clearfix row-deck">

                                        <div class="col-6 col-md-4 col-xl-4">
                                            <div class="card" style="background-color: #7895B2 !important;">
                                                <div class="card-body">

                                                    <router-link to="/investor-bids" class="my_sort_cut text-muted">
                                                        <img style="height: 45px;width:45px" :src="host+'/images/profit.png'" />
                                                        <span style="font-weight: 800;">Total Investment</span>
                                                        <h5 style="font-weight: 800;color:#27445C;font-size:25px">{{basic.totalInvestment|formatNumber}}</h5>
                                                    </router-link>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-4 col-xl-4">
                                            <div class="card" style="background-color: #A0E4CB;">
                                                <div class="card-body">

                                                    <router-link to="/investor-bids" class="my_sort_cut text-muted">
                                                        <img style="color:#fff;height: 45px;width:45px" :src="host+'/images/funds.png'" />
                                                        <span style="font-weight: 800;">Outsanding Investment</span>
                                                        <h5 style="font-weight: 800;color:#27445C;font-size:25px">{{basic.outstandingInvestment|formatNumber}}</h5>
                                                    </router-link>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-4 col-xl-4">
                                            <div class="card" style="background-color: #AEBDCA !important;">
                                                <div class="card-body">

                                                    <router-link to="/investor-bids" class="my_sort_cut text-muted">
                                                        <img style="color:#fff;height: 45px;width:45px" :src="host+'/images/profits.png'" />
                                                        <span style="font-weight: 800;">Total Profit</span>
                                                        <h5 style="font-weight: 800;color:#27445C;font-size:25px">{{basic.profit|formatNumber}}</h5>
                                                    </router-link>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-6 col-xl-6">
                                            <div class="card" style="background-color: #E8DFCA !important;">
                                                <div class="card-body">

                                                    <router-link to="/investor-bids" class="my_sort_cut text-muted">
                                                        <img style="color:#fff;height: 45px;width:45px" :src="host+'/images/assets.png'" />
                                                        <span style="font-weight: 800;">Outsanding Profit</span>
                                                        <h5 style="font-weight: 800;color:#27445C;font-size:25px">{{basic.outsandingProfit|formatNumber}}</h5>
                                                    </router-link>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-6 col-md-6 col-xl-6">
                                            <div class="card" style="background-color: #000 !important;">
                                                <div class="card-body">

                                                    <router-link to="/investor-bids" class="my_sort_cut text-muted">
                                                        <img style="color:#fff;height: 45px;width:45px" :src="host+'/images/assets.png'" />
                                                        <span style="font-weight: 800;">Wallet</span>
                                                        <h5 style="font-weight: 800;color:#27445C;font-size:25px">{{investor.balance|formatNumber}}</h5>
                                                    </router-link>
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
                                                    <button class="nav-link" :class="tab=='bid'?'active':''" @click.prevent="tab='bid'">Bids</button>
                                                    <button class="nav-link" :class="tab=='matured'?'active':''" @click.prevent="tab='matured'">Matured Bids</button>
                                                    <button class="nav-link" :class="tab=='transaction'?'active':''" @click.prevent="tab='transaction'">Transaction</button>
                                                    <button class="nav-link" :class="tab=='notification'?'active':''" @click.prevent="tab='notification'">Notification</button>
                                                </nav>
                                                <hr class="mt-0 mb-4">
                                                <div class="row">
                                                    <div :class="tab=='profile'?'col-xl-4':'hide'">
                                                        <!-- Profile picture card-->
                                                        <div class="card mb-4 mb-xl-0">
                                                            <div class="card-header">Profile Picture</div>
                                                            <div class="card-body text-center">
                                                                <!-- Profile picture image-->
                                                                <img class="img-account-profile rounded-circle mb-2" src="http://bootdey.com/img/Content/avatar/avatar1.png" alt="">
                                                                <!-- Profile picture help block-->
                                                                <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                                                                <!-- Profile picture upload button
                        <button class="btn btn-primary" type="button">Upload new image</button>
                        -->
                                                            </div>
                                                        </div>
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
                                                                            <div class="description">{{investor.user.name}}</div>
                                                                        </div>
                                                                        <!-- Form Group (last name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Email</label>
                                                                            <div class="description">{{investor.user.email}}</div>
                                                                        </div>

                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Phone</label>
                                                                            <div class="description">{{investor.user.phone}}</div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row gx-3 mb-3">
                                                                        <!-- Form Group (first name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Physical Address</label>
                                                                            <div class="description">{{investor.physical}}</div>
                                                                        </div>
                                                                        <!-- Form Group (last name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">ID Type</label>
                                                                            <div class="description">{{investor.id_type}}</div>
                                                                        </div>

                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Id Number</label>
                                                                            <div class="description">{{investor.id_no}}</div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- Form Row        -->

                                                                    <!-- Form Group (email address)-->
                                                                    <div class="row gx-3 mb-3">
                                                                        <!-- Form Group (first name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Region</label>
                                                                            <div class="description">{{investor.region}}</div>
                                                                        </div>
                                                                        <!-- Form Group (last name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">District</label>
                                                                            <div class="description">{{investor.district}}</div>
                                                                        </div>

                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Ward</label>
                                                                            <div class="description">{{investor.ward}}</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row gx-3 mb-3">
                                                                        <!-- Form Group (first name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Street</label>
                                                                            <div class="description">{{investor.street}}</div>
                                                                        </div>
                                                                        <!-- Form Group (last name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Bank</label>
                                                                            <div class="description">{{investor.bankname}}</div>
                                                                        </div>

                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Account Number</label>
                                                                            <div class="description">{{investor.bankno}}</div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row gx-3 mb-3">
                                                                        <!-- Form Group (first name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">TIN</label>
                                                                            <div class="description">{{investor.tin}}</div>
                                                                        </div>
                                                                        <!-- Form Group (last name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Directors</label>
                                                                            <div class="description">{{investor.directors}}</div>
                                                                        </div>

                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Name of Md</label>
                                                                            <div class="description">{{investor.mdName}}</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row gx-3 mb-3">
                                                                        <!-- Form Group (first name)-->
                                                                        <div class="col-md-4">
                                                                            <label class="title-detail">Comments</label>
                                                                            <div class="description">{{investor.comment}}</div>
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
                                                                        <download-excel class="btn btn-info" :data="bids" :fields="labelBids" worksheet="My Worksheet" name="Bids.xls" style="color:#fff"><i class="fas fa-cloud-download-alt" style="color:#fff"></i> &nbsp;Download Excel
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
                                                        <div class="card mb-4" :class="tab=='matured'?'active show':'hide'" role="tabpanel">

                                                            <div class="table-responsive">
                                                                <div class="table-responsive">
                                                                    <ul class="header-dropdown">
                                                                        <li>
                                                                            <download-excel class="btn btn-info" :data="bidsMatured" :fields="labelBids" worksheet="My Worksheet" name="Bids.xls" style="color:#fff"><i class="fas fa-cloud-download-alt" style="color:#fff"></i> &nbsp;Download Excel
                                                                            </download-excel>
                                                                        </li>
                                                                    </ul>
                                                                    <v-data-table :headers="bidHeaders" :items="bidsMatured" :items-per-page="10" :search="search" class="elevation-1">
                                                                        <template #item.index="{ item }">
                                                                            {{ bidsMatured.indexOf(item) + 1 }}
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

                                                                    <template v-slot:item.bal="props">
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
                                                                <div class="profile-desc-item pull-right"><span class="badge alert-secondary">{{investor.previous_stage}}</span></div>
                                                            </li>

                                                            <li class="list-group-item">
                                                                <b>Current Stage</b>
                                                                <div class="profile-desc-item pull-right"><span class="badge alert-secondary">{{investor.current_stage}}</span></div>
                                                            </li>

                                                            <li class="list-group-item">
                                                                <b>Next Stage</b>
                                                                <div class="profile-desc-item pull-right"><span class="badge alert-secondary">{{investor.next_stage}}</span></div>
                                                            </li>

                                                            <li class="list-group-item">
                                                                <b>Status</b>
                                                                <div class="profile-desc-item pull-right"><span class="badge alert-primary">{{investor.user.status}}</span></div>
                                                            </li>

                                                            <li class="list-group-item" v-if="user.role == 'Admin'&& investor.user.status != 'ACTIVE'">
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
export default {
    name: 'WorkspaceJsonInvestor',

    data() {
        return {
            option: "accept",
            investor: [],
            mobile: false,
            host:window.location.protocol + "//" + window.location.host,
            active: 'all',
            title: "",
            progress: 0,
            loading: false,
            search: "",
            transactions: [],
            dialog: false,
            bidsMatured: [],
            bids: [],
            reverseForm: {
                id: "",
                reason: ""
            },
            form: {
                score: 1,
                card: [],
                id: "",
                name: ""
            },
            basic: {
                outsandingProfit: '',
                profit: '',
                totalInvestment: '',
                outstandingInvestment: ''
            },
            tab: "profile",
            profit: 0,
            invested: 0,
            balance: 0,
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
                    value: 'bal',
                },
                {
                    text: 'Created At',
                    value: 'join'
                },
                // { text: 'Action', value: 'actn',},

            ],
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
                    text: 'Borrower',
                    value: 'borrower.user.name',
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
            label: {
                "Transaction Id": "transactionId",
                "Mobile": "mobile",
                "Bank": "bank",
                "Name": "user.name",
                "Reference": "reference",
                "Service": "service",

                "Balance":"balance",
                "Amount": "amount",
                "Channel": "channel",
            },

            labelBids: {
                "Invoice number": "invoice.invoice_number",
                "Owning company": "company.name",
                "Borrower": "invoice.investor.user.name",
                "investor": "investor.user.name",
                "Invoice Date": "invoice.invoice_date",
                "Status": "status",

                "profit": "profit",
                "Payout": "payout_amount",
                "withholding amount": "withholding_amount",
                "Payout After Tax": "payout_after_tax"
            },

        };
    },

    async beforeMount() {
        this.loading = true;
        const id = this.$route.params.id;
        this.form.id = id;
        this.reverseForm.id = id;
        this.id = id;
        this.user = JSON.parse(localStorage.getItem('user'));
        const response = await axios.get('../../api/investor/' + id);

        const result = response.data;
        this.investor = result;

        const transa = await axios.get('../../api/transaction-user/' + result.user_id);
        this.transactions = transa.data;

        const bids = await axios.get('../../api/bid-investor/' + result.id);
        this.bids = bids.data;

        const mature = await axios.get('../../api/matured-bid-investor/' + result.id);
        this.bidsMatured = mature.data;

        const basic = await axios.get('../../api/investor-basic/' + id);
        this.basic = basic.data;

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
                    title: 'Are you sure you want to approve ' + this.investor.user.name,
                    text: "You won't be able to revert this action!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#000',
                    confirmButtonText: 'Yes, confirm !'
                });
                if (result.isConfirmed) {
                    this.loading = true;
                    const response = await axios.get('../api/investor-approved/' + this.investor.id + '/' + 'Admin approved');
                    this.loading = false;
                    if (response.status == 200) {

                        this.$swal(
                            'Confirmed Refresh browser!',
                            response.data,
                            'success'
                        )
                    }

                    this.$router.push('../../investors');

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

                const response = await axios.post('../api/investor-reverse', this.reverseForm);
                this.loading = false;
                if (response.status == 200) {

                    this.$swal(
                        'Confirmed Refresh browser!',
                        response.data,
                        'success'
                    )
                    this.$router.push('../../investors');
                }

            } else if (this.action == 'reject') {

                const response = await axios.post('../api/investor-reject', this.reverseForm);
                this.loading = false;
                if (response.status == 200) {

                    this.$swal(
                        'Confirmed Refresh browser!',
                        response.data,
                        'success'
                    )
                    this.$router.push('../../investors');
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
                    title: 'Are you sure you want to reverse ' + this.investor.user.name,
                    text: "You won't be able to revert this action!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#000',
                    confirmButtonText: 'Yes, confirm !'
                });
                if (result.isConfirmed) {
                    this.title = "Reverse " + this.investor.user.name;
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
                    title: 'Are you sure you want to reject ' + this.investor.user.name,
                    text: "You won't be able to revert this action!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#000',
                    confirmButtonText: 'Yes, confirm !'
                });
                if (result.isConfirmed) {
                    this.title = "Reject " + this.investor.user.name;
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

        showDialog() {
            this.isOpen = true;
        },
        closeDialog() {
            this.isOpen = false;
        },

    },
};
</script>


<style lang="css">
.approved {
    color: rgb(5, 128, 5) !important;

}
</style>
