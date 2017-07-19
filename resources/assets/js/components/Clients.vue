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
                        '__blank__',
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
            }
        },
    }
</script>