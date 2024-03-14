<?php

namespace TotalContestVendors\TotalCore\Contracts\Shortcodes;

/**
 * Shortcodes base class
 * @package TotalContestVendors\TotalCore\Contracts\Shortcodes\Shortcode
 * @since   1.0.0
 */
interface Shortcode {
	/**
	 * Get attribute value.
	 *
	 * @param      $name
	 * @param null $default
	 *
	 * @return mixed|null
	 * @since 1.0.0
	 */
	public function getAttribute( $name, $default = null );

	/**
	 * Get content.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public function getContent();

	/**
	 * Handle shortcode.
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function handle();
}