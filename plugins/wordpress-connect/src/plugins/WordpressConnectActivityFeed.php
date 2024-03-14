<?php

require_once( WP_PLUGIN_DIR . '/wordpress-connect/src/WordpressConnectConstants.php' );

/**
 * @author Tomas Vorobjov
 * @version 2.0
 * @date 11 May 2011
 *
 * @file WordpressConnectActivityFeed.php
 *
 * This class provides methods for displaying the activity feed plugin
 */
class WordpressConnectActivityFeed {

	/**
	 * This function renders a Facebook Activity Feed based on the passed values.
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
	 * @access  public
	 * @see 	http://developers.facebook.com/docs/reference/plugins/activity/
	 * @since	2.0
	 * @static
	 */
	public static function getHtml( $domain, $width, $height, $show_header, $colorscheme, $font, $border_color, $show_recommendations, $filter, $ref ){

		$code = '<!-- Wordpress Connect Activity Feed v' . WPC_VERSION . ' -->' . "\n";
		$code .= '<p class="wp-connect-activity-feed">';
		$code .= WordpressConnectActivityFeed::getFbml( $domain, $width, $height, $show_header, $colorscheme, $font, $border_color, $show_recommendations, $filter, $ref );
		$code .= '</p>';
		$code .= "\n" . '<!-- Wordpress Connect Activity Feed -->' . "\n";

		return $code;

	}

	/**
	 * Returns the Facebook activity feed fbml code based on the passed values.
	 * This function should not be called directly - it is reserved for the
	 * activity feed widget (and the for the WordpressConnectActivityFeed::getHtml method)
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
	 * @access  public
	 * @see 	http://developers.facebook.com/docs/reference/plugins/activity/
	 * @since	2.0
	 * @static
	 */
	public static function getFbml( $domain, $width, $height, $show_header, $colorscheme, $font, $border_color, $show_recommendations, $filter, $ref ){

		$show_header_value = ( $show_header == WPC_OPTION_ENABLED ) ? 'true' : 'false';
		$show_recommendations_value = ( $show_recommendations == WPC_OPTION_ENABLED ) ? 'true' : 'false';

		$fbml .= sprintf(
			'<fb:activity site="%s" width="%d" height="%d" header="%s" colorscheme="%s" font="%s" border_color="%s" recommendations="%s" filter="%s" ref="%s"></fb:activity>',
			$domain, $width, $height, $show_header, $colorscheme, $font, $border_color, $show_recommendations, $filter, $ref
		);

		return $fbml;
	}
}

?>