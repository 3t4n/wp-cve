(function( $ ) {
	$( document ).ready(function() {
		var clicked_time = 0;
		var clicks_number = 0;
		var expiration = 0;
		var minute_in_seconds = 60000;

		$( '.gglstpvrfctn-login-wrap, .gglstpvrfctn-request-email, #gglstpvrfctn-see-question, .gglstpvrfctn-request-sms, .gglstpvrfctn-resending' ).hide();

		var form = $( '.gglstpvrfctn-login-wrap' ).closest( 'form' );

		function getUserInfo( user_login ) {
			var result = false;

			$.ajax( {
				type	: 'POST',
				url		: gglstpvrfctnLoginVars.ajaxurl,
				data	: {
					action					: 'gglstpvrfctn_check_verification_options',
					gglstpvrfctn_login		: user_login,
					gglstpvrfctn_ajax_nonce	: gglstpvrfctnLoginVars.ajax_nonce
				},
				success: function( data ) {
					var user = {
						'enabled' : 0,
					};
					try {
						user = JSON.parse( data );
					} catch ( err ) {
						console.log( 'getUserInfo REQUEST: Server response is invalid' );
					}
					if ( 1 == user.enabled ) {

						$( '#login_error, .message' ).hide();

						if ( -1 != $.inArray( 'email', user.methods ) ) {
							$( '.gglstpvrfctn-request-email' ).show();
						}
						if ( -1 != $.inArray( 'sms', user.methods ) ) {
							$( '.gglstpvrfctn-request-sms' ).show();
						}
						if ( -1 != $.inArray( 'question', user.methods ) ) {
							$( '#gglstpvrfctn-see-question' ).show();
						}

						$( '.gglstpvrfctn-login-wrap' ).show();

						$( '#gglstpvrfctn-code' ).trigger( 'focus' );

					} else {
						form.removeClass( 'processing' ).trigger( 'submit' );
					}
					expiration = user.expiration_time * minute_in_seconds;
				}
			} )
		}

		/* Sending email code request via AJAX and printing message if code is sent */
		function ajaxRequest ( ) {
			$.ajax( {
				type	: 'POST',
				url		: gglstpvrfctnLoginVars.ajaxurl,
				data	: {
					action:						'gglstpvrfctn_request_email_code',
					gglstpvrfctn_login:			user_login,
					gglstpvrfctn_ajax_nonce:	gglstpvrfctnLoginVars.ajax_nonce
				},
				success: function( data ) {
					try {
						data = JSON.parse( data );
						clicks_number++;
						if ( 1 == data['result'] ) {
							if ( $( 'p.gglstpvrfctn-message' ).length ) {
								$( 'p.gglstpvrfctn-message' ).html( gglstpvrfctnLoginVars.resending_message );
							} else {
								form.before( '<p class="message gglstpvrfctn-message">' + data['message'] + '</p>' );
							}
						}
					} catch ( err ) {
						/* server response is invalid. */
						console.log( 'EMAIL REQUEST: Server response is invalid' );
					}
				}
			} );
		}

		/* get user secret question */
		function ajaxGetQuestion() {
			$.ajax( {
				type	: 'POST',
				url		: gglstpvrfctnLoginVars.ajaxurl,
				data	: {
					action:						'gglstpvrfctn_get_secret_question',
					gglstpvrfctn_login:			user_login,
					gglstpvrfctn_ajax_nonce:	gglstpvrfctnLoginVars.ajax_nonce
				},
				success: function( data ) {
					$('#gglstpvrfctn-secret-question-container').html(data).show();
					$( '#gglstpvrfctn-code' ).trigger( 'focus' );
				}
			} );
		}

		/* Sending sms code request via AJAX and printing message if code is sent */
		function ajaxRequestSms ( ) {
			$.ajax( {
				type	: 'POST',
				url		: gglstpvrfctnLoginVars.ajaxurl,
				data	: {
					action:						'gglstpvrfctn_request_sms_code',
					gglstpvrfctn_login:			user_login,
					gglstpvrfctn_ajax_nonce:	gglstpvrfctnLoginVars.ajax_nonce
				},
				success: function( data ) {
					try {
						data = JSON.parse( data );
						var config = {
						    apiKey: data['apikey']
						};
						firebase.initializeApp( config );
						$( '#gglstpvrfctn-recaptcha-container' ).css( "display", "none" );
						window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier( 'gglstpvrfctn-recaptcha-container', { 'size': 'invisible' } );
						var phoneNumber = data['phone'];
						$( '#gglstpvrfctn-code' ).prop( 'disabled' , true);
						$( '.gglstpvrfctn-request-sms' ).hide();
						firebase.auth().signInWithPhoneNumber( phoneNumber, recaptchaVerifier )
						    .then( function ( confirmationResult ) {
						     	// SMS sent. Prompt user to type the code from the message, then sign the
						      	// user in with confirmationResult.confirm(code).
								if ( $( '.gglstpvrfctn-message' ).length ) {
									$( '.gglstpvrfctn-message' ).text( data['message'] );
								} else {
									form.before( '<p class="message gglstpvrfctn-message">' + data['message'] + '</p>' );
								}
						      	window.confirmationResult = confirmationResult;
						      	$( '#gglstpvrfctn-code' ).prop( 'disabled', false );

							    $( '#gglstpvrfctn-code' ).trigger( 'focus' );
						    } ).catch( function ( error ) {
						      	// Error; SMS not sent
						       	console.error( 'Error during signInWithPhoneNumber', error );
						       	window.alert( 'Error during signInWithPhoneNumber:\n\n' + error.code + '\n\n' + error.message );
						    } );
					} catch ( err ) {
						/* server response is invalid. */
						console.log( 'SMS REQUEST: Server response is invalid', err );
					}
				}
			} );
		}

		if ( typeof form != 'undefined' ) {

			var login_field = form.find( '#login, #userlogin, #user_login, #username, #user_name' + gglstpvrfctnLoginVars.custom_login_fields ),
				user_login;

			form.one( 'submit', function( e ) {
				$( this ).addClass( 'processing' );
				e.preventDefault();
				if ( typeof login_field != 'undefined' ) {
					user_login = login_field.val();
				} else {
					user_login = '';
				}

				getUserInfo( user_login );
			} );

			/* Sending email code request via AJAX and printing message if code is sent */
			$( '.gglstpvrfctn-request-email' ).on( 'click', function( e ) {
				e.preventDefault();
				clicks_number++;
				var curr_date = new Date();
				if ( clicked_time + expiration < curr_date.getTime() && expiration != 0 ) {
					clicks_number = 0;
					clicked_time = 0;
				}
				0 == clicked_time ? clicked_time = curr_date.getTime() : clicked_time = clicked_time;
				if ( ( clicks_number > 1 && clicked_time + expiration > curr_date.getTime() ) || ( clicks_number > 1 && expiration == 0 ) ) {
					$( '.gglstpvrfctn-resending' ).show();
				} else {
					if ( typeof login_field != 'undefined' ) {
						user_login = login_field.val();
					} else {
						user_login = '';
					}
					ajaxRequest();
				}
			} );

			/* get secret question for user */
			$( '#gglstpvrfctn-see-question' ).on( 'click', function( e ) {

				e.preventDefault();

				$('#gglstpvrfctn-see-question').hide();
				ajaxGetQuestion();
			});

			$( '#gglstpvrfctn-resend' ).on( 'click', function( e ) {
				e.preventDefault();
				ajaxRequest();
				$( '.gglstpvrfctn-resending' ).hide();
			} );

			/* Sending sms code request via AJAX and printing message if code is sent */
			$( '.gglstpvrfctn-request-sms' ).on( 'click', function( e ) {
				e.preventDefault();

				$('#gglstpvrfctn-see-question').hide();
				$( '.gglstpvrfctn-request-email' ).hide();

				ajaxRequestSms();
			} );
		}

		$( '#gglstpvrfctn-cancel' ).on( 'click', function( e ) {
			e.preventDefault();
			$( '.gglstpvrfctn-resending' ).hide();
		});
	});
})( jQuery );
