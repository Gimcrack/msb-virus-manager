<template>
    <page
        :params="details"
        :toggles="toggles"
        @created="created"
        @deleted="deleted"
    ></page>
</template>

<script>
    export default {
        data() {
            return {
                toggles : {
                    new : false,
                },

                details : {
                    columns : [
                        'id',
                        'name',
                        'version',
                        'updated'
                    ],
                    type : 'client',
                    heading : 'Clients',
                    endpoint : 'clients',
                    help : 'Clients will self-register here once the agent has been installed.',
                    events : {
                        channel : 'clients',
                        created : 'ClientWasCreated',
                        destroyed : 'ClientWasDestroyed',
                    }
                },
            }
        },

        methods : {

            created(event) {
                Bus.$emit('ShouldFetchAgentBuild');
            },

            deleted(event) {
                Bus.$emit('ShouldFetchAgentBuild');
            },
        },
    }
</script>