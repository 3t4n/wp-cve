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
 * @link       https://shapedplugin.com
 * @since      2.0.0
 *
 * @package    WP_Team
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
function sptp_delete_plugin_data() {

	// Delete plugin option settings.
	$option_name = '_sptp_settings';
	delete_option( $option_name );
	delete_site_option( $option_name ); // For site options in Multisite.

	// Delete member post type.
	$member_posts = get_posts(
		array(
			'numberposts' => -1,
			'post_type'   => array( 'sptp_member', 'sptp_generator' ),
			'post_status' => 'any',
		)
	);
	foreach ( $member_posts as $post ) {
		wp_delete_post( $post->ID, true );
	}
	// Delete Team post meta.
	delete_post_meta_by_key( '_sptp_add_member' );
	delete_post_meta_by_key( '_sptp_generator' );
	delete_post_meta_by_key( '_sptp_generator_layout' );
}

// Load WP Team Plugin file.
require plugin_dir_path( __FILE__ ) . '/team-free.php';
$sptp_plugin_settings = get_option( '_sptp_settings' );
$sptp_data_delete     = $sptp_plugin_settings['delete_on_remove'];

if ( $sptp_data_delete ) {
	sptp_delete_plugin_data();
}
