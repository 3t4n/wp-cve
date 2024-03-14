<?php
/**
 * @package Admin
 * Default Options for CWT
 */

/**
 * Returns list of social icons currently supported
 *
 * @since Catch Web Tools 1.5
*/
function catchwebtools_get_social_icons_list() {
	$options =	array(
		'facebook'		=> esc_html__( 'Facebook', 'catch-web-tools' ),
		'twitter'		=> esc_html__( 'Twitter', 'catch-web-tools' ),
		'googleplus'	=> esc_html__( 'Googleplus', 'catch-web-tools' ),
		'mail'			=> esc_html__( 'Email', 'catch-web-tools' ),
		'feed'			=> esc_html__( 'Feed', 'catch-web-tools' ),
		'wordpress'		=> esc_html__( 'WordPress', 'catch-web-tools' ),
		'github'		=> esc_html__( 'GitHub', 'catch-web-tools' ),
		'linkedin'		=> esc_html__( 'LinkedIn', 'catch-web-tools' ),
		'pinterest'		=> esc_html__( 'Pinterest', 'catch-web-tools' ),
		'flickr'		=> esc_html__( 'Flickr', 'catch-web-tools' ),
		'vimeo'			=> esc_html__( 'Vimeo', 'catch-web-tools' ),
		'youtube'		=> esc_html__( 'YouTube', 'catch-web-tools' ),
		'tumblr'		=> esc_html__( 'Tumblr', 'catch-web-tools' ),
		'instagram'		=> esc_html__( 'Instagram', 'catch-web-tools' ),
		'polldaddy'		=> esc_html__( 'PollDaddy', 'catch-web-tools' ),
		'codepen'		=> esc_html__( 'CodePen', 'catch-web-tools' ),
		'path'			=> esc_html__( 'Path', 'catch-web-tools' ),
		'dribbble'		=> esc_html__( 'Dribbble', 'catch-web-tools' ),
		'skype'			=> esc_html__( 'Skype', 'catch-web-tools' ),
		'digg'			=> esc_html__( 'Digg', 'catch-web-tools' ),
		'reddit'		=> esc_html__( 'Reddit', 'catch-web-tools' ),
		'stumbleupon'	=> esc_html__( 'Stumbleupon', 'catch-web-tools' ),
		'pocket'		=> esc_html__( 'Pocket', 'catch-web-tools' ),
		'dropbox'		=> esc_html__( 'DropBox', 'catch-web-tools' ),
		'spotify'		=> esc_html__( 'Spotify', 'catch-web-tools' ),
		'foursquare'	=> esc_html__( 'Foursquare', 'catch-web-tools' ),
		'twitch'		=> esc_html__( 'Twitch', 'catch-web-tools' ),
		'website'		=> esc_html__( 'Website', 'catch-web-tools' ),
		'phone'			=> esc_html__( 'Phone', 'catch-web-tools' ),
		'handset'		=> esc_html__( 'Handset', 'catch-web-tools' ),
		'cart'			=> esc_html__( 'Cart', 'catch-web-tools' ),
		'cloud'			=> esc_html__( 'Cloud', 'catch-web-tools' ),
		'link'			=> esc_html__( 'Link', 'catch-web-tools' ),
		'vk'			=> esc_html__( 'VK', 'catch-web-tools' ),
	);

	return apply_filters( 'catchwebtools_get_social_icons_list', $options );
}

/**
 * Returns list of default options of to top module
 *
 * @since Catch Web Tools 1.8
 */
function catchwebtools_to_top_default_options( $option = null ) {
	$default_options = array(
		//Basic Settings
		'status'					=> 0,
		'scroll_offset'				=> '100',
		'icon_opacity'				=> '50',
		'style'						=> 'icon',

		//Icon Settings
		'icon_type'					=> 'dashicons-arrow-up-alt2',
		'icon_color'				=> '#ffffff',
		'icon_bg_color'				=> '#000000',
		'icon_size'					=> '32',
		'border_radius'				=> '5',

		//Image Settings
		'image'						=> CATCHWEBTOOLS_URL . 'to-top/admin/images/default.png',
		'image_width'				=> '65',
		'image_alt'					=> '',

		//Advanced Settings
		'location'					=> 'bottom-right',
		'margin_x'					=> '20',
		'margin_y'					=> '20',
		'show_on_admin'				=> 0,
		'enable_autohide'			=> 0,
		'autohide_time'				=> '2',
		'enable_hide_small_device'	=> 0,
		'small_device_max_width'	=> '640',

		//Reset Settings
		'reset'						=> 0,
	);

	if ( null == $option ) {
		return apply_filters( 'catchwebtools_to_top_options', $default_options );
	}
	else {
		return $default_options[ $option ];
	}
}

/**
 * Returns list of default options of SEO module
 *
 * @since Catch Web Tools 1.8
 */
function catchwebtools_webmaster_default_options() {
	$defaults = array(
		'status' => 0
	);

	return $defaults;
}


/**
 * Returns list of default options of SEO module
 *
 * @since Catch Web Tools 1.8
 */
function catchwebtools_seo_default_options() {
	$defaults = array(
		'status' => 0
	);

	return $defaults;
}


/**
 * Returns list of default options of Open Graph module
 *
 *  @since Catch Web Tools 1.8
 */
function catchwebtools_og_default_options() {
	$defaults = array(
		'status' => 0
	);

	return $defaults;
}


/**
 * Returns list of default options of Catch IDs module
 *
 * @since Catch Web Tools 1.8
 */
function catchwebtools_catch_ids_default_options() {
	$types = catchwebtools_catchids_get_all_post_types();
	foreach( $types as $key => $value ) {
		$defaults[$key] = 1;
	}
	$defaults['category'] = 1;
	$defaults['media'] = 1;
	$defaults['user'] = 1;
	$defaults['comment'] = 1;
	$defaults['status'] = 0;

	return $defaults;
}


/**
 * Returns list of default options of Social Icons module
 *
 * @since Catch Web Tools 1.8
 */
function catchwebtools_social_default_options() {
	$defaults = array(
		'status'                  => 0,
		'social_icon_brand_color' => 0,
		'social_icon_size'        => 32,
		'social_icon_color'       => '#504f4f',
		'social_icon_hover_color' => '#504f4f',
	);

	return $defaults;
}

/**
 * Returns list of default options of Catch Updater module
 *
 * @since Catch Web Tools 1.8
 */
function catchwebtools_catch_updater_default_options() {
	$defaults = array(
		'status' => 0
	);

	return $defaults;
}

/**
 * Returns list of default options of Catch Updater module
 *
 * @since Catch Web Tools 1.8
 */
function catchwebtools_big_image_size_threshold_default_options() {
	$defaults = array(
		'status' => 0,
		'max'    => 2560
	);

	return $defaults;
}


/**
 * Returns list of available hooks_suffix for admin
 *
 * @since Catch Web Tools 1.6
*/
function catchwebtools_admin_hook_suffix() {
	$options = array(
		'toplevel_page_catch-web-tools',
		'catch-web-tools_page_catch-web-tools-webmasters',
		'catch-web-tools_page_catch-web-tools-catch-ids',
		'catch-web-tools_page_catch-web-tools-custom-css',
		'catch-web-tools_page_catch-web-tools-custom-css',
		'catch-web-tools_page_catch-web-tools-custom-css',
		'catch-web-tools_page_catch-web-tools-custom-css',
		'catch-web-tools_page_catch-web-tools-social-icons',
		'catch-web-tools_page_catch-web-tools-opengraph',
		'catch-web-tools_page_catch-web-tools-seo',
		'catch-web-tools_page_catch-web-tools-to-top'
	);
	return apply_filters( 'catchwebtools_admin_hook_suffix', $options );
}
