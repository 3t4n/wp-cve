<?php
/**
 * Bulk printing notices
 *
 * @package dropp-for-woocommerce
 * @var array $consignment_ids
 * @var array $order_ids
 * @var array $dropp_order_ids
 */

namespace Dropp;

if ( ! empty( $consignment_ids ) ) {
	$url = admin_url( 'admin-ajax.php?action=dropp_pdf_merge&consignment_ids=' . implode( ',', $consignment_ids ) );
	$message = __( 'Click here to download PDF labels for orders:' );
	echo '<div class="updated"><p><a target="_blank" href="' . esc_attr( $url ) . '">' . esc_html( $message . ' ' . implode( ', ', $dropp_order_ids ) ) . '</a></p></div>';
} else {
	$message = __( 'Could not find any labels for the selected orders to print.', 'dropp-for-woocommerce' );
	echo '<div class="error"><p>' . esc_html( $message ) . '</p></div>';
}
