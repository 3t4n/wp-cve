'use strict';

(function( $ ) {

	const updateAmount = () => {
		const formEl = document.querySelector( '#acadp-checkout-form' );		
		const paymentGatewaysEl = formEl.querySelector( '#acadp-checkout-payment-gateways' );
		const cardDetailsEl = formEl.querySelector( '#acadp-checkout-card-details' );
		const totalAmountEl = formEl.querySelector( '#acadp-checkout-total-amount' );
		const submitBtnEl = formEl.querySelector( '.acadp-button-submit' );	
		
		let totalAmount = 0;
		let numFeeFields = 0;

		formEl.querySelectorAll( '.acadp-form-control-amount' ).forEach(( el ) => {
			if ( el.checked ) totalAmount += parseFloat( el.dataset.price );
			++numFeeFields;
		});

		if ( numFeeFields === 0 ) {
			totalAmountEl.innerHTML = '0.00';	

			paymentGatewaysEl.hidden = true;
			cardDetailsEl.hidden = true;
			submitBtnEl.hidden = true;

			return false;
		}

		totalAmountEl.innerHTML = '<div class="acadp-spinner"></div>';
	
		let data = {
			'action': 'acadp_checkout_format_total_amount',
			'amount': totalAmount,
			'security': acadp.ajax_nonce
		}
		
		$.post( acadp.ajax_url, data, function( response ) {												   
			totalAmountEl.innerHTML = response;

			let amount = parseFloat( response );	
			if ( amount > 0 ) {
				paymentGatewaysEl.hidden = false;
				cardDetailsEl.hidden = false;

				submitBtnEl.value = acadp.i18n.button_label_proceed_to_payment;
				submitBtnEl.hidden = false;
			} else {
				paymentGatewaysEl.hidden = true;
				cardDetailsEl.hidden = true;
				
				submitBtnEl.value = acadp.i18n.button_label_finish_submission;
				submitBtnEl.hidden = false;
			}
		});	
	}
	
	/**
	 * Called when the page has loaded.
	 */
	$(function() {	

		const formEl = document.querySelector( '#acadp-checkout-form' );
		let formSubmitted = false;

		if ( formEl !== null ) {
			// Update total amount.
			formEl.querySelectorAll( '.acadp-form-control-amount' ).forEach(( el ) => {
				el.addEventListener( 'change', ( event ) => {	
					updateAmount();
				});
			});

			updateAmount();
			
			// Form Validation.
			formEl.addEventListener( 'submit', ( event ) => {					
				if ( formSubmitted ) {
					return false;
				}

				formSubmitted = true;
			});	
		}	
		
	});

})( jQuery );
