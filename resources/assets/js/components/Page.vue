<template>
    <div class="page">
        <div class="page-header">
            <h2>{{ pageHeading }}</h2>
            <button type="button" @click.prevent="fetch" :disabled="busy" :class="busy ? 'disabled' : ''" class="btn-primary btn">
                <i class="fa fa-fw fa-circle-o-notch" :class="{'fa-spin' : busy}"></i>
                {{ refresh_btn_text }}
            </button>
            
            <button v-if="toggles.new" :class="{ disabled : busy }" @click.prevent="$emit('new')" :disabled="busy" class="btn btn-success">
                <i class="fa fa-fw fa-circle-o-notch" :class="{'fa-spin' : busy}"></i>
                New {{ properType() }}
            </button>

            <input type="text" placeholder="Search..." v-model="search" class="form-control">
        </div>
        <div class="alert alert-info">
            <slot name="help_text"></slot>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <header-sort-button 
                        v-for="(col,index) in headerColumns" 
                        :order-by="orderBy" 
                        :asc="asc" 
                        :column="col"
                        :key="index">
                    </header-sort-button>
                </tr>
            </thead>
            <tbody>
                <template v-for="model in filtered" >
                    <component :is="type" :initial="model" :key="model.id"></component>
                </template>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="100">
                        <span class="label label-primary">Viewing {{ filtered.length }} Records</span>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</template>

<script>
    import Collection from './mixins/collection.js';

    export default {

        mounted() {
            this.$parent.page = this;
        },

        mixins : [
            Collection
        ],

        props : {
            pageHeading : {},
            headerColumns : {},
            collection : {},
            type : {},
            order : {
                default : null
            },
            toggles : {
                default()  { 
                    return {
                        new : false
                    }
                }
            }
        },

        data() {
            return {
                orderBy : this.order,
            }
        },

        methods : {
            postCreated(event) {
                this.$emit('created',event);
            },

            postDeleted(event) {
                this.$emit('deleted',event)
            },
        }
    }
</script>

<style lang="scss">
    .page {
        .page-header {
            padding: 1em 0;

            h2 {
                margin: 0;
                
            }
            display: flex;
            align-items: center;

            * + * {
                margin-left: 0.5em;
            }
        }
    }
</style>