<template>
    <v-app>
        <div id="main_content" :class="mobile?'offcanvas-active':''">
    <!-- Start Main top header -->
<admin-header v-on:childToParent="menuclick"></admin-header>

    <!-- Start Theme panel do not add in project -->

    <!-- Start Quick menu with more functio -->

    <!-- Start Main leftbar navigation -->
       <sidebar-left link="home"></sidebar-left>
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
                        <ol class="breadcrumb page-breadcrumb">
                            <li class="breadcrumb-item"><a href="#">BimaKwik</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Borrowers</li>
                        </ol>
                    </div>
                    <ul class="nav nav-tabs page-header-tab">
                        <li class="nav-item"><a class="nav-link" :class="active=='all'?'active':''" @click.prevent="active='all'">Borrowers</a></li>
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
                                        <div class="user_avtar">
                                         <img class="rounded-circle" :src="'/images/'+JSON.parse(this.borrower.user.image).image" alt="">
                                     </div>
                                        <div  class="wid-u-info" style="text-align:center">
                                            <h5>{{borrower.user.name.toUpperCase()}}</h5>

                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">About</h3>

                                    </div>
									<div class="card-body">
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" :style="'width:'+progress+'%'" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">{{Math.round(progress)+'%'}}</div>
                                        </div>

										<ul class="list-group">
											<li class="list-group-item">
												<b>Email </b>
												<div class="profile-desc-item pull-right">{{borrower.user.email}}</div>
											</li>
                                            <li class="list-group-item">
												<b>Phone</b>
												<div class="profile-desc-item pull-right">{{borrower.user.phone}}</div>
											</li>
                                            <li class="list-group-item">
												<b>sector </b>
												<div class="profile-desc-item pull-right">{{borrower.sector.name}}</div>
											</li>

                                             <li class="list-group-item">
												<b>id Type </b>
												<div class="profile-desc-item pull-right">{{borrower.id_type}}</div>
											</li>
                                             <li class="list-group-item">
												<b>Id number </b>
												<div class="profile-desc-item pull-right">{{borrower.id_no}}</div>
											</li>
                                            <li class="list-group-item">
												<b>Physical Address</b>
												<div class="profile-desc-item pull-right">{{borrower.physical}}</div>
											</li>
                                            <li class="list-group-item">
												<b>Anual income </b>
												<div class="profile-desc-item pull-right">{{borrower.annual_income}}</div>
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

                                             <li class="list-group-item">
												<b>Status</b>
												<div class="profile-desc-item pull-right"><span class="badge alert-primary">{{borrower.user.status}}</span></div>
											</li>

                                               <li class="list-group-item">
												<b>Id Card</b>
												<div class="profile-desc-item pull-right"><a :href="JSON.parse(borrower.card).url" class="badge alert-warning">download</a></div>
											</li>





                                        </ul>

                                    </div>
                                    <div class="card-footer text-center">
                                        <div class="row">
											<button class="col-md-12 col-sm-12 col-12 btn btn-primary" @click.prevent="approve"  v-if="current.role.name.toUpperCase() == 'ADMIN'&& invoice.invoice_status.toUpperCase() != 'ACTIVE'">

												<div>Approve</div>
											</button>

										</div>
                                    </div>

                                </div>


                                <div class="card">
                                <div class="card-header">
                                    <nav>
                                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                            <button class="nav-link" :class="tab=='first'?'active':''"  style="margin-right:10px" @click.prevent="tab='first'"  type="button" >Invoices</button>
                                            <button class="nav-link" :class="tab=='second'?'active':''" style="margin-right:10px" @click.prevent="tab='second'" type="button" role="tab" >Mature Invoices</button>
                                            <button class="nav-link" :class="tab=='third'?'active':''" style="margin-right:10px"  @click.prevent="tab='third'" type="button" role="tab" >Transaction</button>
                                        </div>
                                    </nav>
                                </div>
                                   <div class="card-body">
                                    <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade" :class="tab=='first'?'active show':''"  role="tabpanel" >
                                        <div class="table-responsive">
                                  <v-data-table
                                :headers="invoiceHeaders"
                                :items="invoices"
                                :items-per-page="10"
                                 :search="search"


                                class="elevation-1">

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
                                    <div class="tab-pane fade" :class="tab=='second'?'active show':''" role="tabpanel">
                                        <div class="table-responsive">
                                    <v-data-table
                                    :headers="invoiceHeaders"
                                    :items="invoicesMatured"
                                    :items-per-page="10"
                                    :search="search"


                                    class="elevation-1">

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
                                    <div class="tab-pane fade" :class="tab=='third'?'active show':''" role="tabpanel" >
                                        <ul class="header-dropdown">
                                    <li>
                                     <download-excel
                                          class="btn btn-info"
                                          :data="bids"
                                          :fields="label"
                                          worksheet="My Worksheet"
                                          name="Transactions.xls"
                                          style="color:#fff"
                                      ><i class="fas fa-cloud-download-alt" style="color:#fff"></i> &nbsp;Download Excel
                                      </download-excel></li></ul>
                            <div class="table-responsive">
                                  <v-data-table
                                :headers="transHeaders"
                                :items="transactions"
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
        const response = await axios.get('../../api/borrower/'+id);



           const resp = await axios.get('../../api/flow-by-process/'+1);
           this.processes = resp.data;



            this.active = "detail";

            const result = response.data;
            this.borrower = result;

                  const next = this.processes.find(({stage})=>stage ===result.stage+1);
            if(next){
            this.nextStage = next;
            }else{
                this.nextStage ={
                role:{
                    name:"-"
                }
            }
            }


           if(result.stage>1){
            const prev = this.processes.find(({stage})=>stage ===result.stage-1);
           this.previusStage = prev;
        }else{
            this.previusStage ={
                role:{
                    name:"-"
                }
            }
        }


        this.progress = ((result.stage-1)/this.processes.length)*100;

         const current = this.processes.find(({stage})=>stage ===result.stage);
         if(current){
             this.current = current;
         }else{
               this.current ={
                role:{
                    name:"Admin"
                }
            }
         }

         this.load = false;

         const respo = await axios.get('../api/borrower-invoice/'+result.id);
            this.invoices = respo.data;

            const respon = await axios.get('../api/borrower-invoice-matured/'+result.id);
            this.invoicesMatured = respon.data;

            const res = await axios.get('api/transaction-user/'+result.user_id);
             this.transactions = res.data;



const responses = await axios.get('api/flow-by-process/'+9);
this.processesTrans = responses.data;










   },

    methods: {
       menuclick (value) {
                    this.mobile = value
                },

        async approve(){


             try{


                  var result = await this.$swal({
            title: 'Are you sure you want to approve '+this.borrower.user.name,
            text: "You won't be able to revert this action!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#000',
            confirmButtonText: 'Yes, confirm !'
        });
        if (result.isConfirmed) {
           const response = await axios.get('../api/borrower-approved/'+this.borrower.id+'/ approved');
          if(response.status == 200){
                  const response = await axios.get('api/borrower');
            this.borrowers =response.data;
        const result = response.data.find( ({ id }) => id ===this.borrower.id );
            this.borrower = result;
             this.loading=false;


                this.$swal(
                'Confirmed Refresh browser!',
                response.data,
                'success'
            )
          }

        }





            }catch(e){
                    console.log(e);
                    this.$toast.open({
                            message: 'Internal server error',
                            type: 'error',
                            duration: 5000,
                            position: 'bottom-right'
                            // all of other options may go here
                        });

                 this.loading=false;
            }

        },
        async filterData(){
            this.loading=true;
            const response = await axios.post('api/borrower-filter',this.filter);

        this.borrowers = response.data;
        this.loading=false;


    },

        async show(uid){
             this.load = true;
            this.active = "detail";

            const result = this.borrowers.find( ({ id }) => id === uid );
            this.borrower = result;

                  const next = this.processes.find(({stage})=>stage ===result.stage+1);
            if(next){
            this.nextStage = next;
            }else{
                this.nextStage ={
                role:{
                    name:"-"
                }
            }
            }


           if(result.stage>1){
            const prev = this.processes.find(({stage})=>stage ===result.stage-1);
           this.previusStage = prev;
        }else{
            this.previusStage ={
                role:{
                    name:"-"
                }
            }
        }


        this.progress = ((result.stage-1)/this.processes.length)*100;

         const current = this.processes.find(({stage})=>stage ===result.stage);
         if(current){
             this.current = current;
         }else{
               this.current ={
                role:{
                    name:"Admin"
                }
            }
         }

         this.load = false;

         const respo = await axios.get('../api/borrower-invoice/'+result.id);
            this.invoices = respo.data;

            const respon = await axios.get('../api/borrower-invoice-matured/'+result.id);
            this.invoicesMatured = respon.data;

            const res = await axios.get('api/transaction-user/'+result.user_id);
             this.transactions = res.data;

        },

    },
};
</script>

<style lang="css">
.approved{
    color: rgb(5, 128, 5) !important;
}
</style>
