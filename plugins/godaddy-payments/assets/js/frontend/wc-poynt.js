/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * Payment form handler script.
 *
 * @since 1.0.0
 */
jQuery( ( $ ) => {

	'use strict';

	/**
	 * Payment form handler.
	 *
	 * Interacts with the Poynt Collect API to process a checkout payment form.
	 *
	 * @link https://docs.poynt.com/app-integration/poynt-collect/#poynt-collect
	 *
	 * @since 1.0.0
	 */
	window.WC_Poynt_Payment_Form_Handler = class WC_Poynt_Payment_Form_Handler extends SV_WC_Payment_Form_Handler_v5_12_1 {

		/**
		 * Instantiates the payment form handler.
		 *
		 * Loads the payment handler and intercepts form submissions to inject the token returned by Poynt Collect API.
		 *
		 * @since 1.0.0
		 *
		 * @param {Object} args form handler arguments
		 */
		constructor( args ) {

			super( args );

			this.appId            = args.appId;
			this.businessId       = args.businessId;
			this.customerName     = args.customerName;
			this.customerAddress  = args.customerAddress;
			this.isLoggingEnabled = args.isLoggingEnabled;
			this.mountOptions     = args.mountOptions;
			this.shipping         = args.shipping;

			// initialization on form load, for non-checkout pages
			if ( ! this.formInitialized && ! $( 'form.checkout' ).length ) {
				this.initForm();
			}

			$( document.body ).on( 'sv_wc_payment_form_valid_payment_data', ( event, data ) => {

				if ( data.payment_form && data.payment_form.id !== this.id ) {
					return data.passed_validation;
				}

				// if regular validation passed
				if ( data.passed_validation ) {

					// we have a token if there's a nonce or a saved method is selected
					if ( this.saved_payment_method_selected || this.hasNonce() ) {
						return $( document.body ).triggerHandler( 'wc_poynt_form_submitted', { payment_form: data.payment_form } ) !== false;
					}

					// override the loaded address data if available via form fields
					let customerAddress   = $( '#billing_address_1' ).val(),
						customerAddress2  = $( '#billing_address_2' ).val(),
					    customerPostcode  = $( '#billing_postcode' ).val(),
						customerCity      = $( '#billing_city' ).val(),
						customerState     = $( '#billing_state' ).val(),
						customerCountry   = $( '#billing_country' ).val(),
					    customerFirstName = $( '#billing_first_name' ).val(),
					    customerLastName  = $( '#billing_last_name' ).val(),
						customerEmail     = $( '#billing_email' ).val(),
						customerPhone     = $( '#billing_phone' ).val();

					let shipToDifferentAddress = $( '#ship-to-different-address-checkbox' ).is( ':checked' );

					// override the loaded shipping data if available via form fields
					let shippingLine1   = this.shipping.needsShipping ? (shipToDifferentAddress ? $( '#shipping_address_1' ).val() : customerAddress) : '',
					    shippingLine2   = this.shipping.needsShipping ? (shipToDifferentAddress ? $( '#shipping_address_2' ).val() : customerAddress2) : '',
					    shippingCity    = this.shipping.needsShipping ? (shipToDifferentAddress ? $( '#shipping_city' ).val() : customerCity) : '',
					    shippingState   = this.shipping.needsShipping ? (shipToDifferentAddress ? $( '#shipping_state' ).val() : customerState) : '',
					    shippingPostcode= this.shipping.needsShipping ? (shipToDifferentAddress ? $( '#shipping_postcode' ).val() : customerPostcode) : '';

					if ( customerAddress && customerAddress.length > 0 ) {
						this.customerAddress.line1 = customerAddress;
					}
					if ( customerAddress2 && customerAddress2.length > 0 ) {
						this.customerAddress.line2 = customerAddress2;
					}
					if ( customerCity && customerCity.length > 0 ) {
						this.customerAddress.city = customerCity;
					}
					if ( customerState && customerState.length > 0 ) {
						this.customerAddress.state = customerState;
					}
					if ( customerCountry && customerCountry.length > 0 ) {
						this.customerAddress.country = customerCountry;
					}
					if ( customerPostcode && customerPostcode.length > 0 ) {
						this.customerAddress.postcode = customerPostcode;
					}
					if ( customerFirstName && customerFirstName.length > 0 ) {
						this.customerName.firstName = customerFirstName;
					}
					if ( customerLastName && customerLastName.length > 0 ) {
						this.customerName.lastName = customerLastName;
					}
					if ( customerEmail && customerEmail.length > 0 ) {
						this.customerEmailAddress = customerEmail;
					}
					if ( customerPhone && customerPhone.length > 0 ) {
						this.customerPhone = customerPhone;
					}
					if ( shippingLine1 && shippingLine1.length > 0 ) {
						this.shipping.line1 = shippingLine1;
					}
					if ( shippingLine2 && shippingLine2.length > 0 ) {
						this.shipping.line2 = shippingLine2;
					}
					if ( shippingCity && shippingCity.length > 0 ) {
						this.shipping.city = shippingCity;
					}
					if ( shippingState && shippingState.length > 0 ) {
						this.shipping.state = shippingState;
					}
					if ( shippingPostcode && shippingPostcode.length > 0 ) {
						this.shipping.postcode = shippingPostcode;
					}

					// block the UI
					this.form.block( { message: null, overlayCSS: { background: '#fff', opacity: 0.6 } } );

					// create the nonce
					this.createNonce();

					// always return false to resubmit the form
					return false;
				}
			} );

			// clear the payment nonce on errors
			$( document.body ).on( 'checkout_error', () => {
				this.clearNonce();
			} );
		}

		/**
		 * Gets the nonce field.
		 *
		 * Returns a jQuery object with the hidden input that holds a nonce value.
		 *
		 * @since 1.0.0
		 *
		 * @returns {Object} jQuery object
		 */
		getNonceField() {
			return $( '#wc-' + this.id_dasherized + '-nonce' );
		}

		/**
		 * Clears the payment nonce.
		 *
		 * Resets the nonce value in the hidden input.
		 *
		 * @since 1.0.0
		 */
		clearNonce() {
			this.getNonceField().val( '' );
		}

		/**
		 * Creates a nonce using Poynt Collect.
		 *
		 * Saves the nonce to a hidden input and resubmits the form.
		 *
		 * @link https://docs.poynt.com/app-integration/poynt-collect/#creating-a-nonce
		 *
		 * @since 1.0.0
		 */
		createNonce() {

			let nonceData = {
				businessId: this.businessId
			};

			if ( this.customerName.firstName ) {
				nonceData.firstName = this.customerName.firstName;
			}

			if ( this.customerName.lastName ) {
				nonceData.lastName = this.customerName.lastName;
			}

			if ( this.customerAddress.line1 ) {
				nonceData.line1 = this.customerAddress.line1;
			}

			if ( this.customerAddress.line2 ) {
				nonceData.line2 = this.customerAddress.line2;
			}

			if ( this.customerAddress.postcode ) {
				nonceData.zip = this.customerAddress.postcode;
			}

			if ( this.customerAddress.city ) {
				nonceData.city = this.customerAddress.city;
			}

			if ( this.customerAddress.state ) {
				nonceData.territory = this.customerAddress.state;
			}

			if ( this.customerAddress.country ) {
				nonceData.countryCode = this.customerAddress.country;
			}

			if ( this.customerPhone ) {
				nonceData.phone = this.customerPhone;
			}

			if ( this.customerEmailAddress ) {
				nonceData.emailAddress = this.customerEmailAddress;
			}

			if ( this.shipping.line1 ) {
				nonceData.shippingLine1 = this.shipping.line1;
			}

			if ( this.shipping.line2 ) {
				nonceData.shippingLine2 = this.shipping.line2;
			}

			if ( this.shipping.city ) {
				nonceData.shippingCity = this.shipping.city;
			}

			if ( this.shipping.state ) {
				nonceData.shippingTerritory = this.shipping.state;
			}

			if ( this.shipping.postcode ) {
				nonceData.shippingZip = this.shipping.postcode;
			}

			this.debugLog( nonceData );

			/**
			 * @link https://docs.poynt.com/app-integration/poynt-collect/#collect-getnonce
			 */
			this.collect.getNonce( nonceData );
		}

		/**
		 * Determines whether a nonce exists.
		 *
		 * Checks the hidden input for a value.
		 *
		 * @since 1.0.0
		 *
		 * @returns {boolean} whether a nonce exists
		 */
		hasNonce() {
			return this.getNonceField().val().length > 0;
		}

		/**
		 * Determines whether a postcode field is present.
		 *
		 * @since 1.0.0
		 *
		 * @returns {boolean} whether the postcode field exists
		 */
		hasPostcodeField() {
			return $( '#billing_postcode' ).length > 0;
		}

		/**
		 * Handles the error event data.
		 *
		 * Logs errors to console and maybe renders them in a user-facing notice.
		 *
		 * @since 1.0.0
		 *
		 * @param {Object} event after a form error
		 */
		handleError( event ) {

			let errorMessage = '';

			// Poynt Collect API has some inconsistency about error message response data:
			if ( 'error' === event.type && event.data ) {
				if ( event.data.error && event.data.error.message && event.data.error.message.message ) {
					errorMessage = event.data.error.message.message;
				} else if ( event.data.error && event.data.error.message ) {
					errorMessage = event.data.error.message;
				} else if ( event.data.message ) {
					errorMessage = event.data.message;
				} else if ( event.data.error ) {
					errorMessage = event.data.error;
				}
			}

			if ( 'string' === typeof errorMessage && errorMessage.length > 0 ) {

				this.debugLog( errorMessage );

				// We can't necessarily assume that we have all of the various error strings available in params
				// because if there's a gateway plugin with an older version of the framework loaded, it can
				// clobber newer error messages
				if ( errorMessage.includes( 'Request failed' ) && this.params.generic_error ) {
					super.render_errors( [ this.params.generic_error ] );
				} else if ( (errorMessage.includes( 'Missing details' ) || errorMessage.includes( 'Enter a card number' )) && this.params.missing_card_details ) {
					super.render_errors( [ this.params.missing_card_details ] );
				} else if ( errorMessage.includes( 'Missing field' ) && this.params.missing_billing_fields ) {
					super.render_errors( [ this.params.missing_billing_fields ] );
				} else {
					super.render_errors( [ errorMessage ] );
				}

			} else {

				this.debugLog( event );
			}
		}

		/**
		 * Handles a payment form ready event.
		 *
		 * Unblocks the payment form after initialization.
		 *
		 * @since 1.0.0
		 *
		 * @param {Object} event after the form is ready
		 */
		handlePaymentFormReady( event ) {

			if ( ! event.type || 'ready' !== event.type ) {
				this.debugLog( event );
			} else {
				this.debugLog( 'Payment form ready' );
			}

			this.form.unblock();
		}

		/**
		 * Handles a nonce ready event.
		 *
		 * Sets the nonce to hidden field and submits the form.
		 *
		 * @since 1.0.0
		 *
		 * @param {Object} payload containing the nonce
		 */
		handleNonceReady( payload ) {

			if ( payload.data && payload.data.nonce ) {
				this.getNonceField().val( payload.data.nonce );
				this.debugLog( 'Nonce set' );
			} else {
				this.clearNonce();
				this.debugLog( 'Nonce value is empty' );
			}

			this.form.submit();
		}

		/**
		 * Initializes the form.
		 *
		 * Adds listeners for the ready and error events.
		 *
		 * @link https://docs.poynt.com/app-integration/poynt-collect/#collect-mount
		 *
		 * @since 1.0.0
		 */
		initForm() {

			// run only once
			if ( this.initializingForm ) {
				return;
			}

			this.initializingForm = true;

			this.collect = new TokenizeJs( this.businessId, this.appId );

			let showZip = ! this.customerAddress.postcode && ! this.hasPostcodeField();
			let options = this.mountOptions || {};

			if ( ! options.iFrame ) {
				options.iFrame = {};
			}

			if ( ! options.displayComponents ) {
				options.displayComponents = {};
			}

			if ( showZip ) {
				options.iFrame.height = '280px';
				options.displayComponents.zipCode = true;
			}

			/**
			 * Initialize the Payment Form with Poynt Collect API.
			 * @link https://docs.poynt.com/app-integration/poynt-collect/getting-started/#methods
			 *
			 * For configuration options, see:
			 * @link https://docs.poynt.com/app-integration/poynt-collect/#collect-mount
			 *
			 * For CSS options, see:
			 * @link https://docs.poynt.com/app-integration/poynt-collect/#passing-in-custom-css-optional
			 * @link https://github.com/medipass/react-payment-inputs#styles
			 */
			this.collect.mount( 'wc-' + this.id_dasherized + '-hosted-form', document, options );

			// triggers when a nonce is ready
			this.collect.on( 'nonce', payload => {
				this.handleNonceReady( payload );
			} );

			// triggers when the payment form is ready
			this.collect.on( 'ready', event => {

				this.initializingForm = false;
				this.formInitialized  = true;

				this.handlePaymentFormReady( event );
			} );

			// triggers when there is a payment form error
			this.collect.on( 'error', error => {
				this.handleError( error );
			} );
		}

		/**
		 * Sets up the payment fields.
		 *
		 * Calls parent method and initializes the payment form.
		 *
		 * @since 1.0.0
		 */
		set_payment_fields() {

			super.set_payment_fields();

			if ( this.formInitialized ) {
				this.collect.unmount( 'wc-' + this.id_dasherized + '-hosted-form', document );
				this.formInitialized = false;
			}

			if ( this.businessId && this.appId && ! this.initializingForm ) {
				this.initForm();
			}
		}

		/**
		 * Validates card data.
		 *
		 * Implements and overrides parent method (bypasses validation, handled by Poynt API).
		 *
		 * @since 1.0.0
		 *
		 * @returns {boolean} always true, as the fields are hosted
		 */
		validate_card_data() {
			return true;
		}

		/**
		 * Logs an item to console if logging is enabled.
		 *
		 * @since 1.0.0
		 *
		 * @param {String|Object} logData
		 */
		debugLog( logData ) {
			if ( this.isLoggingEnabled ) {
				console.log( logData );
			}
		}

	}

	// dispatch loaded event
	$( document.body ).trigger( 'wc_poynt_payment_form_handler_loaded' );

} );
