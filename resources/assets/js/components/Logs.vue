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
                        'action',
                        'status',
                        'updated'
                    ],
                    data_key : 'data',
                    order : 'updated_at',
                    orderDir : false,
                    type : 'log',
                    heading : 'Log Entries',
                    endpoint : 'logs',
                    help : 'Review Log Entries',
                    events : {
                        channel : 'logs',
                        created : 'LogEntryWasCreated',
                        destroyed : 'LogEntryWasDestroyed',
                    }
                },
            }
        },

        methods : {

            created(event) {
            },

            deleted(event) {
            },
        },
    }
</script>