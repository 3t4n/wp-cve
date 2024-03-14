/**
 * Front-end JS for cart 
 *
 * @package Fish and Ships Pro
 * @since 1.1.12
 * @version 1.2.5
 */

jQuery(document).ready(function($) {
	
	
	var notices_added = false;
	
	if ( jQuery('.wc_fns_cart_control').length != 0 ) {
		notices_added = jQuery('.wc_fns_cart_control').last().attr('data-some_notice_from_action') == 1;
	}

	jQuery(document).on( 'updated_cart_totals', function () {
		
		// console.log('updated_wc_div');
		
		var some_notice_from_action = false;

		if ( jQuery('.wc_fns_cart_control').length != 0 ) {
			some_notice_from_action = jQuery('.wc_fns_cart_control').last().attr('data-some_notice_from_action') == 1;
		}
		
		//console.log( 'some_notice_from_action: ' + ( some_notice_from_action ? '1' : '0' ) );
		//console.log( 'notices_added: ' + ( notices_added ? '1' : '0' ) );
		
		
		if ( notices_added || some_notice_from_action ) {
			
			update_messages( 'cart' );
			//jQuery(document).trigger('wc_update_cart');
			notices_added = some_notice_from_action;
			
			//console.log( "update_messages()" );
		}
		
	});

	jQuery(document).on('updated_checkout', function ( data ) {
		
		var some_notice_from_action = false;

		if ( jQuery('.wc_fns_cart_control').length != 0 ) {
			some_notice_from_action = jQuery('.wc_fns_cart_control').last().attr('data-some_notice_from_action') == 1;
		}
		
		//console.log( 'some_notice_from_action: ' + ( some_notice_from_action ? '1' : '0' ) );
		//console.log( 'notices_added: ' + ( notices_added ? '1' : '0' ) );
		
		if ( notices_added || some_notice_from_action ) {
			
			update_messages( 'checkout' );
			//jQuery(document).trigger('wc_update_cart');
			notices_added = some_notice_from_action;
			
			//console.log( "update_messages()" );

		}
	});

		
	function get_notices_wrapper( obj ) {

		$target = $(obj).find( '.woocommerce-notices-wrapper' ); 
		if ($target.length != 0) return $target;

		$target = $(obj).find( '.cart-empty' ).closest( '.woocommerce' ); 
		if ($target.length != 0) return $target;
		
		$target = $(obj).find( '.woocommerce-cart-form' ); 
		if ($target.length != 0) return $target;

		return $target;
	}
	
	// Get main wrapper for checkout page
	function get_main_wrapper( obj ) {
		
		$target = $(obj).find( 'main' ); 
		if ($target.length != 0) return $target;

		$target = $(obj).find( '.woocommerce-checkout' ).closest( '.woocommerce' );
		if ($target.length != 0) return $target;

		$target = $(obj).find( '#primary' );
		if ($target.length != 0) return $target;

		$target = $(obj).find( '.entry-content' );
		if ($target.length != 0) return $target;

		$target = $(obj).find( '.woocommerce:last' );
		if ($target.length != 0) return $target;

		$target = $(obj).find( 'div:first' ); 
		return $target;
	}
	
	function scroll_to_messages() {
		
		scrollElement = get_notices_wrapper ( document );
		
		$( 'html, body' ).animate( {
			scrollTop: ( scrollElement.first().offset().top - 100 )
		}, 1000 );
	}
	
	function update_messages ( page_type ) {

		var previous_errors   = jQuery('.wc_fns_type_error').length;
		var previous_success  = jQuery('.wc_fns_type_success').length;
		var	previous_notices  = jQuery('.wc_fns_type_notice').length;
		
		$.ajax({
			url: window.location.href,
			error: function (xhr, status, error) {
				var errorMessage = xhr.status + ': ' + xhr.statusText
				alert('Error reloading page for AJAX message updating - ' + errorMessage);
			},
			success: function (data) {
				
				//console.log('AJAX checkout reload success:');
				var parsed = $('<div/>').append(data);
				
				var pointer = get_notices_wrapper( document );
				
				if ( page_type == 'checkout' ) {

					// some error message?
					some_error_from_action = false;
					if ( jQuery('.wc_fns_cart_control').length != 0 ) {
						some_error_from_action = $('.wc_fns_cart_control').last().attr('data-some_error_from_action') == 1;
					}
					//console.log('error detection: ' + ( some_error_from_action ? '1' : '0' ) );

					// on error, update the primary content to lock checkout
					if ( some_error_from_action ) {

						var wrapper = get_main_wrapper( document );
						var parsed_wrapper = get_main_wrapper ( parsed );
						
						//console.log('wrapper HTML replace');
						//console.log( $(parsed_wrapper).html() );
						$ ( wrapper ).html ( $(parsed_wrapper).html() );

						scroll_to_messages();
						
						return; // the new messages come in the body HTML
					}
				}
				
				// remove current messages ( we don't know the theme messages HTML code )
				$( pointer ).children().each( function( i, message ) {
					if ( $( ".wc_fns_message", message ).length != 0 ) {
						$(message).remove();
					}
				});

				// add new messages  (if there are)
				//console.log('messages:');
				var parsed_pointer = get_notices_wrapper( parsed );
				/*
				console.log( parsed_pointer );
				console.log( $(parsed_pointer).html() );
				console.log( $(parsed).html() );
				*/
				$( parsed_pointer ).children().each( function( i, message ) {
					//console.log('looking for message: ' + jQuery(message).html() );
					if ( $( ".wc_fns_message", message ).length != 0 ) {
				
						$( pointer ).first().append( message );
					}
				});

				if ( previous_errors  < jQuery('.wc_fns_type_error').length  ||
					 previous_success < jQuery('.wc_fns_type_success').length ||
					 previous_notices < jQuery('.wc_fns_type_notice').length ) {
					
					scroll_to_messages();
				}

			},
			
			dataType: 'html'
		});
		
	};
	
	
	/* WC Events 
	jQuery(document).on('wc_cart_emptied', function () {
		console.log('wc_cart_emptied');
	});
	jQuery(document).on('update_checkout', function () {
		console.log('update_checkout');
	});
	jQuery(document).on('updated_wc_div', function () {
		console.log('updated_wc_div');
	});
	jQuery(document).on('updated_cart_totals', function () {
		console.log('updated_cart_totals');
	});
	jQuery(document).on('updated_shipping_method', function () {
		console.log('updated_shipping_method');
	});
	jQuery(document).on('applied_coupon', function () {
		console.log('applied_coupon');
	});
	jQuery(document).on('removed_coupon', function () {
		console.log('removed_coupon');
	});
	jQuery(document).on('wc_fragments_loaded', function () {
		console.log('wc_fragments_loaded');
	});
	jQuery(document).on('wc_fragment_refresh', function () {
		console.log('wc_fragment_refresh');
	});
	jQuery(document).on('wc_fragments_refreshed', function () {
		console.log('wc_fragments_refreshed');
	});
	*/
});
