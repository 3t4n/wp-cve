<?php
/**
 * Watchful audit class.
 *
 * @version   2016-12-20 11:41 UTC+01
 * @package   Watchful WP Client
 * @author    Watchful
 * @authorUrl https://watchful.net
 * @copyright Copyright (c) 2020 watchful.net
 * @license   GNU/GPL
 */

namespace Watchful\Controller;

use Watchful\Audit\Files\FilesScanner;
use Watchful\Audit\Files\Integrity;
use Watchful\Exception;
use Watchful\Helpers\Authentification;
use \WP_REST_Request;
use \WP_REST_Server;
use \WP_REST_Response;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Watchful Scanner class.
 */
class Audit implements BaseControllerInterface  {

	/**
	 * Scanner start value.
	 *
	 * @var int
	 */
	protected $start;

	/**
	 * Scanner constructor.
	 *
	 * @param int $start The start value for the scanner.
	 */
	public function __construct( $start = 0 ) {
		$this->start = $start;
	}

	/**
	 * Register WP REST API routes.
	 */
	public function register_routes() {
		register_rest_route(
			'watchful/v1',
			'/audit',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'audit' ),
					'permission_callback' => array( 'Watchful\Routes', 'authentification' ),
					'args'                => array_merge(
						Authentification::get_arguments(),
						array(
							'start'              => array(
								'default'           => 0,
								'sanitize_callback' => 'absint',
							),
							'max_execution_time' => array(
								'default'           => null,
								'sanitize_callback' => 'absint',
							),
							'task'               => array(
								'default' => null,
							),
						)
					),
				),
			)
		);
	}

	/**
	 * Audit the REST request.
	 *
	 * @param WP_REST_Request $request The WP REST request object.
	 *
	 * @return WP_REST_Response
	 *
	 * @throws Exception If scanner task method doesn't exist.
	 */
	public static function audit( WP_REST_Request $request ) {

		$task    = $request->get_param( 'task' );
		$scanner = new Audit($request->get_param('start' ) );

		if ( ! method_exists( $scanner, $task ) ) {
			throw new Exception( 'bad-task', 403 );
		}

		$result = $scanner->$task();
		return new WP_REST_Response( $result );
	}

	/**
	 * Get the audit configuration.
	 *
	 * @return \stdClass
	 */
	public function auditConfiguration() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
		$this->init_audit();

		$wp_audit     = new \stdClass();
		$ds           = DIRECTORY_SEPARATOR;
		$tests_folder = new \DirectoryIterator( dirname( __FILE__ ) . $ds . '..' . $ds . 'Audit' . $ds . 'Tests' );
		foreach ( $tests_folder as $file_info ) {

			if ( $file_info->isDot() ) {
				continue;
			}

			if ( $file_info->isDir() ) {
				continue;
			}

			$file_name            = basename( $file_info->getFilename(), '.php' );
			$class                = 'Watchful\Audit\Tests\\' . $file_name;
			$test                 = new $class();
			$wp_audit->$file_name = $test->run();
		}

		return $wp_audit;
	}

	/**
	 * Audit the malware scanner.
	 *
	 * @return \stdClass
	 */
	public function auditMalwareScanner() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
		$scanner = new FilesScanner();
		$result  = $scanner->auditMalwareScanner( $this->start );

		return $result;
	}

	/**
	 * Audit the folder permissions.
	 *
	 * @return \stdClass
	 */
	public function auditFoldersPermissions() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
		$scanner = new FilesScanner();
		$result  = $scanner->auditFoldersPermissions( $this->start );

		return $result;
	}

	/**
	 * Audit the file permissions.
	 *
	 * @return \stdClass
	 */
	public function auditFilesPermissions() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
		$scanner = new FilesScanner();
		$result  = $scanner->auditFilesPermissions( $this->start );

		return $result;
	}

	/**
	 * Audit core integrity.
	 *
	 * @return \stdClass
	 */
	public function auditCoreIntegrity() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName
		$model  = new Integrity();
		$result = $model->auditCoreIntegrity( $this->start );

		return $result;
	}

	/**
	 * This method is called only once when the audit starts.
	 * We can do some initializations here before actually starting the audit.
	 *
	 * @return void
	 */
	private function init_audit() {
		// Remove the filesystem cache for the WP root.
		wp_cache_delete( ABSPATH, 'watchful.audit.recursiveListing' );
	}
}
