<?php
// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// variables
$vswc_today = current_datetime()->getTimestamp();
$vswc_day = wp_date('w', $vswc_today);
$vswc_preview = get_option( 'vswc-setting-1' );
$vswc_exclude_admin = get_option( 'vswc-setting-15' );
$vswc_monday = get_option( 'vswc-setting-2' );
$vswc_tuesday = get_option( 'vswc-setting-3' );
$vswc_wednesday = get_option( 'vswc-setting-4' );
$vswc_thursday = get_option( 'vswc-setting-5' );
$vswc_friday = get_option( 'vswc-setting-6' );
$vswc_saturday = get_option( 'vswc-setting-7' );
$vswc_sunday = get_option( 'vswc-setting-8' );
$vswc_page_title = get_option( 'vswc-setting-22' );
$vswc_content_title = get_option( 'vswc-setting-13' );
$vswc_content = get_option( 'vswc-setting-14' );
$vswc_preview_notice = __( 'Preview mode', 'very-simple-website-closed' );
$vswc_background_color = empty( get_option( 'vswc-setting-9' ) ) ? '#fff' : get_option( 'vswc-setting-9' );
$vswc_background_image_id = get_option( 'vswc-setting-23' );
$vswc_background_image = wp_get_attachment_image_src($vswc_background_image_id, 'full');
$vswc_color_title = empty( get_option( 'vswc-setting-20' ) ) ? '#333' : get_option( 'vswc-setting-20' );
$vswc_color = empty( get_option( 'vswc-setting-10' ) ) ? '#333' : get_option( 'vswc-setting-10' );
$vswc_align = empty( get_option( 'vswc-setting-11' ) ) ? 'left' : get_option( 'vswc-setting-11' );
$vswc_font_size_title = get_option( 'vswc-setting-18' );
$vswc_font_size = get_option( 'vswc-setting-19' );
$vswc_logo_image_id = get_option( 'vswc-setting-12' );
$vswc_logo_image = wp_get_attachment_image_src($vswc_logo_image_id, 'full');
$vswc_logo_image_width = get_option( 'vswc-setting-17' );
$vswc_logo_image_alt = get_bloginfo( 'name' );
$vswc_custom_css = get_option( 'vswc-setting-21' );

if(!empty($vswc_background_image)) {
	$vswc_background_img = 'background-image:url('.$vswc_background_image[0].');background-repeat:no-repeat;background-position:center;background-size:cover;';
} else {
	$vswc_background_img = '';
}
if ( empty($vswc_font_size_title) || !is_numeric($vswc_font_size_title) ) {
	$vswc_font_size_title = 32;
}
if ( empty($vswc_font_size) || !is_numeric($vswc_font_size) ) {
	$vswc_font_size = 16;
}
if ( empty($vswc_logo_image_width) || !is_numeric($vswc_logo_image_width) ) {
	$vswc_logo_image_width = 200;
}
if ( empty($vswc_page_title) ) {
	$vswc_page_title = __( 'Closed', 'very-simple-website-closed' );
}
if ( empty($vswc_content_title) ) {
	$vswc_content_title = __( 'Closed', 'very-simple-website-closed' );
}
if ( empty($vswc_content) ) {
	$vswc_content = __( 'Website is closed today.', 'very-simple-website-closed' );
}
