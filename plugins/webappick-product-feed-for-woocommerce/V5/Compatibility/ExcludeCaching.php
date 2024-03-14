<?php

namespace CTXFeed\V5\Compatibility;

use stdClass;

class ExcludeCaching {

	public function __construct() {

		// ########### Exclude Feed files from caching. #########################

		//WP Rocket Cache
		add_filter( 'rocket_cdn_reject_files', [ $this, 'exclude_feed_from_wp_rocket_cache' ], 10, 3 );
		//LiteSpeed Cache
		add_action( 'litespeed_init', [ $this, 'exclude_feed_from_litespeed_cache' ], 10, 0 );
		//WP Fastest Cache
		add_action( "admin_init", [ $this, 'exclude_feed_from_wp_fastest_cache' ], 10, 0 );
		//WP Super Cache
		add_action( "admin_init", [ $this, 'exclude_feed_from_wp_super_cache' ], 10, 0 );
		//BREEZE Cache
		add_action( "admin_init", [ $this, 'exclude_feed_from_breeze_cache' ], 10, 0 );
		//WP Optimize Cache
		add_action( "admin_init", [ $this, 'exclude_feed_from_wp_optimize_cache' ], 10, 0 );
		//Cache Enabler Cache
		add_action( "admin_init", [ $this, 'exclude_feed_from_cache_enabler_cache' ], 10, 0 );
		//SWIFT Performance Cache
		add_action( "admin_init", [ $this, 'exclude_feed_from_swift_performance_cache' ], 10, 0 );
		//Speed Booster Cache
		add_action( "admin_init", [ $this, 'exclude_feed_from_speed_booster_cache' ], 10, 0 );
		//Comet Cache
		add_action( "admin_init", [ $this, 'exclude_feed_from_comet_cache' ], 10, 0 );
		//Hyper Cache
		add_action( "admin_init", [ $this, 'exclude_feed_from_hyper_cache' ], 10, 0 );
		//TODO: W3C Cache
		//add_filter( 'w3tc_save_options', 'woo_save_w3tc_opt', 10, 3 );
	}

	/**
	 * Exclude Feed file URL form WP Rocket caching
	 *
	 * @param $files
	 *
	 * @return array
	 */
	public function exclude_feed_from_wp_rocket_cache( $files ) {
		return array_merge(
			$files,
			[
				'/wp-content/uploads/woo-feed/(.*)',
			]
		);
	}

	/**
	 * Exclude Feed file URL form LiteSpeed caching
	 *
	 * @return false
	 */
	public function exclude_feed_from_litespeed_cache() {
		if ( ! class_exists( 'LiteSpeed\Core' ) || ! defined( 'LSCWP_DIR' ) ) {
			return false;
		}

		$litespeed_ex_paths = maybe_unserialize( get_option( 'litespeed.conf.cdn-exc' ) );
		if ( $litespeed_ex_paths && is_array( $litespeed_ex_paths ) && ! in_array( '/wp-content/uploads/woo-feed', $litespeed_ex_paths, true ) ) {
			$litespeed_ex_paths = array_merge(
				$litespeed_ex_paths,
				[ '/wp-content/uploads/woo-feed' ]
			);
			update_option( 'litespeed.conf.cdn-exc', $litespeed_ex_paths );
		}

		return false;
	}

	/**
	 * Exclude Feed file URL form WP Fastest caching
	 *
	 * @return false
	 */
	public function exclude_feed_from_wp_fastest_cache() {

		if ( ! class_exists( 'WpFastestCache' ) ) {
			return false;
		}

		$wp_fastest_cache_ex_paths = json_decode( get_option( 'WpFastestCacheExclude' ), false );
		if ( $wp_fastest_cache_ex_paths && is_array( $wp_fastest_cache_ex_paths ) ) {

			$feed_path_exist = false;
			foreach ( $wp_fastest_cache_ex_paths as $path ) {
				if ( 'woo-feed' === $path->content ) {
					$feed_path_exist = true;
					break;
				}
			}

			if ( ! $feed_path_exist ) {
				$new_rule          = new stdClass();
				$new_rule->prefix  = "contain";
				$new_rule->content = 'woo-feed';
				$new_rule->type    = "page";

				$wp_fastest_cache_ex_paths = array_merge(
					$wp_fastest_cache_ex_paths,
					[ $new_rule ]
				);

				update_option( 'WpFastestCacheExclude', wp_json_encode( $wp_fastest_cache_ex_paths ) );
			}
		} elseif ( empty( $wp_fastest_cache_ex_paths ) ) {
			$wp_fastest_cache_ex_paths = [];
			$new_rule                  = new stdClass();
			$new_rule->prefix          = "contain";
			$new_rule->content         = 'woo-feed';
			$new_rule->type            = "page";

			$wp_fastest_cache_ex_paths = array_merge(
				$wp_fastest_cache_ex_paths,
				[ $new_rule ]
			);

			update_option( 'WpFastestCacheExclude', wp_json_encode( $wp_fastest_cache_ex_paths ) );
		}

		return false;
	}

	/**
	 * Exclude Feed file URL form WP Super caching
	 *
	 * @return false
	 */
	public function exclude_feed_from_wp_super_cache() {

		if ( ! function_exists( 'wpsc_init' ) ) {
			return false;
		}

		$wp_super_ex_paths = get_option( 'ossdl_off_exclude' );
		if ( $wp_super_ex_paths && strpos( $wp_super_ex_paths, 'woo-feed' ) === false ) {
			$wp_super_ex_paths = explode( ',', $wp_super_ex_paths );
			$wp_super_ex_paths = array_merge( $wp_super_ex_paths, [ 'woo-feed' ] );
			update_option( 'ossdl_off_exclude', implode( ',', $wp_super_ex_paths ) );
		}

		return false;
	}

	/**
	 * Exclude Feed file URL form BREEZE caching
	 *
	 * @return false
	 */
	public function exclude_feed_from_breeze_cache() {

		if ( ! class_exists( 'Breeze_Admin' ) ) {
			return false;
		}

		$breeze_settings = maybe_unserialize( get_option( 'breeze_cdn_integration' ) );
		if ( is_array( $breeze_settings ) ) {
			$woo_feed_files                         = [ '.xml', '.csv', '.tsv', '.txt', '.xls' ];
			$woo_feed_files                         = array_unique( array_merge( $woo_feed_files, $breeze_settings['cdn-exclude-content'] ) );
			$breeze_settings['cdn-exclude-content'] = $woo_feed_files;
			update_option( 'breeze_cdn_integration', $breeze_settings );
		}

		return false;
	}

	/**
	 * Exclude Feed file URL form WP Optimize caching
	 *
	 * @return false
	 */
	public function exclude_feed_from_wp_optimize_cache() {

		if ( ! class_exists( 'WP_Optimize' ) ) {
			return false;
		}

		$wp_optimize_ex_paths = maybe_unserialize( get_option( 'wpo_cache_config' ) );
		// If page Caching enabled
		if ( isset( $wp_optimize_ex_paths['enable_page_caching'] ) && $wp_optimize_ex_paths['enable_page_caching'] && is_array( $wp_optimize_ex_paths ) && ! in_array( '/wp-content/uploads/woo-feed', $wp_optimize_ex_paths['cache_exception_urls'], true ) ) {
			$woo_feed_ex_path['cache_exception_urls'] = [ '/wp-content/uploads/woo-feed' ];
			$wp_optimize_ex_paths                     = array_merge_recursive(
				$wp_optimize_ex_paths,
				$woo_feed_ex_path
			);
			update_option( 'wpo_cache_config', $wp_optimize_ex_paths );
		}

		return false;
	}

	/**
	 * Exclude Feed file URL form Cache Enabler caching
	 *
	 * @return false
	 */
	public function exclude_feed_from_cache_enabler_cache() {

		if ( ! class_exists( 'Cache_Enabler' ) ) {
			return false;
		}

		$cache_enabler_ex_paths = maybe_unserialize( get_option( 'cache_enabler' ) );
		if ( isset( $cache_enabler_ex_paths['excluded_page_paths'] ) && empty( $cache_enabler_ex_paths['excluded_page_paths'] ) ) {
			$cache_enabler_ex_paths['excluded_page_paths'] = '/wp-content/uploads/woo-feed/';
			update_option( 'cache_enabler', $cache_enabler_ex_paths );
		}

		return false;
	}

	/**
	 * Exclude Feed file URL form Swift Performance caching
	 *
	 * @return false
	 */
	public function exclude_feed_from_swift_performance_cache() {

		if ( ! class_exists( 'Swift_Performance_Lite' ) ) {
			return false;
		}

		$swift_perform_ex_paths = maybe_unserialize( get_option( 'swift_performance_options' ) );

		if ( $swift_perform_ex_paths && isset( $swift_perform_ex_paths['exclude-strings'] ) ) {
			$exclude_strings = $swift_perform_ex_paths['exclude-strings'];
			if ( is_array( $exclude_strings ) && ! in_array( '/wp-content/uploads/woo-feed', $exclude_strings, true ) ) {
				$woo_feed_ex_path['exclude-strings'] = [ '/wp-content/uploads/woo-feed' ];
				$swift_perform_ex_paths              = array_merge_recursive(
					$swift_perform_ex_paths,
					$woo_feed_ex_path
				);
			} else {
				$swift_perform_ex_paths['exclude-strings'] = [ '/wp-content/uploads/woo-feed' ];
			}
			update_option( 'swift_performance_options', $swift_perform_ex_paths );
		} elseif ( empty( $swift_perform_ex_paths ) ) {
			$swift_perform_ex_paths['exclude-strings'] = [ '/wp-content/uploads/woo-feed' ];
			update_option( 'swift_performance_options', $swift_perform_ex_paths );
		}

		return false;
	}

	/**
	 * Exclude Feed file URL form Speed Booster Pack caching
	 *
	 * @return false
	 */
	public function exclude_feed_from_speed_booster_cache() {

		if ( ! class_exists( 'Speed_Booster_Pack' ) ) {
			return false;
		}

		$feed_files             = [];
		$speed_booster_settings = maybe_unserialize( get_option( 'sbp_options' ) );
		if ( isset( $speed_booster_settings['caching_exclude_urls'] ) ) {
			$feed_files           = woo_feed_get_feed_file_list();
			$caching_exclude_urls = $speed_booster_settings['caching_exclude_urls'];
			if ( ! empty( $caching_exclude_urls ) ) {
				if ( ! empty( $feed_files ) ) {
					foreach ( $feed_files as $file ) {
						$file = str_replace( array( 'http://', 'https://' ), '', $file );
						if ( ! in_array( $file, explode( "\n", $caching_exclude_urls ), true ) ) {
							$caching_exclude_urls .= "\n" . $file;
						}
					}
				}
			} else {
				$caching_exclude_urls = str_replace( array( 'http://', 'https://' ), '', implode( "\n", $feed_files ) );
			}
			$speed_booster_settings['caching_exclude_urls'] = $caching_exclude_urls;
			update_option( 'sbp_options', $speed_booster_settings );
		}

		//TODO CDN extension exclude
		return false;
	}


	/**
	 * Exclude Feed file URL form Comet Cache caching
	 *
	 * @return false
	 */
	public function exclude_feed_from_comet_cache() {
		if ( ! is_plugin_active( 'comet-cache/comet-cache.php' ) ) {
			return false;
		}

		$comet_cache_settings = maybe_unserialize( get_option( 'comet_cache_options' ) );

		if ( $comet_cache_settings && isset( $comet_cache_settings['exclude_uris'] ) ) {
			$exclude_uris = $comet_cache_settings['exclude_uris'];
			if ( strpos( $exclude_uris, '/wp-content/uploads/woo-feed' ) === false ) {
				$exclude_uris                         .= "\n/wp-content/uploads/woo-feed";
				$comet_cache_settings['exclude_uris'] = $exclude_uris;
				update_option( 'comet_cache_options', $comet_cache_settings );
			}
		}

		return false;
	}


	/**
	 * Exclude Feed file URL form Swift Performance caching
	 *
	 * @return false
	 */
	public function exclude_feed_from_hyper_cache() {

		if ( ! class_exists( 'HyperCache' ) ) {
			return false;
		}

		$hyper_cache_settings = maybe_unserialize( get_option( 'hyper-cache' ) );
		if ( $hyper_cache_settings && isset( $hyper_cache_settings['reject_uris'] ) ) {
			$exclude_strings = $hyper_cache_settings['reject_uris'];
			if ( is_array( $exclude_strings ) && ! in_array( '/wp-content/uploads/woo-feed', $exclude_strings, true ) ) {
				$woo_feed_ex_path['reject_uris']         = [ '/wp-content/uploads/woo-feed' ];
				$woo_feed_ex_path['reject_uris_enabled'] = 1;
				$hyper_cache_settings                    = array_merge_recursive(
					$hyper_cache_settings,
					$woo_feed_ex_path
				);
			}
			update_option( 'hyper-cache', $hyper_cache_settings );
		}

		return false;
	}
}