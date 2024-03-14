<?php
/**
 * Contains code for branding class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Util
 */

namespace Boxtal\BoxtalConnectWoocommerce;

/**
 * Branding class.
 *
 * Helper to manage API responses.
 */
class Branding {

	/**
	 * Branding id
	 *
	 * @var string
	 */
	public static $branding = 'boxtal';

	/**
	 * Branding short id
	 *
	 * @var string
	 */
	public static $branding_short = 'bw';

	/**
	 * Company name displayed
	 *
	 * @var string
	 */
	public static $company_name = 'Boxtal';

	/**
	 * Plugin version
	 *
	 * @var string
	 */
	public static $plugin_version = '1.2.22';

	/**
	 * Minimum woocommerce version
	 *
	 * @var string
	 */
	public static $min_wc_version = '2.6.14';

	/**
	 * Munimum PHP version
	 *
	 * @var string
	 */
	public static $min_php_version = '5.6.0';

	/**
	 * Onboarding url
	 *
	 * @var string
	 */
	public static $onboarding_url = 'https://www.boxtal.com/onboarding?brandingDev=boxtal';
}

