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
                                        <form @submit.prevent="submitEdit">
                                                <div class="row clearfix">
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Name</label>
                                                        <input type="text" class="form-control" v-model="formEdit.name" required>
                                                         <div class="validation-error" v-if="errorsEdit.name">
                                                                {{errorsEdit.name[0]}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Status</label>
                                                        <select class="form-control" v-model="formEdit.status" required>
                                                            <option value="">Status</option>
                                                            <option value="0">Inactive</option>
                                                            <option value="1">Active</option>
                                                        </select>
                                                         <div class="validation-error" v-if="errorsEdit.status">
                                                    {{errorsEdit.status[0]}}
                                              </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <button type="submit" class="btn btn-primary">
                                                        <div class="spinner-border text-danger" role="status" style="width:15px;height:15px" v-if="loading"></div>
                                                        Save
                                                    </button>
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


              formEdit:{
                name:"",
                status:"",
                _method: 'patch',
            },
            display:false,

            errorsEdit:[],

        };
    },

    async beforeMount(){
        const id = this.$route.params.id;
    this.id = id;
    this.load = true;
                this.user = JSON.parse(localStorage.getItem('user'));
        const response = await axios.get('../../api/sectors/'+id);

            const result = response.data;
            this.formEdit.name = result.name;
            this.formEdit.status = result.status;
            this.formEdit.id = id;
            this.load = false;

    },

    mounted() {

    },

    methods: {

       menuclick (value) {
                    this.mobile = value
                },




          async submitEdit(){
            this.loading=true;


            try{
                const response = await axios.post('../api/sectors/'+this.formEdit.id,this.formEdit);
                if(response.status == 200){



                          this.$toast.open({
                            message: ' successful edited',
                            type: 'success',
                            duration: 5000,
                            position: 'top-right'
                            // all of other options may go here
                        });

             this.loading=false;
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

    },
};
</script>

<style lang="scss" scoped>

</style>
