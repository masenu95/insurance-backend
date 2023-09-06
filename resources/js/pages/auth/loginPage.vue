<template>
<v-app >
    <v-dialog v-model="loading" :scrim="false" persistent width="auto">
            <v-card color="primary">
                <v-card-text style="color:#fff">
                    <div style="padding-bottom: 5px;">Please wait</div>
                    <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>


    <div class="h-100">
        <div class="container-auth card">

        <div class="detalis-main" >
            <form @submit.prevent="submit">
                <div class="container" style="width: 90%;">
                <div class="login-main">
                <img class="logo" src="/images/logo.png" alt="SmartSave">
            </div>
            <!--<div class="switch-details">
                <div class="singin btn-switch">Sing in</div>
                <div class="singup btn-switch">Singup</div>
            </div>-->
            <div class="form-detalis">

                <div class="input-main">
                    <svg class="icon-email" xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24" width="20" height="20">
                        <path
                            d="M19,1H5A5.006,5.006,0,0,0,0,6V18a5.006,5.006,0,0,0,5,5H19a5.006,5.006,0,0,0,5-5V6A5.006,5.006,0,0,0,19,1ZM5,3H19a3,3,0,0,1,2.78,1.887l-7.658,7.659a3.007,3.007,0,0,1-4.244,0L2.22,4.887A3,3,0,0,1,5,3ZM19,21H5a3,3,0,0,1-3-3V7.5L8.464,13.96a5.007,5.007,0,0,0,7.072,0L22,7.5V18A3,3,0,0,1,19,21Z" />
                    </svg>
                    <div class="be-main"></div>
                    <div class="input-box">
                        <input type="email" class="input" required placeholder="Email" v-model="form.username">
                        <!--<label for="#">Email Address</label>-->
                    </div>
                   <!-- <svg :class="'icon-continue'" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path
                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                    </svg>-->
                </div>
                <div class="input-main">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path
                        d="M17 9.761v-4.761c0-2.761-2.238-5-5-5-2.763 0-5 2.239-5 5v4.761c-1.827 1.466-3 3.714-3 6.239 0 4.418 3.582 8 8 8s8-3.582 8-8c0-2.525-1.173-4.773-3-6.239zm-8-4.761c0-1.654 1.346-3 3-3s3 1.346 3 3v3.587c-.927-.376-1.938-.587-3-.587s-2.073.211-3 .587v-3.587zm3 17c-3.309 0-6-2.691-6-6s2.691-6 6-6 6 2.691 6 6-2.691 6-6 6zm2-6c0 1.104-.896 2-2 2s-2-.896-2-2 .896-2 2-2 2 .896 2 2z"
                        />
                    </svg>
                    <div class="be-main"></div>
                    <div class="input-box">
                        <input type="password" class="input" placeholder="Password"  required v-model="form.password">
                       <!-- <label for="#">Password</label>-->
                    </div>
                    <!--<svg :class="'icon-continue'" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path
                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                    </svg>-->
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center" style="margin-top: 30px;">
                <router-link to="/password-reset" style="text-transform: none !important;color: #000;">Forget password?</router-link>
                <button type="submit" class="btn  btn-lg btn-multiple-state btn-shadow">
                    <span class="spinner d-inline-block">
                        <span class="bounce1"></span>
                            <span class="bounce2"></span> <span class="bounce3"></span></span>
                            <span class="icon success"><i class="simple-icon-check"></i></span>
                            <span class="icon fail"><i class="simple-icon-exclamation"></i></span>
                                <span class="labeled">LOGIN</span>
                </button>
            </div>
            </div>
            </form>


           <!-- <div class="title-social">Or Sign up as</div>
            <div class="with-social">
                <div class="social google">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-google" viewBox="0 0 16 16">
                        <path
                            d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z" />
                    </svg>
                </div>
                <div class="social apple">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-apple" viewBox="0 0 16 16">
                        <path
                            d="M11.182.008C11.148-.03 9.923.023 8.857 1.18c-1.066 1.156-.902 2.482-.878 2.516.024.034 1.52.087 2.475-1.258.955-1.345.762-2.391.728-2.43Zm3.314 11.733c-.048-.096-2.325-1.234-2.113-3.422.212-2.189 1.675-2.789 1.698-2.854.023-.065-.597-.79-1.254-1.157a3.692 3.692 0 0 0-1.563-.434c-.108-.003-.483-.095-1.254.116-.508.139-1.653.589-1.968.607-.316.018-1.256-.522-2.267-.665-.647-.125-1.333.131-1.824.328-.49.196-1.422.754-2.074 2.237-.652 1.482-.311 3.83-.067 4.56.244.729.625 1.924 1.273 2.796.576.984 1.34 1.667 1.659 1.899.319.232 1.219.386 1.843.067.502-.308 1.408-.485 1.766-.472.357.013 1.061.154 1.782.539.571.197 1.111.115 1.652-.105.541-.221 1.324-1.059 2.238-2.758.347-.79.505-1.217.473-1.282Z" />
                        <path
                            d="M11.182.008C11.148-.03 9.923.023 8.857 1.18c-1.066 1.156-.902 2.482-.878 2.516.024.034 1.52.087 2.475-1.258.955-1.345.762-2.391.728-2.43Zm3.314 11.733c-.048-.096-2.325-1.234-2.113-3.422.212-2.189 1.675-2.789 1.698-2.854.023-.065-.597-.79-1.254-1.157a3.692 3.692 0 0 0-1.563-.434c-.108-.003-.483-.095-1.254.116-.508.139-1.653.589-1.968.607-.316.018-1.256-.522-2.267-.665-.647-.125-1.333.131-1.824.328-.49.196-1.422.754-2.074 2.237-.652 1.482-.311 3.83-.067 4.56.244.729.625 1.924 1.273 2.796.576.984 1.34 1.667 1.659 1.899.319.232 1.219.386 1.843.067.502-.308 1.408-.485 1.766-.472.357.013 1.061.154 1.782.539.571.197 1.111.115 1.652-.105.541-.221 1.324-1.059 2.238-2.758.347-.79.505-1.217.473-1.282Z" />
                    </svg>
                </div>
                <div class="social facebook">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                        <path
                            d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                    </svg>
                </div>
            </div>-->
        </div>
    </div>
    </div>

</v-app>
</template>

<style scoped>



.social {
    width: 50px;
    height: 50px;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    cursor: pointer;
}

.social:last-child {
    margin-right: 0px;
}

.google {
    background-color: #9e9e9e;
}

.apple {
    background-color: black;
}

.facebook {
    background-color: #1977f2;
}

.social svg {
    fill: white;
}

.with-social {
    display: flex;
    align-items: center;
    margin: auto;
    width: 174px;
    margin-top: 20px;
}

.paragraph-description {
    font-size: 0.7rem;
    margin: 0 38px;
    color: #b1b1b1;
    position: absolute;
    bottom: 30px;
}

.be-main {
    width: 0.5px;
    height: 28px;
    background-color: #fff;
    margin-left: 10px;
}

.input {
    width: 100%;
    border: none;
    background: #fff !important;
    outline: none;
}
.input:active,input:focus{
    background: #fff !important;
}
.icon-email {
    width: 15px;
    height: 15px;
}

.icon-continue {
    fill: #2abe88;
    width: 15px;
    height: 15px;
}

.input-box label {
    position: absolute;
    text-align: left;
    font-size: 0.5rem;
    pointer-events: none;
    transition: 0.5s;
    font-size: 0.8rem;
    top: 2px;
    left: 0;
}

.input:focus~label,
.input:valid~label {
    font-size: 0.5rem;
    top: -11px;
}

.input-box {
    width: 100%;
    margin: 0 10px;
    position: relative;
}

.continue {
    background-color: #0266ff;
    color: white;
    border: none;
    width: 150px;
    height: 45px;

    border-radius: 11px;
    font-size: 13px;
    margin-top: 15px;
    cursor: pointer;
}

.input-main {
    width: 100%;
    height: 52px;
    margin: auto;
    margin-top: 20px;
    border: 1px solid #d3d3d3;
    border-radius: 11px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
}

.btn-switch {
    height: 37px;
    width: 159px;
    border-radius: 11px;
    line-height: 37px;
    font-weight: 500;
    font-size: 12px;
    margin-right: 4px;
}

.singin {
    float: left;
    background-color: white;
}

.singup {
    float: left;
    color: #737375;
}

.switch-details {
    border-radius: 11px;
    width: 330px;
    height: 45px;
    padding: 4px;
    padding-right: 0;
    margin: auto;
    margin-top: 20px;
    background-color: #f0eff2;
}

.login-main{
 display: flex;
 justify-content: center;
}

.login-main p {
    font-size: 0.8rem;
    margin-top: 10px;
    color: #ababab;
}

.login-main h1 {
    font-size: 1.5rem;
}





.logo {
    width: 150px;
    height: auto;
    margin-top: 20px;
    margin-bottom: 20px;

}



.title-social {
    color: #949494;
    font-size: 0.8rem;
    margin-top: 15px;
    display: flex;
    width: 310px;
    margin: auto;
    margin-top: 18px;
    align-items: center;
}

.title-social::before {
    content: "";
    display: block;
    width: 90px;
    height: 0.5px;
    background-color: #949494;
    margin-right: 13px;
}

.title-social::after {
    content: "";
    display: block;
    width: 90px;
    height: 0.5px;
    background-color: #949494;
    margin-left: 13px;
}


.theme-cyan .btn-primary {
	background: #0c2e8a !important;
	margin-top: 20px;
    width: 100% !important;
    height: 40px;
    border-radius: 20px !important;
}



</style>

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
                    dialog:false,
                    formOtp:{
                        id:"",
                        otp:"",
                    }
            }
        },

        methods:{
            async submit(){
                this.loading=true;
                try{
                    const response  = await axios.post('api/login',{
                    'username':this.form.username,
                    'password':this.form.password,
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

            const user = JSON.stringify(response.data.user);

                const access_token = localStorage.getItem('token');

                axios.defaults.headers.common['Authorization'] = `Bearer ${access_token}`;




                axios.defaults.headers.common['Authorization'] = `Bearer ${access_token}`;
                    this.loading=false;

                    if(user.role == 'Health'){
                        this.$router.push('strategy-home');
                    }else{
                        this.$router.push('admin-home');
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
            async verifyOtp(){

                if(this.formOtp.otp.length == 4)

      {  this.loading=true;
        try{
            const response  = await axios.post('../../api/verify-otp',{
        'id':this.formOtp.id,
        'otp':this.formOtp.otp,
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
                    this.$router.push('../../staff-home');
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
        });}
        this.loading = false;
}



},

async resendOtp(){
this.loadingResend = true;
try{
    const response  = await axios.post('../../api/resend-otp',{
'id':this.formOtp.id,

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

<style lang="scss" scoped>

</style>
