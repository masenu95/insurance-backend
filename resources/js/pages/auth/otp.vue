<template>
    <div class="auth option2">
        <div class="auth_left">
            <div class="card">

                <div class="card-body">
                    <form @submit.prevent="submit">
                    <div class="text-center">
                        <a class="header-brand" href="https://BimaKwik.com"><img src="https://portal.BimaKwik.com/images/logo.png" style="height:120px;width:120px"></a>
                        <div class="card-title mt-3">Verify Otp</div>
                       <!-- <button type="button" class="btn btn-facebook"><i class="fa fa-facebook mr-2"></i>Facebook</button>
                        <button type="button" class="btn btn-google"><i class="fa fa-google mr-2"></i>Google</button>
                        <h6 class="mt-3 mb-3">Or</h6>-->
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control"  aria-describedby="emailHelp" placeholder="Otp" v-model="form.otp" required>
                    </div>
                    <br>

                    <div class="form-group" style="margin-top:20px">
                        <button  class="btn btn-primary btn-block" style="width:100%"><div class="spinner-border text-danger" role="status" style="width:15px;height:15px" v-if="loading"></div>Verify</button>

                    </div>
                    </form>
                    <div class="form-group" style="margin-top:20px">
                    <button  @click.prevent="resendOtp" class="btn btn-primary btn-block" style="width:100%;background:#000"><div class="spinner-border text-danger" role="status" style="width:15px;height:15px" v-if="loadingResend"></div>Resend Otp</button>
                </div>
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
                      id:this.$route.params.id,
                      otp:null,
                    },
                        error:null,
                        status:null,
                        rememberMe:null,
                        loading:false,
                        loadingResend:false,
                }
            },
            beforeCreate(){
            const id = this.$route.params.id;
            //console.log(email);
            this.form.id=id;
        },



            methods:{

                async submit(){

                    this.loading=true;
                    try{
                        const response  = await axios.post('../../api/verify-otp',{
                    'id':this.form.id,
                    'otp':this.form.otp,
                });


                      this.$toast.open({
                                message: 'Welcome to BimaKwik panel ' + response.data.user.name,
                                type: 'success',
                                duration: 5000,
                                position: 'top-right'
                                // all of other options may go here
                            });
                            this.loading = false;

                    await localStorage.setItem('user',JSON.stringify(response.data.user))
                    await localStorage.setItem('token',response.data.token)

                    const access_token = localStorage.getItem('token');

                    axios.defaults.headers.common['Authorization'] = `Bearer ${access_token}`;
                        this.loading=false;
                        if(response.data.user.role.toUpperCase() == 'admin'.toUpperCase()){
                                this.$router.push('../../admin-home');
                        }else if(response.data.user.role.toUpperCase() == 'Investor'.toUpperCase()){
                                this.$router.push('../../investor-home');
                        }
                        else if(response.data.user.role.toUpperCase() == 'Staff'.toUpperCase()){
                               /* this.$router.push('../../staff-home');*/
                               this.$toast.open({
                                message: 'Network error try again',
                                type: 'error',
                                duration: 5000,
                                position: 'bottom-right'
                                // all of other options may go here
                            });
                        }else if(response.data.user.role.toUpperCase() == 'Borrower'.toUpperCase()){
                                this.$router.push('../../borrower-home');
                        }

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



                },

                async resendOtp(){
                    this.loadingResend = true;
                    try{
                        const response  = await axios.post('../../api/resend-otp',{
                    'id':this.form.id,

                });
                this.$toast.open({
                                message: 'Otp resend successful',
                                type: 'success',
                                duration: 5000,
                                position: 'top-right'
                                // all of other options may go here
                            });
                            this.loadingResend = false;
                    }catch(e){
                        this.loadingResend = false;
                    }
                    this.loadingResend = false;
                },
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
