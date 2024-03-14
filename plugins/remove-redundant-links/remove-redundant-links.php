<?php
/*
Plugin Name: Remove Redundant Links
Text Domain: plugin-rrl
Description: Deactivates links to the current page.
Version:     1.7
Author:      Thomas Scholz
Author URI:  http://toscho.de
License:     GPL v2
*/

define( 'PLUGIN_RRL_TEXT_DOMAIN', 'plugin-rrl' );
load_plugin_textdomain(
	PLUGIN_RRL_TEXT_DOMAIN
,	FALSE
,	basename( dirname( __FILE__ ) ) . '/lang'
);

/**
 * Manager for the plugin Remove Redundant Links.
 *
 * @return void
 */
if ( ! function_exists( 'rrl_start' ) )
{
	function rrl_start()
	{
		if ( is_feed() )
		{
			return;
		}

		! class_exists( 'Remove_Redundant_Links' ) and
			include dirname( __FILE__ ) . '/class.Remove_Redundant_Links.php';

		// Make it global to allow easier deactivation.
		global $Remove_Redundant_Links;

		$settings = array (
			'class'   => 'rrl current_page_item'
		,	'title'   => __( 'You are here.', PLUGIN_RRL_TEXT_DOMAIN )
		,	'charset' => get_bloginfo( 'charset' )
		);

		$Remove_Redundant_Links = new Remove_Redundant_Links(
		// You may hook into the settings with
		// add_filter( 'rrl_settings', 'change_rrl_settings', 10, 1 );
		// where change_rrl_settings( $settings ) returns an altered array.
			apply_filters( 'rrl_settings', $settings )
		);

		ob_start( array ( $Remove_Redundant_Links, 'convert' ) );
	}
}
add_action( 'template_redirect', 'rrl_start', 50 );