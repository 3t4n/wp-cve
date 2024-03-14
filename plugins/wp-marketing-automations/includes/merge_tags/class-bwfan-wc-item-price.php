<?php

class BWFAN_WC_Item_Price extends BWFAN_Cart_Display {

	private static $instance = null;


	public function __construct() {
		$this->tag_name        = 'item_price';
		$this->tag_description = __( 'Purchased Item Price', 'wp-marketing-automations' );
		add_shortcode( 'bwfan_item_price', array( $this, 'parse_shortcode' ) );
	}

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Show the html in popup for the merge tag.
	 */
	public function get_view() {
		$this->get_back_button();

		if ( $this->support_fallback ) {
			$this->get_fallback();
		}

		$this->get_preview();
		$this->get_copy_button();
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

		$this->item_type = 'price';
		$result          = $this->get_item_details();

		if ( ! empty( $result ) ) {
			$item_id = BWFAN_Merge_Tag_Loader::get_data( 'wc_single_item_id' );
			/** If item id is empty */
			if ( empty( $item_id ) ) {
				return $this->parse_shortcode_output( $result, $attr );
			}
			$item = new WC_Order_Item_Product( $item_id );
			if ( empty( $item->get_id() ) ) {
				return $this->parse_shortcode_output( $result, $attr );
			}

			$formatting = BWFAN_Common::get_formatting_for_wc_price( $attr, $item->get_order() );
			$result     = BWFAN_Common::get_formatted_price_wc( $result, $formatting['raw'], $formatting['currency'] );
		}

		return $this->parse_shortcode_output( $result, $attr );
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
	BWFAN_Merge_Tag_Loader::register( 'wc_items', 'BWFAN_WC_Item_Price', null, 'Order Item' );
}