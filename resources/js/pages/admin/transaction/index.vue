<template>
    <v-app>
        <div id="main_content" :class="mobile?'offcanvas-active':''">
    <!-- Start Main top header -->
<admin-header v-on:childToParent="menuclick"></admin-header>
    <!-- Start Rightbar setting panel -->
<!-- Start Main leftbar navigation -->
       <staff-sidebar-left link="invoice"></staff-sidebar-left>
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
                    <ul class="nav nav-tabs page-header-tab" >
                         <li class="nav-item"><a class="nav-link " :class="active=='all'?'active':''"   @click.prevent="active='all'">ALL</a></li>
                        <li class="nav-item"><a class="nav-link" :class="active=='success'?'active':''"  @click.prevent="completed">Success</a></li>
                        <li class="nav-item"><a class="nav-link" :class="active=='pending'?'active':''" @click.prevent="incomplete" >Pending</a></li>

                    </ul>
                </div>
            </div>
        </div>
        <div class="section-body mt-4">
            <div class="container-fluid">
                <div class="tab-content">
                    <div class="tab-pane " :class="active=='all'?'active':''">
                        <div class="filter">
                  <button @click="display=!display">
                    <i class="fa fa-filter" aria-hidden="true"></i>Filter
                  </button>
                  <br />
                  <div class="form-search" v-if="display">
                    <form @submit.prevent="filterData">
                      <div class="form-row">
                        <div class="col form-table">
                          <span class="label">Transaction Number</span>
                          <input
                            type="text"
                            class="form-control input"
                            v-model="filter.transaction"
                            placeholder="Transaction Number"
                          />
                        </div>

                        <div class="col form-table">
                          <span class="label">Reference</span>
                          <input
                            type="text"
                            class="form-control input"
                            v-model="filter.reference"
                            placeholder="Reference Number"
                          />
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="col form-table">
                          <span class="label">Mobile</span>
                          <input
                            type="text"
                            class="form-control input"
                            v-model="filter.mobile"
                            placeholder="Mobile"
                          />
                        </div>

                        <div class="col form-table">
                          <span class="label">Bank</span>
                          <input
                            type="text"
                            class="form-control input"
                            v-model="filter.bank"
                            placeholder="Bank"
                          />
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="col form-table">
                          <span class="label">From</span>
                          <input
                            type="date"
                            class="form-control input"
                            v-model="filter.from"
                          />
                        </div>

                        <div class="col form-table">
                          <span class="label">To</span>
                          <input
                            type="date"
                            class="form-control input"
                            v-model="filter.to"
                          />
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="col form-table">
                          <span class="label">Amount Start</span>
                          <input
                            type="text"
                            class="form-control input"
                            v-model="filter.amountStart"
                          />
                        </div>

                        <div class="col form-table">
                          <span class="label">Amount End</span>
                          <input
                            type="text"
                            class="form-control input"
                            v-model="filter.amountEnd"
                          />
                        </div>
                      </div>

                      <div class="form-row">
                        <div class="col form-table">
                          <span class="label">Service</span>
                          <input
                            type="text"
                            class="form-control input"
                            v-model="filter.service"
                            placeholder="Service"
                          />
                        </div>

                        <div class="col form-table">
                          <span class="label">Channel</span>
                          <input
                            type="text"
                            class="form-control input"
                            v-model="filter.channel"
                            placeholder="Channel"
                          />
                        </div>
                      </div>
                      <div class="form-row">
                        <button
                          type="submit"
                          class="btn btn-primary btn-sm"
                          v-if="!loading"
                        >
                          <i class="fas fa-sync-alt"></i>&nbsp;Refresh
                        </button>
                        <button
                          class="btn btn-primary btn-sm"
                          v-else
                          type="button"
                          disabled
                        >
                          <span
                            class="spinner-grow spinner-grow-sm"
                            role="status"
                            aria-hidden="true"
                          ></span>
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
                                          :data="bids"
                                          :fields="label"
                                          worksheet="My Worksheet"
                                          name="Transactions.xls"
                                          style="color:#fff"
                                      ><i class="fas fa-cloud-download-alt" style="color:#fff"></i> &nbsp;Download Excel
                                      </download-excel></li></ul>
                            <div class="table-responsive">
                                  <v-data-table
                                :headers="headers"
                                :items="all"
                                :items-per-page="10"
                                 :search="search"


                                class="elevation-1">
                                <template #item.index="{ item }">
                                    {{ all.indexOf(item) + 1 }}
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

                                   <span class="badge alert-success" v-if="props.item.status !='ACTIVE'&&props.item.stage == process.stage" title="Approve"><button @click.prevent="approve(props.item)"><i class="fa-solid fa-check-double"></i></button></span>



                                </template>
                            </v-data-table>
                            </div>
                        </div>
                    </div>
                      <div class="tab-pane " :class="active=='success'?'active':''">
                        <div class="card">
                            <div class="table-responsive">
                                  <v-data-table
                                :headers="headers"
                                :items="success"
                                :items-per-page="10"
                                 :search="search"


                                class="elevation-1">
                                <template #item.index="{ item }">
                                    {{ success.indexOf(item) + 1 }}
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
                      <div class="tab-pane " :class="active=='pending'?'active':''">
                        <div class="card">
                            <div class="table-responsive">
                                  <v-data-table
                                :headers="headers"
                                :items="pending"
                                :items-per-page="10"
                                 :search="search"


                                class="elevation-1">
                                <template #item.index="{ item }">
                                    {{ pending.indexOf(item) + 1 }}
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
            display:false,
            staff:[],
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

            flow:[],
              process:[],
            mobile:false,
            processes:[],
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
            headers: [
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
                        text: 'User',
                        value: 'user.name',
                    },
                    {
                        text: 'Borrower',
                        value: 'borrower_name',
                    },
                    /*{
                        text: 'Investor',
                        value: 'investor_name',
                    },*/
                    {
                        text: 'Invoice number',
                        value: 'invoice_number',
                    },
                    {
                        text: 'Discounting amount',
                        value: 'discounting_amount',
                    },
                    {
                        text: 'Bid number',
                        value: 'bid_no',
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
                        text: 'Account number',
                        value: 'account',
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
                    { text: 'Action', value: 'actn',},
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
           const response = await axios.get('../api/transaction-approved/'+item.id+'/'+this.staff.role.name+' approved');
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
