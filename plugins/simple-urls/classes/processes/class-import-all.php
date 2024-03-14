<?php
/**
 * Declare class Lasso_Process_Import_All
 *
 * @package Lasso_Process_Import_All
 */

namespace LassoLite\Classes\Processes;

use LassoLite\Classes\Helper as Lasso_Helper;
use LassoLite\Classes\Import as Lasso_Import;
use LassoLite\Classes\Lasso_DB;

use LassoLite\Classes\Processes\Process;
use LassoLite\Classes\Processes\Revert_All;

use LassoLite\Models\Model;

/**
 * Lasso_Process_Import_All
 */
class Import_All extends Process {
	const LIMIT         = 500;
	const OPTION        = 'lasso_lite_import_all_enable';
	const FILTER_PLUGIN = 'lasso_lite_import_all_filter_plugin';

	/**
	 * Action name
	 *
	 * @var string $action
	 */
	protected $action = 'lassolite_import_process_all';

	/**
	 * Log name
	 *
	 * @var string $log_name
	 */
	protected $log_name = 'lite_bulk_import';

	/**
	 * Import_All constructor.
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
		$time_start       = microtime( true );
		$import_id        = $data['import_id'] ?? false;
		$post_type        = $data['post_type'] ?? false;
		$post_title       = $data['post_title'] ?? '';
		$import_permalink = $data['import_permalink'] ?? '';

		$lasso_import = new Lasso_Import();
		if ( $import_id && $post_type ) {
			$lasso_import->process_single_link_data( $import_id, $post_type, $post_title, $import_permalink );
		}

		$this->set_processing_runtime();
		$time_end       = microtime( true );
		$execution_time = round( $time_end - $time_start, 2 );

		return false;
	}

	/**
	 * Prepare data for process
	 *
	 * @param string $filter_plugin Plugin name.
	 */
	public function import( $filter_plugin = null ) {
		// ? check whether process is age out and make it can work on Lasso UI via ajax requests
		$this->is_process_age_out();

		if ( $this->is_process_running() ) {
			return false;
		}

		$lasso_db = new Lasso_DB();

		$filter_plugin = $filter_plugin ? $filter_plugin : get_option( self::FILTER_PLUGIN, null );
		$sql           = $lasso_db->get_importable_urls_query( false, '', '', $filter_plugin );
		$sql           = $lasso_db->paginate( $sql, 1, self::LIMIT );
		$all_imports   = Model::get_results( $sql );
		$count         = count( $all_imports );

		if ( $count <= 0 ) {
			update_option( self::OPTION, '0' );
			delete_option( self::FILTER_PLUGIN );
			return false;
		}
		update_option( self::OPTION, '1' );

		// ? disable/remove revert all process
		$revert_all_process = new Revert_All();
		$revert_all_process->remove_process();
		update_option( Revert_All::OPTION, '0' );

		if ( $filter_plugin ) {
			update_option( self::FILTER_PLUGIN, $filter_plugin );
		}

		do_action( 'lasso_lite_import_all_process' );

		foreach ( $all_imports as $import ) {
			$import = Lasso_Helper::format_importable_data( $import );
			if ( empty( $import->id ) || empty( $import->post_type ) || 'checked' === $import->check_status ) {
				continue;
			}

			$this->push_to_queue(
				array(
					'import_id'        => $import->id,
					'post_type'        => $import->post_type,
					'post_title'       => Lasso_Helper::remove_unexpected_characters_from_post_title( $import->post_title ),
					'import_permalink' => $import->import_permalink,
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
		// ? If there is nothing to import, we disable the import all process at this time instead of waiting for the next cron(15 minutes for Lite)
		if ( $action === $this->action ) {
			$lasso_db      = new Lasso_DB();
			$filter_plugin = get_option( self::FILTER_PLUGIN, null );
			$sql           = $lasso_db->get_importable_urls_query( false, '', '', $filter_plugin );
			$sql           = $lasso_db->paginate( $sql, 1, 1 );
			$all_imports   = Model::get_results( $sql );
			$count         = count( $all_imports );

			if ( $count <= 0 ) {
				update_option( self::OPTION, '0' );
				delete_option( self::FILTER_PLUGIN );
			}
		}

		return $this;
	}
}
