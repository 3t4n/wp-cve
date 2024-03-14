<?php

namespace TotalContestVendors\TotalCore\Contracts\Modules;

/**
 * Class Module
 * @package TotalContestVendors\TotalCore\Contracts\Modules
 */
interface Module {
	/**
	 * On activation hook.
	 */
	public static function onActivate();

	/**
	 * On deactivation hook.
	 */
	public static function onDeactivate();

	/**
	 * On uninstall hook.
	 */
	public static function onUninstall();

	/**
	 * Get URL.
	 *
	 * @since 1.0.0
	 *
	 * @param string $relativePath relative path.
	 *
	 * @return bool true on success, false on failure.
	 */
	public function getUrl( $relativePath = '' );

	/**
	 * Get path.
	 *
	 * @since 1.0.0
	 *
	 * @param string $relativePath relative path.
	 *
	 * @return bool true on success, false on failure.
	 */
	public function getPath( $relativePath = '' );

	/**
	 * Load text domain.
	 *
	 * @since 1.0.0
	 * @return bool true on success, false on failure.
	 */
	public function loadTextdomain();

	/**
	 * Get option.
	 *
	 * @param      $needle
	 * @param null $default
	 *
	 * @return mixed|null
	 */
	public function getOption( $needle, $default = null );

	/**
	 * Get options.
	 *
	 * @return array
	 */
	public function getOptions();
}