<template>
    <tr ref="row">
        <td class="relative">
            <div class="btn-group"> 
                <button @click.prevent="show_menu = !show_menu" class="btn btn-xs btn-default btn-outline" :class="{ active : show_menu }"> 
                    <i class="fa fa-bars"></i> 
                </button>
                <button @click.prevent="" class="btn btn-xs btn-default btn-outline"> 
                    {{ model.id }} 
                </button>
            </div>
            <div v-if="show_menu" class="btn-submenu">
                <button @click.prevent="view" :disabled="busy" class="btn btn-info btn-xs btn-outline" :class="{disabled : busy}"> 
                    <i class="fa fa-fw fa-info"></i> 
                </button>
                <button @click.prevent="performUpgrade" :disabled="busy" class="btn btn-success btn-xs btn-outline" :class="{disabled : busy}"> 
                    <i class="fa fa-fw fa-refresh" :class="{'fa-spin' : updating}"></i> 
                </button>
                <button @click.prevent="destroy" :disabled="busy" class="btn btn-danger btn-xs btn-outline" :class="{disabled : busy}"> 
                    <i class="fa fa-fw fa-times" :class="{'fa-spin' : deleting}"></i> 
                </button>
            </div> 
        </td>
        <td>{{ model.name }}</td>
        <td>{{ model.version }}</td>
        <td>
            {{ updated }}
        </td>
    </tr>
</template>

<script>
    import item from './mixins/item.js';

    export default {
        mixins : [
            item
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
                    channel : `clients.${this.model.id}`,
                    updated : 'ClientWasUpdated',
                }
            }
        },

        methods : {
            postUpdated(event) {
                Bus.$emit('ShouldFetchAgentBuild');
            },

            performUpgrade() {
                this.updating = true;
                Api.post(`clients/${this.client.name}/upgrade`)
                    .then( this.upgradeSuccess, this.error );
            },

            upgradeSuccess(response) {
                this.updating = false;

                flash.success('The client was told to upgrade.');
            },
        }
    }
</script>

<style lang="scss">
    .btn-submenu {
      position: absolute;
      /* padding: 0 3px 3px; */
      background: white;
      z-index: 3;
      top: 50%;
      margin-top: -12px;
      left: 30px;
      overflow: hidden;
    }
</style>