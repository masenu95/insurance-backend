<template>
<v-app>
    <v-dialog v-model="loading" :scrim="false" persistent width="auto">
        <v-card color="primary">
            <v-card-text style="color:#fff">
                <div style="padding-bottom: 5px;">Please wait</div>
                <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
            </v-card-text>
        </v-card>
    </v-dialog>
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
                            <h1 class="page-title">Invoice</h1>
                            <ol class="breadcrumb page-breadcrumb">
                                <li class="breadcrumb-item">
                                    <router-link to="admin-home">BimaKwik</router-link>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Invoice</li>
                            </ol>
                        </div>
                        <ul class="nav nav-tabs page-header-tab">
                            <li class="nav-item"><a class="nav-link active" @click.prevent="active='all'">List View</a></li>

                            <li class="nav-item"><a class="nav-link">Detail</a></li>

                        </ul>
                    </div>
                </div>
            </div>
            <div class="section-body mt-4">
                <div class="container-fluid">
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <div class="filter" style="padding:30px 15px">
                                <div class="">

                                    <h4><span>All Invoices</span>
                                        <article class="filter-range-date">
                                            <form @submit.prevent="filterData">
                                                <section class="form-col3-left">
                                                    <h5>From Date</h5>
                                                    <input type="date" v-model="filter.min" placeholder="From date" class="form-control" required>
                                                </section>
                                                <input type="hidden" name="filter" value="filter">
                                                <section class="form-col3-mid">
                                                    <h5>To Date</h5>
                                                    <input type="date" v-model="filter.max" placeholder="To date" class="form-control" required>
                                                </section>
                                                <section class="form-col3-right"><button type="submit" class="btn btn-secondary btn-sm"  style="margin-top: 25px !important; background-color:#1976d border:1px solid #f4f6f6; border-radius: 6px; color:#fff">Filter</button></section>
                                            </form>
                                        </article>
                                    </h4>

                                </div>

                            </div>
                            <div class="card" style="padding:20px 15px">

                                <div class="table-responsive">
                                    <div class="row">
                                        <div class="col-6">

                                            <div class="input-group mb-3" style="width:300px">
                                                <input type="text" class="form-control" placeholder="Search" v-model="search">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fa fa-search"></i></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <ul class="header-dropdown" style="float:right">
                                                <li>
                                                    <download-excel class="btn btn-info excel-green" :data="invoices" :fields="label" title="export excel" worksheet="My Worksheet" name="Invoices.xls" style="color:#fff"><i class="fas fa-file-excel" style="color:#fff"></i>&nbsp; Excel
                                                    </download-excel>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <v-data-table :headers="headers" :items="invoices" :items-per-page="10" :search="search" class="elevation-1">

                                        <template v-slot:item.controls="props">
                                            <router-link type="button" :to="'../invoice/'+props.item.id" class="btn btn-icon btn-sm" title="show"><i class="fe fe-maximize"></i></router-link>


                                        </template>

                                        <template v-slot:item.stat="props">

                                            <span class="badge alert-success" v-if="props.item.status=='ACTIVE'">{{props.item.status}}</span>
                                            <span class="badge alert-danger" v-else>{{props.item.status}}</span>

                                        </template>
                                        <template v-slot:item.inStatus="props">

                                            <span class="badge alert-success" v-if="props.item.invoice_status=='ACTIVE'">{{props.item.invoice_status}}</span>
                                            <span class="badge alert-danger" v-else>{{props.item.invoice_status}}</span>

                                        </template>

                                        <template v-slot:item.join="props">
                                            {{props.item.created_at | formatDate}}
                                        </template>

                                        <template v-slot:item.amou="props">
                                            {{props.item.amount | formatNumber}}
                                        </template>
                                        <template v-slot:item.dis="props">
                                            {{props.item.discount_amount | formatNumber}}
                                        </template>
                                        <template v-slot:item.av="props">
                                            {{props.item.available | formatNumber}}
                                        </template>
                                        <template v-slot:item.tradingfee="props">
                                            {{props.item.trading_fee}}%
                                        </template>
                                        <template v-slot:item.tradingfeeAmount="props">
                                            {{props.item.trading_fee_amount | formatNumber}}
                                        </template>
                                        <template v-slot:item.processingfee="props">
                                            {{props.item.proccessing_fee | formatNumber}}
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
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css';
export default {

    name: 'WorkspaceJsonBank',

    data() {
        return {
            search: "",
            search: "",
            invoices: [],
            load: false,
            mobile: false,
            loading: false,
            invoice: [],
            search: "",
            active: "all",
            int: 1,

            companies: [],
            errors: [],
            errorsEdit: [],
            progress: 0,
            previusStage: null,
            nextStage: null,
            process: [],
            processes: [],
            display: false,
            filter: {
                company: "",
                invoice: "",
                borrower: "",
                maturityStart: "",
                maturityEnd: "",
                amountStart: null,
                amountEnd: null,
                status: "",
                from: null,
                to: null,
            },
            flow: [],
            headers: [{
                    value: 'id',
                    text: 'ID',
                    sortable: false,
                },
                {
                    text: 'Invoice number',
                    value: 'invoice_number',
                },

                {
                    text: 'Owning company',
                    value: 'company.name',
                },
                {
                    text: 'Borrower',
                    value: 'borrower.user.name',
                },
                {
                    text: 'invoice Date',
                    value: 'invoice_date',
                },
                {
                    text: 'Maturity Date',
                    value: 'maturity_date',
                },
                {
                    text: "Model",
                    value: "model"
                },
                {
                    text: 'Amount',
                    value: 'amou',
                },
                {
                    text: 'Discounted Amount',
                    value: 'dis',
                },

                {
                    text: 'Available Amount',
                    value: 'av',
                },
                {
                    text: 'Trading fee',
                    value: 'trading_fee',
                },
                {
                    text: 'Trading fee amount',
                    value: 'tradingfeeAmount',
                },

                {
                    text: 'Proccessing fee',
                    value: 'processingfee',
                },
                {
                    text: "Invoice Status",
                    value: "inStatus"
                },

                {
                    text: 'Created At',
                    value: 'join'
                },
                {
                    text: 'Status',
                    value: "stat"
                },

                {
                    text: "Action",
                    value: "controls",
                    sortable: false
                }
            ],
            label: {
                "Invoice number": "invoice_number",
                "owning company": "company.name",
                "Borrower": "borrower.user.name",
                "invoice Date": "invoice_date",
                "Maturity Date": "maturity_date",
                "Model": "model",
                "Amount": "amount",
                "Discounted amount": "discount_amount",
                "Available Amount": "available",
                "Trading fee": "trading_fee",
                "Trading fee Amount": "trading_fee_amount",
                "Proccessing fee": "proccessing_fee",
                "Invoice Status": "invoice_status",
                "status": "status"
            }

        };
    },

    async beforeMount() {
        this.loading = true;
        const res = await axios.get('api/invoices');
        this.invoices = res.data;
        this.loading = false;

    },

    mounted() {

    },

    methods: {
        async filterData() {
            this.loading = true;
            const response = await axios.post('api/invoicefilter', this.filter);

            this.invoices = response.data;
            this.loading = false;

        },
        daysCalc(maturity) {
            const date1 = new Date(maturity);
            const date2 = Date.now();
            const diffTime = Math.abs(date2 - date1);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            if (date1 >= date2) {
                return diffDays;
            } else {
                return diffDays * -1;
            }

        },

        menuclick(value) {
            this.mobile = value
        },
        async show(uid) {
            this.load = true;
            this.active = "detail";

            const result = this.invoices.find(({
                id
            }) => id === uid);
            this.invoice = result;

            const next = this.processes.find(({
                stage
            }) => stage === result.stage + 1);
            if (next) {
                this.nextStage = next;
            } else {
                this.nextStage = {
                    role: {
                        name: "-"
                    }
                }
            }
            if (result.stage > 1) {
                const prev = this.processes.find(({
                    stage
                }) => stage === result.stage - 1);
                this.previusStage = prev;
            } else {
                this.previusStage = {
                    role: {
                        name: "-"
                    }
                }
            }

            if (result.stage > 0) {
                this.progress = ((result.stage - 1) / this.processes.length) * 100;
            } else {
                this.progress = 0;
            }

            const current = this.processes.find(({
                stage
            }) => stage === result.stage);

            if (current) {
                this.current = current;
            } else {
                this.current = {
                    role: {
                        name: "Admin"
                    }
                }
            }

            this.load = false;
        },
        async approve() {

            try {

                var result = await this.$swal({
                    title: 'Are you sure you want to approve ' + this.invoice.invoice_number,
                    text: "You won't be able to revert this action!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#000',
                    confirmButtonText: 'Yes, confirm !'
                });
                if (result.isConfirmed) {
                    const response = await axios.get('../api/invoice-approved/' + this.invoice.id + '/' + this.staff.role.name + ' approved');
                    if (response.status == 200) {

                        const res = await axios.get('api/invoices');
                        this.invoices = res.data;

                        const result = this.invoices.find(({
                            id
                        }) => id === this.invoice.id);
                        this.invoice = result;

                        const next = this.processes.find(({
                            stage
                        }) => stage === result.stage + 1);
                        if (next) {
                            this.nextStage = next;
                        } else {
                            this.nextStage = {
                                role: {
                                    name: "-"
                                }
                            }
                        }
                        if (result.stage > 1) {
                            const prev = this.processes.find(({
                                stage
                            }) => stage === result.stage - 1);
                            this.previusStage = prev;
                        } else {
                            this.previusStage = {
                                role: {
                                    name: "-"
                                }
                            }
                        }

                        if (result.stage > 0) {
                            this.progress = ((result.stage - 1) / this.processes.length) * 100;
                        } else {
                            this.progress = 0;
                        }

                        const current = this.processes.find(({
                            stage
                        }) => stage === result.stage);

                        if (current) {
                            this.current = current;
                        } else {
                            this.current = {
                                role: {
                                    name: "Admin"
                                }
                            }
                        }

                        this.loading = false;

                        this.$swal(
                            'Confirmed Refresh browser!',
                            response.data,
                            'success'
                        )
                    }

                }

            } catch (e) {
                console.log(e);
                this.$toast.open({
                    message: 'Internal server error',
                    type: 'error',
                    duration: 5000,
                    position: 'bottom-right'
                    // all of other options may go here
                });

                this.loading = false;
            }

        },
        async removeItems(id, index) {
            var result = await this.$swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this invoice",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            });
            if (result.isConfirmed) {
                var response = await axios.delete('../../api/invoices/' + id);
                if (response.status == 200) {

                    this.$swal(
                        'Deleted!',
                        response.data,
                        'success'
                    )
                    const res = await axios.get('api/invoices');
                    this.invoices = res.data;
                }

            }

        },
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

<style lang="scss" scoped>

</style>
