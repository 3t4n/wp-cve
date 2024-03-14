<?php

class BWFAN_Business_Name extends BWFAN_Merge_Tag {

	private static $instance = null;

	public function __construct() {
		$this->tag_name        = 'business_name';
		$this->tag_description = __( 'Business Name', 'wp-marketing-automations' );
		add_shortcode( 'bwfan_business_name', array( $this, 'parse_shortcode' ) );
		$this->support_fallback = false;
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
		$global_settings = BWFAN_Common::get_global_settings();
		if ( ! isset( $global_settings['bwfan_setting_business_name'] ) || empty( $global_settings['bwfan_setting_business_name'] ) ) {
			return '';
		}
		$business_name = $global_settings['bwfan_setting_business_name'];

		return $this->parse_shortcode_output( $business_name, $attr );
	}

}

/**
 * Register this merge tag to a group.
 */
BWFAN_Merge_Tag_Loader::register( 'bwfan_default', 'BWFAN_Business_Name', null, 'General' );
