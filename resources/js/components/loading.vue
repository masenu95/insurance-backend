<template>
    <div class="loading-page">
        <div class="center">
                
                 <RingLoader color="#ffffff" size="100px" />
                 <h3 style="margin-top:15px;color:#fff">Loading ....</h3>

        </div>
  
    </div>
</template>


<script>
  import RingLoader from 'vue-spinner/src/RingLoader.vue'
  export default {
    components: {
      RingLoader
    },
    data() {
        return {search:"",search:"",
            loading:true,
            host:window.location.host
        }
    },
  }
</script>

<style>
.loading-page{
    height: 100vh;
    width: 100%;
    background: #70052b;
}
.center {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  flex-direction: column
}
</style>
