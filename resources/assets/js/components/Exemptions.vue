<template>
    <div class="exemptions">
        <div class="exemptions-header">
            <h2>Exemptions</h2>
            <button type="button" @click.prevent="fetch" :disabled="busy" :class="busy ? 'disabled' : ''" class="btn-primary btn">
                <i class="fa fa-fw fa-circle-o-notch" :class="{'fa-spin' : busy}"></i>
                {{ refresh_btn_text }}
            </button>
            <input type="text" placeholder="Search Exemptions..." v-model="search" class="form-control">
        </div>
        <div class="alert alert-info">
            Exemptions are whitelisted patterns, files, and path fragments.
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Pattern</th>
                    <th>Published</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="exemption in filtered_exemptions" >
                    <exemption :exemption="exemption" :key="exemption.id"></exemption>
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
                exemptions : [],
                refresh_btn_text : 'Refresh',
                search : null,
            }
        },

        computed : {
            filtered_exemptions() {
                if ( ! this.search ) return this.exemptions;

                return _.sortBy( _.filter( this.exemptions, (exemption) => { 
                    return this.searchDefinition( exemption );
                }), 'name');
            }
        },

        methods : {
            fetch() {
                this.busy = true;
                this.refresh_btn_text = 'Refreshing';

                Api.get("exemptions")
                    .then( this.process, this.error )
            },

            findExemptionById(id) {
                return this.exemptions.findIndex( (exemption) => {
                    return exemption.id === id;
                });
            },

            deleteExemptionById(id) {
                let index = this.findExemptionById(id);
                if ( !! index ) this.exemptions.$remove(index);
            },

            process(response) {
                this.exemptions = response.data;
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

                Echo.channel(`exemptions`)
                    .listen("ExemptionWasCreated", (event) => {
                        this.exemptions.push(event.exemption);

                        flash.success(`New Exemption Registered: ${event.exemption.pattern}`);
                        Bus.$emit('ShouldFetchDefinitions');
                    })
                    .listen("ExemptionWasDestroyed", (event) => {
                        this.deleteExemptionById(event.exemption.id);
                        flash.warning(`Exemption Removed: ${event.exemption.name}`);
                        Bus.$emit('ShouldFetchDefinitions');
                    });

            },

            searchExemption( exemption ) {
                if ( ! this.search ) return true;

                for ( let prop in exemption )
                {
                    if ( typeof exemption[prop] === "string" ) 
                    {
                        if ( exemption[prop].toLowerCase().indexOf( this.search.toLowerCase() ) !== -1 ) return true;    
                    }
                    else 
                    {
                        if ( this.searchExemption( exemption[prop] ) ) return true;
                    }
                }

                return false;
            }
        },
    }
</script>

<style lang="scss">
    .exemptions {
        .exemptions-header {
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