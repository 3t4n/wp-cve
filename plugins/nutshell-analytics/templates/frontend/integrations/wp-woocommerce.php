<?php
// IMPORTANT: This plugin is dynamically updated - MODIFICATIONS WILL BE OVERWRITTEN

/**************************************************
 * Name: WooCommerce
 * Description: Automatically track new orders as lead submissions
 *************************************************/

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals
// - this file is included in a function, and no globals are being set here
// phpcs:disable WordPress.PHP.NoSilencedErrors
// - we're going to silence some errors just to avoid issues in case Woo function names change

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Anonymous function just so we can stop early if needed
( function () {

	// Make sure Woo is enabled
	if ( ! class_exists( 'WC_Order' ) || ! is_callable( 'is_order_received_page' ) ) {
		return;
	}

	// Only want this JS on the order received / thank-you page
	if ( ! is_order_received_page() ) {
		return;
	}

	// Make sure we have a good order with items
	global $wp;
	if ( ! is_object( $wp ) || empty( $wp->query_vars ) ) {
		return;
	}
	$order_id = isset( $wp->query_vars['order-received'] ) ? (int) $wp->query_vars['order-received'] : null;
	if ( empty( $order_id ) ) {
		return;
	}
	$order       = new WC_Order( $order_id );
	$order_items = @$order->get_items();
	if ( empty( $order_items ) ) {
		return;
	}

	// Prepare product string for tracking
	$products = [];
	foreach ( $order_items as $item ) {
		$product = @$item->get_product();
		if ( is_object( $product ) ) {
			$products[] = @$product->get_name() .
				' (ID: ' . @$product->get_id() .
				' | SKU: ' . @$product->get_sku() .
				' | QTY: ' . @$item->get_quantity() . ')';
		}
	}
	$product_string = implode( ', ', $products );

	?>

<!-- Nutshell Integration: WooCommerce -->
<script type="text/javascript" data-registered="nutshell-plugin" >
	if (
		/* global mcfx */
		'undefined' !== typeof mcfx
		&& 'undefined' !== typeof window.mcfxCaptureCustomFormData
	) {
		document.addEventListener( 'DOMContentLoaded', function() {
			const fieldData = [
				{
					name: 'name',
					value: "<?php echo esc_attr( @$order->get_billing_first_name() . ' ' . @$order->get_billing_last_name() ); ?>"
				},
				{
					name: 'email',
					value: '<?php echo esc_attr( @$order->get_billing_email() ); ?>'
				},
				{
					name: 'phone',
					value: '<?php echo esc_attr( @$order->get_billing_phone() ); ?>'
				},
				{
					name: 'address',
					value: "<?php echo esc_attr( @$order->get_billing_address_1() . ' ' . @$order->get_billing_address_2() ); ?>"
				},
				{
					name: 'order_number',
					value: '<?php echo esc_attr( @$order->get_order_number() ); ?>'
				},
				{
					name: 'order_total',
					value: '<?php echo esc_attr( @$order->get_total() ); ?>'
				},
				{
					name: 'products',
					value: '<?php echo esc_attr( @$product_string ); ?>'
				}
			];

			window.mcfxCaptureCustomFormData(fieldData, 'woocommerce-order-received' );
		});
	}
</script>
	<?php
} )(); // execute the anonymous function

// IMPORTANT: This plugin is dynamically updated - MODIFICATIONS WILL BE OVERWRITTEN
