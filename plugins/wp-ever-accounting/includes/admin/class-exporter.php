<?php
/**
 * Handle export
 *
 * @package     EverAccounting
 * @subpackage  Admin
 * @version     1.0.2
 */

namespace EverAccounting\Admin;

defined( 'ABSPATH' ) || exit();

/**
 * Class Exporter
 *
 * @package EverAccounting/Admin
 */
class Exporter {

	/**
	 * Exporter constructor.
	 */
	public function __construct() {
		add_action( 'wp_ajax_eaccounting_do_ajax_export', array( __CLASS__, 'do_ajax_export' ) );
		add_action( 'admin_init', array( __CLASS__, 'handle_csv_download' ) );
	}

	/**
	 * Run the ajax export process
	 *
	 * @since 1.0.2
	 */
	public static function do_ajax_export() {
		$type = filter_input( INPUT_POST, 'type', FILTER_SANITIZE_STRING );
		if ( empty( $type ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Export type must be present.', 'wp-ever-accounting' ),
				)
			);
		}

		$type = sanitize_key( $type );
		check_admin_referer( "{$type}_exporter_nonce" );
		$step  = filter_input( INPUT_POST, 'step', FILTER_SANITIZE_NUMBER_INT );
		$batch = eaccounting()->utils->batch->get( $type );
		if ( empty( $type ) || false === $batch ) {
			wp_send_json_error(
				array(
					/* translators: %s: export type */
					'message' => sprintf( esc_html__( '%s is an invalid export type.', 'wp-ever-accounting' ), esc_html( $type ) ),
				)
			);
		}

		$class      = isset( $batch['class'] ) ? $batch['class'] : '';
		$class_file = isset( $batch['file'] ) ? $batch['file'] : '';

		if ( empty( $class_file ) ) {
			wp_send_json_error(
				array(
					/* translators: %s: export type */
					'message' => sprintf( esc_html__( 'An invalid file path is registered for the %1$s handler.', 'wp-ever-accounting' ), "<code>{$type}</code>" ),
				)
			);
		} else {
			require_once $class_file;
		}

		if ( empty( $class ) || ! class_exists( $class ) ) {
			wp_send_json_error(
				array(
					'message' => sprintf(
						$type( '%1$s is an invalid exporter handler for the %2$s . Please try again.', 'wp-ever-accounting' ),
						"<code>{$class}</code>",
						"<code>{$type}</code>"
					),
				)
			);
		}

		$exporter = new $class();

		if ( ! $exporter->can_export() ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'You do not have enough privileges to export this.', 'wp-ever-accounting' ),
				)
			);
		}

		$step = ! empty( $step ) ? absint( $step ) : 1;
		$exporter->process_step( $step );

		$query_args = apply_filters(
			'eaccounting_export_get_ajax_query_args',
			array(
				'nonce'    => wp_create_nonce( 'ea-download-file' ),
				'action'   => 'eaccounting_download_export_file',
				'filename' => $exporter->get_filename(),
				'page'     => 'ea-tools',
				'export'   => $type,
				'tab'      => 'export',
			)
		);

		if ( 100 <= $exporter->get_percent_complete() ) {
			$total = $exporter->get_total_exported();
			wp_send_json_success(
				array(
					'step'       => 'done',
					'percentage' => 100,
					'message'    => sprintf(
						// translators: %d: total items.
						esc_html__( 'Total %d items exported', 'wp-ever-accounting' ),
						$total
					),
					'url'        => add_query_arg(
						$query_args,
						eaccounting_admin_url(
							array(
								'page' => 'ea-tools',
								'tab'  => 'export',
							)
						)
					),
				)
			);
		} else {
			wp_send_json_success(
				array(
					'step'       => ++ $step,
					'percentage' => $exporter->get_percent_complete(),
				)
			);
		}

	}

	/**
	 * Handle CSV file download.
	 *
	 * @return void
	 * @since 1.0.2
	 */
	public static function handle_csv_download() {
		$action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
		$nonce  = filter_input( INPUT_GET, 'nonce', FILTER_SANITIZE_STRING );
		if ( isset( $action, $nonce ) && wp_verify_nonce( wp_unslash( $nonce ), 'ea-download-file' ) && 'eaccounting_download_export_file' === wp_unslash( $action ) ) {
			$export_type = filter_input( INPUT_GET, 'export', FILTER_SANITIZE_STRING );
			$filename    = filter_input( INPUT_GET, 'filename', FILTER_SANITIZE_STRING );
			$batch       = eaccounting()->utils->batch->get( $export_type );
			if ( empty( $export_type ) || false === $batch ) {
				wp_die(
					esc_html__( 'Invalid export type.', 'wp-ever-accounting' ),
					esc_html__( 'Error', 'wp-ever-accounting' ),
					array( 'response' => 403 )
				);
			}

			require_once $batch['file'];

			if ( empty( $batch['class'] ) || ( ! empty( $batch['class'] ) && ! class_exists( $batch['class'] ) ) ) {
				wp_die(
					esc_html__( 'Invalid export class.', 'wp-ever-accounting' ),
					esc_html__( 'Error', 'wp-ever-accounting' ),
					array( 'response' => 403 )
				);
			}

			$class    = $batch['class'];
			$exporter = new $class();

			if ( ! $exporter->can_export() ) {
				wp_die(
					esc_html__( 'You do not have enough privileges to export this.', 'wp-ever-accounting' ),
					esc_html__( 'Error', 'wp-ever-accounting' ),
					array( 'response' => 403 )
				);
			}
			if ( ! empty( $filename ) ) {
				$exporter->set_filename( $filename );
			}

			$exporter->export();
		}
	}
}

return new Exporter();
