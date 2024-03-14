<?php

namespace SiteSEO\Helpers;

defined('ABSPATH') or exit('Cheatin&#8217; uh?');

abstract class PagesAdmin {
	const DASHBOARD		= 'dashboard';

	const XML_HTML_SITEMAP = 'xml_html_sitemap';

	const SOCIAL_NETWORKS  = 'social_networks';

	const TITLE_METAS	  = 'titles_metas';

	const ANALYTICS		= 'analytics';

	const ADVANCED		 = 'advanced';

	const TOOLS			= 'tools';

	const INSTANT_INDEXING = 'instant_indexing';

	const PRO			  = 'pro';

	const SCHEMAS		  = 'schemas';

	const BOT			  = 'bot';

	const LICENSE		  = 'license';

	public static function getPages() {
		return apply_filters('siteseo_pages_admin', [
			self::DASHBOARD,
			self::TITLE_METAS,
			self::XML_HTML_SITEMAP,
			self::SOCIAL_NETWORKS,
			self::ANALYTICS,
			self::ADVANCED,
			self::TOOLS,
			self::INSTANT_INDEXING,
			self::PRO,
			self::SCHEMAS,
			self::BOT,
			self::LICENSE,
		]);
	}

	/**
	 * @since 4.6.0
	 *
	 * @param string $page
	 *
	 * @return string
	 */
	public static function getCapabilityByPage($page) {
		switch ($page) {
			case 'siteseo-titles':
				return self::TITLE_METAS;
			case 'siteseo-xml-sitemap':
				return self::XML_HTML_SITEMAP;
			case 'siteseo-social':
				return self::SOCIAL_NETWORKS;
			case 'siteseo-google-analytics':
				return self::ANALYTICS;
			case 'siteseo-import-export':
				return self::TOOLS;
			case 'siteseo-instant-indexing':
				return self::INSTANT_INDEXING;
			case 'siteseo-pro-page':
				return self::PRO;
			case 'siteseo-advanced':
				return self::ADVANCED;
			case 'siteseo-bot-batch':
				return self::BOT;
			default:
				return apply_filters('siteseo_get_capability_by_page', null);
		}
	}

	/**
	 * @since 4.6.0
	 *
	 * @param string $capability
	 *
	 * @return string
	 */
	public static function getPageByCapability($capability) {
		switch ($capability) {
			case self::TITLE_METAS:
				return 'siteseo-titles';
			case self::XML_HTML_SITEMAP:
				return 'siteseo-xml-sitemap';
			case self::SOCIAL_NETWORKS:
				return 'siteseo-social';
			case self::ANALYTICS:
				return 'siteseo-google-analytics';
			case self::TOOLS:
				return 'siteseo-import-export';
			case self::INSTANT_INDEXING:
				return 'siteseo-instant-indexing';
			case self::PRO:
				return 'siteseo-pro-page';
			case self::ADVANCED:
				return 'siteseo-advanced';
			case self::BOT:
				return 'siteseo-bot-batch';
			default:
				return apply_filters('siteseo_get_page_by_capability', null);
		}
	}

	/**
	 * @since 4.6.0
	 *
	 * @param string $capability
	 *
	 * @return string
	 */
	public static function getCustomCapability($capability) {
		return sprintf('siteseo_manage_%s', $capability);
	}
}
