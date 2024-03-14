<?php
/**
 * Restricted Product
 *
 * @author   Codection
 * @category Root
 * @package  Products Restricted Users from WooCommerce
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPRU_Restricted_Product {
	/**
	 * Bool if is enable or not.
	 *
	 * @var enable
	 */
	private $enable;

	/**
	 * String with the mode of restriction.
	 *
	 * @var mode
	 */
	private $mode;

	/**
	 * The list of users associated with the product.
	 *
	 * @var users
	 */
	private $users;

	/**
	 * Constructor
	 *
	 * @param int $product_id The product_id.
	 **/
	public function __construct( $product_id = '' ) {
		if ( empty( $product_id ) ) {
			$product_id = get_the_ID();
		}

		$this->enable = get_post_meta( $product_id, 'wpru_enable', true );
		$this->mode = get_post_meta( $product_id, 'wpru_mode', true );
		$this->users = get_post_meta( $product_id, 'wpru_users', true );

		if ( ! is_array( $this->users ) ) {
			$this->users = array();
		}
	}

	/**
	 * Returns if is enable
	 **/
	public function get_enable() {
		return $this->enable;
	}

	/**
	 * Returns mode
	 **/
	public function get_mode() {
		return $this->mode;
	}

	/**
	 * Returns users
	 **/
	public function get_users() {
		return $this->users;
	}

	/**
	 * Returns if is visible
	 **/
	public function is_visible() {
		if ( ! $this->get_enable() ) {
			return true;
		}

		return ( $this->get_mode() == 'buy' );
	}

	/**
	 * Returns if is visible
	 **/
	public function is_purchasable() {
		if ( ! $this->get_enable() ) {
			return true;
		}

		if ( $this->get_mode() === 'restrict' ) {
			return false;
		}

		return in_array( get_current_user_id(), $this->get_users() );
	}
}
