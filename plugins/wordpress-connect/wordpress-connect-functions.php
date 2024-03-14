<?php

/*  Copyright 2009-2011  SciBuff - Wordpress Connect

    This file is part of Wordpress Connect Wordpress Plugin.

    Wordpress Connect is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Wordpress Connect is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Wordpress Connect.  If not, see <http://www.gnu.org/licenses/>.

*/

if ( !function_exists( 'wp_connect_activity_feed' ) ) :

/**
 * This function renders a Facebook Activity Feed based on the passed values.
 * It should/will be used by theme designers who would like to add specific
 * activity feed(s) to their themes.
 *
 * @param string $domain		the domain to show activity for
 * 								e.g. 'www.example.com'
 *
 * @param int $width			the width of the plugin
 *
 * @param int $height			the height of the plugin
 *
 * @param string $show_header	valid values are:
 * 									WPC_OPTION_DISABLED
 * 									WPC_OPTION_ENABLED
 *
 * @param string $colorscheme	valid values are:
 * 									WPC_THEME_LIGHT
 * 									WPC_THEME_DARK
 *
 * @param string $font			valid values are:
 * 									WPC_FONT_ARIAL
 * 									WPC_FONT_LUCIDA_GRANDE
 * 									WPC_FONT_SEGOE_UI
 * 									WPC_FONT_TAHOMA
 * 									WPC_FONT_TREBUCHET_MS
 * 									WPC_FONT_VERDANA
 *
 * @param string $border_color	the border color of the plugin
 *
 * @param string $show_recommendations	valid values are:
 * 											WPC_OPTION_DISABLED
 * 											WPC_OPTION_ENABLED
 *
 * @param string $filter		filter which URLs are shown in the plugin
 *
 * @param string $ref			a label for tracking referrals
 *
 * @param boolean $echo			A boolean value specifying whether the
 * 								comments box should be printed or returned
 * 								into a PHP variable
 *
 * @access  public
 * @see 	http://developers.facebook.com/docs/reference/plugins/activity/
 * @since	2.0
 *
 * @example
 *
 * <code>
 * // this file is required to enable the use of Wordpress Connect constants
 * $wpc_constants_file = WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php';
 *
 * if ( file_exists( $wpc_constants_file ) && function_exists( 'wp_connect_activity_feed' ) ){
 *
 * 		require_once( $wpc_constants_file );
 *
 *		$activity_domain = 'mysite.com';
 *		$activity_width = 400;
 *		$activity_height = 400;
 *		$show_header = WPC_OPTION_ENABLED;
 *		$colorscheme = WPC_THEME_DARK; // or WPC_THEME_LIGHT
 *		$font = WPC_FONT_ARIAL;
 *		$border_color = '#000';
 *		$show_recommendations = WPC_OPTION_DISABLED;
 *		$filter = '';
 *		$ref = '';
 *
 * 		// render the activity feed box in place
 * 		wp_connect_activity_feed(
 * 			$activity_domain,
 * 			$activity_width,
 * 			$activity_height,
 * 			$show_header,
 * 			$colorscheme,
 * 			$font,
 * 			$border_color,
 * 			$show_recommendations,
 * 			$filter,
 * 			$ref
 * 		);
 * }
 * </code>
 */
function wp_connect_activity_feed( $domain, $width, $height, $show_header, $colorscheme, $font, $border_color, $show_recommendations, $filter, $ref, $echo = TRUE ){

	$plugin_file = WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/WordpressConnectActivityFeed.php';

	$response = '';

	if ( file_exists( $plugin_file ) ){
		require_once( $plugin_file );

		if ( empty( $domain ) ){ $domain = $_SERVER['HTTP_HOST']; }
		
		$response = WordpressConnectActivityFeed::getHtml(
			$domain, $width, $height, $show_header, $colorscheme, $font, $border_color, $show_recommendations, $filter, $ref
		);

	}
	else {
		$response = wp_connect_aux_get_error_message( $plugin_file );
	}

	if ( $echo === TRUE ){ echo $response; }
	else { return $response; }

}
endif;

if ( !function_exists( 'wp_connect_comments' ) ) :

/**
 * This function renders a Facebook comments box based on the passed values.
 * It should/will be used by theme designers who would like to add specific
 * comment boxes to their themes.
 *
 * @param string $href				The url of the comments page
 * @param int $width				The plugin's width
 * @param int $number_of_comments	The number of comments to display
 * @param string $colorscheme		The comments box colorscheme
 * @param boolean $echo				A boolean value specifying whether the
 * 									comments box should be printed or returned
 * 									into a PHP variable
 *
 * @access public
 * @since	2.0
 * @see 	http://developers.facebook.com/docs/reference/plugins/comments/
 *
 * @example
 *
 * <code>
 * // this file is required to enable the use of Wordpress Connect constants
 * $wpc_constants_file = WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php';
 *
 * if ( file_exists( $wpc_constants_file ) && function_exists( 'wp_connect_comments' ) ){
 *
 * 		require_once( $wpc_constants_file );
 *
 *		$comments_url = 'http://www.mysite.com/my-post';
 *		$number_of_posts = 10;
 *		$comments_width = 480;
 *		$colorscheme = WPC_THEME_DARK; // or WPC_THEME_LIGHT
 *
 * 		// render the comments box in place
 * 		wp_connect_comments(
 * 			$comments_url,
 * 			$number_of_comments,
 * 			$comments_width,
 * 			$colorscheme
 * 		);
 * }
 * </code>
 */
function wp_connect_comments( $href, $number_of_comments, $width, $colorscheme, $echo = TRUE ){

	$plugin_file = WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/WordpressConnectComments.php';

	$response = '';

	if ( file_exists( $plugin_file ) ){
		require_once( $plugin_file );

		if ( empty( $href ) ){ $href = ( in_the_loop() ) ? get_permalink() : get_home_url(); }
		
		$response = WordpressConnectComments::getHtml(
			$href, $width, $number_of_comments, $colorscheme
		);

	}
	else {
		$response = wp_connect_aux_get_error_message( $plugin_file );
	}

	if ( $echo === TRUE ){ echo $response; }
	else { return $response; }

}

endif;

if ( !function_exists( 'wp_connect_comments_default' ) ) :

/**
 * This function renders a Facebook comments box with the default settings,
 * i.e the current settings set by the user via the dashboard.
 *
 * It should/will be used by theme designers who would like to add specific
 * comment boxes to their themes.
 *
 * @param string $href			The url of the comments page
 *
 * @param boolean $echo			A boolean value specifying whether the
 * 								comments box should be printed or returned
 * 								into a PHP variable
 *
 * @access	public
 * @since	2.0
 * @see 	wp_connect_comments
 *
 * @example
 *
 * <code>
 *
 * if ( function_exists( 'wp_connect_comments_default' ) ){
 *
 *		$comments_url = 'http://www.mysite.com/my-post';
 *
 * 		// render the comments box in place
 * 		wp_connect_comments_default( $comments_url );
 *
 * }
 * </code>
 */
function wp_connect_comments_default( $href, $echo = TRUE ){

	$plugin_file = WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/WordpressConnectComments.php';

	$response = '';

	if ( file_exists( $plugin_file ) ){
		require_once( $plugin_file );

		if ( empty( $href ) ){ $href = ( in_the_loop() ) ? get_permalink() : get_home_url(); }
		
		$response = WordpressConnectComments::getDefaultHtml( $href );

	}
	else {
		$response = wp_connect_aux_get_error_message( $plugin_file );
	}

	if ( $echo === TRUE ){ echo $response; }
	else { return $response; }

}

endif;

if ( !function_exists( 'wp_connect_facepile' ) ) :

/**
 * This function renders a Facebook Facepile based on the passed values.
 * It should/will be used by theme designers who would like to add specific
 * facepile(s) to their themes.
 *
 * @param string $url			If you want the Facepile to display friends
 * 								who have liked your page, specify the URL of
 * 								the page here.
 *
 * @param int $width			the width of the plugin
 *
 * @param int $max_rows			the maximum number of rows of faces to display
 *
 * @param boolean $echo			A boolean value specifying whether the
 * 								comments box should be printed or returned
 * 								into a PHP variable
 *
 * @access  public
 * @see 	http://developers.facebook.com/docs/reference/plugins/facepile/
 * @since	2.0
 *
 * @example
 *
 * <code>
 * if ( function_exists( 'wp_connect_facepile' ) ){
 *
 *		$facepile_url = 'http://www.mysite.com/';
 *		$width = 480;
 *		$max_rows = 10;
 *
 * 		// render the facepile box in place
 * 		wp_connect_facepile(
 * 			$facepile_url,
 * 			$width,
 * 			$max_rows
 * 		);
 * }
 * </code>
 */
function wp_connect_facepile( $url, $width, $max_rows, $echo = TRUE ){

	$plugin_file = WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/WordpressConnectFacepile.php';

	$response = '';

	if ( file_exists( $plugin_file ) ){
		require_once( $plugin_file );

		if ( empty( $url ) ){ $url = ( in_the_loop() ) ? get_permalink() : get_home_url(); }
		
		$response = WordpressConnectFacepile::getHtml( $url, $width, $max_rows );

	}
	else {
		$response = wp_connect_aux_get_error_message( $plugin_file );
	}

	if ( $echo === TRUE ){ echo $response; }
	else { return $response; }

}

endif;

if ( !function_exists( 'wp_connect_like_box' ) ) :

/**
 * This function renders a Facebook like box based on the passed values.
 * It should/will be used by theme designers who would like to add specific
 * like box(es) to their themes. The like box will be rendered only if the
 * user has specified the Like Box url through the WordPress Connect settin
 * pages. Otherwise an empty string will be printer/returned.
 *
 * @param int $width			the width of the plugin
 *
 * @param int $height			the width of the plugin
 *
 * @param string $colorscheme	valid values are:
 * 									WPC_THEME_LIGHT
 * 									WPC_THEME_DARK
 *
 * @param string $show_faces	valid values are:
 * 									WPC_OPTION_DISABLED
 * 									WPC_OPTION_ENABLED
 *
 *
 * @param string $show_stream	valid values are:
 * 									WPC_OPTION_DISABLED
 * 									WPC_OPTION_ENABLED
 *
 * @param string $show_header	valid values are:
 * 									WPC_OPTION_DISABLED
 * 									WPC_OPTION_ENABLED
 *
 * @param boolean $echo			A boolean value specifying whether the
 * 								comments box should be printed or returned
 * 								into a PHP variable
 *
 * @access  public
 * @see 	http://developers.facebook.com/docs/reference/plugins/like-box/
 * @since	2.0
 *
 * @example
 *
 *
 * <code>
 * // this file is required to enable the use of Wordpress Connect constants
 * $wpc_constants_file = WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php';
 *
 * if ( file_exists( $wpc_constants_file ) && function_exists( 'wp_connect_like_box' ) ){
 *
 * 		require_once( $wpc_constants_file );
 *
 *		$like_box_width = 480;
 *		$like_box_height = 180;
 *		$colorscheme = WPC_THEME_DARK; // or WPC_THEME_LIGHT
 *		$show_faces = WPC_OPTION_ENABLED; // or WPC_OPTION_DISABLED
 *		$show_stream = WPC_OPTION_ENABLED; // or WPC_OPTION_DISABLED
 *		$show_header = WPC_OPTION_ENABLED; // or WPC_OPTION_DISABLED
 *
 * 		// render the like box in place
 * 		wp_connect_like_box(
 * 			$like_box_width,
 * 			$like_box_height,
 * 			$colorscheme,
 * 			$show_faces,
 * 			$show_stream,
 * 			$show_header
 * 		);
 * }
 * </code>
 */
function wp_connect_like_box( $width, $height, $colorscheme, $show_faces, $show_stream, $show_header, $echo = TRUE ){

	$response = '';

	$options = get_option( WPC_OPTIONS_LIKE_BOX );
	if ( !empty( $options ) && isset( $options[ WPC_OPTIONS_LIKE_BOX_URL ] ) && !empty( $options[ WPC_OPTIONS_LIKE_BOX_URL ] ) ){

		$plugin_file = WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/WordpressConnectLikeBox.php';

		if ( file_exists( $plugin_file ) ){
			require_once( $plugin_file );

			$url = $options[ WPC_OPTIONS_LIKE_BOX_URL ];
			$response = WordpressConnectLikeBox::getHtml(
				$url, $width, $height, $colorscheme, $show_faces, $show_stream, $show_header
			);

		}
		else {
			$response = wp_connect_aux_get_error_message( $plugin_file );
		}
	}

	if ( $echo === TRUE ){ echo $response; }
	else { return $response; }

}

endif;

if ( !function_exists( 'wp_connect_like_button' ) ) :

/**
 * This function renders a Facebook like button based on the passed values.
 *
 * It should/will be used by theme designers who would like to add specific
 * like button to their themes.
 *
 * @param string $url			the like button url
 *
 * @param string $send_button	valid values are:
 * 									WPC_OPTION_DISABLED
 * 									WPC_OPTION_ENABLED
 *
 * @param string $layout		valid values are:
 * 									WPC_LAYOUT_STANDARD
 * 									WPC_LAYOUT_BUTTON_COUNT
 * 									WPC_LAYOUT_BOX_COUNT
 *
 * @param int $width			the width of the like button
 *
 * @param string $show_faces	valid values are:
 * 									WPC_OPTION_DISABLED
 * 									WPC_OPTION_ENABLED
 *
 * @param string $verb			valid values are:
 * 									WPC_ACTION_LIKE
 * 									WPC_ACTION_RECOMMEND
 *
 * @param string $colorscheme	valid values are:
 * 									WPC_THEME_LIGHT
 * 									WPC_THEME_DARK
 *
 * @param string $font			valid values are:
 * 									WPC_FONT_ARIAL
 * 									WPC_FONT_LUCIDA_GRANDE
 * 									WPC_FONT_SEGOE_UI
 * 									WPC_FONT_TAHOMA
 * 									WPC_FONT_TREBUCHET_MS
 * 									WPC_FONT_VERDANA
 *
 * @param string $ref			a label for tracking referrals
 *
 * @param boolean $echo			A boolean value specifying whether the
 * 								like button should be printed or returned
 * 								into a PHP variable
 *
 * @since	2.0
 * @see 	http://developers.facebook.com/docs/reference/plugins/like/
 *
 * @example
 *
 * <code>
 * // this file is required to enable the use of Wordpress Connect constants
 * $wpc_constants_file = WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php';
 *
 * if ( file_exists( $wpc_constants_file ) && function_exists( 'wp_connect_like_button' ) ){
 *
 * 		require_once( $wpc_constants_file );
 *
 *		$like_url = 'http://www.mysite.com/my-post';
 *		$send_button = WPC_OPTION_DISABLED; // or WPC_OPTION_ENABLED
 *		$layout = WPC_LAYOUT_STANDARD;
 *		$like_button_width = 480;
 *		$show_faces = WPC_OPTION_ENABLED; // or WPC_OPTION_DISABLED
 *		$verb = WPC_ACTION_RECOMMEND;
 *		$colorscheme = WPC_THEME_DARK; // or WPC_THEME_LIGHT
 *		$font = WPC_FONT_DEFAULT;
 *		$ref = 'top';
 *
 * 		// render the like button in place
 * 		wp_connect_like_button(
 * 			$like_url,
 * 			$send_button,
 * 			$layout,
 * 			$like_button_width,
 * 			$show_faces,
 * 			$verb,
 * 			$colorscheme,
 * 			$font,
 * 			$ref
 * 		);
 * }
 * </code>
 */
function wp_connect_like_button( $url, $send_button, $layout, $width, $show_faces, $verb, $colorscheme, $font, $ref, $echo = TRUE ){

	$response = '';

	$plugin_file = WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/WordpressConnectLikeButton.php';

	if ( file_exists( $plugin_file ) ){
		require_once( $plugin_file );

		if ( empty( $url ) ){ $url = ( in_the_loop() ) ? get_permalink() : get_home_url(); }
		
		$response = WordpressConnectLikeButton::getHtml(
			$url, $send_button, $layout, $width, $show_faces, $verb, $colorscheme, $font, $ref
		);

	}
	else {
		$response = wp_connect_aux_get_error_message( $plugin_file );
	}

	if ( $echo === TRUE ){ echo $response; }
	else { return $response; }

}

endif;

if ( !function_exists( 'wp_connect_like_button_default' ) ) :

/**
 * This function renders a Facebook like button with the default settings,
 * ie the current settings set by the user via the dashboard.
 *
 * It should/will be used by theme designers who would like to add specific
 * like button to their themes.
 *
 * @param string $url			the like button url
 *
 * @param boolean $echo			A boolean value specifying whether the
 * 								like button should be printed or returned
 * 								into a PHP variable
 *
 * @since	2.0
 * @see 	wp_connect_like_button
 *
 * @example
 *
 * <code>
 * if ( function_exists( wp_connect_like_button_default ) ){
 *
 *		$like_url = 'http://www.mysite.com/my-post';
 *
 * 		// render the like button in place
 * 		wp_connect_like_button_default( $like_url );
 * }
 * </code>
 */
function wp_connect_like_button_default( $url, $echo = TRUE ){

	$plugin_file = WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/WordpressConnectLikeButton.php';

	$response = '';

	if ( file_exists( $plugin_file ) ){
		require_once( $plugin_file );

		if ( empty( $url ) ){ $url = ( in_the_loop() ) ? get_permalink() : get_home_url(); }
		
		$response = WordpressConnectLikeButton::getDefaultHtml( $url );

	}
	else {
		$response = wp_connect_aux_get_error_message( $plugin_file );
	}

	if ( $echo === TRUE ){ echo $response; }
	else { return $response; }

}

endif;

if ( !function_exists( 'wp_connect_live_stream' ) ) :

/**
 * This function renders a Facebook Live Stream based on the passed values.
 * It should/will be used by theme designers who would like to add specific
 * live stream(s) to their themes.
 *
 * @param int $width				the width of the plugin
 *
 * @param int $height				the height of the plugin
 *
 * @param string $xid				the xid of this plugin instance
 *
 * @param string $attribution		the via attribution url
 *
 * @param string $post_to_friends	valid values are:
 * 										WPC_OPTION_DISABLED
 * 										WPC_OPTION_ENABLED
 *
 * @param boolean $echo				A boolean value specifying whether the
 * 									comments box should be printed or returned
 * 									into a PHP variable
 * @access  public
 * @see 	http://developers.facebook.com/docs/reference/plugins/live-stream/
 * @since	2.0
 *
 * @example
 *
 * <code>
 * // this file is required to enable the use of Wordpress Connect constants
 * $wpc_constants_file = WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php';
 *
 * if ( file_exists( $wpc_constants_file ) && function_exists( 'wp_connect_live_stream' ) ){
 *
 * 		require_once( $wpc_constants_file );
 *
 *		$live_stream_width = 320;
 *		$live_stream_height = 480;
 *		$xid = md5( 'my-live-stream' );
 *		$attribution = '';
 *		$post_to_friends = WPC_OPTION_ENABLED; // or WPC_OPTION_DISABLED
 *
 * 		// render the live stream box in place
 * 		wp_connect_live_stream(
 * 			$live_stream_width,
 * 			$live_stream_height,
 * 			$xid,
 * 			$attribution,
 * 			$post_to_friends
 * 		);
 * }
 * </code>
 */
function wp_connect_live_stream( $width, $height, $xid, $attribution, $post_to_friends, $echo = TRUE ){

	$plugin_file = WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/WordpressConnectLiveStream.php';

	$response = '';

	if ( file_exists( $plugin_file ) ){
		require_once( $plugin_file );

		$response = WordpressConnectLiveStream::getHtml(
			$width, $height, $xid, $attribution, $post_to_friends
		);

	}
	else {
		$response = wp_connect_aux_get_error_message( $plugin_file );
	}

	if ( $echo === TRUE ){ echo $response; }
	else { return $response; }

}

endif;

if ( !function_exists( 'wp_connect_login_button' ) ) :

/**
 * This function renders a Facebook Login Button based on the passed values.
 * It should/will be used by theme designers who would like to add specific
 * login button(s) to their themes.
 *
 * @param string $show_faces	valid values are:
 * 									WPC_OPTION_DISABLED
 * 									WPC_OPTION_ENABLED
 *
 * @param int $width			the width of the plugin
 *
 * @param int $max_rows			the maximum number of rows of faces to display
 *
 * @param string $perms			a comma separated list of extended permissions
 *
 * @param boolean $echo			A boolean value specifying whether the
 * 								comments box should be printed or returned
 * 								into a PHP variable
 * @access  public
 * @see 	http://developers.facebook.com/docs/reference/plugins/login/
 * @see 	http://developers.facebook.com/docs/authentication/permissions/
 * @since	2.0
 *
 * @example
 *
 * <code>
 * // this file is required to enable the use of Wordpress Connect constants
 * $wpc_constants_file = WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php';
 *
 * if ( file_exists( $wpc_constants_file ) && function_exists( 'wp_connect_login_button' ) ){
 *
 * 		require_once( $wpc_constants_file );
 *
 *		$show_faces = WPC_OPTION_ENABLED;
 *		$login_button_width = 480;
 *		$max_rows = 2;
 *		$perms = 'email';
 *
 * 		// render the login button in place
 * 		wp_connect_login_button(
 * 			$show_faces,
 * 			$login_button_width,
 * 			$max_rows,
 * 			$perms
 * 		);
 * }
 * </code>
 */
function wp_connect_login_button( $show_faces, $width, $max_rows, $perms, $echo = TRUE ){

	$plugin_file = WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/WordpressConnectLoginButton.php';

	$response = '';

	if ( file_exists( $plugin_file ) ){
		require_once( $plugin_file );

		$response = WordpressConnectLoginButton::getHtml( $show_faces, $width, $max_rows, $perms );

	}
	else {
		$response = wp_connect_aux_get_error_message( $plugin_file );
	}

	if ( $echo === TRUE ){ echo $response; }
	else { return $response; }

}

endif;

if ( !function_exists( 'wp_connect_recommendations' ) ) :

/**
 * This function renders a Facebook Recommendations based on the passed values.
 * It should/will be used by theme designers who would like to add specific
 * recommendation(s) to their themes.
 *
 * @param string $domain		the domain to show activity for
 * 								e.g. 'www.example.com'
 *
 * @param int $width			the width of the plugin
 *
 * @param int $height			the height of the plugin
 *
 * @param string $show_header	valid values are:
 * 									WPC_OPTION_DISABLED
 * 									WPC_OPTION_ENABLED
 *
 * @param string $colorscheme	valid values are:
 * 									WPC_THEME_LIGHT
 * 									WPC_THEME_DARK
 *
 * @param string $font			valid values are:
 * 									WPC_FONT_ARIAL
 * 									WPC_FONT_LUCIDA_GRANDE
 * 									WPC_FONT_SEGOE_UI
 * 									WPC_FONT_TAHOMA
 * 									WPC_FONT_TREBUCHET_MS
 * 									WPC_FONT_VERDANA
 *
 * @param string $border_color	the border color of the plugin
 *
 * @param string $ref			a label for tracking referrals
 *
 * @param boolean $echo			A boolean value specifying whether the
 * 								comments box should be printed or returned
 * 								into a PHP variable
 * @access  public
 * @see 	http://developers.facebook.com/docs/reference/plugins/recommendations/
 * @since	2.0
 *
 * @example
 *
 * <code>
 * // this file is required to enable the use of Wordpress Connect constants
 * $wpc_constants_file = WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php';
 *
 * if ( file_exists( $wpc_constants_file ) && function_exists( 'wp_connect_recommendations' ) ){
 *
 * 		require_once( $wpc_constants_file );
 *
 *		$domain = 'mysite.com';
 *		$width = 240;
 *		$height = 480;
 *		$show_header = WPC_OPTION_ENABLED;
 *		$colorscheme = WPC_THEME_LIGHT;
 *		$font = WPC_FONT_TAHOMA;
 *		$border_color = '#FF0';
 *		$ref = '';
 *
 * 		// render the recommendations box in place
 * 		wp_connect_recommendations(
 * 			$domain,
 * 			$width,
 * 			$height,
 * 			$show_header,
 * 			$colorscheme,
 * 			$font,
 * 			$border_color,
 * 			$ref
 * 		);
 * }
 * </code>
 */
function wp_connect_recommendations( $domain, $width, $height, $show_header, $colorscheme, $font, $border_color, $ref, $echo = TRUE ){

	$plugin_file = WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/WordpressConnectRecommendations.php';

	$response = '';

	if ( file_exists( $plugin_file ) ){
		require_once( $plugin_file );

		if ( empty( $domain ) ){ $domain = $_SERVER['HTTP_HOST']; }
		
		$response = WordpressConnectRecommendations::getHtml(
			$domain, $width, $height, $show_header, $colorscheme, $font, $border_color, $ref
		);

	}
	else {
		$response = wp_connect_aux_get_error_message( $plugin_file );
	}

	if ( $echo === TRUE ){ echo $response; }
	else { return $response; }

}

endif;

if ( !function_exists( 'wp_connect_send_button' ) ) :

/**
 * This function renders a Facebook send button based on the passed values.
 * It should/will be used by theme designers who would like to add specific
 * send button(s) to their themes.
 *
 * @param string $url			the like button url
 *
 * @param string $font			valid values are:
 * 									WPC_FONT_ARIAL
 * 									WPC_FONT_LUCIDA_GRANDE
 * 									WPC_FONT_SEGOE_UI
 * 									WPC_FONT_TAHOMA
 * 									WPC_FONT_TREBUCHET_MS
 * 									WPC_FONT_VERDANA
 *
 * @param string $colorscheme	valid values are:
 * 									WPC_THEME_LIGHT
 * 									WPC_THEME_DARK
 *
 * @param string $ref			a label for tracking referrals
 * @param int $height			the height of the send button
 *
 * @access  public
 * @see 	http://developers.facebook.com/docs/reference/plugins/send/
 * @since	2.0
 *
 * @example
 *
 * <code>
 * // this file is required to enable the use of Wordpress Connect constants
 * $wpc_constants_file = WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php';
 *
 * if ( file_exists( $wpc_constants_file ) && function_exists( 'wp_connect_send_button' ) ){
 *
 * 		require_once( $wpc_constants_file );
 *
 *		$send_button_url = 'http://www.my-site.com/post/1/';
 *		$font = WPC_FONT_TREBUCHET_MS;
 *		$colorscheme = WPC_THEME_DARK;
 *		$ref = '';
 *		$height = 300;
 *
 * 		// render the comments box in place
 * 		wp_connect_send_button(
 * 			$send_button_url,
 * 			$font,
 * 			$colorscheme,
 * 			$ref,
 * 			$height
 * 		);
 * }
 * </code>
 */
function wp_connect_send_button( $url, $font, $colorscheme, $ref, $height, $echo = TRUE ){

	$plugin_file = WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/WordpressConnectSendButton.php';

	$response = '';

	if ( file_exists( $plugin_file ) ){
		require_once( $plugin_file );

		if ( empty( $url ) ){ $url = ( in_the_loop() ) ? get_permalink() : get_home_url(); }
		
		$response = WordpressConnectSendButton::getHtml( $url, $font, $colorscheme, $ref, $height );

	}
	else {
		$response = wp_connect_aux_get_error_message( $plugin_file );
	}

	if ( $echo === TRUE ){ echo $response; }
	else { return $response; }

}

endif;

/**
 * Auxilliary function to handle plugin errors
 *
 * @param strintg $plugin_file	the path to the plugin file
 *
 * @return	the error message
 */
function wp_connect_aux_get_error_message( $plugin_file ){

	$response = sprintf(
		__( 'Required file does not exists at the specified path: %s. Please make sure that Wordpress Connect is installed and active ', WPC_TEXT_DOMAIN ),
		$plugin_file
	);

	return $response;
}

?>