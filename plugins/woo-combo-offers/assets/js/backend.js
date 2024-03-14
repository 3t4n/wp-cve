'use strict';

jQuery( document ).ready( function( $ ) {
	var wooco_timeout = null;

	wooco_active_settings();

	$( '#product-type' ).on( 'change', function() {
		wooco_active_settings();
	} );

	// hide search result box by default
	$( '#wooco_results' ).hide();
	$( '#wooco_loading' ).hide();

	// total price
	if ( $( '#product-type' ).val() == 'wooco' ) {
		wooco_change_regular_price();
	}

	// set regular price
	$( '#wooco_set_regular_price' ).on( 'click', function() {
		if ( $( '#wooco_disable_auto_price' ).is( ':checked' ) ) {
			$( 'li.general_tab a' ).trigger( 'click' );
			$( '#_regular_price' ).focus();
		} else {
			alert( 'You must disable auto calculate price first!' );
		}
	} );

	// set optional
	$( '#wooco_optional_products' ).on( 'click', function() {
		if ( $( this ).is( ':checked' ) ) {
			$( '.wooco_tr_show_if_optional_products' ).show();
		} else {
			$( '.wooco_tr_show_if_optional_products' ).hide();
		}
	} );

	// checkbox
	$( '#wooco_disable_auto_price' ).on( 'change', function() {
		if ( $( this ).is( ':checked' ) ) {
			$( '#_regular_price' ).prop( 'readonly', false );
			$( '#_sale_price' ).prop( 'readonly', false );
			$( '.wooco_tr_show_if_auto_price' ).hide();
		} else {
			$( '#_regular_price' ).prop( 'readonly', true );
			$( '#_sale_price' ).prop( 'readonly', true );
			$( '.wooco_tr_show_if_auto_price' ).show();
		}
		if ( $( '#product-type' ).val() == 'wooco' ) {
			wooco_change_regular_price();
		}
	} );

	// search input
	$( '#wooco_keyword' ).keyup( function() {
		if ( $( '#wooco_keyword' ).val() != '' ) {
			$( '#wooco_loading' ).show();
			if ( wooco_timeout != null ) {
				clearTimeout( wooco_timeout );
			}
			wooco_timeout = setTimeout( wooco_ajax_get_data, 300 );
			return false;
		}
	} );

	// actions on search result items
	$( '#wooco_results' ).on( 'click', 'li', function() {
		$( this ).children( 'span.remove' ).attr( 'aria-label', 'Remove' ).html( '×' );
		$( '#wooco_selected ul' ).append( $( this ) );
		$( '#wooco_results' ).hide();
		$( '#wooco_keyword' ).val( '' );
		wooco_get_ids();
		wooco_change_regular_price();
		wooco_arrange();
		return false;
	} );

	// change qty of each item
	$( '#wooco_selected' ).on( 'keyup change', '.qty input', function() {
		wooco_get_ids();
		wooco_change_regular_price();
		return false;
	} );

	// actions on selected items
	$( '#wooco_selected' ).on( 'click', 'span.remove', function() {
		$( this ).parent().remove();
		wooco_get_ids();
		wooco_change_regular_price();
		return false;
	} );

	// hide search result box if click outside
	$( document ).on( 'click', function( e ) {
		if ( $( e.target ).closest( $( '#wooco_results' ) ).length == 0 ) {
			$( '#wooco_results' ).hide();
		}
	} );

	// arrange
	wooco_arrange();

	$( document ).on( 'wooco_drag_event', function() {
		wooco_get_ids();
	} );

	// hide updated
	setTimeout( function() {
		$( '.wooco_updated_price' ).slideUp();
	}, 3000 );

	// ajax update price
	$( '.wooco-update-price-btn' ).on( 'click', function( e ) {
		var this_btn = $( this );
		if ( !this_btn.hasClass( 'disabled' ) ) {
			this_btn.addClass( 'disabled' );
			var count = 0;
			(
				function wooco_update_price() {
					var data = {
						action: 'wooco_update_price',
						wooco_nonce: wooco_vars.wooco_nonce
					};
					setTimeout( function() {
						jQuery.post( ajaxurl, data, function( response ) {
							var response_num = Number( response );
							if ( response_num != 0 ) {
								count += response_num;
								wooco_update_price();
								$( '.wooco_updated_price_ajax' ).html( 'Updating... ' + count );
							} else {
								$( '.wooco_updated_price_ajax' ).html( 'Finished! ' + count + ' updated.' );
								this_btn.removeClass( 'disabled' );
							}
						} );
					}, 1000 );
				}
			)();
		}
		e.preventDefault();
	} );

	// metabox
	$( '#wooco_meta_box_update_price' ).on( 'click', function( e ) {
		var btn = $( this );
		if ( !btn.hasClass( 'disabled' ) ) {
			var btn_text = btn.val();
			var product_id = btn.attr( 'data-id' );
			btn.val( btn_text + '...' ).addClass( 'disabled' );
			$( '#wooco_meta_box_update_price_result' ).html( '' ).prepend( '<li>Start!</li>' );
			var count = 0;
			(
				function wooco_metabox_update_price() {
					var data = {
						action: 'wooco_metabox_update_price',
						product_id: product_id,
						count: count,
						wooco_nonce: wooco_vars.wooco_nonce
					};
					setTimeout( function() {
						jQuery.post( ajaxurl, data, function( response ) {
							if ( response != 0 ) {
								$( '#wooco_meta_box_update_price_result' ).prepend( response );
								count ++;
								wooco_metabox_update_price();
							} else {
								$( '#wooco_meta_box_update_price_result' ).prepend( '<li>Finished!</li>' );
								btn.val( btn_text ).removeClass( 'disabled' );
							}
						} );
					}, 100 );
				}
			)();
		}
	} );

	function wooco_arrange() {
		$( '#wooco_selected li' ).arrangeable( {
			dragEndEvent: 'wooco_drag_event',
			dragSelector: '.move'
		} );
	}

	function wooco_get_ids() {
		var listId = new Array();
		$( '#wooco_selected li' ).each( function() {
			listId.push( $( this ).data( 'id' ) + '/' + $( this ).find( 'input' ).val() );
		} );
		if ( listId.length > 0 ) {
			$( '#wooco_ids' ).val( listId.join( ',' ) );
		} else {
			$( '#wooco_ids' ).val( '' );
		}
	}

	function wooco_active_settings() {
		if ( $( '#product-type' ).val() == 'wooco' ) {
			$( 'li.general_tab' ).addClass( 'show_if_wooco' );
			$( '#general_product_data .pricing' ).addClass( 'show_if_wooco' );
			$( '._tax_status_field' ).closest( '.options_group' ).addClass( 'show_if_wooco' );
			$( '#_downloadable' ).closest( 'label' ).addClass( 'show_if_wooco' ).removeClass( 'show_if_simple' );
			$( '#_virtual' ).closest( 'label' ).addClass( 'show_if_wooco' ).removeClass( 'show_if_simple' );

			$( '.show_if_external' ).hide();
			$( '.show_if_simple' ).show();
			$( '.show_if_wooco' ).show();

			$( '.product_data_tabs li' ).removeClass( 'active' );
			$( '.product_data_tabs li.wooco_tab' ).addClass( 'active' );

			$( '.panel-wrap .panel' ).hide();
			$( '#wooco_settings' ).show();

			if ( $( '#wooco_optional_products' ).is( ':checked' ) ) {
				$( '.wooco_tr_show_if_optional_products' ).show();
			} else {
				$( '.wooco_tr_show_if_optional_products' ).hide();
			}

			if ( $( '#wooco_disable_auto_price' ).is( ':checked' ) ) {
				$( '.wooco_tr_show_if_auto_price' ).hide();
			} else {
				$( '.wooco_tr_show_if_auto_price' ).show();
			}

			wooco_change_regular_price();
		} else {
			$( 'li.general_tab' ).removeClass( 'show_if_wooco' );
			$( '#general_product_data .pricing' ).removeClass( 'show_if_wooco' );
			$( '._tax_status_field' ).closest( '.options_group' ).removeClass( 'show_if_wooco' );
			$( '#_downloadable' ).closest( 'label' ).removeClass( 'show_if_wooco' ).addClass( 'show_if_simple' );
			$( '#_virtual' ).closest( 'label' ).removeClass( 'show_if_wooco' ).addClass( 'show_if_simple' );

			$( '#_regular_price' ).prop( 'readonly', false );
			$( '#_sale_price' ).prop( 'readonly', false );

			if ( $( '#product-type' ).val() != 'grouped' ) {
				$( '.general_tab' ).show();
			}

			if ( $( '#product-type' ).val() == 'simple' ) {
				$( '#_downloadable' ).closest( 'label' ).show();
				$( '#_virtual' ).closest( 'label' ).show();
			}
		}
	}

	function wooco_change_regular_price() {
		var total = 0;
		var total_max = 0;
		$( '#wooco_selected li' ).each( function() {
			total += $( this ).data( 'price' ) * $( this ).find( 'input' ).val();
			total_max += $( this ).data( 'price-max' ) * $( this ).find( 'input' ).val();
		} );
		total = accounting.formatMoney( total, '', wooco_vars.price_decimals, wooco_vars.price_thousand_separator, wooco_vars.price_decimal_separator );
		total_max = accounting.formatMoney( total_max, '', wooco_vars.price_decimals, wooco_vars.price_thousand_separator, wooco_vars.price_decimal_separator );
		if ( total == total_max ) {
			$( '#wooco_regular_price' ).html( total );
		} else {
			$( '#wooco_regular_price' ).html( total + ' - ' + total_max );
		}
		if ( !$( '#wooco_disable_auto_price' ).is( ':checked' ) ) {
			$( '#_regular_price' ).prop( 'readonly', true ).val( total ).trigger( 'change' );
			$( '#_sale_price' ).prop( 'readonly', true );
		}
	}

	function wooco_ajax_get_data() {
		// ajax search product
		wooco_timeout = null;
		var data = {
			action: 'wooco_get_search_results',
			keyword: $( '#wooco_keyword' ).val(),
			ids: $( '#wooco_ids' ).val()
		};
		jQuery.post( ajaxurl, data, function( response ) {
			$( '#wooco_results' ).show();
			$( '#wooco_results' ).html( response );
			$( '#wooco_loading' ).hide();
		} );
	}
} );