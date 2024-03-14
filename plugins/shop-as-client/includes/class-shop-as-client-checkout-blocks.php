<?php
use Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface;

/**
 * Class for integrating with WooCommerce Blocks
 */
class ShopAsClient_Checkout_Blocks implements IntegrationInterface {

	/**
	 * The name of the integration.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'ptwoo_shop_as_client';
	}

	/**
	 * When called invokes any initialization/setup for the integration.
	 */
	public function initialize() {
		$this->register_block_frontend_scripts();
		$this->register_block_editor_scripts();
	}

	/**
	 * Returns an array of script handles to enqueue in the frontend context.
	 *
	 * @return string[]
	 */
	public function get_script_handles() {
		return array( 'ptwoo-shop-as-client-block-frontend' );
	}

	/**
	 * Returns an array of script handles to enqueue in the editor context.
	 *
	 * @return string[]
	 */
	public function get_editor_script_handles() {
		return array( 'ptwoo-shop-as-client-block-editor' );
	}

	/**
	 * An array of key, value pairs of data made available to the block on the client side.
	 *
	 * @return array
	 */
	public function get_script_data() {

		$data = array(
			'canCheckout'         => wc_string_to_bool( shop_as_client_can_checkout() ),
			'defaultShopAsClient' => wc_string_to_bool( apply_filters( 'shop_as_client_default_shop_as_client', 'yes' ) ),
			'defaultCreateUser'   => wc_string_to_bool( apply_filters( 'shop_as_client_default_create_user', 'no' ) ),
			'showProAddOnNotice'  => true,
			'blockPosition'       => '.wp-block-woocommerce-checkout-terms-block',
		);

		$data = apply_filters( 'shop_as_client_blocks_data', $data );

		return $data;
	}

	/**
	 * Register block editor scripts.
	 *
	 * @return void
	 */
	public function register_block_editor_scripts() {
		$script_url        = SHOPASCLIENT_PLUGIN_URL . 'build/ptwoo-shop-as-client-block.js';
		$script_asset_path = SHOPASCLIENT_PLUGIN_DIR . 'build/ptwoo-shop-as-client-block.asset.php';
		$script_asset      = file_exists( $script_asset_path )
			? require $script_asset_path
			: array(
				'dependencies' => array(),
				'version'      => $this->get_file_version( $script_asset_path ),
			);

		wp_register_script(
			'ptwoo-shop-as-client-block-editor',
			$script_url,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);

		wp_set_script_translations(
			'ptwoo-shop-as-client-block-editor',
			'shop-as-client'
		);
	}

	/**
	 * Register block frontend scripts.
	 *
	 * @return void
	 */
	public function register_block_frontend_scripts() {
		$script_url        = SHOPASCLIENT_PLUGIN_URL . 'build/ptwoo-shop-as-client-block-frontend.js';
		$script_asset_path = SHOPASCLIENT_PLUGIN_DIR . 'build/ptwoo-shop-as-client-block-frontend.asset.php';
		$script_asset      = file_exists( $script_asset_path )
			? require $script_asset_path
			: array(
				'dependencies' => array(),
				'version'      => $this->get_file_version( $script_asset_path ),
			);

		wp_register_script(
			'ptwoo-shop-as-client-block-frontend',
			$script_url,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);

		wp_set_script_translations(
			'ptwoo-shop-as-client-block-frontend',
			'shop-as-client'
		);
	}

	/**
	 * Get the file modified time as a cache buster if we're in dev mode.
	 *
	 * @param string $file Local path to the file.
	 * @return string The cache buster value to use for the given file.
	 */
	protected function get_file_version( $file ) {
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG && file_exists( $file ) ) {
			return filemtime( $file );
		}

		return SHOPASCLIENT_VERSION;
	}
}
