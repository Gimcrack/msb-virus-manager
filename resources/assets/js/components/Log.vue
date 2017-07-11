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
        <td>{{ name }}</td>
        <td>{{ model.action }}</td>
        <td>{{ status }}</td>
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
            },

            name() {
                return ( this.model.client ) ? this.model.client.name : '-Unknown-';
            },

            status() {
                return ( this.model.status.length > 30 ) ? this.model.status.slice(0,30) + '...' : this.model.status;
            }
        },

        data() {
            return {
                item : {
                    key : 'id',
                    type : 'log_entry',
                    endpoint : 'logs',
                    model_friendly : 'status'
                },

                toggles : {
                    update : false,
                    delete : false
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