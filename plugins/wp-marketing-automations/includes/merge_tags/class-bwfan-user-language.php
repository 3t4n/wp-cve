<?php

class BWFAN_Contact_Language extends BWFAN_Merge_Tag {

	private static $instance = null;

	public function __construct() {
		$this->tag_name        = 'contact_language';
		$this->tag_description = __( 'Contact Language', 'wp-marketing-automations' );
		add_shortcode( 'bwfan_user_language', array( $this, 'parse_shortcode' ) );
		add_shortcode( 'bwfan_contact_language', array( $this, 'parse_shortcode' ) );
		$this->support_fallback = true;
		$this->priority = 36;
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
			return $this->get_dummy_preview();
		}

		$language = BWFAN_Merge_Tag_Loader::get_data( 'language' );
		if ( is_array( $language ) ) {
			$language = implode( ',', $language );
		}

		return $this->parse_shortcode_output( $language, $attr );
	}

	/**
	 * Show dummy value of the current merge tag.
	 *
	 * @return string
	 */
	public function get_dummy_preview() {
		return 'en';
	}

	/**
	 * Return mergetag schema
	 *
	 * @return array[]
	 */
	public function get_setting_schema() {
		return [
			[
				'id'          => 'fallback',
				'label'       => __( 'Fallback', 'wp-marketing-automations' ),
				'type'        => 'text',
				'class'       => '',
				'placeholder' => '',
				'required'    => false,
				'toggler'     => array(),
			],
		];
	}
}

/**
 * Register this merge tag to a group.
 */
BWFAN_Merge_Tag_Loader::register( 'bwf_contact', 'BWFAN_Contact_Language', null, 'Contact' );
