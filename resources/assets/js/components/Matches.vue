<template>
    <page
        :params="details"
        :toggles="toggles"
        @new="create"
        @created="created"
        @deleted="deleted"
    >
        <template slot="selection-dropdown-menu">
            <li>
                <a href="#" @click.prevent="muteSelected">
                    Mute Selected
                </a>
            </li>
            <li>
                <a href="#" @click.prevent="unmuteSelected">
                    Unmute Selected
                </a>
            </li>
            <li>
                <a href="#" @click.prevent="exemptSelectedFilenames">
                    Exempt Selected - Filenames
                </a>
            </li>
            <li>
                <a href="#" @click.prevent="exemptSelectedFiles">
                    Exempt Selected - Full Paths
                </a>
            </li>
        </template>

        <template slot="menu">
            <button v-show="has_unacknowledged" :class="{ disabled : page.busy }" @click.prevent="ackAll" :disabled="page.busy" class="btn btn-success btn-outline">
                <i class="fa fa-fw fa-check" :class="{'fa-spin' : page.busy}"></i>
                Acknowledge All
            </button>
            <button @click.prevent="toggleMuted" class="btn btn-primary">
                <i class="fa fa-fw fa-check" :class="[ show_muted ? 'fa-toggle-on' : 'fa-toggle-off', { active : show_muted } ]"></i>
                Toggle Muted
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

                show_muted : false,

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
                    },
                    where : {
                        muted_flag : false
                    },
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
            });

            Bus.$on('AllMatchedFilesAcknowledged', () => {
                this.has_unacknowledged = false;
            });

            // Bus.$on('UpdateMatches', () => {
            //     this.page.fetch();
            // });
        },

        methods : {

            muteSelected() {
                this.page.busy = true;
                Api.post(`matches/_mute`, { matches : this.page.selectedIds() })
                    .then(this.muteSuccess, this.error);
            },

            muteSuccess() {
                this.page.busy = false;
                flash.success('The matched files were muted');
            },

            unmuteSelected() {
                this.page.busy = true;
                Api.post(`matches/_unmute`, { matches : this.page.selectedIds() })
                    .then(this.unmuteSuccess, this.error);
            },

            unmuteSuccess() {
                this.page.busy = false;
                flash.success('The matched files were unmuted');
            },

            exemptSelectedFiles() {
                this.page.busy = true;
                Api.post(`matches/_exempt`, { matches : this.page.selectedIds() })
                    .then(this.exemptSelectedFilesSuccess, this.error);
            },

            exemptSelectedFilesSuccess() {
                this.page.busy = false;
                flash.success('The matched files were exempted');
            },

            exemptSelectedFilenames() {
                this.page.busy = true;
                Api.post(`matches/_exempt/filename`, { matches : this.page.selectedIds() })
                    .then(this.exemptSelectedFilenamesSuccess, this.error);
            },

            exemptSelectedFilenamesSuccess() {
                this.page.busy = false;
                flash.success('The matched filenames were exempted');
            },

            toggleMuted() {
                this.show_muted = ! this.show_muted;

                this.details.where =  ( this.show_muted ) ? {} : { muted_flag : false };
            },

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
