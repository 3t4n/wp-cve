jQuery( document ).ready( function() {
	
	jQuery( '#sl_enable_subdomain' ).click( function(e) {
		if( jQuery(this).is(":checked") ) {
			jQuery( "#subdomain-options" ).fadeIn('fast');
		} else {
			jQuery( "#subdomain-options" ).fadeOut('fast');
		}
	} );

	jQuery( '#sl_add_disclosure_badge' ).click( function(e) {
		if( jQuery(this).is(":checked") ) {
			jQuery( "#badge-options" ).fadeIn('fast');
		} else {
			jQuery( "#badge-options" ).fadeOut('fast');
		}
	} );
	
} );