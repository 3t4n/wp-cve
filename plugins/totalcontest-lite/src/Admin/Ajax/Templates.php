<?php

namespace TotalContest\Admin\Ajax;

use TotalContest\Contracts\Modules\Repository;
use TotalContestVendors\TotalCore\Contracts\Http\Request;

/**
 * Class Templates
 * @package TotalContest\Admin\Ajax
 * @since   1.0.0
 */
class Templates {
	/**
	 * @var array $template
	 */
	protected $template;
	/**
	 * @var Request $request
	 */
	protected $request;
	/**
	 * @var Repository $request
	 */
	protected $repository;
	/**
	 * @var array
	 */
	protected $templates = [];

	/**
	 * Templates constructor.
	 *
	 * @param Request    $request
	 * @param Repository $repository
	 */
	public function __construct( Request $request, Repository $repository ) {
		$this->request    = $request;
		$this->repository = $repository;

		$this->template  = (string) $this->request->request( 'template' );
		$this->templates = $this->repository->getActiveWhere( [ 'type' => 'template' ] );

		if ( empty( $this->template ) || ! isset( $this->templates[ $this->template ] ) ):
			wp_send_json_error( new \WP_Error( 'unknown_template', 'Unknown template.' ) );
		endif;
	}

	/**
	 * Get template defaults
	 * @action-callback wp_ajax_totalcontest_templates_get_defaults
	 */
	public function getDefaults() {
		wp_send_json( $this->repository->getDefaults( $this->template ) );
	}

	/**
	 * Get template settings
	 * @action-callback wp_ajax_totalcontest_templates_get_settings
	 */
	public function getSettings() {
		echo $this->repository->getSettings( $this->template );
		wp_die();
	}

	/**
	 * Get template preview
	 * @action-callback wp_ajax_totalcontest_templates_get_preview
	 */
	public function getPreview() {
		echo $this->repository->getPreview( $this->template );
		wp_die();
	}
}