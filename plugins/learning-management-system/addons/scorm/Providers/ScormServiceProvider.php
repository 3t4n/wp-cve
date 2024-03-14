<?php
/**
 * Scorm service provider.
 *
 * @since 1.8.3
 */

namespace Masteriyo\Addons\Scorm\Providers;

defined( 'ABSPATH' ) || exit;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Masteriyo\Addons\Scorm\Controllers\ScormController;
use Masteriyo\Addons\Scorm\ScormAddon;

/**
 * Scorm service provider.
 *
 * @since 1.8.3
 */
class ScormServiceProvider extends AbstractServiceProvider {
	/**
	 * The provided array is a way to let the container
	 * know that a service is provided by this service
	 * provider. Every service that is registered via
	 * this service provider must have an alias added
	 * to this array or it will be ignored
	 *
	 * @since 1.8.3
	 *
	 * @var array
	 */
	protected $provides = array(
		'scorm',
		'scorm.rest',
		'addons.scorm',
		ScormAddon::class,
	);

	/**
	 * Registers services and dependencies for the Scorm.
	 * Accesses the container to register or retrieve necessary services,
	 * ensuring each service declared here is included in the `$provides` array.
	 *
	 * @since 1.8.3
	 */
	public function register() {

		// Register the REST controller for migration operations.
		$this->getContainer()->add( 'scorm.rest', ScormController::class )
			->addArgument( 'permission' );

		// Register the main addon class.
		$this->getContainer()->add( 'addons.scorm', ScormAddon::class, true );
	}
}
