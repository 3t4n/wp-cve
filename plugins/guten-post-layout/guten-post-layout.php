<?php
/**
 * Plugin Name: Guten Post Layout
 * Plugin URI: https://wordpress.org/plugins/guten-post-layout
 * Description: Your blog post layouts will be awesome without any doubt! We have grid, sliders, masonry and list layouts for you!
 * Author: GutenDev
 * Author URI: https://gutendev.com/
 * Version: 1.2.4
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: guten-post-layout
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Define Version
define('GUTEN_POST_LAYOUT_VERSION', '1.2.4');

// Define License
define('GUTEN_POST_LAYOUT_LICENSE', 'free');

// Define plugin file
define('GUTEN_POST_LAYOUT_MAIN_FILE', __FILE__);

// Define Dir URL
define('GUTEN_POST_LAYOUT_DIR_URL', plugin_dir_url(__FILE__));

// Define Physical Path
define('GUTEN_POST_LAYOUT_DIR_PATH', plugin_dir_path(__FILE__));

define('GUTEN_POST_LAYOUT_BASE', plugin_basename(__FILE__) );

// Include Require File
require_once GUTEN_POST_LAYOUT_DIR_PATH .'classes/class-version-check.php'; // Initial Setup Data

// Version Check & Include Init File
if ( ! version_compare( PHP_VERSION, '5.6', '>=' ) ) {
	add_action( 'admin_notices', array('GPL_Version_Check', 'php_version_error_notice') );
} elseif ( ! version_compare( get_bloginfo( 'version' ), '4.7', '>=' ) ) {
	add_action( 'admin_notices', array('GPL_Version_Check', 'wp_version_error_notice') );
} else {
	require_once 'classes/class-gpl-core.php';
}
