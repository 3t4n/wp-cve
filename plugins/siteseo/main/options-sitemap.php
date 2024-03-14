<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// XML/HTML Sitemap
// Are we being accessed directly ?
if(!defined('SITESEO_VERSION')) {
	exit('Hacking Attempt !');
}

if('1' == siteseo_get_service('SitemapOption')->getHtmlEnable()){
	function siteseo_xml_sitemap_html_display(){
		
		if ('' != siteseo_get_service('SitemapOption')->getHtmlMapping()) {
			if (is_page(explode(',', siteseo_get_service('SitemapOption')->getHtmlMapping()))) {
				add_filter('the_content', 'siteseo_xml_sitemap_html_hook');
			}
		}

	}
	add_action('wp', 'siteseo_xml_sitemap_html_display');

	function siteseo_xml_sitemap_html_hook($html){
		
		// Attributes
		$atts = shortcode_atts(
			[
				'cpt' => '',
			],
			$html,
			'[siteseo_html_sitemap]'
		);

		// Exclude IDs
		if ('' != siteseo_get_service('SitemapOption')->getHtmlExclude()) {
			$get_html_exclude = siteseo_get_service('SitemapOption')->getHtmlExclude();
		} else {
			$get_html_exclude = '';
		}

		// Order
		if ('' != siteseo_get_service('SitemapOption')->getHtmlOrder()) {
			$siteseo_xml_sitemap_html_order_option = siteseo_get_service('SitemapOption')->getHtmlOrder();
		} else {
			$siteseo_xml_sitemap_html_order_option = '';
		}

		// Orderby
		if ('' != siteseo_get_service('SitemapOption')->getHtmlOrderBy()) {
			$siteseo_xml_sitemap_html_orderby_option = siteseo_get_service('SitemapOption')->getHtmlOrderBy();
		} else {
			$siteseo_xml_sitemap_html_orderby_option = '';
		}

		$html = '';

		// CPT
		$siteseo_xml_sitemap_post_types_list_option = siteseo_get_service('SitemapOption')->getPostTypesList();
		if ('' != $siteseo_xml_sitemap_post_types_list_option) {
			$html .= '<div class="wrap-html-sitemap siteseo-html-sitemap">';

			if (isset($siteseo_xml_sitemap_post_types_list_option['page'])) {
				$siteseo_xml_sitemap_post_types_list_option = ['page' => $siteseo_xml_sitemap_post_types_list_option['page']] + $siteseo_xml_sitemap_post_types_list_option; //Display page first
			}

			if (! empty($atts['cpt'])) {
				unset($siteseo_xml_sitemap_post_types_list_option);

				$cpt = explode(',', $atts['cpt']);

				foreach ($cpt as $key => $value) {
					$siteseo_xml_sitemap_post_types_list_option[$value] = ['include' => '1'];
				}
			}

			$siteseo_xml_sitemap_post_types_list_option = apply_filters('siteseo_sitemaps_html_cpt', $siteseo_xml_sitemap_post_types_list_option);

			$display_archive = '';
			foreach ($siteseo_xml_sitemap_post_types_list_option as $cpt_key => $cpt_value) {
				if ('1' !== siteseo_get_service('SitemapOption')->getHtmlArchiveLinks()) {
					$display_archive = false;
				}
				$display_archive = apply_filters('siteseo_sitemaps_html_remove_archive', $display_archive, $cpt_key);

				if (! empty($cpt_value)) {
					$html .= '<div class="siteseo-wrap-cpt">';
				}
				$obj = get_post_type_object($cpt_key);

				if ($obj) {
					$cpt_name = $obj->labels->name;
					$cpt_name = apply_filters('siteseo_sitemaps_html_cpt_name', $cpt_name, $obj->name);

					$html .= '<h2 class="siteseo-cpt-name">' . $cpt_name . '</h2>';
				}
				foreach ($cpt_value as $_cpt_key => $_cpt_value) {
					if ('1' == $_cpt_value) {
						$args = [
							'posts_per_page'	=> 1000,
							'order'				=> $siteseo_xml_sitemap_html_order_option,
							'orderby'			=> $siteseo_xml_sitemap_html_orderby_option,
							'post_type'			=> $cpt_key,
							'post_status'		=> 'publish',
							'meta_query'		=> [['key' => '_siteseo_robots_index', 'value' => 'yes', 'compare' => 'NOT EXISTS']],
							'fields'			=> 'ids',
							'exclude'			=> $get_html_exclude,
							'suppress_filters'	=> false,
							'no_found_rows'		=> true,
							'nopaging'			=> true,
						];
						if ('post' === $cpt_key || 'product' === $cpt_key) {
							if (get_post_type_archive_link($cpt_key) && 0 != get_option('page_for_posts')) {
								if (false === $display_archive) {
									$html .= '<ul>';
									$html .= '<li><a href="' . get_post_type_archive_link($cpt_key) . '">' . $obj->labels->name . '</a></li>';
									$html .= '</ul>';
								}
							}

							$args_cat_query = [
								'orderby' => 'name',
								'order' => 'ASC',
								'meta_query' => [['key' => '_siteseo_robots_index', 'value' => 'yes', 'compare' => 'NOT EXISTS']],
								'exclude' => $get_html_exclude,
								'suppress_filters' => false,
							];
							if ('post' === $cpt_key) {
								$args_cat_query = apply_filters('siteseo_sitemaps_html_cat_query', $args_cat_query);

								$cats = get_categories($args_cat_query);
							} elseif ('product' === $cpt_key) {
								$args_cat_query = apply_filters('siteseo_sitemaps_html_product_cat_query', $args_cat_query);

								$cats = get_terms('product_cat', $args_cat_query);
							}

							if (! empty($cats)) {
								$html .= '<div class="siteseo-wrap-cats">';

								foreach ($cats as $cat) {
									if ( ! is_wp_error($cat) && is_object($cat)) {
										$html .= '<div class="siteseo-wrap-cat">';
										$html .= '<h3 class="siteseo-cat-name"><a href="'. get_term_link($cat->term_id) .'">' . $cat->name . '</a></h3>';

										if ('post' === $cpt_key) {
											unset($args['cat']);
											$args['cat'][] = $cat->term_id;
										} elseif ('product' === $cpt_key) {
											unset($args['tax_query']);
											$args['tax_query'] = [[
												'taxonomy' => 'product_cat',
												'field'	=> 'term_id',
												'terms'	=> $cat->term_id,
											]];
										}

										require dirname(__FILE__) . '/sitemap/template-html-sitemap.php';

										$html .= '</div>';
									}
								}

								$html .= '</div>';
							}
						} else {
							require dirname(__FILE__) . '/sitemap/template-html-sitemap.php';
						}
					}
				}
				if (! empty($cpt_value)) {
					$html .= '</div>';
				}
			}
			$html .= '</div>';
		}

		return $html;
	}
	add_shortcode('siteseo_html_sitemap', 'siteseo_xml_sitemap_html_hook');
}
