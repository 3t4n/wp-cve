<?php
/**
 * X Pro Theme Compatibility added
 * By Theme.co
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *
 * below code only work with gutenberg block
 * not with x pro theme editor
 * #[AllowDynamicProperties] 

  class WFACP_Compatibility_With_ThemeCoXPro
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_ThemeCoXPro {

	public function __construct() {
		add_action( 'wfacp_header_print_in_head', [ $this, 'print_css' ], 20 );
		add_action( 'wfacp_woocommerce_review_order_before_submit', [ $this, 'remove_loader' ] );
	}

	public function print_css() {
		if ( function_exists( 'x_output_generated_styles' ) ) {
			$template = wfacp_template();
			global $post;
			if ( ! is_null( $post ) && $post->post_type == WFACP_Common::get_post_type_slug() && "embed_form" == $template->get_template_type() ) {
				x_output_generated_styles();
			}
		}
	}

	public function remove_loader() {
		if ( function_exists( 'x_woocommerce_add_submit_spinner' ) ) {
			remove_action( 'woocommerce_review_order_after_submit', 'x_woocommerce_add_submit_spinner' );
		}
	}
}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_ThemeCoXPro(), 'themeco_xpro' );
