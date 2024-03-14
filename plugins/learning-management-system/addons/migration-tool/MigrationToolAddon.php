<?php
/**
 * Migration Tool Addon for Masteriyo.
 *
 * @since 1.8.0
 */

namespace Masteriyo\Addons\MigrationTool;

use Masteriyo\Addons\MigrationTool\Controllers\LMSMigrationController;

/**
 * Migration Tool Addon main class for Masteriyo.
 *
 * @since 1.8.0
 */
class MigrationToolAddon {

	/**
	 * Initialize.
	 *
	 * @since 1.8.0
	 */
	public function init() {
		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.8.0
	 */
	public function init_hooks() {
		add_filter( 'masteriyo_rest_api_get_rest_namespaces', array( $this, 'register_rest_namespaces' ) );
	}

	/**
	 * Register REST API namespaces for the migration tool.
	 *
	 * @since 1.8.0
	 *
	 * @param array $namespaces Rest namespaces.
	 *
	 * @return array Modified REST namespaces including migration tool endpoints.
	 */
	public function register_rest_namespaces( $namespaces ) {
		$namespaces['masteriyo/v1']['migration-tool'] = LMSMigrationController::class;
		return $namespaces;
	}
}
