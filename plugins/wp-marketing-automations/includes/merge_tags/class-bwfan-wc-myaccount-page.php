<?php

class BWFAN_WC_MyAccount_Page extends BWFAN_Merge_Tag {

	private static $instance = null;


	public function __construct() {
		$this->tag_name        = 'wc_myaccount_page';
		$this->tag_description = __( 'WC Account Page URL', 'wp-marketing-automations' );
		add_shortcode( 'bwfan_wc_myaccount_page', array( $this, 'parse_shortcode' ) );
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
	 * @return mixed|void
	 */
	public function parse_shortcode( $attr ) {
		return $this->parse_shortcode_output( wc_get_page_permalink( 'myaccount' ), $attr );
	}


}

/**
 * Register this merge tag to a group.
 */
if(bwfan_is_woocommerce_active()){
	BWFAN_Merge_Tag_Loader::register( 'bwfan_default', 'BWFAN_WC_MyAccount_Page', null, 'General' );
}

