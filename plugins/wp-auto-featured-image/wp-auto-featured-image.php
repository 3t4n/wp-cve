<?php
/**
 * Plugin Name: WP Auto Featured Image
 * Plugin URI: https://github.com/sannysri/WordPress-Auto-Featured-Image
 * Description: Set a default featured image effortlessly for your posts, pages, or custom post types using our plugin. Streamline the process by establishing a fallback image based on categories. Choose an image from your media library or upload a new one with ease, ensuring a consistent and efficient way to manage featured images across your content.
 * Version: 2.0
 * Author: Sanny Srivastava
 * Author URI: https://sanny.dev/
 * License: GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-default-featured-image
 * Domain Path: /languages
 *
 * @package WP_Auto_Featured_Image
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define Constants and Version.
define( 'WP_AUTO_FI_URL', WP_PLUGIN_URL . '/wp-auto-featured-image' );

// Include necessary files.
require_once plugin_dir_path( __FILE__ ) . 'admin/class-wpafi-admin.php';

// Initialize the admin class.
if ( class_exists( 'WPAFI_Admin' ) ) {
	$wp_auto_featured_image_admin = new WPAFI_Admin();
}
