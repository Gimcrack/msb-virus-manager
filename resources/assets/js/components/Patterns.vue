<template>
    <page
        :params="details"
        :toggles="toggles"
        @new="create"
        @created="created"
        @deleted="deleted"
    ></page>
</template>

<script>
    export default {
        data() {
            return {
                toggles : {
                    new : true,
                },

                details : {
                    columns : [
                        'id',
                        'pattern',
                        'published'
                    ],
                    heading : 'Custom Blacklist',
                    component : 'customPattern',
                    type : 'pattern',
                    endpoint : 'patterns',
                    help : 'Manage custom definitions here.',
                    events : {
                        channel : 'patterns',
                        created : 'PatternWasCreated',
                        destroyed : 'PatternWasDestroyed',
                    },
                    newBtnText : 'New Definition'
                },
            }
        },

        methods : {

            create() {
                return swal({
                    title: "Custom Definition",
                    text : "What is the pattern?",
                    inputPlaceholder: "*.xyz",
                    input: "text",
                    showLoaderOnConfirm: true,
                    showCancelButton: true,
                    animation: "slide-from-top",
                })
                .then( this.store, this.page.ignore );
            },

            store( name ) {
                this.page.busy = true;

                Api.post('patterns', { name })
                    .then( this.page.ignore, this.page.error );
            },

            created(event) {
                this.page.busy = false;
            },

            deleted(event) {
                return swal({
                      title: `Create an exemption?`,
                      text: "Do you want to create an exemption for this definition?",
                      type: "info",
                      showCancelButton: true,
                      confirmButtonColor: "#bf5329",
                      confirmButtonText: "Yes, create an exemption",
                      cancelButtonText: "No"
                    }
                ).then( () => this.performExempt( event.pattern ), this.ignore );
            },

            performExempt(pattern) {
                this.busy = true;
                Api.post(`exemptions`, { pattern : pattern.name })
                    .then( this.done, this.error );
            },
        },
    }
</script>