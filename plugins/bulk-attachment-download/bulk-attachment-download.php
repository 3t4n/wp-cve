<?php

/**
 * Plugin Name:		Bulk Attachment Download
 * Plugin URI:		https://wordpress.org/plugins/bulk-attachment-download/
 * Description:		Bulk download media or attachments selectively from your Media Library as a zip file.
 * Version:			1.3.8
 * Author:			Jon Anwyl
 * Author URI:		https://www.sneezingtrees.com
 * Text Domain:		bulk-attachment-download
 * Domain Path:		/languages
 * License:			GPLv2
 * License URI:		https://www.gnu.org/licenses/gpl-2.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/*---------------------------------------------------------------------------------------------------------*/
/* Setup */

// Define constants.
if ( ! defined( 'JABD_PLUGIN_NAME' ) ) define( 'JABD_PLUGIN_NAME', 'Bulk Attachment Download' );
if ( ! defined( 'JABD_PLUGIN_DIR' ) ) define( 'JABD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
if ( ! defined( 'JABD_PLUGIN_BASE_URL' ) ) define( 'JABD_PLUGIN_BASE_URL', plugin_dir_url( __FILE__ ) );
if ( ! defined( 'JABD_DOWNLOADS_DIR' ) ) define( 'JABD_DOWNLOADS_DIR', 'jabd-downloads' );
if ( ! defined( 'JABD_VERSION' ) ) define( 'JABD_VERSION', '1.3.8' );

// Include plugin class and create instance.
require_once JABD_PLUGIN_DIR . 'incl/class-bulk-attachment-download-manager.php';
$jabd = new Bulk_Attachment_Download_Manager();

// Define uploads constant here so that it's available for uninstall process.
$jabd->define_uploads_folder();

// Include admin notice manager class and initialize.
require_once JABD_PLUGIN_DIR . 'incl/admin-notice-manager/class-admin-notice-manager.php';
Bulk_Attachment_Download_Admin_Notice_Manager::init( array(
	'plugin_name'		=>	'Bulk Attachment Download',
	'manager_id'		=>	'jabd',
	'text_domain'		=>	'bulk-attachment-download',
	'version'			=>	JABD_VERSION
) );

// Internationalization.
add_action( 'plugins_loaded', array( $jabd, 'load_plugin_textdomain' ) );

/*--------------------------------------------------------------------------------------------------*/
/* Code for integration with Freemius functionality (https://freemius.com/wordpress/insights/) */

if ( ! function_exists( 'jabd_fs' ) ) {
    // Create a helper function for easy SDK access.
    function jabd_fs() {
        global $jabd_fs;

        if ( ! isset( $jabd_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';

            $jabd_fs = fs_dynamic_init( array(
                'id'                  => '1226',
                'slug'                => 'bulk-attachment-download',
                'type'                => 'plugin',
                'public_key'          => 'pk_b313e39f6475c257bc3aadfbc55df',
                'is_premium'          => false,
                'has_addons'          => false,
                'has_paid_plans'      => false,
                'menu'                => array(
                    'first-path'     => 'plugins.php',
                    'account'        => false,
                    'contact'        => false,
                    'support'        => false,
                ),
            ) );
        }

        return $jabd_fs;
    }

    // Init Freemius.
    jabd_fs();
    // Signal that SDK was initiated.
    do_action( 'jabd_fs_loaded' );
}

// Hook in uninstall actions.
jabd_fs()->add_action( 'after_uninstall', array( $jabd, 'fs_uninstall_cleanup' ) );

/*---------------------------------------------------------------------------------------------------------*/
/* Plugin activation, deactivation and upgrade */
register_activation_hook( __FILE__, array( $jabd, 'on_activation' ) );
register_deactivation_hook( __FILE__, array( $jabd, 'on_deactivation' ) );
add_action( 'plugins_loaded' , array( $jabd, 'check_version' ) );
