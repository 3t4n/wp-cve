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
 * @link       https://www.jssor.com
 * @since      1.0.0
 *
 * @package    WP_Jssor_Slider
 */

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Fired during plugin uninstallation.
 *
 * This class defines all code necessary to run during the plugin's uninstallation.
 *
 * @since      1.0.0
 * @package    WP_Jssor_Slider
 * @subpackage WP_Jssor_Slider/includes
 * @author     Your Name <email@example.com>
 */
class WP_Jssor_Slider_Uninstallation{

	/**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
	public static function uninstall() {
        if (file_exists(plugin_dir_path( __FILE__ ) . 'includes/framework/class-jssor-slider-globals.php')) {
            require_once plugin_dir_path( __FILE__ ) . 'includes/framework/class-jssor-slider-globals.php';
            self::delete_upload_folders();
            self::delete_dbs();
        }
	}

    /**
     * delete upload folders of the plugin
     *
     * @return void
     */
    private static function delete_upload_folders()
    {
        $upload_dir_paths = wp_upload_dir();
        $dirs = array();

        $dirs[] = $upload_dir_paths['basedir'] . WP_Jssor_Slider_Globals::UPLOAD_SLIDER;
        $dirs[] = $upload_dir_paths['basedir'] . WP_Jssor_Slider_Globals::UPLOAD_HTML;
        $dirs[] = $upload_dir_paths['basedir'] . WP_Jssor_Slider_Globals::UPLOAD_USER;

        //backward compatibility
        $dirs[] = $upload_dir_paths['basedir'] . WP_Jssor_Slider_Globals::UPLOAD_GENCODES;
        $dirs[] = $upload_dir_paths['basedir'] . WP_Jssor_Slider_Globals::UPLOAD_GENSLIDER_HTML;
        $dirs[] = $upload_dir_paths['basedir'] . WP_Jssor_Slider_Globals::UPLOAD_TEMP;
        $dirs[] = $upload_dir_paths['basedir'] . WP_Jssor_Slider_Globals::UPLOAD_THUMBNAILS;

        //dirs under /jssor-slider/jssor.com
        $dirs[] = $upload_dir_paths['basedir'] . WP_Jssor_Slider_Globals::UPLOAD_JSSOR_COM . '/help-slideo';
        $dirs[] = $upload_dir_paths['basedir'] . WP_Jssor_Slider_Globals::UPLOAD_JSSOR_COM . '/script';
        $dirs[] = $upload_dir_paths['basedir'] . WP_Jssor_Slider_Globals::UPLOAD_JSSOR_COM . '/template';
        $dirs[] = $upload_dir_paths['basedir'] . WP_Jssor_Slider_Globals::UPLOAD_JSSOR_COM . '/theme';
        $dirs[] = $upload_dir_paths['basedir'] . WP_Jssor_Slider_Globals::UPLOAD_JSSOR_COM . '/wordpress';

        foreach ($dirs as $dir) {
            self::rmdirr($dir);
        }
    }

    /**
     * delete tables of the plugin
     *
     * @return void
     */
    private static function delete_dbs() {
        $options = array(
            'wp_jssor_slider_db_version',
            'wjssl-update-check-short',
            'wjssl-connection',
            'wjssl-latest-version',
            'wjssl-stable-version',
            'wjssl-beta-version',
            //'wjssl-valid',
            'wjssl-deact-notice',
            'wjssl-update-check',
            'wp_jssor_slider_instance_id',
            'wjssl_actcode',
            'wjssl_acckey',
            'wjssl_purchcode',
        );
        foreach ($options as $key) {
            delete_option($key);
            // For site options in Multisite
            delete_site_option( $key);
        }

        // drop tables
        global $wpdb;
        $sliders_table_name = $wpdb->prefix . WP_Jssor_Slider_Globals::TABLE_SLIDERS;
        $wpdb->query( "DROP TABLE IF EXISTS $sliders_table_name" );

        $trans_table_name = $wpdb->prefix . WP_Jssor_Slider_Globals::TABLE_TRANSACTIONS;
        $wpdb->query( "DROP TABLE IF EXISTS $trans_table_name" );
    }

    private static function rmdirr( $dirname ){

		if( !file_exists( $dirname ) ) {
			return false;
		}

		if( is_file( $dirname ) ) {
			return @unlink( $dirname );
		}

		/* Loop through the folder */
		$dir = dir( $dirname );
		while( false !== $entry = $dir->read() ) {
			/*Skip	pointers */
			if( $entry == '.' || $entry == '..' ) {
				continue;
			}

			/* Recurse */
            self::rmdirr( "$dirname/$entry" );
		}

		/* Clean up */
		$dir->close();
		return rmdir( $dirname );

	}
}

WP_Jssor_Slider_Uninstallation::uninstall();
