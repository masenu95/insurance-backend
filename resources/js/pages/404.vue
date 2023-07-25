<template>
<div class="auth option2">
    <div class="auth_left">
        <div class="card">
            <div class="card-body text-center">
                <div class="display-3 text-muted mb-5"><i class="si si-exclamation"></i> 404</div>
                <h1 class="h3 mb-3">Oops.. You just found an error page..</h1>
                <p class="h6 text-muted font-weight-normal mb-3">We are sorry but our service is currently not available&hellip;</p>
                <a class="btn btn-primary" href="javascript:history.back()"><i class="fe fe-arrow-left mr-2"></i>Go back</a>
            </div>
        </div>
    </div>
</div>

</template>

<script>
   export default {
        mounted() {

            console.log('Component mounted.')

        },


        watch: {
        $route: {
            immediate: true,
            handler(to, from) {
                document.title = to.meta.title || 'Some Default Title';
            }
        },
    }

    }

</script>
<style scoped>
.main{
    height: 100vh;
    width: 100vw;
    margin: 0;
    padding: 0;
    background: url('/images/404.jpg');
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
}

.content{
    float: right;
    margin-top: 25%;
    margin-right: 10%;
    width: 35%;

}

.content h3{
    font-weight: 900;
    font-size: 50px;
    width: 100%;
    text-align: center;

}

.content p{
    width: 100%;
    text-align: center;
    font-size: 20px;
}

.btn-div{
    width: 100%;
    margin: 10px auto;
    text-align: center;
}

.btn-div  .btn-welcome{
    background:  #0275c8;
}


</style>


