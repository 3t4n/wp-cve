wp.RespAccordionSlider = 'undefined' === typeof( wp.RespAccordionSlider ) ? {} : wp.RespAccordionSlider;

(function( $, RespAccordionSlider ){

    var ImageSliderModal = Backbone.Model.extend( {

        defaults: {
            'item': false,
        },

        initialize: function( args ){

            var modalView = new RespAccordionSlider.modal['view']({
                'model': this,
                'childViews' : args.childViews
            });
            

            var wpMediaView = new wp.media.view.Modal( {
                controller: {
                    trigger: function() {
                    }
                }
            } );

            this.set( 'wpMediaView', wpMediaView );
            this.set( 'ImageSliderModal', modalView );

        },

        open: function( $item ) {
            var wpMediaView = this.get( 'wpMediaView' ),
                ImageSliderModal = this.get( 'ImageSliderModal' );

            // Set the current item
            this.set( 'item', $item );
            // Render RespAccordionSlider View
            ImageSliderModal.render();
            // Append ImageSliderModalView to wpMediaView
            wpMediaView.content( ImageSliderModal );
            // Open wpMediaView
            wpMediaView.open();

        }

    } );

    var ImageSliderModalView = Backbone.View.extend( {

        /**
        * The Tag Name and Tag's Class(es)
        */
        tagName:    'div',
        className:  'edit-attachment-frame mode-select hide-menu hide-router ras-edit-popup',

        /**
        * Template
        * - The template to load inside the above tagName element
        */
        template:   wp.template( 'ras-image-editor' ),

        /**
        * Events
        * - Functions to call when specific events occur
        */
        events: {
            'click .edit-media-header .left':               'loadPreviousItem',
            'click .edit-media-header .right':              'loadNextItem',

            'keyup input':                                  'updateItem',
            'keyup textarea':                               'updateItem',
            'change input':                                 'updateItem',
            'change textarea':                              'updateItem',
            'blur textarea':                                'updateItem',
            'change select':                                'updateItem',

            'click .actions a.ras-gallery-meta-submit':       'saveItem',
            'click .actions a.ras-gallery-meta-submit-close': 'saveItemAndClose',

            'keyup input#link-search':                      'searchLinks',
            'click div.query-results li':                   'insertLink',

        },

        /**
        * Initialize
        *
        * @param object model   RespAccordionSliderImage Backbone Model
        */
        initialize: function( args ) {

            // Define loading and loaded events, which update the UI with what's happening.
            this.on( 'loading', this.loading, this );
            this.on( 'loaded',  this.loaded, this );

            // Change item when model updates his attribute
            this.listenTo( this.model, 'change:item', this.changeItem );

            // Child Views
            this.childViews = args.childViews;

            // Set some flags
            this.is_loading = false;
            this.search_timer = '';
            this.item = false;

        },

        changeItem: function(){
            this.item = this.model.get( 'item' );
        },

        /**
        * Render
        * - Binds the model to the view, so we populate the view's fields and data
        */
        render: function() {

            // Get the current item from model if not exist.
            if ( ! this.item ) {
                this.item = this.model.get( 'item' );
            }

            // Get current Index.
            this.attachment_index = RespAccordionSlider.Items.indexOf( this.item );

            // Get HTML
            this.$el.html( this.template( this.item.toJSON() ) );
            
            if ( this.childViews.length > 0 ) {
                this.childViews.forEach( function( view ) {
                    // Init with model
                    var childView = new view( {
                        model: this.model
                    } );

                    // Render view within our main view
                    this.$el.find( 'div.ras-addons' ).append( childView.render().el );
                }, this );
            }

            // Enable / disable the buttons depending on the index
            if ( this.attachment_index == 0 ) {
                // Disable left button
                this.$el.find( 'button.left' ).addClass( 'disabled' );
            }
            if ( this.attachment_index == ( RespAccordionSlider.Items.length - 1 ) ) {
                // Disable right button
                this.$el.find( 'button.right' ).addClass( 'disabled' );
            }

            // Return
            return this;

        },

        /**
        * Renders an error using
        * wp.media.view.RespAccordionSliderGalleryError
        */
        renderError: function( error ) {

            // Define model
            var model = {};
            model.error = error;

            // Define view
            var view = new wp.media.view.RespAccordionSliderGalleryError( {
                model: model
            } );

            // Return rendered view
            return view.render().el;

        },

        /**
        * Tells the view we're loading by displaying a spinner
        */
        loading: function() {

            // Set a flag so we know we're loading data
            this.is_loading = true;

            // Show the spinner
            this.$el.find( '.spinner' ).css( 'visibility', 'visible' );

        },

        /**
        * Hides the loading spinner
        */
        loaded: function( response ) {

            // Set a flag so we know we're not loading anything now
            this.is_loading = false;

            // Hide the spinner
            this.$el.find( '.spinner' ).css( 'visibility', 'hidden' );

            // Display the error message, if it's provided
            if ( typeof response !== 'undefined' ) {
                this.$el.find( 'div.media-toolbar' ).after( this.renderError( response ) );
            }

        },

        /**
        * Load the previous model in the collection
        */
        loadPreviousItem: function() {

            var item;

            // Decrement the index
            this.attachment_index--;

            // Get the model at the new index from the collection
            item = RespAccordionSlider.Items.at( this.attachment_index );
            this.model.set( 'item', item );

            // Re-render the view
            this.render();

        },

        /**
        * Load the next model in the collection
        */
        loadNextItem: function() {

            var item;

            // Increment the index
            this.attachment_index++;

            // Get the model at the new index from the collection
            item = RespAccordionSlider.Items.at( this.attachment_index );
            this.model.set( 'item', item );
            
            // Re-render the view
            this.render();

        },

        /**
        * Updates the model based on the changed view data
        */
        updateItem: function( event ) {

            // Check if the target has a name. If not, it's not a model value we want to store
            if ( event.target.name == '' ) {
                return;
            }

            // Update the model's value, depending on the input type
            if ( event.target.type == 'checkbox' ) {
                value = ( event.target.checked ? event.target.value : 0 );
            } else {
                value = event.target.value;
            }

            // Update the model
            this.item.set( event.target.name, value );

        },

        /**
        * Saves the image metadata
        */
        saveItem: function( event ) {
            var view,
                self = this,
                item = this.model.get( 'item' );

            event.preventDefault();

            // Tell the View we're loading
            this.trigger( 'loading' );

            // Get item view and render it.
            view = this.item.get( 'view' );
            view.render();

            // Show the user the 'saved' notice for 1.5 seconds
            var saved = this.$el.find( '.saved' );
            saved.fadeIn();

            wp.RespAccordionSlider.Save.saveImage( item.get( 'id' ), function(){
                // Tell the view we've finished successfully
                self.trigger( 'loaded loaded:success' );
                saved.fadeOut();
            });

        },

        saveItemAndClose: function ( event ){
            var view,
                self = this;

            event.preventDefault();

            // Tell the View we're loading
            this.trigger( 'loading' );

            clearInterval( wp.RespAccordionSlider.Save.updateInterval );
            wp.RespAccordionSlider.Save.saveImages( function(){
                // Get item view and render it.
                view = self.model.get( 'wpMediaView' );
                view.close();
            });

        },

        /**
        * Searches Links
        */
        searchLinks: function( event ) {
        },

        /**
        * Inserts the clicked link into the URL field
        */
        insertLink: function( event ) {
        },

    } );

    RespAccordionSlider.modal = {
        'model' : ImageSliderModal,
        'view' : ImageSliderModalView
    };

}( jQuery, wp.RespAccordionSlider ))
