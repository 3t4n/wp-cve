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
	class WP_Background_Process_Demo_Importer_Single extends WP_Background_Process {

		/**
		 * Image Process
		 *
		 * @var string
		 */
		protected $action = 'demo_importer_plus_single_page';

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

			$page_id = $object['page_id'];

			$process = $object['instance'];

			if ( method_exists( $process, 'import_single_post' ) ) {
				$process->import_single_post( $page_id );
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

			demo_importer_plus_error_log( 'Complete' );

			parent::complete();

			do_action( 'demo_importer_plus_sites_image_import_complete' );

			remove_filter( 'elementor/files/allow_unfiltered_upload', '__return_true', 9898 );

		}

	}

endif;
