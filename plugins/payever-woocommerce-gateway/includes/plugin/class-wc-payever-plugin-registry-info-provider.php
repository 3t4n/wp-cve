<?php
if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Plugin_Registry_Info_Provider' ) ) {
	return;
}

use Payever\Sdk\Core\Enum\ChannelSet;
use Payever\Sdk\Plugins\Enum\PluginCommandNameEnum;
use Payever\Sdk\Plugins\Base\PluginRegistryInfoProviderInterface;

class WC_Payever_Plugin_Registry_Info_Provider implements PluginRegistryInfoProviderInterface {

	use WC_Payever_WP_Wrapper_Trait;

	/**
	 * @inheritDoc
	 */
	public function getPluginVersion() {
		return WC_PAYEVER_PLUGIN_VERSION;
	}

	/**
	 * @inheritDoc
	 */
	public function getCmsVersion() {
		return WOOCOMMERCE_VERSION;
	}

	/**
	 * @inheritDoc
	 */
	public function getHost() {
		return get_site_url();
	}

	/**
	 * @inheritDoc
	 */
	public function getChannel() {
		return ChannelSet::CHANNEL_WOOCOMMERCE;
	}

	/**
	 * @inheritDoc
	 */
	public function getSupportedCommands() {
		return array(
			PluginCommandNameEnum::SET_SANDBOX_HOST,
			PluginCommandNameEnum::SET_LIVE_HOST,
			PluginCommandNameEnum::NOTIFY_NEW_PLUGIN_VERSION,
			PluginCommandNameEnum::SET_API_VERSION,
		);
	}

	/**
	 * @inheritDoc
	 */
	public function getCommandEndpoint() {
		return WC()->api_request_url( 'payever_execute_commands' );
	}

	/**
	 * @inheritDoc
	 */
	public function getBusinessIds() {
		return array(
			$this->get_wp_wrapper()->get_option( WC_Payever_Helper::PAYEVER_BUSINESS_ID ),
		);
	}
}
