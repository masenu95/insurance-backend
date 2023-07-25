<template>
    <div class="auth option2">
        <div class="auth_left">
            <div class="card">

                <div class="card-body">
                    <form @submit.prevent="submit">
                    <div class="text-center">
                        <a class="header-brand" href="https://BimaKwik.com"><img src="images/logo.png" style="height:120px;width:120px"></a>
                        <div class="card-title mt-3">Enter reference number to procceed</div>
                       <!-- <button type="button" class="btn btn-facebook"><i class="fa fa-facebook mr-2"></i>Facebook</button>
                        <button type="button" class="btn btn-google"><i class="fa fa-google mr-2"></i>Google</button>
                        <h6 class="mt-3 mb-3">Or</h6>-->
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control"  aria-describedby="referenceHelp" placeholder="Enter reference number" v-model="form.reference" required>
                    </div>
                    <br><br>


                    <div class="text-center">
                        <button  class="btn btn-primary btn-block" title=""><div class="spinner-border text-danger" role="status" style="width:15px;height:15px" v-if="loading"></div>&nbsp;&nbsp;&nbsp;Continue</button>

                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    </template>



    <script>

        export default {

            mounted() {

                console.log('Component mounted.')

            },
            data(){
                return{
                       form:{
                           reference:null,

                        },
                        error:null,
                        status:null,

                        loading:false,
                }
            },

            methods:{
                async submit(){
                    this.loading=true;
                    try{
                        const response  = await axios.post('api/reference',{
                        'reference':this.form.reference,

                    });

                     /* this.$toast.open({
                                message: 'Welcome to BimaKwik panel ' + response.data.user.name,
                                type: 'success',
                                duration: 5000,
                                position: 'top-right'
                                // all of other options may go here
                            });
                            this.loading = false;*/

                            this.$toast.open({
                                message: 'make payment ' + response.data.name,
                                type: 'success',
                                duration: 5000,
                                position: 'top-right'
                                // all of other options may go here
                            });
                            this.loading = false;



                            this.$router.push('../../asset-payment/'+response.data.id);



                  /*  await localStorage.setItem('user',JSON.stringify(response.data.user))
                    await localStorage.setItem('token',response.data.token)

                    const access_token = localStorage.getItem('token');

                    axios.defaults.headers.common['Authorization'] = `Bearer ${access_token}`;
                        this.loading=false;
                        if(response.data.user.role.toUpperCase() == 'admin'.toUpperCase()){
                                this.$router.push('admin-home');
                        }else if(response.data.user.role.toUpperCase() == 'Investor'.toUpperCase()){
                                this.$router.push('investor-home');
                        }
                        else if(response.data.user.role.toUpperCase() == 'Staff'.toUpperCase()){
                                this.$router.push('staff-home');
                        }else if(response.data.user.role.toUpperCase() == 'Borrower'.toUpperCase()){
                                this.$router.push('borrower-home');
                        }*/

                    } catch(e){

                        if(e.response.status == 409){
                              this.$router.push('../disable');
                        }
                            this.$toast.open({
                                message: e.response.data.error,
                                type: 'error',
                                duration: 5000,
                                position: 'bottom-right'
                                // all of other options may go here
                            });
                            this.loading = false;
                    }



                }
            },
            watch: {
            $route: {
                immediate: true,
                handler(to, from) {
                    document.title = to.meta.title || 'Some Default Title';
                }
            }
            },

        }

    </script>
    <style scoped>
    .avatar-auth-box{
        float: none;
        display: table;
        margin: 20px auto;
    }
    .auth-avatar{
        height: 90px;
        width: 90px;
        margin-right: auto;
        margin-left: auto;
        border-radius: 100%;
    }
    .form-auth-small .form-control{
        border-radius: 6px;
    }

    .form-auth-small button{
        border-radius: 6px;
        background: #70052b;
    }
    </style>
