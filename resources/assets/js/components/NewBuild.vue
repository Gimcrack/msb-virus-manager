<template>
    <button id="new-build" @click.prevent="build" :disabled="busy" class="btn" :class="[ busy ? 'btn-default' : 'btn-success', { 'disabled' : busy} ]">
        <i class="fa fa-fw fa-circle-o-notch" :class="{ 'fa-spin' : busy }"></i> {{ btn_text }}
    </button>
</template>

<script>
    export default {
        
        data() {
            return {
                busy : false,
                btn_text : 'New Build'
            }
        },

        mounted() {
            this.listen();
        },

        methods : {

            listen() {
                Echo.channel('clients')
                    .listen('NewBuild', (event) => {
                        flash.success('New Build Received');
                    })
            },

            build() {
                this.busy = true;
                this.btn_text = 'Sending';

                Api.post('builds')
                    .then( this.success, this.error );
            },

            success(response) {
                this.btn_text = 'Received';

                sleep(1000).then( () => {
                    this.btn_text = 'New Build';
                    this.busy = false;
                    
                });
            },

            error(error) {
                this.busy = false;
                flash.error('There was a problem submitting the new build.')
                console.error(error);
            }
        }
    }
</script>

<style lang="scss">
    #new-build {
        position: fixed;
        bottom: 1em;
        right: 1em;

        z-index: 3;
    }
</style>