<?php
/**
 * order Refund amount.
 *
 */

class BWFAN_WC_Order_Refund_Amount extends BWFAN_Merge_Tag {

	private static $instance = null;

	public function __construct() {
		$this->tag_name        = 'order_refund_amount';
		$this->tag_description = __( 'Order Refund Amount', 'wp-marketing-automations' );
		add_shortcode( 'bwfan_order_refund_amount', array( $this, 'parse_shortcode' ) );
		$this->support_fallback = false;
		$this->support_v2       = true;
		$this->support_v1       = false;
	}

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function parse_shortcode( $attr ) {
		if ( true === BWFAN_Merge_Tag_Loader::get_data( 'is_preview' ) ) {
			return $this->get_dummy_preview( $attr );
		}

		$refund_id = BWFAN_Merge_Tag_Loader::get_data( 'refund_id' );
		if ( empty( $refund_id ) || ! class_exists( 'WC_Order_Refund' ) ) {
			$this->parse_shortcode_output( '', $attr );
		}
		$refund     = new WC_Order_Refund( $refund_id );
		$formatting = BWFAN_Common::get_formatting_for_wc_price( $attr, '' );
		$amount     = BWFAN_Common::get_formatted_price_wc( $refund->get_amount(), $formatting['raw'], $formatting['currency'] );

		return $this->parse_shortcode_output( $amount, $attr );

	}

	/**
	 * Show dummy value of the current merge tag.
	 *
	 * @return string
	 */
	public function get_dummy_preview( $attr ) {
		$formatting = BWFAN_Common::get_formatting_for_wc_price( $attr, '' );

		return BWFAN_Common::get_formatted_price_wc( 10, $formatting['raw'], $formatting['currency'] );
	}

	/**
	 * Return merge tag schema
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
	BWFAN_Merge_Tag_Loader::register( 'wc_order_refund', 'BWFAN_WC_Order_Refund_Amount', null, 'Order' );
}
