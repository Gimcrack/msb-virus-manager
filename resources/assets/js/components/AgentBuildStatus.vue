<template>
    <li v-show="loaded">
        <a href="#">  
            <span class="label label-primary">Agent {{ build }}</span>
        </a>
    </li>
</template>

<script>
    export default {

        data() {
            return {
                build : null,
                loaded : false,
            }
        },

        mounted() {
            this.listen();
            this.fetch();
        },

        methods : {
            listen() {
                Bus.$on('ShouldFetchAgentBuild', this.fetch);
            },

            fetch() {
                Api.get('agent-build')
                    .then( this.success, this.error );
            },

            success(response) {
                this.build = response.data.version;
                this.loaded = !! this.build;
            },

            error(error) {
                flash.error('There was a problem fetching the latest agent build');
            },
        }
    }
</script>

<style lang="less">

</style>