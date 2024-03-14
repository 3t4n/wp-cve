<?php
/**
 * Plugin Name: Responsive iframe
 * Plugin URI: 
 * Description: Creates responsive iframe elements
 * Author: PatrickPelayo
 * Author URI: http://www.PatrickP.Tech/
 * Version: 1.2.0
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';
