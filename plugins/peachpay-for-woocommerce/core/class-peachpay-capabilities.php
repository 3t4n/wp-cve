<?php
/**
 * Utility class for managing the PeachPay plugin capabilities.
 *
 * @package PeachPay
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

require_once PEACHPAY_ABSPATH . 'core/traits/trait-peachpay-singleton.php';

/**
 * Utility class for PeachPay plugin capabilities.
 */
final class PeachPay_Capabilities {

	use PeachPay_Singleton;

	/**
	 * The plugin capabilities data.
	 *
	 * @var array|null
	 */
	private $capabilities_data = null;

	/**
	 * Constructs the capabilities utility class.
	 */
	public function __construct() {
		$this->capabilities_data = get_option( 'peachpay_plugin_capabilities', null );

		if ( null === $this->capabilities_data ) {
			$this->set( self::fetch() );
		}
	}

	/**
	 * Checks if the capability is connected for the plugin to use.
	 *
	 * @param string $capability_key The capability key to check if it is connected.
	 */
	public static function connected( $capability_key ) {
		$plugin_capabilities = self::instance()->capabilities_data;

		if ( ! is_array( $plugin_capabilities ) ) {
			return false;
		}

		if ( ! isset( $plugin_capabilities[ $capability_key ] ) ) {
			return false;
		}

		$capability_data = $plugin_capabilities[ $capability_key ];

		if ( ! isset( $capability_data['connected'] ) ) {
			return false;
		}

		return filter_var( $capability_data['connected'], FILTER_VALIDATE_BOOLEAN );
	}

	/**
	 * Checks if the current merchant has the given capability.
	 *
	 * @param string $capability_key The capability key to check if it exists.
	 * @param string $data_type The data type to check for.
	 *
	 * @return bool True if the capability exists, false otherwise.
	 */
	public static function has( $capability_key, $data_type = 'config' ) {
		$plugin_capabilities = self::instance()->capabilities_data;

		if ( ! is_array( $plugin_capabilities ) ) {
			return false;
		}

		if ( ! isset( $plugin_capabilities[ $capability_key ] ) ) {
			return false;
		}

		$capability_data = $plugin_capabilities[ $capability_key ];

		if ( 'config' === $data_type ) {
			if ( isset( $capability_data['config'] ) && is_array( $capability_data['config'] ) ) {
				return true;
			}
		} elseif ( 'account' === $data_type ) {
			if ( isset( $capability_data['account'] ) && is_array( $capability_data['account'] ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Gets the data for a given capability.
	 *
	 * @param string $capability_key The capability key to get the data for.
	 * @param string $data_type The data type to get.
	 *
	 * @return array|null The capability data or null if it does not exist.
	 */
	public static function get( $capability_key, $data_type = 'config' ) {
		$plugin_capabilities = self::instance()->capabilities_data;

		if ( ! is_array( $plugin_capabilities ) ) {
			return null;
		}

		if ( ! isset( $plugin_capabilities[ $capability_key ] ) ) {
			return null;
		}

		$capability_data = $plugin_capabilities[ $capability_key ];

		if ( 'config' === $data_type ) {
			if ( isset( $capability_data['config'] ) && is_array( $capability_data['config'] ) ) {
				return $capability_data['config'];
			}
		} elseif ( 'account' === $data_type ) {
			if ( isset( $capability_data['account'] ) && is_array( $capability_data['account'] ) ) {
				return $capability_data['account'];
			}
		}

		return null;
	}

	/**
	 * Refreshes the plugin capabilities for the current merchant.
	 *
	 * @return void
	 */
	public static function refresh() {
		self::instance()->set( self::fetch() );
	}

	/**
	 * Saves updated plugin capabilities and caches the data for later.
	 *
	 * @param array $capabilities_data The capabilities data.
	 *
	 * @return void
	 */
	private function set( $capabilities_data ) {
		$this->capabilities_data = $capabilities_data;

		update_option( 'peachpay_plugin_capabilities', $capabilities_data );

		do_action( 'peachpay_plugin_capabilities_updated' );
	}

	/**
	 * Fetches the plugin capabilities for the current merchant from the PeachPay API.
	 *
	 * @return array The plugin capabilities.
	 */
	private static function fetch() {
		$response = wp_remote_post(
			peachpay_api_url( 'detect', true ) . 'api/v1/plugin/capabilities',
			array(
				'body'     => array(
					'domain'      => home_url(),
					'merchant_id' => peachpay_plugin_merchant_id(),
				),
				'blocking' => true,
			)
		);

		if ( ! peachpay_response_ok( $response ) ) {
			$code = wp_remote_retrieve_response_code( $response );
			if ( 404 === $code ) {
				delete_option( 'peachpay_merchant_id' );
			}
			return array();
		}

		$data = json_decode( wp_remote_retrieve_body( $response ), true )['data'];

		update_option( 'peachpay_merchant_id', $data['merchant_id'] );

		return $data;
	}
}

return PeachPay_Capabilities::instance();
