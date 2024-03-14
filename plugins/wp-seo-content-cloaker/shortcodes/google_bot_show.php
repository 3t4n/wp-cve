<?php

/*
 * Fonction permettant d'afficher un contenu uniquement à google
 */
if(!function_exists('wp_seo_content_cloaker_showToBot_shortcode')){
	function wp_seo_content_cloaker_showToBot_shortcode( $attrs, $content = null ){
		$contentCloackerTool = new WPSeoContentCloackerTool();
		if($contentCloackerTool->isGoogleBot()){ return $content; }
		return null;
	}
	add_shortcode('google_bot_show','wp_seo_content_cloaker_showToBot_shortcode');
}