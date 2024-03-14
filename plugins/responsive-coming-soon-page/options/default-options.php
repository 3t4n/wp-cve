<?php
// Default Options
function weblizar_rcsm_default_settings() {
	$dateweblizar        = '2016-' . date( 'Y' );
	$default_bg          = RCSM_PLUGIN_URL . 'options/images/cg_img1.jpg';
	$default_bg_sub_form = plugin_dir_url( __FILE__ ) . 'images/sub_bg.jpg';
	$logo_img            = RCSM_PLUGIN_URL . 'options/images/logo.png';
	$favicon_img         = RCSM_PLUGIN_URL . 'options/images/favicon.png';
	$site_title          = get_bloginfo( 'name' );
	$site_description    = get_bloginfo( 'description' );
	global $current_user;
	wp_get_current_user();
	$LoggedInUserEmail1 = $current_user->user_email;
	$LoggedInUsername1  = $current_user->user_login;
	$wl_rcsm_options    = array(
		
		// Ganeral Settings Options
		'select_template'                            => 'select_template1',
		'page_meta_title'                            => $site_title . ' - ' . $site_description,
		'page_meta_keywords'                         => '',
		'page_meta_discription'                      => 'Our website is under construction. It will be live soon.',
		'search_robots'                              => 'on',
		'rcsm_robots_meta'                           => 'index follow',
		'theme_font_family'                          => 'Merienda',
		'upload_favicon'                             => $favicon_img,

		// Appearance Settings Options
		'layout_status'                              => 'deactivate',
		// Coming Soon Mode
		'coming-soon_title'                          => esc_html__( 'Our Site Is Coming Soon!!!', 'RCSM_TEXT_DOMAIN' ),
		'coming-soon_sub_title'                      => 'Stay Tuned For Something Amazing',
		'coming-soon_message'                        => 'Responsive Design & Faster User Interface',
		'site_logo'                                  => 'logo_image',
		'site_logo_alignment'                        => 'center',
		'logo_text_value'                            => $site_title,
		'upload_image_logo'                          => $logo_img,
		'logo_height'                                => '150',
		'logo_width'                                 => '250',
		'sub_bg_color'                               => '#0098ff',
		'bg_color'                                   => '#0098ff',
		'template_bg_select'                         => 'Custom_Background',
		'select_bg_subs'                             => 'sub_bg_clr',
		'custom_bg_img'                              => $default_bg,
		'custom_sub_bg_img'                          => $default_bg_sub_form,
		'button_onoff'                               => 'on',
		'button_text'                                => 'DISCOVER MORE',
		'button_text_link'                           => '#timer',
		'link_admin'                                 => 'on',
		'admin_link_text'                            => 'Admin Dashboard',
		'custom_bg_title_color' 					 => '#FFFFFF',
		'custom_bg_desc_color'  					 => '#FFFFFF',

		// Access Control Settings
		'user_value'                                 => array(),
		'page_layout_swap'                           => array( 'Count Down Timer', 'Subscriber Form' ),
		// Skin Layout Settings
		'theme_color_schemes'                        => '#eb5054',

		// Social Settings
		'social_icon_1'                              => 'fab fa-facebook-f',
		'social_icon_2'                              => 'fab fa-twitter',
		'social_icon_3'                              => 'fab fa-google-plus-g',
		'social_icon_4'                              => 'fab fa-pinterest-p',
		'social_icon_5'                              => 'fab fa-linkedin-in',
		'social_link_1'                              => '#',
		'social_link_2'                              => '#',
		'social_link_3'                              => '#',
		'social_link_4'                              => '#',
		'social_link_5'                              => '#',
		'link_tab_1'                                 => 'off',
		'link_tab_2'                                 => 'off',
		'link_tab_3'                                 => 'off',
		'link_tab_4'                                 => 'off',
		'link_tab_5'                                 => 'off',
		'total_Social_links'                         => '5',
		'social_icon_list'                           => '',

		// Subscriber Form Settings
		'subscriber_form'                            => 'on',
		'subscriber_form_title'                      => esc_html__( 'SUBSCRIBE TO OUR NEWSLETTER', 'RCSM_TEXT_DOMAIN' ),
		'subscriber_form_icon'                       => 'fa fa-envelope',
		'subscriber_form_sub_title'                  => esc_html__( 'In the mean time connect with us to subscribed our newsletter', 'RCSM_TEXT_DOMAIN' ),
		'subscriber_form_message'                    => esc_html__( "Subscribe and we'll notify you on our launch. We'll also throw in a freebie for your effort.", 'RCSM_TEXT_DOMAIN' ),
		'sub_form_button_text'                       => 'Subscribe',
		'sub_form_button_f_name'                     => 'First Name',
		'sub_form_button_l_name'                     => 'Last Name',
		'sub_form_subscribe_title'                   => 'Email',
		'user_sets'                                  => '$user_sets_all',
		'sub_form_subscribe_seuccess_message'        => esc_html__( 'Thank you! We will be back with the quote.', 'RCSM_TEXT_DOMAIN' ),
		'sub_form_subscribe_invalid_message'         => esc_html__( 'You have already subscribed.', 'RCSM_TEXT_DOMAIN' ),
		'subscriber_msg_body'                        => '',
		'sub_form_subscribe_confirm_success_message' => esc_html__( 'Thank You!!! Subscription has been confirmed. We will notify when the site is live.', 'RCSM_TEXT_DOMAIN' ),
		'sub_form_subscribe_already_confirm_message' => esc_html__( 'You subscription is already active. We will notify when the site is live.', 'RCSM_TEXT_DOMAIN' ),
		'sub_form_invalid_confirmation_message'      => esc_html__( 'Error: Invalid subscription details.', 'RCSM_TEXT_DOMAIN' ),

		// Subscriber Form Option Settings
		'subscribe_select'                           => 'wp_mail',
		'wp_mail_email_id'                           => $LoggedInUserEmail1,
		'confirm_email_subscribe'                    => 'off',

		// Subscriber List Options Setting
		'auto_sentto_activeusers'                    => 'on',
		'subscriber_users_mail_option'               => 'all_users',
		'subscriber_mail_subject'                    => '',
		'subscriber_mail_message'                    => '',
		// Counter Clock and Progress Bar Options
		'counter_title'                              => "We're Coming Soon",
		'counter_title_icon'                         => 'fas fa-clock',
		'counter_msg'                                => 'We Are Currently Working On Something Awesome',
		'disable_the_plugin'                         => 'off',
		'maintenance_date'                           => date( 'Y/m/d h:i', strtotime( '+7 day' ) ),

		// Footer Options
		'footer_copyright_text'                      => 'Copyright © ' . $dateweblizar . ' Weblizar Themes & Plugins | All Rights Reserved By',
		'footer_link'                                => 'https://weblizar.com',
		'footer_link_text'                           => 'Weblizar',

		// Extra Advance options/option
		'custom_css'                                 => '',
		'google_analytics'                           => '',

		// feedback Settings
		'feedback_mail'                              => '',
		'feedback_heading'                           => 'Book Appointment',
		'feedback_icon'                              => 'fas fa-calendar-week',
		'feedback_btn'                               => 'Booking Appointment',
	);
	return apply_filters( 'weblizar_rcsm_options', $wl_rcsm_options );
}

// Options API
function weblizar_rcsm_get_options() {
	// Options API Settings
	return wp_parse_args( get_option( 'weblizar_rcsm_options', array() ), weblizar_rcsm_default_settings() );
}

// General Options Setting
function rcsm_general_setting() {
	$wl_rcsm_options                          = get_option( 'weblizar_rcsm_options' );
	$site_title                               = get_bloginfo( 'name' );
	$site_description                         = get_bloginfo( 'description' );
	$wl_rcsm_options['page_meta_title']       = $site_title . ' - ' . $site_description;
	$wl_rcsm_options['page_meta_keywords']    = '';
	$wl_rcsm_options['page_meta_discription'] = 'Our website is under construction. It will be live soon.';
	$wl_rcsm_options['search_robots']         = 'on';
	$wl_rcsm_options['rcsm_robots_meta']      = 'index follow';
	$wl_rcsm_options['upload_image_favicon']  = '';
	update_option( 'weblizar_rcsm_options', $wl_rcsm_options );
}

// Appearance Options Setting
function rcsm_appearance_setting() {
	$wl_rcsm_options                          = get_option( 'weblizar_rcsm_options' );
	$site_title                               = get_bloginfo( 'name' );
	$default_bg                               = RCSM_PLUGIN_URL . 'options/images/cg_img1.jpg';
	$default_bg_sub_form                      = plugin_dir_url( __FILE__ ) . 'images/sub_bg.jpg';
	$logo_img                                 = RCSM_PLUGIN_URL . 'options/images/logo.png';
	$wl_rcsm_options['layout_status']         = 'deactivate';
	$wl_rcsm_options['coming-soon_title']     = esc_html__( 'Our Site Is Coming Soon!!!', 'RCSM_TEXT_DOMAIN' );
	$wl_rcsm_options['coming-soon_sub_title'] = esc_html__( 'Stay Tuned For Something Amazing', 'RCSM_TEXT_DOMAIN' );
	$wl_rcsm_options['coming-soon_message']   = esc_html__( 'Responsive Design & Faster User Interface', 'RCSM_TEXT_DOMAIN' );
	$wl_rcsm_options['upload_favicon']        = $favicon_img;
	$wl_rcsm_options['site_logo']             = 'logo_image';
	$wl_rcsm_options['site_logo_alignment']   = 'center';
	$wl_rcsm_options['logo_text_value']       = $site_title;
	$wl_rcsm_options['upload_image_logo']     = $logo_img;
	$wl_rcsm_options['logo_height']           = '150';
	$wl_rcsm_options['logo_width']            = '250';
	$wl_rcsm_options['sub_bg_color']          = '#0098ff';
	$wl_rcsm_options['bg_color']              = '#0098ff';
	$wl_rcsm_options['template_bg_select']    = 'Custom_Background';
	$wl_rcsm_options['select_bg_subs']        = '';
	$wl_rcsm_options['custom_bg_img']         = $default_bg;
	$wl_rcsm_options['custom_sub_bg_img']     = $default_bg_sub_form;
	$wl_rcsm_options['custom_bg_title_color'] = '#FFFFFF';
	$wl_rcsm_options['custom_bg_desc_color']  = '#FFFFFF';


	$wl_rcsm_options['button_onoff']     = 'on';
	$wl_rcsm_options['button_text']      = 'DISCOVER MORE';
	$wl_rcsm_options['button_text_link'] = '#timer';
	$wl_rcsm_options['link_admin']       = 'on';
	$wl_rcsm_options['admin_link_text']  = 'Admin Dashboard';
	update_option( 'weblizar_rcsm_options', $wl_rcsm_options );
}

// Access control Options Setting
function rcsm_access_control_setting() {
	$wl_rcsm_options                 = get_option( 'weblizar_rcsm_options' );
	$wl_rcsm_options['show_page_as'] = 'as_role';
	$wl_rcsm_options['user_value']   = array();
	update_option( 'weblizar_rcsm_options', $wl_rcsm_options );
}

// Layout swap control Options Setting
function rcsm_page_layout_swap_setting() {
	$wl_rcsm_options                     = get_option( 'weblizar_rcsm_options' );
	$wl_rcsm_options['page_layout_swap'] = array( 'Count Down Timer', 'Subscriber Form' );
	update_option( 'weblizar_rcsm_options', $wl_rcsm_options );
}

// Layout Settings
function rcsm_layout_setting() {
	$wl_rcsm_options                        = get_option( 'weblizar_rcsm_options' );
	$wl_rcsm_options['theme_font_family']   = 'Merienda';
	$wl_rcsm_options['theme_color_schemes'] = '#eb5054';
	update_option( 'weblizar_rcsm_options', $wl_rcsm_options );
}


// Social Options Setting
function rcsm_social_setting() {
	$wl_rcsm_options                  = get_option( 'weblizar_rcsm_options' );
	$wl_rcsm_options['social_icon_1'] = 'fab fa-facebook-f';
	$wl_rcsm_options['social_icon_2'] = 'fab fa-twitter';
	$wl_rcsm_options['social_icon_3'] = 'fab fa-google-plus-g';
	$wl_rcsm_options['social_icon_4'] = 'fab fa-pinterest-p';
	$wl_rcsm_options['social_icon_5'] = 'fab fa-linkedin-in';
	
	for ( $i = 1; $i <= 5; $i++ ) {
		$wl_rcsm_options[ 'social_link_' . $i ] = '#';
		$wl_rcsm_options[ 'link_tab_' . $i ]    = 'off';
	}

	$wl_rcsm_options['total_Social_links'] = '5';
	$wl_rcsm_options['social_icon_list']   = '';
	update_option( 'weblizar_rcsm_options', $wl_rcsm_options );
}

// Subscriber Form Options Setting
function rcsm_subscriber_form_setting() {
	$wl_rcsm_options                                        = get_option( 'weblizar_rcsm_options' );
	$wl_rcsm_options['subscriber_form']                     = 'on';
	$wl_rcsm_options['subscriber_form_title']               = esc_html__( 'SUBSCRIBE TO OUR NEWSLETTER', 'RCSM_TEXT_DOMAIN' );
	$wl_rcsm_options['subscriber_form_icon']                = 'fas fa-envelope-open-text';
	$wl_rcsm_options['subscriber_form_sub_title']           = esc_html__( 'In the mean time connect with us to subscribed our newsletter', 'RCSM_TEXT_DOMAIN' );
	$wl_rcsm_options['subscriber_form_message']             = esc_html__( "Subscribe and we'll notify you on our launch. We'll also throw in a freebie for your effort.", 'RCSM_TEXT_DOMAIN' );
	$wl_rcsm_options['sub_form_button_f_name']              = 'First Name';
	$wl_rcsm_options['sub_form_button_l_name']              = 'Last Name';
	$wl_rcsm_options['sub_form_button_text']                = 'Subscribe';
	$wl_rcsm_options['sub_form_subscribe_title']            = 'Email';
	$wl_rcsm_options['sub_form_subscribe_seuccess_message'] = esc_html__( 'Thank you! We will be back with the quote.', 'RCSM_TEXT_DOMAIN' );
	$wl_rcsm_options['sub_form_subscribe_invalid_message']  = esc_html__( 'You have already subscribed.', 'RCSM_TEXT_DOMAIN' );
	$wl_rcsm_options['subscriber_msg_body']                 = '';
	$wl_rcsm_options['sub_form_subscribe_confirm_success_message'] = esc_html__( 'Thank You!!! Subscription has been confirmed. We will notify when the site is live.', 'RCSM_TEXT_DOMAIN' );
	$wl_rcsm_options['sub_form_subscribe_already_confirm_message'] = esc_html__( 'You subscription is already active. We will notify when the site is live.', 'RCSM_TEXT_DOMAIN' );
	$wl_rcsm_options['sub_form_invalid_confirmation_message']      = esc_html__( 'Error: Invalid subscription details.', 'RCSM_TEXT_DOMAIN' );
	update_option( 'weblizar_rcsm_options', $wl_rcsm_options );
}

// Subscriber Form Provider Options Setting
function rcsm_subscriber_provider_setting() {
	global $current_user;
	wp_get_current_user();
	$LoggedInUserEmail1                         = $current_user->user_email;
	$LoggedInUsername1                          = $current_user->user_login;
	$wl_rcsm_options                            = get_option( 'weblizar_rcsm_options' );
	$wl_rcsm_options['subscribe_select']        = 'wp_mail';
	$wl_rcsm_options['wp_mail_email_id']        = $LoggedInUserEmail1;
	$wl_rcsm_options['confirm_email_subscribe'] = 'off';
	update_option( 'weblizar_rcsm_options', $wl_rcsm_options );
}

// Subscriber List Options Setting
function rcsm_subscriber_list_setting() {
	$wl_rcsm_options                                 = get_option( 'weblizar_rcsm_options' );
	$wl_rcsm_options['auto_sentto_activeusers']      = 'off';
	$wl_rcsm_options['subscriber_users_mail_option'] = 'all_users';
	$wl_rcsm_options['subscriber_mail_subject']      = '';
	$wl_rcsm_options['subscriber_mail_message']      = '';
	update_option( 'weblizar_rcsm_options', $wl_rcsm_options );
}

// Counter Clock and Progress Bar Options Setting
function rcsm_counter_clock_setting() {
	$wl_rcsm_options                       = get_option( 'weblizar_rcsm_options' );
	$wl_rcsm_options['counter_title_icon'] = 'fas fa-clock';
	$wl_rcsm_options['counter_title']      = "We're Coming Soon";
	$wl_rcsm_options['counter_msg']        = 'We Are Currently Working On Something Awesome';
	$wl_rcsm_options['disable_the_plugin'] = 'off';
	$wl_rcsm_options['maintenance_date']   = date( 'Y/m/d h:i', strtotime( '+7 day' ) );
	update_option( 'weblizar_rcsm_options', $wl_rcsm_options );
}

// footer  Options Setting
function rcsm_footer_setting() {
	$dateweblizar                             = '2016-' . date( 'Y' );
	$wl_rcsm_options                          = get_option( 'weblizar_rcsm_options' );
	$wl_rcsm_options['footer_copyright_text'] = esc_html__( 'Copyright © ' . $dateweblizar . ' Weblizar Themes & Plugins | All Rights Reserved By', 'RCSM_TEXT_DOMAIN' );
	$wl_rcsm_options['footer_link']           = 'https://weblizar.com';
	$wl_rcsm_options['footer_link_text']      = 'Weblizar';
	update_option( 'weblizar_rcsm_options', $wl_rcsm_options );
}

// Advance Options Setting
function rcsm_advance_option_setting() {
	$wl_rcsm_options                     = get_option( 'weblizar_rcsm_options' );
	$wl_rcsm_options['custom_css']       = '';
	$wl_rcsm_options['google_analytics'] = '';
	update_option( 'weblizar_rcsm_options', $wl_rcsm_options );
}

// Feedback options Setting
function rcsm_feedback_setting() {
	$wl_rcsm_options                     = get_option( 'weblizar_rcsm_options' );
	$wl_rcsm_options['feedback_mail']    = '';
	$wl_rcsm_options['feedback_heading'] = 'Book Appointment';
	$wl_rcsm_options['feedback_icon']    = 'fas fa-calendar-week';
	$wl_rcsm_options['feedback_btn']     = 'Booking Appointment';
	update_option( 'weblizar_rcsm_options', $wl_rcsm_options );
}
