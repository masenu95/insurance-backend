<template>
    <v-app>
        <div id="main_content" :class="mobile?'offcanvas-active':''">
    <!-- Start Main top header -->
<admin-header v-on:childToParent="menuclick"></admin-header>
    <!-- Start Rightbar setting panel -->

    <!-- Start Main leftbar navigation -->
       <investor-sidebar-left link="pre" v-if="user.role=='Investor'"></investor-sidebar-left>
       <borrower-sidebar-left link="pre" v-if="user.role=='Borrower'"></borrower-sidebar-left>
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
                        <h1 class="page-title">Wallet</h1>
                        <ol class="breadcrumb page-breadcrumb">
                            <li class="breadcrumb-item"><router-link to="#">BimaKwik</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">Wallet</li>
                        </ol>
                    </div>
                    <ul class="nav nav-tabs page-header-tab" >
                         <li class="nav-item"><a class="nav-link " :class="active=='summary'?'active':''"   @click.prevent="active='summary'">Summary</a></li>
                        <li class="nav-item"><a class="nav-link" :class="active=='topUp'?'active':''"  @click.prevent="active='topUp'">Top Up</a></li>
                        <li class="nav-item"><a class="nav-link" :class="active=='withdraw'?'active':''" @click.prevent="active='withdraw'" >Withdraw</a></li>

                    </ul>

                </div>
            </div>
        </div>
        <div class="section-body mt-4">
            <div class="container-fluid">
                <div class="tab-content">
                    <div class="tab-pane " :class="active=='summary'?'active':''">
                        <div class="card">
                            <div class="table-responsive">
                                  <v-data-table
                                :headers="headers"
                                :items="summary"
                                :items-per-page="10"
                                 :search="search"


                                class="elevation-1">
                                <template #item.index="{ item }">
                                    {{ summary.indexOf(item) + 1 }}
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

                        <div class="tab-pane " :class="active=='topUp'?'active':''">
                          <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Top Up</h3>

                                    </div>
                                        <div class="card-body">
                                       <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Payment</h3>

                                    </div>
                                    <div class="card-body">
                                         <form @submit.prevent="submit">
                                            <div class="row clearfix">
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Amount</label>
                                                        <input type="text" class="form-control" v-model="form.amount" required>
                                                         <div class="validation-error" v-if="errors.amount">
                                                                {{errors.amount[0]}}
                                                        </div>
                                                    </div>
                                                </div>
                                               <!-- <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">

                                                        <div class="form-check">
                                                            <input class="form-check-input" name="method" type="radio" value="mobile" v-model="form.method">Mobile Money
                                                        </div>




                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12" v-if="form.method=='mobile'">

                                                    <div class="form-group">
                                                        <label>mobile(format(eg):0789xxxxxx)</label>
                                                        <input type="text" class="form-control" v-model="form.mobile" required>
                                                         <div class="validation-error" v-if="errors.mobile">
                                                                {{errors.mobile[0]}}
                                                        </div>
                                                    </div>
                                                       <div class="mobile-logo">
                                                    <h6>Accept:</h6>

                                                        <img src="images/airtel_money.png" alt="">
                                                        <img src="images/tigo_pesa.png" alt="">
                                                        <img src="images/ezy_pesa.png" alt="">
                                                        <img src="images/m_pesa.png" alt="">
                                                        <img src="images/halo_pesa.png" alt="">

                                                    </div>
                                                </div>-->
                                                  <div class="col-md-12 col-sm-12">
                                                <div class="form-group">
                                                      <div class="form-check">
                                                             <input class="form-check-input" type="radio" name="method" value='bank' v-model="form.method">Bank Transfer
                                                        </div>
                                                </div>
                                                </div>

                                                <div class="col-md-12 col-sm-12" v-if="form.method=='bank'">
                                                    <div class="form-group">
                                                        <label>Bank</label>
                                                        <input type="text" class="form-control" v-model="form.bank" required>
                                                         <div class="validation-error" v-if="errorsbank">
                                                                {{errors.bank[0]}}
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Reference Number</label>
                                                        <input type="text" class="form-control" v-model="form.reference" required>
                                                         <div class="validation-error" v-if="errors.reference">
                                                                {{errors.reference[0]}}
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-12" style="margin-top:30px">
                                                        <h6>Bank Details</h6>
                                                        <h6>Account Name: <span class="badge bg-primary">CASH ME TANZANIA BIDS</span></h6>
                                                        <h6>Account No: <span class="badge bg-danger">182227000017</span> </h6>
                                                        <h6>Bank Name:<span class="badge bg-info">TANZANIA COMMERCIAL BANK (TCB)</span> </h6>
                                                    </div>
                                                    <div class="col-sm-12">
                                                       <button type="submit" class="btn btn-primary">
                                                        <div class="spinner-border text-danger" role="status" style="width:15px;height:15px" v-if="loading"></div>
                                                      Pay By Bank
                                                    </button>

                                                </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12">
                                                <div class="form-group">
                                                      <div class="form-check">
                                                             <input class="form-check-input" type="radio" name="method" value='online' @change="onlineClick"  v-model="form.method">Online
                                                        </div>
                                                </div>
                                                </div>

                                                <div class="col-sm-12" v-if="form.method=='online'">

                                                     <button class="awesome-checkout-button" @click.prevent="checkout"></button>
                                                </div>
                                                </div>
                                          </form>
                                        </div>
                                </div>
                            </div>
                          </div>
                                        </div>
                                </div>
                            </div>
                          </div>
                        </div>
                            <div class="tab-pane " :class="active=='withdraw'?'active':''">
                          <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Withdraw</h3>

                                    </div>
                                        <div class="card-body">
                                            <form @submit.prevent="submitWithdraw">
                                            <div class="row clearfix">
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Amount</label>
                                                        <input type="text" class="form-control" v-model="formWithdraw.amount" required>
                                                        <small>{{formWithdraw.amount|formatNumber}}</small>
                                                         <div class="validation-error" v-if="errorsWithdraw.amount">
                                                                {{errorsWithdraw.amount[0]}}
                                                        </div>
                                                    </div>
                                                </div>
                                                        <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>method</label>
                                                        <select class="form-control" v-model="formWithdraw.method" required>
                                                        <option value="">Select method</option>
                                                            <option value="bank">Bank</option>
                                                           <option value="mobile">Mobile</option>
                                                        </select>
                                                         <div class="validation-error" v-if="errorsWithdraw.method">
                                                                {{errorsWithdraw.method[0]}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12" v-if="formWithdraw.method=='mobile'">
                                                    <div class="form-group">
                                                         <label>mobile(format(eg):0789xxxxxx)</label>
                                                        <input type="text" class="form-control" v-model="formWithdraw.mobile" required>
                                                         <div class="validation-error" v-if="errorsWithdraw.mobile">
                                                                {{errorsWithdraw.mobile[0]}}
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12">
                                                       <button type="submit" class="btn btn-primary">
                                                        <div class="spinner-border text-danger" role="status" style="width:15px;height:15px" v-if="loading"></div>
                                                        Submit
                                                    </button>
                                                     <button @click.prevent="active='summary'" class="btn btn-outline-secondary">Cancel</button>
                                                </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12" v-if="formWithdraw.method=='bank'">
                                                    <div class="form-group">
                                                        <label>Bank</label>
                                                        <input type="text" class="form-control" v-model="formWithdraw.bank" required>
                                                         <div class="validation-error" v-if="errorsWithdraw.bank">
                                                                {{errorsWithdraw.bank[0]}}
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Account Name</label>
                                                        <input type="text" class="form-control" v-model="formWithdraw.accountName" required>
                                                         <div class="validation-error" v-if="errorsWithdraw.accountName">
                                                                {{errorsWithdraw.accountName[0]}}
                                                        </div>
                                                    </div>
                                                        <div class="form-group">
                                                        <label>Account Number</label>
                                                        <input type="text" class="form-control" v-model="formWithdraw.accountNumber" required>
                                                         <div class="validation-error" v-if="errorsWithdraw.accountNumber">
                                                                {{errorsWithdraw.accountNumber[0]}}
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12">
                                                       <button type="submit" class="btn btn-primary">
                                                        <div class="spinner-border text-danger" role="status" style="width:15px;height:15px" v-if="loading"></div>
                                                        Submit
                                                    </button>
                                                     <button @click.prevent="active='summary'" class="btn btn-outline-secondary">Cancel</button>
                                                </div>
                                                </div>




                                                </div>
                                          </form>
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
</div>

 </v-app>
</template>


<script>
 import vue2Dropzone from 'vue2-dropzone'
 import 'vue2-dropzone/dist/vue2Dropzone.min.css';
export default {

    data() {
        return {search:"",search:"",
            summary:[],
            load:false,
            wallet:[],
            search:"",
            balance:0,
            loading:false,
            bidsAmount:0,
            mobile:false,
            availabeAmount:0,
            active:"summary",
            int:1,
            form:{
                amount:1000,
                mobile:"",
                payout:"",
                action:"1",
                method:"",
                bank:"",
                reference:"",
                description:"Walet",
            },
             formWithdraw:{
                amount:1000,
                mobile:"",
                payout:"",
                action:"0",
                method:"",
                bank:"",
                reference:"",


            },
             dropzoneOptions: {
                url: '../../api/uploadInvoice',
                thumbnailWidth: 200,
                addRemoveLinks: true,
                dictDefaultMessage: "<i class='fa fa-cloud-upload upload-icon'></i></i>Drag and drop a file here or click"
            },
            companies:[],
            errors:[],
            errorsWithdraw:[],
            errorsEdit:[],
            headers: [
                    {
                        value: 'index',
                        text: '#',
                         sortable: false,
                    },
                          {
                        text: 'Transaction Id',
                        value: 'transactions.transactionId',
                    },
                      {
                        text: 'Mobile',
                        value: 'transactions.mobile',
                    },
                        {
                        text: 'Bank',
                        value: 'transactions.bank',
                    },
                        {
                        text: 'Reference',
                        value: 'transactions.reference',
                    },
                        {
                        text: 'Action',
                        value: 'actn',
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
                        value: 'balance',
                    },


                    { text: 'Created At', value: 'join' },


                ],

        };
    },

    async beforeMount(){
                this.user = JSON.parse(localStorage.getItem('user'));

        const res = await axios.get('api/summary');
             this.summary = res.data;
        const result = await axios.get('api/my-balance');
        this.balance = result.data;
    },

    mounted() {

    },
    methods: {
    setLoaded: function() {
      window.Tingg.renderPayButton({
                className: 'awesome-checkout-button',
                text: 'online Payment',
                color: 'blue'
            });
    },
      async checkout(){
      /*  var date = new Date();
        var min = parseInt(date.getMinutes())+10;
        var hr = date.getHours()
        if(min>59){
            min = min-60;
            hr = hr+1;
        }

        var datetime = date.getFullYear()+'-'+date.getMonth()+'-'+date.getDate() +' '+hr+':'+min+':'+date.getSeconds();
        this.payload.dueDate = datetime;*/
        const checkoutType = 'redirect';
        const resp = await axios.post("../api/trans-encrypt",this.form);
        console.log(resp.data);
        window.Tingg.renderCheckout({
                       checkoutType,
                    merchantProperties:{params:resp.data, accessKey: 'JlJaKWNYTHowlmEbxdsNASCsRKuOjrszVjdDHifmNoXVbfARkC', countryCode: 'TZ'}
                  });

    },
        methodChangeBank(){
            this.form.method = 'bank';
        },
        methodChangeMobile(){
             this.form.method = 'mobile';
        },

        onlineClick(){
            const script = document.createElement("script");
        script.src = "https://online.tingg.africa/v2/tingg-checkout.js";

            script.addEventListener("load", this.setLoaded);
    document.body.appendChild(script);
        },
       menuclick (value) {
                    this.mobile = value
                },

            async submit(){
            this.loading=true;

            try{
                const response = await axios.post('../api/wallet',this.form);
                if(response.status == 200){

                          this.$toast.open({
                            message: ' successful added',
                            type: 'success',
                            duration: 5000,
                            position: 'top-right'
                            // summary of other options may go here
                        });

                this.form.amount = 1000;
                this.form.mobile="";



        const res = await axios.get('api/summary');
             this.summary = res.data;
                this.loading=false;
                this.active = "summary";

                }
            }catch(e){
                this.loading=false;
                if(e.response){
                    if(e.response.status = 422){
                        this.errors = e.response.data.errors;

                         this.$toast.open({
                            message: e.response.data.message,
                            type: 'error',
                            duration: 5000,
                            position: 'bottom-right'
                            // summary of other options may go here
                        });


                    }
                } else{
                    this.$toast.open({
                            message: 'Internal server error',
                            type: 'error',
                            duration: 5000,
                            position: 'bottom-right'
                            // summary of other options may go here
                        });
                }
                 this.loading=false;
            }

        },
             async submitWithdraw(){
            this.loading=true;

            try{
                const response = await axios.post('../api/wallet',this.formWithdraw);
                if(response.status == 200){

                          this.$toast.open({
                            message: ' successful added',
                            type: 'success',
                            duration: 5000,
                            position: 'top-right'
                            // summary of other options may go here
                        });

                this.formWithdraw.amount = 1000;
                this.formWithdraw.mobile="";



        const res = await axios.get('api/summary');
             this.summary = res.data;
                this.loading=false;
                this.active = "summary";

                }
            }catch(e){
                    this.loading=false;
                if(e.response){
                    if(e.response.status = 422){
                        this.errors = e.response.data.errors;

                         this.$toast.open({
                            message: e.response.data.message,
                            type: 'error',
                            duration: 5000,
                            position: 'bottom-right'
                            // summary of other options may go here
                        });


                    }
                } else{
                    this.$toast.open({
                            message: 'Internal server error',
                            type: 'error',
                            duration: 5000,
                            position: 'bottom-right'
                            // summary of other options may go here
                        });
                }
                 this.loading=false;
            }

        },
              async withdraw(){
            this.loading=true;

            try{
                const response = await axios.post('../api/wallet',this.formWithdraw);
                if(response.status == 200){

                          this.$toast.open({
                            message: ' successful added',
                            type: 'success',
                            duration: 5000,
                            position: 'top-right'
                            // summary of other options may go here
                        });

                this.form.amount = 1000;
                this.form.mobile="";



        const res = await axios.get('api/summary');
             this.summary = res.data;
                this.loading=false;
                this.active = "summary";

                }
            }catch(e){
                this.loading=false;
                if(e.response){
                    if(e.response.status = 422){
                        this.errors = e.response.data.errors;

                         this.$toast.open({
                            message: e.response.data.message,
                            type: 'error',
                            duration: 5000,
                            position: 'bottom-right'
                            // summary of other options may go here
                        });


                    }
                } else{
                    this.$toast.open({
                            message: 'Internal server error',
                            type: 'error',
                            duration: 5000,
                            position: 'bottom-right'
                            // summary of other options may go here
                        });
                }
                 this.loading=false;
            }

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
