<?php
/**
 * Global boot
 *
 * @author        Webcraftic <wordpress.webraftic@gmail.com>
 * @copyright (c) 01.07.2018, Webcraftic
 * @version       1.0
 */

// Exit if accessed directly
if( !defined('ABSPATH') ) {
	exit;
}

/**
 * Remove wp version from any enqueued scripts
 *
 * @param string $target_url
 *
 * @return string
 */
function wbcr_mac_hide_file_versions($src, $handle)
{
	if( is_user_logged_in() && !WMAC_Plugin::app()->getPopulateOption('optimize_scripts_for_logged') ) {
		return $src;
	}

	$filename_arr = explode('?', basename($src));
	$exclude_file_list = WMAC_Plugin::app()->getPopulateOption('remove_version_exclude', '');
	$exclude_files_arr = array_map('trim', explode(PHP_EOL, $exclude_file_list));

	if( strpos($src, 'ver=') && !in_array(str_replace('?' . $filename_arr[1], '', $src), $exclude_files_arr, true) ) {
		$src = remove_query_arg('ver', $src);
	}

	return $src;
}

/**
 * Priority set to 9999. Higher numbers correspond with later execution.
 * Hook into the style loader and remove the version information.
 */

if( WMAC_Plugin::app()->getPopulateOption('remove_style_version') ) {
	add_filter('style_loader_src', 'wbcr_mac_hide_file_versions', 9999, 2);
}
/**
 * Hook into the script loader and remove the version information.
 */
if( WMAC_Plugin::app()->getPopulateOption('remove_js_version') ) {
	add_filter('script_loader_src', 'wbcr_mac_hide_file_versions', 9999, 2);
}

function wbcr_mac_clear_cache()
{
	if( isset($_GET['wbcr_mac_clear_cache']) && isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'clear_all_cache') ) {
		$page = isset($_GET['page']) ? $_GET['page'] : 'minify_and_combine-' . WMAC_Plugin::app()->getPluginName();

		if( is_network_admin() ) {
			WMAC_PluginCache::clearAllMultisite();
			$base_url = network_admin_url('settings.php') . '?page=' . $page;
		} else {
			WMAC_PluginCache::clearAll();
			$base_url = admin_url('options-general.php') . '?page=' . $page;
		}

		wp_safe_redirect(add_query_arg(['wbcr_mac_clear_cache_success' => 1], $base_url));
	}
}

add_action('init', 'wbcr_mac_clear_cache');

/**
 * Добавляем кнопку сброса кеша в админ бар
 *
 * @param $wp_admin_bar
 */
function wbcr_mac_admin_bar_menu($wp_admin_bar)
{
	if( !current_user_can('manage_options') ) {
		return;
	}
	$current_url = esc_url(wp_nonce_url(add_query_arg(['wbcr_mac_clear_cache' => 1]), 'clear_all_cache'));
	$percent = '';
	if( !is_network_admin() ) {
		$get_used_cache = WMAC_PluginCache::getUsedCache();
		$percent = ' (' . $get_used_cache['percent'] . '%)';
	}

	$args = [
		'id' => 'clear-cache-btn',
		'title' => __('Clear cache', 'minify-and-combine') . $percent,
		'href' => $current_url
	];
	$wp_admin_bar->add_menu($args);
}

/**
 * Добавляем кнопку сброса кеша в Clearfy меню
 */
function wbcr_mac_clearfy_admin_bar_menu($menu_items)
{
	$current_url = esc_url(wp_nonce_url(add_query_arg(['wbcr_mac_clear_cache' => 1]), 'clear_all_cache'));
	$percent = '';
	if( !is_network_admin() ) {
		$get_used_cache = WMAC_PluginCache::getUsedCache();
		$percent = ' (' . $get_used_cache['percent'] . '%)';
	}

	$menu_items['mac-clear-cache'] = [
		'title' => '<span class="dashicons dashicons-image-rotate"></span> ' . __('Clear cache', 'minify-and-combine') . $percent,
		'href' => $current_url
	];

	return $menu_items;
}

if( defined('LOADING_MINIFY_AND_COMBINE_AS_ADDON') ) {
	add_action('wbcr/clearfy/adminbar_menu_items', 'wbcr_mac_clearfy_admin_bar_menu');
} else {
	add_action('admin_bar_menu', 'wbcr_mac_admin_bar_menu');
}