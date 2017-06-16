export default {
    
    mounted() {
        this.listen();
        this.fetch();
    },

    methods : {

        fetch() {
            if ( !! this.preFetch )
                this.preFetch();

            Api.get( this.collection.endpoint )
                .then( this.success, this.error ) 
        },

        success(response) {
            this.models = response.data;

            if ( !! this.postSuccess )
                this.postSuccess();
        },

        error(error) {
            flash.error('There was an error performing the operation. See the console for more details');
            console.error(error);

            if ( !! this.postError )
                this.postError();
        },

        findModelById(id) {
            return this.models.findIndex( (model) => {
                return model.id === id;
            });
        },

        remove(model) {
            let index = this.findModelById(model.entity.id);
            if ( index > -1 ) this.models.$remove(index);

            flash.warning(`${model.type} Removed: ${model.name}`)
        },

        add( model ) {
            this.models.push(model.entity);

            flash.success(`New ${model.type}: ${model.name}`);
        },

        model( event ) {
            let type = this.collection.type,
                entity = event[type],
                friendly = this.collection.model_friendly || 'name';

            return {
                entity,
                type : type.$ucfirst(),
                name : entity[friendly]
            }
        },

        sortBy(key) {
            if ( key == this.orderBy ) {
                this.asc = ! this.asc;
                console.log('swapping');
            }
            this.orderBy = key;
        },

        listen() {
            Echo.channel(this.collection.channel)
                .listen( this.collection.created, (event) => {
                    this.add( this.model(event) );

                    if ( !! this.postCreated )
                        this.postCreated(event);
                })
                .listen( this.collection.destroyed, (event) => {
                    this.remove( this.model(event) );

                    if ( !! this.postDeleted )
                        this.postDeleted(event);
                });
        },
    }
}