<?php
/**
 * Skrill Payments Form
 *
 * The file is for displaying the Skrill payment form
 * Copyright (c) Skrill
 *
 * @package Skrill/Templates
 * @located at  /template/admin/order/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<p>
	<strong><?php echo esc_html( __( 'Payment Information', 'wc-skrill' ) ); ?></strong>
	<br />
	<?php
	echo esc_html( $payment_method_title ) . '<br />';
	echo esc_html( __( 'Payment status', 'wc-skrill' ) ) . ' : ' . esc_html( $payment_status ) . '<br />';
	echo esc_html( __( 'Used payment method', 'wc-skrill' ) ) . ' : ' . esc_html( $payment_type ) . '<br />';
	if ( isset( $additional_information['order_origin'] ) ) {
		echo esc_html( __( 'Order originated from', 'wc-skrill' ) ) . ' : ' . esc_html( $additional_information['order_origin'] ) . '<br />';
	}
	if ( isset( $additional_information['order_country'] ) ) {
		echo esc_html( __( 'Country (of the card issuer)', 'wc-skrill' ) ) . ' : ' . esc_html( $additional_information['order_country'] ) . '<br />';
	}
	echo esc_html( __( 'Order was placed using', 'wc-skrill' ) ) . ' : ' . esc_html( $transaction['currency'] ) . '<br />';
	echo '<br />';
	echo esc_html( __( 'MB Transaction ID', 'wc-skrill' ) ) . ' : ' . esc_html( $transaction['mb_transaction_id'] ) . '<br />';
	echo esc_html( __( 'Transaction ID', 'wc-skrill' ) ) . ' : ' . esc_html( $transaction['transaction_id'] ) . '<br />';

	$payment_response = maybe_unserialize( $transaction['payment_response'] );

	if ( isset( $payment_response['rec_payment_id'] ) ) {
		echo esc_html( __( 'Recurring Payment Id', 'wc-skrill' ) ) . ' : ' . esc_html( $payment_response['rec_payment_id'] ) . '<br />';
	}


	if ( isset( $additional_information['skrill_account'] ) ) {
		echo esc_html( __( 'Email address of skrill account', 'wc-skrill' ) ) . ' : ' . esc_html( $additional_information['skrill_account'] ) . '<br />';
	}
	?>
</p>
