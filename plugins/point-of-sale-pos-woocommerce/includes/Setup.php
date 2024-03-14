<?php

namespace ZPOS;

class Setup
{
	public function __construct()
	{
		new Activate();
		new Deactivate();
		new Login();
		new Auth();

		add_action('before_woocommerce_init', [$this, 'add_order_storage_support']);
		add_action('woocommerce_init', [$this, 'init']);
		add_action('plugins_loaded', [$this, 'checkVersion']);

		$this->activateUpdateCenter();
	}

	public function add_order_storage_support()
	{
		if (class_exists('Automattic\WooCommerce\Utilities\FeaturesUtil')) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
				'custom_order_tables',
				PLUGIN_ROOT_FILE,
				true
			);
		}
	}

	public function checkVersion()
	{
		if (!class_exists('WooCommerce')) {
			add_action('admin_notices', [$this, 'requireWCNotice']);
		}
	}

	public function requireWCNotice()
	{
		echo '<div class="notice notice-error is-dismissible"><p>';
		_e('Point of Sale (POS) for WooCommerce require WooCommerce', 'zpos-wp-api');
		echo '</p></div>';
	}

	public function init()
	{
		if (!class_exists('WooCommerce')) {
			return;
		}

		new Translation();
		new Woocommerce();
		new Frontend();
		new Model();
		new Emails();
		new API();
		new Admin();
	}

	private function activateUpdateCenter()
	{
		require PLUGIN_ROOT . '/plugin-update-checker/plugin-update-checker.php';

		$updateChecker = \Puc_v4_Factory::buildUpdateChecker(
			'https://github.com/SupportBizSwoop/Point-of-Sale-WP-API-Deploy/',
			PLUGIN_ROOT_FILE,
			'Point-of-Sale-WP-API'
		);

		$updateChecker->setAuthentication('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');
	}
}
