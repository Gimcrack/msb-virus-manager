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
                    Reset Admin Password
                </a>
            </li>
            <li>
                <a href="#" @click.prevent="destroy">
                    Delete
                </a>
            </li>
        </template>

    </page>
</template>

<script>
    export default {
        data() {
            return {
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
                    }
                },
            }
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
                Api.post(`clients/_delete`, { clients : this.page.toggled.map(o => o.model.id) })
                    .then(this.deleteSuccess, this.error);
            },

            deleteSuccess(response) {
                this.page.busy = false;
            },
        },
    }
</script>