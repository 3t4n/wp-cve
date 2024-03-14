<?php
/**
 * This file contains a class that is used to setup the LearnDash endpoints.
 *
 * @package learndash-reports-by-wisdmlabs
 */

require_once 'class-wrld-common-functions.php';
/**
 * Class that sets up all the LearnDash endpoints
 *
 * @author     WisdmLabs
 * @since      1.0.0
 */
class WRLD_Course_Progress_Info extends WRLD_Common_Functions {

	/**
	 * This static contains the number of points being assigned on course completion
	 *
	 * @var    Instance of WRLD_Revenue_API class
	 * @since  1.0.0
	 * @access private
	 */
	private static $instance = null;

	/**
	 * This static method is used to return a single instance of the class
	 *
	 * @since  1.0.0
	 * @access public
	 * @return Object
	 */
	public static function get_instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * This is a constructor which will be used to initialize required hooks
	 *
	 * @since  1.0.0
	 * @access private
	 * @see    initHook static method
	 */
	private function __construct() {
	}

	/**
	 * Gets the time spent by user in the quiz.
	 *
	 * Total of each started/complete time set.
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id   Optional. The ID of the user to get quiz time spent. Default 0.
	 * @param int $quiz_id Optional. The ID of the quiz to get time spent. Default 0.
	 *
	 * @return int Total number of seconds spent.
	 */
	public function learndash_get_user_quiz_attempts_time_spent( $user_id, $quiz_id ) {
		$total_time_spent = 0;

		$attempts = learndash_get_user_quiz_attempts( $user_id, $quiz_id );
		if ( ( ! empty( $attempts ) ) && ( is_array( $attempts ) ) ) {
			foreach ( $attempts as $attempt ) {
				if ( empty( $attempt->activity_completed ) || empty( $attempt->activity_started ) ) {
					continue;
				}
				if ( $attempt->activity_completed - $attempt->activity_started < 0 ) {
					continue;
				}
				$total_time_spent += ( $attempt->activity_completed - $attempt->activity_started );
			}
		}

		return $total_time_spent;
	}

	/**
	 * Gets the time spent by user in the course.
	 *
	 * Total of each started/complete time set.
	 *
	 * @since 1.0.0
	 *
	 * @param int $user_id   Optional. The ID of the user to get course time spent. Default 0.
	 * @param int $course_id Optional. The ID of the course to get time spent. Default 0.
	 *
	 * @return int Total number of seconds spent.
	 */
	public function learndash_get_user_course_attempts_time_spent( $user_id = 0, $course_id = 0 ) {
		$total_time_spent = 0;

		$attempts = learndash_get_user_course_attempts( $user_id, $course_id );

		// We should only ever have one entry for a user+course_id. But still we are returned an array of objects.
		if ( ( ! empty( $attempts ) ) && ( is_array( $attempts ) ) ) {
			foreach ( $attempts as $attempt ) {

				if ( ! empty( $attempt->activity_completed ) ) {
					// If the Course is complete then we take the time as the completed - started times.
					if ( empty( $attempt->activity_completed ) || empty( $attempt->activity_started ) ) {
						continue;
					}
					if ( $attempt->activity_completed - $attempt->activity_started < 0 ) {
						continue;
					}
					$total_time_spent += ( $attempt->activity_completed - $attempt->activity_started );
				} else {
					if ( empty( $attempt->activity_updated ) || empty( $attempt->activity_started ) ) {
						continue;
					}
					if ( $attempt->activity_updated - $attempt->activity_started < 0 ) {
						continue;
					}
					// But if the Course is not complete we calculate the time based on the updated timestamp.
					// This is updated on the course for each lesson, topic, quiz.
					$total_time_spent += ( $attempt->activity_updated - $attempt->activity_started );
				}
			}
		}

		return $total_time_spent;
	}

	public function sort_assoc_by( $field, &$array, $direction = 'asc' ) {
		usort(
			$array,
			function ( $item1, $item2 ) use ( $field ) {
				return $item2[ $field ] <=> $item1[ $field ];
			}
		);

		return true;
	}

	public function get_learner_activity_log() {
		$request_data = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING );
		$request_data = self::get_request_params( $request_data );
		$learner      = $request_data['learner'];
		$duration     = $request_data['duration'];
		$page         = empty( $request_data['page'] ) ? 1 : $request_data['page'];
		do_action( 'wpml_switch_language',  $_GET[ 'wpml_lang' ] ); // switch the content language
		$is_wrld_json_source = apply_filters('wrld_api_json_sorce' , false);
		if($is_wrld_json_source){
			$response = apply_filters('wrld_api_response_from_json_learner_activity_log',$request_data);
            $response['requestData'] = self::get_values_for_request_params( $request_data );
			return new WP_REST_Response(
				$response,
				200
			);
		}

		if ( 'custom' === $duration ) {
			$start_date = strtotime( $request_data['start_date'] );
			$end_date   = strtotime( $request_data['end_date'] );
		} else {
			$start_date = strtotime( gmdate( 'Y-m-d', strtotime( '-' . $duration ) ) );
			// $start_date = strtotime( '-5 years' );
			$end_date = current_time( 'timestamp' );
		}

		$user_role_access   = self::get_current_user_role_access();
		$accessible_courses = self::get_accessible_courses_for_the_user( get_current_user_id(), $user_role_access, 'learner_activity_log' );
		$accessible_users   = self::get_accessible_users_for_the_user( get_current_user_id(), $user_role_access, 'learner_activity_log' );
		$excluded_users     = get_option( 'exclude_users', array() );
		if ( empty( $excluded_users ) || ! defined( 'LDRP_PLUGIN_VERSION' ) ) {
			$excluded_users = array();
		}
		if ( ! is_null( $accessible_users ) && -1 != $accessible_users && empty( $accessible_users ) ) {
			return new WP_Error(
				'unauthorized',
				sprintf(/* translators: %s: custom label for course */
					__( 'You don\'t have access to any users', 'learndash-reports-by-wisdmlabs' )
				),
				array( 'requestData' => self::get_values_for_request_params( $request_data ) )
			);
		}

		if ( -1 === $accessible_courses && isset( $_GET[ 'wpml_lang' ] ) ) {
			$accessible_courses = get_posts(
				array(
					'post_type' => 'sfwd-courses',
					'suppress_filters' => 0,
					'posts_per_page' => -1,
					'fields' => 'ids'
				)
			);
		}

		if ( ! is_null( $accessible_courses ) && -1 != $accessible_courses && empty( $accessible_courses ) ) {
			return new WP_Error(
				'unauthorized',
				sprintf(/* translators: %s: custom label for course */
					__( 'You don\'t have access to this %s.', 'learndash-reports-by-wisdmlabs' ),
					\LearnDash_Custom_Label::label_to_lower( 'course' )
				),
				array( 'requestData' => self::get_values_for_request_params( $request_data ) )
			);
		}

		if ( ! empty( $learner ) && ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ) {
			$accessible_users = array_intersect( array( $learner ), $accessible_users );
		}

		if ( ! empty( $learner ) && ( is_null( $accessible_users ) || -1 == $accessible_users ) ) {
			$accessible_users = array( $learner );
		}
		// $accessible_users = array_diff( $accessible_users , $excluded_users );

		$table   = array();
		$data    = \WRLD_Quiz_Export_Db::get_learner_activity_log( $start_date, $end_date, $accessible_courses, $accessible_users, $excluded_users, $page );
		$showing = ( ( (int) $page - 1 ) * 5 ) + count( $data );
		$total   = \WRLD_Quiz_Export_Db::get_learner_activity_log_count( $start_date, $end_date, $accessible_courses, $accessible_users, $excluded_users );
		if ( ! empty( $data ) ) {
			foreach ( $data as $key => $entry ) {
				if ( empty( $entry['activity_type'] ) ) {
					$data[ $key ]['latest'] = $entry['activity_updated'];
					$data[ $key ]['status'] = __( 'Visited', 'learndash-reports-by-wisdmlabs' );
					$type                   = get_post_type( $entry['post_id'] );
					$post_type_object       = get_post_type_object( $type );
					if ( ( $post_type_object ) && ( is_a( $post_type_object, 'WP_Post_Type' ) ) ) {
						$type = $post_type_object->labels->singular_name;
					}
				} else {
					$data[ $key ]['latest'] = max( $entry['activity_completed'], $entry['activity_started'], $entry['activity_updated'] );
					$type                   = $data[ $key ]['activity_type'];
					$data[ $key ]['status'] = __( 'Completed', 'learndash-reports-by-wisdmlabs' );
					if ( 'access' === $type ) {
						$type                   = __( 'Course', 'learndash-reports-by-wisdmlabs' );
						$data[ $key ]['status'] = __( 'Enrolled', 'learndash-reports-by-wisdmlabs' );
					}
					if ( 'quiz' == $type && '1' == $data[ $key ]['activity_status'] ) {
						$data[ $key ]['status'] = __( 'Passed', 'learndash-reports-by-wisdmlabs' );
					} elseif ( 'quiz' == $type && '0' == $data[ $key ]['activity_status'] ) {
						$data[ $key ]['status'] = __( 'Failed', 'learndash-reports-by-wisdmlabs' );
					} elseif ( in_array( $type, array( 'course', 'lesson', 'topic' ) ) && '0' == $data[ $key ]['activity_status'] ) {
						$data[ $key ]['status'] = __( 'Started', 'learndash-reports-by-wisdmlabs' );
					}
				}
				/* translators: %s: Course Title */
				$data[ $key ]['course_id'] = sprintf( __( 'Course - %s', 'learndash-reports-by-wisdmlabs' ), get_the_title( $data[ $key ]['course_id'] ) );
				$data[ $key ]['post_id']   = sprintf( '%s - %s', ucfirst( $type ), get_the_title( $data[ $key ]['post_id'] ) );
				$user_data = get_userdata( $entry['user_id'] );
				$data[ $key ]['user_name'] = ! $user_data ? __('deleted user', 'learndash-reports-by-wisdmlabs' ) : $user_data->display_name;
			}
			$this->sort_assoc_by( 'latest', $data, 'desc' );
			$table = array();
			foreach ( $data as $key => $entry ) {
				$date             = gmdate( 'M j, Y', $data[ $key ]['latest'] );
				$table[ $date ][] = $data[ $key ];
			}
		}

		return new WP_REST_Response(
			array(
				'requestData' => self::get_values_for_request_params( $request_data ),
				'table'       => $table,
				'more_data'   => ( $total - $showing > 0 ) ? 'yes' : 'no',
				'total'       => $total,
				'showing'     => $showing,
			),
			200
		);
	}

	public function get_inactive_users_info() {
		$request_data = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING );
		$request_data = self::get_request_params( $request_data );
		$course_id    = $request_data['course'];
		$group_id     = $request_data['group'];
		$duration     = $request_data['duration'];
		$page         = empty( $request_data['page'] ) ? 1 : $request_data['page'];
		do_action( 'wpml_switch_language',  $_GET[ 'wpml_lang' ] ); // switch the content language
		if ( 'custom' === $duration ) {
			$start_date = strtotime( $request_data['start_date'] );
			$end_date   = strtotime( $request_data['end_date'] );
		} else {
			$start_date = strtotime( gmdate( 'Y-m-d', strtotime( '-' . $duration ) ) );
			// $start_date = strtotime( '-5 years' );
			$end_date = current_time( 'timestamp' );
		}

		$user_role_access   = self::get_current_user_role_access();
		$accessible_courses = self::get_accessible_courses_for_the_user( get_current_user_id(), $user_role_access, 'inactive_users' );
		$accessible_users   = self::get_accessible_users_for_the_user( get_current_user_id(), $user_role_access, 'inactive_users' );
		$excluded_users     = get_option( 'exclude_users', array() );
		if ( empty( $excluded_users ) || ! defined( 'LDRP_PLUGIN_VERSION' ) ) {
			$excluded_users = array();
		}
		if ( ! is_null( $accessible_users ) && -1 != $accessible_users && empty( $accessible_users ) ) {
			return new WP_Error(
				'unauthorized',
				sprintf(/* translators: %s: custom label for course */
					__( 'You don\'t have access to any users', 'learndash-reports-by-wisdmlabs' )
				),
				array( 'requestData' => self::get_values_for_request_params( $request_data ) )
			);
		}
		if ( ! is_null( $accessible_courses ) && -1 != $accessible_courses && empty( $accessible_courses ) ) {
			return new WP_Error(
				'unauthorized',
				sprintf(/* translators: %s: custom label for course */
					__( 'You don\'t have access to this %s.', 'learndash-reports-by-wisdmlabs' ),
					\LearnDash_Custom_Label::label_to_lower( 'course' )
				),
				array( 'requestData' => self::get_values_for_request_params( $request_data ) )
			);
		}

		if ( ! empty( $group_id ) ) {
			if ( get_option( 'migrated_group_access_data', false ) ) {
				$group_users = \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] );
			} else {
				$group_users = self::get_ld_group_user_ids( $request_data['group'] );
			}
			if ( empty( $group_users ) ) {
				return new WP_Error(
					'unauthorized',
					sprintf(
						__( 'No Users enrolled to this group', 'learndash-reports-by-wisdmlabs' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}
			$accessible_users = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $group_users, $accessible_users ) : $group_users;
			$group_courses    = self::get_list_of_courses_in_groups( array( $group_id ) );
			if ( empty( $group_courses ) ) {
				return new WP_Error(
					'unauthorized',
					sprintf(/* translators: %s: custom label for courses */
						__( 'No %s present in this Group.', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::label_to_lower( 'courses' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}
		}
		if ( isset( $group_courses ) && ( ! is_null( $accessible_courses ) && -1 != $accessible_courses ) ) {
			$accessible_courses = array_intersect( $group_courses, $accessible_courses );
		}

		if ( isset( $group_courses ) && ( is_null( $accessible_courses ) || -1 == $accessible_courses ) ) {
			$accessible_courses = $group_courses;
		}

		if ( ! empty( $course_id ) && ( ! is_null( $accessible_courses ) && -1 != $accessible_courses ) ) {
			$accessible_courses = array_intersect( array( $course_id ), $accessible_courses );
		}

		if ( isset( $group_courses ) && ( is_null( $accessible_courses ) || -1 == $accessible_courses ) ) {
			$accessible_courses = $group_courses;
		}

		if ( ! empty( $course_id ) && ( is_null( $accessible_courses ) || -1 == $accessible_courses ) ) {
			$accessible_courses = array( $course_id );
		}
		// $accessible_users = array_diff( $accessible_users , $excluded_users );
		$data           = \WRLD_Quiz_Export_Db::get_inactive_users_info( $start_date, $end_date, $accessible_courses, $accessible_users, $excluded_users, $page );
		$showing        = ( ( (int) $page - 1 ) * 10 ) + count( $data );
		$total          = \WRLD_Quiz_Export_Db::get_inactive_users_info_count( $start_date, $end_date, $accessible_courses, $accessible_users, $excluded_users );
		$formatted_data = array();
		if ( ! empty( $data ) ) {
			foreach ( $data as $key => $entry ) {
				// if ( $entry['updated'] >= max( $entry['activity_completed'], $entry['activity_updated'], $entry['activity_started'] ) ) {
				// 	$user_id   = $entry['user'];
				// 	$post_id   = $entry['post'];
				// 	$course_id = $entry['course'];
				// 	$latest    = $entry['updated'];
				// } else {
					$user_id   = $entry['user_id'];
					$post_id   = $entry['post_id'];
					$course_id = $entry['course_id'];
					if ( array_key_exists( 'great', $entry ) ) {
						$latest = $entry['great'];
					} else {
						$latest = $entry['activity_updated'];
					}
					// $latest    = max( $entry['activity_completed'], $entry['activity_updated'], $entry['activity_started'] );
					
				// }
				$user_data                             = get_userdata( $user_id );
				$formatted_data[ $key ]['name']        = $user_data->display_name;
				$formatted_data[ $key ]['email']       = ( strlen( $user_data->user_email ) > 40 ) ? substr( $user_data->user_email, 0, 37 ) . '...' : $user_data->user_email;
				$formatted_data[ $key ]['latest']      = $latest;
				$formatted_data[ $key ]['last_access'] = gmdate( 'M d, Y', $latest );
				unset( $user_id );
				unset( $post_id );
				unset( $course_id );
				unset( $latest );
			}
			$this->sort_assoc_by( 'latest', $formatted_data, 'desc' );
			foreach ( $formatted_data as $key => $sorted ) {
				unset( $formatted_data[ $key ]['latest'] );
			}
		}

		$table = $formatted_data;
		return new WP_REST_Response(
			array(
				'requestData' => self::get_values_for_request_params( $request_data ),
				'table'       => $table,
				'more_data'   => ( $total - $showing > 0 ) ? 'yes' : 'no',
				'total'       => $total,
				'showing'     => $showing,
			),
			200
		);
	}



	/**
	 * This method is used as a API callback to return day-to-day enrollments data.
	 *
	 * @return WP_REST_Response/WP_Error Objects.
	 */
	public function get_daily_enrollments() {
		global $wpdb;
		$request_data = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING );
		$request_data = self::get_request_params( $request_data );
		$duration     = self::get_duration_data( $request_data['start_date'], $request_data['end_date'], 'Y-m-d' );
		do_action( 'wpml_switch_language',  $_GET[ 'wpml_lang' ] ); // switch the content language

		$is_wrld_json_source = apply_filters('wrld_api_json_sorce' , false);
		if($is_wrld_json_source){
			$response = apply_filters('wrld_api_response_from_json_daily_enrollment',$duration);
            $response['requestData'] = self::get_values_for_request_params( $request_data );
			return new WP_REST_Response(
				$response,
				200
			);
		}

		// Loop through all the days in the required time-period.
		$begin = new DateTime( $duration['start_date'] );
		$begin = $begin->modify( '+1 day' );
		$end   = new DateTime( $duration['end_date'] );
		$end   = $end->modify( '+1 day' );

		$interval = new DateInterval( 'P1D' );
		$period   = new DatePeriod( $begin, $interval, $end );

		$enrollments      = array();
		$total_enrollment = 0;
		$days             = 0;
		foreach ( $period as $date ) {
			$days++;
			// Get the timestamp of the target date's start and end.
			$target_date = $date->format( 'Y-m-d' );
			$day_start   = $date->format( 'Y-m-d 00:00:00' );
			$day_end     = $date->format( 'Y-m-d 23:59:59' );

			// Fetch enrollment activity between day start and day end.
			// $course_enrolls = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT( * ) FROM ' . esc_sql( LDLMS_DB::get_table_name( 'user_activity' ) ) . ' WHERE activity_type=%s AND activity_started BETWEEN %d AND %d LIMIT 1', 'access', $day_start, $day_end ) );
			// $course_enrolls = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) as count FROM ' . $wpdb->users . ' as users  JOIN ' . $wpdb->usermeta . " as usermeta ON users.id=usermeta.user_id WHERE users.user_registered>=%s AND users.user_registered<=%s AND usermeta.meta_key LIKE '%_capabilities' AND usermeta.meta_value NOT LIKE %s", $day_start, $day_end, '%administrator%' ) );
			$course_enrolls   = WRLD_Revenue_API::get_users_registered_between( $day_start, $day_end );
			$total_enrollment = $total_enrollment + $course_enrolls;
			$enrollments[]    = array(
				'count' => $course_enrolls,
				'date'  => $target_date,
			);
		}
		// Calculate average Enrollment.
		$average_enrollment = round( $total_enrollment / $days );
		return new WP_REST_Response(
			array(
				'requestData'       => self::get_values_for_request_params( $request_data ),
				'enrollments'       => $enrollments,
				/* translators: %1d:Average Enrollments*/
				'averageEnrollment' => 1 === $average_enrollment ? sprintf( __( '%d Learner', 'learndash-reports-by-wisdmlabs' ), $average_enrollment ) : sprintf( __( '%d Learners', 'learndash-reports-by-wisdmlabs' ), $average_enrollment ),
			),
			200
		);
	}

	/**
	 * This method returns the pending assignments count.
	 *
	 * @return WP_REST_Response/WP_Error Objects.
	 */
	public function get_pending_assignments_info() {
		do_action( 'wpml_switch_language',  $_GET[ 'wpml_lang' ] ); // switch the content language
		$pending_count = learndash_get_assignments_pending_count(
			array(
				'reports_api' => 1,
			)
		);
		if ( empty( $pending_count ) || ! defined( 'LDRP_PLUGIN_VERSION' ) ) {
			$pending_count = 0;
		}
		return new WP_REST_Response(
			array(
				'pendingAssignments' => $pending_count,
			),
			200
		);
	}

	/**
	 * This method returns the Quiz completion rate based on the input parameters.
	 *
	 * @return WP_REST_Response/WP_Error Objects.
	 */
	public function get_quiz_completion_rate() {
		global $wpdb;
		// Get Inputs.
		$request_data = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING );
		$request_data = self::get_request_params( $request_data );
		unset( $request_data['start_date'] );
		unset( $request_data['end_date'] );
		$time_spent_row = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT id, option_value, expires_on, created_on FROM {$wpdb->prefix}wrld_cached_entries WHERE object_type=%s AND object_id=%d AND option_name=%s",
				'user',
				get_current_user_id(),
				'wrld_quiz_completion_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic']
			)
		);
		if ( empty( $time_spent_row ) || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $time_spent_row->expires_on <= current_time( 'timestamp' ) || $request_data['disable_cache'] ) {
			if ( ! empty( $time_spent_row ) ) {
				$wpdb->delete(
					$wpdb->prefix . 'wrld_cached_entries',
					array(
						'id' => $time_spent_row->id
					),
					array(
						'%d'
					)
				);
			}
			do_action( 'wpml_switch_language',  $_GET[ 'wpml_lang' ] ); // switch the content language
			$user_role_access   = self::get_current_user_role_access();
			$accessible_courses = self::get_accessible_courses_for_the_user( get_current_user_id(), $user_role_access, 'quiz_completion_rate' );
			$accessible_users   = self::get_accessible_users_for_the_user( get_current_user_id(), $user_role_access, 'quiz_completion_rate' );
			$excluded_users     = get_option( 'exclude_users', array() );
			if ( empty( $excluded_users ) || ! defined( 'LDRP_PLUGIN_VERSION' ) ) {
				$excluded_users = array();
			}

			$is_wrld_json_source = apply_filters('wrld_api_json_sorce' , false);
			if($is_wrld_json_source){
				$response = apply_filters('wrld_api_response_from_json_quiz_completion_rate',$request_data);
	            $response['requestData'] = self::get_values_for_request_params( $request_data );
				return new WP_REST_Response(
					$response,
					200
				);
			}
			if ( isset( $request_data['group'] ) && ! empty( $request_data['group'] ) ) {
				$group_users = get_transient( 'wrld_group_students_data_' . $request_data['group'] );
				if ( false === $group_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
					if ( empty( $group_users ) ) {
						$group_users = array();
					}
					// Get all students for a course.
					if ( get_option( 'migrated_group_access_data', false ) ) {
						$group_users = array_unique( array_merge( $group_users, \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] ) ) );
					} else {
						$group_users = self::get_ld_group_user_ids( $request_data['group'] );
					}
					delete_transient( 'wrld_group_students_data_' . $request_data['group'] );
					set_transient( 'wrld_group_students_data_' . $request_data['group'], $group_users, 1 * HOUR_IN_SECONDS );
				}
				$accessible_users = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $group_users, $accessible_users ) : $group_users;
			}
			$category_query = array();
			// Learner Filters.
			if ( isset( $request_data['learner'] ) && ! empty( $request_data['learner'] ) ) {
				$courses = learndash_user_get_enrolled_courses( $request_data['learner'], array(), false );
				if ( isset( $accessible_courses ) && -1 != $accessible_courses ) {
					$courses = array_intersect( $courses, $accessible_courses );
				}
				if ( empty( $courses ) ) {
					$current_timestamp = current_time( 'timestamp' );
					$response = new WP_Error(
						'no-data',
						sprintf(
							/* translators: %s: custom label for courses */
							__( 'No accessible %s found', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::get_label( 'courses' )
						),
						array( 'requestData' => self::get_values_for_request_params( $request_data ), 'updated_on' => $current_timestamp )
					);
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_quiz_completion_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
							'option_value' => maybe_serialize( $response ),
							'object_id'    => get_current_user_id(),
							'object_type'  => 'user',
							'created_on'   => $current_timestamp,
							'expires_on'   => $current_timestamp + DAY_IN_SECONDS
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d',
							'%d',
						)
					);
					$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
					return $response;
				}
				$quiz_count       = 0;
				$total_percentage = 0;
				$course_data      = array();
				$completed_count  = 0;
				foreach ( $courses as $course_id ) {
					$quizzes    = learndash_course_get_children_of_step( $course_id, $course_id, 'sfwd-quiz', 'ids', true );
					$quiz_count = $quiz_count + count( $quizzes );
					if ( empty( $quizzes ) ) {
						continue;
					}
					foreach ( $quizzes as $quiz ) {
						$percentage = 0;
						if ( learndash_is_quiz_complete( $request_data['learner'], $quiz, $course_id ) ) {
							$percentage = 100;
							$completed_count++;
						}
						$total_percentage = $total_percentage + $percentage;

						$course_data[ $quiz ] = array(
							'average_completion' => $percentage,
							'title'              => get_the_title( $quiz ),
						);
					}
				}
				if ( $quiz_count > 0 ) {
					$average_completion = floatval( number_format( $total_percentage / $quiz_count, 2, '.', '' ) );// Cast to integer if no decimals.
				} else {
					$average_completion = 0;
				}
				$response = array(
					'requestData'               => self::get_values_for_request_params( $request_data ),
					'average_completion'        => $average_completion,
					'total_quiz_completion'     => $completed_count,
					'total_quizzes'             => $quiz_count,
					'coursewise_statistics_new' => $course_data,
				);
				$current_timestamp = current_time( 'timestamp' );
				$wpdb->insert(
					$wpdb->prefix . 'wrld_cached_entries',
					array(
						'option_name'  => 'wrld_quiz_completion_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
						'option_value' => maybe_serialize( $response ),
						'object_id'    => get_current_user_id(),
						'object_type'  => 'user',
						'created_on'   => $current_timestamp,
						'expires_on'   => $current_timestamp + DAY_IN_SECONDS
					),
					array(
						'%s',
						'%s',
						'%d',
						'%s',
						'%d',
						'%d',
					)
				);
				$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
				$response['updated_on'] = $updated_on;
				return new WP_REST_Response(
					$response,
					200
				);
			} elseif ( isset( $request_data['course'] ) && ! empty( $request_data['course'] ) ) {
				if ( isset( $accessible_courses ) && -1 != $accessible_courses ) {
					if ( ! in_array( $request_data['course'], $accessible_courses ) ) {
						$current_timestamp = current_time( 'timestamp' );
						$response = new WP_Error(
							'unauthorized',
							sprintf(/* translators: %s: custom label for course */
								__( 'You don\'t have access to this %s.', 'learndash-reports-by-wisdmlabs' ),
								\LearnDash_Custom_Label::label_to_lower( 'course' )
							),
							array( 'requestData' => self::get_values_for_request_params( $request_data ), 'updated_on' => $current_timestamp )
						);
						$wpdb->insert(
							$wpdb->prefix . 'wrld_cached_entries',
							array(
								'option_name'  => 'wrld_quiz_completion_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
								'option_value' => maybe_serialize( $response ),
								'object_id'    => get_current_user_id(),
								'object_type'  => 'user',
								'created_on'   => $current_timestamp,
								'expires_on'   => $current_timestamp + DAY_IN_SECONDS
							),
							array(
								'%s',
								'%s',
								'%d',
								'%s',
								'%d',
								'%d',
							)
						);
						$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
						return $response;
					}
				}
				$course        = get_post( $request_data['course'] );
				$lesson        = $request_data['lesson'];
				$topic         = $request_data['topic'];
				$child_step_id = $course->ID;
				$child_step_id = ! empty( $lesson ) ? (int) $lesson : $child_step_id;
				$child_step_id = ! empty( $topic ) ? (int) $topic : $child_step_id;
				// Check for valid course.
				if ( empty( $course ) ) {
					$current_timestamp = current_time( 'timestamp' );
					$response = new WP_Error(
						'invalid-input',
						sprintf(/* translators: %s: custom label for course */
							__( '%s doesn\'t exist', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::get_label( 'course' )
						),
						array( 'requestData' => self::get_values_for_request_params( $request_data ), 'updated_on' => $current_timestamp )
					);
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_quiz_completion_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
							'option_value' => maybe_serialize( $response ),
							'object_id'    => get_current_user_id(),
							'object_type'  => 'user',
							'created_on'   => $current_timestamp,
							'expires_on'   => $current_timestamp + DAY_IN_SECONDS
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d',
							'%d',
						)
					);
					$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
					return $response;
				}
				$course_price_type = learndash_get_course_meta_setting( $course->ID, 'course_price_type' );
				if ( 'open' === $course_price_type ) {
					$current_timestamp = current_time( 'timestamp' );
					$response = new WP_Error(
						'no-data',
						sprintf(/* translators: %s: custom label for courses */
							__( 'Reports for open %s are not accessible for the time-being', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::get_label( 'courses' )
						),
						array( 'requestData' => self::get_values_for_request_params( $request_data ), 'updated_on' => $current_timestamp )
					);
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_quiz_completion_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
							'option_value' => maybe_serialize( $response ),
							'object_id'    => get_current_user_id(),
							'object_type'  => 'user',
							'created_on'   => $current_timestamp,
							'expires_on'   => $current_timestamp + DAY_IN_SECONDS
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d',
							'%d',
						)
					);
					$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
					return $response;
				}
				$students = get_transient( 'wrld_course_students_data_' . $course->ID );
				if ( false === $students || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
				// Get all students for a course.
					if ( get_option( 'migrated_course_access_data', false ) ) {
						$students = \WRLD_Quiz_Export_Db::instance()->get_users_for_course( $course->ID );
					} else {
						$students = learndash_get_users_for_course( $course->ID, array(), false ); // Third argument is $exclude_admin.
					}
					$students = is_array( $students ) ? $students : $students->get_results();
					delete_transient( 'wrld_course_students_data_' . $course->ID );
					set_transient( 'wrld_course_students_data_' . $course->ID, $students, 1 * HOUR_IN_SECONDS );
				}
				$students = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, $students ) : $students;
				$students = array_diff( $students , $excluded_users );
				// Check if any students enrolled.
				if ( empty( $students ) ) {
					$current_timestamp = current_time( 'timestamp' );
					$response = new WP_Error(
						'no-data',
						__( 'No Students enrolled', 'learndash-reports-by-wisdmlabs' ),
						array( 'requestData' => self::get_values_for_request_params( $request_data ), 'updated_on' => $current_timestamp )
					);
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_quiz_completion_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
							'option_value' => maybe_serialize( $response ),
							'object_id'    => get_current_user_id(),
							'object_type'  => 'user',
							'created_on'   => $current_timestamp,
							'expires_on'   => $current_timestamp + DAY_IN_SECONDS
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d',
							'%d',
						)
					);
					$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
					return $response;
				}

				$quizzes       = learndash_course_get_children_of_step( $course->ID, $child_step_id, 'sfwd-quiz', 'ids', true );
				$quiz_count    = count( $quizzes );
				$student_count = count( $students );
				if ( empty( $quizzes ) ) {
					$current_timestamp = current_time( 'timestamp' );
					$response = new WP_Error(
						'no-data',
						sprintf(/* translators: %s: custom label for quizzes */
							__( 'No %s found for the selected filters', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::get_label( 'quizzes' )
						),
						array( 'requestData' => self::get_values_for_request_params( $request_data ), 'updated_on' => $current_timestamp )
					);
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_quiz_completion_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
							'option_value' => maybe_serialize( $response ),
							'object_id'    => get_current_user_id(),
							'object_type'  => 'user',
							'created_on'   => $current_timestamp,
							'expires_on'   => $current_timestamp + DAY_IN_SECONDS
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d',
							'%d',
						)
					);
					$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
					return $response;
				}
				$completed_count = 0;
				foreach ( $students as $student ) {
					$completed = 0;
					foreach ( $quizzes as $quiz ) {
						if ( learndash_is_quiz_complete( $student, $quiz, $course->ID ) ) {
							$completed++;
						}
					}
					$quiz_completion_rate_per_student[] = $completed / $quiz_count;
					if ( $quiz_count === $completed ) {
						$completed_count++;
					}
				}
				$average_completion = floatval( number_format( ( $completed_count / $student_count ) * 100, 2, '.', '' ) );// Cast to integer if no decimals.
				$completed_count    = $completed_count;
				$incomplete_count   = $student_count - $completed_count;
				$response = array(
					'requestData'              => self::get_values_for_request_params( $request_data ),
					'completed_count'          => $completed_count,
					'incomplete_count'         => $incomplete_count,
					'average_completion'       => $average_completion,
					'learner_count'            => $student_count,
					'learner_completion_count' => $completed_count,
					'quiz_count'               => $quiz_count,
				);
				$current_timestamp = current_time( 'timestamp' );
				$wpdb->insert(
					$wpdb->prefix . 'wrld_cached_entries',
					array(
						'option_name'  => 'wrld_quiz_completion_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
						'option_value' => maybe_serialize( $response ),
						'object_id'    => get_current_user_id(),
						'object_type'  => 'user',
						'created_on'   => $current_timestamp,
						'expires_on'   => $current_timestamp + DAY_IN_SECONDS
					),
					array(
						'%s',
						'%s',
						'%d',
						'%s',
						'%d',
						'%d',
					)
				);
				$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
				$response['updated_on'] = $updated_on;
				return new WP_REST_Response(
					$response,
					200
				);
			} elseif ( isset( $request_data['category'] ) && ! empty( $request_data['category'] ) ) {
				// Check if course category enabled.
				if ( ! taxonomy_exists( 'ld_course_category' ) ) {
					$current_timestamp = current_time( 'timestamp' );
					$response = new WP_Error(
						'invalid-input',
						sprintf(/* translators: %s: custom label for course */
							__( '%s Category disabled. Please contact admin.', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::get_label( 'course' )
						),
						array( 'requestData' => self::get_values_for_request_params( $request_data ), 'updated_on' => $current_timestamp )
					);
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_quiz_completion_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
							'option_value' => maybe_serialize( $response ),
							'object_id'    => get_current_user_id(),
							'object_type'  => 'user',
							'created_on'   => $current_timestamp,
							'expires_on'   => $current_timestamp + DAY_IN_SECONDS
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d',
							'%d',
						)
					);
					$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
					return $response;
				}
				// Check if valid category passed.
				if ( ! is_object( get_term_by( 'id', $request_data['category'], 'ld_course_category' ) ) ) {
					$current_timestamp = current_time( 'timestamp' );
					$response = new WP_Error(
						'invalid-input',
						sprintf(/* translators: %s: custom label for course */
							__( '%s Category doesn\'t exist', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::get_label( 'course' )
						),
						array( 'requestData' => self::get_values_for_request_params( $request_data ), 'updated_on' => $current_timestamp )
					);
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_quiz_completion_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
							'option_value' => maybe_serialize( $response ),
							'object_id'    => get_current_user_id(),
							'object_type'  => 'user',
							'created_on'   => $current_timestamp,
							'expires_on'   => $current_timestamp + DAY_IN_SECONDS
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d',
							'%d',
						)
					);
					$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
					return $response;
				}
				$category_query = array(
					array(
						'taxonomy'         => 'ld_course_category',
						'field'            => 'term_id',
						'terms'            => $request_data['category'], // Where term_id of Term 1 is "1".
						'include_children' => false,
					),
				);
			} elseif ( isset( $request_data['group'] ) && ! empty( $request_data['group'] ) ) {
				$group_courses      = learndash_group_enrolled_courses( $request_data['group'] );
				$accessible_courses = ( ! is_null( $accessible_courses ) && -1 != $accessible_courses ) ? array_intersect( $group_courses, $accessible_courses ) : $group_courses;
				$group_users = get_transient( 'wrld_group_students_data_' . $request_data['group'] );
				if ( false === $group_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
					if ( empty( $group_users ) ) {
						$group_users = array();
					}
					// Get all students for a course.
					if ( get_option( 'migrated_group_access_data', false ) ) {
						$group_users = array_unique( array_merge( $group_users, \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] ) ) );
					} else {
						$group_users = self::get_ld_group_user_ids( $request_data['group'] );
					}
					delete_transient( 'wrld_group_students_data_' . $request_data['group'] );
					set_transient( 'wrld_group_students_data_' . $request_data['group'], $group_users, 1 * HOUR_IN_SECONDS );
				}
				if ( empty( $group_users ) ) {
					$group_users = array();
				}
				$accessible_users   = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $group_users, $accessible_users ) : $group_users;
			}
			$query_args = array(
				'post_type'      => 'sfwd-courses',
				'posts_per_page' => '-1',
				'post__in'       => -1 == $accessible_courses ? null : $accessible_courses,
				'suppress_filters' => 0,
			);

			if ( ! empty( $category_query ) ) {
				$query_args['tax_query'] = $category_query;
			}
			$courses     = get_posts( $query_args );
			$course_data = array();
			// Check if any courses present in the category.
			if ( empty( $courses ) || ( ! is_null( $accessible_courses ) && -1 != $accessible_courses && empty( $accessible_courses ) ) ) {
				$current_timestamp = current_time( 'timestamp' );
				$response = new WP_Error(
					'no-data',
					sprintf(/* translators: %s: custom label for courses */
						__( 'No %s data found', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'quiz' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ), 'updated_on' => $current_timestamp )
				);
				$wpdb->insert(
					$wpdb->prefix . 'wrld_cached_entries',
					array(
						'option_name'  => 'wrld_quiz_completion_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
						'option_value' => maybe_serialize( $response ),
						'object_id'    => get_current_user_id(),
						'object_type'  => 'user',
						'created_on'   => $current_timestamp,
						'expires_on'   => $current_timestamp + DAY_IN_SECONDS
					),
					array(
						'%s',
						'%s',
						'%d',
						'%s',
						'%d',
						'%d',
					)
				);
				$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
				return $response;
			}
			// Average Completion percentage count variables.
			$course_count                    = count( $courses );
			$universal_quiz_completion_count = 0;
			$universal_quiz_count            = 0;
			$course_data                     = array();
			foreach ( $courses as $course ) {
				$course_price_type = learndash_get_course_meta_setting( $course->ID, 'course_price_type' );
				if ( 'open' === $course_price_type ) {
					continue;
				}
				$students = get_transient( 'wrld_course_students_data_' . $course->ID );
				if ( false === $students || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
				// Get all students for a course.
					if ( get_option( 'migrated_course_access_data', false ) ) {
						$students = \WRLD_Quiz_Export_Db::instance()->get_users_for_course( $course->ID );
					} else {
						$students = learndash_get_users_for_course( $course->ID, array(), false ); // Third argument is $exclude_admin.
					}
					$students = is_array( $students ) ? $students : $students->get_results();
					delete_transient( 'wrld_course_students_data_' . $course->ID );
					set_transient( 'wrld_course_students_data_' . $course->ID, $students, 1 * HOUR_IN_SECONDS );
				}
				$students = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, $students ) : $students;
				$students = array_diff( $students , $excluded_users );
				if ( empty( $students ) ) {
					continue;
				}

				$quizzes               = learndash_course_get_children_of_step( $course->ID, $course->ID, 'sfwd-quiz', 'ids', true );
				$quiz_count            = count( $quizzes );
				$student_count         = count( $students );
				$universal_quiz_count += $quiz_count;
				// If no students in the course then the course has 0 percent completion.
				if ( empty( $quizzes ) ) {
					continue;
				}
				$completed_count = 0;
				foreach ( $students as $student ) {
					$completed = 0;
					foreach ( $quizzes as $quiz ) {
						if ( learndash_is_quiz_complete( $student, $quiz, $course->ID ) ) {
							$completed++;
							$universal_quiz_completion_count++;
						}
					}
					$quiz_completion_rate_per_student[] = $completed / $quiz_count;
					if ( $quiz_count === $completed ) {
						$completed_count++;
					}
				}
				$average_completion         = floatval( number_format( array_sum( $quiz_completion_rate_per_student ) / $student_count, 2, '.', '' ) );// Cast to integer if no decimals.
				$completed_count            = $completed_count;
				$incomplete_count           = $student_count - $completed_count;
				$course_data[ $course->ID ] = array(
					'completed_count'          => $completed_count,
					'incomplete_count'         => $incomplete_count,
					'average_completion'       => $average_completion,
					'learner_count'            => $student_count,
					'learner_completion_count' => $completed_count,
					'title'                    => get_the_title( $course->ID ),
				);
			}
			$average_completion       = 0;
			$learner_completion_count = 0;
			if ( empty( $course_data ) ) {
				$current_timestamp = current_time( 'timestamp' );
				$response = new WP_Error(
					'no-data',
					sprintf(/* translators: %s: custom label for courses */
						__( 'No %s data found', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'quiz' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ), 'updated_on' => $current_timestamp )
				);
				$wpdb->insert(
					$wpdb->prefix . 'wrld_cached_entries',
					array(
						'option_name'  => 'wrld_quiz_completion_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
						'option_value' => maybe_serialize( $response ),
						'object_id'    => get_current_user_id(),
						'object_type'  => 'user',
						'created_on'   => $current_timestamp,
						'expires_on'   => $current_timestamp + DAY_IN_SECONDS
					),
					array(
						'%s',
						'%s',
						'%d',
						'%s',
						'%d',
						'%d',
					)
				);
				$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
				return $response;
			}
			foreach ( $course_data as $course_id => $course_info ) {
				$average_completion       = $average_completion + $course_info['average_completion'];
				$learner_completion_count = $learner_completion_count + $course_info['learner_completion_count'];
			}
			$average_completion = floatval( number_format( $average_completion / count( $course_data ), 2, '.', '' ) );// Cast to integer if no decimals.

			$response = array(
				'requestData'              => self::get_values_for_request_params( $request_data ),
				'average_completion'       => $average_completion,
				'learner_completion_count' => $learner_completion_count,
				'total_quiz_completion'    => $universal_quiz_completion_count,
				'total_quizzes'            => $universal_quiz_count,
				'coursewise_statistics'    => $course_data,
			);
			
			$response = apply_filters('wrld_api_response_to_excel',$response);

			$current_timestamp = current_time( 'timestamp' );
			$wpdb->insert(
				$wpdb->prefix . 'wrld_cached_entries',
				array(
					'option_name'  => 'wrld_quiz_completion_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
					'option_value' => maybe_serialize( $response ),
					'object_id'    => get_current_user_id(),
					'object_type'  => 'user',
					'created_on'   => $current_timestamp,
					'expires_on'   => $current_timestamp + DAY_IN_SECONDS
				),
				array(
					'%s',
					'%s',
					'%d',
					'%s',
					'%d',
					'%d',
				)
			);
			$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
			$response['updated_on'] = $updated_on;
			return new WP_REST_Response(
				$response,
				200
			);
		}
		$updated_on = date_i18n( 'Y-m-d H:i:s', $time_spent_row->created_on );
		$response = maybe_unserialize( $time_spent_row->option_value );
		if ( is_array( $response ) ) {
			$response['updated_on'] = $updated_on;
			return new WP_REST_Response(
				$response,
				200
			);
		} else {
			return $response;
		}
	}

	/**
	 * This method returns quiz passing rate for quizzes of a course/lesson/topic.
	 *
	 * @return WP_REST_Response/WP_Error Objects.
	 */
	public function get_quiz_passing_rate() {
		// Get Inputs.
		$request_data       = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING );
		$request_data       = self::get_request_params( $request_data );
		$duration           = self::get_duration_data( $request_data['start_date'], $request_data['end_date'], 'Y-m-d H:i:s' );

		$is_wrld_json_source = apply_filters('wrld_api_json_sorce' , false);
		if($is_wrld_json_source){
			$response = apply_filters('wrld_api_response_from_json_learner_pass_rate',$request_data);
            $response['requestData'] = self::get_values_for_request_params( $request_data );
			return new WP_REST_Response(
				$response,
				200
			);
		}
		do_action( 'wpml_switch_language',  $_GET[ 'wpml_lang' ] ); // switch the content language
		$user_role_access   = self::get_current_user_role_access();
		$accessible_courses = self::get_accessible_courses_for_the_user( get_current_user_id(), $user_role_access, 'quiz_passing_rate' );
		$accessible_users   = self::get_accessible_users_for_the_user( get_current_user_id(), $user_role_access, 'quiz_passing_rate' );
		if ( isset( $request_data['group'] ) && ! empty( $request_data['group'] ) ) {
			$group_users = get_transient( 'wrld_group_students_data_' . $request_data['group'] );
			if ( false === $group_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
				// Get all students for a course.
				if ( get_option( 'migrated_group_access_data', false ) ) {
					// $group_users = array_unique( array_merge( $group_users, \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] ) ) );
					$group_users = \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] );
				} else {
					$group_users = self::get_ld_group_user_ids( $request_data['group'] );
				}
				delete_transient( 'wrld_group_students_data_' . $request_data['group'] );
				set_transient( 'wrld_group_students_data_' . $request_data['group'], $group_users, 1 * HOUR_IN_SECONDS );
			}
			if ( empty( $group_users ) ) {
				$group_users = array();
			}
			$accessible_users = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $group_users, $accessible_users ) : $group_users;
		}
		$category_query = array();

		$attempts   = array();
		$start_date = $duration['start_date'];
		$end_date   = $duration['end_date'];
		if ( empty( $start_date ) || empty( $end_date ) ) {
			$start_date = strtotime( '-1 month' );
			$end_date   = current_time( 'timestamp' );
		}
		if ( ( ! is_null( $accessible_users ) && -1 != $accessible_users ) && 0 === count( $accessible_users ) ) {
			return new WP_Error(
				'no-data',
				__( 'No learners found', 'learndash-reports-by-wisdmlabs' ),
				array( 'requestData' => self::get_values_for_request_params( $request_data ) )
			);
		}

		if ( ! is_null( $accessible_courses ) && -1 != $accessible_courses && 0 === count( $accessible_courses ) ) {
			return new WP_Error(
				'no-data',
				sprintf(
						/* translators: %s: custom label for courses */
					__( 'No accessible %s found', 'learndash-reports-by-wisdmlabs' ),
					\LearnDash_Custom_Label::label_to_lower( 'courses' )
				),
				array( 'requestData' => self::get_values_for_request_params( $request_data ) )
			);
		}

		if ( ! empty( $request_data['course'] ) ) {
			$attempts = $this->get_modulewise_activity( $request_data['course'], $request_data['course'], $request_data['start_date'], $request_data['end_date'], $accessible_users );
		}

		if ( ! empty( $request_data['lesson'] ) ) {
			$attempts = $this->get_modulewise_activity( $request_data['course'], $request_data['lesson'], $request_data['start_date'], $request_data['end_date'], $accessible_users );
		}

		if ( ! empty( $request_data['topic'] ) ) {
			$attempts = $this->get_modulewise_activity( $request_data['course'], $request_data['topic'], $request_data['start_date'], $request_data['end_date'], $accessible_users );
		}

		if ( ! empty( $request_data['topic'] ) || ! empty( $request_data['lesson'] ) || ! empty( $request_data['course'] ) ) {
			if ( isset( $accessible_courses ) && -1 != $accessible_courses ) {
				if ( ! in_array( $request_data['course'], $accessible_courses ) ) {
					return new WP_Error(
						'unauthorized',
						sprintf(/* translators: %s: custom label for course */
							__( 'You don\'t have access to this %s.', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::label_to_lower( 'course' )
						),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					);
				}
			}
			if ( empty( $attempts ) ) {
				$total   = 0;
				$pass    = 0;
				$quizzes = 0;
				return new WP_REST_Response(
					array(
						'requestData'         => self::get_values_for_request_params( $request_data ),
						'quizwise_data'       => $attempts,
						'total_quiz_attempts' => $total,
						'pass_quiz_attempts'  => $pass,
						'avg_quiz_attempts'   => $total,
					),
					200
				);
			}
			$total   = 0;
			$pass    = 0;
			$quizzes = count( $attempts );
			$total   = array_sum( array_column( $attempts, 'total' ) );
			$pass    = array_sum( array_column( $attempts, 'pass' ) );
			$avg     = round( $total / $quizzes );

			return new WP_REST_Response(
				array(
					'requestData'         => self::get_values_for_request_params( $request_data ),
					'quizwise_data'       => $attempts,
					'total_quiz_attempts' => $total,
					'pass_quiz_attempts'  => $pass,
					'avg_quiz_attempts'   => $avg,
				),
				200
			);
		}

		$query_args = array(
			'post_type'      => 'sfwd-courses',
			'posts_per_page' => '-1',
			'suppress_filters' => 0,
		);
		// Now consider the scenario where a category is selected. (Pro feature).
		if ( isset( $request_data['category'] ) && ! empty( $request_data['category'] ) ) {
			// Check if course category enabled.
			if ( ! taxonomy_exists( 'ld_course_category' ) ) {
				return new WP_Error(
					'invalid-input',
					sprintf(/* translators: %s: custom label for course */
						__( '%s Category disabled. Please contact admin.', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'course' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}
			// Check if valid category passed.
			if ( ! is_object( get_term_by( 'id', $request_data['category'], 'ld_course_category' ) ) ) {
				return new WP_Error(
					'invalid-input',
					sprintf(/* translators: %s: custom label for course */
						__( '%s Category doesn\'t exist', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'course' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}
			$query_args['tax_query'] = array(
				array(
					'taxonomy'         => 'ld_course_category',
					'field'            => 'term_id',
					'terms'            => $request_data['category'], // Where term_id of Term 1 is "1".
					'include_children' => false,
				),
			);
		}
		if ( isset( $request_data['group'] ) && ! empty( $request_data['group'] ) ) {
			$query_args['meta_query'] = array(
				array(
					'key'     => 'learndash_group_enrolled_' . $request_data['group'],
					'compare' => 'EXISTS',
				),
			);
		}
		$courses      = get_posts( $query_args );
		$course_data  = array();
		$course_count = count( $courses );
		// Check if any courses present in the category.
		if ( empty( $courses ) ) {
			return new WP_Error(
				'no-data',
				sprintf(/* translators: %s: custom label for courses */
					__( 'No %s found', 'learndash-reports-by-wisdmlabs' ),
					\LearnDash_Custom_Label::get_label( 'courses' )
				),
				array( 'requestData' => self::get_values_for_request_params( $request_data ) )
			);
		}

		$course_stats = array(
			'quiz_count'              => 0,
			'quiz_attempt_count'      => 0,
			'quiz_pass_attempt_count' => 0,
			'users_count'             => 0,
		);

		foreach ( $courses as $course ) {
			if ( isset( $accessible_courses ) && -1 != $accessible_courses ) {
				if ( ! in_array( $course->ID, $accessible_courses ) ) {
					--$course_count;
					continue;
				}
			}
			if ( count( learndash_course_get_children_of_step( $course->ID, $course->ID, 'sfwd-quiz', 'ids', true ) ) <= 0 ) {
				--$course_count;
				continue;
			}
			$attempts = $this->get_modulewise_activity( $course->ID, $course->ID, $request_data['start_date'], $request_data['end_date'], $accessible_users );
			if ( empty( $attempts ) ) {
				// $course_stats['quiz_attempt_count']       = $course_stats['quiz_attempt_count'] + 0;
				// $course_stats['users_count']              = $course_stats['users_count'] + 0;
				// $course_data[ $course->ID ]['percentage'] = 100;
				--$course_count;
				continue;
			}
			$course_data[ $course->ID ]['title']      = $course->post_title;
			$course_stats['quiz_count']               = $course_stats['quiz_count'] + count( learndash_course_get_children_of_step( $course->ID, $course->ID, 'sfwd-quiz', 'ids', true ) );
			$course_stats['quiz_attempt_count']       = $course_stats['quiz_attempt_count'] + array_sum( array_column( $attempts, 'total' ) );
			$course_stats['quiz_pass_attempt_count']  = $course_stats['quiz_pass_attempt_count'] + array_sum( array_column( $attempts, 'pass' ) );
			$course_stats['users_count']              = $course_stats['users_count'] + count( array_unique( array_merge( ...array_column( $attempts, 'users' ) ) ) );
			$course_data[ $course->ID ]['percentage'] = floatval( number_format( array_sum( array_column( $attempts, 'percentage' ) ) / count( $attempts ), 2, '.', '' ) );// Cast to integer if no decimals.
		}

		if ( $course_count <= 0 ) {
			return new WP_Error(
				'no-data',
				sprintf(/* translators: %s: custom label for quiz */
					__( 'No %s attempts found', 'learndash-reports-by-wisdmlabs' ),
					\LearnDash_Custom_Label::get_label( 'quiz' )
				),
				array( 'requestData' => self::get_values_for_request_params( $request_data ) )
			);
		} else {
			$average_pass_rate = floatval( number_format( array_sum( array_column( $course_data, 'percentage' ) ) / $course_count, 2, '.', '' ) );// Cast to integer if no decimals.
		}

		return new WP_REST_Response(
			array(
				'requestData'             => self::get_values_for_request_params( $request_data ),
				'average_pass_rate'       => $average_pass_rate,
				'learner_count'           => $course_stats['users_count'],
				'quiz_attempt_count'      => $course_stats['quiz_attempt_count'],
				'quiz_pass_attempt_count' => $course_stats['quiz_pass_attempt_count'],
				'quiz_count'              => $course_stats['quiz_count'],
				'coursewise_data'         => $course_data,
			),
			200
		);
	}

	/**
	 * This method returns average quiz attempts by students for quizzes of a course/lesson/topic.
	 *
	 * @return WP_REST_Response/WP_Error Objects.
	 */
	public function get_average_quiz_attempts() {
		global $wpdb;
		// Get Inputs.
		$request_data       = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING );
		$request_data       = self::get_request_params( $request_data );
		do_action( 'wpml_switch_language',  $_GET[ 'wpml_lang' ] ); // switch the content language
		$user_role_access   = self::get_current_user_role_access();
		$accessible_courses = self::get_accessible_courses_for_the_user( get_current_user_id(), $user_role_access, 'average_quiz_attempts' );
		$accessible_users   = self::get_accessible_users_for_the_user( get_current_user_id(), $user_role_access, 'average_quiz_attempts' );
		$excluded_users     = get_option( 'exclude_users', array() );
		if ( empty( $excluded_users ) || ! defined( 'LDRP_PLUGIN_VERSION' ) ) {
			$excluded_users = array();
		}

		$is_wrld_json_source = apply_filters('wrld_api_json_sorce' , false);
		if($is_wrld_json_source){
			$response = apply_filters('wrld_api_response_from_json_average_quiz_attempt',$request_data);
            $response['requestData'] = self::get_values_for_request_params( $request_data );
			return new WP_REST_Response(
				$response,
				200
			);
		}

		if ( isset( $request_data['group'] ) && ! empty( $request_data['group'] ) ) {
			$group_users = get_transient( 'wrld_group_students_data_' . $request_data['group'] );
			if ( false === $group_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
				// Get all students for a course.
				if ( get_option( 'migrated_group_access_data', false ) ) {
					// $group_users = array_unique( array_merge( $group_users, \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] ) ) );
					$group_users = \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] );
				} else {
					$group_users = self::get_ld_group_user_ids( $request_data['group'] );
				}
				delete_transient( 'wrld_group_students_data_' . $request_data['group'] );
				set_transient( 'wrld_group_students_data_' . $request_data['group'], $group_users, 1 * HOUR_IN_SECONDS );
			}
			$accessible_users = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $group_users, $accessible_users ) : $group_users;
		}
		$category_query = array();
		$group_users    = null;// initialize with user ids if the user is group leader & have access to limited users

		$start_date = $request_data['start_date'];
		$end_date   = $request_data['end_date'];
		if ( empty( $start_date ) || empty( $end_date ) ) {
			$start_date = strtotime( '-1 month' );
			$end_date   = current_time( 'timestamp' );
		}

		if ( isset( $request_data['learner'] ) && ! empty( $request_data['learner'] ) ) {
			$courses = learndash_user_get_enrolled_courses( $request_data['learner'], array(), false );
			if ( isset( $accessible_courses ) && -1 != $accessible_courses ) {
				if ( empty( $courses ) ) {
					$courses = array();
				}
				$courses = array_intersect( $courses, $accessible_courses );
			}
			if ( empty( $courses ) ) {
				return new WP_Error(
					'no-data',
					sprintf(/* translators: %s: custom label for courses */
						__( 'No accessible %s found', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'courses' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}
			$quiz_attempt_data = array();
			$total_attempts    = 0;
			$quiz_count        = 0;
			$total_quizzes     = array();
			foreach ( $courses as $course_id ) {
				$course_time = 0;
				$quizzes     = learndash_course_get_children_of_step( $course_id, $course_id, 'sfwd-quiz', 'ids', true );
				$quiz_count  = $quiz_count + count( $quizzes );
				if ( empty( $quizzes ) ) {
					continue;
				}
				$total_quizzes = array_unique( array_merge( $total_quizzes, $quizzes ) );
				foreach ( $quizzes as $quiz ) {
					// $count                      = learndash_get_user_quiz_attempts_count( $request_data['learner'], $quiz );
					// $count          = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM ' . esc_sql( \LDLMS_DB::get_table_name( 'user_activity' ) ) . ' WHERE user_id = %d AND post_id = %d AND activity_type = %s AND ( activity_completed >= %d AND activity_completed <= %d ) AND course_id=%d ORDER BY activity_id, activity_started ASC', $request_data['learner'], $quiz, 'quiz', $start_date, $end_date, $course_id ) );
					$count          = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM ' . esc_sql( \LDLMS_DB::get_table_name( 'quiz_statistic_ref' ) ) . ' WHERE user_id = %d AND quiz_post_id = %d AND ( create_time >= %d AND create_time <= %d ) AND course_post_id=%d', $request_data['learner'], $quiz, $start_date, $end_date, $course_id ) );
					$total_attempts = $total_attempts + $count;
					if ( ! array_key_exists( $quiz, $quiz_attempt_data ) ) {
						$quiz_attempt_data[ $quiz ] = array(
							'count' => null == $count ? 0 : $count,
							'title' => get_the_title( $quiz ),
						);
					} else {
						$quiz_attempt_data[ $quiz ]['count'] += null == $count ? 0 : $count;
					}
				}
				unset( $quizzes );
			}
			if ( $quiz_count > 0 ) {
				$average_attempts = floatval( number_format( $total_attempts / count( $total_quizzes ), 2, '.', '' ) );// Cast to integer if no decimals.
			} else {
				$average_attempts = 0;
			}
			return new WP_REST_Response(
				array(
					'requestData'      => self::get_values_for_request_params( $request_data ),
					'quizwise_data'    => $quiz_attempt_data,
					'total_attempts'   => $total_attempts,
					'average_attempts' => $average_attempts,
					'total_quizzes'    => $quiz_count,
				),
				200
			);
		}

		if ( empty( $request_data['course'] ) ) {
			return new WP_Error(
				'invalid-input',
				sprintf(/* translators: %s: custom label for course */
					__( '%s selection is required', 'learndash-reports-by-wisdmlabs' ),
					\LearnDash_Custom_Label::get_label( 'course' )
				),
				array( 'requestData' => self::get_values_for_request_params( $request_data ) )
			);
		}

		if ( isset( $accessible_courses ) && -1 != $accessible_courses ) {
			if ( ! in_array( $request_data['course'], $accessible_courses ) ) {
				return new WP_Error(
					'unauthorized',
					sprintf(/* translators: %s: custom label for course */
						__( 'You don\'t have access to this %s.', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::label_to_lower( 'course' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}
		}

		if ( ! empty( $request_data['topic'] ) ) {
			$quizzes = learndash_course_get_children_of_step( $request_data['course'], $request_data['topic'], 'sfwd-quiz', 'ids', true );
		} elseif ( ! empty( $request_data['lesson'] ) ) {
			$quizzes = learndash_course_get_children_of_step( $request_data['course'], $request_data['lesson'], 'sfwd-quiz', 'ids', true );
		} else {
			$quizzes = learndash_course_get_children_of_step( $request_data['course'], $request_data['course'], 'sfwd-quiz', 'ids', true );
		}
		if ( empty( $quizzes ) ) {
			return new WP_Error(
				'no-data',
				sprintf(/* translators: %s: custom label for quizzes */
					__( 'No %s found for the selected filters', 'learndash-reports-by-wisdmlabs' ),
					\LearnDash_Custom_Label::get_label( 'quizzes' )
				),
				array( 'requestData' => self::get_values_for_request_params( $request_data ) )
			);
		}
		$quiz_attempt_data = array();
		$total_attempts    = 0;
		$quizzes_count     = count( array_unique( $quizzes ) );
		$course_price_type = learndash_get_course_meta_setting( $request_data['course'], 'course_price_type' );
		if ( 'open' === $course_price_type ) {
			return new WP_Error(
				'no-data',
				sprintf(/* translators: %s: custom label for courses */
					__( 'Reports for open %s are not accessible for the time-being', 'learndash-reports-by-wisdmlabs' ),
					\LearnDash_Custom_Label::get_label( 'courses' )
				),
				array( 'requestData' => self::get_values_for_request_params( $request_data ) )
			);
		}
		// $students = get_transient( 'wrld_course_students_data_' . $request_data['course'] );
		// if ( false === $students || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) ) {
		// 	// Get all students for a course.
		// 	if ( get_option( 'migrated_course_access_data', false ) ) {
		// 		$students = \WRLD_Quiz_Export_Db::instance()->get_users_for_course( $request_data['course'] );
		// 	} else {
		// 		$students = learndash_get_users_for_course( $request_data['course'], array(), false );
		// 	}
		// 	// Get all students for a course.
		// 	$students = is_array( $students ) ? $students : $students->get_results();
		// 	set_transient( 'wrld_course_students_data_' . $request_data['course'], $students, 1 * HOUR_IN_SECONDS );
		// }
		// $students          = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, $students ) : $students;
		if ( ! is_null( $accessible_users ) && -1 != $accessible_users ) {
			$accessible_users = array_diff( $accessible_users , $excluded_users );
			$student_count     = count( $accessible_users );
			$students = implode( ',', $accessible_users );
			if ( empty( $accessible_users ) ) {
				return new WP_Error(
					'no-data',
					sprintf(/* translators: %s: custom label for course */
						__( 'No Students enrolled in the selected %s/module.', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::label_to_lower( 'course' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}
		} elseif ( ! empty( $excluded_users ) ) {
			$excluded_users = implode( ',', $excluded_users );
		}
		$user_ids = array();
		// $students = '"' . implode( '","', $students ) . '"';
		foreach ( $quizzes as $quiz ) {
			$quiz_pro_id = get_post_meta( $quiz, 'quiz_pro_id', true );
			$args        = array(
				'limit'   => 9999999999,
				'offset'  => 0,
				'order'   => 'DESC',
				'orderby' => 'create_time',
			);

			$placeholder_args = array();
			if ( ( false !== $start_date ) && ( false !== $end_date ) ) {
				$where    = 'statref.quiz_id="' . $quiz_pro_id . '"';
				$where   .= ' AND statref.create_time>="' . $start_date . '"';
				$where   .= ' AND statref.create_time<="' . $end_date . '"';
				$where   .= ' AND statref.course_post_id="' . $request_data['course'] . '"';
				$orderby  = ' ORDER BY ' . $args['orderby'] . ' ' . $args['order'];
				$limit    = ' LIMIT ' . $args['offset'] . ', ' . (int) $args['limit'];
			} elseif ( ( false !== $start_date ) && ( false === $end_date ) ) {
				$where    = 'statref.quiz_id="' . $quiz_pro_id . '"';
				$where   .= ' AND statref.create_time>="' . $start_date . '"';
				$where   .= ' AND statref.course_post_id="' . $request_data['course'] . '"';
				$orderby  = ' ORDER BY ' . $args['orderby'] . ' ' . $args['order'];
				$limit    = ' LIMIT ' . $args['offset'] . ', ' . (int) $args['limit'];
			} elseif ( ( false === $start_date ) && ( false !== $end_date ) ) {
				$where    = 'statref.quiz_id="' . $quiz_pro_id . '"';
				$where   .= ' AND statref.create_time<="' . $end_date . '"';
				$where   .= ' AND statref.course_post_id="' . $request_data['course'] . '"';
				$orderby  = ' ORDER BY ' . $args['orderby'] . ' ' . $args['order'];
				$limit    = ' LIMIT ' . $args['offset'] . ', ' . (int) $args['limit'];

			} else {
				$where    = 'statref.quiz_id="' . $quiz_pro_id . '"';
				$where   .= ' AND statref.course_post_id="' . $request_data['course'] . '"';
				$orderby  = ' ORDER BY ' . $args['orderby'] . ' ' . $args['order'];
				$limit    = ' LIMIT ' . $args['offset'] . ', ' . (int) $args['limit'];
			}
			if ( ! empty( $students ) ) {
				$where   .= ' AND statref.user_id IN (' . $students . ')';
			} elseif ( ! empty( $excluded_users ) ) {
				$where   .= ' AND statref.user_id NOT IN (' . $excluded_users . ')';
			}

			$attempts = $wpdb->get_results( 'SELECT * FROM ' . esc_sql( LDLMS_DB::get_table_name( 'quiz_statistic_ref' ) ) . ' as statref WHERE ' . $where . $orderby . $limit ); // phpcs:ignore.
			$quiz_attempt_data[ $quiz ] = array(
				'title' => get_the_title( $quiz ),
				'count' => count( $attempts ),
			);
			if ( ! empty( $attempts ) ) {
				$user_ids = array_merge( $user_ids, array_unique( wp_list_pluck( $attempts, 'user_id' ) ) );
			}
			$total_attempts = $total_attempts + count( $attempts );
		}
		if ( ! isset( $student_count ) ) {
			$student_count = count( array_unique( $user_ids ) );
		}
		$average_attempts = floatval( number_format( $total_attempts / $quizzes_count, 2, '.', '' ) );// Cast to integer if no decimals.
		return new WP_REST_Response(
			array(
				'requestData'      => self::get_values_for_request_params( $request_data ),
				'quizwise_data'    => $quiz_attempt_data,
				'total_attempts'   => $total_attempts,
				'average_attempts' => $average_attempts,
				'student_count'    => $student_count,
			),
			200
		);
	}

	/**
	 * This method is used to return Student-wise quiz activity for a module.
	 *
	 * @param  int $course     Course ID.
	 * @param  int $module     Lesson/Topic ID.
	 * @param  int $start_date Timestamp.
	 * @param  int $end_date   Timestamp.
	 * @return Array of Quiz related activity data.
	 */
	public function get_modulewise_activity( $course, $module, $start_date, $end_date, $accessible_users = null ) {
		global $wpdb;
		$quizzes = learndash_course_get_children_of_step( $course, $module, 'sfwd-quiz', 'ids', true );
		if ( empty( $quizzes ) ) {
			return array();
		}
		$quizzes_str         = implode( ', ', $quizzes );
		$args        = array(
			'limit'   => 9999999999,
			'offset'  => 0,
			'order'   => 'DESC',
			'orderby' => 'create_time',
		);
		// $activity_query_args = array(
		// 	'post_ids'        => $quizzes_str,
		// 	'activity_type'   => 'quiz',
		// 	'time_start'      => $start_date,// phpcs:ignore.
		// 	'time_end'        => $end_date,// phpcs:ignore.
		// 	'per_page'        => 1844674407370,
		// 	'activity_status' => array( 'NOT_STARTED', 'IN_PROGRESS', 'COMPLETED' ),
		// );
		// if ( ! empty( $course ) ) {
		// 	$activity_query_args['course_ids'] = array( $course );
		// }
		$where    = 'statref.quiz_post_id IN (' . $quizzes_str . ')';
		$where   .= ' AND statref.create_time>="' . $start_date . '"';
		$where   .= ' AND statref.create_time<="' . $end_date . '"';
		$where   .= ' AND statref.course_post_id="' . $course . '"';
		$excluded_users     = get_option( 'exclude_users', array() );
		if ( empty( $excluded_users ) || ! defined( 'LDRP_PLUGIN_VERSION' ) ) {
			$excluded_users = array();
		}
		if ( ! is_null( $accessible_users ) && -1 != $accessible_users ) {
			$accessible_users = array_diff( $accessible_users , $excluded_users );
			$accessible_users = implode( ',', $accessible_users );
			// $activity_query_args['user_ids'] = $accessible_users;
			$where   .= ' AND statref.user_id IN (' . $accessible_users . ')';
		} elseif ( ! empty( $excluded_users ) ) {
			$excluded_users = implode( ',', $excluded_users );
			$where   .= ' AND statref.user_id NOT IN (' . $excluded_users . ')';
			// $activity_query_args['user_ids'] = $excluded_users;
			// $activity_query_args['user_ids_action'] = 'NOT IN';
		} 
		$orderby  = ' ORDER BY ' . $args['orderby'] . ' ' . $args['order'];
		$limit    = ' LIMIT ' . $args['offset'] . ', ' . (int) $args['limit'];
		$attempts = $wpdb->get_results( 'SELECT * FROM ' . esc_sql( LDLMS_DB::get_table_name( 'quiz_statistic_ref' ) ) . ' as statref WHERE ' . $where . $orderby . $limit );
		// $quiz_activity = learndash_reports_get_activity( $activity_query_args );
		$quiz_data     = array();
		$user          = wp_get_current_user();
		/*if ( ( isset( $quiz_activity['results'] ) ) && ( ! empty( $quiz_activity['results'] ) ) ) {
			foreach ( $quiz_activity['results'] as $result ) {
				if ( ! current_user_can( 'manage_options' ) ) {
					if ( in_array( 'group_leader', (array) $user->roles, true ) ) {
						$group_users = get_transient( 'wrld_course_groups_students_data_' . $course );
						if ( false === $group_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) ) {
							if ( get_option( 'migrated_group_access_data', false ) ) {
								$group_users = \WRLD_Quiz_Export_Db::instance()->get_course_groups_users_access( $course );
							} else {
								$group_users = learndash_get_course_groups_users_access( $course );
							}
							set_transient( 'wrld_course_groups_students_data_' . $course, $group_users, 1 * HOUR_IN_SECONDS );
						}
						if ( ! in_array( $result->user_id, $group_users ) ) {
							continue;
						}
					}
				}
				if ( ! isset( $quiz_data[ $result->post_id ]['title'] ) ) {
					$quiz_data[ $result->post_id ]['title'] = get_the_title( $result->post_id );
				}
				if ( ! isset( $quiz_data[ $result->post_id ]['total'] ) ) {
					$quiz_data[ $result->post_id ]['total'] = 0;
				}
				if ( ! isset( $quiz_data[ $result->post_id ]['pass'] ) ) {
					$quiz_data[ $result->post_id ]['pass'] = 0;
				}
				if ( ! isset( $quiz_data[ $result->post_id ]['users'] ) ) {
					$quiz_data[ $result->post_id ]['users'] = array();
				}
				$quiz_data[ $result->post_id ]['total']++;
				$quiz_data[ $result->post_id ]['users'][] = $result->user_id;
				if ( ( isset( $result->activity_meta['pass'] ) ) && $result->activity_meta['pass'] ) {
					$quiz_data[ $result->post_id ]['pass']++;
				}
			}
			foreach ( $quiz_data as $quiz_id => $quiz_stat ) {
				if ( 0 == $quiz_data[ $quiz_id ]['total'] ) {
					$quiz_data[ $quiz_id ]['percentage'] = 0;
					continue;
				}
				$quiz_data[ $quiz_id ]['percentage'] = floatval( number_format( 100 * $quiz_data[ $quiz_id ]['pass'] / $quiz_data[ $quiz_id ]['total'], 2, '.', '' ) );// Cast to integer if no decimals.
			}
		}*/
		if ( ( isset( $attempts ) ) && ( ! empty( $attempts ) ) ) {
			foreach ( $attempts as $result ) {
				if ( ! current_user_can( 'manage_options' ) ) {
					if ( in_array( 'group_leader', (array) $user->roles, true ) ) {
						$group_users = get_transient( 'wrld_course_groups_students_data_' . $course );
						if ( false === $group_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
							if ( get_option( 'migrated_group_access_data', false ) ) {
								$group_users = \WRLD_Quiz_Export_Db::instance()->get_course_groups_users_access( $course );
							} else {
								$group_users = learndash_get_course_groups_users_access( $course );
							}
							delete_transient( 'wrld_course_groups_students_data_' . $course );
							set_transient( 'wrld_course_groups_students_data_' . $course, $group_users, 1 * HOUR_IN_SECONDS );
						}
						if ( ! in_array( $result->user_id, $group_users ) ) {
							continue;
						}
					}
				}
				if ( ! isset( $quiz_data[ $result->quiz_post_id ]['title'] ) ) {
					$quiz_data[ $result->quiz_post_id ]['title'] = get_the_title( $result->quiz_post_id );
				}
				if ( ! isset( $quiz_data[ $result->quiz_post_id ]['total'] ) ) {
					$quiz_data[ $result->quiz_post_id ]['total'] = 0;
				}
				if ( ! isset( $quiz_data[ $result->quiz_post_id ]['pass'] ) ) {
					$quiz_data[ $result->quiz_post_id ]['pass'] = 0;
				}
				if ( ! isset( $quiz_data[ $result->quiz_post_id ]['users'] ) ) {
					$quiz_data[ $result->quiz_post_id ]['users'] = array();
				}
				$quiz_data[ $result->quiz_post_id ]['users'][] = $result->user_id;
				$usermeta = get_user_meta( $result->user_id, '_sfwd-quizzes', true );
				if ( ! empty( $usermeta ) ) {
					foreach ( $usermeta as $quiz_attempt ) {
						if ( $quiz_attempt['statistic_ref_id'] == $result->statistic_ref_id ) {
							$quiz_data[ $result->quiz_post_id ]['total']++;
							if ( ( isset( $quiz_attempt['pass'] ) ) && $quiz_attempt['pass'] > 0 ) {
								$quiz_data[ $result->quiz_post_id ]['pass']++;
							}
							if ( ( isset( $quiz_attempt['percentage'] ) ) && $quiz_attempt['percentage'] ) {
								$quiz_data[ $result->quiz_post_id ]['percentage'] = $quiz_attempt['percentage'];
							}							
							break;
						}
					}
				}
			}
		}
		return $quiz_data;
	}

	/**
	 * This method is used to return Student-wise quiz activity for a course.
	 *
	 * @param  int $course     Course ID.
	 * @param  int $start_date Timestamp.
	 * @param  int $end_date   Timestamp.
	 * @return Array of Quiz related activity data.
	 */
	public function get_studentwise_activity( $course, $start_date, $end_date ) {
		$quizzes = learndash_course_get_children_of_step( $course, $course, 'sfwd-quiz', 'ids', true );
		if ( empty( $quizzes ) ) {
			return array();
		}
		$quizzes_str         = implode( ', ', $quizzes );
		$activity_query_args = array(
			'post_ids'        => $quizzes_str,
			'activity_type'   => 'quiz',
			'activity_status' => array( 'NOT_STARTED', 'IN_PROGRESS', 'COMPLETED' ),
			'time_start'      => $start_date,// phpcs:ignore.
			'time_end'        => $end_date,// phpcs:ignore.
			'per_page'        => 1844674407370,
		);
		$quiz_activity       = learndash_reports_get_activity( $activity_query_args );
		$quiz_data           = array();
		if ( ( isset( $quiz_activity['results'] ) ) && ( ! empty( $quiz_activity['results'] ) ) ) {
			foreach ( $quiz_activity['results'] as $result ) {
				if ( ! isset( $quiz_data[ $result->user_id ]['total'] ) ) {
					$quiz_data[ $result->user_id ]['total'] = 0;
				}
				if ( ! isset( $quiz_data[ $result->user_id ]['pass'] ) ) {
					$quiz_data[ $result->user_id ]['pass'] = 0;
				}
				$quiz_data[ $result->user_id ]['total']++;
				if ( ( isset( $result->activity_meta['pass'] ) ) && $result->activity_meta['pass'] ) {
					$quiz_data[ $result->user_id ]['pass']++;
				}
			}
		}
		return $quiz_data;
	}

	/**
	 * This method returns Quiz Statistic info.
	 *
	 * @param  int $course     Course ID.
	 * @param  int $module     Lesson/Topic ID.
	 * @param  int $start_date Timestamp.
	 * @param  int $end_date   Timestamp.
	 * @return Array of WpProQuiz_Model_StatisticRefModel objects.
	 */
	public function get_modulewise_statistics( $course, $module, $start_date, $end_date ) {
		global $wpdb;
		$quizzes      = learndash_course_get_children_of_step( $course, $module, 'sfwd-quiz', 'ids', true );
		$quiz_pro_ids = array_map(
			function( $quiz ) {
				return get_post_meta( $quiz, 'quiz_pro_id', true );
			},
			$quizzes
		);

		$quiz_pro_ids_str = implode( ', ', $quiz_pro_ids );

		$placeholder_args = array();
		$a                = array();
		$args             = array(
			'limit'   => 18446744073709551615,
			'offset'  => 0,
			'order'   => 'DESC',
			'orderby' => 'create_time',
		);

		if ( ( false !== $start_date ) && ( false !== $end_date ) ) {
			$placeholder_args[] = $quiz_pro_ids_str;
			$placeholder_args[] = $start_date;
			$placeholder_args[] = $end_date;
			$placeholder_args[] = $args['orderby'];
			$placeholder_args[] = $args['order'];
			$placeholder_args[] = $args['offset'];
			$placeholder_args[] = $args['limit'];

			$results = $wpdb->get_results( $wpdb->prepare( 'SELECT COUNT(*) FROM ' . esc_sql( LDLMS_DB::get_table_name( 'quiz_statistic_ref' ) ) . ' as statref WHERE statref.quiz_id IN (%s) AND statref.create_time >= %s AND statref.create_time <= %s ORDER BY %s %s LIMIT %d, %d', $placeholder_args ), ARRAY_A );

		} elseif ( ( false !== $start_date ) && ( false === $end_date ) ) {
			$placeholder_args[] = $quiz_pro_ids_str;
			$placeholder_args[] = $start_date;
			$placeholder_args[] = $args['orderby'];
			$placeholder_args[] = $args['order'];
			$placeholder_args[] = $args['offset'];
			$placeholder_args[] = $args['limit'];

			$results = $wpdb->get_results( $wpdb->prepare( 'SELECT COUNT(*) FROM ' . esc_sql( LDLMS_DB::get_table_name( 'quiz_statistic_ref' ) ) . ' as statref WHERE  statref.quiz_id IN (%s) AND statref.create_time >= %s ORDER BY %s %s LIMIT %d, %d', $placeholder_args ), ARRAY_A );

		} elseif ( ( false === $start_date ) && ( false !== $end_date ) ) {
			$placeholder_args[] = $quiz_pro_ids_str;
			$placeholder_args[] = $end_date;
			$placeholder_args[] = $args['orderby'];
			$placeholder_args[] = $args['order'];
			$placeholder_args[] = $args['offset'];
			$placeholder_args[] = $args['limit'];

			$results = $wpdb->get_results( $wpdb->prepare( 'SELECT COUNT(*) FROM ' . esc_sql( LDLMS_DB::get_table_name( 'quiz_statistic_ref' ) ) . ' as statref WHERE  statref.quiz_id IN (%s) AND statref.create_time <= %s ORDER BY %s %s LIMIT %d, %d', $placeholder_args ), ARRAY_A );

		} else {
			$placeholder_args[] = $quiz_pro_ids_str;
			$placeholder_args[] = $args['orderby'];
			$placeholder_args[] = $args['order'];
			$placeholder_args[] = $args['offset'];
			$placeholder_args[] = $args['limit'];

			$results = $wpdb->get_results( $wpdb->prepare( 'SELECT COUNT(*) FROM ' . esc_sql( LDLMS_DB::get_table_name( 'quiz_statistic_ref' ) ) . ' as statref WHERE  statref.quiz_id IN (%s) ORDER BY %s %s LIMIT %d, %d', $placeholder_args ), ARRAY_A );

		}
		foreach ( $results as $row ) {
			$a[] = new WpProQuiz_Model_StatisticRefModel( $row );
		}
		return $a;
	}

	/**
	 * This method is used to return the tabular data shown in the Course List Info block.
	 *
	 * @return WP_REST_Response/WP_Error Objects.
	 */
	public function get_course_list_info() {
		global $wpdb;
		$request_data       = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING );
		$request_data       = self::get_request_params( $request_data );
		$duration           = self::get_duration_data( $request_data['start_date'], $request_data['end_date'], 'Y-m-d H:i:s' );
		$time_spent_row = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT id, option_value, expires_on, created_on FROM {$wpdb->prefix}wrld_cached_entries WHERE object_type=%s AND object_id=%d AND option_name=%s",
				'user',
				get_current_user_id(),
				'wrld_course_list_info_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic']
			)
		);
		if ( empty( $time_spent_row ) || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $time_spent_row->expires_on <= current_time( 'timestamp' ) || $request_data['disable_cache'] ) {
			if ( ! empty( $time_spent_row ) ) {
				$wpdb->delete(
					$wpdb->prefix . 'wrld_cached_entries',
					array(
						'id' => $time_spent_row->id
					),
					array(
						'%d'
					)
				);
			}
			do_action( 'wpml_switch_language',  $_GET[ 'wpml_lang' ] ); // switch the content language
			$user_role_access   = self::get_current_user_role_access();
			$accessible_courses = self::get_accessible_courses_for_the_user( get_current_user_id(), $user_role_access, 'course_list_info' );
			$accessible_users   = self::get_accessible_users_for_the_user( get_current_user_id(), $user_role_access, 'course_list_info' );
			$excluded_users     = get_option( 'exclude_users', array() );
			if ( empty( $excluded_users ) || ! defined( 'LDRP_PLUGIN_VERSION' ) ) {
				$excluded_users = array();
			}
			if ( isset( $request_data['group'] ) && ! empty( $request_data['group'] ) ) {
				$group_users = get_transient( 'wrld_group_students_data_' . $request_data['group'] );
				if ( false === $group_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
					// Get all students for a course.
					if ( get_option( 'migrated_group_access_data', false ) ) {
						// $group_users = array_unique( array_merge( $group_users, \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] ) ) );
						$group_users = \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] );
					} else {
						$group_users = self::get_ld_group_user_ids( $request_data['group'] );
					}
					delete_transient( 'wrld_group_students_data_' . $request_data['group'] );
					set_transient( 'wrld_group_students_data_' . $request_data['group'], $group_users, 1 * HOUR_IN_SECONDS );
				}
				$accessible_users = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $group_users, $accessible_users ) : $group_users;
			}
			$category_query = array();
			$group_users    = null;// initialize with user ids if the user is group leader & have access to limited users
			$time_tracking  = WRLD_Course_Time_Tracking::get_instance();
			global $wpdb;

			$is_pro_version_active = apply_filters( 'wisdm_ld_reports_pro_version', false );

			$start_date = $duration['start_date'];
			$end_date   = $duration['end_date'];
			if ( empty( $start_date ) || empty( $end_date ) ) {
				$start_date = strtotime( '-1 month' );
				$end_date   = current_time( 'timestamp' );
			}
			$args        = array(
				'limit'   => 9999999999,
				'offset'  => 0,
				'order'   => 'DESC',
				'orderby' => 'create_time',
			);

			if ( isset( $request_data['learner'] ) && ! empty( $request_data['learner'] ) ) {
				$courses = learndash_user_get_enrolled_courses( $request_data['learner'], array(), false );
				if ( isset( $accessible_courses ) && -1 != $accessible_courses ) {
					$courses = array_intersect( $courses, $accessible_courses );
				}
				if ( empty( $courses ) ) {
					$response = new WP_Error(
						'no-data',
						sprintf(/* translators: %s: custom label for courses */
							__( 'No %s found', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::get_label( 'courses' )
						),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					);
					$current_timestamp = current_time( 'timestamp' );
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_course_list_info_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
							'option_value' => maybe_serialize( $response ),
							'object_id'    => get_current_user_id(),
							'object_type'  => 'user',
							'created_on'   => $current_timestamp,
							'expires_on'   => $current_timestamp + DAY_IN_SECONDS
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d',
							'%d',
						)
					);
					$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
					return $response;
				}
				$table = array();
				foreach ( $courses as $course_id ) {
					$since = ld_course_access_from( $course_id, $request_data['learner'] );
					if ( ! empty( $since ) ) {
						$since = learndash_adjust_date_time_display( $since, 'd M, Y' );
					} else {
						$since = learndash_user_group_enrolled_to_course_from( $request_data['learner'], $course_id );
						if ( ! empty( $since ) ) {
							$since = learndash_adjust_date_time_display( $since, 'd M, Y' );
						}
					}

					$completed = get_user_meta( $request_data['learner'], 'course_completed_' . $course_id, true );
					if ( ! empty( $completed ) ) {
						$completed = learndash_adjust_date_time_display( $completed, 'd M, Y' );
					}
					$quizzes       = learndash_course_get_children_of_step( $course_id, $course_id, 'sfwd-quiz', 'ids', true );
					$quiz_attempts = 0;
					$pass_count    = 0;
					$total_count   = 0;
					if ( ! empty( $quizzes ) ) {
						// foreach ( $quizzes as $quiz ) {
						// 	$count         = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM ' . esc_sql( \LDLMS_DB::get_table_name( 'quiz_statistic_ref' ) ) . ' WHERE user_id = %d AND quiz_post_id = %d AND ( create_time >= %d AND create_time <= %d ) AND course_post_id=%d ORDER BY create_time DESC', $request_data['learner'], $quiz, $request_data['start_date'], $request_data['end_date'], $course_id ) );
						// 	// $count         = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM ' . esc_sql( \LDLMS_DB::get_table_name( 'user_activity' ) ) . ' WHERE user_id = %d AND post_id = %d AND activity_type = %s AND ( activity_completed >= %d AND activity_completed <= %d ) AND course_id=%d ORDER BY activity_id, activity_started ASC', $request_data['learner'], $quiz, 'quiz', $request_data['start_date'], $request_data['end_date'], $course_id ) );
						// 	$quiz_attempts = $quiz_attempts + $count;
						// }
						$quizzes_str         = implode( ', ', $quizzes );
						// $activity_query_args = array(
						// 	'post_ids'        => $quizzes_str,
						// 	'user_ids'        => $request_data['learner'],
						// 	'activity_type'   => 'quiz',
						// 	'time_start'      => $start_date,// phpcs:ignore.
						// 	'time_end'        => $end_date,// phpcs:ignore.
						// 	'per_page'        => 1844674407370,
						// 	'activity_status' => array( 'NOT_STARTED', 'IN_PROGRESS', 'COMPLETED' ),
						// );
						// $quiz_activity       = learndash_reports_get_activity( $activity_query_args );
						// $pass_count          = 0;
						// $total_count         = 0;
						// if ( ( isset( $quiz_activity['results'] ) ) && ( ! empty( $quiz_activity['results'] ) ) ) {
						// 	foreach ( $quiz_activity['results'] as $result ) {
						// 		$total_count++;
						// 		if ( ( isset( $result->activity_meta['pass'] ) ) && $result->activity_meta['pass'] ) {
						// 			$pass_count++;
						// 		}
						// 	}
						// }
						$where    = 'statref.quiz_post_id IN (' . $quizzes_str . ')';
						$where   .= ' AND statref.create_time>="' . $start_date . '"';
						$where   .= ' AND statref.create_time<="' . $end_date . '"';
						$where   .= ' AND statref.course_post_id="' . $course_id . '"';
						$where   .= ' AND statref.user_id="' . $request_data['learner'] . '"';
						$orderby  = ' ORDER BY ' . $args['orderby'] . ' ' . $args['order'];
						$limit    = ' LIMIT ' . $args['offset'] . ', ' . (int) $args['limit'];
						$attempts = $wpdb->get_results( 'SELECT * FROM ' . esc_sql( LDLMS_DB::get_table_name( 'quiz_statistic_ref' ) ) . ' as statref WHERE ' . $where . $orderby . $limit );
						$quiz_attempts = count( $attempts );
						$usermeta = get_user_meta( $request_data['learner'], '_sfwd-quizzes', true );
						if ( ( isset( $attempts ) ) && ( ! empty( $attempts ) ) ) {
							foreach ( $attempts as $result ) {
								if ( empty( $usermeta ) ) {
									break;
								}
								foreach ( $usermeta as $quiz_attempt ) {
									if ( $quiz_attempt['statistic_ref_id'] == $result->statistic_ref_id ) {
										$total_count++;
										if ( ( isset( $quiz_attempt['pass'] ) ) && $quiz_attempt['pass'] > 0 ) {
											$pass_count++;
										}
										break;
									}

								}
							}
						}
					}
					$progress   = learndash_user_get_course_progress( $request_data['learner'], $course_id, 'summary' );
					$percentage = 100;
					if ( 0 < $progress['total'] ) {
						$percentage = floatval( number_format( 100 * $progress['completed'] / $progress['total'], 2, '.', '' ) );// Cast to integer if no decimals.
					}

					if ( $is_pro_version_active ) {

						$time     = $time_tracking->fetch_user_course_time_spent( $course_id, $request_data['learner'] );
						$avg_time = $time_tracking->fetch_user_average_course_completion_time( $course_id, $request_data['learner'] );
						$entry    = array(
							'course'                 => get_the_title( $course_id ),
							'start_date'             => ! empty( $since ) ? $since : '-',
							'end_date'               => ! empty( $completed ) ? $completed : '-',
							'total_time_spent'       => 0 == $time ? '-' : date_i18n( 'H:i:s', $time ),
							'course_completion_time' => 0 == $avg_time ? '-' : date_i18n( 'H:i:s', $avg_time ),
							'quiz_count'             => count( $quizzes ),
							'quiz_attempts'          => $quiz_attempts,
							'pass_count'             => $pass_count,
							'fail_count'             => $total_count - $pass_count,
							'course_progress'        => $percentage,
						);
					} else {
						$entry =
							array(
								'course'          => get_the_title( $course_id ),
								'start_date'      => ! empty( $since ) ? $since : '-',
								'end_date'        => ! empty( $completed ) ? $completed : '-',
								'quiz_count'      => count( $quizzes ),
								'quiz_attempts'   => $quiz_attempts,
								'pass_count'      => $pass_count,
								'fail_count'      => $total_count - $pass_count,
								'course_progress' => $percentage,
							);

					}
					$table[] = $entry;
				}
				$response = array(
					'requestData' => self::get_values_for_request_params( $request_data ),
					'table'       => $table,
				);
				$current_timestamp = current_time( 'timestamp' );
				$wpdb->insert(
					$wpdb->prefix . 'wrld_cached_entries',
					array(
						'option_name'  => 'wrld_course_list_info_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
						'option_value' => maybe_serialize( $response ),
						'object_id'    => get_current_user_id(),
						'object_type'  => 'user',
						'created_on'   => $current_timestamp,
						'expires_on'   => $current_timestamp + DAY_IN_SECONDS
					),
					array(
						'%s',
						'%s',
						'%d',
						'%s',
						'%d',
						'%d',
					)
				);
				$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
				$response['updated_on'] = $updated_on;
				return new WP_REST_Response(
					$response,
					200
				);
			}
			$data = array();

			if ( isset( $request_data['course'] ) && ! empty( $request_data['course'] ) ) {
				if ( isset( $accessible_courses ) && -1 != $accessible_courses ) {
					if ( ! in_array( $request_data['course'], $accessible_courses ) ) {
						$response = new WP_Error(
							'unauthorized',
							sprintf(/* translators: %s: custom label for course */
								__( 'You don\'t have access to this %s.', 'learndash-reports-by-wisdmlabs' ),
								\LearnDash_Custom_Label::label_to_lower( 'course' )
							),
							array( 'requestData' => self::get_values_for_request_params( $request_data ) )
						);
						$current_timestamp = current_time( 'timestamp' );
						$wpdb->insert(
							$wpdb->prefix . 'wrld_cached_entries',
							array(
								'option_name'  => 'wrld_course_list_info_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
								'option_value' => maybe_serialize( $response ),
								'object_id'    => get_current_user_id(),
								'object_type'  => 'user',
								'created_on'   => $current_timestamp,
								'expires_on'   => $current_timestamp + DAY_IN_SECONDS
							),
							array(
								'%s',
								'%s',
								'%d',
								'%s',
								'%d',
								'%d',
							)
						);
						$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
						return $response;
					}
				}
			}
			if ( ! empty( $request_data['topic'] ) ) {
				$course_price_type = learndash_get_course_meta_setting( $request_data['course'], 'course_price_type' );
				if ( 'open' === $course_price_type ) {
					$response = new WP_Error(
						'no-data',
						sprintf(/* translators: %s: custom label for courses */
							__( 'Reports for open %s are not accessible for the time-being', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::get_label( 'courses' )
						),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					);
					$current_timestamp = current_time( 'timestamp' );
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_course_list_info_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
							'option_value' => maybe_serialize( $response ),
							'object_id'    => get_current_user_id(),
							'object_type'  => 'user',
							'created_on'   => $current_timestamp,
							'expires_on'   => $current_timestamp + DAY_IN_SECONDS
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d',
							'%d',
						)
					);
					$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
					return $response;
				}
				$students = get_transient( 'wrld_course_students_data_' . $request_data['course'] );
				if ( false === $students || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
				// Get all students for a course.
					if ( get_option( 'migrated_course_access_data', false ) ) {
						$students = \WRLD_Quiz_Export_Db::instance()->get_users_for_course( $request_data['course'] );
					} else {
						$students = learndash_get_users_for_course( $request_data['course'], array(), false ); // Third argument is $exclude_admin.
					}
					$students = is_array( $students ) ? $students : $students->get_results();
					delete_transient( 'wrld_course_students_data_' . $request_data['course'] );
					set_transient( 'wrld_course_students_data_' . $request_data['course'], $students, 1 * HOUR_IN_SECONDS );
				}
				$students      = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, $students ) : $students;
				$students = array_diff( $students , $excluded_users );

				$student_count = is_array( $students ) ? count( $students ) : $students->get_total();
				if ( empty( $students ) ) {
					$response = new WP_Error(
						'no-data',
						__( 'No Data to display', 'learndash-reports-by-wisdmlabs' ),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					);
					$current_timestamp = current_time( 'timestamp' );
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_course_list_info_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
							'option_value' => maybe_serialize( $response ),
							'object_id'    => get_current_user_id(),
							'object_type'  => 'user',
							'created_on'   => $current_timestamp,
							'expires_on'   => $current_timestamp + DAY_IN_SECONDS
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d',
							'%d',
						)
					);
					$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
					return $response;
				}
				$table         = array();
				$quizzes       = learndash_course_get_children_of_step( $request_data['course'], $request_data['topic'], 'sfwd-quiz', 'ids', true );
				$quiz_count    = count( $quizzes );
				$quiz_activity = array();
				foreach ( $students as $student_id ) {
					$student_progress       = learndash_user_get_course_progress( $student_id, $request_data['course'], 'activity' );
					$student_progress_steps = learndash_user_get_course_progress( $student_id, $request_data['course'], 'summary' );
					$student_info           = get_userdata( $student_id );
					$attempts               = 0;
					$pass                   = 0;
					$score                  = 0;
					$counter                = 0;
					if ( $quiz_count > 0 ) {
						$quizzes_str         = implode( ', ', $quizzes );
						// $activity_query_args = array(
						// 	'post_ids'        => $quizzes_str,
						// 	'course_ids'      => $request_data['course'],
						// 	'user_ids'        => $student_id,
						// 	'activity_type'   => 'quiz',
						// 	'activity_status' => array( 'NOT_STARTED', 'IN_PROGRESS', 'COMPLETED' ),
						// 	'per_page'        => 1844674407370,
						// );
						// $quiz_activity       = learndash_reports_get_activity( $activity_query_args );
						// if ( ( isset( $quiz_activity['results'] ) ) && ( ! empty( $quiz_activity['results'] ) ) ) {
						// 	foreach ( $quiz_activity['results'] as $result ) {
						// 		$attempts++;
						// 		if ( ( isset( $result->activity_meta['pass'] ) ) && $result->activity_meta['pass'] ) {
						// 			$pass++;
						// 		}
						// 		if ( ( isset( $result->activity_meta['percentage'] ) ) && $result->activity_meta['percentage'] ) {
						// 			$counter++;
						// 			$score += (float) $result->activity_meta['percentage'];
						// 		}
						// 	}
						// }
						$where    = 'statref.quiz_post_id IN (' . $quizzes_str . ')';
						$where   .= ' AND statref.course_post_id="' . $request_data['course'] . '"';
						$where   .= ' AND statref.user_id="' . $student_id . '"';
						$orderby  = ' ORDER BY ' . $args['orderby'] . ' ' . $args['order'];
						$limit    = ' LIMIT ' . $args['offset'] . ', ' . (int) $args['limit'];
						$results = $wpdb->get_results( 'SELECT * FROM ' . esc_sql( LDLMS_DB::get_table_name( 'quiz_statistic_ref' ) ) . ' as statref WHERE ' . $where . $orderby . $limit );
						$quiz_attempts = count( $results );
						$usermeta = get_user_meta( $student_id, '_sfwd-quizzes', true );
						if ( ( isset( $results ) ) && ( ! empty( $results ) ) ) {
							foreach ( $results as $result ) {
								if ( empty( $usermeta ) ) {
									break;
								}
								foreach ( $usermeta as $quiz_attempt ) {
									if ( $quiz_attempt['statistic_ref_id'] == $result->statistic_ref_id ) {
										$attempts++;
										if ( ( isset( $quiz_attempt['pass'] ) ) && $quiz_attempt['pass'] > 0 ) {
											$pass++;
										}
										if ( ( isset( $quiz_attempt['percentage'] ) ) && $quiz_attempt['percentage'] ) {
											$counter++;
											$score += (float) $quiz_attempt['percentage'];
										}
										break;
									}

								}
							}
						}
						if ( $counter > 0 ) {
							$score /= $counter;
						}
					}
					foreach ( $student_progress as $key => $progress ) {
						if ( empty( $progress ) || 'sfwd-topic:' . $request_data['topic'] != $key ) {
							continue;
						}
						$completed_timestamp = $student_progress[ 'sfwd-topic:' . $request_data['topic'] ]['activity_completed'];
						if ( $is_pro_version_active ) {
							$completion_time = $time_tracking->fetch_user_average_topic_completion_time( $request_data['topic'], $student_id );
							$entry           = array(
								'name'                   => $student_info->display_name,
								'email'                  => $student_info->user_email,
								'status'                 => $student_progress[ 'sfwd-topic:' . $request_data['topic'] ]['activity_status'] ? __( 'Complete', 'learndash-reports-by-wisdmlabs' ) : __( 'Not Complete', 'learndash-reports-by-wisdmlabs' ),
								/* translators: 1: Steps Completed, 2: Total Steps */
								'steps'                  => sprintf( _x( '%1$d of %2$d', '1: Steps Completed, 2: Total Steps', 'learndash-reports-by-wisdmlabs' ), $student_progress_steps['completed'], $student_progress_steps['total'] ),
								'date'                   => ! empty( $completed_timestamp ) ? date_i18n( 'd M, Y', $completed_timestamp ) : '-',
								'course_completion_time' => 0 == $completion_time ? '-' : date_i18n( 'H:i:s', $completion_time ),
								'attempts'               => $attempts,
								'pass_rate'              => empty( $attempts ) ? '-' : floatval( number_format( 100 * $pass / $attempts, 2, '.', '' ) ) . '%',
								'avg_score'              => floatval( number_format( $score, 2, '.', '' ) ) . '%',
							);

						} else {
							$entry = array(
								'name'      => $student_info->display_name,
								'email'     => $student_info->user_email,
								'status'    => $student_progress[ 'sfwd-topic:' . $request_data['topic'] ]['activity_status'] ? __( 'Complete', 'learndash-reports-by-wisdmlabs' ) : __( 'Not Complete', 'learndash-reports-by-wisdmlabs' ),
								/* translators: 1: Steps Completed, 2: Total Steps */
								'steps'     => sprintf( _x( '%1$d of %2$d', '1: Steps Completed, 2: Total Steps', 'learndash-reports-by-wisdmlabs' ), $student_progress_steps['completed'], $student_progress_steps['total'] ),
								'date'      => ! empty( $completed_timestamp ) ? date_i18n( 'd M, Y', $completed_timestamp ) : '-',
								'time'      => ! empty( $completed_timestamp ) ? date_i18n( 'H:i:s', (int) $student_progress[ 'sfwd-topic:' . $request_data['topic'] ]['activity_completed'] - (int) $student_progress[ 'sfwd-topic:' . $request_data['topic'] ]['activity_started'] ) : '-',
								'attempts'  => $attempts,
								'pass_rate' => empty( $attempts ) ? '-' : floatval( number_format( 100 * $pass / $attempts, 2, '.', '' ) ) . '%',
								'avg_score' => floatval( number_format( $score, 2, '.', '' ) ) . '%',
							);
						}
						$table[] = $entry;
						break;
					}
				}
				$response = array(
					'requestData' => self::get_values_for_request_params( $request_data ),
					'table'       => $table,
				);
				$current_timestamp = current_time( 'timestamp' );
				$wpdb->insert(
					$wpdb->prefix . 'wrld_cached_entries',
					array(
						'option_name'  => 'wrld_course_list_info_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
						'option_value' => maybe_serialize( $response ),
						'object_id'    => get_current_user_id(),
						'object_type'  => 'user',
						'created_on'   => $current_timestamp,
						'expires_on'   => $current_timestamp + DAY_IN_SECONDS
					),
					array(
						'%s',
						'%s',
						'%d',
						'%s',
						'%d',
						'%d',
					)
				);
				$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
				$response['updated_on'] = $updated_on;
				return new WP_REST_Response(
					$response,
					200
				);				
			}
			if ( ! empty( $request_data['lesson'] ) ) {
				$course_price_type = learndash_get_course_meta_setting( $request_data['course'], 'course_price_type' );
				if ( 'open' === $course_price_type ) {
					$response = new WP_Error(
						'no-data',
						sprintf(/* translators: %s: custom label for courses */
							__( 'Reports for open %s are not accessible for the time-being', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::get_label( 'courses' )
						),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					);
					$current_timestamp = current_time( 'timestamp' );
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_course_list_info_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
							'option_value' => maybe_serialize( $response ),
							'object_id'    => get_current_user_id(),
							'object_type'  => 'user',
							'created_on'   => $current_timestamp,
							'expires_on'   => $current_timestamp + DAY_IN_SECONDS
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d',
							'%d',
						)
					);
					$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
					return $response;
				}
				$students = get_transient( 'wrld_course_students_data_' . $request_data['course'] );
				if ( false === $students || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
				// Get all students for a course.
					if ( get_option( 'migrated_course_access_data', false ) ) {
						$students = \WRLD_Quiz_Export_Db::instance()->get_users_for_course( $request_data['course'] );
					} else {
						$students      = learndash_get_users_for_course( $request_data['course'], array(), false );
					}
					$students = is_array( $students ) ? $students : $students->get_results();
					delete_transient( 'wrld_course_students_data_' . $request_data['course'] );
					set_transient( 'wrld_course_students_data_' . $request_data['course'], $students, 1 * HOUR_IN_SECONDS );
				}
				$students      = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, $students ) : $students;
				$students = array_diff( $students , $excluded_users );
				$student_count = is_array( $students ) ? count( $students ) : $students->get_total();
				if ( empty( $students ) ) {
					$response = new WP_Error(
						'no-data',
						sprintf(/* translators: %s: custom label for lesson */
							__( 'No %1$s assigned to this %2$s', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::get_label( 'topics' ),
							\LearnDash_Custom_Label::label_to_lower( 'lesson' )
						),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					);
					$current_timestamp = current_time( 'timestamp' );
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_course_list_info_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
							'option_value' => maybe_serialize( $response ),
							'object_id'    => get_current_user_id(),
							'object_type'  => 'user',
							'created_on'   => $current_timestamp,
							'expires_on'   => $current_timestamp + DAY_IN_SECONDS
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d',
							'%d',
						)
					);
					$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
					return $response;
				}
				$table         = array();
				$quizzes       = learndash_course_get_children_of_step( $request_data['course'], $request_data['lesson'], 'sfwd-quiz', 'ids', true );
				$quiz_count    = count( $quizzes );
				$quiz_activity = array();
				foreach ( $students as $student_id ) {
					$student_progress       = learndash_user_get_course_progress( $student_id, $request_data['course'], 'activity' );
					$student_progress_steps = learndash_user_get_course_progress( $student_id, $request_data['course'], 'summary' );
					$student_info           = get_userdata( $student_id );
					$attempts               = 0;
					$pass                   = 0;
					$score                  = 0;
					$counter                = 0;
					$completion_time        = 0;
					if ( $quiz_count > 0 ) {
						$quizzes_str         = implode( ', ', $quizzes );
						// $activity_query_args = array(
						// 	'post_ids'        => $quizzes_str,
						// 	'course_ids'      => $request_data['course'],
						// 	'user_ids'        => $student_id,
						// 	'activity_type'   => 'quiz',
						// 	'activity_status' => array( 'NOT_STARTED', 'IN_PROGRESS', 'COMPLETED' ),
						// 	'per_page'        => 1844674407370,
						// );

						// $quiz_activity = learndash_reports_get_activity( $activity_query_args );
						// if ( ( isset( $quiz_activity['results'] ) ) && ( ! empty( $quiz_activity['results'] ) ) ) {
						// 	foreach ( $quiz_activity['results'] as $result ) {
						// 		$attempts++;
						// 		if ( ( isset( $result->activity_meta['pass'] ) ) && $result->activity_meta['pass'] ) {
						// 			$pass++;
						// 		}
						// 		if ( ( isset( $result->activity_meta['percentage'] ) ) && $result->activity_meta['percentage'] ) {
						// 			$counter++;
						// 			$score += (float) $result->activity_meta['percentage'];
						// 		}
						// 	}
						// }
						$where    = 'statref.quiz_post_id IN (' . $quizzes_str . ')';
						$where   .= ' AND statref.course_post_id="' . $request_data['course'] . '"';
						$where   .= ' AND statref.user_id="' . $student_id . '"';
						$orderby  = ' ORDER BY ' . $args['orderby'] . ' ' . $args['order'];
						$limit    = ' LIMIT ' . $args['offset'] . ', ' . (int) $args['limit'];
						$results = $wpdb->get_results( 'SELECT * FROM ' . esc_sql( LDLMS_DB::get_table_name( 'quiz_statistic_ref' ) ) . ' as statref WHERE ' . $where . $orderby . $limit );
						$quiz_attempts = count( $results );
						$usermeta = get_user_meta( $student_id, '_sfwd-quizzes', true );
						if ( ( isset( $results ) ) && ( ! empty( $results ) ) ) {
							foreach ( $results as $result ) {
								if ( empty( $usermeta ) ) {
									break;
								}
								foreach ( $usermeta as $quiz_attempt ) {
									if ( $quiz_attempt['statistic_ref_id'] == $result->statistic_ref_id ) {
										$attempts++;
										if ( ( isset( $quiz_attempt['pass'] ) ) && $quiz_attempt['pass'] > 0 ) {
											$pass++;
										}
										if ( ( isset( $quiz_attempt['percentage'] ) ) && $quiz_attempt['percentage'] ) {
											$counter++;
											$score += (float) $quiz_attempt['percentage'];
										}
										break;
									}

								}
							}
						}
						if ( $counter > 0 ) {
							$score /= $counter;
						}
					}
					foreach ( $student_progress as $key => $progress ) {
						if ( empty( $progress ) || 'sfwd-lessons:' . $request_data['lesson'] != $key ) {
							continue;
						}
						$completed_timestamp = $student_progress[ 'sfwd-lessons:' . $request_data['lesson'] ]['activity_completed'];
						if ( $is_pro_version_active ) {
							$completion_time = $time_tracking->fetch_user_average_lesson_completion_time( $request_data['lesson'], $student_id );
							$entry           = array(
								'name'                   => $student_info->display_name,
								'email'                  => $student_info->user_email,
								'status'                 => $student_progress[ 'sfwd-lessons:' . $request_data['lesson'] ]['activity_status'] ? __( 'Complete', 'learndash-reports-by-wisdmlabs' ) : __( 'Not Complete', 'learndash-reports-by-wisdmlabs' ),
								/* translators: 1: Steps Completed, 2: Total Steps */
								'steps'                  => sprintf( _x( '%1$d of %2$d', '1: Steps Completed, 2: Total Steps', 'learndash-reports-by-wisdmlabs' ), $student_progress_steps['completed'], $student_progress_steps['total'] ),
								'completion_rate'        => ! empty( $student_progress_steps['total'] ) ? floatval( number_format( 100 * $student_progress_steps['completed'] / $student_progress_steps['total'], 2, '.', '' ) ) . '%' : '100%',
								'date'                   => ! empty( $completed_timestamp ) ? date_i18n( 'd M, Y', $completed_timestamp ) : '-',
								'course_completion_time' => 0 == $completion_time ? '-' : date_i18n( 'H:i:s', $completion_time ),
								'attempts'               => $attempts,
								'pass_rate'              => empty( $attempts ) ? '-' : floatval( number_format( 100 * $pass / $attempts, 2, '.', '' ) ) . '%',
								'avg_score'              => empty( $score ) ? '-' : floatval( number_format( $score, 2, '.', '' ) ) . '%',
							);
						} else {
							$entry = array(
								'name'            => $student_info->display_name,
								'email'           => $student_info->user_email,
								'status'          => $student_progress[ 'sfwd-lessons:' . $request_data['lesson'] ]['activity_status'] ? __( 'Complete', 'learndash-reports-by-wisdmlabs' ) : __( 'Not Complete', 'learndash-reports-by-wisdmlabs' ),
								/* translators: 1: Steps Completed, 2: Total Steps */
								'steps'           => sprintf( _x( '%1$d of %2$d', '1: Steps Completed, 2: Total Steps', 'learndash-reports-by-wisdmlabs' ), $student_progress_steps['completed'], $student_progress_steps['total'] ),
								'completion_rate' => ! empty( $student_progress_steps['total'] ) ? floatval( number_format( 100 * $student_progress_steps['completed'] / $student_progress_steps['total'], 2, '.', '' ) ) . '%' : '100%',
								'date'            => ! empty( $completed_timestamp ) ? date_i18n( 'd M, Y', $completed_timestamp ) : '-',
								'time'            => ! empty( $completed_timestamp ) ? date_i18n( 'H:i:s', (int) $student_progress[ 'sfwd-lessons:' . $request_data['lesson'] ]['activity_completed'] - (int) $student_progress[ 'sfwd-lessons:' . $request_data['lesson'] ]['activity_started'] ) : '-',
								'attempts'        => $attempts,
								'pass_rate'       => empty( $attempts ) ? '-' : floatval( number_format( 100 * $pass / $attempts, 2, '.', '' ) ) . '%',
								'avg_score'       => empty( $score ) ? '-' : floatval( number_format( $score, 2, '.', '' ) ) . '%',
							);
						}

						$table[] = $entry;

						break;
					}
				}
				$response = array(
					'requestData' => self::get_values_for_request_params( $request_data ),
					'table'       => $table,
				);
				$current_timestamp = current_time( 'timestamp' );
				$wpdb->insert(
					$wpdb->prefix . 'wrld_cached_entries',
					array(
						'option_name'  => 'wrld_course_list_info_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
						'option_value' => maybe_serialize( $response ),
						'object_id'    => get_current_user_id(),
						'object_type'  => 'user',
						'created_on'   => $current_timestamp,
						'expires_on'   => $current_timestamp + DAY_IN_SECONDS
					),
					array(
						'%s',
						'%s',
						'%d',
						'%s',
						'%d',
						'%d',
					)
				);
				$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
				$response['updated_on'] = $updated_on;
				return new WP_REST_Response(
					$response,
					200
				);			
			}

			if ( ! empty( $request_data['course'] ) ) {
				$course_price_type = learndash_get_course_meta_setting( $request_data['course'], 'course_price_type' );
				if ( 'open' === $course_price_type ) {
					$response = new WP_Error(
						'no-data',
						sprintf(/* translators: %s: custom label for courses */
							__( 'Reports for open %s are not accessible for the time-being', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::get_label( 'courses' )
						),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					);
					$current_timestamp = current_time( 'timestamp' );
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_course_list_info_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
							'option_value' => maybe_serialize( $response ),
							'object_id'    => get_current_user_id(),
							'object_type'  => 'user',
							'created_on'   => $current_timestamp,
							'expires_on'   => $current_timestamp + DAY_IN_SECONDS
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d',
							'%d',
						)
					);
					$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
					return $response;
				}
				$students = get_transient( 'wrld_course_students_data_' . $request_data['course'] );
				if ( false === $students || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
					if ( get_option( 'migrated_course_access_data', false ) ) {
						$students = \WRLD_Quiz_Export_Db::instance()->get_users_for_course( $request_data['course'] );
					} else {
						$students      = learndash_get_users_for_course( $request_data['course'], array(), false );
					}
					$students = is_array( $students ) ? $students : $students->get_results();
					delete_transient( 'wrld_course_students_data_' . $request_data['course'] );
					set_transient( 'wrld_course_students_data_' . $request_data['course'], $students, 1 * HOUR_IN_SECONDS );
				}
				$students      = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, $students ) : $students;
				$students = array_diff( $students , $excluded_users );

				$student_count = is_array( $students ) ? count( $students ) : $students->get_total();

				if ( empty( $students ) ) {
					$response = new WP_Error(
						'no-data',
						sprintf(/* translators: %s: custom label for course */
							__( 'No Students enrolled in this %s.', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::label_to_lower( 'course' )
						),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					);
					$current_timestamp = current_time( 'timestamp' );
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_course_list_info_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
							'option_value' => maybe_serialize( $response ),
							'object_id'    => get_current_user_id(),
							'object_type'  => 'user',
							'created_on'   => $current_timestamp,
							'expires_on'   => $current_timestamp + DAY_IN_SECONDS
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d',
							'%d',
						)
					);
					$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
					return $response;
				}
				$table       = array();
				$quizzes     = learndash_course_get_children_of_step( $request_data['course'], $request_data['course'], 'sfwd-quiz', 'ids', true );
				$quizzes_str = implode( ', ', $quizzes );
				$quiz_count  = count( $quizzes );
				foreach ( $students as $student_id ) {
					$attempts = 0;
					$pass     = 0;
					$score    = 0;
					$counter  = 0;
					if ( 0 < $quiz_count ) {
						// $activity_query_args = array(
						// 	'post_ids'        => $quizzes_str,
						// 	'course_ids'      => $request_data['course'],
						// 	'user_ids'        => $student_id,
						// 	'activity_type'   => 'quiz',
						// 	'activity_status' => array( 'NOT_STARTED', 'IN_PROGRESS', 'COMPLETED' ),
						// 	'per_page'        => 1844674407370,
						// );
						// $quiz_activity       = learndash_reports_get_activity( $activity_query_args );
						// if ( ( isset( $quiz_activity['results'] ) ) && ( ! empty( $quiz_activity['results'] ) ) ) {
						// 	foreach ( $quiz_activity['results'] as $result ) {
						// 		$attempts++;
						// 		if ( ( isset( $result->activity_meta['pass'] ) ) && $result->activity_meta['pass'] ) {
						// 			$pass++;
						// 		}
						// 		if ( ( isset( $result->activity_meta['percentage'] ) ) && $result->activity_meta['percentage'] ) {
						// 			$counter++;
						// 			$score += (float) $result->activity_meta['percentage'];
						// 		}
						// 	}
						// }
						$where    = 'statref.quiz_post_id IN (' . $quizzes_str . ')';
						$where   .= ' AND statref.course_post_id="' . $request_data['course'] . '"';
						$where   .= ' AND statref.user_id="' . $student_id . '"';
						$orderby  = ' ORDER BY ' . $args['orderby'] . ' ' . $args['order'];
						$limit    = ' LIMIT ' . $args['offset'] . ', ' . (int) $args['limit'];
						$results = $wpdb->get_results( 'SELECT * FROM ' . esc_sql( LDLMS_DB::get_table_name( 'quiz_statistic_ref' ) ) . ' as statref WHERE ' . $where . $orderby . $limit );
						$quiz_attempts = count( $results );
						$usermeta = get_user_meta( $student_id, '_sfwd-quizzes', true );
						if ( ( isset( $results ) ) && ( ! empty( $results ) ) ) {
							foreach ( $results as $result ) {
								if ( empty( $usermeta ) ) {
									break;
								}
								foreach ( $usermeta as $quiz_attempt ) {
									if ( $quiz_attempt['statistic_ref_id'] == $result->statistic_ref_id ) {
										$attempts++;
										if ( ( isset( $quiz_attempt['pass'] ) ) && $quiz_attempt['pass'] > 0 ) {
											$pass++;
										}
										if ( ( isset( $quiz_attempt['percentage'] ) ) && $quiz_attempt['percentage'] ) {
											$counter++;
											$score += (float) $quiz_attempt['percentage'];
										}
										break;
									}

								}
							}
						}
						if ( $counter > 0 ) {
							$score /= $counter;
						}
					}
					$progress = learndash_user_get_course_progress( $student_id, $request_data['course'], 'summary' );
					// $student_progress = learndash_user_get_course_progress( $student_id, $request_data['course'], 'activity' );
					$student_info = get_userdata( $student_id );
					$percentage   = 100;
					/*
					if ( $student_progress[ 'sfwd-courses:' . $request_data['course'] ]['activity_status'] ) {
						$status = __( 'Completed', 'learndash-reports-by-wisdmlabs' );
					} elseif ( empty( $student_progress[ 'sfwd-courses:' . $request_data['course'] ]['activity_updated'] ) ) {
						$status = __( 'Not Started', 'learndash-reports-by-wisdmlabs' );
					} else {
						$status = __( 'In Progress', 'learndash-reports-by-wisdmlabs' );
					}*/
					if ( 0 != $progress['total'] ) {
						$percentage = floatval( number_format( 100 * $progress['completed'] / $progress['total'], 2, '.', '' ) );// Cast to integer if no decimals.
					}

					$since = ld_course_access_from( $request_data['course'], $student_id );
					if ( ! empty( $since ) ) {
						$since = learndash_adjust_date_time_display( $since, 'd M, Y' );
					} else {
						$since = learndash_user_group_enrolled_to_course_from( $student_id, $request_data['course'] );
						if ( ! empty( $since ) ) {
							$since = learndash_adjust_date_time_display( $since, 'd M, Y' );
						}
					}

					$completed = get_user_meta( $student_id, 'course_completed_' . $request_data['course'], true );
					if ( ! empty( $completed ) ) {
						$completed = learndash_adjust_date_time_display( $completed, 'd M, Y' );
					}

					if ( $is_pro_version_active ) {

						$time     = $time_tracking->fetch_user_course_time_spent( $request_data['course'], $student_id );
						$avg_time = $time_tracking->fetch_user_average_course_completion_time( $request_data['course'], $student_id );

						$table[] = array(
							'name'                   => $student_info->display_name,
							'email'                  => $student_info->user_email,
							// 'status'		  => $status,
							'started'                => ! empty( $since ) ? $since : '-',
							'course_progress'        => $percentage . '%',
							'completed'              => ! empty( $completed ) ? $completed : '-',
							'total_time_spent'       => 0 == $time ? '-' : date_i18n( 'H:i:s', $time ),
							'course_completion_time' => 0 == $avg_time ? '-' : date_i18n( 'H:i:s', $avg_time ),
							'quiz_attempts'          => $attempts,
							'pass_rate'              => empty( $attempts ) ? '-' : floatval( number_format( 100 * $pass / $attempts, 2, '.', '' ) ) . '%',
							'avg_score'              => empty( $score ) ? '-' : floatval( number_format( $score, 2, '.', '' ) ) . '%',
						);
					} else {
						$table[] = array(
							'name'            => $student_info->display_name,
							'email'           => $student_info->user_email,
							// 'status'		  => $status,
							'started'         => ! empty( $since ) ? $since : '-',
							'course_progress' => $percentage . '%',
							'completed'       => ! empty( $completed ) ? $completed : '-',
							'time_spent'      => date_i18n( 'H:i:s', $this->learndash_get_user_course_attempts_time_spent( $student_id, $request_data['course'] ) ),
							'quiz_attempts'   => $attempts,
							'pass_rate'       => empty( $attempts ) ? '-' : floatval( number_format( 100 * $pass / $attempts, 2, '.', '' ) ) . '%',
							'avg_score'       => empty( $score ) ? '-' : floatval( number_format( $score, 2, '.', '' ) ) . '%',
						);
					}
				}
				$response = array(
					'requestData' => self::get_values_for_request_params( $request_data ),
					'table'       => $table,
				);
				$current_timestamp = current_time( 'timestamp' );
				$wpdb->insert(
					$wpdb->prefix . 'wrld_cached_entries',
					array(
						'option_name'  => 'wrld_course_list_info_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
						'option_value' => maybe_serialize( $response ),
						'object_id'    => get_current_user_id(),
						'object_type'  => 'user',
						'created_on'   => $current_timestamp,
						'expires_on'   => $current_timestamp + DAY_IN_SECONDS
					),
					array(
						'%s',
						'%s',
						'%d',
						'%s',
						'%d',
						'%d',
					)
				);
				$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
				$response['updated_on'] = $updated_on;
				return new WP_REST_Response(
					$response,
					200
				);
			}

			$query_args         = array(
				'post_type'      => 'sfwd-courses',
				'posts_per_page' => '-1',
				'post__in'       => -1 == $accessible_courses ? null : $accessible_courses,
				'suppress_filters' => 0,
			);
			$category_str = '-';

			// Now consider the scenario where a category is selected. (Pro feature).
			if ( isset( $request_data['category'] ) && ! empty( $request_data['category'] ) ) {
				// Check if course category enabled.
				if ( ! taxonomy_exists( 'ld_course_category' ) ) {
					$response = new WP_Error(
						'invalid-input',
						sprintf(/* translators: %s: custom label for course */
							__( '%s Category disabled. Please contact admin.', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::get_label( 'course' )
						),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					);
					$current_timestamp = current_time( 'timestamp' );
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_course_list_info_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
							'option_value' => maybe_serialize( $response ),
							'object_id'    => get_current_user_id(),
							'object_type'  => 'user',
							'created_on'   => $current_timestamp,
							'expires_on'   => $current_timestamp + DAY_IN_SECONDS
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d',
							'%d',
						)
					);
					$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
					return $response;
				}
				// Check if valid category passed.
				if ( ! is_object( get_term_by( 'id', $request_data['category'], 'ld_course_category' ) ) ) {
					$response = new WP_Error(
						'invalid-input',
						sprintf(/* translators: %s: custom label for course */
							__( '%s Category doesn\'t exist', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::get_label( 'course' )
						),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					);
					$current_timestamp = current_time( 'timestamp' );
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_course_list_info_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
							'option_value' => maybe_serialize( $response ),
							'object_id'    => get_current_user_id(),
							'object_type'  => 'user',
							'created_on'   => $current_timestamp,
							'expires_on'   => $current_timestamp + DAY_IN_SECONDS
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d',
							'%d',
						)
					);
					$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
					return $response;
				}
				$query_args['tax_query'] = array(
					array(
						'taxonomy'         => 'ld_course_category',
						'field'            => 'term_id',
						'terms'            => $request_data['category'], // Where term_id of Term 1 is "1".
						'include_children' => false,
					),
				);
				$category_str      = get_term( $request_data['category'] )->name;
			}
			if ( isset( $request_data['group'] ) && ! empty( $request_data['group'] ) ) {
				$query_args['meta_query'] = array(
					array(
						'key'     => 'learndash_group_enrolled_' . $request_data['group'],
						'compare' => 'EXISTS',
					),
				);
			}
			$courses = get_posts( $query_args );
			// Check if any courses present in the category.
			if ( empty( $courses ) || ( ! is_null( $accessible_courses ) && -1 != $accessible_courses && empty( $accessible_courses ) ) ) {
				$response = new WP_Error(
					'no-data',
					sprintf(/* translators: %s: custom label for courses */
						__( 'No %s found', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'courses' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
				$current_timestamp = current_time( 'timestamp' );
				$wpdb->insert(
					$wpdb->prefix . 'wrld_cached_entries',
					array(
						'option_name'  => 'wrld_course_list_info_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
						'option_value' => maybe_serialize( $response ),
						'object_id'    => get_current_user_id(),
						'object_type'  => 'user',
						'created_on'   => $current_timestamp,
						'expires_on'   => $current_timestamp + DAY_IN_SECONDS
					),
					array(
						'%s',
						'%s',
						'%d',
						'%s',
						'%d',
						'%d',
					)
				);
				$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
				return $response;
			}
			$table        = array();
			$course_count = count( $courses );
			$user         = wp_get_current_user();
			foreach ( $courses as $course ) {
				if ( '-' == $category_str ) {
					$categories = wp_get_object_terms( $course->ID, 'ld_course_category' );
					if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
						$categories   = wp_list_pluck( $categories, 'name' );
						$category_str = implode( ', ', $categories );
					}
				}

				$groups_str = '-';
				$group_ids  = learndash_get_course_groups( $course->ID );
				if ( ! current_user_can( 'manage_options' ) ) {
					if ( in_array( 'group_leader', (array) $user->roles, true ) ) {
						$associated_groups = learndash_get_administrators_group_ids( get_current_user_id() );
						foreach ( $group_ids as $key => $group ) {
							if ( ! in_array( $group, $associated_groups ) ) {
								unset( $group_ids[ $key ] );
							}
						}
					}
				}
				if ( ! empty( $group_ids ) ) {
					$groups     = array_map(
						function( $group_id ) {
							return str_replace( '&#8211;', '-', get_the_title( $group_id ) );
						},
						$group_ids
					);
					$groups_str = implode( ', ', $groups );
				}

				if ( function_exists( 'ir_get_instructor_complete_course_list' ) ) {
					// Get list of co-instructors.
					$co_instructor_list = get_post_meta( $course->ID, 'ir_shared_instructor_ids', 1 );
					$all_instructor_ids = explode( ',', $co_instructor_list );

					// Include course author.
					array_unshift( $all_instructor_ids, $course->post_author );

					// Remove any duplicates.
					$all_instructor_ids = array_filter( array_unique( $all_instructor_ids ) );
					$instructors        = array_map(
						function( $instructor_id ) {
							return get_userdata( $instructor_id )->display_name;
						},
						$all_instructor_ids
					);
					$instructor         = implode( ', ', $instructors );
				} else {
					$instructor = get_userdata( $course->post_author )->display_name;
				}
				$course_price_type = learndash_get_course_meta_setting( $course->ID, 'course_price_type' );
				if ( 'open' === $course_price_type ) {
					continue;
				}
				$students = get_transient( 'wrld_course_students_data_' . $course->ID );
				if ( false === $students || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
				// Get all students for a course.
					if ( get_option( 'migrated_course_access_data', false ) ) {
						$students = \WRLD_Quiz_Export_Db::instance()->get_users_for_course( $course->ID );
					} else {
						$students = learndash_get_users_for_course( $course->ID, array(), false ); // Third argument is $exclude_admin.
					}
					$students = is_array( $students ) ? $students : $students->get_results();
					delete_transient( 'wrld_course_students_data_' . $request_data['course'] );
					set_transient( 'wrld_course_students_data_' . $course->ID, $students, 1 * HOUR_IN_SECONDS );
				}
				$students      = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, $students ) : $students;
				$students = array_diff( $students , $excluded_users );

				$student_count = is_array( $students ) ? count( $students ) : $students->get_total();

				$quizzes           = learndash_course_get_children_of_step( $course->ID, $course->ID, 'sfwd-quiz', 'ids', true );
				$quiz_count        = count( $quizzes );
				$time              = 0;
				$avg_time          = 0;
				$quiz_time         = 0;
				$quiz_average_time = 0;
				$not_started_count = 0;
				$in_progress_count = 0;
				$completed_count   = 0;
				$score             = 0;
				$counter           = 0;
				if ( 0 < $quiz_count ) {
					$quizzes_str         = implode( ', ', $quizzes );
					// $activity_query_args = array(
					// 	'post_ids'        => $quizzes_str,
					// 	'course_ids'      => $course->ID,
					// 	'activity_type'   => 'quiz',
					// 	'activity_status' => array( 'NOT_STARTED', 'IN_PROGRESS', 'COMPLETED' ),
					// 	'per_page'        => 1844674407370,
					// );
					// $quiz_activity       = learndash_reports_get_activity( $activity_query_args );
					// if ( ( isset( $quiz_activity['results'] ) ) && ( ! empty( $quiz_activity['results'] ) ) ) {
					// 	foreach ( $quiz_activity['results'] as $result ) {
					// 		if ( ( isset( $result->activity_meta['percentage'] ) ) && $result->activity_meta['percentage'] ) {
					// 			$counter++;
					// 			$score += (float) $result->activity_meta['percentage'];
					// 		}
					// 	}
					// }
					$where    = 'statref.quiz_post_id IN (' . $quizzes_str . ')';
					$where   .= ' AND statref.course_post_id="' . $course->ID . '"';
					// $where   .= ' AND statref.user_id="' . $student_id . '"';
					$orderby  = ' ORDER BY ' . $args['orderby'] . ' ' . $args['order'];
					$limit    = ' LIMIT ' . $args['offset'] . ', ' . (int) $args['limit'];
					$results = $wpdb->get_results( 'SELECT * FROM ' . esc_sql( LDLMS_DB::get_table_name( 'quiz_statistic_ref' ) ) . ' as statref WHERE ' . $where . $orderby . $limit );
					$quiz_attempts = count( $results );
					if ( ( isset( $results ) ) && ( ! empty( $results ) ) ) {
						foreach ( $results as $result ) {
							$usermeta = get_user_meta( $result->user_id, '_sfwd-quizzes', true );
							if ( empty( $usermeta ) ) {
								break;
							}
							foreach ( $usermeta as $quiz_attempt ) {
								if ( $quiz_attempt['statistic_ref_id'] == $result->statistic_ref_id ) {
									if ( ( isset( $quiz_attempt['percentage'] ) ) && $quiz_attempt['percentage'] ) {
										$counter++;
										$score += (float) $quiz_attempt['percentage'];
									}
									break;
								}

							}
						}
					}
					if ( $counter > 0 ) {
						$score /= $counter;
					}
				}
				if ( $student_count > 0 ) {
					foreach ( $students as $student_id ) {

						$status = learndash_user_get_course_progress( $student_id, $course->ID, 'summary' );
						if ( $is_pro_version_active ) {
							$time     = $time + $time_tracking->fetch_user_course_time_spent( $course->ID, $student_id );
							$avg_time = $avg_time + $time_tracking->fetch_user_average_course_completion_time( $course->ID, $student_id );
						} else {
							$time      = $time + $this->learndash_get_user_course_attempts_time_spent( $student_id, $course->ID );
							 $avg_time = 123456789;// configure average time method
						}
						if ( empty( $status ) ) {
							$not_started_count++;
						} else {
							switch ( $status['status'] ) {
								case 'in_progress':
									$in_progress_count++;
									break;
								case 'completed':
									$completed_count++;
									break;
								case 'not_started':
								default:
									$not_started_count++;
									break;
							}
						}
					}
				}
				if ( ! empty( $quizzes ) && 0 < $student_count ) {
					foreach ( $students as $student_id ) {
						foreach ( $quizzes as $quiz ) {
							$quiz_time = $quiz_time + $this->learndash_get_user_quiz_attempts_time_spent( $student_id, $quiz );
						}
					}
					$quiz_average_time = floatval( number_format( $quiz_time / $student_count, 2, '.', '' ) );// Cast to integer if no decimals.
				}
				$table_data = array(
					'course' => $course->post_title,
				);
				if ( ! isset( $request_data['category'] ) || empty( $request_data['category'] ) ) {
					$table_data['category'] = $category_str;
					$category_str           = '-';
				}

				$avg_time = 0 == $completed_count ? 0 : floatval( number_format( $avg_time / $completed_count, 2, '.', '' ) );
				$time     = 0 == $student_count ? 0 : floatval( number_format( $time / $student_count, 2, '.', '' ) );

				if ( $is_pro_version_active ) {
					$arraydata = array(
						/* translators: %1$d: Completed Count %2$d: Total Student count */
						'completed_users'      => sprintf( __( '%1$d of %2$d', 'learndash-reports-by-wisdmlabs' ), $completed_count, $student_count ),
						'in_progress'          => $in_progress_count,
						'not_started'          => $not_started_count,
						'completion_rate2'     => empty( $student_count ) ? '-' : floatval( number_format( 100 * $completed_count / $student_count, 2, '.', '' ) ) . '%',
						'avg_total_time_spent' => 0 == $time ? '-' : sprintf( '%02d:%02d:%02d', ( $time / 3600 ), ( $time / 60 % 60 ), $time % 60 ),
						'avg_time_spent'       => 0 == $avg_time ? '-' : date_i18n( 'H:i:s', $avg_time ),
						'avg_score'            => floatval( number_format( $score, 2, '.', '' ) ) . '%',
						'quizzes'              => $quiz_count,
						'quiz_time'            => date_i18n( 'H:i:s', $quiz_average_time ),
						'groups'               => $groups_str,
						'instructors'          => $instructor,
					);
				} else {
					$arraydata = array(
						/* translators: %1$d: Completed Count %2$d: Total Student count */
						'completed_users'  => sprintf( __( '%1$d of %2$d', 'learndash-reports-by-wisdmlabs' ), $completed_count, $student_count ),
						'in_progress'      => $in_progress_count,
						'not_started'      => $not_started_count,
						'completion_rate2' => empty( $student_count ) ? '-' : floatval( number_format( 100 * $completed_count / $student_count, 2, '.', '' ) ) . '%',
						'total_time_spent' => date_i18n( 'H:i:s', $time ),
						'avg_score'        => floatval( number_format( $score, 2, '.', '' ) ) . '%',
						'quizzes'          => $quiz_count,
						'quiz_time'        => date_i18n( 'H:i:s', $quiz_average_time ),
						'groups'           => $groups_str,
						'instructors'      => $instructor,
					);
				}

				$table_data = array_merge(
					$table_data,
					$arraydata
				);
				$table[]    = $table_data;
			}
			if ( $course_count <= 0 ) {
				$response = new WP_Error(
					'no-data',
					sprintf(/* translators: %s: custom label for courses */
						__( 'No %s found', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'courses' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
				$current_timestamp = current_time( 'timestamp' );
				$wpdb->insert(
					$wpdb->prefix . 'wrld_cached_entries',
					array(
						'option_name'  => 'wrld_course_list_info_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
						'option_value' => maybe_serialize( $response ),
						'object_id'    => get_current_user_id(),
						'object_type'  => 'user',
						'created_on'   => $current_timestamp,
						'expires_on'   => $current_timestamp + DAY_IN_SECONDS
					),
					array(
						'%s',
						'%s',
						'%d',
						'%s',
						'%d',
						'%d',
					)
				);
				$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
				return $response;
			}

			$response = array(
				'requestData' => self::get_values_for_request_params( $request_data ),
				'table'       => $table,
			);
			$current_timestamp = current_time( 'timestamp' );
			$wpdb->insert(
				$wpdb->prefix . 'wrld_cached_entries',
				array(
					'option_name'  => 'wrld_course_list_info_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
					'option_value' => maybe_serialize( $response ),
					'object_id'    => get_current_user_id(),
					'object_type'  => 'user',
					'created_on'   => $current_timestamp,
					'expires_on'   => $current_timestamp + DAY_IN_SECONDS
				),
				array(
					'%s',
					'%s',
					'%d',
					'%s',
					'%d',
					'%d',
				)
			);
			$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
			$response['updated_on'] = $updated_on;
			return new WP_REST_Response(
				$response,
				200
			);			
		}

		$updated_on = date_i18n( 'Y-m-d H:i:s', $time_spent_row->created_on );
		$response = maybe_unserialize( $time_spent_row->option_value );
		if ( is_wp_error( $response ) ) {
			return $response;
		}
		$response['updated_on'] = $updated_on;
		return new WP_REST_Response(
			$response,
			200
		);
	}

	public function get_course_progress_rate_csv() {
		// Get Inputs.
		$request_data = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING );
		$request_data = self::get_request_params( $request_data );
		$user_role_access   = self::get_current_user_role_access();
		do_action( 'wpml_switch_language',  $_GET[ 'wpml_lang' ] ); // switch the content language
		$accessible_courses = self::get_accessible_courses_for_the_user( get_current_user_id(), $user_role_access, 'course_completion_rate' );
		$accessible_users   = self::get_accessible_users_for_the_user( get_current_user_id(), $user_role_access, 'course_completion_rate' );
		$excluded_users     = get_option( 'exclude_users', array() );
		if ( empty( $excluded_users ) || ! defined( 'LDRP_PLUGIN_VERSION' ) ) {
			$excluded_users = array();
		}
		if ( isset( $request_data['group'] ) && ! empty( $request_data['group'] ) ) {
			$group_users = get_transient( 'wrld_group_students_data_' . $request_data['group'] );
			if ( false === $group_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
				// Get all students for a course.
				if ( get_option( 'migrated_group_access_data', false ) ) {
					// $group_users = array_unique( array_merge( $group_users, \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] ) ) );
					$group_users = \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] );
				} else {
					$group_users = self::get_ld_group_user_ids( $request_data['group'] );
				}
				delete_transient( 'wrld_group_students_data_' . $request_data['group'] );
				set_transient( 'wrld_group_students_data_' . $request_data['group'], $group_users, 1 * HOUR_IN_SECONDS );
			}
			$accessible_users = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $group_users, $accessible_users ) : $group_users;
		}
		if ( isset( $request_data['learner'] ) && ! empty( $request_data['learner'] ) ) {
			$learner_courses    = learndash_user_get_enrolled_courses( $request_data['learner'], array(), false );
			$accessible_courses = ( ! is_null( $accessible_courses ) && -1 != $accessible_courses ) ? array_intersect( $accessible_courses, $learner_courses ) : $learner_courses;
			$accessible_users   = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, array( $request_data['learner'] ) ) : $request_data['learner'];

			if ( empty( $accessible_courses ) ) {
				return new WP_Error(
					'no-data',
					sprintf(/* translators: %s: custom label for courses */
						__( 'No accessible %s found', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'courses' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}

			$total_percentage  = 0;
			$progress_data     = array();
			$completed_count   = 0;
			$not_started_count = 0;
			$in_progress_count = 0;
			$upto_20 = 0;
			$upto_40 = 0;
			$upto_60 = 0;
			$upto_80 = 0;
			$upto_100 = 0;

			$course_count = count( $accessible_courses );

			foreach ( $accessible_courses as $course_id ) {
				if ( isset( $request_data['duration'] ) && 'all' != $request_data['duration'] ) {
					$start_date = strtotime( gmdate( 'Y-m-d', strtotime( '-' . $request_data['duration'] ) ) );
					$end_date = current_time( 'timestamp' );
					// $enrolled_on = get_user_meta( $student, 'course_' . $course->ID . '_access_from', true );
					$enrolled_on = ld_course_access_from( $course_id, $request_data['learner'] );
					if ( empty( $enrolled_on ) ) {
						$enrolled_on = learndash_user_group_enrolled_to_course_from( $request_data['learner'], $course_id );
					}
					if ( empty( $enrolled_on ) ) {
						$course_count--;
						continue;
					}
					if ( (int) $enrolled_on < $start_date || (int) $enrolled_on > $end_date ) {
						$course_count--;
						continue;
					}
				}
				$progress = learndash_user_get_course_progress( $request_data['learner'], $course_id, 'summary' );
				if ( empty( $progress ) || ! isset( $progress['total'] ) || 0 == $progress['total'] ) {
					$percentage = 0;
					$not_started_count++;
					$upto_20++;
				} else {
					$percentage = floatval( number_format( 100 * $progress['completed'] / $progress['total'], 2, '.', '' ) );// Cast to integer if no decimals.
					if ( 100 == $percentage ) {
						$completed_count++;
					} elseif ( 0 == $percentage ) {
						$not_started_count++;
					} else {
						$in_progress_count++;
					}
					if ( $percentage > 80 && $percentage <= 100 ) {
						$upto_100++;
					} elseif ( $percentage > 60 && $percentage <= 80 ) {
						$upto_80++;
					} elseif ( $percentage > 40 && $percentage <= 60 ) {
						$upto_60++;
					} elseif ( $percentage > 20 && $percentage <= 40 ) {
						$upto_40++;
					} elseif ( $percentage >= 0 && $percentage <= 20 ) {
						$upto_20++;
					}
				}
				$total_percentage            = 0 <= $percentage ? $total_percentage + $percentage : $total_percentage;
			}

			if ( $course_count <= 0 ) {
				return new WP_Error(
					'no-data',
					__( 'No data available for the selected duration and/or filters', 'learndash-reports-by-wisdmlabs' ),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}

			// Calculate average across courses.
			$average_completion_percentage = 0;
			if ( $course_count > 0 ) {
				$average_completion_percentage = floatval( number_format( $total_percentage / $course_count, 2, '.', '' ) );
			}
			//csv logic for learner here
			// return new WP_REST_Response(
			// 	array(
			// 		'requestData'             	=> self::get_values_for_request_params( $request_data ),
			// 		'upto_20'   				=> $upto_20,
			// 		'upto_40'   				=> $upto_40,
			// 		'upto_60'   				=> $upto_60,
			// 		'upto_80'  					=> $upto_80,
			// 		'upto_100'   				=> $upto_100,
			// 		'averageCourseCompletion' => $average_completion_percentage,
			// 		'course_count'            => $course_count,
			// 		'completedCount'          => $completed_count,
			// 		'notstartedCount'         => $not_started_count,
			// 		'inprogressCount'         => $in_progress_count,
			// 	),
			// 	200
			// );
		$upload_dir = wp_upload_dir();
		if ( ! file_exists( $upload_dir['basedir'] . '/learndash_reports' ) ) {
			mkdir( $upload_dir['basedir'] . '/learndash_reports' );
		}
		$file      = fopen( $upload_dir['basedir'] . '/learndash_reports' . '/' . 'course_progress.csv', 'w' );
		// $file      = fopen( 'php://output', 'w' );
		// $file = fopen(filename, mode)
		// Checks if file opened on php output stream
		if ( $file ) {
			// For each row of the table in Data recieved.
			$th = array(
				__( 'Learner: ', 'learndash-reports-by-wisdmlabs' ) . get_userdata($request_data['learner'])->display_name,
				__( 'Duration: ', 'learndash-reports-by-wisdmlabs' ) . $request_data['duration']
			);
			// Inserts Heading into the csv file.
			if ( ! empty( $th ) ) {
				fputcsv( $file, $th );
			}
			fputcsv($file, array());
			fputcsv($file, array('Courses with Progress in below ranges:'));
			$th2 = array(
				'0% - 20%',
				'21% - 40%',
				'41% - 60%',
				'61% - 80%',
				'81% - 100%',
			);
			// Inserts Heading into the csv file.
			if ( ! empty( $th2 ) ) {
				fputcsv( $file, $th2 );
			}
			fputcsv($file, array($upto_20, $upto_40, $upto_60, $upto_80, $upto_100));
			// For cell's value.
			// foreach ($course_data as $course => $progress) {
			// 	$td = array();
			// 	$td = array( $course, $progress['percentage'] );
			// 	fputcsv( $file, $td );
			// }
			// Closes the Csv file.
			fclose( $file );
		} else {
			esc_html_e( 'File Permission Issue!!!', 'learndash-reports-pro' );
		}
		return new WP_REST_Response(
			array(
				'filename' => $upload_dir['baseurl'] . '/learndash_reports' . '/' . 'course_progress.csv'
			),
			200
		);

		} elseif ( isset( $request_data['course'] ) && ! empty( $request_data['course'] ) ) {
			if ( ( ! is_null( $accessible_courses ) && -1 != $accessible_courses && empty( $accessible_courses ) ) ) {
				return new WP_Error(
					'no-data',
					sprintf(/* translators: %s: custom label for courses */
						__( 'No %s found', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'courses' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}
			if ( ! is_null( $accessible_courses ) && -1 != $accessible_courses && ! in_array( $request_data['course'], $accessible_courses ) ) {
				return new WP_Error(
					'no-data',
					sprintf(/* translators: %s: custom label for courses */
						__( 'You donot have sufficient access to view this %s reports', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'courses' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}
			$course = $request_data['course'];
			$course_price_type = learndash_get_course_meta_setting( $course, 'course_price_type' );
			if ( 'open' === $course_price_type ) {
				return new WP_Error(
					'no-data',
					sprintf(/* translators: %s: custom label for courses */
						__( 'You cannot view reports for the open %s for the time-being', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'courses' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}
			$students = get_transient( 'wrld_course_students_data_' . $course );
			if ( false === $students || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
			// Get all students for a course.
				if ( get_option( 'migrated_course_access_data', false ) ) {
					$students = \WRLD_Quiz_Export_Db::instance()->get_users_for_course( $course );
				} else {
					$students = learndash_get_users_for_course( $course, array(), false ); // Third argument is $exclude_admin.
				}
				$students = is_array( $students ) ? $students : $students->get_results();
				delete_transient( 'wrld_course_students_data_' . $course );
				set_transient( 'wrld_course_students_data_' . $course, $students, 1 * HOUR_IN_SECONDS );
			}
			$students = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, $students ) : $students;
			$students = array_diff( $students , $excluded_users );
			
			$upto_20 = 0;
			$upto_40 = 0;
			$upto_60 = 0;
			$upto_80 = 0;
			$upto_100 = 0;
			$percentage = 0;
			if ( empty( $students ) ) {
				// return new WP_REST_Response(
				// 	array(
				// 		'requestData' => self::get_values_for_request_params( $request_data ),
				// 		'percentage' => $percentage,
				// 		'upto_20'   => $upto_20,
				// 		'upto_40'   => $upto_40,
				// 		'upto_60'   => $upto_60,
				// 		'upto_80'   => $upto_80,
				// 		'upto_100'   => $upto_100,
				// 	),
				// 	200
				// );
				//csv logic
					$upload_dir = wp_upload_dir();
				if ( ! file_exists( $upload_dir['basedir'] . '/learndash_reports' ) ) {
					mkdir( $upload_dir['basedir'] . '/learndash_reports' );
				}
				$file      = fopen( $upload_dir['basedir'] . '/learndash_reports' . '/' . 'course_progress.csv', 'w' );
				// $file      = fopen( 'php://output', 'w' );
				// $file = fopen(filename, mode)
				// Checks if file opened on php output stream
				if ( $file ) {
					// For each row of the table in Data recieved.
					$th = array(
						__( 'Course Category: ', 'learndash-reports-by-wisdmlabs' ) . ( ! empty( $request_data['category'] ) ? get_term( (int) $request_data['category'], 'ld_course_category' )->name : __( 'All', 'learndash-reports-by-wisdmlabs' ) ),
						__( 'Group: ', 'learndash-reports-by-wisdmlabs' ) . ( ! empty( $request_data['group'] ) ? get_the_title( $request_data['group'] ) : __( 'All', 'learndash-reports-by-wisdmlabs' ) ),
						__( 'Course: ', 'learndash-reports-by-wisdmlabs' ) . ( ! empty( $request_data['course'] ) ? get_the_title( $request_data['course'] ) : __( 'All', 'learndash-reports-by-wisdmlabs' ) ),
						__( 'Duration: ', 'learndash-reports-by-wisdmlabs' ) . $request_data['duration']
					);
					// Inserts Heading into the csv file.
					if ( ! empty( $th ) ) {
						fputcsv( $file, $th );
					}
					fputcsv( $file, array() );
					fputcsv($file, array('Learners with Progress in below ranges:'));
					$th2 = array(
						'0% - 20%',
						'21% - 40%',
						'41% - 60%',
						'61% - 80%',
						'81% - 100%',
					);
					// Inserts Heading into the csv file.
					if ( ! empty( $th2 ) ) {
						fputcsv( $file, $th2 );
					}
					fputcsv($file, array(0, 0, 0, 0, 0));
					// For cell's value.
					// foreach ($course_data as $course => $progress) {
					// 	$td = array();
					// 	$td = array( $course, $progress['percentage'] );
					// 	fputcsv( $file, $td );
					// }
					// Closes the Csv file.
					fclose( $file );
				} else {
					esc_html_e( 'File Permission Issue!!!', 'learndash-reports-pro' );
				}
				return new WP_REST_Response(
					array(
						'filename' => $upload_dir['baseurl'] . '/learndash_reports' . '/' . 'course_progress.csv'
					),
					200
				);
			}
			
			// $all_students = array_unique( array_merge( $all_students, $students ) );
			$class_size       = is_array( $students ) ? count( $students ) : $students->get_total();
			$total_completion = 0; 
			// If no students in the course then the course has 0 percent completion.
			foreach ( $students as $student ) {
				// Get course progress info.
				if ( isset($request_data['duration']) && 'all' != $request_data['duration'] ) {
					$start_date = strtotime( gmdate( 'Y-m-d', strtotime( '-' . $request_data['duration'] ) ) );
					$end_date = current_time( 'timestamp' );
					// $enrolled_on = get_user_meta( $student, 'course_' . $course->ID . '_access_from', true );
					$enrolled_on = ld_course_access_from( $course, $student );
					if ( empty( $enrolled_on ) ) {
						$enrolled_on = learndash_user_group_enrolled_to_course_from( $student, $course );
					}
					if ( empty( $enrolled_on ) ) {
						$class_size--;
						continue;
					}
					if ( (int) $enrolled_on < $start_date || (int) $enrolled_on > $end_date ) {
						$class_size--;
						continue;
					}
				}
				$progress = learndash_user_get_course_progress( $student, $course, 'summary' );
				if ( ! isset( $progress['total'] ) || empty( $progress['total'] ) ) {
					$upto_20++;
					continue;
				}
				$completion = floatval( number_format( 100 * $progress['completed'] / $progress['total'], 2, '.', '' ) );// Cast to integer if no decimals.
				$total_completion += $completion;
				if ( $completion > 80 && $completion <= 100 ) {
					$upto_100++;
				} elseif ( $completion > 60 && $completion <= 80 ) {
					$upto_80++;
				} elseif ( $completion > 40 && $completion <= 60 ) {
					$upto_60++;
				} elseif ( $completion > 20 && $completion <= 40 ) {
					$upto_40++;
				} elseif ( $completion >= 0 && $completion <= 20 ) {
					$upto_20++;
				}
			}
			if ( $class_size <= 0 ) {
				return new WP_Error(
					'no-data',
					__( 'No data available for the selected duration and/or filters.', 'learndash-reports-by-wisdmlabs' ),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}
			$percentage = floatval( number_format( $total_completion / $class_size, 2, '.', '' ) );
			// CSV logic for course tab here
			// return new WP_REST_Response(
			// 	array(
			// 		'requestData' => self::get_values_for_request_params( $request_data ),
			// 		'percentage' => $percentage,
			// 		'upto_20'   => $upto_20,
			// 		'upto_40'   => $upto_40,
			// 		'upto_60'   => $upto_60,
			// 		'upto_80'   => $upto_80,
			// 		'upto_100'   => $upto_100,
			// 	),
			// 	200
			// );
				$upload_dir = wp_upload_dir();
				if ( ! file_exists( $upload_dir['basedir'] . '/learndash_reports' ) ) {
					mkdir( $upload_dir['basedir'] . '/learndash_reports' );
				}
				$file      = fopen( $upload_dir['basedir'] . '/learndash_reports' . '/' . 'course_progress.csv', 'w' );
				// $file      = fopen( 'php://output', 'w' );
				// $file = fopen(filename, mode)
				// Checks if file opened on php output stream
				if ( $file ) {
					// For each row of the table in Data recieved.
					$th = array(
						__( 'Course Category: ', 'learndash-reports-by-wisdmlabs' ) . ( ! empty( $request_data['category'] ) ? get_term( (int) $request_data['category'], 'ld_course_category' )->name : __( 'All', 'learndash-reports-by-wisdmlabs' ) ),
						__( 'Group: ', 'learndash-reports-by-wisdmlabs' ) . ( ! empty( $request_data['group'] ) ? get_the_title( $request_data['group'] ) : __( 'All', 'learndash-reports-by-wisdmlabs' ) ),
						__( 'Course: ', 'learndash-reports-by-wisdmlabs' ) . ( ! empty( $request_data['course'] ) ? get_the_title( $request_data['course'] ) : __( 'All', 'learndash-reports-by-wisdmlabs' ) ),
						__( 'Duration: ', 'learndash-reports-by-wisdmlabs' ) . $request_data['duration']
					);
					// Inserts Heading into the csv file.
					if ( ! empty( $th ) ) {
						fputcsv( $file, $th );
					}
					fputcsv( $file, array() );
					fputcsv($file, array('Learners with Progress in below ranges:'));
					$th2 = array(
						'0% - 20%',
						'21% - 40%',
						'41% - 60%',
						'61% - 80%',
						'81% - 100%',
					);
					// Inserts Heading into the csv file.
					if ( ! empty( $th2 ) ) {
						fputcsv( $file, $th2 );
					}
					fputcsv($file, array($upto_20, $upto_40, $upto_60, $upto_80, $upto_100));
					// For cell's value.
					// foreach ($course_data as $course => $progress) {
					// 	$td = array();
					// 	$td = array( $course, $progress['percentage'] );
					// 	fputcsv( $file, $td );
					// }
					// Closes the Csv file.
					fclose( $file );
				} else {
					esc_html_e( 'File Permission Issue!!!', 'learndash-reports-pro' );
				}
				return new WP_REST_Response(
					array(
						'filename' => $upload_dir['baseurl'] . '/learndash_reports' . '/' . 'course_progress.csv'
					),
					200
				);
		} elseif ( isset( $request_data['category'] ) && ! empty( $request_data['category'] ) ) {
			// Check if course category enabled.
			if ( ! taxonomy_exists( 'ld_course_category' ) ) {
				return new WP_Error(
					'invalid-input',
					sprintf(/* translators: %s: custom label for course */
						__( '%s Category disabled. Please contact admin.', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'course' ),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					)
				);
			}
			// Check if valid category passed.
			if ( ! is_object( get_term_by( 'id', $request_data['category'], 'ld_course_category' ) ) ) {
				return new WP_Error(
					'invalid-input',
					sprintf(/* translators: %s: custom label for course */
						__( '%s Category doesn\'t exist', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'course' ),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					)
				);
			}
			$category_query = array(
				array(
					'taxonomy'         => 'ld_course_category',
					'field'            => 'term_id',
					'terms'            => $request_data['category'], // Where term_id of Term 1 is "1".
					'include_children' => false,
				),
			);
		} elseif ( isset( $request_data['group'] ) && ! empty( $request_data['group'] ) ) {
			$group_courses      = learndash_group_enrolled_courses( $request_data['group'] );
			$accessible_courses = ( ! is_null( $accessible_courses ) && -1 != $accessible_courses ) ? array_intersect( $group_courses, $accessible_courses ) : $group_courses;
			$group_users = get_transient( 'wrld_group_students_data_' . $request_data['group'] );
			if ( false === $group_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
				// Get all students for a course.
				if ( get_option( 'migrated_group_access_data', false ) ) {
					// $group_users = array_unique( array_merge( $group_users, \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] ) ) );
					$group_users = \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] );
				} else {
					$group_users = self::get_ld_group_user_ids( $request_data['group'] );
				}
				delete_transient( 'wrld_group_students_data_' . $request_data['group'] );
				set_transient( 'wrld_group_students_data_' . $request_data['group'], $group_users, 1 * HOUR_IN_SECONDS );
			}
			$accessible_users   = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $group_users, $accessible_users ) : $group_users;
		}

		$query_args = array(
			'post_type'      => 'sfwd-courses',
			'posts_per_page' => -1,
			'post__in'       => -1 == $accessible_courses ? null : $accessible_courses,
			'suppress_filters' => 0,
		);

		if ( ! empty( $category_query ) ) {
			$query_args['tax_query'] = $category_query;
		}

		$courses      = get_posts( $query_args );
		$course_data = array();
		$avg_percentage = 0;
		// Check if any courses present in the category.
		if ( empty( $courses ) || ( ! is_null( $accessible_courses ) && -1 != $accessible_courses && empty( $accessible_courses ) ) ) {
			return new WP_Error(
				'no-data',
				sprintf(/* translators: %s: custom label for courses */
					__( 'No %s found', 'learndash-reports-by-wisdmlabs' ),
					\LearnDash_Custom_Label::get_label( 'courses' )
				),
				array( 'requestData' => self::get_values_for_request_params( $request_data ) )
			);
		}
		$course_count = count( $courses );
		foreach ( $courses as $course ) {
			$course_price_type = learndash_get_course_meta_setting( $course->ID, 'course_price_type' );
			if ( 'open' === $course_price_type ) {
				continue;
			}
			$students = get_transient( 'wrld_course_students_data_' . $course->ID );
			if ( false === $students || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
			// Get all students for a course.
				if ( get_option( 'migrated_course_access_data', false ) ) {
					$students = \WRLD_Quiz_Export_Db::instance()->get_users_for_course( $course->ID );
				} else {
					$students = learndash_get_users_for_course( $course->ID, array(), false ); // Third argument is $exclude_admin.
				}
				$students = is_array( $students ) ? $students : $students->get_results();
				delete_transient( 'wrld_course_students_data_' . $course->ID );
				set_transient( 'wrld_course_students_data_' . $course->ID, $students, 1 * HOUR_IN_SECONDS );
			}
			$students = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, $students ) : $students;
			$students = array_diff( $students , $excluded_users );
			
			// $all_students = array_unique( array_merge( $all_students, $students ) );
			$class_size = is_array( $students ) ? count( $students ) : $students->get_total();
			$upto_20 = 0;
			$upto_40 = 0;
			$upto_60 = 0;
			$upto_80 = 0;
			$upto_100 = 0;
			$percentage = 0;
			$course_title = get_the_title( $course->ID );
			// If no students in the course then the course has 0 percent completion.
			if ( empty( $students ) ) {
				$course_data[ $course_title ] = array(
					'percentage' => $percentage,
					'upto_20'   => $upto_20,
					'upto_40'   => $upto_40,
					'upto_60'   => $upto_60,
					'upto_80'   => $upto_80,
					'upto_100'   => $upto_100,
					'total'      => $class_size
				);
				continue;
			}
			$total_completion = 0;
			foreach ( $students as $student ) {
				// Get course progress info.
				if ( isset($request_data['duration']) && 'all' != $request_data['duration'] ) {
					$start_date = strtotime( gmdate( 'Y-m-d', strtotime( '-' . $request_data['duration'] ) ) );
					$end_date = current_time( 'timestamp' );
					// $enrolled_on = get_user_meta( $student, 'course_' . $course->ID . '_access_from', true );
					$enrolled_on = ld_course_access_from( $course->ID, $student );
					if ( empty( $enrolled_on ) ) {
						$enrolled_on = learndash_user_group_enrolled_to_course_from( $student, $course->ID );
					}
					if ( empty( $enrolled_on ) ) {
						$class_size--;
						continue;
					}
					if ( (int) $enrolled_on < $start_date || (int) $enrolled_on > $end_date ) {
						$class_size--;
						continue;
					}
				}
				$progress = learndash_user_get_course_progress( $student, $course->ID, 'summary' );
				if ( ! isset( $progress['total'] ) || empty( $progress['total'] ) ) {
					$upto_20++;
					continue;
				}
				$completion = floatval( number_format( 100 * $progress['completed'] / $progress['total'], 2, '.', '' ) );// Cast to integer if no decimals.
				$total_completion += $completion;
				if ( $completion > 80 && $completion <= 100 ) {
					$upto_100++;
				} elseif ( $completion > 60 && $completion <= 80 ) {
					$upto_80++;
				} elseif ( $completion > 40 && $completion <= 60 ) {
					$upto_60++;
				} elseif ( $completion > 20 && $completion <= 40 ) {
					$upto_40++;
				} elseif ( $completion >= 0 && $completion <= 20 ) {
					$upto_20++;
				}
			}
			if ( $class_size <= 0 ) {
				$course_data[ $course_title ] = array(
					'percentage' => $percentage,
					'upto_20'   => $upto_20,
					'upto_40'   => $upto_40,
					'upto_60'   => $upto_60,
					'upto_80'   => $upto_80,
					'upto_100'   => $upto_100,
					'total'      => $class_size
				);
				continue;
			}
			$percentage = floatval( number_format( $total_completion / $class_size, 2, '.', '' ) );
			$avg_percentage += $percentage;
			// Average completion Course-wise.
			$course_data[ $course_title ] = array(
				'percentage' => $percentage,
				'completed'  => $completed,
				'total'      => $class_size,
			);
		}
		$overall_average_completion = floatval( number_format( $avg_percentage / $course_count, 2, '.', '' ) );// Cast to integer if no decimals.
		$showing        = ( ( (int) $request_data['page'] - 1 ) * 5 ) + count( $courses );
		$total          = $course_count;
		
	}

	/**
	 * This method is used to calculate course completion rate across courses by all the students.
	 * This method is doesn't support the date-wise filters because the completion dates aren't stored in the database.
	 *
	 * @return WP_REST_Response/WP_Error Objects.
	 */
	public function get_course_progress_rate() {
		global $wpdb;
		// Get Inputs.
		$request_data = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING );
		$request_data = self::get_request_params( $request_data );
		$time_spent_row = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT id, option_value, expires_on, created_on FROM {$wpdb->prefix}wrld_cached_entries WHERE object_type=%s AND object_id=%d AND option_name=%s",
				'user',
				get_current_user_id(),
				'wrld_course_progress_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['duration'] . '_' . $request_data['learner']
			)
		);
		if ( empty( $time_spent_row ) || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $time_spent_row->expires_on <= current_time( 'timestamp' ) || $request_data['disable_cache'] ) {
			if ( ! empty( $time_spent_row ) ) {
				$wpdb->delete(
					$wpdb->prefix . 'wrld_cached_entries',
					array(
						'id' => $time_spent_row->id
					),
					array(
						'%d'
					)
				);
			}
			do_action( 'wpml_switch_language',  $_GET[ 'wpml_lang' ] ); // switch the content language
			$user_role_access   = self::get_current_user_role_access();
			$accessible_courses = self::get_accessible_courses_for_the_user( get_current_user_id(), $user_role_access, 'course_completion_rate' );
			$accessible_users   = self::get_accessible_users_for_the_user( get_current_user_id(), $user_role_access, 'course_completion_rate' );
			$excluded_users     = get_option( 'exclude_users', array() );
			if ( empty( $excluded_users ) || ! defined( 'LDRP_PLUGIN_VERSION' ) ) {
				$excluded_users = array();
			}
			if ( isset( $request_data['group'] ) && ! empty( $request_data['group'] ) ) {
				$group_users = get_transient( 'wrld_group_students_data_' . $request_data['group'] );
				if ( false === $group_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
					// Get all students for a course.
					if ( get_option( 'migrated_group_access_data', false ) ) {
						// $group_users = array_unique( array_merge( $group_users, \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] ) ) );
						$group_users = \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] );
					} else {
						$group_users = self::get_ld_group_user_ids( $request_data['group'] );
					}
					delete_transient( 'wrld_group_students_data_' . $request_data['group'] );
					set_transient( 'wrld_group_students_data_' . $request_data['group'], $group_users, 1 * HOUR_IN_SECONDS );
				}
				$accessible_users = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $group_users, $accessible_users ) : $group_users;
			}
			if ( isset( $request_data['learner'] ) && ! empty( $request_data['learner'] ) ) {
				$learner_courses    = learndash_user_get_enrolled_courses( $request_data['learner'], array(), false );
				$accessible_courses = ( ! is_null( $accessible_courses ) && -1 != $accessible_courses ) ? array_intersect( $accessible_courses, $learner_courses ) : $learner_courses;
				$accessible_users   = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, array( $request_data['learner'] ) ) : $request_data['learner'];

				if ( empty( $accessible_courses ) ) {
					$response = new WP_Error(
						'no-data',
						sprintf(/* translators: %s: custom label for courses */
							__( 'No accessible %s found', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::get_label( 'courses' )
						),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					);
					$current_timestamp = current_time( 'timestamp' );
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_course_progress_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['duration'] . '_' . $request_data['learner'],
							'option_value' => maybe_serialize( $response ),
							'object_id'    => get_current_user_id(),
							'object_type'  => 'user',
							'created_on'   => $current_timestamp,
							'expires_on'   => $current_timestamp + DAY_IN_SECONDS
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d',
							'%d',
						)
					);
					$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
					return $response;
				}

				$total_percentage  = 0;
				$progress_data     = array();
				$completed_count   = 0;
				$not_started_count = 0;
				$in_progress_count = 0;
				$upto_20 = 0;
				$upto_40 = 0;
				$upto_60 = 0;
				$upto_80 = 0;
				$upto_100 = 0;

				$course_count = count( $accessible_courses );

				foreach ( $accessible_courses as $course_id ) {
					if ( isset( $request_data['duration'] ) && 'all' != $request_data['duration'] ) {
						$start_date = strtotime( gmdate( 'Y-m-d', strtotime( '-' . $request_data['duration'] ) ) );
						$end_date = current_time( 'timestamp' );
						// $enrolled_on = get_user_meta( $student, 'course_' . $course->ID . '_access_from', true );
						$enrolled_on = ld_course_access_from( $course_id, $request_data['learner'] );
						if ( empty( $enrolled_on ) ) {
							$enrolled_on = learndash_user_group_enrolled_to_course_from( $request_data['learner'], $course_id );
						}
						if ( empty( $enrolled_on ) ) {
							$course_count--;
							continue;
						}
						if ( (int) $enrolled_on < $start_date || (int) $enrolled_on > $end_date ) {
							$course_count--;
							continue;
						}
					}
					$progress = learndash_user_get_course_progress( $request_data['learner'], $course_id, 'summary' );
					if ( empty( $progress ) || ! isset( $progress['total'] ) || 0 == $progress['total'] ) {
						$percentage = 0;
						$not_started_count++;
						$upto_20++;
					} else {
						$percentage = floatval( number_format( 100 * $progress['completed'] / $progress['total'], 2, '.', '' ) );// Cast to integer if no decimals.
						if ( 100 == $percentage ) {
							$completed_count++;
						} elseif ( 0 == $percentage ) {
							$not_started_count++;
						} else {
							$in_progress_count++;
						}
						if ( $percentage > 80 && $percentage <= 100 ) {
							$upto_100++;
						} elseif ( $percentage > 60 && $percentage <= 80 ) {
							$upto_80++;
						} elseif ( $percentage > 40 && $percentage <= 60 ) {
							$upto_60++;
						} elseif ( $percentage > 20 && $percentage <= 40 ) {
							$upto_40++;
						} elseif ( $percentage >= 0 && $percentage <= 20 ) {
							$upto_20++;
						}
					}
					$total_percentage            = 0 <= $percentage ? $total_percentage + $percentage : $total_percentage;
				}

				if ( $course_count <= 0 ) {
					$response = new WP_Error(
						'no-data',
						__( 'No data available for the selected duration and/or filters', 'learndash-reports-by-wisdmlabs' ),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					);
					$current_timestamp = current_time( 'timestamp' );
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_course_progress_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['duration'] . '_' . $request_data['learner'],
							'option_value' => maybe_serialize( $response ),
							'object_id'    => get_current_user_id(),
							'object_type'  => 'user',
							'created_on'   => $current_timestamp,
							'expires_on'   => $current_timestamp + DAY_IN_SECONDS
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d',
							'%d',
						)
					);
					$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
					return $response;
				}

				// Calculate average across courses.
				$average_completion_percentage = 0;
				if ( $course_count > 0 ) {
					$average_completion_percentage = floatval( number_format( $total_percentage / $course_count, 2, '.', '' ) );
				}

				$response = array(
					'requestData'             	=> self::get_values_for_request_params( $request_data ),
					'upto_20'   				=> $upto_20,
					'upto_40'   				=> $upto_40,
					'upto_60'   				=> $upto_60,
					'upto_80'  					=> $upto_80,
					'upto_100'   				=> $upto_100,
					'averageCourseCompletion' => $average_completion_percentage,
					'course_count'            => $course_count,
					'completedCount'          => $completed_count,
					'notstartedCount'         => $not_started_count,
					'inprogressCount'         => $in_progress_count,
				);
				$current_timestamp = current_time( 'timestamp' );
				$wpdb->insert(
					$wpdb->prefix . 'wrld_cached_entries',
					array(
						'option_name'  => 'wrld_course_progress_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['duration'] . '_' . $request_data['learner'],
						'option_value' => maybe_serialize( $response ),
						'object_id'    => get_current_user_id(),
						'object_type'  => 'user',
						'created_on'   => $current_timestamp,
						'expires_on'   => $current_timestamp + DAY_IN_SECONDS
					),
					array(
						'%s',
						'%s',
						'%d',
						'%s',
						'%d',
						'%d',
					)
				);
				$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
				$response['updated_on'] = $updated_on;
				return new WP_REST_Response(
					$response,
					200
				);
			} elseif ( isset( $request_data['course'] ) && ! empty( $request_data['course'] ) ) {
				if ( ( ! is_null( $accessible_courses ) && -1 != $accessible_courses && empty( $accessible_courses ) ) ) {
					$response = new WP_Error(
						'no-data',
						sprintf(/* translators: %s: custom label for courses */
							__( 'No %s found', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::get_label( 'courses' )
						),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					);
					$current_timestamp = current_time( 'timestamp' );
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_course_progress_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['duration'] . '_' . $request_data['learner'],
							'option_value' => maybe_serialize( $response ),
							'object_id'    => get_current_user_id(),
							'object_type'  => 'user',
							'created_on'   => $current_timestamp,
							'expires_on'   => $current_timestamp + DAY_IN_SECONDS
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d',
							'%d',
						)
					);
					$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
					return $response;
				}
				if ( ! is_null( $accessible_courses ) && -1 != $accessible_courses && ! in_array( $request_data['course'], $accessible_courses ) ) {
					$response = new WP_Error(
						'no-data',
						sprintf(/* translators: %s: custom label for courses */
							__( 'You donot have sufficient access to view this %s reports', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::get_label( 'courses' )
						),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					);
					$current_timestamp = current_time( 'timestamp' );
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_course_progress_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['duration'] . '_' . $request_data['learner'],
							'option_value' => maybe_serialize( $response ),
							'object_id'    => get_current_user_id(),
							'object_type'  => 'user',
							'created_on'   => $current_timestamp,
							'expires_on'   => $current_timestamp + DAY_IN_SECONDS
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d',
							'%d',
						)
					);
					$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
					return $response;
				}
				$course = $request_data['course'];
				$course_price_type = learndash_get_course_meta_setting( $course, 'course_price_type' );
				if ( 'open' === $course_price_type ) {
					$response = new WP_Error(
						'no-data',
						sprintf(/* translators: %s: custom label for courses */
							__( 'You cannot view reports for the open %s for the time-being', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::get_label( 'courses' )
						),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					);
					$current_timestamp = current_time( 'timestamp' );
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_course_progress_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['duration'] . '_' . $request_data['learner'],
							'option_value' => maybe_serialize( $response ),
							'object_id'    => get_current_user_id(),
							'object_type'  => 'user',
							'created_on'   => $current_timestamp,
							'expires_on'   => $current_timestamp + DAY_IN_SECONDS
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d',
							'%d',
						)
					);
					$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
					return $response;
				}
				$students = get_transient( 'wrld_course_students_data_' . $course );
				if ( false === $students || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
				// Get all students for a course.
					if ( get_option( 'migrated_course_access_data', false ) ) {
						$students = \WRLD_Quiz_Export_Db::instance()->get_users_for_course( $course );
					} else {
						$students = learndash_get_users_for_course( $course, array(), false ); // Third argument is $exclude_admin.
					}
					$students = is_array( $students ) ? $students : $students->get_results();
					delete_transient( 'wrld_course_students_data_' . $course );
					set_transient( 'wrld_course_students_data_' . $course, $students, 1 * HOUR_IN_SECONDS );
				}
				$students = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, $students ) : $students;
				$students = array_diff( $students , $excluded_users );
				
				$upto_20 = 0;
				$upto_40 = 0;
				$upto_60 = 0;
				$upto_80 = 0;
				$upto_100 = 0;
				$percentage = 0;
				if ( empty( $students ) ) {
					$response = array(
						'requestData' => self::get_values_for_request_params( $request_data ),
						'percentage' => $percentage,
						'upto_20'   => $upto_20,
						'upto_40'   => $upto_40,
						'upto_60'   => $upto_60,
						'upto_80'   => $upto_80,
						'upto_100'   => $upto_100,
					);
					$current_timestamp = current_time( 'timestamp' );
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_course_progress_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['duration'] . '_' . $request_data['learner'],
							'option_value' => maybe_serialize( $response ),
							'object_id'    => get_current_user_id(),
							'object_type'  => 'user',
							'created_on'   => $current_timestamp,
							'expires_on'   => $current_timestamp + DAY_IN_SECONDS
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d',
							'%d',
						)
					);
					$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
					$response['updated_on'] = $updated_on;
					return new WP_REST_Response(
						$response,
						200
					);
				}
				
				// $all_students = array_unique( array_merge( $all_students, $students ) );
				$class_size       = is_array( $students ) ? count( $students ) : $students->get_total();
				$total_completion = 0; 
				// If no students in the course then the course has 0 percent completion.
				foreach ( $students as $student ) {
					// Get course progress info.
					if ( isset($request_data['duration']) && 'all' != $request_data['duration'] ) {
						$start_date = strtotime( gmdate( 'Y-m-d', strtotime( '-' . $request_data['duration'] ) ) );
						$end_date = current_time( 'timestamp' );
						// $enrolled_on = get_user_meta( $student, 'course_' . $course->ID . '_access_from', true );
						$enrolled_on = ld_course_access_from( $course, $student );
						if ( empty( $enrolled_on ) ) {
							$enrolled_on = learndash_user_group_enrolled_to_course_from( $student, $course );
						}
						if ( empty( $enrolled_on ) ) {
							$class_size--;
							continue;
						}
						if ( (int) $enrolled_on < $start_date || (int) $enrolled_on > $end_date ) {
							$class_size--;
							continue;
						}
					}
					$progress = learndash_user_get_course_progress( $student, $course, 'summary' );
					if ( ! isset( $progress['total'] ) || empty( $progress['total'] ) ) {
						$upto_20++;
						continue;
					}
					$completion = floatval( number_format( 100 * $progress['completed'] / $progress['total'], 2, '.', '' ) );// Cast to integer if no decimals.
					$total_completion += $completion;
					if ( $completion > 80 && $completion <= 100 ) {
						$upto_100++;
					} elseif ( $completion > 60 && $completion <= 80 ) {
						$upto_80++;
					} elseif ( $completion > 40 && $completion <= 60 ) {
						$upto_60++;
					} elseif ( $completion > 20 && $completion <= 40 ) {
						$upto_40++;
					} elseif ( $completion >= 0 && $completion <= 20 ) {
						$upto_20++;
					}
				}
				if ( $class_size <= 0 ) {
					$response = new WP_Error(
						'no-data',
						__( 'No data available for the selected duration and/or filters.', 'learndash-reports-by-wisdmlabs' ),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					);
					$current_timestamp = current_time( 'timestamp' );
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_course_progress_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['duration'] . '_' . $request_data['learner'],
							'option_value' => maybe_serialize( $response ),
							'object_id'    => get_current_user_id(),
							'object_type'  => 'user',
							'created_on'   => $current_timestamp,
							'expires_on'   => $current_timestamp + DAY_IN_SECONDS
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d',
							'%d',
						)
					);
					$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
					return $response;
				}
				$percentage = floatval( number_format( $total_completion / $class_size, 2, '.', '' ) );
				// Average completion Course-wise.
				$response = array(
					'requestData' => self::get_values_for_request_params( $request_data ),
					'percentage' => $percentage,
					'upto_20'   => $upto_20,
					'upto_40'   => $upto_40,
					'upto_60'   => $upto_60,
					'upto_80'   => $upto_80,
					'upto_100'   => $upto_100,
				);
				$current_timestamp = current_time( 'timestamp' );
				$wpdb->insert(
					$wpdb->prefix . 'wrld_cached_entries',
					array(
						'option_name'  => 'wrld_course_progress_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['duration'] . '_' . $request_data['learner'],
						'option_value' => maybe_serialize( $response ),
						'object_id'    => get_current_user_id(),
						'object_type'  => 'user',
						'created_on'   => $current_timestamp,
						'expires_on'   => $current_timestamp + DAY_IN_SECONDS
					),
					array(
						'%s',
						'%s',
						'%d',
						'%s',
						'%d',
						'%d',
					)
				);
				$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
				$response['updated_on'] = $updated_on;
				return new WP_REST_Response(
					$response,
					200
				);

			} elseif ( isset( $request_data['category'] ) && ! empty( $request_data['category'] ) ) {
				// Check if course category enabled.
				if ( ! taxonomy_exists( 'ld_course_category' ) ) {
					$response = new WP_Error(
						'invalid-input',
						sprintf(/* translators: %s: custom label for course */
							__( '%s Category disabled. Please contact admin.', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::get_label( 'course' ),
							array( 'requestData' => self::get_values_for_request_params( $request_data ) )
						)
					);
					$current_timestamp = current_time( 'timestamp' );
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_course_progress_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['duration'] . '_' . $request_data['learner'],
							'option_value' => maybe_serialize( $response ),
							'object_id'    => get_current_user_id(),
							'object_type'  => 'user',
							'created_on'   => $current_timestamp,
							'expires_on'   => $current_timestamp + DAY_IN_SECONDS
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d',
							'%d',
						)
					);
					$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
					return $response;
				}
				// Check if valid category passed.
				if ( ! is_object( get_term_by( 'id', $request_data['category'], 'ld_course_category' ) ) ) {
					$response = new WP_Error(
						'invalid-input',
						sprintf(/* translators: %s: custom label for course */
							__( '%s Category doesn\'t exist', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::get_label( 'course' ),
							array( 'requestData' => self::get_values_for_request_params( $request_data ) )
						)
					);
					$current_timestamp = current_time( 'timestamp' );
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_course_progress_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['duration'] . '_' . $request_data['learner'],
							'option_value' => maybe_serialize( $response ),
							'object_id'    => get_current_user_id(),
							'object_type'  => 'user',
							'created_on'   => $current_timestamp,
							'expires_on'   => $current_timestamp + DAY_IN_SECONDS
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d',
							'%d',
						)
					);
					$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
					return $response;
				}
				$category_query = array(
					array(
						'taxonomy'         => 'ld_course_category',
						'field'            => 'term_id',
						'terms'            => $request_data['category'], // Where term_id of Term 1 is "1".
						'include_children' => false,
					),
				);
			} elseif ( isset( $request_data['group'] ) && ! empty( $request_data['group'] ) ) {
				$group_courses      = learndash_group_enrolled_courses( $request_data['group'] );
				$accessible_courses = ( ! is_null( $accessible_courses ) && -1 != $accessible_courses ) ? array_intersect( $group_courses, $accessible_courses ) : $group_courses;
				$group_users = get_transient( 'wrld_group_students_data_' . $request_data['group'] );
				if ( false === $group_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
					// Get all students for a course.
					if ( get_option( 'migrated_group_access_data', false ) ) {
						// $group_users = array_unique( array_merge( $group_users, \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] ) ) );
						$group_users = \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] );
					} else {
						$group_users = self::get_ld_group_user_ids( $request_data['group'] );
					}
					delete_transient( 'wrld_group_students_data_' . $request_data['group'] );
					set_transient( 'wrld_group_students_data_' . $request_data['group'], $group_users, 1 * HOUR_IN_SECONDS );
				}
				$accessible_users   = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $group_users, $accessible_users ) : $group_users;
			}
			$query_args = array(
				'post_type'      => 'sfwd-courses',
				'posts_per_page' => -1,
				'post__in'       => -1 == $accessible_courses ? null : $accessible_courses,
				'suppress_filters' => 0,
			);

			if ( ! empty( $category_query ) ) {
				$query_args['tax_query'] = $category_query;
			}

			$courses      = get_posts( $query_args );
			$course_data = array();
			$avg_percentage = 0;
			// Check if any courses present in the category.
			if ( empty( $courses ) || ( ! is_null( $accessible_courses ) && -1 != $accessible_courses && empty( $accessible_courses ) ) ) {
				$response = new WP_Error(
					'no-data',
					sprintf(/* translators: %s: custom label for courses */
						__( 'No %s found', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'courses' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
				$current_timestamp = current_time( 'timestamp' );
				$wpdb->insert(
					$wpdb->prefix . 'wrld_cached_entries',
					array(
						'option_name'  => 'wrld_course_progress_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['duration'] . '_' . $request_data['learner'],
						'option_value' => maybe_serialize( $response ),
						'object_id'    => get_current_user_id(),
						'object_type'  => 'user',
						'created_on'   => $current_timestamp,
						'expires_on'   => $current_timestamp + DAY_IN_SECONDS
					),
					array(
						'%s',
						'%s',
						'%d',
						'%s',
						'%d',
						'%d',
					)
				);
				$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
				return $response;
			}
			$course_count = count( $courses );
			foreach ( $courses as $course ) {
				$course_price_type = learndash_get_course_meta_setting( $course->ID, 'course_price_type' );
				if ( 'open' === $course_price_type ) {
					continue;
				}
				$students = get_transient( 'wrld_course_students_data_' . $course->ID );
				if ( false === $students || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
				// Get all students for a course.
					if ( get_option( 'migrated_course_access_data', false ) ) {
						$students = \WRLD_Quiz_Export_Db::instance()->get_users_for_course( $course->ID );
					} else {
						$students = learndash_get_users_for_course( $course->ID, array(), false ); // Third argument is $exclude_admin.
					}
					$students = is_array( $students ) ? $students : $students->get_results();
					delete_transient( 'wrld_course_students_data_' . $course->ID );
					set_transient( 'wrld_course_students_data_' . $course->ID, $students, 1 * HOUR_IN_SECONDS );
				}
				$students = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, $students ) : $students;
				$students = array_diff( $students , $excluded_users );
				
				// $all_students = array_unique( array_merge( $all_students, $students ) );
				$class_size = is_array( $students ) ? count( $students ) : $students->get_total();
				$upto_20 = 0;
				$upto_40 = 0;
				$upto_60 = 0;
				$upto_80 = 0;
				$upto_100 = 0;
				$percentage = 0;
				$course_title = get_the_title( $course->ID );
				// If no students in the course then the course has 0 percent completion.
				if ( empty( $students ) ) {
					$course_data[ $course_title ] = array(
						'percentage' => $percentage,
						'upto_20'   => $upto_20,
						'upto_40'   => $upto_40,
						'upto_60'   => $upto_60,
						'upto_80'   => $upto_80,
						'upto_100'   => $upto_100,
						'total'      => $class_size
					);
					continue;
				}
				$total_completion = 0;
				foreach ( $students as $student ) {
					// Get course progress info.
					if ( isset($request_data['duration']) && 'all' != $request_data['duration'] ) {
						$start_date = strtotime( gmdate( 'Y-m-d', strtotime( '-' . $request_data['duration'] ) ) );
						$end_date = current_time( 'timestamp' );
						// $enrolled_on = get_user_meta( $student, 'course_' . $course->ID . '_access_from', true );
						$enrolled_on = ld_course_access_from( $course->ID, $student );
						if ( empty( $enrolled_on ) ) {
							$enrolled_on = learndash_user_group_enrolled_to_course_from( $student, $course->ID );
						}
						if ( empty( $enrolled_on ) ) {
							$class_size--;
							continue;
						}
						if ( (int) $enrolled_on < $start_date || (int) $enrolled_on > $end_date ) {
							$class_size--;
							continue;
						}
					}
					$progress = learndash_user_get_course_progress( $student, $course->ID, 'summary' );
					if ( ! isset( $progress['total'] ) || empty( $progress['total'] ) ) {
						$upto_20++;
						continue;
					}
					$completion = floatval( number_format( 100 * $progress['completed'] / $progress['total'], 2, '.', '' ) );// Cast to integer if no decimals.
					$total_completion += $completion;
					if ( $completion > 80 && $completion <= 100 ) {
						$upto_100++;
					} elseif ( $completion > 60 && $completion <= 80 ) {
						$upto_80++;
					} elseif ( $completion > 40 && $completion <= 60 ) {
						$upto_60++;
					} elseif ( $completion > 20 && $completion <= 40 ) {
						$upto_40++;
					} elseif ( $completion >= 0 && $completion <= 20 ) {
						$upto_20++;
					}
				}
				if ( $class_size <= 0 ) {
					$course_data[ $course_title ] = array(
						'percentage' => $percentage,
						'upto_20'   => $upto_20,
						'upto_40'   => $upto_40,
						'upto_60'   => $upto_60,
						'upto_80'   => $upto_80,
						'upto_100'   => $upto_100,
						'total'      => $class_size
					);
					continue;
				}
				$percentage = floatval( number_format( $total_completion / $class_size, 2, '.', '' ) );
				$avg_percentage += $percentage;
				// Average completion Course-wise.
				$course_data[ $course_title ] = array(
					'percentage' => $percentage,
					'completed'  => $completed,
					'total'      => $class_size,
				);
			}
			$overall_average_completion = floatval( number_format( $avg_percentage / $course_count, 2, '.', '' ) );// Cast to integer if no decimals.
			$showing        = ( ( (int) $request_data['page'] - 1 ) * 5 ) + count( $courses );
			$total          = $course_count;
			
			$response = array(
				'requestData'             => self::get_values_for_request_params( $request_data ),
				'averageCourseCompletion' => $overall_average_completion,
				'tableData'               => $course_data,
				'more_data'   => ( $total - $showing > 0 ) ? 'yes' : 'no',
				'total'       => $total,
				'showing'     => $showing,
			);
			$current_timestamp = current_time( 'timestamp' );
			$wpdb->insert(
				$wpdb->prefix . 'wrld_cached_entries',
				array(
					'option_name'  => 'wrld_course_progress_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['duration'] . '_' . $request_data['learner'],
					'option_value' => maybe_serialize( $response ),
					'object_id'    => get_current_user_id(),
					'object_type'  => 'user',
					'created_on'   => $current_timestamp,
					'expires_on'   => $current_timestamp + DAY_IN_SECONDS
				),
				array(
					'%s',
					'%s',
					'%d',
					'%s',
					'%d',
					'%d',
				)
			);
			$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
			$response['updated_on'] = $updated_on;
			return new WP_REST_Response(
				$response,
				200
			);
		}
		$updated_on = date_i18n( 'Y-m-d H:i:s', $time_spent_row->created_on );
		$response = maybe_unserialize( $time_spent_row->option_value );
		if ( is_wp_error( $response ) ) {
			return $response;
		}
		$response['updated_on'] = $updated_on;
		return new WP_REST_Response(
			$response,
			200
		);
	}

	public function get_course_progress_details() {
		$request_data = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING );
		$request_data = self::get_request_params( $request_data );
		$user_role_access   = self::get_current_user_role_access();
		$accessible_courses = self::get_accessible_courses_for_the_user( get_current_user_id(), $user_role_access, 'course_completion_rate' );
		$accessible_users   = self::get_accessible_users_for_the_user( get_current_user_id(), $user_role_access, 'course_completion_rate' );
		$excluded_users     = get_option( 'exclude_users', array() );
		do_action( 'wpml_switch_language',  $_GET[ 'wpml_lang' ] ); // switch the content language
		if ( empty( $excluded_users ) || ! defined( 'LDRP_PLUGIN_VERSION' ) ) {
			$excluded_users = array();
		}
		if ( isset( $request_data['group'] ) && ! empty( $request_data['group'] ) ) {
			$group_users = get_transient( 'wrld_group_students_data_' . $request_data['group'] );
			if ( false === $group_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
				// Get all students for a course.
				if ( get_option( 'migrated_group_access_data', false ) ) {
					// $group_users = array_unique( array_merge( $group_users, \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] ) ) );
					$group_users = \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] );
				} else {
					$group_users = self::get_ld_group_user_ids( $request_data['group'] );
				}
				delete_transient( 'wrld_group_students_data_' . $request_data['group'] );
				set_transient( 'wrld_group_students_data_' . $request_data['group'], $group_users, 1 * HOUR_IN_SECONDS );
			}
			$accessible_users = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $group_users, $accessible_users ) : $group_users;
		}
		if ( isset( $request_data['learner'] ) && ! empty( $request_data['learner'] ) ) {
			$learner_courses    = learndash_user_get_enrolled_courses( $request_data['learner'], array(), false );
			$accessible_courses = ( ! is_null( $accessible_courses ) && -1 != $accessible_courses ) ? array_intersect( $accessible_courses, $learner_courses ) : $learner_courses;
			$accessible_users   = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, array( $request_data['learner'] ) ) : $request_data['learner'];

			if ( empty( $accessible_courses ) ) {
				return new WP_Error(
					'no-data',
					sprintf(/* translators: %s: custom label for courses */
						__( 'No accessible %s found', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'courses' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}

			$min_progress    = intval( $request_data['datapoint'] );
			$max_progress    = $min_progress + 20;
			$table_data      = array();
			$skip_count      = isset( $request_data['page'] ) && ! empty( $request_data['page'] ) ? ( (int) $request_data['page'] - 1 ) * 5 : 0;
			$condition_met_count = 0;
			$more = 'no';

			foreach ( $accessible_courses as $course_id ) {
				if ( isset( $request_data['duration'] ) && 'all' != $request_data['duration'] ) {
					$start_date = strtotime( gmdate( 'Y-m-d', strtotime( '-' . $request_data['duration'] ) ) );
					$end_date = current_time( 'timestamp' );
					// $enrolled_on = get_user_meta( $student, 'course_' . $course->ID . '_access_from', true );
					$enrolled_on = ld_course_access_from( $course_id, $request_data['learner'] );
					if ( empty( $enrolled_on ) ) {
						$enrolled_on = learndash_user_group_enrolled_to_course_from( $request_data['learner'], $course_id );
					}
					if ( empty( $enrolled_on ) ) {
						continue;
					}
					if ( (int) $enrolled_on < $start_date || (int) $enrolled_on > $end_date ) {
						continue;
					}
				}
				$progress = learndash_user_get_course_progress( $request_data['learner'], $course_id, 'summary' );
				if ( empty( $progress ) || ! isset( $progress['total'] ) || 0 == $progress['total'] ) {
					$percentage = 0;					
				} else {
					$percentage = floatval( number_format( 100 * $progress['completed'] / $progress['total'], 2, '.', '' ) );// Cast to integer if no decimals.
				}
				if ( $percentage >= $min_progress && $percentage < $max_progress ) {
					if ( $condition_met_count >= $skip_count + 5 ) {
						$more = 'yes';
						break;
					}
					if ( $condition_met_count < $skip_count ) {
						$condition_met_count++;
						continue;
					}
					$condition_met_count++;
					$enrolled_on = ld_course_access_from( $course_id, $request_data['learner'] );
					if ( empty( $enrolled_on ) ) {
						$enrolled_on = learndash_user_group_enrolled_to_course_from( $request_data['learner'], $course_id );
					}
					$table_data[ get_the_title( $course_id ) ] = array(
						'enrolled_on' => ! empty( $enrolled_on ) ? date( 'd M, Y', $enrolled_on ) : __( 'Not available', 'learndash-reports-by-wisdmlabs' ),
						'progress'    => $percentage
					);
				}
			}

			return new WP_REST_Response(
				array(
					'tableData'   				=> $table_data,
					'more_data'   				=> $more,
				),
				200
			);
		} elseif ( isset( $request_data['course'] ) && ! empty( $request_data['course'] ) ) {
			if ( ( ! is_null( $accessible_courses ) && -1 != $accessible_courses && empty( $accessible_courses ) ) ) {
				return new WP_Error(
					'no-data',
					sprintf(/* translators: %s: custom label for courses */
						__( 'No %s found', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'courses' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}
			if ( ! is_null( $accessible_courses ) && -1 != $accessible_courses && ! in_array( $request_data['course'], $accessible_courses ) ) {
				return new WP_Error(
					'no-data',
					sprintf(/* translators: %s: custom label for courses */
						__( 'You donot have sufficient access to view this %s reports', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'courses' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}
			$course = $request_data['course'];
			$course_price_type = learndash_get_course_meta_setting( $course, 'course_price_type' );
			if ( 'open' === $course_price_type ) {
				return new WP_Error(
					'no-data',
					sprintf(/* translators: %s: custom label for courses */
						__( 'You cannot view reports for the open %s for the time-being', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'courses' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}
			$students = get_transient( 'wrld_course_students_data_' . $course );
			if ( false === $students || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
			// Get all students for a course.
				if ( get_option( 'migrated_course_access_data', false ) ) {
					$students = \WRLD_Quiz_Export_Db::instance()->get_users_for_course( $course );
				} else {
					$students = learndash_get_users_for_course( $course, array(), false ); // Third argument is $exclude_admin.
				}
				$students = is_array( $students ) ? $students : $students->get_results();
				delete_transient( 'wrld_course_students_data_' . $course );
				set_transient( 'wrld_course_students_data_' . $course, $students, 1 * HOUR_IN_SECONDS );
			}
			$students = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, $students ) : $students;
			$students = array_diff( $students , $excluded_users );
			$min_progress    = intval( $request_data['datapoint'] );
			$max_progress    = $min_progress + 20;
			$table_data      = array();
			$skip_count      = isset( $request_data['page'] ) && ! empty( $request_data['page'] ) ? ( (int) $request_data['page'] - 1 ) * 5 : 0;
			$condition_met_count = 0;
			$more = 'no';
			// If no students in the course then the course has 0 percent completion.
			if ( empty( $students ) ) {
				return new WP_REST_Response(
					array(
						'tableData' => $table_data,
						'more_data' => $more,
					),
					200
				);
			}
			foreach ( $students as $student ) {
				// Get course progress info.
				if ( isset($request_data['duration']) && 'all' != $request_data['duration'] ) {
					$start_date = strtotime( gmdate( 'Y-m-d', strtotime( '-' . $request_data['duration'] ) ) );
					$end_date = current_time( 'timestamp' );
					// $enrolled_on = get_user_meta( $student, 'course_' . $course->ID . '_access_from', true );
					$enrolled_on = ld_course_access_from( $course, $student );
					if ( empty( $enrolled_on ) ) {
						$enrolled_on = learndash_user_group_enrolled_to_course_from( $student, $course );
					}
					if ( empty( $enrolled_on ) ) {
						continue;
					}
					if ( (int) $enrolled_on < $start_date || (int) $enrolled_on > $end_date ) {
						continue;
					}
				}
				$progress = learndash_user_get_course_progress( $student, $course, 'summary' );
				if ( ! isset( $progress['total'] ) || empty( $progress['total'] ) ) {
					$percentage = 0;
				} else {
					$percentage = floatval( number_format( 100 * $progress['completed'] / $progress['total'], 2, '.', '' ) );// Cast to integer if no decimals.
				}
				if ( $percentage >= $min_progress && $percentage < $max_progress ) {
					if ( $condition_met_count >= $skip_count + 5 ) {
						$more = 'yes';
						break;
					}
					if ( $condition_met_count < $skip_count ) {
						$condition_met_count++;
						continue;
					}
					$condition_met_count++;
					$enrolled_on = ld_course_access_from( $course, $student );
					if ( empty( $enrolled_on ) ) {
						$enrolled_on = learndash_user_group_enrolled_to_course_from( $student, $course );
					}
					$table_data[ get_userdata( $student )->display_name ] = array(
						'enrolled_on' => ! empty( $enrolled_on ) ? date( 'd M, Y', $enrolled_on ) : __( 'Not available', 'learndash-reports-by-wisdmlabs' ),
						'progress'    => $percentage
					);
				}
			}
			return new WP_REST_Response(
				array(
					'tableData' => $table_data,
					'more_data' => $more,
				),
				200
			);
		}
	}

	public function get_course_completion_rate_csv() {
		// Get Inputs.
		$request_data = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING );
		$request_data = self::get_request_params( $request_data );
		do_action( 'wpml_switch_language',  $_GET[ 'wpml_lang' ] ); // switch the content language
		$user_role_access   = self::get_current_user_role_access();
		$accessible_courses = self::get_accessible_courses_for_the_user( get_current_user_id(), $user_role_access, 'course_completion_rate' );
		$accessible_users   = self::get_accessible_users_for_the_user( get_current_user_id(), $user_role_access, 'course_completion_rate' );
		$excluded_users     = get_option( 'exclude_users', array() );
		if ( empty( $excluded_users ) || ! defined( 'LDRP_PLUGIN_VERSION' ) ) {
			$excluded_users = array();
		}
		if ( isset( $request_data['group'] ) && ! empty( $request_data['group'] ) ) {
			$group_users = get_transient( 'wrld_group_students_data_' . $request_data['group'] );
			if ( false === $group_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
				// Get all students for a course.
				if ( get_option( 'migrated_group_access_data', false ) ) {
					$group_users = \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] );
				} else {
					$group_users = self::get_ld_group_user_ids( $request_data['group'] );
				}
				delete_transient( 'wrld_group_students_data_' . $request_data['group'] );
				set_transient( 'wrld_group_students_data_' . $request_data['group'], $group_users, 1 * HOUR_IN_SECONDS );
			}
			$accessible_users = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $group_users, $accessible_users ) : $group_users;
		}
		if ( isset( $request_data['category'] ) && ! empty( $request_data['category'] ) ) {
			// Check if course category enabled.
			if ( ! taxonomy_exists( 'ld_course_category' ) ) {
				return new WP_Error(
					'invalid-input',
					sprintf(/* translators: %s: custom label for course */
						__( '%s Category disabled. Please contact admin.', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'course' ),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					)
				);
			}
			// Check if valid category passed.
			if ( ! is_object( get_term_by( 'id', $request_data['category'], 'ld_course_category' ) ) ) {
				return new WP_Error(
					'invalid-input',
					sprintf(/* translators: %s: custom label for course */
						__( '%s Category doesn\'t exist', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'course' ),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					)
				);
			}
			$category_query = array(
				array(
					'taxonomy'         => 'ld_course_category',
					'field'            => 'term_id',
					'terms'            => $request_data['category'], // Where term_id of Term 1 is "1".
					'include_children' => false,
				),
			);
		} elseif ( isset( $request_data['group'] ) && ! empty( $request_data['group'] ) ) {
			$group_courses      = learndash_group_enrolled_courses( $request_data['group'] );
			$accessible_courses = ( ! is_null( $accessible_courses ) && -1 != $accessible_courses ) ? array_intersect( $group_courses, $accessible_courses ) : $group_courses;
			$group_users = get_transient( 'wrld_group_students_data_' . $request_data['group'] );
			if ( false === $group_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
				// Get all students for a course.
				if ( get_option( 'migrated_group_access_data', false ) ) {
					// $group_users = $group_users = \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] );;
					$group_users = \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] );
				} else {
					$group_users = self::get_ld_group_user_ids( $request_data['group'] );
				}
				delete_transient( 'wrld_group_students_data_' . $request_data['group'] );
				set_transient( 'wrld_group_students_data_' . $request_data['group'], $group_users, 1 * HOUR_IN_SECONDS );
			}
			$accessible_users   = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $group_users, $accessible_users ) : $group_users;
		}

		$posts_per_page = 5;
		if ( isset( $request_data['page'] ) && 'all' === $request_data['page'] ) {
			$posts_per_page = -1;
		}

		$query_args = array(
			'post_type'      => 'sfwd-courses',
			'posts_per_page' => $posts_per_page,
			'post__in'       => -1 == $accessible_courses ? null : $accessible_courses,
			'orderby'        => 'date',
			'order'          => isset($request_data['sort'])?$request_data['sort']:'DESC',
			'paged'           => 1,
			'suppress_filters' => 0,
		);

		$count_query_args = array(
			'post_type'      => 'sfwd-courses',
			'posts_per_page' => -1,
			'post__in'       => -1 == $accessible_courses ? null : $accessible_courses,
			'suppress_filters' => 0,
		);

		if ( ! empty( $category_query ) ) {
			$query_args['tax_query'] = $category_query;
			$count_query_args['tax_query'] = $category_query;
		}

		$courses      = get_posts( $query_args );
		$all_courses  = get_posts( $count_query_args );
		$course_count = count( $all_courses );
		$course_data = array();
		$avg_percentage = 0;
		// Check if any courses present in the category.
		if ( empty( $courses ) || ( ! is_null( $accessible_courses ) && -1 != $accessible_courses && empty( $accessible_courses ) ) ) {
			return new WP_Error(
				'no-data',
				sprintf(/* translators: %s: custom label for courses */
					__( 'No %s found', 'learndash-reports-by-wisdmlabs' ),
					\LearnDash_Custom_Label::get_label( 'courses' )
				),
				array( 'requestData' => self::get_values_for_request_params( $request_data ) )
			);
		}
		foreach ( $courses as $course ) {
			$course_price_type = learndash_get_course_meta_setting( $course->ID, 'course_price_type' );
			if ( 'open' === $course_price_type ) {
				$course_count--;
				continue;
			}
			$students = get_transient( 'wrld_course_students_data_' . $course->ID );
			if ( false === $students || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
			// Get all students for a course.
				if ( get_option( 'migrated_course_access_data', false ) ) {
					$students = \WRLD_Quiz_Export_Db::instance()->get_users_for_course( $course->ID );
				} else {
					$students = learndash_get_users_for_course( $course->ID, array(), false ); // Third argument is $exclude_admin.
				}
				$students = is_array( $students ) ? $students : $students->get_results();
				delete_transient( 'wrld_course_students_data_' . $course->ID );
				set_transient( 'wrld_course_students_data_' . $course->ID, $students, 1 * HOUR_IN_SECONDS );
			}
			$students = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, $students ) : $students;
			$students = array_diff( $students , $excluded_users );
			
			// $all_students = array_unique( array_merge( $all_students, $students ) );
			$class_size = is_array( $students ) ? count( $students ) : $students->get_total();
			$completed = 0;
			$percentage = 0;
			$course_title = get_the_title( $course->ID );
			// If no students in the course then the course has 0 percent completion.
			if ( empty( $students ) ) {
				$course_data[ $course_title ] = array(
					'percentage' => $percentage,
					'completed'  => $completed,
					'total'      => $class_size
				);
				continue;
			}
			foreach ( $students as $student ) {
				// Get course progress info.
				if ( isset($request_data['duration']) && 'all' != $request_data['duration'] ) {
					$start_date = strtotime( gmdate( 'Y-m-d', strtotime( '-' . $request_data['duration'] ) ) );
					$end_date = current_time( 'timestamp' );
					// $enrolled_on = get_user_meta( $student, 'course_' . $course->ID . '_access_from', true );
					$enrolled_on = ld_course_access_from( $course->ID, $student );
					if ( empty( $enrolled_on ) ) {
						$enrolled_on = learndash_user_group_enrolled_to_course_from( $student, $course->ID );
					}
					if ( empty( $enrolled_on ) ) {
						$class_size--;
						continue;
					}
					if ( (int) $enrolled_on < $start_date || (int) $enrolled_on > $end_date ) {
						$class_size--;
						continue;
					}
				}
				$is_complete = get_user_meta( $student, 'course_completed_' . $course->ID, true );
				if ( ! isset( $is_complete ) || empty( $is_complete ) ) {
					$percentage = $percentage + 0;
					continue;
				}
				$completed++;
			}
			if ( $class_size <= 0 ) {
				$course_data[ $course_title ] = array(
					'percentage' => $percentage,
					'completed'  => $completed,
					'total'      => $class_size
				);
				continue;
			}
			$percentage = floatval( number_format( 100 * $completed / $class_size, 2, '.', '' ) );
			$avg_percentage += $percentage;
			// Average completion Course-wise.
			$course_data[ $course_title ] = array(
				'percentage' => $percentage,
				'completed'  => $completed,
				'total'      => $class_size,
			);
		}
		$updated_on = current_time( 'timestamp' );
		$overall_average_completion = $this->get_average_completion_percentage( $all_courses, $course_count, $accessible_users, $excluded_users, $request_data, $updated_on );
		// $overall_average_completion = floatval( number_format( $avg_percentage / $course_count, 2, '.', '' ) );// Cast to integer if no decimals.

		$showing        = ( ( (int) $request_data['page'] - 1 ) * 5 ) + count( $courses );
		$total          = $course_count;
		// return new WP_REST_Response(
		// 	array(
		// 		'requestData'             => self::get_values_for_request_params( $request_data ),
		// 		'averageCourseCompletion' => $overall_average_completion,
		// 		'tableData'               => $course_data,
		// 		'more_data'   => ( $total - $showing > 0 ) ? 'yes' : 'no',
		// 		'total'       => $total,
		// 		'showing'     => $showing,
		// 		'updated'     => $updated_on
		// 	),
		// 	200
		// );
		$upload_dir = wp_upload_dir();
		if ( ! file_exists( $upload_dir['basedir'] . '/learndash_reports' ) ) {
			mkdir( $upload_dir['basedir'] . '/learndash_reports' );
		}
		$file      = fopen( $upload_dir['basedir'] . '/learndash_reports' . '/' . 'course_completion.csv', 'w' );
		// $file      = fopen( 'php://output', 'w' );
		// $file = fopen(filename, mode)
		// Checks if file opened on php output stream
		if ( $file ) {
			// For each row of the table in Data recieved.
			$th = array(
				__( 'Course Category: ', 'learndash-reports-by-wisdmlabs' ) . (! empty( $request_data['category'] ) ? get_term( (int) $request_data['category'], 'ld_course_category' )->name : __( 'All', 'learndash-reports-by-wisdmlabs' )),
				__( 'Group: ', 'learndash-reports-by-wisdmlabs' ) . (! empty( $request_data['group'] ) ? get_the_title( $request_data['group'] ) : __( 'All', 'learndash-reports-by-wisdmlabs' ) ),
				__( 'Duration: ', 'learndash-reports-by-wisdmlabs' ) . $request_data['duration']
			);
			// Inserts Heading into the csv file.
			if ( ! empty( $th ) ) {
				fputcsv( $file, $th );
			}
			fputcsv($file, array());
			$th2 = array(
				__( 'Course', 'learndash-reports-by-wisdmlabs' ),
				__( 'Course Completion Rate', 'learndash-reports-by-wisdmlabs' )
			);
			// Inserts Heading into the csv file.
			if ( ! empty( $th2 ) ) {
				fputcsv( $file, $th2 );
			}
			// For cell's value.
			foreach ($course_data as $course => $progress) {
				$td = array();
				$td = array( $course, $progress['percentage'] );
				fputcsv( $file, $td );
			}
			// Closes the Csv file.
			fclose( $file );
		} else {
			esc_html_e( 'File Permission Issue!!!', 'learndash-reports-pro' );
		}
		return new WP_REST_Response(
			array(
				'filename' => $upload_dir['baseurl'] . '/learndash_reports' . '/' . 'course_completion.csv'
			),
			200
		);
	}

	/**
	 * This method is used to calculate course completion rate across courses by all the students.
	 * This method is doesn't support the date-wise filters because the completion dates aren't stored in the database.
	 *
	 * @return WP_REST_Response/WP_Error Objects.
	 */
	public function get_course_completion_rate() {
		global $wpdb;
		// Get Inputs.
		$request_data = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING );
		$request_data = self::get_request_params( $request_data );
		if ( ! isset( $request_data['page'] ) || empty( $request_data['page'] ) ) {
			$request_data['page'] = 1;
		}
		if ( ! isset( $request_data['sort'] ) || empty( $request_data['sort'] ) ) {
			$request_data['sort'] = 'DESC';
		}
		$time_spent_row = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT id, option_value, expires_on, created_on FROM {$wpdb->prefix}wrld_cached_entries WHERE object_type=%s AND object_id=%d AND option_name=%s",
				'user',
				get_current_user_id(),
				'wrld_course_completion_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['sort'] . '_' . $request_data['duration'] . '_' . $request_data['page']
			)
		);
		if ( empty( $time_spent_row ) || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $time_spent_row->expires_on <= current_time( 'timestamp' ) || $request_data['disable_cache'] ) {
			if ( ! empty( $time_spent_row ) ) {
				$wpdb->delete(
					$wpdb->prefix . 'wrld_cached_entries',
					array(
						'id' => $time_spent_row->id
					),
					array(
						'%d'
					)
				);
			}
			do_action( 'wpml_switch_language',  $_GET[ 'wpml_lang' ] ); // switch the content language
			$user_role_access   = self::get_current_user_role_access();
			$accessible_courses = self::get_accessible_courses_for_the_user( get_current_user_id(), $user_role_access, 'course_completion_rate' );
			$accessible_users   = self::get_accessible_users_for_the_user( get_current_user_id(), $user_role_access, 'course_completion_rate' );
			$excluded_users     = get_option( 'exclude_users', array() );
			if ( empty( $excluded_users ) || ! defined( 'LDRP_PLUGIN_VERSION' ) ) {
				$excluded_users = array();
			}
			if ( isset( $request_data['group'] ) && ! empty( $request_data['group'] ) ) {
				$group_users = get_transient( 'wrld_group_students_data_' . $request_data['group'] );
				if ( false === $group_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
					// Get all students for a course.
					if ( get_option( 'migrated_group_access_data', false ) ) {
						$group_users = \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] );
					} else {
						$group_users = self::get_ld_group_user_ids( $request_data['group'] );
					}
					set_transient( 'wrld_group_students_data_' . $request_data['group'], $group_users, 1 * HOUR_IN_SECONDS );
				}
				$accessible_users = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $group_users, $accessible_users ) : $group_users;
			}
			if ( isset( $request_data['category'] ) && ! empty( $request_data['category'] ) ) {
				// Check if course category enabled.
				if ( ! taxonomy_exists( 'ld_course_category' ) ) {
					$response = new WP_Error(
						'invalid-input',
						sprintf(/* translators: %s: custom label for course */
							__( '%s Category disabled. Please contact admin.', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::get_label( 'course' ),
							array( 'requestData' => self::get_values_for_request_params( $request_data ) )
						)
					);
					$current_timestamp = current_time( 'timestamp' );
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_course_completion_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['sort'] . '_' . $request_data['duration'] . '_' . $request_data['page'],
							'option_value' => maybe_serialize( $response ),
							'object_id'    => get_current_user_id(),
							'object_type'  => 'user',
							'created_on'   => $current_timestamp,
							'expires_on'   => $current_timestamp + DAY_IN_SECONDS
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d',
							'%d',
						)
					);
					$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
					return $response;
				}
				// Check if valid category passed.
				if ( ! is_object( get_term_by( 'id', $request_data['category'], 'ld_course_category' ) ) ) {
					$response = new WP_Error(
						'invalid-input',
						sprintf(/* translators: %s: custom label for course */
							__( '%s Category doesn\'t exist', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::get_label( 'course' ),
							array( 'requestData' => self::get_values_for_request_params( $request_data ) )
						)
					);
					$current_timestamp = current_time( 'timestamp' );
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_course_completion_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['sort'] . '_' . $request_data['duration'] . '_' . $request_data['page'],
							'option_value' => maybe_serialize( $response ),
							'object_id'    => get_current_user_id(),
							'object_type'  => 'user',
							'created_on'   => $current_timestamp,
							'expires_on'   => $current_timestamp + DAY_IN_SECONDS
						),
						array(
							'%s',
							'%s',
							'%d',
							'%s',
							'%d',
							'%d',
						)
					);
					$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
					return $response;
				}
				$category_query = array(
					array(
						'taxonomy'         => 'ld_course_category',
						'field'            => 'term_id',
						'terms'            => $request_data['category'], // Where term_id of Term 1 is "1".
						'include_children' => false,
					),
				);
			} elseif ( isset( $request_data['group'] ) && ! empty( $request_data['group'] ) ) {
				$group_courses      = learndash_group_enrolled_courses( $request_data['group'] );
				$accessible_courses = ( ! is_null( $accessible_courses ) && -1 != $accessible_courses ) ? array_intersect( $group_courses, $accessible_courses ) : $group_courses;
				$group_users = get_transient( 'wrld_group_students_data_' . $request_data['group'] );
				if ( false === $group_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
					// Get all students for a course.
					if ( get_option( 'migrated_group_access_data', false ) ) {
						// $group_users = $group_users = \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] );;
						$group_users = \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] );
					} else {
						$group_users = self::get_ld_group_user_ids( $request_data['group'] );
					}
					delete_transient( 'wrld_group_students_data_' . $request_data['group'] );
					set_transient( 'wrld_group_students_data_' . $request_data['group'], $group_users, 1 * HOUR_IN_SECONDS );
				}
				$accessible_users   = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $group_users, $accessible_users ) : $group_users;
			}
			$posts_per_page = 5;
			if ( isset( $request_data['page'] ) && 'all' === $request_data['page'] ) {
				$posts_per_page = -1;
			}

			$query_args = array(
				'post_type'      => 'sfwd-courses',
				'posts_per_page' => $posts_per_page,
				'post__in'       => -1 == $accessible_courses ? null : $accessible_courses,
				'orderby'        => 'date',
				'order'          => isset($request_data['sort'])?$request_data['sort']:'DESC',
				'paged'           => 1,
				'suppress_filters' => 0,
			);

			$count_query_args = array(
				'post_type'      => 'sfwd-courses',
				'posts_per_page' => -1,
				'post__in'       => -1 == $accessible_courses ? null : $accessible_courses,
				'suppress_filters' => 0,
			);

			if ( ! empty( $category_query ) ) {
				$query_args['tax_query'] = $category_query;
				$count_query_args['tax_query'] = $category_query;
			}

			$courses      = get_posts( $query_args );
			$all_courses  = get_posts( $count_query_args );
			$course_count = count( $all_courses );
			$course_data = array();
			$avg_percentage = 0;

			// Check if any courses present in the category.
			if ( empty( $courses ) || ( ! is_null( $accessible_courses ) && -1 != $accessible_courses && empty( $accessible_courses ) ) ) {
				$response = new WP_Error(
					'no-data',
					sprintf(/* translators: %s: custom label for courses */
						__( 'No %s found', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'courses' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
				$current_timestamp = current_time( 'timestamp' );
				$wpdb->insert(
					$wpdb->prefix . 'wrld_cached_entries',
					array(
						'option_name'  => 'wrld_course_completion_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['sort'] . '_' . $request_data['duration'] . '_' . $request_data['page'],
						'option_value' => maybe_serialize( $response ),
						'object_id'    => get_current_user_id(),
						'object_type'  => 'user',
						'created_on'   => $current_timestamp,
						'expires_on'   => $current_timestamp + DAY_IN_SECONDS
					),
					array(
						'%s',
						'%s',
						'%d',
						'%s',
						'%d',
						'%d',
					)
				);
				$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
				return $response;
			}

			foreach ( $courses as $course ) {
				$course_price_type = learndash_get_course_meta_setting( $course->ID, 'course_price_type' );
				if ( 'open' === $course_price_type ) {
					$course_count--;
					continue;
				}
				$students = get_transient( 'wrld_course_students_data_' . $course->ID );
				if ( false === $students || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
				// Get all students for a course.
					if ( get_option( 'migrated_course_access_data', false ) ) {
						$students = \WRLD_Quiz_Export_Db::instance()->get_users_for_course( $course->ID );
					} else {
						$students = learndash_get_users_for_course( $course->ID, array(), false ); // Third argument is $exclude_admin.
					}
					$students = is_array( $students ) ? $students : $students->get_results();
					delete_transient( 'wrld_course_students_data_' . $course->ID );
					set_transient( 'wrld_course_students_data_' . $course->ID, $students, 1 * HOUR_IN_SECONDS );
				}
				$students = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, $students ) : $students;
				$students = array_diff( $students , $excluded_users );
				
				// $all_students = array_unique( array_merge( $all_students, $students ) );
				$class_size = is_array( $students ) ? count( $students ) : $students->get_total();
				$completed = 0;
				$percentage = 0;
				$course_title = get_the_title( $course->ID );
				// If no students in the course then the course has 0 percent completion.
				if ( empty( $students ) ) {
					$course_data[ $course_title ] = array(
						'percentage' => $percentage,
						'completed'  => $completed,
						'total'      => $class_size
					);
					continue;
				}
				foreach ( $students as $student ) {
					// Get course progress info.
					if ( isset($request_data['duration']) && 'all' != $request_data['duration'] ) {
						$start_date = strtotime( gmdate( 'Y-m-d', strtotime( '-' . $request_data['duration'] ) ) );
						$end_date = current_time( 'timestamp' );
						// $enrolled_on = get_user_meta( $student, 'course_' . $course->ID . '_access_from', true );
						$enrolled_on = ld_course_access_from( $course->ID, $student );
						if ( empty( $enrolled_on ) ) {
							$enrolled_on = learndash_user_group_enrolled_to_course_from( $student, $course->ID );
						}
						if ( empty( $enrolled_on ) ) {
							$class_size--;
							continue;
						}
						if ( (int) $enrolled_on < $start_date || (int) $enrolled_on > $end_date ) {
							$class_size--;
							continue;
						}
					}
					$is_complete = get_user_meta( $student, 'course_completed_' . $course->ID, true );
					if ( ! isset( $is_complete ) || empty( $is_complete ) ) {
						$percentage = $percentage + 0;
						continue;
					}
					$completed++;
				}
				if ( $class_size <= 0 ) {
					$course_data[ $course_title ] = array(
						'percentage' => $percentage,
						'completed'  => $completed,
						'total'      => $class_size
					);
					continue;
				}
				$percentage = floatval( number_format( 100 * $completed / $class_size, 2, '.', '' ) );
				$avg_percentage += $percentage;
				// Average completion Course-wise.
				$course_data[ $course_title ] = array(
					'percentage' => $percentage,
					'completed'  => $completed,
					'total'      => $class_size,
				);
			}
			$updated_on = current_time( 'timestamp' );
			$overall_average_completion = $this->get_average_completion_percentage( $all_courses, $course_count, $accessible_users, $excluded_users, $request_data, $updated_on, $request_data['disable_cache'] );
			// $overall_average_completion = floatval( number_format( $avg_percentage / $course_count, 2, '.', '' ) );// Cast to integer if no decimals.

			$showing        = ( ( (int) $request_data['page'] - 1 ) * 5 ) + count( $courses );
			$total          = $course_count;
			$response = array(
				'requestData'             => self::get_values_for_request_params( $request_data ),
				'averageCourseCompletion' => $overall_average_completion,
				'tableData'               => $course_data,
				'more_data'   => ( $total - $showing > 0 ) ? 'yes' : 'no',
				'total'       => $total,
				'showing'     => $showing,
				'updated'     => $updated_on
			);
			$current_timestamp = current_time( 'timestamp' );
			$wpdb->insert(
				$wpdb->prefix . 'wrld_cached_entries',
				array(
					'option_name'  => 'wrld_course_completion_rate_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['sort'] . '_' . $request_data['duration'] . '_' . $request_data['page'],
					'option_value' => maybe_serialize( $response ),
					'object_id'    => get_current_user_id(),
					'object_type'  => 'user',
					'created_on'   => $current_timestamp,
					'expires_on'   => $current_timestamp + DAY_IN_SECONDS
				),
				array(
					'%s',
					'%s',
					'%d',
					'%s',
					'%d',
					'%d',
				)
			);
			$updated_on = date_i18n( 'Y-m-d H:i:s', $current_timestamp );
			$response['updated_on'] = $updated_on;
			return new WP_REST_Response(
				$response,
				200
			);
		}
		$updated_on = date_i18n( 'Y-m-d H:i:s', $time_spent_row->created_on );
		$response = maybe_unserialize( $time_spent_row->option_value );
		if ( is_wp_error( $response ) ) {
			return $response;
		}
		$response['updated_on'] = $updated_on;
		return new WP_REST_Response(
			$response,
			200
		);
	}

	public function get_course_completion_rate_average() {
		// Get Inputs.
		$request_data = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING );
		$request_data = self::get_request_params( $request_data );
		do_action( 'wpml_switch_language',  $_GET[ 'wpml_lang' ] ); // switch the content language
		$user_role_access   = self::get_current_user_role_access();
		$accessible_courses = self::get_accessible_courses_for_the_user( get_current_user_id(), $user_role_access, 'course_completion_rate' );
		$accessible_users   = self::get_accessible_users_for_the_user( get_current_user_id(), $user_role_access, 'course_completion_rate' );
		$excluded_users     = get_option( 'exclude_users', array() );
		if ( empty( $excluded_users ) || ! defined( 'LDRP_PLUGIN_VERSION' ) ) {
			$excluded_users = array();
		}
		if ( isset( $request_data['group'] ) && ! empty( $request_data['group'] ) ) {
			$group_users = get_transient( 'wrld_group_students_data_' . $request_data['group'] );
			if ( false === $group_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
				// Get all students for a course.
				if ( get_option( 'migrated_group_access_data', false ) ) {
					$group_users = \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] );
				} else {
					$group_users = self::get_ld_group_user_ids( $request_data['group'] );
				}
				delete_transient( 'wrld_group_students_data_' . $request_data['group'] );
				set_transient( 'wrld_group_students_data_' . $request_data['group'], $group_users, 1 * HOUR_IN_SECONDS );
			}
			$accessible_users = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $group_users, $accessible_users ) : $group_users;
		}
		if ( isset( $request_data['category'] ) && ! empty( $request_data['category'] ) ) {
			// Check if course category enabled.
			if ( ! taxonomy_exists( 'ld_course_category' ) ) {
				return new WP_Error(
					'invalid-input',
					sprintf(/* translators: %s: custom label for course */
						__( '%s Category disabled. Please contact admin.', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'course' ),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					)
				);
			}
			// Check if valid category passed.
			if ( ! is_object( get_term_by( 'id', $request_data['category'], 'ld_course_category' ) ) ) {
				return new WP_Error(
					'invalid-input',
					sprintf(/* translators: %s: custom label for course */
						__( '%s Category doesn\'t exist', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'course' ),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					)
				);
			}
			$category_query = array(
				array(
					'taxonomy'         => 'ld_course_category',
					'field'            => 'term_id',
					'terms'            => $request_data['category'], // Where term_id of Term 1 is "1".
					'include_children' => false,
				),
			);
		} elseif ( isset( $request_data['group'] ) && ! empty( $request_data['group'] ) ) {
			$group_courses      = learndash_group_enrolled_courses( $request_data['group'] );
			$accessible_courses = ( ! is_null( $accessible_courses ) && -1 != $accessible_courses ) ? array_intersect( $group_courses, $accessible_courses ) : $group_courses;
			$group_users = get_transient( 'wrld_group_students_data_' . $request_data['group'] );
			if ( false === $group_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
				// Get all students for a course.
				if ( get_option( 'migrated_group_access_data', false ) ) {
					// $group_users = $group_users = \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] );;
					$group_users = \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] );
				} else {
					$group_users = self::get_ld_group_user_ids( $request_data['group'] );
				}
				delete_transient( 'wrld_group_students_data_' . $request_data['group'] );
				set_transient( 'wrld_group_students_data_' . $request_data['group'], $group_users, 1 * HOUR_IN_SECONDS );
			}
			$accessible_users   = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $group_users, $accessible_users ) : $group_users;
		}

		$count_query_args = array(
			'post_type'      => 'sfwd-courses',
			'posts_per_page' => -1,
			'post__in'       => -1 == $accessible_courses ? null : $accessible_courses,
			'suppress_filters' => 0,
		);

		if ( ! empty( $product_category_to_export_query ) ) {
			$count_query_args['tax_query'] = $category_query;
		}

		$all_courses  = get_posts( $count_query_args );
		$course_count = count( $all_courses );
		$course_data = array();
		$updated_on = current_time( 'timestamp' );
		$avg_percentage = $this->get_average_completion_percentage( $all_courses, $course_count, $accessible_users, $excluded_users, $request_data, $updated_on, true );
		return new WP_REST_Response(
			array(
				'requestData'             => self::get_values_for_request_params( $request_data ),
				'averageCourseCompletion' => $avg_percentage,
				'updated'                 => $updated_on
			),
			200
		);
	}

	public function get_average_completion_percentage( $courses, &$course_count, $accessible_users, $excluded_users, $request_data, &$updated_on, $disable_cache=false ) {
		global $wpdb;
		$group = ! empty( $request_data['group'] ) ? $request_data['group'] : 'all';
		$category = ! empty( $request_data['category'] ) ? $request_data['category'] : 'all';
		// $average_completion_percentage = get_option( 'wrld_average_course_completion_' . get_current_user_id() );
		$completion_percentage_row = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT id, option_value, expires_on, created_on FROM {$wpdb->prefix}wrld_cached_entries WHERE object_type=%s AND object_id=%d AND option_name=%s",
				'user',
				get_current_user_id(),
				'wrld_average_course_completion_' . $group . '_' . $category . '_' . $request_data['duration']
			)
		);
		if ( empty( $completion_percentage_row ) || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $completion_percentage_row->expires_on <= current_time( 'timestamp' ) || $disable_cache ) {
			if ( ! empty( $completion_percentage_row ) ) {
				$wpdb->delete(
					$wpdb->prefix . 'wrld_cached_entries',
					array(
						'id' => $completion_percentage_row->id
					),
					array(
						'%d'
					)
				);
			}
			$avg_percentage = 0;
			foreach ( $courses as $course ) {
				$course_price_type = learndash_get_course_meta_setting( $course->ID, 'course_price_type' );
				if ( 'open' === $course_price_type ) {
					$course_count--;
					continue;
				}
				$students = get_transient( 'wrld_course_students_data_' . $course->ID );
				if ( false === $students || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
				// Get all students for a course.
					if ( get_option( 'migrated_course_access_data', false ) ) {
						$students = \WRLD_Quiz_Export_Db::instance()->get_users_for_course( $course->ID );
					} else {
						$students = learndash_get_users_for_course( $course->ID, array(), false ); // Third argument is $exclude_admin.
					}
					$students = is_array( $students ) ? $students : $students->get_results();
					delete_transient( 'wrld_course_students_data_' . $course->ID );
					set_transient( 'wrld_course_students_data_' . $course->ID, $students, 1 * HOUR_IN_SECONDS );
				}
				$students = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, $students ) : $students;
				$students = array_diff( $students , $excluded_users );
				// $all_students = array_unique( array_merge( $all_students, $students ) );
				$class_size = is_array( $students ) ? count( $students ) : $students->get_total();
				$completed = 0;
				$percentage = 0;
				$course_title = get_the_title( $course->ID );
				// If no students in the course then the course has 0 percent completion.
				if ( empty( $students ) ) {
					continue;
				}
				foreach ( $students as $student ) {
					// Get course progress info.
					if ( isset($request_data['duration']) && 'all' != $request_data['duration'] ) {
						$start_date = strtotime( gmdate( 'Y-m-d', strtotime( '-' . $request_data['duration'] ) ) );
						$end_date = current_time( 'timestamp' );
						// $enrolled_on = get_user_meta( $student, 'course_' . $course->ID . '_access_from', true );
						$enrolled_on = ld_course_access_from( $course->ID, $student );
						if ( empty( $enrolled_on ) ) {
							$enrolled_on = learndash_user_group_enrolled_to_course_from( $student, $course->ID );
						}
						if ( empty( $enrolled_on ) ) {
							$class_size--;
							continue;
						}
						if ( (int) $enrolled_on < $start_date || (int) $enrolled_on > $end_date ) {
							$class_size--;
							continue;
						}
					}
					$is_complete = get_user_meta( $student, 'course_completed_' . $course->ID, true );
					if ( ! isset( $is_complete ) || empty( $is_complete ) ) {
						$percentage = $percentage + 0;
						continue;
					}
					$completed++;
				}
				if ( $class_size <= 0 ) {
					continue;
				}
				$percentage = floatval( number_format( 100 * $completed / $class_size, 2, '.', '' ) );
				$avg_percentage += $percentage;
				// Average completion Course-wise.
			}
			if ( $course_count <= 0 ) {
				$average_completion_percentage = 0;
			}
			$average_completion_percentage = floatval( number_format( $avg_percentage / $course_count, 2, '.', '' ) );// Cast to integer if no decimals.
			$current_timestamp = current_time( 'timestamp' );
			$wpdb->insert(
				$wpdb->prefix . 'wrld_cached_entries',
				array(
					'option_name'  => 'wrld_average_course_completion_' . $group . '_' . $category . '_' . $request_data['duration'],
					'option_value' => $average_completion_percentage,
					'object_id'    => get_current_user_id(),
					'object_type'  => 'user',
					'created_on'   => $current_timestamp,
					'expires_on'   => $current_timestamp + DAY_IN_SECONDS
				),
				array(
					'%s',
					'%d',
					'%d',
					'%s',
					'%d',
					'%d',
				)
			);
			$updated_on = date_i18n( 'Y-m-j H:i:s', $current_timestamp );
			return $average_completion_percentage;
		}
		$updated_on = date_i18n( 'Y-m-j H:i:s', $completion_percentage_row->created_on );
		return $completion_percentage_row->option_value;
	}

	/**
	 * This method is used to calculate course completion rate across courses by all the students.
	 * This method is doesn't support the date-wise filters because the completion dates aren't stored in the database.
	 *
	 * @return WP_REST_Response/WP_Error Objects.
	 */
	public function get_course_completion_rate_old() {
		// Get Inputs.
		$request_data = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING );
		$request_data = self::get_request_params( $request_data );
		unset( $request_data['start_date'] );
		unset( $request_data['end_date'] );
		$user_role_access   = self::get_current_user_role_access();
		$accessible_courses = self::get_accessible_courses_for_the_user( get_current_user_id(), $user_role_access, 'course_completion_rate' );
		$accessible_users   = self::get_accessible_users_for_the_user( get_current_user_id(), $user_role_access, 'course_completion_rate' );
		$excluded_users     = get_option( 'exclude_users', array() );
		if ( empty( $excluded_users ) || ! defined( 'LDRP_PLUGIN_VERSION' ) ) {
			$excluded_users = array();
		}
		if ( isset( $request_data['group'] ) && ! empty( $request_data['group'] ) ) {
			$group_users = get_transient( 'wrld_group_students_data_' . $request_data['group'] );
			if ( false === $group_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
				// Get all students for a course.
				if ( get_option( 'migrated_group_access_data', false ) ) {
					// $group_users = array_unique( array_merge( $group_users, \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] ) ) );
					$group_users = \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] );
				} else {
					$group_users = self::get_ld_group_user_ids( $request_data['group'] );
				}
				delete_transient( 'wrld_group_students_data_' . $request_data['group'] );
				set_transient( 'wrld_group_students_data_' . $request_data['group'], $group_users, 1 * HOUR_IN_SECONDS );
			}
			$accessible_users = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $group_users, $accessible_users ) : $group_users;
		}
		$category_query = array();
		$group_users    = null;// initialize with user ids if the user is group leader & have access to limited users

		if ( isset( $request_data['learner'] ) && ! empty( $request_data['learner'] ) ) {
			$learner_courses    = learndash_user_get_enrolled_courses( $request_data['learner'], array(), false );
			$accessible_courses = ( ! is_null( $accessible_courses ) && -1 != $accessible_courses ) ? array_intersect( $accessible_courses, $learner_courses ) : $learner_courses;
			$accessible_users   = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, array( $request_data['learner'] ) ) : $request_data['learner'];

			if ( empty( $accessible_courses ) ) {
				return new WP_Error(
					'no-data',
					sprintf(/* translators: %s: custom label for courses */
						__( 'No accessible %s found', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'courses' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}

			$total_percentage  = 0;
			$progress_data     = array();
			$completed_count   = 0;
			$not_started_count = 0;
			$in_progress_count = 0;

			foreach ( $accessible_courses as $course_id ) {
				$progress = learndash_user_get_course_progress( $request_data['learner'], $course_id, 'summary' );
				if ( empty( $progress ) || ! isset( $progress['total'] ) || 0 == $progress['total'] ) {
					$percentage = 0;
					$not_started_count++;
				} else {
					$percentage = floatval( number_format( 100 * $progress['completed'] / $progress['total'], 2, '.', '' ) );// Cast to integer if no decimals.
					if ( 100 == $percentage ) {
						$completed_count++;
					} elseif ( 0 == $percentage ) {
						$not_started_count++;
					} else {
						$in_progress_count++;
					}
				}
				$progress_data[ $course_id ] = array(
					'user_name' => get_the_title( $course_id ),
					'progress'  => 100 <= $percentage ? $percentage : 0,
				);
				$total_percentage            = 100 <= $percentage ? $total_percentage + $percentage : $total_percentage;
			}

			// Calculate average across courses.
			$average_completion_percentage = $total_percentage / count( $accessible_courses );
			return new WP_REST_Response(
				array(
					'requestData'             => self::get_values_for_request_params( $request_data ),
					'progress_data_new'       => $progress_data,
					'averageCourseCompletion' => $average_completion_percentage,
					'completedCount'          => $completed_count,
					'notstartedCount'         => $not_started_count,
					'inprogressCount'         => $in_progress_count,
				),
				200
			);
		} elseif ( isset( $request_data['course'] ) && ! empty( $request_data['course'] ) ) {
			if ( isset( $accessible_courses ) && -1 != $accessible_courses ) {
				if ( ! in_array( $request_data['course'], $accessible_courses ) ) {
					return new WP_Error(
						'unauthorized',
						sprintf(/* translators: %s: custom label for course */
							__( 'You don\'t have access to this %s.', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::label_to_lower( 'course' )
						),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					);
				}
			}
			$course = get_post( $request_data['course'] );
			// Check for valid course.
			if ( empty( $course ) ) {
				return new WP_Error(
					'invalid-input',
					sprintf(/* translators: %s: custom label for course */
						__( '%s doesn\'t exist', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'course' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}
			$course_price_type = learndash_get_course_meta_setting( $request_data['course'], 'course_price_type' );
			if ( 'open' === $course_price_type ) {
				return new WP_Error(
					'no-data',
					sprintf(/* translators: %s: custom label for courses */
						__( 'Reports for open %s are not accessible for the time-being', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'courses' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}
			$course_users = get_transient( 'wrld_course_students_data_' . $request_data['course'] );
			if ( false === $course_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
				if ( get_option( 'migrated_course_access_data', false ) ) {
					$course_users = \WRLD_Quiz_Export_Db::instance()->get_users_for_course( $request_data['course'] );
				} else {
					$course_users = learndash_get_users_for_course( $request_data['course'], array(), false );
				}
				$course_users     = is_array( $course_users ) ? $course_users : $course_users->get_results();
				delete_transient( 'wrld_course_students_data_' . $request_data['course'] );
				set_transient( 'wrld_course_students_data_' . $request_data['course'], $course_users, 1 * HOUR_IN_SECONDS );
			}
			$accessible_users = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $course_users, $accessible_users ) : $course_users;
			$accessible_users = array_diff( $accessible_users , $excluded_users );

			$student_count    = count( $accessible_users );
			// Check if any students enrolled.
			if ( empty( $accessible_users ) ) {
				return new WP_Error(
					'no-data',
					__( 'No Students enrolled', 'learndash-reports-by-wisdmlabs' ),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}

			$completed_count   = 0;
			$not_started_count = 0;
			$in_progress_count = 0;
			$total_percentage  = 0;
			// Get progress for each student.
			foreach ( $accessible_users as $student ) {

				$progress = learndash_user_get_course_progress( $student, $request_data['course'], 'summary' );
				if ( 0 < $progress['total'] ) {
					$percentage = floatval( number_format( 100 * $progress['completed'] / $progress['total'], 2, '.', '' ) );// Cast to integer if no decimals.
				} else {
					$percentage = 0;
				}
				$progress_data[ $student ] = array(
					'user_name' => get_userdata( $student )->display_name,
					'progress'  => 100 <= $percentage ? $percentage : 0,
				);
				if ( 0 == $percentage ) {
					$not_started_count++;
				} elseif ( 100 == $percentage ) {
					$completed_count++;
				} else {
					$in_progress_count++;
				}
				$total_percentage = 100 <= $percentage ? $total_percentage + $percentage : $total_percentage;
			}
			// Calculate average across students.
			$average_completion_percentage = $total_percentage / $student_count;
			return new WP_REST_Response(
				array(
					'requestData'             => self::get_values_for_request_params( $request_data ),
					'progress_data'           => $progress_data,
					'averageCourseCompletion' => $average_completion_percentage,
					'completedCount'          => $completed_count,
					'notstartedCount'         => $not_started_count,
					'inprogressCount'         => $in_progress_count,
				),
				200
			);
		} elseif ( isset( $request_data['category'] ) && ! empty( $request_data['category'] ) ) {
			// Check if course category enabled.
			if ( ! taxonomy_exists( 'ld_course_category' ) ) {
				return new WP_Error(
					'invalid-input',
					sprintf(/* translators: %s: custom label for course */
						__( '%s Category disabled. Please contact admin.', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'course' ),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					)
				);
			}
			// Check if valid category passed.
			if ( ! is_object( get_term_by( 'id', $request_data['category'], 'ld_course_category' ) ) ) {
				return new WP_Error(
					'invalid-input',
					sprintf(/* translators: %s: custom label for course */
						__( '%s Category doesn\'t exist', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'course' ),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					)
				);
			}
			$category_query = array(
				array(
					'taxonomy'         => 'ld_course_category',
					'field'            => 'term_id',
					'terms'            => $request_data['category'], // Where term_id of Term 1 is "1".
					'include_children' => false,
				),
			);
		} elseif ( isset( $request_data['group'] ) && ! empty( $request_data['group'] ) ) {
			$group_courses      = learndash_group_enrolled_courses( $request_data['group'] );
			$accessible_courses = ( ! is_null( $accessible_courses ) && -1 != $accessible_courses ) ? array_intersect( $group_courses, $accessible_courses ) : $group_courses;
			$group_users = get_transient( 'wrld_group_students_data_' . $request_data['group'] );
			if ( false === $group_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
				// Get all students for a course.
				if ( get_option( 'migrated_group_access_data', false ) ) {
					// $group_users = array_unique( array_merge( $group_users, \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] ) ) );
					$group_users = \WRLD_Quiz_Export_Db::instance()->get_users_for_group( $request_data['group'] );
				} else {
					$group_users = self::get_ld_group_user_ids( $request_data['group'] );
				}
				delete_transient( 'wrld_group_students_data_' . $request_data['group'] );
				set_transient( 'wrld_group_students_data_' . $request_data['group'], $group_users, 1 * HOUR_IN_SECONDS );
			}
			$accessible_users   = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $group_users, $accessible_users ) : $group_users;
		}

		$query_args = array(
			'post_type'      => 'sfwd-courses',
			'posts_per_page' => '-1',
			'post__in'       => -1 == $accessible_courses ? null : $accessible_courses,
			'suppress_filters' => 0,
		);

		if ( ! empty( $category_query ) ) {
			$query_args['tax_query'] = $category_query;
		}

		$courses     = get_posts( $query_args );
		$course_data = array();
		// Check if any courses present in the category.
		if ( empty( $courses ) || ( ! is_null( $accessible_courses ) && -1 != $accessible_courses && empty( $accessible_courses ) ) ) {
			return new WP_Error(
				'no-data',
				sprintf(/* translators: %s: custom label for courses */
					__( 'No %s found', 'learndash-reports-by-wisdmlabs' ),
					\LearnDash_Custom_Label::get_label( 'courses' )
				),
				array( 'requestData' => self::get_values_for_request_params( $request_data ) )
			);
		}
		// Average Completion percentage count variables.
		$course_count     = count( $courses );
		$total_completion = 0;

		// Count for students based on completion status.
		$all_students = array();
		foreach ( $courses as $course ) {
			$course_price_type = learndash_get_course_meta_setting( $course->ID, 'course_price_type' );
			if ( 'open' === $course_price_type ) {
				continue;
			}
			$students = get_transient( 'wrld_course_students_data_' . $course->ID );
			if ( false === $students || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
			// Get all students for a course.
				if ( get_option( 'migrated_course_access_data', false ) ) {
					$students = \WRLD_Quiz_Export_Db::instance()->get_users_for_course( $course->ID );
				} else {
					$students = learndash_get_users_for_course( $course->ID, array(), false ); // Third argument is $exclude_admin.
				}
				$students = is_array( $students ) ? $students : $students->get_results();
				delete_transient( 'wrld_course_students_data_' . $course->ID );
				set_transient( 'wrld_course_students_data_' . $course->ID, $students, 1 * HOUR_IN_SECONDS );
			}
			$students = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, $students ) : $students;
			$students = array_diff( $students , $excluded_users );
			
			// $all_students = array_unique( array_merge( $all_students, $students ) );
			$class_size = is_array( $students ) ? count( $students ) : $students->get_total();
			$percentage = 0;

			// If no students in the course then the course has 0 percent completion.
			if ( empty( $students ) ) {
				$course_data[ $course->ID ] = array(
					'completion' => $percentage,
					'title'      => get_the_title( $course->ID ),
				);
				continue;
			}

			foreach ( $students as $student ) {
				// Get course progress info.
				$progress = learndash_user_get_course_progress( $student, $course->ID, 'summary' );
				if ( ! isset( $progress['total'] ) || empty( $progress['total'] ) ) {
					$percentage = $percentage + 0;
					continue;
				}
				// Logic to calculate the status-wise count.
				switch ( $progress['status'] ) {
					case 'completed':
						// $completed_count++;
						if ( ! isset( $all_students[ $student ]['completed'] ) ) {
							$all_students[ $student ]['completed'] = 0;
						}
						$all_students[ $student ]['completed'] += 1;
						$percentage                             = $percentage + ( ( 100 * $progress['completed'] ) / $progress['total'] );
						break;
					case 'not_started':
						if ( ! isset( $all_students[ $student ]['not_started'] ) ) {
							$all_students[ $student ]['not_started'] = 0;
						}
						$all_students[ $student ]['not_started'] += 1;
						// $not_started_count++;
						break;
					case 'in_progress':
						if ( ! isset( $all_students[ $student ]['in_progress'] ) ) {
							$all_students[ $student ]['in_progress'] = 0;
						}
						$all_students[ $student ]['in_progress'] += 1;
						// $in_progress_count++;
						break;
					default:
						break;
				}
			}

			// Average completion Course-wise.
			$course_data[ $course->ID ] = array(
				'completion' => floatval( number_format( $percentage / $class_size, 2, '.', '' ) ),
				'title'      => get_the_title( $course->ID ),
			);

			$total_completion = $total_completion + $course_data[ $course->ID ]['completion'];
		}

		$not_started_count = 0;
		$completed_count   = 0;
		$in_progress_count = 0;
		foreach ( $all_students as $student_id => $progress ) {
			$max_status = array_keys( $progress, max( $progress ) );
			if ( in_array( 'completed', $max_status ) && ( in_array( 'in_progress', array_keys( $progress ) ) || in_array( 'not_started', array_keys( $progress ) ) ) ) {
				unset( $progress['completed'] );
				$max_status = array_keys( $progress, max( $progress ) );
			}
			if ( in_array( 'not_started', $max_status ) && ( in_array( 'in_progress', array_keys( $progress ) ) || in_array( 'completed', array_keys( $progress ) ) ) ) {
				unset( $progress['not_started'] );
				$max_status = array_keys( $progress, max( $progress ) );
			}
			if ( in_array( 'not_started', $max_status ) ) {
				$not_started_count++;
			} elseif ( in_array( 'in_progress', $max_status ) ) {
				$in_progress_count++;
			} elseif ( in_array( 'completed', $max_status ) ) {
				$completed_count++;
			}
		}

		if ( $course_count <= 0 ) {
			return new WP_Error(
				'no-data',
				sprintf(/* translators: %s: custom label for courses */
					__( 'No %s found', 'learndash-reports-by-wisdmlabs' ),
					\LearnDash_Custom_Label::get_label( 'courses' )
				),
				array( 'requestData' => self::get_values_for_request_params( $request_data ) )
			);
		}

		// Average Completion across all courses.
		$overall_average_completion = floatval( number_format( $total_completion / $course_count, 2, '.', '' ) );// Cast to integer if no decimals.

		return new WP_REST_Response(
			array(
				'requestData'             => self::get_values_for_request_params( $request_data ),
				'averageCourseCompletion' => $overall_average_completion,
				'courseWiseCompletion'    => $course_data,
				'completedCount'          => $completed_count,
				'notstartedCount'         => $not_started_count,
				'inprogressCount'         => $in_progress_count,
			),
			200
		);
	}
}
