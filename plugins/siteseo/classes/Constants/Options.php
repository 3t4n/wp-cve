<?php

namespace SiteSEO\Constants;

if ( ! defined('ABSPATH')) {
	exit;
}

abstract class Options {
	/**
	 * @since 4.3.0
	 *
	 * @var string
	 */
	const KEY_OPTION_SITEMAP = 'siteseo_xml_sitemap_option_name';

	/**
	 * @since 4.3.0
	 *
	 * @var string
	 */
	const KEY_OPTION_TITLE = 'siteseo_titles_option_name';

	/**
	 * @since 4.5.0
	 *
	 * @var string
	 */
	const KEY_OPTION_SOCIAL = 'siteseo_social_option_name';

	/**
	 * @since 4.6.0
	 *
	 * @var string
	 */
	const KEY_OPTION_ADVANCED = 'siteseo_advanced_option_name';

	/**
	 * @since 6.0.0
	 *
	 * @var string
	 */
	const KEY_OPTION_NOTICE = 'siteseo_notices';

	/**
	 * @since 4.5.0
	 *
	 * @var string
	 */
	const KEY_TOGGLE_OPTION = 'siteseo_toggle';

	/**
	 * @since 5.8.0
	 *
	 * @var string
	 */
	const KEY_OPTION_GOOGLE_ANALYTICS = 'siteseo_google_analytics_option_name';
}
