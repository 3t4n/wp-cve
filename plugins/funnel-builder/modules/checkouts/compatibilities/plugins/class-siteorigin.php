<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_SiteOrigin {

	public function __construct() {
		add_action( 'wfacp_none_checkout_pages', [ $this, 'force_execute_embed_shortcode' ], - 1 );
	}

	public function force_execute_embed_shortcode() {
		if ( class_exists( 'WFACP_Template_loader' ) ) {
			global $post;
			if ( is_null( $post ) || $post->post_type == WFACP_Common::get_post_type_slug() ) {
				return;
			}
			$panels_data = get_post_meta( $post->ID, 'panels_data', true );;
			if ( empty( $panels_data ) ) {
				return;
			}
			$shortcodes     = json_encode( $panels_data );
			$start_position = strpos( $shortcodes, '[wfacp_forms' );
			if ( false !== $start_position ) {
				$shortcode_string = substr( $shortcodes, $start_position );
				$closing_position = strpos( $shortcode_string, ']', 1 );
				if ( false !== $closing_position ) {
					$shortcode_string = substr( $shortcodes, $start_position, $closing_position + 1 );
					if ( strlen( $shortcode_string ) > 0 ) {
						do_shortcode( $shortcode_string );
					}
				}
			}
		}
	}


}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_SiteOrigin(), 'siteorigin' );
