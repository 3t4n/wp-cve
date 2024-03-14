jQuery( document ).ready( function($) {

	$( document ).on( 'click', '#eclg-submit-btn', function() {

		var thisObj = $( this );
		var formObj = thisObj.parents( 'form#eclg-form' );

		// Get variables
		var firstnameObj	= formObj.find( 'input.eclg_firstname' );
		var firstname		= firstnameObj.val();

		var lastnameObj		= formObj.find( 'input.eclg_lastname' );
		var lastname		= lastnameObj.val();

		var emailObj		= formObj.find( 'input.eclg_email' );
		var email			= emailObj.val();

		// Error operation
		var error = 0;
		formObj.find( '.eclg-error' ).remove();
		formObj.find( '.eclg-error-container' ).hide();

		// Validate firstname
		if( firstname == '' ) {
			error = 1;
			firstnameObj.parent().append( '<span class="error eclg-error">'+EcLg.fname_empty+'</span>' );
		}

		// Validate Email
		if( email == '' ) {
			error = 1;
			emailObj.parent().append( '<span class="error eclg-error">'+EcLg.email_empty+'</span>' );
		} else if( !isValidEmail(email) ) {
			error = 1;
			emailObj.parent().append( '<span class="error eclg-error">'+EcLg.email_valid+'</span>' );
		}

		// Check if not error
		if( error == 0 ) {

			formObj.find( '.eclg_ajax_loader' ).show();

			var data = {
				action: 'eclg_add_newsletter',
				firstname: firstname,
				lastname: lastname,
				email: 	email
			};

			$.post( EcLg.ajaxurl, data, function( response ) {

				var data = $.parseJSON( response );
				if( data.status == '0' ) {
					formObj.find( '.eclg-message-container' ).html( data.errmsg ).fadeIn();
				} else {
					formObj.find( '.eclg-message-container' ).html( data.errmsg ).fadeIn();
					formObj[0].reset();
				} 
				
				setTimeout(function(){
					formObj.find( '.eclg-message-container' ).html( '' ).hide();
				}, 4000);
				
				formObj.find( '.eclg_ajax_loader' ).hide();

			}); // Ajax over
		} // Not error condition over

		return false;
	});

	
}); // Over document ready

// Email validation reply true if valid
function isValidEmail( emailAddress ) {
	var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
	return pattern.test( emailAddress );
};
