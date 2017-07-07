
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

window.Bus = new Vue();

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// components
Vue.component('home', require('./components/Home.vue'));
Vue.component('page', require('./components/Page.vue'));
Vue.component('item', require('./components/Item.vue'));
Vue.component('vinput', require('./components/Vinput.vue'));
Vue.component('flash', require('./components/Flash.vue'));
Vue.component('newBuild', require('./components/NewBuild.vue'));
Vue.component('newExemptionFromMatch', require('./components/NewExemptionFromMatch.vue'));
Vue.component('agentBuildStatus', require('./components/AgentBuildStatus.vue'));
Vue.component('definitionsStatus', require('./components/DefinitionsStatus.vue'));
Vue.component('newFilesShortcut', require('./components/NewFilesShortcut.vue'));
Vue.component('headerSortButton', require('./components/HeaderSortButton.vue'));
Vue.component('exemptions', require('./components/Exemptions.vue'));
Vue.component('exemption', require('./components/Exemption.vue'));
Vue.component('matches', require('./components/Matches.vue'));
Vue.component('match', require('./components/Match.vue'));
Vue.component('definitions', require('./components/Definitions.vue'));
Vue.component('definition', require('./components/Definition.vue'));
Vue.component('patterns', require('./components/Patterns.vue'));
Vue.component('customPattern', require('./components/Pattern.vue'));
Vue.component('clients', require('./components/Clients.vue'));
Vue.component('client', require('./components/Client.vue'));
Vue.component('logs', require('./components/Logs.vue'));
Vue.component('log', require('./components/Log.vue'));
Vue.component('users', require('./components/Users.vue'));
Vue.component('user', require('./components/User.vue'));

const app = new Vue({
    el: '#app'
});

window.flash = {
    success(message) {
        Bus.$emit('flash', { message, type : 'success' } );
    },

    warning(message) {
        Bus.$emit('flash', { message, type : 'warning' } );
    },

    danger(message) {
        Bus.$emit('flash', { message, type : 'danger' } );
    },

    error(message) {
        Bus.$emit('flash', { message, type : 'danger' } );
    },
}
