<template>
    <v-app>
        <div id="main_content" :class="mobile?'offcanvas-active':''">
    <!-- Start Main top header -->

    <!-- Start Rightbar setting panel -->

    <!-- Start Main leftbar navigation -->

    <!-- Start project content area -->
    <!-- Start project content area -->
    <div class="container">
        <!-- Start Page header -->
        <top-nav></top-nav>
        <!-- Start Page title and tab -->
        <div class="section-body" >
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center ">
                    <div class="header-action">
                        <h1 class="page-title">Asset financing payment</h1>
                        <ol class="breadcrumb page-breadcrumb">
                            <li class="breadcrumb-item"><router-link to="admin-home">BimaKwik</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">Asset financing payment</li>
                        </ol>
                    </div>
                    <ul class="nav nav-tabs page-header-tab">
                        <li class="nav-item"><a class="nav-link active"  >Payment </a></li>
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
                                        <h3 class="card-title">Payment</h3>

                                    </div>
                                    <div class="card-body">
                                         <form @submit.prevent="submitpay">
                                            <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Reference</label>
                                                        <input type="text" class="form-control" v-model="formpay.reference" readonly>
                                                         <div class="validation-error" v-if="errorspay.reference">
                                                                {{errorspay.reference[0]}}
                                                        </div>
                                                    </div>
                                                </div>
                                            <div class="row clearfix">
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Total Loan</label>
                                                        <input type="text" class="form-control" v-model="formpay.credit" readonly>
                                                         <div class="validation-error" v-if="errorspay.credit">
                                                                {{errorspay.credit[0]}}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Name</label>
                                                        <input type="text" class="form-control" v-model="formpay.name" readonly>
                                                         <div class="validation-error" v-if="errorspay.name">
                                                                {{errorspay.name[0]}}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Phone</label>
                                                        <input type="text" class="form-control" v-model="formpay.phone" readonly>
                                                         <div class="validation-error" v-if="errorspay.phone">
                                                                {{errorspay.phone[0]}}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Group</label>
                                                        <input type="text" class="form-control" v-model="formpay.group" readonly>
                                                         <div class="validation-error" v-if="errorspay.group">
                                                                {{errorspay.group[0]}}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Remain</label>
                                                        <input type="text" class="form-control" v-model="formpay.remain" readonly>
                                                         <div class="validation-error" v-if="errorspay.remain">
                                                                {{errorspay.remain[0]}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Payment structure</label>
                                                        <select v-model="formpay.structure" class="form-control"  readonly>
                                                            <option value="">Select payment structure</option>
                                                            <option value="180">Daily</option>
                                                            <option value="26">Weekly</option>
                                                            <option value="6">Monthly</option>
                                                        </select>
                                                            <div class="validation-error" v-if="errorspay.structure">
                                                                {{errorspay.structure[0]}}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <label>Amount</label>
                                                        <input type="text" class="form-control" v-model="formpay.amount" readonly>
                                                         <div class="validation-error" v-if="errorspay.amount">
                                                                {{errorspay.amount[0]}}
                                                        </div>
                                                    </div>
                                                </div>




                                               <!-- <div class="col-md-12 col-sm-12">
                                                    <div class="form-group">

                                                        <div class="form-check">
                                                            <input class="form-check-input" name="method" type="radio" value="mobile" v-model="formpay.method">Mobile Money
                                                        </div>




                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12" v-if="formpay.method=='mobile'">

                                                    <div class="form-group">
                                                        <label>mobile(format(eg):0789xxxxxx)</label>
                                                        <input type="text" class="form-control" v-model="formpay.mobile" required>
                                                         <div class="validation-error" v-if="errorspay.mobile">
                                                                {{errorspay.mobile[0]}}
                                                        </div>
                                                    </div>
                                                       <div class="mobile-logo">
                                                    <h6>Accept:</h6>

                                                        <img src="images/airtel_money.png" alt="">
                                                        <img src="images/tigo_pesa.png" alt="">
                                                        <img src="images/ezy_pesa.png" alt="">
                                                        <img src="images/m_pesa.png" alt="">
                                                        <img src="images/halo_pesa.png" alt="">

                                                    </div>
                                                </div>-->





                                                <div class="col-sm-12" >

                                                     <button class="awesome-checkout-button" @click.prevent="checkout"></button>
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

    data() {
        return {search:"",search:"",
            load:false,
            invoice:[],
            loading:false,
            bidsCredit:0,
            mobile:false,
            availabeCredit:0,
            int:1,
            id:"",

            formpay:{
                id:"",
                credit:"",
                amount:1000,
                group:"",
                remain:0,
                reference:"",
                structure:"",
               name:"",
               service:"asset",
            },

            display:false,

            errorspay:[],

        }
    },


    async beforeMount(){
        const id = this.$route.params.id;
        this.id = id;
        const res =await axios.get('../../api/customer-data/'+id);
        console.log(res);
        this.formpay.name = res.data.name;
        this.formpay.phone = res.data.phone;
        this.formpay.remain = res.data.remain;
        this.formpay.credit = res.data.total;
        this.formpay.group = res.data.group;
        this.formpay.reference = res.data.reference;
        this.formpay.id = res.data.id;
        this.formpay.amount = res.data.amount;
        this.formpay.structure = res.data.structure;


                const script = document.createElement("script");
        script.src = "https://online.tingg.africa/v2/tingg-checkout.js";

            script.addEventListener("load", this.setLoaded);
    document.body.appendChild(script);





    },


      methods: {
        onlineClick(){

        },
        setLoaded: function() {
      window.Tingg.renderPayButton({
                className: 'awesome-checkout-button',
                text: 'Online Payment',
                color: 'blue'
            });
    },
    async checkout(){
      /*  var date = new Date();
        var min = parseInt(date.getMinutes())+10;
        var hr = date.getHours()
        if(min>59){
            min = min-60;
            hr = hr+1;
        }

        var datetime = date.getFullYear()+'-'+date.getMonth()+'-'+date.getDate() +' '+hr+':'+min+':'+date.getSeconds();
        this.payload.dueDate = datetime;*/
        const checkoutType = 'redirect';
        const resp = await axios.post("../../api/asset-encrypt",this.formpay);
        console.log(resp.data);
        window.Tingg.renderCheckout({
                       checkoutType,
                    merchantProperties:{params:resp.data, accessKey: 'JlJaKWNYTHowlmEbxdsNASCsRKuOjrszVjdDHifmNoXVbfARkC', countryCode: 'TZ'}
                  });

    },
       menuclick (value) {
                    this.mobile = value
                },







       async submitpay(){
            this.loading=true;

            try{
                const response = await axios.post('../api/pay-bid',this.formpay);
                 this.loading=false;
                if(response.status == 200){
                         this.loading=false;
                          this.$toast.open({
                            message: ' successful',
                            type: 'success',
                            duration: 5000,
                            position: 'top-right'
                            // summary of other options may go here
                        });

                        this.$router.push('../transactions');



                }
            }catch(e){

                 this.loading=false;

                if(e.response){
                    if(e.response.status = 422){
                         this.loading=false;
                        this.errors = e.response.data.errors;

                         this.$toast.open({
                            message: e.response.data.message,
                            type: 'error',
                            duration: 5000,
                            position: 'bottom-right'
                            // summary of other options may go here
                        });


                    }else{
                         this.loading=false;
                        this.$toast.open({
                            message: e.response.data.message,
                            type: 'error',
                            duration: 5000,
                            position: 'bottom-right'
                            // summary of other options may go here
                        });
                    }
                } else{
                     this.loading=false;
                    this.$toast.open({
                            message: 'Internal server error',
                            type: 'error',
                            duration: 5000,
                            position: 'bottom-right'
                            // summary of other options may go here
                        });
                }
                 this.loading=false;
            }
             this.loading=false;

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

<style scoped>
.awesome-checkout-button{
padding:0 !important;
}
</style>
