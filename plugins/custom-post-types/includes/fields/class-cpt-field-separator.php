<?php

defined( 'ABSPATH' ) || exit;

class CPT_Field_Separator extends CPT_Field {
	/**
	 * @return string
	 */
	public static function get_type() {
		return 'separator';
	}

	/**
	 * @return string
	 */
	public static function get_label() {
		return __( 'Separator', 'custom-post-types' ) . self::get_pro_label();
	}

	/**
	 * @return array
	 */
	public static function get_extra() {
		return array();
	}

	/**
	 * @param $input_name
	 * @param $input_id
	 * @param $field_config
	 *
	 * @return string
	 */
	public static function render( $input_name, $input_id, $field_config ) {
		return cpt_utils()->get_pro_banner();
	}
}

cpt_fields()->add_field_type( CPT_Field_Separator::class );
