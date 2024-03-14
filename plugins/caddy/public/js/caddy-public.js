(function( $ ) {
	'use strict';

	var ccWindow = $( '.cc-window' );

	jQuery( document ).ready( function( $ ) {

		// Get refreshed fragments onLoad
		setTimeout( function() {
			cc_cart_screen();
		}, 200 );

		// Tab usability
		$( '.cc-nav ul li a' ).mousedown( function() {
			$( this ).addClass( 'using-mouse' );
		} );

		$( 'body' ).keydown( function() {
			$( '.cc-nav ul li a' ).removeClass( 'using-mouse' );
		} );

		// cc-window tabbing
		var tabs = new Tabby( '[data-tabs]' );

		// Clicking outside of mini cart
		$( document ).mouseup( function( e ) {
			var container = $( '.cc-window.visible, .cc-compass, #toast-container' );

			// if the target of the click isn't the container nor a descendant of the container
			if ( !container.is( e.target ) && container.has( e.target ).length === 0 ) {
				if ( ccWindow.hasClass( 'visible' ) ) {

					$( '.cc-compass' ).toggleClass( 'cc-compass-open' );
					$( 'body' ).toggleClass( 'cc-window-open' );

					$( '.cc-overlay' ).hide();
					ccWindow.animate( { 'right': '-1000px' }, 'slow' ).removeClass( 'visible' );

					// Remove previous cc-notice-rec (if any)
					if ( $( '#toast-container' ).length > 0 ) {
						$( '#toast-container' ).animate( { 'right': '25px' }, 'fast' ).toggleClass( 'cc-toast-open' );
					}

				}
			}
		} );

		// toggle .cc-window with .cc-compass
		$( document ).on( 'click', '.cc-compass', function() {

			$( this ).toggleClass( 'cc-compass-open' );
			$( 'body' ).toggleClass( 'cc-window-open' );

			// Show or hide cc-window
			if ( ccWindow.hasClass( 'visible' ) ) {
				$( '.cc-overlay' ).hide();
				ccWindow.animate( { 'right': '-1000px' }, 'slow' ).removeClass( 'visible' );
			} else {
				$( '.cc-overlay' ).show();

				// Activate tabby cart tab
				tabs.toggle( '#cc-cart' );

				ccWindow.animate( { 'right': '0' }, 'slow' ).addClass( 'visible' );
			}
		} );

		// .cc-window close button
		$( document ).on( 'click', '.ccicon-x', function() {
			$( '.cc-overlay' ).hide();
			// Show or hide cc-window
			ccWindow.animate( { 'right': '-1000px' }, 'slow' ).removeClass( 'visible' );
			$( '.cc-compass' ).toggleClass( 'cc-compass-open' );
			$( 'body' ).toggleClass( 'cc-window-open' );
		} );

		// Remove cart item
		$( document ).on( 'click', '.cc-cart-product-list .cc-cart-product a.remove_from_cart_button', function() {
			var button = $( this );
			remove_item_from_cart( button );
		} );

		// Remove from save for later
		$( document ).on( 'click', 'a.remove_from_sfl_button', function() {
			var button = $( this );
			remove_item_from_save_for_later( button );
		} );

		$( 'body' ).on( 'added_to_cart', function( e, fragments, cart_hash, this_button ) {
			var cpDeskNotice = $( '.cc-compass-desk-notice' ).val(),
				cpMobNotice = $( '.cc-compass-mobile-notice' ).val();

			// Trigger cc-compass click event
			if ( cc_ajax_script.is_mobile && !ccWindow.hasClass( 'visible' ) && 'mob_disable_notices' === cpMobNotice ) {
				setTimeout( function() {
					$( '.cc-compass' ).trigger( 'click' );
				}, 20 );
			} else if ( !cc_ajax_script.is_mobile && !ccWindow.hasClass( 'visible' )
				&& ('desk_disable_notices' === cpDeskNotice || 'desk_notices_caddy_window' === cpDeskNotice || '' === cpDeskNotice) ) {
				setTimeout( function() {
					$( '.cc-compass' ).trigger( 'click' );
				}, 20 );
			}
		} );

		/* CUSTOM ADD-TO-CART FUNCTIONALITY */
		$( document ).on( 'click', '.single_add_to_cart_button', function( e ) {

			e.preventDefault();

			//If the button is disabled don't allow this to fire.
			if ( $( this ).hasClass( 'disabled' ) ) {
				return;
			}

			//If the product is not simple on the shop page.
			if ( $( this ).hasClass( 'product_type_variable' ) || $( this ).hasClass( 'product_type_bundle' ) ||
				$( this ).hasClass( 'product_type_external' ) ) {
				window.location = $( this ).attr( 'href' );
				return;
			}

			var $button = $( this ),
				$form = $button.closest( 'form.cart' ),
				productData = $form.serializeArray(),
				hasProductId = false;

			$.each( productData, function( key, form_item ) {
				if ( form_item.name === 'productID' || form_item.name === 'add-to-cart' ) {
					if ( form_item.value ) {
						hasProductId = true;
						return false;
					}
				}
			} );

			//If no product id found , look for the form action URL
			if ( !hasProductId ) {
				var productID = $( this ).data( 'product_id' );
			}

			// if button as name add-to-cart get it and add to form
			if ( $button.attr( 'name' ) && $button.attr( 'name' ) == 'add-to-cart' && $button.attr( 'value' ) ) {
				var productID = $button.attr( 'value' );
			}

			if ( productID ) {
				productData.push( { name: 'add-to-cart', value: productID } );
			}

			productData.push( { name: 'action', value: 'cc_add_to_cart' } );

			$( document.body ).trigger( 'adding_to_cart', [$button, productData] );

			$.ajax( {
				type: 'post',
				url: cc_ajax_script.wc_ajax_url.toString().replace( '%%endpoint%%', 'cc_add_to_cart' ),
				data: $.param( productData ),
				beforeSend: function( response ) {
					$( '#cc-cart' ).css( 'opacity', '0.3' );
					// Replace compass icon with loader icon
					$( '.cc-compass' ).find( '.licon' ).hide();
					$( '.cc-compass' ).find( '.cc-loader' ).show();
					if ( $( 'form.cart' ).length > 0 ) {
						$button.removeClass( 'added' ).addClass( 'loading' );
					}
				},
				success: function( response ) {
					if ( response.error && response.product_url ) {
						window.location.reload();
					} else {
						// Trigger event so themes can refresh other areas.
						if ( !$button.hasClass( 'add_to_cart_button' ) ) {
							cc_cart_screen();
							$( document.body ).trigger( 'added_to_cart', [response.fragments, response.cart_hash, $button] );
						}
					}
				},
				complete: function( response ) {
					// Replace loader icon with compass close icon
					$( '.cc-compass' ).find( '.cc-loader' ).hide();
					$( '.cc-compass' ).find( '.licon' ).show();
					if ( $( 'form.cart' ).length > 0 ) {
						$button.addClass( 'added' ).removeClass( 'loading' );
					}
					$( '#cc-cart' ).css( 'opacity', '1' );
				}
			} );

			return false;
		} );

		// Product added view cart button
		$( document ).on( 'click', '.cc-pl-info .cc-pl-actions .cc-view-cart', function() {
			// Activate tabby cart tab
			tabs.toggle( '#cc-cart' );
		} );

		// Item quantity update
		$( document ).on( 'click', '.cc_item_quantity_update', function() {
			var $this = $(this);
			var quantityInput = $this.siblings('.cc_item_quantity');
			var currentQuantity = parseInt(quantityInput.val(), 10);
			
			// Check if minus button is clicked and quantity is 1
			if ($this.hasClass('cc_item_quantity_minus') && currentQuantity === 1) {
				// Find the remove button related to this product and trigger its click event
				var removeButton = $this.closest('.cc-cart-product').find('a.remove_from_cart_button');
				removeButton.trigger('click');
			} else {
				// Regular quantity update process
				cc_quantity_update_buttons($this);
			}
		} );

		// Save for later button click from the Caddy cart screen
		$( document ).on( 'click', '.save_for_later_btn', function() {
			cc_save_for_later( $( this ) );
		} );

		// Move to cart button clicked
		$( document ).on( 'click', '.cc_cart_from_sfl', function() {
			cc_move_to_cart( $( this ) );
		} );

		// Move to cart button
		$( document ).on( 'click', '.cc_back_to_cart', function() {
			cc_back_to_cart();
		} );

		// View cart button clicked
		$( document ).on( 'click', '.added_to_cart.wc-forward, .woocommerce-error .button.wc-forward', function( e ) {
			e.preventDefault();
			cc_cart_item_list();
		} );

		// Saved items list button clicked
		$( document ).on( 'click', '.cc_saved_items_list', function() {
			cc_saved_item_list();
		} );

		// Cart items list button clicked
		$( document ).on( 'click', '.cc_cart_items_list', function() {
			cc_cart_item_list();
		} );

		// Clicks on a view saved items
		$( document ).on( 'click', '.cc-view-saved-items', function() {

			// Activate tabby saves tab
			var tabs = new Tabby( '[data-tabs]' );
			tabs.toggle( '#cc-saves' );

		} );

		if ( $( '.variations_form' ).length > 0 ) {

			$( '.cc_add_product_to_sfl' ).addClass( 'disabled' );
			$( this ).each( function() {

				// when variation is found, do something
				$( this ).on( 'found_variation', function( event, variation ) {
					$( '.cc_add_product_to_sfl' ).removeClass( 'disabled' );
				} );

				$( this ).on( 'reset_data', function() {
					$( '.cc_add_product_to_sfl' ).addClass( 'disabled' );
				} );

			} );

		}

		$( document ).on( 'submit', '#apply_coupon_form', function( e ) {
			e.preventDefault();
			cc_coupon_code_applied_from_cart_screen();
		} );

		$( document ).on( 'click', '.cc-applied-coupon .cc-remove-coupon', function() {
			cc_coupon_code_removed_from_cart_screen( $( this ) );
		} );

		$( document ).on( 'click', '.cc-nav ul li a', function() {
			var current_tab = $( this ).attr( 'data-id' );
			if ( 'cc-cart' === current_tab ) {
				$( '.cc-pl-upsells-slider' ).resize();
			}
		} );
	} ); // end ready

	$( window ).on( 'load', function() {
		$( '.cc-compass .cc-compass-count' ).show();
	} );

	/* Load cart screen */
	function cc_cart_screen( productAdded = '' ) {
		$.ajax( {
			type: 'post',
			url: cc_ajax_script.wc_ajax_url.toString().replace( '%%endpoint%%', 'get_refreshed_fragments' ),
			beforeSend: function( response ) {
				$( '#cc-cart' ).css( 'opacity', '0.3' );
			},
			complete: function( response ) {
				$( '#cc-cart' ).css( 'opacity', '1' );
			},
			success: function( response ) {
				var fragments = response.fragments;
				// Replace fragments
				if ( fragments ) {
					$.each( fragments, function( key, value ) {
						$( key ).replaceWith( value );
					} );
				}

				// Activate tabby cart tab
				var tabs = new Tabby( '[data-tabs]' );
				tabs.toggle( '#cc-cart' );

				if ( 'yes' == productAdded ) {
					$( '.cc-window-wrapper' ).hide();
				}

				if ( 'move_to_cart' === productAdded ) {
					$( '.cc_cart_from_sfl' ).removeClass( 'cc_hide_btn' );
					$( '.cc_cart_from_sfl' ).parent().find( '.cc-loader' ).hide();
					$( '.cc-coupon .woocommerce-notices-wrapper' ).remove();
					$( '.cc-cart' ).removeAttr( 'hidden' );
				}

				// Redirect to product page based on flag
				var flatsome_product_redirect = response.flatsome_product_redirect;
				if ( 'no' === flatsome_product_redirect ) {
					return false;
				}

			}
		} );

	}

	var cc_quanity_update_send = true;

	/* Quantity update in cart screen */
	function cc_quantity_update_buttons( el ) {
		if ( cc_quanity_update_send ) {
			cc_quanity_update_send = false;
			$( '.cc-notice' ).hide();
			var wrap = $( el ).parents( '.cc-cart-product-list' );
			var input = $( wrap ).find( '.cc_item_quantity' );
			var key = $( input ).data( 'key' );
			var productID = $( input ).data( 'product_id' );
			var number = parseInt( $( input ).val() );
			var type = $( el ).data( 'type' );
			if ( 'minus' == type ) {
				number --;
			} else {
				number ++;
			}
			if ( number < 1 ) {
				number = 1;
			}
			var data = {
				key: key,
				number: number,
				product_id: productID,
				security: cc_ajax_script.nonce
			};

			$.ajax( {
				type: 'post',
				url: cc_ajax_script.wc_ajax_url.toString().replace( '%%endpoint%%', 'cc_quantity_update' ),
				data: data,
				beforeSend: function( response ) {
					$( '#cc-cart' ).css( 'opacity', '0.3' );
				},
				complete: function( response ) {
					$( '#cc-cart' ).css( 'opacity', '1' );
				},
				success: function( response ) {

					var fragments = response.fragments,
						qty_error_msg = response.qty_error_msg;

					// Replace fragments
					if ( fragments ) {
						$.each( fragments, function( key, value ) {
							$( key ).replaceWith( value );
						} );
					}

					if ( qty_error_msg ) {
						$( '.cc-notice' ).addClass( 'cc-error' ).show().html( qty_error_msg );
						setTimeout( function() {
								$( '.cc-notice' ).removeClass( 'cc-error' ).html( '' ).hide();
							},
							2000 );
					}

					$( input ).val( number );
					cc_quanity_update_send = true;

					// Activate tabby cart tab
					var tabs = new Tabby( '[data-tabs]' );
					tabs.toggle( '#cc-cart' );

				}
			} );

		}
	}

	/* Move to save for later */
	function cc_save_for_later( $button ) {
		var product_id = $button.data( 'product_id' );
		var cart_item_key = $button.data( 'cart_item_key' );

		// AJAX Request for add item to wishlist
		var data = {
			security: cc_ajax_script.nonce,
			product_id: product_id,
			cart_item_key: cart_item_key
		};

		$.ajax( {
			type: 'post',
			dataType: 'json',
			url: cc_ajax_script.wc_ajax_url.toString().replace( '%%endpoint%%', 'cc_save_for_later' ),
			data: data,
			beforeSend: function( response ) {
				$( '#cc-cart' ).css( 'opacity', '0.3' );
				$button.addClass( 'cc_hide_btn' );
				$button.parent().find( '.cc-loader' ).show();
			},
			complete: function( response ) {
				$button.removeClass( 'cc_hide_btn' );
				$button.parent().find( '.cc-loader' ).hide();
				$( '#cc-cart' ).css( 'opacity', '1' );
			},
			success: function( response ) {
				var fragments = response.fragments;
				// Replace fragments
				if ( fragments ) {
					$.each( fragments, function( key, value ) {
						$( key ).replaceWith( value );
					} );
				}

				// Activate tabby saves tab
				var tabs = new Tabby( '[data-tabs]' );
				tabs.toggle( '#cc-saves' );
			}
		} );

	}

	/* Move to cart from save for later */
	function cc_move_to_cart( $button ) {
		var product_id = $button.data( 'product_id' );

		// AJAX Request for add item to cart from wishlist
		var data = {
			security: cc_ajax_script.nonce,
			product_id: product_id,
		};

		$.ajax( {
			type: 'post',
			dataType: 'json',
			url: cc_ajax_script.wc_ajax_url.toString().replace( '%%endpoint%%', 'cc_move_to_cart' ),
			data: data,
			beforeSend: function( response ) {
				$button.addClass( 'cc_hide_btn' );
				$button.parent().find( '.cc-loader' ).show();
			},
			success: function( response ) {
				if ( response.error ) {
					$button.removeClass( 'cc_hide_btn' );
					$button.parent().find( '.cc-loader' ).hide();

					// Activate tabby saves tab
					var tabs = new Tabby( '[data-tabs]' );
					tabs.toggle( '#cc-saves' );

					$( '.cc-sfl-notice' ).show().html( response.error_message );
					setTimeout( function() {
							$( '.cc-sfl-notice' ).html( '' ).hide();
						},
						2000 );
				} else {
					cc_cart_screen( 'move_to_cart' );
				}
			}
		} );
	}

	/* Remove item from the cart */
	function remove_item_from_cart( button ) {
		var cartItemKey = button.data( 'cart_item_key' ),
			productName = button.data( 'product_name' ),
			product_id = button.data( 'product_id' );

		// AJAX Request for remove product from the cart
		var data = {
			nonce: cc_ajax_script.nonce,
			cart_item_key: cartItemKey
		};
		$.ajax( {
			type: 'post',
			url: cc_ajax_script.wc_ajax_url.toString().replace( '%%endpoint%%', 'cc_remove_item_from_cart' ),
			data: data,
			complete: function( response ) {
				// Remove "added" class after deleting the item from the cart
				if ( ($( '.single_add_to_cart_button, .add_to_cart_button' ).length > 0) ) {
					$( '.single_add_to_cart_button.added, .add_to_cart_button.added' ).each( function() {
						if ( $( 'form.cart' ).length > 0 && !$( this ).hasClass( 'add_to_cart_button' ) ) {
							var $form = $( this ).closest( 'form.cart' ),
								atc_product_id = $form.find( 'input[name=add-to-cart]' ).val() || $( this ).val(),
								atc_variation_id = $form.find( 'input[name=variation_id]' ).val() || 0;
							if ( atc_variation_id !== 0 ) {
								atc_product_id = atc_variation_id;
							}
						} else {
							var atc_product_id = $( this ).data( 'product_id' );
						}
						if ( atc_product_id == product_id ) {
							if ( $( this ).hasClass( 'added' ) ) {
								$( this ).removeClass( 'added' );
							}
						}
					} );
				}
			},
			success: function( response ) {
				var fragments = response.fragments;
				// Replace fragments
				if ( fragments ) {
					$.each( fragments, function( key, value ) {
						$( key ).replaceWith( value );
					} );
				}

				// Activate tabby cart tab
				var tabs = new Tabby( '[data-tabs]' );
				tabs.toggle( '#cc-cart' );
			}
		} );
	}

	/* Remove item from save for later */
	function remove_item_from_save_for_later( button ) {

		var productID = button.data( 'product_id' );

		// AJAX Request for remove product from the cart
		var data = {
			nonce: cc_ajax_script.nonce,
			product_id: productID
		};

		$.ajax( {
			type: 'post',
			url: cc_ajax_script.wc_ajax_url.toString().replace( '%%endpoint%%', 'cc_remove_item_from_sfl' ),
			data: data,
			beforeSend: function( response ) {
				$( '#cc-saves' ).css( 'opacity', '0.3' );
			},
			complete: function( response ) {
				$( '#cc-saves' ).css( 'opacity', '1' );
			},
			success: function( response ) {
				var fragments = response.fragments;
				// Replace fragments
				if ( fragments ) {
					$.each( fragments, function( key, value ) {
						$( key ).replaceWith( value );
					} );
				}

				// Change to empty heart icon after removing the product
				var sfl_btn = $( 'a.cc-sfl-btn.remove_from_sfl_button' );
				if ( sfl_btn.has( 'i.ccicon-heart-filled' ) ) {
					sfl_btn.find( 'i' ).removeClass( 'ccicon-heart-filled' ).addClass( 'ccicon-heart-empty' );
					var sfl_btn_text = sfl_btn.find( 'span' ).text();
					if ( sfl_btn_text.length > 0 ) {
						sfl_btn.find( 'span' ).text( 'Save for later' );
					}
					sfl_btn.removeClass( 'remove_from_sfl_button' ).addClass( 'cc_add_product_to_sfl' );
				}

				// Activate tabby cart tab
				var tabs = new Tabby( '[data-tabs]' );
				tabs.toggle( '#cc-saves' );

			}

		} );

	}

	/* Back to cart link */
	function cc_back_to_cart() {
		$( '.cc-pl-info-container' ).hide();
		$( '.cc-window-wrapper' ).show();
	}

	/* Saved item list button clicked */
	function cc_saved_item_list() {

		$( '.cc-compass' ).toggleClass( 'cc-compass-open' );
		$( 'body' ).toggleClass( 'cc-window-open' );

		$( '.cc-pl-info-container' ).hide();
		$( '.cc-window-wrapper' ).show();

		// Show or hide cc-window
		$( '.cc-overlay' ).show();

		// Activate tabby saves tab
		var tabs = new Tabby( '[data-tabs]' );
		tabs.toggle( '#cc-saves' );

		ccWindow.animate( { 'right': '0' }, 'slow' ).addClass( 'visible' );
	}

	/* Cart item list button clicked */
	function cc_cart_item_list() {
		if ( !ccWindow.hasClass( 'visible' ) ) {
			$( '.cc-compass' ).trigger( 'click' );
		}
	}

	/* Apply coupon code from the cart screen */
	function cc_coupon_code_applied_from_cart_screen() {

		var coupon_code = $( '.cc-coupon-form #cc_coupon_code' ).val();

		// AJAX Request to apply coupon code to the cart
		var data = {
			nonce: cc_ajax_script.nonce,
			coupon_code: coupon_code
		};

		$.ajax( {
			type: 'post',
			url: cc_ajax_script.wc_ajax_url.toString().replace( '%%endpoint%%', 'cc_apply_coupon_to_cart' ),
			data: data,
			beforeSend: function( response ) {
				$( '#cc-cart' ).css( 'opacity', '0.3' );
			},
			complete: function( response ) {
				$( '#cc-cart' ).css( 'opacity', '1' );
			},
			success: function( response ) {
				var fragments = response.fragments,
					caddy_cart_subtotal = response.caddy_cart_subtotal;

				// Replace fragments
				if ( fragments ) {
					$.each( fragments, function( key, value ) {
						$( key ).replaceWith( value );
					} );
				}

				$( '.cc-total-amount' ).html( caddy_cart_subtotal );

				// Activate tabby cart tab
				var tabs = new Tabby( '[data-tabs]' );
				tabs.toggle( '#cc-cart' );

			}

		} );

	}

	/* Remove coupon code from the cart screen */
	function cc_coupon_code_removed_from_cart_screen( $remove_code ) {

		var coupon_code_to_remove = $remove_code.parent( '.cc-applied-coupon' ).find( '.cc_applied_code' ).text();

		// AJAX Request to apply coupon code to the cart
		var data = {
			nonce: cc_ajax_script.nonce,
			coupon_code_to_remove: coupon_code_to_remove
		};

		$.ajax( {
			type: 'post',
			url: cc_ajax_script.wc_ajax_url.toString().replace( '%%endpoint%%', 'cc_remove_coupon_code' ),
			data: data,
			beforeSend: function( response ) {
				$( '#cc-cart' ).css( 'opacity', '0.3' );
			},
			complete: function( response ) {
				$( '#cc-cart' ).css( 'opacity', '1' );
			},
			success: function( response ) {
				var fragments = response.fragments,
					fs_title = response.free_shipping_title,
					fs_meter = response.free_shipping_meter,
					final_cart_subtotal = response.final_cart_subtotal;

				// Replace fragments
				if ( fragments ) {
					$.each( fragments, function( key, value ) {
						$( key ).replaceWith( value );
					} );
				}

				$( '.cc-fs-title' ).html( fs_title );
				$( '.cc-fs-meter' ).html( fs_meter );
				$( '.cc-total-amount' ).html( final_cart_subtotal );

				// Activate tabby cart tab
				var tabs = new Tabby( '[data-tabs]' );
				tabs.toggle( '#cc-cart' );

			}

		} );

	}

})( jQuery );