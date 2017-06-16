<template>
    <li v-show="loaded">
        <a href="#">
            <span class="label label-primary">{{ definitions }}</span>
        </a>
    </li>
</template>

<script>
    export default {

        data() {
            return {
                definitions : null,
                loaded : false,
            }
        },

        mounted() {
            this.listen();
            this.fetch();
        },

        methods : {

            listen() {
                Bus.$on('ShouldFetchDefinitions', this.fetch);
            },

            fetch() {
                Api.get('definitions-status')
                    .then( this.success, this.error );
            },

            success(response) {
                this.definitions = response.data.definitions;
                this.loaded = !! this.definitions;
            },

            error(error) {
                flash.error('There was a problem fetching the definitions status');
            },
        }
    }
</script>

<style lang="less">

</style>