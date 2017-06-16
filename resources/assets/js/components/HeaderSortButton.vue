<template>
    <th>
        {{ column.$ucfirst() }}
        <button :class="{active, 'btn-outline' : ! active}" class="btn btn-xs btn-primary " @click="$parent.sortBy(column)"> 
            <i class="fa fa-fw" :class="active_asc ? 'fa-sort-amount-asc' : 'fa-sort-amount-desc' "></i> 
        </button>
    </th>
</template>

<script>
    export default {
        props : [
            'column',
            'asc',
            'orderBy'
        ],

        computed : {
            active() {
                return this.orderBy == this.column;
            },

            active_asc() {
                return this.asc && this.active;
            }
        }
    }
</script>

<style lang="less">

</style>