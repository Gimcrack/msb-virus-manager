<template>
    <page
        type="definition"
        page-heading="Definitions"
        order="name"
        :header-columns="headerColumns"
        :collection="collection"
    >
        <div slot="help_text">
            Definitions are automatically downloaded from the provider.
        </div>
    </page>
</template>

<script>
    export default {
        data() {
            return {
                page : null,

                headerColumns : [
                    'id',
                    'pattern',
                    'status'
                ],

                collection : {
                    type : 'definition',
                    endpoint : 'definitions',
                    channel : 'definitions',
                    created : 'DefinitionWasCreated',
                    destroyed : 'DefinitionWasDestroyed'
                },
            }
        },

        mounted() {
            Bus.$on('ShouldFetchDefinitions', this.page.fetch)
        },
    }
</script>