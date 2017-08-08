<template>
    <div v-if="visible" class="item-detail-wrapper">
        <div class="item-detail">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <span class="text-success">
                        <i class="fa fa-fw fa-exclamation-triangle"></i>
                        {{ endpoint.$ucfirst() }} Detail
                    </span>
                </div>
                <div v-if="! busy" class="panel-body">                    
                    <pre><code>{{ detail }}</code></pre>
                </div>
                <div v-else class="panel-body">
                    <i class="fa fa-fw fa-refresh fa-spin fa-5x"></i>
                </div>
                <div class="panel-footer">
                    <div class="btn-group">
                        <button :disabled="busy" :class="{disabled:busy}" type="button" @click.prevent="cancel" class="btn btn-primary btn-outline">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import pretty from 'prettyprint';

    export default {
        mounted() {
            this.listen();
        },

        data() {
            return {
                visible : false,
                busy : false,
                endpoint : null,
                id : null,
                model : {}
            }
        },

        computed : {
            detail() {
                return pretty(this.model);
            }
        },

        methods : {
            listen() {
                Bus.$on('ShowItemDetail', (event) => {
                    this.resetDetail();
                    this.endpoint = event.endpoint;
                    this.id = event.id;
                    this.show();
                    this.fetch();
                });
            },

            show() {
                this.visible = true;
            },

            cancel() {
                this.visible = false;
                this.resetDetail();
            },

            resetDetail() {
                this.busy = false;
                this.endpoint = null;
                this.id = null;
                this.model = {};
            },

            fetch() {
                this.busy = true;

                Api.get(`${this.endpoint}/${this.id}`)
                    .then(this.success,this.error);
            },

            ignore() {

            },

            success( response ) {
                this.busy = false;
                this.model = response.data;
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
    .item-detail {
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

    .item-detail-wrapper {
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