<?php

namespace SiteSEO\Actions\Front;

if ( ! defined('ABSPATH')) {
	exit;
}

use SiteSEO\Core\Hooks\ExecuteHooksFrontend;
use SiteSEO\ManualHooks\Thirds\WooCommerce\WooCommerceAnalytics;

class GoogleAnalytics implements ExecuteHooksFrontend {
	/**
	 * @since 4.4.0
	 *
	 * @return void
	 */
	public function hooks() {
		add_action('siteseo_google_analytics_html', [$this, 'analytics'], 10, 1);
	}

	public function analytics($echo) {
		
		$google_analytics = siteseo_get_service('GoogleAnalyticsOption');
		
		if ('' != $google_analytics->getGA4() && '1' == $google_analytics->getEnableOption()) {
			if (siteseo_get_service('WooCommerceActivate')->isActive()) {
				$woocommerceAnalyticsHook = new WooCommerceAnalytics();
				$woocommerceAnalyticsHook->hooks();
			}
		}
	}
}
