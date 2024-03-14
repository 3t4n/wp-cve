<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Libs;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Cache
{
	public static function invalidate()
	{
		// WP Rocket
		if (function_exists('\rocket_clean_domain'))
		{
			\rocket_clean_domain();
		}

		// W3 Total Cache : w3tc
		if (function_exists('\w3tc_pgcache_flush'))
		{
			\w3tc_pgcache_flush();
		}

		// WP Super Cache : wp-super-cache
		if (function_exists('\wp_cache_clear_cache'))
		{
			\wp_cache_clear_cache();
		}

		// WP Fastest Cache
		if (function_exists('\wpfc_clear_all_cache'))
		{
			\wpfc_clear_all_cache(true);
		}

		// WPEngine
		if (class_exists('\WpeCommon') && method_exists('\WpeCommon', 'purge_memcached'))
		{
			\WpeCommon::purge_memcached();
			\WpeCommon::clear_maxcdn_cache();
			\WpeCommon::purge_varnish_cache();
		}

		// SG Optimizer by Siteground
		if (function_exists('\sg_cachepress_purge_cache'))
		{
			\sg_cachepress_purge_cache();
		}

		// LiteSpeed
		if (class_exists('\LiteSpeed_Cache_API') && method_exists('\LiteSpeed_Cache_API', 'purge_all'))
		{
			\LiteSpeed_Cache_API::purge_all();
		}

		// Cache Enabler
		if (class_exists('\Cache_Enabler') && method_exists('\Cache_Enabler', 'clear_total_cache'))
		{
			\Cache_Enabler::clear_total_cache();
		}

		// Pagely
		if (class_exists('\PagelyCachePurge') && method_exists('\PagelyCachePurge', 'purgeAll'))
		{
			\PagelyCachePurge::purgeAll();
		}

		// Autoptimize
		if (class_exists('\autoptimizeCache') && method_exists('\autoptimizeCache', 'clearall'))
		{
			\autoptimizeCache::clearall();
		}

		//comet cache (formerly zencache)
		if (class_exists('\comet_cache') && method_exists('\comet_cache', 'clear'))
		{
			\comet_cache::clear();
		}

		// Hummingbird Cache
		if (class_exists('\Hummingbird\WP_Hummingbird') && method_exists('\Hummingbird\WP_Hummingbird', 'flush_cache'))
		{
			\WP_Hummingbird::flush_cache();
		}
	}
}