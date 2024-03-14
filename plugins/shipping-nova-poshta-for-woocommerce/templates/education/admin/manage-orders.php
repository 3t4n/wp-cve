<?php
/**
 * Manage orders education page.
 *
 * @package NovaPosta\Templates\Education\Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}
?>

<div class="shipping-nova-poshta-education-manage-orders">
	<div class="shipping-nova-poshta-education-manage-orders-background"></div>
	<div class="shipping-nova-poshta-education-popup">
		<div class="shipping-nova-poshta-education-popup-title">
			<?php esc_html_e( 'View and manage all your orders related to the Nova Poshta Delivery', 'shipping-nova-poshta-for-woocommerce' ); ?>
		</div>
		<div class="shipping-nova-poshta-education-popup-content">
			<?php esc_html_e( 'You can see all active orders and bulk create invoices for delivery.', 'shipping-nova-poshta-for-woocommerce' ); ?>
		</div>
		<a
			href="https://wp-unit.com/product/nova-poshta-pro/"
			target="_blank"
			class="button button-primary shipping-nova-poshta-education-button">
			<?php esc_html_e( 'Upgrade Now', 'shipping-nova-poshta-for-woocommerce' ); ?>
		</a>
	</div>
</div>
