<template>

            <div id="header_top" class="header_top">
        <div class="container">
            <div class="hleft">
                <router-link class="header-brand" to="/admin-home"><img :src="host+'/images/logo.png'" class="brand-logo"></router-link>
            </div>
            <div class="hright">
                <button class="nav-link icon right_tab" @click.prevent="emitToParent"><i style="color:red" class="fe fe-align-right"></i></button>
                <a href="login.html" class="nav-link icon settingbar"><i class="fe fe-power"></i></a>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    name: 'admin-header',

    data() {
        return {search:"",search:"",
          mobile:false,
          host:window.location.protocol + "//" + window.location.host,
        };
    },

    mounted() {

    },

    methods: {
               emitToParent (event) {
             this.mobile = !this.mobile;
      this.$emit('childToParent', this.mobile)
    }
    },
};
</script>

<style lang="scss" scoped>

</style>
