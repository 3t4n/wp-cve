<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// get current plugin version
$csmm_current_version = get_option( 'csmm_current_version' );
$csmm_last_version    = get_option( 'csmm_last_version' );

//defaults
$csmm_content = array();
$csmm_templates = array();
$csmm_settings = array();
$csmm_posts = array();
$csmm_pages = array();
$csmm_other_pages = array();
$csmm_website_mode = 3;
$csmm_template_id = 1;
$csmm_logo_id = "";
$csmm_logo_alt = "coming-soon-logo";
$csmm_logo_url[0] = esc_url( CSMM_URL.'templates/images/logo-w.png' );
$csmm_title = sanitize_text_field("Coming Soon");
$csmm_description = sanitize_text_field("Thank you for visiting our website! We are currently working on creating a new and exciting online experience for you. While we finish up the final touches, please sign up for our newsletter to receive exclusive updates and offers.");
$csmm_countdown = 1;
$csmm_countdown_title = sanitize_text_field("Launching In...");
$csmm_current_date = date('Y-m-d');
$csmm_launch_dt = date('Y-m-d', strtotime($csmm_current_date . ' +30 days'));
$csmm_countdown_date = date('Y-m-d', strtotime($csmm_current_date . ' +30 days'));
$csmm_countdown_time = "10:00";
$csmm_sm_facebook = $csmm_sm_twitter = $csmm_sm_instagram = "#";


//load CSMM settings
$csmm_settings = get_option('csmm_settings');
if(is_array($csmm_settings)){
	if(array_key_exists('website_mode', $csmm_settings)){ $csmm_website_mode = $csmm_settings['website_mode']; }
}
//load CSMM templates
$csmm_templates = get_option('csmm_templates');
if(is_array($csmm_templates)){
	if(array_key_exists('template_id', $csmm_templates)){ $csmm_template_id = $csmm_templates['template_id']; }
}
//load CSMM content
$csmm_content = get_option('csmm_content');
//print_r($csmm_content);
if(is_array($csmm_content)){
	if(array_key_exists('logo', $csmm_content)){ 
		$csmm_logo_id = $csmm_content['logo']; 
		if($csmm_logo_id)
			$csmm_logo_url = wp_get_attachment_image_src($csmm_logo_id, 'medium', false); // attachment medium URL
		else
			$csmm_logo_url[0] = esc_url( CSMM_URL.'templates/images/logo-w.png' );
	}
	if(array_key_exists('title', $csmm_content)){ $csmm_title = $csmm_content['title']; }
	if(array_key_exists('description', $csmm_content)){ $csmm_description = $csmm_content['description']; }
	if(array_key_exists('countdown', $csmm_content)){ $csmm_countdown = $csmm_content['countdown']; }
	if(array_key_exists('countdown_title', $csmm_content)){ $csmm_countdown_title = $csmm_content['countdown_title']; }
	if(array_key_exists('countdown_date', $csmm_content)){ $csmm_countdown_date = $csmm_content['countdown_date']; }
	if(array_key_exists('countdown_time', $csmm_content)){ $csmm_countdown_time = $csmm_content['countdown_time']; }
	
	// launch date calculation
	$csmm_launch_date = date('F d, Y', strtotime($csmm_countdown_date));
	$csmm_launch_time = date('H:i:s', strtotime($csmm_countdown_time));
	$csmm_launch_dt = $csmm_launch_date." ".$csmm_launch_time; // March 25, 2024 15:37:25
}

// load social media
$csmm_social_media = get_option('csmm_social_media');
if(is_array($csmm_social_media)){
	if(array_key_exists('csmm_sm_facebook', $csmm_social_media)){ $csmm_sm_facebook = $csmm_social_media['csmm_sm_facebook']; }
	if(array_key_exists('csmm_sm_twitter', $csmm_social_media)){ $csmm_sm_twitter = $csmm_social_media['csmm_sm_twitter']; }
	if(array_key_exists('csmm_sm_instagram', $csmm_social_media)){ $csmm_sm_instagram = $csmm_social_media['csmm_sm_instagram']; }
}

//print_r($cmss_subscriber_list);
$file = plugin_dir_path( __FILE__ )."templates/$csmm_template_id.php";
include($file);