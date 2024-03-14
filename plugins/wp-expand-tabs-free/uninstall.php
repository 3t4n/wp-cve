<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://shapedplugin.com/
 * @since      2.0.0
 *
 * @package    WP_Tabs
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Delete plugin data function.
 *
 * @return void
 */
function sp_wptabspro_delete_plugin_data() {

	// Delete plugin option settings.
	$option_name = 'sp-tab__settings';
	delete_option( $option_name );
	delete_site_option( $option_name ); // For site options in Multisite.

	// Delete member post type.
	$tab_posts = get_posts(
		array(
			'numberposts' => -1,
			'post_type'   => 'sp_wp_tabs',
			'post_status' => 'any',
		)
	);

	foreach ( $tab_posts as $post ) {
		wp_delete_post( $post->ID, true );
	}

	// Delete Team post meta.
	delete_post_meta_by_key( 'sp_tab_source_options' );
	delete_post_meta_by_key( 'sp_tab_shortcode_options' );
	delete_post_meta_by_key( 'sp_tab_display_shortcode' );

}

// Load WP Tabs file.
require plugin_dir_path( __FILE__ ) . '/plugin-main.php';
$spwptabspro_plugin_settings = get_option( 'sp-tab__settings' );
$spwptabspro_data_delete     = $spwptabspro_plugin_settings['sptpro_data_remove'];

if ( $spwptabspro_data_delete ) {
	sp_wptabspro_delete_plugin_data();
}
