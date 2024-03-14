<?php

require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php' );
//require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/AbstractWordpressConnectPlugin.php' );

/**
 * @author Tomas Vorobjov
 * @version 2.0
 * @date 12 May 2011
 *
 * @file WordpressConnectLikeBox
 *
 * This class provides methods for displaying the like box facebook plugin
 */
class WordpressConnectLikeBox {

	/**
	 * This function renders a Facebook like box based on the passed values.
	 *
	 * @param string $url			the facebook page url
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
	 * @access  public
	 * @see 	http://developers.facebook.com/docs/reference/plugins/like-box/
	 * @since	2.0
	 * @static
	 */
	public static function getHtml( $url, $width, $height, $colorscheme, $show_faces, $show_stream, $show_header ){

		$code = '<!-- Wordpress Connect Like Box v' . WPC_VERSION . ' -->' . "\n";
		$code .= '<p class="wp-connect-like-box">';
		$code .= WordpressConnectLikeBox::getFbml( $url, $width, $height, $colorscheme, $show_faces, $show_stream, $show_header );
		$code .= '</p>';
		$code .= "\n" . '<!-- Wordpress Connect Like Box -->' . "\n";

		return $code;

	}

	/**
	 * Returns the Facebook like box fbml code based on the passed values.
	 * This function should not be called directly - it is reserved for the
	 * like box widget (and the for the WordpressConnectLikeBox::getHtml method)
	 *
	 * @param string $url			the facebook page url
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
	 * @access  public
	 * @see 	http://developers.facebook.com/docs/reference/plugins/like-box/
	 * @since	2.0
	 * @static
	 */
	public static function getFbml( $url, $width, $height, $colorscheme, $show_faces, $show_stream, $show_header ){

		$show_faces_value = ( $show_faces == WPC_OPTION_ENABLED ) ? 'true' : 'false';
		$show_stream_value = ( $show_stream == WPC_OPTION_ENABLED ) ? 'true' : 'false';
		$show_header_value = ( $show_header == WPC_OPTION_ENABLED ) ? 'true' : 'false';

		$fbml .= sprintf(
			'<fb:like-box href="%s" width="%d" height="%d" colorscheme="%s" show_faces="%s" stream="%s" header="%s"></fb:like-box>',
			$url, $width, $height, $colorscheme, $show_faces_value, $show_stream_value, $show_header_value
		);

		return $fbml;
	}
}

?>