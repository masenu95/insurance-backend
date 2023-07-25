<template>
    <v-app>
        <div id="main_content" :class="mobile?'offcanvas-active':''">
    <!-- Start Main top header -->
    <admin-header v-on:childToParent="menuclick"></admin-header>
    <!-- Start Rightbar setting panel -->

    <!-- Start Main leftbar navigation -->
       <sidebar-left link="invoice"></sidebar-left>
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
                        <h1 class="page-title">Pre determine Invoice</h1>
                        <ol class="breadcrumb page-breadcrumb">
                            <li class="breadcrumb-item"><router-link to="admin-home">BimaKwik</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">Pre determine Invoice</li>
                        </ol>
                    </div>
                    <ul class="nav nav-tabs page-header-tab">
                        <li class="nav-item"><router-link to="/borrower-preMarket" class="nav-link">List View</router-link></li>
                        <li class="nav-item"><a class="nav-link active">Add</a></li>

                    </ul>
                </div>
            </div>
        </div>
        <div class="section-body mt-4" style="background:#fff">
            <div class="container-fluid">
                <div class="tab-content">
                    <div class="tab-pane active">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Device invoice Information</h3>

                                    </div>
                                    <div class="card-body">
                                        <form @submit.prevent="submit">
                                                <div class="row clearfix">


                                                 <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Return Rate</label>
                                                        <input type="text" class="form-control" v-model="form.interest" required>
                                                         <div class="validation-error" v-if="errors.interest">
                                                                {{errors.interest[0]}}
                                                        </div>
                                                    </div>
                                                </div>
                                                  <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Maturity Date</label>
                                                        <input type="date" class="form-control" v-model="form.maturity_date" required>
                                                         <div class="validation-error" v-if="errors.maturity_date">
                                                                {{errors.maturity_date[0]}}
                                                        </div>
                                                    </div>
                                                </div>



                                                 <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Amount</label>
                                                        <input type="text" class="form-control" v-model="form.amount"  placeholder="amount" readonly>
                                                        <small style="color:red"> {{form.amount|formatNumber}}</small>
                                                         <div class="validation-error" v-if="errors.amount">
                                                                {{errors.amount[0]}}
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
 import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css';
export default {

    name: 'WorkspaceJsonBank',

    data() {
        return {search:"",search:"",
            load:false,
            loading:false,
            mobile:false,
            limit:0,
            dataBorrower:[],
            search:"",
            int:1,
            form:{

                maturity_date:"",
                amount:"",
                interest:"",
            },


            companies:[],
            errors:[],
        };
    },

    async beforeMount(){
                this.user = JSON.parse(localStorage.getItem('user'));
        const response = await axios.get('api/request-amount');


        this.form.amount = response.data;



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

                const response = await axios.post('../api/device-invoice',this.form);
                if(response.status == 200){

                          this.$toast.open({
                            message: ' successful added',
                            type: 'success',
                            duration: 5000,
                            position: 'top-right'
                            // all of other options may go here
                        });
                    this.$router.push('../../admin-device-invoices');

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



       payingChange(){
           console.log('masenu');
            this.form.company = this.company.id;
            /*  var score = this.company.score * this.dataBorrower.score;
            if(score >=12||score<=16 ){
                this.form.interest = 4
            }else if(score >=6||score<=9 ){
                this.form.interest = 5
            }else if(score >=2||score<=4 ){
                this.form.interest = 6
            }else{
                this.form.interest = 8
            }*/
        },
        dateChange(){

            const date1 = new Date(this.form.maturity_date);
            const date2 = Date.now();
            const diffTime = Math.abs(date2 - date1);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            var score = this.company.score * this.dataBorrower.score;
           /* if(score >=12||score<=16 ){
                this.form.interest = 4
            }else if(score >=6||score<=9 ){
                this.form.interest = 5
            }else if(score >=2||score<=4 ){
                this.form.interest = 6
            }else{
                this.form.interest = 8
            }*/
               if(diffDays <= 30 ){
                this.form.percent =90;
                this.form.interest = 4;
                this.form.trading_fee = 3.5;
            }else if(diffDays > 30 && diffDays <= 60){
                this.form.percent = 85;
                this.form.interest = 6.5;
                this.form.trading_fee = 6;
            }else if(diffDays > 60 && diffDays <= 90){
                this.form.interest = 9;
                this.form.percent = 80;
                this.form.trading_fee = 8.5;
            }
        },
    },
   components:{

    vueDropzone: vue2Dropzone,

},
watch: {
    $route: {
        immediate: true,
            handler(to, from) {
                document.title = to.meta.title || 'Some Default Title';
            }
        },
    }




};
</script>

<style lang="scss" scoped>

</style>
