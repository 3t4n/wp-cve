<?php
/**
 * Native Rent functions
 *
 * @package    nativerent
 * @subpackage nativerent/includes
 */

use NativeRent\Options;

defined( 'ABSPATH' ) || exit;

/**
 * Clear cache
 *
 * @return bool
 */
function nativerent_clear_cache_possible() {
	return NativeRent\Cache_Handler::is_clearing_cache_possible();
}

/**
 * Check if there is a compatible cache plugins installed and clear their cache.
 *
 * @return void
 */
function nativerent_clear_cache() {
	NativeRent\Cache_Handler::clear_cache();
}

/**
 * Check if cache is active.
 *
 * @return bool
 */
function nativerent_cache_active() {
	return NativeRent\Cache_Handler::is_active_cache();
}

/**
 * Error reporting function.
 *
 * @param  Exception|WP_Error $error  Error instance.
 * @param  array              $extra  Additional data.
 *
 * @return void
 */
function nativerent_report_error( $error, $extra = array() ) {
	if ( $error instanceof WP_Error ) {
		$error = new Exception( $error->get_error_code() . ': ' . $error->get_error_message() );
	}

	if ( $error instanceof Exception ) {
		try {
			NativeRent\API::report_error( $error, $extra );
		} catch ( Exception $e ) {
			// Skip errors. TODO: need create errors dump.
			return;
		}
	}
}

/**
 * Wrapper for `get_plugins()`
 *
 * @return array[]
 */
function nativerent_get_plugins() {
	if ( ! function_exists( 'get_plugins' ) ) {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	return get_plugins();
}

/**
 * Get some info about this WP
 *
 * @return array{version: string, plugins: array{name: string, version: string, url: string}}
 */
function nativerent_get_wp_info() {
	global $wp_version;

	$wp_plugins = nativerent_get_plugins();
	$plugins    = array();
	if ( is_array( $wp_plugins ) ) {
		foreach ( $wp_plugins as $plugin ) {
			$plugins[] = array(
				'name'    => $plugin['Name'],
				'version' => $plugin['Version'],
				'url'     => $plugin['PluginURI'],
			);
		}
	}

	return array(
		'cms'     => 'Wordpress',
		'version' => ! empty( $wp_version ) ? $wp_version : '(undefined)',
		'plugins' => $plugins,
	);
}

/**
 * Get current state data.
 *
 * @return array{
 *     options: array{
 *          siteID: ?string,
 *          version: ?string,
 *          adUnitsConfig: ?array,
 *     },
 *     cmsInfo: array,
 * }
 */
function nativerent_get_plugin_state() {
	return array(
		'options' => array(
			'siteID'               => Options::get_site_id(),
			'version'              => Options::get_version(),
			'siteModerationStatus' => Options::get_site_moderation_status()->get_value(),
			'adUnitsConfig'        => Options::get_adunits_config(),
			'monetizations'        => Options::get_monetizations()->convert_to_array(),
		),
		'cmsInfo' => nativerent_get_wp_info(),
	);
}

/**
 * Get paginated posts list
 *
 * @param  int $page      Page.
 * @param  int $per_page  Posts per page.
 *
 * @return WP_Post[]
 * @throws \Exception
 */
function nativerent_get_posts( $page = 1, $per_page = 1 ) {
	$q = new WP_Query();

	return $q->query(
		array(
			'post_type'      => 'post',
			'post_status'    => array( 'publish' ),
			'orderby'        => 'ID',
			'order'          => 'DESC',
			'posts_per_page' => $per_page,
			'paged'          => $page,
		)
	);
}

/**
 * Get permalinks by posts list
 *
 * @param  WP_Post[] $posts  List of posts.
 *
 * @return  string[]
 */
function nativerent_get_posts_permalinks( $posts ) {
	$links = array();
	foreach ( $posts as $post ) {
		if ( ! $post instanceof WP_Post ) {
			continue;
		}
		$link = get_permalink( $post );
		if ( ! is_string( $link ) ) {
			continue;
		}

		$links[] = $link;
	}

	return $links;
}
