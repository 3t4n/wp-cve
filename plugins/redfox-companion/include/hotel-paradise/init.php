<?php
require_once('default-service/default-service.php');


require_once('default-room/default-room.php');


require_once('customizer/customizer.php');


require_once('home-page/section-service.php');


require_once('home-page/section-room.php');


function rfc_hotel_paradise_default_data($data){
	$new_data = array(
		'switcher_hide'=> true,
		
		'header_tb_hide' => false,
		'header_tb_icon1'=> 'fa-map',
		'header_tb_text1'=> __('2050 Bamako Place California','hotel-paradise'),
		'header_tb_icon2'=> 'fa-phone',
		'header_tb_text2'=> __('001-541-754-3010','hotel-paradise'),
		'header_facebook_link'=> '#',
		'header_twitter_link'=> '#',
		'header_linkedin_link'=> '#',
		'header_googleplus_link'=> '#',
		'header_skype_link'=> '#',
		
		'theme_color'=> '#85D13D',
		'theme_color_custom_show'=> false,
		'theme_color_custom_color'=> '#85D13D',
		'site_layout'=> false,
		'nav_padding'=> 18,	
		'primary_sidebar'=> 'right',
		'animation_effect_hide'=> false,
		'google_fonts_hide'=> false,
		'single_image_hide'=> false,
		'single_meta_hide'=> false,
		'btt_disable' => false,
		'copyright' => __('&copy; 2019, WordPress Theme by Redfoxthemes','hotel-paradise'),
		'footer_logo_image' => get_template_directory_uri() . '/images/footer-logo.png',
		'papal_icon_hide' => false,
		'stripe_icon_hide' => false,
		'visa_icon_hide' => false,
		'mc_icon_hide' => false,
		'ae_icon_hide' => false,
		
		'hero_section_hide' => false,
		'hero_animation_type' => 'slide',
		'hero_speed' => 3000,
		'hero_media' => '',
		'hero_largetext' => __('Welcome to Hotel Paradise','hotel-paradise'),
		'hero_large_effect' => 'zoomIn',		
		'hero_smalltext' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.','hotel-paradise'),
		'hero_small_effect' => 'zoomIn',
		'hero_btn_text' => __('Learn More','hotel-paradise'),
		'hero_btn_link' => '#',
		'hero_btn_effect' => 'rotateIn',

		'service_s_hide' => false,
		'service_s_column' => 4,
		'service_s_title' => __('About Hotel <span>Features</span>','hotel-paradise'),
		'service_s_subtitle' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.','hotel-paradise'),
		'service_s_content' => '',
		'service_s_bgcolor' => '#162541',
		'service_s_bgimage' => '',
		
		'room_s_hide' => false,
		'room_s_column' => 4,
		'room_s_title' => __('Book a Quality <span>Room</span>','hotel-paradise'),
		'room_s_subtitle' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.','hotel-paradise'),
		'room_s_content' => '',
		'room_s_bgcolor' => '#ffffff',
		'room_s_bgimage' => '',
		
		
		'blog_s_hide' => false,
		'blog_s_title' => __("Our Latest<span> Events</span>",'hotel-paradise'),
		'blog_s_subtitle' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.','hotel-paradise'),
		'blog_s_noofshow' => 4,
		'blog_s_orderby' => 0,
		'blog_s_order' => 'desc',
		'blog_s_cat' => 0,
		'blog_s_bgcolor' => '#ffffff',
		'blog_s_bgimage' => '',
		
		'contact_s_hide' => false,
		'contact_s_map_html' => '',
		'contact_s_address' => __('2050 Bamako Place California','hotel-paradise'),
		'contact_s_phone' => __('001-541-754-3010','hotel-paradise'),
		'contact_s_email' => __('info@example.com','hotel-paradise'),
		'contact_s_bgcolor' => '',
		'contact_s_bgimage' => '',
		
		'subheader_hide'=> false,
		'subheader_p_top'=> 80,
		'subheader_p_bottom'=> 80,
		'subheader_color'=> '',
		'subheader_align'=> 'center',
		'subheader_overlay_bg'=> '',
		
		'footer_logo'=> '',
		'footer_menu_hide'=> false,
		'footer_bttopBtn_hide'=> false,
		'footer_w_bg_color'=> '',
		'footer_w_t_color'=> '',
		'footer_w_l_color'=> '',
		'footer_w_l_h_color'=> '',
		'footer_widget_hide'=> false,
		'footer_bg_color'=> '',
		'footer_t_color'=> '',
		'footer_l_color'=> '',
		'footer_l_h_color'=> '',
		
		'ap_section_hide' => false,
		'ap_section_title' => __('Our History','hotel-paradise'),
		'ap_section_contents' => '',
		
		'p_fontsize' => '',
		'm_fontsize' => '',
		'h1_fontsize' => '',
		'h2_fontsize' => '',
		'h3_fontsize' => '',
		'h4_fontsize' => '',
		'h5_fontsize' => '',
		'h6_fontsize' => '',
	);

	$data = array_merge($data,$new_data);

	return $data;
}
add_filter('hotel_paradise_default_data','rfc_hotel_paradise_default_data');
?>