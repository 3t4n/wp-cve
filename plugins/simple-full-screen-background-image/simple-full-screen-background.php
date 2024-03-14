<?php
/*
Plugin Name: Simple Full Screen Background Image
Plugin URI:  https://fullscreenbackgroundimages.com/
Description: Easily set an automatically scaled full-screen background image
Version: 1.2.10
Author: AMP-MODE
Author URI: https://amplifyplugins.com
*/

function sfsb_textdomain() {

	// Set filter for plugin's languages directory
	$lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$lang_dir = apply_filters( 'sfsb_languages_directory', $lang_dir );

	// Load the translations
	load_plugin_textdomain( 'simple-full-screen-background-image', false, $lang_dir );
}
add_action( 'init', 'sfsb_textdomain' );

/*****************************************
* global
*****************************************/

$sfsb_options = get_option( 'fsb_settings' );

/*****************************************
* includes
*****************************************/
include( 'includes/admin-page.php' );
include( 'includes/display-image.php' );
include( 'includes/scripts.php' );
include( 'includes/meta-box.php' );


/**
 * When activating the pro version of Full Screen Background Images, this plugin's admin menu remains.
 * The menus have the same name and slug, so users get an inconsistent experience. It seems that some users
 * get the correct admin page when the pro version while others get this version's admin page.
 *
 * First we're checking if the pro version is installed, then removing this version's admin page.
 * This needs to come first since the admin_menu action is called before the admin_init action, which we'll need below.
 */
if( defined( 'FSB_VERSION' ) && is_string( FSB_VERSION ) ){
	remove_action( 'admin_menu', 'sfsb_init_admin' );
}

/**
 * Now, using the admin_init action we can copy the image that was set in this version of the plugin over
 * to the pro version. This image will be set with a global context, which is the same behavior as this version.
 * This will give the site the same appearance with the pro version activated as it had with this version activated.
 *
 * Finally, once the image has been copied over we'll deactivate this plugin. It is recommended that deactivate_plugins
 * is called in the admin_init action. https://codex.wordpress.org/Function_Reference/deactivate_plugins
 * However, this would be too late for the admin_menu action, so we had to remove this version's menu earlier.
 */
add_action( 'admin_init', 'sfsb_check_for_pro' );
function sfsb_check_for_pro(){
	// FSB_VERSION is defined in Pro, so this should be true if Pro is active.
	if( defined( 'FSB_VERSION' ) && is_string( FSB_VERSION ) ){
		global $wpdb;
		global $sfsb_options;
		$pro_table		= $wpdb->prefix . "fsb_images";

		// If pro is active, we want to add the existing image from Simple Full Screen Background Image to the Pro database. First need to check if the pro table exists.
		if ( $wpdb->get_var( "show tables like '$pro_table'" ) == $pro_table ) {
			// Pro table exists, so let's check to see if any images are in the pro table.
			$rowcount	= $wpdb->get_var( "SELECT COUNT(*) FROM $pro_table" );
			if( $rowcount == 0 ){
				// No images in the pro table, so we'll add the existing image from Simple Full Screen Background Image with a global context so the site does not lose it's appearance.
				$new_image = $wpdb->insert( $pro_table,
					array(
						'name'			=> $sfsb_options['image'],
						'url'			=> $sfsb_options['image'],
						'context'		=> 'global',
						'page_ids'		=> '',
						'needs_updated'	=> 0,
						'priority'		=> 1
					)
				);
			}
		}

		deactivate_plugins( plugin_basename( __FILE__ ) );
	}
}