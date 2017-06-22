<template>
    <tr ref="row" :class="{sticky}">
        <slot name="pre"></slot> 
        <td class="relative">
            <div class="btn-group"> 
                <button @click.prevent="show_menu = !show_menu" class="btn btn-xs btn-default btn-outline" :class="{ active : show_menu }"> 
                    <i class="fa fa-bars"></i> 
                </button>
                <button @click.prevent="" class="btn btn-xs btn-default btn-outline"> 
                    {{ id }}
                </button>
            </div>
            <div v-if="show_menu" class="btn-submenu">
                <button v-if="toggles.info" @click.prevent="$emit('view')" :disabled="busy" class="btn btn-info btn-xs btn-outline" :class="{disabled : busy}"> 
                    <i class="fa fa-fw fa-info"></i> 
                </button>
                <button v-if="toggles.update" @click.prevent="$emit('update')" :disabled="busy" class="btn btn-success btn-xs btn-outline" :class="{disabled : busy}"> 
                    <i class="fa fa-fw fa-refresh" :class="{'fa-spin' : updating}"></i> 
                </button>
                <slot name="menu"></slot>
                <button v-if="toggles.delete" @click.prevent="$emit('destroy')" :disabled="busy" class="btn btn-danger btn-xs btn-outline" :class="{disabled : busy}"> 
                    <i class="fa fa-fw fa-times" :class="{'fa-spin' : deleting}"></i> 
                </button>
            </div> 
        </td>
        <slot></slot>
    </tr>
</template>

<script>
    export default {

        mounted() {
            this.$parent.$item = this;
        },

        props : {
            id : {
                required : true
            },
            updating : {
                default : false,
            },
            deleting : {
                default : false
            },
            sticky : {
                default : false
            },
            toggles: {
                default() {
                    return {
                        info : true,
                        update : true,
                        delete : true
                    }
                }
            }
        },

        data() {
            return {
                show_menu : false
            }
        },

        computed : {
            busy() {
                return this.updating || this.deleting;
            }
        }
    }
</script>