jQuery( document ).ready( ( $ ) => {

	'use strict';

	const paymentMethods = typeof poyntPaymentMethods !== 'undefined' ? poyntPaymentMethods : null;

	if( paymentMethods && Array.isArray( paymentMethods ) ){

		for ( let method of paymentMethods ) {
			if ( ! method.allowEnable ) {
				$('tr[data-gateway_id="'+method.gatewayId+'"] .wc-payment-gateway-method-toggle-enabled').css('pointer-events','none').css('opacity', '0.2');
			}
		}
	}

} );
