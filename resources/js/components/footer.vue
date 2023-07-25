<template>
        <div class="section-body">
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            Copyright © {{date}} <a href="https://BimaKwik.com">BimaKwik</a>.
                        </div>

                    </div>
                </div>
            </footer>

        </div>
</template>


<script>
export default {
    name: 'footer-component',

    data() {
        return {search:"",search:"",
            date:new Date().getFullYear()
        };
    },

    mounted() {

    },

    methods: {

    },
};
</script>

<style lang="scss" scoped>

</style>
