<?php

namespace TotalContest\Admin\Ajax;

use TotalContestVendors\TotalCore\Contracts\Http\Request;
use TotalContestVendors\TotalCore\Contracts\Modules\Manager;

/**
 * Class Modules
 * @package TotalContest\Admin\Ajax
 */
class Modules {
	/**
	 * @var array
	 */
	protected $module = [ 'id' => null, 'type' => null ];
	/**
	 * @var Request
	 */
	protected $request;
	/**
	 * @var Manager
	 */
	protected $manager;

	/**
	 * Modules constructor.
	 *
	 * @param Request $request
	 * @param Manager $manager
	 */
	public function __construct( Request $request, Manager $manager ) {
		$this->request = $request;
		$this->manager = $manager;

		$this->module['id']   = $this->request->post( 'id' );
		$this->module['type'] = $this->request->post( 'type' );

		if ( $this->module['id'] && $this->module['type'] && ! in_array( $this->module['type'], [ 'extension', 'template' ] ) ):
			status_header( 406 );
			wp_send_json_error( new \WP_Error( 'unknown_module_type', 'Unknown module type.' ) );
		endif;
	}

	/**
	 * Install from file.
	 */
	public function installFromFile() {
		$result = $this->manager->install( $this->request->file( 'module' ) );

		if ( $result instanceof \WP_Error ):
			status_header( 406 );
			wp_send_json_error( $result->get_error_message() );
		else:
			wp_send_json_success( esc_html__( 'Module installed.', 'totalcontest' ) );
		endif;
	}

	/**
	 * Install from store.
	 */
	public function installFromStore() {
		$result = $this->manager->installFromStore( $this->module['id'] );

		if ( $result instanceof \WP_Error ):
			status_header( 406 );
			wp_send_json_error( $result->get_error_message() );
		else:
			wp_send_json_success( esc_html__( 'Module downloaded and installed.', 'totalcontest' ) );
		endif;
	}

	/**
	 * Fetch modules.
	 */
	public function fetch() {
		$hard = $this->request->request( 'hard', false );
		if ( ! empty( $hard ) ):
			TotalContest( 'utils.purge.store' );
		endif;

		$modules = array_values( $this->manager->fetch() );

		/**
		 * Filters modules sent to modules manager interface.
		 *
		 * @param \TotalContestVendors\TotalCore\Modules\Module[] $modules Array of modules.
		 * @param Manager                                         $manager Modules manager.
		 * @param Request                                         $request HTTP Request.
		 *
		 * @since 4.0.2
		 * @return array
		 */
		$modules = apply_filters( 'totalcontest/filters/admin/modules/fetch', $modules, $this->manager, $this->request );

		wp_send_json( $modules );
	}

	/**
	 * Update module.
	 */
	public function update() {
		$result = $this->manager->update( $this->module['id'] );

		if ( $result instanceof \WP_Error ):
			status_header( 406 );
			wp_send_json_error( $result->get_error_message() );
		else:
			wp_send_json_success( esc_html__( 'Module updated.', 'totalcontest' ) );
		endif;
	}

	/**
	 * Uninstall module.
	 */
	public function uninstall() {
		$uninstalled = $this->manager->uninstall( $this->module['id'] );

		if ( $uninstalled instanceof \WP_Error ):
			status_header( 406 );
			wp_send_json_error( $uninstalled->get_error_message() );
		else:
			wp_send_json_success( esc_html__( 'Module uninstalled.', 'totalcontest' ) );
		endif;
	}

	/**
	 * Activate module.
	 */
	public function activate() {
		$activated = $this->manager->activate( $this->module['id'] );

		if ( $activated instanceof \WP_Error ):
			status_header( 406 );
			wp_send_json_error( $activated->get_error_message() );
		else:
			wp_send_json_success( esc_html__( 'Module activated.', 'totalcontest' ) );
		endif;
	}

	/**
	 * Deactivate module.
	 */
	public function deactivate() {
		$deactivated = $this->manager->deactivate( $this->module['id'] );

		if ( $deactivated instanceof \WP_Error ):
			status_header( 406 );
			wp_send_json_error( $deactivated->get_error_message() );
		else:
			wp_send_json_success( esc_html__( 'Module deactivated.', 'totalcontest' ) );
		endif;
	}

}
