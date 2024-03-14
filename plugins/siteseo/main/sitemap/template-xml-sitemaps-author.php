<?php

defined('ABSPATH') or exit('Please don&rsquo;t call the plugin directly. Thanks :)');

//XML

//Headers
if (function_exists('siteseo_sitemaps_headers')) {
	siteseo_sitemaps_headers();
}

//WPML
add_filter('wpml_get_home_url', 'siteseo_remove_wpml_home_url_filter', 20, 5);

function siteseo_xml_sitemap_author() {
	if ('' !== get_query_var('siteseo_cpt')) {
		$path = get_query_var('siteseo_cpt');
	}

	$home_url = home_url() . '/';

	if (function_exists('pll_home_url')) {
		$home_url = site_url() . '/';
	}

	$home_url = apply_filters('siteseo_sitemaps_home_url', $home_url);

	$siteseo_sitemaps = '<?xml version="1.0" encoding="UTF-8"?>';
	$siteseo_sitemaps .= '<?xml-stylesheet type="text/xsl" href="' . esc_url($home_url) . 'sitemaps_xsl.xsl"?>';
	$siteseo_sitemaps .= "\n";
	$siteseo_sitemaps .= apply_filters('siteseo_sitemaps_urlset', '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');
	$args = [
		'fields'			  => 'ID',
		'orderby'			 => 'nicename',
		'order'			   => 'ASC',
		'has_published_posts' => ['post'],
			'blog_id'		 => absint(get_current_blog_id()),
			'lang'			=> '',
	];
	$args = apply_filters('siteseo_sitemaps_author_query', $args);

	$authorslist = get_users($args);

	foreach ($authorslist as $author) {
		$siteseo_sitemaps_url = '';
		// array with all the information needed for a sitemap url
		$siteseo_url = [
			'loc'	=> urldecode(get_author_posts_url($author)),
			'mod'	=> '',
			'images' => [],
		];
		$siteseo_sitemaps_url .= "\n";
		$siteseo_sitemaps_url .= '<url>';
		$siteseo_sitemaps_url .= "\n";
		$siteseo_sitemaps_url .= '<loc>';
		$siteseo_sitemaps_url .= esc_url($siteseo_url['loc']);
		$siteseo_sitemaps_url .= '</loc>';
		$siteseo_sitemaps_url .= "\n";
		$siteseo_sitemaps_url .= '</url>';

		$siteseo_sitemaps .= apply_filters('siteseo_sitemaps_url', $siteseo_sitemaps_url, $siteseo_url);
	}
	$siteseo_sitemaps .= '</urlset>';
	$siteseo_sitemaps .= "\n";

	$siteseo_sitemaps = apply_filters('siteseo_sitemaps_xml_author', $siteseo_sitemaps);

	return $siteseo_sitemaps;
}
echo siteseo_xml_sitemap_author(); // phpcs:ignore
