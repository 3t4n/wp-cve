jQuery( document ).ready( function ( $ ) {
	var block = function ( $node ) {
		if ( ! is_blocked( $node ) ) {
			$node.addClass( 'processing' ).block( {
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6,
				},
			} );
		}
	};

	var is_blocked = function ( $node ) {
		return (
			$node.is( '.processing' ) || $node.parents( '.processing' ).length
		);
	};

	var unblock = function ( $node ) {
		$node.removeClass( 'processing' ).unblock();
	};

	// variation id
	$( window )
		.bind( 'found_variation', function ( event, variation ) {
			if ( variation == null ) {
			} else {
				var variation_data = {};
				var count = 0;
				var chosen = 0;
				var variation_selector = '';
				var variation_id = '';
				if ( event.hasOwnProperty( 'target' ) ) {
					variation_selector = event.target;
				} else {
					variation_selector = 'form.variations_form.cart';
				}

				$( variation_selector )
					.find( '.variations select' )
					.each( function () {
						var attribute_name =
							$( this ).data( 'attribute_name' ) ||
							$( this ).attr( 'name' );
						var value = $( this ).val() || '';

						if ( value.length > 0 ) {
							chosen++;
						}

						count++;
						variation_data[ attribute_name ] = value;
					} );

				if ( variation.hasOwnProperty( 'id' ) ) {
					variation_id = variation.id;
					$( '#product-id-for-enquiry' ).val( variation.id );
				} else if ( variation.hasOwnProperty( 'variation_id' ) ) {
					variation_id = variation.variation_id;
					$( '#product-id-for-enquiry' ).val(
						variation.variation_id
					);
				} else {
					variation_id = $( 'form.variations_form' ).attr(
						'data-product_id'
					);
					$( '#product-id-for-enquiry' ).val(
						$( 'form.variations_form' ).attr( 'data-product_id' )
					);
				}

				var ajax_url = catalog_enquiry_front.ajaxurl;
				var data = {
					action: 'add_variation_for_enquiry_mail',
					product_id: variation_id,
					variation_data: variation_data,
				};
				$.post( ajax_url, data, function ( response ) {
					console.log( response );
				} );
			}
		} )
		.trigger( 'found_variation' );
	//$('.variations_form').trigger('found_variation');

	// Modal Close
	$(
		'#woocommerce-catalog .catalog-modal .close, #woocommerce-catalog .catalog-modal .btn-default'
	).on( 'click', function () {
		//$("#responsive").hide();
		$( '#responsive' ).slideToggle( 500 );
	} );

	$( '#woocommerce-catalog .woocommerce-catalog-enquiry-btn' ).on(
		'click',
		function () {
			$( '#woocommerce-catalog #responsive' ).slideToggle( 1000 );
		}
	);

	$( '#woocommerce-submit-enquiry' ).on( 'click', function () {
		var name = document.getElementById( 'woocommerce-user-name' ).value;
		var email = document.getElementById( 'woocommerce-user-email' ).value;
		var nonce = document.getElementById( 'wc_catalog_enq' ).value;
		var enquiry_product_type = document.getElementById(
			'enquiry-product-type'
		).value;
		var subject = document.getElementById( 'woocommerce-user-subject' )
			? document.getElementById( 'woocommerce-user-subject' ).value
			: '';
		var phone = document.getElementById( 'woocommerce-user-phone' )
			? document.getElementById( 'woocommerce-user-phone' ).value
			: '';
		var address = document.getElementById( 'woocommerce-user-address' )
			? document.getElementById( 'woocommerce-user-address' ).value
			: '';
		var comment = document.getElementById( 'woocommerce-user-comment' )
			? document.getElementById( 'woocommerce-user-comment' ).value
			: '';
		var fd = new FormData();
		var json_arr = catalog_enquiry_front.json_arr;
		if ( json_arr.indexOf( 'subject' ) != -1 ) {
			subject = document.getElementById(
				'woocommerce-user-subject'
			).value;
		}
		if ( json_arr.indexOf( 'phone' ) != -1 ) {
			phone = document.getElementById( 'woocommerce-user-phone' ).value;
		}
		if ( json_arr.indexOf( 'address' ) != -1 ) {
			address = document.getElementById(
				'woocommerce-user-address'
			).value;
		}
		if ( json_arr.indexOf( 'comment' ) != -1 ) {
			comment = document.getElementById(
				'woocommerce-user-comment'
			).value;
		}
		if ( json_arr.indexOf( 'fileupload' ) != -1 ) {
			var files_data = jQuery( '#woocommerce-user-fileupload' );
			jQuery.each( jQuery( files_data ), function ( i, obj ) {
				jQuery.each( obj.files, function ( j, file ) {
					fd.append( 'fileupload[' + j + ']', file );
				} );
			} );
		}
		var product_name = document.getElementById(
			'product-name-for-enquiry'
		).value;
		var product_url = document.getElementById(
			'product-url-for-enquiry'
		).value;
		var product_id = document.getElementById(
			'product-id-for-enquiry'
		).value;

		if (catalog_enquiry_front.settings_gen.enquiry_form_fileds.find(([key]) => key === 'is-captcha_checkbox') != 'undefined') {
			var captcha = document.getElementById(
				'woocommerce-catalog-captcha'
			);
		}

		if ( name == '' || name == ' ' ) {
			document.getElementById( 'msg-for-enquiry-error' ).innerHTML =
				catalog_enquiry_front.error_levels.name_required;
			document.getElementById( 'woocommerce-user-name' ).focus();
			return false;
		}

		if ( email == '' || email == ' ' ) {
			document.getElementById( 'msg-for-enquiry-error' ).innerHTML =
				catalog_enquiry_front.error_levels.email_required;
			document.getElementById( 'woocommerce-user-email' ).focus();
			return false;
		}
		if ( ! validateEmail( email ) ) {
			document.getElementById( 'msg-for-enquiry-error' ).innerHTML =
				catalog_enquiry_front.error_levels.email_valid;
			document.getElementById( 'woocommerce-user-email' ).focus();
			return false;
		}

		if (catalog_enquiry_front.settings_gen.enquiry_form_fileds.find(([key]) => key === 'is-captcha_checkbox') != 'undefined') {
			if ( captcha && (captcha.value == '' || captcha.value == ' ' ) ) {
				document.getElementById( 'msg-for-enquiry-error' ).innerHTML =
					catalog_enquiry_front.error_levels.captcha_required;
				document
					.getElementById( 'woocommerce-catalog-captcha' )
					.focus();
				return false;
			}
			if ( captcha && captcha.value != catalog_enquiry_front.captcha ) {
				document.getElementById( 'msg-for-enquiry-error' ).innerHTML =
					catalog_enquiry_front.error_levels.captcha_valid;
				document
					.getElementById( 'woocommerce-catalog-captcha' )
					.focus();
				return false;
			}
		}
		block( $( '#responsive' ) );
		jQuery( '#loader-after-sumitting-the-form' ).show();
		jQuery( '#msg-for-enquiry-error' ).html( '' );

		var ajax_url = catalog_enquiry_front.ajaxurl;
		if ( json_arr.indexOf( 'fileupload' ) != -1 ) {
			fd.append( 'action', 'send_enquiry_mail' );
			fd.append( 'wc_catalog_enq', nonce );
			fd.append( 'woocommerce_customer_name', name );
			fd.append( 'woocommerce_customer_email', email );
			fd.append( 'woocommerce_customer_subject', subject );
			fd.append( 'woocommerce_customer_phone', phone );
			fd.append( 'woocommerce_customer_address', address );
			fd.append( 'woocommerce_customer_comment', comment );
			fd.append( 'woocommerce_customer_product_name', product_name );
			fd.append( 'woocommerce_customer_product_url', product_url );
			fd.append( 'woocommerce_customer_product_id', product_id );
			fd.append( 'enquiry_product_type', enquiry_product_type );
			jQuery.ajax( {
				type: 'post',
				url: ajax_url,
				data: fd,
				contentType: false,
				cache: false,
				processData: false,
				success: function ( response ) {
					unblock( $( '#responsive' ) );
					if ( response.status == 1 ) {
						jQuery( '#loader-after-sumitting-the-form' ).hide();
						jQuery( '#msg-for-enquiry-sucesss' ).html( '' );
						jQuery( '#msg-for-enquiry-sucesss' ).html(
							catalog_enquiry_front.ajax_success_msg
						);
						jQuery( '#woocommerce-user-name' ).val( '' );
						jQuery( '#woocommerce-user-email' ).val( '' );
						jQuery( '#woocommerce-catalog-captcha' ).val( '' );
						if ( json_arr.indexOf( 'subject' ) != -1 ) {
							jQuery( '#woocommerce-user-subject' ).val( '' );
						}
						if ( json_arr.indexOf( 'phone' ) != -1 ) {
							jQuery( '#woocommerce-user-phone' ).val( '' );
						}
						if ( json_arr.indexOf( 'address' ) != -1 ) {
							jQuery( '#woocommerce-user-address' ).val( '' );
						}
						if ( json_arr.indexOf( 'comment' ) != -1 ) {
							jQuery( '#woocommerce-user-comment' ).val( '' );
						}
						if ( json_arr.indexOf( 'fileupload' ) != -1 ) {
							jQuery( '#woocommerce-user-fileupload' ).val( '' );
						}

						if (
							typeof catalog_enquiry_front.settings
								.is_page_redirect != 'undefined' &&
							catalog_enquiry_front.settings_gen
								.is_page_redirect !== null
						) {
							window.location.href =
								catalog_enquiry_front.redirect_link;
						}
					} else if ( response.status == 2 ) {
						jQuery( '#loader-after-sumitting-the-form' ).hide();
						jQuery( '#msg-for-enquiry-sucesss' ).html( '' );
						jQuery( '#msg-for-enquiry-error' ).html(
							catalog_enquiry_front.error_levels.filetype_error
						);
					} else if ( response.status == 3 ) {
						jQuery( '#loader-after-sumitting-the-form' ).hide();
						jQuery( '#msg-for-enquiry-sucesss' ).html( '' );
						jQuery( '#msg-for-enquiry-error' ).html(
							catalog_enquiry_front.error_levels.filesize_error
						);
					} else {
						jQuery( '#loader-after-sumitting-the-form' ).hide();
						jQuery( '#msg-for-enquiry-sucesss' ).html( '' );
						if ( response.error_report != '' ) {
							jQuery( '#msg-for-enquiry-error' ).html(
								response.error_report
							);
						} else {
							jQuery( '#msg-for-enquiry-error' ).html(
								catalog_enquiry_front.error_levels.ajax_error
							);
						}
					}
				},
			} );
		} else {
			var data = {
				action: 'send_enquiry_mail',
				wc_catalog_enq: nonce,
				woocommerce_customer_name: name,
				woocommerce_customer_email: email,
				woocommerce_customer_subject: subject,
				woocommerce_customer_phone: phone,
				woocommerce_customer_address: address,
				woocommerce_customer_comment: comment,
				woocommerce_customer_product_name: product_name,
				woocommerce_customer_product_url: product_url,
				woocommerce_customer_product_id: product_id,
				enquiry_product_type: enquiry_product_type,
			};
			jQuery.post( ajax_url, data, function ( response ) {
				unblock( $( '#responsive' ) );
				if ( response.status == 1 ) {
					jQuery( '#loader-after-sumitting-the-form' ).hide();
					jQuery( '#msg-for-enquiry-sucesss' ).html( '' );
					jQuery( '#msg-for-enquiry-sucesss' ).html(
						catalog_enquiry_front.ajax_success_msg
					);
					jQuery( '#woocommerce-user-name' ).val( '' );
					jQuery( '#woocommerce-user-email' ).val( '' );
					jQuery( '#woocommerce-catalog-captcha' ).val( '' );
					if ( json_arr.indexOf( 'subject' ) != -1 ) {
						jQuery( '#woocommerce-user-subject' ).val( '' );
					}
					if ( json_arr.indexOf( 'phone' ) != -1 ) {
						jQuery( '#woocommerce-user-phone' ).val( '' );
					}
					if ( json_arr.indexOf( 'address' ) != -1 ) {
						jQuery( '#woocommerce-user-address' ).val( '' );
					}
					if ( json_arr.indexOf( 'comment' ) != -1 ) {
						jQuery( '#woocommerce-user-comment' ).val( '' );
					}
					if (
						typeof catalog_enquiry_front.settings
							.is_page_redirect != 'undefined' &&
						catalog_enquiry_front.settings_gen.is_page_redirect !==
							null
					) {
						window.location.href =
							catalog_enquiry_front.redirect_link;
					}
				} else if ( response.status == 2 ) {
					jQuery( '#loader-after-sumitting-the-form' ).hide();
					jQuery( '#msg-for-enquiry-sucesss' ).html( '' );
					jQuery( '#msg-for-enquiry-error' ).html(
						catalog_enquiry_front.error_levels.filetype_error
					);
				} else if ( response.status == 3 ) {
					jQuery( '#loader-after-sumitting-the-form' ).hide();
					jQuery( '#msg-for-enquiry-sucesss' ).html( '' );
					jQuery( '#msg-for-enquiry-error' ).html(
						catalog_enquiry_front.error_levels.filesize_error
					);
				} else {
					jQuery( '#loader-after-sumitting-the-form' ).hide();
					jQuery( '#msg-for-enquiry-sucesss' ).html( '' );
					if ( response.error_report != '' ) {
						jQuery( '#msg-for-enquiry-error' ).html(
							response.error_report
						);
					} else {
						jQuery( '#msg-for-enquiry-error' ).html(
							catalog_enquiry_front.error_levels.ajax_error
						);
					}
				}
			} );
		}
	} );

	var modal = document.getElementById( 'responsive' );
	window.onclick = function ( event ) {
		if ( event.target == modal ) {
			modal.style.display = 'none';
		}
	};
} );
function validateEmail( $email ) {
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	return emailReg.test( $email );
}
