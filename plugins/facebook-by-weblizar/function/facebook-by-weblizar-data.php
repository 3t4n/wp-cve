<?php if (!defined('ABSPATH')) exit;
if (isset($_POST['security']) && isset($_POST['facebook-page-url']) && isset($_POST['fb-app-id'])) {
	if (!wp_verify_nonce($_POST['security'], 'facebook_shortcode_settings')) {
		die();
	}

	$FacebookSettingsArray = serialize(
		array(
			'FacebookPageUrl'    => esc_url_raw($_POST['facebook-page-url']),
			'ColorScheme'        => '',
			'Header'             => isset($_POST['show-widget-header']) ? sanitize_text_field($_POST['show-widget-header']) : '',
			'Stream'             => sanitize_text_field($_POST['show-live-stream']),
			'Width'              => sanitize_text_field($_POST['widget-width']),
			'Height'             => sanitize_text_field($_POST['widget-height']),
			'FbAppId'            => sanitize_text_field($_POST['fb-app-id']),
			'ShowBorder'         => 'true',
			'ShowFaces'          => sanitize_text_field($_POST['show-fan-faces']),
			'ForceWall'          => 'false',
			'weblizar_locale_fb' => sanitize_text_field($_POST['weblizar_locale_fb'])
		)
	);
	update_option("weblizar_facebook_shortcode_settings", $FacebookSettingsArray);
}
$FacebookSettings = unserialize(get_option("weblizar_facebook_shortcode_settings"));
//load default values OR saved values
$ForceWall = 'false';
if (isset($FacebookSettings['ForceWall'])) {
	$ForceWall = $FacebookSettings['ForceWall'];
}

$Header = 'true';
if (isset($FacebookSettings['Header'])) {
	$Header = $FacebookSettings['Header'];
}

$Height = 560;
if (isset($FacebookSettings['Height'])) {
	$Height = $FacebookSettings['Height'];
}

$FacebookPageUrl = 'https://www.facebook.com/Weblizarwp/';
if (isset($FacebookSettings['FacebookPageUrl'])) {
	$FacebookPageUrl = $FacebookSettings['FacebookPageUrl'];
}

$ShowBorder = 'true';
if (isset($FacebookSettings['ShowBorder'])) {
	$ShowBorder = $FacebookSettings['ShowBorder'];
}

$ShowFaces = 'true';
if (isset($FacebookSettings['ShowFaces'])) {
	$ShowFaces = $FacebookSettings['ShowFaces'];
}

$Stream = 'true';
if (isset($FacebookSettings['Stream'])) {
	$Stream = $FacebookSettings['Stream'];
}

$Width = 292;
if (isset($FacebookSettings['Width'])) {
	$Width = $FacebookSettings['Width'];
}

$FbAppId = "488390501239538";
if (isset($FacebookSettings['FbAppId'])) {
	$FbAppId = $FacebookSettings['FbAppId'];
}

$weblizar_locale_fb = "en_GB";
if (isset($FacebookSettings['weblizar_locale_fb'])) {
	$weblizar_locale_fb = $FacebookSettings['weblizar_locale_fb'];
}
