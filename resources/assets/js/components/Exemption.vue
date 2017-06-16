<template>
    <tr ref="row">
        <td class="relative">
            <div class="btn-group"> 
                <button @click.prevent="show_menu = !show_menu" class="btn btn-xs btn-default btn-outline" :class="{ active : show_menu }"> 
                    <i class="fa fa-bars"></i> 
                </button>
                <button @click.prevent="" class="btn btn-xs btn-default btn-outline"> 
                    {{ exemption_data.id }}
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
        <td>{{ exemption_data.pattern }}</td>
        <td>{{ exemption_data.published_flag }}</td>
    </tr>
</template>

<script>
    export default {
        props : [
            'exemption'
        ],

        computed : {
            busy() {
                return this.deleting || this.updating;
            }
        },

        data() {
            return {
                exemption_data : this.exemption,
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

                Echo.channel(`exemptions.${this.exemption_data.id}`)
                    .listen("ExemptionWasUpdated", (event) => {
                        this.exemption_data = event.exemption;
                        this.highlight();

                        flash.success(`Exemption ${this.exemption.name} Was Updated`);
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
                      title: `Remove Exemption: ${this.exemption.pattern}?`,
                      text: "The pattern will no longer be whitelisted.",
                      type: "warning",
                      showCancelButton: true,
                      confirmButtonColor: "#bf5329",
                      confirmButtonText: "Yes, remove this exemption.",
                    }
                ).then( this.performDelete, this.ignore );
            },

            performDelete() {
                this.deleting = true;
                Api.delete(`exemptions/${this.exemption.id}`)
                    .then( this.deleteSuccess, this.error );
            },

            deleteSuccess(response) {
                flash.success('The exemption was removed.');
                this.deleting = false;
            },

            // performUpdate() {
            //     this.updating = true;
            //     axios.post(`/api/v1/exemptions/${this.exemption.name}/upgrade`)
            //         .then( this.updateSuccess, this.error );
            // },

            // updateSuccess(response) {
            //     flash.success('The exemption has been told to upgrade.')
            //     this.updating = false;
            // },

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