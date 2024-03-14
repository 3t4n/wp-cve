<?php

session_start();

$last_order_id = (int) $_SESSION['tb_last_order_id'];

if ( $last_order_id != 0 ) {
	$order = BookingHelper::get_order_by_id( $last_order_id );
	// print_r($order);
	// if order total is zero, no need to process payment gateway
	if ( $order->cprice > 0 ) {
		$paymentObj = BookingHelper::get_payment_details( $order->payment );

		if ( ! empty( $paymentObj ) ) {
			if ( ! empty( $paymentObj->payment_element ) && file_exists( TBLIGHT_PLUGIN_PATH . 'classes/payment_plugins/' . $paymentObj->payment_element . '.php' ) ) {
				require_once TBLIGHT_PLUGIN_PATH . 'classes/tbpayment.helper.php';
				require_once TBLIGHT_PLUGIN_PATH . 'classes/payment_plugins/' . $paymentObj->payment_element . '.php';

				$pluginTitle     = 'plgTblightPayment' . ucfirst( $paymentObj->payment_element );
				$tbPaymentPlugin = new $pluginTitle();
				$html            = $tbPaymentPlugin->plgTbProcessPayment( $order );
			}
		}
	} else {
		$html  = 'You will receive a confirmation e-mail confirming this booking.';
		$html .= '<br><br>Thank you for booking with us!';
		$html .= '&nbsp;<a href="' . $booking_form_url . '">';
		$html .= 'Go back to booking form';
		$html .= '</a>';
	}

	$_SESSION['tb_last_order_id'] = 0;
	?>
<style type="text/css">
#booking {
	background: #fff;
	padding: 50px 10px 10px 10px;
	border-radius: 4px;
	color: #000 !important;
	font-family: Muli,sans-serif;
	letter-spacing: normal;
}
@media screen and (max-width: 767px) {
	#booking br {
		display: none;
	}
}
</style>
<div id="booking" class="booking">
	<div class="contenth">
		<div id="prev1">
			<div id="prev2" align="center" style="font-size: 18px; margin-bottom: 50px;">
		   <?php echo html_entity_decode( esc_html( $html ) ); ?>
			</div>
		</div>
	</div>
</div>

<?php } ?>
