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
                        <h1 class="page-title">Workflow</h1>
                        <ol class="breadcrumb page-breadcrumb">
                            <li class="breadcrumb-item"><router-link to="admin-home">BimaKwik</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">Workflow</li>
                        </ol>
                    </div>
                 <ul class="nav nav-tabs page-header-tab">
                        <li class="nav-item"><a class="nav-link active" @click.prevent="active='all'">List View</a></li>
                        <li class="nav-item"><a class="nav-link"  @click.prevent="active='add'" >Add</a></li>
                        <li class="nav-item"><a class="nav-link"  >Edit</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="section-body mt-4">
            <div class="container-fluid">
                <div class="tab-content">
                    <div class="tab-pane" :class="active=='all'?'active':''" >
                        <div class="card">
                            <div class="table-responsive">
                                  <v-data-table
                                :headers="headers"
                                :items="process.flows"
                                :items-per-page="10"
                                 :search="search"


                                class="elevation-1">
                                <template #item.index="{ item }"
                                >
                                    {{ process.flows.indexOf(item) + 1 }}
                                </template>
                                 <template v-slot:item.controls="props">
                                      <button type="button" class="btn btn-icon btn-sm" title="show" @click="show(props.item.id)"><i class="fa fa-eye"></i></button>
                                                <button type="button" class="btn btn-icon btn-sm" @click="showEdit(props.item.id)" title="Edit"><i class="fa fa-edit"></i></button>
                                                <button type="button" class="btn btn-icon btn-sm js-sweetalert" title="Delete" @click="removeItems(props.item.id,index)" data-type="confirm"><i class="fa fa-trash-o text-danger"></i></button>
                                </template>



                                     <template v-slot:item.join="props">
                                        {{props.item.created_at | formatDate}}
                                    </template>
                            </v-data-table>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" :class="active=='add'?'active':''">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Work flow</h3>

                                    </div>
                                    <div class="card-body">
                                        <form @submit.prevent="submit">
                                                <div class="row clearfix">
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Process</label>
                                                       <select class="form-control" v-model="form.process" required>
                                                            <option :value="process.id">{{process.name}}</option>
                                                        </select>
                                                         <div class="validation-error" v-if="errors.process">
                                                                {{errors.process[0]}}
                                                        </div>
                                                    </div>
                                                </div>



                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Stage</label>
                                                        <select class="form-control" v-model="form.stage" required>
                                                            <option v-for="int in 13" :key="int" :value="int">{{int}}</option>

                                                        </select>
                                                         <div class="validation-error" v-if="errors.stage">
                                                    {{errors.stage[0]}}
                                              </div>
                                                    </div>
                                                </div>

                                                   <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label>User Group</label>
                                        <select  v-model="form.role" class="form-control" required>
                                            <option value="">User group</option>

                                            <option v-for="role in roles" :key="role.id" :value="role.id">{{role.name}}</option>

                                        </select>
                                                 <div class="validation-error" v-if="errors.role">
                                                {{errors.role[0]}}
                                            </div>
                                    </div>
                                                   </div>
                                                      <div class="col-md-12 col-sm-12">
                                         <div class="form-group">
                                        <label>Sms template</label>
                                        <textarea  v-model="form.sms" class="form-control" required>
                                        </textarea>
                                                 <div class="validation-error" v-if="errors.sms">
                                                {{errors.sms[0]}}
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
                     <div class="tab-pane" :class="active=='edit'?'active':''">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Edit Work flow</h3>

                                    </div>
                                    <div class="card-body">
                                        <form @submit.prevent="submitEdit">
                                                <div class="row clearfix">
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Process</label>
                                                       <select class="form-control" v-model="formEdit.process" required>
                                                            <option :value="process.id">{{process.name}}</option>
                                                        </select>
                                                         <div class="validation-error" v-if="errorsEdit.process">
                                                                {{errorsEdit.process[0]}}
                                                        </div>
                                                    </div>
                                                </div>



                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Stage</label>
                                                        <select class="form-control" v-model="formEdit.stage" required>
                                                            <option v-for="int in 13" :key="int" :value="int">{{int}}</option>

                                                        </select>
                                                         <div class="validation-error" v-if="errorsEdit.stage">
                                                    {{errorsEdit.stage[0]}}
                                              </div>
                                                    </div>
                                                </div>

                                                   <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label>User Group</label>
                                        <select  v-model="formEdit.role" class="form-control" required>
                                            <option value="">User group</option>

                                            <option v-for="role in roles" :key="role.id" :value="role.id">{{role.name}}</option>

                                        </select>
                                                 <div class="validation-error" v-if="errorsEdit.role">
                                                {{errorsEdit.role[0]}}
                                            </div>
                                    </div>
                                                   </div>
                                                      <div class="col-md-12 col-sm-12">
                                         <div class="form-group">
                                        <label>Sms template</label>
                                        <textarea  v-model="formEdit.sms" class="form-control" required>
                                        </textarea>
                                                 <div class="validation-error" v-if="errorsEdit.sms">
                                                {{errorsEdit.sms[0]}}
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
                       <div class="tab-pane" :class="active=='detail'?'active':''">
                             <div class="row"  v-if="active=='detail'&&load==false">
                            <div class="col-xl-12 col-md-12">
                                <div class="card">
                                    <div class="card-body w_user">

                                        <div  class="wid-u-info" style="text-align:center">
                                            <h5>{{flow.role.name}}</h5>

                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">About</h3>

                                    </div>
									<div class="card-body">


										<ul class="list-group">
											<li class="list-group-item">
												<b>Process </b>
												<div class="profile-desc-item pull-right">{{flow.process.name}}</div>
											</li>
                                            <li class="list-group-item">
												<b>Stage</b>
												<div class="profile-desc-item pull-right">{{flow.stage}}</div>
											</li>
                                            <li class="list-group-item">
												<b>Role</b>
												<div class="profile-desc-item pull-right">{{flow.role.name}}</div>
											</li>








                                        </ul>

                                    </div>
                                      <div class="card-footer text-center">
                                        <div class="row" style="text-align:center">
                                            <h6>Sms</h6>
											{{flow.sms}}

										</div>
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
            flows:[],
            flow:[],
            load:false,
            loading:false,
            mobile:false,
            active:"all",
            process:[],
            search:"",
            int:1,
            form:{
                sms:"",
                role:"",
                stage:1,
                process:"",
            },
             formEdit:{
                sms:"",
                role:"",
                stage:1,
                process:"",
                _method: 'patch',
            },
            roles:[],
            errors:[],
            errorsEdit:[],
            headers: [
                    {
                        value: 'index',
                        text: '#',
                         sortable: false,
                    },
                      {
                        text: 'Process',
                        value: 'process.name',
                    },


                        {
                        text: 'Stage',
                        value: 'stage',
                    },
                        {
                        text: 'User Group',
                        value: 'role.name',
                    },

                    { text: 'Created At', value: 'join' },
                     { text: 'Created By', value: "user.name" },

                    { text: "Action", value: "controls", sortable: false }
                ],
        };
    },

    async beforeMount(){
                this.user = JSON.parse(localStorage.getItem('user'));

                 const id = this.$route.params.id;
        const response = await axios.get('../api/process/'+id);


        this.process = response.data;
        this.form.process = response.data.id;
        const role = await axios.get('../api/roles');
        this.roles = role.data;

    },

    mounted() {

    },

    methods: {
       menuclick (value) {
                    this.mobile = value
                },
            async submit(){
            this.loading=true;

            try{
                const response = await axios.post('../../api/flows',this.form);
                if(response.status == 200){
                    this.loading=false;

                          this.$toast.open({
                            message:'workflow is successful added',
                            type: 'success',
                            duration: 5000,
                            position: 'top-right'
                            // all of other options may go here
                        });


        this.form.sms="";
        this.form.role="";
        this.form.stage=1;
                  const id = this.$route.params.id;
              const res = await axios.get('../api/process/'+id);


        this.process = res.data;


                }
            }catch(e){
                console.log(e);
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
          async show(uid) {
               const result = this.process.flows.find( ({ id }) => id === uid );

               this.flow = result;
               this.active = 'detail';

        },

            async showEdit(uid){
            this.load = true;
            this.active ="edit";
            const result = this.process.flows.find( ({ id }) => id === uid );

            this.formEdit.id = uid;
            this.formEdit.sms = result.sms;
            this.formEdit.role=result.role_id;
            this.formEdit.stage=result.stage,
            this.formEdit.process=result.process_id,
            this.load = false;
        },
          async submitEdit(){
            this.loading=true;


            try{
                const response = await axios.post('../api/flows/'+this.formEdit.id,this.formEdit);
                if(response.status == 200){



                          this.$toast.open({
                            message: ' successful edited',
                            type: 'success',
                            duration: 5000,
                            position: 'top-right'
                            // all of other options may go here
                        });
             const res = await axios.get('api/flows');
             this.flow = res.data;
               const result = this.process.flows.find( ({ id }) => id === this.formEdit.id );
            this.flow = result;
             this.loading=false;
            this.active = "all";

                }
            }catch(e){
                console.log(e);
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
      async removeItems(id,index){
      var result = await this.$swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this sector",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        });
        if (result.isConfirmed) {
            try{
          var response = await axios.delete('../../api/flows/'+id);
          if(response.status == 200){

                this.$swal(
                'Deleted!',
                response.data,
                'success'
            )
                 const id = this.$route.params.id;
        const result = await axios.get('../api/process/'+id);


        this.process = result.data;
        this.form.process = result.data.id;
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
