<?php
/**
 * Single Page Background Process
 *
 * @package Demo Importer Plus
 */

if ( class_exists( 'WP_Background_Process' ) ) :

	/**
	 * Image Background Process
	 */
	class WP_Background_Process_Demo_Importer_Site_Importer extends WP_Background_Process {

		/**
		 * Image Process
		 *
		 * @var string
		 */
		protected $action = 'demo_importer_plus_importer';

		/**
		 * Task
		 *
		 * Override this method to perform any actions required on each
		 * queue item. Return the modified item for further processing
		 * in the next pass through. Or, return false to remove the
		 * item from the queue.
		 *
		 * @param object $object Queue item object.
		 * @return mixed
		 */
		protected function task( $object ) {

			$process = $object['instance'];
			$method  = $object['method'];

			if ( 'import_page_builders' === $method ) {
				demo_importer_plus_error_log( '-------- Importing Page Builders --------' );
				update_site_option( 'demo-importer-plus-batch-status-string', 'Importing Page Builders', 'no' );
				$process->import_page_builders();
			} elseif ( 'import_categories' === $method ) {
				demo_importer_plus_error_log( '-------- Importing Tags --------' );
				update_site_option( 'demo-importer-plus-batch-status-string', 'Importing Tags', 'no' );
				$process->import_categories();
			} elseif ( 'import_sites' === $method ) {
				demo_importer_plus_error_log( '-------- Importing Sites --------' );
				$page = $object['page'];
				demo_importer_plus_error_log( 'Inside Batch ' . $page );
				update_site_option( 'demo-importer-plus-batch-status-string', 'Inside Batch ' . $page, 'no' );
				$process->import_sites( $page );
			} elseif ( 'import_blocks' === $method ) {
				demo_importer_plus_error_log( '-------- Importing Blocks --------' );
				$page = $object['page'];
				demo_importer_plus_error_log( 'Inside Batch ' . $page );
				update_site_option( 'demo-importer-plus-batch-status-string', 'Inside Batch ' . $page, 'no' );
				$process->import_blocks( $page );
			} elseif ( 'import_block_categories' === $method ) {
				demo_importer_plus_error_log( '-------- Importing Blocks Categories --------' );
				update_site_option( 'demo-importer-plus-batch-status-string', 'Importing Blocks Categories', 'no' );
				$process->import_block_categories();
			} elseif ( 'import_site_categories' === $method ) {
				demo_importer_plus_error_log( '-------- Importing Site Categories --------' );
				update_site_option( 'demo-importer-plus-batch-status-string', 'Importing Site Categories', 'no' );
				$process->import_site_categories();
			}

			return false;
		}

		/**
		 * Complete
		 *
		 * Override if applicable, but ensure that the below actions are
		 * performed, or, call parent::complete().
		 */
		protected function complete() {
			parent::complete();

			demo_importer_plus_error_log( esc_html__( 'All processes are complete', 'demo-importer-plus' ) );
			update_site_option( 'demo-importer-plus-batch-status-string', 'All processes are complete', 'no' );
			delete_site_option( 'demo-importer-plus-batch-status' );
			update_site_option( 'demo-importer-plus-batch-is-complete', 'yes', 'no' );

			do_action( 'demo_importer_plus_site_import_batch_complete' );

			remove_filter( 'elementor/files/allow_unfiltered_upload', '__return_true', 9898 );
		}

	}

endif;
