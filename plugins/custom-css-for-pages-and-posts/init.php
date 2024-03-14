<?php
/*
Plugin Name: Custom CSS for Pages and Posts
Plugin URI: http://ahjira.com/plugins/custom-css-for-pages-and-posts
Description: Add a custom css metabox on the post/page/custom post type edit screens
Version: 1.1
Author: Suzanne Ahjira
Author URI: http://ahjira.com
License: GPLv2 or later

Adapted from the source found here:
http://www.iwebsource.net/2012/custom-css-wordpress-plugin

To add the custom css entry field to a custom post type, add this line to functions.php where 
POST_TYPE is the name of the custom post type, i.e. product, event, book

add_post_type_support( 'POST_TYPE', 'ahjira-custom-css');

*/


add_filter( 'admin_menu', 'ahjira_add_custom_css_metaboxes' );
add_filter( 'save_post', 'ahjira_save_custom_css_content' );
add_filter( 'wp_head', 'ahjira_inject_custom_css_styles' );

function ahjira_add_custom_css_metaboxes() {
  
	foreach ( (array)get_post_types( array( 'public' => true ) ) as $type ) {

		if ( post_type_supports( $type, 'ahjira-custom-css' ) || $type == 'post' || $type == 'page' ) {
			add_meta_box('ahjira_custom_css', 'Custom CSS', 'ahjira_insert_custom_css_textarea', $type, 'side', 'low');
		}

	}

}

function ahjira_insert_custom_css_textarea() {

  global $post;
  
  $value = get_post_meta( $post->ID, '_ahjira_custom_css', TRUE );
  
  $thecss = isset( $value ) ? esc_textarea( $value ) : "";  
  
  wp_nonce_field( basename( __FILE__ ), 'ahjira_custom_css_nonce' );

  ?>
  
  <textarea name="ahjira_custom_css" id="ahjira_custom_css_id" rows="10" style="width:100%;"><?php echo $thecss; ?></textarea>

  <?php
  
}

function ahjira_save_custom_css_content( $post_id ) {

  if( !isset( $_POST['ahjira_custom_css_nonce'] ) || !wp_verify_nonce( $_POST['ahjira_custom_css_nonce'], basename(__FILE__) ) )
    return $post_id;

  if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    return $post_id;

  $new_meta_value = ( isset( $_POST['ahjira_custom_css'] ) ? esc_textarea( $_POST['ahjira_custom_css'] ) : '' );
  
	$meta_value = get_post_meta( $post_id, '_ahjira_custom_css', true );

	if ( $new_meta_value && '' == $meta_value )
		add_post_meta( $post_id, '_ahjira_custom_css', $new_meta_value, true );

	elseif ( $new_meta_value && $new_meta_value != $meta_value )
		update_post_meta( $post_id, '_ahjira_custom_css', $new_meta_value );

	elseif ( '' == $new_meta_value && $meta_value )
		delete_post_meta( $post_id, '_ahjira_custom_css', $meta_value ); 

}

function ahjira_inject_custom_css_styles() {
  
  if( get_post_meta( get_the_ID(), '_ahjira_custom_css', true ) ) {
    echo '<style type="text/css">' . get_post_meta(get_the_ID(), '_ahjira_custom_css', true) . '</style>';
  }

}

