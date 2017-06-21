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
                <button @click.prevent="exempt" :disabled="busy" class="btn btn-success btn-xs btn-outline" :class="{disabled : busy}"> 
                    <i class="fa fa-fw fa-check" :class="{'fa-spin' : updating}"></i> 
                </button>
            </div> 
        </td>
        <td>{{ model.pattern }}</td>
        <td>{{ model.status }}</td>
    </tr>
</template>

<script>
    import item from './mixins/item.js';

    export default {
        mixins : [
            item
        ],

        data() {
            return {
                item : {
                    key : 'pattern',
                    type : 'definition',
                    endpoint : 'definitions',
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
        }
    }
</script>
