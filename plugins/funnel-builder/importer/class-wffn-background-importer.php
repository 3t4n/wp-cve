<?php
/**
 * Background Updater
 *
 * @version 1.7.4
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'WP_Async_Request', false ) ) {
	include_once dirname( dirname( __FILE__ ) ) . '/woofunnels/libraries/wp-async-request.php';
}

if ( ! class_exists( 'WP_Background_Process', false ) ) {
	include_once dirname( dirname( __FILE__ ) ) . '/woofunnels/libraries/wp-background-process.php';
}

if ( ! class_exists( 'WFFN_Background_Process', false ) ) {
	include_once dirname( dirname( __FILE__ ) ) . '/includes/class-wffn-background-process.php';
}

/**
 * WFFN_Background_Importer Class.
 * Based on WFFN_Background_Process concept
 */
if ( ! class_exists( 'WFFN_Background_Importer' ) ) {
	class WFFN_Background_Importer extends WFFN_Background_Process {


		/**
		 * Initiate new background process.
		 * WFFN_Background_Importer constructor.
		 */
		public function __construct() {
			$this->action = 'funnel_importer';
			parent::__construct();

		}


		/**
		 * Task
		 *
		 * Override this method to perform any actions required on each
		 * queue item. Return the modified item for further processing
		 * in the next pass through. Or, return false to remove the
		 * item from the queue.
		 *
		 * @param string $callback Update callback function.
		 *
		 * @return string|bool
		 * @SuppressWarnings(PHPMD.DevelopmentCodeFragment)
		 * @SuppressWarnings(PHPMD.ElseExpression)
		 */
		protected function task( $callback ) {

			$result = false;
			if ( is_callable( $callback ) ) {
				BWF_Logger::get_instance()->log( 'Running the callback: ' . print_r( $callback, true ), 'wffn_template_import' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
				$result = (bool) call_user_func( $callback );

				if ( $result ) {
					BWF_Logger::get_instance()->log( "Result: $result Need to run again the callback: " . print_r( $callback, true ), 'wffn_template_import' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
				} else {
					BWF_Logger::get_instance()->log( "Result: $result Finished running the callback: " . print_r( $callback, true ), 'wffn_template_import' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
				}
			} else {
				BWF_Logger::get_instance()->log( "Result: $result Could not find the callback: " . print_r( $callback, true ), 'wffn_template_import' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			}

			return $result ? $callback : false;
		}

		/**
		 * Complete
		 *
		 * Override if applicable, but ensure that the below actions are
		 * performed, or, call parent::complete().
		 */
		protected function complete() {
			delete_option( '_wffn_scheduled_funnel_id' );
			BWF_Logger::get_instance()->log( 'Background importing completed.', 'wffn_template_import' );
			do_action( 'wffn_importing_completed' );
			parent::complete();
		}
	}
}
