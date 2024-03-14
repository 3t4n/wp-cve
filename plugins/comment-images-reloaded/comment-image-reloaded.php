<?php
/* ====================================================================================================
 *	Plugin Name: Comment Images Reloaded 
 *	Description: Plugin allows visitors attach images to comments (reloaded version <a href="https://wordpress.org/plugins/comment-images/">Comment Image by Tom McFarlin</a>).
 *	Plugin URI: http://wp-puzzle.com/comment-images-reloaded/
 *  Author: WP Puzzle 
 *  Author URI: http://wp-puzzle.com/
 *	Version: 2.2.1
 * ==================================================================================================== */

define( "CIR_PLUGIN_PATH", plugin_dir_path(__FILE__) );

// If this file is called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die;
} 



require_once( plugin_dir_path( __FILE__ ) . 'class-comment-image-reloaded.php' );
require_once( plugin_dir_path( __FILE__ ) . 'front/front-functions.php');

$cir = Comment_Image_Reloaded::get_instance();




function the_cir_upload_field() {

	global $cir, $post;
	$cir->cir_front->add_image_upload_form( $post->ID );

}


function get_cir_upload_field() {

	global $cir, $post;

	ob_start();
	$cir->cir_front->add_image_upload_form( $post->ID );
	$field = ob_get_contents();
	ob_end_clean();	

	return $field;

}