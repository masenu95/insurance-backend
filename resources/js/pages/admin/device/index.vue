<template>
    <v-app>
        <div id="main_content" :class="mobile?'offcanvas-active':''">
    <!-- Start Main top header -->
<admin-header v-on:childToParent="menuclick"></admin-header>
    <!-- Start Rightbar setting panel -->

    <!-- Start Main leftbar navigation -->
       <sidebar-left link="Device"></sidebar-left>
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
                        <h1 class="page-title">Asset Financing</h1>
                        <ol class="breadcrumb page-breadcrumb">
                            <li class="breadcrumb-item"><router-link to="admin-home">BimaKwik</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">Asset Financing</li>
                        </ol>
                    </div>
                    <ul class="nav nav-tabs page-header-tab">
                        <li class="nav-item"><router-link to="../../admin-devices" class="nav-link active" >List View</router-link ></li>
                        <li class="nav-item"><router-link to="../../admin-device-create"  class="nav-link " >Add</router-link ></li>
                       <!-- <li class="nav-item"><a class="nav-link" :class="active=='edit'?'active':''">Edit</a></li>
                    --></ul>
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
                            placeholder="Device Name"
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
                                :items="devices"
                                :items-per-page="10"
                                 :search="search"


                                class="elevation-1">
                                <template #item.index="{ item }"
                                >
                                    {{ devices.indexOf(item) + 1 }}
                                </template>
                                 <template v-slot:item.controls="props">
                                      <button type="button" class="btn btn-icon btn-sm" title="View"><i class="fe fe-maximize"></i></button>
                                                <router-link :to="'/admin-device-edit/'+props.item.id" type="button" class="btn btn-icon btn-sm"  title="Edit"><i class="fa fa-edit"></i></router-link>

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
            devices:[],
            load:false,
            mobile:false,
            search:"",
            int:1,

            display:false,
            filter:{
             name:"",
             phone:"",
             email:"",
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
                        text: 'Category',
                        value: 'category.name',
                    },



                    {
                        text: 'Name',
                        value: 'name',
                    },

                    {
                        text: 'Price',
                        value: 'price',
                    },



                    { text: 'Created At', value: 'join' },
                     { text: 'Registered by', value: "user.name" },

                    { text: "Action", value: "controls", sortable: false }
                ],
        };
    },

    async beforeMount(){
        this.user = JSON.parse(localStorage.getItem('user'));
        const response = await axios.get('api/devices');
        this.devices = response.data;
    },
    components:{

        vueDropzone: vue2Dropzone,

    },

    methods: {
        async filterData(){
            this.loading=true;
            const response = await axios.post('api/device-filter',this.filter);

        this.devices = response.data;
        this.loading=false;


    },
       menuclick (value) {
                    this.mobile = value
                },


    },
};
</script>

<style>

</style>
