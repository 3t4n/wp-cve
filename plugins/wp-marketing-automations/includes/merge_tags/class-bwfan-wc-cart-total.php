<?php

class BWFAN_WC_Cart_Total extends BWFAN_Merge_Tag {

	private static $instance = null;


	public function __construct() {
		$this->tag_name        = 'cart_total';
		$this->tag_description = __( 'Cart Items Total', 'wp-marketing-automations' );
		add_shortcode( 'bwfan_cart_total', array( $this, 'parse_shortcode' ) );
		$this->support_fallback = false;
		$this->priority         = 5.1;
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
			return $this->get_dummy_preview( $attr );
		}

		$cart_details = BWFAN_Merge_Tag_Loader::get_data( 'cart_details' );

		if ( empty( $cart_details ) ) {
			$abandoned_id = BWFAN_Merge_Tag_Loader::get_data( 'cart_abandoned_id' );
			$cart_details = BWFAN_Model_Abandonedcarts::get( $abandoned_id );
		}

		if ( empty( $cart_details ) ) {
			return $this->parse_shortcode_output( '', $attr );
		}

		$formatting  = BWFAN_Common::get_formatting_for_wc_price( $attr, '' );
		$items_total = BWFAN_Common::get_formatted_price_wc( $cart_details['total'], $formatting['raw'], $formatting['currency'] );

		return $this->parse_shortcode_output( $items_total, $attr );
	}

	/**
	 * Show dummy value of the current merge tag.
	 *
	 * @return string
	 */
	public function get_dummy_preview( $attr ) {
		$formatting = BWFAN_Common::get_formatting_for_wc_price( $attr, '' );

		return BWFAN_Common::get_formatted_price_wc( 255, $formatting['raw'], $formatting['currency'] );
	}


	/**
	 * Return mergetag schema
	 *
	 * @return array[]
	 */
	public function get_setting_schema() {
		$options = [
			[
				'value' => 'raw',
				'label' => 'Raw',
			],
			[
				'value' => 'formatted',
				'label' => 'Formatted',
			],
			[
				'value' => 'formatted-currency',
				'label' => 'Formatted with currency',
			],
		];

		return [
			[
				'id'          => 'format',
				'type'        => 'select',
				'options'     => $options,
				'label'       => __( 'Display', 'wp-marketing-automations' ),
				"class"       => 'bwfan-input-wrapper',
				"placeholder" => 'Raw',
				"required"    => false,
				"description" => ""
			],
		];
	}
}

/**
 * Register this merge tag to a group.
 */
if ( bwfan_is_woocommerce_active() ) {
	BWFAN_Merge_Tag_Loader::register( 'wc_ab_cart', 'BWFAN_WC_Cart_Total', null, 'Abandoned Cart' );
}
