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

		// Setup vars
		var elementSettings = getElementSettings( $scope ),
			$wrapper = $scope.find( '.ep-inline-svg' );

		// Initially we have no value so lets ignore this case
		if ( ! elementSettings.svg.url )
			return;

		// Check if svg file is selected.
		if ( elementSettings.svg.url.split('.').pop().toLowerCase() !== 'svg' ) {
			alert( "Please upload an SVG file." );
			return;
		}

		// Get the file
		jQuery.get( elementSettings.svg.url, function( data ) {

			// And append the the first node to our wrapper
			$wrapper.html( $(data).find('svg') );

			var $svg = $wrapper.find( 'svg' ),

				svgTitle 		= $svg.find( 'title' ),
				svgDesc 		= $svg.find( 'desc' ),
				svgShapes   = $svg.find( 'circle, ellipse, polygon, rect, path, line, polyline' );

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

					$(this).attr( 'stroke', stroke );
					$(this).attr( 'stroke-width', strokeWidth );
					$(this).attr( 'stroke-linecap', strokeLinecap );
					$(this).attr( 'stroke-dasharray', strokeDasharray );
					$(this).attr( 'stroke-miterlimit', strokeMiterlimit );
					$(this).attr( 'fill', fill );

				});

				svgShapes.filter('[fill]:not([fill="none"])').attr( 'fill', 'currentColor' );
				svgShapes.filter('[stroke]:not([stroke="none"])').attr( 'stroke', 'currentColor' );

				// Remove inline CSS
				if ( 'yes' === elementSettings.remove_inline_css ) {
					$svg.find('*').removeAttr('style');
				}
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
		elementorFrontend.hooks.addAction( 'frontend/element_ready/ep-inline-svg.default', WidgetInlineSvgHandler );
	});

} )( jQuery );
