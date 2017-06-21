export default {
    
    data() {
        return {
            busy : false,
            models : [],
            refresh_btn_text : 'Refresh',
            search : null,
            orderBy : 'name',
            asc : true
        }
    },

    mounted() {
        this.listen();
        this.fetch();
    },

    computed : {
        filtered() {

            let models = _(this.models)
                .filter( this.searchModel )
                .sortBy(this.orderBy);

            return (this.asc) ? models.value() : models.reverse().value();
        }
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

        done(response) {
            this.busy = false;
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
            let index = this.findModelById(model.entity.id);

            // if the model exists, replace it
            if ( index > -1 ) {
                console.log('Updating model');
                return this.models[index] = model.entity;
            }
            else {
                console.log('New model');
                this.models.push(model.entity);
                flash.success(`New ${model.type}: ${model.name}`);
            }
        },

        properType() {
            return this.collection.type.$ucfirst();
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

        searchModel( model ) {
            if ( ! this.search ) return true;

            for ( let prop in model )
            {
                if ( typeof model[prop] === "string" ) 
                {
                    if ( model[prop].toLowerCase().indexOf( this.search.toLowerCase() ) !== -1 ) return true;    
                }
                else 
                {
                    if ( this.searchModel( model[prop] ) ) return true;
                }
            }

            return false;
        },

        // override these on the instance if you want to customize the behavior

        preFetch() {
            this.busy = true;
            this.refresh_btn_text = 'Refreshing';
        },

        postSuccess() {
            this.refresh_btn_text = 'Refreshed';

            sleep(1000).then( () => {
                this.refresh_btn_text = 'Refresh';
                this.busy = false;
            })
        },

        postError() {
            this.busy = false;
            this.refresh_btn_text = 'Refresh';
        },
    }
}