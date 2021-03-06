<template>
    <page
        :params="details"
        :toggles="toggles"
        @created="created"
        @deleted="deleted"
    >
        <template slot="selection-dropdown-menu">
            <li>
                <a href="#" @click.prevent="resetAdminPasswordSelected">
                    Reset Client Admin Password
                </a>
            </li>
            <li>
                <a href="#" @click.prevent="upgradeSelected">
                    Upgrade Clients
                </a>
            </li>
            <li>
                <a href="#" @click.prevent="scanSelected">
                    Scan Clients
                </a>
            </li>
            <!-- <li>
                <a href="#" @click.prevent="batchUpdateSelected">
                    Show Batch File
                </a>
            </li> -->
            <li>
                <a href="#" @click.prevent="destroy">
                    Delete Clients
                </a>
            </li>
        </template>

        <template slot="menu">
            <button @click.prevent="toggleUptodate" class="btn btn-primary">
                <i class="fa fa-fw fa-check" :class="[ show_up_to_date ? 'fa-toggle-on' : 'fa-toggle-off', { active : show_up_to_date } ]"></i>
                Toggle Up-To-Date
            </button>
        </template>

    </page>
</template>

<script>
    export default {
        data() {
            return {

                show_up_to_date : true,
                current_version : null,

                toggles : {
                    new : false,
                },

                details : {
                    columns : [
                        'id',
                        'name',
                        'version',
                        {
                            title : 'Status',
                            key : 'scanned_files_current'
                        },{
                            title : 'Recent Password',
                            key : 'password_reset_recently'
                        },{
                            title : 'OS',
                            key : 'os'
                        },{
                            title : 'Heartbeat',
                            key : 'heartbeat_at'
                        }
                    ],
                    type : 'client',
                    heading : 'Clients',
                    endpoint : 'clients',
                    help : 'Clients will self-register here once the agent has been installed.',
                    events : {
                        channel : 'clients',
                        created : 'ClientWasCreated',
                        destroyed : 'ClientWasDestroyed',
                    },
                    reject : {
                        placeholder : 'some-nonsense-value'
                    }
                },
            }
        },

        mounted() {
            Bus.$on('AgentBuild', event => this.current_version = event.build);
        },

        methods : {

            created(event) {
                Bus.$emit('ShouldFetchAgentBuild');
            },

            deleted(event) {
                Bus.$emit('ShouldFetchAgentBuild');
            },

            resetAdminPasswordSelected() {
                Bus.$emit('ShowClientPasswordResetForm', { clients : this.page.toggled });
            },

            deleteSelected() {
                Bus.$emit('DeleteSelected', { model : 'client', clients : this.page.toggled });
            },

            batchUpdateSelected() {
                Bus.$emit('BatchUpdateSelected', { clients : this.page.toggled });
            },

            destroy() {
                return swal({
                      title: `Remove Selected Clients?`,
                      text: `This cannot be undone.`,
                      type: "warning",
                      showCancelButton: true,
                      confirmButtonColor: "#bf5329",
                      confirmButtonText: `Yes, remove them.`,
                    }
                ).then( this.performDestroy, this.ignore );
            },

            performDestroy() {
                this.page.busy = true;
                Api.post(`clients/_delete`, { clients : this.page.selectedIds() })
                    .then(this.deleteSuccess, this.error);
            },

            deleteSuccess(response) {
                this.page.busy = false;
            },

            upgradeSelected() {
                this.page.busy = true;
                Api.post(`clients/_upgrade`, { clients : this.page.selectedIds() })
                    .then(this.upgradeSuccess, this.error);
            },

            upgradeSuccess() {
                this.page.busy = false;
                flash.success('The selected clients were instructed to upgrade.');
            },

            scanSelected() {
                this.page.busy = true;
                Api.post(`clients/_scan`, { clients : this.page.selectedIds() })
                    .then(this.scanSuccess, this.error);
            },

            scanSuccess() {
                this.page.busy = false;
                flash.success('The selected clients were instructed to scan.');
            },

            toggleUptodate() {
                this.show_up_to_date = ! this.show_up_to_date;

                this.details.reject =  ( this.show_up_to_date ) ? {
                    placeholder : 'some-nonsense-value' // this is required or the reject filter won't work
                } : {
                    version : this.current_version
                };
            }
        },
    }
</script>
