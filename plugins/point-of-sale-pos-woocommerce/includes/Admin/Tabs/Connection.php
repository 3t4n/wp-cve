<?php

namespace ZPOS\Admin\Tabs;

use ZPOS\Admin\Setting\Box;
use ZPOS\Admin\Setting\PageTab;
use ZPOS\Admin\Setting\Input\ConnectionTypes;
use ZPOS\API\Auth;
use ZPOS\Deactivate;
use ZPOS\Plugin;
use ZPOS\Structure\ArrayObject;
use ZPOS_UI\License as UILicense;

class Connection extends PageTab
{
	public $exact = true;
	public $name;
	public $path = '/connection';

	public function __construct()
	{
		parent::__construct();

		$this->name = __('Connection', 'zpos-wp-api');
	}

	public function getBoxes()
	{
		$isPOSUIActive = Plugin::isActive('pos-ui');

		return [
			new Box(
				null,
				null,
				new ConnectionTypes(
					'',
					'pos_ui_connection',
					get_option('pos_ui_connection', 'cloud-hosted'),
					$this->get_connections_values($isPOSUIActive),
					['isPOSUIActive' => $isPOSUIActive]
				)
			),
		];
	}

	private function get_connections_values($isPOSUIActive)
	{
		$connections_values = [
			[
				'value' => 'cloud-hosted',
				'label' => __('Cloud Connection', 'zpos-wp-api'),
				'info' => [
					'Title' => __('Cloud', 'zpos-wp-api'),
					'TextDomain' => 'zpos-wp-api',
					'Description' => __('POS hosted on BizSwoop servers', 'zpos-wp-api'),
					'URI' => 'https://jovvie.com/cloud-vs-self/#tab_cloud-service',
					'Installed' => false,
					'Status' => false,
					'HideStatus' => true,
					'PermalinkURI' => Plugin::getPOSCloudAppURL(),
					'Type' => 'cloud-hosted',
					'Connected' => self::is_cloud_connected(),
					'ConnectionLabels' => [
						'true' => __('Connected', 'zpos-wp-api'),
						'false' => __('Not Connected', 'zpos-wp-api'),
					],
					'cards' => [
						[
							'icon' => 'window-restore',
							'title' => __('Connection', 'zpos-wp-api'),
							'link' => [
								'href' => 'https://pos.bizswoop.app/applications',
								'title' => __('Manage', 'zpos-wp-api'),
							],
						],
					],
				],
				'cards' => [
					[
						'icon' => 'draw-circle',
						'title' => __('Dashboard', 'zpos-wp-api'),
						'link' => [
							'href' => 'https://pos.bizswoop.app/',
							'title' => __('Open', 'zpos-wp-api'),
						],
					],
					[
						'icon' => 'shapes',
						'title' => __('Documentation', 'zpos-wp-api'),
						'link' => [
							'href' => 'https://jovvie.com/documentation/',
							'title' => __('View', 'zpos-wp-api'),
						],
					],
					[
						'icon' => 'rocket-launch',
						'title' => __('Quick Start Guide', 'zpos-wp-api'),
						'link' => [
							'href' => 'https://jovvie.com/quick-start-guide/pos-cloud/',
							'title' => __('Launch', 'zpos-wp-api'),
						],
					],
				],
			],
			[
				'value' => 'server-hosted',
				'label' => __('Self-Hosted Connection', 'zpos-wp-api'),
				'info' => [
					'Title' => __('Self-Hosted Server', 'zpos-wp-api'),
					'TextDomain' => 'zpos-wp-api',
					'Description' => __('POS hosted on your WordPress website server.', 'zpos-wp-api'),
					'URI' => 'https://jovvie.com/cloud-vs-self/#tab_self-hosted-plugin',
					'Installed' => in_array('zpos-wp-ui', array_column(get_plugins(), 'TextDomain')),
					'Status' => $isPOSUIActive,
					'HideStatus' => true,
					'PermalinkURI' => home_url('pos'),
					'Type' => 'server-hosted',
					'Connected' => self::is_ui_active(),
					'ConnectionLabels' => [
						'true' => __('Active', 'zpos-wp-api'),
						'false' => __('Not Active', 'zpos-wp-api'),
					],
					'Enable' => ['type' => 'link', 'to' => admin_url('plugins.php')],
				],
			],
		];

		return (new ArrayObject($connections_values))
			->map(function ($plugin) {
				$lowerCaseInfo = array_combine(
					array_map('strtolower', array_keys($plugin['info'])),
					$plugin['info']
				);

				$plugin = array_merge($plugin, ['info' => $lowerCaseInfo]);

				return array_combine(array_map('strtolower', array_keys($plugin)), $plugin);
			})
			->get();
	}

	public static function is_cloud_connected()
	{
		return 'cloud-hosted' === get_option('pos_ui_connection', 'cloud-hosted') &&
			Auth::is_cloud_key_installed();
	}

	public static function is_ui_active()
	{
		return 'server-hosted' === get_option('pos_ui_connection', 'cloud-hosted') &&
			Plugin::isActive('pos-ui') &&
			UILicense::isActive();
	}

	public function init()
	{
		register_setting('pos' . $this->path, 'pos_ui_connection', [
			'default' => 'cloud-hosted',
		]);
	}

	public static function reset()
	{
		if (!did_action(Deactivate::class . '::resetSettings')) {
			return _doing_it_wrong(
				__METHOD__,
				'Reset POS settings should called by ' . Deactivate::class . '::resetSettings',
				'2.0.3'
			);
		}

		delete_option('pos_ui_connection');
	}
}
