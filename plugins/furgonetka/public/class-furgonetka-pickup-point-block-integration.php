<?php

/**
 * Furgonetka Pickup Point block integration
 */
class Furgonetka_Pickup_Point_Block_Integration implements \Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface {

	/**
	 * The name of the integration.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'furgonetka-pickup-point';
	}

	/**
	 * When called invokes any initialization/setup for the integration.
	 */
	public function initialize() {
		$script_path = $this->get_block_relative_path( 'index.js' );
		$script_url  = plugins_url( $script_path, $this->get_plugin_absolute_path( 'furgonetka.php' ) );

		$script_asset_path = $this->get_plugin_absolute_path( $this->get_block_relative_path( 'index.asset.php' ) );
		$script_asset      = file_exists( $script_asset_path )
			? require $script_asset_path
			: array(
				'dependencies' => array( 'react' ),
				'version'      => filemtime( $script_path ),
			);

		wp_register_script(
			$this->get_script_handle(),
			$script_url,
			$script_asset['dependencies'],
			$script_asset['version'],
			true
		);

		wp_set_script_translations(
			$this->get_script_handle(),
			FURGONETKA_PLUGIN_NAME,
			$this->get_plugin_absolute_path( 'languages' )
		);
	}

	/**
	 * Returns default script handle to enqueue in the frontend context.
	 *
	 * @return string
	 */
	private function get_script_handle() {
		return $this->get_name() . '-wc-block-integration';
	}

	/**
	 * Returns an array of script handles to enqueue in the frontend context.
	 *
	 * @return string[]
	 */
	public function get_script_handles() {
		return array( $this->get_script_handle() );
	}

	/**
	 * Returns an array of script handles to enqueue in the editor context.
	 *
	 * @return string[]
	 */
	public function get_editor_script_handles() {
		return array();
	}

	/**
	 * An array of key, value pairs of data made available to the block on the client side.
	 *
	 * @return array
	 */
	public function get_script_data() {
		return array(
			'furgonetka_service_by_shipping_rate_id' => get_option( FURGONETKA_PLUGIN_NAME . '_deliveryToType' ) ?: array(),
		);
	}

	/**
	 * Get absolute path to the file based on main plugin directory
	 *
	 * @return string
	 */
	private function get_plugin_absolute_path( $path ) {
		return FURGONETKA_PLUGIN_DIR . '/' . $path;
	}

	/**
	 * Get relative path to the current block directory
	 *
	 * @return string
	 */
	private function get_block_relative_path( $path ) {
		return '/public/blocks/' . $this->get_name() . '/' . $path;
	}
}
