<template>
<div class="auth option2" style="height:100%">
    <div class="container" style="margin-top:40px">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <a class="header-brand" href="index.html"><img src="images/logo.png" style="height:120px;width:120px"></a>
                    <div class="card-title">Register Borrower</div>
                </div>
                <p class="text-muted"><strong>How we will use the information about you</strong> <br>


                        We will use your data to process your application, to allow us to provide finance products to your business and improve our sites. We may share your data with third parties to achieve these purposes. We will not share your data for marketing purposes. We will keep your data safe in accordance with data protection legislation. Information on how your data may be used is available on our Terms and Conditions.

                        You can change your mind at any time by emailing legal@BimaKwik.com</p>
                       <form @submit.prevent="submit">
                        <div class="row clearfix">
                            <div class="row clearfix">
                                    <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label>Register as</label>
                                        <select  v-model="form.as" class="form-control" required>
                                            <option value="">Register As</option>
                                            <option value="LIMITED COMPANY">LIMITED COMPANY</option>
                                            <option value="SACCOS">SACCOS</option>
                                            <option value="VICOBA">VICOBA</option>
                                            <option value="GOVERNMENT AGENCY">GOVERNMENT AGENCY</option>
                                            <option value="CLUB">CLUB</option>
                                            <option value="BANK">BANK</option>
                                            <option value="NGO">NGO</option>
                                        </select>
                                                 <div class="validation-error" v-if="errors.as">
                                                {{errors.as[0]}}
                                            </div>
                                    </div>
                                </div>
                                </div>

<div class="col-md-4 col-sm-4">
    <div class="form-group">
    <label>Name of Entity</label>
    <input type="text" class="form-control" v-model="form.name"  placeholder="Name of Entity *" required>
      <div class="validation-error" v-if="errors.name">
            {{errors.name[0]}}
      </div>
</div>
</div>

<div class="col-md-4 col-sm-4">
    <div class="form-group">
    <label>REG/INCO NUMBER</label>
    <input type="text" class="form-control" v-model="form.registrationNo"  placeholder="REGISTRATION/INCORPORATION NUMBER *" required>
      <div class="validation-error" v-if="errors.registrationNo">
            {{errors.registrationNo[0]}}
      </div>
</div>
</div>

<div class="col-md-4 col-sm-12">
<div class="form-group">
<label>TIN</label>
<input type="text" v-model="form.tin" class="form-control"
       placeholder="TIN ">
         <div class="validation-error" v-if="errors.tin">
        {{errors.tin[0]}}
    </div>
</div>
</div>

<div class="row clearfix">
<div class="col-sm-6">
<div class="form-group">
    <label>Number of Directors</label>
    <input type="text" class="form-control" v-model="form.directors"  placeholder="Number of Directors *" required>
      <div class="validation-error" v-if="errors.directors">
            {{errors.directors[0]}}
      </div>
</div>
</div>

<div class="col-sm-6">
<div class="form-group">
    <label>Name of managing director</label>
    <input type="text" class="form-control" v-model="form.mdName"  placeholder="Name of managing director *" required>
      <div class="validation-error" v-if="errors.mdName">
            {{errors.mdName[0]}}
      </div>
</div>
</div>
</div>

<div class="row clearfix">
<div class="col-md-3 col-sm-12">
<div class="form-group">
    <label>Region</label>
    <select class="form-control show-tick" v-model="region" required>
        <option :value="[]">Select Region</option>
        <option v-for="reg in regions" :key="reg.id" :value="reg">{{reg.name}}</option>
    </select>
</div>
</div>

<div class="col-md-3 col-sm-12" v-if="region != ''">
<div class="form-group" >
     <label>District</label>
    <select class="form-control show-tick" v-model="form.district">
        <option value="">Select District</option>
        <option  v-for="dist in region.districts" :key="dist.id" :value="dist.name">{{dist.name}}</option>
    </select>
    <div class="validation-error" v-if="errors.district">{{errors.district[0]}}</div>

</div>
</div>
<div class="col-md-3 col-sm-12">
<div class="form-group">
     <label>Ward</label>
    <input type="text"  v-model="form.ward" class="form-control" placeholder="Ward *" required />
    <div class="validation-error" v-if="errors.ward">{{errors.ward[0]}}</div>

</div>
</div>
<div class="col-md-3 col-sm-12">
<div class="form-group">
     <label>Physical Location</label>
    <input type="text"  v-model="form.street" class="form-control" placeholder="Street *" required />
    <div class="validation-error" v-if="errors.street">{{errors.street[0]}}</div>

</div>
</div>
</div>
<div class="row clearfix">
<div class="col-md-6 col-sm-12">
<div class="form-group">
     <label>Bank Account No</label>
    <input type="text"  v-model="form.bankno" class="form-control" placeholder="Bank Account No*" required />
    <div class="validation-error" v-if="errors.bankno">{{errors.bankno[0]}}</div>

</div>
</div>
<div class="col-md-6 col-sm-12">
<div class="form-group">
     <label>Bank Name</label>
    <input type="text"  v-model="form.bankname" class="form-control" placeholder="Bank Account No*" required />
    <div class="validation-error" v-if="errors.bankname">{{errors.bankname[0]}}</div>

</div>
</div>
</div>
<div class="row clearfix">
<div class="col-md-4 col-sm-12">
<div class="form-group">
    <label>Email (Authorized email)</label>
    <input type="email"  v-model="form.email" class="form-control" placeholder="Email *" required>
           <div class="validation-error" v-if="errors.email">
            {{errors.email[0]}}
      </div>
</div>
</div>
<div class="col-md-4 col-sm-12">
<div class="form-group">
   <label>Phone(eg:255622958539)</label>
    <input type="text"  v-model="form.phone" class="form-control" placeholder="Phone *" required>
    <div class="validation-error" v-if="errors.phone">
        {{errors.phone[0]}}
    </div>
</div>
</div>
<div class="col-md-4 col-sm-12">
<div class="form-group">
<label>ANNUAL TURNOVER</label>
<input type="text" v-model="form.annualTurnOver" class="form-control"
       placeholder="ANNUAL TURNOVER *" required>
         <div class="validation-error" v-if="errors.annualTurnOver">
        {{errors.annualTurnOver[0]}}
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




</div>

<div class="row clearfix">

<div class="col-md-6 col-sm-12">
<div class="form-group">
    <label>Physical Address(PO BOX)</label>
    <input type="text"  v-model="form.address" class="form-control" placeholder="Physical Address *" required>
    <div class="validation-error" v-if="errors.address">
        {{errors.address[0]}}
    </div>
</div>
</div>




<div class="col-md-6 col-sm-12">
<div class="form-group">
<label>BUSINESS COMMENCEMENT DATE</label>
<input type="date" v-model="form.cDate" class="form-control" required>
         <div class="validation-error" v-if="errors.cDate">
        {{errors.cDate[0]}}
    </div>
</div>
</div>



</div>

<div class="row clearfix">
<div class="col-12">
<label>Upload MANAGING DIRECTOR Id card</label>
    <vue-dropzone :options="dropzoneOptions" v-on:vdropzone-success="uploadSuccess" id="upload"></vue-dropzone>
</div>

<div class="col-12">
<label>Upload  Registration Certification</label>
    <vue-dropzone :options="dropzoneOptions" v-on:vdropzone-success="uploadSuccessRegistration" id="upload"></vue-dropzone>
</div>

<div class="col-12">
<label>Upload Business License</label>
    <vue-dropzone :options="dropzoneOptions" v-on:vdropzone-success="uploadSuccessLicence" id="upload"></vue-dropzone>
</div>


<p>
I declare information which I provided in this form is accurate and valid at the date of opening account and I have also fully read
and understood terms and conditions for opening CASHME TANZANIA account as contained herein, and also the accompanying documents as
 applicable and agree to be bound by all terms and conditions as
applicable to the Investor services applied for by me</p>
<p> I therefore request that you open an account and provide your services to me inline with the above information.

</p>
<p><input type="checkbox" v-model="form.declaration" /></p>

</div>

                                        <div class="row clearfix" style="margin-top:20px">
                                    <div class="col-sm-12">
                                         <div class="text-center" v-if="form.declaration">
                                              <button class="btn btn-primary btn-block" type="button" disabled v-if="loading" >
                                                <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                                Loading...
                                            </button>
                    <button type="submit" class="btn btn-primary btn-block" v-else >Create new account</button>

                    <div class="text-muted mt-4">Already have account? <router-link to="/">Sign in</router-link></div>
                </div>

                                    </div>
                                </div>
                            </form>
            </div>
        </div>
    </div>
</div>
</template>

<script>
  import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css';
export default {
    name: 'borrowerRegistration',

    data() {
        return {search:"",search:"",
            form:{
               id_type: "",
                id_no: null,
                card:[],
                other:[],
                license:[],
                certificate:[],
                plan:"",
                as:"",
                gender:"",
                title:"",
                region:"",
                district:"",
                bankno:"",
                nextkin:"",
                password:"",
                password_confirmation:"",
                declaration:false,
            },
            sectors:[],
            errors:[],
            region:[],
            regions:[],
            errors:[],
            load:false,
            loading:false,
            dropzoneOptions: {
                url: '../../api/uploadCard',
                thumbnailWidth: 200,
                addRemoveLinks: true,
                dictDefaultMessage: "<i class='fa fa-cloud-upload upload-icon'></i></i>Drag and drop a file here or click"
            },
        };
    },

    async beforeMount() {
          const response = await axios.get('api/all-sector');
        this.sectors = response.data;
        const region = await axios.get("../../api/regions");
            this.regions =region.data;
    },

       methods:{
        uploadSuccess(file, response) {
             this.form.card = response;

        },

        uploadSuccessOther(file, response) {
             this.form.other = response;

        },
        uploadSuccessRegistration(file, response) {
             this.form.certificate = response;

        },
        uploadSuccessLicense(file, response) {
             this.form.license = response;

        },
        async submit(){
            this.loading=true;
            try{
                const response = await axios.post('../api/register-borrower',this.form);
                if(response.status == 200){
                    this.loading=false;

                          this.$toast.open({
                            message: 'Your successful registered as borrower',
                            type: 'success',
                            duration: 5000,
                            position: 'top-right'
                            // all of other options may go here
                        });

                           await localStorage.setItem('user',JSON.stringify(response.data.user))
                await localStorage.setItem('token',response.data.token)

                const access_token = localStorage.getItem('token');

                axios.defaults.headers.common['Authorization'] = `Bearer ${access_token}`;
                    this.$router.push('borrower-home');
                }
            }catch(e){
                this.loading=false;
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
          menuclick (value) {
      this.mobile = value
    }

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

<style>
.card{
    background: #fff;
}
.upload-icon{
    display: block;
    font-size: 50px;
}

#dropzone{
    border-radius: 4px;
    border:0.3px solid #ced4da;
}
</style>
