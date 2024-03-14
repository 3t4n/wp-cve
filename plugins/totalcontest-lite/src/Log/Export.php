<?php

namespace TotalContest\Log;

use TotalContest\Contracts\Log\Model as LogModel;
use TotalContest\Writers\PartialCsvWriter;
use TotalContest\Writers\PartialHTMLWriter;
use TotalContest\Writers\PartialJsonWriter;
use TotalContest\Writers\PartialSpreadsheet;
use TotalContestVendors\TotalCore\Export\ColumnTypes\DateColumn;
use TotalContestVendors\TotalCore\Export\ColumnTypes\TextColumn;
use TotalContestVendors\TotalCore\Export\Writer;

/**
 * Log Export Job
 * @package TotalLog\Log
 * @since   1.0.0
 */
class Export {
	const ENQUEUED = 'enqueued';
	const STARTED = 'started';
	const FINISHED = 'finished';

	const ACTION_NAME = 'totalcontest_export_log';
	const BATCH_SIZE = 100;

	public static function process( $context ) {
		$export = static::getState( $context['uid'] );

		if ( ! $export ) {
			return new \WP_Error( 'no_such_export', 'No such export job.' );
		}

		if ( $export['status'] === self::ENQUEUED ) {
			$export['status']            = self::STARTED;
			$export['total']             = TotalContest( 'log.repository' )->count( $context['query'] );
			$export['file']              = static::firstWrite( $context );
			$context['query']['page']    = 0;
			$context['query']['perPage'] = self::BATCH_SIZE;
			$context['file']             = $export['file'];
		}

		if ( $export['status'] === self::STARTED ) {
			$context['query']['page'] += 1;
			$entries                  = (array) TotalContest( 'log.repository' )->get( $context['query'] );
			$count                    = count( $entries );
			$export['processed']      += $count;

			if ( $count > 0 ) {
				static::partialWrite( $entries, $context );
			} else {
				$export['status'] = static::FINISHED;
				$export['url']    = TotalContest()->env( 'exports.url' ) . $export['file'];
				static::lastWrite( $context );
			}

			as_enqueue_async_action( self::ACTION_NAME, [ $context ], $export['uid'] );
		}

		static::setState( $export );
	}

	public static function getPartialSpreadsheet() {
		$export = new PartialSpreadsheet();

		$export->addColumn( new TextColumn( 'Status' ) );
		$export->addColumn( new TextColumn( 'Action' ) );
		$export->addColumn( new DateColumn( 'Date' ) );
		$export->addColumn( new TextColumn( 'IP' ) );
		$export->addColumn( new TextColumn( 'Browser' ) );
		$export->addColumn( new TextColumn( 'User ID' ) );
		$export->addColumn( new TextColumn( 'User login' ) );
		$export->addColumn( new TextColumn( 'User name' ) );
		$export->addColumn( new TextColumn( 'User email' ) );
		$export->addColumn( new TextColumn( 'Contest ID' ) );
		$export->addColumn( new TextColumn( 'Submission ID' ) );
		$export->addColumn( new TextColumn( 'Submission URL' ) );
		$export->addColumn( new TextColumn( 'Details' ) );

		/**
		 * Fires after setup essential columns and before populating data. Useful for define new columns.
		 *
		 * @param PartialSpreadsheet $export PartialSpreadsheet object.
		 * @param array $entries Array of log entries.
		 *
		 * @since 2.0.0
		 */
		do_action( 'totalcontest/actions/admin/log/export/columns', $export );

		return $export;
	}

	public static function getWriter( $format ) {

		
		$writer = new PartialHTMLWriter();
		

		

		/**
		 * Filters the file writer for a specific format when exporting log entries.
		 *
		 * @param Writer $writer Writer object.
		 *
		 * @return Writer
		 * @since 2.0.0
		 */
		$writer = apply_filters( "totalcontest/filters/admin/log/export/writer/{$format}", $writer );

		return $writer;
	}

	public static function firstWrite( $context ) {
		$export = static::getPartialSpreadsheet();
		$writer = static::getWriter( $context['format'] );

		TotalContest( 'utils.create.exports' );
		$filename = sanitize_title_with_dashes( 'totalcontest-export-log-' . date( 'Y-m-d H:i:s' ) ) . '.' . $writer->getDefaultExtension();
		$filename = apply_filters('totalcontest/filters/admin/log/export/filename', $filename, $context);
		$path     = TotalContest()->env( 'exports.path' ) . $filename;

		$writer->markAsFirstLine();
		$export->save( $writer, $path );

		return $filename;
	}

	public static function lastWrite( $context ) {
		$export = static::getPartialSpreadsheet();
		$writer = static::getWriter( $context['format'] );

		$writer->markAsLastLine();
		$export->save( $writer, TotalContest()->env( 'exports.path' ) . $context['file'] );
	}

	public static function partialWrite( $entries, $context ) {
		$export = static::getPartialSpreadsheet();
		$writer = static::getWriter( $context['format'] );

		/**
		 * Filters the list of log entries to be exported.
		 *
		 * @param LogModel[] $entries Array of log entries models.
		 *
		 * @return array
		 * @since 2.0.0
		 */
		$entries = apply_filters( 'totalcontest/filters/admin/log/export/entries', $entries );

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
					$entry->getContestId(),
					$entry->getSubmissionId(),
					get_permalink($entry->getSubmissionId()),
					$entry->getDetails(),
				],
				$entry,
				$context
			);

			$export->addRow( $row );
		endforeach;

		$export->save( $writer, TotalContest()->env( 'exports.path' ) . $context['file'] );
	}

	public static function enqueue( array $query, $format = 'csv' ) {
		$context = [
			'query'  => $query,
			'format' => $format,
			'uid'    => wp_generate_uuid4()
		];

		as_enqueue_async_action( self::ACTION_NAME, [ $context ], $context['uid'] );

		$export = [
			'uid'       => $context['uid'],
			'status'    => 'enqueued',
			'format'    => $format,
			'processed' => 0,
			'total'     => 0,
			'file'      => '',
			'url'       => ''
		];

		static::setState( $export );

		return $export;
	}

	public static function getState( $exportUid ) {
		return get_transient( "totalcontest_export:{$exportUid}" );
	}

	public static function setState( $export ) {
		set_transient( "totalcontest_export:{$export['uid']}", $export, WEEK_IN_SECONDS );
	}
}
