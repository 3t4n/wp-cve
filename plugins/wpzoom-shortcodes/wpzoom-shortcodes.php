<?php
/**
 * Plugin Name: WPZOOM Shortcodes
 * Plugin URI: https://www.wpzoom.com/
 * Description: A suite of useful shortcodes compatible with any existing themes, not just with WPZOOM themes.
 * Author: WPZOOM
 * Author URI: httpd://www.wpzoom.com/
 * Version: 1.0.3
 * Copyright: (c) 2019 WPZOOM
 * License: GPLv2 or later
 * Text Domain: wpzoom-shortcodes
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once plugin_dir_path( __FILE__ ) . 'shortcodes/wzslider.php';

require_once plugin_dir_path( __FILE__ ) . "shortcodes/shortcodes.php";
require_once plugin_dir_path( __FILE__ ) . 'init.php';
