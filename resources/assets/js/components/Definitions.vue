<template>
    <page
        :params="details"
        :toggles="toggles"
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
                        'pattern',
                        'status'
                    ],
                    order: 'pattern',
                    heading : 'Definitions',
                    type : 'definition',
                    endpoint : 'definitions',
                    help : 'Definitions are automatically downloaded from the provider.',
                    events : {
                        channel : 'definitions',
                        created : 'DefinitionWasCreated',
                        destroyed : 'DefinitionWasDestroyed',
                        global : {
                            ShouldFetchDefinitions: this.page.fetch
                        }
                    }
                },
            }
        },
    }
</script>
