<?php

namespace TotalContestVendors\TotalCore\Contracts\Foundation;

use TotalContestVendors\TotalCore\Application;

/**
 * Interface Plugin
 * @package TotalContestVendors\TotalCore\Contracts\Foundation
 */
interface Plugin {
	/**
	 * On uninstall.
	 *
	 * @return void
	 */
	public static function onUninstall();

	/**
	 * Register providers.
	 *
	 * @return void
	 */
	public function registerProviders();

	/**
	 * Register widgets.
	 *
	 * @return void
	 */
	public function registerWidgets();

	/**
	 * Register shortcodes.
	 *
	 * @return void
	 */
	public function registerShortCodes();

	/**
	 * Register CPTs.
	 *
	 * @return void
	 */
	public function registerCustomPostTypes();

	/**
	 * Register taxonomies.
	 *
	 * @return void
	 */
	public function registerTaxonomies();

	/**
	 * Load text domain.
	 *
	 * @return void
	 */
	public function loadTextDomain();

	/**
	 * On activation.
	 *
	 * @param $networkWide
	 *
	 * @return void
	 */
	public function onActivation( $networkWide );

	/**
	 * On deactivation.
	 *
	 * @param $networkWide
	 *
	 * @return void
	 */
	public function onDeactivation( $networkWide );

	/**
	 * Bootstrap plugin.
	 *
	 * @return void
	 */
	public function bootstrap();

	/**
	 * Bootstrap admin.
	 *
	 * @return void
	 */
	public function bootstrapAdmin();

	/**
	 * Bootstrap AJAX.
	 *
	 * @return void
	 */
	public function bootstrapAjax();

	/**
	 * Bootstrap extensions.
	 *
	 * @return mixed
	 */
	public function bootstrapExtensions();

	/**
	 * Set plugin application.
	 *
	 * @param Application $application
	 *
	 * @return void
	 */
	public function setApplication( Application $application );

	/**
	 * Get plugin application.
	 *
	 * @return Application
	 */
	public function getApplication();
}
