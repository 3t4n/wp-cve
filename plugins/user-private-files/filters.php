<?php
// Exit if accessed directly
if ( ! defined('ABSPATH') ) {
   exit;
}

// Filter to return correct status when restoring files & folders
add_filter( 'wp_untrash_post_status', 'upfp_untrash_post_status', 10, 3 );
if (!function_exists('upfp_untrash_post_status')) {
	function upfp_untrash_post_status($new_status, $post_id, $previous_status){
		$post_types = array( 'upf_folder' );

		if ( in_array( get_post_type( $post_id ), $post_types, true ) ) {
			$new_status = $previous_status;
		}

		return $new_status;
	}
}

// filter function to modify uploads directory temporary while uploading files
if (!function_exists('upfp_modify_upload_dir')) {
	function upfp_modify_upload_dir($dir){
		$dir['path'] = $dir['basedir'] . '/upf-docs';
		$dir['url'] = $dir['baseurl'] . '/upf-docs';
		$dir['subdir'] = '/upf-docs';
		return $dir;
	}
}

// Fix larger file not uploading issue
add_filter( 'wp_image_editors', function() {
	return array( 'WP_Image_Editor_GD' ); 
} );
