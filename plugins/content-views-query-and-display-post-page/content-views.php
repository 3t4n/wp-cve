<?php
/**
 * @package   PT_Content_Views
 * @author    PT Guy <http://www.contentviewspro.com/>
 * @license   GPL-2.0+
 * @link      http://www.contentviewspro.com/
 * @copyright 2014 PT Guy
 *
 * @wordpress-plugin
 * Plugin Name:       Content Views
 * Plugin URI:        http://wordpress.org/plugins/content-views-query-and-display-post-page/
 * Description:       Display your posts, pages, custom posts, media files in beautiful Grid, List, Slider, and other layouts easily. With 15+ blocks & 75+ patterns for the Block Editor, and powerful shortcode feature for the Classic Editor & page builders.
 * Version:           3.6.4
 * Author:            Content Views
 * Author URI:        http://profiles.wordpress.org/pt-guy
 * Text Domain:       content-views-query-and-display-post-page
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
	die;
}

// Define Constant
define( 'PT_CV_VERSION', '3.6.4' );
define( 'PT_CV_FILE', __FILE__ );
define( 'PT_CV_PATH', plugin_dir_path( __FILE__ ) );
define( 'PT_CV_URL', plugin_dir_url( __FILE__ ) );
include_once( PT_CV_PATH . 'includes/defines.php' );

// Include library files
include_once( PT_CV_PATH . 'includes/formatting.php' );
include_once( PT_CV_PATH . 'includes/assets.php' );
include_once( PT_CV_PATH . 'includes/compatibility.php' );
include_once( PT_CV_PATH . 'includes/functions.php' );
include_once( PT_CV_PATH . 'includes/hooks.php' );
include_once( PT_CV_PATH . 'includes/html-viewtype.php' );
include_once( PT_CV_PATH . 'includes/html.php' );
include_once( PT_CV_PATH . 'includes/settings.php' );
include_once( PT_CV_PATH . 'includes/update.php' );
include_once( PT_CV_PATH . 'includes/values.php' );
include_once( PT_CV_PATH . 'block/init.php' );
include_once( PT_CV_PATH . 'includes/block_view.php' );

// Main file
include_once( PT_CV_PATH . 'public/content-views.php' );

// Register hooks when the plugin is activated or deactivated.
register_activation_hook( __FILE__, array( 'PT_Content_Views', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'PT_Content_Views', 'deactivate' ) );

// Load plugin
PT_Content_Views::get_instance();

// For Admin
if ( is_admin() ) {
	include_once( PT_CV_PATH . 'admin/includes/options.php' );
	include_once( PT_CV_PATH . 'admin/includes/plugin.php' );
	include_once( PT_CV_PATH . 'admin/content-views-admin.php' );

	PT_Content_Views_Admin::get_instance();
}

// Enable post thumbnails
add_theme_support( 'post-thumbnails' );

// Enable View shortcode in post content, text widget
add_filter( 'the_content', 'cv_enable_shortcode', 12 );
add_filter( 'widget_text', 'cv_enable_shortcode', 12 );
function cv_enable_shortcode( $text ) {
	if ( has_shortcode( $text, PT_CV_POST_TYPE ) ) {
		$text = do_shortcode( $text );
	}

	return $text;
}
