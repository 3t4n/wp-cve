<?php

class BWFAN_WC_Item_Data extends BWFAN_Cart_Display {

	private static $instance = null;


	public function __construct() {
		$this->tag_name        = 'item_data';
		$this->tag_description = __( 'Purchased Item Data', 'wp-marketing-automations' );
		add_shortcode( 'bwfan_item_data', array( $this, 'parse_shortcode' ) );
		$this->is_delay_variable = true;
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
		$this->data_key();
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
			return $this->get_dummy_preview();
		}

		$this->item_type = 'item_data';
		$value           = $this->get_item_details( $attr );
		/** if value is date then checking format in attribute */
		$value = $this->get_date_value( $value, $attr );

		return $this->parse_shortcode_output( $value, $attr );
	}

	/**
	 * Show dummy value of the current merge tag.
	 *
	 * @return string
	 */
	public function get_dummy_preview() {
		return __( 'Value of the key', 'wp-marketing-automations' );
	}

	/**
	 * Get formatted date value
	 *
	 * @param $value
	 * @param $attr
	 *
	 * @return false|string
	 */
	public function get_date_value( $value, $attr ) {
		if ( ! is_array( $attr ) || ! isset( $attr['type'] ) || 'date' !== $attr['type'] || ! isset( $attr['format'] ) || empty( $attr['format'] ) ) {
			return $value;
		}

		return $this->format_datetime( $value, $attr );
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
				'id'          => 'key',
				'label'       => __( 'Meta Key', 'wp-marketing-automations' ),
				'type'        => 'text',
				'class'       => '',
				'placeholder' => '',
				'hint'        => __( 'Input the correct meta key in order to get the data', 'wp-marketing-automations' ),
				'required'    => true,
				'toggler'     => array(),
			],
			[
				'id'          => 'type',
				'type'        => 'select',
				'options'     => [
					[
						'value' => '',
						'label' => 'Text',
					],
					[
						'value' => 'date',
						'label' => 'Date',
					]
				],
				'label'       => __( 'Meta Field Type', 'wp-marketing-automations' ),
				"class"       => 'bwfan-input-wrapper',
				"required"    => false,
				'placeholder' => 'Select',
			],
			[
				'id'          => 'input_format',
				'type'        => 'select',
				'options'     => $date_formats,
				'label'       => __( 'Date Saved Format', 'wp-marketing-automations' ),
				"class"       => 'bwfan-input-wrapper',
				"placeholder" => 'Select',
				"required"    => false,
				'hint'        => __( 'Select the date format in which date value is saved on the meta key', 'wp-marketing-automations' ),
				'toggler'     => array(
					'fields'   => array(
						array(
							'id'    => 'type',
							'value' => 'date',
						),
					),
					'relation' => 'AND',
				)
			],
			[
				'id'          => 'format',
				'type'        => 'select',
				'options'     => $date_formats,
				'label'       => __( 'Date Output Format', 'wp-marketing-automations' ),
				"class"       => 'bwfan-input-wrapper',
				"placeholder" => 'Select',
				"required"    => false,
				'hint'        => __( 'Desired date output format', 'wp-marketing-automations' ),
				'toggler'     => array(
					'fields'   => array(
						array(
							'id'    => 'type',
							'value' => 'date',
						),
					),
					'relation' => 'AND',
				)
			]
		];
	}

	/**
	 * Return merge tag delay step schema
	 *
	 * @return array[]
	 */
	public function get_delay_setting_schema() {
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
				'id'          => 'key',
				'label'       => __( 'Meta Key', 'wp-marketing-automations' ),
				'type'        => 'text',
				'class'       => '',
				'placeholder' => '',
				'hint'        => __( 'Input the correct meta key in order to get the data', 'wp-marketing-automations' ),
				'required'    => true,
				'toggler'     => array(),
			],
			[
				'id'          => 'format',
				'type'        => 'select',
				'options'     => $date_formats,
				'label'       => __( 'Date Format', 'wp-marketing-automations' ),
				"class"       => 'bwfan-input-wrapper',
				"placeholder" => 'Select',
				'hint'        => __( 'Select the date format in which date value is saved on the meta key', 'wp-marketing-automations' ),
				"required"    => false,
				"description" => "",
				'toggler'     => array()
			],
		];
	}
}

/**
 * Register this merge tag to a group.
 */
if ( bwfan_is_woocommerce_active() ) {
	BWFAN_Merge_Tag_Loader::register( 'wc_items', 'BWFAN_WC_Item_Data', null, 'Order Item' );
}