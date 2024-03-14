<?php
// exit when accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include( EXPAND_DIVI_PATH . 'inc/temp/share_icons.php' );

/**
 * social share shortcode
 *
 */
function expand_divi_social_share_shortcode() {
	global $html;
	return $html;
}

add_shortcode( 'ed_share_icons', 'expand_divi_social_share_shortcode');