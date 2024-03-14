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
 * @date 19 Apr 2011
 *
 * @file WordpressConnectComments.php
 *
 * This class provides methods for displaying the comments facebook plugin
 */
class WordpressConnectComments extends AbstractWordpressConnectPlugin {

	/**
	 * Handles the wp content. The function will determine whether the
	 * comments box is enabled for a particular post/page, then it will
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
			$post->ID, WordpressConnectCustomFields::PREFIX . WPC_CUSTOM_FIELD_NAME_COMMENTS_ENABLE, TRUE,
			WPC_OPTIONS_COMMENTS, WPC_OPTIONS_COMMENTS_ENABLED
		);

		
		if ( !empty( $enabled ) && $enabled != WPC_OPTION_ENABLED ){ return $content; }

		$isNotEnabledOnCurrentView = ! AbstractWordpressConnectPlugin::isEnabledOnCurrentView( WPC_OPTIONS_COMMENTS );

		if ( $isNotEnabledOnCurrentView ){ return $content; }
//
//		if ( WordpressConnectComments::isEnabled() === FALSE ){
//			return $content;
//		}

		$html = '';

		$position = AbstractWordpressConnectPlugin::getPostMetaOrDefault(
			$post->ID, WordpressConnectCustomFields::PREFIX . WPC_CUSTOM_FIELD_NAME_COMMENTS_POSITION, TRUE,
			WPC_OPTIONS_COMMENTS, WPC_OPTIONS_COMMENTS_POSITION
		);

		$href = get_permalink( $post->ID );

		$code = WordpressConnectComments::getDefaultHtml( $href );

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

		$comments_options = get_option( WPC_OPTIONS_COMMENTS );
		$width = $comments_options[ WPC_OPTIONS_COMMENTS_WIDTH ];
		$number_of_comments = $comments_options[ WPC_OPTIONS_COMMENTS_NUMBER ];

		return WordpressConnectComments::getHtml( $href, $width, $number_of_comments, $colorscheme );

	}


	/**
	 * Returns the fbml code for the comments facebook plugin. This
	 * function should not be called directly - it is reserved for the
	 * comments widget (and the for the WordpressConnectComments::getHtml
	 * method)
	 *
	 * @param string $href				The url of the comments page
	 * @param int $width				The plugin's width
	 * @param int $number_of_comments	The number of comments to display
	 * @param string $colorscheme		The comments box colorscheme
	 *
	 * @access  public
	 * @see 	http://developers.facebook.com/docs/reference/plugins/comments/
	 * @since	2.0
	 * @static
	 */
	public static function getFbml( $href, $width, $number_of_comments, $colorscheme ){

		$fbml .= sprintf(
			'<fb:comments href="%s" width="%d" num_posts="%d" colorscheme="%s"></fb:comments>',
			$href, $width, $number_of_comments, $colorscheme
		);

		return $fbml;
	}

	/**
	 * Returns the html code for the comments facebook plugin. This
	 * function should not be called directly; instead use
	 * <code>wp_connect_comments</code>, eg:
	 *
	 * @param string $href				The url of the comments page
	 * @param int $width				The plugin's width
	 * @param int $number_of_comments	The number of comments to display
	 * @param string $colorscheme		The comments box colorscheme
	 *
	 * @access  public
	 * @see 	http://developers.facebook.com/docs/reference/plugins/comments/
	 * @since	2.0
	 * @static
	 */
	public static function getHtml( $href, $width, $number_of_comments, $colorscheme ){

		$code = '<!-- Wordpress Connect Comments v' . WPC_VERSION . ' -->' . "\n";
		$code .= '<p class="wp-connect-comments">';
		$code .= WordpressConnectComments::getFbml( $href, $width, $number_of_comments, $colorscheme );
		$code .= '</p>';
		$code .= "\n" . '<!-- Wordpress Connect Comments -->' . "\n";

		return $code;
	}

	/**
	 * Creates a shortcode for the plugin based on the specified value
	 *
	 * @param string $href
	 * @param int $width
	 * @param int $number_of_comments
	 * @param string $colorscheme
	 *
	 * @return The shortcode for this plugin
	 *
	 * @access public
	 * @since 2.0
	 * @static
	 */
	public static function getShortCode( $href, $width, $number_of_comments, $colorscheme ){

		$code = '[wp_connect_comments href="%s" width="%d" num_posts="%d" colorscheme="%s" /]';
		$shortcode = sprintf( $code, $href, $width, $number_of_comments, $colorscheme );
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
	 * [wp_connect_comments num_posts="10" href="<url>" width="600" colorscheme="dark" /]
	 * </pre>
	 */
	public static function shortcodeHandler( $atts, $content ){

		if ( is_feed() ){ return ''; }		
		
		global $post;

		$general_options = get_option( WPC_OPTIONS );
		$colorscheme_default = $general_options[ WPC_OPTIONS_THEME ];

		$comments_options = get_option( WPC_OPTIONS_COMMENTS );
		$width_default = $comments_options[ WPC_OPTIONS_COMMENTS_WIDTH ];
		$number_of_comments_default = $comments_options[ WPC_OPTIONS_COMMENTS_NUMBER ];

		extract( shortcode_atts( array(
			'href' => '',
			'num_posts' => $number_of_comments_default,
			'width' => $width_default,
			'colorscheme' => $colorscheme_default
		), $atts ) );

		if ( empty( $href ) ){ $href = get_permalink( $post->ID ); }
		if ( empty( $width ) ){ $width = $width_default; }
		if ( empty( $number_of_comments ) ){ $number_of_comments = $number_of_comments_default; }
		if ( empty( $colorscheme ) ){ $colorscheme = $colorscheme_default; }

		return WordpressConnectComments::getHtml(
			$href, $width, $num_posts, $colorscheme
		);

	}
}

?>