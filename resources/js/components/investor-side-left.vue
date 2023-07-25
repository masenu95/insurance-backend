<template>

          <div id="left-sidebar" class="sidebar">
        <h5 class="brand-name">BimaKwik</h5>
        <ul class="nav nav-tabs">

            <li class="nav-item"><a class="nav-link active"  href="#menu-admin">Investor</a></li>
        </ul>
        <div class="tab-content mt-3">
            <div class="tab-pane fade show active" id="menu-admin" role="tabpanel">
                <nav class="sidebar-nav">
                    <ul :class="gridmenu?'metismenu grid':'metismenu'">
                        <li :class="this.link=='home'?'active':''"><router-link to="../investor-home"><i class="fa fa-dashboard"></i><span>Dashboard</span></router-link></li>
                        <li :class="this.link=='open'?'active':''"><router-link to="../investor-openMarket"><i class="fa fa-bank"></i><span>Open Market</span></router-link></li>
                        <li :class="this.link=='pre'?'active':''"><router-link to="../investor-preMarket"><i class="fa fa-industry"></i><span>Pre Market</span></router-link></li>
                        <li :class="this.link=='device'?'active':''"><router-link to="../investor-device-market"><i class="fa fa-industry"></i><span>Device Market</span></router-link></li>
                        <li :class="this.link=='bids'?'active':''"><router-link to="../investor-bids"><i class="fa fa-building"></i><span>My Bids</span></router-link></li>
                        <li :class="this.link=='transactions'?'active':''"><router-link to="../transactions"><i class="fas fa-coins"></i><span>Payments</span></router-link></li>


                    </ul>
                </nav>
            </div>

        </div>

    </div>

</template>

<script>
export default {
    name: 'WorkspaceJsonSidebarLeft',

    data() {
        return {search:"",search:"",
            gridmenu:false,
            user:[],
            staff:[],
            host:window.location.protocol + "//" + window.location.host,
        };
    },
   async beforeMount(){
         this.user = JSON.parse(localStorage.getItem('user'));
         var grid = localStorage.getItem('grid');
         console.log()
         if(JSON.parse(grid)){
             this.gridmenu = JSON.parse(grid);
         }
          const staff = await axios.get('../../api/active-staff');
          this.staff = staff.data;

    },

    mounted() {

    },
    props:['link'],

    methods: {
        async grid(){
               await localStorage.setItem('grid',JSON.stringify(!this.gridmenu))
            this.gridmenu=!this.gridmenu
        }
    },
};
</script>

<style lang="css" scoped>
    a{
        color: aliceblue;
    }
</style>
