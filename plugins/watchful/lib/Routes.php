<?php
/**
 * Initialize the REST API routes.
 *
 * @version     2016-12-20 11:41 UTC+01
 * @package     Watchful WP Client
 * @author      Watchful
 * @authorUrl   https://watchful.net
 * @copyright   Copyright (c) 2020 watchful.net
 * @license     GNU/GPL
 */

namespace Watchful;

use Watchful\Controller\Backups;
use Watchful\Controller\Core;
use Watchful\Controller\Files;
use Watchful\Controller\Plugins;
use Watchful\Controller\Audit;
use Watchful\Controller\Tests;
use Watchful\Controller\Themes;
use Watchful\Controller\Validation;
use Watchful\Helpers\Authentification;
use WP_REST_Request;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class to register REST API routes.
 */
class Routes {
	/**
	 * Register watchful routes for WP API v2.
	 *
	 * @since  1.2.0
	 */
	public function register_routes() {

		new ExceptionHandler();
        new ShutdownHandler();

		$scanner = new Audit();
		$scanner->register_routes();

		$themes = new Themes();
		$themes->register_routes();

		$plugins = new Plugins();
		$plugins->register_routes();

		$core = new Core();
		$core->register_routes();

		$files = new Files();
		$files->register_routes();

		$files = new Tests();
		$files->register_routes();

		$backups = new Backups();
		$backups->register_routes();

        $validation = new Validation();
        $validation->register_routes();
	}

    /**
     * Authenticate REST request.
     *
     * @param WP_REST_Request $request The REST request.
     *
     * @return bool
     * @throws Exception
     */
	public static function authentification( WP_REST_Request $request ) {
		$settings         = get_option( 'watchfulSettings', '000' );
		$private_key      = $settings['watchfulSecretKey'];
		$authentification = new Authentification(
			$private_key,
			$request->get_param( 'verify_key' ),
			$request->get_param( 'stamp' ),
			$request->get_param( 'stamp' )
		);

		return $authentification->check();
	}
}
