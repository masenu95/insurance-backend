<template>
    <v-app>
        <div id="main_content" :class="mobile?'offcanvas-active':''">
    <!-- Start Main top header -->
<admin-header v-on:childToParent="menuclick"></admin-header>
    <!-- Start Rightbar setting panel -->

    <!-- Start Main leftbar navigation -->
       <sidebar-left link="process"></sidebar-left>
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
                        <h1 class="page-title">Processes</h1>
                        <ol class="breadcrumb page-breadcrumb">
                            <li class="breadcrumb-item"><router-link to="admin-home">BimaKwik</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">Process</li>
                        </ol>
                    </div>
                    <ul class="nav nav-tabs page-header-tab">
                        <li class="nav-item"><a class="nav-link active"  href="#process-all">List View</a></li>

                    </ul>
                </div>
            </div>
        </div>
        <div class="section-body mt-4">
            <div class="container-fluid">
                <div class="tab-content">
                    <div class="tab-pane active" id="process-all">
                        <div class="card">
                            <div class="table-responsive">
                                  <v-data-table
                                :headers="headers"
                                :items="processes"
                                :items-per-page="10"
                                 :search="search"


                                class="elevation-1">
                                <template #item.index="{ item }"
                                >
                                    {{ processes.indexOf(item) + 1 }}
                                </template>
                                 <template v-slot:item.controls="props">
                                      <router-link :to="'/flow/'+props.item.id" type="button" class="btn btn-icon btn-sm"  title="Create workflow"><i class="fa fa-plus"></i></router-link>

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
            processes:[],
            load:false,
            mobile:false,
            search:"",
            form:{
                name:"",
                status:"",
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




                    { text: "Action", value: "controls", sortable: false }
                ],
        };
    },

    async beforeMount(){
                this.user = JSON.parse(localStorage.getItem('user'));
        const response = await axios.get('api/processes');
        this.processes = response.data;
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
                const response = await axios.post('../api/processes',this.form);
                if(response.status == 200){
                    this.loading=false;

                          this.$toast.open({
                            message: response.data.name +' successful added',
                            type: 'success',
                            duration: 5000,
                            position: 'top-right'
                            // all of other options may go here
                        });
             const res = await axios.get('api/processes');
        this.processes = res.data;

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
