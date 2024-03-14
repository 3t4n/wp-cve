<?php
/**
 * Plugin Name:  WP Featherlight Disabled
 * Plugin URI:   https://wpjohnny.com/wp-featherlight-disabled/
 * Description:  An ultra lightweight jQuery lightbox for WordPress images and galleries.
 * Donate link: https://www.paypal.me/wpjohnny
 * Version:      1.0.3
 * Author: <a href="https://wpjohnny.com">WPJohnny</a>, <a href="https://profiles.wordpress.org/zeroneit/">zerOneIT</a>
 * License:      GPL-2.0+
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  wp-featherlight
 * Domain Path:  /languages
 *
 * @package   WPFeatherlight
 * @copyright Copyright (c) 2020, WPJohnny
 * @license   GPL-2.0+
 * @since     0.1.0
 */

defined( 'WPINC' ) || die;

// Load the main plugin class.
require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/constants.php';

add_action( 'plugins_loaded', array( wp_featherlight(), 'run' ) );
/**
 * Allow themes and plugins to access WP_Featherlight methods and properties.
 *
 * Because we aren't using a singleton pattern for our main plugin class, we
 * need to make sure it's only instantiated once in our helper function.
 * If you need to access methods inside the plugin classes, use this function.
 *
 * Example:
 *
 * <?php wp_featherlight()->scripts; ?>
 *
 * @since  0.1.0
 * @access public
 * @uses   WP_Featherlight
 * @return object WP_Featherlight A single instance of the main plugin class.
 */
function wp_featherlight() {
	static $plugin;
	if ( null === $plugin ) {
		if (get_option('featherlight_plugin_db_updated') != 'yes') {
	        // update code here.
			global $wpdb;
			$results = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'postmeta WHERE meta_key="wp_featherlight_disable"');
			if (count($results)) {
				foreach ($results as $r) {
					$post_id = $r->post_id;
					$meta_value = $r->meta_value;
					update_post_meta($post_id, 'zeroneit_featherlight_disable', $meta_value);
				}
			}

	        // delete transient.
	        update_option( 'featherlight_plugin_db_updated', 'yes' );
	    }

		$plugin = new WP_Featherlight( array( 'file' => __FILE__ ) );
	}
	return $plugin;
}

/**
 * Register an activation hook to run all necessary plugin setup procedures.
 *
 * @since  0.1.0
 * @access public
 * @return void
 */
register_activation_hook( __FILE__, array( wp_featherlight(), 'activate' ) );
