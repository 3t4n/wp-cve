<?php
/**
 * Cart & Checkout Blocks integration class.
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH\ProductBundles
 */

use Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface;

defined( 'YITH_WCPB' ) || exit;

if ( ! class_exists( 'YITH_WCPB_Cart_Checkout_Blocks_Integration' ) ) {
	/**
	 * Cart & Checkout Blocks integration class.
	 *
	 * @since 1.29.0
	 */
	class YITH_WCPB_Cart_Checkout_Blocks_Integration implements IntegrationInterface {

		/**
		 * The name of the integration.
		 *
		 * @return string
		 */
		public function get_name() {
			return 'yith-wcpb-cart-checkout-blocks';
		}

		/**
		 * Get scripts to be loaded.
		 *
		 * @return string[]
		 */
		private function get_scripts(): array {
			return array( 'cart-checkout-blocks' );
		}

		/**
		 * Get the script handle by name.
		 *
		 * @param string $name The script name.
		 *
		 * @return string
		 */
		private function get_script_handle( string $name ): string {
			return 'yith-wcpb-' . $name;
		}

		/**
		 * When called invokes any initialization/setup for the integration.
		 */
		public function initialize() {
			foreach ( $this->get_scripts() as $script_name ) {
				$url          = YITH_WCPB_DIST_URL . '/' . $script_name . '/index.js';
				$asset_path   = YITH_WCPB_DIST_PATH . '/' . $script_name . '/index.asset.php';
				$script_asset = file_exists( $asset_path ) ? require $asset_path : null;

				if ( $script_asset ) {
					wp_register_script(
						$this->get_script_handle( $script_name ),
						$url,
						$script_asset['dependencies'],
						$script_asset['version'],
						true
					);
				}
			}
		}

		/**
		 * Get script handles.
		 *
		 * @return array
		 */
		public function get_script_handles() {
			return array_map( array( $this, 'get_script_handle' ), $this->get_scripts() );
		}

		/**
		 * Get editor script handles.
		 *
		 * @return array
		 */
		public function get_editor_script_handles() {
			return array();
		}

		/**
		 * Get script data.
		 *
		 * @return array
		 */
		public function get_script_data() {
			return array();
		}
	}
}
