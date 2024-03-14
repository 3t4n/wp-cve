<?php

/*
 * Fonction permettant de cacher un contenu à google
 */
if(!function_exists('wp_seo_content_cloaker_shortcode')){
	function wp_seo_content_cloaker_shortcode( $attrs, $content = null ){
		$contentCloackerTool = new WPSeoContentCloackerTool();
		if($contentCloackerTool->isGoogleBot()){ return null; }
		return $content;
	}
	add_shortcode('seo_cloaker','wp_seo_content_cloaker_shortcode');
	add_shortcode('google_bot_hide','wp_seo_content_cloaker_shortcode');
}