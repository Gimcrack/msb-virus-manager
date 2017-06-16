<template>
    <tr ref="row">
        <td class="relative">
            <div class="btn-group"> 
                <button @click.prevent="show_menu = !show_menu" class="btn btn-xs btn-default btn-outline" :class="{ active : show_menu }"> 
                    <i class="fa fa-bars"></i> 
                </button>
                <button @click.prevent="" class="btn btn-xs btn-default btn-outline"> {{ definition.id }}
                </button>
            </div>
            <div v-if="show_menu" class="btn-submenu">
                <button @click.prevent="view" :disabled="busy" class="btn btn-info btn-xs btn-outline" :class="{disabled : busy}"> 
                    <i class="fa fa-fw fa-info"></i> 
                </button>
                <button @click.prevent="exempt" :disabled="busy" class="btn btn-success btn-xs btn-outline" :class="{disabled : busy}"> 
                    <i class="fa fa-fw fa-check" :class="{'fa-spin' : updating}"></i> 
                </button>
            </div> 
        </td>
        <td>{{ definition.pattern }}</td>
        <td>{{ definition.status }}</td>
    </tr>
</template>

<script>
    export default {
        props : {
            pattern : {
                required : true,
            },
            status : {
                default : 'Active'
            },
            id : {
                default : null,
            }
        },

        computed : {
            busy() {
                return this.deleting || this.updating;
            }
        },

        data() {
            return {
                definition : {
                    id : ( this.id == null ) ? this.pattern : this.id,
                    pattern : this.pattern,
                    status : this.status,
                },
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

                // Echo.channel(`clients.${this.client_data.id}`)
                //     .listen("ClientWasUpdated", (event) => {
                //         this.client_data = event.client;
                //         this.highlight();

                //         flash.success(`Client ${this.client.name} Was Updated`);
                //     });
            },

            highlight() {
                $(this.$refs.row)
                    .addClass('hover');

                sleep(2000).then( () => {
                    $(this.$refs.row).removeClass('hover');
                });
            },

            exempt() {
                return swal({
                      title: `Create Exemption For: ${this.definition.pattern}?`,
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
                Api.post(`exemptions`, { pattern : this.definition.pattern })
                    .then( this.updateSuccess, this.error );
            },

            updateSuccess(response) {
                flash.success('Exemption created.')
                this.updating = false;
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
    
</style>