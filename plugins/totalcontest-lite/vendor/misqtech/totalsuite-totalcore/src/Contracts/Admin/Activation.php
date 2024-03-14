<?php

namespace TotalContestVendors\TotalCore\Contracts\Admin;

use TotalContestVendors\TotalCore\Contracts\Helpers\Arrayable;

/**
 * Activation service class
 * @package TotalContestVendors\TotalCore\Admin
 */
interface Activation extends Arrayable, \JsonSerializable {
	/**
	 * Get license status.
	 *
	 * @return bool
	 */
	public function getLicenseStatus();

	/**
	 * Get license key.
	 *
	 * @return string
	 */
	public function getLicenseKey();

	/**
	 * Get license email.
	 *
	 * @return string
	 */
	public function getLicenseEmail();

	/**
	 * Set license key.
	 *
	 * @param string $licenseKey
	 *
	 * @return bool
	 */
	public function setLicenseKey( $licenseKey );

	/**
	 * Set license email.
	 *
	 * @param string $licenseEmail
	 *
	 * @return bool
	 */
	public function setLicenseEmail( $licenseEmail );

	/**
	 * Set license status.
	 *
	 * @param $licenseStatus
	 *
	 * @return bool
	 */
	public function setLicenseStatus( $licenseStatus );

	/**
	 * Check license key validity.
	 *
	 * @param string $licenseKey
	 * @param string $licenseEmail
	 *
	 * @return bool
	 */
	public function checkLicenseValidity( $licenseKey, $licenseEmail );

	/**
	 * Reactivate license.
	 *
	 * @return bool
	 */
	public function reactivateLicense();

}