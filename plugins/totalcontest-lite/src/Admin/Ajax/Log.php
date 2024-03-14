<?php

namespace TotalContest\Admin\Ajax;

use TotalContest\Contracts\Log\Model as LogModel;
use TotalContest\Contracts\Log\Repository;
use TotalContest\Helpers\DateTime;
use TotalContest\Log\Export;
use TotalContestVendors\TotalCore\Contracts\Http\Request;
use TotalContestVendors\TotalCore\Export\ColumnTypes\DateColumn;
use TotalContestVendors\TotalCore\Export\ColumnTypes\TextColumn;
use TotalContestVendors\TotalCore\Export\Spreadsheet;
use TotalContestVendors\TotalCore\Export\Writer;
use TotalContestVendors\TotalCore\Export\Writers\CsvWriter;
use TotalContestVendors\TotalCore\Export\Writers\HTMLWriter;
use TotalContestVendors\TotalCore\Export\Writers\JsonWriter;
use TotalContestVendors\TotalCore\Helpers\Tracking;

/**
 * Class Log
 * @package TotalContest\Admin\Ajax
 */
class Log {
	/**
	 * @var Request $request
	 */
	protected $request;
	/**
	 * @var Repository $log
	 */
	protected $log;
	/**
	 * @var array $criteria
	 */
	protected $criteria = [];

	/**
	 * Log constructor.
	 *
	 * @param Request $request
	 * @param Repository $log
	 */
	public function __construct( Request $request, Repository $log ) {
		$this->request = $request;
		$this->log     = $log;

		$this->criteria = [
			'page'       => absint( $this->request->request( 'page', 1 ) ),
			'contest'    => $this->request->request( 'contest', null ),
			'submission' => $this->request->request( 'submission', null ),
			'from'       => $this->request->request( 'from', null ),
			'to'         => $this->request->request( 'to', null ),
			'format'     => $this->request->request( 'format', null ),
		];
	}

	/**
	 * Get AJAX endpoint.
	 * @action-callback wp_ajax_totalcontest_log_list
	 */
	public function fetch() {
		$args = [ 'conditions' => [ 'date' => [] ], 'page' => $this->criteria['page'], 'perPage' => 30 ];

		if ( $this->criteria['contest'] ):
			$args['conditions']['contest_id'] = $this->criteria['contest'];
		endif;

		if ( $this->criteria['submission'] ):
			$args['conditions']['submission_id'] = $this->criteria['submission'];
		endif;

		if ( $this->criteria['from'] && DateTime::strptime( $this->criteria['from'], '%Y-%m-%d' ) ):
			$args['conditions']['date'][] = [ 'operator' => '>=', 'value' => "{$this->criteria['from']} 00:00:00" ];
		endif;

		if ( $this->criteria['to'] && DateTime::strptime( $this->criteria['to'], '%Y-%m-%d' ) ):
			$args['conditions']['date'][] = [ 'operator' => '<=', 'value' => "{$this->criteria['to']} 23:59:59" ];
		endif;

		$entries = $this->log->get( $args );

		/**
		 * Filters the list of log entries sent to log browser.
		 *
		 * @param LogModel[] $entries Array of log entries models.
		 * @param array $criteria Array of criteria.
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$entries = apply_filters( 'totalcontest/filters/admin/log/fetch', $entries, $this->criteria );


		wp_send_json( [ 'entries' => $entries, 'lastPage' => count( $entries ) === 0 || count( $entries ) < 30 ] );
	}

	/**
	 * Download AJAX endpoint.
	 * @action-callback wp_ajax_totalcontest_log_download
	 */
	public function download() {
		$args = [ 'conditions' => [], 'perPage' => - 1 ];

		if ( $this->criteria['page'] == 0 ):
			$args['page']    = 1;
			$args['perPage'] = 999999;
		endif;

		if ( $this->criteria['contest'] ):
			$args['conditions']['contest_id'] = $this->criteria['contest'];
		endif;

		if ( $this->criteria['submission'] ):
			$args['conditions']['submission_id'] = $this->criteria['submission'];
		endif;

		$args['conditions']['date'] = [];

		if ( $this->criteria['from'] && DateTime::strptime( $this->criteria['from'], '%Y-%m-%d' ) ):
			$args['conditions']['date'][] = [ 'operator' => '>=', 'value' => "{$this->criteria['from']} 00:00:00" ];
		endif;

		if ( $this->criteria['to'] && DateTime::strptime( $this->criteria['to'], '%Y-%m-%d' ) ):
			$args['conditions']['date'][] = [ 'operator' => '<=', 'value' => "{$this->criteria['to']} 23:59:59" ];
		endif;

		$entries = (array) $this->log->get( $args );

		/**
		 * Filters the list of log entries to be exported.
		 *
		 * @param LogModel[] $entries Array of log entries models.
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$entries = apply_filters( 'totalcontest/filters/admin/log/export/entries', $entries );

		$export = new Spreadsheet();

		$export->addColumn( new TextColumn( 'Status' ) );
		$export->addColumn( new TextColumn( 'Action' ) );
		$export->addColumn( new DateColumn( 'Date' ) );
		$export->addColumn( new TextColumn( 'IP' ) );
		$export->addColumn( new TextColumn( 'Browser' ) );
		$export->addColumn( new TextColumn( 'User ID' ) );
		$export->addColumn( new TextColumn( 'User login' ) );
		$export->addColumn( new TextColumn( 'User name' ) );
		$export->addColumn( new TextColumn( 'User email' ) );
		$export->addColumn( new TextColumn( 'Details' ) );


		/**
		 * Fires after setup essential columns and before populating data. Useful for define new columns.
		 *
		 * @param Spreadsheet $export Spreadsheet object.
		 * @param array $entries Array of log entries.
		 *
		 * @since 2.0.0
		 */
		do_action( 'totalcontest/actions/admin/log/export/columns', $export, $entries );

		foreach ( $entries as $entry ):
			/**
			 * Filters a row of exported log entries.
			 *
			 * @param array $row Array of values.
			 * @param LogModel $entry Log entry model.
			 *
			 * @return array
			 * @since 2.0.0
			 */
			$row = apply_filters(
				'totalcontest/filters/admin/log/export/row',
				[
					$entry->getStatus(),
					$entry->getAction(),
					$entry->getDate(),
					$entry->getIp(),
					$entry->getUseragent(),
					$entry->getUserId() ?: 'N/A',
					$entry->getUser()->user_login ?: 'N/A',
					$entry->getUser()->display_name ?: 'N/A',
					$entry->getUser()->user_email ?: 'N/A',
					esc_html( $this->criteria['format'] !== 'json' ? json_encode( $entry->getDetails(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) : $entry->getDetails() ),
				],
				$entry,
				$this
			);

			$export->addRow( $row );
		endforeach;

		
		$writer = new HTMLWriter();
		

		

		/**
		 * Filters the file writer for a specific format when exporting log entries.
		 *
		 * @param Writer $writer Writer object.
		 *
		 * @return Writer
		 * @since 2.0.0
		 */
		$writer = apply_filters( "totalcontest/filters/admin/log/export/writer/{$this->criteria['format']}", $writer );

		$writer->includeColumnHeaders = true;

		$export->download( $writer, 'totalcontest-export-log-' . date( 'Y-m-d H:i:s' ) );

		exit;
	}

	public function export() {
		$args = [ 'conditions' => [], 'page' => 0 ];

		if ( $this->criteria['contest'] ):
			$args['conditions']['contest_id'] = $this->criteria['contest'];
		endif;

		if ( $this->criteria['submission'] ):
			$args['conditions']['submission_id'] = $this->criteria['submission'];
		endif;

		$args['conditions']['date'] = [];

		if ( $this->criteria['from'] && DateTime::strptime( $this->criteria['from'], '%Y-%m-%d' ) ):
			$args['conditions']['date'][] = [ 'operator' => '>=', 'value' => "{$this->criteria['from']} 00:00:00" ];
		endif;

		if ( $this->criteria['to'] && DateTime::strptime( $this->criteria['to'], '%Y-%m-%d' ) ):
			$args['conditions']['date'][] = [ 'operator' => '<=', 'value' => "{$this->criteria['to']} 23:59:59" ];
		endif;

		wp_send_json_success( Export::enqueue( $args, $this->criteria['format'] ) );
	}

	public function exportStatus() {
		$uid = $this->request->request( 'uid', null );
		if ( empty( $uid ) ) {
			wp_send_json_error();
		}

		wp_send_json_success( Export::getState( $uid ) );
	}

	/**
	 * Remove ItemAJAX endpoint.
	 * @action-callback wp_ajax_totalcontest_remove
	 */
	public function remove() {
		$id = (int) $this->request->post( 'id' );

		$log = $this->log->getById($id);

		if($submission = $log->getSubmission()){
			$submission->incrementVotes(-1);
		}

		$result = $this->log->delete( [
			'id' => $id
		] );

		if ( $result ) {
			wp_send_json_success();
		}

		wp_send_json_error();
	}

}
