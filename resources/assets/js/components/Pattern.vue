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
                <!-- <button @click.prevent="exempt" :disabled="busy" class="btn btn-success btn-xs btn-outline" :class="{disabled : busy}"> 
                    <i class="fa fa-fw fa-check" :class="{'fa-spin' : updating}"></i> 
                </button> -->
                <button @click.prevent="destroy" :disabled="busy" class="btn btn-danger btn-xs btn-outline" :class="{disabled : busy}"> 
                    <i class="fa fa-fw fa-times" :class="{'fa-spin' : deleting}"></i> 
                </button>
            </div> 
        </td>
        <td>{{ model.name }}</td>
        <td>{{ model.published_flag }}</td>
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
                    type : 'pattern',
                    endpoint : 'patterns',
                    channel : `patterns.${this.model.id}`,
                    updated : 'PatternWasUpdated',
                }
            }
        },
    }
</script>

<style lang="scss">
    
</style>