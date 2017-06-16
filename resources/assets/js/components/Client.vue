<template>
    <tr ref="row">
        <td class="relative">
            <div class="btn-group"> 
                <button @click.prevent="show_menu = !show_menu" class="btn btn-xs btn-default btn-outline" :class="{ active : show_menu }"> 
                    <i class="fa fa-bars"></i> 
                </button>
                <button @click.prevent="" class="btn btn-xs btn-default btn-outline"> 
                    {{ client_data.id }} 
                </button>
            </div>
            <div v-if="show_menu" class="btn-submenu">
                <button @click.prevent="view" :disabled="busy" class="btn btn-info btn-xs btn-outline" :class="{disabled : busy}"> 
                    <i class="fa fa-fw fa-info"></i> 
                </button>
                <button @click.prevent="performUpdate" :disabled="busy" class="btn btn-success btn-xs btn-outline" :class="{disabled : busy}"> 
                    <i class="fa fa-fw fa-refresh" :class="{'fa-spin' : updating}"></i> 
                </button>
                <button @click.prevent="destroy" :disabled="busy" class="btn btn-danger btn-xs btn-outline" :class="{disabled : busy}"> 
                    <i class="fa fa-fw fa-times" :class="{'fa-spin' : deleting}"></i> 
                </button>
            </div> 
        </td>
        <td>{{ client_data.name }}</td>
        <td>{{ client_data.version }}</td>
        <td>
            {{ updated }}
        </td>
    </tr>
</template>

<script>

    export default {
        props : [
            'client'
        ],

        computed : {
            busy() {
                return this.deleting || this.updating;
            },

            updated() {
                return fromNow(this.client_data.updated_at);
            }
        },

        data() {
            return {
                client_data : this.client,
                updating : false,
                deleting : false,
                show_menu : false,
            }
        },

        mounted() {
            this.highlight();
            this.listen();
        },

        methods : {
            listen() {

                Echo.channel(`clients.${this.client_data.id}`)
                    .listen("ClientWasUpdated", (event) => {
                        this.client_data = event.client;
                        this.highlight();

                        flash.success(`Client ${this.client.name} Was Updated`);

                        Bus.$emit('ShouldFetchAgentBuild');
                    });
            },

            highlight() {
                $(this.$refs.row)
                    .addClass('hover');

                sleep(2000).then( () => {
                    $(this.$refs.row).removeClass('hover');
                });
            },

            destroy() {
                return swal({
                      title: `Remove Client: ${this.client.name}?`,
                      text: "Remember to remove the agent from the server",
                      type: "warning",
                      showCancelButton: true,
                      confirmButtonColor: "#bf5329",
                      confirmButtonText: "Yes, remove this client.",
                    }
                ).then( this.performDelete, this.ignore );
            },

            performUpdate() {
                this.updating = true;
                Api.post(`clients/${this.client.name}/upgrade`)
                    .then( this.updateSuccess, this.error );
            },

            performDelete() {
                this.deleting = true;
                Api.delete(`clients/${this.client.name}`)
                    .then( this.deleteSuccess, this.error );
            },

            updateSuccess(response) {
                flash.success('The client has been told to upgrade.')
                this.updating = false;
            },

            deleteSuccess(response) {
                //flash.success('The client was removed.');
                this.deleting = false;
            },

            ignore() {
            
            },

            error(error) {
                console.error(error);
                this.deleting = false;
                flash.error('There was an error performing the operation. See the console for more details');
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