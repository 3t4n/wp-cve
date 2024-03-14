wp.PhotoGallery = 'undefined' === typeof( wp.PhotoGallery ) ? {} : wp.PhotoGallery;

(function( $, PhotoGallery ){

    var PhotoGalleryGalleryResizer = Backbone.Model.extend({
    	defaults: {
            'columns': 12,
            'gutter': 10,
            'containerSize': false,
            'size': false,
        },

        initialize: function( args ){
            var resizer = this;

            this.set( 'containerSize', jQuery( '#photo-gallery-uploader-container .photo-gallery-uploader-inline-content' ).width() );

            // Get options
            this.set( 'gutter', parseInt( wp.PhotoGallery.Settings.get('gutter') ) );

        	// calculate block size.
        	this.generateSize();

            // Listen to column and gutter change
            // this.listenTo( wp.PhotoGallery.Settings, 'change:columns', this.changeColumns );
            this.listenTo( wp.PhotoGallery.Settings, 'change:gutter', this.changeGutter );

            // Listen to window resize
            jQuery( window ).on( 'resize', $.proxy( this.windowResize, this ) );
            new ResizeSensor( jQuery( '#photo-gallery-uploader-container .photo-gallery-uploader-inline-content' ), function() {
                resizer.windowResize();
            });
        },
        
        generateSize: function(){
        	var columns = this.get( 'columns' ),
        		gutter = this.get( 'gutter' ),
        		containerWidth = this.get( 'containerSize' ),
        		size;

        	/* 
        	   We will calculate the size ( width and height, because every item is a square ) of an item.
    		   The formula is : from the container size we will subtract gutter * number of columns and then we will dived by number of columns
        	 */
        	size = Math.floor( ( containerWidth - ( gutter * ( columns - 1 ) ) ) / columns );
        	this.set( 'size', size );
        },
        /* 
           Here we will calculate the new size of the item.
           This will be called after resize event, that means the item is resized and we need to check it.
           currentSize is the new size of the item after we resized it.
         */
        calculateSize: function( currentSize ){
        	var size = this.get( 'size' ),
        		columns = Math.round( currentSize / size ),
        		gutter = this.get( 'gutter' ),
                containerColumns = this.get( 'columns' ),
        		correctSize;

            if ( columns > containerColumns ) {
                columns = containerColumns;
            }

        	correctSize = size * columns + ( gutter * ( columns - 1 ) );
        	return correctSize;
        },

        // Get columns from width/height
        getSizeColumns: function( currentSize ){
            var size = this.get( 'size' );
            return Math.round( currentSize / size );
        },

        windowResize: function() {
            var currentSize = this.get( 'containerSize' ),
                newSize = jQuery( '#photo-gallery-uploader-container .photo-gallery-uploader-inline-content' ).width();

            // Check if container size have been changed.
            if ( currentSize == newSize ) {
                return;
            }

            // Change Container Width
            this.set( 'containerSize', newSize );

            // Resize Items
            this.resizeItems();

        },

        resizeItems: function(){

            // Generate new sizes.
            this.generateSize();

            if ( 'undefined' != typeof wp.PhotoGallery.Items && wp.PhotoGallery.Items.length > 0 ) {

                // Resize all items when gutter or columns have changed.
                wp.PhotoGallery.Items.each( function( item ){
                    item.resize();
                });

            }

            if ( 'custom-grid' == PhotoGallery.Settings.get( 'type' ) ) {
                // Change packary columnWidth & columnHeight
                wp.PhotoGallery.GalleryView.setPackaryOption( 'columnWidth', this.get( 'size' ) );
                wp.PhotoGallery.GalleryView.setPackaryOption( 'rowHeight', this.get( 'size' ) );

                // Update Grid
                wp.PhotoGallery.GalleryView.setPackaryOption( 'gutter', parseInt( this.get( 'gutter' ) ) );

                // Reset Packary
                wp.PhotoGallery.GalleryView.resetPackary();
            }

        },

        changeColumns: function( model, value ){
            this.set( 'columns', value );

            // Resize all gallery items
            this.resizeItems();

        },

        changeGutter: function( model, value ){
            this.set( 'gutter', parseInt( value ) );

            // Resize all gallery items
            this.resizeItems();

        }

    });

    var PhotoGalleryGalleryView = Backbone.View.extend({

    	isSortable : false,
    	isResizeble: false,
        refreshTimeout: false,
        updateIndexTimeout: false,

    	initialize: function( args ) {

    		// This is the container where the gallery items are.
    		this.container = this.$el.find( '.photo-gallery-uploader-inline-content' );

            // Helper Grid container
            this.helperGridContainer = this.$el.parent().find( '.photo-gallery-helper-guidelines-container' );
            this.helperGrid = this.$el.find( '#photo-gallery-grid' );

            // Listen to grid toggle
            this.helperGridContainer.on( 'change', 'input', $.proxy( this.updateSettings, this ) );

    		// Listent when gallery type is changing.
        	this.listenTo( wp.PhotoGallery.Settings, 'change:type', this.checkSettingsType );

        	// Enable current gallery type
        	this.checkGalleryType( wp.PhotoGallery.Settings.get( 'type' ) );

            // Grid
           //this.gridView = new wp.PhotoGallery.previewer['helpergrid']({ 'el' : this.$el.find( '#photo-gallery-grid' ), 'galleryView' : this });

        },

        updateSettings: function( event ) {
            var value,
                setting = event.target.dataset.setting;

            value = event.target.checked ? 1 : 0;

            wp.PhotoGallery.Settings.set( 'helpergrid', value );

            if ( value ) {
                this.helperGrid.hide();
            }else{
                this.helperGrid.show();
            }

        },

        checkSettingsType: function( model, value ) {
        	this.checkGalleryType( value );
        },

        checkGalleryType: function( type ) {

            if ( 'creative-gallery' == type ) {

            	// If resizeble is enable we will destroy it
            	if ( this.isResizeble ) {
            		this.disableResizeble();
            	}

            	// If sortable is not enabled, we will initialize it.
            	if ( ! this.isSortable ) {
            		this.enableSortable();
            	}

                this.helperGridContainer.find( '.photo-gallery-helper-guidelines-wrapper' ).hide();
                this.container.removeClass( 'photo-gallery-custom-grid' ).addClass( 'photo-gallery-creative-gallery' );

            }else if ( 'custom-grid' == type ) {

            	// If sortable is enable we will destroy it
            	if ( this.isSortable ) {
            		this.disableSortable();
            	}

            	// If resizeble is not enabled, we will initialize it.
            	if ( ! this.isResizeble ) {
            		this.enableResizeble();
            	}

               /* this.helperGridContainer.find( '.photo-gallery-helper-guidelines-wrapper' ).show();
                if ( ! wp.PhotoGallery.Settings.get( 'helpergrid' ) ) {
                    this.helperGrid.show();
                }*/

                this.container.removeClass( 'photo-gallery-creative-gallery' ).addClass( 'photo-gallery-custom-grid' );

            }
        },

        enableSortable: function() {
            var galleryView = this;

        	this.isSortable = true;
        	this.container.sortable( {
    	        items: '.photo-gallery-single-image',
    	        cursor: 'move',
    	        forcePlaceholderSize: true,
    	        placeholder: 'photo-gallery-single-image-placeholder',
                stop: function( event, ui ) {
                    var itemsIDs = galleryView.container.sortable( 'toArray' );
                    itemsIDs.forEach( function( itemID, i ) {
                        var id = "#" + itemID;
                        $( id ).trigger( 'PhotoGallery:updateIndex', { 'index': i } );
                    });
                }
    	    } );
        },

        disableSortable: function() {
        	this.isSortable = false;
        	this.container.sortable( 'destroy' );
        },

        enableResizeble: function() {

        	this.isResizeble = true;
            this.$el.addClass( 'photo-gallery-resizer-enabled' );

            if ( 'undefined' == typeof wp.PhotoGallery.Resizer ) {
                wp.PhotoGallery.Resizer = new wp.PhotoGallery.previewer['resizer']({ 'galleryView': this });
            }

        	this.container.packery({
        		itemSelector: '.photo-gallery-single-image',
                gutter: parseInt( wp.PhotoGallery.Resizer.get( 'gutter' ) ),
                columnWidth: wp.PhotoGallery.Resizer.get( 'size' ),
                rowHeight: wp.PhotoGallery.Resizer.get( 'size' ),
    		});

            this.container.on( 'layoutComplete', this.updateItemsIndex );
            this.container.on( 'dragItemPositioned', this.updateItemsIndex );
        },

        disableResizeble: function() {
    		this.isResizeble = false;
            this.$el.removeClass( 'photo-gallery-resizer-enabled' );
            this.container.packery( 'destroy' );
        },

        bindDraggabillyEvents: function( item ){
        	if ( this.isResizeble ) {
        		this.container.packery( 'bindUIDraggableEvents', item );
        	}
        },

        resetPackary: function() {
            var view = this;

            if ( this.refreshTimeout ) {
                clearTimeout( this.refreshTimeout );
            }

            this.refreshTimeout = setTimeout(function () {        
                view.container.packery();
            }, 200);

        },

        updateItemsIndex: function(){

            var container = this;

            if ( this.updateIndexTimeout ) {
                clearTimeout( this.updateIndexTimeout );
            }
            
            this.updateIndexTimeout = setTimeout( function() {
                var items = $(container).packery('getItemElements');
                $( items ).each( function( i, itemElem ) {
                    $( itemElem ).trigger( 'PhotoGallery:updateIndex', { 'index': i } );
                });
            }, 200);

        },

        setPackaryOption: function( option, value ){

            var packaryOptions = this.container.data('packery');
            if ( 'undefined' != typeof packaryOptions ) {
                packaryOptions.options[ option ] = value;
            }

        },

    });

    var PhotoGalleryGalleryGrid = Backbone.View.extend({

        containerHeight: 0,
        currentRows: 0,
        updateGridTimeout: false,

        initialize: function( args ) {
            var view = this;

            this.galleryView = args.galleryView;
            if ( 'undefined' == typeof wp.PhotoGallery.Resizer ) {
                wp.PhotoGallery.Resizer = new wp.PhotoGallery.previewer['resizer']({ 'galleryView': this.galleryView });
            }
            
            this.containerHeight = this.galleryView.container.height();

            // Listent when gallery type is changing.
            this.listenTo( wp.PhotoGallery.Settings, 'change:type', this.checkSettingsType );

            // Listent when gallery gutter is changing.
            this.listenTo( wp.PhotoGallery.Settings, 'change:gutter', this.changeGutter );

            // Listen when column width is changing
            this.listenTo( wp.PhotoGallery.Resizer, 'change:size', this.updateGrid );

            // On layout complete
            this.galleryView.container.on( 'layoutComplete', function( event ){
                view.updateGrid();
            });

            // Enable current gallery type
            this.checkGalleryType( wp.PhotoGallery.Settings.get( 'type' ) );

        },

        checkSettingsType: function( model, value ) {
            this.checkGalleryType( value );
        },

        checkGalleryType: function( type ) {
            if ( 'creative-gallery' == type ) {
                this.$el.hide();
            }else if ( 'custom-grid' == type ) {
                if ( ! wp.PhotoGallery.Settings.get( 'helpergrid' ) ) {
                    this.$el.show();
                }

                // Generate grid
                this.generateGrid();
            }
        },

        generateGrid: function() {
            var view = this,
                neededRows = 0,
                columnWidth = wp.PhotoGallery.Resizer.get( 'size' ),
                gutter = wp.PhotoGallery.Resizer.get( 'gutter' ),
                neededItems = 0,
                neededContainerHeight = 0,
                containerHeight = 0,
                minContainerHeight = 0,
                parentHeight = this.$el.parent().height();

            containerHeight = view.$el.height();
            minContainerHeight = ( columnWidth + gutter ) * 3 - gutter;

            if ( containerHeight < minContainerHeight ) {
                containerHeight = minContainerHeight;
            }

            neededRows = Math.round( ( containerHeight + gutter ) / ( columnWidth + gutter ) ) + 1;
            neededContainerHeight = ( neededRows ) * ( columnWidth + gutter ) - gutter;

            while( containerHeight < neededContainerHeight ) {
                neededContainerHeight = neededContainerHeight - ( columnWidth + gutter );
            }

            this.$el.height( neededContainerHeight );
            $( '#photo-gallery-uploader-container' ).css( 'min-height', minContainerHeight + 'px' );
            if ( neededContainerHeight > parentHeight ) {
                this.$el.parent().height( neededContainerHeight );
            }

            if ( neededRows > this.currentRows ) {

                neededItems = ( neededRows - this.currentRows ) * 12;
                this.currentRows = neededRows;

                for ( var i = 1; i <= neededItems; i++ ) {
                    this.$el.append( '<div class="photo-gallery-grid-item"></div>' );
                }

                this.$el.find( '.photo-gallery-grid-item' ).css( { 'width': columnWidth, 'height' : columnWidth, 'margin-right' : gutter, 'margin-bottom' : gutter } );

            }

        },

        updateGrid: function() {
            var view = this,
                neededRows = 0,
                columnWidth = wp.PhotoGallery.Resizer.get( 'size' ),
                gutter = wp.PhotoGallery.Resizer.get( 'gutter' ),
                neededItems = 0,
                neededContainerHeight = 0,
                containerHeight = 0,
                packery = view.galleryView.container.data('packery'),
                parentHeight = this.$el.parent().height();

            if ( 'undefined' == typeof packery ) {
                return;
            }

            containerHeight = packery.maxY - packery.gutter;
            minContainerHeight = ( columnWidth + gutter ) * 3 - gutter;

            if ( containerHeight < minContainerHeight ) {
                containerHeight = minContainerHeight;
            }

            neededRows = Math.round( ( containerHeight + gutter ) / ( columnWidth + gutter ) ) + 1;
            neededContainerHeight = ( neededRows ) * ( columnWidth + gutter ) - gutter;

            while( containerHeight < neededContainerHeight ) {
                neededContainerHeight = neededContainerHeight - ( columnWidth + gutter );
            }

            this.$el.height( neededContainerHeight );
            $( '#photo-gallery-uploader-container' ).css( 'min-height', minContainerHeight + 'px' );
            if ( neededContainerHeight > parentHeight ) {
                this.$el.parent().height( neededContainerHeight );
            }

            neededItems = ( neededRows - this.currentRows ) * 12;
            this.currentRows = neededRows;

            for ( var i = 1; i <= neededItems; i++ ) {
                this.$el.append( '<div class="photo-gallery-grid-item"></div>' );
            }

            this.$el.find( '.photo-gallery-grid-item' ).css( { 'width': columnWidth, 'height' : columnWidth, 'margin-right' : gutter, 'margin-bottom' : gutter } );

        },

        changeGutter: function() {
            var view = this,
                neededRows = 0,
                columnWidth = wp.PhotoGallery.Resizer.get( 'size' ),
                gutter = wp.PhotoGallery.Resizer.get( 'gutter' ),
                neededItems = 0,
                neededContainerHeight = 0,
                containerHeight = 0,
                packery = view.galleryView.container.data('packery');

            if ( 'undefined' == typeof packery ) {
                return;
            }

            containerHeight = packery.maxY - packery.gutter;

            if ( containerHeight < 300 ) {
                containerHeight = 300;
            }

            neededRows = Math.round( ( containerHeight + gutter ) / ( columnWidth + gutter ) ) + 1;
            neededContainerHeight = ( neededRows ) * ( columnWidth + gutter ) - gutter;

            while( containerHeight < neededContainerHeight ) {
                neededContainerHeight = neededContainerHeight - ( columnWidth + gutter );
            }

            this.$el.height( neededContainerHeight );

            this.$el.find( '.photo-gallery-grid-item' ).css( { 'width': columnWidth, 'height' : columnWidth, 'margin-right' : gutter, 'margin-bottom' : gutter } );

        }

    });

    PhotoGallery.previewer = {
        'resizer' : PhotoGalleryGalleryResizer,
        'helpergrid' : PhotoGalleryGalleryGrid,
        'view' : PhotoGalleryGalleryView
    }

}( jQuery, wp.PhotoGallery ))

