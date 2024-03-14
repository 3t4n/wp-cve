<?php

namespace Ilabs\BM_Woocommerce\Integration\Woocommerce_Blocks;

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;
use Exception;
use Ilabs\BM_Woocommerce\Domain\Service\White_Label\Group_Mapper;
use Ilabs\BM_Woocommerce\Gateway\Blue_Media_Gateway;

/**
 * Dummy Payments Blocks integration
 *
 * @since 1.0.3
 */
final class WC_Gateway_Autopay_Blocks_Support extends AbstractPaymentMethodType {

	/**
	 * The gateway instance.
	 *
	 * @var Blue_Media_Gateway
	 */
	private $gateway;

	/**
	 * Payment method name/id/slug.
	 *
	 * @var string
	 */
	protected $name = 'bluemedia';

	/**
	 * Settings from the WP options table
	 *
	 * @var array
	 */
	protected $settings = [];

	/**
	 * Initializes the payment method type.
	 */
	public function initialize() {
		$gateways      = WC()->payment_gateways->payment_gateways();
		$this->gateway = $gateways[ $this->name ];
	}

	/**
	 * Returns if this payment method should be active. If false, the scripts
	 * will not be enqueued.
	 *
	 * @return boolean
	 */
	public function is_active(): bool {
		return $this->gateway->is_available();
	}

	/**
	 * Returns an array of scripts/handles to be registered for this payment
	 * method.
	 *
	 * @return array
	 * @throws Exception
	 */
	public function get_payment_method_script_handles() {
		/*blue_media()->get_woocommerce_logger()->log_debug(
			'[WC_Gateway_Autopay_Blocks_Support get_payment_method_script_handles]' );*/

		$script_path       = 'blocks/assets/js/frontend/blocks.js';
		$script_path_css       = 'blocks/assets/js/frontend/blocks-styles.css';
		$script_asset_path = blue_media()->get_plugin_dir() . '/blocks/assets/js/frontend/blocks.asset.php';
		$script_asset      = file_exists( $script_asset_path )
			? require( $script_asset_path )
			: [
				'dependencies' => [],
				'version'      => '1.2.0',
			];
		$script_url        = blue_media()->get_plugin_url() . $script_path;
		$script_url_css        = blue_media()->get_plugin_url() . $script_path_css;

		wp_register_script(
			'autopay-payments-blocks',
			$script_url,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);

		wp_enqueue_style(
			'autopay-payments-blocks-css',
			$script_url_css
		);

		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( 'autopay-payments-blocks',
				blue_media()->get_text_domain(),
				blue_media()->get_plugin_dir() . '/' . blue_media()->get_from_config( 'lang_dir' ) );
		}

		return [ 'autopay-payments-blocks' ];
	}

	/**
	 * Returns an array of key=>value pairs of data made available to the
	 * payment methods script.
	 *
	 * @return array
	 */
	public function get_payment_method_data(): array {
		$channels = blue_media()
			->get_blue_media_gateway()
			->gateway_list( true );

		return [
			'title'       => $this->gateway->get_title(),
			'description' => $this->gateway->get_method_description(),
			'icon_src'    => blue_media()->get_plugin_images_url() . "/logo-autopay-banner.svg",
			'supports'    => array_filter( $this->gateway->supports,
				[ $this->gateway, 'supports' ] ),
			'channels'    => ( new Group_Mapper( $channels ) )->map_for_blocks(),
		];
	}
}
