( function ( $ ) {
	let timer;
	const INTRKT_cart_abandonment = {
		init() {
			if (
				intrkt_ca_vars._show_gdpr_message &&
				! $( '#INTRKT_cf_gdpr_message_block' ).length
			) {
				$( '#billing_email' ).after(
					"<span id='INTRKT_cf_gdpr_message_block'> <span style='font-size: xx-small'> " +
						intrkt_ca_vars._gdpr_message +
						" <a style='cursor: pointer' id='INTRKT_ca_gdpr_no_thanks'> " +
						intrkt_ca_vars._gdpr_nothanks_msg +
						' </a></span></span>'
				);
			}

			$( document ).on(
				'focusout',
				'#billing_email, #billing_phone, input.input-text, textarea.input-text, select',
				this._getCheckoutData
			);

			$( '#INTRKT_ca_gdpr_no_thanks' ).on( 'click', function () {
				// INTRKT_cart_abandonment._set_cookie();
			} );

			$( document.body ).on( 'updated_checkout', function () {
				INTRKT_cart_abandonment._getCheckoutData();
			} );

			$( function () {
				setTimeout( function () {
					INTRKT_cart_abandonment._getCheckoutData();
				}, 800 );
			} );
		},

		_validate_email( value ) {
			let valid = true;
			if ( value.indexOf( '@' ) === -1 ) {
				valid = false;
			} else {
				const parts = value.split( '@' );
				const domain = parts[ 1 ];
				if ( domain.indexOf( '.' ) === -1 ) {
					valid = false;
				} else {
					const domainParts = domain.split( '.' );
					const ext = domainParts[ 1 ];
					if ( ext.length > 14 || ext.length < 2 ) {
						valid = false;
					}
				}
			}
			return valid;
		},
		_validate_phone_number( value ) {
			// var re = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im;
			var re = /^[\+]?\d+$/;
			return re.test(value);
		},

		_getCheckoutData() {
			const intrkt_email = jQuery( '#billing_email' ).val();

			if ( typeof intrkt_email === 'undefined' ) {
				return;
			}

			let intrkt_phone = jQuery( '#billing_phone' ).val();
			const atposition = intrkt_email.indexOf( '@' );
			const dotposition = intrkt_email.lastIndexOf( '.' );

			if ( typeof intrkt_phone === 'undefined' || intrkt_phone === null ) {
				//If phone number field does not exist on the Checkout form
				intrkt_phone = '';
			}

			clearTimeout( timer );

			if (
				intrkt_phone.length >= 1
			) {
				//Checking if the email field is valid or phone number is longer than 1 digit
				//If Email or Phone valid
				const intrkt_name = jQuery( '#billing_first_name' ).val();
				const intrkt_surname = jQuery( '#billing_last_name' ).val();
				intrkt_phone = jQuery( '#billing_phone' ).val();
				const intrkt_country = jQuery( '#billing_country' ).val();
				const intrkt_city = jQuery( '#billing_city' ).val();

				//Other fields used for "Remember user input" function
				const intrkt_billing_company = jQuery( '#billing_company' ).val();
				const intrkt_billing_address_1 = jQuery(
					'#billing_address_1'
				).val();
				const intrkt_billing_address_2 = jQuery(
					'#billing_address_2'
				).val();
				const intrkt_billing_state = jQuery( '#billing_state' ).val();
				const intrkt_billing_postcode = jQuery(
					'#billing_postcode'
				).val();
				const intrkt_shipping_first_name = jQuery(
					'#shipping_first_name'
				).val();
				const intrkt_shipping_shipping_last_name = jQuery(
					'#shipping_last_name'
				).val();
				const intrkt_shipping_company = jQuery(
					'#shipping_company'
				).val();
				const intrkt_shipping_country = jQuery(
					'#shipping_country'
				).val();
				const intrkt_shipping_address_1 = jQuery(
					'#shipping_address_1'
				).val();
				const intrkt_shipping_address_2 = jQuery(
					'#shipping_address_2'
				).val();
				const intrkt_shipping_city = jQuery( '#shipping_city' ).val();
				const intrkt_shipping_state = jQuery( '#shipping_state' ).val();
				const intrkt_shipping_postcode = jQuery(
					'#shipping_postcode'
				).val();
				const intrkt_order_comments = jQuery( '#order_comments' ).val();
				// Coupon code
				// const intrkt_coupon_code = jQuery( '.cart-discount a'
				// ).data('coupon');
				const data = {
					action: 'intrkt_save_cart_abandonment_data',
					intrkt_email,
					intrkt_name,
					intrkt_surname,
					intrkt_phone,
					intrkt_country,
					intrkt_city,
					intrkt_billing_company,
					intrkt_billing_address_1,
					intrkt_billing_address_2,
					intrkt_billing_state,
					intrkt_billing_postcode,
					intrkt_shipping_first_name,
					intrkt_shipping_shipping_last_name,
					intrkt_shipping_company,
					intrkt_shipping_country,
					intrkt_shipping_address_1,
					intrkt_shipping_address_2,
					intrkt_shipping_city,
					intrkt_shipping_state,
					intrkt_shipping_postcode,
					intrkt_order_comments,
					// intrkt_coupon_code,
					security: intrkt_ca_vars._nonce,
					intrkt_post_id: intrkt_ca_vars._post_id,
				};

				timer = setTimeout( function () {
					if (
						INTRKT_cart_abandonment._validate_phone_number( data.intrkt_phone )
					) {
						jQuery.post(
							intrkt_ca_vars.ajaxurl,
							data, //Ajaxurl coming from localized script and contains the link to wp-admin/admin-ajax.php file that handles AJAX requests on Wordpress
							function () {
								// success response
							}
						);
					}
				}, 500 );
			} else {
				//console.log("Not a valid e-mail or phone address");
			}
		},
	};

	INTRKT_cart_abandonment.init();
} )( jQuery );
