<template>
    <v-app>
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
                        <h1 class="page-title">Fees</h1>
                        <ol class="breadcrumb page-breadcrumb">
                            <li class="breadcrumb-item"><router-link to="admin-home">BimaKwik</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">Fees</li>
                        </ol>
                    </div>
                    <ul class="nav nav-tabs page-header-tab">
                        <li class="nav-item"><a class="nav-link active"   @click.prevent="active='all'">List View</a></li>


                    </ul>
                </div>
            </div>
        </div>
        <div class="section-body mt-4">
            <div class="container-fluid">
                <div class="tab-content">
                    <div class="tab-pane active">
                        <div class="filter">
                                    <button @click="display=!display"><i class="fa fa-filter" aria-hidden="true"></i>Filter</button>
                                    <br>
                                    <div class="form-search" v-if="display">

                                        <form @submit.prevent="filterData">
                                            <div class="form-row">
                                                <div class="col form-table">
                                                    <span class="label">Invoice Number</span>
                                                    <input type="text" class="form-control input" v-model="filter.invoice" placeholder="Invoice Number">
                                                </div>

                                                <div class="col form-table">

                                                    <span class="label">Owning company</span>
                                                    <select class="form-control input" v-model="filter.company" @change.prevent="companyChange">
                                                        <option value="">Owning company</option>
                                                        <option v-for="company in companies" :key="company.id" :value="company.id">{{company.name}}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="col form-table">
                                                    <span class="label">Maturity Date Start</span>
                                                    <input type="date" class="form-control input" v-model="filter.maturityStart" >
                                                </div>

                                                 <div class="col form-table">
                                                    <span class="label">Maturity Date End</span>
                                                    <input type="date" class="form-control input" v-model="filter.maturityEnd" >
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col form-table">
                                                    <span class="label">Invoice Date Start</span>
                                                    <input type="date" class="form-control input" v-model="filter.from" >
                                                </div>

                                                 <div class="col form-table">
                                                    <span class="label">Invoice Date End</span>
                                                    <input type="date" class="form-control input" v-model="filter.to" >
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="col form-table">
                                                    <span class="label">Amount Start</span>
                                                    <input type="text" class="form-control input" v-model="filter.amountStart" >
                                                </div>

                                                 <div class="col form-table">
                                                    <span class="label">Amount End</span>
                                                    <input type="text" class="form-control input" v-model="filter.amountEnd" >
                                                </div>
                                            </div>

                                            <div class="form-row">

                                                                <div class="col form-table">
                                                                <span class="label">Status</span>
                                                                <select class="form-control input" v-model="filter.status">
                                                                    <option value="">Status</option>
                                                                    <option value="1">Active</option>
                                                                    <option value="0">Inactive</option>

                                                                </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <button  type="submit" class="btn btn-primary btn-sm" v-if="!loading"><i class="fas fa-sync-alt"></i>&nbsp;Refresh</button>
                                                                <button class="btn btn-primary btn-sm" v-else type="button" disabled>
                                                                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                                                    Loading...
                                                                </button>
                                                            </div>
                                                        </form>
                                                </div>

                                </div>
                        <div class="card">
                            <ul class="header-dropdown">
                                    <li>
                                     <download-excel
                                          class="btn btn-info"
                                          :data="invoices"
                                          :fields="label"
                                          worksheet="My Worksheet"
                                          name="fees.xls"
                                          style="color:#fff"
                                      ><i class="fas fa-cloud-download-alt" style="color:#fff"></i> &nbsp;Download Excel
                                      </download-excel></li></ul>
                            <div class="table-responsive">
                                  <v-data-table
                                :headers="headers"
                                :items="invoices"
                                :items-per-page="10"
                                 :search="search"


                                class="elevation-1">

                                 <template v-slot:item.controls="props">
                                      <router-link type="button" :to="'../invoice/'+props.item.id" class="btn btn-icon btn-sm" title="show"><i class="fe fe-maximize"></i></router-link>


                                </template>


                                   <template v-slot:item.stat="props">

                                   <span class="badge alert-success" v-if="props.item.status=='ACTIVE'">{{props.item.status}}</span>
                                   <span class="badge alert-danger" v-else>{{props.item.status}}</span>


                                </template>
                                  <template v-slot:item.inStatus="props">

                                   <span class="badge alert-success" v-if="props.item.invoice_status=='ACTIVE'">{{props.item.invoice_status}}</span>
                                   <span class="badge alert-danger" v-else>{{props.item.invoice_status}}</span>


                                </template>

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
                                      {{props.item.trading_fee }}%
                                    </template>
                                    <template v-slot:item.tradingfeeAmount="props">
                                        {{props.item.trading_fee_amount | formatNumber}}
                                    </template>
                                    <template v-slot:item.processingfee="props">
                                        {{props.item.proccessing_fee }}
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

    name: 'WorkspaceJsonBank',

    data() {
        return {search:"",search:"",
            invoices:[],
            load:false,
            mobile:false,
            loading:false,
            invoice:[],
            search:"",
            active:"all",
            int:1,
            form:{
                invoice_number:"",
                invoice_date:"",
                company:"",
                maturity_date:"",
                amount:"",
                currency:"TZS",
                model:"OPEN",
                image:"",
                percent:80,
                final:0,

            },
             formEdit:{
                invoice_number:"",
                invoice_date:"",
                company:"",
                maturity_date:"",
                amount:"",
                currency:"TZS",
                model:"OPEN",
                image:"",
                percent:80,
                final:0,
                _method: 'patch'

            },
             dropzoneOptions: {
                url: '../../api/uploadInvoice',
                thumbnailWidth: 200,
                addRemoveLinks: true,
                dictDefaultMessage: "<i class='fa fa-cloud-upload upload-icon'></i></i>Drag and drop a file here or click"
            },
            companies:[],
            errors:[],
            errorsEdit:[],
            progress:0,
            previusStage:null,
            nextStage:null,
              process:[],
            processes:[],
            display:false,
               filter:{
                company:"",
                invoice:"",
                borrower:"",
                maturityStart:"",
                maturityEnd:"",
                amountStart:null,
                amountEnd:null,
                status:"",
                from:null,
                to:null,
            },
            flow:[],
            headers: [
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



                    { text: 'Created At', value: 'join' },
                     { text: 'Status', value: "stat" },
                ],

                label:{
                      "Invoice number":"invoice_number",
                      "Owning company":"company.name",
                      "Borrower":"borrower.user.name",
                      "Invoice Date":"invoice_date",
                      "Maturity Date":"maturity_date",
                      "Model":"model",
                      "Amount":"amount",
                      "Discount %":"discount_perc",
                      "Discounted Amount":"discount_amount",
                      "Trading fee":"trading_fee",
                      "Trading Fee Amount":"trading_fee_amount",
                      "Proccessing fee":"proccessing_fee",

                      "Status":"status",



                  },

        };
    },

    async beforeMount(){

        const res = await axios.get('api/invoices');
             this.invoices = res.data;


    },

    mounted() {

    },

    methods: {
        async filterData(){
            this.loading=true;
            const response = await axios.post('api/invoicefilter',this.filter);

        this.invoices = response.data;
        this.loading=false;


    },


       menuclick (value) {
                    this.mobile = value
                },


    },
   components:{

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
