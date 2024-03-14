<?php

if ( ! class_exists( 'TSM_Process_Tracking_Scripts' ) ) {

	class TSM_Process_Tracking_Scripts extends WP_Background_Process {

		/**
		 * @var string
		 */
		protected $action = 'process_tracking_scripts';
		/**
		 * Task
		 *
		 * Override this method to perform any actions required on each
		 * queue item. Return the modified item for further processing
		 * in the next pass through. Or, return false to remove the
		 * item from the queue.
		 *
		 * @param mixed $item Queue item to iterate over
		 *
		 * @return mixed
		 */
		protected function task( $script ) {
			$script_post = array(
				'post_type'    => 'r8_tracking_scripts',
				'post_title'   => $script->script_name,
				'post_content' => '',
				'post_status'  => 'publish',
				'meta_input'   => array(
					'r8_tsm_script_code'     => $script->script_code,
					'r8_tsm_active'          => $script->active ? 'active' : 'inactive',
					'r8_tsm_script_order'    => $script->order ? $script->order : 1,
					'r8_tsm_script_location' => $script->location,
					'r8_tsm_script_page'     => $script->page_id ? array( $script->page_id ) : array(),
				),
			);

			$post_id = wp_insert_post( $script_post );

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
			// Show notice to user or perform some other arbitrary task...

			delete_option( 'header_tracking_script_code' );
			delete_option( 'page_tracking_script_code' );
			delete_option( 'footer_tracking_script_code' );
			delete_option( 'tsm_is_processing' );
		}

	}

}
