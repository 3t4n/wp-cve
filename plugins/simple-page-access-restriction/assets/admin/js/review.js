jQuery( document ).on( 'click', '#simple-page-access-restriction-review .notice-dismiss', function() {
	var ps_simple_par_review_data = {
		action: 'ps_simple_par_review_notice',
	};
	
	jQuery.post( ajaxurl, ps_simple_par_review_data, function( response ) {
		if ( response ) {
			console.log( response );
		}
	} );
} );