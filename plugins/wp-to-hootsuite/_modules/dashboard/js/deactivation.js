/**
 * Shows the deactivation modal window when the Plugin is deactivated
 * through the Plugins screen, giving the user an option to specify
 * why they are deactivating.
 *
 * @package WPZincDashboardWidget
 * @author WP Zinc
 */

var wpzinc_deactivation_url;

jQuery( document ).ready(
	function ( $ ) {

		/**
		 * Show deactivation modal if the user is deactivating our plugin.
		 */
		$( 'span.deactivate a' ).on(
			'click',
			function ( e ) {

				// If the link slug doesn't exist, let the request through.
				var plugin_name = $( this ).closest( 'tr' ).data( 'slug' );
				if ( typeof plugin_name === 'undefined' ) {
					return true;
				}

				// If the Plugin being deactivated isn't our one, let the request through.
				if ( plugin_name != wpzinc_dashboard.plugin.name ) {
					return true;
				}

				// If here, we're deactivating our plugin.
				e.preventDefault();

				// Store the target URL.
				wpzinc_deactivation_url = $( this ).attr( 'href' );

				// Position the modal.
				$( '#wpzinc-deactivation-modal' ).css(
					{
						top: ( $( this ).offset().top - $( this ).height() - 25 ) + 'px',
						left: ( $( this ).offset().left + $( this ).width() + 20 ) + 'px'
					}
				);

				// Show the modal.
				$( '#wpzinc-deactivation-modal, #wpzinc-deactivation-modal-overlay' ).show();

			}
		);

		/**
		 * Update input text field's placeholder when a reason radio button is clicked
		 */
		$( 'input[name="wpzinc-deactivation-reason"]' ).on(
			'change',
			function ( e ) {

				$( 'input[name="wpzinc-deactivation-reason-text"]' ).attr(
					'placeholder',
					$( this ).data( 'placeholder' )
				).show();
				$( 'input[name="wpzinc-deactivation-reason-email"]' ).show();
				$( 'small.wpzinc-deactivation-reason-email' ).css( 'display', 'block' );
			}
		);

		/**
		 * Send the result of the deactivation modal when the submit button is clicked,
		 * and load the deactivation URL so that the plugin gets deactivated.
		 */
		$( 'form#wpzinc-deactivation-modal-form' ).on(
			'submit',
			function ( e ) {

				e.preventDefault();

				var wpzinc_dashboard_deactivation_reason   = $( 'input[name=wpzinc-deactivation-reason]:checked', $( this ) ).val(),
				wpzinc_dashboard_deactivation_reason_text  = $( 'input[name=wpzinc-deactivation-reason-text]', $( this ) ).val(),
				wpzinc_dashboard_deactivation_reason_email = $( 'input[name=wpzinc-deactivation-reason-email]', $( this ) ).val();

				// Submit the form via AJAX if a reason was given.
				if ( typeof wpzinc_dashboard_deactivation_reason !== 'undefined' ) {
					$.ajax(
						{
							url: 		ajaxurl,
							type: 		'POST',
							async:    	true,
							data: 		{
								action: 		'wpzinc_dashboard_deactivation_modal_submit',
								product: 		wpzinc_dashboard.plugin.name,
								version: 		wpzinc_dashboard.plugin.version,
								reason: 		wpzinc_dashboard_deactivation_reason,
								reason_text: 	wpzinc_dashboard_deactivation_reason_text,
								reason_email: 	wpzinc_dashboard_deactivation_reason_email
							},
							error: function ( a, b, c ) {
							},
							success: function ( result ) {
							}
						}
					);
				}

				// Hide the modal.
				$( '#wpzinc-deactivation-modal, #wpzinc-deactivation-modal-overlay' ).hide();

				// Load the deactivation URL.
				window.location.href = wpzinc_deactivation_url;

			}
		);

		/**
		 * Hide the overlay and modal when the overlay is clicked.
		 */
		$( '#wpzinc-deactivation-modal-overlay' ).on(
			'click',
			function ( e ) {

				$( '#wpzinc-deactivation-modal, #wpzinc-deactivation-modal-overlay' ).hide();

			}
		);

	}
);
