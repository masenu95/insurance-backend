<template>
    <v-app>
        <div id="main_content" :class="mobile?'offcanvas-active':''">
    <!-- Start Main top header -->
<admin-header v-on:childToParent="menuclick"></admin-header>
    <!-- Start Rightbar setting panel -->

    <!-- Start Main leftbar navigation -->
       <sidebar-left link="Customer"></sidebar-left>
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
                        <h1 class="page-title">Asset Customers</h1>
                        <ol class="breadcrumb page-breadcrumb">
                            <li class="breadcrumb-item"><router-link to="admin-home">BimaKwik</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">Customers</li>
                        </ol>
                    </div>
                    <ul class="nav nav-tabs page-header-tab">
                        <li class="nav-item"><router-link to="../../admin-customers" class="nav-link active" >List View</router-link ></li>
                        <li class="nav-item"><router-link to="../../admin-customer-create"  class="nav-link " >Add</router-link ></li>

                       <li class="nav-item"><a class="nav-link" >Edit</a></li>
                       <li class="nav-item"><button @click.prevent="resendAll"   class="nav-link " >Resend sms </button></li>
                  </ul>
                </div>
            </div>
        </div>
        <div class="section-body mt-4">
            <div class="container-fluid">
                <div class="tab-content">
                    <div class="tab-pane active">
                        <div class="filter">
                  <button @click="display=!display">
                    <i class="fa fa-filter" aria-hidden="true"></i>Filter
                  </button>
                  <br />
                  <div class="form-search" v-if="display">
                    <form @submit.prevent="filterData">
                      <div class="form-row">
                        <div class="col form-table">
                          <span class="label">Name</span>
                          <input
                            type="text"
                            class="form-control input"
                            v-model="filter.name"
                            placeholder="Customer Name"
                          />
                        </div>


                        <div class="col form-table">
                          <span class="label">Phone</span>
                          <input type="text"

                            class="form-control input"
                            v-model="filter.phone"

                          >

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
                            <div class="table-responsive">
                                  <v-data-table
                                :headers="headers"
                                :items="customers"
                                :items-per-page="10"
                                 :search="search"


                                class="elevation-1">
                                <template #item.index="{ item }"
                                >
                                    {{ customers.indexOf(item) + 1 }}
                                </template>
                                 <template v-slot:item.controls="props">
                                      <router-link :to="'../../admin-customer/'+props.item.id"  class="btn btn-icon btn-sm" title="View"><i class="fe fe-maximize"></i></router-link>
                                                <router-link class="btn btn-icon btn-sm" :to="'../../asset-customer-edit/'+props.item.id" title="Edit"><i class="fa fa-edit"></i></router-link>
                                                <button type="button" class="btn btn-icon btn-sm js-sweetalert" title="Delete" @click="removeItems(props.item.id,index)" data-type="confirm"><i :class="props.item.user.status == 'ACTIVE'?'fa fa-trash-o text-danger':'fa fa-recycle'"></i></button>
                                                <button type="button" class="btn btn-icon btn-sm js-sweetalert" title="Send sms" @click="sendSms(props.item.id)" data-type="confirm"><i class="fa fa-sms"></i></button>
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
            customers:[],
            load:false,
            mobile:false,
            search:"",
            int:1,

            display:false,
            filter:{
             name:"",
             phone:"",
             email:"",
             status:""
            },

            load:false,
            loading:false,

            headers: [
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
                        text: 'Initial Price',
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

                    { text: 'Created At', value: 'join' },
                     { text: 'Registered by', value: "user.name" },

                    { text: "Action", value: "controls", sortable: false }
                ],
        };
    },

    async beforeMount(){
        this.user = JSON.parse(localStorage.getItem('user'));
        const response = await axios.get('api/customers');
        this.customers = response.data;
    },
    components:{

        vueDropzone: vue2Dropzone,

    },

    methods: {
        async filterData(){
            this.loading=true;
            const response = await axios.post('api/customer-filter',this.filter);

        this.customers = response.data;
        this.loading=false;


    },

       menuclick (value) {
                    this.mobile = value
                },

    async resendAll(){
        var result = await this.$swal({
        title: 'Are you sure you want to resend sms to all',
        text: "You won't be able to revert this action!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#000',
        confirmButtonText: 'Yes, confirm !'
        });
        if (result.isConfirmed) {
        const response = await axios.get('../api/resend-asset-text');
        if(response.status == 200){

            this.$swal(
            'Confirmed Refresh browser!',
            response.data,
            'success'
            )
    }


    }
},
async sendSms(id){
        var result = await this.$swal({
        title: 'Are you sure you want to resend sms to all',
        text: "You won't be able to revert this action!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#000',
        confirmButtonText: 'Yes, confirm !'
        });
        if (result.isConfirmed) {
        var response = await axios.post('../api/resend-asset-text/'+id);
        console.log(response);

        if(response.status == 200){


            this.$swal(
            'Confirmed Refresh browser!',
            response.data,
            'success'
            )
    }


    }
}
},
};
</script>

<style>

</style>
