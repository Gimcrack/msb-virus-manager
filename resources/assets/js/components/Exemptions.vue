<template>
    <page
        type="exemption"
        page-heading="Exemptions"
        order="pattern"
        :header-columns="headerColumns"
        :collection="collection"
        @created=""
        @deleted=""
    >
        <div slot="help_text">
            Exemptions are whitelisted patterns, files, and path fragments.
        </div>
    </page>
</template>

<script>
    export default {
        data() {
            return {
                headerColumns : [
                    'id',
                    'pattern',
                    'published'
                ],

                collection : {
                    type : 'exemption',
                    endpoint : 'exemptions',
                    channel : 'exemptions',
                    created : 'ExemptionWasCreated',
                    destroyed : 'ExemptionWasDestroyed'
                },
            }
        },
    }
</script>