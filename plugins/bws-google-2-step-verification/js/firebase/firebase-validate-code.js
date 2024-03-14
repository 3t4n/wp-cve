( function( $ ) {
	$( document ).ready(function() {
		var submitForm = false;
		$( 'form' ).submit( function() {
			var form = $( this );
			user_login = form.find( '#login, #userlogin, #user_login, #username, #user_name' + gglstpvrfctnLoginVars.custom_login_fields ).val();
			if ( ! ( typeof confirmationResult === 'undefined' ) ) {
		        if( ! submitForm ) {
			        var code = $( '#gglstpvrfctn-code' ).val();
					confirmationResult.confirm( code ).then( function ( result ) {
						//User signed in successfully.
						$.ajax( {
							type	: 'POST',
							url		: gglstpvrfctnLoginVars.ajaxurl,
							data	: {
								action:						'gglstpvrfctn_get_sms_response',
								gglstpvrfctn_login:			user_login,
								gglstpvrfctn_ajax_nonce:	gglstpvrfctnLoginVars.ajax_nonce
							},
						} ).done( function() {
								submitForm = true;
								form.submit();
						} );
					} ).catch( function ( error ) {
						// User couldn't sign in (bad verification code?)
						if ( $( 'p.gglstpvrfctn-message' ).length ) {
							$( 'p.gglstpvrfctn-message' ).html( gglstpvrfctnLoginVars.err_message );
							$( 'p.gglstpvrfctn-message' ).attr( 'id' , 'login_error' );
							$( 'p.gglstpvrfctn-message' ).removeClass( 'gglstpvrfctn-message' );
						} else {
							form.before( '<p id="login_error">' + gglstpvrfctnLoginVars.err_message + '</p>' );
						}
					} );
				return false;
				}
	        }
	    } );
	} );
} )( jQuery );