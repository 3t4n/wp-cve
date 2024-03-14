<?php
/**
 * Initialize this version of the REST API.
 */

namespace SG_Email_Marketing\Rest;

use SG_Email_Marketing\Rest\Controllers\v1\Pages\Dashboard;
use SG_Email_Marketing\Rest\Controllers\v1\Pages\Forms;
use SG_Email_Marketing\Rest\Controllers\v1\Pages\Settings;
use SG_Email_Marketing\Rest\Controllers\v1\Labels;
use SG_Email_Marketing\Rest\Controllers\v1\Integrations\CF7;

defined( 'ABSPATH' ) || exit;

/**
 * Class responsible for loading the REST API and all REST API namespaces.
 */
class Server {
	/**
	 * REST API namespaces and endpoints.
	 *
	 * @var array
	 */
	protected $controllers = array();

	/**
	 * Register REST API routes.
	 *
	 * @since 1.0.0
	 */
	public function register_rest_routes() {
		$dashboard       = new Dashboard();
		$forms_page      = new Forms();
		$settings_page   = new Settings();
		$labels          = new Labels();
		$cf7_integration = new CF7();

		$dashboard->register_rest_routes();
		$forms_page->register_routes();
		$settings_page->register_rest_routes();
		$labels->register_rest_routes();
		$cf7_integration->register_rest_routes();
	}
}
