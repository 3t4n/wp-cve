/*
 * ======================================================================
 * Slider Pro Lightbox Slider
 * ======================================================================
 */
(function( $ ) {

	var LightboxSlider = {

		/**
		 * Indicates if a lightbox slider is in process
		 * of being opened.
		 *
		 * @since 4.5.0
		 * 
		 * @type {Boolean}
		 */
		ajaxRequestInProgress: false,

		/**
		 * Adds a 'click' event handler to all the elements that have a
		 * class that starts with 'sliderpro-lightbox'. The ID of the 
		 * slider that will be loaded is indicated in the class name.
		 *
		 * @since 4.5.0
		 */
		init: function() {
			$( '[class^=sliderpro-lightbox]' ).on( 'click', function( event ) {
				event.preventDefault();

				var id = parseInt( $( this ).attr( 'class' ).split( '-' ).pop(), 10 );
				LightboxSlider.load( id );
			});
		},

		/**
		 * Loads the slider with the indicated ID.
		 *
		 * @since 4.5.0
		 */
		load: function( id ) {
			if ( this.ajaxRequestInProgress === true )
				return;

			this.ajaxRequestInProgress = true;

			// display the lightbox overlay
			$( 'body' ).append( '<div class="lightbox-slider-overlay"></div>');

			$.ajax({
				url: sp_js_vars.ajaxurl,
				type: 'get',
				dataType:'html',
				data: {
					action: 'sliderpro_load_lightbox_slider',
					id: id
				},
				complete: function( data ) {
					LightboxSlider.ajaxRequestInProgress = false;
					LightboxSlider.show( data.responseText );
				}
			});
		},

		/**
		 * Shows the lightbox slider.
		 *
		 * @since 4.5.0
		 */
		show: function( content ) {

			// create the lightbox elements and add the slider's HTML and JavaScript
			$( '.lightbox-slider-overlay' ).append(
				'<div class="lightbox-slider-outer-container">' +
				'	<div class="lightbox-slider-inner-container">' +
				'		<span class="lightbox-slider-close"></span>' +
				'		' + content +
				'	</div>' +
				'</div>');

			// get some data about the slider that will be used to set
			// the size of the lightbox container
			var lightboxContainer = $( '.lightbox-slider-inner-container' ),
				slider = lightboxContainer.find( '.slider-pro' ),
				sliderSettings = slider.data( 'sliderPro' ).settings,
				sliderWidth = sliderSettings[ 'width' ],
				sliderHeight = sliderSettings[ 'height' ],
				visibleSize = sliderSettings[ 'visibleSize' ],
				forceSize = sliderSettings[ 'forceSize' ],
				orientation = sliderSettings[ 'orientation' ],
				isThumbnailScroller = slider.find( '.sp-thumbnail' ).length !== 0,
				thumbnailScrollerOrientation = sliderSettings[ 'thumbnailsPosition' ] === 'top' || sliderSettings[ 'thumbnailsPosition' ] === 'bottom' ? 'horizontal' : 'vertical',
				isButtons = sliderSettings[ 'buttons' ];

			if ( visibleSize !== 'auto' ) {
				if ( orientation === 'horizontal' ) {
					sliderWidth = visibleSize;
				} else if ( orientation === 'vertical' ) {
					sliderHeight = visibleSize;
				}
			}

			if ( forceSize === 'fullWidth' ) {
				sliderWidth = '100%';
			} else if ( forceSize === 'fullWindow' ) {
				sliderWidth = '100%';
				sliderHeight = '100%';
			}

			var isPercentageWidth = sliderWidth.toString().indexOf( '%' ) !== -1,
				isPercentageHeight = sliderHeight.toString().indexOf( '%' ) !== -1;

			// include the thumbnail scrolle's width in the width of the slider
			if ( isThumbnailScroller === true && thumbnailScrollerOrientation === 'vertical' && isPercentageWidth === false ) {
				sliderWidth = parseInt( sliderWidth, 10 ) + parseInt( sliderSettings[ 'thumbnailWidth' ], 10 );
			}

			// resize the lightbox container when the window is resized
			$( window ).on( 'resize.lightboxSlider', function() {

				// get the lightbox container's padding size
				var horizontalPadding = parseInt( lightboxContainer.css( 'padding-left' ), 10 ) + parseInt( lightboxContainer.css( 'padding-right' ), 10 ),
					verticalPadding = parseInt( lightboxContainer.css( 'padding-top' ), 10 ) + parseInt( lightboxContainer.css( 'padding-bottom' ), 10 );

				// set the width and height of the lightbox container
				// and make sure it stays within the window's boundaries
				if ( isPercentageWidth === true ) {
					lightboxContainer.css( 'width', $( window ).width() * ( parseInt( sliderWidth, 10 ) / 100 ) - horizontalPadding );
				} else if ( sliderWidth >= $( window ).width() - horizontalPadding ) {
					lightboxContainer.css( 'width', $( window ).width() - horizontalPadding );
				} else {
					lightboxContainer.css( 'width', sliderWidth );
				}

				if ( isPercentageHeight === true ) {
					lightboxContainer.css( 'height', $( window ).height() * ( parseInt( sliderHeight, 10 ) / 100 ) - verticalPadding );
				}
			});

			// trigger a 'resize' for the initial calculation of the lightbox's size
			$( window ).trigger( 'resize' );

			// close the lightbox when the close button is clicked
			lightboxContainer.find( '.lightbox-slider-close' ).on( 'click', function( event ) {
				LightboxSlider.hide();
			});
		},

		/**
		 * Hides the lightbox slider.
		 *
		 * @since 4.5.0
		 */
		hide: function() {
			$( '.lightbox-slider-close' ).off( 'click' );
			$( window ).off( 'resize.lightboxSlider' );

			$( '.lightbox-slider-outer-container .slider-pro' ).sliderPro( 'destroy' );
			$( 'body' ).find( '.lightbox-slider-overlay, .lightbox-slider-outer-container' ).remove();
		}
	};

	$( document ).ready(function() {
		LightboxSlider.init();
	});

})( jQuery );