<?php
/**
 * Class RTWWDPDL_Module_Base to calculate discount according to Simple Modules.
 *
 * @since    1.0.0
 */
abstract class RTWWDPDL_Simple_Base extends RTWWDPDL_Module_Base {

	/**
	 * variable to check available rules.
	 *
	 * @since    1.0.0
	 */
	public $available_rulesets = array();

	/**
	 * construct function.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $module_id ) {
		parent::__construct( $module_id, 'simple' );

		add_action( 'init', array(&$this, 'initialize_rules'), 0 );
	}

	/**
	 * function to initialize rules.
	 *
	 * @since    1.0.0
	 */
	public abstract function initialize_rules();

	/**
	 * function to check if the product is same on which discount rule is made.
	 *
	 * @since    1.0.0
	 */
	public abstract function is_applied_to_product( $rtwwdpdl_product );

	/**
	 * function to get product discounted amount.
	 *
	 * @since    1.0.0
	 */
	public abstract function get_discounted_price_for_shop( $rtwwdpdl_product, $rtwwdpdl_working_price );

	/**
	 * Function to check if a product is already discounted by the same rule.
	 *
	 * @since    1.0.0
	 */
	protected function rtwwdpdl_is_cumulative( $cart_item, $cart_item_key, $default = false ) {
		global $woocommerce;
		$rtwwdpdl_cumulative = null;
		if ( isset( WC()->cart->cart_contents[$cart_item_key]['discounts'] ) ) {
			if ( in_array( $this->module_id, WC()->cart->cart_contents[$cart_item_key]['discounts']['by'] ) ) {
				
				return false;
			} elseif ( count( array_intersect( array('simple_category', 'simple_membership', 'simple_group'), WC()->cart->cart_contents[$cart_item_key]['discounts']['by'] ) ) > 0 ) {
				$rtwwdpdl_cumulative = true;
			}
		} else {
			$rtwwdpdl_cumulative = $default;
		}

		return apply_filters( 'rtwwdpdl_dynamic_pricing_is_cumulative', $rtwwdpdl_cumulative, $this->module_id, $cart_item, $cart_item_key );
	}

	/**
	 * Function to get product price.
	 *
	 * @since    1.0.0
	 */
	public function get_product_working_price( $rtwwdpdl_working_price, $rtwwdpdl_product ) {
		return apply_filters( 'rtwwdpdl_dynamic_pricing_get_product_price_to_discount', $rtwwdpdl_working_price, $rtwwdpdl_product );
	}

}