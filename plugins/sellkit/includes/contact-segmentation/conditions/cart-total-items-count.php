<?php

namespace Sellkit\Contact_Segmentation\Conditions;

use Sellkit\Contact_Segmentation\Conditions\Condition_Base;

defined( 'ABSPATH' ) || die();

/**
 * Class Cart_Total_Items_Count
 *
 * @package Sellkit\Contact_Segmentation\Conditions
 * @since 1.5.7
 */
class Cart_Total_Items_Count extends Condition_Base {

	/**
	 * Condition name.
	 *
	 * @since 1.5.7
	 */
	public function get_name() {
		return 'cart-total-items-count';
	}

	/**
	 * Condition title.
	 *
	 * @since 1.5.7
	 */
	public function get_title() {
		return esc_html__( 'Cart Total Items Count', 'sellkit' );
	}

	/**
	 * Condition type.
	 *
	 * @since 1.5.7
	 */
	public function get_type() {
		return self::SELLKIT_NUMBER_CONDITION_VALUE;
	}

	/**
	 * Gets value.
	 *
	 * @since 1.5.7
	 */
	public function get_value() {
		if ( ! sellkit()->has_valid_dependencies() ) {
			return 0;
		}

		return ! empty( WC()->cart->get_cart_contents_count() ) ? WC()->cart->get_cart_contents_count() : 0;
	}

	/**
	 * It is pro feature or not.
	 *
	 * @since 1.5.7
	 */
	public function is_pro() {
		return true;
	}
}
