<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://ljapps.com
 * @since             1.0
 * @package           WP_Facebook_Reviews
 *
 * @wordpress-plugin
 * Plugin Name:       WP Review Slider
 * Plugin URI:        https://wpreviewslider.com/
 * Description:       Allows you to easily display your Facebook Page reviews and Twitter posts in your Posts, Pages, and Widget areas.
 * Version:           13.2
 * Author:            LJ Apps
 * Author URI:        http://ljapps.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-fb-reviews
 * Domain Path:       /languages
 */

if ( ! function_exists( 'wfr_fs' ) ) {
    // Create a helper function for easy SDK access.
    function wfr_fs() {
        global $wfr_fs;

        if ( ! isset( $wfr_fs ) ) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $wfr_fs = fs_dynamic_init( array(
                'id'                  => '1558',
                'slug'                => 'wpfb-welcome-slug',
                'type'                => 'plugin',
                'public_key'          => 'pk_4c8c2b98757ea4e5c4c2b6d48622a',
                'is_premium'          => false,
                'has_addons'          => false,
                'has_paid_plans'      => false,
                'menu'                => array(
                    'slug'           => 'wpfb-welcome-slug',
                ),
            ) );
        }

        return $wfr_fs;
    }

    // Init Freemius.
    wfr_fs();
    // Signal that SDK was initiated.
    do_action( 'wfr_fs_loaded' );
}
 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-fb-reviews-activator.php
 */
function activate_WP_FB_Reviews($networkwide) {
	//save time activated
	$newtime=time();
	update_option( 'wprev_activated_time', $newtime );
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-fb-reviews-activator.php';
	WP_FB_Reviews_Activator::activate_all($networkwide);
}

//add link to change log on plugins menu
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wprevfb_action_links' );
function wprevfb_action_links( $links )
{
    $links[] = '<a href="https://wpreviewslider.com/" target="_blank"><strong style="color: #009040; display: inline;">Go Pro!</strong></a>';
    return $links;
}


/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-fb-reviews-deactivator.php
 */
function deactivate_WP_FB_Reviews() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-fb-reviews-deactivator.php';
	WP_FB_Reviews_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_WP_FB_Reviews' );
register_deactivation_hook( __FILE__, 'deactivate_WP_FB_Reviews' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-fb-reviews.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_WP_FB_Reviews() {
	define( 'wpfbrev_plugin_dir', plugin_dir_path( __FILE__ ) );
	define( 'wpfbrev_plugin_url', plugins_url( "",__FILE__) );

	// Not like register_uninstall_hook(), you do NOT have to use a static function.
    wfr_fs()->add_action('after_uninstall', 'wfr_fs_uninstall_cleanup');
	
	$plugin = new WP_FB_Reviews();
	$plugin->run();

}

//uninstall cleanup.
function wfr_fs_uninstall_cleanup()
{
	
			// Leave no trail
		$option1 = 'widget_wprev_widget';
		$option2 = 'wp-fb-reviews_version';
		$option3 = 'wpfbr_options';
		$option4 = 'wpfbr_fb_app_id';
		$option5 = 'wprev_notice_hide';
		$option6 = 'wprev_activated_time';
		
		
		
	//================
	//check for pro version, if yes then do not delete this stuff
	//check for pro version, if yes then do not delete this stuff
$filename = plugin_dir_path( __DIR__ ).'/wp-review-slider-pro-premium/wp-review-slider-pro.php';
$filename2 = plugin_dir_path( __DIR__ ).'/wp-review-slider-pro/wp-review-slider-pro.php';

	if ( is_plugin_active( 'wp-review-slider-pro-premium/wp-review-slider-pro.php' ) || file_exists($filename)) {
		//pro version is installed and activated do not delete tables
		
	} else if ( is_plugin_active( 'wp-review-slider-pro/wp-review-slider-pro.php' ) || file_exists($filename2)) {
		//pro version is installed and activated do not delete tables
		
	} else {
	
		//pro version not installed, okay to delete tables
		if ( !is_multisite() ) 
		{
			delete_option( $option1 );
			delete_option( $option2 );
			delete_option( $option3 );
			delete_option( $option4 );
			delete_option( $option5);
			delete_option( $option6 );
			
			//delete review table in database
			global $wpdb;

			$table_name = $wpdb->prefix . 'wpfb_reviews';
			
			$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
		
			//drop review template table 
			$table_name = $wpdb->prefix . 'wpfb_post_templates';
			
			$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
		} 
		else 
		{
			global $wpdb;
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
			$original_blog_id = get_current_blog_id();

			foreach ( $blog_ids as $blog_id ) 
			{
				switch_to_blog( $blog_id );
				delete_option( $option1 );
				delete_option( $option2 );
				delete_option( $option3 );
				delete_option( $option4 );
				delete_option( $option5);
			delete_option( $option6 );
				
				$table_name = $wpdb->prefix . 'wpfb_reviews';
			
				$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
			
				//drop review template table 
				$table_name = $wpdb->prefix . 'wpfb_post_templates';
				
				$wpdb->query( "DROP TABLE IF EXISTS $table_name" );

				// OR
				// delete_site_option( $option_name );  
			}

			switch_to_blog( $original_blog_id );
		}
		
		    //delete avatar and cache directories if pro version not installed.
			
			$upload = wp_upload_dir();
			$upload_dir = $upload['basedir'];
			$upload_dir_wprev = $upload_dir . '/wprevslider/';
			wpprorev_rmrf_fb( $upload_dir_wprev );
	}
	//==================
}
	
function wpprorev_rmrf_fb( $dir )
	{
		foreach ( glob( $dir ) as $file ) {
			if ( is_dir( $file ) ) {
				wpprorev_rmrf_fb( "{$file}/*" );
				rmdir( $file );
			} else {
				unlink( $file );
			}
		
		}
	}

run_WP_FB_Reviews();

