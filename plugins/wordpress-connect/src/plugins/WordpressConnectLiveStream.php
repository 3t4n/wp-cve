<?php

require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php' );
//require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/AbstractWordpressConnectPlugin.php' );

/**
 * @author Tomas Vorobjov
 * @version 2.0
 * @date 12 May 2011
 *
 * @file WordpressConnectLiveStream.php
 *
 * This class provides methods for displaying the live stream plugin
 */
class WordpressConnectLiveStream {

	/**
	 * This function renders a Facebook Live Stream based on the passed values.
	 *
	 * @param int $width				the width of the plugin
	 *
	 * @param int $height				the height of the plugin
	 *
	 * @param string $xid				the xid of this plugin instance
	 *
	 * @param string $attribution		the via attribution url
	 *
	 * @param string $post_to_friends		valid values are:
	 * 											WPC_OPTION_DISABLED
	 * 											WPC_OPTION_ENABLED
	 *
	 * @access  public
	 * @see 	http://developers.facebook.com/docs/reference/plugins/live-stream/
	 * @since	2.0
	 * @static
	 */
	public static function getHtml( $width, $height, $xid, $attribution, $post_to_friends ){

		$code = '<!-- Wordpress Connect Live Stream v' . WPC_VERSION . ' -->' . "\n";
		$code .= '<p class="wp-connect-live-stream">';
		$code .= WordpressConnectLiveStream::getFbml( $width, $height, $xid, $attribution, $post_to_friends );
		$code .= '</p>';
		$code .= "\n" . '<!-- Wordpress Connect Live Stream -->' . "\n";

		return $code;

	}

	/**
	 * Returns the Facebook live stream fbml code based on the passed values.
	 * This function should not be called directly - it is reserved for the
	 * live stream widget (and the for the WordpressConnectLiveStream::getHtml method)
	 *
	 * @param int $width				the width of the plugin
	 *
	 * @param int $height				the height of the plugin
	 *
	 * @param string $xid				the xid of this plugin instance
	 *
	 * @param string $attribution		the via attribution url
	 *
	 * @param string $post_to_friends		valid values are:
	 * 											WPC_OPTION_DISABLED
	 * 											WPC_OPTION_ENABLED
	 *
	 * @access  public
	 * @see 	http://developers.facebook.com/docs/reference/plugins/live-stream/
	 * @since	2.0
	 * @static
	 */
	public static function getFbml( $width, $height, $xid, $attribution, $post_to_friends ){

		$post_to_friends_value = ( $post_to_friends == WPC_OPTION_ENABLED ) ? 'true' : 'false';

		$options = get_option( WPC_OPTIONS );

		$app_id = $options[ WPC_OPTIONS_APP_ID ];

		$fbml .= sprintf(
			'<fb:live-stream event_app_id="%s" width="%d" height="%d" xid="%s" via_url="%s" always_post_to_friends="%s"></fb:live-stream>',
			$app_id, $width, $height, $xid, $attribution, $post_to_friends_value
		);

		return $fbml;
	}
}

?>