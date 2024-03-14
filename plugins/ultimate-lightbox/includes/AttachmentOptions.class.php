<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'ewdulbAttachmentOptions' ) ) {
/**
 * Class to handle adding and retrieving attachment options for Ultimate Lightbox
 *
 * @since 1.0.0
 */
class ewdulbAttachmentOptions {

	public function __construct() {

		// add_filter( 'attachment_fields_to_edit', 			array( $this, 'add_attachment_fields' ), 10, 2 );
		// add_filter( 'attachment_fields_to_save', 			array( $this, 'save_attachment_fields' ), 10, 2 );
		// add_filter( 'image_send_to_editor', 				array( $this, 'add_attachment_html' ), 10, 8 );

		// add_action( 'wp_ajax_ulb_get_paired_images', 		array( $this, 'get_paired_images' ) );
		// add_action( 'wp_ajax_nopriv_ulb_get_paired_images', array( $this, 'get_paired_images' ) );
	}


	public function add_attachment_fields( $form_fields, $post ) {
		global $ulb_controller;

	    if (substr($post->post_mime_type, 0, 5) == 'image') {
	
	        $form_fields["ewd_ulb_lightbox"] = array(
	            "label" => __("Lightbox?", 'ultimate-lightbox'),
	            "input" => "html",
	            "html" => "<input type='checkbox' name='attachments[{$post->ID}][ewd_ulb_lightbox]' id='attachments[{$post->ID}][ewd_ulb_lightbox]' value='on' " . ( get_post_meta($post->ID, "_EWD_ULB_Add_Lightbox", true) == "on" ? 'checked' : '' ) . "/>",
	            "helps" => "Should this image open in a lightbox when clicked?"
	        );
	
	        if ( $ulb_controller->settings->get_setting( 'curtain-slide' ) ) {
	            
	            if ( get_post_meta( $post->ID, "_EWD_ULB_Paired_Image_ID", true ) ) {
		            
		            $image = wp_get_attachment_image_src( get_post_meta( $post->ID, "_EWD_ULB_Paired_Image_ID", true ) );
		
		            if ( $image ) {$current_image_html = "<img class='ewd-ulb-paired-image-preview' src='" . $image[0] . "' /><br /><div class='ewd-ulb-remove-paired-image'>" . __("Remove Pairing", 'ultimate-lightbox') . "</div>";}
		        }
		        if ( ! isset( $current_image_html ) ) { $current_image_html = ""; }

	            $form_fields["ewd_ulb_curtain_slide_pair"] = array(
	                "label" => __("Curtain Slide Paired Image?", 'ultimate-lightbox'),
	                "input" => "html",
	                "html" => $current_image_html . "<div class='ewd-ulb-paired-image-select'>" . __("Select Pair", 'ultimate-lightbox') . "</div><input type='hidden' name='attachments[{$post->ID}][ewd_ulb_curtain_slide_pair]' id='attachments[{$post->ID}][ewd_ulb_curtain_slide_pair]' value='" . get_post_meta( $post->ID, "_EWD_ULB_Paired_Image_ID", true ) . "'/>",
	                "helps" => "What image, if any, should be revealed by the curtain slide for this image in the lightbox?"
	            );
	        }
	    }
	    return $form_fields;
	}

	public function save_attachment_fields( $post, $attachment ) {

	    if ( isset( $attachment['ewd_ulb_lightbox'] ) ) { update_post_meta( $post['ID'], '_EWD_ULB_Add_Lightbox', $attachment['ewd_ulb_lightbox'] ); }
	    else { update_post_meta( $post['ID'], '_EWD_ULB_Add_Lightbox', 'off' ); }

	    if ( isset( $attachment['ewd_ulb_curtain_slide_pair'] ) ) { update_post_meta( $post['ID'], '_EWD_ULB_Paired_Image_ID', $attachment['ewd_ulb_curtain_slide_pair'] ); }
	    else { update_post_meta( $post['ID'], '_EWD_ULB_Paired_Image_ID', 'off' ); }
	    
	    return $post;
	}

	public function add_attachment_html( $html, $id, $caption, $title, $align, $url, $size, $alt = '' ) {
	    
	    $class = 'ewd-ulb-lightbox';
	    $html = '';

	    if ( get_post_meta( $id, "_EWD_ULB_Add_Lightbox", true ) == 'on' ) {
	        
	        if ( preg_match('/<a.*? class=".*?">/', $html) ) {
	            
	            $html = preg_replace('/(<a.*? class=".*?)(".*?>)/', '$1 ' . $class . '$2', $html);
	        } else {

	            $html = preg_replace('/(<a.*?)>/', '$1 class="' . $class . '" >', $html);
	        }

	        $html = str_replace("><img", " data-ulbsource='" . $url . "'><img", $html);
	    } 

	    $html .= "Word"; 

	    return $html;
	}

	public function get_paired_images() {
	    
	    $image_source_array = json_decode( stripslashes( $_POST['image_sources'] ) );
	    if ( ! is_array( $image_source_array ) ) { $image_source_array = array(); }

	    array_walk( $image_source_array, 'esc_url_raw' );
	
	    $paired_image_array = array();
	    foreach ( $image_source_array as $image_source ) {

	    	$attachment_id = attachment_url_to_postid( $image_source );
	    	if ( ! $attachment_id ) { continue; }

	    	$paired_image_id = get_post_meta( $attachment_id, "_EWD_ULB_Paired_Image_ID", true );
	    	if ( ! $paired_image_id) { continue; }

	    	$paired_image = wp_get_attachment_image_src( $paired_image_id, 'full' );
	    	$paired_image_url = $paired_image[0];
	
	    	$paired_image_array[$image_source] = $paired_image_url;
	    }
	
	    echo json_encode($paired_image_array); //response
	
	    die();
	}

}
}