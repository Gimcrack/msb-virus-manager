<template>
    <div id="home-container" class="container">
        <div class="">
            <div class="col-xs-1"> <!-- required for floating -->
                <!-- Nav tabs -->
                <ul class="nav nav-tabs tabs-left">
                  <!-- <li >
                    <a href="#home">
                        <i class="fa fa-fw fa-2x fa-home"></i>
                    </a>
                  </li> -->
                  <li class="active">
                    <a id="clients" @click="nav('clients',$event)">
                        <i class="fa fa-fw fa-2x fa-windows"></i>
                    </a>
                  </li>
                  <li>
                    <a id="files" @click="nav('files',$event)">
                        <i v-show="has_unacknowledged" class="fa fa-exclamation-circle text-danger fa-badge"></i>
                        <i class="fa fa-fw fa-2x fa-bug"></i>
                    </a>
                  </li>
                  <li>
                    <a id="exemptions" @click="nav('exemptions',$event)">
                        <i class="fa fa-fw fa-2x fa-check"></i>
                    </a>
                  </li>
                  <li>
                    <a id="patterns" @click="nav('patterns',$event)">
                        <i class="fa fa-fw fa-2x fa-shield"></i>
                    </a>
                  </li>
                  <li>
                    <a id="custom" @click="nav('custom',$event)" class="relative">
                        <i class="fa fa-plus-circle text-success fa-badge"></i>
                        <i class="fa fa-fw fa-shield fa-2x"></i>
                    </a>
                  </li>
                  <li>
                    <a id="logs" @click="nav('logs',$event)">
                        <i class="fa fa-fw fa-2x fa-file-text-o"></i>
                    </a>
                  </li>
                  <li>
                    <a id="users" @click="nav('users',$event)">
                        <i class="fa fa-fw fa-2x fa-users"></i>
                    </a>
                  </li>
                </ul>
            </div>

            <div class="col-xs-11">
                <!-- Tab panes -->
                <div class="tab-content">
                    <!-- <div class="tab-pane " id="home">Home</div> -->
                    <div class="tab-pane active" id="clients">
                        <div>
                            <clients></clients>
                        </div>
                    </div>
                    <div class="tab-pane" id="files">
                        <div>
                            <matches></matches>
                        </div>
                    </div>
                    <div class="tab-pane" id="exemptions">
                        <div>
                            <exemptions></exemptions>
                        </div>
                    </div>
                    <div class="tab-pane" id="patterns">
                        <div>
                            <definitions></definitions>
                        </div>
                    </div>
                    <div class="tab-pane" id="custom">
                        <div>
                            <patterns></patterns>
                        </div>
                    </div>
                    <div class="tab-pane" id="logs">
                        <div>
                            <logs></logs>
                        </div>
                    </div>
                    <div class="tab-pane" id="users">
                        <div>
                            <users></users>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                has_unacknowledged : false
            }
        },

        mounted() {
            Bus.$on('UnacknowledgedMatch', () => {
                this.has_unacknowledged = true;
            });

            Bus.$on('AllMatchedFilesAcknowledged', () => {
                this.has_unacknowledged = false;
            });


            this.setInitialTab();

            window.addEventListener('popstate', (event) => {
                if ( event.state && event.state.tab )
                {
                    this.nav(event.state.tab);
                }
            });
        },

        methods : {

            nav(tab, event) {
                history.pushState( {tab : tab}, `MSB Virus Manager - ${tab.$ucfirst()}`, `/#${tab}`);
                $('.nav-tabs').find('.active').removeClass('active').end().find(`#${tab}`).closest('li').addClass('active');
                $('.tab-content').find('.active').removeClass('active').end().find(`#${tab}`).addClass('active');
            },

            setInitialTab() {
                let tab = location.hash.replace('#','');
                if ( !! tab )
                {
                    this.nav( tab );
                }
            }
        },
    }
</script>

<style lang="less">
    #home-container {
        background: white;
        width: 1400px;
        padding: 0;

        .col-xs-1 {
            padding: 0;
        }

        .nav-tabs li:not(.active) a {
            cursor: pointer;
        }
    }
</style>