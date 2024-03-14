<?php
/**
 * Plugin Name: TinyMCE editor Font FIX
 * Plugin URI: http://wordpress.org/extend/plugins/selfish-fresh-start
 * Description: This WordPress plugin change the font style, in my opinion, default crappy font size and font family to something better. Its all about being effective and productive. How can you be productive if the default tinyMCE font style is total crap? So here we go, this plugin gonna fix your nightmare. It will change the font family of TinyMCE editor to Arial and also change the font size to 15px. Its Important you to understand that its not effects your website design. This plugin was create for tinyMCE editor in admin panel. Lightwaight and great plugin that everyone needs! Hope you Enjoy
 DON'T FORGET - GIVE ME 5 STARS :) THANX
 * Author: Yossi Jana
 * Author URI: http://www.webist.co.il/
 * Version: 1.0
 */

function plugin_mce_css( $mce_css ) {
	if ( ! empty( $mce_css ) )
		$mce_css .= ',';

	$mce_css .= plugins_url( 'css-fix/webist-editor.css', __FILE__ );

	return $mce_css;
}

add_filter( 'mce_css', 'plugin_mce_css' );

function remove_footer_admin ()
{
	echo "<span id='footer-thankyou'><a href='http://www.webist.co.il' target='_blank'><img src='http://www.webist.co.il/banners/logos/logo-webist-tr.png' width='80' height='19'></a></span>";
}
add_filter('admin_footer_text', 'remove_footer_admin');


function custom_dashboard_help() {
echo '<p>תודה שבחרת להשתמש בתוסף של ווביסט. כדי לקבל תמיכה ומידע נוסף ניתן לגשת לאתר <a href="http://www.webist.co.il">באמצעות לחיצה על הלינק</a>. יותר מידע ניתן לקבל ב: <a href="http://www.webist.co.il" target="_blank">Webist</a></p>

<p>Thank you for using TinyMCE editor Font FIX plugin, and we will appriciate your support by linking back to us or share this plugin with others.</a> - <a href="http://www.webist.co.il" target="_blank">Webist</a></p>
';
}


?>