<?php
/**
 * Migration Tool service provider.
 *
 * @since 1.8.0
 */

namespace Masteriyo\Addons\MigrationTool\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Masteriyo\Addons\MigrationTool\Controllers\LMSMigrationController;
use Masteriyo\Addons\MigrationTool\MigrationToolAddon;

/**
 * Migration Tool service provider.
 *
 * @since 1.8.0
 */
class MigrationToolServiceProvider extends AbstractServiceProvider {
	/**
	 * The provided array is a way to let the container
	 * know that a service is provided by this service
	 * provider. Every service that is registered via
	 * this service provider must have an alias added
	 * to this array or it will be ignored
	 *
	 * @since 1.8.0
	 *
	 * @var array
	 */
	protected $provides = array(
		'migration-tool',
		'migration-tool.rest',
		'addons.migration-tool',
		MigrationToolAddon::class,
	);

	/**
	 * Registers services and dependencies for the Migration Tool.
	 * Accesses the container to register or retrieve necessary services,
	 * ensuring each service declared here is included in the `$provides` array.
	 *
	 * @since 1.8.0
	 */
	public function register() {

		// Register the REST controller for migration operations.
		$this->getContainer()->add( 'migration-tool.rest', LMSMigrationController::class )
			->addArgument( 'permission' );

		// Register the main addon class.
		$this->getContainer()->add( 'addons.migration-tool', MigrationToolAddon::class, true );
	}
}
