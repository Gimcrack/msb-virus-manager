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
        <td v-if="model.status == 'active'" ><span class="label label-success">Active</span></td>
        <td v-else><span class="label label-danger">Exempt</span></td>

        <template slot="menu">
            <button @click.prevent="exempt" :disabled="busy" class="btn btn-success btn-xs btn-outline" :class="{disabled : busy}"> 
                <i :class="{'fa-spin' : updating}" class="fa fa-fw fa-check"></i> 
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
                    key : 'pattern',
                    model_friendly : 'pattern',
                    type : 'definition',
                    endpoint : 'definitions',
                },

                toggles : {
                    update : false,
                    delete : false
                }
            }
        },

        methods : {
            exempt() {
                return swal({
                      title: `Create Exemption For: ${this.model.pattern}?`,
                      text: "The pattern will be whitelisted.",
                      type: "warning",
                      showCancelButton: true,
                      confirmButtonColor: "#bf5329",
                      confirmButtonText: "Yes, create the exemption.",
                    }
                ).then( this.performExempt, this.ignore );
            },

            performExempt() {

                this.updating = true;
                Api.post(`exemptions`, { pattern : this.model.pattern })
                    .then( this.updateSuccess, this.error );
            },

            updateSuccess() {
                this.updating = false;
                Bus.$emit('ShouldFetchDefinitions');
            }
        }
    }
</script>
