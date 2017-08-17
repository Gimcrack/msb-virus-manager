<template>
    <li class="definitions-status" v-show="loaded">
        <a href="#">
            <span class="label label-info">{{ definitions }}</span>
        </a>
    </li>
</template>

<script>
    export default {

        data() {
            return {
                definitions : null,
                loaded : false,
                timeouts : {}
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
                if ( !! this.timeouts.fetch )
                    clearTimeout(this.timeouts.fetch)

                this.timeouts.fetch = setTimeout( this.performFetch, 1000 );
            },

            performFetch() {
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
    .definitions-status {
        a span {
            font-family: "Courier New";
        }
    }
</style>
