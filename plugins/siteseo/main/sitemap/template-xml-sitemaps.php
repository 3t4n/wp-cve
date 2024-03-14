<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// XML Index sitemaps
// Are we being accessed directly ?
if(!defined('SITESEO_VERSION')) {
	exit('Hacking Attempt !');
}

//Headers
if (function_exists('siteseo_sitemaps_headers')) {
	siteseo_sitemaps_headers();
}

//WPML
add_filter('wpml_get_home_url', 'siteseo_remove_wpml_home_url_filter', 20, 5);

add_filter('siteseo_sitemaps_index_cpt_query', function ($args) {
	global $sitepress, $sitepress_settings;

	$sitepress_settings['auto_adjust_ids'] = 0;
	remove_filter('terms_clauses', [$sitepress, 'terms_clauses']);
	remove_filter('category_link', [$sitepress, 'category_link_adjust_id'], 1);

	return $args;
});

add_action('the_post', function ($post) {
	$language = apply_filters(
		'wpml_element_language_code',
		null,
		['element_id' => $post->ID, 'element_type' => 'page']
	);
	do_action('wpml_switch_language', $language);
});

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

//WPML: remove hidden languages
function siteseo_wpml_exclude_hidden_lang($url) {
	//@credits WPML compatibility team
	if (function_exists('get_setting') && is_plugin_active('sitepress-multilingual-cms/sitepress.php')) { //WPML
		global $sitepress, $sitepress_settings;

		// Check that at least ID is set in post object.
		if ( ! isset( $post->ID ) ) {
			return $url;
		}

		// Get list of hidden languages.
		$hidden_languages = $sitepress->get_setting( 'hidden_languages', array() );

		// If there are no hidden languages return original URL.
		if ( empty( $hidden_languages ) ) {
			return $url;
		}

		// Get language information for post.
		$language_info = $sitepress->post_translations()->get_element_lang_code( $post->ID );

		// If language code is one of the hidden languages return null to skip the post.
		if ( in_array( $language_info, $hidden_languages, true ) ) {
			return null;
		}
	}
}

function siteseo_xml_sitemap_index() {
	$home_url = home_url() . '/';

	if (function_exists('pll_home_url')) {
		$home_url = site_url() . '/';
	}

	$home_url = apply_filters('siteseo_sitemaps_home_url', $home_url);

	$siteseo_sitemaps ='<?xml version="1.0" encoding="UTF-8"?>';
	$siteseo_sitemaps .= '<?xml-stylesheet type="text/xsl" href="' . esc_url($home_url) . 'sitemaps_xsl.xsl"?>';
	$siteseo_sitemaps .= "\n";
	$siteseo_sitemaps .= '<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

	//CPT
	$sitemap_post_types_list = siteseo_get_service('SitemapOption')->getPostTypesList();
	
	if ('' != $sitemap_post_types_list) {
		foreach ($sitemap_post_types_list as $cpt_key => $cpt_value) {
			foreach ($cpt_value as $_cpt_key => $_cpt_value) {
				if ('1' == $_cpt_value) {
					$args = [
						'posts_per_page' => -1,
						'post_type'	  => $cpt_key,
						'post_status'	=> 'publish',
						'meta_query'	 => [
							'relation' => 'OR',
							[
								'key'	 => '_siteseo_robots_index',
								'value'   => '',
								'compare' => 'NOT EXISTS',
							],
							[
								'key'	 => '_siteseo_robots_index',
								'value'   => 'yes',
								'compare' => '!=',
							],
						],
						'fields'	   => 'ids',
						'lang'		 => '',
						'has_password' => false,
					];

					//Polylang: exclude hidden languages
					$args = siteseo_pll_exclude_hidden_lang($args);

					$args = apply_filters('siteseo_sitemaps_index_post_types_query', $args, $cpt_key);

					$count_posts = count(get_posts($args));

					//Max posts per paginated sitemap
					$max = 1000;
					$max = apply_filters('siteseo_sitemaps_max_posts_per_sitemap', $max);

					if ($count_posts >= $max) {
						$max_loop = $count_posts / $max;
					} else {
						$max_loop = 1;
					}

					$paged ='';
					$i	 = '';
					for ($i=0; $i < $max_loop; ++$i) {
						if (isset($offset) && absint($offset) && '' != $offset && 0 != $offset) {
							$offset = ((($i) * $max));
						} else {
							$offset = 0;
						}

						if ($i >= 1 && $i <= $max_loop) {
							$paged = $i + 1;
						} else {
							$paged = 1;
						}

						$siteseo_sitemaps .= "\n";
						$siteseo_sitemaps .= '<sitemap>';
						$siteseo_sitemaps .= "\n";
						$siteseo_sitemaps .= '<loc>';
						$siteseo_sitemaps .= $home_url . $cpt_key . '-sitemap' . $paged . '.xml';
						$siteseo_sitemaps .= '</loc>';
						$siteseo_sitemaps .= "\n";

						//Remove lastmod column in index sitemap for lage sitemap
						$display_lastmod = apply_filters('siteseo_sitemaps_index_lastmod', false);

						if (true == $display_lastmod) {
							$args = [
								'post_type' 					=> $cpt_key,
								'offset' 						  => $offset,
								'post_status' 				  => 'publish',
								'ignore_sticky_posts' 	=> true,
								'posts_per_page' 			=> 1,
								'meta_query'		   => [
										'relation' => 'OR',
										[
											'key'	 => '_siteseo_robots_index',
											'value'   => '',
											'compare' => 'NOT EXISTS',
										],
										[
											'key'	 => '_siteseo_robots_index',
											'value'   => 'yes',
											'compare' => '!=',
										],
									],
								'order' 					  => 'DESC',
								'orderby' 					=> 'modified',
								'lang' 						  => '',
								'has_password' 			=> false,
							];

							$args = siteseo_pll_exclude_hidden_lang($args);

							$args = apply_filters('siteseo_sitemaps_index_cpt_query', $args, $cpt_key);

							$get_latest_post = new WP_Query($args);

							if ($get_latest_post->have_posts()) {
								$siteseo_sitemaps .= '<lastmod>';
								$siteseo_sitemaps .= date('c', strtotime($get_latest_post->posts[0]->post_modified));
								$siteseo_sitemaps .= '</lastmod>';
								$siteseo_sitemaps .= "\n";
							}
						}

						$siteseo_sitemaps .= '</sitemap>';
					}
				}
			}
		}
	}

	//Taxonomies
	$sitemap_taxonomies_list = siteseo_get_service('SitemapOption')->getTaxonomiesList();
	if ('' != $sitemap_taxonomies_list) {
		//Init
		$siteseo_xml_terms_list = [];
		foreach ($sitemap_taxonomies_list as $tax_key => $tax_value) {
			foreach ($tax_value as $_tax_key => $_tax_value) {
				if ('1' == $_tax_value) {
					$args = [
						'taxonomy'   => $tax_key,
						'hide_empty' => false,
						'lang'	   => '',
						'fields'	 => 'ids',
						'meta_query' => [
							'relation' => 'OR',
							[
								'key'	 => '_siteseo_robots_index',
								'value'   => '',
								'compare' => 'NOT EXISTS',
							],
							[
								'key'	 => '_siteseo_robots_index',
								'value'   => 'yes',
								'compare' => '!=',
							],
						],
					];

					//Polylang: exclude hidden languages
					$args = siteseo_pll_exclude_hidden_lang($args);

					$args = apply_filters('siteseo_sitemaps_index_tax_query', $args, $tax_key);

					$termsData   = get_terms($args);
					$count_terms = 0;
					if (is_array($termsData) && ! is_wp_error($termsData)) {
						$count_terms = count($termsData);
					}

					//Max terms per paginated sitemap
					$max = 1000;
					$max = apply_filters('siteseo_sitemaps_max_terms_per_sitemap', $max);

					if ($count_terms >= $max) {
						$max_loop = $count_terms / $max;
					} else {
						$max_loop = 1;
					}

					$paged ='';
					$i	 = '';
					for ($i=0; $i < $max_loop; ++$i) {
						if (isset($offset) && absint($offset) && '' != $offset && 0 != $offset) {
							$offset = ((($i) * $max));
						} else {
							$offset = 0;
						}

						if ($i >= 1 && $i <= $max_loop) {
							$paged = $i + 1;
						} else {
							$paged = 1;
						}

						$siteseo_sitemaps .= "\n";
						$siteseo_sitemaps .= '<sitemap>';
						$siteseo_sitemaps .= "\n";
						$siteseo_sitemaps .= '<loc>';
						$siteseo_sitemaps .= $home_url . $tax_key . '-sitemap' . $paged . '.xml';
						$siteseo_sitemaps .= '</loc>';
						$siteseo_sitemaps .= "\n";
						$siteseo_sitemaps .= '</sitemap>';
					}
				}
			}
		}
	}

	//Google News
	if (function_exists('siteseo_xml_sitemap_news_enable_option') && '' != siteseo_xml_sitemap_news_enable_option()
		&& function_exists('siteseo_get_toggle_option') && '1' == siteseo_get_toggle_option('news')) {
		//Include Custom Post Types
		function siteseo_xml_sitemap_news_cpt_option() {
			$options = get_option('siteseo_pro_option_name');
			if ( ! empty($options) && isset($options['news_name_post_types_list'])) {
				return $options['news_name_post_types_list'];
			}
		}
		if ('' != siteseo_xml_sitemap_news_cpt_option()) {
			$siteseo_xml_sitemap_news_cpt_array = [];
			foreach (siteseo_xml_sitemap_news_cpt_option() as $cpt_key => $cpt_value) {
				foreach ($cpt_value as $_cpt_key => $_cpt_value) {
					if ('1' == $_cpt_value) {
						array_push($siteseo_xml_sitemap_news_cpt_array, $cpt_key);
					}
				}
			}
		}

		$args = [
			'post_type'		   => $siteseo_xml_sitemap_news_cpt_array,
			'post_status'		 => 'publish',
			'ignore_sticky_posts' => true,
			'posts_per_page'	  => 1,
			'orderby'			 => 'modified',
			'meta_query'		  => [
				[
					'key'	 => '_siteseo_robots_index',
					'value'   => 'yes',
					'compare' => 'NOT EXISTS',
				],
			],
			'order'		=> 'DESC',
			'lang'		 => '',
			'has_password' => false,
		];

		//Polylang: exclude hidden languages
		$args = siteseo_pll_exclude_hidden_lang($args);

		$args = apply_filters('siteseo_sitemaps_index_gnews_query', $args);

		$get_latest_post = new WP_Query($args);
		if ($get_latest_post->have_posts()) {
			$siteseo_sitemaps .= "\n";
			$siteseo_sitemaps .= '<sitemap>';
			$siteseo_sitemaps .= "\n";
			$siteseo_sitemaps .= '<loc>';
			$siteseo_sitemaps .= $home_url . 'news.xml';
			$siteseo_sitemaps .= '</loc>';
			$siteseo_sitemaps .= "\n";
			$siteseo_sitemaps .= '<lastmod>';
			$siteseo_sitemaps .= date('c', strtotime($get_latest_post->posts[0]->post_modified));
			$siteseo_sitemaps .= '</lastmod>';
			$siteseo_sitemaps .= "\n";
			$siteseo_sitemaps .= '</sitemap>';
		}
	}

	//Video sitemap
	// TODO: implimet function "siteseo_xml_sitemap_video_enable_option"
	if (function_exists('siteseo_xml_sitemap_video_enable_option') && '' != siteseo_xml_sitemap_video_enable_option()) {
		$sitemap_post_types_list = siteseo_get_service('SitemapOption')->getPostTypesList();
		if ('' != $sitemap_post_types_list) {
			$cpt = [];
			foreach ($sitemap_post_types_list as $cpt_key => $cpt_value) {
				foreach ($cpt_value as $_cpt_key => $_cpt_value) {
					if ('1' == $_cpt_value) {
						$cpt[] = $cpt_key;
					}
				}
			}
		}

		$args = [
			'post_type'		   => $cpt,
			'post_status'		 => 'publish',
			'ignore_sticky_posts' => true,
			'posts_per_page'	  => -1,
			'meta_query'		  => [
				'relation' => 'AND',
				[
					'relation' => 'OR',
					[
						'key'	 => '_siteseo_robots_index',
						'value'   => '',
						'compare' => 'NOT EXISTS',
					],
					[
						'key'	 => '_siteseo_robots_index',
						'value'   => 'yes',
						'compare' => '!=',
					],
				],
				[
					'key'	 => '_siteseo_video',
					'compare' => 'EXISTS',
				],
			],
			'lang'		 => '',
			'has_password' => false,
			'fields'	   => 'ids',
		];

		//Polylang: exclude hidden languages
		$args = siteseo_pll_exclude_hidden_lang($args);

		$args = apply_filters('siteseo_sitemaps_index_video_query', $args, $cpt_key);

		$ids  = get_posts($args);

		$args = [
			'post_type'		   => $cpt,
			'post_status'		 => 'publish',
			'posts_per_page'	  => -1,
			'post__in'			=> $ids,
			'meta_query'		  => [
				[
					'relation' => 'OR',
					[
						'key'	 => '_siteseo_video_disabled',
						'value'   => '',
						'compare' => 'NOT EXISTS',
					],
					[
						'key'	 => '_siteseo_video_disabled',
						'value'   => 'yes',
						'compare' => '!=',
					],
				],
			],
			'lang'		 => '',
			'fields'	   => 'ids',
		];

		//Polylang: exclude hidden languages
		$args = siteseo_pll_exclude_hidden_lang($args);

		$posts	   = get_posts($args);
		$count_posts = count($posts);

		foreach ($posts as $key => $postID) {
			$siteseo_video	= get_post_meta($postID, '_siteseo_video', true);
			$siteseo_video_xml_yt = get_post_meta($postID, '_siteseo_video_xml_yt', true);

			if ( !empty($siteseo_video_xml_yt)) {
				continue;
			}

			if ( ! $siteseo_video) {
				--$count_posts;
				unset($posts[$key]);
				continue;
			}

			if (empty($siteseo_video[0]['url'])) {
				--$count_posts;
				unset($posts[$key]);
			}
		}
		$idsVideos = get_transient('_siteseo_sitemap_ids_video');
		if ( ! $idsVideos) {
			set_transient('_siteseo_sitemap_ids_video', $posts, 3600);
		}

		//Max posts per paginated sitemap
		$max = 1000;
		$max = apply_filters('siteseo_sitemaps_max_videos_per_sitemap', $max);

		if ($count_posts >= $max) {
			$max_loop = $count_posts / $max;
		} else {
			$max_loop = 1;
		}

		$paged ='';
		$i	 = '';
		for ($i=0; $i < $max_loop; ++$i) {
			if (isset($offset) && absint($offset) && '' != $offset && 0 != $offset) {
				$offset = ((($i) * $max));
			} else {
				$offset = 0;
			}

			if ($i >= 1 && $i <= $max_loop) {
				$paged = $i + 1;
			} else {
				$paged = 1;
			}

			$siteseo_sitemaps .= "\n";
			$siteseo_sitemaps .= '<sitemap>';
			$siteseo_sitemaps .= "\n";
			$siteseo_sitemaps .= '<loc>';
			$siteseo_sitemaps .= esc_url($home_url . 'video' . $paged . '.xml');
			$siteseo_sitemaps .= '</loc>';
			$siteseo_sitemaps .= "\n";
			$siteseo_sitemaps .= '</sitemap>';
		}
	}

	//Author sitemap
	if(1 == siteseo_get_service('SitemapOption')->authorIsEnable()) {
		$siteseo_sitemaps .= "\n";
		$siteseo_sitemaps .= '<sitemap>';
		$siteseo_sitemaps .= "\n";
		$siteseo_sitemaps .= '<loc>';
		$siteseo_sitemaps .= esc_url($home_url) . 'author.xml';
		$siteseo_sitemaps .= '</loc>';
		$siteseo_sitemaps .= "\n";
		$siteseo_sitemaps .= '</sitemap>';
	}

	//Custom sitemap
	$custom_sitemap = null;
	$custom_sitemap = apply_filters('siteseo_sitemaps_external_link', $custom_sitemap);
	if (isset($custom_sitemap)) {
		foreach ($custom_sitemap as $key => $sitemap) {
			$siteseo_sitemaps .= "\n";
			$siteseo_sitemaps .= '<sitemap>';
			$siteseo_sitemaps .= "\n";
			$siteseo_sitemaps .= '<loc>';
			$siteseo_sitemaps .= esc_url($sitemap['sitemap_url']);
			$siteseo_sitemaps .= '</loc>';
			if (isset($sitemap['sitemap_last_mod'])) {
				$siteseo_sitemaps .= "\n";
				$siteseo_sitemaps .= '<lastmod>';
				$siteseo_sitemaps .= esc_html($sitemap['sitemap_last_mod']);
				$siteseo_sitemaps .= '</lastmod>';
			}
			$siteseo_sitemaps .= "\n";
			$siteseo_sitemaps .= '</sitemap>';
		}
	}

	$siteseo_sitemaps .= "\n";
	$siteseo_sitemaps .= '</sitemapindex>';

	$siteseo_sitemaps = apply_filters('siteseo_sitemaps_xml_index', $siteseo_sitemaps);

	return $siteseo_sitemaps;
}

echo siteseo_xml_sitemap_index(); //phpcs:ignore
