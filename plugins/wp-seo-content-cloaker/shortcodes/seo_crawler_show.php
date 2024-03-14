<?php

/*
 * Fonction permettant de cacher un contenu aux crawler SEO
 */
if(!function_exists('wp_seo_content_seo_crawler_show')){
	function wp_seo_content_seo_crawler_show( $attrs, $content = null ){
		$contentCloackerTool = new WPSeoContentCloackerTool();
		if($contentCloackerTool->isSeoCrawler()){ return $content; }
		return null;
	}
	add_shortcode('seo_crawler_show','wp_seo_content_seo_crawler_show'); 
}