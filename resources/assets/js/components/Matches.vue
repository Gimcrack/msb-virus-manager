<template>
    <page
        :params="details"
        :toggles="toggles"
        @new="create"
        @created="created"
        @deleted="deleted"
    >
        <template slot="menu">
            <button v-show="has_unacknowledged" :class="{ disabled : page.busy }" @click.prevent="ackAll" :disabled="page.busy" class="btn btn-success btn-outline">
                <i class="fa fa-fw fa-check" :class="{'fa-spin' : page.busy}"></i>
                Acknowledge All
            </button>
        </template>

    </page>
</template>

<script>
    export default {
        data() {
            return {
                page : {
                    busy : false
                },

                details : {
                    columns : [
                        '__blank__',
                        'id',
                        'client',
                        'pattern',
                        'file',
                        'times_matched',
                        'last_match',
                        {
                            title : 'Status',
                            key : 'muted_flag'
                        },
                    ],
                    type : 'matched_file',
                    component : 'match',
                    heading : 'Identified Threats',
                    order : 'id',
                    orderDir: 'desc',
                    model_friendly : 'file',
                    endpoint : 'matches',
                    help : 'These files match blacklisted patterns',
                    events : {
                        channel : 'matches',
                        created : 'MatchedFileWasCreated',
                        destroyed : 'MatchedFileWasDestroyed',
                        other : {
                            AllMatchedFilesAcknowledged() {
                                Bus.$emit('AllMatchedFilesAcknowledged');
                            }
                        }
                    }
                },
                toggles : {
                    new : false
                },
                has_unacknowledged : false
            }
        },

        mounted() {
            Bus.$on('UnacknowledgedMatch', () => {
                this.has_unacknowledged = true;
            })

            Bus.$on('AllMatchedFilesAcknowledged', () => {
                this.has_unacknowledged = false;
            })
        },

        methods : {

            ackAll() {
                this.page.busy = true;

                Api.post('matches/acknowledge')
                    .then(this.doneAckAll, this.page.error)
            },

            doneAckAll() {
                this.page.busy = false;
                this.has_unacknowledged = false;
            },

            create() {
                return swal({
                    title: "New Exemption",
                    text : "What is the pattern?",
                    inputPlaceholder: "*.xyz",
                    input: "text",
                    showLoaderOnConfirm: true,
                    showCancelButton: true,
                    animation: "slide-from-top",
                })
                .then( this.store, this.page.ignore );
            },

            store( pattern ) {
                this.page.busy = true;

                Api.post('exemptions', { pattern })
                    .then( this.page.ignore, this.page.error );
            },

            created(event) {
                this.page.busy = false;
                Bus.$emit('ShouldFetchDefinitions');
            },

            deleted(event) {
                Bus.$emit('ShouldFetchDefinitions');
            },
        },
    }
</script>