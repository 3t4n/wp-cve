/**
 * Takes lots of notes of how WooCommerce uses AJAX and JavaScript to add and handle the proccess
 * of adding a variable product to the cart
 * 
 * @since 1.0.0
 */
jQuery( function( $ ) {
	'use strict';

	/**
	 * AddToCartHandler class.
	 */
	var AddToCartHandler = function() {
		var self = this;

		self.showValidation = self.showValidation.bind( self );
		self.showNotices = self.showNotices.bind( self );
		self.scrollToNotices = self.scrollToNotices.bind( self );
		self.blockButtons = self.blockButtons.bind( self );
		self.unblockButtons = self.unblockButtons.bind( self );

		if ( true == EAA2C.blocking ) {
			$( document.body )
				.on( 'click', '.variable_add_to_cart_button', this.blockButtons )
				.on( 'click', '.simple_add_to_cart_button', this.blockButtons )
				.on( 'click', '.a2cp_button', this.blockButtons )
				.on( 'a2cp_added_to_cart', this.unblockButtons )
				.on( 'notices_received', this.unblockButtons );
		}

		$( document.body )
			.on( 'click', '.variable_add_to_cart_button', this.onAddAnyToCart )
			.on( 'click', '.simple_add_to_cart_button', this.onAddAnyToCart )
			.on( 'click', '.a2cp_button', this.onAddAnyToCart )
			.on( 'a2cp_added_to_cart', this.updateButton )
			.on( 'a2cp_added_to_cart', this.updateCartPage )
			.on( 'a2cp_added_to_cart', this.updateFragments )
			.on( 'notices_received', this.showNotices )
			.on( 'validation_message', this.showValidation );

	};

	AddToCartHandler.prototype.blockButtons = function( e ) {
		$( '.simple_add_to_cart_button' ).attr( 'disabled', true );
		$( '.variable_add_to_cart_button' ).attr( 'disabled', true );
		$( '.a2cp_button' ).attr( 'disabled', true );
	};

	AddToCartHandler.prototype.unblockButtons = function( e ) {
		$( '.simple_add_to_cart_button' ).attr( 'disabled', false );
		$( '.variable_add_to_cart_button' ).attr( 'disabled', false );
		$( '.a2cp_button' ).attr( 'disabled', false );
	};

	/**
	 * Handle the variable product add to cart event.
	 */
	AddToCartHandler.prototype.onAddAnyToCart = function( e ) {
		var $thisbutton = $( this );
		e.preventDefault();

		$thisbutton.removeClass( "added" );
		$thisbutton.addClass( "loading" );

		var data = {
			pid: 0,
			vid: 0,
			qty: 0,
		};
		var qty = {};
		var min = 0;
		var max = 0;

		if ( true === EAA2C.domCheck ) {
			data['pid'] = parseInt( $thisbutton.data('pid') );
			data['vid'] = parseInt( $thisbutton.data('vid') );
		} else {
			$.each( $( this ).data(), function( key, value ) {
				data[ key ] = value;
			});
		}

		qty = $( this ).siblings( '.quantity-container' ).find( 'input.input-text.qty.text' );
		data[ 'action' ] = 'eaa2c_add_to_cart';

		min = parseInt( qty.attr( 'min' ) );
		max = parseInt( qty.attr( 'max' ) );

		if ( isNaN( max ) || max === '' ) {
			max = -1;
		}

		data[ 'qty' ] = qty.val();
		if ( EAA2C.debug ) {
			console.log( "quantity max: " + max + " and min: " + min + " and val: " + qty.val() );
		}

		// Trigger event.
		$( document.body ).trigger( 'adding_to_cart', [ $thisbutton, data ] );

		if ( ( parseInt( max ) === -1 && data[ 'qty' ] >= min )
				|| ( data[ 'qty' ] <= max && data[ 'qty' ] >= min )
		) {
			$.ajax({
				url:  EAA2C.ajax_url,
				type: "POST",
				data: {
					product:  data[ 'pid' ],
					variable: data[ 'vid' ],
					quantity: data[ 'qty' ],
					action:   'eaa2c_add_to_cart',
					eaa2c_action: true,
					'wc-ajax': true,
				},
				success: function( response ) {
					if ( EAA2C.debug ) {
						console.log( "product id: " + data[ 'pid' ] + " variable id: " + data[ 'vid' ] + " quantity: " + data[ 'qty' ] );
					}
					if ( response.added ) {
						$( document.body ).trigger( 'a2cp_added_to_cart', [ response.fragments, response.cart_hash, $thisbutton ] );
						$( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash, $thisbutton ] ); // possibly catches custom implementations?
					}
					if ( response.error ) {
						$thisbutton.removeClass( "loading" );
					}
					if ( response.html ) {
						$( document.body ).trigger( 'notices_received', [ response.html ] );
					}
				},
				error: function() {
					console.error( "Failure adding product to cart!" );
					if ( EAA2C.debug ) {
						console.log( "product id: " + data[ 'pid' ] + " variable id: " + data[ 'vid' ] + " quantity: " + data[ 'qty' ] );
					}
				},
			});
		} else if ( data[ 'qty' ] > max ) {
			var errorHtml = '<ul class="woocommerce-error eaa2c-error eaa2c-message" role="alert"><li>cannot add product to cart, you are over the allowed maximum of ' + qty.attr( 'max' ) + ' to add to your cart.</li></ul>';
			$thisbutton.removeClass( "loading" );
			$( document.body ).trigger( 'notices_received', [ errorHtml ] );
			// $( document.body ).trigger( 'validation_message', [ $thisbutton, errorHtml ] );
		} else if ( data[ 'qty' ] < min ) {
			var errorHtml = '<ul class="woocommerce-error eaa2c-error eaa2c-message" role="alert"><li>cannot add product to cart, you are under the allowed minimum ' + qty.attr( 'min' ) + ' to add to your cart.</li></ul>';
			$thisbutton.removeClass( "loading" );
			$( document.body ).trigger( 'notices_received', [ errorHtml ] );
			// $( document.body ).trigger( 'validation_message', [ $thisbutton, errorHtml ] );
		}
	};

	/**
	 * @deprecated Since version 2.0.0. Will be deleted in 3.0. Use AddToCartHandler.prototype.onAddAnyToCart instead.
	 * Handle the variable product add to cart event.
	 */
	AddToCartHandler.prototype.onAddVariableToCart = function( e ) {
		console.warn( "Calling deprecated function 'onAddVariableToCart'. Deprecated in 2.0.0 of Enhanced AJAX Add to Cart." );
		var $thisbutton = $( this );
		e.preventDefault();

		$thisbutton.removeClass( "added" );
		$thisbutton.addClass( "loading" );

		var data = {};

		if ( true === EAA2C.domCheck ) {
			data['pid'] = parseInt( $thisbutton.data('pid') );
			data['vid'] = parseInt( $thisbutton.data('vid') );
		} else {
			$.each( $( this ).data(), function( key, value ) {
				data[ key ] = value;
			});
		}

		data[ 'qty' ]	 = $thisbutton.siblings( '.quantity-container' ).find( 'input' ).val();
		data[ 'action' ] = 'variable_add_to_cart';

		// Trigger event.
		$( document.body ).trigger( 'adding_to_cart', [ $thisbutton, data ] );

		$.ajax({
			url: EAA2C.ajax_url,
			type: "POST",
			data:{
				product:  data[ 'pid' ],
				variable: data[ 'vid' ],
				quantity: data[ 'qty' ],
				action:   'variable_add_to_cart',
				eaa2c_action: true,
				'wc-ajax': true,
			},
			success: function( response ) {
				if ( EAA2C.debug ) {
					console.log( "product id: " + data[ 'pid' ] + " variation: " + data[ 'vid' ] + " quantity: " + data[ 'qty' ] );
				}
				$( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash, $thisbutton ] );
				if ( response.html ) {
					$( document.body ).trigger( 'notices_received', [ response.html ] );
				}
			},
			error: function() {
				console.error( "Failure adding variable product to cart!" );
				if ( EAA2C.debug ) {
					console.log( "product id: " + data[ 'pid' ] + " variation: " + data[ 'vid' ] + " quantity: " + data[ 'qty' ] );
				}
			},
		});
	};

	/**
	 * @deprecated Since version 2.0.0. Will be deleted in 3.0. Use AddToCartHandler.prototype.onAddAnyToCart instead.
	 * Handle the simple product add to cart event.
	 */
	AddToCartHandler.prototype.onAddSimpleToCart = function( e ) {
		console.warn( "Calling deprecated function 'onAddSimpleToCart'. Deprecated in 2.0.0 of Enhanced AJAX Add to Cart." );
		var $thisbutton = $( this );
		e.preventDefault();

		$thisbutton.removeClass( "added" );
		$thisbutton.addClass( "loading" );

		var data = {};

		if ( true === EAA2C.domCheck ) {
			data['pid'] = parseInt( $thisbutton.data('pid') );
			data['vid'] = parseInt( $thisbutton.data('vid') );
		} else {
			$.each( $( this ).data(), function( key, value ) {
				data[ key ] = value;
			});
		}

		data[ 'qty' ]    = $( this ).siblings( '.quantity-container' ).find( 'input.input-text.qty.text' ).val();
		data[ 'action' ] = 'simple_add_to_cart';

		// Trigger event.
		$( document.body ).trigger( 'adding_to_cart', [ $thisbutton, data ] );

		$.ajax({
			url: EAA2C.ajax_url,
			type: "POST",
			data:{
				product:  data['pid'],
				quantity: data['qty'],
				action:   'simple_add_to_cart',
				eaa2c_action: true,
				'wc-ajax': true,
			},
			success: function( response ) {
				if ( EAA2C.debug ) {
					console.log( "product id: " + data[ 'pid' ] + " quantity: " + data[ 'qty' ] );
				}
				$( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash, $thisbutton ] );
				if ( response.html ) {
					$( document.body ).trigger( 'notices_received', [ response.html ] );
				}
			},
			error: function() {
				console.error( "Failure adding non-variable product to cart!" );
				if ( EAA2C.debug ) {
					console.log( "product id: " + data[ 'pid' ] + " quantity: " + data[ 'qty' ] );
				}
			},
		});
	};

	/**
	 * Update cart page elements after add to cart events.
	 */
	AddToCartHandler.prototype.updateButton = function( e, fragments, cart_hash, $button ) {
		$button = typeof $button === 'undefined' ? false : $button;

		if ( $button ) {
			$button.removeClass( 'loading' );
			$button.addClass( 'added' );


			if ( ! wc_add_to_cart_params.is_cart && $button.parent().find( '.added_to_cart' ).length === 0 ) {
				var afterAddText = wc_add_to_cart_params.i18n_view_cart;
				var afterAddUrl = wc_add_to_cart_params.cart_url;
				if ( EAA2C.afterAddText ) {
					afterAddText = EAA2C.afterAddText;
				}
				if ( EAA2C.afterAddUrl ) {
					afterAddUrl = EAA2C.afterAddUrl;
				}
				// View cart text.
				$button.after( ' <a href="' + afterAddUrl + '" class="added_to_cart wc-forward" title="' +
					afterAddText + '">' + afterAddText + '</a>' );
			}

			// $( document.body ).trigger( 'wc_cart_button_updated', [ $button ] );
		}
	};

	/**
	 * Update cart page elements after add to cart events.
	 */
	AddToCartHandler.prototype.updateCartPage = function() {
		var page = window.location.toString().replace( 'add-to-cart', 'added-to-cart' );

		$( '.shop_table.cart' ).load( page + ' .shop_table.cart:eq(0) > *', function() {
			$( '.shop_table.cart' ).stop( true ).css( 'opacity', '1' ).unblock();
			$( document.body ).trigger( 'cart_page_refreshed' );
		});

		$( '.cart_totals' ).load( page + ' .cart_totals:eq(0) > *', function() {
			$( '.cart_totals' ).stop( true ).css( 'opacity', '1' ).unblock();
			$( document.body ).trigger( 'cart_totals_refreshed' );
		});
	};

	/**
	 * Update fragments after add to cart events.
	 */
	AddToCartHandler.prototype.updateFragments = function( e, fragments ) {
		if ( fragments && ! EAA2C.stopRefreshFrags ) {
			$.each( fragments, function( key ) {
				$( key )
					.addClass( 'updating' )
					.fadeTo( '400', '0.6' )
					.block({
						message: null,
						overlayCSS: {
							opacity: 0.6
						}
					});
			});

			$.each( fragments, function( key, value ) {
				$( key ).replaceWith( value );
				$( key ).stop( true ).css( 'opacity', '1' ).unblock();
			});

			$( document.body ).trigger( 'wc_fragments_loaded' );
		}
	};

	AddToCartHandler.prototype.showNotices = function( element, target ) {
		$( '.woocommerce-error, .woocommerce-message' ).remove();
		var domTarget = $( '.content-area' );
		// console.log( target );
		// console.log( "showing notices" );
		domTarget.before( target );
		this.scrollToNotices();
		// this.unblockButtons();
	}

	AddToCartHandler.prototype.scrollToNotices = function( e ) {
		var scrollElement = $( '.woocommerce-error, .woocommerce-message' );
		var isSmoothScrollSupported = 'scrollBehavior' in document.documentElement.style;

		if ( ! scrollElement.length ) {
		}

		if ( scrollElement.length ) {
			if ( isSmoothScrollSupported ) {
				scrollElement[ 0 ].scrollIntoView({
					behavior: 'smooth'
				});
			}
			else {
				$( 'html, body' ).animate( {
					scrollTop: ( scrollElement.offset().top - 100 ),
				}, 1000);
			}
		}
	}

	AddToCartHandler.prototype.showValidation = function( e, element, target ) {
		$( '.eaa2c-error, .eaa2c-message' ).remove();
		var domTarget = $( element ).parent().find('>:first-child');

		domTarget.before( target );
	}

	/**
	 * Init AddToCartHandler.
	 */
	new AddToCartHandler();
});
