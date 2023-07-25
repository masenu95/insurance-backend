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
                        <h1 class="page-title">Device Financing Invoice</h1>
                        <ol class="breadcrumb page-breadcrumb">
                            <li class="breadcrumb-item"><router-link to="admin-home">BimaKwik</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">Device Financing Invoice</li>
                        </ol>
                    </div>
                    <ul class="nav nav-tabs page-header-tab">
                        <li class="nav-item"><router-link class="nav-link " to="../../invoice"  >List View</router-link></li>

                        <li class="nav-item"><a class="nav-link active"  >Detail</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="section-body mt-4">
            <div class="container-fluid">
                <div class="tab-content">



                         <div class="tab-pane active">
                             <div class="row" >
                            <div class="col-xl-12 col-md-12">
                                <div class="card">
                                    <div class="card-body w_user">

                                    <div  class="wid-u-info" style="text-align:center">
                                    Invoice no:   <h5>{{this.invoice.invoice_number}}</h5>

                                    </div>
                                    <div  class="wid-u-info" style="text-align:center">
                                    Available:  <h5>{{this.invoice.available |formatNumber}}</h5>

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
												<b>Invoice Number </b>
												<div class="profile-desc-item pull-right">{{invoice.invoice_number}}</div>
											</li>

                                            <li class="list-group-item">
												<b>Borrower</b>
												<div class="profile-desc-item pull-right">Cashme Device </div>
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
												<div class="profile-desc-item pull-right"> {{invoice.amount|formatNumber}}</div>
											</li>



                                            <li class="list-group-item">
												<b>Rate</b>
												<div class="profile-desc-item pull-right">{{invoice.interest_rate}}</div>
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










                                        </ul>

                                    </div>

                                    <div class="card-footer text-center" v-if="(invoice.status =='CLOSED'||invoice.status =='CLOSE') && invoice.invoice_status=='ACTIVE'" >
                                        <div class="row">
											<button class="col-md-12 col-sm-12 col-12 btn btn-primary" @click.prevent="disburseInvoice" >

												<div>Disburse invoice</div>
											</button>

										</div>
                                    </div>

                                    <div class="card-footer text-center">
                                       <div class="row">
											<button class="col-md-12 col-sm-12 col-12 btn btn-primary" @click.prevent="approve"  v-if="invoice.invoice_status.toUpperCase() != 'ACTIVE'">

												<div>Approve</div>
											</button>

										</div>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <div class="card">
                            <div class="card-header">
                                    <nav>
                                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                            <button class="nav-link" :class="tab=='first'?'active':''"  style="margin-right:10px" @click.prevent="tab='first'"  type="button" >Bids</button>
                                            <button class="nav-link" :class="tab=='second'?'active':''" style="margin-right:10px" @click.prevent="tab='second'" type="button" role="tab" >Customers</button>

                                        </div>
                                    </nav>
                                </div>
                                <div class="card-body">
                                    <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade" :class="tab=='first'?'active show':''"  role="tabpanel" >
                                        <h6>Bids</h6>
                        <ul class="header-dropdown">
                                    <li>
                                     <download-excel
                                          class="btn btn-info"
                                          :data="bids"
                                          :fields="label"
                                          worksheet="My Worksheet"
                                          name="Bids.xls"
                                          style="color:#fff"
                                      ><i class="fas fa-cloud-download-alt" style="color:#fff"></i> &nbsp;Download Excel
                                      </download-excel></li></ul>
                        <v-data-table
                        :headers="bidHeaders"
                        :items="bids"
                        :items-per-page="10"
                        :search="search"
                        class="elevation-1"
                      >
                        <template #item.index="{ item }" >
                          {{ bids.indexOf(item) + 1 }}
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
                                        <div class="tab-pane fade" :class="tab=='second'?'active show':''"  role="tabpanel" >
                                        <h6>Customers</h6>
                                        <v-data-table
                                :headers="custHeaders"
                                :items="customers"
                                :items-per-page="10"
                                 :search="search"


                                class="elevation-1">
                                <template #item.index="{ item }"
                                >
                                    {{ customers.indexOf(item) + 1 }}
                                </template>
                                 <template v-slot:item.controls="props">

                                                <button type="button" class="btn btn-icon btn-sm js-sweetalert" v-if="invoice.status =='DISBURSED'&&props.item.status !='ACTIVE'" title="Enter IME" @click="enterIme(props.item.id)" data-type="confirm"><i class="fa fa-android"></i></button>

                                </template>


                                   <template v-slot:item.stat="props">

                                   <span class="tag tag-success" v-if="props.item.user.status=='ACTIVE'">{{props.item.user.status}}</span>
                                   <span class="tag tag-danger" style="background:red;color:#fff" v-else>{{props.item.user.status}}</span>


                                </template>
                                     <template v-slot:item.join="props">
                                        {{props.item.created_at | formatDate}}
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
        <!-- Start main footer -->
        <footer-component></footer-component>
    </div>
</div>

 </v-app>
</template>


<script>
 import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css';
import { router } from '../../../router';
export default {

    name: 'WorkspaceJsonBank',

    data() {
        return {search:"",search:"",
            invoices:[],
            bids:[],
            load:false,
            mobile:false,
            loading:false,
            invoice:[],
            search:"",
            active:"all",
            tab:"first",
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
                url: '../../../../api/uploadInvoice',
                thumbnailWidth: 200,
                addRemoveLinks: true,
                dictDefaultMessage: "<i class='fa fa-cloud-upload upload-icon'></i></i>Drag and drop a file here or click"
            },
            companies:[],
            errors:[],
            errorsEdit:[],
            progress:0,
            id:null,
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
            customers:[],
            flow:[],

            label:{
                      "Invoice number":"invoice.invoice_number",
                      "Owning company":"company.name",
                      "Borrower":"invoice.borrower.user.name",
                      "investor":"investor.user.name",
                      "Invoice Date":"invoice.invoice_date",
                      "Status":"status",

                      "profit":"profit",
                      "Payout":"payout_amount",
                      "withholding amount":"withholding_amount",
                      "Payout After Tax":"payout_after_tax"
                  },

                  custHeaders: [
                    {
                        value: 'index',
                        text: '#',
                         sortable: false,
                    },
                      {
                        text: 'Name',
                        value: 'name',
                    },

                        {
                        text: 'Phone',
                        value: 'phone',
                    },
                        {
                        text: 'Device Category',
                        value: 'device.category.name',
                    },
                        {
                        text: 'Device name',
                        value: 'device.name',
                    },

                    {
                        text: 'Price',
                        value: 'price',
                    },
                    {
                        text: 'vicoba/cmpd fee',
                        value: 'commission',
                    },
                    {
                        text: 'Insurance Cost',
                        value: 'insurance',
                    },

                    {
                        text: 'Technology Cost',
                        value: 'technology',
                    },

                    {
                        text: 'Loan Cost',
                        value: 'loan',
                    },

                    {
                        text: 'Down Payment',
                        value: 'initial',
                    },

                    {
                        text: 'Total',
                        value: 'total',
                    },
                       {
                        text: 'Remain',
                        value: 'remain',
                    },
                    {
                        text: 'reference',
                        value: 'reference',
                    },


                    {
                        text: 'IME',
                        value: 'ime',
                    },

                    {
                        text: 'Duration',
                        value: 'days',
                    },


                    {
                        text: 'status',
                        value: 'status',
                    },

                    { text: 'Created At', value: 'join' },
                     { text: 'Registered by', value: "user.name" },

                    { text: "Action", value: "controls", sortable: false }
                ],
                bidHeaders: [
                      {
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



                      { text: 'Created At', value: 'join' },
                       { text: 'Status', value: "stat" },

                  ],
        };
    },

    async beforeMount(){
        const id = this.$route.params.id;
        this.id = id;

        const bid =  await axios.get('../../api/device-bid-byinvoice/'+id);
        this.bids = bid.data;
        const res = await axios.get('../../api/device-invoice/'+id);



        const req = await axios.get('../../api/request-by-invoice/'+id);
        this.customers = req.data;

              const staff = await axios.get('../../../../api/active-staff');
         const results = await axios.get('../../api/flow-process/'+2+'/'+staff.data.role_id);


           const resp = await axios.get('../../api/flow-by-process/'+2);
           this.processes = resp.data;



        this.process = results.data;

        this.staff = staff.data;

        this.load = true;
            this.active = "detail";

            const result = res.data;
            this.invoice = result;

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

       if(result.stage >0){
           this.progress = ((result.stage-1)/this.processes.length)*100;
       }else{
           this.progress = 0;
       }




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

    },

    mounted() {

    },

    methods: {
        async filterData(){
            this.loading=true;
            const response = await axios.post('../../api/invoicefilter',this.filter);

        this.invoices = response.data;
        this.loading=false;


    },

    async disburseInvoice(){

try{

var result = await this.$swal({
title: 'Are you sure you want to initiate disbursement '+this.invoice.invoice_number,
text: "You won't be able to revert this action!",
icon: 'warning',
showCancelButton: true,
confirmButtonColor: '#3085d6',
cancelButtonColor: '#000',
confirmButtonText: 'Yes, confirm !'
});
if (result.isConfirmed) {
const response = await axios.get('../../../api/request-disbursed/'+this.invoice.id);
    if(response.status == 200){
this.loading = false;

     this.$swal(
    'Confirmed Refresh browser!',
    response.data,
    'success'
);
this.$router.push('../../admin-device-invoices');

}

}   }catch(e){
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
    daysCalc(maturity){
                  const date1 = new Date(maturity);
              const date2 = Date.now();
              const diffTime = Math.abs(date2 - date1);
              const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
              if(date1>=date2){
                return diffDays;
              }else{
                return diffDays*-1;
              }

          },

       menuclick (value) {
                    this.mobile = value
                },
         async show(uid){

        },
        async approve(){


try{


     var result = await this.$swal({
title: 'Are you sure you want to approve '+this.invoice.invoice_number,
text: "You won't be able to revert this action!",
icon: 'warning',
showCancelButton: true,
confirmButtonColor: '#3085d6',
cancelButtonColor: '#000',
confirmButtonText: 'Yes, confirm !'
});
if (result.isConfirmed) {
const response = await axios.get('../api/device-invoice-approved/'+this.invoice.id+'/ approved');
if(response.status == 200){

   this.$swal(
   'Confirmed Refresh browser!',
   response.data,
   'success'
)
this.$router.push('../../admin-device-invoices');
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
      async removeItems(id,index){
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
          var response = await axios.delete('../../../../api/devic-invoices/'+id);
          if(response.status == 200){

                this.$swal(
                'Deleted!',
                response.data,
                'success'
            )
             //const res = await axios.get('../../api/invoices');
             this.invoices = res.data;
          }

        }

    },

    async enterIme(id){
        let customer = await this.$swal({
        title: 'Enter phone IME',
        input: 'text',
        inputPlaceholder: 'Enter phone IME',
        showCloseButton: true,
    });

    if(customer.isConfirmed){
        console.log(customer.value)
        try{
                const response = await axios.post('../api/update-ime',{
                    "ime":customer.value,
                    "id":id,
                });
                if(response.status == 200){
                    this.loading=false;

                          this.$toast.open({
                            message: 'ime successful added',
                            type: 'success',
                            duration: 5000,
                            position: 'top-right'
                            // all of other options may go here
                        });

                        const id = this.$route.params.id;

        const res = await axios.get('../../api/device-invoice/'+this.id);

        const req = await axios.get('../../api/request-by-invoice/'+this.id);
        this.customers = req.data;
        this.invoice =res.data;


                }
            }catch(e){

            }
    }

    },
    },
   components:{
    vueDropzone: vue2Dropzone,
    router
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
