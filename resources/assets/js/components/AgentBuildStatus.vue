<template>
    <li class="agent-build-status" v-show="loaded">
        <a href="#">
            <span class="label label-info">Agent {{ build }}</span>
        </a>
    </li>
</template>

<script>
    export default {

        data() {
            return {
                build : null,
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
                Bus.$on('ShouldFetchAgentBuild', this.fetch);
            },

            fetch() {
                if ( !! this.timeouts.fetch )
                    clearTimeout(this.timeouts.fetch)

                this.timeouts.fetch = setTimeout( this.performFetch, 1000 );
            },

            performFetch() {
                Api.get('agent-build')
                    .then( this.success, this.error );
            },

            success(response) {
                this.build = response.data.version;
                this.loaded = !! this.build;

                Bus.$emit('AgentBuild', { build : this.build });
            },

            error(error) {
                flash.error('There was a problem fetching the latest agent build');
            },
        }
    }
</script>

<style lang="less">
    .agent-build-status {
        a span {
            font-family: "Courier New";
        }
    }
</style>
