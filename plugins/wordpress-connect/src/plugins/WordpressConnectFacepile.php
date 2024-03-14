<?php

require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php' );
//require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/AbstractWordpressConnectPlugin.php' );

/**
 * @author Tomas Vorobjov
 * @version 2.0
 * @date 12 May 2011
 *
 * @file WordpressConnectFacepile.php
 *
 * This class provides methods for displaying the facepile plugin
 */
class WordpressConnectFacepile {

	/**
	 * This function renders a Facebook Facepile based on the passed values.
	 *
	 * @param string $url			If you want the Facepile to display friends
	 * 								who have liked your page, specify the URL of
	 * 								the page here.
	 *
	 * @param int $width			the width of the plugin
	 *
	 * @param int $max_rows			the maximum number of rows of faces to display
	 *
	 * @access  public
	 * @see 	http://developers.facebook.com/docs/reference/plugins/facepile/
	 * @since	2.0
	 * @static
	 */
	public static function getHtml( $url, $width, $max_rows ){

		$code = '<!-- Wordpress Connect Facepile v' . WPC_VERSION . ' -->' . "\n";
		$code .= '<p class="wp-connect-facepile">';
		$code .= WordpressConnectFacepile::getFbml( $url, $width, $max_rows );
		$code .= '</p>';
		$code .= "\n" . '<!-- Wordpress Connect Facepile -->' . "\n";

		return $code;

	}

	/**
	 * Returns the Facebook faceiple fbml code based on the passed values.
	 * This function should not be called directly - it is reserved for the
	 * facepile widget (and the for the WordpressConnectFacepile::getHtml method)
	 *
	 * @param string $url			the url to show facepile for
	 *
	 * @param int $width			the width of the plugin
	 *
	 * @param int $max_rows			the maximum number of rows of faces to display
	 *
	 * @access  public
	 * @see 	http://developers.facebook.com/docs/reference/plugins/facepile/
	 * @since	2.0
	 * @static
	 */
	public static function getFbml( $url, $width, $max_rows ){

		$fbml .= sprintf(
			'<fb:facepile href="%s" width="%d" max_rows="%d"></fb:facepile>',
			$url, $width, $max_rows
		);
		return $fbml;
	}
}

?>