<?php
/**
 * Image Background Process
 *
 * @package Demo Importer Plus
 * @since 1.0.0
 */

if ( class_exists( 'WP_Background_Process' ) ) :

	/**
	 * Image Background Process
	 *
	 * @since 1.0.0
	 */
	class WP_Background_Process_Demo_Importer extends WP_Background_Process {

		/**
		 * Image Process
		 *
		 * @var string
		 */
		protected $action = 'image_process';

		/**
		 * Task
		 *
		 * Override this method to perform any actions required on each
		 * queue item. Return the modified item for further processing
		 * in the next pass through. Or, return false to remove the
		 * item from the queue.
		 *
		 * @param object $process Queue item object.
		 * @return mixed
		 */
		protected function task( $process ) {

			if ( method_exists( $process, 'import' ) ) {
				$process->import();
			}

			return false;
		}

		/**
		 * Complete
		 *
		 * Override if applicable, but ensure that the below actions are
		 * performed, or, call parent::complete().
		 *
		 * @since 1.0.0
		 */
		protected function complete() {

			parent::complete();

			Demo_Importer_plus_Sites_Importer_Log::add( 'Batch Process Complete!' );

			// Delete Log file.
			delete_option( 'demo_importer_plus_recent_import_log_file' );

			do_action( 'demo_importer_plus_image_import_complete' );

			remove_filter( 'elementor/files/allow_unfiltered_upload', '__return_true', 9898 );
		}

	}

endif;
