<template>
   <div class="section-body" id="page_top" >
            <div class="container-fluid">
                <div class="page-header">
                    <div class="left">
                        <div class="input-group">
                            <div class="input-group-append">
                               Borrowing Limit: <span style="background:#274db3;color:#fff;padding:5px 10px;border-radius:6px">TZS {{limit | formatNumber}} </span>
                            </div>
                        </div>
                    </div>

                    <div class="">
                        <div class="input-group">
                            <div class="input-group-append">
                               Balance: <router-link to="/wallet" style="background:#274db3;color:#fff;padding:5px 10px;border-radius:6px">TZS {{this.balance | formatNumber}} </router-link>
                            </div>
                        </div>
                    </div>
                    <div class="right">

                        <div class="notification d-flex">
                            <div class="dropdown d-flex">
                                <a href="javascript:void(0)" class="chip ml-3" data-toggle="dropdown">
                                    <span class="avatar" style="background-image: url(images/user.jpg)"></span>{{user.name}}</a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                    <router-link to="" class="dropdown-item" ><i class="dropdown-icon fe fe-user"></i> Profile</router-link >
                                    <router-link to="/wallet" class="dropdown-item" ><i class="dropdown-icon fa fa-google-wallet"></i> Walllet</router-link>

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
        };
    },
    async beforeMount(){
         this.user = JSON.parse(localStorage.getItem('user'));
         const result = await axios.get('../../api/my-balance');
        this.balance = result.data;
    },
    mounted(){


    },
    props:['limit'],



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
