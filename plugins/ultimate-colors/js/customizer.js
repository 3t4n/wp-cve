/* global Ultimate_Colors */

/**
 * This file adds some LIVE to the Theme Customizer live preview.
 */
(function ( $ ) {
	'use strict';

	$.each( Ultimate_Colors, function ( key, element ) {
		var settings = 'ultimate_colors_customize[' + element.selector + '-' + element.property + ']';

		wp.customize( settings, function ( value ) {
			value.bind( function ( newval ) {
				$( element.selector ).css( element.property, newval );
			} );
		} );
	} )
})( jQuery );
