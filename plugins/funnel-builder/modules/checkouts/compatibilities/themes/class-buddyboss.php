<?php
/**
 * BuddyBoss Theme by BuddyBoss (v.1.7.3)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_BuddyBoss {

	private $wfacp_id = 0;

	public function __construct() {
		add_action( 'wfacp_checkout_page_found', [ $this, 'initialize_elementor_widgets' ], 15 );
	}

	public function initialize_elementor_widgets( $post_id ) {
		$design = WFACP_Common::get_page_design( $post_id );

		$is_global_checkout = WFACP_Core()->public->is_checkout_override();

		if ( $is_global_checkout === true && 'elementor' == $design['selected_type'] && class_exists( '\Elementor\Plugin' ) ) {
			$this->wfacp_id = $post_id;
			add_filter( 'template_include', [ $this, 're_assign' ], 90 );
		}
	}

	public function re_assign( $template ) {
		if ( $this->wfacp_id > 0 ) {
			global $post;
			$post = get_post( $this->wfacp_id );
		}

		return $template;
	}
}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_BuddyBoss(), 'buddyBoss' );
