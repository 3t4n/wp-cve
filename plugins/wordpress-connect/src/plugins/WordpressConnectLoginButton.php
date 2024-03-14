<?php

require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php' );
//require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/AbstractWordpressConnectPlugin.php' );

/**
 * @author Tomas Vorobjov
 * @version 2.0
 * @date 12 May 2011
 *
 * @file WordpressConnectLoginButton
 *
 * This class provides methods for displaying the login button plugin
 */
class WordpressConnectLoginButton {

	/**
	 * This function renders a Facebook Login Button based on the passed values.
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
	 * @access  public
	 * @see 	http://developers.facebook.com/docs/reference/plugins/login/
	 * @see 	http://developers.facebook.com/docs/authentication/permissions/
	 * @since	2.0
	 * @static
	 */
	public static function getHtml( $show_faces, $width, $max_rows, $perms ){

		$code = '<!-- Wordpress Connect Login Button v' . WPC_VERSION . ' -->' . "\n";
		$code .= '<p class="wp-connect-login-button">';
		$code .= WordpressConnectLoginButton::getFbml( $show_faces, $width, $max_rows, $perms );
		$code .= '</p>';
		$code .= "\n" . '<!-- Wordpress Connect Login Button -->' . "\n";

		return $code;

	}

	/**
	 * Returns the Facebook login button fbml code based on the passed values.
	 * This function should not be called directly - it is reserved for the
	 * login button widget (and the for the WordpressConnectLoginButton::getHtml method)
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
	 * @access  public
	 * @see 	http://developers.facebook.com/docs/reference/plugins/login/
	 * @see 	http://developers.facebook.com/docs/authentication/permissions/
	 * @since	2.0
	 * @static
	 */
	public static function getFbml( $show_faces, $width, $max_rows, $perms ){

		$show_faces_value = ( $show_faces == WPC_OPTION_ENABLED ) ? 'true' : 'false';

		$fbml .= sprintf(
			'<fb:login-button show-faces="%s" width="%d" max-rows="%d" perms="%s"></fb:login-button>',
			$show_faces_value, $width, $max_rows, $perms
		);

		return $fbml;
	}
}

?>