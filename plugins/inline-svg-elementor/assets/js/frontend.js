var isAdminBar		= false,
	isEditMode		= false;

( function( $ ) {
	var getElementSettings = function( $element ) {
		var elementSettings = {},
			modelCID 		= $element.data( 'model-cid' );

		if ( isEditMode && modelCID ) {
			var settings 		= elementorFrontend.config.elements.data[ modelCID ],
				settingsKeys 	= elementorFrontend.config.elements.keys[ settings.attributes.widgetType || settings.attributes.elType ];

			jQuery.each( settings.getActiveControls(), function( controlKey ) {
				if ( -1 !== settingsKeys.indexOf( controlKey ) ) {
					elementSettings[ controlKey ] = settings.attributes[ controlKey ];
				}
			} );
		} else {
			elementSettings = $element.data('settings') || {};
		}

		return elementSettings;
	}
	
	var WidgetInlineSvgHandler = function( $scope, $ ) {

		// if ( ! isEditMode )
		// 	return;

		// Setup vars
		var elementSettings = getElementSettings( $scope ),
			$wrapper = $scope.find( '.elementor-inline-svg' );

		// Initially we have no value so lets ignore this case
		if ( ! elementSettings.svg.url )
			return;

		// Check the extension means we're expecting an svg file type or quit
		if ( elementSettings.svg.url.split('.').pop().toLowerCase() !== 'svg' ) {
			alert( "Please select a SVG file format." );
			return;
		}

		// Get the file
		jQuery.get( elementSettings.svg.url, function( data ) {

			// And append the the first node to our wrapper
			$wrapper.html( $(data).find('svg') );

			var $svg = $wrapper.find( 'svg' ),
			
				svgTitle 		= $svg.find( 'title' ),
				svgDesc 		= $svg.find( 'desc' ),
				svgFills 		= $svg.find( '*[fill]' ),
				svgShapes 		= $svg.find( 'circle, ellipse, polygon, rect, path, line, polyline' ),
				svgNonFills 	= $svg.find( 'circle, ellipse, polygon, rect, path' ).filter(':not([fill])'),
				svgStrokes 		= $svg.find( '*[stroke]' ),
				svgNonStrokes 	= $svg.find( 'line, polyline' ).filter(':not([fill])');

			// Remove unnecessary tags
			svgTitle.remove();
			svgDesc.remove();

			// Color override
			if ( 'yes' === elementSettings.override_colors ) {
				// Convert css styles to attributes
				svgShapes.each( function() {
					stroke = $(this).css( 'stroke' );
					strokeWidth = $(this).css( 'stroke-width' );
					strokeLinecap = $(this).css( 'stroke-linecap' );
					strokeDasharray = $(this).css( 'stroke-dasharray' );
					strokeMiterlimit = $(this).css( 'stroke-miterlimit' );
					fill = $(this).css( 'fill' );

					console.log( stroke );

					$(this).attr( 'stroke', stroke );
					$(this).attr( 'stroke-width', strokeWidth );
					$(this).attr( 'stroke-linecap', strokeLinecap );
					$(this).attr( 'stroke-dasharray', strokeDasharray );
					$(this).attr( 'stroke-miterlimit', strokeMiterlimit );
					$(this).attr( 'fill', fill );

				});

				svgShapes.filter('[fill]:not([fill="none"])').attr( 'fill', 'currentColor' );
				svgShapes.filter('[stroke]:not([stroke="none"])').attr( 'stroke', 'currentColor' );

				// Remove comments from markup
				// $svg.contents().each( function() {
				//     if ( this.nodeType === Node.COMMENT_NODE ) { $(this).remove(); }
				// });

				// Remove inline CSS
				if ( 'yes' === elementSettings.remove_inline_css ) {
					$svg.find( 'style' ).remove();
				}
			}

			if ( 'yes' !== elementSettings.maintain_ratio ) {
				$svg[0].setAttribute( 'preserveAspectRatio', 'none' );
			}

			if ( 'yes' === elementSettings.sizing ) {
				$svg.removeAttr( 'width' );
				$svg.removeAttr( 'height' );
			}
		} );
	};

	$(window).on( 'elementor/frontend/init', function() {

		if ( elementorFrontend.isEditMode() ) {
			isEditMode = true;
		}

		if ( $('body').is('.admin-bar') ) {
			isAdminBar = true;
		}

		// Image Gallery
		elementorFrontend.hooks.addAction( 'frontend/element_ready/inline-svg.default', WidgetInlineSvgHandler );
	});
	
} )( jQuery );
