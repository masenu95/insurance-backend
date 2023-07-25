<template>
    <v-app>
        <div id="main_content" :class="mobile?'offcanvas-active':''">
    <!-- Start Main top header -->
<admin-header v-on:childToParent="menuclick"></admin-header>
    <!-- Start Rightbar setting panel -->

    <!-- Start Main leftbar navigation -->
       <sidebar-left link="matured"></sidebar-left>
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
                        <h1 class="page-title">Matured Invoice</h1>
                        <ol class="breadcrumb page-breadcrumb">
                            <li class="breadcrumb-item"><router-link to="admin-home">BimaKwik</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">Matured Invoice</li>
                        </ol>
                    </div>
                    <ul class="nav nav-tabs page-header-tab">
                        <li class="nav-item"><a class="nav-link active"   @click.prevent="active='all'">List View</a></li>

                        <li class="nav-item"><a class="nav-link"  >Detail</a></li>

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
                                                                    <option value="OPEN">OPEN</option>
                                                                    <option value="CLOSE">CLOSE</option>
                                                                    <option value="DIRSBUSED">DISBURSED</option>
                                                                    <option value="PAID">PAID</option>
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
                            <div class="table-responsive">
                                  <v-data-table
                                :headers="headers"
                                :items="invoices"
                                :items-per-page="10"
                                 :search="search"


                                class="elevation-1">

                                 <template v-slot:item.controls="props">
                                      <router-link type="button" :to="'../matured/'+props.item.id" class="btn btn-icon btn-sm" title="show"><i class="fe fe-maximize"></i></router-link>


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
                                      {{props.item.trading_fee}}%
                                    </template>
                                    <template v-slot:item.av="props">
                                        {{props.item.available | formatNumber}}
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
                        text: 'Amount',
                        value: 'amou',
                    },
                    {
                        text: 'Discounted Amount',
                        value: 'dis',
                    },
                    {
                        text: 'Available',
                        value: 'av',
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

        };
    },

    async beforeMount(){

        const res = await axios.get('api/matured-invoice');
             this.invoices = res.data;

    },

    mounted() {

    },

    methods: {
        async filterData(){
            this.loading=true;
            const response = await axios.post('api/invoice-mature-filter',this.filter);

        this.invoices = response.data;
        this.loading=false;


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
             this.load = true;
            this.active = "detail";

            const result = this.invoices.find( ({ id }) => id === uid );
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
                const response = await axios.get('../api/invoice-approved/'+this.invoice.id+'/'+'Admin approved');
                if(response.status == 200){

            const res = await axios.get('api/invoices');
             this.invoices = res.data;

             const result = this.invoices.find( ({ id }) => id === this.invoice.id  );
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


            this.loading = false;

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
          var response = await axios.delete('../../api/invoices/'+id);
          if(response.status == 200){

                this.$swal(
                'Deleted!',
                response.data,
                'success'
            )
             const res = await axios.get('api/invoices');
             this.invoices = res.data;
          }

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
