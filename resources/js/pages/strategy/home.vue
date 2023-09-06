<template>
    <v-app>
        <div id="main_content" :class="mobile?'offcanvas-active':''">
            <!-- Start Main top header -->
            <admin-header v-on:childToParent="menuclick"></admin-header>

            <!-- Start Theme panel do not add in project -->

            <!-- Start Quick menu with more functio -->

            <!-- Start Main leftbar navigation -->
            <strategy-sidebar-left link="home"></strategy-sidebar-left>
            <!-- Start project content area -->
            <div class="page">
                <!-- Start Page header -->
                <top-nav></top-nav>
                <!-- Start Page title and tab -->
                <div class="section-body">
                    <div class="container-fluid">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="header-action">
                                <h1 class="page-title">Dashboard</h1>

                            </div>
                            <ul class="nav nav-tabs page-header-tab">
                                <li class="nav-item"><a class="nav-link active" href="#admin-Dashboard">Dashboard</a></li>

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="section-body mt-4">
                    <div class="container-fluid">
                        <div class="row clearfix row-deck">

                            <div class="col-6 col-md-4 col-xl-3">
                                <div class="card" style="background-color: #7895B2 !important;">
                                    <div class="card-body">

                                        <router-link to="/staff-motor" class="my_sort_cut text-muted">
                                            <i style="color:#fff" class="fa fa-address-book"></i>
                                            <span style="color:#fff">Active</span>
                                            <h5 style="color:#fff">{{activeCount|formatNumber}}</h5>
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-xl-3">
                                <div class="card" style="background-color: #A0E4CB;">
                                    <div class="card-body">

                                        <router-link to="/staff-life" class="my_sort_cut text-muted">
                                            <i style="color:#fff" class="fa fa-user-circle-o"></i>
                                            <span style="color:#fff">Request</span>
                                            <h5 style="color:#fff">{{requestCount|formatNumber}}</h5>
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-xl-3">
                                <div class="card" style="background-color: #AEBDCA !important;">
                                    <div class="card-body">

                                        <router-link to="/staff-invoice" class="my_sort_cut text-muted">
                                            <i class="fa fa-folder"></i>
                                            <span>Processing</span>
                                            <h5>{{processingCount|formatNumber}}</h5>
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-xl-3">
                                <div class="card" style="background-color: #E8DFCA !important;">
                                    <div class="card-body">

                                        <router-link to="/staff-engineering" class="my_sort_cut text-muted">
                                            <i class="fa fa-gavel" aria-hidden="true"></i>
                                            <span>Expiring</span>
                                            <h5>{{expiringCount|formatNumber}}</h5>
                                        </router-link>
                                    </div>
                                </div>
                            </div>
                            <!--<div class="col-6 col-md-4 col-xl-4">
                                    <div class="card">
                                        <div class="card-body ribbon">

                                            <router-link to="/staff-transaction" class="my_sort_cut text-muted">
                                                <i  class="fa fa-bullhorn"></i>
                                                <span >Transactions</span>
                                                <h5 >{{transCount|formatNumber}}</h5>
                                            </router-link >
                                        </div>
                                    </div>
                                </div>-->
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="admin-Dashboard" role="tabpanel">
                                <div class="row clearfix row-deck">
                                    <!--<div class="col-xl-8 col-lg-8 col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Transactions</h3>
                                                <div class="card-options">
                                                    <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                                    <div class="item-action dropdown ml-2">
                                                        <a href="javascript:void(0)" data-toggle="dropdown"><i class="fe fe-more-vertical"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-eye"></i> View Details </a>
                                                            <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-share-alt"></i> Share </a>
                                                            <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-cloud-download"></i> Download</a>
                                                            <div class="dropdown-divider"></div>
                                                            <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-copy"></i> Copy to</a>
                                                            <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-folder"></i> Move to</a>
                                                            <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-edit"></i> Rename</a>
                                                            <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-trash"></i> Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="table-responsive" style="height: 310px;">
                                                <line-chart style="height: 100%" :chart-data="purpleLineChart.chartData" :gradient-colors="purpleLineChart.gradientColors" :gradient-stops="purpleLineChart.gradientStops" :extra-options="purpleLineChart.extraOptions">
                                                </line-chart>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Engineering</h3>
                                            </div>
                                            <div class="card-body">
                                                <Doughnut ref="skills_chart" :chart-data="doughnutData" :options="options">
                                                </Doughnut>
                                            </div>
                                        </div>
                                    </div>-->
                                </div>
                                <!--<div class="row clearfix">
                                    <div class="col-xl-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Invoice Report</h3>
                                                <div class="card-options">
                                                    <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                                    <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen"><i class="fe fe-maximize"></i></a>
                                                    <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-sm-flex justify-content-between">
                                                    <div class="font-12 mb-2"><span>Measure How Fast You’re Growing Monthly Recurring Revenue. <a href="#">Learn More</a></span></div>
                                                    <div class="selectgroup w250">
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="intensity" value="low" class="selectgroup-input" checked="">
                                                            <span class="selectgroup-button">1D</span>
                                                        </label>
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="intensity" value="medium" class="selectgroup-input">
                                                            <span class="selectgroup-button">1W</span>
                                                        </label>
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="intensity" value="high" class="selectgroup-input">
                                                            <span class="selectgroup-button">1M</span>
                                                        </label>
                                                        <label class="selectgroup-item">
                                                            <input type="radio" name="intensity" value="veryhigh" class="selectgroup-input">
                                                            <span class="selectgroup-button">1Y</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div id="apex-chart-line-column"></div>
                                            </div>
                                            <div class="card-footer">
                                                <div class="row">
                                                    <div class="col-xl-3 col-md-6 mb-2">
                                                        <div class="clearfix">
                                                            <div class="float-left"><strong>Transactions</strong></div>
                                                            <div class="float-right"><small class="text-muted">35%</small></div>
                                                        </div>
                                                        <div class="progress progress-xs">
                                                            <div class="progress-bar bg-indigo" role="progressbar" style="width: 35%" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <span class="text-uppercase font-10">Compared to last year</span>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 mb-2">
                                                        <div class="clearfix">
                                                            <div class="float-left"><strong>Invoice</strong></div>
                                                            <div class="float-right"><small class="text-muted">61%</small></div>
                                                        </div>
                                                        <div class="progress progress-xs">
                                                            <div class="progress-bar bg-yellow" role="progressbar" style="width: 61%" aria-valuenow="61" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <span class="text-uppercase font-10">Compared to last year</span>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 mb-2">
                                                        <div class="clearfix">
                                                            <div class="float-left"><strong>Life</strong></div>
                                                            <div class="float-right"><small class="text-muted">87%</small></div>
                                                        </div>
                                                        <div class="progress progress-xs">
                                                            <div class="progress-bar bg-green" role="progressbar" style="width: 87%" aria-valuenow="87" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <span class="text-uppercase font-10">Compared to last year</span>
                                                    </div>
                                                    <div class="col-xl-3 col-md-6 mb-2">
                                                        <div class="clearfix">
                                                            <div class="float-left"><strong>Motor</strong></div>
                                                            <div class="float-right"><small class="text-muted">42%</small></div>
                                                        </div>
                                                        <div class="progress progress-xs">
                                                            <div class="progress-bar bg-pink" role="progressbar" style="width: 42%" aria-valuenow="42" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <span class="text-uppercase font-10">Compared to last year</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                        <div class="col-xl-12 col-lg-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class="card-title">Perfomance</h3>
                                                    <div class="card-options">
                                                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                                        <div class="item-action dropdown ml-2">
                                                            <a href="javascript:void(0)" data-toggle="dropdown"><i class="fe fe-more-vertical"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-eye"></i> View Details </a>
                                                                <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-share-alt"></i> Share </a>
                                                                <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-cloud-download"></i> Download</a>
                                                                <div class="dropdown-divider"></div>
                                                                <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-copy"></i> Copy to</a>
                                                                <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-folder"></i> Move to</a>
                                                                <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-edit"></i> Rename</a>
                                                                <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fa fa-trash"></i> Delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="table-responsive" style="height: 310px;">
                                                <BarChart style="height: 100%" :chart-data="purpleLineChart.chartData">
                                                        </BarChart>
                                                </div>

                                            </div>
                                        </div>
                                    </div>-->
                                <div class="row clearfix">
                                    <div class="col-xl-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Last Ten Transactions</h3>

                                            </div>
                                            <div class="card-body">
                                                <div class="d-sm-flex justify-content-between">
                                                    <!--<div class="font-12 mb-2"><span>Measure How Fast You’re Growing Monthly Recurring Revenue. <a href="#">Learn More</a></span></div>-->

                                                </div>
                                                <div id="apex-chart-line-column"></div>
                                            </div>
                                            <div class="card-footer">
                                                <div class="table-responsive">
                                                    <v-data-table :headers="headers" :items="marine" :items-per-page="10" :search="search" class="elevation-1">
                                                        <template #item.index="{ item }">
                                                            {{ marine.indexOf(item) + 1 }}
                                                        </template>

                                                        <template v-slot:item.controls="props">
                                                            <button type="button" @click.prevent="show(props.item.id)" class="btn btn-icon btn-sm" title="show"><i class="fe fe-maximize"></i></button>
                                                            <button type="button" @click.prevent="showEdit(props.item.id)" class="btn btn-icon btn-sm" title="Edit"><i class="fa fa-edit"></i></button>

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
                                                    </v-data-table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--<div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Life</h3>
                                            <div class="card-options">
                                                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                                <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen"><i class="fe fe-maximize"></i></a>
                                                <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-striped mb-0 text-nowrap">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Name</th>
                                                            <th>Assigned Professor</th>
                                                            <th>Date Of Admit</th>
                                                            <th>Fees</th>
                                                            <th>Branch</th>
                                                            <th>Edit</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>Jens Brincker</td>
                                                            <td>Kenny Josh</td>
                                                            <td>27/05/2016</td>
                                                            <td>
                                                                <span class="tag tag-success">paid</span>
                                                            </td>
                                                            <td>Mechanical</td>
                                                            <td>
                                                                <a href="javascript:void(0)"><i class="fa fa-check"></i></a>
                                                                <a href="javascript:void(0)"><i class="fa fa-trash"></i></a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>2</td>
                                                            <td>Mark Hay</td>
                                                            <td> Mark</td>
                                                            <td>26/05/2018</td>
                                                            <td>
                                                                <span class="tag tag-warning">unpaid</span>
                                                            </td>
                                                            <td>Science</td>
                                                            <td>
                                                                <a href="javascript:void(0)"><i class="fa fa-check"></i></a>
                                                                <a href="javascript:void(0)"><i class="fa fa-trash"></i></a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>3</td>
                                                            <td>Anthony Davie</td>
                                                            <td>Cinnabar</td>
                                                            <td>21/05/2018</td>
                                                            <td>
                                                                <span class="tag tag-success ">paid</span>
                                                            </td>
                                                            <td>Commerce</td>
                                                            <td>
                                                                <a href="javascript:void(0)"><i class="fa fa-check"></i></a>
                                                                <a href="javascript:void(0)"><i class="fa fa-trash"></i></a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>4</td>
                                                            <td>David Perry</td>
                                                            <td>Felix </td>
                                                            <td>20/04/2019</td>
                                                            <td>
                                                                <span class="tag tag-danger">unpaid</span>
                                                            </td>
                                                            <td>Mechanical</td>
                                                            <td>
                                                                <a href="javascript:void(0)"><i class="fa fa-check"></i></a>
                                                                <a href="javascript:void(0)"><i class="fa fa-trash"></i></a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>5</td>
                                                            <td>Anthony Davie</td>
                                                            <td>Beryl</td>
                                                            <td>24/05/2017</td>
                                                            <td>
                                                                <span class="tag tag-success ">paid</span>
                                                            </td>
                                                            <td>M.B.A.</td>
                                                            <td>
                                                                <a href="javascript:void(0)"><i class="fa fa-check"></i></a>
                                                                <a href="javascript:void(0)"><i class="fa fa-trash"></i></a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>-->
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
    import LineChart from "../../components/LineChart.js";
    //import BarChart from "../../components/BarChart";

    import Doughnut from '../../components/doughnutChart.js'
    import * as chartConfigs from "../../components/config.js"

    const config = {
        colors: {
            default: "#344675",
            primary: "#42b883",
            info: "#1d8cf8",
            danger: "#fd5d93",
            teal: "#00d6b4",
            primaryGradient: ['rgba(76, 211, 150, 0.1)', 'rgba(53, 183, 125, 0)', 'rgba(119,52,169,0)'],
        }
    }

    const months = ['JAN', 'FEB', 'MARCH', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];

    export default {
        name: 'WorkspaceJsonHome',
        components: {
            LineChart,
            Doughnut,
            //BarChart,
        },

        data() {
            return {
                options: {
                    responsive: true,

                    maintainAspectRatio: false,
                    animation: {
                        animateRotate: false
                    }
                },
                search: "",
                search: "",
                mobile: false,
                processingCount: 0,
                staffCount: 0,
                expiringCount: 0,
                requestCount: 0,
                transCount: 0,
                activeCount: 0,
                limit: 0,
                marine: [],
                purpleLineChart: {
                    extraOptions: chartConfigs.purpleChartOptions,
                    chartData: {
                        labels: months,
                        datasets: [{
                            label: "Data",
                            fill: true,
                            borderColor: config.colors.primary,
                            borderWidth: 2,
                            borderDash: [],
                            borderDashOffset: 0.0,
                            pointBackgroundColor: config.colors.primary,
                            pointBorderColor: 'rgba(255,255,255,0)',
                            pointHoverBackgroundColor: config.colors.primary,
                            pointBorderWidth: 20,
                            pointHoverRadius: 4,
                            pointHoverBorderWidth: 15,
                            pointRadius: 4,
                            data: Array(months.length).fill().map(() => Math.round(Math.random() * 200)),
                        }]
                    },

                    gradientColors: config.colors.primaryGradient,
                    gradientStops: [1, 0.2, 0],
                },

                doughnutData: {
                    labels: ['Success', 'failed'],
                    datasets: [{
                        backgroundColor: ['rgb(160, 228, 203)', 'rgb(232, 223, 202)', ],
                        data: [40, 15]
                    }]
                },
                headers: [{
                    value: 'index',
                    text: '#',
                    sortable: false,
                },
                {
                    text: 'customer',
                    value: 'customer.full_name',
                },
                {
                    text: 'Type',
                    value: 'insurance_type.name',
                },

                {
                    text: 'Region',
                    value: 'customer.region.name',
                },

                {
                    text: 'Payment',
                    value: 'payment_mode',
                },


                {
                    text: 'Vehicle',
                    value: 'registration_number',
                },

                {
                    text: 'Premium',
                    value: 'premium',
                },

                {
                    text: 'Status',
                    value: 'stat',
                },


                {
                    text: 'Created At',
                    value: 'join'
                },

                {
                    text: '',
                    value: 'action'
                },

            ],

            };
        },

        async beforeMount() {
            this.user = JSON.parse(localStorage.getItem('user'));

            const stat = await axios.get('/api/admin-stat');

            this.processingCount = stat.data.invoice;
            this.transCount = stat.data.trans;
            this.expiringCount = stat.data.engineering;
            this.requestCount = stat.data.life;
            this.staffCount = stat.data.staff;
            this.activeCount = stat.data.motor;

            const marine = await axios.get('/api/admin-latest-invoice');
            this.marine = marine.data;

        },

        mounted() {

        },

        methods: {
            menuclick(value) {
                this.mobile = value
            },
        },
    };
    </script>
