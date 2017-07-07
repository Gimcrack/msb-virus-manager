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
                    key : 'name',
                    type : 'client',
                    endpoint : 'clients',
                    channel : `clients.${this.initial.name.toLowerCase()}`,
                    updated : 'ClientWasUpdated',
                }
            }
        },

        methods : {
            postUpdated(event) {
                Bus.$emit('ShouldFetchAgentBuild');
            },

            update() {
                this.updating = true;
                Api.post(`clients/${this.model.name}/upgrade`)
                    .then( this.upgradeSuccess, this.error );
            },

            upgradeSuccess(response) {
                this.updating = false;

                flash.success('The client was told to upgrade.');
            },
        }
    }
</script>