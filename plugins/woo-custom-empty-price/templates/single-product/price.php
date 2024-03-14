<?php
/**
 * Single Product Price
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<!-- Custom Empty Price START: https://wordpress.org/plugins/woo-custom-empty-price/ -->
<?php echo html_entity_decode( esc_html( Woo_Custom_Empty_Price::render_custom_empty_price() ) ); ?>
<!-- Custom Empty Price END: https://wordpress.org/plugins/woo-custom-empty-price/ -->