<?php

class BWFAN_Current_Datetime extends BWFAN_Merge_Tag {

	private static $instance = null;


	public function __construct() {
		$this->tag_name        = 'current_datetime';
		$this->tag_description = __( 'Current Datetime', 'wp-marketing-automations' );
		add_shortcode( 'bwfan_current_datetime', array( $this, 'parse_shortcode' ) );
		$this->support_fallback = false;
		$this->support_date     = true;
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
	 * @return mixed|void
	 */
	public function parse_shortcode( $attr ) {
		$parameters           = [];
		$parameters['format'] = isset( $attr['format'] ) ? $attr['format'] : 'j M Y';
		if ( isset( $attr['modify'] ) ) {
			$parameters['modify'] = $attr['modify'];
		}

		$date_time = $this->format_datetime( date( 'Y-m-d H:i:s' ), $parameters );

		return $this->parse_shortcode_output( $date_time, $attr );
	}

	/**
	 * Return mergetag schema
	 *
	 * @return array[]
	 */
	public function get_setting_schema() {
		$formats = $this->date_formats;
		$date_formats = [];
		foreach ( $formats as $data ) {
			if( isset( $data['format'] ) ) {
				$date_time = date( $data['format'] );
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
BWFAN_Merge_Tag_Loader::register( 'bwfan_default', 'BWFAN_Current_Datetime', null, 'General' );
