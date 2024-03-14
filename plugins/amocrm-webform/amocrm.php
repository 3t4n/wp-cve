<?php
/*
Plugin Name: amoCRM WebForm
Description: Enables shortcode to embed amoCRM forms. Usage: <code>[amocrm id="123" hash="x7w3w3"]</code>. This code is available to copy and paste directly from the amoCRM forms editor.
Version: 1.1
Author: amoCRM
Author URI: http://amocrm.com
*/

function createAmocrmEmbedJS($atts, $content = null) {

	extract(shortcode_atts(array(
		'id'	=> '',
		'hash'	=> '',
		'locale'=> ''
	), $atts));

	$locale = ($locale == 'ru')? 'ru' : 'com';

	if (!$id or !$hash) {

		$error = "
		<div style='border: 10px solid #D08C89; border-radius: 15px; padding: 40px; margin: 50px 0 70px;'>
			<h3>Oh no!</h3>
			<p style='margin: 0;'>Something is wrong with your amoCRM shortcode. Please, check it out and try again.</p>
		</div>";

		return $error;

	} else {

		/**
		* Return embed JS
		*/

		$JSEmbed  = '<script>var amo_forms_params = {id:"' . $id . '", hash: "' . $hash . '"};</script>';
		$JSEmbed .= '<script id="amoforms_script" async="async" src="https://forms.amocrm.'.$locale.'/forms/assets/js/amoforms.js"></script>';
		
		return $JSEmbed;
	}
}

function utm_cookies(){

	if (stristr(get_locale(), 'ru')) {
		$script = 'https://forms.amocrm.ru/forms/assets/js/ga_utm_cookies.js';
	} else {
		$script = 'https://forms.amocrm.com/forms/assets/js/ga_utm_cookies.js';
	}

	wp_enqueue_script( 'utm_cookies', $script);
}

add_shortcode('amocrm', 'createAmocrmEmbedJS');
add_action('init', 'utm_cookies');


?>