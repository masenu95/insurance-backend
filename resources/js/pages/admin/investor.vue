<template>
    <v-app>
        <div id="main_content" :class="mobile?'offcanvas-active':''">
    <!-- Start Main top header -->
<admin-header v-on:childToParent="menuclick"></admin-header>

    <!-- Start Theme panel do not add in project -->

    <!-- Start Quick menu with more functio -->

    <!-- Start Main leftbar navigation -->
       <sidebar-left link="home"></sidebar-left>
    <!-- Start project content area -->
    <div class="page">
        <!-- Start Page header -->
        <top-nav></top-nav>
        <!-- Start Page title and tab -->
        <div class="section-body">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="header-action">
                        <h1 class="page-title">Investors</h1>
                        <ol class="breadcrumb page-breadcrumb">
                            <li class="breadcrumb-item"><a href="#">BimaKwik</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Investors</li>
                        </ol>
                    </div>
                    <ul class="nav nav-tabs page-header-tab">
                        <li class="nav-item"><a class="nav-link" :class="active=='all'?'active':''" @click.prevent="active='all'">Investors</a></li>
                        <li class="nav-item"><a class="nav-link" :class="active=='detail'?'active':''">Details</a></li>

                    </ul>
                </div>
            </div>
        </div>

         <div class="section-body mt-4">
            <div class="container-fluid">
                <div class="tab-content">
                    <div class="tab-pane " :class="active=='all'?'active':''">
                        <div class="filter">
                  <button @click="display=!display">
                    <i class="fa fa-filter" aria-hidden="true"></i>Filter
                  </button>
                  <br />
                  <div class="form-search" v-if="display">
                    <form @submit.prevent="filterData">
                      <div class="form-row">
                        <div class="col form-table">
                          <span class="label">Name</span>
                          <input
                            type="text"
                            class="form-control input"
                            v-model="filter.name"
                            placeholder="Borrower Name"
                          />
                        </div>

                        <div class="col form-table">
                          <span class="label">Email</span>
                          <input
                            type="text"
                            class="form-control input"
                            v-model="filter.email"
                            placeholder="Email"
                          />
                        </div>
                      </div>



                      <div class="form-row">
                        <div class="col form-table">
                          <span class="label">Phone</span>
                          <input type="text"

                            class="form-control input"
                            v-model="filter.phone"

                          >

                        </div>
                        <div class="col form-table">
                          <span class="label">Status</span>
                          <select

                            class="form-control input"
                            v-model="filter.status"

                          >
                          <option value="">Status</option>
                          <option value="ACTIVE">ACTIVE</option>
                          <option value="PENDING">PENDING</option>
                          </select>
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
                            <div class="table-responsive">
                                  <v-data-table
                                :headers="headers"
                                :items="investors"
                                :items-per-page="10"



                                class="elevation-1">
                                <template #item.index="{ item }">
                                    {{ investors.indexOf(item) + 1 }}
                                </template>
                                 <template v-slot:item.controls="props">
                                      <button type="button" @click.prevent="show(props.item.id)" class="btn btn-icon btn-sm" title="show"><i class="fe fe-maximize"></i></button>
                                </template>


                                   <template v-slot:item.stat="props">

                                   <span class="badge alert-success" v-if="props.item.user.status=='ACTIVE'">{{props.item.user.status}}</span>
                                   <span class="badge alert-danger" v-else>{{props.item.user.status}}</span>


                                </template>
                                     <template v-slot:item.join="props">
                                        {{props.item.created_at | formatDate}}
                                    </template>
                            </v-data-table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" :class="active=='detail'?'active':''">
                             <div class="row"  v-if="active=='detail'&&load==false">
                            <div class="col-xl-12 col-md-12">
                                <div class="card">
                                    <div class="card-body w_user">
                                        <div class="user_avtar">
                                         <img class="rounded-circle" :src="'/images/'+JSON.parse(this.investor.user.image).image" alt="">
                                     </div>
                                        <div  class="wid-u-info" style="text-align:center">
                                            <h5>{{investor.user.name.toUpperCase()}}</h5>

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
												<b>Email </b>
												<div class="profile-desc-item pull-right">{{investor.user.email}}</div>
											</li>
                                            <li class="list-group-item">
												<b>Phone</b>
												<div class="profile-desc-item pull-right">{{investor.user.phone}}</div>
											</li>
                                            <li class="list-group-item">
												<b>Plan </b>
												<div class="profile-desc-item pull-right">{{investor.plan}}</div>
											</li>

                                             <li class="list-group-item">
												<b>id Type </b>
												<div class="profile-desc-item pull-right">{{investor.id_type}}</div>
											</li>
                                             <li class="list-group-item">
												<b>Id number </b>
												<div class="profile-desc-item pull-right">{{investor.id_no}}</div>
											</li>
                                            <li class="list-group-item">
												<b>Physical Address</b>
												<div class="profile-desc-item pull-right">{{investor.physical}}</div>
											</li>
                                            <li class="list-group-item">
												<b>Monthly income </b>
												<div class="profile-desc-item pull-right">{{investor.month_income}}</div>
											</li>

                                             <li class="list-group-item">
												<b>Register as </b>
												<div class="profile-desc-item pull-right">{{investor.register_as}}</div>
											</li>



                                             <li class="list-group-item">
												<b>Previous Stage</b>
												<div class="profile-desc-item pull-right"><span class="badge alert-secondary">{{previusStage.role.name}}</span></div>
											</li>

                                            <li class="list-group-item">
												<b>Current Stage</b>
												<div class="profile-desc-item pull-right"><span class="badge alert-success">{{current.role.name}}</span></div>
											</li>

                                               <li class="list-group-item">
												<b>Next Stage</b>
												<div class="profile-desc-item pull-right"><span class="badge alert-danger">{{nextStage.role.name}}</span></div>
											</li>

                                             <li class="list-group-item">
												<b>Status</b>
												<div class="profile-desc-item pull-right"><span class="badge alert-primary">{{investor.user.status}}</span></div>
											</li>

                                               <li class="list-group-item">
												<b>Id Card</b>
												<div class="profile-desc-item pull-right"><a :href="JSON.parse(investor.card).url" class="badge alert-warning">download</a></div>
											</li>





                                        </ul>

                                    </div>
                                       <div class="card-footer text-center">
                                       <div class="row">
											<button class="col-md-12 col-sm-12 col-12 btn btn-primary" @click.prevent="approve" v-if="current.role.name.toUpperCase() == 'ADMIN'&& investor.user.status.toUpperCase() != 'ACTIVE'">

												<div>Approve</div>
											</button>

										</div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>


            </div>
         </div>

     <footer-component></footer-component>
    </div>
</div>

 </v-app>
</template>


<script>
export default {
    name: 'WorkspaceJsonInvestor',

    data() {
        return {search:"",search:"",
            investors:[],
            investor:[],
            process:[],
            processes:[],
            staff:[],
            flow:[],
            active:'all',
            mobile:false,
            current:[],
            progress:0,
            previusStage:null,
            display:false,
            filter:{
             name:"",
             phone:"",
             email:"",
             status:""
            },
            nextStage:null,
            transHeaders: [
                    {
                        value: 'index',
                        text: '#',
                         sortable: false,
                    },
                          {
                        text: 'Transaction Id',
                        value: 'transactionId',
                    },
                      {
                        text: 'Mobile',
                        value: 'mobile',
                    },
                        {
                        text: 'Bank',
                        value: 'bank',
                    },
                        {
                        text: 'Reference',
                        value: 'reference',
                    },
                         {
                        text: 'Service',
                        value: 'service',
                    },
                           {
                        text: 'Channel',
                        value: 'channel',
                    },
                        {
                        text: 'Status',
                        value: 'stat',
                    },
                    {
                        text: 'Amount',
                        value: 'amou',
                    },
                    { text: 'Created At', value: 'join' },
                   // { text: 'Action', value: 'actn',},


                ],
                bidHeaders: [
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
                          value: 'bid_no',
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
                label:{
                      "Transaction Id":"transactionId",
                      "Mobile":"mobile",
                      "Bank":"bank",
                      "Name":"user.name",
                      "Reference":"reference",
                      "Service":"service",

                      "Amount":"amount",
                      "Channel":"channel",
                  },
        };
    },

   async beforeMount(){
       this.user = JSON.parse(localStorage.getItem('user'));
        const response = await axios.get('api/investor');
        const staff = await axios.get('../../api/active-staff');
         const res = await axios.get('api/flow-process/'+1+'/'+staff.data.role_id);

           const resp = await axios.get('api/flow-by-process/'+1);
           this.processes = resp.data;



        this.process = res.data;
        this.investors =response.data;
        this.staff = staff.data;




   },

    methods: {
       menuclick (value) {
                    this.mobile = value
                },

        async approve(){


             try{

                       var result = await this.$swal({
            title: 'Are you sure you want to approve '+this.investor.user.name,
            text: "You won't be able to revert this action!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#000',
            confirmButtonText: 'Yes, confirm !'
        });
        if (result.isConfirmed) {
                const response = await axios.get('../api/investor-approved/'+this.investor.id+'/'+' approved');
                if(response.status == 200){



             const response = await axios.get('api/investor');
            this.investors =response.data;
        const result = response.data.find( ({ id }) => id ===this.investor.id );
            this.investor = result;
               const next = this.processes.find(({stage})=>stage ===result.stage+1);
            if(next){
            this.nextStage = next;
            }else{
                this.nextStage ={
                role:{
                    name:"-"
                }
            }
            }


           if(result.stage>1){
            const prev = this.processes.find(({stage})=>stage ===result.stage-1);
           this.previusStage = prev;
        }else{
            this.previusStage ={
                role:{
                    name:"-"
                }
            }
        }


        this.progress = ((result.stage-1)/this.processes.length)*100;

         const current = this.processes.find(({stage})=>stage ===result.stage);
         if(current){
             this.current = current;
         }else{
               this.current ={
                role:{
                    name:"Admin"
                }
            }
         }

             this.loading=false;

                 this.$swal(
                'Confirmed Refresh browser!',
                response.data,
                'success'
            )
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
        async filterData(){
            this.loading=true;
            const response = await axios.post('api/investor-filter',this.filter);

        this.investors = response.data;
        this.loading=false;


    },

        async show(uid){
             this.load = true;
            this.active = "detail";

            const result = this.investors.find( ({ id }) => id === uid );
            this.investor = result;

            const next = this.processes.find(({stage})=>stage ===result.stage+1);
            if(next){
            this.nextStage = next;
            }else{
                this.nextStage ={
                role:{
                    name:"-"
                }
            }
            }


           if(result.stage>1){
            const prev = this.processes.find(({stage})=>stage ===result.stage-1);
           this.previusStage = prev;
        }else{
            this.previusStage ={
                role:{
                    name:"-"
                }
            }
        }


        this.progress = ((result.stage-1)/this.processes.length)*100;

         const current = this.processes.find(({stage})=>stage ===result.stage);
         if(current){
             this.current = current;
         }else{
               this.current ={
                role:{
                    name:"Admin"
                }
            }
         }



            this.load = false;
        },

    },
};
</script>

<style lang="css">
.approved{
    color: rgb(5, 128, 5) !important;
}
</style>
