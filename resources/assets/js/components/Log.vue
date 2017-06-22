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
        <td>{{ model.version }}</td>
        <td>{{ updated }}</td>
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
                    type : 'log_entry',
                    endpoint : 'logs',
                    channel : `logs.${this.initial.id}`,
                    updated : 'LogEntryWasUpdated',
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