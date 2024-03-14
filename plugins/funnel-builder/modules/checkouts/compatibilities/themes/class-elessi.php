<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
Theme Name: Elessi Theme
Author: NasaTheme
*/

#[AllowDynamicProperties] 

  class WFACP_Compatabilty_Elessi {
	public function __construct() {

		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_theme_hooks' ] );
	}

	public function remove_theme_hooks() {

		$template = wfacp_template();
		if ( ! $template instanceof WFACP_Template_Common ) {
			return;
		}

		if ( $template->get_template_type() !== 'pre_built' ) {
			return;
		}
		if ( ! function_exists( 'elessi_get_footer_theme' ) ) {
			return;
		}


		remove_action( 'wp_footer', 'elessi_get_footer_theme', 1 );
	}


}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatabilty_Elessi(), 'wfacp-elessi-theme' );
