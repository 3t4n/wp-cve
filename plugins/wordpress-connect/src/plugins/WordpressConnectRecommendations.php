<?php

require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php' );
//require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/plugins/AbstractWordpressConnectPlugin.php' );

/**
 * @author Tomas Vorobjov
 * @version 2.0
 * @date 12 May 2011
 *
 * @file WordpressConnectRecommendations.php
 *
 * This class provides methods for displaying the recommendations plugin
 */
class WordpressConnectRecommendations {

	/**
	 * This function renders a Facebook Recommendations based on the passed values.
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
	 * @access  public
	 * @see 	http://developers.facebook.com/docs/reference/plugins/recommendations/
	 * @since	2.0
	 * @static
	 */
	public static function getHtml( $domain, $width, $height, $show_header, $colorscheme, $font, $border_color, $ref ){

		$code = '<!-- Wordpress Connect Recommendations v' . WPC_VERSION . ' -->' . "\n";
		$code .= '<p class="wp-connect-recommendations">';
		$code .= WordpressConnectRecommendations::getFbml( $domain, $width, $height, $show_header, $colorscheme, $font, $border_color, $ref );
		$code .= '</p>';
		$code .= "\n" . '<!-- Wordpress Connect Recommendations -->' . "\n";

		return $code;

	}

	/**
	 * Returns the Facebook recommendations fbml code based on the passed values.
	 * This function should not be called directly - it is reserved for the
	 * recommendations widget (and the for the WordpressConnectRecommendations::getHtml method)
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
	 * @access  public
	 * @see 	http://developers.facebook.com/docs/reference/plugins/recommendations/
	 * @since	2.0
	 * @static
	 */
	public static function getFbml( $domain, $width, $height, $show_header, $colorscheme, $font, $border_color, $ref ){

		$show_header_value = ( $show_header == WPC_OPTION_ENABLED ) ? 'true' : 'false';

		$fbml .= sprintf(
			'<fb:recommendations site="%s" width="%d" height="%d" header="%s" colorscheme="%s" font="%s" border_color="%s" ref="%s"></fb:recommendations>',
			$domain, $width, $height, $show_header, $colorscheme, $font, $border_color,$ref
		);

		return $fbml;
	}
}

?>
