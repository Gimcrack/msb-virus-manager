<template>
    <div v-if="visible" class="client-password-reset-request-wrapper">
        <div class="client-password-reset-request">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span class="text-success">
                        <i class="fa fa-fw fa-exclamation-circle"></i>
                        Reset Client Admin Password
                    </span>
                </div>
                <div class="panel-body">
                    
                    <div class="alert alert-warning"><i class="fa fa-fw fa-exclamation-circle"></i>
                        <strong>Note</strong> You are about to reset the <em>Local Admin Password</em> on the selected computers. Be careful.
                        <h3>Password Requirements</h3>
                        <ul>
                            <li>Must be at least 20 characters</li>
                            <li>Must contain at least 3 of these: Uppercase, Lowercase, Numbers, or Symbols</li>
                        </ul>
                    </div>
                    
                    <p>
                        <label for="clients">Clients</label>
                        <textarea id="clients" rows="6" v-model="client" placeholder="Client(s)" class="form-control full"></textarea>
                    </p>

                    <p>
                        <label for="clients">Master Password (Required For Client Password Resets)</label>
                        <input v-model="master_password" placeholder="Master Password" type="password" required="true" class="form-control full">
                    </p>

                    <p>
                        <input v-model="password" placeholder="New Password" type="password" required="true" class="form-control full">
                    </p>
                    <p>
                        <input v-model="password_confirmation" placeholder="Confirm" type="password" required="true" class="form-control full">
                    </p>
                    
                </div>
                <div class="panel-footer">
                    <div class="btn-group">
                        <button :disabled="busy" :class="{disabled:busy}" @click.prevent="submit" class="btn btn-success btn-outline">Go</button>
                        <button :disabled="busy" :class="{disabled:busy}" type="button" @click.prevent="cancel" class="btn btn-danger btn-outline">Cancel</button>
                    </div>
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
                visible : false,
                password : null,
                password_confirmation : null,
                master_password : null,
                client : null,
                busy : false
            }
        },

        computed : {
            clients() {
                return this.client.split('\n');
            }
        },

        methods : {
            listen() {
                Bus.$on('ShowClientPasswordResetForm', (event) => {
                    this.resetForm();
                    if ( !! event.clients ) this.client = event.clients.map(o => o.model.name).join('\n');
                    this.show();
                })
            },

            show() {
                this.visible = true;
            },

            cancel() {
                this.visible = false;
                this.resetForm();
            },

            resetForm() {
                this.busy = false;
                this.password = null;
                this.password_confirmation = null;
                this.master_password = null;
                this.client = null;
            },

            submit() {
                return swal({
                  title: `Confirm Password Reset`,
                  text: "Are you sure you want to reset the password on the selected client?",
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#bf5329",
                  confirmButtonText: `Yes, reset the admin password.`,
                }).then( this.resetPassword, this.ignore );
            },

            route() {
                if ( this.clients.length == 1) return this.resetPassword();

                return this.resetMany();
            },

            resetPassword() {
                this.busy = true;

                Api.post(`clients/${this.client}/admin-password-reset`, { 
                        password : this.password, 
                        password_confirmation : this.password_confirmation,
                        master_password : this.master_password,
                    })
                    .then( this.success, this.error );
            },

            resetMany() {
                this.busy = true;

                Api.post(`admin-password-reset`, { 
                        clients : this.clients,
                        password : this.password, 
                        password_confirmation : this.password_confirmation,
                        master_password : this.master_password, 
                    })
                    .then( this.success, this.error );
            },

            ignore() {

            },

            success() {
                flash.success('Password Reset Requested')

                this.busy = false;
                this.visible = false;
            },

            error(error) {
                let message = ( !! error.response.data.password ) ? error.response.data.password[0]  : 'There was an error performing the operation. See the console for more details';
                flash.error(message);
                console.error(error.response);

                this.busy = false;
            },
        }


    }
</script>

<style lang="less">
    .client-password-reset-request {
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

    .client-password-reset-request-wrapper {
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