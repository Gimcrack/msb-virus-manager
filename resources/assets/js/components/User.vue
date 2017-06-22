<template>
    <item 
        :id="model.id"
        :deleting="deleting"
        :updating="updating"
        :toggles="toggles"
        @view="view"
        @update="update"
        @destroy="destroy"
    >
        <td>{{ model.name }}</td>
        <td>{{ model.email }}</td>
        <td>{{ model.admin_flag }}</td>
    </item>
</template>

<script>
    export default {
        mixins : [
            mixins.item
        ],

        computed : {
            updated() {
                return fromNow(this.model.updated_at);
            }
        },

        data() {
            return {
                item : {
                    key : 'id',
                    type : 'user',
                    endpoint : 'users',
                    channel : `users.${this.initial.id}`,
                    updated : 'UserWasUpdated',
                }
            }
        },

        methods : {
            postUpdated(event) {
            },

            update() {

            },

            upgradeSuccess(response) {

            },
        }
    }
</script>