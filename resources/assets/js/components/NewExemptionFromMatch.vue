<template>
    <div v-if="visible" class="new-exemption-from-match-wrapper">
        <div class="new-exemption-from-match">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span class="text-success">
                        <i class="fa fa-fw fa-check"></i>
                        New Exemption
                    </span>
                </div>
                <div class="panel-body">
                    
                    <table class="table table-striped">
                        <tr>
                            <th nowrap="nowrap">Client</th>
                            <td>{{ match.client.name }}</td>
                        </tr>
                        <tr>
                            <th nowrap="nowrap">Pattern Matched</th>
                            <td>{{ match.pattern.name }}</td>
                        </tr>
                        <tr>
                            <th nowrap="nowrap">File Matched</th>
                            <td>{{ match.file }}</td>
                        </tr>
                        <tr>
                            <th nowrap="nowrap">Times Matched</th>
                            <td>{{ match.times_matched }}</td>
                        </tr>
                    </table>

                    <h3>
                        What do you want to make exempt?
                    </h3>
                    <div class="alert alert-warning"><i class="fa fa-fw fa-exclamation-circle"></i>
                        Don't make it too broad, you risk exempting legitimate threats.
                    </div>
                    <p>
                        <button :disabled="busy" :class="{disabled:busy}" type="button" @click.prevent="exempt(match.pattern.name)" class="btn btn-success btn-outline full-width">
                            Pattern: {{ match.pattern.name }}
                        </button>
                    </p>
                    <p>
                        <button :disabled="busy" :class="{disabled:busy}" type="button" @click.prevent="exempt(filename)" class="btn btn-success btn-outline full-width">
                            Filename: {{ filename }}
                        </button>
                    </p>
                    <p>
                        <button :disabled="busy" :class="{disabled:busy}" type="button" @click.prevent="exempt(match.file)" class="btn btn-success btn-outline full-width">
                            Full Path: {{ match.file }}
                        </button>
                    </p>
                    <p>
                        <button :disabled="busy" :class="{disabled:busy}" type="button" @click.prevent="exemptPartialPath" class="btn btn-info btn-outline full-width">
                            Partial Path ...
                        </button>                        
                    </p>
                    <p class="partial-path-form" v-if="show_partial_path">
                        <input v-model="exemption_pattern" placeholder="Partial Path" type="text" class="form-control">
                        <button :disabled="busy" :class="{disabled:busy}" @click.prevent="submit" class="btn btn-success btn-outline">Go</button>
                    </p>
                </div>
                <div class="panel-footer">
                    <!-- <div class="btn-group"> -->
                    <button :disabled="busy" :class="{disabled:busy}" type="button" @click.prevent="cancel" class="btn btn-danger btn-outline">Cancel</button>
                    <!-- </div> -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.listen();
        },

        data() {
            return {
                match : null,
                visible : false,
                exemption_pattern : null,
                show_partial_path : false,
                busy : false
            }
        },

        computed : {
            filename() {
                if ( this.match.file.indexOf('\\') == -1 ) {
                    return this.match.file.split(' ').pop();
                }

                return this.match.file.split('\\').pop();
            }
        },

        methods : {
            listen() {
                Bus.$on('newExemptionFromMatch', (event) => {
                    this.match = event.match;
                    this.show();
                })
            },

            show() {
                this.visible = true;
            },

            cancel() {
                this.busy = false;
                this.match = null;
                this.visible = false;
                this.exemption_pattern = null;
                this.show_partial_path = false;
            },

            exemptPartialPath() {
                this.exemption_pattern = this.match.file;
                this.show_partial_path = true;
            },

            exempt(pattern) {
                this.exemption_pattern = pattern;
                this.submit();
            },

            submit() {
                return swal({
                  title: `Create Exemption`,
                  text: this.exemption_pattern,
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#bf5329",
                  confirmButtonText: `Yes, create the exemption.`,
                }).then( this.createExemption, this.ignore );
            },

            createExemption() {
                this.busy = true;

                Api.post('exemptions', { pattern : this.exemption_pattern })
                    .then( this.mute, this.errorDuplicate );
            },

            errorDuplicate() {
                flash.error('Oops. That pattern may be exempt already.');
                this.busy = false;
            },

            mute() {
                Api.post(`clients/${this.match.client.name}/matches/${this.match.id}/mute`)
                    .then(this.updateMatches, this.error)
            },

            updateMatches() {
                Bus.$emit('UpdateMatches');
                this.cancel();
            },

            ignore() {

            },

            error(error) {
                flash.error('There was an error performing the operation. See the console for more details');
                console.error(error);

                this.busy = false;
            },
        }


    }
</script>

<style lang="less">
    .new-exemption-from-match {
        width: 600px;
        min-height: 400px;

        .panel-heading {
            font-size: 24px;
        }

        .panel-footer {
            display: flex;
            justify-content: flex-end;
        }

        .panel-body {
            button {
                font-weight: bold;
            }
        }

        .partial-path-form {
            display: flex;

            input {
                flex: 1;
            }

            * + * {
                margin-left: 15px;
            }
        }
    }

    .new-exemption-from-match-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1000;
        height: 100vh;
        width: 100vw;
        background: rgba(0,0,0,0.3);

        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>