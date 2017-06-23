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
                        {
                            title : 'Logged',
                            key : 'updated_at'
                        }
                    ],
                    data_key : 'data',
                    order : 'updated_at',
                    orderDir : false,
                    type : 'log_entry',
                    component : 'log',
                    model_friendly : 'status',
                    heading : 'Recent Logs',
                    endpoint : 'logs',
                    help : 'Review Log Entries',
                    events : {
                        channel : 'log_entries',
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