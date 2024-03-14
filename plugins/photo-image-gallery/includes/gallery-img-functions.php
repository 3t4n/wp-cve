<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Change hex color more bright or more dark
 * 
 * @param $hex
 * @param $steps
 *
 * @return string
 */
function uxgallery_adjust_brightness($hex, $steps) {
	// Steps should be between -255 and 255. Negative = darker, positive = lighter
	$steps = max(-255, min(255, $steps));

	// Normalize into a six character long hex string
	$hex = str_replace('#', '', $hex);
	if (strlen($hex) == 3) {
		$hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
	}

	// Split into three parts: R, G and B
	$color_parts = str_split($hex, 2);
	$new_color = '';

	foreach ($color_parts as $color) {
		$color   = hexdec($color); // Convert to decimal
		$color   = max(0,min(255,$color + $steps)); // Adjust color
		$new_color .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
	}

	return $new_color;
}

/**
 * Get all general options parameters in a single array
 *
 * @todo: use wp_options instead
 *
 * @return array Array of all general options
 */
function uxgallery_get_general_options()
{
    $gallery_default__params = array(
        'ht_view2_element_linkbutton_text' => 'Explore',
        'ht_view2_element_show_linkbutton' => 'on',
        'ht_view2_element_linkbutton_color' => 'ffffview2ff',
        'ht_view2_element_linkbutton_font_size' => '14',
        'ht_view2_element_linkbutton_background_color' => '#2c2c2c',
        'ht_view2_show_popup_linkbutton' => 'on',
        'ht_view2_popup_linkbutton_text' => 'Explore',
        'ht_view2_popup_linkbutton_background_hover_color' => '3215C2',
        'ht_view2_popup_linkbutton_background_color' => '#2c2c2c',
        'ht_view2_popup_linkbutton_font_hover_color' => 'ffffff',
        'ht_view2_popup_linkbutton_color' => 'ffffff',
        'ht_view2_popup_linkbutton_font_size' => '14',
        'ht_view2_description_color' => '222222',
        'ht_view2_description_font_size' => '14',
        'ht_view2_show_description' => 'on',
        'ht_view2_thumbs_width' => '75',
        'ht_view2_thumbs_height' => '75',
        'ht_view2_thumbs_position' => 'before',
        'ht_view2_show_thumbs' => 'on',
        'ht_view2_popup_background_color' => 'FFFFFF',
        'ht_view2_popup_overlay_color' => '000000',
        'ht_view2_popup_overlay_transparency_color' => '70',
        'ht_view2_popup_closebutton_style' => 'dark',
        'ht_view2_show_separator_lines' => 'on',
        'ht_view2_show_popup_title' => 'on',
        'ht_view2_element_title_font_size' => '18',
        'ht_view2_element_title_font_color' => '222222',
        'ht_view2_popup_title_font_size' => '18',
        'ht_view2_popup_title_font_color' => '222222',
        'ht_view2_element_overlay_color' => 'FFFFFF',
        'ht_view2_element_overlay_transparency' => '70',
        'ht_view2_zoombutton_style' => 'light',
        'ht_view2_element_border_width' => '1',
        'ht_view2_element_border_color' => 'dedede',
        'ht_view2_element_background_color' => 'f9f9f9',
        'ht_view2_element_width' => '275',
        'ht_view2_element_height' => '160',
        'ht_view5_icons_style' => 'dark',
        'ht_view5_show_separator_lines' => 'on',
        'ht_view5_linkbutton_text' => 'Explore',
        'ht_view5_show_linkbutton' => 'on',
        'ht_view5_linkbutton_background_hover_color' => '3215C2',
        'ht_view5_linkbutton_background_color' => '#2c2c2c',
        'ht_view5_linkbutton_font_hover_color' => 'ffffff',
        'ht_view5_linkbutton_color' => 'ffffff',
        'ht_view5_linkbutton_font_size' => '14',
        'ht_view5_description_color' => '555555',
        'ht_view5_description_font_size' => '14',
        'ht_view5_show_description' => 'on',
        'ht_view5_thumbs_width' => '75',
        'ht_view5_thumbs_height' => '75',
        'ht_view5_show_thumbs' => 'on',
        'ht_view5_title_font_size' => '16',
        'ht_view5_title_font_color' => '3215C2',
        'ht_view5_main_image_width' => '275',
        'ht_view5_slider_tabs_font_color' => 'd9d99',
        'ht_view5_slider_tabs_background_color' => '555555',
        'ht_view5_slider_background_color' => 'f9f9f9',
        'ht_view6_title_font_size' => '16',
        'ht_view6_title_font_color' => '3215C2',
        'ht_view6_title_font_hover_color' => '0742df',
        'ht_view6_title_background_color' => '000000',
        'ht_view6_title_background_transparency' => '80',
        'ht_view6_border_radius' => '3',
        'ht_view6_border_width' => '0',
        'ht_view6_border_color' => 'eeeeee',
        'ht_view6_width' => '275',
        'light_box_size' => '17',
        'light_box_width' => '500',
        'light_box_transition' => 'elastic',
        'light_box_speed' => '800',
        'light_box_href' => 'False',
        'light_box_title' => 'false',
        'light_box_scalephotos' => 'true',
        'light_box_rel' => 'false',
        'light_box_scrolling' => 'false',
        'light_box_opacity' => '20',
        'light_box_open' => 'false',
        'light_box_overlayclose' => 'true',
        'light_box_esckey' => 'false',
        'light_box_arrowkey' => 'false',
        'light_box_loop' => 'true',
        'light_box_data' => 'false',
        'light_box_classname' => 'false',
        'light_box_fadeout' => '300',
        'light_box_closebutton' => 'true',
        'light_box_current' => 'image',
        'light_box_previous' => 'previous',
        'light_box_next' => 'next',
        'light_box_close' => 'close',
        'light_box_iframe' => 'false',
        'light_box_inline' => 'false',
        'light_box_html' => 'false',
        'light_box_photo' => 'false',
        'light_box_height' => '500',
        'light_box_innerwidth' => 'false',
        'light_box_innerheight' => 'false',
        'light_box_initialwidth' => '300',
        'light_box_initialheight' => '100',
        'light_box_maxwidth' => '900',
        'light_box_maxheight' => '700',
        'light_box_slideshow' => 'false',
        'light_box_slideshowspeed' => '2500',
        'light_box_slideshowauto' => 'true',
        'light_box_slideshowstart' => 'start slideshow',
        'light_box_slideshowstop' => 'stop slideshow',
        'light_box_fixed' => 'true',
        'light_box_top' => 'false',
        'light_box_bottom' => 'false',
        'light_box_left' => 'false',
        'light_box_right' => 'false',
        'light_box_reposition' => 'false',
        'light_box_retinaimage' => 'true',
        'light_box_retinaurl' => 'false',
        'light_box_retinasuffix' => '@2x.$1',
        'light_box_returnfocus' => 'true',
        'light_box_trapfocus' => 'true',
        'light_box_fastiframe' => 'true',
        'light_box_preloading' => 'true',
        'lightbox_open_position' => '5',
        'light_box_style' => '1',
        'light_box_size_fix' => 'false',
        'slider_crop_image' => 'crop',
        'slider_title_color' => '000000',
        'slider_title_font_size' => '13',
        'slider_description_color' => 'ffffff',
        'slider_description_font_size' => '12',
        'slider_title_position' => 'right-top',
        'slider_description_position' => 'right-bottom',
        'slider_title_border_size' => '0',
        'slider_title_border_color' => 'ffffff',
        'slider_title_border_radius' => '4',
        'slider_description_border_size' => '0',
        'slider_description_border_color' => 'ffffff',
        'slider_description_border_radius' => '0',
        'slider_slideshow_border_size' => '0',
        'slider_slideshow_border_color' => 'ffffff',
        'slider_slideshow_border_radius' => '0',
        'slider_navigation_type' => '1',
        'slider_navigation_position' => 'bottom',
        'slider_title_background_color' => 'ffffff',
        'slider_description_background_color' => '000000',
        'slider_title_transparent' => 'on',
        'slider_description_transparent' => 'on',
        'slider_slider_background_color' => 'ffffff',
        'slider_dots_position' => 'top',
        'slider_active_dot_color' => 'ffffff',
        'slider_dots_color' => '000000',
        'slider_description_width' => '70',
        'slider_description_height' => '50',
        'slider_description_background_transparency' => '70',
        'slider_description_text_align' => 'justify',
        'slider_title_width' => '30',
        'slider_title_height' => '50',
        'slider_title_background_transparency' => '70',
        'slider_title_text_align' => 'right',
        'slider_title_has_margin' => 'off',
        'slider_description_has_margin' => 'off',
        'slider_show_arrows' => 'on',
        'thumb_image_behavior' => 'on',
        'thumb_image_width' => '240',
        'thumb_image_height' => '150',
        'thumb_image_border_width' => '1',
        'thumb_image_border_color' => '444444',
        'thumb_image_border_radius' => '5',
        'thumb_margin_image' => '1',
        'thumb_title_font_size' => '16',
        'thumb_title_font_color' => 'FFFFFF',
        'thumb_title_background_color' => 'CCCCCC',
        'thumb_title_background_transparency' => '80',
        'thumb_box_padding' => '28',
        'thumb_box_background' => '333333',
        'thumb_box_use_shadow' => 'on',
        'thumb_box_has_background' => 'on',
        'thumb_view_text' => 'View Picture',
        'ht_view8_element_cssAnimation' => 'false',
        'ht_view8_element_height' => '120',
        'ht_view8_element_maxheight' => '155',
        'ht_view8_element_show_caption' => 'true',
        'ht_view8_element_padding' => '0',
        'ht_view8_element_border_radius' => '5',
        'ht_view8_icons_style' => 'dark',
        'ht_view8_element_title_font_size' => '13',
        'ht_view8_element_title_font_color' => '3AD6FC',
        'ht_view8_popup_background_color' => '000000',
        'ht_view8_popup_overlay_transparency_color' => '0',
        'ht_view8_popup_closebutton_style' => 'dark',
        'ht_view8_element_title_overlay_transparency' => '90',
        'ht_view8_element_size_fix' => 'false',
        'ht_view8_element_title_background_color' => 'FF1C1C',
        'ht_view8_element_justify' => 'true',
        'ht_view8_element_randomize' => 'false',
        'ht_view8_element_animation_speed' => '2000',
        'ht_view2_content_in_center' => 'off',
        'ht_view6_content_in_center' => 'off',
        'ht_view2_popup_full_width' => 'on',
        'ht_view9_title_fontsize' => '18',
        'ht_view9_title_color' => 'FFFFFF',
        'ht_view9_desc_color' => '000000',
        'ht_view9_desc_fontsize' => '14',
        'ht_view9_element_title_show' => 'true',
        'ht_view9_element_desc_show' => 'true',
        'ht_view9_general_width' => '100',
        'view9_general_position' => 'center',
        'view9_title_textalign' => 'left',
        'view9_desc_textalign' => 'justify',
        'view9_image_position' => '2',
        'ht_view9_title_back_color' => '000000',
        'ht_view9_title_opacity' => '70',
        'ht_view9_desc_opacity' => '100',
        'ht_view9_desc_back_color' => 'FFFFFF',
        'ht_view9_general_space' => '0',
        'ht_view9_general_separator_size' => '0',
        'ht_view9_general_separator_color' => '010457',
        'view9_general_separator_style' => 'dotted',
        'ht_view9_paginator_fontsize' => '22',
        'ht_view9_paginator_color' => '1046B3',
        'ht_view9_paginator_icon_color' => '1046B3',
        'ht_view9_paginator_icon_size' => '18',
        'view9_paginator_position' => 'center',
        'video_view9_loadmore_position' => 'center',
        'video_ht_view9_loadmore_fontsize' => '19',
        'video_ht_view9_button_color' => '5CADFF',
        'loading_type' => '2',
        'video_ht_view9_loadmore_text' => 'Explore',
        'video_ht_view8_paginator_position' => 'center',
        'video_ht_view8_paginator_icon_size' => '18',
        'video_ht_view8_paginator_icon_color' => '26A6FC',
        'video_ht_view8_paginator_color' => '26A6FC',
        'video_ht_view8_paginator_fontsize' => '18',
        'video_ht_view8_loadmore_position' => 'center',
        'video_ht_view8_loadmore_fontsize' => '14',
        'video_ht_view8_button_color' => '26A6FC',
        'video_ht_view8_loadmore_font_color' => 'FF1C1C',
        'video_ht_view8_loading_type' => '3',
        'video_ht_view8_loadmore_text' => 'Explore',
        'video_ht_view7_paginator_fontsize' => '22',
        'video_ht_view7_paginator_color' => '0A0202',
        'video_ht_view7_paginator_icon_color' => '333333',
        'video_ht_view7_paginator_icon_size' => '22',
        'video_ht_view7_paginator_position' => 'center',
        'video_ht_view7_loadmore_position' => 'center',
        'video_ht_view7_loadmore_fontsize' => '19',
        'video_ht_view7_button_color' => '333333',
        'video_ht_view7_loadmore_font_color' => 'CCCCCC',
        'video_ht_view7_loading_type' => '1',
        'video_ht_view7_loadmore_text' => 'Explore',
        'video_ht_view4_paginator_fontsize' => '19',
        'video_ht_view4_paginator_color' => 'FF2C2C',
        'video_ht_view4_paginator_icon_color' => 'B82020',
        'video_ht_view4_paginator_icon_size' => '21',
        'video_ht_view4_paginator_position' => 'center',
        'video_ht_view4_loadmore_position' => 'center',
        'video_ht_view4_loadmore_fontsize' => '16',
        'video_ht_view4_button_color' => '5CADFF',
        'video_ht_view4_loadmore_font_color' => 'FF0D0D',
        'video_ht_view4_loading_type' => '3',
        'video_ht_view4_loadmore_text' => 'Explore',
        'video_ht_view1_paginator_fontsize' => '22',
        'video_ht_view1_paginator_color' => '222222',
        'video_ht_view1_paginator_icon_color' => 'FF2C2C',
        'video_ht_view1_paginator_icon_size' => '22',
        'video_ht_view1_paginator_position' => 'left',
        'video_ht_view1_loadmore_position' => 'center',
        'video_ht_view1_loadmore_fontsize' => '22',
        'video_ht_view1_button_color' => 'FF2C2C',
        'video_ht_view1_loadmore_font_color' => 'FFFFFF',
        'video_ht_view1_loading_type' => '2',
        'video_ht_view1_loadmore_text' => 'Load More',
        'video_ht_view9_loadmore_font_color_hover' => 'D9D9D9',
        'video_ht_view9_button_color_hover' => '8F827C',
        'video_ht_view8_loadmore_font_color_hover' => 'FF4242',
        'video_ht_view8_button_color_hover' => '0FEFFF',
        'video_ht_view7_loadmore_font_color_hover' => 'D9D9D9',
        'video_ht_view7_button_color_hover' => '8F827C',
        'video_ht_view4_loadmore_font_color_hover' => 'FF4040',
        'video_ht_view4_button_color_hover' => '99C5FF',
        'video_ht_view1_loadmore_font_color_hover' => 'F2F2F2',
        'video_ht_view1_button_color_hover' => '991A1A',
        'image_natural_size_thumbnail' => 'resize',
        'image_natural_size_contentpopup' => 'resize',
        'ht_popup_rating_count' => 'on',
        'ht_popup_likedislike_bg' => '7993A3',
        'ht_contentsl_rating_count' => 'on',
        'ht_popup_likedislike_bg_trans' => '0',
        'ht_popup_likedislike_thumb_color' => '2EC7E6',
        'ht_popup_likedislike_thumb_active_color' => '2883C9',
        'ht_popup_likedislike_font_color' => '454545',
        'ht_popup_active_font_color' => '000000',
        'ht_contentsl_likedislike_bg' => '7993A3',
        'ht_contentsl_likedislike_bg_trans' => '0',
        'ht_contentsl_likedislike_thumb_color' => '2EC7E6',
        'ht_contentsl_likedislike_thumb_active_color' => '2883C9',
        'ht_contentsl_likedislike_font_color' => '454545',
        'ht_contentsl_active_font_color' => '1C1C1C',
        'ht_lightbox_rating_count' => 'on',
        'ht_lightbox_likedislike_bg' => 'FFFFFF',
        'ht_lightbox_likedislike_bg_trans' => '20',
        'ht_lightbox_likedislike_thumb_color' => '7A7A7A',
        'ht_lightbox_likedislike_thumb_active_color' => 'E83D09',
        'ht_lightbox_likedislike_font_color' => 'FFFFFF',
        'ht_lightbox_active_font_color' => 'FFFFFF',
        'ht_slider_rating_count' => 'on',
        'ht_slider_likedislike_bg' => 'FFFFFF',
        'ht_slider_likedislike_bg_trans' => '70',
        'ht_slider_likedislike_thumb_color' => '000000',
        'ht_slider_likedislike_thumb_active_color' => 'FF3D3D',
        'ht_slider_likedislike_font_color' => '3D3D3D',
        'ht_slider_active_font_color' => '1C1C1C',
        'ht_thumb_rating_count' => 'on',
        'ht_thumb_likedislike_bg' => '63150C',
        'ht_thumb_likedislike_bg_trans' => '0',
        'ht_thumb_likedislike_thumb_color' => 'F7F7F7',
        'ht_thumb_likedislike_thumb_active_color' => 'E65010',
        'ht_thumb_likedislike_font_color' => 'E6E6E6',
        'ht_thumb_active_font_color' => 'FFFFFF',
        'ht_just_rating_count' => 'off',
        'ht_just_likedislike_bg' => 'FFFFFF',
        'ht_just_likedislike_bg_trans' => '0',
        'ht_just_likedislike_thumb_color' => 'FFFFFF',
        'ht_just_likedislike_thumb_active_color' => '0ECC5A',
        'ht_just_likedislike_font_color' => '030303',
        'ht_just_active_font_color' => 'EDEDED',
        'ht_blog_rating_count' => 'on',
        'ht_blog_likedislike_bg' => '0B0B63',
        'ht_blog_likedislike_bg_trans' => '0',
        'ht_blog_likedislike_thumb_color' => '8F827C',
        'ht_blog_likedislike_thumb_active_color' => '5CADFF',
        'ht_blog_likedislike_font_color' => '4D4B49',
        'ht_blog_active_font_color' => '020300',
        'ht_popup_heart_likedislike_thumb_color' => '4B5FE3',
        'ht_popup_heart_likedislike_thumb_active_color' => '3215C2',
        'ht_contentsl_heart_likedislike_thumb_color' => '4B5FE3',
        'ht_contentsl_heart_likedislike_thumb_active_color' => '3215C2',
        'ht_lightbox_heart_likedislike_thumb_color' => 'B50000',
        'ht_lightbox_heart_likedislike_thumb_active_color' => 'EB1221',
        'ht_slider_heart_likedislike_thumb_color' => '8F8F8F',
        'ht_slider_heart_likedislike_thumb_active_color' => 'FF2A12',
        'ht_thumb_heart_likedislike_thumb_color' => 'CC2525',
        'ht_thumb_heart_likedislike_thumb_active_color' => 'C21313',
        'ht_just_heart_likedislike_thumb_color' => 'E0E0E0',
        'ht_just_heart_likedislike_thumb_active_color' => 'F23D3D',
        'ht_blog_heart_likedislike_thumb_color' => 'D63E48',
        'ht_blog_heart_likedislike_thumb_active_color' => 'E00000',
        'uxgallery_admin_image_hover_preview' => 'on',
        'uxgallery_ht_view10_image_behaviour' => 'crop',
        'uxgallery_ht_view10_element_width' => '200',
        'uxgallery_ht_view10_element_height' => '180',
        'uxgallery_ht_view10_element_margin' => '10',
        'uxgallery_ht_view10_element_border_width' => '0',
        'uxgallery_ht_view10_element_border_color' => 'DEDEDE',
        'uxgallery_ht_view10_element_overlay_background_color_' => '1AD9C6',
        'uxgallery_ht_view10_element_overlay_opacity' => '50',
        'uxgallery_ht_view10_element_hover_effect' => 'true',
        'uxgallery_ht_view10_hover_effect_delay' => '0',
        'uxgallery_ht_view10_hover_effect_inverse' => 'false',
        'uxgallery_ht_view10_expanding_speed' => '500',
        'uxgallery_ht_view10_expand_block_height' => '500',
        'uxgallery_ht_view10_element_title_font_size' => '16',
        'uxgallery_ht_view10_element_title_font_color' => 'FFFFFF',
        'uxgallery_ht_view10_element_title_align' => 'center',
        'uxgallery_ht_view10_element_title_border_width' => '1',
        'uxgallery_ht_view10_element_title_border_color' => 'FFFFFF',
        'uxgallery_ht_view10_element_title_margin_top' => '40',
        'uxgallery_ht_view10_element_title_padding_top_bottom' => '10',
        'uxgallery_ht_view10_expand_block_background_color' => '222222',
        'uxgallery_ht_view10_expand_block_opacity' => '100',
        'uxgallery_ht_view10_expand_block_title_color' => '444444',
        'uxgallery_ht_view10_expand_block_title_font_size' => '35',
        'uxgallery_ht_view10_expand_block_description_font_size' => '16',
        'uxgallery_ht_view10_expand_block_description_font_color' => '444444',
        'uxgallery_ht_view10_expand_block_description_font_hover_color' => 'ddd',
        'uxgallery_ht_view10_expand_block_description_text_align' => 'left',
        'uxgallery_ht_view10_expand_block_button_background_color' => '454545',
        'uxgallery_ht_view10_expand_block_button_background_hover_color' => '454545',
        'uxgallery_ht_view10_expand_block_button_text_color' => '9f9f9f',
        'uxgallery_ht_view10_expand_block_button_font_size' => '11',
        'uxgallery_ht_view10_expand_block_button_text' => 'Explore',
        'uxgallery_ht_view10_show_center' => 'on',
        'uxgallery_ht_view10_expand_width' => '100',
        'uxgallery_ht_view10_paginator_fontsize' => '22',
        'uxgallery_ht_view10_paginator_color' => '1046B3',
        'uxgallery_ht_view10_paginator_icon_color' => '1046B3',
        'uxgallery_ht_view10_paginator_icon_size' => '18',
        'uxgallery_ht_view10_paginator_position' => 'center',
        'uxgallery_lightbox_slideAnimationType' => 'effect_1',
        'uxgallery_lightbox_lightboxView' => 'view1',
        'uxgallery_lightbox_speed_new' => '600',
        'uxgallery_lightbox_width_new' => '100',
        'uxgallery_lightbox_height_new' => '100',
        'uxgallery_lightbox_videoMaxWidth' => '790',
        'uxgallery_lightbox_overlayDuration' => '150',
        'uxgallery_lightbox_overlayClose_new' => 'true',
        'uxgallery_lightbox_loop_new' => 'true',
        'uxgallery_lightbox_escKey_new' => 'true',
        'uxgallery_lightbox_keyPress_new' => 'true',
        'uxgallery_lightbox_arrows' => 'true',
        'uxgallery_lightbox_mouseWheel' => 'true',
        'uxgallery_lightbox_download' => 'false',
        'uxgallery_lightbox_showCounter' => 'true',
        'uxgallery_lightbox_nextHtml' => '',     //not used
        'uxgallery_lightbox_prevHtml' => '',     //not used
        'uxgallery_lightbox_sequence_info' => 'image',
        'uxgallery_lightbox_sequenceInfo' => 'of',
        'uxgallery_lightbox_slideshow_new' => 'true',
        'uxgallery_lightbox_slideshow_auto_new' => 'false',
        'uxgallery_lightbox_slideshow_speed_new' => '2500',
        'uxgallery_lightbox_slideshow_start_new' => '',     //not used
        'uxgallery_lightbox_slideshow_stop_new' => '',     //not used
        'uxgallery_lightbox_watermark' => 'false',
        'uxgallery_lightbox_socialSharing' => 'true',
        'uxgallery_lightbox_facebookButton' => 'true',
        'uxgallery_lightbox_twitterButton' => 'true',
        'uxgallery_lightbox_googleplusButton' => 'true',
        'uxgallery_lightbox_pinterestButton' => 'false',
        'uxgallery_lightbox_linkedinButton' => 'false',
        'uxgallery_lightbox_tumblrButton' => 'false',
        'uxgallery_lightbox_redditButton' => 'false',
        'uxgallery_lightbox_bufferButton' => 'false',
        'uxgallery_lightbox_diggButton' => 'false',
        'uxgallery_lightbox_vkButton' => 'false',
        'uxgallery_lightbox_yummlyButton' => 'false',
        'uxgallery_lightbox_watermark_text' => 'Watermark',
        'uxgallery_lightbox_watermark_textColor' => 'ffffff',
        'uxgallery_lightbox_watermark_textFontSize' => '30',
        'uxgallery_lightbox_watermark_containerBackground' => '000000',
        'uxgallery_lightbox_watermark_containerOpacity' => '90',
        'uxgallery_lightbox_watermark_containerWidth' => '300',
        'uxgallery_lightbox_watermark_position_new' => '9',
        'uxgallery_lightbox_watermark_opacity' => '70',
        'uxgallery_lightbox_watermark_margin' => '10',
        'uxgallery_lightbox_watermark_img_src_new' => UXGALLERY_IMAGES_URL . '/admin_images/No-image-found.jpg',
        'uxgallery_lightbox_type' => 'new_type',

        //ns code start here

        "uxgallery_ht_album_view_style" => '2',
        "uxgallery_ht_album_view_sorting" => '2',
        "uxgallery_ht_album_onhover_effects" => '0',
        "uxgallery_ht_album_image_scale_color" => 'FFFFFF',
        "uxgallery_ht_album_image_scale_opacity" => '80',
        "uxgallery_ht_album_image_scale_text_color" => '333333',
        "uxgallery_ht_album_image_bottom_hover_color" => '333333',
        "uxgallery_ht_album_image_bottom_hover_text_color" => 'FFFFFF',
        "uxgallery_ht_album_popup_view_sorting" => '2',
        "uxgallery_ht_album_popup_onhover_effects" => '0',
        "uxgallery_ht_album_popup_image_scale_color" => 'FFFFFF',
        "uxgallery_ht_album_popup_image_scale_opacity" => '80',
        "uxgallery_ht_album_popup_image_scale_text_color" => '333333',
        "uxgallery_ht_album_popup_image_bottom_hover_color" => '333333',
        "uxgallery_ht_album_popup_image_bottom_hover_text_color" => 'FFFFFF',
        "uxgallery_ht_album_show_image_count" => 'true',
        "uxgallery_ht_album_popup_show_image_count" => 'true',
        "uxgallery_ht_album_count_style" => '0',
        "uxgallery_ht_album_popup_count_style" => '0',
        "uxgallery_ht_album_category_style" => '0',
        "uxgallery_ht_album_popup_category_style" => '0',
        "uxgallery_ht_album_grid_style" => '1',
        "uxgallery_ht_album_thumbnail_width_in_px" => '220',
        "uxgallery_ht_album_thumbnail_height_in_px" => '170',
        "uxgallery_ht_album_thumbnail_background" => 'e3e3e3',
        "uxgallery_ht_album_thumbnail_image_border_width" => '1',
        "uxgallery_ht_album_thumbnail_image_border_color" => '333333',
        "uxgallery_ht_album_thumbnail_image_border_radius" => '0',
        "uxgallery_ht_album_mosaic_image_column_count" => '2',
        "uxgallery_ht_album_mosaic_image_margin_bottom_in_px" => '0',
        "uxgallery_ht_album_mosaic_image_margin_right_in_px" => '0',
        "uxgallery_ht_album_mosaic_image_border_width_in_px" => '0',
        "uxgallery_ht_album_popup_image_border_color" => '333333',
        "uxgallery_ht_album_image_border_color" => '336699',
        "uxgallery_ht_album_mosaic_image_border_radius" => '0',
        "uxgallery_ht_album_show_title" => 'true',
        "uxgallery_ht_album_show_description" => 'true',
        "uxgallery_ht_album_show_sharing_buttons" => 'true',
        "uxgallery_ht_album_popup_grid_style" => '1',
        "uxgallery_ht_album_popup_thumbnail_width_in_px" => '220',
        "uxgallery_ht_album_popup_thumbnail_height_in_px" => '170',
        "uxgallery_ht_album_popup_thumbnail_background" => '333333',
        "uxgallery_ht_album_popup_thumbnail_image_border_width" => '1',
        "uxgallery_ht_album_popup_thumbnail_image_border_color" => '333333',
        "uxgallery_ht_album_popup_thumbnail_image_border_radius" => '0',
        "uxgallery_ht_album_popup_mosaic_image_column_count" => '2',
        "uxgallery_ht_album_popup_mosaic_image_margin_bottom_in_px" => '0',
        "uxgallery_ht_album_popup_mosaic_image_margin_right_in_px" => '0',
        "uxgallery_ht_album_popup_mosaic_image_border_width_in_px" => '0',
        "uxgallery_ht_album_popup_mosaic_image_border_color" => '333333',
        "uxgallery_ht_album_popup_mosaic_image_border_radius" => '0',
        "uxgallery_ht_album_popup_show_title" => 'true',
        "uxgallery_ht_album_popup_show_description" => 'true',
        "uxgallery_ht_album_popup_show_sharing_buttons" => 'true',
        "uxgallery_ht_album_popup_window_thumbnails" => 'true',
        "uxgallery_ht_album_popup_window_controls" => 'true',
        "uxgallery_ht_album_popup_window_controls_on_top" => 'true',

        // album options for all types

        // popup

        "uxgallery_ht_view2_element_width" => '250',
        "uxgallery_album_popup_onhover_effects" => '1',
        "uxgallery_album_popup_dark_text_color" => 'FFFFFF',
        "uxgallery_album_popup_blur_text_color" => '333333',
        "uxgallery_album_popup_scale_color" => 'CCCCCC',
        "uxgallery_album_popup_scale_opacity" => '80',
        "uxgallery_album_popup_scale_text_color" => '333333',
        "uxgallery_album_popup_bottom_color" => '333333',
        "uxgallery_album_popup_bottom_text_color" => 'FFFFFF',
        "uxgallery_album_popup_elastic_text_color" => '333333',

        "uxgallery_album_popup_show_title" => 'on',
        "uxgallery_album_popup_show_image_count_2" => 'on',
        "uxgallery_album_popup_show_description" => 'on',
        "uxgallery_album_popup_window_thumbnails" => 'on',
        "uxgallery_album_popup_window_controls" => 'on',
        "uxgallery_album_popup_window_controls_on_top" => 'on',

        "uxgallery_album_popup_count_style" => '3',
        "uxgallery_album_popup_category_style" => '0',


        // lightbox
        "uxgallery_ht_view6_width" => '250',
        "uxgallery_album_lightbox_onhover_effects" => '1',
        "uxgallery_album_lightbox_dark_text_color" => 'FFFFFF',
        "uxgallery_album_lightbox_blur_text_color" => '333333',
        "uxgallery_album_lightbox_scale_color" => 'CCCCCC',
        "uxgallery_album_lightbox_scale_opacity" => '80',
        "uxgallery_album_lightbox_scale_text_color" => '333333',
        "uxgallery_album_lightbox_bottom_color" => '333333',
        "uxgallery_album_lightbox_bottom_text_color" => 'FFFFFF',
        "uxgallery_album_lightbox_elastic_text_color" => '333333',

        "uxgallery_album_lightbox_show_title" => 'on',
        "uxgallery_album_lightbox_show_image_count_2" => 'on',
        "uxgallery_album_lightbox_show_description" => 'on',

        "uxgallery_album_lightbox_count_style" => '3',
        "uxgallery_album_lightbox_category_style" => '0',


        // thumbnail
        "uxgallery_thumb_image_width" => '250',
        "uxgallery_album_thumbnail_onhover_effects" => '1',
        "uxgallery_album_thumbnail_dark_text_color" => 'FFFFFF',
        "uxgallery_album_thumbnail_blur_text_color" => '333333',
        "uxgallery_album_thumbnail_scale_color" => 'CCCCCC',
        "uxgallery_album_thumbnail_scale_opacity" => '80',
        "uxgallery_album_thumbnail_scale_text_color" => '333333',
        "uxgallery_album_thumbnail_bottom_color" => '333333',
        "uxgallery_album_thumbnail_bottom_text_color" => 'FFFFFF',
        "uxgallery_album_thumbnail_elastic_text_color" => '333333',

        "uxgallery_album_thumbnail_show_title" => 'on',
        "uxgallery_album_thumbnail_show_image_count_2" => 'on',
        "uxgallery_album_thumbnail_show_description" => 'on',

        "uxgallery_album_thumbnail_count_style" => '3',
        "uxgallery_album_thumbnail_category_style" => '0',

    );

	return $gallery_default__params;
}
/**
 * Get all general options parameters in a single array
 *
 * @return array Array of all general options
 */
function uxgallery_get_option() {
	$new_options            = array(
		'uxgallery_admin_image_hover_preview' => 'on',
		'uxgallery_light_box_size_fix'        => 'false',
		'uxgallery_light_box_width'           => '500',
		'uxgallery_light_box_height'          => '500',
		'uxgallery_light_box_maxwidth'        => '900',
		'uxgallery_light_box_maxheight'       => '700',
		'uxgallery_light_box_initialwidth'    => '300',
		'uxgallery_light_box_initialheight'   => '100',
		'uxgallery_version'                   => '2.0.2'
	);
	$uxgallery_get_option = array();
	foreach ( $new_options as $name => $new_option ) {
		$uxgallery_get_option[ $name ] = get_option( $name );
	}

	return $uxgallery_get_option;
}
function uxgallery_get_view_slag_by_id( $id ) {
	global $wpdb;
	$query = $wpdb->prepare( "SELECT ux_sl_effects from " . $wpdb->prefix . "ux_gallery_gallerys WHERE id=%d", $id );
	$view  = $wpdb->get_var( $query );
	switch ( $view ) {
		case 0:
			$slug = 'content-popup';
			break;
		case 1:
			$slug = 'content-slider';
			break;
		case 3:
			$slug = 'slider';
			break;
		case 4:
			$slug = 'thumbnails';
			break;
		case 5:
			$slug = 'lightbox-gallery';
			break;
		case 6:
			$slug = 'justified';
			break;
		case 7:
			$slug = 'blog-style-gallery';
			break;
		case 10:
			$slug = 'elastic-grid';
			break;
	}

	return $slug;
}

/**
 * Get attachment ID by image src
 *
 * @param $image_url
 *
 * @return mixed
 */
function uxgallery_get_image_id( $image_url ) {
	global $wpdb;
	$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM " . $wpdb->prefix . "posts WHERE guid='%s';", $image_url ) );
	if ( $attachment ) {
		return $attachment[0];
	}
}

/**
 * * Get image url by image src, width, height
 *
 * @param $image_src
 * @param $image_sizes
 * @param $is_thumbnail
 *
 * @return false|string
 */
function uxgallery_get_image_by_sizes_and_src( $image_src, $image_sizes, $is_thumbnail ) {
	$is_attachment = uxgallery_get_image_id( $image_src );
	$is_readable   = is_readable( $image_src );
	if ( $is_readable ) {
		$img_sizes  = getimagesize( $image_src );
		$img_height = $img_sizes[1];
	} else {
		$img_height = null;
	}

	if ( is_string( $image_sizes ) ) {
		$image_sizes = $image_sizes;
	}
	if ( is_object( $image_sizes ) ) {
		$image_sizes = array( $image_sizes, '' );
	}
	if ( ! $is_attachment ) {
		$image_url = $image_src;
	} else {
		$attachment_id     = uxgallery_get_image_id( $image_src );
		$natural_img_width = explode( ',', wp_get_attachment_image_sizes( $attachment_id, 'full' ) );
		$natural_img_width = $natural_img_width[1];
		$natural_img_width = str_replace( ' ', '', $natural_img_width );
		$natural_img_width = intval( str_replace( 'px', '', $natural_img_width ) );
		if ( $is_thumbnail ) {
			$image_url = wp_get_attachment_image_url( $attachment_id, 'thumbnail' );
		} elseif ( $image_sizes[0] <= 300 || $image_sizes[0] == '' ) {
			if ( $img_height == null || $img_height >= $natural_img_width ) {
				$image_url = wp_get_attachment_image_url( $attachment_id, 'large' );
			} else {
				$image_url = wp_get_attachment_image_url( $attachment_id, 'medium' );
			}
		} elseif ( $image_sizes[0] <= 700 ) {
			$image_url = wp_get_attachment_image_url( $attachment_id, 'large' );
		} elseif ( $image_sizes[0] >= $natural_img_width ) {
			$image_url = wp_get_attachment_image_url( $attachment_id, 'full' );
		} else {
			$image_url = wp_get_attachment_image_url( $attachment_id, $image_sizes );
		}
	}

	return $image_url;
}

/**
 * Get User IP
 * @return mixed
 */
function uxgallery_get_ip() {
	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ux_ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ux_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ux_ip = $_SERVER['REMOTE_ADDR'];
	}

	return $ux_ip;
}