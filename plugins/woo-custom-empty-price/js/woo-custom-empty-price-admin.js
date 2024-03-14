(function ($) {
	'use strict'

	$( window ).on( 'load',
		function () {

			// Initiate colour picker
			$( '.colour_picker' ).wpColorPicker();

			// Toggle the setting section visibility
			$( 'select#content_type' ).on(
				'change',
				function () {
					var $current_value = $( this ).val()
					console.log( $( 'tr[class*="_settings_section"]' ) )
					$( 'tr[class*="_settings_section"]' ).removeClass( 'show' ).addClass( 'hide' )
					$( '.' + $current_value + '_settings_section' ).removeClass( 'hide' ).addClass( 'show' )
				}
			)
		}
	)
})( jQuery );