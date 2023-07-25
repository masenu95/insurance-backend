<template>
<div class="auth option2">
    <div class="auth_left">
        <div class="card">

            <div class="card-body">
                <form @submit.prevent="submit">
                <div class="text-center">
                    <a class="header-brand" href="https://BimaKwik.com"><img src="images/logo.png" style="height:120px;width:120px"></a>
                    <div class="card-title mt-3">Login to your account</div>
                   <!-- <button type="button" class="btn btn-facebook"><i class="fa fa-facebook mr-2"></i>Facebook</button>
                    <button type="button" class="btn btn-google"><i class="fa fa-google mr-2"></i>Google</button>
                    <h6 class="mt-3 mb-3">Or</h6>-->
                </div>
                <div class="form-group">
                    <input type="text" class="form-control"  aria-describedby="emailHelp" placeholder="Enter email" v-model="form.username" required>
                </div>
                <div class="form-group">
                    <label class="form-label"><router-link to="/password-reset" class="float-right small"> I forgot password</router-link> </label>
                    <input type="password" class="form-control" v-model="form.password" placeholder="Password">
                </div>
               <!-- <div class="form-group">
                    <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" />
                    <span class="custom-control-label">Remember me</span>
                    </label>
                </div>-->
                <div class="text-center">
                    <button  class="btn btn-primary btn-block" title=""><div class="spinner-border text-danger" role="status" style="width:15px;height:15px" v-if="loading"></div>&nbsp;&nbsp;&nbsp;Sign in</button>
                    <div class="text-muted mt-4">Don't have account yet? <br><router-link to="/term-borrower" style="color:#e11e2e">Sign up as borrower</router-link>
                   <br><router-link to="/term-investor" >Sign up as investor</router-link><br>
                   <router-link to="/asset-payment" >Pay Asset</router-link>
                    </div>
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
                       username:null,
                       password:null,
                    },
                    error:null,
                    status:null,
                    rememberMe:null,
                    loading:false,
            }
        },

        methods:{
            async submit(){
                this.loading=true;
                try{
                    const response  = await axios.post('api/user-login',{
                    'username':this.form.username,
                    'password':this.form.password,
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
                            message: 'Verify otp to proceed ' + response.data.user.name,
                            type: 'success',
                            duration: 5000,
                            position: 'top-right'
                            // all of other options may go here
                        });
                        this.loading = false;

                await localStorage.setItem('user',JSON.stringify(response.data.user))
                //await localStorage.setItem('token',response.data.token)

               // const access_token = localStorage.getItem('token');

                //axios.defaults.headers.common['Authorization'] = `Bearer ${access_token}`;
                    this.loading=false;

                        this.$router.push('../../otp/'+response.data.user.id);



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
