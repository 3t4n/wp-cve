<?php
/**
 * It includes the some information about the most popular plugins.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.
$maintenance_plugins = array(
	'cmp-coming-soon-maintenance/niteo-cmp.php',
	'wp-maintenance-mode/wp-maintenance-mode.php',
	'maintenance/maintenance.php',
	'coming-soon-page/coming_soon.php',
	'nifty-coming-soon-and-under-construction-page/nifty-coming-soon.php',
	'lj-maintenance-mode/lj-maintenance-mode.php',
	'wp-maintenance/wp-maintenance.php',
	'coming-soon-maintenance-mode-from-acurax/acx_csma.php',
	'colorlib-coming-soon-maintenance/colorlib-coming-soon-and-maintenance-mode.php',
);
$disable_on_process  = array( 
	'query-monitor/query-monitor.php',
	'wp-hummingbird/wp-hummingbird.php'
);
$opts                = eos_dp_get_option( 'eos_dp_pro_main' );
$opts                = isset( $opts['plugins_setts'] ) ? $opts['plugins_setts'] : array();
$opts                = isset( $opts['as'] ) ? $opts['as'] : array();
if ( $plugins && is_array( $plugins ) && ! empty( $plugins ) ) {
	foreach ( $plugins as $plugin ) {
		$b = isset( $opts[ sanitize_key( dirname( $plugin ) ) ] ) && ! $opts[ sanitize_key( dirname( $plugin ) ) ] ? false : true;
		if ( ! $b ) {
			$disable_on_process[] = $plugin;
		}
	}
}
$disable_on_process = array_unique( array_merge( $disable_on_process, $maintenance_plugins ) );
$plugins_to_skip    = array(
	'all-in-one-seo-pack/all_in_one_seo_pack.php',
	'autoptimize/autoptimize.php',
	'asset-preloader/asset-preloader.php',
	'breeze/breeze.php',
	'cache-enabler/cache-enabler.php',
	'cachify/cachify.php',
	'comet-cache/comet-cache.php',
	'cookie-law-info/cookie-law-info.php',
	'cookie-notice/cookie-notice.php',
	'fdp-debug/fdp-debug.php',
	'flying-pages/flying-pages.php',
	'flying-press/flying-press.php',
	'freesoul-deactivate-plugins-pro/freesoul-deactivate-plugins-pro.php',
	'freesoul-deactivate-plugins/freesoul-deactivate-plugins.php',
	'google-analytics-for-wordpress/googleanalytics.php',
	'https-redirection/https-redirection.php',
	'hyper-cache/plugin.php',
	'insert-headers-and-footers/ihaf.php',
	'litespeed-cache/litespeed-cache.php',
	// 'minify-html-markup/minify-html.php',
	'perfmatters/perfmatters.php',
	'really-simple-ssl/rlrsssl-really-simple-ssl.php',
	'scfm-debug/scfm-debug.php',
	'simple-cache/simple-cache.php',
	'simple-google-analytics/simple_google_analytics.php',
	'swift-performance-lite/performance.php',
	'w3-total-cache/w3-total-cache.php',
	'wordfence/wordfence.php',
	'wordpress-seo-premium/wordpress-seo-premium.php',
	'wordpress-seo/wordpress-seo.php',
	'wp-fastest-cache/wpFastestCache.php',
	'wp-hummingbird/wp-hummingbird.php',
	'wp-optimize/wp-optimize.php',
	'wp-performance/wp-performance.php',
	'wp-rocket/wp-rocket.php',
	'wp-speed-of-light/wp-speed-of-light.php',
	'wp-super-cache/wp-cache.php',
	'wpbase-cache/wpbase-cache.php',
	'wpe-advanced-cache-options/wpe-advanced-cache.php',
);
$skip_strings       = array(
	'-webp',
	'/webp-',
	'-cache',
	'/cache-',
	'-seo',
	'/seo-',
	'-minify',
	'/minify',
	'-ssl',
	'/ssl',
);

$exclude_from_skip_strings = array(
	'content-no-cache/content-no-cache.php',
);

if ( $plugins && is_array( $plugins ) && ! empty( $plugins ) ) {
	foreach ( $plugins as $plugin ) {
		foreach ( $skip_strings as $string ) {
			if ( false !== strpos( $plugin, $string ) && ! in_array( $plugin, $exclude_from_skip_strings ) ) {
				$plugins_to_skip[] = $plugin;
				break;
			}
		}
	}
}
$plugins_to_skip = array_merge( $disable_on_process, $plugins_to_skip );
$backend_plugins = array(
	'updraftplus/updraftplus.php',
	'all-in-one-wp-migration/all-in-one-wp-migration.php',
	'all-in-one-wp-migration-unlimited-extension/all-in-one-wp-migration-unlimited-extension.php',
	'better-search-replace/better-search-replace.php',
	'broken-link-checker/broken-link-checker.php',
	'duplicate-post/duplicate-post.php',
	'duplicator/duplicator.php',
	'one-click-demo-import/one-click-demo-import.php',
	'query-monitor/query-monitor.php',
	'wordpress-importer/wordpress-importer.php',
	'wp-beta-tester/wp-beta-tester.php',
);
