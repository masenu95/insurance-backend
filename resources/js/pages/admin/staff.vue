<template>
    <v-app>
        <div id="main_content" :class="mobile?'offcanvas-active':''">
    <!-- Start Main top header -->
<admin-header v-on:childToParent="menuclick"></admin-header>
    <!-- Start Rightbar setting panel -->

    <!-- Start Main leftbar navigation -->
       <sidebar-left link="Staff"></sidebar-left>
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
                        <h1 class="page-title">Staffs</h1>
                        <ol class="breadcrumb page-breadcrumb">
                            <li class="breadcrumb-item"><router-link to="admin-home">BimaKwik</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">Staffs</li>
                        </ol>
                    </div>
                    <ul class="nav nav-tabs page-header-tab">
                        <li class="nav-item"><a class="nav-link" :class="active=='all'?'active':''" @click.prevent="active='all'">List View</a></li>
                        <li class="nav-item"><a class="nav-link" :class="active=='add'?'active':''" @click.prevent="active='add'" >Add</a></li>
                        <li class="nav-item"><a class="nav-link" :class="active=='edit'?'active':''">Edit</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="section-body mt-4">
            <div class="container-fluid">
                <div class="tab-content">
                    <div class="tab-pane" :class="active=='all'?'active':''">
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
                            placeholder="Staff Name"
                          />
                        </div>

                        <div class="col form-table">
                          <span class="label">Email</span>
                          <input
                            type="text"
                            class="form-control input"
                            v-model="filter.email"
                            placeholder="Email"
                          />
                        </div>
                      </div>



                      <div class="form-row">
                        <div class="col form-table">
                          <span class="label">Phone</span>
                          <input type="text"

                            class="form-control input"
                            v-model="filter.phone"

                          >

                        </div>
                        <div class="col form-table">
                          <span class="label">Status</span>
                          <select

                            class="form-control input"
                            v-model="filter.status"

                          >
                          <option value="">Status</option>
                          <option value="ACTIVE">ACTIVE</option>
                          <option value="PENDING">PENDING</option>
                          </select>
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
                                :items="staffs"
                                :items-per-page="10"
                                 :search="search"


                                class="elevation-1">
                                <template #item.index="{ item }"
                                >
                                    {{ staffs.indexOf(item) + 1 }}
                                </template>
                                 <template v-slot:item.controls="props">
                                      <button type="button" class="btn btn-icon btn-sm" title="View"><i class="fe fe-maximize"></i></button>
                                                <button type="button" class="btn btn-icon btn-sm" @click="showEdit(props.item.id)" title="Edit"><i class="fa fa-edit"></i></button>
                                                <button type="button" class="btn btn-icon btn-sm js-sweetalert" title="Delete" @click="removeItems(props.item.id,index)" data-type="confirm"><i :class="props.item.user.status == 'ACTIVE'?'fa fa-trash-o text-danger':'fa fa-recycle'"></i></button>
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

                    <div class="tab-pane" :class="active=='add'?'active':''">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Staff Information</h3>

                                    </div>
                                    <div class="card-body">

                       <form @submit.prevent="submit">
                                <div class="row clearfix">
                                   <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>full name</label>
                                            <input type="text" class="form-control" v-model="form.name"  placeholder="Full name *" required>
                                              <div class="validation-error" v-if="errors.name">
                                                    {{errors.name[0]}}
                                              </div>
                                        </div>
                                    </div>


                                       <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" class="form-control" v-model="form.password"  placeholder="Password *" required>
                                              <div class="validation-error" v-if="errors.password">
                                                    {{errors.password[0]}}
                                              </div>
                                        </div>
                                    </div>
                                       <div class="col-sm-6">
                                        <div class="form-group" >
                                            <label>Confirm Password</label>
                                              <input type="password" class="form-control" v-model="form.password_confirmation"  placeholder="Confirm Password *" required>

                                                   <div class="validation-error" v-if="errors.password">
                                                    {{errors.password[0]}}
                                              </div>
                                        </div>
                                    </div>


                                </div>
                                <div class="row clearfix">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                           <label> Phone(eg:255622958539)</label>
                                            <input type="text"  v-model="form.phone" class="form-control" placeholder="Phone *" required>
                                            <div class="validation-error" v-if="errors.phone">
                                                {{errors.phone[0]}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email"  v-model="form.email" class="form-control" placeholder="Email *" required>
                                                   <div class="validation-error" v-if="errors.email">
                                                    {{errors.email[0]}}
                                              </div>
                                        </div>
                                    </div>


                                       <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>ID Type</label>
                                        <select class="form-control show-tick" v-model="form.id_type">
                                            <option value="">Select ID type</option>
                                            <option value="nida">National ID (NIDA)</option>
                                            <option value="zanzibar">Zanzibar ID (NIDA)</option>
                                            <option value="voter">Voter ID</option>
                                            <option value="passport">Passport</option>
                                            <option value="driving">Driving Licence</option>
                                        </select>
                                        <div class="validation-error" v-if="errors.id_type">
                                                {{errors.id_type[0]}}
                                            </div>
                                    </div>
                                </div>



                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>ID Number</label>
                                        <input type="text" v-model="form.id_no" class="form-control"
                                               placeholder="ID number *" required>
                                                 <div class="validation-error" v-if="errors.no">
                                                {{errors.id_no[0]}}
                                            </div>
                                    </div>
                                </div>

                                  <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>Role</label>
                                        <select  v-model="form.role" class="form-control" required>
                                            <option value="">Role</option>

                                            <option v-for="role in roles" :key="role.id" :value="role.id">{{role.name}}</option>

                                        </select>
                                                 <div class="validation-error" v-if="errors.role">
                                                {{errors.role[0]}}
                                            </div>
                                    </div>
                                </div>
                               <div class="col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label>Region</label>
                                            <select class="form-control show-tick" v-model="region">
                                                <option value="">Select Region</option>
                                                <option v-for="reg in regions" :key="reg.id" :value="reg">{{reg.name}}</option>
                                            </select>
                                                   <div class="validation-error" v-if="errors.region">
                                                    {{errors.region[0]}}
                                              </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12" v-if="region != ''">
                                        <div class="form-group" >
                                            <label>District</label>
                                            <select class="form-control show-tick" v-model="district">
                                                <option value="">Select District</option>
                                                <option  v-for="dist in region.districts" :key="dist.id" :value="dist">{{dist.name}}</option>
                                            </select>
                                                <div class="validation-error" v-if="errors.district">
                                                    {{errors.district[0]}}
                                              </div>
                                        </div>
                                    </div>
                                     <div class="col-md-4 col-sm-12"  v-if="district != ''">
                                        <div class="form-group">
                                            <label>Ward</label>
                                             <select class="form-control show-tick" v-model="form.ward">
                                                <option value="">Select Ward</option>
                                                <option  v-for="ward in district.wards" :key="ward.id" :value="ward.name">{{ward.name}}</option>
                                            </select>
                                                <div class="validation-error" v-if="errors.ward">
                                                    {{errors.ward[0]}}
                                              </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label>Street</label>
                                            <input type="text"  v-model="form.street" class="form-control" placeholder="Street *" required />
                                                <div class="validation-error" v-if="errors.street">
                                                    {{errors.street[0]}}
                                              </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row clearfix">
                                    <div class="col-12">
                                        <label>Upload Id card</label>
                                            <vue-dropzone :options="dropzoneOptions" v-on:vdropzone-success="uploadSuccess" id="upload"></vue-dropzone>
                                    </div>
                                </div>
                                        <div class="row clearfix" style="margin-top:20px">

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
                                        <h3 class="card-title">Edit Staff Information</h3>

                                    </div>
                                    <div class="card-body">
                                    <form @submit.prevent="submitEdit">
                                        <div class="row clearfix">
                                        <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label>full name</label>
                                                    <input type="text" class="form-control" v-model="formEdit.name"  placeholder="Full name *" required>
                                                    <div class="validation-error" v-if="errorsEdit.name">
                                                            {{errorsEdit.name[0]}}
                                                    </div>
                                                </div>
                                            </div>





                                        </div>
                                        <div class="row clearfix">
                                            <div class="col-md-6 col-sm-12">
                                                <div class="form-group">
                                                <label> Phone(eg:0622958539)</label>
                                                    <input type="text"  v-model="formEdit.phone" class="form-control" placeholder="Phone *" required>
                                                    <div class="validation-error" v-if="errorsEdit.phone">
                                                        {{errorsEdit.phone[0]}}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="email"  v-model="formEdit.email" class="form-control" placeholder="Email *" required>
                                                        <div class="validation-error" v-if="errorsEdit.email">
                                                            {{errorsEdit.email[0]}}
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-4 col-sm-12">
                                            <div class="form-group">
                                                <label>ID Type</label>
                                                <select class="form-control show-tick" v-model="formEdit.id_type">
                                                    <option value="">Select ID type</option>
                                                    <option value="nida">National ID (NIDA)</option>
                                                    <option value="zanzibar">Zanzibar ID (NIDA)</option>
                                                    <option value="voter">Voter ID</option>
                                                    <option value="passport">Passport</option>
                                                    <option value="driving">Driving Licence</option>
                                                </select>
                                                <div class="validation-error" v-if="errorsEdit.id_type">
                                                        {{errorsEdit.id_type[0]}}
                                                    </div>
                                            </div>
                                        </div>



                                        <div class="col-md-4 col-sm-12">
                                            <div class="form-group">
                                                <label>ID Number</label>
                                                <input type="text" v-model="formEdit.id_no" class="form-control"
                                                    placeholder="ID number *" required>
                                                        <div class="validation-error" v-if="errorsEdit.no">
                                                        {{errorsEdit.id_no[0]}}
                                                    </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-sm-12">
                                            <div class="form-group">
                                                <label>Role</label>
                                                <select  v-model="formEdit.role" class="form-control" required>
                                                    <option value="">Role</option>

                                                    <option v-for="role in roles" :key="role.id" :value="role.id">{{role.name}}</option>

                                                </select>
                                                        <div class="validation-error" v-if="errorsEdit.role">
                                                        {{errorsEdit.role[0]}}
                                                    </div>
                                            </div>
                                        </div>
                                    <div class="col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label>Region</label>
                                                    <select class="form-control show-tick" v-model="regionEdit">
                                                        <option value="">Select Region</option>
                                                        <option v-for="reg in regions" :key="reg.id" :value="reg">{{reg.name}}</option>
                                                    </select>
                                                        <div class="validation-error" v-if="errorsEdit.region">
                                                            {{errorsEdit.region[0]}}
                                                        </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-12" v-if="region != ''">
                                                <div class="form-group" >
                                                    <label>District</label>
                                                    <select class="form-control show-tick" v-model="district">
                                                        <option value="">Select District</option>
                                                        <option  v-for="dist in region.districts" :key="dist.id" :value="dist">{{dist.name}}</option>
                                                    </select>
                                                        <div class="validation-error" v-if="errors.district">
                                                            {{errors.district[0]}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-12"  v-if="district != ''">
                                                <div class="form-group">
                                                    <label>Ward</label>
                                                    <select class="form-control show-tick" v-model="form.ward">
                                                        <option value="">Select Ward</option>
                                                        <option  v-for="ward in district.wards" :key="ward.id" :value="ward.name">{{ward.name}}</option>
                                                    </select>
                                                        <div class="validation-error" v-if="errors.ward">
                                                            {{errors.ward[0]}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-12">
                                                <div class="form-group">
                                                    <label>Street</label>
                                                    <input type="text"  v-model="formEdit.street" class="form-control" placeholder="Street *" required />
                                                        <div class="validation-error" v-if="errorsEdit.street">
                                                            {{errorsEdit.street[0]}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                                <div class="row clearfix" style="margin-top:20px">

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
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css';
export default {

    name: 'WorkspaceJsonBank',

    data() {
        return {search:"",search:"",
            staffs:[],
            load:false,
            mobile:false,
            search:"",
            int:1,
            active:'all',
            form:{
                name:"",
                status:"",
                role:"",
                region:"",
                district:"",
                ward:"",
                street:"",
                id_type:"",
                card:"",

            },
            display:false,
            filter:{
             name:"",
             phone:"",
             email:"",
             status:""
            },
              formEdit:{
                name:"",
                status:"",
                role:"",
                region:"",
                district:"",
                ward:"",
                street:"",
                id_type:"",
                _method: 'patch',

            },
             errors:[],
             errorsEdit:[],
             roles:[],
            load:false,
            loading:false,
            dropzoneOptions: {
                url: '../../api/uploadCard',
                thumbnailWidth: 200,
                addRemoveLinks: true,
                dictDefaultMessage: "<i class='fa fa-cloud-upload upload-icon'></i></i>Drag and drop a file here or click"
            },
           region:"",
           regionEdit:"",
            regions:"",
            district:"",
            headers: [
                    {
                        value: 'index',
                        text: '#',
                         sortable: false,
                    },
                      {
                        text: 'Name',
                        value: 'user.name',
                    },
                        {
                        text: 'Email',
                        value: 'user.email',
                    },
                        {
                        text: 'Phone',
                        value: 'user.phone',
                    },
                        {
                        text: 'Role',
                        value: 'role.name',
                    },
                        {
                        text: 'Id type',
                        value: 'id_type',
                    },
                       {
                        text: 'Id no',
                        value: 'id_no',
                    },

                    { text: 'Created At', value: 'join' },
                     { text: 'Status', value: "stat" },

                    { text: "Action", value: "controls", sortable: false }
                ],
        };
    },

    async beforeMount(){
        this.user = JSON.parse(localStorage.getItem('user'));
        const response = await axios.get('api/staffs');

        const role = await axios.get('api/roles');
        this.roles = role.data;

        const regions = await axios.get('../api/regions');

        this.regions = regions.data;
        this.staffs = response.data;
    },
    components:{

        vueDropzone: vue2Dropzone,

    },

    methods: {
        async filterData(){
            this.loading=true;
            const response = await axios.post('api/staff-filter',this.filter);

        this.staffs = response.data;
        this.loading=false;


    },
       menuclick (value) {
                    this.mobile = value
                },
         uploadSuccess(file, response) {
             this.form.card = response;

        },
            async submit(){
            this.loading=true;
            this.form.region = this.region.name.toLowerCase();
            this.form.district = this.district.name.toLowerCase();
            this.form.name = this.form.name.toLowerCase();
            try{
                const response = await axios.post('../api/staffs',this.form);
                if(response.status == 200){
                    this.loading=false;

                          this.$toast.open({
                            message: 'Staff successful added',
                            type: 'success',
                            duration: 5000,
                            position: 'top-right'
                            // all of other options may go here
                        });
             const res = await axios.get('api/staffs');
        this.staffs = res.data;
        this.active='all';
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
             const res = await axios.get('api/staffs');
             this.staffs = res.data;
          }

        }
        },

            async showEdit(uid){
            this.load = true;
            this.active ="edit";

            const result = this.staffs.find( ({ id }) => id === uid );
            const region = this.regions.find(({name})=>name.toLowerCase === result.region.toLowerCase);
            console.log(result.region);
            this.regionEdit = region;
            this.formEdit.name = result.user.name;
            this.formEdit.status = result.status;
            this.formEdit.phone = result.user.phone;
            this.formEdit.email = result.user.email;
            this.formEdit.id = uid;
            this.formEdit.role= result.role.id;
            this.formEdit.street=result.street;
            this.formEdit.ward = result.ward;
            this.formEdit.id_type = result.id_type;
            this.formEdit.id_no = result.id_no;
            this.formEdit.district = result.district;
            this.formEdit.region = result.region;
            this.formEdit.id = result.id;



            this.load = false;
        },
          async submitEdit(){
            this.loading=true;


            try{
                const response = await axios.post('../api/staffs/'+this.formEdit.id,this.formEdit);
                if(response.status == 200){



                          this.$toast.open({
                            message: ' successful edited',
                            type: 'success',
                            duration: 5000,
                            position: 'top-right'
                            // all of other options may go here
                        });
             const res = await axios.get('api/staffs');
             this.staffs = res.data;
               const result = this.staffs.find( ({ id }) => id === this.formEdit.id );
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
            this.loading=false;

        },
      async removeItems(id,index){
      var result = await this.$swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this staff",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes,'
        });
        if (result.isConfirmed) {
            try{
          var response = await axios.delete('../../api/staffs/'+id);
          if(response.status == 200){

                this.$swal(
                'Deleted!',
                response.data,
                'success'
            )
             const res = await axios.get('api/staffs');
             this.staffs = res.data;
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

<style>

</style>
