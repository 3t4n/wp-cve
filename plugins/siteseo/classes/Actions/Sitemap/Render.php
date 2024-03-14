<?php

namespace SiteSEO\Actions\Sitemap;

defined('ABSPATH') or exit('Cheatin&#8217; uh?');

use SiteSEO\Core\Hooks\ExecuteHooksFrontend;

class Render implements ExecuteHooksFrontend {
	/**
	 * @since 4.3.0
	 *
	 * @return void
	 */
	public function hooks() {
		add_action('pre_get_posts', [$this, 'render'], 1);
		add_action('template_redirect', [$this, 'sitemapShortcut'], 1);
	}

	/**
	 * @since 4.3.0
	 * @see @pre_get_posts
	 *
	 * @param Query $query
	 *
	 * @return void
	 */
	public function render($query) {
		if ( ! $query->is_main_query()) {
			return;
		}
		
		$sitemap_service = siteseo_get_service('SitemapOption');
		
		if ('1' !== $sitemap_service->isEnabled() || '1' !== siteseo_get_toggle_option('xml-sitemap')) {
			return;
		}

		$filename = null;
		if ('1' === get_query_var('siteseo_sitemap')) {
			$filename = 'template-xml-sitemaps.php';
		} elseif ('1' === get_query_var('siteseo_sitemap_xsl')) {
			$filename = 'template-xml-sitemaps-xsl.php';
		} elseif ('1' === get_query_var('siteseo_sitemap_video_xsl')) {
			$filename = 'template-xml-sitemaps-video-xsl.php';
		} elseif ('1' === get_query_var('siteseo_author')) {
			$filename = 'template-xml-sitemaps-author.php';
		} elseif ('' !== get_query_var('siteseo_cpt')) {
			
			$sitemap_post_types_list = $sitemap_service->getPostTypesList();
			if ('' != $sitemap_post_types_list
				&& array_key_exists(get_query_var('siteseo_cpt'), $sitemap_post_types_list)) {
				siteseo_get_service('SitemapRenderSingle')->render();
				exit();
			} elseif ('' != $sitemap_service->getTaxonomiesList()
				&& array_key_exists(get_query_var('siteseo_cpt'), $sitemap_service->getTaxonomiesList())) {
				$filename = 'template-xml-sitemaps-single-term.php';
			}
			else{
				global $wp_query;
				$wp_query->set_404();
				status_header(404);
				return;
			}
		}


		if ($filename === 'template-xml-sitemaps-video-xsl.php') {
			include SITESEO_PRO_PLUGIN_DIR_PATH.'/video-sitemap/' . $filename;
			exit();
		} elseif (null !== $filename && file_exists(SITESEO_MAIN.'/sitemap/' . $filename)) {
			include SITESEO_MAIN.'/sitemap/' . $filename;
			exit();
		}

	}

	/**
	 * @since 4.3.0
	 * @see @template_redirect
	 *
	 * @return void
	 */
	public function sitemapShortcut() {
		if ('1' !== siteseo_get_toggle_option('xml-sitemap')) {
			return;
		}
		//Redirect sitemap.xml to sitemaps.xml
		$get_current_url = esc_url(get_home_url() . sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])));
		if (in_array($get_current_url, [
				get_home_url() . '/sitemap.xml/',
				get_home_url() . '/sitemap.xml',
				get_home_url() . '/wp-sitemap.xml/',
				get_home_url() . '/wp-sitemap.xml',
				get_home_url() . '/sitemap_index.xml/',
				get_home_url() . '/sitemap_index.xml',
			])) {
			wp_safe_redirect(get_home_url() . '/sitemaps.xml', 301);
			exit();
		}
	}
}
