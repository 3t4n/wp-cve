<?php
/**
 * Cache handler
 *
 * @package nativerent
 */

namespace NativeRent;

use function function_exists;
use function has_action;
use function sc_cache_flush;
use function seraph_accel\CacheOp;

defined( 'ABSPATH' ) || exit;

/**
 * Class Cache_Handler
 */
class Cache_Handler {

	/**
	 * Check if Cache is active
	 */
	public static function is_active_cache() {
		$file = trailingslashit( WP_CONTENT_DIR ) . 'advanced-cache.php';
		if ( file_exists( $file ) || ( defined( 'WP_CACHE' ) && true === WP_CACHE ) || self::is_cachify_fallback() ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if clearing possible
	 *
	 * @return bool
	 */
	public static function is_clearing_cache_possible() {
		// WP Super Cache.
		if ( self::is_wp_super_cache() || self::is_wp_super_cache_1() ) {
			return true;
		}

		// W3 Total Cache.
		if ( self::is_w3_total_cache() ) {
			return true;
		}

		// WP Fastest Cache.
		if ( self::is_wp_fastest_cache() ) {
			return true;
		}

		// Autoptimize.
		if ( self::is_autoptimize() ) {
			return true;
		}

		// WP Optimize.
		if ( self::is_wp_optomize() ) {
			return true;
		}

		// Comet Cache.
		if ( self::is_comet_cache() ) {
			return true;
		}

		// Cachify.
		if ( self::is_cachify() ) {
			return true;
		}

		// Rapid Cache.
		if ( self::is_rapid_cache() ) {
			return true;
		}

		// Swift Performance.
		if ( self::is_swift_performance_cache() ) {
			return true;
		}

		// WP Engine.
		if ( self::is_wp_engine() ) {
			return true;
		}

		// SG Optimizer.
		if ( self::is_siteground_optimizer() ) {
			return true;
		}

		// D-WP cache.
		if ( self::is_d_wp_cache() ) {
			return true;
		}

		// Nginx Helper.
		if ( self::is_nginx_helper() ) {
			return true;
		}

		// Breeze cache.
		if ( self::is_breeze() ) {
			return true;
		}

		// Hummingbird.
		if ( self::is_hummingbird() ) {
			return true;
		}

		// HyperCache.
		if ( self::is_hypercache() ) {
			return true;
		}

		// WP Rocket.
		if ( self::is_rocket() ) {
			return true;
		}

		// Seraphinite Accelerator.
		if ( self::is_seraph_accel() ) {
			return true;
		}

		// Simple Cache.
		if ( self::is_simple_cache() ) {
			return true;
		}

		return false;
	}

	/**
	 * Clear cache
	 */
	public static function clear_cache() {
		// WP Super Cache.
		if ( self::is_wp_super_cache() ) {
			if ( is_multisite() ) {
				\wp_cache_clear_cache( get_current_blog_id() );
			} else {
				\wp_cache_clear_cache();
			}
		} elseif ( self::is_wp_super_cache_1() ) {
			global $cache_path;
			if ( is_multisite() ) {
				\prune_super_cache( get_supercache_dir( get_current_blog_id() ), true );
				\prune_super_cache( $cache_path . 'blogs/', true );
			} else {
				\prune_super_cache( $cache_path . 'supercache/', true );
				\prune_super_cache( $cache_path, true );
			}

			// W3 Total Cache.
		} elseif ( self::is_w3_total_cache() ) {
			\w3tc_pgcache_flush();

			// WP Fastest Cache.
		} elseif ( self::is_wp_fastest_cache() ) {
			$wpfc = new \WpFastestCache();
			$wpfc->deleteCache( true );

			// Autoptimize.
		} elseif ( self::is_autoptimize() ) {
			\autoptimizeCache::clearall();

			// WP Optimize.
		} elseif ( self::is_wp_optomize() ) {
			\WP_Optimize()->get_page_cache()->purge();

			// Comet Cache.
		} elseif ( self::is_comet_cache() ) {
			\comet_cache::clear();

			// Cachify.
		} elseif ( self::is_cachify() ) {
			do_action( 'cachify_flush_cache' );

			// Rapid Cache.
		} elseif ( self::is_rapid_cache() ) {
			\rapidcache_clear_cache();

			// Swift Performance.
		} elseif ( self::is_swift_performance_cache() ) {
			\Swift_Performance_Cache::clear_all_cache();

			// WP Engine.
		} elseif ( self::is_wp_engine() ) {
			\WpeCommon::purge_varnish_cache();

			// SG Optimizer.
		} elseif ( self::is_siteground_optimizer() ) {
			\sg_cachepress_purge_cache();

			// Nginx Helper.
		} elseif ( self::is_nginx_helper() ) {
			do_action( 'rt_nginx_helper_purge_all' );

			// Breeze cache.
		} elseif ( self::is_breeze() ) {
			do_action( 'breeze_clear_all_cache' );

			// Hummingbird.
		} elseif ( self::is_hummingbird() ) {
			do_action( 'wphb_clear_page_cache' );

			// HyperCache.
		} elseif ( self::is_hypercache() ) {
			do_action( 'autoptimize_action_cachepurged' );

			// D-WP cache.
		} elseif ( self::is_d_wp_cache() ) {
			\d_cache::get()->clear_all();

			// WP Rocket.
		} elseif ( self::is_rocket() ) {
			\rocket_clean_domain();

			// Seraphinite Accelerator.
		} elseif ( self::is_seraph_accel() ) {
			CacheOp( 0 );

			// Simple Cache.
		} elseif ( self::is_simple_cache() ) {
			sc_cache_flush();
		}

		do_action( 'nativerent_cache_is_cleared' );
	}

	/**
	 * Exceptions for plugins with unusual behaviour.
	 *
	 * @return bool
	 */
	public static function is_compatability_mode() {
		// Check for incompatible plugins.
		if ( self::is_d_wp_cache() || self::is_cachify_fallback() ) {
			return true;
		}

		return false;
	}

	/**
	 * WP Super Cache.
	 *
	 * @return bool
	 */
	private static function is_wp_super_cache() {
		return function_exists( 'wp_cache_clear_cache' );
	}

	/**
	 * WP Super Cache.
	 *
	 * @return bool
	 */
	private static function is_wp_super_cache_1() {
		return ( file_exists( WP_CONTENT_DIR . '/wp-cache-config.php' ) && function_exists( 'prune_super_cache' ) );
	}

	/**
	 * W3 Total Cache.
	 *
	 * @return bool
	 */
	private static function is_w3_total_cache() {
		return function_exists( 'w3tc_pgcache_flush' );
	}

	/**
	 * WP Fastest Cache.
	 *
	 * @return bool
	 */
	private static function is_wp_fastest_cache() {
		 return ( class_exists( 'WpFastestCache' ) && method_exists( 'WpFastestCache', 'deleteCache' ) );
	}

	/**
	 * Autoptimize.
	 *
	 * @return bool
	 */
	private static function is_autoptimize() {
		return ( class_exists( 'autoptimizeCache' ) && is_callable( array( 'autoptimizeCache', 'clearall' ) ) );
	}

	/**
	 * WP Optimize.
	 *
	 * @return bool
	 */
	private static function is_wp_optomize() {
		return ( class_exists( 'WP_Optimize' ) && method_exists( 'WP_Optimize', 'get_page_cache' ) );
	}

	/**
	 * Comet Cache.
	 *
	 * @return bool
	 */
	private static function is_comet_cache() {
		return ( class_exists( '\\comet_cache' ) && is_callable( array( '\\comet_cache', 'clear' ) ) );
	}

	/**
	 * Cachify.
	 *
	 * @return bool
	 */
	private static function is_cachify() {
		if ( has_action( 'cachify_flush_cache' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Cachify fallback checking.
	 *
	 * @return bool
	 */
	private static function is_cachify_fallback() {
		return isset( $_SERVER['CACHIFY_HOST'] );
	}

	/**
	 * Rapid Cache.
	 *
	 * @return bool
	 */
	private static function is_rapid_cache() {
		return function_exists( 'rapidcache_clear_cache' );
	}

	/**
	 * Swift Performance.
	 *
	 * @return bool
	 */
	private static function is_swift_performance_cache() {
		return ( class_exists( 'Swift_Performance_Cache' )
			&& is_callable(
				array(
					'Swift_Performance_Cache',
					'clear_all_cache',
				)
			)
		);
	}

	/**
	 * WP Engine.
	 *
	 * @return bool
	 */
	private static function is_wp_engine() {
		return ( class_exists( 'WpeCommon' ) && is_callable( array( 'WpeCommon', 'purge_varnish_cache' ) ) );
	}

	/**
	 * SiteGround Optimizer.
	 *
	 * @return bool
	 */
	private static function is_siteground_optimizer() {
		return ( function_exists( 'sg_cachepress_purge_cache' ) );
	}

	/**
	 * D-WP cache.
	 *
	 * @return bool
	 */
	private static function is_d_wp_cache() {
		return class_exists( 'd_cache' );
	}

	/**
	 * Nginx Helper.
	 *
	 * @return bool
	 */
	private static function is_nginx_helper() {
		return defined( 'NGINX_HELPER_BASENAME' );
	}

	/**
	 * Breeze cache.
	 */
	private static function is_breeze() {
		return class_exists( 'Breeze_Admin' );
	}

	/**
	 * Hummingbird.
	 */
	private static function is_hummingbird() {
		return class_exists( 'Hummingbird\\WP_Hummingbird' );
	}

	/**
	 * HyperCache.
	 */
	private static function is_hypercache() {
		return class_exists( 'HyperCache' );
	}

	/**
	 * WP Rocket.
	 */
	private static function is_rocket() {
		return function_exists( 'rocket_clean_domain' );
	}

	/**
	 * Seraphinite Accelerator.
	 */
	private static function is_seraph_accel() {
		return function_exists( 'seraph_accel\\CacheOp' );
	}

	/**
	 * Simple Cache.
	 */
	private static function is_simple_cache() {
		return function_exists( 'sc_cache_flush' );
	}
}
