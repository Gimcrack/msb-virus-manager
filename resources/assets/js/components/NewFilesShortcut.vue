<template>
    <li v-show="show">
        <p class="navbar-btn">
            <button @click.prevent="navigate" class="btn btn-danger">
                <i class="fa fa-fw fa-bug"></i> New Threats
            </button>
        </p>
    </li>
</template>

<script>
    export default {
        data() {
            return {
                show : false
            }
        },

        mounted() {
            Bus.$on('UnacknowledgedMatch', () => {
                this.show = true;
            });

            Bus.$on('AllMatchedFilesAcknowledged', () => {
                this.show = false;
            });
        },

        methods : {
            navigate() {
                $('[href="#files"]').click();
            }
        }
    }
</script>

<style lang="less">

</style>