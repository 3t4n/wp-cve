wp.ImgSlider = 'undefined' === typeof( wp.ImgSlider ) ? {} : wp.ImgSlider;

(function( $, ImgSlider ){

    var ImgSliderItemsCollection = Backbone.Collection.extend({
        maxFiles: 30,
        updateInterval: false,

        initialize: function() {
            // Listen to remove items from collections
            this.listenTo( this, 'remove', $.proxy( wp.ImgSlider.Save.checkSave, wp.ImgSlider.Save ) );
            this.listenTo( this, 'add', $.proxy( wp.ImgSlider.Save.checkSave, wp.ImgSlider.Save ) );
        },

        modelId: function( attrs ) {
            return attrs.id;
        },

        moveItem: function( model, index ){
            var currentIndex = this.indexOf( model );

            if ( currentIndex != index ) {
                // silence this to stop excess event triggers
                this.remove(model, {silent: true}); 
                this.add(model, {at: index-1});
            }

        },

        addItem: function( model ) {

            if ( this.length > 29 ) {
                var removedItem = this.at( 0 );
                removedItem.delete();
            }
            this.add( model );

        },

    });

    var ImgSliderItem = Backbone.Model.extend( {

        /**
        * Defaults
        * As we always populate this model with existing data, we
        * leave these blank to just show how this model is structured.
        */
        defaults: {
            'id':          '',
            'title':       '',
            'description': '',
            'alt':         '',
            'link':        '',
            'halign':      '',
            'valign':      '',
            'target':      '',
            'src':         '',
            'type':        'image',
            'width':       2,
            'height':      2,
            'full' :       '',
            'thumbnail':   '',
            'resize':      false,
            'index':       '',
            'orientation': 'landscape'
        },
        updateInterval: false,

        initialize: function( args ){


      		// Check if wp.ImgSlider.Items exist
      		ImgSlider.Items = 'undefined' === typeof( ImgSlider.Items ) ? new ImgSlider.items['collection']() : ImgSlider.Items;

      		// Add this model to items
      		ImgSlider.Items.addItem( this );

      		// Set collection index to this model
      		this.set( 'index', ImgSlider.Items.indexOf( this ) );

            // Create item HTML
            var view = new ImgSlider.items['view'](
                { model: this, 'id' : 'portfolio-wp-item-' + this.get('id') }
                );
         
      		this.set( 'view', view );

            if ( 'custom-grid' == ImgSlider.Settings.get( 'type' ) ) {
                this.set( 'resize', true );
                this.resize();
            }

        },

        getAttributes: function(){
            var attributes = this.toJSON(),
                data = {};

            jQuery.each( attributes, function( attribute, value ){
                if ( 'object' != typeof value ) {
                    data[ attribute ] = value;
                }
            });

            return data;
        },

        resize: function() {
            var size = ImgSlider.Resizer.get( 'size' ),
                gutter = ImgSlider.Resizer.get( 'gutter' ),
                columns = ImgSlider.Resizer.get( 'columns' ),
                currentWidth = this.get( 'width' ),
                currentHeight = this.get( 'height' ),
                view = this.get( 'view' ),
                width, height;

            // We will check to see if the image columns is bigger than container columns.
            if ( parseInt( currentWidth ) > parseInt( columns ) ) {
                this.set( 'width', columns );
                currentWidth = columns;
            }

            if ( 'custom-grid' == ImgSlider.Settings.get( 'type' ) ) {

                // We will calculate item width and height based on new gutter and columns
                width = ( size * currentWidth ) + ( ( currentWidth - 1 ) * gutter );
                height = ( size * currentHeight ) + ( ( currentHeight - 1 ) * gutter );

                view.$el.width( width );
                view.$el.height( height );

            }else{

                width = ( size * 2 ) +  gutter;

                view.$el.width( width );
                view.$el.height( width );

            }

            // We need to render our view with new attributes
            this.get( 'view' ).render();

        },

        delete: function(){

        	this.trigger('destroy', this, this.collection, {});
        	this.get( 'view' ).remove();
            ImgSlider.GalleryView.resetPackary();

        },

    } );

    var ImgSliderItemView = Backbone.View.extend({

    	/**
        * The Tag Name and Tag's Class(es)
        */
        tagName:    'div',
        className:  'img-slider-single-image',
        fitTimeout: false,
        id: '',

    	/**
        * Template
        * - The template to load inside the above tagName element
        */
        template:   wp.template( 'portfolio-image' ),

        /**
        * Events
        * - Functions to call when specific events occur
        */
    	events: {
    		'click .img-slider-edit-image'  :   'editImage',
    		'click .img-slider-delete-image':   'deleteImage',
            'resize'                    :   'resizeImage',
            'resizestop'                :   'resizeStop',
            'ImgSlider:updateIndex'        :   'updateIndex',
        },

        initialize: function( args ) {

            // append element to DOM
            ImgSlider.GalleryView.container.append( this.render().$el );

        	// Listen if we need to enable/disable resize.
            this.listenTo( ImgSlider.Settings, 'change:type', this.checkSettingsType );

            // Listen to remove items from collections
            this.listenTo( ImgSlider.Items, 'remove', this.actualizeIndex );

            // Enable current gallery type
            this.checkGalleryType( ImgSlider.Settings.get( 'type' ) );

            if ( this.model.get( 'resize' ) ) {
                ImgSlider.GalleryView.container.packery( 'appended', this.$el );
                ImgSlider.GalleryView.container.packery();
            }

        },

        editImage: function( event ){
        	event.preventDefault();
        	ImgSlider.EditModal.open( this.model );
        },

        deleteImage: function( event ){
        	event.preventDefault();

        	this.model.delete();
        },

        checkSettingsType: function( model, value ) {
            this.checkGalleryType( value );
        },

        checkGalleryType: function( type ) {
            var isResizeble = this.model.get( 'resize' ),
                view = this;

            if ( 'custom-grid' == type && ! isResizeble ) {
                var size = ImgSlider.Resizer.get( 'size' ),
                    gutter = ImgSlider.Resizer.get( 'gutter' ),
                    columns = ImgSlider.Resizer.get( 'columns' ),
                    currentWidth = this.model.get( 'width' ),
                    currentHeight = this.model.get( 'height' ),
                    width, height;

                view.model.set( 'resize', true );

                width = ( size * currentWidth ) + ( ( currentWidth - 1 ) * gutter );
                height = ( size * currentHeight ) + ( ( currentHeight - 1 ) * gutter );

                this.$el.draggable();
                this.initResizable();
                
                this.$el.height( height );
                this.$el.width( width );

                ImgSlider.GalleryView.bindDraggabillyEvents( view.$el );
                ImgSlider.GalleryView.resetPackary();
                
            }else if ( 'custom-grid' != type && isResizeble ) {
                this.destroyResizible();
            }

            view.render();

        },

        initResizable: function(){
            var size = ImgSlider.Resizer.get( 'size' );

            this.$el.resizable({
                handles: { 
                    'se': this.$('.segrip'), 
                },
                minHeight: size,
                minWidth: size,
                maxWidth: ImgSlider.Resizer.get( 'containerSize' ),
                helper: "ui-resizable-helper",
            });
        },

        resizeImage: function( event, ui ) {

            $(event.target).css('z-index','999');

            var snap_width = ImgSlider.Resizer.calculateSize( ui.size.width );
            var snap_height = ImgSlider.Resizer.calculateSize( ui.size.height );

            // We need to snap the helper to a grid
            ui.helper.width( snap_width );
            ui.helper.height( snap_height );

            // The element will increase normally 
            ui.element.width( ui.size.width );
            ui.element.height( ui.size.height );

        },

        resizeStop: function( event, ui ) {
            $(event.target).css('z-index','auto');

            var width = ui.size.width;
            var height = ui.size.height;
            var newWidth = ImgSlider.Resizer.calculateSize( width );
            var newHeight = ImgSlider.Resizer.calculateSize( height );

            this.$el.width( newWidth );
            this.$el.height( newHeight );

            // Update Model Width & height
            this.model.set( 'width', ImgSlider.Resizer.getSizeColumns( width ) );
            this.model.set( 'height', ImgSlider.Resizer.getSizeColumns( height ) );

            // Render our view in order to update  width/height.
            this.render();

            // Save Image
            wp.ImgSlider.Save.saveImage( this.model.get( 'id' ) );
            ImgSlider.GalleryView.resetPackary();
        },

        destroyResizible: function() {

            this.model.set( 'resize', false );
            this.$el.draggable( "destroy" );
            this.$el.resizable( "destroy" );
            this.$el.removeAttr("style");

        },

        updateIndex: function( event, data ) {

            this.model.set( 'index', data.index );
            ImgSlider.Items.moveItem( this.model, data.index );
            this.render();

        },

        actualizeIndex: function( event, data ) {
            var currentIndex = this.model.get( 'index' ),
                newIndex = ImgSlider.Items.indexOf( this.model );

            // If is -1 means this views is deleted
            if ( -1 == newIndex ) {
                return;
            }

            // If currentIndex and newIndex are the same that means we don't need to change index
            if ( currentIndex == newIndex ) {
                return;
            }

            this.model.set( 'index', newIndex );
            this.render();

        },

        render: function() {

            // Destroy resizable
            if ( this.$el.is('.ui-resizable') ) {
                this.$el.resizable( "destroy" );
            }

        	// Get HTML
            this.$el.html( this.template( this.model.attributes ) );

            // Enable Resizeble
            if ( this.model.get( 'resize' ) ) {
                this.initResizable();
            }

            // Return
            return this;
        	
        }

    });

    ImgSlider.items = {
        'collection' : ImgSliderItemsCollection,
        'model' : ImgSliderItem,
        'view' : ImgSliderItemView
    };

}( jQuery, wp.ImgSlider ))
