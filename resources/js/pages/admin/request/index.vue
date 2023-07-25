<template>
    <v-app>
        <div id="main_content" :class="mobile?'offcanvas-active':''">
    <!-- Start Main top header -->
<admin-header v-on:childToParent="menuclick"></admin-header>
    <!-- Start Rightbar setting panel -->

    <!-- Start Main leftbar navigation -->
       <sidebar-left link="request"></sidebar-left>
    <!-- Start project content area -->
    <!-- Start project content area -->
    <div class="page">
        <!-- Start Page header -->
        <top-nav :selectedBalance="select"></top-nav>
        <!-- Start Page title and tab -->

        <div class="section-body">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center ">
                    <div class="header-action">
                        <h1 class="page-title">Asset Financing Request</h1>
                        <ol class="breadcrumb page-breadcrumb">
                            <li class="breadcrumb-item"><router-link to="admin-home">BimaKwik</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">Asset Financing Request</li>
                        </ol>
                    </div>
                    <ul class="nav nav-tabs page-header-tab">
                        <li class="nav-item"><router-link to="../../admin-device-request" class="nav-link active" >List View</router-link ></li>


                  </ul>
                </div>
            </div>
        </div>
        <div class="section-body mt-4">
            <div class="container-fluid">
                <div class="right" style="float:right">
                           <router-link to="../../admin-device-generate"  style="background:#274db3;color:#fff;padding:5px 10px;border-radius:6px">Generate Invoice: {{select | formatNumber}} </router-link>
                    </div>

                <div class="tab-content">
                    <div class="tab-pane active">
                        <ul class="header-dropdown">
                                    <li>
                                     <download-excel
                                          class="btn btn-info"
                                          :data="customers"
                                          :fields="label"
                                          worksheet="My Worksheet"
                                          name="customers.xls"
                                          style="color:#fff"
                                      ><i class="fas fa-cloud-download-alt" style="color:#fff"></i> &nbsp;Download Excel
                                      </download-excel></li></ul>
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

                                                <button type="button" class="btn btn-icon btn-sm js-sweetalert" v-if="props.item.status =='RECEIVED'" title="Select" @click="selectRequest(props.item.id)" data-type="confirm"><i class="fa fa-check"></i></button>
                                                <button type="button" class="btn btn-icon btn-sm js-sweetalert" v-if="props.item.status =='SELECTED'" title="unselect" @click="unselectRequest(props.item.id)" data-type="confirm"><i class="fa fa-times"></i></button>
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
            select:0,
            label:{
                "name":"name",
                "phone":"phone",
                "Device Category":"device.category.name",
                "Device name":"device.name",
                "Price":"price",
                "vicoba/cmpd fee":"commission",
                "insurance":"insurance",
                "Technology Cost":"technology",
                "Loan Cost":"loan",
                "Down Payment":"initial",
                "Total":"total",
                "Remain":"remain",
                "Reference":"reference",
                "Arrears":"arrears",
                "ime":"ime",

            },

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
                        text: 'Arrears',
                        value: 'arrears',
                    },
                    {
                        text: 'reference',
                        value: 'reference',
                    },

                    {
                        text: 'Start Date',
                        value: 'start',
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
        };
    },

    async beforeMount(){
        this.user = JSON.parse(localStorage.getItem('user'));
        const response = await axios.get('api/device-request');
        this.customers = response.data;

        const amount = await axios.get('api/request-amount');
        this.select = amount.data;


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

    async selectRequest(id){
        var result = await this.$swal({
        title: 'Are you sure you want to select this device',
        text: "You won't be able to revert this action!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#000',
        confirmButtonText: 'Yes, confirm !'
        });
        if (result.isConfirmed) {
        const response = await axios.post('../api/select-request/'+id);
        if(response.status == 200){
        const resp = await axios.get('api/device-request');
        this.customers = resp.data;
        this.select = response.data;

            this.$swal(
            'Confirmed Refresh browser!',
            response.data,
            'success'
            )
    }


    }
},

async unselectRequest(id){
        var result = await this.$swal({
        title: 'Are you sure you want to unselect this device',
        text: "You won't be able to revert this action!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#000',
        confirmButtonText: 'Yes, confirm !'
        });
        if (result.isConfirmed) {
        const response = await axios.post('../api/unselect-request/'+id);
        if(response.status == 200){
        const resp = await axios.get('api/device-request');
        this.customers = resp.data;
        this.select = response.data;

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
