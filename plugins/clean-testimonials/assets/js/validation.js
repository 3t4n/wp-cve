/* ! testimonial submission form validation */

jQuery( function() {

	jQuery('#submit-testimonial').bind( 'click', function( event ) {
		
		jQuery('#add-testimonial input[required="required"], #add-testimonial textarea[required="required"]').each( function() {
		
			if( jQuery(this).val() == '' ) {
			
				alert( 'Please ensure all require fields are filled out' );
				
				event.preventDefault();
				return false;
			
			}
		
		});
		
		// validate email address if data is present
		if( jQuery('#testimonial_client_email').val() != '' ) {
			
			if ( !/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test( jQuery('#testimonial_client_email').val() ) ) {
			
				alert( 'Please enter a valid email address.' );
				event.preventDefault();
			
			}
			
		}
		
		// validate website address if data is present
		if( jQuery('#testimonial_client_company_website').val() != '' ) {
			
			if( !/^[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/.test( jQuery('#testimonial_client_company_website').val() ) ) {
			
				alert( 'Please enter a valid website address.' );
				event.preventDefault();
			
			}
			
		}

	});

});