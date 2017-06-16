<template>
    <div class="definitions">
        <div class="definitions-header">
            <h2>Definitions</h2>
            <button type="button" @click.prevent="fetch" :disabled="busy" :class="busy ? 'disabled' : ''" class="btn-primary btn">
                <i class="fa fa-fw fa-circle-o-notch" :class="{'fa-spin' : busy}"></i>
                {{ refresh_btn_text }}
            </button>
            <input type="text" placeholder="Search Definitions..." v-model="search" class="form-control">
        </div>
        <div class="alert alert-info">
            Definitions are automatically downloaded from the provider.
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Pattern</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(definition, index) in filtered_definitions" >
                    <definition :pattern="definition" :key="definition" :id="index"></definition>
                </template>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.listen();
            this.fetch();
        },

        data() {
            return {
                busy : false,
                definitions : [],
                refresh_btn_text : 'Refresh',
                search : null,
            }
        },

        computed : {
            filtered_definitions() {
                if ( ! this.search ) return this.definitions;

                return _.sortBy( _.filter( this.definitions, (definition) => { 
                    return this.searchDefinition( definition );
                }), 'name');
            }
        },

        methods : {
            fetch() {
                this.busy = true;
                this.refresh_btn_text = 'Refreshing';

                Api.get("definitions")
                    .then( this.process, this.error )
            },

            // findDefinitionByPattern(id) {
            //     return this.definitions.findIndex( (definition) => {
            //         return definition.id === id;
            //     });
            // },

            // deleteDefinitionByPattern(id) {
            //     let index = this.findDefinitionByPattern(id);
            //     if ( !! index ) this.definitions.$remove(index);
            // },

            process(response) {
                this.definitions = response.data.definitions;
                this.refresh_btn_text = 'Refreshed';

                sleep(1000).then( () => {
                    this.refresh_btn_text = 'Refresh';
                    this.busy = false;
                })
            },

            error(error) {
                this.busy = false;
                this.refresh_btn_text = 'Refresh';
                flash.error('There was an error performing the update. See the console for more details').
                console.error(error);
            },

            listen() {

                Bus.$on('ShouldFetchDefinitions', this.fetch)

                // Echo.channel(`definitions`)
                //     .listen("DefinitionWasCreated", (event) => {
                //         this.definitions.push(event.definition);

                //         flash.success(`New Definition Registered: ${event.definition.name}`);
                //     })
                //     .listen("DefinitionWasDestroyed", (event) => {
                //         this.deleteDefinitionByPattern(event.definition);
                //         flash.warning(`Definition Removed: ${event.definition.name}`)
                //     });

            },

            searchDefinition( definition ) {
                if ( ! this.search ) return true;

                if ( definition.indexOf(this.search) !== -1 ) return true;

                return false;
            }
        },
    }
</script>

<style lang="scss">
    .definitions {
        .definitions-header {
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