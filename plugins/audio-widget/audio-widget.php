<?php 
/**
 * Plugin Name: Audio Widget
 * Plugin URI: http://sinayazdi.com/audio-widget
 * Description: This plugin adds Audio with Poster to your Widget.
 * Version: 0.1
 * Author: Sina Yazdi
 * Author URI: http://sinayazdi.com
 * License: GPL2
 */

defined ( 'ABSPATH' ) or die( 'No script kiddies please!' );

define ( 'SMY_AUDIO_WIDGET__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define ( 'SMY_AUDIO_WIDGET__PLUGIN_URL', plugin_dir_url( __FILE__ ) );

function smy_audio_widget() {
	wp_enqueue_script( 'uploader', SMY_AUDIO_WIDGET__PLUGIN_URL . 'js/uploader.js', array('jquery'), '0.1', true );
	wp_enqueue_media ();
}

add_action( 'admin_enqueue_scripts', 'smy_audio_widget' );
require_once( SMY_AUDIO_WIDGET__PLUGIN_DIR . 'inc/class-audio.php' );
?>