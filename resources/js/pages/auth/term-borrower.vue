<template>
<div class="auth option2">
    <div class="container" style="margin-top:40px">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <a class="header-brand" href="index.html"><img src="images/logo.png" style="height:120px;width:120px"></a>
                    <div class="card-title">Register Borrower</div>
                </div>

                <div class="stepper-wrapper">
                    <div class="stepper-item " :class="counter>=2?'completed':''">
                        <div class="step-counter">1</div>
                        <div class="step-name">Term and Condition</div>
                    </div>

                    <div class="stepper-item " :class="counter>=3?'completed':''">
                        <div class="step-counter">2</div>
                        <div class="step-name">Details</div>
                    </div>
                    <div class="stepper-item " :class="counter==4?'completed':''">
                        <div class="step-counter">3</div>
                        <div class="step-name">Ducuments</div>
                    </div>

                </div>

                <div v-if="counter==1">
                    <p class="text-muted"><strong>Terms Borrower</strong> <br></p>

                    <ol>
                        <li>The information contained on this page together with any further instructions and conditions that may be prescribed by the
                            <strong>CASHME TANZANIA</strong> from time to time shall constitute the terms of the agreement between the Investor and CASHME
                        </li>
                        <li>
                            The Customer will provide to CASHME TANZANIA all documents and other information reasonably required by it in relation to any Account or any Service.
                        </li>
                        <li>
                            CASHME TANZANIA reserves the right to levy service fees at such general rates and intervals as it shall determine from time to time,
                            such service fees shall be displayed on the notice to investor.
                        </li>
                        <li>
                            Statement of Account will be handed over to the customer or authorized agent on request after using the registered email address last provided by the Customer.
                        </li>
                        <li>
                            By submitting this information, you grant access to CASHME TANZANIA for use of information for verification of compliance with AML laws and regulations
                        </li>
                        <li>CASHME TANZANIA reserves the right to use information provided for analysing credit worthiness of investor</li>
                        <li>
                            By Submitting this information, the you agree to be bound by Terms and Conditions of services for Investors
                        </li>
                    </ol>

                    <div class="row clearfix" style="margin-top:20px">
                        <div class="col-6">
                            <div class="text-muted mt-4">Already have account? <router-link to="/">Sign in</router-link>
                            </div>
                        </div>

                        <div class="col-6">
                            <div style="float:right">
                                <button type="submit" class="btn btn-primary btn-block" @click.prevent="counter++">Continue</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else-if="counter==2">
                    <form @submit.prevent="counter++">
                        <div class="row clearfix">
                            <div class="row clearfix">
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label>Register as</label>
                                        <select v-model="form.as" class="form-control" required>
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
                                    <input type="text" class="form-control" v-model="form.name" placeholder="Name of Entity *" required>
                                    <div class="validation-error" v-if="errors.name">
                                        {{errors.name[0]}}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-4">
                                <div class="form-group">
                                    <label>REG/INCO NUMBER</label>
                                    <input type="text" class="form-control" v-model="form.registrationNo" placeholder="REGISTRATION/INCORPORATION NUMBER *" required>
                                    <div class="validation-error" v-if="errors.registrationNo">
                                        {{errors.registrationNo[0]}}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label>TIN</label>
                                    <input type="text" v-model="form.tin" class="form-control" placeholder="TIN ">
                                    <div class="validation-error" v-if="errors.tin">
                                        {{errors.tin[0]}}
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Number of Directors</label>
                                        <input type="text" class="form-control" v-model="form.directors" placeholder="Number of Directors *" required>
                                        <div class="validation-error" v-if="errors.directors">
                                            {{errors.directors[0]}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Name of managing director</label>
                                        <input type="text" class="form-control" v-model="form.mdName" placeholder="Name of managing director *" required>
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
                                    <div class="form-group">
                                        <label>District</label>
                                        <select class="form-control show-tick" v-model="form.district">
                                            <option value="">Select District</option>
                                            <option v-for="dist in region.districts" :key="dist.id" :value="dist.name">{{dist.name}}</option>
                                        </select>
                                        <div class="validation-error" v-if="errors.district">{{errors.district[0]}}</div>

                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Ward</label>
                                        <input type="text" v-model="form.ward" class="form-control" placeholder="Ward *" required />
                                        <div class="validation-error" v-if="errors.ward">{{errors.ward[0]}}</div>

                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label>Physical Location</label>
                                        <input type="text" v-model="form.street" class="form-control" placeholder="Street *" required />
                                        <div class="validation-error" v-if="errors.street">{{errors.street[0]}}</div>

                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Bank Account No</label>
                                        <input type="text" v-model="form.bankno" class="form-control" placeholder="Bank Account No*" required />
                                        <div class="validation-error" v-if="errors.bankno">{{errors.bankno[0]}}</div>

                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Bank Name</label>
                                        <input type="text" v-model="form.bankname" class="form-control" placeholder="Bank Account No*" required />
                                        <div class="validation-error" v-if="errors.bankname">{{errors.bankname[0]}}</div>

                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>Email (Authorized email)</label>
                                        <input type="email" v-model="form.email" class="form-control" placeholder="Email *" required>
                                        <div class="validation-error" v-if="errors.email">
                                            {{errors.email[0]}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>Phone(eg:255622958539)</label>
                                        <input type="text" v-model="form.phone" class="form-control" placeholder="Phone *" required>
                                        <div class="validation-error" v-if="errors.phone">
                                            {{errors.phone[0]}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>ANNUAL TURNOVER</label>
                                        <input type="text" v-model="form.annualTurnOver" class="form-control" placeholder="ANNUAL TURNOVER *" required>
                                        <div class="validation-error" v-if="errors.annualTurnOver">
                                            {{errors.annualTurnOver[0]}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" class="form-control" v-model="form.password" placeholder="Password *" required>
                                        <div class="validation-error" v-if="errors.password">
                                            {{errors.password[0]}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Confirm Password</label>
                                        <input type="password" class="form-control" v-model="form.password_confirmation" placeholder="Confirm Password *" required>

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
                                    <input type="text" v-model="form.address" class="form-control" placeholder="Physical Address *" required>
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

                        <div class="row clearfix" style="margin-top:20px">
                            <div class="col-6">

                                <button @click.prevent="counter--" class="btn btn-primary btn-block">Back</button>

                            </div>

                            <div class="col-6">
                                <div style="float:right">
                                    <button class="btn btn-primary btn-block" type="button" disabled v-if="loading">
                                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                        Loading...
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-block" v-else>Continue</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div v-else>

                    <form @submit.prevent="submit">

                        <div class="row clearfix">
                            <div class="col-12">
                                <label>Upload MANAGING DIRECTOR Id card</label>
                                <vue-dropzone :options="dropzoneOptions" v-on:vdropzone-success="uploadSuccess" id="upload"></vue-dropzone>
                            </div>

                            <div class="col-12">
                                <label>Upload Registration Certification</label>
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
                            <div class="col-6">

                                <button @click.prevent="counter--" class="btn btn-primary btn-block">Back</button>

                            </div>

                            <div class="col-6">
                                <div style="float:right" v-if="form.declaration">
                                    <button class="btn btn-primary btn-block" type="button" disabled v-if="loading">
                                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                        Loading...
                                    </button>
                                    <button type="submit" class="btn btn-primary btn-block" v-else>Finish</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

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
        return {
            counter: 1,
            form: {
                id_type: "",
                id_no: null,
                card: [],
                other: [],
                license: [],
                certificate: [],
                plan: "",
                as: "",
                gender: "",
                title: "",
                region: "",
                district: "",
                bankno: "",
                nextkin: "",
                password: "",
                password_confirmation: "",
                declaration: false,
            },
            sectors: [],
            errors: [],
            region: [],
            regions: [],
            errors: [],
            load: false,
            mobile: false,
            loading: false,
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
        this.regions = region.data;
    },

    methods: {
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
        async submit() {
            this.loading = true;
            try {
                const response = await axios.post('../api/register-borrower', this.form);
                if (response.status == 200) {
                    this.loading = false;

                    this.$toast.open({
                        message: 'Your successful registered as borrower',
                        type: 'success',
                        duration: 5000,
                        position: 'top-right'
                        // all of other options may go here
                    });

                    await localStorage.setItem('user', JSON.stringify(response.data.user))
                    await localStorage.setItem('token', response.data.token)

                    const access_token = localStorage.getItem('token');

                    axios.defaults.headers.common['Authorization'] = `Bearer ${access_token}`;
                    this.$router.push('borrower-home');
                }
            } catch (e) {
                this.loading = false;
                if (e.response) {
                    if (e.response.status = 422) {
                        this.errors = e.response.data.errors;

                        this.$toast.open({
                            message: e.response.data.message,
                            type: 'error',
                            duration: 5000,
                            position: 'bottom-right'
                            // all of other options may go here
                        });

                    }
                } else {
                    this.$toast.open({
                        message: 'Internal server error',
                        type: 'error',
                        duration: 5000,
                        position: 'bottom-right'
                        // all of other options may go here
                    });
                }
                this.loading = false;
            }

        },
        menuclick(value) {
            this.mobile = value
        }

    },

    components: {

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
.card {
    background: #fff;
}

ol {
    padding: 20px 10px;

}

ol li {
    padding: 15px 0;
}

.stepper-wrapper {
    margin-top: auto;
    display: flex;
    justify-content: space-between;
    margin-bottom: 60px;
    margin-top: 20px;
}

.stepper-item {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;

    @media (max-width: 768px) {
        font-size: 12px;
    }
}

.stepper-item::before {
    position: absolute;
    content: "";
    border-bottom: 2px solid #ccc;
    width: 100%;
    top: 20px;
    left: -50%;
    z-index: 2;
}

.stepper-item::after {
    position: absolute;
    content: "";
    border-bottom: 2px solid #ccc;
    width: 100%;
    top: 20px;
    left: 50%;
    z-index: 2;
}

.stepper-item .step-counter {
    position: relative;
    z-index: 5;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #ccc;
    color: #fff;
    margin-bottom: 6px;
}

.stepper-item.active {
    font-weight: bold;
}

.stepper-item.completed .step-counter {
    background-color: #2DAB54!important;
    color: #000 !important;
}

.stepper-item.completed::after {
    position: absolute;
    content: "";
    border-bottom: 2px solid #2DAB54!important;
    width: 100%;
    top: 20px;
    left: 50%;
    z-index: 3;
}

.stepper-item:first-child::before {
    content: none;
}

.stepper-item:last-child::after {
    content: none;
}

.card {
    background: #fff;
}

.upload-icon {
    display: block;
    font-size: 50px;
}

label {
    text-transform: capitalize;
}

#dropzone {
    border-radius: 4px;
    border: 0.3px solid #ced4da;
}
</style>
