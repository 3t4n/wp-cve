<?php

class BWFAN_WC_Order_Data extends BWFAN_Merge_Tag {

	private static $instance = null;


	public function __construct() {
		$this->tag_name        = 'order_data';
		$this->tag_description = __( 'Order Data', 'wp-marketing-automations' );
		add_shortcode( 'bwfan_order_data', array( $this, 'parse_shortcode' ) );
		$this->priority          = 5;
		$this->support_date      = true;
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
			return $this->parse_shortcode_output( $this->get_dummy_preview( $attr ), $attr );
		}

		$item_key = $attr['key'];
		$order    = $this->get_order_obj();
		if ( ! $order instanceof WC_Order ) {
			return $this->parse_shortcode_output( '', $attr );
		}

		$value = $order->get_meta( $item_key );
		if ( class_exists( 'WFACP_Common' ) ) {
			$value = $this->get_wfacp_label( $item_key, $value );
		}
		$value = is_string( $value ) ? nl2br( $value ) : $value;

		/** if value is date then checking format in attribute */
		$value = $this->get_date_value( $value, $attr );

		return $this->parse_shortcode_output( $value, $attr );
	}

	/**
	 * Show dummy value of the current merge tag.
	 *
	 * @return string
	 */
	public function get_dummy_preview( $attr ) {
		if ( ! is_array( $attr ) || ! isset( $attr['type'] ) || 'date' !== $attr['type'] || ! isset( $attr['format'] ) || empty( $attr['format'] ) ) {
			return __( 'Key value', 'wp-marketing-automations' );
		}

		return $this->format_datetime( date( 'Y-m-d H:i:s' ), $attr );
	}

	protected function get_order_obj() {
		$order = BWFAN_Merge_Tag_Loader::get_data( 'wc_order' );
		if ( $order instanceof WC_Order ) {
			return $order;
		}

		$order_id = BWFAN_Merge_Tag_Loader::get_data( 'order_id' );
		if ( empty( $order_id ) ) {
			return '';
		}

		$order = wc_get_order( $order_id );

		return ( $order instanceof WC_Order ) ? $order : '';
	}

	/**
	 * fetch label for dropdown and radio type
	 *
	 * @param $order_id
	 * @param $item_key
	 * @param $return_value
	 *
	 * @return mixed
	 */
	public function get_wfacp_label( $item_key, $return_value ) {
		$order = $this->get_order_obj();
		if ( ! $order instanceof WC_Order ) {
			return $return_value;
		}

		$wfacp_id = $order->get_meta( '_wfacp_post_id' );
		if ( empty( $wfacp_id ) ) {
			return $return_value;
		}

		$custom_field = WFACP_Common::get_checkout_fields( $wfacp_id );
		if ( empty( $custom_field ) || ! isset( $custom_field['advanced'] ) || ! isset( $custom_field['advanced'][ $item_key ] ) ) {
			return $return_value;
		}

		$valid_field_types = [ 'select', 'wfacp_radio' ];

		$field = $custom_field['advanced'][ $item_key ];
		if ( ! isset( $field['type'] ) || ! in_array( $field['type'], $valid_field_types, true ) ) {
			return $return_value;
		}

		if ( ! isset( $field['options'] ) || empty( $field['options'] ) || ! isset( $field['options'][ $return_value ] ) ) {
			return $return_value;
		}

		return empty( $field['options'][ $return_value ] ) ? $return_value : $field['options'][ $return_value ];
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
	 * Return merge tag schema
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
				'label'       => __( 'Date Field Format', 'wp-marketing-automations' ),
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
				'label'       => __( 'Output Format', 'wp-marketing-automations' ),
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
	BWFAN_Merge_Tag_Loader::register( 'wc_order', 'BWFAN_WC_Order_Data', null, 'Order' );
}