/**
 * Main js file containing required custom scripts for the plugin.
 *
 * @author    WP Square
 * @package		fancy-elements-avada
 */

( function ( $ ) {
	'use strict';
	$( document ).ready(
		function () {
			$( '.fea-fancy-tab-function a' ).on(
				'click',
				function( e ) {
					var eleParent,
						index;
					e.preventDefault();
					eleParent = $( this ).closest( '.fea-fancy-tabs' );
					$( this ).tab( 'show' );
					$( eleParent ).find( '.fea-fancy-tabs-shortcode-tab' ).hide();
					index = $( this ).attr( 'index' );
					$( eleParent ).find( '.fea-tab-content-' + index ).show();
				}
			);
		}
	);
}( jQuery ) );
