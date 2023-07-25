<template>
    <v-app>
        <div id="main_content" :class="mobile?'offcanvas-active':''">
    <!-- Start Main top header -->
<admin-header v-on:childToParent="menuclick"></admin-header>
    <!-- Start Rightbar setting panel -->

    <!-- Start Main leftbar navigation -->
       <sidebar-left link="sector"></sidebar-left>
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
                        <h1 class="page-title">Sectors</h1>
                        <ol class="breadcrumb page-breadcrumb">
                            <li class="breadcrumb-item"><router-link to="admin-home">BimaKwik</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">Sector</li>
                        </ol>
                    </div>
                    <ul class="nav nav-tabs page-header-tab">
                        <li class="nav-item"><router-link to="../../sectors" class="nav-link active">List View</router-link></li>
                        <li class="nav-item"><router-link to="../../sector-create" class="nav-link" >Add</router-link></li>
                        <li class="nav-item"><a class="nav-link"  >Edit</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="section-body mt-4">
            <div class="container-fluid">
                <div class="tab-content">


                    <div class="tab-pane active">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Sector Information</h3>

                                    </div>
                                    <div class="card-body">
                                        <form @submit.prevent="submit">
                                                <div class="row clearfix">
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Name</label>
                                                        <input type="text" class="form-control" v-model="form.name" required>
                                                         <div class="validation-error" v-if="errors.name">
                                                                {{errors.name[0]}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Status</label>
                                                        <select class="form-control" v-model="form.status" required>
                                                            <option value="">Status</option>
                                                            <option value="0">Inactive</option>
                                                            <option value="1">Active</option>
                                                        </select>
                                                         <div class="validation-error" v-if="errors.status">
                                                    {{errors.status[0]}}
                                              </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                     <button @click.prevent="active='all'" class="btn btn-outline-secondary">Cancel</button>
                                                </div>
                                            </div>
                                        </form>

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

export default {

    name: 'WorkspaceJsonSector',

    data() {
        return {search:"",search:"",
            sectors:[],
            load:false,
            loading:false,
            mobile:false,
            active:"all",
            search:"",
            form:{
                name:"",
                status:"",
            },
              formEdit:{
                name:"",
                status:"",
                _method: 'patch',
            },
            display:false,
            filter:{
             name:"",
            },
            errors:[],
            errorsEdit:[],
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
        const response = await axios.get('api/sectors');
        this.sectors = response.data;
    },

    mounted() {

    },

    methods: {
        async filterData(){
            this.loading=true;
            const response = await axios.post('api/sector-filter',this.filter);

        this.sectors = response.data;
        this.loading=false;


    },
       menuclick (value) {
                    this.mobile = value
                },
            async submit(){
            this.loading=true;
            this.form.name = this.form.name.toLowerCase();
            try{
                const response = await axios.post('../api/sectors',this.form);
                if(response.status == 200){
                    this.loading=false;

                          this.$toast.open({
                            message: response.data.name +' successful added',
                            type: 'success',
                            duration: 5000,
                            position: 'top-right'
                            // all of other options may go here
                        });
                        this.$router.push('../sectors');

                }
            }catch(e){
                if(e.response){
                    if(e.response.status = 422){
                        this.errors = e.response.data.errors;

                         this.$toast.open({
                            message: e.response.data.message,
                            type: 'error',
                            duration: 5000,
                            position: 'bottom-right'
                            // all of other options may go here
                        });


                    }
                } else{
                    this.$toast.open({
                            message: 'Internal server error',
                            type: 'error',
                            duration: 5000,
                            position: 'bottom-right'
                            // all of other options may go here
                        });
                }
                 this.loading=false;
            }

        },
         async activate(id) {

                var result = await this.$swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this sector",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes,'
        });
        if (result.isConfirmed) {
          var response = await axios.get('../../api/activate-sector/'+id);
          if(response.status == 200){

                this.$swal(
                'Updated',
                response.data,
                'success'
            )
             const res = await axios.get('api/sectors');
             this.sectors = res.data;
          }

        }
        },


    },
};
</script>

<style lang="scss" scoped>

</style>
