//Hide / show
jQuery( document ).on( 'updated_checkout', function( e, data ) {
	//console.log( 'CPPW - Hide pickup points' );
	jQuery( function( $ ) {
		$( '#cppw' ).hide();
		$( '#cppw_point_active' ).val( '0' );
		//Country - we only do this for Portugal
		if ( $( '#ship-to-different-address' ).find( 'input' ).is( ':checked' ) ) {
			var country = $( '#shipping_country' ).val();
		} else {
			var country = $( '#billing_country' ).val();
		}
		if ( country === undefined ) {
			//console.log( 'CPPW - Country fields probably removed from checkout, assume store country '+cppw.shop_country );
			country = cppw.shop_country;
		}
		//console.log( 'CPPW - '+country );
		if ( country == 'PT' ) {
			//checkout.js : 271
			var shipping_methods = {};
			$( 'select.shipping_method, input[name^="shipping_method"][type="radio"]:checked, input[name^="shipping_method"][type="hidden"]' ).each( function() {
				shipping_methods[ $( this ).data( 'index' ) ] = $( this ).val();
			} );
			//console.log( 'CPPW - DDP activated shipping methods:' );
			//console.log( cppw.shipping_methods );
			//console.log( 'CPPW - Chosen shipping methods:' );
			//console.log( shipping_methods );
			//Only one shipping method chosen?
			if ( Object.keys( shipping_methods ).length == 1 ) {
				//console.log( 'CPPW - 1 chosen shipping method' );
				var shipping_method = $.trim( shipping_methods[0] );
				if ( $.inArray( shipping_method, cppw.shipping_methods ) >= 0 ) {
					//console.log( 'CPPW - Show pickup points' );
					$( '#cppw' ).show();
					$( '#cppw_point_active' ).val( '1' );
					if ( $().select2 ) {
						$( '#cppw_point' ).select2();
					}
				}
			}
		}
	} );
	
} );
//Update chosen point
jQuery( document ).on( 'change', '#cppw_point', function() {
	jQuery( function( $ ) {
		$( '.cppw-points-fragment-point-details' ).addClass( 'updating' );
		var data = {
			cppw_point: $( '#cppw_point' ).val()
		};
		$.ajax( {
			type:		'POST',
			url:		wc_checkout_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'cppw_point_details' ),
			data:		data,
			success:	function( data ) {
				//Fragments
				if ( data && data.fragments ) {
					$.each( data.fragments, function ( key, value ) {
						$( key ).replaceWith( value );
						$( key ).unblock();
					} );
					$( document.body ).trigger( 'cppw_point_changed' );
				}
			}
		} );
	} );
} );