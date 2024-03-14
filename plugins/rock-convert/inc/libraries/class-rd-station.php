<?php
/**
 * The RD Station class.
 *
 * @package    Rock_Convert\Inc\libraries
 * @link       https://rockcontent.com
 * @since      1.0.0
 *
 * @author     Rock Content
 */

namespace Rock_Convert\inc\libraries;

/**
 * Class RD_Station
 *
 * @package Rock_Convert\inc\libraries
 * @since   2.0.0
 */
class RD_Station {

	/**
	 * Public API token for RD Station
	 *
	 * @since 2.0.0
	 *
	 * @var null
	 */
	public $token;

	/**
	 * Base API URL
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	public $base_url = 'https://www.rdstation.com.br/api/';

	/**
	 * Default identifier for a new lead
	 *
	 * @since 2.0.0
	 *
	 * @var string
	 */
	public $default_identifier = 'rock-convert';

	/**
	 * RD_Station constructor.
	 *
	 * @since 2.0.0
	 *
	 * @param string $token Integration token.
	 */
	public function __construct( $token = null ) {
		$this->token = $token;
	}

	/**
	 * Send a new lead to RD Station
	 *
	 * @param string $email Subscriber emaeil.
	 * @param array  $data Subscriber data.
	 *
	 * @since 2.0.0
	 *
	 * @return array|\WP_Error
	 * @throws \Exception Thros an exception if fails.
	 */
	public function new_lead( $email, $data = array() ) {
		if ( empty( $this->token ) ) {
			throw new \Exception( 'Token is required to connect with RD Station' );
		}

		if ( empty( $email ) ) {
			throw new \Exception( 'Email is required to send a lead to RD Station' );
		}

		if ( empty( $data['identificador'] ) ) {
			$data['identificador'] = $this->default_identifier;
		}
		if ( empty( $data['client_id'] ) && ! empty( $_COOKIE['rdtrk'] ) ) {
			$data['client_id'] = json_decode( sanitize_text_field( wp_unslash( $_COOKIE['rdtrk'] ) ) )->{'id'};
		}
		if ( empty( $data['traffic_source'] ) && ! empty( $_COOKIE['__trf_src'] ) ) {
			$data['traffic_source'] = sanitize_text_field( wp_unslash( $_COOKIE['__trf_src'] ) );
		}

		$data['email'] = $email;

		return $this->request( $this->get_url(), $data );
	}

	/**
	 * Make a POST application/json request to an endpoint
	 *
	 * @param string $url RDS URL.
	 * @param array  $data Data to transfer.
	 *
	 * @return array|\WP_Error
	 */
	private function request( $url, $data = array() ) {
		$data['token_rdstation'] = $this->token;

		return wp_remote_post(
			$url,
			array(
				'headers' => array( 'Content-Type' => 'application/json; charset=utf-8' ),
				'body'    => wp_json_encode( $data ),
				'method'  => 'POST',
			)
		);
	}

	/**
	 * Get URL for registering a new lead on RD Station
	 *
	 * @param string $api_version Api version.
	 *
	 * @since 2.0.0
	 * @see   https://www.rdstation.com.br/api/1.3/conversions
	 * @return string
	 */
	protected function get_url( $api_version = '1.3' ) {
		return esc_url( $this->base_url . $api_version . '/conversions' );
	}
}
