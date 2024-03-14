<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//oEmbed
// Are we being accessed directly ?
if(!defined('SITESEO_VERSION')) {
	exit('Hacking Attempt !');
}

// Get Oembed Title (custom OG:title or Post title)
function siteseo_oembed_title_hook($post){
	
	//Init
	$siteseo_oembed_title ='';

	$variables = null;
	$variables = apply_filters('siteseo_dyn_variables_fn', $variables, $post, true);

	$siteseo_titles_template_variables_array 	= $variables['siteseo_titles_template_variables_array'];
	$siteseo_titles_template_replace_array 	= $variables['siteseo_titles_template_replace_array'];

	//If OG title set
	if (get_post_meta($post->ID, '_siteseo_social_fb_title', true) !='') {
		$siteseo_oembed_title = get_post_meta($post->ID, '_siteseo_social_fb_title', true);
	} elseif (get_post_meta($post->ID, '_siteseo_titles_title', true) !='') {
		$siteseo_oembed_title = get_post_meta($post->ID, '_siteseo_titles_title', true);
	} elseif (get_the_title($post) !='') {
		$siteseo_oembed_title = the_title_attribute(['before'=>'','after'=>'','echo'=>false,'post'=>$post]);
	}

	//Apply dynamic variables
	preg_match_all('/%%_cf_(.*?)%%/', $siteseo_oembed_title, $matches); //custom fields

	if ( ! empty($matches)) {
		$siteseo_titles_cf_template_variables_array = [];
		$siteseo_titles_cf_template_replace_array   = [];

		foreach ($matches['0'] as $key => $value) {
			$siteseo_titles_cf_template_variables_array[] = $value;
		}

		foreach ($matches['1'] as $key => $value) {
			$siteseo_titles_cf_template_replace_array[] = esc_attr(get_post_meta($post->ID, $value, true));
		}
	}

	preg_match_all('/%%_ct_(.*?)%%/', $siteseo_oembed_title, $matches2); //custom terms taxonomy

	if ( ! empty($matches2)) {
		$siteseo_titles_ct_template_variables_array = [];
		$siteseo_titles_ct_template_replace_array   = [];

		foreach ($matches2['0'] as $key => $value) {
			$siteseo_titles_ct_template_variables_array[] = $value;
		}

		foreach ($matches2['1'] as $key => $value) {
			$term = wp_get_post_terms($post->ID, $value);
			if ( ! is_wp_error($term)) {
				$terms									   = esc_attr($term[0]->name);
				$siteseo_titles_ct_template_replace_array[] = apply_filters('siteseo_titles_custom_tax', $terms, $value);
			}
		}
	}

	//Default
	$siteseo_oembed_title = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_oembed_title);

	//Custom fields
	if ( ! empty($matches) && ! empty($siteseo_titles_cf_template_variables_array) && ! empty($siteseo_titles_cf_template_replace_array)) {
		$siteseo_oembed_title = str_replace($siteseo_titles_cf_template_variables_array, $siteseo_titles_cf_template_replace_array, $siteseo_oembed_title);
	}

	//Custom terms taxonomy
	if ( ! empty($matches2) && ! empty($siteseo_titles_ct_template_variables_array) && ! empty($siteseo_titles_ct_template_replace_array)) {
		$siteseo_oembed_title = str_replace($siteseo_titles_ct_template_variables_array, $siteseo_titles_ct_template_replace_array, $siteseo_oembed_title);
	}

	$siteseo_oembed_title = str_replace($siteseo_titles_template_variables_array, $siteseo_titles_template_replace_array, $siteseo_oembed_title);

	//Hook on post oEmbed title - 'siteseo_oembed_title'
	$siteseo_oembed_title = apply_filters('siteseo_oembed_title', $siteseo_oembed_title);

	return $siteseo_oembed_title;
}

// Get Oembed Thumbnail (custom OG:IMAGE or Post thumbnail)
function siteseo_oembed_thumbnail_hook($post){
	
	//Init
	$siteseo_oembed_thumbnail = [];

	//If OG title set
	if (get_post_meta($post->ID, '_siteseo_social_fb_img', true) !='') {
		$siteseo_oembed_thumbnail['url'] = get_post_meta($post->ID, '_siteseo_social_fb_img', true);
	} elseif (get_post_thumbnail_id($post) !='') {
		$post_thumbnail_id 	=  get_post_thumbnail_id($post);

		$img_size 			= 'full';

		$img_size 			= apply_filters('siteseo_oembed_thumbnail_size', $img_size);

		$attachment 		= wp_get_attachment_image_src($post_thumbnail_id, $img_size);

		if (is_array($attachment)) {
			$siteseo_oembed_thumbnail['url'] 		= $attachment[0];
			$siteseo_oembed_thumbnail['width']		= $attachment[1];
			$siteseo_oembed_thumbnail['height'] 	= $attachment[2];
		}
	}

	//Hook on post oEmbed thumbnail - 'siteseo_oembed_thumbnail'
	$siteseo_oembed_thumbnail = apply_filters('siteseo_oembed_thumbnail', $siteseo_oembed_thumbnail);

	return $siteseo_oembed_thumbnail;
}

add_filter('oembed_response_data', 'siteseo_oembed_response_data', 10, 4);
function siteseo_oembed_response_data($data, $post, $width, $height){
	
	if (function_exists('siteseo_oembed_title_hook') && siteseo_oembed_title_hook($post) !='') {
		$data['title'] = siteseo_oembed_title_hook($post);
	}
	if (function_exists('siteseo_oembed_thumbnail_hook') && siteseo_oembed_thumbnail_hook($post) !='') {
		$thumbnail = siteseo_oembed_thumbnail_hook($post);

		if (!empty($thumbnail['url'])) {
			$data['thumbnail_url']		= $thumbnail['url'];
		}
		if (!empty($thumbnail['width'])) {
			$data['thumbnail_width']	= $thumbnail['width'];
		} else {
			$data['thumbnail_width']	= '';
		}
		if (!empty($thumbnail['height'])) {
			$data['thumbnail_height']	= $thumbnail['height'];
		} else {
			$data['thumbnail_height']	= '';
		}
	}
	return $data;
}
