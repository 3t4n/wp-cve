<?php
/**
 * Ajax handler for user course completion.
 *
 * @since 1.8.3
 * @package Masteriyo\AjaxHandlers
 */

namespace Masteriyo\Addons\GoogleClassroomIntegration\AjaxHandlers;

use Masteriyo\Abstracts\AjaxHandler;
use Masteriyo\Enums\CourseProgressStatus;

class CourseCompletionAjaxHandler extends AjaxHandler {
	/**
	 * Ajax action name.
	 *
	 * @since 1.8.3
	 *
	 * @var string
	 */
	public $action = 'masteriyo_course_complete';

	/**
		 * Register ajax handler.
		 *
		 * @since 1.8.3
		 */
	public function register() {
		add_action( "wp_ajax_{$this->action}", array( $this, 'process' ) );
	}

	/**
	 * Process ajax handler review notice.
	 *
	 * @since 1.8.3
	 */
	public function process() {

		if ( ! isset( $_POST['nonce'] ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Nonce is required.', 'masteriyo' ),
				)
			);
		}
		if ( ! isset( $_POST['course_id'] ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'Course Id is required.', 'masteriyo' ),
				)
			);
		}

		try {
			if ( ! wp_verify_nonce( $_POST['nonce'], 'masteriyo_course_completion_nonce' ) ) {
				throw new \Exception( __( 'Invalid nonce. Maybe you should reload the page.', 'masteriyo' ) );
			}

			if ( ! is_user_logged_in() ) {
				wp_send_json_error( array( 'message' => __( 'You must be logged in to complete this action.', 'masteriyo' ) ) );
				return;
			}

			$course_id    = $_POST['course_id'];
			$current_user = masteriyo_get_current_user_id();

			global $wpdb;

			$activity_data = array(
				'item_id'         => $course_id,
				'user_id'         => $current_user,
				'activity_type'   => 'course_progress',
				'parent_id'       => 0,
				'activity_status' => CourseProgressStatus::COMPLETED,
				'created_at'      => current_time( 'mysql' ),
				'completed_at'    => current_time( 'mysql' ),
			);

			$table_name = $wpdb->prefix . 'masteriyo_user_activities';

			$query = "INSERT INTO $table_name (item_id, user_id, activity_type, parent_id, activity_status, created_at, completed_at)
								SELECT %d, %d, %s, %d, %s, %s, %s FROM DUAL
								WHERE NOT EXISTS (
										SELECT 1 FROM $table_name WHERE user_id = %d AND item_id = %d
								) LIMIT 1;";

			// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.PreparedSQL.NotPrepared
			$prepared_query = $wpdb->prepare(
				$query,
				$activity_data['item_id'],
				$activity_data['user_id'],
				$activity_data['activity_type'],
				$activity_data['parent_id'],
				$activity_data['activity_status'],
				$activity_data['created_at'],
				$activity_data['completed_at'],
				$activity_data['user_id'],
				$activity_data['item_id']
			);

			$wpdb->query( $prepared_query );
			// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.PreparedSQL.NotPrepared

			wp_send_json_success();

		} catch ( \Exception $e ) {
			wp_send_json_error(
				array(
					'message' => $e->getMessage(),
				)
			);
		}

	}
}
