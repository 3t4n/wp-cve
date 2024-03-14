<?php
/**
 * Clear Site Cache.
 *
 */

defined( 'ABSPATH' ) || exit;

/**
 * Site cache class.
 */
class REVIVESO_SiteCache
{
	use REVIVESO_Hooker;

	/**
	 * Register functions.
	 */
	public function register() {
		$this->action( 'reviveso_clear_site_cache', 'purge_site_cache' );
	}

	/**
	 * Purge site cache.
	 */
	public function purge_site_cache() {
		# WordPress default cache
		if ( function_exists( 'wp_cache_flush' ) ) {
			wp_cache_flush();
		}
			
		# Purge all W3 Total Cache
		if ( function_exists( 'w3tc_pgcache_flush' ) ) {
			w3tc_pgcache_flush();
		}
		
		# Purge WP Super Cache
		if ( function_exists( 'wp_cache_clear_cache' ) ) {
			wp_cache_clear_cache();
		}
		
		# Purge WP Rocket
		if ( function_exists( 'rocket_clean_domain' ) ) {
			rocket_clean_domain();
		}
		
		# Purge Wp Fastest Cache
		if ( function_exists( 'wpfc_clear_all_cache' ) ) {
			wpfc_clear_all_cache( true );
		}
		
		# Purge Cachify
		if ( function_exists( 'cachify_flush_cache' ) ) {
			cachify_flush_cache();
		}
		
		# Purge Comet Cache
		if ( class_exists( 'comet_cache' ) && method_exists( 'comet_cache', 'clear' ) ) {
			\comet_cache::clear();
		}
		
		# Purge Zen Cache
		if ( class_exists( 'zencache' ) && method_exists( 'zencache', 'clear' ) ) {
			\zencache::clear();
		}
		
		# Purge LiteSpeed Cache 
		if ( class_exists( '\LiteSpeed\Purge' ) && method_exists( '\LiteSpeed\Purge', 'purge_all' ) ) {
			\LiteSpeed\Purge::purge_all();
		}
		
		# Purge Cache Enabler
		if ( has_action( 'ce_clear_cache' ) ) {
			\do_action( 'ce_clear_cache' );
		}

		# Purge Hyper Cache
		if ( class_exists( 'HyperCache' ) ) {
			$hC = new \HyperCache();
			if ( method_exists( $hC, 'clean' ) ) {
			    $hC->clean();
			}
		}

		# Purge Autoptimize Cache
		if ( class_exists( 'autoptimizeCache' ) && method_exists( 'autoptimizeCache', 'clearall' ) ) {
			\autoptimizeCache::clearall();
		}   
		
		# Purge SG Optimizer
	    if ( function_exists( 'sg_cachepress_purge_cache' ) ) {
	    	sg_cachepress_purge_cache();
	    }
	    
	    # Purge Breeze Cache
	    if ( class_exists( 'Breeze_PurgeCache' ) && method_exists( 'Breeze_PurgeCache', 'breeze_cache_flush' ) ) {
	    	\Breeze_PurgeCache::breeze_cache_flush();
	    }
	
		# Purge Swift Cache
	    if ( class_exists( 'Swift_Performance_Cache' ) && method_exists( 'Swift_Performance_Cache', 'clear_all_cache' ) ) {
	    	\Swift_Performance_Cache::clear_all_cache();
	    }
	}
}