<?php
/**
 * Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts\Cookie_Integration interface
 *
 * @package WP_GDPR_Cookie_Notice
 * @since 1.0.0
 */

namespace Felix_Arntz\WP_GDPR_Cookie_Notice\Contracts;

/**
 * Interface for a class that controls a cookie or set of cookies.
 *
 * @since 1.0.0
 */
interface Cookie_Integration {

	/**
	 * Gets the cookie integration identifier.
	 *
	 * @since 1.0.0
	 *
	 * @return string Cookie integration identifier.
	 */
	public function get_id() : string;

	/**
	 * Gets the cookie type that the cookies managed by this integration are part of.
	 *
	 * @since 1.0.0
	 *
	 * @return string Cookie type.
	 */
	public function get_type() : string;

	/**
	 * Gets the label to display alongside the checkbox for enabling this integration.
	 *
	 * @since 1.0.0
	 *
	 * @return string Enable checkbox label.
	 */
	public function get_enable_label() : string;

	/**
	 * Checks whether the cookie integration is applicable to the current setup.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if applicable, false otherwise.
	 */
	public function is_applicable() : bool;

	/**
	 * Adds the necessary hooks to integrate.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $allowed Whether cookies for the cookie type are currently allowed. Note that this value
	 *                      is not necessarily reliable since it is cookie-based and thus may be off in setups
	 *                      that leverage page caching. It is recommended to use a JS-only solution.
	 */
	public function add_hooks( bool $allowed );
}
