<?php
/**
 * Handle import
 *
 * @package     EverAccounting
 * @subpackage  Admin
 * @version     1.0.2
 */

namespace EverAccounting\Admin;

use EverAccounting\Ajax;

defined( 'ABSPATH' ) || exit();

/**
 * Class Importer
 *
 * @package EverAccounting/Admin
 */
class Importer {
	/**
	 * Importer constructor.
	 */
	public function __construct() {
		add_action( 'wp_ajax_eaccounting_do_ajax_import', array( __CLASS__, 'do_ajax_import' ) );
	}

	/**
	 * Run the ajax import process
	 *
	 * @since 1.0.2
	 */
	public static function do_ajax_import() {
		$type = filter_input( INPUT_POST, 'type', FILTER_SANITIZE_STRING );
		if ( empty( $type ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Import type must be present.', 'wp-ever-accounting' ),
				)
			);
		}

		check_ajax_referer( $type . '_importer_nonce', 'nonce' );
		$delimiter       = filter_input( INPUT_POST, 'delimiter', FILTER_SANITIZE_STRING );
		$position        = filter_input( INPUT_POST, 'position', FILTER_SANITIZE_NUMBER_INT );
		$mapping         = filter_input( INPUT_POST, 'mapping', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY );
		$update_existing = filter_input( INPUT_POST, 'update_existing', FILTER_SANITIZE_STRING );
		$limit           = filter_input( INPUT_POST, 'limit', FILTER_SANITIZE_NUMBER_INT );
		$step            = filter_input( INPUT_POST, 'step', FILTER_SANITIZE_STRING );
		$file            = filter_input( INPUT_POST, 'file', FILTER_SANITIZE_STRING );
		$params          = array(
			'delimiter'       => ! empty( $delimiter ) ? sanitize_key( $delimiter ) : ',',
			'position'        => ! empty( $position ) ? absint( $position ) : 0,
			'mapping'         => ! empty( $mapping ) ? (array) wp_unslash( $mapping ) : array(),
			'update_existing' => ! empty( $update_existing ) && (bool) $update_existing,
			'limit'           => apply_filters( 'eaccounting_import_batch_size', 30 ),
			'parse'           => true,
		);


		$batch = eaccounting()->utils->batch->get( $type );
		if ( empty( $type ) || false === $batch ) {
			wp_send_json_error(
				array(
					/* translators: %s: import type */
					'message' => sprintf( esc_html__( '%s is an invalid import type.', 'wp-ever-accounting' ), esc_html( $type ) ),
				)
			);
		}

		$class      = isset( $batch['class'] ) ? $batch['class'] : '';
		$class_file = isset( $batch['file'] ) ? $batch['file'] : '';

		if ( empty( $class_file ) ) {
			wp_send_json_error(
				array(
					/* translators: %s: import type */
					'message' => sprintf( esc_html__( 'An invalid file path is registered for the %1$s handler.', 'wp-ever-accounting' ), "<code>{$type}</code>" ),
				)
			);
		}

		require_once $class_file;

		if ( empty( $class ) || ! class_exists( $class ) ) {
			wp_send_json_error(
				array(
					'message' => sprintf(
						/* translators: %1$s: import type, %2$s: class name */
						esc_html__( '%1$s is an invalid importer handler for the %2$s . Please try again.', 'wp-ever-accounting' ),
						"<code>{$class}</code>",
						"<code>{$type}</code>"
					),
				)
			);
		}

		if ( empty( $file ) && empty( $_FILES['upload'] ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Missing import file. Please provide an import file.', 'wp-ever-accounting' ),
				)
			);
		}

		if ( ! empty( $_FILES['upload'] ) ) {
			$accepted_mime_types = array(
				'text/csv',
				'text/comma-separated-values',
				'text/plain',
				'text/anytext',
				'text/*',
				'text/plain',
				'text/anytext',
				'text/*',
				'application/csv',
				'application/excel',
				'application/vnd.ms-excel',
				'application/vnd.msexcel',
			);

			if ( empty( $_FILES['upload']['type'] ) || ! in_array( strtolower( $_FILES['upload']['type'] ), $accepted_mime_types, true ) ) {
				wp_send_json_error(
					array(
						'message' => __( 'The file you uploaded does not appear to be a CSV file.', 'wp-ever-accounting' ),
					)
				);
			}

			if ( isset( $_FILES['upload']['tmp_name'] ) && ! file_exists( wp_unslash( $_FILES['upload']['tmp_name'] ) ) ) {
				wp_send_json_error(
					array(
						'message' => __( 'Something went wrong during the upload process, please try again.', 'wp-ever-accounting' ),
					)
				);
			}

			// Let WordPress import the file. We will remove it after import is complete.
			$upload      = wp_unslash( $_FILES['upload'] );
			$import_file = wp_handle_upload( $upload, array( 'test_form' => false ) );
			if ( ! empty( $import_file['error'] ) ) {
				wp_send_json_error(
					array(
						'message' => __( 'Something went wrong during the upload process, please try again.', 'wp-ever-accounting' ),
						'error'   => $import_file,
					)
				);
			}

			$file = $import_file['file'];
		}

		if ( empty( $file ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Missing import file. Please provide an import file.', 'wp-ever-accounting' ),
				)
			);
		}
		require_once EACCOUNTING_ABSPATH . '/includes/admin/ea-admin-functions.php';
		$importer = new $class( $file, $params );
		if ( ! $importer->can_import() ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to import data', 'wp-ever-accounting' ) ) );
		}

		$headers = $importer->get_raw_keys();
		$sample  = current( $importer->get_raw_data() );

		if ( empty( $sample ) ) {
			wp_send_json_error( array( 'message' => __( 'The file is empty or using a different encoding than UTF-8, please try again with a new file.', 'wp-ever-accounting' ) ) );
		}

		if ( 'upload' === $step ) {
			wp_send_json_success(
				array(
					'position' => 0,
					'headers'  => $headers,
					'required' => $importer->get_required(),
					'sample'   => $sample,
					'step'     => $step,
					'file'     => $file,
				)
			);
		}

		// Log failures.
		if ( $params['position'] > 0 ) {
			$imported = (int) get_user_option( "{$type}_import_log_imported" );
			$skipped  = (int) get_user_option( "{$type}_import_log_skipped" );
		} else {
			$skipped  = 0;
			$imported = 0;
		}

		$results          = $importer->import();
		$percent_complete = $importer->get_percent_complete();
		$skipped         += (int) $results['skipped'];
		$imported        += (int) $results['imported'];

		update_user_option( get_current_user_id(), "{$type}_import_log_imported", $imported );
		update_user_option( get_current_user_id(), "{$type}_import_log_skipped", $skipped );

		if ( 100 <= $percent_complete ) {
			delete_user_option( get_current_user_id(), "{$type}_import_log_imported" );
			delete_user_option( get_current_user_id(), "{$type}_import_log_skipped" );
			wp_send_json_success(
				array(
					'position'   => 'done',
					'percentage' => 100,
					'imported'   => (int) $imported,
					'skipped'    => (int) $skipped,
					'file'       => $file,
					/* translators: %d: number of imported items */
					'message'    => sprintf( esc_html__( '%1$d items imported and %2$d items skipped.', 'wp-ever-accounting' ), $imported, $skipped ),
				)
			);
		} else {
			wp_send_json_success(
				array(
					'position'   => $importer->get_position(),
					'percentage' => $percent_complete,
					'imported'   => (int) $imported,
					'skipped'    => (int) $skipped,
					'file'       => $file,
					'step'       => 'import',
					'mapping'    => $params['mapping'],
				)
			);
		}
		exit();
	}
}

return new Importer();
