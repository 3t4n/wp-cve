<?php

/**
 * Fired during plugin activation
 *
 * @link       http://linkpizza.com
 * @since      1.0.0
 *
 * @package    linkPizza_Manager
 * @subpackage linkPizza_Manager/includes
 */
class linkPizza_Manager_Activator {

	/**
	 * Activation methods.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		register_setting( 'linkPizza_Page', 'pzz_settings' );
		register_setting( 'linkPizza_Page', 'pzz_tracking_only_categories' );

		register_setting( 'linkPizza_Page_link_summary', 'pzz_link_summary_border_color' );
		register_setting( 'linkPizza_Page_link_summary', 'pzz_link_summary_border_width' );
		register_setting( 'linkPizza_Page_link_summary', 'pzz_link_summary_border_padding' );
		register_setting( 'linkPizza_Page_link_summary', 'pzz_link_summary_width' );
		register_setting( 'linkPizza_Page_link_summary', 'pzz_link_summary_link_color' );
		register_setting( 'linkPizza_Page_link_summary', 'pzz_link_summary_enabled' );
		register_setting( 'linkPizza_Page_link_summary', 'pzz_link_summary_layout_type' );

		// Link summary.
		if ( get_option( 'pzz_link_summary_border_color' ) === false ) {
			add_option( 'pzz_link_summary_border_color', '#D3D3D3', '', 'yes' );
		}

		if ( get_option( 'pzz_link_summary_border_width' ) === false ) {
			add_option( 'pzz_link_summary_border_width', '1', '', 'yes' );
		}

		if ( get_option( 'pzz_link_summary_border_padding' ) === false ) {
			add_option( 'pzz_link_summary_border_padding', '6', '', 'yes' );
		}

		if ( get_option( 'pzz_link_summary_width' ) === false ) {
			add_option( 'pzz_link_summary_width', '100', '', 'yes' );
		}

		if ( get_option( 'pzz_link_summary_link_color' ) === false ) {
			add_option( 'pzz_link_summary_link_color', '#3699DC', '', 'yes' );
		}

		if ( get_option( 'pzz_link_summary_position' ) === false ) {
			add_option( 'pzz_link_summary_position', '2', '', 'yes' );
		}

		if ( get_option( 'pzz_link_summary_layout_type' ) === false ) {
			add_option( 'pzz_link_summary_layout_type', '2', '', 'yes' );
		}

	}

}
