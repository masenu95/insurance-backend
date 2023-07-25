<template>
    <v-app>
        <div id="main_content" :class="mobile?'offcanvas-active':''">
    <!-- Start Main top header -->
<admin-header v-on:childToParent="menuclick"></admin-header>

    <!-- Start Theme panel do not add in project -->

    <!-- Start Quick menu with more functio -->

    <!-- Start Main leftbar navigation -->
       <sidebar-left link="Customer"></sidebar-left>
    <!-- Start project content area -->
    <div class="page">
        <!-- Start Page header -->
        <top-nav></top-nav>
        <!-- Start Page title and tab -->
        <div class="section-body">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="header-action">
                        <h1 class="page-title">Asset Customers</h1>
                        <ol class="breadcrumb page-breadcrumb">
                            <li class="breadcrumb-item"><a href="#">BimaKwik</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Asset Customers</li>
                        </ol>
                    </div>
                    <ul class="nav nav-tabs page-header-tab">
                        <li class="nav-item"><a class="nav-link" :class="active=='all'?'active':''" @click.prevent="active='all'">Asset Customers</a></li>
                        <li class="nav-item"><a class="nav-link" :class="active=='detail'?'active':''">Details</a></li>

                    </ul>
                </div>
            </div>
        </div>

         <div class="section-body mt-4">
            <div class="container-fluid">
                <div class="tab-content">

                    <div class="tab-pane active">
                             <div class="row"  v-if="active=='detail'&&load==false">
                            <div class="col-xl-12 col-md-12">
                                <div class="card">
                                    <div class="card-body w_user">

                                        <div  class="wid-u-info" style="text-align:center">
                                            <h5>{{customer.name.toUpperCase()}}</h5>

                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">About</h3>

                                    </div>
									<div class="card-body">


										<ul class="list-group">
											<li class="list-group-item">
												<b>Email </b>
												<div class="profile-desc-item pull-right">{{customer.email}}</div>
											</li>
                                            <li class="list-group-item">
												<b>Phone</b>
												<div class="profile-desc-item pull-right">{{customer.phone}}</div>
											</li>
                                            <li class="list-group-item">
												<b>Insurance </b>
												<div class="profile-desc-item pull-right">{{customer.insurance}}</div>
											</li>

                                             <li class="list-group-item">
												<b>Technology</b>
												<div class="profile-desc-item pull-right">{{customer.technology}}</div>
											</li>
                                             <li class="list-group-item">
												<b>Total </b>
												<div class="profile-desc-item pull-right">{{customer.total}}</div>
											</li>
                                            <li class="list-group-item">
												<b>Initial</b>
												<div class="profile-desc-item pull-right">{{customer.initial}}</div>
											</li>
                                            <li class="list-group-item">
												<b>Loan</b>
												<div class="profile-desc-item pull-right">{{customer.loan}}</div>
											</li>


                                           <!--<li class="list-group-item">
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
											</li>-->

                                        </ul>
                                        </div>
                                </div>


                                <div class="card">
                                <div class="card-header">
                                    <nav>
                                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                            <button class="nav-link" :class="tab=='first'?'active':''"  style="margin-right:10px" @click.prevent="tab='first'"  type="button" >Transactions</button>

                                        </div>
                                    </nav>
                                </div>
                                   <div class="card-body">
                                    <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade" :class="tab=='first'?'active show':''"  role="tabpanel" >
                                        <div class="table-responsive">
                                            <v-data-table
                                :headers="transHeaders"
                                :items="customer.transactions"
                                :items-per-page="10"
                                 :search="search"


                                class="elevation-1">
                                <template #item.index="{ item }">
                                    {{ transactions.indexOf(item) + 1 }}
                                </template>




                                     <template v-slot:item.amou="props">
                                       TZS {{props.item.amount | formatNumber}}
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
    name: 'WorkspaceJsonBorrower',

    data() {
        return {search:"",search:"",

            borrower:[],
            loading:false,
            process:[],
            mobile:false,
            processes:[],
            processTrans:[],
            customer:[],
            processesTrans:[],
            form:{
             score:1,
             card:[],
             id:"",
             name:""
            },

            flow:[],
            errors:[],
            active:'all',
            current:[],
            invoices:[],
            invoicesMatured:[],
            transactions:[],
            progress:0,
            display:false,
            filter:{
             name:"",
             phone:"",
             email:"",
             status:""
            },
            tab:"first",
            previusStage:null,
            nextStage:null,
               dropzoneOptions: {
                url: '../../api/uploadCard',
                thumbnailWidth: 200,
                addRemoveLinks: true,
                dictDefaultMessage: "<i class='fa fa-cloud-upload upload-icon'></i></i>Drag and drop a file here or click"
            },
            invoiceHeaders: [
                    {
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
                        text:"Model",
                        value:"model"
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
                        text:"Invoice Status",
                        value:"inStatus"
                    },


                    { text: 'Created At', value: 'join' },
                     { text: 'Status', value: "stat" },

                    { text: "Action", value: "controls", sortable: false }
                ],


                transHeaders: [
                    {
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
                    { text: 'Created At', value: 'join' },



                ],
                label:{
                      "Transaction Id":"transactionId",
                      "Mobile":"mobile",
                      "Bank":"bank",
                      "Name":"user.name",
                      "Reference":"reference",
                      "Service":"service",

                      "Amount":"amount",
                      "Channel":"channel",
                  },

        };
    },


   async beforeMount(){
    const id = this.$route.params.id;
    this.id = id;
    this.load = true;
       this.user = JSON.parse(localStorage.getItem('user'));
        const response = await axios.get('../../api/customers/'+id);






            this.active = "detail";

            const result = response.data;
            this.customer = result;




         this.load = false;


   },

    methods: {
       menuclick (value) {
                    this.mobile = value
                },


        async filterData(){
            this.loading=true;
            const response = await axios.post('api/borrower-filter',this.filter);

        this.borrowers = response.data;
        this.loading=false;


    },



    },
};
</script>

<style lang="css">
.approved{
    color: rgb(5, 128, 5) !important;
}
</style>
