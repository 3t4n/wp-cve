wp.RespAccordionSlider = 'undefined' === typeof( wp.RespAccordionSlider ) ? {} : wp.RespAccordionSlider;

(function( $, RespAccordionSlider ){

    var RespAccordionSliderGalleryResizer = Backbone.Model.extend({
    	defaults: {
            'columns': 12,
            'gutter': 10,
            'containerSize': false,
            'size': false,
        },

        initialize: function( args ){
            var resizer = this;

            this.set( 'containerSize', jQuery( '#ras-uploader-container .ras-uploader-inline-content' ).width() );

            // Get options
            this.set( 'gutter', parseInt( wp.RespAccordionSlider.Settings.get('gutter') ) );

        	// calculate block size.
        	this.generateSize();

        },
        
        generateSize: function(){
        	var columns = this.get( 'columns' ),
        		gutter = this.get( 'gutter' ),
        		containerWidth = this.get( 'containerSize' ),
        		size;

        	size = Math.floor( ( containerWidth - ( gutter * ( columns - 1 ) ) ) / columns );
        	this.set( 'size', size );
        },
       
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

    
        resizeItems: function(){

            // Generate new sizes.
            this.generateSize();

            if ( 'undefined' != typeof wp.RespAccordionSlider.Items && wp.RespAccordionSlider.Items.length > 0 ) {

                // Resize all items when gutter or columns have changed.
                wp.RespAccordionSlider.Items.each( function( item ){
                    item.resize();
                });

            }

            if ( 'custom-grid' == RespAccordionSlider.Settings.get( 'type' ) ) {
                // Change packary columnWidth & columnHeight
                wp.RespAccordionSlider.GalleryView.setPackaryOption( 'columnWidth', this.get( 'size' ) );
                wp.RespAccordionSlider.GalleryView.setPackaryOption( 'rowHeight', this.get( 'size' ) );

                // Update Grid
                wp.RespAccordionSlider.GalleryView.setPackaryOption( 'gutter', parseInt( this.get( 'gutter' ) ) );

                // Reset Packary
                wp.RespAccordionSlider.GalleryView.resetPackary();
            }

        },

    });

    var RespAccordionSliderGalleryView = Backbone.View.extend({

    	isSortable : false,
    	isResizeble: false,
        refreshTimeout: false,
        updateIndexTimeout: false,

    	initialize: function( args ) {

    		// This is the container where the gallery items are.
    		this.container = this.$el.find( '.ras-uploader-inline-content' );

            // Helper Grid container
            this.helperGridContainer = this.$el.parent().find( '.ras-helper-guidelines-container' );
            this.helperGrid = this.$el.find( '#ras-slider-grid' );

            // Listen to grid toggle
            this.helperGridContainer.on( 'change', 'input', $.proxy( this.updateSettings, this ) );

    		// Listent when gallery type is changing.
        	this.listenTo( wp.RespAccordionSlider.Settings, 'change:type', this.checkSettingsType );

        	// Enable current gallery type
        	this.checkGalleryType( wp.RespAccordionSlider.Settings.get( 'type' ) );

        },

        updateSettings: function( event ) {
            var value,
                setting = event.target.dataset.setting;

            value = event.target.checked ? 1 : 0;

            wp.RespAccordionSlider.Settings.set( 'helpergrid', value );

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
           if ( 'creative-gallery' == type || 'grid' == type) {

                // If resizeble is enable we will destroy it
                if ( this.isResizeble ) {
                    this.disableResizeble();
                }

                // If sortable is not enabled, we will initialize it.
                if ( ! this.isSortable ) {
                    this.enableSortable();
                }
            }
            
           if ( 'custom-grid' == type ) {

            	// If sortable is enable we will destroy it
            	if ( this.isSortable ) {
            		this.disableSortable();
            	}

            	// If resizeble is not enabled, we will initialize it.
            	if ( ! this.isResizeble ) {
            		this.enableResizeble();
            	}

            }
        },

        disableResizeble: function() {
            this.isResizeble = false;
            this.$el.removeClass( 'ras-resizer-enabled' );
            this.container.packery( 'destroy' );
        },

        enableSortable: function() {
            var galleryView = this;

            this.isSortable = true;
            this.container.sortable( {
                items: '.ras-single-image',
                cursor: 'move',
                forcePlaceholderSize: true,
                placeholder: 'ras-single-image-placeholder',
                stop: function( event, ui ) {
                    var itemsIDs = galleryView.container.sortable( 'toArray' );
                    itemsIDs.forEach( function( itemID, i ) {
                        var id = "#" + itemID;
                        $( id ).trigger( 'RespAccordionSlider:updateIndex', { 'index': i } );
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
            this.$el.addClass( 'ras-resizer-enabled' );

            if ( 'undefined' == typeof wp.RespAccordionSlider.Resizer ) {
                wp.RespAccordionSlider.Resizer = new wp.RespAccordionSlider.previewer['resizer']({ 'galleryView': this });
            }

        	this.container.packery({
        		itemSelector: '.ras-single-image',
                gutter: parseInt( wp.RespAccordionSlider.Resizer.get( 'gutter' ) ),
                columnWidth: wp.RespAccordionSlider.Resizer.get( 'size' ),
                rowHeight: wp.RespAccordionSlider.Resizer.get( 'size' ),
    		});

            this.container.on( 'layoutComplete', this.updateItemsIndex );
            this.container.on( 'dragItemPositioned', this.updateItemsIndex );
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
                    $( itemElem ).trigger( 'RespAccordionSlider:updateIndex', { 'index': i } );
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

    var RespAccordionSliderGalleryGrid = Backbone.View.extend({

        containerHeight: 0,
        currentRows: 0,
        updateGridTimeout: false,

        initialize: function( args ) {
            var view = this;

            this.galleryView = args.galleryView;
            if ( 'undefined' == typeof wp.RespAccordionSlider.Resizer ) {
                wp.RespAccordionSlider.Resizer = new wp.RespAccordionSlider.previewer['resizer']({ 'galleryView': this.galleryView });
            }
            
            this.containerHeight = this.galleryView.container.height();

            // Listent when gallery type is changing.
            this.listenTo( wp.RespAccordionSlider.Settings, 'change:type', this.checkSettingsType );

            // Listen when column width is changing
            this.listenTo( wp.RespAccordionSlider.Resizer, 'change:size', this.updateGrid );

            // On layout complete
            this.galleryView.container.on( 'layoutComplete', function( event ){
                view.updateGrid();
            });

            // Enable current gallery type
            this.checkGalleryType( wp.RespAccordionSlider.Settings.get( 'type' ) );

        },

        checkSettingsType: function( model, value ) {
            this.checkGalleryType( value );
        },

        checkGalleryType: function( type ) {
            if ( 'creative-gallery' == type ) {
                this.$el.hide();
            }else if ( 'custom-grid' == type ) {
                if ( ! wp.RespAccordionSlider.Settings.get( 'helpergrid' ) ) {
                    this.$el.show();
                }

                // Generate grid
                this.generateGrid();
            }
        },

        generateGrid: function() {
            var view = this,
                neededRows = 0,
                columnWidth = wp.RespAccordionSlider.Resizer.get( 'size' ),
                gutter = wp.RespAccordionSlider.Resizer.get( 'gutter' ),
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
            $( '#ras-uploader-container' ).css( 'min-height', minContainerHeight + 'px' );
            if ( neededContainerHeight > parentHeight ) {
                this.$el.parent().height( neededContainerHeight );
            }

            if ( neededRows > this.currentRows ) {

                neededItems = ( neededRows - this.currentRows ) * 12;
                this.currentRows = neededRows;

                for ( var i = 1; i <= neededItems; i++ ) {
                    this.$el.append( '<div class="ras-slider-grid-item"></div>' );
                }

                this.$el.find( '.ras-slider-grid-item' ).css( { 'width': columnWidth, 'height' : columnWidth, 'margin-right' : gutter, 'margin-bottom' : gutter } );

            }

        },

        updateGrid: function() {
            var view = this,
                neededRows = 0,
                columnWidth = wp.RespAccordionSlider.Resizer.get( 'size' ),
                gutter = wp.RespAccordionSlider.Resizer.get( 'gutter' ),
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
            $( '#ras-uploader-container' ).css( 'min-height', minContainerHeight + 'px' );
            if ( neededContainerHeight > parentHeight ) {
                this.$el.parent().height( neededContainerHeight );
            }

            neededItems = ( neededRows - this.currentRows ) * 12;
            this.currentRows = neededRows;

            for ( var i = 1; i <= neededItems; i++ ) {
                this.$el.append( '<div class="ras-slider-grid-item"></div>' );
            }

            this.$el.find( '.ras-slider-grid-item' ).css( { 'width': columnWidth, 'height' : columnWidth, 'margin-right' : gutter, 'margin-bottom' : gutter } );

        },


    });

    RespAccordionSlider.previewer = {
        'resizer' : RespAccordionSliderGalleryResizer,
        'helpergrid' : RespAccordionSliderGalleryGrid,
        'view' : RespAccordionSliderGalleryView
    }

}( jQuery, wp.RespAccordionSlider ))

