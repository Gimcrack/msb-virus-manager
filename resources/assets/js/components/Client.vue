<template>
    <item
        :id="model.id"
        :deleting="deleting"
        :updating="updating"
        :toggles="toggles"
        @view="view"
        @update="update"
        @destroy="destroy"
        @ToggledHasChanged="$emit('ToggledHasChanged')"
    >
        <td>{{ model.name }}</td>
        <td>{{ model.version }}</td>
        <td>{{ scan_status }}</td>
        <td>{{ model.password_reset_recently }}</td>
        <td>{{ os }}</td>
        <td><button @click.prevent="requestHeartbeat" class="heartbeat-status" :class="[ model.heartbeat_status ]"></button> {{ heartbeat }}</td>

        <template slot="menu">
            <button @click.prevent="scanClient" :disabled="busy" class="btn btn-success btn-xs btn-outline" :class="{disabled : busy}">
                <i :class="[ 'fa-bug', {'fa-spin' : updating}]" class="fa fa-fw"></i>
            </button>
        </template>
    </item>
</template>

<script>
    export default {
        mixins : [
            mixins.item
        ],

        computed : {
            updated() {
                return fromNow(this.model.updated_at);
            },

            heartbeat() {
                return this.model.heartbeat_at;
            },

            scan_status() {
                if (this.model.scanned_files_count == this.model.scanned_files_current) {
                    return `${this.model.scanned_files_count} Files Watched`;
                }

                return `Scanned ${this.model.scanned_files_current}/${this.model.scanned_files_count} Files`;
            },

            os() {
                return (this.model.os || '').replace('Microsoft Windows','');
            }
        },

        data() {
            return {
                item : {
                    key : 'name',
                    type : 'client',
                    endpoint : 'clients',
                    channel : `clients.${this.initial.name.toLowerCase()}`,
                    updated : 'ClientWasUpdated',
                    events : [
                        {
                            event : 'ClientWasUpgraded',
                            handler : this.upgradedEvent
                        },
                        {
                            event : 'ClientPasswordResetWasCompleted',
                            handler : this.updatedEvent
                        },
                        {
                            event : 'ClientSentHeartbeat',
                            handler : this.heartbeatEvent
                        }
                    ]
                }
            }
        },

        methods : {
            heartbeatEvent(event) {
                this.updatedEvent(event,true);
            },

            upgradedEvent(event) {
                Bus.$emit('ShouldFetchAgentBuild');
            },

            requestHeartbeat() {
                Api.get(`clients/${this.model.name}/marco`);
            },

            scanClient() {
                this.updating = true;
                Api.post(`clients/${this.model.name}/scan`)
                    .then( this.scanSuccess, this.error );
            },

            update() {
                this.updating = true;
                Api.post(`clients/${this.model.name}/upgrade`)
                    .then( this.upgradeSuccess, this.error );
            },

            upgradeSuccess(response) {
                this.updating = false;

                flash.success('The client was told to upgrade.');
            },

            scanSuccess(response) {
                this.updating = false;

                flash.success('The client was told to scan.');
            },
        }
    }
</script>

<style lang="scss">
    @import "./../../sass/_variables";

    .heartbeat-status {
        border-radius: 50%;
        height: 15px;
        width: 15px;
        margin-top: 5px;

        outline: none !important;

        &.success {
            background: $brand-success;
        }

        &.warning {
            background: $brand-warning;
        }

        &.danger {
            background: $brand-danger;
        }
    }
</style>
