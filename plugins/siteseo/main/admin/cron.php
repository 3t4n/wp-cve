<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Are we being accessed directly ?
if(!defined('SITESEO_VERSION')) {
	exit('Hacking Attempt !');
}

/**
 * Automatically ping Google daily for XML sitemaps
 *
 * @since 1.0.0
 *
 */
function siteseo_xml_sitemaps_ping_cron_action(){
	
	//Disable if MainWP add-on enabled
	if (defined('SITESEO_WPMAIN_VERSION')) {
		return;
	}

	// If site is set to noindex globally
	if ('1' === siteseo_get_service('TitleOption')->getTitleNoIndex() || '0' === get_option('blog_public')) {
		return;
	}
	
	// Check if XML sitemaps is enabled
	if ('1' !== siteseo_get_service('SitemapOption')->isEnabled() || '1' !== siteseo_get_toggle_option('xml-sitemap')) {
		return;
	}

	// Disable if IndexNow is enabled
	$options = get_option('siteseo_instant_indexing_option_name');
	if ('1' == siteseo_get_toggle_option('instant-indexing') && isset($options['engines']['bing']) && $options['engines']['bing'] === '1') {
		return;
	}

	$url = rawurlencode(get_option('home').'/sitemaps.xml/');

	$url = apply_filters( 'siteseo_sitemaps_xml_ping_url', $url);

	$args = [
		'blocking' => false,
	];

	$args = apply_filters( 'siteseo_sitemaps_xml_ping_args', $args);

	wp_remote_get('https://www.google.com/ping?sitemap='.$url, $args);
}
add_action('siteseo_xml_sitemaps_ping_cron', 'siteseo_xml_sitemaps_ping_cron_action');
