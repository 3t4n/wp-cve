(function( $ ) {
	$( document ).ready(function() {
	
		/* Returns true if the phone number is valid. */
		function isPhoneNumberValid() {
		    var pattern = /^\+[0-9\s\-\(\)]{8,15}$/;
		    var phoneNumber = $('.gglstpvrfctn-test-userphone').val();
		    return phoneNumber.search(pattern) !== -1;
		}
		
		$( '.gglstpvrfctn-test-userphone' ).on( 'input', function() {
			var input = $( this );
			if( isPhoneNumberValid () ) {
				input.removeClass( 'gglstpvrfctn-invalid' ).addClass( 'gglstpvrfctn-valid' );
				$( '.gglstpvrfctn-error' ).hide();
			} else {
				input.removeClass( 'gglstpvrfctn-valid' ).addClass( 'gglstpvrfctn-invalid' );
				$( '.gglstpvrfctn-error' ).show();
			}
		});

		$( '.gglstpvrfctn-error, #gglstpvrfctn_firebase_test, #gglstpvrfctn-test-code-label' ).hide();
		if (  '' ==  $( '#gglstpvrfctn_firebase_apikey' ).val()  ) {
            $( '#gglstpvrfctn_firebase_test_button' ).hide();
		}
		// Hide test button if change  firebase config
		$( '#gglstpvrfctn_firebase_apikey' ).change( function() {
			$( '#gglstpvrfctn_firebase_test_button, .gglstpvrfctn-firebase-test-result' ).hide();
		} );

		$( '#gglstpvrfctn_firebase_test_button' ).on( 'click', function( ) {
			$( '#gglstpvrfctn_firebase_test' ).show();
			$( '#gglstpvrfctn_firebase_test_button' ).hide();
		} );
		// Check phone number valid and sent SMS
		$( '#gglstpvrfctn_firebase_test_sms' ).on( 'click', function( e ) {
			if ( ! isPhoneNumberValid() ) {
				e.preventDefault();
				$( '.gglstpvrfctn-test-userphone' ).focus();
				$( '.gglstpvrfctn-test-userphone' ).removeClass( 'gglstpvrfctn-valid' ).addClass( 'gglstpvrfctn-invalid' );
				$( '.gglstpvrfctn-error' ).show();
			} else {
				var config = {
						    apiKey: $( '#gglstpvrfctn_firebase_apikey' ).val(),
						};
				firebase.initializeApp( config );
				$( '#gglstpvrfctn-test-recaptcha-container' ).css( "display", "none" );
				window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier( 'gglstpvrfctn-test-recaptcha-container', { 'size': 'invisible' } );
				var phoneNumber = $('.gglstpvrfctn-test-userphone').val();
				$( '#gglstpvrfctn_firebase_test_sms' ).hide();
				$( '#gglstpvrfctn-phone-label' ).hide();
				firebase.auth().signInWithPhoneNumber( phoneNumber, recaptchaVerifier )
				    .then( function ( confirmationResult ) {
				     	// SMS sent. Prompt user to type the code from the message, then sign the
				      	// user in with confirmationResult.confirm(code).
				      	$( '#gglstpvrfctn-test-code-label' ).show();
						window.confirmationResult = confirmationResult;
				    } ).catch( function ( error ) {
				      	// Error; SMS not sent
				      	$( '.gglstpvrfctn-firebase-test-result' ).html( '<p>' + gglstpvrfctnAdminScriptVars.invalid_sms_message + '</p>' ); 
				      	$( '.gglstpvrfctn-firebase-test-result' ).addClass( 'error' );
				    } );
			}
		} );
		// Verify SMS code
		$( '#gglstpvrfctn_firebase_test_code_button' ).on( 'click', function() {
			var code = $( '#gglstpvrfctn-test-code' ).val();
			confirmationResult.confirm( code ).then( function ( result ) {
				// User signed in successfully.
				$( '.gglstpvrfctn-firebase-test-result' ).html( '<p>' + gglstpvrfctnAdminScriptVars.success_message + '</p>' ); 
				$( '.gglstpvrfctn-firebase-test-result' ).addClass( 'updated' );
				$( '#gglstpvrfctn-test-code-label' ).hide();
			} ).catch( function ( error ) {
				// User couldn't sign in (bad verification code?)
				$( '.gglstpvrfctn-firebase-test-result' ).html( '<p>' + gglstpvrfctnAdminScriptVars.invalid_message + '</p>' ); 
				$( '.gglstpvrfctn-firebase-test-result' ).addClass( 'error' );
				$( '#gglstpvrfctn-test-code-label' ).hide();
			} );

		});

		/* mark/unmark every single role on check/uncheck "All" option in user roles list */
		$( '#gglstpvrfctn-all-roles' ).on( 'change', function() {
			var roles = $( '.gglstpvrfctn-role' );
			if ( $( this ).is( ':checked' ) ) {
				roles.prop( 'checked', true );
			} else {
				roles.prop( 'checked', false );
			}
		} );

		/* check if "All" option should be marked on check/uncheck single user role in user role list */
		$( '.gglstpvrfctn-role' ).on( 'change', function() {
			var roles = $( '.gglstpvrfctn-role' ),
				checked_roles = roles.filter(':checked');
			if ( roles.length == checked_roles.length ) {
				$( '#gglstpvrfctn-all-roles' ).prop( 'checked', true );
			} else {
				$( '#gglstpvrfctn-all-roles' ).prop( 'checked', false );
			}
		} );

		/* Hide notice and unset option using AJAX */
		$( document ).on( 'click', '.gglstpvrfctn-banner .notice-dismiss', function( e ) {
			e.preventDefault();
			var form = $( this ).closest( '.gglstpvrfctn-banner' ).find( 'form' ),
				ajax_nonce = form.find( '#gglstpvrfctn_settings_nonce' ).val();
			$.ajax( {
				type    : 'POST',
				url     : ajaxurl,
				data    : {
					action						: 'gglstpvrfctn_hide_settings_notice',
					gglstpvrfctn_hide_banner	: form.find( '#gglstpvrfctn_hide_banner' ).val(),
					gglstpvrfctn_settings_nonce	: ajax_nonce
				},
				success: function( data ) {
					if ( '1' == data ) {
						form.closest('.gglstpvrfctn-banner').hide();
					}
				}
			} );
		} );
	});
})( jQuery );
