<template>
    <div class="alert alert-flash" :class="`alert-${message_type}`" role="alert" v-show="show">
        <i class="fa fa-fw fa-3x" :class="icon"></i> {{ body }}
    </div>
</template>

<script>
    export default {
        props: {
            message : {
                required : true
            },

            type : {
                default : 'success'
            }
        },

        data() {
            return {
                body: '',
                message_type : this.type,
                show: false,
            }
        },

        computed : {
            icon() {
                switch (this.message_type) {
                    case 'success' : return 'fa-check-square-o';
                    case 'warning' : return 'fa-exclamation-triangle';
                    case 'danger' : return 'fa-exclamation-circle';
                }
            }
        },

        mounted() {
            if (this.message) {
                this.flash(this.message);
            }

            Bus.$on('flash', (data) => {
                    this.flash(data);
                }
            );
        },

        methods: {
            flash({ message, type }) {
                
                console.log(message, type);
                this.body = message;
                this.message_type = type;
                this.show = true;

                this.hide();
            },

            hide() {
                sleep(3000).then(() => {
                    this.show = false;
                });
            }
        }
    };
</script>

<style>
    .alert-flash {
        display: flex;
        align-items: center;
        position: fixed;
        right: 1em;
        bottom: 1em;

        z-index: 4;
        margin: 0;
    }
</style>