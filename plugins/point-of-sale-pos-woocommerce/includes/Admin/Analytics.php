<?php

namespace ZPOS\Admin;

use const ZPOS\PLUGIN_ROOT;
use const ZPOS\PLUGIN_ROOT_FILE;
use const ZPOS\PLUGIN_VERSION;

class Analytics
{
	private $reports;

	public function __construct()
	{
		new Analytics\Orders();

		$this->reports = [
			[
				'key' => 'pos-gateway',
				'title' => __('Payments', 'zpos-wp-api'),
				'position' => 4,
			],
			[
				'key' => 'pos-user',
				'title' => __('Users', 'zpos-wp-api'),
				'position' => 8,
			],
			[
				'key' => 'pos-station',
				'title' => __('Stations', 'zpos-wp-api'),
				'position' => 9,
			],
		];

		add_filter('woocommerce_analytics_report_menu_items', [$this, 'add_report_pages']);
		add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
	}

	public function add_report_pages(array $report_pages): array
	{
		foreach ($this->reports as $report) {
			$report_pages = array_merge(
				array_slice($report_pages, 0, $report['position'], true),
				[
					[
						'id' => $report['key'],
						'title' => $report['title'],
						'parent' => 'woocommerce-analytics',
						'path' => '/analytics/' . $report['key'],
					],
				],
				array_slice($report_pages, $report['position'], count($report_pages) - 1, true)
			);
		}

		return $report_pages;
	}

	public function enqueue_assets()
	{
		$asset_path = PLUGIN_ROOT . '/assets/analytics/analytics.asset.php';
		$asset = file_exists($asset_path)
			? require $asset_path
			: ['dependencies' => [], 'version' => PLUGIN_VERSION];

		wp_enqueue_script(
			'zpos_analytics',
			plugins_url('assets/analytics/analytics.js', PLUGIN_ROOT_FILE),
			$asset['dependencies'],
			$asset['version'],
			true
		);
		wp_enqueue_style(
			'zpos_analytics',
			plugins_url('assets/analytics/analytics.css', PLUGIN_ROOT_FILE),
			[],
			$asset['version']
		);
	}
}
