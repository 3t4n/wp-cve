<?php

defined( 'ABSPATH' ) or die(':)');


function alobaidi_video_popup_shortcode($atts){
	if( !empty($atts["url"]) ){
		$url = esc_url($atts["url"]);
	}else{
		$url = null;
	}

	if( !empty($atts["text"]) ){
		$text = esc_html($atts["text"]); // Escape html tags.
	}else{
		if( !empty($atts["url"]) ){
			$text = esc_url($atts["url"]);
		}else{
			$text = null;
		}
	}

	/* No need to sanitize this attributes, because the user cannot control the output:
	*/
	if( !empty($atts["auto"]) ){ // No need to sanitize
		if( strtolower($atts["auto"]) == 'no' or $atts["auto"] == "1"){
			$auto = "vp-s"; // This is forced output.
		}else{
			$auto = "vp-a"; // This is forced output.
		}
	}else{
		$auto = "vp-a"; // This is forced output.
	}


	if( !empty($atts["p"]) ){ // No need to sanitize
		$p_before = '<p>'; // This is forced output.
		$p_after = '</p>'; // This is forced output.
	}else{
		$p_before = null;
		$p_after = null;
	}

	if( !empty($atts["n"]) ){ // No need to sanitize
		$nofollow = ' rel="nofollow"'; // This is forced output.
	}else{
		$nofollow = null;
	}

	$filter1 = null;

	$filter2 = apply_filters('wpt_video_popup_shortcode_filter', 0);

	if($filter2 == 1){
		if( !empty($atts["rel"]) ){
			$rel = 1; // This is forced output.
		}else{
			$rel = 0;
		}

		$filter1 = apply_filters('wpt_video_popup_attr_filter', $rel);
	}

	if( !empty($atts["url"]) ){
		$parse = parse_url($atts["url"]);
		if( strtolower($parse['host']) == 'soundcloud.com' ){
			if( $auto == 'vp-a' ){
				$sc_auto = '&vp_soundcloud_a=true';
			}else{
				$sc_auto = '&vp_soundcloud_a=false';
			}
			$embed_sc_url = home_url("/?vp_soundcloud=") . esc_url($atts["url"]) . $sc_auto;
			$data_sc = ' data-soundcloud="1" data-soundcloud-url="'.esc_url($atts["url"]).'" data-embedsc="'.esc_url($embed_sc_url).'"'; // This is forced output.
			$url = '#';
		}else{
			$data_sc = null;
		}
	}else{
		$data_sc = null;
	}

	if( !empty($atts['wrap']) ){
		if( strtolower($atts['wrap']) == 'no' or $atts['wrap'] == '1' ){
			$dis_wrap = ' data-dwrap="1"'; // This is forced output.
		}else{
			$dis_wrap = null;
		}
	}else{
		$dis_wrap = null;
	}

	if( !empty($atts['rv']) and $atts['rv'] == '1' ){
		$rv_class = ' vp-dr'; // This is forced output.
	}else{
		$rv_class = null;
	}

	$NumbersRegex = '/^[0-9]+(%|px)?$/'; // With allow px and % mark only, for w="" and h="" atts.

	if( !empty($atts["w"]) ){
		if (preg_match($NumbersRegex, $atts["w"])) { // Check if the value is numbers (with allow % mark and px):
			$width_attr = ' data-width="'.esc_attr($atts["w"]).'"'; // The output will be such as data-width="NUMBERS"
		}else{
			$width_attr = null; // If the value is not a numbers.
		}
	}else{
		$width_attr = null;
	}

	if( !empty($atts["h"]) ){
		if (preg_match($NumbersRegex, $atts["h"])) { // Check if the value is numbers (with allow % mark and px):
			$height_attr = ' data-height="'.esc_attr($atts["h"]).'"'; // The output will be such as data-height="NUMBERS"
		}else{
			$height_attr = null; // If the value is not a numbers.
		}
	}else{
		$height_attr = null;
	}

	if( !empty($atts["title"]) ){
		$get_title = esc_html($atts["title"]); // Escape html tags.
		$title_attr = ' title="'.esc_attr($get_title).'"'; // This is a title for <a></a>, so that we use esc_attr() function.
	}else{
		$title_attr = null;
	}

	// Sanitize co="" attr:
	if( !empty($atts["co"]) ){
		$pattern = '/^#[0-9a-fA-F]{6}$/'; // HEX Pattern
		if ( preg_match($pattern, $atts["co"]) ) {
			$overlay_color_attr = ' data-overlay="'.esc_attr($atts["co"]).'"'; // The output will be such as data-overlay="#XXXXXX" (forced).
		}else{
			$overlay_color_attr = null; // If $atts["co"] is not a HEX Code, will return null, it's ok.
		}
	}else{
		$overlay_color_attr = null;
	}

	if( !empty($atts["dc"]) ){
		$dis_controls_attr = ' data-controls="1"'; // This is forced output.
	}else{
		$dis_controls_attr = null;
	}

	if( !empty($atts["di"]) ){
		$dis_info_attr = ' data-info="1"'; // This is forced output.
	}else{
		$dis_info_attr = null;
	}

	if( !empty($atts["iv"]) ){
		$dis_iv_attr = ' data-iv="1"'; // This is forced output.
	}else{
		$dis_iv_attr = null;
	}

	if( !empty($atts["img"]) ){
		$image_url = esc_url($atts["img"]);
		$text = null;
		$text = '<img class="vp-img" src="'.esc_attr($image_url).'">';
	}

	$media = '<a'.$filter1.' class="'.$auto.$rv_class.'" href="'.$url.'"'.$title_attr.$nofollow.$width_attr.$height_attr.$data_sc.$dis_wrap.$overlay_color_attr.$dis_controls_attr.$dis_info_attr.$dis_iv_attr.'>'.$text.'</a>';

	return $p_before.$media.$p_after;
}
add_shortcode('video_popup', 'alobaidi_video_popup_shortcode');