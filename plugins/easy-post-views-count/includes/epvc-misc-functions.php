<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Scripts Class
 *
 * Function for post views count html
 *
 * @package Easy Post Views Count
 * @since 1.0.0
 */
function epvc_display_post_views( $post_id = '' ){

	global $post, $epvc_settings;

	if( empty($post_id) ){
		$post_id = isset( $post->id ) ? $post->id : '';
	} else {
		$post = get_post( $post_id );
	}

	$post_types 	= is_array($epvc_settings['post_types'])?$epvc_settings['post_types']:array();
	$display_icon 	= $epvc_settings['display_icon'];
	$display_label 	= $epvc_settings['display_label'];
	$label_text 	= sanitize_text_field( $epvc_settings['label_text'] );
	$position 		= $epvc_settings['position'];
	
	if( in_array( $post->post_type, array_keys($post_types) ) ){

		$postCount = get_post_meta( $post->ID, 'post_count_'.$post->ID, true );
		$postCount = !empty($postCount)?$postCount:0;

		$label = '';
		$icon = '';

		if( $display_label == 'yes' ){
			$label = "<span class='epvc-label'> ".$label_text."</span>";
		}
		if( $display_icon == 'yes' ){
			$icon = "<span class='epvc-eye'></span> ";
		}

		$epvcCount = "<div class='epvc-post-count'>".$icon.' <span class="epvc-count"> '.number_format_i18n( $postCount ).'</span>'.$label."</div>";
		return $epvcCount;
	}
}