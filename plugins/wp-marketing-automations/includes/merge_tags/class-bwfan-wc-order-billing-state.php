<?php

/**
 * Class BWFAN_WC_Order_Billing_State
 *
 * Merge tag outputs order billing state
 *
 * Since 2.0.6
 */
class BWFAN_WC_Order_Billing_State extends BWFAN_Merge_Tag {

	private static $instance = null;

	public function __construct() {
		$this->tag_name        = 'order_billing_state';
		$this->tag_description = __( 'Order Billing State', 'wp-marketing-automations' );
		add_shortcode( 'bwfan_order_billing_state', array( $this, 'parse_shortcode' ) );
	}

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Parse the merge tag and return its value.
	 *
	 * @param $attr
	 *
	 * @return mixed|string|void
	 */
	public function parse_shortcode( $attr ) {
		if ( true === BWFAN_Merge_Tag_Loader::get_data( 'is_preview' ) ) {
			return $this->parse_shortcode_output( $this->get_dummy_preview(), $attr );
		}

		$order_id = BWFAN_Merge_Tag_Loader::get_data( 'order_id' );
		$order    = wc_get_order( $order_id );
		if ( ! $order instanceof WC_Order ) {
			return $this->parse_shortcode_output( '', $attr );
		}

		$state = BWFAN_Woocommerce_Compatibility::get_order_billing_state( $order );
		if ( isset( $attr['format'] ) && 'state_code' === $attr['format'] ) {
			return $this->parse_shortcode_output( $state, $attr );
		}

		$country = BWFAN_Woocommerce_Compatibility::get_billing_country_from_order( $order );
		if ( ! empty( $country ) && ! empty( $state ) ) {
			$states = WC()->countries->get_states( $country );
			$state  = ( is_array( $states ) && isset( $states[ $state ] ) ) ? $states[ $state ] : $state;
		}

		return $this->parse_shortcode_output( $state, $attr );
	}

	/**
	 * Show dummy value of the current merge tag.
	 *
	 * @return string
	 */
	public function get_dummy_preview() {
		return 'NE';
	}

	public function get_setting_schema() {
		$options = [
			[
				'value' => 'state_name',
				'label' => __( 'State Name', 'wp-marketing-automations' ),
			],
			[
				'value' => 'state_code',
				'label' => __( 'State Code', 'wp-marketing-automations' ),
			],
		];

		return [
			[
				'id'          => 'format',
				'type'        => 'select',
				'options'     => $options,
				'label'       => __( 'Select Format', 'wp-marketing-automations' ),
				"class"       => 'bwfan-input-wrapper',
				"placeholder" => 'Select',
				"required"    => true,
				"description" => ""
			],
		];
	}
}

/**
 * Register this merge tag to a group.
 */
if ( bwfan_is_woocommerce_active() ) {
	BWFAN_Merge_Tag_Loader::register( 'wc_order', 'BWFAN_WC_Order_Billing_State', null, 'Order' );
}
