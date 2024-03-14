<?php

/**
 * Contains the main class for the plugin.
 *
 * @package StockSyncWithGoogleSheetForWooCommerce
 * @since 1.0.0
 */

// Namespace.
namespace StockSyncWithGoogleSheetForWooCommerce;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit();

if ( ! class_exists( '\StockSyncWithGoogleSheetForWooCommerce\App' ) ) {
	/**
	 * Contains the main class for the plugin.
	 *
	 * @package StockSyncWithGoogleSheetForWooCommerce
	 * @since 1.0.0
	 */
	class App {
		// Utilities Trait to use in all classes globally.
		use Utilities;

		/**
		 * WooCommerce plugin file name
		 *
		 * @var string
		 */
		public $woo_commerce = 'woocommerce/woocommerce.php';

		/**
		 * Ultimate plugin file name
		 *
		 * @var string
		 */
		public $ultimate = 'stock-sync-with-google-sheet-for-woocommerce-ultimate/stock-sync-with-google-sheet-for-woocommerce-ultimate.php';

		/**
		 * Default options
		 *
		 * @return array Default options
		 */
		public function get_default_options() {
			$options = [
				'credentials'             => [],
				'show_custom_fileds'      => [],
				'credential_file'         => '',
				'spreadsheet_url'         => '',
				'spreadsheet_id'          => '',

				'sheet_tab'               => 'Sheet1',
				'sheet_id'                => '',

				'setup_step'              => 0,

				'add_products_from_sheet' => false,
				'freeze_headers'          => false,

				'show_sku'                => false,
				'show_short_description'  => false,
				'show_product_category'   => false,
				'show_total_sales'        => false,
				'show_attributes'         => false,
				'show_custom_meta_fileds' => false,
				'bulk_edit_option'        => true,
				'token'                   => '',
				'apps_script__notice'     => 0,
				'show_product_images'     => false,
				'save_and_sync'           => false,
			];

			return apply_filters( 'ssgsw_options', $options );
		}

		/**
		 * Get saved options.
		 *
		 * @return object Saved options
		 */
		public function get_options() {
			$ssgsw_options = [];
			foreach ( $this->get_default_options() as $key => $value ) {
				$ssgsw_options[ $key ] = ssgsw_get_option( $key );
			}

			$ssgsw_options = (object) $ssgsw_options;
			return $ssgsw_options;
		}

		/**
		 * Checks if WooCommerce is installed.
		 *
		 * @return bool
		 */
		public function is_woocommerce_installed() {
			// Check if WooCommerce is installed in plugin folder.
			if ( file_exists( WP_PLUGIN_DIR . '/' . $this->woo_commerce ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Checks if WooCommerce is activated.
		 *
		 * @return bool
		 */
		public function is_woocommerce_activated() {
			if (
				in_array(
					$this->woo_commerce,
					apply_filters(
						'active_plugins',
						get_option( 'active_plugins' ),
						false
					)
				)
			) {
				return true;
			}

			return false;
		}

		/**
		 * Is Ultimate version installed
		 */
		public function is_ultimate_installed() {
			if ( file_exists( WP_PLUGIN_DIR . '/' . $this->ultimate ) ) {
				return true;
			}

			return false;
		}

		/**
		 * Is Ultimate activated?
		 */
		public function is_ultimate_activated() {
			if (
				$this->is_ultimate_installed() &&
				in_array(
					$this->ultimate,
					apply_filters(
						'active_plugins',
						get_option( 'active_plugins' )
					)
				)
			) {
				return true;
			}

			return false;
		}

		/**
		 * Check if license is activated and valid.
		 *
		 * @return bool
		 */
		public function is_license_valid() {
			global $ssgsw_license;

			if ( ! $ssgsw_license ) {
				return false;
			}

			return $ssgsw_license->is_valid();
		}

		/**
		 * Check if setup is completed.
		 *
		 * @return bool
		 */
		public function is_setup_complete() {
			$options = $this->get_options();
			return $options->setup_step >= 5;
		}

		/**
		 * Check if plugin is ready to use.
		 *
		 * @return bool|\Exception
		 */
		public function is_plugin_ready() {
			$options = $this->get_options();

			if ( ! $options ) {
				return new \Exception(
					__(
						'Setup not complete yet',
						'stock-sync-with-google-sheet-for-woocommerce'
					)
				);
			}

			if ( empty( $options->credentials ) ) {
				return new \Exception(
					__(
						'Credentials not set',
						'stock-sync-with-google-sheet-for-woocommerce'
					)
				);
			}

			if ( empty( $options->spreadsheet_url ) ) {
				return new \Exception(
					__(
						'Spreadsheet URL not set',
						'stock-sync-with-google-sheet-for-woocommerce'
					)
				);
			}

			if ( empty( $options->sheet_tab ) ) {
				return new \Exception(
					__(
						'Sheet tab not set',
						'stock-sync-with-google-sheet-for-woocommerce'
					)
				);
			}

			if ( ! $this->is_woocommerce_activated() ) {
				return new \Exception(
					__(
						'WooCommerce not activated',
						'stock-sync-with-google-sheet-for-woocommerce'
					)
				);
			}

			return true;
		}

		/**
		 * Returns localized script.
		 *
		 * @return array Localized script
		 */
		public function localized_script() {
			$script_file =
				plugin_dir_path( SSGSW_FILE ) . '/public/js/AppsScript.js';
			$script      = '';

			if ( file_exists( $script_file ) ) {
				$script = file_get_contents( $script_file ); // phpcs:ignore
			}

			$keys = [
				'ajax_url'                 => admin_url( 'admin-ajax.php' ),
				'site_url'                 => site_url(),
				'nonce'                    => wp_create_nonce( 'ssgsw_nonce' ),
				'is_license_valid'         => $this->is_license_valid(),
				'is_ultimate_installed'    => $this->is_ultimate_installed(),
				'is_woocommerce_activated' => $this->is_woocommerce_activated(),
				'is_woocommerce_installed' => $this->is_woocommerce_installed(),
				'is_setup_complete'        => $this->is_setup_complete(),
				'is_plugin_ready'          => true === wp_validate_boolean( $this->is_plugin_ready() ),
				'options'                  => $this->get_options(),
				'public_url'               => SSGSW_PUBLIC,
				'apps_script'              => $script,
				'currentScreen'            => get_current_screen(),
				'limit'                    => apply_filters( 'ssgsw_product_limit', 100 ),
				'is_debug'                 => defined( 'WP_DEBUG' ) && WP_DEBUG,
			];

			return apply_filters( 'ssgsw_localized_script', $keys );
		}

		/**
		 * Reset options.
		 *
		 * @param bool $force Force reset.
		 * @return bool
		 */
		public function reset_options( $force = false ) {
			$options = $this->get_default_options();

			foreach ( $options as $key => $value ) {
				if ( $force ) {
					delete_option( SSGSW_PREFIX . $key );
				}

				add_option( SSGSW_PREFIX . $key, $value );
			}
			return true;
		}

		/**
		 * Returns all product columns.
		 *
		 * @return array
		 */
		public function get_product_columns() {
			$columns = [
				'ID'            => [
					'label'       => __(
						'ID',
						'stock-sync-with-google-sheet-for-woocommerce'
					),
					'type'        => 'number',
					'description' => __(
						'Product ID',
						'stock-sync-with-google-sheet-for-woocommerce'
					),
					'required'    => true,
				],
				'post_type'     => 'Type',
				'post_title'    => 'Name',
				'stock_status'  => 'Stock Status',
				'post_excerpt'  => 'Short description',
				'regular_price' => 'Regular Price',
				'sale_price'    => 'Sale Price',
				'category'      => 'Category',
				'sales'         => 'Sales',
			];

			return apply_filters( 'ssgsw_product_columns', $columns );
		}

		/**
		 * Activates WooCommerce.
		 *
		 * @return mixed
		 */
		public function activate_woocommerce() {
			if ( ! defined( 'WP_ADMIN ') ) {
				define( 'WP_ADMIN', true );
			}
			if ( ! defined( 'WP_NETWORK_ADMIN' ) ) {
				define( 'WP_NETWORK_ADMIN', true );
			}
			if ( ! defined( 'WP_USER_ADMIN' ) ) {
				define( 'WP_USER_ADMIN', true );
			}

			$woocommerce =
				ABSPATH . 'wp-content/plugins/woocommerce/woocommerce.php';

			if ( file_exists( $woocommerce ) ) {
				require_once ABSPATH . 'wp-load.php';
				require_once ABSPATH . 'wp-admin/includes/admin.php';
				require_once ABSPATH . 'wp-admin/includes/upgrade.php';
				require_once ABSPATH . 'wp-admin/includes/plugin.php';

				activate_plugin( $woocommerce );
			} else {
				require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
				require_once ABSPATH .
					'wp-admin/includes/class-wp-upgrader.php';
				require_once ABSPATH .
					'wp-admin/includes/class-wp-ajax-upgrader-skin.php';
				require_once ABSPATH .
					'wp-admin/includes/class-plugin-upgrader.php';

				// Get Plugin Info.
				$api      = plugins_api( 'plugin_information', [
					'slug'   => 'woocommerce',
					'fields' => [
						'short_description' => false,
						'sections'          => false,
						'requires'          => false,
						'rating'            => false,
						'ratings'           => false,
						'downloaded'        => false,
						'last_updated'      => false,
						'added'             => false,
						'tags'              => false,
						'compatibility'     => false,
						'homepage'          => false,
						'donate_link'       => false,
					],
				] );
				$skin     = new \WP_Ajax_Upgrader_Skin();
				$upgrader = new \Plugin_Upgrader( $skin );

				if ( is_wp_error( $api ) ) {
					return;
				}

				$upgrader->install( $api->download_link ); // phpcs:ignore

				activate_plugin( $woocommerce );
			}
		}
	}
}
