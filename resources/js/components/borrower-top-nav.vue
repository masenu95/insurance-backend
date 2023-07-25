<template>
   <div class="section-body" id="page_top" >
            <div class="container-fluid">
                <div class="page-header">
                    <div class="left">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="What you want to find">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button">Search</button>
                            </div>
                        </div>
                    </div>
                 <div class="right">

                        <div class="notification d-flex">
                            <div class="dropdown d-flex">
                                <a href="javascript:void(0)" class="chip ml-3" data-toggle="dropdown">
                                    <span class="avatar" style="background-image: url(images/user.jpg)"></span>{{user.name}}</a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                    <router-link to="" class="dropdown-item"><i class="dropdown-icon fe fe-user"></i> Profile</router-link >
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
    beforeMount(){
         this.user = JSON.parse(localStorage.getItem('user'));
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
