<?php

namespace TotalContestVendors\TotalCore\Admin;

use TotalContestVendors\TotalCore\Contracts\Admin\Account as AccountContract;
use TotalContestVendors\TotalCore\Contracts\Foundation\Environment as EnvironmentContract;
use TotalContestVendors\TotalCore\Helpers\Strings;

/**
 * Account service class
 * @package TotalContestVendors\TotalCore\Admin
 */
class Account implements AccountContract {
	/**
	 * @var EnvironmentContract
	 */
	protected $env;

	/**
	 * Account service constructor.
	 *
	 * @param EnvironmentContract $env
	 */
	public function __construct( EnvironmentContract $env ) {
		$this->env = $env;
	}

	/**
	 * @param array $account
	 *
	 * @return bool
	 */
	public function set( $account ) {
		return update_option( $this->env['prefix'] . 'account', $account );
	}

	/**
	 * Get account.
	 *
	 * @return array|false
	 */
	public function get() {
		return get_option( $this->env['prefix'] . 'account', false );
	}

	/**
	 * Get account status.
	 *
	 * @return bool
	 */
	public function isLinked() {
		$account = $this->get();

		return ! empty( $account );
	}

	/**
	 * Get account key.
	 *
	 * @return string
	 */
	public function getAccessToken() {
		$account = $this->get();

		if ( ! empty( $account['token'] ) ):
			return $account['token'];
		endif;

		return null;
	}

	/**
	 * Get account email.
	 *
	 * @return string
	 */
	public function getEmail() {
		$account = $this->get();

		if ( ! empty( $account['email'] ) ):
			return $account['email'];
		endif;

		return null;
	}

	/**
	 * @param string $accessToken
	 *
	 * @return array|\WP_Error
	 */
	public function checkAccessToken( $accessToken ) {
		$fields = [
			'access_token' => $accessToken,
			'domain'       => $this->env['domain'],
			'version'      => $this->env['version'],
		];

		$apiEndpoint = Strings::template( $this->env['api.check-access-token'], $fields );
		$apiRequest  = add_query_arg( $fields, $apiEndpoint );
		$apiResponse = json_decode( wp_remote_retrieve_body( wp_remote_post( $apiRequest, [ 'body' => $fields, 'sslverify' => true ] ) ), true );

		if ( empty( $apiResponse['success'] ) ):
			if ( empty( $apiResponse['message'] ) ):
				return new \WP_Error( 'invalid_access_token', __( 'Access token is invalid. Please double check and try again.', $this->env['slug'] ) );
			else:
				return new \WP_Error( 'invalid_access_token', esc_html( $apiResponse['message'] ) );
			endif;
		endif;

		return $apiResponse['data'];
	}

	/**
	 * Get the instance for json.
	 *
	 * @return array
	 */
    #[\ReturnTypeWillChange]
	public function jsonSerialize() {
		return $this->toArray();
	}

	/**
	 * Get the instance as an array.
	 *
	 * @return array
	 */
	public function toArray() {
		return [
			'status'       => $this->isLinked(),
			'access_token' => $this->getAccessToken(),
			'email'        => $this->getEmail(),
		];
	}
}
