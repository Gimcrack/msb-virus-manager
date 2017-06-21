export default {
    props : [
        'initial'
    ],

    mounted() {
        this.highlight();

        if ( !! this.item.channel )
            this.listen();
    },

    data() {
        return {
            model : this.initial,
            updating : false,
            deleting : false,
            show_menu : false,

            item : {
                key : null,
                type : null,
                endpoint : null,
                channel : null,
                updated : null
            }
        }
    },

    computed : {
        busy() {
            return this.deleting || this.updating;
        }
    },

    methods : {
        highlight() {
            $(this.$refs.row)
                .addClass('hover');

            sleep(2000).then( () => {
                $(this.$refs.row).removeClass('hover');
            });
        },

        destroy() {
            let friendly = this.item.model_friendly || 'name'; 

            return swal({
                  title: `Remove ${this.item.type}: ${this.model[friendly]}?`,
                  text: `This cannot be undone.`,
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#bf5329",
                  confirmButtonText: `Yes, remove this ${this.item.type}.`,
                }
            ).then( this.performDestroy, this.ignore );
        },

        performDestroy() {
            let key = this.item.key || 'id';
            this.deleting = true;
            Api.delete(`${this.item.endpoint}/${this.model[key]}`)
                .then(this.deleteSuccess, this.error);
        },

        deleteSuccess(response) {
            this.deleting = false;
        },

        updateSuccess(response) {
            this.updating = false;
        },

        error(error) {
            flash.error('There was an error performing the operation. See the console for more details');
            console.error(error);

            if ( !! this.postError )
                this.postError();
        },

        model( event ) {
            let type = this.item.type,
                entity = event[type],
                friendly = this.item.model_friendly || 'name';

            return {
                entity,
                type : type.$ucfirst(),
                name : entity[friendly]
            }
        },

        listen() {
            Echo.channel(this.item.channel)
                .listen(this.item.updated, (event) => {
                    let model = this.model(event);

                    this.model = model.entity;
                    this.highlight();

                    flash.success(`${this.item.type} ${model.name} Was Updated`);

                    if ( !! this.postUpdated )
                        this.postUpdated(event);
                });
        },

        ignore() {

        }
    }
}