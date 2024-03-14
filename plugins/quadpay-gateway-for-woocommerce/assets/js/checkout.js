// Zip MFPP reload total on checkout
(function () {
	var previousPaymentMethod;
	jQuery( document.body ).on( 'payment_method_selected', function() {
		var selectedPaymentMethod = jQuery( '.woocommerce-checkout input[name="payment_method"]:checked' ).attr( 'id' );

		if(selectedPaymentMethod === 'payment_method_quadpay' || previousPaymentMethod === 'payment_method_quadpay') {
			jQuery( 'form.checkout' ).trigger('update');
		}

		previousPaymentMethod = selectedPaymentMethod;
	});
})();
