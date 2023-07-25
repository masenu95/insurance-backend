<template>
    <v-app>
        <div id="main_content" :class="mobile?'offcanvas-active':''">
    <!-- Start Main top header -->
<admin-header v-on:childToParent="menuclick"></admin-header>
    <!-- Start Rightbar setting panel -->

    <!-- Start Main leftbar navigation -->
       <sidebar-left link="company"></sidebar-left>
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
                            <li class="breadcrumb-item active" aria-current="page">Paying Company</li>
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
                     <div class="tab-pane"  :class="active=='all'?'active':''" >
                        <div class="card">
                            <div class="table-responsive">
                                  <v-data-table
                                :headers="headers"
                                :items="companies"
                                :items-per-page="10"
                                 :search="search"


                                class="elevation-1">
                                <template #item.index="{ item }"
                                >
                                    {{ companies.indexOf(item) + 1 }}
                                </template>
                               <template v-slot:item.controls="props">
                                      <button type="button" class="btn btn-icon btn-sm" :title="props.item.status == 1 ?'Approve':'Deactivate'" @click="activate(props.item.id)"><i class="fa fa-eye"></i></button>
                                                <button type="button" class="btn btn-icon btn-sm" @click="showEdit(props.item.id)" title="Edit"><i class="fa fa-edit"></i></button>
                                                <button type="button" class="btn btn-icon btn-sm js-sweetalert" title="Delete" @click="removeItems(props.item.id,index)" data-type="confirm"><i class="fa fa-trash-o text-danger"></i></button>
                                </template>


                                   <template v-slot:item.stat="props">

                                   <span class="tag tag-success" v-if="props.item.status==1">Active</span>
                                   <span class="tag tag-danger" style="background:red;color:#fff" v-else>Inactive</span>


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
                                        <h3 class="card-title">Paying Company Information</h3>

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
                                                        <label>Mobile (format:255622958539)</label>
                                                        <input type="text" class="form-control" v-model="form.phone" required>
                                                         <div class="validation-error" v-if="errors.phone">
                                                                {{errors.phone[0]}}
                                                        </div>
                                                    </div>
                                                </div>
                                                  <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Email </label>
                                                        <input type="email" class="form-control" v-model="form.email" required>
                                                         <div class="validation-error" v-if="errors.email">
                                                                {{errors.email[0]}}
                                                        </div>
                                                    </div>
                                                </div>
                                                   <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Sector</label>
                                                        <select class="form-control" v-model="form.sector" required>
                                                            <option value="">Sector</option>
                                                            <option v-for="sector in sectors" :key="sector.id" :value="sector.name">{{sector.name}}</option>

                                                        </select>
                                                         <div class="validation-error" v-if="errors.sector">
                                                    {{errors.sector[0]}}
                                              </div>
                                                    </div>
                                                </div>
                                                   <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Score</label>
                                                        <select class="form-control" v-model="form.score" required>
                                                            <option v-for="int in 5" :key="int" :value="int">{{int}}</option>

                                                        </select>
                                                         <div class="validation-error" v-if="errors.score">
                                                    {{errors.sector[0]}}
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
                       <div class="tab-pane" :class="active=='edit'?'active':''">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Edit Paying Company Information</h3>

                                    </div>
                                    <div class="card-body">
                                        <form @submit.prevent="submitEdit">
                                                <div class="row clearfix">
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Name</label>
                                                        <input type="text" class="form-control" v-model="formEdit.name" required>
                                                         <div class="validation-error" v-if="errors.name">
                                                                {{errorsEdit.name[0]}}
                                                        </div>
                                                    </div>
                                                </div>
                                                 <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Mobile (format:255622958539)</label>
                                                        <input type="text" class="form-control" v-model="formEdit.phone" required>
                                                         <div class="validation-error" v-if="errorsEdit.phone">
                                                                {{errorsEdit.phone[0]}}
                                                        </div>
                                                    </div>
                                                </div>
                                                  <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Email </label>
                                                        <input type="email" class="form-control" v-model="formEdit.email" required>
                                                         <div class="validation-error" v-if="errorsEdit.email">
                                                                {{errorsEdit.email[0]}}
                                                        </div>
                                                    </div>
                                                </div>
                                                   <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Sector</label>
                                                        <select class="form-control" v-model="formEdit.sector" required>
                                                            <option value="">Sector</option>
                                                            <option v-for="sector in sectors" :key="sector.id" :value="sector.name">{{sector.name}}</option>

                                                        </select>
                                                         <div class="validation-error" v-if="errorsEdit.sector">
                                                    {{errorsEdit.sector[0]}}
                                              </div>
                                                    </div>
                                                </div>
                                                   <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Score</label>
                                                        <select class="form-control" v-model="formEdit.score" required>
                                                            <option v-for="int in 5" :key="int" :value="int">{{int}}</option>

                                                        </select>
                                                         <div class="validation-error" v-if="errorsEdit.score">
                                                    {{errorsEdit.sector[0]}}
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

    name: 'WorkspaceJsonBank',

    data() {
        return {search:"",search:"",
            companies:[],
            load:false,
            loading:false,
            mobile:false,
            search:"",
            active:"all",
            int:1,
            form:{
                name:"",
                status:"",
                sector:"",
                score:1,
            },
              formEdit:{
                name:"",
                status:"",
                sector:"",
                score:1,
                _method: 'patch',
            },
            sectors:[],
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
                        {
                        text: 'Email',
                        value: 'email',
                    },
                        {
                        text: 'Mobile',
                        value: 'mobile',
                    },
                        {
                        text: 'Sector',
                        value: 'sector',
                    },
                        {
                        text: 'Score',
                        value: 'score',
                    },

                    { text: 'Created At', value: 'join' },
                     { text: 'Status', value: "stat" },

                    { text: "Action", value: "controls", sortable: false }
                ],
        };
    },

    async beforeMount(){
                this.user = JSON.parse(localStorage.getItem('user'));
        const response = await axios.get('api/companies');

         const sectors = await axios.get('api/all-sector');
        this.sectors = sectors.data;
        this.companies = response.data;
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
                const response = await axios.post('../api/companies',this.form);
                if(response.status == 200){
                    this.loading=false;

                          this.$toast.open({
                            message: response.data.name +' successful added',
                            type: 'success',
                            duration: 5000,
                            position: 'top-right'
                            // all of other options may go here
                        });
             const res = await axios.get('api/companies');
        this.companies = res.data;

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
            text: "You won't be able to revert this company",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes,'
        });
        if (result.isConfirmed) {
          var response = await axios.get('../../api/activate-company/'+id);
          if(response.status == 200){

                this.$swal(
                'Updated',
                response.data,
                'success'
            )
             const res = await axios.get('api/companies');
             this.companies = res.data;
          }

        }
        },

            async showEdit(uid){
            this.load = true;
            this.active ="edit";
            const result = this.companies.find( ({ id }) => id === uid );
            this.formEdit.name = result.name;
            this.formEdit.email = result.email;
            this.formEdit.phone = result.mobile;
            this.formEdit.status = result.status;
            this.formEdit.sector=result.sector;
            this.formEdit.score=result.score;
            this.formEdit.id = uid;
            this.load = false;
        },
          async submitEdit(){
            this.loading=true;


            try{
                const response = await axios.post('../api/companies/'+this.formEdit.id,this.formEdit);
                if(response.status == 200){



                          this.$toast.open({
                            message: ' successful edited',
                            type: 'success',
                            duration: 5000,
                            position: 'top-right'
                            // all of other options may go here
                        });
             const res = await axios.get('api/companies');
             this.companies = res.data;
               const result = this.companies.find( ({ id }) => id === this.formEdit.id );
            this.sector = result;
             this.loading=false;
            this.active = "all";

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
          var response = await axios.delete('../../api/companies/'+id);
          if(response.status == 200){

                this.$swal(
                'Deleted!',
                response.data,
                'success'
            )
             const res = await axios.get('api/companies');
             this.companies = res.data;
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
