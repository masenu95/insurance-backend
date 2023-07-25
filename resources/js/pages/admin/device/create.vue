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
                        <h1 class="page-title">Device</h1>
                        <ol class="breadcrumb page-breadcrumb">
                            <li class="breadcrumb-item"><router-link to="admin-home">BimaKwik</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">Devices</li>
                        </ol>
                    </div>
                    <ul class="nav nav-tabs page-header-tab">
                        <li class="nav-item"><router-link to="../../admin-devices" class="nav-link" >List View</router-link ></li>
                        <li class="nav-item"><router-link to="../../admin-device-create"  class="nav-link active" >Add</router-link ></li>

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
                                        <h3 class="card-title">Asset Device Information</h3>

                                    </div>
                                    <div class="card-body">

                       <form @submit.prevent="submit">
                                <div class="row clearfix">
                                   <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Category</label>
                                            <select class="form-control" v-model="form.category" required>
                                                <option value="">Select category</option>
                                                <option v-for="category in categories" :key="category.id" :value="category.id">{{category.name}}</option>
                                            </select>
                                              <div class="validation-error" v-if="errors.category">
                                                    {{errors.category[0]}}
                                              </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" class="form-control" v-model="form.name"  placeholder="Name *" required>
                                              <div class="validation-error" v-if="errors.name">
                                                    {{errors.name[0]}}
                                              </div>
                                        </div>


                                        <div class="form-group">
                                            <label>Price</label>
                                            <input type="text" class="form-control" v-model="form.price"  placeholder="Price *" @keyup="priceChange" required>
                                            <small>{{form.price|formatNumber}}</small>
                                              <div class="validation-error" v-if="errors.price">
                                                    {{errors.price[0]}}
                                              </div>
                                        </div>


                                    </div>

                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label>Technology price</label>
                                            <input type="text" class="form-control" v-model="form.technology"  placeholder="Technology price*" @keyup="priceChange" required>
                                            <small>{{form.technology|formatNumber}}</small>
                                            <div class="validation-error" v-if="errors.technology">
                                                    {{errors.technology[0]}}
                                              </div>
                                        </div>

                                        </div>

                                        <div class="col-md-4 col-sm-12">

                                        <div class="form-group">
                                            <label>Insurance %</label>
                                            <input type="text" class="form-control" v-model="form.insurance_perc"  placeholder="Insurance percent*" @keyup="priceChange" required>
                                            <small>{{form.insurance|formatNumber}}</small>
                                            <div class="validation-error" v-if="errors.insurance_perc">
                                                    {{errors.insurance_perc[0]}}
                                              </div>
                                        </div>
                                        </div>

                                        <div class="col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label>Interest %</label>
                                            <input type="text" class="form-control" v-model="form.interest_perc"  placeholder="Interest percent*" @keyup="priceChange" required>
                                            <small>{{form.loan|formatNumber}}</small>
                                            <div class="validation-error" v-if="errors.interest_perc">
                                                    {{errors.interest_percn[0]}}
                                              </div>
                                        </div>
                                        </div>

                                        <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label>Total price</label>
                                            <input type="text" class="form-control" v-model="form.total"  placeholder="Loan price*"  readonly>
                                            <small>{{form.total|formatNumber}}</small>
                                            <div class="validation-error" v-if="errors.total">
                                                    {{errors.total[0]}}
                                              </div>
                                        </div>
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

            form:{
                name:"",
                price:0,
                category:"",
                technology:0,
                loan:0,
                insurance:0,
                total:0,
                insurance_perc:0,
                interest_perc:0,

            },
            display:false,

             errors:[],

            load:false,
            loading:false,
            categories:[],

        };
    },

    async beforeMount(){
        this.user = JSON.parse(localStorage.getItem('user'));
        const response = await axios.get('api/categories');
        this.categories = response.data;
    },
    components:{

        vueDropzone: vue2Dropzone,

    },

    methods: {

       menuclick (value) {
                    this.mobile = value
                },

        priceChange(){
            this.form.insurance = parseInt(this.form.price)*(parseInt(this.form.insurance_perc)/100);
            const tot = parseInt(this.form.price) + this.form.insurance + parseInt(this.form.technology);
            this.form.loan = tot*(parseInt(this.form.interest_perc)/100);


            this.form.total = parseInt(this.form.price) + parseInt(this.form.insurance) + parseInt(this.form.loan) + parseInt(this.form.technology);
        },

            async submit(){
            this.loading=true;


            this.form.name = this.form.name.toLowerCase();
            try{
                const response = await axios.post('../api/devices',this.form);
                if(response.status == 200){
                    this.loading=false;

                          this.$toast.open({
                            message: 'Device successful added',
                            type: 'success',
                            duration: 5000,
                            position: 'top-right'
                            // all of other options may go here
                        });
                        this.$router.push('../../admin-devices')
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

<style>

</style>
