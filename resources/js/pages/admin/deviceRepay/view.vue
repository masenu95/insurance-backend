<template>
    <v-app>
        <div id="main_content" :class="mobile?'offcanvas-active':''">
    <!-- Start Main top header -->
<admin-header v-on:childToParent="menuclick"></admin-header>
    <!-- Start Rightbar setting panel -->

    <!-- Start Main leftbar navigation -->
       <sidebar-left link="repay"></sidebar-left>
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
                        <h1 class="page-title">Bid Repay</h1>
                        <ol class="breadcrumb page-breadcrumb">
                            <li class="breadcrumb-item"><router-link to="admin-home">BimaKwik</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">Bid Repay</li>
                        </ol>
                    </div>
                    <ul class="nav nav-tabs page-header-tab">
                        <li class="nav-item"><a class="nav-link " :class="active=='all'?'active':''"   @click.prevent="active='all'">List View</a></li>

                        <li class="nav-item"><a class="nav-link" :class="active=='detail'?'active':''"  >Detail</a></li>
                        <li class="nav-item"><a class="nav-link" :class="active=='bid'?'active':''"  >Detail</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="section-body mt-4">
            <div class="container-fluid">
                <div class="tab-content">



                         <div class="tab-pane active">
                             <div class="row" >
                            <div class="col-xl-12 col-md-12">
                                <div class="card">
                                    <div class="card-body w_user">

                                    <div  class="wid-u-info" style="text-align:center">
                                    Invoice no:   <h5>{{this.invoice.invoice.invoice_number.toUpperCase()}}</h5>

                                    </div>
                                    <div  class="wid-u-info" style="text-align:center">
                                    Available:  <h5>{{this.invoice.invoice.available |formatNumber}}</h5>

                                    </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">About</h3>

                                    </div>
									<div class="card-body">
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" :style="'width:'+progress+'%'" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">{{Math.round(progress)+'%'}}</div>
                                        </div>

										<ul class="list-group">
											<li class="list-group-item">
												<b>Invoice Number </b>
												<div class="profile-desc-item pull-right">{{invoice.invoice.invoice_number.toUpperCase()}}</div>
											</li>
                                            <li class="list-group-item">
												<b>Owning Company</b>
												<div class="profile-desc-item pull-right">{{invoice.invoice.company.name.toUpperCase()}}</div>
											</li>
                                            <li class="list-group-item">
												<b>Borrower</b>
												<div class="profile-desc-item pull-right">{{invoice.invoice.borrower.user.name.toUpperCase()}} </div>
											</li>

                                             <li class="list-group-item">
												<b>Invoice Date</b>
												<div class="profile-desc-item pull-right">{{invoice.invoice.invoice_date|formatDate}}</div>
											</li>
                                             <li class="list-group-item">
												<b>Maturity date </b>
												<div class="profile-desc-item pull-right">{{invoice.invoice.maturity_date|formatDate}}</div>
											</li>
                                            <li class="list-group-item">
												<b>Amount</b>
												<div class="profile-desc-item pull-right">{{invoice.invoice.currency}} {{invoice.invoice.amount|formatNumber}}</div>
											</li>
                                            <li class="list-group-item">
												<b>Percentage discount </b>
												<div class="profile-desc-item pull-right">{{invoice.invoice.discount_perc}}</div>
											</li>
                                              <li class="list-group-item">
												<b>Discount amount</b>
												<div class="profile-desc-item pull-right">{{invoice.invoice.currency}} {{(invoice.invoice.amount - invoice.invoice.discount_amount)|formatNumber}}</div>
											</li>
                                               <li class="list-group-item">
												<b>Amount after discount</b>
												<div class="profile-desc-item pull-right">{{invoice.invoice.currency}} {{invoice.invoice.discount_amount|formatNumber}}</div>
											</li>
                                            <li class="list-group-item">
												<b>Rate</b>
												<div class="profile-desc-item pull-right">{{invoice.invoice.interest_rate}}</div>
											</li>
                                                  <li class="list-group-item">
												<b>Previous Stage</b>
												<div class="profile-desc-item pull-right"><span class="badge alert-secondary">{{invoice.previous}}</span></div>
											</li>

                                            <li class="list-group-item">
												<b>Current Stage</b>
												<div class="profile-desc-item pull-right"><span class="badge alert-success">{{invoice.current}}</span></div>
											</li>

                                               <li class="list-group-item">
												<b>Next Stage</b>
												<div class="profile-desc-item pull-right"><span class="badge alert-danger">{{invoice.next}}</span></div>
											</li>







                                             <li class="list-group-item">
												<b>Status</b>
												<div class="profile-desc-item pull-right"><span class="badge alert-primary">{{invoice.status}}</span></div>
											</li>

                                            <li class="list-group-item">
												<b>View</b>
												<div class="profile-desc-item pull-right">

                                                    <a :href="JSON.parse(invoice.invoice.images).url" class="badge alert-info"> View Invoice</a>
                                                    </div>
											</li>








                                        </ul>

                                    </div>

                                     <div class="card-footer text-center">
                                        <div class="row">
											<button class="col-md-12 col-sm-12 col-12 btn btn-primary" @click.prevent="approve" v-if="invoice.status != 'ACTIVE'">

												<div>Approve</div>
											</button>

										</div>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <h6>Bids</h6>
                        <ul class="header-dropdown">
                                    <li>
                                     <download-excel
                                          class="btn btn-info"
                                          :data="inv.bids"
                                          :fields="label"
                                          worksheet="My Worksheet"
                                          name="Bids.xls"
                                          style="color:#fff"
                                      ><i class="fas fa-cloud-download-alt" style="color:#fff"></i> &nbsp;Download Excel
                                      </download-excel></li></ul>
                        <v-data-table
                        :headers="bidHeaders"
                        :items="inv.bids"
                        :items-per-page="10"
                        :search="search"
                        class="elevation-1"
                      >
                        <template #item.index="{ item }" >
                          {{ inv.bids.indexOf(item) + 1 }}
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
                          {{inv.maturity_date | formatDate}}
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

                        <template v-slot:item.availabl="props">

                          {{inv.available | formatNumber}}
                        </template>
                        <template v-slot:item.number="props">

                            {{inv.invoice_number | formatNumber}}
                        </template>
                        <template v-slot:item.profits="props">

                          {{props.item.profit | formatNumber}}
                        </template>
                        <template v-slot:item.gprofits="props">

                          {{parseInt(props.item.profit)+parseInt(props.item.withholding_amount) | formatNumber}}
                        </template>
                        <template v-slot:item.payout="props">

                          {{props.item.payout_amount | formatNumber}}
                        </template>
                        <template v-slot:item.withholdingAmount="props">

                          {{props.item.withholding_amount | formatNumber}}
                        </template>

                        <template v-slot:item.payoutAfterTax="props">

                          {{props.item.payout_after_tax| formatNumber}}
                        </template>


                      </v-data-table>
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
        return {search:"",search:"",
            invoices:[],
            load:false,
            mobile:false,
            loading:false,
            invoice:[],
            repayStage:[],
            search:"",
            inv:"",
            active:"all",
            int:1,
            form:{
                invoice_number:"",
                invoice_date:"",
                company:"",
                maturity_date:"",
                amount:"",
                currency:"TZS",
                model:"OPEN",
                image:"",
                percent:80,
                final:0,

            },
             formEdit:{
                invoice_number:"",
                invoice_date:"",
                company:"",
                maturity_date:"",
                amount:"",
                currency:"TZS",
                model:"OPEN",
                image:"",
                percent:80,
                final:0,
                _method: 'patch'

            },
             dropzoneOptions: {
                url: '../../../../api/uploadInvoice',
                thumbnailWidth: 200,
                addRemoveLinks: true,
                dictDefaultMessage: "<i class='fa fa-cloud-upload upload-icon'></i></i>Drag and drop a file here or click"
            },
            companies:[],
            errors:[],
            errorsEdit:[],
            progress:0,
            id:null,
            previusStage:null,
            nextStage:null,
              process:[],
            processes:[],
            display:false,
               filter:{
                company:"",
                invoice:"",
                borrower:"",
                maturityStart:"",
                maturityEnd:"",
                amountStart:null,
                amountEnd:null,
                status:"",
                from:null,
                to:null,
            },
            flow:[],




                bidHeaders: [
                      {
                          value: 'index',
                          text: '#',
                           sortable: false,
                      },
                        {
                          text: 'Invoice number',
                          value: 'number',
                      },
                          {
                          text: 'Bid number',
                          value: 'bid_no',
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
                          text: 'Avaiable',
                          value: 'availabl',
                      },
                          {
                          text: 'Bid rate(%)',
                          value: 'interest_rate',
                      },
                      {
                          text: 'Profit',
                          value: 'gprofits',
                      },
                      {
                          text: 'withholding tax',
                          value: 'withholdingAmount',
                      },
                        {
                          text: 'Net Profit',
                          value: 'profits',
                      },

                        {
                          text: 'Payout',
                          value: 'payout',
                      },


                      {
                          text: 'Payout After Tax',
                          value: 'payoutAfterTax',
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
        const id = this.$route.params.id;
        this.id = id;
        const res = await axios.get('../../api/device-bid-repay/'+id);

              const staff = await axios.get('../../../../api/active-staff');
         const results = await axios.get('../../api/flow-process/'+5+'/'+staff.data.role_id);


           const resp = await axios.get('../../api/flow-by-process/'+5);
           this.processes = resp.data;

           const inv = await axios.get('../../api/invoices/'+res.data.invoice_id);
           this.inv =inv.data;



        this.process = results.data;

        this.staff = staff.data;

        this.load = true;
            this.active = "detail";

            const result = res.data;
            this.invoice = result;









            this.load = false;

    },

    mounted() {

    },

    methods: {
        async filterData(){
            this.loading=true;
            const response = await axios.post('../../api/invoicefilter',this.filter);

        this.invoices = response.data;
        this.loading=false;


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

       menuclick (value) {
                    this.mobile = value
                },
         async show(uid){

        },

              async approve(){


             try{

            var result = await this.$swal({
            title: 'Are you sure you want to approve disbursement of invoice no: '+this.invoice.invoice.invoice_number,
            text: "You won't be able to revert this action!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#000',
            confirmButtonText: 'Yes, confirm !'
        });
        if (result.isConfirmed) {
                const response = await axios.get('../../../api/device-bid-repay-approve/'+this.invoice.id+'/'+' approved');
                if(response.status == 200){
            this.loading = false;

                 this.$swal(
                'Confirmed Refresh browser!',
                response.data,
                'success'
            );
            this.$router.push('../../device-repay');

          }

        }

            }catch(e){
                    console.log(e);
                    this.$toast.open({
                            message: 'Internal server error',
                            type: 'error',
                            duration: 5000,
                            position: 'bottom-right'
                            // all of other options may go here
                        });

                 this.loading=false;
            }

        },
      async removeItems(id,index){
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
          var response = await axios.delete('../../../../api/invoices/'+id);
          if(response.status == 200){

                this.$swal(
                'Deleted!',
                response.data,
                'success'
            )
             const res = await axios.get('../../api/invoices');
             this.invoices = res.data;
          }

        }

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

<style lang="scss" scoped>

</style>
