/* global wppfm_support_form_vars */
(
	function( $ ) {

		$( function() {

			/**
			 * Reacts on the click button event of the "Sign Up Now" button on the Support page.
			 *
			 * Sends a given email to the subscribers (Lead) list on the WpMarketingRobot.com webserver.
			 */
			$( '#wppfm-sign-up-button' ).on(
				'click',
				function() {
					let email = $( '#wppfm-sign-up-mail-input' ).val();
					const wpUserName = $( '#wppfm-support-page-data-storage' ).data( 'wppfmUsername' );

					email = wppfm_sanitizeEmail( email );

					if ( ! email ) {
						//noinspection JSUnresolvedVariable
						wppfm_showErrorMessage( wppfm_support_form_vars.email_not_valid );
						return;
					}

					wppfm_sendSubscriber( wpUserName, email );
				}
			);

			function wppfm_sendSubscriber( wpUserName, email ) {
				const authorizationUsername = "listing_manager";
				const authorizationPassword = "rxgbwedYS0XqF1AvkHNPbC06";
				const base64Auth = btoa(`${authorizationUsername}:${authorizationPassword}`);  // Encode to base64

				let params = {
					last_name: wpUserName,
					email: email,
					status: 'subscribed',
					tags: [1], // tag 1 = "Google Manager Free"
					lists: [6] // list 6 = "Lead"
				}

				$.ajax({
					method: "POST",
					url: "https://wpmarketingrobot.com/wp-json/fluent-crm/v2/subscribers/",  // The URL of the API
					data: params,
					headers: {
						"Authorization": "Basic " + base64Auth
					},
					success: function() {
						//noinspection JSUnresolvedVariable
						wppfm_showSuccessMessage( wppfm_support_form_vars.signed_up_success )
					},
					error: function( xhr ) {
						wppfm_handleSubscriberError( xhr )
					}
				});
			}

			function wppfm_handleSubscriberError( xhr ) {
				let emailMessage = JSON.parse( xhr.responseText ).email;
				if ( emailMessage.hasOwnProperty( 'unique' ) ) { // email is not unique
					//noinspection JSUnresolvedVariable
					wppfm_showErrorMessage( wppfm_support_form_vars.email_already_registered )
				} else {
					//noinspection JSUnresolvedVariable
					wppfm_showErrorMessage( wppfm_support_form_vars.signup_failed )
				}
			}
		});

	}( jQuery )
);
