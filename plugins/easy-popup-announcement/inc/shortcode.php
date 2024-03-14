<?php

/*
 * Shortocode for single post / page
 */
function epa_setcookie_post() {
	$epa_expire = get_option( 'epa_expire' );
	$expire = time() + (float) $epa_expire;
	if(isset($_COOKIE['epa_popup'])):
	else:
		//$_COOKIE['epa_popup'] = 'active';
		setcookie('epa_popup', 'active', $expire);
	endif;
} 

function epa_shortcode_single( $atts ) {
	$atts = shortcode_atts( array(
 	      'id' => ''
      ), $atts );

	$default_popup = get_post($atts['id']);

	if(get_option('epa_enable') == 'no') :

		$epa_html .= '<div id="my_popup" class="well"><span class="my_popup_close"></span>';
		$epa_html .= $default_popup->post_content;
		$epa_html .= '</div>';

		if(!isset($_COOKIE['epa_popup'])) {
			return $epa_html;
		}

	endif;

}
add_shortcode( 'epapop', 'epa_shortcode_single' );
