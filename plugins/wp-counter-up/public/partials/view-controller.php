<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://logichunt.com
 * @since      1.0.0
 *
 * @package    Wp_Counter_Up
 * @subpackage Wp_Counter_Up/public/partials
 */

if( (LGX_WCU_PLUGIN_BASE == 'wp-counter-up/wp-counter-up.php') && (LGX_WCU_PLUGIN_META_FIELD_PRO == 'enabled') ) {
	die('<p style="color: red;">Please buy a pro version of this plugin.</p>');
}


//echo '<pre>';print_r($lgx_generator_meta); echo '</pre>';

$lgx_app_id                 = $atts['id'];
$lgx_showcase_type          = (isset($lgx_generator_meta['lgx_counter_showcase_type']) ? $lgx_generator_meta['lgx_counter_showcase_type'] : 'grid');

$lgx_layout_order              = $lgx_generator_meta['lgx_item_content_order'];

// Responsive
$lgx_large_desktop_item   = intval($lgx_generator_meta['lgx_large_desktop_item']);
$lgx_desktop_item         = intval($lgx_generator_meta['lgx_desktop_item']);
$lgx_tablet_item          = intval( $lgx_generator_meta['lgx_tablet_item']);
$lgx_mobile_item          = intval($lgx_generator_meta['lgx_mobile_item']);

//New

$lgx_icon_padding                = (isset($lgx_generator_meta['lgx_icon_padding']) ? $lgx_generator_meta['lgx_icon_padding'] : '0px');

$lgx_value_width                = (isset($lgx_generator_meta['lgx_value_width']) ? $lgx_generator_meta['lgx_value_width'] : 'auto');
$lgx_value_height               = (isset($lgx_generator_meta['lgx_value_height']) ? $lgx_generator_meta['lgx_value_height'] : 'auto');

$lgx_value_border_color_en      = (isset($lgx_generator_meta['lgx_value_border_color_en']) ? $lgx_generator_meta['lgx_value_border_color_en'] : 'no');

$lgx_value_border_color         = (isset($lgx_generator_meta['lgx_value_border_color']) ? $lgx_generator_meta['lgx_value_border_color'] : '#F9f9f9');
$lgx_value_border_color_hover   = (isset($lgx_generator_meta['lgx_value_border_color_hover']) ? $lgx_generator_meta['lgx_value_border_color_hover'] : '#F9f9f9');
$lgx_value_border_width         = (isset($lgx_generator_meta['lgx_value_border_width']) ? $lgx_generator_meta['lgx_value_border_width'] : '1px');
$lgx_value_border_radius        = (isset($lgx_generator_meta['lgx_value_border_radius']) ? $lgx_generator_meta['lgx_value_border_radius'] : '100px');

$lgx_item_floating                = (isset($lgx_generator_meta['lgx_item_floating']) ? $lgx_generator_meta['lgx_item_floating'] : 'none');



//Section Background Settings
$lgx_section_width              = $lgx_generator_meta['lgx_section_width'];
$lgx_section_container          = $lgx_generator_meta['lgx_section_container'];
$lgx_section_bg_img_en          = $lgx_generator_meta['lgx_section_bg_img_en'];
$lgx_section_bg_img             = $lgx_generator_meta['lgx_section_bg_img'];
$lgx_section_bg_img_attachment  = $lgx_generator_meta['lgx_section_bg_img_attachment'];
$lgx_section_bg_img_size        = $lgx_generator_meta['lgx_section_bg_img_size'];
$lgx_section_bg_color_en        = $lgx_generator_meta['lgx_section_bg_color_en'];
$lgx_section_bg_color           = $lgx_generator_meta['lgx_section_bg_color'];
$lgx_section_top_margin         = $lgx_generator_meta['lgx_section_top_margin'];
$lgx_section_bottom_margin      = $lgx_generator_meta['lgx_section_bottom_margin'];
$lgx_section_top_padding        = $lgx_generator_meta['lgx_section_top_padding'];
$lgx_section_bottom_padding     = $lgx_generator_meta['lgx_section_bottom_padding'];

//echo '<pre>';
//print_r($lgx_generator_meta['lgx_section_bg_img']);
//echo '</pre>';

//Header Settings
$lgx_header_title_font_size         = $lgx_generator_meta['lgx_header_title_font_size'];
$lgx_header_title_color             = $lgx_generator_meta['lgx_header_title_color'];
$lgx_header_title_font_weight       = $lgx_generator_meta['lgx_header_title_font_weight'];
$lgx_header_title_bottom_margin     = $lgx_generator_meta['lgx_header_title_bottom_margin'];
$lgx_header_subtitle_font_size      = $lgx_generator_meta['lgx_header_subtitle_font_size'];
$lgx_header_subtitle_color          = $lgx_generator_meta['lgx_header_subtitle_color'];
$lgx_header_subtitle_font_weight    = $lgx_generator_meta['lgx_header_subtitle_font_weight'];
$lgx_header_subtitle_bottom_margin  = $lgx_generator_meta['lgx_header_subtitle_bottom_margin'];



/**
 *
 * Global Style Declaration
 *
 */

include 'dynamic-style/loader-pre-style.php';

wp_enqueue_style('lgx-counter-up-style');

wp_enqueue_script('lgx-waypoints_v2');
wp_enqueue_script('lgx-milestone_v2');
wp_enqueue_script('lgx-counter-script');



/**
 *
 * Plugin view
 *
 */


if ( 'grid' == $lgx_showcase_type ) {

    include 'dynamic-style/grid-style.php';
    include 'template/view-default.php';

} elseif ( 'flexbox' == $lgx_showcase_type ) {
//
    include 'dynamic-style/flexbox-style.php';
    include 'template/view-default.php';

} 

/**
 *  Load Dynamic Style 
 */

include 'dynamic-style/general-style.php';
include 'dynamic-style/pro-style.php';

if( (LGX_LS_PLUGIN_BASE == 'wp-counter-up-pro/wp-counter-up-pro.php') ) {   
    include 'dynamic-style/pro-style-pro.php';
}
