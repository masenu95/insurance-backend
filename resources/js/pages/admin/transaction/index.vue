<template>
    <v-app>
        <div id="main_content" :class="mobile?'offcanvas-active':''">
    <!-- Start Main top header -->
<admin-header v-on:childToParent="menuclick"></admin-header>
    <!-- Start Rightbar setting panel -->
<!-- Start Main leftbar navigation -->
       <sidebar-left link="transaction"></sidebar-left>
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
                        <h1 class="page-title">Transactions</h1>
                        <ol class="breadcrumb page-breadcrumb">
                            <li class="breadcrumb-item"><router-link to="#">BimaKwik</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">Wallet</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <div class="section-body mt-4">
            <div class="container-fluid">
                <div class="tab-content">
                    <div class="tab-pane active">
                        <div class="filter" style="padding:30px 15px">
                                <div class="">

                                    <h4><span>All Invoices</span>
                                        <article class="filter-range-date">
                                            <form @submit.prevent="filterData">
                                                <section class="form-col3-left">
                                                    <h5>From Date</h5>
                                                    <input type="date" v-model="filter.min" placeholder="From date" class="form-control" required>
                                                </section>
                                                <input type="hidden" name="filter" value="filter">
                                                <section class="form-col3-mid">
                                                    <h5>To Date</h5>
                                                    <input type="date" v-model="filter.max" placeholder="To date" class="form-control" required>
                                                </section>
                                                <section class="form-col3-right"><button type="submit" class="btn btn-secondary btn-sm"  style="margin-top: 25px !important; background-color:#1976d border:1px solid #f4f6f6; border-radius: 6px; color:#fff">Filter</button></section>
                                            </form>
                                        </article>
                                    </h4>

                                </div>

                            </div>
                        <div class="card">
                            <div class="table-responsive">
                                <div class="row">
                                        <div class="col-6">

                                            <div class="input-group mb-3" style="width:300px">
                                                <input type="text" class="form-control" placeholder="Search" v-model="search">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <ul class="header-dropdown" style="float:right">
                                                <li>
                                                    <download-excel class="btn btn-info excel-green" :data="invoices" :fields="label" title="export excel" worksheet="My Worksheet" name="Invoices.xls" style="color:#fff"><i class="fas fa-file-excel" style="color:#fff"></i>&nbsp; Excel
                                                    </download-excel>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                  <v-data-table
                                :headers="headers"
                                :items="all"
                                :items-per-page="10"
                                 :search="search"


                                class="elevation-1">
                                <template #item.index="{ item }">
                                    {{ all.indexOf(item) + 1 }}
                                </template>




                                     <template v-slot:item.premium="props">
                                        {{props.item.total_premium_including_tax | formatNumber}}
                                    </template>

                                     <template v-slot:item.join="props">
                                       {{props.item.created_at | formatDate}}
                                    </template>


                                <template v-slot:item.stat="props">

                                   <span class="badge alert-success" v-if="props.item.status=='ACTIVE'">{{props.item.status}}</span>
                                   <span class="badge alert-danger" v-else>{{props.item.status}}</span>


                                </template>
                                       <template v-slot:item.actn="props">

                                   <span class="badge alert-success" v-if=" props.item.status.toUpperCase() != 'ACTIVE'" title="Approve"><button @click.prevent="approve(props.item)"><i class="fa-solid fa-check-double"></i></button></span>



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

    data() {
        return {
            all:[],
            success:[],
            pending:[],
            load:false,
            wallet:[],
            search:"",
            balance:0,
            loading:false,
            bidsAmount:0,
            progress:0,
            current:[],
            previusStage:null,
            nextStage:null,
            staff:[],
            flow:[],
              process:[],
            mobile:false,
            processes:[],
            display:false,
            filter:{
             reference:"",
             transaction:"",
             mobile:null,
             service:null,
             channel:null,
             bank:null,
             from:"",
             to:"",
             amountStart:"",
             amountEnd:""
            },

            current:[],
            progress:0,
            previusStage:null,
            nextStage:null,
            availabeAmount:0,
            active:"all",
            int:1,

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
            headers: [{
                    value: 'index',
                    text: '#',
                    sortable: false,
                },
                {
                    text: 'customer',
                    value: 'customer.full_name',
                },
                {
                    text: 'Type',
                    value: 'insurance_type.name',
                },

                {
                    text: 'Region',
                    value: 'customer.region.name',
                },

                {
                    text: 'Payment',
                    value: 'payment_mode',
                },


                {
                    text: 'Vehicle',
                    value: 'registration_number',
                },

                {
                    text: 'Premium',
                    value: 'premium',
                },

                {
                    text: 'Status',
                    value: 'stat',
                },


                {
                    text: 'Created At',
                    value: 'join'
                },

                {
                    text: '',
                    value: 'action'
                },

            ],

        };
    },

    async beforeMount(){
                this.user = JSON.parse(localStorage.getItem('user'));

        const res = await axios.get('api/transactions');
             this.all = res.data;

        const staff = await axios.get('../../api/active-staff');
        const result = await axios.get('api/flow-process/'+9+'/'+staff.data.role_id);

        const resp = await axios.get('api/flow-by-process/'+9);
        this.processes = resp.data;



        this.process = result.data;

        this.staff = staff.data;

    },

    mounted() {

    },

      methods: {
       menuclick (value) {
                    this.mobile = value
                },
    async filterData(){
            this.loading=true;
            const response = await axios.post('api/user-trans-filter',this.filter);

        this.all = response.data;
        this.loading=false;


    },



        async approve(item){


             try{


                  var result = await this.$swal({
            title: 'Are you sure you want to approve '+item.transactionId,
            text: "You won't be able to revert this action!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#000',
            confirmButtonText: 'Yes, confirm !'
        });
        if (result.isConfirmed) {
           const response = await axios.get('../api/transaction-approved/'+item.id+'/'+this.user.role +' approved');
          if(response.status == 200){
                  const response = await axios.get('api/transactions');
            this.all =response.data;

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

           completed(){
             this.load = true;
            this.active = "success";

            const result = this.all.filter( ({ status }) => status === 'ACTIVE' );
            this.success = result;

            this.load = false;
        },
    incomplete(){
             this.load = true;
            this.active = "pending";

            const result = this.all.filter( ({ status }) => status === 'PENDING' );
            this.pending = result;

            this.load = false;
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
