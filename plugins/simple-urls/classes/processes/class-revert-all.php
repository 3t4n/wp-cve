<?php
/**
 * Declare class Revert_All
 *
 * @package Revert_All
 */

namespace LassoLite\Classes\Processes;

use LassoLite\Classes\Import as Lasso_Import;
use LassoLite\Classes\Lasso_DB;
use LassoLite\Classes\Processes\Process;
use LassoLite\Classes\Processes\Import_All;

use LassoLite\Models\Model;

/**
 * Revert_All
 */
class Revert_All extends Process {
	const LIMIT  = 500;
	const OPTION = 'lasso_lite_revert_all_enable';

	/**
	 * Action name
	 *
	 * @var string $action
	 */
	protected $action = 'lassolite_revert_process_all';

	/**
	 * Log name
	 *
	 * @var string $log_name
	 */
	protected $log_name = 'lite_bulk_revert';

	/**
	 * Revert_All constructor.
	 */
	public function __construct() {
		parent::__construct();
		add_action( self::COMPLETE_ACTION_KEY, array( $this, 'complete_action' ), 10, 1 );
	}

	/**
	 * Task
	 *
	 * Override this method to perform any actions required on each
	 * queue item. Return the modified item for further processing
	 * in the next pass through. Or, return false to remove the
	 * item from the queue.
	 *
	 * @param mixed $data Queue item to iterate over.
	 *
	 * @return mixed
	 */
	public function task( $data ) {
		$start_time = microtime( true );

		$lasso_import = new Lasso_Import();
		$lasso_import->process_single_link_revert( $data['revert_id'], $data['source'] );

		$this->set_processing_runtime();
		$time_end       = microtime( true );
		$execution_time = round( $time_end - $start_time, 2 );

		return false;
	}

	/**
	 * Prepare data for process
	 *
	 * @param string $filter_plugin Plugin name. Default to null (all plugins).
	 */
	public function revert( $filter_plugin = null ) {
		// ? check whether process is age out and make it can work on Lasso UI via ajax requests
		$this->is_process_age_out();

		if ( $this->is_process_running() ) {
			return false;
		}

		$lasso_db = new Lasso_DB();

		$sql         = $lasso_db->get_revertable_urls_query( $filter_plugin );
		$sql         = $lasso_db->paginate( $sql, 1, self::LIMIT );
		$all_reverts = Model::get_results( $sql );
		$count       = count( $all_reverts );

		if ( $count <= 0 ) {
			update_option( self::OPTION, '0' );
			return false;
		}
		update_option( self::OPTION, '1' );

		// ? disable/remove import all process
		$import_all_process = new Import_All();
		$import_all_process->remove_process();
		update_option( Import_All::OPTION, '0' );
		delete_option( Import_All::FILTER_PLUGIN );

		foreach ( $all_reverts as $revert ) {
			if ( empty( $revert->import_id ) || empty( $revert->import_source ) ) {
				continue;
			}
			$this->push_to_queue(
				array(
					'revert_id' => $revert->import_id,
					'source'    => $revert->import_source,
				)
			);
		}

		$this->set_total( $count );
		$this->set_log_file_name( $this->log_name );
		$this->task_start_log();
		// ? save queue
		$this->save()->dispatch();

		return true;
	}

	/**
	 * Complete action
	 *
	 * @param string $action Action name.
	 * @return $this
	 */
	public function complete_action( $action ) {
		// ? If there is nothing to revert, we disable the revert all process at this time instead of waiting for the next cron(15 minutes for Lite)
		if ( $action === $this->action ) {
			$lasso_db    = new Lasso_DB();
			$sql         = $lasso_db->get_revertable_urls_query( null );
			$sql         = $lasso_db->paginate( $sql, 1, 1 );
			$all_reverts = Model::get_results( $sql );
			$count       = count( $all_reverts );

			if ( $count <= 0 ) {
				update_option( self::OPTION, '0' );
			}
		}

		return $this;
	}
}
