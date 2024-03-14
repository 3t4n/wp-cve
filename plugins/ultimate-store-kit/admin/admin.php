<?php

namespace UltimateStoreKit;

use Elementor\Utils;

if (!defined('ABSPATH')) {
	exit;
} // Exit if accessed directly

class Admin {

	public function __construct() {

		// Embed the Script on our Plugin's Option Page Only
		if (isset($_GET['page']) && ($_GET['page'] == 'ultimate_store_kit_options')) {
			add_action('admin_enqueue_scripts', [$this, 'enqueue_styles']);
		}

		add_action('admin_init', [$this, 'admin_script']);

		add_action('plugins_loaded', [$this, 'plugin_meta']);
	}

	/**
	 * @return [type] [description]
	 * Add some meta link in plugin page with the plugin
	 */
	public function plugin_meta() {
		add_filter('plugin_row_meta', [$this, 'plugin_row_meta'], 10, 2);
		add_filter('plugin_action_links_' . BDTUSK_PBNAME, [$this, 'plugin_action_meta']);
	}

	public function enqueue_styles() {

		$direction_suffix = is_rtl() ? '.rtl' : '';
		$suffix           = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style('bdt-uikit', BDTUSK_ADM_ASSETS_URL . 'css/bdt-uikit' . $direction_suffix . '.css', [], BDTUSK_VER);
		wp_enqueue_script('bdt-uikit', BDTUSK_ADM_ASSETS_URL . 'js/bdt-uikit' . $suffix . '.js', ['jquery'], BDTUSK_VER);
		wp_enqueue_style('ultimate-store-kit-font', BDTUSK_ASSETS_URL . 'css/ultimate-store-kit-font' . $direction_suffix . '.css', [], BDTUSK_VER);
		wp_enqueue_style('ultimate-store-kit-editor', BDTUSK_ASSETS_URL . 'css/ultimate-store-kit-editor' . $direction_suffix . '.css', [], BDTUSK_VER);
		wp_enqueue_style('ultimate-store-kit-admin', BDTUSK_ADM_ASSETS_URL . 'css/usk-admin' . $direction_suffix . '.css', [], BDTUSK_VER);
	}


	public function plugin_row_meta($plugin_meta, $plugin_file) {
		if (BDTUSK_PBNAME === $plugin_file) {
			$row_meta = [
				'docs'  => '<a href="https://bdthemes.com/contact/" aria-label="' . esc_attr(__('Go for Get Support', 'ultimate-store-kit')) . '" target="_blank">' . __('Get Support', 'ultimate-store-kit') . '</a>',
				'video' => '<a href="https://www.youtube.com/c/bdthemes" aria-label="' . esc_attr(__('View Ultimate Store Kit Video Tutorials', 'ultimate-store-kit')) . '" target="_blank">' . __('Video Tutorials', 'ultimate-store-kit') . '</a>',
			];

			$plugin_meta = array_merge($plugin_meta, $row_meta);
		}

		return $plugin_meta;
	}

	public function plugin_action_meta($links) {

		$links = array_merge([sprintf('<a href="%s">%s</a>', ultimate_store_kit_dashboard_link('#ultimate_store_kit_welcome'), esc_html__('Settings', 'ultimate-store-kit'))], $links);


		return $links;
	}

	/**
	 * register admin script
	 */
	public function admin_script() {
		$suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
		if (is_admin()) { // for Admin Dashboard Only
			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-form');

			if (isset($_GET['page']) && ($_GET['page'] == 'ultimate_store_kit_options')) {
				wp_enqueue_script('chart', BDTUSK_ADMIN_URL . 'assets/js/chart.min.js', ['jquery'], '3.9.3', true);
				wp_enqueue_script('usk-admin', BDTUSK_ADMIN_URL  . 'assets/js/usk-admin.js', ['jquery', 'chart'], BDTUSK_VER, true);
			} else {
				wp_enqueue_script('usk-admin', BDTUSK_ADMIN_URL  . 'assets/js/usk-admin.js', ['jquery'], BDTUSK_VER, true);
			}
		}
	}
}
