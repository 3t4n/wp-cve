<?php

namespace QuadLayers\QuadMenu\Integrations;

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * WooCommerce ex QuadMenu_WooCommerce Class
 */
class WooCommerce {

	private static $instance;

	public function __construct() {
		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'add_to_cart_qty' ) );
		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'add_to_cart_total' ) );
	}

	function add_to_cart_qty( $fragments ) {
		ob_start();
		?>
	<span class="quadmenu-cart-qty"><?php echo esc_attr( WC()->cart->get_cart_contents_count() ); ?></span>
		<?php
		$fragments['span.quadmenu-cart-qty'] = ob_get_clean();

		return $fragments;
	}

	function add_to_cart_total( $fragments ) {
		ob_start();
		?>
	<span class="quadmenu-cart-total"><?php echo WC()->cart->get_cart_total(); ?></span>
	<?php
		$fragments['span.quadmenu-cart-total'] = ob_get_clean();

		return $fragments;
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

}
