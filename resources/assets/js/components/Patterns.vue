<template>
    <page
        type="customPattern"
        page-heading="Patterns"
        order="name"
        :header-columns="headerColumns"
        :collection="collection"
        :toggles="toggles"
        @new="newPattern"
        @created=""
        @deleted=""
    >
        <template slot="help_text">
            Manage custom definitions here.
        </template>
    </page>
</template>

<script>
    

    export default {

        data() {
            return {
                toggles : {
                    new : true,
                },

                headerColumns : [
                    'id',
                    'pattern',
                    'published'
                ],

                collection : {
                    type : 'pattern',
                    endpoint : 'patterns',
                    channel : 'patterns',
                    created : 'PatternWasCreated',
                    destroyed : 'PatternWasDestroyed'
                }
            }
        },

        methods : {

            newPattern() {
                return swal({
                    title: "Custom Definition",
                    inputPlaceholder: "*.xyz",
                    input: "text",
                    showLoaderOnConfirm: true,
                    showCancelButton: true,
                    animation: "slide-from-top",
                })
                .then( this.storeNewPattern, this.ignore );
            },

            storeNewPattern( pattern ) {
                this.busy = true;

                Api.post('patterns', {
                    name : pattern
                })
                .then( this.done, this.error );
            },

            postCreated(event) {

            },

            postDeleted(event) {
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