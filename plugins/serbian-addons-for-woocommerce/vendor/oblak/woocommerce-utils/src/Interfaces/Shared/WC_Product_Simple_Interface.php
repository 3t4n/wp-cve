<?php
/**
 * WC_Product_Simple_Interface interface file.
 *
 * @package WooCommerce Utils
 */

/**
 * Simple product class.
 */
interface WC_Product_Simple_Interface extends WC_Product_Interface {
    /**
	 * Initialize simple product.
	 *
	 * @param WC_Product|int $product Product instance or ID.
	 */
	public function __construct( $product = 0 );

	/**
	 * Get internal type.
	 *
	 * @return string
	 */
	public function get_type();

	/**
	 * Get the add to url used mainly in loops.
	 *
	 * @return string
	 */
	public function add_to_cart_url();

	/**
	 * Get the add to cart button text.
	 *
	 * @return string
	 */
	public function add_to_cart_text();

	/**
	 * Get the add to cart button text description - used in aria tags.
	 *
	 * @since 3.3.0
	 * @return string
	 */
	public function add_to_cart_description();
}
