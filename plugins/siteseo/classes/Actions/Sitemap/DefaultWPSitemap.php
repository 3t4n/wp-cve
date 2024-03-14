<?php

namespace SiteSEO\Actions\Sitemap;

defined('ABSPATH') or exit('Cheatin&#8217; uh?');

use SiteSEO\Core\Hooks\ExecuteHooks;

class DefaultWPSitemap implements ExecuteHooks {
	/**
	 * @since 4.3.0
	 *
	 * @return void
	 */
	public function hooks() {
		/*
		 * Remove default WP XML sitemaps
		 */
		if ('1' == siteseo_get_toggle_option('xml-sitemap')) {
			remove_action('init', 'wp_sitemaps_get_server');
		}
	}
}
