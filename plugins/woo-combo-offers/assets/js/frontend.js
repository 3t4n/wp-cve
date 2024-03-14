'use strict';

jQuery( document ).on( 'ready', function() {
	jQuery( '.product-type-wooco' ).each( function() {
		wooco_init( jQuery( this ) );
	} );
} );

jQuery( document ).on( 'found_variation', function( e, t ) {
	var $wooco_wrap = jQuery( e['target'] ).closest( '.product-type-wooco' );
	var $wooco_products = jQuery( e['target'] ).closest( '.wooco-products' );
	var $wooco_product = jQuery( e['target'] ).closest( '.wooco-product' );

	if ( $wooco_product.length ) {
		if ( t['image']['url'] && t['image']['srcset'] ) {
			// change image
			$wooco_product.find( '.wooco-thumb-ori' ).hide();
			$wooco_product.find( '.wooco-thumb-new' ).html( '<img src="' + t['image']['url'] + '" srcset="' + t['image']['srcset'] + '"/>' ).show();
		}
		if ( t['price_html'] ) {
			// change price
			$wooco_product.find( '.wooco-price-ori' ).hide();
			$wooco_product.find( '.wooco-price-new' ).html( t['price_html'] ).show();
		}
		if ( t['is_purchasable'] ) {
			// change stock notice
			if ( t['is_in_stock'] ) {
				$wooco_products.next( 'p.stock' ).show();
				$wooco_product.attr( 'data-id', t['variation_id'] );
				$wooco_product.attr( 'data-price', t['display_price'] );
			} else {
				$wooco_products.next( 'p.stock' ).hide();
				$wooco_product.attr( 'data-id', 0 );
				$wooco_product.attr( 'data-price', 0 );
			}

			// change availability text
			jQuery( e['target'] ).closest( '.variations_form' ).find( 'p.stock' ).remove();
			if ( t['availability_html'] != '' ) {
				jQuery( e['target'] ).closest( '.variations_form' ).append( t['availability_html'] );
			}
		}
		if ( t['variation_description'] != '' ) {
			$wooco_product.find( '.wooco-variation-description' ).html( t['variation_description'] ).show();
		} else {
			$wooco_product.find( '.wooco-variation-description' ).html( '' ).hide();
		}

		if ( wooco_vars.change_image == 'no' ) {
			// prevent changing the main image
			jQuery( e['target'] ).closest( '.variations_form' ).trigger( 'reset_image' );
		}

		wooco_init( $wooco_wrap );
	}
} );

jQuery( document ).on( 'reset_data', function( e ) {
	var $wooco_wrap = jQuery( e['target'] ).closest( '.product-type-wooco' );
	var $wooco_product = jQuery( e['target'] ).closest( '.wooco-product' );

	if ( $wooco_product.length ) {
		// reset thumb
		$wooco_product.find( '.wooco-thumb-new' ).hide();
		$wooco_product.find( '.wooco-thumb-ori' ).show();

		// reset price
		$wooco_product.find( '.wooco-price-new' ).hide();
		$wooco_product.find( '.wooco-price-ori' ).show();

		// reset stock
		jQuery( e['target'] ).closest( '.variations_form' ).find( 'p.stock' ).remove();

		// reset desc
		$wooco_product.find( '.wooco-variation-description' ).html( '' ).hide();

		// reset id
		$wooco_product.attr( 'data-id', 0 );
		$wooco_product.attr( 'data-price', 0 );

		wooco_init( $wooco_wrap );
	}
} );

jQuery( document ).on( 'click touch', '.single_add_to_cart_button', function( e ) {
	var $this = jQuery( this );
	var $wooco_products = $this.closest( 'wooco-products' );

	if ( $this.hasClass( 'wooco-disabled' ) ) {
		if ( $this.hasClass( 'wooco-selection' ) ) {
			alert( wooco_vars.alert_selection );
		} else if ( $this.hasClass( 'wooco-empty' ) ) {
			alert( wooco_vars.alert_empty );
		} else if ( $this.hasClass( 'wooco-min' ) ) {
			alert( wooco_vars.alert_min.replace( '[min]', $wooco_products.attr( 'data-min' ) ) );
		} else if ( $this.hasClass( 'wooco-max' ) ) {
			alert( wooco_vars.alert_max.replace( '[max]', $wooco_products.attr( 'data-max' ) ) );
		}
		e.preventDefault();
	}
} );

jQuery( document ).on( 'keyup change', '.wooco-qty input', function() {
	var $this = jQuery( this );
	var $wooco_wrap = $this.closest( '.product-type-wooco' );
	var qty = parseInt( $this.val() );
	var min_qty = parseInt( $this.attr( 'min' ) );
	var max_qty = parseInt( $this.attr( 'max' ) );

	if ( !isNaN( min_qty ) && (
		qty < min_qty
	) ) {
		qty = min_qty;
	}

	if ( !isNaN( max_qty ) && (
		qty > max_qty
	) ) {
		qty = max_qty;
	}

	$this.val( qty );
	$this.closest( '.wooco-product' ).attr( 'data-qty', qty );

	wooco_init( $wooco_wrap );
} );

jQuery( document ).on( 'woosq_loaded', function() {
	// product bundles in quick view popup
	wooco_init( jQuery( '#woosq-popup .product-type-wooco' ) );
} );

function wooco_init( $wooco_wrap ) {
	var total = 0;
	var is_selection = false;
	var is_empty = true;
	var is_min = false;
	var is_max = false;

	var $wooco_products = $wooco_wrap.find( '.wooco-products' );
	var $wooco_btn = $wooco_wrap.find( '.single_add_to_cart_button' );

	$wooco_products.find( '.wooco-product' ).each( function() {
		var $this = jQuery( this );
		if ( (
			     $this.attr( 'data-qty' ) > 0
		     ) && (
			     $this.attr( 'data-id' ) == 0
		     ) ) {
			is_selection = true;
		}
		if ( $this.attr( 'data-qty' ) > 0 ) {
			is_empty = false;
			total += parseInt( $this.attr( 'data-qty' ) );
		}
	} );

	// check min
	if ( (
		     $wooco_products.attr( 'data-optional' ) == 'yes'
	     ) && $wooco_products.attr( 'data-min' ) && (
		     total < parseInt( $wooco_products.attr( 'data-min' ) )
	     ) ) {
		is_min = true;
	}

	// check max
	if ( (
		     $wooco_products.attr( 'data-optional' ) == 'yes'
	     ) && $wooco_products.attr( 'data-max' ) && (
		     total > parseInt( $wooco_products.attr( 'data-max' ) )
	     ) ) {
		is_max = true;
	}

	if ( is_selection || is_empty || is_min || is_max ) {
		$wooco_btn.addClass( 'wooco-disabled' );
		if ( is_selection ) {
			$wooco_btn.addClass( 'wooco-selection' );
		} else {
			$wooco_btn.removeClass( 'wooco-selection' );
		}
		if ( is_empty ) {
			$wooco_btn.addClass( 'wooco-empty' );
		} else {
			$wooco_btn.removeClass( 'wooco-empty' );
		}
		if ( is_min ) {
			$wooco_btn.addClass( 'wooco-min' );
		} else {
			$wooco_btn.removeClass( 'wooco-min' );
		}
		if ( is_max ) {
			$wooco_btn.addClass( 'wooco-max' );
		} else {
			$wooco_btn.removeClass( 'wooco-max' );
		}
	} else {
		$wooco_btn.removeClass( 'wooco-disabled wooco-selection wooco-empty wooco-min wooco-max' );
	}

	wooco_calc_price( $wooco_wrap );
	wooco_save_ids( $wooco_wrap );
}

function wooco_calc_price( $wooco_wrap ) {
	var total = 0;
	var total_html = '';

	var $wooco_products = $wooco_wrap.find( '.wooco-products' );
	var $wooco_total = $wooco_wrap.find( '.wooco-total' );

	$wooco_products.find( '.wooco-product' ).each( function() {
		var $this = jQuery( this );
		if ( $this.attr( 'data-price' ) > 0 ) {
			total += $this.attr( 'data-price' ) * $this.attr( 'data-qty' );
		}
	} );
	if ( (
		     $wooco_products.attr( 'data-discount' ) > 0
	     ) && (
		     $wooco_products.attr( 'data-discount' ) < 100
	     ) ) {
		total = total * (
			100 - $wooco_products.attr( 'data-discount' )
		) / 100;
	}
	var total_formatted = wooco_format_money( total, wooco_vars.price_decimals, '', wooco_vars.price_thousand_separator, wooco_vars.price_decimal_separator );
	switch ( wooco_vars.price_format ) {
		case '%1$s%2$s':
			//left
			total_html += wooco_vars.currency_symbol + '' + total_formatted;
			break;
		case '%1$s %2$s':
			//left with space
			total_html += wooco_vars.currency_symbol + ' ' + total_formatted;
			break;
		case '%2$s%1$s':
			//right
			total_html += total_formatted + '' + wooco_vars.currency_symbol;
			break;
		case '%2$s %1$s':
			//right with space
			total_html += total_formatted + ' ' + wooco_vars.currency_symbol;
			break;
		default:
			//default
			total_html += wooco_vars.currency_symbol + '' + total_formatted;
	}
	if ( (
		     parseFloat( $wooco_products.attr( 'data-discount' ) ) > 0
	     ) && (
		     parseFloat( $wooco_products.attr( 'data-discount' ) ) < 100
	     ) ) {
		var saved = wooco_round( parseFloat( $wooco_products.attr( 'data-discount' ) ) );
		total_html += ' (' + wooco_vars.price_saved + ' ' + saved + '%)';
	}
	$wooco_total.html( wooco_vars.price_text + ' ' + total_html ).slideDown();
	jQuery( document ).trigger( 'wooco_calc_price', [total, total_formatted, total_html] );
}

function wooco_save_ids( $wooco_wrap ) {
	var wooco_ids = Array();
	var $wooco_products = $wooco_wrap.find( '.wooco-products' );
	var $wooco_ids = $wooco_wrap.find( '.wooco-ids' );

	$wooco_products.find( '.wooco-product' ).each( function() {
		var $this = jQuery( this );
		if ( (
			     $this.attr( 'data-id' ) > 0
		     ) && (
			     $this.attr( 'data-qty' ) > 0
		     ) ) {
			wooco_ids.push( $this.attr( 'data-id' ) + '/' + $this.attr( 'data-qty' ) );
		}
	} );

	$wooco_ids.val( wooco_ids.join( ',' ) );
}

function wooco_round( num ) {
	return + (
		Math.round( num + "e+2" ) + "e-2"
	);
}

function wooco_format_money( number, places, symbol, thousand, decimal ) {
	number = number || 0;
	places = !isNaN( places = Math.abs( places ) ) ? places : 2;
	symbol = symbol !== undefined ? symbol : "$";
	thousand = thousand || ",";
	decimal = decimal || ".";
	var negative = number < 0 ? "-" : "",
		i = parseInt( number = Math.abs( + number || 0 ).toFixed( places ), 10 ) + "",
		j = 0;
	if ( i.length > 3 ) {
		j = i.length % 3;
	}
	return symbol + negative + (
		j ? i.substr( 0, j ) + thousand : ""
	) + i.substr( j ).replace( /(\d{3})(?=\d)/g, "$1" + thousand ) + (
		       places ? decimal + Math.abs( number - i ).toFixed( places ).slice( 2 ) : ""
	       );
}