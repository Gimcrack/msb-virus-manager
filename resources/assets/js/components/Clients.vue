<template>
    <page
        type="client"
        page-heading="Cients"
        order="name"
        :header-columns="headerColumns"
        :collection="collection"
    >
        <div slot="help_text">
            Clients will self-register here once the agent has been installed.
        </div>
    </page>
</template>

<script>
    import Collection from './mixins/collection.js';

    export default {
        mixins : [
            Collection
        ],

        data() {
            return {
                headerColumns : [
                    'id',
                    'name',
                    'version',
                    'updated'
                ],
                collection : {
                    type : 'client',
                    endpoint : 'clients',
                    channel : 'clients',
                    created : 'ClientWasCreated',
                    destroyed : 'ClientWasDestroyed'
                }
            }
        },

        methods : {

            postCreated(event) {
                Bus.$emit('ShouldFetchAgentBuild');
            },

            postDeleted(event) {
                Bus.$emit('ShouldFetchAgentBuild');
            },
        },
    }
</script>