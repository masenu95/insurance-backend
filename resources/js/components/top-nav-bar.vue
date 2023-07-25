<template>
   <div class="section-body" id="page_top"  style="background-color: #fff;">
            <div class="container-fluid">
                <div class="page-header">
                    <div class="left" style="font-weight: 700;">
                            Balance : &nbsp; <router-link to="/wallet" style="padding:5px 10px;border-radius:6px;border: 1px solid black;border-radius: 5px;color: black;font-weight: 700;background-color:rgb(232, 223, 202);"> {{(selectedBalance ? selectedBalance:this.balance) | formatNumber}} </router-link>
                    </div>
                    <div class="right">

                        <div class="notification d-flex">
                            <div class="dropdown d-flex">
                                <a href="javascript:void(0)" class="chip ml-3" data-toggle="dropdown">
                                    <span class="avatar"  style="padding:5px 10px;border-radius:6px;border: 1px solid black;border-radius: 5px;color: black;font-weight: 700;background-color:rgb(232, 223, 202);"></span>{{user.name}}</a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                    <router-link to="../../profile" class="dropdown-item" ><i class="dropdown-icon fe fe-user"></i> Profile</router-link >
                                    <router-link to="/wallet" class="dropdown-item" ><i class="dropdown-icon fa fa-google-wallet" ></i> Walllet</router-link>

                                    <a class="dropdown-item" href="" @click.prevent="logout"><i class="dropdown-icon fe fe-log-out"></i> Sign out</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
</template>


<script>
export default {
    name: 'WorkspaceJsonTopNavBar',

    data() {
        return {search:"",search:"",
          user:[],
          host:window.location.protocol + "//" + window.location.host,
          balance:0,
        };
    },
   async beforeMount(){
         this.user = JSON.parse(localStorage.getItem('user'));

         const result = await axios.get('../api/my-balance');
        this.balance = result.data;
    },

    props:['selectedBalance'],



    methods: {
             async logout(){
                const response  = await axios.post('../../../api/logout');

                if(response.status == 200){
                    localStorage.removeItem('user');
                    localStorage.removeItem('token');

                    this.$router.push('../../../login')

                }
            }
    },
};
</script>

<style lang="scss" scoped>

</style>
