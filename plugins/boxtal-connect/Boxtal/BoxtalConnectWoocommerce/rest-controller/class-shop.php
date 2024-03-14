<?php
/**
 * Contains code for the shop class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Rest_Controller
 */

namespace Boxtal\BoxtalConnectWoocommerce\Rest_Controller;

use Boxtal\BoxtalConnectWoocommerce\Notice\Notice_Controller;
use Boxtal\BoxtalConnectWoocommerce\Util\Api_Util;
use Boxtal\BoxtalConnectWoocommerce\Util\Auth_Util;
use Boxtal\BoxtalConnectWoocommerce\Util\Configuration_Util;
use Boxtal\BoxtalConnectWoocommerce\Util\Configuration_Report_Util;
use Boxtal\BoxtalConnectWoocommerce\Branding;

/**
 * Shop class.
 *
 * Opens API endpoint to pair.
 */
class Shop {

	/**
	 * Run class.
	 *
	 * @void
	 */
	public function run() {
		add_action(
			'rest_api_init',
			function() {
				register_rest_route(
					Branding::$branding . '-connect/v1',
					'shop/pair',
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'pairing_handler' ),
						'permission_callback' => array( $this, 'authenticate' ),
					)
				);
			}
		);

		add_action(
			'rest_api_init',
			function() {
				register_rest_route(
					Branding::$branding . '-connect/v1',
					'shop/update-configuration',
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'update_configuration_handler' ),
						'permission_callback' => array( $this, 'authenticate_access_key' ),
					)
				);
			}
		);

		add_action(
			'rest_api_init',
			function() {
				register_rest_route(
					Branding::$branding . '-connect/v1',
					'shop/delete-configuration',
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'delete_configuration_handler' ),
						'permission_callback' => array( $this, 'authenticate_access_key' ),
					)
				);
			}
		);

		add_action(
			'rest_api_init',
			function() {
				register_rest_route(
					Branding::$branding . '-connect/v1',
					'shop/get-configuration',
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'get_configuration_handler' ),
						'permission_callback' => array( $this, 'authenticate_access_key' ),
					)
				);
			}
		);
	}

	/**
	 * Call to auth helper class authenticate function.
	 *
	 * @param WP_REST_Request $request request.
	 * @return WP_Error|boolean
	 */
	public function authenticate( $request ) {
		return Auth_Util::authenticate( $request );
	}

	/**
	 * Call to auth helper class authenticate access key function.
	 *
	 * @param WP_REST_Request $request request.
	 * @return WP_Error|boolean
	 */
	public function authenticate_access_key( $request ) {
		return Auth_Util::authenticate_access_key( $request );
	}

	/**
	 * Endpoint callback.
	 *
	 * @param WP_REST_Request $request request.
	 * @void
	 */
	public function pairing_handler( $request ) {
		$body = Auth_Util::decrypt_body( $request->get_body() );

		if ( null === $body ) {
			Api_Util::send_api_response( 400 );
		}

		$access_key   = null;
		$secret_key   = null;
		$callback_url = null;
		$settings_url = admin_url( 'admin.php?page=' . Branding::$branding . '-connect-settings' );
		if ( is_object( $body ) && property_exists( $body, 'accessKey' ) && property_exists( $body, 'secretKey' ) ) {
			$access_key = $body->accessKey;
			$secret_key = $body->secretKey;

			if ( property_exists( $body, 'pairCallbackUrl' ) ) {
				$callback_url = $body->pairCallbackUrl;
			}
		}

		if ( null !== $access_key && null !== $secret_key ) {
			if ( ! Auth_Util::is_plugin_paired() ) { // initial pairing.
				Auth_Util::pair_plugin( $access_key, $secret_key );
				Notice_Controller::remove_notice( Notice_Controller::$setup_wizard );
				Notice_Controller::add_notice( Notice_Controller::$pairing, array( 'result' => 1 ) );
				Api_Util::send_api_response( 200, array( 'pluginConfigurationUrl' => $settings_url ) );
			} else { // pairing update.
				if ( null !== $callback_url ) {
					Auth_Util::pair_plugin( $access_key, $secret_key );
					Notice_Controller::remove_notice( Notice_Controller::$pairing );
					Auth_Util::start_pairing_update( $callback_url );
					Notice_Controller::add_notice( Notice_Controller::$pairing_update );
					Api_Util::send_api_response( 200, array( 'pluginConfigurationUrl' => $settings_url ) );
				} else {
					Api_Util::send_api_response( 403 );
				}
			}
		} else {
			Notice_Controller::add_notice( Notice_Controller::$pairing, array( 'result' => 0 ) );
			Api_Util::send_api_response( 400 );
		}
	}

	/**
	 * Endpoint callback.
	 *
	 * @param \WP_REST_Request $request request.
	 * @void
	 */
	public function delete_configuration_handler( $request ) {
		$body = Auth_Util::decrypt_body( $request->get_body() );

		if ( null === $body ) {
			Api_Util::send_api_response( 400 );
		}

		Configuration_Util::delete_configuration();
		Api_Util::send_api_response( 200 );
	}

	/**
	 * Endpoint callback.
	 *
	 * @param \WP_REST_Request $request request.
	 * @void
	 */
	public function update_configuration_handler( $request ) {
		$body = Auth_Util::decrypt_body( $request->get_body() );

		if ( Configuration_Util::parse_configuration( $body ) ) {
			Api_Util::send_api_response( 200 );
		}

		Api_Util::send_api_response( 400 );
	}

	/**
	 * Endpoint callback.
	 *
	 * @void
	 */
	public function get_configuration_handler() {
		$response = Configuration_Report_Util::get_configuration_report();
		Api_Util::send_api_response( 200, $response );
	}
}
