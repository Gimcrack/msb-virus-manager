<template>
    <item
        :id="model.id"
        :deleting="deleting"
        :updating="updating"
        :toggles="toggles"
        :sticky="! this.model.acknowledged_flag"
        @view="view"
        @update="update"
        @destroy="destroy"
        @ToggledHasChanged="$emit('ToggledHasChanged')"
    >
        <td slot="pre">
            <button v-if="! model.acknowledged_flag" @click.prevent="acknowledge" :disabled="busy" class="btn btn-success btn-xs btn-outline" :class="{disabled : busy}">
                <i class="fa fa-fw fa-check" :class="{'fa-spin' : updating}"></i>
            </button>
        </td>
        <td>{{ model.client.name }}</td>
        <td>{{ model.pattern.name }}</td>
        <td>{{ fileName }}</td>
        <td>{{ model.times_matched }}</td>
        <td>{{ lastMatch }}</td>
        <td><span :class="[ model.muted_flag ? 'label-danger' : 'label-success']" class="label" v-text="model.muted_flag ? 'Muted' : ''"></span></td>


        <template slot="menu">
            <button @click.prevent="newExemptionFromMatch" :disabled="busy" class="btn btn-success btn-xs btn-outline" :class="{disabled : busy}">
                <i :class="[ 'fa-check', {'fa-spin' : updating}]" class="fa fa-fw"></i>
            </button>
            <button @click.prevent="toggleMute" :disabled="busy" class="btn btn-success btn-xs btn-outline" :class="{disabled : busy}">
                <i :class="[ model.muted_flag ? 'fa-bell-o' : 'fa-bell-slash-o', {'fa-spin' : updating}]" class="fa fa-fw"></i>
            </button>
        </template>
    </item>
</template>

<script>
    export default {
        mixins : [
            mixins.item
        ],

        mounted() {
            if ( ! this.initial.acknowledged_flag )
                Bus.$emit('UnacknowledgedMatch');
        },

        data() {
            return {
                item : {
                    type : 'matched_file',
                    model_friendly : 'file',
                    endpoint : `matches`,
                    channel : `clients.${this.initial.client.name.toLowerCase()}.matches.${this.initial.id}`,
                    updated : 'MatchedFileWasUpdated',
                    events : [
                        {
                            event : 'MatchedFileWasMuted',
                            handler : this.updatedEvent
                        },
                        {
                            event : 'MatchedFileWasUnmuted',
                            handler : this.updatedEvent
                        },
                        {
                            event : 'MatchedFileWasIncremented',
                            handler : this.updatedEvent
                        },
                        {
                            event : 'MatchedFileWasIncremented',
                            handler : this.incremented
                        },
                    ]
                },

                toggles : {
                    update : false,
                    delete : false
                }
            }
        },

        computed: {
            lastMatch() {
                return fromNow(this.model.updated_at);
            },

            fileName() {
                return ( this.model.file.indexOf('\\') > -1 ) ?
                    this.model.file.split('\\').pop() :
                    this.model.file.split(' ').pop()
            }
        },

        methods: {
            incremented() {
                Bus.$emit('UnacknowledgedMatch');
            },

            newExemptionFromMatch() {
                Bus.$emit('newExemptionFromMatch', {
                    match : this.model
                });
            },

            toggleMute() {
                if ( this.model.muted_flag )
                    return this.unmute();

                return this.mute();
            },

            unmute() {
                this.updating = true;

                Api.post(`clients/${this.initial.client.name}/matches/${this.initial.id}/unmute`)
                    .then(this.updateSuccess, this.error)
            },

            mute() {
                this.updating = true;

                Api.post(`clients/${this.initial.client.name}/matches/${this.initial.id}/mute`)
                    .then(this.updateSuccess, this.error)
            },

            acknowledge() {
                this.updating = true;

                Api.post(`clients/${this.initial.client.name}/matches/${this.initial.id}/acknowledge`)
                    .then(this.updateSuccess, this.error)
            },
        }
    }
</script>
