/*
 * Custom Js for hide and show buttons
 * 
 * @package catch-updater
 *
 * @since catch-updater 0.1
 */
jQuery( document ).ready( function( $ ) {
	$( '#more_options_show_button' ).click( function() {
		$( '#more_options' ).show();
		
		$( '#more_options_hide_button' ).show();
		
		$( this ).hide();
		
		return false;
	});

	$( '#more_options_hide_button' ).click( function() {
		$( '#more_options' ).hide();
		
		$( '#more_options_show_button' ).show();
		
		$( this ).hide();
		
		return false;
	});
})