<?php
/**
 (C) Copryright https://www.ezusy.com
 
**/

/**
 * Description for this functions
 *
 * @param Request object $request Data.
 * @return JSON data
 */

function ezusy_set_flatform() {

	global $post;
	$settings = get_option( 'ezusy_settings_reviews' ); 
	if($settings){
		$variations = trim($settings["list_name_variation"]);
		$width_images = trim($settings["ez_width_images"]);
		if($variations == ''){
			$variations = "color,couleur";
		}
		if($width_images == ''){
			$width_images = "40";
		}
	}else{
		$variations = "color,couleur";
	}
	$variations = trim($variations);
	$variations = str_replace(" ","_", $variations);
	$variations = str_replace(",_",",",$variations);
	$variations = strtolower($variations);
	$list_variation =  explode(",",$variations);
	$shop_url = home_url();
	$domain = str_replace(array('https://', 'http://'), '', $shop_url);
	
	$custom_css = '.ezusy-section .ezusy-img{width:'.$width_images.'px;min-height:'.$width_images.'px;}';
	wp_enqueue_style('ezusy', EZUSY_URL_ASSETS.'css/ezusy-public.css', array(), EZUSY_WOO_VERSION );
	wp_add_inline_style( 'ezusy', $custom_css );
	
    wp_enqueue_script( 'ezusy',  EZUSY_URL_ASSETS.'js/ezusy-public.js', array( 'jquery' ), EZUSY_WOO_VERSION ,true );
    wp_add_inline_script( 'ezusy', 'var ezusy_WC = {domain: "'. $domain .'", shop_url: "'. $shop_url .'"}, ezusy_variation = '.json_encode($list_variation).';', 'before' );
}

add_action( 'wp_enqueue_scripts', 'ezusy_set_flatform' );