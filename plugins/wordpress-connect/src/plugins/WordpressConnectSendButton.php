<?php

require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php' );
//require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/AbstractWordpressConnectPlugin.php' );

/**
 * @author Tomas Vorobjov
 * @version 2.0
 * @date 11 May 2011
 *
 * @file WordpressConnectSendButton.php
 *
 * This class provides methods for displaying the facebook send button plugin
 */
class WordpressConnectSendButton {

	/**
	 * This function renders a Facebook send button based on the passed values.
	 *
	 * Please note that it is necessary to specify the height because widget
	 * containers will NOT automatically resize to show the dynamically loaded
	 * content (which is displayed below the send button after the user
	 * clicks on it)
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
	 * @static
	 */
	public static function getHtml( $url, $font, $colorscheme, $ref, $height ){

		$code = '<!-- Wordpress Connect Send Button v' . WPC_VERSION . ' -->' . "\n";
		$code .= '<p class="wp-connect-send-button">';
		$code .= WordpressConnectSendButton::getFbml( $url, $font, $colorscheme, $ref, $height );
		$code .= '</p>';
		$code .= "\n" . '<!-- Wordpress Connect Send Button -->' . "\n";

		return $code;

	}

	/**
	 * Returns the Facebook send button fbml code based on the passed values.
	 * This function should not be called directly - it is reserved for the
	 * send button widget (and the for the WordpressConnectSendButton::getHtml method)
	 *
	 * Please note that it is necessary to specify the height because widget
	 * containers will NOT automatically resize to show the dynamically loaded
	 * content (which is displayed below the send button after the user
	 * clicks on it)
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
	 * @static
	 */
	public static function getFbml( $url, $font, $colorscheme, $ref, $height ){

		$fbml .= sprintf(
			'<div class="facebook-send-button-container" style="height:%dpx;"><fb:send href="%s" font="%s" colorscheme="%s" ref="%s"></fb:send></div>',
			$height, $url, $font, $colorscheme, $ref
		);

		return $fbml;
	}
}


?>
