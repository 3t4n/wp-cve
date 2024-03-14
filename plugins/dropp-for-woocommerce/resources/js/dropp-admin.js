
import Booking from './components/booking/booking';

import Vue from 'vue';

if ( window._dropp && window['dropp-booking']) {
	var settings = new Vue( {
		el: '#dropp-booking',
		render:  function( createElement ) {
			return createElement( Booking );
		},
		data: {
		},
		computed: {
		},
		components: {
			// productitem: ProductItem,
		},
	} );

	jQuery( function( $ ) {
		if ( _dropp.locations && ! _dropp.locations.length ) {
			$( '#woocommerce-order-dropp-booking' ).addClass( 'closed' );
		}
	} );
}

require( './scripts/free-shipping-threshold.js' );
require( './scripts/load-prices-from-api.js' );
