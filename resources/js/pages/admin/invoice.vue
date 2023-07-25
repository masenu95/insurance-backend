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
                        <h1 class="page-title">Invoice</h1>
                        <ol class="breadcrumb page-breadcrumb">
                            <li class="breadcrumb-item"><router-link to="admin-home">BimaKwik</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">Invoice</li>
                        </ol>
                    </div>
                    <ul class="nav nav-tabs page-header-tab">
                        <li class="nav-item"><a class="nav-link " :class="active=='all'?'active':''"   @click.prevent="active='all'">List View</a></li>

                        <li class="nav-item"><a class="nav-link" :class="active=='detail'?'active':''"  >Detail</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="section-body mt-4">
            <div class="container-fluid">
                <div class="tab-content">
                    <div class="tab-pane " :class="active=='all'?'active':''">
                        <div class="card">
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
                            <div class="table-responsive">
                                  <v-data-table
                                :headers="headers"
                                :items="invoices"
                                :items-per-page="10"
                                 :search="search"


                                class="elevation-1">
                                <template #item.index="{ item }"
                                >
                                    {{ invoices.indexOf(item) + 1 }}
                                </template>
                                 <template v-slot:item.controls="props">
                                      <button type="button" @click.prevent="show(props.item.id)" class="btn btn-icon btn-sm" title="show"><i class="fe fe-maximize"></i></button>


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
                            </v-data-table>
                            </div>
                        </div>
                    </div>


                         <div class="tab-pane" :class="active=='detail'?'active':''">
                             <div class="row"  v-if="active=='detail'&&load==false">
                            <div class="col-xl-12 col-md-12">
                                <div class="card">
                                    <div class="card-body w_user">

                                        <div  class="wid-u-info" style="text-align:center">
                                            <h5>{{this.invoice.invoice_number.toUpperCase()}}</h5>

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
												<b>Bids</b>
												<div class="profile-desc-item pull-right">

                                                    <router-link to="/" class="badge alert-info"> View</router-link>
                                                    </div>
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
             dropzoneOptions: {
                url: '../../api/uploadInvoice',
                thumbnailWidth: 200,
                addRemoveLinks: true,
                dictDefaultMessage: "<i class='fa fa-cloud-upload upload-icon'></i></i>Drag and drop a file here or click"
            },
            companies:[],
            mobile:false,
            errors:[],
            errorsEdit:[],
              progress:0,
            previusStage:null,
            nextStage:null,
              process:[],
            processes:[],
            flow:[],
            headers: [
                    {
                        value: 'index',
                        text: '#',
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

        const res = await axios.get('api/invoices');
             this.invoices = res.data;
              const staff = await axios.get('../../api/active-staff');
         const result = await axios.get('api/flow-process/'+2+'/'+staff.data.role_id);

           const resp = await axios.get('api/flow-by-process/'+2);
           this.processes = resp.data;



        this.process = result.data;

        this.staff = staff.data;

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
           const response = await axios.get('../api/invoice-approved/'+this.invoice.id+'/ approved');
          if(response.status == 200){
                  const response = await axios.get('api/invoices');
            this.invoices =response.data;
        const result = response.data.find( ({ id }) => id ===this.invoice.id );
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
