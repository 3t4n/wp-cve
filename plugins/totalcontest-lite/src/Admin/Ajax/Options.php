<?php

namespace TotalContest\Admin\Ajax;

use TotalContest\Contracts\Migrations\Contest\Migrator;
use TotalContestVendors\TotalCore\Contracts\Http\Request;

/**
 * Class Options
 * @package TotalContest\Admin\Ajax
 */
class Options {
	/**
	 * @var Request
	 */
	protected $request;
	/**
	 * @var Migrator[] $migrators
	 */
	protected $migrators;

	/**
	 * Options constructor.
	 *
	 * @param Request    $request
	 * @param Migrator[] $migrators
	 */
	public function __construct( Request $request, $migrators ) {
		$this->request   = $request;
		$this->migrators = $migrators;
	}

	/**
	 * Save options.
	 */
	public function saveOptions() {
		$options = json_decode( $this->request->post( 'options', '{}' ), true );
		if ( ! empty( $options ) ):
			TotalContest( 'options' )->setOptions( $options, true );
			wp_schedule_single_event( time(), 'totalcontest/actions/urls/flush' );
		endif;
		wp_send_json_success( esc_html__( 'Saved.', 'totalcontest' ) );
	}

	/**
	 * Purge.
	 */
	public function purge() {
		$type = $this->request->request( 'type', 'cache' );
		if ( $type === 'cache' ):
			TotalContest( 'utils.purge.cache' );
			TotalContest( 'utils.purge.store' );
		endif;
		wp_send_json_success( esc_html__( 'Purged.', 'totalcontest' ) );
	}


	/**
	 * Migrate contests AJAX endpoint.
	 * @action-callback wp_ajax_totalcontest_options_migrate_contests
	 */
	public function migrateContests() {
		
	}
}
