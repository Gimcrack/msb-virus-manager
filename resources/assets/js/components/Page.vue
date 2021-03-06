<template>
    <div class="page">
        <div class="page-header">
            <h2>{{ params.heading }}</h2>
            <button type="button" @click.prevent="fetch" :disabled="busy" :class="busy ? 'disabled' : ''" class="btn-primary btn">
                <i class="fa fa-fw fa-circle-o-notch" :class="{'fa-spin' : busy}"></i>
                {{ refresh_btn_text }}
            </button>
            
            <span class="dropdown">
                <a v-if="toggled.length" data-toggle="dropdown" role="button" aria-expanded="false" :class="busy ? 'disabled' : ''" class="dropdown-toggle btn-success btn">
                    <i class="fa fa-fw fa-bars" :class="{'fa-spin' : busy}"></i>
                    Do With Selected
                    <span class="caret"></span>
                </a>
    
                <ul class="dropdown-menu" role="menu">
                    <slot name="selection-dropdown-menu"></slot>
                </ul>
            </span>
            
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
                    <th>
                        <i @click="toggleAll" style="cursor:pointer; font-size:1.5em; line-height:1" class="fa fa-fw" :class="toggleAllClass"></i>
                    </th>
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
                    <component :is="params.component || params.type" :initial="model" :key="model.id" @ToggledHasChanged="setToggled"></component>
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
                        orderDir : true,
                        columns : [],
                        model_friendly : 'name',
                        endpoint : '',
                        help : null,
                        events : {
                            channel : '',
                            created : '',
                            destroyed : '',
                            global : null
                        },
                        where : {},
                        reject : { placeholder : 'some-nonsense-value'}
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
                orderBy : this.params.order || 'name',
                asc : ( this.params.orderDir != null ) ? this.params.orderDir : true,
                toggled : this.getToggled(),
                models : this.getInitialState(),
            }
        },

        computed : {

            hasToggled() {
                return !! this.toggled.length;
            },

            allToggled() {
                return this.toggled.length == this.filtered.length;
            },

            toggleAllClass() {
                if ( this.allToggled ) return ['fa-check-square-o'];
                if ( this.hasToggled ) return ['fa-minus-square-o'];
                return ['fa-square-o'];
            }

        },

        methods : {
            getInitialState() {
                let key = this.params.endpoint,
                    state = INITIAL_STATE[key] || [];

                // get the data if it has a key (like when paginating)
                if ( this.params.data_key ) {
                    state = state[this.params.data_key];
                }

                return state;
            },

            postCreated(event) {
                this.$emit('created',event);
            },

            postDeleted(event) {
                this.$emit('deleted',event)
            },

            setToggled() {
                this.toggled = this.getToggled();
            },

            getToggled() {
                return this.$children
                    .filter( child => { return child.$children.length && child.$children[0].toggled; } );
            },

            getUntoggled() {
                return this.$children
                    .filter( child => { return child.$children.length && ! child.$children[0].toggled; } );
            },

            toggleAll() {
                if ( this.hasToggled ) {
                    return this.getToggled().forEach( child => { child.$children[0].toggle() } );
                }

                return this.getUntoggled().forEach( child => { child.$children[0].toggle() } );
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

            & > * + * {
                margin-left: 0.5em;
            }
        }
    }
</style>