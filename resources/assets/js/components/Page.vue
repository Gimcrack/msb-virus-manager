<template>
    <div class="page">
        <div class="page-header">
            <h2>{{ params.heading }}</h2>
            <button type="button" @click.prevent="fetch" :disabled="busy" :class="busy ? 'disabled' : ''" class="btn-primary btn">
                <i class="fa fa-fw fa-circle-o-notch" :class="{'fa-spin' : busy}"></i>
                {{ refresh_btn_text }}
            </button>
            
            <button v-if="toggles.new" :class="{ disabled : busy }" @click.prevent="$emit('new')" :disabled="busy" class="btn btn-success">
                <i class="fa fa-fw fa-circle-o-notch" :class="{'fa-spin' : busy}"></i>
                <template v-if="params.newBtnText">{{ params.newBtnText}}</template>
                <template v-else>New {{ properType }}</template>
            </button>

            <slot name="menu"></slot>

            <vinput icon="fa-search" placeholder="Search..." v-model="search"></vinput>
        </div>
        <div class="alert alert-info" v-text="params.help" v-show="params.help">
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <header-sort-button 
                        v-for="(col,index) in params.columns" 
                        :order-by="orderBy" 
                        :asc="asc" 
                        :column="col"
                        :key="index">
                    </header-sort-button>
                </tr>
            </thead>
            <tbody v-if="filtered.length">
                <template v-for="model in filtered" >
                    <component :is="params.component || params.type" :initial="model" :key="model.id"></component>
                </template>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="100">
                        <span class="label label-primary">
                            Viewing {{ filtered.length }} Records
                        </span>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</template>

<script>
    export default {
        mixins : [
            mixins.collection
        ],

        mounted() {
            this.$parent.page = this;
        },

        props : {
            params : {
                default() {
                    return {
                        type : '',
                        heading : 'Page Heading',
                        data_key : null,
                        order : 'name',
                        orderDir : 'asc',
                        columns : [],
                        model_friendly : 'name',
                        endpoint : '',
                        help : null,
                        events : {
                            channel : '',
                            created : '',
                            destroyed : '',
                            global : null
                        }
                        
                    }
                }
            },
            toggles : {
                default()  { 
                    return {
                        new : true
                    }
                }
            }
        },

        data() {
            return {
                orderBy : this.params.order,
                asc : this.params.orderDir
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