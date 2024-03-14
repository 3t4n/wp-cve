<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access
}
if ( 'edit' === $action ) :
?>
	<script type="text/javascript" >
		jQuery( document ).ready( function( $ ) {

			$( "#_payment_method" ).parent().hide();

		} );
	</script>

<?php
else :
?>

	<div style="clear:both"></div>
	<div class="edit_address">

		<p class="form-field _transaction_id_field" id="sibs_payment">
			<label for="payment_recurring"><?php echo esc_attr( __( 'Payment Recurring:', 'wc-sibs' ) ); ?></label>
			<select name="_payment_recurring" id="_payment_recurring" class="first"></select>

		</p>
	</div>
	<div style="clear:both"></div>

	<script type="text/javascript" >

	jQuery( document ).ready( function( $ ) {
		getSibsPaymentMethod( $ );

		$( "#_payment_method" ).click( function() {
		getSibsPaymentMethod( $ );
		} );

		$( "#_payment_method" ).change( function() {
			getSibsPaymentMethod( $ );
		} );

		$( "#_payment_method" ).keyup( function() {
			getSibsPaymentMethod( $ );
		} );
	} );

	function getSibsPaymentMethod( $ ) {

		$( "#sibs_payment" ).hide();
		var payment_id = $( "#_payment_method" ).val();
		var user_id = $( '#customer_user' ).val();

		var data = {
			'action': 'my_action',
			'user_id': user_id,
			'payment_id': payment_id,
		};

		jQuery.post( ajaxurl, data, function( response ) {

			var registered_payments = $.parseJSON( response );

			if ( registered_payments.length === 0 ) {
				$( '#_payment_recurring' ).html( $( '<option>', {
						value: 0,
						text : 'N/A'
				} ) );
			} else {
				if ( payment_id === 'sibs_ccsaved' ) {
					$.each( registered_payments, function ( i, payment ) {
						if ( i === 0 ) {
							$( '#_payment_recurring' ).html( $( '<option>', {
								value: payment.reg_id,
								text : 'Cards - '+ payment.brand + ' ( ending in: ' + payment.last4digits + ' ; expires on: ' + payment.expiry_year + ' )'
							} ) );
						}else{
							$( '#_payment_recurring' ).append( $( '<option>', {
								value: payment.reg_id,
								text : 'Cards - '+ payment.brand + ' ( ending in: ' + payment.last4digits + ' ; expires on: ' + payment.expiry_year + ' )'
							} ) );
						}
					} );
				} else if ( payment_id === 'sibs_ddsaved' ) {
					$.each( registered_payments, function ( i, payment ) {
						if ( i === 0 ) {
							$( '#_payment_recurring' ).html( $( '<option>', {
									value: payment.reg_id,
									text : 'Direct Debit ( Account:  * * * * '+ payment.last4digits + ' )'
							} ) );
						}else{
							$( '#_payment_recurring' ).append( $( '<option>', {
									value: payment.reg_id,
									text : 'Direct Debit ( Account:  * * * * '+ payment.last4digits + ' )'
							} ) );
						}
					} );
				} else if ( payment_id === 'sibs_paypalsaved' ) {
					$.each( registered_payments, function ( i, payment ) {
						if ( i === 0 ) {
							$( '#_payment_recurring' ).html( $( '<option>', {
									value: payment.reg_id,
									text : 'PayPal ( '+ payment.email +' )'
							} ) );
						}else{
							$( '#_payment_recurring' ).append( $( '<option>', {
									value: payment.reg_id,
									text : 'PayPal ( '+ payment.email +' )'
							} ) );
						}
					} );
				}
			}
			if ( payment_id === 'sibs_ccsaved'
				|| payment_id === 'sibs_ddsaved'
				|| payment_id === 'sibs_paypalsaved'
			) {
				$( "#sibs_payment" ).show();
			}
		} );
	}
	</script>

<?php
endif;
