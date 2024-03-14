<?php

namespace IC\Plugin\CartLinkWooCommerce\Notice;

use IC\Plugin\CartLinkWooCommerce\PluginData;

class NoticeWooCommerceRequired {

	/**
	 * @var PluginData
	 */
	private $plugin_data;

	/**
	 * @param PluginData $plugin_data .
	 */
	public function __construct( PluginData $plugin_data ) {
		$this->plugin_data = $plugin_data;
	}

	/**
	 * @return void
	 */
	public function hooks(): void {
		add_action( 'admin_notices', [ $this, 'display_notice' ] );
	}

	/**
	 * @return void
	 */
	public function display_notice(): void {
		if ( ! $this->should_display_notice() ) {
			return;
		}

		$plugin_name = $this->plugin_data->get_plugin_name();

		include $this->plugin_data->get_plugin_absolute_path( 'views/html-notice-woocommerce-required.php' );
	}

	/**
	 * @return bool
	 */
	private function should_display_notice(): bool {
		return ! is_plugin_active( 'woocommerce/woocommerce.php' );
	}
}
