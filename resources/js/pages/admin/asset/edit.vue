<template>
    <v-app>
        <div id="main_content" :class="mobile?'offcanvas-active':''">
    <!-- Start Main top header -->
<admin-header v-on:childToParent="menuclick"></admin-header>
    <!-- Start Rightbar setting panel -->

    <!-- Start Main leftbar navigation -->
       <sidebar-left link="Customer"></sidebar-left>
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
                        <h1 class="page-title">Asset Customers</h1>
                        <ol class="breadcrumb page-breadcrumb">
                            <li class="breadcrumb-item"><router-link to="admin-home">BimaKwik</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">Customers</li>
                        </ol>
                    </div>
                    <ul class="nav nav-tabs page-header-tab">
                        <li class="nav-item"><router-link to="../../admin-customers" class="nav-link" >List View</router-link ></li>
                        <li class="nav-item"><router-link to="../../admin-customer-create"  class="nav-link active" >Add</router-link ></li>

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
                                        <h3 class="card-title">Asset Customer Information</h3>

                                    </div>
                                    <div class="card-body">

                       <form @submit.prevent="submit">
                                <div class="row clearfix">
                                    <div class="col-md-6 col-sm-12">

                                    <div class="form-group">
                                            <label>Category</label>
                                            <select class="form-control" v-model="form.category" required>
                                                <option value="">Select category</option>
                                                <option v-for="category in categories" :key="category.id" :value="category">{{category.name}}</option>
                                            </select>
                                              <div class="validation-error" v-if="errors.category">
                                                    {{errors.category[0]}}
                                              </div>
                                        </div>

                                   </div>

                                   <div class="col-md-6 col-sm-12">

                                        <div class="form-group">
                                            <label>Device</label>
                                            <select class="form-control" v-model="device" required>
                                                <option value="">Select device</option>
                                                <option v-for="device in form.category.devices" :key="device.id" :value="device">{{device.name}}</option>
                                            </select>
                                              <div class="validation-error" v-if="errors.device">
                                                    {{errors.device[0]}}
                                              </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-sm-12">


                                        <div class="form-group">
                                            <label>Device price</label>
                                            <input type="text" class="form-control" v-model="device.price"  placeholder="Device price*" readonly>
                                              <div class="validation-error" v-if="errors.price">
                                                    {{errors.price[0]}}
                                              </div>
                                        </div>

                                        </div>

                                        <div class="col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label>Technology price</label>
                                            <input type="text" class="form-control" v-model="device.technology"  placeholder="Technology price*"  readonly>
                                              <div class="validation-error" v-if="errors.technology">
                                                    {{errors.technology[0]}}
                                              </div>
                                        </div>

                                        </div>

                                        <div class="col-md-4 col-sm-12">

                                        <div class="form-group">
                                            <label>Insurance price</label>
                                            <input type="text" class="form-control" v-model="device.insurance"  placeholder="Insurance price*" readonly>

                                        </div>
                                        </div>

                                        <div class="col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label>Loan price</label>
                                            <input type="text" class="form-control" v-model="device.interest"  placeholder="Loan price*" readonly>
                                              <div class="validation-error" v-if="errors.loan">
                                                    {{errors.loan[0]}}
                                              </div>
                                        </div>
                                        </div>





                                        <div class="col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label>Total price</label>
                                            <input type="text" class="form-control" v-model="device.total"  placeholder="Total price*" readonly>
                                              <div class="validation-error" v-if="errors.total">
                                                    {{errors.total[0]}}
                                              </div>
                                        </div>
                                        </div>

                                        <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Initial price</label>
                                            <input type="text"  v-model="form.initial" class="form-control" placeholder="Paid *" @change="initialChange" required>
                                                   <div class="validation-error" v-if="errors.initial">
                                                    {{errors.initial[0]}}
                                              </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Remain Debt</label>
                                            <input type="text"  v-model="form.balance" class="form-control" placeholder="Paid *" required>
                                                   <div class="validation-error" v-if="errors.balance">
                                                    {{errors.balance[0]}}
                                              </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Payment structure</label>
                                            <select v-model="form.structure" class="form-control" @change="structureChange"  required>
                                                <option value="">Select payment structure</option>
                                                <option value="180">Daily</option>
                                                <option value="26">Weekly</option>
                                                <option value="6">Monthly</option>
                                            </select>
                                                   <div class="validation-error" v-if="errors.structure">
                                                    {{errors.structure[0]}}
                                              </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Payment Amount</label>
                                            <input type="text"  v-model="form.amount" class="form-control" placeholder="Paid *" required>
                                                   <div class="validation-error" v-if="errors.amount">
                                                    {{errors.amount[0]}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">

                                        <div class="form-group">
                                            <label>Name</label>
                                            <input type="text" class="form-control" v-model="form.name"  placeholder="Name *" required>
                                              <div class="validation-error" v-if="errors.name">
                                                    {{errors.name[0]}}
                                              </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Group</label>
                                            <input type="text" class="form-control" v-model="form.group"  placeholder="Group *" required>
                                              <div class="validation-error" v-if="errors.group">
                                                    {{errors.group[0]}}
                                              </div>
                                        </div>
                                    </div>
                                       <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Phone(eg:255622958539)</label>
                                            <input type="text" class="form-control" v-model="form.phone"  placeholder="Phone *" required>
                                              <div class="validation-error" v-if="errors.phone">
                                                    {{errors.phone[0]}}
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
            customers:[],
            load:false,
            mobile:false,
            search:"",

            form:{
                name:"",
                status:"",
                device:"",
                role:"",
                region:"",
                district:"",
                ward:"",
                street:"",
                id_type:"",
                card:"",
                category:"",
                technology:0,
                loan:0,
                insurance:0,
                initial:0,
                daily:0,
                amount:0,
                structure:"",
                balance:0,
                _method:'patch',

            },
            display:false,
            device:"",
             errors:[],

            load:false,
            loading:false,
            categories:[],

        };
    },

    async beforeMount(){
        this.user = JSON.parse(localStorage.getItem('user'));
        const id = this.$route.params.id;
    this.id = id;




        const categories = await axios.get('../api/categories');
        this.categories = categories.data;
        const customer =  await axios.get('../api/customers/'+id);
        this.form.daily = customer.data.daily;
        this.form.technology=customer.data.technology;
        this.form.total =customer.data.total;
        this.form.initial=customer.data.initial;
        this.form.loan=customer.data.loan;
        this.form.insurance=customer.data.insurance;
        this.form.name = customer.data.name;
        this.form.group = customer.data.group;
        this.form.phone = customer.data.phone;

        this.form.initial=customer.data.payed;
        this.form.device = customer.data.dname_id;
        this.form.structure = customer.data.structure;
        this.form.amount=customer.data.amount;
        this.form.category = customer.data.device.category;
        this.device =customer.data.device;
    },
    components:{

        vueDropzone: vue2Dropzone,

    },

    methods: {

       menuclick (value) {
                    this.mobile = value
                },

            async submit(){
            this.loading=true;
            this.form.group = this.form.group.toLowerCase();

            this.form.name = this.form.name.toLowerCase();
            this.form.price = this.device.price;
            this.form.device = this.device.id;
            this.form.insurance = this.device.insurance;
            this.form.loan =this.device.interest;
            this.form.technology = this.device.technology;
            this.form.total = this.device.total;
            try{
                const response = await axios.post('../api/customers/'+this.id,this.form);
                if(response.status == 200){
                    this.loading=false;

                          this.$toast.open({
                            message: 'Customer successful added',
                            type: 'success',
                            duration: 5000,
                            position: 'top-right'
                            // all of other options may go here
                        });
                        this.$router.push('../../admin-customers')
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

        structureChange(){
            this.form.amount = parseInt(this.form.balance/this.form.structure);

        },

        initialChange(){
            this.form.balance=parseInt(this.device.total)-parseInt(this.form.initial);
        }



    },
};
</script>

<style>

</style>
