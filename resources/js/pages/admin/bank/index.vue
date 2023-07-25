<template>
    <v-app>
        <div id="main_content" :class="mobile?'offcanvas-active':''">
    <!-- Start Main top header -->
<admin-header v-on:childToParent="menuclick"></admin-header>
    <!-- Start Rightbar setting panel -->

    <!-- Start Main leftbar navigation -->
       <sidebar-left link="bank"></sidebar-left>
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
                        <h1 class="page-title">Banks</h1>
                        <ol class="breadcrumb page-breadcrumb">
                            <li class="breadcrumb-item"><router-link to="admin-home">BimaKwik</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">Bank</li>
                        </ol>
                    </div>
                    <ul class="nav nav-tabs page-header-tab">

                        <li class="nav-item"><router-link to="../../banks" class="nav-link active" @click.prevent="active='all'">List View</router-link></li>
                        <li class="nav-item"><router-link to="../../bank-create" class="nav-link "  >Add</router-link></li>
                        <li class="nav-item"><a class="nav-link"  >Edit</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="section-body mt-4">
            <div class="container-fluid">
                <div class="tab-content">
                    <div class="tab-pane"  :class="active=='all'?'active':''" >
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
                            placeholder="Bank Name"
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
                            <div class="table-responsive">
                                  <v-data-table
                                :headers="headers"
                                :items="banks"
                                :items-per-page="10"
                                 :search="search"


                                class="elevation-1">
                                <template #item.index="{ item }"
                                >
                                    {{ banks.indexOf(item) + 1 }}
                                </template>
                                 <template v-slot:item.controls="props">
                                      <button type="button" class="btn btn-icon btn-sm" :title="props.item.status == 1 ?'Approve':'Deactivate'" @click="activate(props.item.id)"><i class="fa fa-eye"></i></button>
                                                <router-link :to ="'../../bank-edit/'+props.item.id" class="btn btn-icon btn-sm" title="Edit"><i class="fa fa-edit"></i></router-link>
                                                <button type="button" class="btn btn-icon btn-sm js-sweetalert" title="Delete" @click="removeItems(props.item.id,index)" data-type="confirm"><i class="fa fa-trash-o text-danger"></i></button>
                                </template>


                                   <template v-slot:item.stat="props">

                                   <span class="tag tag-success" v-if="props.item.status==1">Active</span>
                                   <span class="tag tag-danger" v-else>Inactive</span>


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

export default {

    name: 'WorkspaceJsonBank',

    data() {
        return {search:"",search:"",
            banks:[],
            load:false,
            loading:false,
            mobile:false,
            search:"",
            active:"all",
            display:false,
            filter:{
             name:"",
            },



            errors:[],

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

                    { text: 'Created At', value: 'join' },
                     { text: 'Status', value: "stat" },

                    { text: "Action", value: "controls", sortable: false }
                ],
        };
    },

    async beforeMount(){
                this.user = JSON.parse(localStorage.getItem('user'));
        const response = await axios.get('api/banks');
        this.banks = response.data;
    },

    mounted() {

    },

    methods: {
       menuclick (value) {
                    this.mobile = value
                },

       async activate(id) {

                var result = await this.$swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this bank",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes,'
        });
        if (result.isConfirmed) {
          var response = await axios.get('../../api/activate-bank/'+id);
          if(response.status == 200){

                this.$swal(
                'Updated',
                response.data,
                'success'
            )
             const res = await axios.get('api/banks');
             this.banks = res.data;
          }

        }
        },


        async filterData(){
            this.loading=true;
            const response = await axios.post('api/bank-filter',this.filter);

        this.banks = response.data;
        this.loading=false;


    },

      async removeItems(id,index){
      var result = await this.$swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this bank",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        });
        if (result.isConfirmed) {
            try{
          var response = await axios.delete('../../api/banks/'+id);
          if(response.status == 200){

                this.$swal(
                'Deleted!',
                response.data,
                'success'
            )
             const res = await axios.get('api/banks');
             this.banks = res.data;
          }
          }catch(e){
                 this.$toast.open({
                            message: e.response.data.error,
                            type: 'error',
                            duration: 5000,
                            position: 'bottom-right'
                            // all of other options may go here
                        });
          }

        }

    },
    },
};
</script>

<style lang="scss" scoped>

</style>
