<?php

namespace TotalContestVendors\TotalCore\Admin;

use TotalContestVendors\TotalCore\Contracts\Admin\Activation as ActivationContract;
use TotalContestVendors\TotalCore\Contracts\Foundation\Environment as EnvironmentContract;
use TotalContestVendors\TotalCore\Helpers\Strings;

/**
 * Activation service class
 * @package TotalContestVendors\TotalCore\Admin
 */
class Activation implements ActivationContract {
	/**
	 * @var EnvironmentContract
	 */
	protected $env;

	/**
	 * Activation service constructor.
	 *
	 * @param EnvironmentContract $env
	 */
	public function __construct( EnvironmentContract $env ) {
		$this->env = $env;
	}

	/**
	 * Set license key.
	 *
	 * @param string $licenseKey
	 *
	 * @return bool
	 */
	public function setLicenseKey( $licenseKey ) {
		return update_option( $this->env['prefix'] . 'license_key', $licenseKey );
	}

	/**
	 * Set license status.
	 *
	 * @param $licenseStatus
	 *
	 * @return bool
	 */
	public function setLicenseStatus( $licenseStatus ) {
		return update_option( $this->env['prefix'] . 'license_status', $licenseStatus );
	}

	/**
	 * Set license email.
	 *
	 * @param string $licenseEmail
	 *
	 * @return bool
	 */
	public function setLicenseEmail( $licenseEmail ) {
		return update_option( $this->env['prefix'] . 'license_email', $licenseEmail );
	}

	/**
	 * Check license key validity.
	 *
	 * @param string $licenseKey
	 * @param string $licenseEmail
	 *
	 * @return bool|\WP_Error
	 */
	public function checkLicenseValidity( $licenseKey, $licenseEmail ) {
		$fields = [
			'license' => $licenseKey,
			'email'   => $licenseEmail,
			'domain'  => $this->env['domain'],
			'version' => $this->env['version'],
			'env'     => $this->env['versions'],
		];

		$apiEndpoint = Strings::template( $this->env['api.activation'], $fields );
		$apiRequest  = add_query_arg( $fields, $apiEndpoint );
		$apiResponse = json_decode( wp_remote_retrieve_body( wp_remote_post( $apiRequest, [ 'body' => $fields, 'sslverify' => true ] ) ), true );

		if ( empty( $apiResponse['success'] ) ):
			if ( empty( $apiResponse['message'] ) ):
				return new \WP_Error( 'invalid_license', __( 'License key is invalid. Please double check and try again.', $this->env['slug'] ) );
			else:
				return new \WP_Error( 'invalid_license', esc_html( $apiResponse['message'] ) );
			endif;
		endif;

		return true;
	}

	/**
	 * Get license status.
	 *
	 * @return bool
	 */
	public function getLicenseStatus() {
		return (bool) ( $this->getLicenseKey() && get_option( $this->env['prefix'] . 'license_status', false ) );
	}

	/**
	 * Get license key.
	 *
	 * @return string
	 */
	public function getLicenseKey() {
		return get_option( $this->env['prefix'] . 'license_key', false );
	}

	/**
	 * Get license email.
	 *
	 * @return string
	 */
	public function getLicenseEmail() {
		return get_option( $this->env['prefix'] . 'license_email', false );
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
			'status' => $this->getLicenseStatus(),
			'key'    => $this->getLicenseKey(),
			'email'  => $this->getLicenseEmail(),
		];
	}

	/**
	 * Reactivate license.
	 *
	 * @return bool
	 */
	public function reactivateLicense() {
		$licenseKey   = $this->getLicenseKey();
		$licenseEmail = $this->getLicenseEmail();

		if ( ! empty( $licenseKey ) && ! empty( $licenseEmail ) ):
			return ! is_wp_error( $this->checkLicenseValidity( $licenseKey, $licenseEmail ) );
		endif;

		return false;
	}
}
