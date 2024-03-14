<?php 

/**
 * @package    DirectoryPress
 * @subpackage DirectoryPress/public/single-listing
 * @author     Designinvento <developers@designinvento.net>
*/

global $DIRECTORYPRESS_ADIMN_SETTINGS;
$post_id = $listing->post->ID;

//$text_string = ($button_text)? esc_html__('Share', 'DIRECTORYPRESS'): '';
//$tooltip = (!$button_text)? 'data-toggle="tooltip" title="'.esc_attr__('Share', 'DIRECTORYPRESS').'"':'';
$facebook = (metadata_exists('post', $post_id, 'facebook_link') && !empty(get_post_meta($post_id, 'facebook_link', true)))? get_post_meta($post_id, 'facebook_link', true):'';
$twitter = (metadata_exists('post', $post_id, 'twitter_link') && !empty(get_post_meta($post_id, 'twitter_link', true)))? get_post_meta($post_id, 'twitter_link', true):'';
$linkedin = (metadata_exists('post', $post_id, 'linkedin_link') && !empty(get_post_meta($post_id, 'linkedin_link', true)))? get_post_meta($post_id, 'linkedin_link', true):'';
$youtube = (metadata_exists('post', $post_id, 'youtube_link') && !empty(get_post_meta($post_id, 'youtube_link', true)))? get_post_meta($post_id, 'youtube_link', true):'';
$instagram = (metadata_exists('post', $post_id, 'instagram_link') && !empty(get_post_meta($post_id, 'instagram_link', true)))? get_post_meta($post_id, 'instagram_link', true):'';


if ($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_share_buttons']['enabled']):
	echo '<div class="directorypress-listing-social-links">';
		echo '<ul class="clearfix">';
			if(!empty($facebook)){
				echo '<li><a href="'. esc_url($facebook) .'" target="_blank"><i class="fab fa-facebook-f"></i></a></li>';
			}
			if(!empty($twitter)){
				echo '<li><a href="'. esc_url($twitter) .'" target="_blank"><i class="fab fa-twitter"></i></a></li>';
			}
			if(!empty($linkedin)){
				echo '<li><a href="'. esc_url($linkedin) .'" target="_blank"><i class="fab fa-linkedin-in"></i></a></li>';
			}
			if(!empty($youtube)){
				echo '<li><a href="'. esc_url($youtube) .'" target="_blank"><i class="fab fa-youtube"></i></a></li>';
			}
			if(!empty($instagram)){
				echo '<li><a href="'. esc_url($instagram) .'" target="_blank"><i class="fab fa-instagram"></i></a></li>';
			}
		echo '</ul>';
	echo '</div>';
endif;



 