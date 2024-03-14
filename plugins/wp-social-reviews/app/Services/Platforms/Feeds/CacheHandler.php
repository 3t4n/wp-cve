<?php

namespace WPSocialReviews\App\Services\Platforms\Feeds;

use WPSocialReviews\App\Models\Cache;
use WPSocialReviews\Framework\Support\Arr;
use WPSocialReviews\Framework\Database\Orm\DateTime;

class CacheHandler
{
	public $platform;

	public function __construct($platform)
	{
		$this->platform = $platform;
	}

    public function checkCharset()
    {
        global $wpdb;
        $charset = $wpdb->get_col_charset( $wpdb->posts, 'post_content' );

        return $charset;
    }

	/**
	 * Create cache
	 *
	 * cache the data for future usage
	 *
	 * @param string $transientName transient name
	 * @param string|array $data           data
	 *
	 * @throws /Exception
	 * @since  1.2.5
	 */
	public function createCache($transientName, $data)
	{
		$settings = get_option('wpsr_' . $this->platform . '_global_settings', false);

		$cacheTimeLimit = Arr::get($settings, 'global_settings.expiration', 86400);

		$this->createOrUpdateCache($transientName, $data, $cacheTimeLimit);
	}

	public function createOrUpdateCache($name, $data, $expiration)
	{
        $data = 'utf8' === $this->checkCharset() || 'utf8mb4' === $this->checkCharset() ? json_encode($data) : maybe_serialize($data);
		$cacheQuery = Cache::where('platform', $this->platform)->where('name', $name);
		$cache = $cacheQuery->first();

		$data = [
			'value'      => wp_encode_emoji($data),
			'expiration' => date('Y-m-d H:i:s', current_time('timestamp') + $expiration)
		];

		if ($cache) {
			$cacheQuery->update($data);
		} else {
			Cache::create(array_merge(
				[
					'platform' => $this->platform,
					'name'     => $name,
				],
				$data
			));
		}
	}

	/**
	 * Clear Cache
	 * @throws /Exception
	 * @since  1.1.0
	 */
	public function clearCache()
	{
		Cache::where('platform', $this->platform)->delete();
	}

	public function clearCacheByName($cacheName)
	{
		Cache::where('platform', $this->platform)->where('name', 'like', $cacheName.'%')->delete();
	}

    public function clearCacheByAccount($account)
    {
        Cache::where('platform', $this->platform)->where('name', 'like', '%'.$account.'%')->delete();
    }

	/**
	 * Get feed from cache
	 *
	 * @param string $transientName transient name
	 *
	 * @return mixed
	 * @since 1.2.5
	 */
	public function getFeedCache($transientName = '')
	{
		$cache = Cache::where('platform', $this->platform)->where('name', $transientName)->first();

		if ($cache) {
			return 'utf8' === $this->checkCharset() || 'utf8mb4' === $this->checkCharset() ? json_decode($cache->value, true) : maybe_unserialize($cache->value);
		}

		return false;
	}

	public function getExpiredCaches()
	{
		$caches = Cache::where('platform', $this->platform)->get();

		return $this->getExpired($caches);
	}

	private function getExpired($caches)
	{
		$expired = [];

		foreach ($caches as $cache) {
			if (new DateTime($cache->expiration) < new DateTime(current_time('mysql'))) {
				$value = 'utf8' === $this->checkCharset() || 'utf8mb4' === $this->checkCharset() ? json_decode($cache->value, true) : maybe_unserialize($cache->value);

				if ($value) {
					$expired[$cache->name] = $value;
				}
			}
		}

		return $expired;
	}

	public function getExpiredCacheByName($name)
	{
		$caches = Cache::where('platform', $this->platform)->where('name', 'like', $name . '%')->get();

		return $this->getExpired($caches);
	}

    /**
     * Other Caching plugins page caches need to clear
     *
     * @param string $platform
     *
     * @since 3.5.5
     */
    public function clearPageCaches($platform = '')
    {
        do_action('wpsn_purge_cache');

        // clear wp litespeed caches
        if(defined('LSCWP_V')) {
            do_action( 'litespeed_purge', 'wpsn_purge_'.$platform );
        }

        // clear wp redis caches
        if(defined('NGINX_HELPER_BASEURL')) {
            do_action('rt_nginx_helper_purge_all');
        }

        // clear wp rocket caches
        if ( function_exists( 'rocket_clean_domain' ) ) {
            rocket_clean_domain();
        }

        // clear godaddy internal caches
        if(class_exists('\WPaaS\Cache')) {
            if (has_action('shutdown', ['\WPaaS\Cache', 'ban'])) {
                return;
            }

            remove_action('shutdown', ['\WPaaS\Cache', 'purge'], PHP_INT_MAX);
            add_action('shutdown', ['\WPaaS\Cache', 'ban'], PHP_INT_MAX);
        }

        // clear wp-fastest caches
        if ( isset( $GLOBALS['wp_fastest_cache'] ) && method_exists( $GLOBALS['wp_fastest_cache'], 'deleteCache' ) ){
            $GLOBALS['wp_fastest_cache']->deleteCache();
        }

        if ( function_exists( 'wp_cache_clear_cache' ) ) {
            wp_cache_clear_cache();
        }

        // clear autooptimizepress caches
        if(class_exists('autoptimizeCache')) {
            \autoptimizeCache::clearall();
        }

        // clear wp-optimize caches
        if(class_exists('WPO_Page_Cache')) {
            (new \WPO_Page_Cache())->purge();
        }

        // clear SiteGround Optimizer caches
        if (function_exists('sg_cachepress_purge_cache')) {
            sg_cachepress_purge_cache();
        }

        if(defined('CLOUDFLARE_PLUGIN_DIR') && class_exists('CF\WordPress\Hooks')){
            (new \CF\WordPress\Hooks())->purgeCacheEverything();
        }

        // clear cloudflare breeze plugins caches
        if(defined('BREEZE_VERSION')) {
            do_action('breeze_purge_cache');
        }
    }
}
