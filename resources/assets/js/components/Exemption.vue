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
        <td>{{ model.pattern }}</td>
        <td v-if="model.published_flag"><span class="label label-success">Yes</span></td>
        <td v-else><span class="label label-danger">No</span></td>

        <template slot="menu">
            <button @click.prevent="togglePublish" :disabled="busy" class="btn btn-success btn-xs btn-outline" :class="{disabled : busy}"> 
                <i :class="[ model.published_flag ? 'fa-arrow-down' : 'fa-arrow-up', {'fa-spin' : updating}]" class="fa fa-fw"></i> 
            </button>
        </template>
    </item>
</template>

<script>
    export default {
        mixins : [
            mixins.item
        ],
        
        data() {
            return {
                item : {
                    type : 'exemption',
                    model_friendly : 'pattern',
                    endpoint : `exemptions`,
                    channel : `exemptions.${this.initial.id}`,
                    updated : 'ExemptionWasUpdated',
                },

                toggles : {
                    update : false
                }
            }
        },

        methods: {
            togglePublish() {
                if ( this.model.published_flag ) 
                    return this.unpublish();

                return this.publish(); 
            },

            unpublish() {
                this.updating = true;

                Api.post(`exemptions/${this.initial.id}/unpublish`)
                    .then(this.updateSuccess, this.error)
            },

            publish() {
                this.updating = true;

                Api.post(`exemptions/${this.initial.id}/publish`)
                    .then(this.updateSuccess, this.error)
            },
        }
    }
</script>
