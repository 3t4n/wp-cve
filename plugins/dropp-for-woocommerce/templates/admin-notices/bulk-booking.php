<?php
/**
 *
 *
 * @package woocommerce-dropp-booking
 * @var array $existing
 * @var array $success
 * @var array $not_dropp
 * @var array $failed
 * @var array $consignment_ids
 * @var array $order_ids
 * @var array $dropp_order_ids
 */

if ( ! empty( $existing ) ) {
	$message = __( 'These orders were booked previously: ' );
	echo '<div class="notice notice-info"><p>' . esc_html( $message . implode( ', ', $existing ) ) . '</p></div>';
}
if ( ! empty( $success ) ) {
	$message = __( 'Booked these orders: ' );
	echo '<div class="updated"><p>' . esc_html( $message . implode( ', ', $success ) ) . '</p></div>';

	if ( ! empty( $consignment_ids ) ) {
		$url = admin_url( 'admin-ajax.php?action=dropp_pdf_merge&consignment_ids=' . implode( ',', $consignment_ids ) );
		$message = __( 'Click here to download PDF labels for orders:' );
		echo '<div class="updated"><p><a target="_blank" href="' . esc_attr( $url ) . '">' . esc_html( $message . ' ' . implode( ', ', $dropp_order_ids ) ) . '</a></p></div>';
	}
}
if ( ! empty( $not_dropp ) ) {
	$message = __( 'No dropp shipping method found on these orders: ' );
	echo '<div class="notice notice-info"><p>' . esc_html( $message . implode( ', ', $not_dropp ) ) . '</p></div>';
}
if ( ! empty( $failed ) ) {
	$message = __( 'Failed to book these orders: ' );
	echo '<div class="error"><p>' . esc_html( $message . implode( ', ', $failed ) ) . '</p></div>';
}

