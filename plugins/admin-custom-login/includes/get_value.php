<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

// Get value of Dashboard page
$dashboard_page   = unserialize(get_option('Admin_custome_login_dashboard'));
$dashboard_status = isset($dashboard_page['dashboard_status']) ? $dashboard_page['dashboard_status'] : null;

// Get value of Top page
$top_page       = unserialize(get_option('Admin_custome_login_top'));
if (!empty($top_page)) {
	$top_bg_type    = $top_page['top_bg_type'];
	$top_color      = $top_page['top_color'];
	$top_image      = $top_page['top_image'];
	$top_cover      = $top_page['top_cover'];
	$top_repeat     = $top_page['top_repeat'];
	$top_position   = $top_page['top_position'];
	$top_attachment = $top_page['top_attachment'];
}
global $top_slideshow_no;
$top_slideshow_no        = $top_page['top_slideshow_no'];
$top_bg_slider_animation = $top_page['top_bg_slider_animation'];

// Load Login Form Settings
$login_page            = unserialize(get_option('Admin_custome_login_login'));
$login_form_position   = isset($login_page['login_form_position']) ? $login_page['login_form_position'] : 'default';
$login_form_left       = $login_page['login_form_left'];
$login_form_top        = isset($login_page['login_form_top']) ? $login_page['login_form_top'] : '300';
$login_form_float      = isset($login_page['login_form_float']) ? $login_page['login_form_float'] : 'center';
$login_bg_type         = $login_page['login_bg_type'];
$login_bg_color        = $login_page['login_bg_color'];
$login_bg_effect       = $login_page['login_bg_effect'];
$login_bg_image        = $login_page['login_bg_image'];
$login_form_opacity    = $login_page['login_form_opacity'];
$login_form_width      = $login_page['login_form_width'];
$login_form_radius     = $login_page['login_form_radius'];
$login_border_style    = $login_page['login_border_style'];
$login_border_thikness = $login_page['login_border_thikness'];
$login_border_color    = $login_page['login_border_color'];
$login_bg_repeat       = $login_page['login_bg_repeat'];
$login_bg_position     = $login_page['login_bg_position'];
$login_enable_shadow   = $login_page['login_enable_shadow'];
$login_enable_language_switcher   = $login_page['login_enable_language_switcher'];

if (isset($login_page['login_redirect_force'])) {
	$login_redirect_force = $login_page['login_redirect_force'];
} else {
	$login_redirect_force = 'no';
}

$login_shadow_color  = $login_page['login_shadow_color'];
$login_custom_css    = isset($login_page['login_custom_css']) ? $login_page['login_custom_css'] : '';
$login_redirect_user = isset($login_page['login_redirect_user']) ? $login_page['login_redirect_user'] : '';

if (isset($login_page['login_force_redirect_url'])) {
	$login_force_redirect_url = $login_page['login_force_redirect_url'];
} else {
	$login_force_redirect_url = get_home_url() . "/wp-login.php";
}

$log_form_above_msg       = $login_page['log_form_above_msg'];
$login_pass_reset_msg     = $login_page['login_pass_reset_msg'];
$login_msg_fontsize       = $login_page['login_msg_fontsize'];
$login_msg_font_color     = $login_page['login_msg_font_color'];
$login_tagline_text_color = isset($login_page['login_tagline_text_color']) ? $login_page['login_tagline_text_color'] : '#fff';
$login_tagline_link_color = isset($login_page['login_tagline_link_color']) ? $login_page['login_tagline_link_color'] : '#f00';
$tagline_msg              = $login_page['tagline_msg'];
if (isset($login_page['user_cust_lbl'])) {
	$user_cust_lbl = $login_page['user_cust_lbl'];
} else {
	$user_cust_lbl = "Type Username or Email";
}
if (isset($login_page['pass_cust_lbl'])) {
	$pass_cust_lbl = $login_page['pass_cust_lbl'];
} else {
	$pass_cust_lbl = "Type Password";
}

if (isset($login_page['label_username'])) {
	$label_username = $login_page['label_username'];
} else {
	$label_username = "Username or Email";
}
if (isset($login_page['label_password'])) {
	$label_password = $login_page['label_password'];
} else {
	$label_password = "Password";
}
if (isset($login_page['label_loginButton'])) {
	$label_loginButton = $login_page['label_loginButton'];
} else {
	$label_loginButton = "Log In";
}

// Get value of Text and Color page
$text_and_color_page = unserialize(get_option('Admin_custome_login_text'));
$heading_font_color  = $text_and_color_page['heading_font_color'];
// $social_link_new_window = $text_and_color_page['social_link_new_window'];
$input_font_color = $text_and_color_page['input_font_color'];
$link_color       = $text_and_color_page['link_color'];
$button_color     = $text_and_color_page['button_color'];
if (isset($text_and_color_page['login_button_font_color'])) {
	$login_button_font_color = $text_and_color_page['login_button_font_color'];
} else {
	$login_button_font_color = "#ffffff";
}
$heading_font_size        = $text_and_color_page['heading_font_size'];
$input_font_size          = $text_and_color_page['input_font_size'];
$link_size                = $text_and_color_page['link_size'];
$button_font_size         = $text_and_color_page['button_font_size'];
$enable_link_shadow       = $text_and_color_page['enable_link_shadow'];
$show_remember_me_field   = isset($text_and_color_page['show_remember_me_field']) ? $text_and_color_page['show_remember_me_field'] : 'yes';
$show_back_to_site_link   = isset($text_and_color_page['show_back_to_site_link']) ? $text_and_color_page['show_back_to_site_link'] : 'yes';
$show_copyright_link_text = isset($text_and_color_page['show_copyright_link_text']) ? $text_and_color_page['show_copyright_link_text'] : 'yes';
$link_shadow_color        = $text_and_color_page['link_shadow_color'];
$heading_font_style       = $text_and_color_page['heading_font_style'];
$input_font_style         = $text_and_color_page['input_font_style'];
$link_font_style          = $text_and_color_page['link_font_style'];
$button_font_style        = $text_and_color_page['button_font_style'];
$enable_inputbox_icon     = $text_and_color_page['enable_inputbox_icon'];
$user_input_icon          = $text_and_color_page['user_input_icon'];
$password_input_icon      = $text_and_color_page['password_input_icon'];

// Get value of Logo page
$logo_page      = unserialize(get_option('Admin_custome_login_logo'));
$logo_image     = $logo_page['logo_image'];
$logo_show      = isset($logo_page['logo_show']) ? $logo_page['logo_show'] : 'yes';
$logo_width     = $logo_page['logo_width'];
$logo_height    = $logo_page['logo_height'];
$logo_url       = $logo_page['logo_url'];
$logo_url_title = $logo_page['logo_url_title'];

// Get value of Gcaptcha page
$g_page                = unserialize(get_option('Admin_custome_login_gcaptcha'));
$site_key              = $g_page['site_key'];
$secret_key            = $g_page['secret_key'];
$site_key_v3           = isset($g_page['site_key_v3']) ? $g_page['site_key_v3'] : '';
$secret_key_v3         = isset($g_page['secret_key_v3']) ? $g_page['secret_key_v3'] : '';
$login_enable_gcaptcha = $g_page['login_enable_gcaptcha'];

$login_v_gcaptcha = isset($g_page['login_v_gcaptcha']) ? $g_page['login_v_gcaptcha'] : 'v2';

if (isset($g_page['acl_gcaptcha_theme'])) {
	$acl_gcaptcha_theme = $g_page['acl_gcaptcha_theme'];
} else {
	$acl_gcaptcha_theme = "yes";
}

// Get value of Slidshow image
$Slidshow_image   = unserialize(get_option('Admin_custome_login_Slidshow'));
$Slidshow_image_1 = $Slidshow_image['Slidshow_image_1'];
$Slidshow_image_2 = $Slidshow_image['Slidshow_image_2'];
$Slidshow_image_3 = $Slidshow_image['Slidshow_image_3'];
$Slidshow_image_4 = $Slidshow_image['Slidshow_image_4'];
$Slidshow_image_5 = $Slidshow_image['Slidshow_image_5'];
$Slidshow_image_6 = $Slidshow_image['Slidshow_image_6'];

$Slidshow_image_label_1 = $Slidshow_image['Slidshow_image_label_1'];
$Slidshow_image_label_2 = $Slidshow_image['Slidshow_image_label_2'];
$Slidshow_image_label_3 = $Slidshow_image['Slidshow_image_label_3'];
$Slidshow_image_label_4 = $Slidshow_image['Slidshow_image_label_4'];
$Slidshow_image_label_5 = $Slidshow_image['Slidshow_image_label_5'];
$Slidshow_image_label_6 = $Slidshow_image['Slidshow_image_label_6'];

$Social_page               = unserialize(get_option('Admin_custome_login_Social'));
$enable_social_icon        = $Social_page['enable_social_icon'];
$social_icon_size          = $Social_page['social_icon_size'];
$social_icon_layout        = $Social_page['social_icon_layout'];
$social_icon_color         = $Social_page['social_icon_color'];
$social_icon_color_onhover = $Social_page['social_icon_color_onhover'];
$social_icon_bg            = $Social_page['social_icon_bg'];
$social_icon_bg_onhover    = $Social_page['social_icon_bg_onhover'];
$social_facebook_link      = $Social_page['social_facebook_link'];
$social_twitter_link       = $Social_page['social_twitter_link'];
$social_linkedin_link      = $Social_page['social_linkedin_link'];
$social_pinterest_link     = $Social_page['social_pinterest_link'];
$social_digg_link          = $Social_page['social_digg_link'];
$social_youtube_link       = $Social_page['social_youtube_link'];
$social_flickr_link        = $Social_page['social_flickr_link'];
$social_tumblr_link        = $Social_page['social_tumblr_link'];
$social_skype_link         = $Social_page['social_skype_link'];
$social_instagram_link     = $Social_page['social_instagram_link'];
$social_telegram_link      = $Social_page['social_telegram_link'];
$social_whatsapp_link      = $Social_page['social_whatsapp_link'];
