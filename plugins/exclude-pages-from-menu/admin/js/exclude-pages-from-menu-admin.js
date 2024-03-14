/**
 * Dismisses plugin notices
 *
 */
( function( $ ) {
	"use strict";
	$( document ).ready( function() {
		$( '.notice.is-dismissible.exclude-pages-from-menu .notice-dismiss').on( 'click', function() {

			$.ajax( {
				url: exclude_pages_from_menu.ajax_url,
				data: {
					action: 'exclude_pages_from_menu_notice_dismiss'
				}
			} );

		} );

		$( '#epfm-help').on( 'click', function(e) {
			 // Cancel the default action
			e.preventDefault();
			e.stopPropagation();
			$( '#epfm-help-wrapper' ).slideToggle();
		} );
	} );
} )( jQuery );