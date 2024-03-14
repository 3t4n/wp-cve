<?php

namespace SiteSEO\Actions\Sitemap;

defined('ABSPATH') or exit('Cheatin&#8217; uh?');

use SiteSEO\Core\Hooks\ExecuteHooks;

class Router implements ExecuteHooks {
	/**
	 * @since 4.3.0
	 *
	 * @return void
	 */
	public function hooks() {
		add_action('init', [$this, 'init']);
		add_action('query_vars', [$this, 'queryVars']);
	}

	/**
	 * @since 4.3.0
	 * @see init
	 *
	 * @return void
	 */
	public function init() {
		
		$sitemap_service = siteseo_get_service('SitemapOption');
		
		if ('1' !== $sitemap_service->isEnabled() || '1' !== siteseo_get_toggle_option('xml-sitemap')) {
			return;
		}

		//XML Index
		add_rewrite_rule('^sitemaps.xml$', 'index.php?siteseo_sitemap=1', 'top');

		//XSL Sitemap
		add_rewrite_rule('^sitemaps_xsl.xsl$', 'index.php?siteseo_sitemap_xsl=1', 'top');

		//XSL Video Sitemap
		add_rewrite_rule('^sitemaps_video_xsl.xsl$', 'index.php?siteseo_sitemap_video_xsl=1', 'top');

		add_rewrite_rule('([^/]+?)-sitemap([0-9]+)?\.xml$', 'index.php?siteseo_cpt=$matches[1]&siteseo_paged=$matches[2]', 'top');

		//XML Author
		if (1 == $sitemap_service->authorIsEnable()) {
			add_rewrite_rule('author.xml?$', 'index.php?siteseo_author=1', 'top');
		}
	}

	/**
	 * @since 4.3.0
	 * @see query_vars
	 *
	 * @param array $vars
	 *
	 * @return array
	 */
	public function queryVars($vars) {
		$vars[] = 'siteseo_sitemap';
		$vars[] = 'siteseo_sitemap_xsl';
		$vars[] = 'siteseo_sitemap_video_xsl';
		$vars[] = 'siteseo_cpt';
		$vars[] = 'siteseo_paged';
		$vars[] = 'siteseo_author';

		return $vars;
	}
}
