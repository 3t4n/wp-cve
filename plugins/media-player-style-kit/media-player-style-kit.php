<?php
/*
 * Plugin Name: Media Player Style Kit
 * Version: 1.0.1
 * Plugin URI: https://wordpress.org/plugins/media-player-style-kit/
 * Description: Change the colors of the WordPress media player right from the Customizer.
 * Author: Hugh Lashbrooke
 * Author URI: https://hugh.blog/
 * Requires at least: 4.6
 * Tested up to: 5.0
 *
 * Text Domain: media-player-style-kit
 *
 * @package WordPress
 * @author Hugh Lashbrooke
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Load plugin class files
require_once( 'includes/class-media-player-style-kit.php' );

/**
 * Returns the main instance of Media_Player_Style_Kit to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Media_Player_Style_Kit
 */
function Media_Player_Style_Kit () {
	$instance = Media_Player_Style_Kit::instance( __FILE__, '1.0.1' );
	return $instance;
}

Media_Player_Style_Kit();
