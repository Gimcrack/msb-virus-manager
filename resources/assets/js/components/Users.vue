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
                },

                details : {
                    columns : [
                        'id',
                        'name',
                        'email',
                        'admin_flag'
                    ],
                    type : 'user',
                    heading : 'Users',
                    endpoint : 'users',
                    help : 'System Users',
                    events : {
                        channel : 'users',
                        created : 'UserWasCreated',
                        destroyed : 'UserWasDestroyed',
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