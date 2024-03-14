<?php
/**
 * Compatible theme interface.
 *
 * @package Stax
 */

namespace Stax;

interface CompatibleTheme {
	/**
	 * Front actions to replace theme header.
	 *
	 * @return void
	 */
	public function front_actions();

	/**
	 * Return header tag for theme
	 *
	 * @return string
	 */
	public function get_tag();

	/**
	 * Checks to ensure theme is compatible
	 *
	 * @return boolean
	 */
	public function is_front_compatible();
}
