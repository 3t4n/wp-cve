<?php

/**
 * If the classes that extend this class are access directly through the 'editor' folder
 * php files, WP_PLUGIN_DIR will not exist
 */
if ( defined( WP_PLUGIN_DIR ) ){
	require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php' );
	require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/AbstractWordpressConnectPlugin.php' );
}
else {
	require_once( 'AbstractWordpressConnectPlugin.php' );
}

/**
 * @author Tomas Vorobjov
 * @version 2.0
 * @date 5 May 2011
 *
 * @file WordpressConnectLikeButton.php
 *
 * This class provides methods for displaying the facebook like button plugin
 */
class WordpressConnectLikeButton extends AbstractWordpressConnectPlugin {

	/**
	 * Handles the wp content. The function will determine whether the
	 * like button is enabled for a particular post/page, then it will
	 * determine the position at which it is supposed to be displayed
	 * within the post/page
	 *
	 * @param string $content
	 *
	 * @access private
	 */
	public function contentHandler( $content ){

//
		global $post;

		$enabled = AbstractWordpressConnectPlugin::getPostMetaOrDefault(
			$post->ID, WordpressConnectCustomFields::PREFIX . WPC_CUSTOM_FIELD_NAME_LIKE_BUTTON_ENABLE, TRUE,
			WPC_OPTIONS_LIKE_BUTTON, WPC_OPTIONS_LIKE_BUTTON_ENABLED
		);

		if ( $enabled != WPC_OPTION_ENABLED ){ return $content; }

		$isNotEnabledOnCurrentView = ! AbstractWordpressConnectPlugin::isEnabledOnCurrentView( WPC_OPTIONS_LIKE_BUTTON );
		if ( $isNotEnabledOnCurrentView ){ return $content; }
//

//		if ( WordpressConnectLikeButton::isEnabled() === FALSE ){
//			return $content;
//		}

		$html = '';

		$position = get_post_meta(
			$post->ID,
			WordpressConnectCustomFields::PREFIX . WPC_CUSTOM_FIELD_NAME_LIKE_BUTTON_POSITION,
			TRUE
		);

		$position = AbstractWordpressConnectPlugin::getPostMetaOrDefault(
			$post->ID, WordpressConnectCustomFields::PREFIX . WPC_CUSTOM_FIELD_NAME_LIKE_BUTTON_POSITION, TRUE,
			WPC_OPTIONS_LIKE_BUTTON, WPC_OPTIONS_LIKE_BUTTON_POSITION
		);

		$href = get_permalink( $post->ID );

		$code = WordpressConnectLikeButton::getDefaultHtml( $href );

		switch( $position ){
			case WPC_CUSTOM_FIELD_VALUE_POSITION_CUSTOM : {
				return $content;
				break;
			}
			case WPC_CUSTOM_FIELD_VALUE_POSITION_TOP : {
				$html = $code . $content;
				break;
			}
			case WPC_CUSTOM_FIELD_VALUE_POSITION_BOTTOM : {}
			default : {
				$html = $content . $code;
			}
		}

		return $html;

	}

	/**
	 * Renders the Facebook Comments Social Plugin based on the default
	 * (currently set) settings/options. This method can be used on its
	 * own outside the loop when one wished to you the default (currently
	 * set) options of the width, number of comments and the color scheme.
	 *
	 * <code>
	 *		$url = home_url();
	 *		echo WordpressConnectComments::getDefaultHtml( $url );
	 * </code>
	 *
	 * @param string $href			The url of the comments box
	 *
	 * @access public
	 * @since	2.0
	 * @static
	 */
	public static function getDefaultHtml( $href ){

		$general_options = get_option( WPC_OPTIONS );
		$colorscheme = $general_options[ WPC_OPTIONS_THEME ];

		$like_options = get_option( WPC_OPTIONS_LIKE_BUTTON );
		$width = $like_options[ WPC_OPTIONS_LIKE_BUTTON_WIDTH ];

		$send_button = $like_options[ WPC_OPTIONS_LIKE_BUTTON_SEND ];
		$layout = $like_options[ WPC_OPTIONS_LIKE_BUTTON_LAYOUT ];
		$width = $like_options[ WPC_OPTIONS_LIKE_BUTTON_WIDTH ];
		$show_faces = $like_options[ WPC_OPTIONS_LIKE_BUTTON_FACES ];
		$verb = $like_options[ WPC_OPTIONS_LIKE_BUTTON_VERB ];
		$font = $like_options[ WPC_OPTIONS_LIKE_BUTTON_FONT ];
		$ref = $like_options[ WPC_OPTIONS_LIKE_BUTTON_REF ];

		return WordpressConnectLikeButton::getHtml( $href, $send_button, $layout, $width, $show_faces, $verb, $colorscheme, $font, $ref );

	}


	/**
	 * Returns the Facebook like button fbml code based on the passed values.
	 * This function should not be called directly - it is reserved for the
	 * like button widget (and the for the WordpressConnectLikeButton::getHtml method)
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
	 * @access  public
	 * @see 	http://developers.facebook.com/docs/reference/plugins/like/
	 * @since	2.0
	 * @static
	 */
	public static function getFbml( $url, $send_button, $layout, $width, $show_faces, $verb, $colorscheme, $font, $ref ){

		$send_button_value = ( $send_button == WPC_OPTION_ENABLED ) ? 'true' : 'false';
		$show_faces_value = ( $show_faces == WPC_OPTION_ENABLED ) ? 'true' : 'false';

		$fbml .= sprintf(
			'<fb:like href="%s" send="%s" layout="%s" width="%d" show_faces="%s" action="%s" colorscheme="%s" font="%s" ref="%s"></fb:like>',
			$url, $send_button_value, $layout, $width, $show_faces_value, $verb, $colorscheme, $font, $ref
		);

		return $fbml;
	}

	/**
	 * This function renders a Facebook like button based on the passed values.
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
	 * @access  public
	 * @see 	http://developers.facebook.com/docs/reference/plugins/like/
	 * @since	2.0
	 * @static
	 */
	public static function getHtml( $url, $send_button, $layout, $width, $show_faces, $verb, $colorscheme, $font, $ref ){

		$code = '<!-- Wordpress Connect Like Button v' . WPC_VERSION . ' -->' . "\n";
		$code .= '<p class="wp-connect-like-button">';
		$code .= WordpressConnectLikeButton::getFbml( $url, $send_button, $layout, $width, $show_faces, $verb, $colorscheme, $font, $ref );
		$code .= '</p>';
		$code .= "\n" . '<!-- Wordpress Connect Like Button -->' . "\n";

		return $code;

	}

	/**
	 * Creates a shortcode for the plugin based on the specified value
	 *
	 * @param string $href
	 * @param string $send_button
	 * @param string $layout
	 * @param int $width
	 * @param string $show_faces
	 * @param string $verb
	 * @param string $colorscheme
	 * @param string $font
	 * @param string $ref
	 *
	 * @return The shortcode for this plugin
	 *
	 * @access public
	 * @since 2.0
	 * @static
	 */
	public static function getShortCode( $href, $send_button, $layout, $width, $show_faces, $verb, $colorscheme, $font, $ref ){

		$code = '[wp_connect_like_button href="%s" send_button="%s" layout="%s" width="%d" show_faces="%s" verb="%s" colorscheme="%s" font="%s" ref="%s" /]';

		$shortcode = sprintf(
			$code,
			$href, $send_button, $layout, $width, $show_faces, $verb, $colorscheme, $font, $ref
		);

		return $shortcode;

	}

	/**
	 * The handler for the wordpress shortcode.
	 *
	 * @param array $atts		array of attributes
	 * @param string $content	text within enclosing form of shortcode element
	 *
	 * @example
	 * <pre>
	 * [wp_connect_like_button href="<url>" send_button="disabled" layout="standard" width="480" show_faces="enabled" verb="like" colorscheme="like" font="arial" ref="top" /]
	 * </pre>
	 */
	public static function shortcodeHandler( $atts, $content ){

		if ( is_feed() ){ return ''; }		
		
		global $post;

		$general_options = get_option( WPC_OPTIONS );
		$colorscheme_default = $general_options[ WPC_OPTIONS_THEME ];

		$like_options = get_option( WPC_OPTIONS_LIKE_BUTTON );
		$width_default = $like_options[ WPC_OPTIONS_LIKE_BUTTON_WIDTH ];

		$send_button_default = $like_options[ WPC_OPTIONS_LIKE_BUTTON_SEND ];
		$layout_default = $like_options[ WPC_OPTIONS_LIKE_BUTTON_LAYOUT ];
		$show_faces_default = $like_options[ WPC_OPTIONS_LIKE_BUTTON_FACES ];
		$verb_default = $like_options[ WPC_OPTIONS_LIKE_BUTTON_VERB ];
		$font_default = $like_options[ WPC_OPTIONS_LIKE_BUTTON_FONT ];
		$ref = $like_options[ WPC_OPTIONS_LIKE_BUTTON_REF ];

		extract( shortcode_atts( array(
			'href' => '',
			'send_button' => $send_button_default,
			'layout' => $layout_default,
			'width' => $width_default,
			'show_faces' => $show_faces_default,
			'verb' => $verb_default,
			'colorscheme' => $colorscheme_default,
			'font' => $font_default,
			'ref' => $ref
		), $atts ) );

		if ( empty( $href ) ){ $href = get_permalink( $post->ID ); }
		if ( empty( $send_button ) ){ $send_button = $send_button_default; }
		if ( empty( $layout ) ){ $layout = $layout_default; }
		if ( empty( $width ) ){ $width = $width_default; }
		if ( empty( $show_faces ) ){ $show_faces = $show_faces_default; }
		if ( empty( $verb ) ){ $verb = $verb_default; }
		if ( empty( $colorscheme ) ){ $colorscheme = $colorscheme_default; }
		if ( empty( $font ) ){ $font = $font_default; }
		
		return WordpressConnectLikeButton::getHtml(
			$href, $send_button, $layout, $width, $show_faces, $verb, $colorscheme, $font, $ref
		);

	}
}

?>