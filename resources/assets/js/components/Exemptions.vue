<template>
    <page
        :params="details"
        @new="create"
        @created="created"
        @deleted="deleted"
    ></page>
</template>

<script>
    export default {
        data() {
            return {
                details : {
                    columns : [
                        'id',
                        'pattern',
                        'published'
                    ],
                    type : 'exemption',
                    heading : 'Exemptions',
                    order : 'pattern',
                    model_friendly : 'pattern',
                    endpoint : 'exemptions',
                    help : 'Exemptions are whitelisted patterns, files, and path fragments.',
                    events : {
                        channel : 'exemptions',
                        created : 'ExemptionWasCreated',
                        destroyed : 'ExemptionWasDestroyed',
                    }
                },
            }
        },

        methods : {

            create() {
                return swal({
                    title: "New Exemption",
                    text : "What is the pattern?",
                    inputPlaceholder: "*.xyz",
                    input: "text",
                    showLoaderOnConfirm: true,
                    showCancelButton: true,
                    animation: "slide-from-top",
                })
                .then( this.store, this.page.ignore );
            },

            store( pattern ) {
                this.page.busy = true;

                Api.post('exemptions', { pattern })
                    .then( this.page.ignore, this.page.error );
            },

            created(event) {
                this.page.busy = false;
                Bus.$emit('ShouldFetchDefinitions');
            },

            deleted(event) {
                Bus.$emit('ShouldFetchDefinitions');
            },
        },
    }
</script>