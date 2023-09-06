<template>
    <v-app>
        <v-dialog v-model="dialog" activator="parent" width="auto">
            <v-card style="padding: 30px; width: 800px">
                <v-card-text>
                    <form style="">
                        <div
                            style="width: 100%;display: flex;flex-direction: column;align-items: center;position: relative; !important">
                            <img :src="url + form.url" alt="" style="width: 150px; height: 150px" />

                            <div class="upload-icon">
                                <label for="upload-img"><i class="fas fa-camera camera-icon"></i></label>
                                <input type="file" style="display: none" id="upload-img" @change="uploadImage" />
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group" style="margin-top: 30px">
                                    <label for="">First Name</label>

                                    <input type="text" class="form-control" v-model="form.fname" required />
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group" style="margin-top: 30px">
                                    <label for="">Last Name</label>

                                    <input type="text" class="form-control" v-model="form.lname" required />
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group" >
                                    <label for="">Username</label>

                                    <input type="text" class="form-control" v-model="form.username" required />
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="">Phone</label>
                                    <input type="text" class="form-control" v-model="form.phone" />
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input type="text" class="form-control" v-model="form.email" />
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Commission Rate</label>
                                    <input type="number" min="1" max="100" v-model="form.rate" class="form-control" placeholder="Rate *" required>
                                    <div class="validation-error" v-if="errors.rate">
                                        {{errors.rate[0]}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Region</label>
                                    <select class="form-control show-tick" v-model="region" required>
                                        <option :value="[]">
                                            Select Region
                                        </option>
                                        <option v-for="reg in regions" :key="reg.id" :value="reg">
                                            {{ reg.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>District</label>
                                    <select class="form-control show-tick" v-model="form.district">
                                        <option value="">
                                            Select District
                                        </option>
                                        <option v-for="dist in region.districts" :key="dist.id" :value="dist.name">
                                            {{ dist.name }}
                                        </option>
                                    </select>
                                    <div class="validation-error" v-if="errors.district">
                                        {{ errors.district[0] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>ID Type</label>
                                    <select class="form-control show-tick" v-model="form.idtype">
                                        <option value="">Select ID type</option>
                                        <option value="National ID (NIDA)">National ID (NIDA)</option>
                                        <option value="Zanzibar ID (NIDA)">Zanzibar ID (NIDA)</option>
                                        <option value="Voter ID">Voter ID</option>
                                        <option value="Passport">Passport</option>
                                        <option value="Driving Licence">Driving Licence</option>

                                    </select>
                                    <div class="validation-error" v-if="errors.idtype">
                                        {{errors.idtype[0]}}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>ID Number</label>
                                    <input type="text" v-model="form.idno" class="form-control" placeholder="ID number *" required>
                                    <div class="validation-error" v-if="errors.idno">
                                        {{errors.idno[0]}}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="primary" block @click="submit">Save</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="editDialog" activator="parent" width="auto">
            <v-card style="padding: 30px; width: 500px">
                <v-card-text>
                    <form style="">
                        <div
                            style="width: 100%;display: flex;flex-direction: column;align-items: center;position: relative; !important">
                            <img :src="url + formEdit.url" alt="" style="width: 150px; height: 150px" />

                            <div class="upload-icon">
                                <label for="upload-img"><i class="fas fa-camera camera-icon"></i></label>
                                <input type="file" style="display: none" id="upload-img" @change="uploadImageEdit" />
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 30px">
                            <label for="">Insurance Name</label>

                            <input type="text" class="form-control" v-model="formEdit.name" required />
                        </div>
                        <div class="form-group">
                            <label for="">Phone</label>
                            <input type="text" class="form-control" v-model="formEdit.phone" />
                        </div>
                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="text" class="form-control" v-model="formEdit.email" />
                        </div>

                        <div class="form-group">
                            <label for="">Code</label>
                            <input type="text" class="form-control" v-model="formEdit.code" />
                        </div>
                    </form>
                </v-card-text>
                <v-card-actions>
                    <v-btn color="primary" block @click="submitEdit">Update</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <div id="main_content" :class="mobile ? 'offcanvas-active' : ''">
            <!-- Start Main top header -->
            <admin-header v-on:childToParent="menuclick"></admin-header>
            <!-- Start Rightbar setting panel -->
            <!-- Start Main leftbar navigation -->
            <sidebar-left link="agent"></sidebar-left>
            <!-- Start project content area -->
            <!-- Start project content area -->
            <div class="page">
                <!-- Start Page header -->
                <top-nav></top-nav>
                <!-- Start Page title and tab -->
                <div class="section-body">
                    <div class="container-fluid">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="header-action">
                                <h1 class="page-title">Agents</h1>
                                <ol class="breadcrumb page-breadcrumb">
                                    <li class="breadcrumb-item">
                                        <router-link to="#">BimaKwik</router-link>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="section-body mt-4">
                    <div class="container-fluid">
                        <div class="tab-content">
                            <div class="tab-pane active">
                                <div class="filter" style="padding: 30px 15px">
                                    <div class="">
                                        <h4>
                                            <span>All Agents</span>
                                            <article class="filter-range-date">
                                                <form @submit.prevent="filterData">
                                                    <section class="form-col3-left">
                                                        <h5>From Date</h5>
                                                        <input type="date" v-model="filter.min" placeholder="From date"
                                                            class="form-control" required />
                                                    </section>
                                                    <input type="hidden" name="filter" value="filter" />
                                                    <section class="form-col3-mid">
                                                        <h5>To Date</h5>
                                                        <input type="date" v-model="filter.max" placeholder="To date"
                                                            class="form-control" required />
                                                    </section>
                                                    <section class="form-col3-right">
                                                        <button type="submit" class="btn btn-secondary btn-sm"
                                                            style="margin-top: 25px !important; background-color:#1976d border:1px solid #f4f6f6; border-radius: 6px; color:#fff">
                                                            Filter
                                                        </button>
                                                    </section>
                                                </form>
                                            </article>
                                        </h4>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="table-responsive">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="input-group mb-3" style="width: 300px">
                                                    <input type="text" class="form-control" placeholder="Search"
                                                        v-model="search" />
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-6">
                                                <ul class="header-dropdown inline-button" style="float: right">
                                                    <li>
                                                        <download-excel class="btn btn-info excel-green" :data="all"
                                                            :fields="label" title="export excel" worksheet="My Worksheet"
                                                            name="companies.xls" style="color: #fff"><i
                                                                class="fas fa-file-excel" style="
                                                                    color: #fff;
                                                                "></i>&nbsp; Excel
                                                        </download-excel>
                                                    </li>
                                                    <li>
                                                        <button class="btn btn-info excel-green" type="button" @click="
                                                            dialog = !dialog
                                                            ">
                                                            New Agents
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <v-data-table :headers="headers" :items="all" :items-per-page="10" :search="search"
                                            class="elevation-1">
                                            <template #item.index="{ item }">
                                                {{ all.indexOf(item) + 1 }}
                                            </template>

                                            <template v-slot:item.img="props">
                                                <img :src="url +
                                                    props.item.url
                                                    " style="
                                                        height: 50px;
                                                        width: 50px;
                                                        border-radius: 6px;
                                                    " alt="" srcset="" />
                                            </template>

                                            <template v-slot:item.join="props">
                                                {{
                                                    props.item.created_at
                                                    | formatDate
                                                }}
                                            </template>

                                            <template v-slot:item.stat="props">
                                                <span class="badge alert-success" v-if="props.item.user.status.toUpperCase() ==
                                                    'ACTIVE'
                                                    ">{{
        props.item.status
    }}</span>
                                                <span class="badge alert-danger" v-else>{{
                                                    props.item.status
                                                }}</span>
                                            </template>
                                            <template v-slot:item.actn="props">
                                                <span class="badge alert-success">
                                                    <button>
                                                        <i class="fas fa-expand"></i>
                                                        View
                                                    </button>
                                                </span>

                                                <span class="badge alert-info">
                                                    <button @click="
                                                        editEnable(
                                                            props.item.id
                                                        )
                                                        ">
                                                        <i class="fas fa-edit"></i>
                                                        Edit
                                                    </button>
                                                </span>

                                                <span class="badge alert-danger">
                                                    <button @click="
                                                        removeItems(
                                                            props.item.id,
                                                            index
                                                        )
                                                        ">
                                                        <i class="fas fa-bin"></i>
                                                        Delete
                                                    </button>
                                                </span>
                                            </template>
                                        </v-data-table>
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
import vue2Dropzone from "vue2-dropzone";
import "vue2-dropzone/dist/vue2Dropzone.min.css";
export default {
    data() {
        return {
            all: [],
            url: window.location.protocol + "//" + window.location.host,

            search: "",
            editDialog: false,
            form: {
                fname: "",
                lname:"",
                phone: "",
                email: "",
                rate: "",
                idtype:"",
                idno:"",
                role:"agent",
                region: "",
                district: "",
                url: "/images/user.png",
                path: "",
            },

            formEdit: {
                id: "",
                name: "",
                phone: "",
                email: "",
                code: "",
                url: "/images/user.png",
                path: "",
            },

            loading: false,
            dialog: false,

            process: [],
            mobile: false,
            processes: [],
            display: false,
            filter: {
                from: "",
                to: "",
            },

            active: "all",
            int: 1,

            companies: [],
            errors: [],
            errorsWithdraw: [],
            region: [],
            regions: [],
            errorsEdit: [],
            headers: [
                {
                    value: "index",
                    text: "#",
                    sortable: false,
                },
                {
                    text: "",
                    value: "img",
                },
                {
                    text: "First Name",
                    value: "user.first_name",
                },
                {
                    text: "Last Name",
                    value: "user.last_name",
                },
                {
                    text: "Username",
                    value: "user.username",
                },
                {
                    text: "Email",
                    value: "user.email",
                },


                {
                    text: "Phone",
                    value: "user.phone",
                },
                {
                    text: "Commission Rate (%)",
                    value: "commission_rate",
                },
                {
                    text: "Region",
                    value: "region",
                },
                {
                    text: "District",
                    value: "district",
                },

                {
                    text: "ID Type",
                    value: "idtype",
                },

                {
                    text: "ID Number",
                    value: "idno",
                },

                {
                    text: "Status",
                    value: "stat",
                },

                {
                    text: "Created At",
                    value: "join",
                },

                {
                    text: "",
                    value: "actn",
                },
            ],
            label: {
                "First Name": "user.first_name",
                "Last Name": "user.last_name",
                Phone: "user.phone",
                Email: "user.email",
                Policies: "policies",
                Claim: "claims",

                status: "status",
            },
        };
    },

    async beforeMount() {
        this.user = JSON.parse(localStorage.getItem("user"));

        const region = await axios.get("../../api/regions");
        this.regions = region.data;

        const res = await axios.get("../api/agent");
        this.all = res.data;
    },

    mounted() { },

    methods: {
        menuclick(value) {
            this.mobile = value;
        },

        async removeItems(id, index) {
            var result = await this.$swal({
                title: "Are you sure?",
                text: "You won't be able to revert this",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            });
            if (result.isConfirmed) {
                try {
                    var response = await axios.delete(
                        "../../api/company/" + id
                    );
                    if (response.status == 200) {
                        this.$swal("Deleted!", response.data, "success");
                        const res = await axios.get("api/company");
                        this.all = res.data;
                    }
                } catch (e) {
                    this.$toast.open({
                        message: e.response.data.error,
                        type: "error",
                        duration: 5000,
                        position: "bottom-right",
                        // all of other options may go here
                    });
                }
            }
        },
        async filterData() {
            this.loading = true;
            const response = await axios.post(
                "api/expiring-filter",
                this.filter
            );

            this.all = response.data;
            this.loading = false;
        },

        async uploadImage(event) {
            const file = event.target.files[0];
            const formData = new FormData();
            formData.append("image", file);

            this.loading = true;

            const resp = await axios.post("api/upload-file", formData);

            console.log(resp.data);

            this.form.url = resp.data.url;
            this.form.path = resp.data.path;

            this.loading = false;
        },

        async uploadImageEdit(event) {
            const file = event.target.files[0];
            const formData = new FormData();
            formData.append("image", file);

            this.loading = true;

            const resp = await axios.post("api/upload-file", formData);

            console.log(resp.data);

            this.formEdit.url = resp.data.url;
            this.formEdit.path = resp.data.path;

            this.loading = false;
        },

        async approve(item) {
            try {
                var result = await this.$swal({
                    title:
                        "Are you sure you want to approve " +
                        item.transactionId,
                    text: "You won't be able to revert this action!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#000",
                    confirmButtonText: "Yes, confirm !",
                });
                if (result.isConfirmed) {
                    const response = await axios.get(
                        "../api/transaction-approved/" +
                        item.id +
                        "/" +
                        this.user.role +
                        " approved"
                    );
                    if (response.status == 200) {
                        const response = await axios.get("api/transactions");
                        this.all = response.data;

                        this.loading = false;

                        this.$swal(
                            "Confirmed Refresh browser!",
                            response.data,
                            "success"
                        );
                    }
                }
            } catch (e) {
                console.log(e);
                this.$toast.open({
                    message: "Internal server error",
                    type: "error",
                    duration: 5000,
                    position: "bottom-right",
                    // all of other options may go here
                });

                this.loading = false;
            }
        },

        async submit() {
            this.loading = true;
            this.form.region = this.region.name;

            try {
                const resp = axios.post("../api/agent", this.form);

                const res = await axios.get("../api/agent");
                this.all = res.data;

                this.loading = false;

                this.dialog = false;

                this.$swal("Company added", "success");
            } catch (e) { }
        },

        editEnable(id) {
            const company = this.all.filter((item) => item.id === id);

            console.log(company);

            this.editDialog = true;

            this.formEdit.name = company[0].name;
            this.formEdit.code = company[0].company_code;
            this.formEdit.phone = company[0].phone;
            this.formEdit.url = company[0].logo_url;
            this.formEdit.id = company[0].id;
            this.formEdit.path = company[0].logo_path;
            this.formEdit.email = company[0].email;
        },

        async submitEdit() {
            this.loading = true;

            try {
                const resp = axios.post(
                    "../api/company-update/" + this.formEdit.id,
                    this.formEdit
                );

                const res = await axios.get("../api/company");
                this.all = res.data;

                this.loading = false;

                this.editDialog = false;

                this.$swal("Company edited", "success");
            } catch (e) { }
        },
    },
    components: {
        vueDropzone: vue2Dropzone,
    },
    watch: {
        $route: {
            immediate: true,
            handler(to, from) {
                document.title = to.meta.title || "Some Default Title";
            },
        },
    },
};
</script>

<style scoped>
.inline-button li {
    display: inline;
}

.upload-icon {
    position: absolute;
    color: black;
    bottom: 10px;
}

.camera-icon {
    font-size: 25px;
}
</style>
