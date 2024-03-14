<?php

defined('ABSPATH') or exit('Please don&rsquo;t call the plugin directly. Thanks :)');

//XML

//Headers
if (function_exists('siteseo_sitemaps_headers')) {
	siteseo_sitemaps_headers();
}

//WPML
add_filter('wpml_get_home_url', 'siteseo_remove_wpml_home_url_filter', 20, 5);

add_filter('siteseo_sitemaps_single_term_query', function ($args) {
	global $sitepress, $sitepress_settings;

	$sitepress_settings['auto_adjust_ids'] = 0;
	remove_filter('terms_clauses', [$sitepress, 'terms_clauses']);
	remove_filter('category_link', [$sitepress, 'category_link_adjust_id'], 1);

	return $args;
});

add_filter('siteseo_sitemaps_term_single_url', function($url, $term) {
	//Exclude custom canonical from sitemaps
	if (get_term_meta($term->term_id, '_siteseo_robots_canonical', true) && get_term_link( $term->term_id ) !== get_term_meta($term->term_id, '_siteseo_robots_canonical', true)) {
		return null;
	}

	//Exclude noindex
	if (get_term_meta($term->term_id, '_siteseo_robots_index', true)) {
		return null;
	}

	//Exclude hidden languages
	//@credits WPML compatibility team
	if (function_exists('icl_object_id') && defined('ICL_SITEPRESS_VERSION')) { //WPML
		global $sitepress, $sitepress_settings;

		// Check that at least ID is set in post object.
		if ( ! isset( $term->term_id ) ) {
			return $url;
		}

		// Get list of hidden languages.
		$hidden_languages = $sitepress->get_setting( 'hidden_languages', array() );

		// If there are no hidden languages return original URL.
		if ( empty( $hidden_languages ) ) {
			return $url;
		}

		// Get language information for post.
		$language_info = $sitepress->term_translations()->get_element_lang_code( $term->term_id );

		// If language code is one of the hidden languages return null to skip the post.
		if ( in_array( $language_info, $hidden_languages, true ) ) {
			return null;
		}
	}

	return $url;
}, 10, 2);

// Polylang: remove hidden languages
function siteseo_pll_exclude_hidden_lang($args) {
	if (function_exists('get_languages_list') && is_plugin_active('polylang/polylang.php') || is_plugin_active('polylang-pro/polylang.php')) {
		$languages = PLL()->model->get_languages_list();
		if ( wp_list_filter( $languages, array( 'active' => false ) ) ) {
			$args['lang'] = wp_list_pluck( wp_list_filter( $languages, array( 'active' => false ), 'NOT' ), 'slug' );
		}
	}
	return $args;
}

function siteseo_xml_sitemap_single_term() {
	if ('' !== get_query_var('siteseo_cpt')) {
		$path = get_query_var('siteseo_cpt');
	}

	remove_all_filters('pre_get_posts');

	$offset = basename(parse_url(sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])), PHP_URL_PATH), '.xml');
	$offset = preg_match_all('/\d+/', $offset, $matches);
	$offset = end($matches[0]);

	//Max posts per paginated sitemap
	$max = 1000;
	$max = apply_filters('siteseo_sitemaps_max_terms_per_sitemap', $max);

	if (isset($offset) && absint($offset) && '' != $offset && 0 != $offset) {
		$offset = (($offset - 1) * $max);
	} else {
		$offset = 0;
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
		'taxonomy'   => $path,
		'offset'	 => $offset,
		'hide_empty' => false,
		'number'	 => 1000,
		//'fields'	 => 'ids',
		'lang'	   => '',
	];

	$args = siteseo_pll_exclude_hidden_lang($args);

	$args = apply_filters('siteseo_sitemaps_single_term_query', $args, $path);

	$termslist = new WP_Term_Query($args);

	if (is_array($termslist->terms) && ! empty($termslist->terms)) {
		foreach ($termslist->terms as $term) {
			$siteseo_sitemaps_url = '';
			// array with all the information needed for a sitemap url
			$siteseo_url = [
				'loc'	=> urldecode(get_term_link($term)),
				'mod'	=> '',
				'images' => [],
			];

			$siteseo_url = apply_filters( 'siteseo_sitemaps_term_single_url', $siteseo_url, $term );

			if (!empty($siteseo_url['loc'])) {
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
		}
	}
	$siteseo_sitemaps .= '</urlset>';
	$siteseo_sitemaps .= "\n";

	$siteseo_sitemaps = apply_filters('siteseo_sitemaps_xml_single_term', $siteseo_sitemaps);

	return $siteseo_sitemaps;
}
echo siteseo_xml_sitemap_single_term(); //phpcs:ignore
