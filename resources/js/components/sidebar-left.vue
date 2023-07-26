<template>

          <div id="left-sidebar" class="sidebar">
        <h5 class="brand-name">BimaKwik</h5>

        <div class="tab-content mt-3" >
            <div class="tab-pane fade " :class="tab=='invoice'?'show active':''" id="menu-admin" role="tabpanel">
                <nav class="sidebar-nav" style="padding-top: 40px;">
                    <ul :class="gridmenu?'metismenu grid':'metismenu'">
                        <li :class="this.link=='home'?'active':''"><router-link to="../admin-home"><i class="fa fa-dashboard"></i><span>Dashboard</span></router-link></li>
                        <li :class="this.link=='pending'?'active':''"><router-link to="../pending"><i class="fa fa-bank"></i><span>Pending</span></router-link></li>
                        <li :class="this.link=='success'?'active':''"><router-link to="../success"><i class="fa fa-industry"></i><span>Success</span></router-link></li>
                        <li :class="this.link=='expiring'?'active':''"><router-link to="../expiring"><i class="fa fa-building"></i><span>Expiring</span></router-link></li>
                        <li :class="this.link=='transaction'?'active':''"><router-link to="../transactions"><i class="fas fa-coins"></i><span>Transactions</span></router-link>
                    </li>

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
            tab:'invoice',
        };
    },
   async beforeMount(){
         this.user = JSON.parse(localStorage.getItem('user'));
         var grid = localStorage.getItem('grid');
         console.log()
         if(JSON.parse(grid)){
             this.gridmenu = JSON.parse(grid);
         }

         var tab = localStorage.getItem('tab');
         if(JSON.parse(tab)){
             this.tab = JSON.parse(tab);
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
        },

        async tabChange(tab){
               await localStorage.setItem('tab',JSON.stringify(tab))
            this.tab=tab
        }
    },
};
</script>

<style lang="css" scoped>
    a{
        color: aliceblue;
    }
</style>
