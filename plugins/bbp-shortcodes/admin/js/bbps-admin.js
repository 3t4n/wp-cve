/**
 * Dismisses plugin notices.
 */
( function( $ ) {
	'use strict';
	$( document ).ready( function() {
		$( '.notice.is-dismissible.bbpress-shortcodes .notice-dismiss' ).on( 'click', function() {

			$.ajax( {
				url: bbpress_shortcodes.ajax_url,
				data: {
					action: 'dismiss_notice'
				}
			} );

		} );
	} );
} )( jQuery );
