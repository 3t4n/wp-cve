<?php

namespace WpifyWoo;

use Puc_v4_Factory;
use WpifyWoo\Abstracts\AbstractModule;
use WpifyWooDeps\Wpify\Core\Abstracts\AbstractComponent;

/**
 * Class License
 * @package WpifyWoo
 * @property Plugin $plugin
 */
class License extends AbstractComponent {
	const API_KEY = 'ck_b543732d2aa924962757690d0d929c043c3f37c1';
	const API_SECRET = 'cs_5d3605fd909d8e6c1aed7ad19ee0c569ca50d32a';

	/**
	 * Activate the license
	 *
	 * @param $license
	 * @param $data
	 *
	 * @return \WP_Error
	 */
	public function activate_license( $license, $data ) {
		$response = wp_remote_get(
			add_query_arg( $data, $this->get_activation_url() . $license ),
			$this->get_request_args()
		);

		$result = json_decode( wp_remote_retrieve_body( $response ) );
		$code   = wp_remote_retrieve_response_code( $response );

		if ( 200 !== $code ) {
			return new \WP_Error( $code, $result->message );
		}

		$module = $this->plugin->get_modules_manager()->get_module_by_id( $data['module_id'] );

		if ( $module ) {
			/** @var AbstractModule $module */
			$module->save_option_activated( $result->data->crypted_message );
			$module->save_option_public_key( $result->data->public_key );
			$module->save_option_license( $license );
		}

		return $result;
	}

	/**
	 * Get Activation URL
	 * @return string
	 */
	public function get_activation_url(): string {
		return $this->get_license_url() . 'activate/';
	}

	/**
	 * Get License API URL
	 * @return string
	 */
	public function get_license_url(): string {
		return $this->get_base_url() . 'licenses/';
	}

	/**
	 * Get Base URL
	 * @return string
	 */
	public function get_base_url(): string {
		return 'https://wpify.io/wp-json/lmfwc/v2/';
	}

	/**
	 * Get request args
	 * @return array
	 */
	public function get_request_args(): array {
		return array(
			'headers'   => array(
				'Authorization' => 'Basic ' . base64_encode( $this::API_KEY . ':' . $this::API_SECRET ),
			),
			'timeout'   => 30,
			'sslverify' => false,
		);
	}

	/**
	 * Deactivate the license
	 *
	 * @param $license
	 * @param $data
	 *
	 * @return \WP_Error
	 */
	public function deactivate_license( $license, $data ) {
		$response = wp_remote_get(
			add_query_arg( $data, $this->get_deactivation_url() . $license ),
			$this->get_request_args()
		);

		$result = json_decode( wp_remote_retrieve_body( $response ) );
		$code   = wp_remote_retrieve_response_code( $response );
		$module = $this->plugin->get_modules_manager()->get_module_by_id( $data['module_id'] );

		if ( $module ) {
			/** @var AbstractModule $module */
			$module->delete_option_activated();
			$module->delete_option_public_key();
		}

		if ( 200 !== $code ) {
			return new \WP_Error( $code, $result->message );
		}

		return $result;
	}

	/**
	 * Get Deactivation URL
	 * @return string
	 */
	public function get_deactivation_url(): string {
		return $this->get_license_url() . 'deactivate/';
	}

	/**
	 * Validate license
	 *
	 * @param $license
	 * @param $data
	 */
	public function validate_license( $license, $data ) {
		$response = wp_remote_get(
			add_query_arg( $data, $this->get_license_url() . $license ),
			$this->get_request_args()
		);

		if ( is_wp_error( $response ) ) {
			// Don't do anything, the request failed for some reason
			return;
		}

		$code = wp_remote_retrieve_response_code( $response );

		$this->plugin->get_logger()->info( 'Revalidated license',
			[
				'data' =>
					[
						'license'       => $license,
						'data'          => $data,
						'response_code' => $code,
						'response_body' => wp_remote_retrieve_body( $response ),
					],
			]
		);

		if ( 200 !== $code ) {
			// If we don't get response 200, the license is not valid!
			$module = $this->plugin->get_modules_manager()->get_module_by_id( $data['module_id'] );
			if ( $module ) {
				/** Abstract Module @var AbstractModule $module */
				$module->delete_option_activated();
				$module->delete_option_public_key();
			}
		}
	}

	public function init_updates_check( $plugin_slug, $plugin_file, $extra_data = [] ) {
		$url = sprintf( 'https://wpify.io/?update_action=get_metadata&update_slug=%s&site_url=%s', $plugin_slug, site_url() );
		$url = add_query_arg( $extra_data, $url );

		Puc_v4_Factory::buildUpdateChecker(
			$url,
			$plugin_file,
			$plugin_slug
		);
	}
}
