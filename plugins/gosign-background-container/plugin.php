<?php
/**
 * Plugin Name: Gosign - Background Container Block
 * Plugin URI: https://www.gosign.de/
 * Description: Gosign - Background Container — This plugin contains 40+ background container options i.e parallax, background color, images and many more..
 * Author: Gosign.de
 * Author URI: https://www.gosign.de/wordpress-agentur/
 * Version: 2.7.2
 * License: GPL3+
 * License URI: https://www.gnu.org/licenses/gpl.txt
 *
 * @package GOSIGN
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';
