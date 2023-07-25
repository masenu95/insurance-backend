<template>
    <v-app>
      <div id="main_content" :class="mobile?'offcanvas-active':''">
        <!-- Start Main top header -->
        <admin-header v-on:childToParent="menuclick"></admin-header>
        <!-- Start Rightbar setting panel -->

        <!-- Start Main leftbar navigation -->
        <sidebar-left link="home"></sidebar-left>
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
                  <h1 class="page-title">Bids</h1>
                  <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item">
                      <router-link to="admin-home">BimaKwik</router-link>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                     Bids
                    </li>
                  </ol>
                </div>
                <ul class="nav nav-tabs page-header-tab">
                  <li class="nav-item">
                    <a
                      class="nav-link"
                      :class="active=='all'?'active':''"
                      @click.prevent="active='all'"
                      >List View</a
                    >
                  </li>


                  <li class="nav-item">
                    <a class="nav-link" :class="active=='detail'?'active':''"
                      >Detail</a
                    >
                  </li>

                </ul>
              </div>
            </div>
          </div>
          <div class="section-body mt-4">
            <div class="container-fluid">
              <div class="tab-content">
                <div class="tab-pane" :class="active=='all'?'active':''">
                  <div class="filter">
                    <button @click="display=!display">
                      <i class="fa fa-filter" aria-hidden="true"></i>Filter
                    </button>
                    <br />
                    <div class="form-search" v-if="display">
                      <form @submit.prevent="filterData">
                        <div class="form-row">
                          <div class="col form-table">
                            <span class="label">Invoice Number</span>
                            <input
                              type="text"
                              class="form-control input"
                              v-model="filter.invoice"
                              placeholder="Invoice Number"
                            />
                          </div>

                          <div class="col form-table">
                            <span class="label">Borrower</span>
                            <input
                              type="text"
                              class="form-control input"
                              v-model="filter.borrower"
                              placeholder="Borrower"
                            />
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="col form-table">
                            <span class="label">Invested amount Start</span>
                            <input
                              type="text"
                              class="form-control input"
                              v-model="filter.investedStart"
                              placeholder="Invested amount Start"
                            />
                          </div>

                           <div class="col form-table">
                            <span class="label">Invested amount End</span>
                            <input
                              type="text"
                              class="form-control input"
                              v-model="filter.investedEnd"
                              placeholder="Invested amount End"
                            />
                          </div>
                        </div>
                      <!--  <div class="form-row">
                          <div class="col form-table">
                            <span class="label">Profit amount Start</span>
                            <input
                              type="text"
                              class="form-control input"
                              v-model="filter.profitStart"
                              placeholder="Profit amount Start"
                            />
                          </div>

                           <div class="col form-table">
                            <span class="label">Profit amount End</span>
                            <input
                              type="text"
                              class="form-control input"
                              v-model="filter.profitEnd"
                              placeholder="Profit amount End"
                            />
                          </div>
                        </div>-->
                        <div class="form-row">
                          <div class="col form-table">
                            <span class="label">From</span>
                            <input
                              type="date"
                              class="form-control input"
                              v-model="filter.from"
                            />
                          </div>

                          <div class="col form-table">
                            <span class="label">To</span>
                            <input
                              type="date"
                              class="form-control input"
                              v-model="filter.to"
                            />
                          </div>
                        </div>
                             <div class="form-row">
                          <div class="col form-table">
                            <span class="label">Maturity From</span>
                            <input
                              type="date"
                              class="form-control input"
                              v-model="filter.maturityFrom"
                            />
                          </div>

                          <div class="col form-table">
                            <span class="label">Maturity To</span>
                            <input
                              type="date"
                              class="form-control input"
                              v-model="filter.maturityTo"
                            />
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="col form-table">
                            <span class="label">Payout Amount Start</span>
                            <input
                              type="text"
                              class="form-control input"
                              v-model="filter.payoutStart"
                            />
                          </div>

                          <div class="col form-table">
                            <span class="label">Payout Amount End</span>
                            <input
                              type="text"
                              class="form-control input"
                              v-model="filter.payoutEnd"
                            />
                          </div>
                        </div>

                        <div class="form-row">
                          <div class="col form-table">
                            <span class="label">Status</span>
                            <select

                              class="form-control input"
                              v-model="filter.status"

                            >
                            <option value="">Status</option>
                            </select>
                          </div>

                          <div class="col form-table">
                            <span class="label">Rate</span>
                            <input
                              type="text"
                              class="form-control input"
                              v-model="filter.rate"
                              placeholder="Rate"
                            />
                          </div>
                        </div>
                        <div class="form-row">
                          <button
                            type="submit"
                            class="btn btn-primary btn-sm"
                            v-if="!loading"
                          >
                            <i class="fas fa-sync-alt"></i>&nbsp;Refresh
                          </button>
                          <button
                            class="btn btn-primary btn-sm"
                            v-else
                            type="button"
                            disabled
                          >
                            <span
                              class="spinner-grow spinner-grow-sm"
                              role="status"
                              aria-hidden="true"
                            ></span>
                            Loading...
                          </button>
                        </div>
                      </form>
                    </div>
                  </div>
                  <div class="card">
                    <ul class="header-dropdown">
                                    <li>
                                     <download-excel
                                          class="btn btn-info"
                                          :data="bids"
                                          :fields="label"
                                          worksheet="My Worksheet"
                                          name="MyBids.xls"
                                          style="color:#fff"
                                      ><i class="fas fa-cloud-download-alt" style="color:#fff"></i> &nbsp;Download Excel
                                      </download-excel></li></ul>
                    <div class="table-responsive">
                      <v-data-table
                        :headers="headers"
                        :items="bids"
                        :items-per-page="10"
                        :search="search"
                        class="elevation-1"
                      >
                        <template #item.index="{ item }" >
                          {{ bids.indexOf(item) + 1 }}
                        </template>
                        <template v-slot:item.controls="props" >

                                  </template>

                        <template v-slot:item.stat="props">
                          <span
                            class="badge alert-success"
                            v-if="props.item.status=='ACTIVE'"
                            >{{props.item.status}}</span
                          >
                          <span
                            class="badge alert-danger"
                            v-else
                            >{{props.item.status}}</span
                          >
                        </template>

                        <template v-slot:item.join="props">
                          {{props.item.created_at | formatDate}}
                        </template>

                        <template v-slot:item.maturity="props">
                          {{props.item.invoice.maturity_date | formatDate}}
                        </template>
                        <template v-slot:item.remain="props">
                            <span v-if="daysCalc(props.item.invoice.maturity_date ) >= 0">
                                {{daysCalc(props.item.invoice.maturity_date )| formatNumber}}
                            </span>
                            <span
                            class="badge alert-danger"
                            v-else
                            >Matured</span
                          >
                        </template>

                        <template v-slot:item.invested="props">

                          {{props.item.amount | formatNumber}}
                        </template>
                        <template v-slot:item.profit="props">

                          {{(parseInt(props.item.payout_amount)-parseInt(props.item.amount)) | formatNumber}}
                        </template>
                        <template v-slot:item.payout="props">

                          {{props.item.payout_amount | formatNumber}}
                        </template>
                      </v-data-table>
                    </div>
                  </div>
                </div>
                <div class="tab-pane" :class="active=='detail'?'active':''">
                  <div class="row" v-if="active=='detail'&&load==false">
                    <div class="col-xl-12 col-md-12">
                      <div class="card">
                        <div class="card-body w_user">
                          <div class="wid-u-info" style="text-align: center">
                            <h5>{{this.bid.bid_no.toUpperCase()}}</h5>
                          </div>
                        </div>
                      </div>
                      <div class="card">
                        <div class="card-header">
                          <h3 class="card-title">About</h3>
                        </div>
                        <div class="card-body">
                          <ul class="list-group">
                            <li class="list-group-item">
                              <b>Bid Number </b>
                              <div class="profile-desc-item pull-right">
                                {{this.bid.bid_no.toUpperCase()}}
                              </div>
                            </li>
                            <li class="list-group-item">
                              <b>Owning Company</b>
                              <div class="profile-desc-item pull-right">
                                {{bid.invoice.company.name.toUpperCase()}}
                              </div>
                            </li>
                            <li class="list-group-item">
                              <b>Borrower</b>
                              <div class="profile-desc-item pull-right">
                                {{bid.invoice.borrower.user.name.toUpperCase()}}
                              </div>
                            </li>

                            <li class="list-group-item">
                              <b>Invoice Date</b>
                              <div class="profile-desc-item pull-right">
                                {{bid.invoice.invoice_date|formatDate}}
                              </div>
                            </li>
                            <li class="list-group-item">
                              <b>Maturity date </b>
                              <div class="profile-desc-item pull-right">
                                {{bid.invoice.maturity_date|formatDate}}
                              </div>
                            </li>

                            <li class="list-group-item">
                              <b>Value</b>
                              <div class="profile-desc-item pull-right">
                                {{bid.invoice.currency}}
                                {{bid.invoice.amount|formatNumber}}
                              </div>
                            </li>

                            <li class="list-group-item">
                              <b>Invoice Status</b>
                              <div class="profile-desc-item pull-right">
                                <span
                                  v-if="bid.invoice.invoice_status =='ACTIVE'"
                                  class="badge alert-success"
                                  >ACTIVE</span
                                >
                                <span v-else class="badge alert-danger"
                                  >PENDING</span
                                >
                              </div>
                            </li>
                          </ul>
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
          return {
              mobile:false,
              bids:[],
              load:false,
              bid:[],
              search:"",
              balance:0,
              loading:false,
              bidsAmount:0,
              availabeAmount:0,
              active:"all",
              int:1,

              display:false,
              filter:{
               invoice:"",
               borrower:"",
               investedStart:"",
               investedEnd:"",
               rate:null,
               from:null,
               to:null,
               maturityFrom:null,
               maturityTo:null,
               payoutStart:"",
               payoutEnd:"",
               profitStart:"",
               profitEnd:"",
               amountStart:"",
               amountEnd:"",
               status:""
              },

              companies:[],
              errors:[],
              errorsEdit:[],
              label:{
                      "Invoice number":"invoice.invoice_number",
                      "Owning company":"company.name",
                      "Borrower":"invoice.borrower.user.name",
                      "investor":"investor.user.name",
                      "Invoice Date":"invoice.invoice_date",
                      "Status":"status",

                      "Amount":"amount",
                      "Payout":"payout_amount",
                  },

              headers: [
                      {
                          value: 'index',
                          text: '#',
                           sortable: false,
                      },
                        {
                          text: 'Invoice number',
                          value: 'invoice.invoice_number',
                      },   {
                          text: 'Borrower',
                          value: 'invoice.borrower.user.name',
                      },
                          {
                          text: 'Bid number',
                          value: 'id',
                      },
                      {
                          text: 'TIN',
                          value: 'TIN',
                      },
                      {
                          text: 'Investor',
                          value: 'investor.user.name',
                      },
                          {
                          text: 'Invested Amount',
                          value: 'invested',
                      },
                          {
                          text: 'Bid rate(%)',
                          value: 'interest_rate',
                      },
                        {
                          text: 'Profit',
                          value: 'profit',
                      },
                        {
                          text: 'Payout',
                          value: 'payout',
                      },
                           {
                          text: 'Maturity Date',
                          value: 'maturity',
                      },
                           {
                          text: 'Remain days',
                          value: 'remain',
                      },



                      { text: 'Created At', value: 'join' },
                       { text: 'Status', value: "stat" },

                  ],
          };
      },

      async beforeMount(){
                  this.user = JSON.parse(localStorage.getItem('user'));
                  const response = await axios.get('api/companies');


this.companies = response.data;
          const res = await axios.get('api/bid');
               this.bids = res.data;

      },

      mounted() {

      },
      computed:{

      },

      methods: {
               menuclick (value) {
                      this.mobile = value
                  },
            daysCalc(maturity){
                  const date1 = new Date(maturity);
              const date2 = Date.now();
              const diffTime = Math.abs(date2 - date1);
              const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
              if(date1>=date2){
                return diffDays;
              }else{
                return diffDays*-1;
              }

          },


          async show(uid){
               this.load = true;
              this.active = "detail";

              const result = this.bids.find( ({ id }) => id === uid );
              this.bid = result;



              this.availabeAmount = result.discount_amount;


              this.load = false;
          },





             async filterData(){
              this.loading=true;
              const response = await axios.post('api/bid-filter',this.filter);

          this.bids = response.data;
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

  <style lang="scss" scoped></style>
