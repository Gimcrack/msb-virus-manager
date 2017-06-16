<template>
    <div class="clients">
        <div class="clients-header">
            <h2>Clients</h2>
            <button type="button" @click.prevent="fetch" :disabled="busy" :class="busy ? 'disabled' : ''" class="btn-primary btn">
                <i class="fa fa-fw fa-circle-o-notch" :class="{'fa-spin' : busy}"></i>
                {{ refresh_btn_text }}
            </button>
            <input type="text" placeholder="Search Clients..." v-model="search" class="form-control">
        </div>
        <div class="alert alert-info">
            Clients will self-register here once the agent has been installed.
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <header-sort-button :order-by="orderBy" :asc="asc" column="id"></header-sort-button>
                    <header-sort-button :order-by="orderBy" :asc="asc" column="name"></header-sort-button>
                    <header-sort-button :order-by="orderBy" :asc="asc" column="version"></header-sort-button>
                    <header-sort-button :order-by="orderBy" :asc="asc" column="updated"></header-sort-button>
                </tr>
            </thead>
            <tbody>
                <template v-for="client in filtered_clients" >
                    <client :client="client" :key="client.id"></client>
                </template>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="100">
                        <span class="label label-primary">Viewing {{ filtered_clients.length}} Records</span>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</template>

<script>
    import Collection from './mixins/collection.js';

    export default {
        mixins : [
            Collection
        ],

        data() {
            return {
                busy : false,
                models : [],
                refresh_btn_text : 'Refresh',
                search : null,
                orderBy : 'name',
                asc : true,

                collection : {
                    type : 'client',
                    endpoint : 'clients',
                    channel : 'clients',
                    created : 'ClientWasCreated',
                    destroyed : 'ClientWasDestroyed'
                }
            }
        },

        computed : {
            filtered_clients() {
                //if ( ! this.search ) return this.models;

                // return _.sortBy( _.filter( this.models, (client) => { 
                //     return this.searchClient( client );
                // }), this.orderBy);

                let models = _(this.models)
                    .filter( this.searchClient )
                    .sortBy(this.orderBy);

                return (this.asc) ? models.value() : models.reverse().value();
            }
        },

        methods : {
            preFetch() {
                this.busy = true;
                this.refresh_btn_text = 'Refreshing';
            },

            postSuccess() {
                this.refresh_btn_text = 'Refreshed';

                sleep(1000).then( () => {
                    this.refresh_btn_text = 'Refresh';
                    this.busy = false;
                })
            },

            postError() {
                this.busy = false;
                this.refresh_btn_text = 'Refresh';
            },

            postCreated(event) {
                Bus.$emit('ShouldFetchAgentBuild');
            },

            postDeleted(event) {
                Bus.$emit('ShouldFetchAgentBuild');
            },

            searchClient( client ) {
                if ( ! this.search ) return true;

                for ( let prop in client )
                {
                    if ( typeof client[prop] === "string" ) 
                    {
                        if ( client[prop].toLowerCase().indexOf( this.search.toLowerCase() ) !== -1 ) return true;    
                    }
                    else 
                    {
                        if ( this.searchClient( client[prop] ) ) return true;
                    }
                }

                return false;
            }
        },
    }
</script>

<style lang="scss">
    .clients {
        .clients-header {
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