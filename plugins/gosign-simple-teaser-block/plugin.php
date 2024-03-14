<?php
/**
 * Plugin Name: Gosign - Simple Teaser Block
 * Plugin URI: https://www.gosign.de/
 * Description: Gosign - Simple Teaser Block  — is a Gutenberg plugin created by Gosign. This contains basic simple teaser where there is an image, headline, text and button link.
 * Author: Gosign.de
 * Author URI: https://www.gosign.de/team/
 * Version: 2.0.1
 * License: GPL3+
 * License URI: https://www.gnu.org/licenses/gpl.txt
 *
 * @package GSTB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';
