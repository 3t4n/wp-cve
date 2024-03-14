<?php

/*
 * Fonction permettant de cacher un contenu aux crawler SEO
 */
if(!function_exists('wp_seo_content_seo_crawler_hide')){
	function wp_seo_content_seo_crawler_hide( $attrs, $content = null ){
		$contentCloackerTool = new WPSeoContentCloackerTool();
		if($contentCloackerTool->isSeoCrawler()){ return null; }
		return $content;
	}
	add_shortcode('seo_crawler_hide','wp_seo_content_seo_crawler_hide');
}