<?php

class BWFAN_WC_Cart_Abandoned_Date extends BWFAN_Merge_Tag {

	private static $instance = null;

	public function __construct() {
		$this->tag_name        = 'cart_abandoned_date';
		$this->tag_description = __( 'Cart Abandoned Date', 'wp-marketing-automations' );
		add_shortcode( 'bwfan_cart_abandoned_date', array( $this, 'parse_shortcode' ) );

		$this->support_fallback = false;
		$this->support_date     = true;
		$this->priority         = 4;
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
		$parameters           = [];
		$parameters['format'] = isset( $attr['format'] ) ? $attr['format'] : get_option( 'date_format' );
		if ( isset( $attr['modify'] ) ) {
			$parameters['modify'] = $attr['modify'];
		}
		if ( true === BWFAN_Merge_Tag_Loader::get_data( 'is_preview' ) ) {
			return $this->parse_shortcode_output( $this->get_dummy_preview( $parameters ), $attr );
		}

		$abandoned_row_details = BWFAN_Merge_Tag_Loader::get_data( 'cart_details' );

		if ( empty( $abandoned_row_details ) ) {
			$abandoned_id          = BWFAN_Merge_Tag_Loader::get_data( 'cart_abandoned_id' );
			$abandoned_row_details = BWFAN_Model_Abandonedcarts::get( $abandoned_id );
		}

		if ( empty( $abandoned_row_details ) ) {
			return $this->parse_shortcode_output( '', $attr );
		}

		$date = $this->format_datetime( $abandoned_row_details['last_modified'], $parameters );

		return $this->parse_shortcode_output( $date, $attr );
	}

	/**
	 * Show dummy value of the current merge tag.
	 *
	 * @param $parameters
	 *
	 * @return string
	 */
	public function get_dummy_preview( $parameters ) {
		return $this->format_datetime( '2018-12-07 13:25:39', $parameters );
	}

	/**
	 * Return mergetag schema
	 *
	 * @return array[]
	 */
	public function get_setting_schema() {
		$formats      = $this->date_formats;
		$date_formats = [];
		foreach ( $formats as $data ) {
			if ( isset( $data['format'] ) ) {
				$date_time      = date( $data['format'] );
				$date_formats[] = [
					'value' => $data['format'],
					'label' => $date_time,
				];
			}
		}

		return [
			[
				'id'          => 'format',
				'type'        => 'select',
				'options'     => $date_formats,
				'label'       => __( 'Select Date Format', 'wp-marketing-automations' ),
				"class"       => 'bwfan-input-wrapper',
				"placeholder" => 'Select',
				"required"    => false,
				"description" => ""
			],
			[
				'id'          => 'modify',
				'label'       => __( 'Modify (Optional)', 'wp-marketing-automations' ),
				'type'        => 'text',
				'class'       => '',
				'placeholder' => 'e.g. +2 months, -1 day, +6 hours',
				'required'    => false,
				'toggler'     => array(),
			],
		];
	}
}

/**
 * Register this merge tag to a group.
 */
if ( bwfan_is_woocommerce_active() ) {
	BWFAN_Merge_Tag_Loader::register( 'wc_ab_cart', 'BWFAN_WC_Cart_Abandoned_Date', null, 'Abandoned Cart' );
}
