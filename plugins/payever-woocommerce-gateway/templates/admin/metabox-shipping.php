<?php
/** @var string $tracking_number */
/** @var string $tracking_url */
/** @var string $shipping_provider */
/** @var string $shipping_date */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
?>
<div class="payever-shipping-tracking">
	<p class="payever-tracking-content">
		<strong><?php esc_html_e( $shipping_provider ); ?></strong>
		<?php if ( ! empty( $tracking_url ) ) : ?>
			- <?php echo sprintf( '<a href="%s" target="_blank" title="' . esc_attr( __( 'Click here to track your shipment', 'payever-woocommerce-gateway' ) ) . '">' . __( 'Track', 'payever-woocommerce-gateway' ) . '</a>', esc_url( $tracking_url ) ); ?>
		<?php endif; ?>
		<br/>
		<em><?php esc_html_e( $tracking_number ); ?></em>
	</p>
	<p class="payever-meta">
		<?php /* translators: 1: shipping date */ esc_html_e( sprintf( __( 'Shipped on %s', 'payever-woocommerce-gateway' ), date_i18n( wc_date_format(), $shipping_date ) ) ); ?>
	</p>
</div>
