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
                        <li class="nav-item"><router-link to="../../banks" class="nav-link " @click.prevent="active='all'">List View</router-link></li>
                        <li class="nav-item"><router-link to="../../bank-create" class="nav-link active"  >Add</router-link></li>
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
                                        <h3 class="card-title">Bank Information</h3>

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
                                                     <router-link to="../banks" class="btn btn-outline-secondary">Cancel</router-link>
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

    name: 'WorkspaceJsonBank',

    data() {
        return {search:"",search:"",

            load:false,
            loading:false,
            mobile:false,

            display:false,
            filter:{
             name:"",
            },

            form:{
                id:0,
                name:"",
                status:"",
            },

            errors:[],


        };
    },

    async beforeMount(){
                this.user = JSON.parse(localStorage.getItem('user'));

    },

    mounted() {

    },

    methods: {
       menuclick (value) {
                    this.mobile = value
                },
            async submit(){
            this.loading=true;
            this.form.name = this.form.name.toLowerCase();
            try{
                const response = await axios.post('../api/banks',this.form);
                if(response.status == 200){
                    this.loading=false;

                          this.$toast.open({
                            message: response.data.name +' successful added',
                            type: 'success',
                            duration: 5000,
                            position: 'top-right'
                            // all of other options may go here
                        });

                        this.$router.push('../banks');

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
