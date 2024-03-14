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
 * @subpackage LearnDash API
 */
class WRLD_Course_Time_Tracking extends WRLD_Common_Functions {

	/**
	 * This static contains the number of points being assigned on course completion
	 *
	 * @var    Instance of WRLD_Course_Time_Tracking class
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
	 * This is a constructor which will be used to initialize required hooks.
	 *
	 * @since  1.0.0
	 * @access private
	 * @see    initHook static method
	 */
	private function __construct() {
	}

	/**
	 * This method is used to add all the WordPress hooks/filters.
	 */
	public function init_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_tracking_script' ) );// Enqueue Tracking JS.
		add_action( 'wp_ajax_add_time_entry', array( $this, 'add_time_tracking_entry' ) );// Add time entry.
		add_action( 'learndash_course_completed', array( $this, 'save_completion_time' ), 10, 2 );
		add_action( 'learndash_lesson_completed', array( $this, 'save_lesson_completion_time' ), 10, 3 );
		add_action( 'learndash_topic_completed', array( $this, 'save_topic_completion_time' ), 10, 4 );
		add_action( 'learndash_quiz_completed', array( $this, 'save_quiz_completion_time' ), 10, 2 );
		add_action( 'update_user_metadata', array( $this, 'add_ts_entry_course_user_enroll' ), 10, 4 );

	}

	public function save_completion_time( $course_data ) {
		$user   = $course_data['user'];
		$course = $course_data['course'];
		$time   = $this->fetch_user_course_time_spent( $course->ID, $user->ID );
		update_user_meta( $user->ID, 'course_time_' . $course->ID, $time );
		$this->wrld_update_completion_time_spent($course->ID , $user->ID , $time );
	}

	public function save_lesson_completion_time( $lesson_data ) {
		$user   = $lesson_data['user'];
		$course = $lesson_data['course'];
		$lesson = $lesson_data['lesson'];

		$time          = $this->fetch_user_module_time_spent( $lesson->ID, $course->ID, $user->ID );
		$all_module_id = learndash_course_get_children_of_step( $course->ID, $lesson->ID, '', 'ids', true );
		update_user_meta( $user->ID, 'lesson_time_bk_' . $lesson->ID, $time );
		if ( ! empty( $all_module_id ) ) {
			foreach ( $all_module_id as $module_id ) {
				$time += $this->fetch_user_module_time_spent( $module_id, $course->ID, $user->ID );
			}
		}
		update_user_meta( $user->ID, 'lesson_time_' . $lesson->ID, $time );
	}

	public function save_topic_completion_time( $topic_data ) {
		$user   = $topic_data['user'];
		$course = $topic_data['course'];
		$topic  = $topic_data['topic'];

		$time          = (int) $this->fetch_user_module_time_spent( $topic->ID, $course->ID, $user->ID );
		$all_module_id = learndash_course_get_children_of_step( $course->ID, $topic->ID, '', 'ids', true );
		update_user_meta( $user->ID, 'topic_time_bk_' . $topic->ID, $time );
		if ( ! empty( $all_module_id ) ) {
			foreach ( $all_module_id as $module_id ) {
				$time += (int) $this->fetch_user_module_time_spent( $module_id, $course->ID, $user->ID );
			}
		}
		update_user_meta( $user->ID, 'topic_time_' . $topic->ID, $time );
	}

	public function save_quiz_completion_time( $quizdata, $user ) {
		$time = $this->fetch_user_module_time_spent( $quizdata['quiz'], $quizdata['course']->ID, $user->ID );
		update_user_meta( $user->ID, 'quiz_time_' . $quizdata['quiz'], $time );
	}

	public function add_ts_entry_course_user_enroll( $meta_id, $user_id, $meta_key, $meta_value ) {
		//for group
		$pattern = '/learndash_group_users_/';
		if (preg_match($pattern, $meta_key)) {
			$group_id = $meta_value;
			$group_course_ids = learndash_group_enrolled_courses($group_id);
			// Ensure $group_course_ids is an array
			if (!is_array($group_course_ids)) {
				$group_course_ids = array();
			}
			foreach ($group_course_ids as $course_id) {
				// Output or process the course ID as needed
				$this->createTimeSpentEntryForUser($course_id,$user_id, $group_id);
				
			}
		}
		if ( ! function_exists( 'str_starts_with' ) ) {//for PHP < 8.0
			if ( strpos( $meta_key, 'course_') !== 0 || substr_compare( $meta_key, '_access_from', -strlen( '_access_from' ) ) !== 0 ) {
				return;
			}
		} else {// PHP > 8.0
			if ( ! str_starts_with( $meta_key, 'course_' ) || ! str_ends_with( $meta_key , '_access_from' ) ) {
				return;
			}
		}  
		global $wpdb;
		$table_name_time_spent = $wpdb->prefix . 'ld_course_time_spent'; 
		$data_formats = array(
			'%d', // course_id
			'%d', // total_time_spent
			'%d', // completion_time
			'%d', // user_id
			'%d', // enrollment_date
			'%d', // compenrolled_onletion_date
		);
		$course_id = filter_var( $meta_key, FILTER_SANITIZE_NUMBER_INT );
        
		$total_time_spent = 0;
		$completion_time = 0;
		$timestamp = null;
		$enrolled_on = current_time( 'timestamp' );
		
		$data = array(
			'course_id' => $course_id,
			'total_time_spent' => $total_time_spent,
			'completion_time' => $completion_time == 0 ? null : $completion_time ,
			'user_id' => $user_id,
			'enrollment_date' => $enrolled_on,
			'completion_date' => $timestamp,
		);
		$wpdb->insert(
			$table_name_time_spent,
			$data,
			$data_formats
		);
		
	}

	public function createTimeSpentEntryForUser($course_id , $user_id, $group_id = null){

		// Check if the entry exists in the table
		global $wpdb;
		
		$table_name_time_spent = $wpdb->prefix . 'ld_course_time_spent'; 
		$query = $wpdb->prepare(
			"SELECT COUNT(*) FROM {$table_name_time_spent} WHERE course_id = %d AND user_id = %d",
			$course_id,
			$user_id
		);

		$entry_exists = $wpdb->get_var($query);

		$data_formats = array(
			'%d', // course_id
			'%d', //post_id
			'%d', //group_id
			'%d', // total_time_spent
			'%d',// completion_time
			'%d', // user_id
			'%d', // enrollment_date
			'%d', // completion_date
		);
		if ($entry_exists) {
			return;
		} else {
			// Insert a new entry
			$enrolled_on = ld_course_access_from( $course_id, $user_id );
			if ( empty( $enrolled_on ) ) {
			    $enrolled_on = learndash_group_course_access_from( $group_id, $course_id );
			}
			$userdata = get_userdata( $user_id );
			$user_registered_timestamp = strtotime( $userdata->user_registered );
			if ( ! is_null( $enrolled_on ) ) {
				if ( $enrolled_on <= time() ) {
					/** If the user registered AFTER the course was enrolled into the group
					 * then we use the user registration date.
					 */
					if ( $user_registered_timestamp > $enrolled_on ) {
						if ( ( defined( 'LEARNDASH_GROUP_ENROLLED_COURSE_FROM_USER_REGISTRATION' ) ) && ( true === LEARNDASH_GROUP_ENROLLED_COURSE_FROM_USER_REGISTRATION ) ) {
							$enrolled_on = $user_registered_timestamp;
						}
					}
				} else {
					/**
					 * If $enrolled_on is greater than the current timestamp
					 * we reset the enrolled from time to null. Not sure why.
					 */
					$enrolled_on = null;
				}
			}
			$total_time_spent = 0;
			// $completion_time = get_user_meta( $user_id, 'course_time_' . $course_id, true );
			$timestamp = null;
			$data = array(
				'course_id' => $course_id,
				'post_id' => $course_id,
				'group_id' => $group_id,
				'total_time_spent' => 0,
				'completion_time' => null ,
				'user_id' => $user_id,
				'enrollment_date' =>  $enrolled_on,
				'completion_date' => $timestamp,
			);
			$wpdb->insert(
				$table_name_time_spent,
				$data,
				$data_formats
			);
			return;
		}

	}

	/**
	 * Enqueue the script on course/lesson/topic which tracks the amount of time spent on a particular module by a student.
	 *
	 * @return void
	 */
	public function enqueue_tracking_script() {
		global $post;

		if ( ! is_singular( array( 'sfwd-courses', 'sfwd-lessons', 'sfwd-topic', 'sfwd-quiz' ) ) ) {
			return;
		}

		$min = '.min';
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$min = '';
		}

		if ( ! is_user_logged_in() || ! defined( 'LDRP_PLUGIN_VERSION' ) ) {
			return;
		}

		$course_id = learndash_get_course_id( $post->ID );
		$user_id   = get_current_user_id();
		$meta      = learndash_get_setting( $course_id );

		if ( ( isset( $meta['course_price_type'] ) ) && ( 'open' === $meta['course_price_type'] ) ) {
			return;
		}

		if ( ! sfwd_lms_has_access( $course_id, $user_id ) ) {
			return;
		}

		wp_enqueue_script( 'wrld_time_tracking_script', plugins_url( 'assets/js/time-tracking/index.js', WRLD_REPORTS_FILE ), array( 'jquery' ), WRLD_PLUGIN_VERSION, true );

		wp_localize_script(
			'wrld_time_tracking_script',
			'page_info',
			array(
				'post_id'     => $post->ID,
				'course_id'   => $course_id,
				'user_id'     => $user_id,
				'is_enrolled' => sfwd_lms_has_access( $course_id, $user_id ),
				'security'    => wp_create_nonce( 'add-course-time' ),
				'ajax_url'    => admin_url( 'admin-ajax.php' ),
			)
		);

		wp_localize_script(
			'wrld_time_tracking_script',
			'settings',
			array(
				'status'   => get_option( 'wrld_time_tracking_status', 'on' ),
				'timer'    => get_option( 'wrld_time_tracking_timer', 600 ),
				'message'  => get_option( 'wrld_time_tracking_message', __( 'Are you still on this page?', 'learndash-reports-by-wisdmlabs' ) ),
				'btnlabel' => get_option( 'wrld_time_tracking_btnlabel', 'Yes' ),
			)
		);
	}

	/**
	 * This method adds an timespent entry for a user on a particular course.
	 */
	public function add_time_tracking_entry() {
		global $wpdb;
		$user_id          = filter_input( INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT );
		$post_id          = filter_input( INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT );
		$course_id        = filter_input( INPUT_POST, 'course_id', FILTER_SANITIZE_NUMBER_INT );
		$activity_updated = filter_input( INPUT_POST, 'time', FILTER_VALIDATE_INT );
		$time_spent       = filter_input( INPUT_POST, 'total_time', FILTER_VALIDATE_INT );
		// $ip_address       = $this->get_user_ip_address();
		$nonce = filter_input( INPUT_POST, 'security', FILTER_SANITIZE_STRING );

		if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'add-course-time' ) ) {
			wp_send_json_error();
			die();
		}
		if ( empty( $post_id ) || empty( $course_id ) || empty( $time_spent ) ) {
			wp_send_json_error();
			die();
		}

		//add data into ld_course time spent_table
		$time_spent_table_name = $wpdb->prefix . 'ld_course_time_spent';

		$is_entry_exist = $this->fetch_last_updated_time_spent( $post_id, $course_id, $user_id );

		if ( empty( $is_entry_exist ) ) {
			//check category
			// Get the categories assigned to the course
			$categories = wp_get_post_terms($course_id, 'ld_course_category');
			$category_id = null;
			if(count($categories) > 0){
				$category_id = $categories[0]->term_id;
			}
			// Create new entry.
			$insert_id = $wpdb->insert(
				$time_spent_table_name,
				array(
					'course_id'        => $course_id,
					'post_id'          => $post_id,
					'user_id'          => $user_id,
					'group_id'          => wrld_get_user_course_group_id($course_id , $user_id) ?? null,
					'category_id'          => $category_id ?? null,
					'completion_time' => null,
					'total_time_spent'       => $time_spent,
					'enrollment_date'       => current_time( 'timestamp' ),
					'completion_date'       => null,
				),
				array(
					'%d',
					'%d',
					'%d',
					'%d',
					'%d',
					'%d',
					'%d',
					'%s',
					'%s',
				)
			);
		}else{

			// Update existing entry.
			$activity = $this->fetch_last_updated_time_spent_entry( $post_id, $course_id, $user_id );
			
			$activity_id         = current( array_column( $activity, 'id' ) );
			$previous_time_spent = current( array_column( $activity, 'total_time_spent' ) );
			$total_time_spent    = $time_spent + $previous_time_spent;

			$updated = $wpdb->update(
				$time_spent_table_name,
				array(
					'total_time_spent'       => $total_time_spent,
				),
				array(
					'id' => $activity_id,
				),
				array(
					'%d',
				),
				array(
					'%d',
				)
			);

		}

		//end ld_course_time_spent code


		$table_name   = $wpdb->prefix . 'ld_time_entries';
		$last_updated = $this->fetch_last_updated_activity( $post_id, $course_id, $user_id );
		if ( empty( $last_updated ) ) {
			// Create new entry.
			$insert_id = $wpdb->insert(
				$table_name,
				array(
					'course_id'        => $course_id,
					'post_id'          => $post_id,
					'user_id'          => $user_id,
					'activity_updated' => $activity_updated,
					'time_spent'       => $time_spent,
					'ip_address'       => '',
				),
				array(
					'%d',
					'%d',
					'%d',
					'%d',
					'%d',
					'%s',
				)
			);
			if ( false === $insert_id ) {
				wp_send_json_error();
				die();
			}
			wp_send_json_success();
			die();
		}
		// Update existing entry.
		$activity = $this->fetch_last_updated_entry( $post_id, $course_id, $user_id );
		if ( empty( $activity ) ) {
			wp_send_json_error();
			die();
		}
		$activity_id         = current( array_column( $activity, 'id' ) );
		$previous_time_spent = current( array_column( $activity, 'time_spent' ) );
		$total_time_spent    = $time_spent + $previous_time_spent;

		$updated = $wpdb->update(
			$table_name,
			array(
				'activity_updated' => $activity_updated,
				'time_spent'       => $total_time_spent,
			),
			array(
				'id' => $activity_id,
			),
			array(
				'%d',
				'%d',
			),
			array(
				'%d',
			)
		);
		if ( false === $updated ) {
			wp_send_json_error();
			die();
		}
		wp_send_json_success();
		die();
	}

	public function wrld_update_completion_time_spent($course_id, $user_id = 0 , $time_spent=0){
		global $wpdb;

		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}
        
		// Update existing entry.
		$time_spent_table_name = $wpdb->prefix . 'ld_course_time_spent';
		$activity = $this->fetch_last_updated_time_spent_entry( $course_id, $course_id, $user_id );
			
		$activity_id         = current( array_column( $activity, 'id' ) );
		

		$updated = $wpdb->update(
			$time_spent_table_name,
			array(
				'completion_time'       => $time_spent,
				'completion_date'		=> current_time( 'timestamp' ),
			),
			array(
				'id' => $activity_id,
			),
			array(
				'%d',
			),
			array(
				'%d',
				'%d',
			)
		);

	}

	/**
	 * This method is used to fetch the last updated entry for a user.
	 *
	 * @param  integer $course_id    Course ID.
	 * @param  integer $user_id      User ID.
	 * @return integer $enrolled_group_id  value for the supplied params.
	 */

	public function wrld_get_user_course_group_id($course_id , $user_id = 0){

		// Get the group ID(s) associated with the course and user
		$group_ids = learndash_get_course_groups($course_id);
		$enrolled_group_id = null;
	
		// Loop through the group IDs
		foreach ($group_ids as $group_id) {
			// Check if the user is enrolled in the group
			if (learndash_is_user_in_group($user_id, $group_id)) {
				$enrolled_group_id  = $group_id;
				break;
			}
		}
	  return $enrolled_group_id ?? null;
   }

	/**
	 * This method is used to fetch user's IP address.
	 *
	 * @return IP Address of the user.
	 */
	public function get_user_ip_address() {
		$ip_addr = '';
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {// phpcs:ignore
			// check ip from shared internet.
			$ip_addr = $_SERVER['HTTP_CLIENT_IP'];// phpcs:ignore
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {// phpcs:ignore
			// to check ip is passed from proxy.
			$ip_addr = $_SERVER['HTTP_X_FORWARDED_FOR'];// phpcs:ignore
		} else {
			$ip_addr = $_SERVER['REMOTE_ADDR'];// phpcs:ignore
		}
		return apply_filters( 'ldrp_get_ip', $ip_addr );
	}

	/**
	 * This method is used to fetch the last updated entry for a user.
	 *
	 * @param  integer $post_id      Post ID.
	 * @param  integer $course_id    Course ID.
	 * @param  integer $user_id      User ID.
	 * @return array    Timespent value for the supplied params.
	 */
	public function fetch_last_updated_entry( $post_id, $course_id, $user_id = 0 ) {
		global $wpdb;

		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$table_name = $wpdb->prefix . '';
		$output     = $wpdb->get_results( $wpdb->prepare( 'SELECT id, time_spent FROM ' . $wpdb->prefix . 'ld_time_entries WHERE post_id = %d AND course_id = %d AND user_id = %d', $post_id, $course_id, $user_id ), ARRAY_A );
		return $output;
	}


	/**
	 * This method is used to fetch the last updated entry for a user.
	 *
	 * @param  integer $post_id      Post ID.
	 * @param  integer $course_id    Course ID.
	 * @param  integer $user_id      User ID.
	 * @return array    Timespent value for the supplied params.
	 */
	public function fetch_last_updated_time_spent_entry( $post_id, $course_id, $user_id = 0 ) {
		global $wpdb;

		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$table_name = $wpdb->prefix . '';
		$output     = $wpdb->get_results( $wpdb->prepare( 'SELECT id, total_time_spent , completion_time FROM ' . $wpdb->prefix . 'ld_course_time_spent WHERE course_id = %d AND user_id = %d', $course_id, $user_id ), ARRAY_A );
		return $output;
	}

	/**
	 * This method is used to fetch the last updated activity for a user.
	 *
	 * @param  integer $post_id      Post ID.
	 * @param  integer $course_id    Course ID.
	 * @param  integer $user_id      User ID.
	 * @return array    Timespent value for the supplied params.
	 */
	public function fetch_last_updated_activity( $post_id, $course_id, $user_id = 0 ) {
		global $wpdb;

		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$output = $wpdb->get_results( $wpdb->prepare( 'SELECT time_spent FROM ' . $wpdb->prefix . 'ld_time_entries WHERE post_id = %d AND course_id = %d AND user_id = %d', $post_id, $course_id, $user_id ), ARRAY_A );
		if ( empty( $output ) ) {
			return false;
		}
		return $output;
		// $latest_update = max( array_column( $output, 'activity_updated' ) );// Max timestamp.
		// return $latest_update;
	}

		/**
	 * This method is used to fetch the last updated ld_course_time_spent for a user.
	 *
	 * @param  integer $post_id      Post ID.
	 * @param  integer $course_id    Course ID.
	 * @param  integer $user_id      User ID.
	 * @return array    Timespent value for the supplied params.
	 */
	public function fetch_last_updated_time_spent( $post_id, $course_id, $user_id = 0 ) {
		global $wpdb;

		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$output = $wpdb->get_results( $wpdb->prepare( 'SELECT total_time_spent , completion_time FROM ' . $wpdb->prefix . 'ld_course_time_spent WHERE course_id = %d AND user_id = %d', $course_id, $user_id ), ARRAY_A );
		if ( empty( $output ) ) {
			return false;
		}
		return $output;
		// $latest_update = max( array_column( $output, 'activity_updated' ) );// Max timestamp.
		// return $latest_update;
	}

	/**
	 * This method is used to fetch user's time spent on a course.
	 *
	 * @param  int $course_id Course ID.
	 * @param  int $user_id   User ID.
	 * @return int Time in seconds.
	 */
	public function fetch_user_course_time_spent( $course_id, $user_id ) {
		global $wpdb;

		$total_time_spent = 0;

		$output = $wpdb->get_results( $wpdb->prepare( 'SELECT time_spent FROM ' . $wpdb->prefix . 'ld_time_entries WHERE course_id = %d AND user_id = %d', $course_id, $user_id ), ARRAY_A );

		if ( empty( $output ) ) {
			return $total_time_spent;
		}

		$total_time_spent = array_sum( array_column( $output, 'time_spent' ) );
		return $total_time_spent;
	}

	/**
	 * This method is used to fetch user's time spent on a course.
	 *
	 * @param  int $course_id Course ID.
	 * @param  int $user_id   User ID.
	 * @return int Time in seconds.
	 */
	public function fetch_user_average_course_completion_time( $course_id, $user_id ) {
		$time = get_user_meta( $user_id, 'course_time_' . $course_id, true );
		return empty( $time ) ? 0 : $time;
	}

	/**
	 * This method is used to fetch user's time spent on a course.
	 *
	 * @param  int $course_id Course ID.
	 * @param  int $user_id   User ID.
	 * @return int Time in seconds.
	 */
	public function fetch_user_average_lesson_completion_time( $lesson_id, $user_id ) {
		$time = get_user_meta( $user_id, 'lesson_time_' . $lesson_id, true );
		return empty( $time ) ? 0 : $time;
	}
	/**
	 * This method is used to fetch user's time spent on a course.
	 *
	 * @param  int $course_id Course ID.
	 * @param  int $user_id   User ID.
	 * @return int Time in seconds.
	 */
	public function fetch_user_average_topic_completion_time( $topic_id, $user_id ) {
		$time = get_user_meta( $user_id, 'topic_time_' . $topic_id, true );
		return empty( $time ) ? 0 : $time;
	}
	/**
	 * This method is used to fetch user's time spent on a course.
	 *
	 * @param  int $course_id Course ID.
	 * @param  int $user_id   User ID.
	 * @return int Time in seconds.
	 */
	public function fetch_user_average_quiz_completion_time( $quiz_id, $user_id ) {
		$time = get_user_meta( $user_id, 'quiz_time_' . $quiz_id, true );
		return empty( $time ) ? 0 : $time;
	}

	/**
	 * This method is used to fetch user's time spent on a course.
	 *
	 * @param  int $course_id Course ID.
	 * @param  int $user_id   User ID.
	 * @return int Time in seconds.
	 */
	public function fetch_user_course_completion_time_spent( $course_id, $user_id ) {
		global $wpdb;

		$total_time_spent     = 0;
		$completed_time_stamp = $this->learndash_get_completed_timestamp( $user_id, $course_id, 'course' );

		$output = $wpdb->get_results( $wpdb->prepare( 'SELECT time_spent FROM ' . $wpdb->prefix . 'ld_time_entries WHERE course_id = %d AND user_id = %d AND activity_updated <= %d', $course_id, $user_id, $completed_time_stamp ), ARRAY_A );

		if ( empty( $output ) ) {
			return $total_time_spent;
		}

		$total_time_spent = array_sum( array_column( $output, 'time_spent' ) );
		return $total_time_spent;
	}

	/**
	 * This method is used to fetch user's time spent on a course.
	 *
	 * @param  int $course_id Course ID.
	 * @param  int $user_id   User ID.
	 * @return int Time in seconds.
	 */
	public function fetch_user_module_time_spent( $post_id, $course_id, $user_id ) {
		global $wpdb;

		$total_time_spent = 0;

		$output = $wpdb->get_results( $wpdb->prepare( 'SELECT time_spent FROM ' . $wpdb->prefix . 'ld_time_entries WHERE post_id = %d AND course_id = %d AND user_id = %d', $post_id, $course_id, $user_id ), ARRAY_A );

		if ( empty( $output ) ) {
			return $total_time_spent;
		}

		$total_time_spent = array_sum( array_column( $output, 'time_spent' ) );
		return $total_time_spent;
	}

	public function learndash_get_completed_timestamp( $user_id = 0, $course_id = 0, $type = 'course' ) {
		$completed_on_timestamp = 0;
		if ( ( ! empty( $user_id ) ) && ( ! empty( $course_id ) ) ) {
			if ( 'course' === $type ) {
				$completed_on_timestamp = get_user_meta( $user_id, 'course_completed_' . $course_id, true );
			}

			if ( empty( $completed_on_timestamp ) ) {
				$activity_query_args = array(
					'post_ids'      => $course_id,
					'user_ids'      => $user_id,
					'activity_type' => $type,
					'per_page'      => 1,
				);

				$activity = learndash_reports_get_activity( $activity_query_args );
				if ( ! empty( $activity['results'] ) ) {
					foreach ( $activity['results'] as $activity_item ) {
						if ( property_exists( $activity_item, 'activity_completed' ) ) {
							$completed_on_timestamp = $activity_item->activity_completed;
							if ( 'course' === $type ) {
								// To make the next check easier we update the user meta.
								update_user_meta( $user_id, 'course_completed_' . $course_id, $completed_on_timestamp );
							}
							break;
						}
					}
				}
			}
		}

		return $completed_on_timestamp;
	}

	/**
	 * This method is used to get user's time spent on a quiz.
	 *
	 * @param  int $user_id User ID.
	 * @param  int $quiz_id Quiz ID.
	 * @return int Timespent on a quiz by a user in seconds.
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
		$attempts         = learndash_get_user_course_attempts( $user_id, $course_id );

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

	/**
	 * This method returns time spent on each of the courses.
	 *
	 * @return WP_Rest_Response/WP_Error object.
	 */
	public function get_course_time_spent() {
		global $wpdb;
		// Get Inputs.
		global $wpdb;
		$request_data = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING );
		$request_data = self::get_request_params( $request_data );
		$user_role_access   = self::get_current_user_role_access();
		do_action( 'wpml_switch_language',  $_GET[ 'wpml_lang' ] ); // switch the content language
		$time_spent_row = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT id, option_value, expires_on, created_on FROM {$wpdb->prefix}wrld_cached_entries WHERE object_type=%s AND object_id=%d AND option_name=%s",
				'user',
				get_current_user_id(),
				'wrld_time_spent_on_a_course_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['duration'] . '_' . $request_data['learner'] . '_' . $request_data['timeperiod'] . '_' . $request_data['rtype']
			)
		);

		if ( empty( $time_spent_row ) || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $time_spent_row->expires_on <= current_time( 'timestamp' ) || $request_data['disable_cache'] ){
          
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



		if ( isset( $request_data['learner'] ) && ! empty( $request_data['learner'] ) ) {
            $where = " ";
			$where1 = " ";
			if($request_data['duration'] != 'all'){
				$afterDuration = $request_data['duration'];
				$enrollment_date = strtotime( gmdate( 'Y-m-d', strtotime( '-' . $afterDuration ) ) );
			  	$where = $where . "AND ct.enrollment_date > ".$enrollment_date . " ";
				$where1 = $where . "AND enrollment_date > ".$enrollment_date . " ";
			}

			$is_wrld_json_source = apply_filters('wrld_api_json_sorce' , false);
			if($is_wrld_json_source){
				$response = apply_filters('wrld_api_response_from_json_time_spent_learner',$request_data);
				$response['requestData'] = self::get_values_for_request_params( $request_data );
				
				return new WP_REST_Response(
					$response,
					200
				);

			}

            $user_id = $request_data['learner'];
			$query = "
			SELECT p.ID AS course_id, p.post_title AS course_name
			FROM {$wpdb->prefix}ld_course_time_spent ct
			JOIN {$wpdb->prefix}posts p ON ct.course_id = p.ID
			WHERE p.post_type = 'sfwd-courses'
			AND ct.user_id = $user_id $where
			GROUP BY ct.course_id ORDER BY ct.course_id ASC;
			";
			

			$results = $wpdb->get_results($query, ARRAY_A);
			$course_ids = [];
			$course_names = [];
			if ($results) {
				$course_ids = wp_list_pluck($results, 'course_id');
				
				if ( isset( $_GET[ 'wpml_lang' ] ) ) {
					$args             = array(
						'post_type'      => 'sfwd-courses',
						'posts_per_page' => -1,
						'suppress_filters' => 0,
						'fields'           => 'ids',
					);
					$language_courses = get_posts( $args );
					$course_ids = array_intersect( $course_ids, $language_courses );
				}
				$results = array_filter($results, function($result) use ($course_ids) {
					if ( in_array( $result['course_id'], $course_ids ) ) {
						return true;
					}
					return false;
				});
				$course_names = wp_list_pluck( array_values( $results ), 'course_name' );
			}
            $course_count = count($course_ids);
			if ( $course_count <= 0 ) {
				$response = new WP_Error(
					'no-data',
					__( 'No data available for the selected duration and/or filters', 'learndash-reports-by-wisdmlabs' ),
					array( 'requestData' => self::get_values_for_request_params( $request_data ),"updated_on" =>date_i18n( 'Y-m-d H:i:s', current_time( 'timestamp' )),"is_old_data_migrated" => get_option( 'migrated_course_time_access_data', false ) )
				);
				$current_timestamp = current_time( 'timestamp' );
				$wpdb->insert(
					$wpdb->prefix . 'wrld_cached_entries',
					array(
						'option_name'  => 'wrld_time_spent_on_a_course_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['duration'] . '_' . $request_data['learner'] . '_' . $request_data['timeperiod'] . '_' . $request_data['rtype'],
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

			$course_ids_str = implode(',', $course_ids);

			$query_total_time_by_user = "
				SELECT ct.total_time_spent
				FROM {$wpdb->prefix}ld_course_time_spent ct
				WHERE ct.user_id = $user_id
				AND ct.course_id IN ($course_ids_str) $where ORDER BY ct.course_id ASC;
			";

			$total_time_by_user_on_each_course = $wpdb->get_col($query_total_time_by_user);

			$queryAllUserAvg = "
				SELECT AVG(ct.total_time_spent) AS average_time_spent
				FROM {$wpdb->prefix}ld_course_time_spent ct
				WHERE ct.course_id IN ($course_ids_str) $where 
				GROUP BY ct.course_id ORDER BY ct.course_id ASC;
			";

			$all_averages = $wpdb->get_col($queryAllUserAvg);

			$LearnerAverageTimequery = "
				SELECT AVG(total_time_spent) AS average_time_spent
				FROM {$wpdb->prefix}ld_course_time_spent
				WHERE user_id = %d $where1;
			";
			$average = $wpdb->get_var($wpdb->prepare($LearnerAverageTimequery, $user_id));

				$response = array(
						'requestData' => self::get_values_for_request_params( $request_data ),
						"courseLabels" => $course_names,
						"total_time_by_user_on_each_course" => $total_time_by_user_on_each_course,
						"average_time_spent_by_all" => $all_averages,
						"average_time_by_learner" => $average,
						"total_courses" => count($course_ids),
						"is_old_data_migrated" => get_option( 'migrated_course_time_access_data', false ),
				);
				$current_timestamp = current_time( 'timestamp' );
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_time_spent_on_a_course_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['duration'] . '_' . $request_data['learner'] . '_' . $request_data['timeperiod'] . '_' . $request_data['rtype'],
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
            $user_role_access   = self::get_current_user_role_access();
			$accessible_courses = self::get_accessible_courses_for_the_user( get_current_user_id(), $user_role_access, 'course_completion_rate' );
			$accessible_users   = self::get_accessible_users_for_the_user( get_current_user_id(), $user_role_access, 'course_completion_rate' );
			$excluded_users     = get_option( 'exclude_users', array() );
			if ( empty( $excluded_users ) || ! defined( 'LDRP_PLUGIN_VERSION' ) ) {
				$excluded_users = array();
			}

			$course = $request_data['course'];
             
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


			$course_price_type = learndash_get_course_meta_setting( $course, 'course_price_type' );
			if ( 'open' === $course_price_type ) {
				$response = new WP_Error(
					'no-data',
					sprintf(/* translators: %s: custom label for courses */
						__( 'You cannot view reports for the open %s for the time-being', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'courses' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ), "updated_on" =>date_i18n( 'Y-m-d H:i:s', current_time( 'timestamp' )) , "is_old_data_migrated" => get_option( 'migrated_course_time_access_data', false ), )
				);
				$current_timestamp = current_time( 'timestamp' );
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_time_spent_on_a_course_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['duration'] . '_' . $request_data['learner'] . '_' . $request_data['timeperiod'] . '_' . $request_data['rtype'],
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
           
			

            $where = ($request_data['rtype'] == "false" || $request_data['rtype'] == false ) ?  " AND completion_time IS NOT NULL " : " AND completion_time IS NULL ";
            if(! is_null( $accessible_users ) && -1 != $accessible_users ){
                $user_ids_str = implode(',', $accessible_users);
				$where = $where. " AND user_id IN ($user_ids_str) ";
			}
			if($request_data['duration'] != 'all'){
				$afterDuration = $request_data['duration'];
				$enrollment_date = strtotime( gmdate( 'Y-m-d', strtotime( '-' . $afterDuration ) ) );
			  	$where = $where . "AND enrollment_date > ".$enrollment_date . " ";
			}
			
			$course_id = $request_data['course'];
			$duration = $request_data['timeperiod'];

			

			$checkCourseUserssql = $wpdb->prepare("
				SELECT COUNT(DISTINCT user_id) AS total_users
				FROM {$wpdb->prefix}ld_course_time_spent
				WHERE course_id = %d
				$where
			", $course_id);
           
			// Execute the query
			$total_users_in_course = $wpdb->get_var($checkCourseUserssql);

			$msg_no_data = ($request_data['rtype'] == "false" || $request_data['rtype'] == false ) ? __( 'There are no learners yet who are in progress or have completed the selected course” or select a different course.', 'learndash-reports-by-wisdmlabs' ) :  __( 'All the learners have completed the selected course. To view time spent data either toggle to “Courses completed” or select a different course.', 'learndash-reports-by-wisdmlabs' ) ;

			if ( $total_users_in_course <= 0 ) {
				$response =  new WP_Error(
					'no-data',
					$msg_no_data,
					array( 'requestData' => self::get_values_for_request_params( $request_data ),'acce'=>$accessible_users, "updated_on" =>date_i18n( 'Y-m-d H:i:s', current_time( 'timestamp' ) ), "is_old_data_migrated" => get_option( 'migrated_course_time_access_data', false ), )
				);
				$current_timestamp = current_time( 'timestamp' );
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_time_spent_on_a_course_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['duration'] . '_' . $request_data['learner'] . '_' . $request_data['timeperiod'] . '_' . $request_data['rtype'],
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

			

			$table_name =$wpdb->prefix . 'ld_course_time_spent'; 
			$users_count_data = array();
			$users_average_data = array();
			$users_label_data = array();
			if($duration == 1){

				$query0 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
						FROM $table_name
						WHERE total_time_spent = 0 AND course_id = $course_id $where";

				$query1 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
				FROM $table_name
				WHERE total_time_spent BETWEEN 1 AND 3600 AND course_id = $course_id $where";

				$query2 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
								FROM $table_name
								WHERE total_time_spent BETWEEN 3600 AND 7200 AND course_id = $course_id $where";

				$query3 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
								FROM $table_name
								WHERE total_time_spent BETWEEN 7200 AND 10800 AND course_id = $course_id $where";

				$query4 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
								FROM $table_name
								WHERE total_time_spent BETWEEN 10800 AND 14400 AND course_id = $course_id $where";


				$query5 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
								FROM $table_name
								WHERE total_time_spent > 14400 AND course_id = $course_id $where";

				$user_count_0 = $wpdb->get_row($query0);
				$user_count_0_60 = $wpdb->get_row($query1);
				$user_count_60_120 = $wpdb->get_row($query2);
				$user_count_120_180 = $wpdb->get_row($query3);
				$user_count_180_240 = $wpdb->get_row($query4);
				$user_count_more_than_240 = $wpdb->get_row($query5);

				array_push($users_average_data, $user_count_0->average_time_spent,$user_count_0_60->average_time_spent,$user_count_60_120->average_time_spent , $user_count_120_180->average_time_spent , $user_count_180_240->average_time_spent , $user_count_more_than_240->average_time_spent);
				array_push($users_count_data, $user_count_0->user_count,$user_count_0_60->user_count,$user_count_60_120->user_count , $user_count_120_180->user_count , $user_count_180_240->user_count , $user_count_more_than_240->user_count);
				
				array_push($users_label_data,"0","0-60" ,"61-120","121-180","181-240",">240");
			}

			if( $duration == 2){
				
				$query0 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
				FROM $table_name
				WHERE total_time_spent = 0 AND course_id = $course_id $where";

				$query1 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
				FROM $table_name
				WHERE total_time_spent BETWEEN 1 AND 14400 AND course_id = $course_id $where";

				$query2 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
								FROM $table_name
								WHERE total_time_spent BETWEEN 14401 AND 28800 AND course_id = $course_id $where";

				$query3 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
								FROM $table_name
								WHERE total_time_spent BETWEEN 28801 AND 43200 AND course_id = $course_id $where";

				$query4 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
								FROM $table_name
								WHERE total_time_spent BETWEEN 43201 AND 57600 AND course_id = $course_id $where";


				$query5 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
								FROM $table_name
								WHERE total_time_spent > 57600 AND course_id = $course_id $where";

				$user_count_0 = $wpdb->get_row($query0);
				$user_count_1_4 = $wpdb->get_row($query1);
				$user_count_5_8 = $wpdb->get_row($query2);
				$user_count_9_12 = $wpdb->get_row($query3);
				$user_count_13_16 = $wpdb->get_row($query4);
				$user_count_more_than_16 = $wpdb->get_row($query5);
				array_push($users_count_data, $user_count_0->user_count,$user_count_1_4->user_count,$user_count_5_8->user_count , $user_count_9_12->user_count , $user_count_13_16->user_count , $user_count_more_than_16->user_count);
				array_push($users_average_data, $user_count_0->average_time_spent,$user_count_1_4->average_time_spent,$user_count_5_8->average_time_spent , $user_count_9_12->average_time_spent , $user_count_13_16->average_time_spent , $user_count_more_than_16->average_time_spent);
				array_push($users_label_data,"0","0-4" ,"4-8","8-12","12-16",">16");
			}
			if( $duration == 3){
				
				$query0 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
				FROM $table_name
				WHERE total_time_spent = 0 AND course_id = $course_id $where";

				$query1 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
				FROM $table_name
				WHERE total_time_spent BETWEEN 1 AND 86400 AND course_id = $course_id $where";

				$query2 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
								FROM $table_name
								WHERE total_time_spent BETWEEN 86400 AND 172800 AND course_id = $course_id $where";

				$query3 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
								FROM $table_name
								WHERE total_time_spent BETWEEN 172801 AND 259200 AND course_id = $course_id $where";

				$query4 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
								FROM $table_name
								WHERE total_time_spent BETWEEN 259201 AND 345600 AND course_id = $course_id $where";


				$query5 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
								FROM $table_name
								WHERE total_time_spent > 345600 AND course_id = $course_id $where";

				$user_count_0 = $wpdb->get_row($query0);
				$user_count_0_24 = $wpdb->get_row($query1);
				$user_count_24_48 = $wpdb->get_row($query2);
				$user_count_48_72 = $wpdb->get_row($query3);
				$user_count_72_96 = $wpdb->get_row($query4);
				$user_count_more_than_96 = $wpdb->get_row($query5);
				array_push($users_count_data, $user_count_0->user_count,$user_count_0_24->user_count,$user_count_24_48->user_count , $user_count_48_72->user_count , $user_count_72_96->user_count , $user_count_more_than_96->user_count);

				array_push($users_average_data, $user_count_0->average_time_spent,$user_count_0_24->average_time_spent,$user_count_24_48->average_time_spent , $user_count_48_72->average_time_spent , $user_count_72_96->average_time_spent , $user_count_more_than_96->average_time_spent);

				array_push($users_label_data,"0","0-24" ,"24-48","48-72","72-96",">96");
			}

			
			$totalUsersql = "SELECT COUNT(DISTINCT user_id) AS total_users FROM {$wpdb->prefix}ld_course_time_spent WHERE course_id = $course_id";

			if(! is_null( $accessible_users ) && -1 != $accessible_users ){
                $user_ids_str = implode(',', $accessible_users);
				$totalUsersql = $totalUsersql. " AND user_id IN ($user_ids_str) ";
			}
			$query_average = "
						SELECT AVG(total_time_spent) AS average_time_spent
						FROM {$wpdb->prefix}ld_course_time_spent
						WHERE course_id = {$course_id} {$where}
						;
					";

			$average_result = $wpdb->get_row($query_average);
			$total_users = $wpdb->get_var($totalUsersql);
			$average_time_spent = $average_result->average_time_spent;
			$sql = $wpdb->prepare("
					SELECT COUNT(user_id) AS unique_users_count
					FROM $table_name
					WHERE course_id = %d
					{$where}
					AND total_time_spent <= %d
					", $course_id, $average_time_spent );

					// Execute the query and retrieve the count
					$users_below_average = $wpdb->get_var($sql);

			$sql = $wpdb->prepare("
					SELECT COUNT(user_id) AS unique_users_count
					FROM $table_name
					WHERE course_id = %s
					{$where}
					AND total_time_spent > %d
					", $course_id, $average_time_spent );

					// Execute the query and retrieve the count
					$users_above_average = $wpdb->get_var($sql);
					
			//fetch user filter data
			$current_user_id = get_current_user_id();
			$goup_enabled = get_user_meta( $current_user_id, 'wrld_ts_goup_enabled', true );
			$category_enabled = get_user_meta( $current_user_id, 'wrld_ts_category_enabled', true );

			// Average completion Course-wise.
			$response = array(
				'requestData' => self::get_values_for_request_params( $request_data ),
				"time_invervals" => $users_label_data ,
				"learner_counts_in_interval" => $users_count_data ,
				"learners_avg_data" => $users_average_data,
				"average_time_spent" => $average_time_spent,
				"learner_below_average" => $users_below_average,
				"learner_above_average" => $users_above_average,
				"total_learners" => $total_users,
				"is_group_enabled" => $goup_enabled,
				"is_category_enabled" => $category_enabled,
				"is_old_data_migrated" => get_option( 'migrated_course_time_access_data', false ),
			);

			$current_timestamp = current_time( 'timestamp' );
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_time_spent_on_a_course_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['duration'] . '_' . $request_data['learner'] . '_' . $request_data['timeperiod'] . '_' . $request_data['rtype'],
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

		} else{
			$response = new WP_Error(
				'no-data',
				sprintf(/* translators: %s: custom label for courses */
					__( 'No %s available', 'learndash-reports-by-wisdmlabs' ),
					\LearnDash_Custom_Label::get_label( 'courses' )
				),
				array( 'requestData' => self::get_values_for_request_params( $request_data ), "updated_on" =>date_i18n( 'Y-m-d H:i:s', current_time( 'timestamp' )) , "is_old_data_migrated" => get_option( 'migrated_course_time_access_data', false ), )
			);
			$current_timestamp = current_time( 'timestamp' );
				$wpdb->insert(
					$wpdb->prefix . 'wrld_cached_entries',
					array(
						'option_name'  => 'wrld_time_spent_on_a_course_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['duration'] . '_' . $request_data['learner'] . '_' . $request_data['timeperiod'] . '_' . $request_data['rtype'],
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
		$updated_on = date_i18n( 'Y-m-d H:i:s', $time_spent_row->created_on );
		$response = maybe_unserialize( $time_spent_row->option_value );
		if ( is_wp_error( $response ) ) {
		
			return $response;
		}
		$response['updated_on'] = $updated_on;
		unset($response["is_old_data_migrated"]);
		$response['is_old_data_migrated'] = get_option( 'migrated_course_time_access_data', false );
		return new WP_REST_Response(
			$response,
			200
		);
	}

	/**
	 * This method returns time spent on each lessons of a course.
	 *
	 * @author Seraj Alam
	 * @return WP_Rest_Response/WP_Error object.
	 */
	public function get_course_time_details(){

		$request_data = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING );
		$request_data = self::get_request_params( $request_data );
        $range = $request_data['datapoint'];
		$course_id = $request_data['course'];
		$timePeriod = $request_data['timeperiod'];
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
		
		
		$time_spent_lower_limit = (int) $request_data['lower_limit'] ?? 0;
		$time_spent_upper_limit = (int) $request_data['upper_limit'] ?? 0;

		
        $where = ($request_data['rtype'] == "false" || $request_data['rtype'] == false ) ?  " AND ct.completion_time IS NOT NULL " : " AND ct.completion_time IS NULL ";
		
		//logic for time period
		$timeCondition = "";
		if($time_spent_lower_limit === 0 && $time_spent_upper_limit === 0){
			$timeCondition = "total_time_spent = 0 ";
		}else{
			if($time_spent_lower_limit > 0 && $time_spent_upper_limit === 0){
			$timeCondition = "total_time_spent >= $time_spent_lower_limit ";
			}else{
					$timeCondition = "total_time_spent >= $time_spent_lower_limit AND total_time_spent < $time_spent_upper_limit ";
			}
		}

		if(! is_null( $accessible_users ) && -1 != $accessible_users ){
			$user_ids_str = implode(',', $accessible_users);
			$where = $where. " AND user_id IN ($user_ids_str) ";
		}
		
		$offset  = ((int) $request_data['page'] - 1 ) * 5;
		$more = 'no';

		global $wpdb;

		// Table names
		$table_users = $wpdb->prefix . 'users';
		$table_course_time_spent = $wpdb->prefix . 'ld_course_time_spent';
		$items_per_page = 5; // Number of items to display per page
        $current_page = (get_query_var('paged')) ? get_query_var('paged') : 1; // Get the current page
        
		// SQL query
		$sql = $wpdb->prepare("SELECT ct.total_time_spent , ct.enrollment_date , u.display_name
		FROM $table_course_time_spent as ct , $table_users as u WHERE $timeCondition AND  ct.user_id = u.id AND ct.course_id = $course_id");

        // Calculate pagination offset
        $sql .= " $where ORDER BY total_time_spent LIMIT $items_per_page OFFSET  $offset";
        // Execute the query
        $results = $wpdb->get_results($sql);

		//check for fore data
		$newoffset = $offset + $items_per_page ;
		$sql2 = "SELECT * FROM {$table_course_time_spent} ct WHERE $timeCondition AND course_id = $course_id $where LIMIT $items_per_page OFFSET  $newoffset";
		$query = $wpdb->prepare($sql2);

		$result2 = $wpdb->get_results($query);

        $more = count($result2) > 0 ? "yes" : "no";;
		return new WP_REST_Response(
			array(
				'tableData' => $results,
				'more_data' => $more,
				'datapoint' => $request_data['datapoint'] ,
				'page'      => $request_data['page'],
				'offset' => $offset,
				"lower" => $time_spent_lower_limit,
				"upper"=>$time_spent_upper_limit,
				"range" => $range,
				"numbers" => 0,
			),
			200
		);
	}


	/**
	 * This method returns time spent on each lessons of a course.
	 *
	 * @author Seraj Alam
	 * @return WP_Rest_Response/WP_Error object.
	 */
	public function get_course_time_spent_csv(){
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
		global $wpdb;
		if ( isset( $request_data['learner'] ) && ! empty( $request_data['learner'] ) ) {
			$learner_courses    = learndash_user_get_enrolled_courses( $request_data['learner'], array(), false );

			//getting lerner data
			$user_id = $request_data['learner'];
			$query = "
			SELECT p.ID AS course_id, p.post_title AS course_name
			FROM {$wpdb->prefix}ld_course_time_spent ct
			JOIN {$wpdb->prefix}posts p ON ct.course_id = p.ID
			WHERE p.post_type = 'sfwd-courses'
			AND ct.user_id = $user_id
			GROUP BY ct.course_id;
			";

			$results = $wpdb->get_results($query, ARRAY_A);
			$course_ids = [];
			$course_names = [];
			if ($results) {
				$course_ids = wp_list_pluck($results, 'course_id');
				$course_names = wp_list_pluck($results, 'course_name');
			}
            $course_count = count($course_ids);
			if ( $course_count <= 0 ) {
				return new WP_Error(
					'no-data',
					__( 'No data available for the selected duration and/or filters', 'learndash-reports-by-wisdmlabs' ),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
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
				$accessible_users   = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $group_users, $accessible_users ) : $group_users;
			}

			$course_ids_str = implode(',', $course_ids);

			$query_total_time_by_user = "
				SELECT ct.total_time_spent
				FROM {$wpdb->prefix}ld_course_time_spent ct
				WHERE ct.user_id = $user_id
				AND ct.course_id IN ($course_ids_str);
			";

		$total_time_by_user_on_each_course = $wpdb->get_col($query_total_time_by_user);


		$queryAllUserAvg = "
			SELECT AVG(ct.total_time_spent) AS average_time_spent
			FROM {$wpdb->prefix}ld_course_time_spent ct
			WHERE ct.course_id IN ($course_ids_str)
			GROUP BY ct.course_id;
		";

		$all_averages = $wpdb->get_col($queryAllUserAvg);
			

			
		$upload_dir = wp_upload_dir();
		if ( ! file_exists( $upload_dir['basedir'] . '/learndash_reports' ) ) {
			mkdir( $upload_dir['basedir'] . '/learndash_reports' );
		}
		$file      = fopen( $upload_dir['basedir'] . '/learndash_reports' . '/' . 'learner_time_spent.csv', 'w' );
		
		if ( $file ) {
			// For each row of the table in Data recieved.
			$th = array(
				__( 'Learner: ', 'learndash-reports-by-wisdmlabs' ) . get_userdata($request_data['learner'])->display_name,
				__( 'Duration: ', 'learndash-reports-by-wisdmlabs' ). $request_data['duration']
			);
			// Inserts Heading into the csv file.
			if ( ! empty( $th ) ) {
				fputcsv( $file, $th );
			}
			fputcsv($file, array());
			fputcsv($file, array(__( 'Courses with Progress in below ranges:', 'learndash-reports-by-wisdmlabs' )));
			$th2 = array(
				__( 'Course', 'learndash-reports-by-wisdmlabs' ),
				__( 'Time spent by learner(in seconds)', 'learndash-reports-by-wisdmlabs' ),
				__( 'Avg. Time spent by all learners(in seconds)', 'learndash-reports-by-wisdmlabs' ),
			);
			// Inserts Heading into the csv file.
			if ( ! empty( $th2 ) ) {
				fputcsv( $file, $th2 );
			}
			foreach ($course_names as $index => $value) {
				fputcsv($file, array($value, $total_time_by_user_on_each_course[$index], $all_averages[$index] ));
			}
			
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
				'filename' => $upload_dir['baseurl'] . '/learndash_reports' . '/' . 'learner_time_spent.csv'
			),
			200
		);

		} elseif ( isset( $request_data['course'] ) && ! empty( $request_data['course'] ) ) {
			
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

            
            $where = ($request_data['rtype'] == "false" || $request_data['rtype'] == false ) ?  " AND completion_time IS NOT NULL " : " AND completion_time IS NULL ";


			
			
			$course_id = $request_data['course'];
			$duration = $request_data['timeperiod'];

			$checkCourseUserssql = $wpdb->prepare("
				SELECT COUNT(DISTINCT user_id) AS total_users
				FROM {$wpdb->prefix}ld_course_time_spent
				WHERE course_id = %d
				$where
			", $course_id);

			// Execute the query
			$total_users_in_course = $wpdb->get_var($checkCourseUserssql);

			if ( $total_users_in_course <= 0 ) {
				return new WP_Error(
					'no-data',
					__( 'No data available for the selected duration and/or filters.', 'learndash-reports-by-wisdmlabs' ),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
			}

			

			$table_name =$wpdb->prefix . 'ld_course_time_spent'; 
			$users_count_data = array();
			$users_average_data = array();
			$users_label_data = array();
			if($duration == 1){

				$query0 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
						FROM $table_name
						WHERE total_time_spent = 0 AND course_id = $course_id $where";

				$query1 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
				FROM $table_name
				WHERE total_time_spent BETWEEN 0 AND 3600 AND course_id = $course_id $where";

				$query2 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
								FROM $table_name
								WHERE total_time_spent BETWEEN 3600 AND 7200 AND course_id = $course_id $where";

				$query3 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
								FROM $table_name
								WHERE total_time_spent BETWEEN 7200 AND 10800 AND course_id = $course_id $where";

				$query4 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
								FROM $table_name
								WHERE total_time_spent BETWEEN 10800 AND 14400 AND course_id = $course_id $where";


				$query5 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
								FROM $table_name
								WHERE total_time_spent > 14400 AND course_id = $course_id $where";

				$user_count_0 = $wpdb->get_row($query0);
				$user_count_0_60 = $wpdb->get_row($query1);
				$user_count_60_120 = $wpdb->get_row($query2);
				$user_count_120_180 = $wpdb->get_row($query3);
				$user_count_180_240 = $wpdb->get_row($query4);
				$user_count_more_than_240 = $wpdb->get_row($query5);

				array_push($users_average_data, $user_count_0->average_time_spent,$user_count_0_60->average_time_spent,$user_count_60_120->average_time_spent , $user_count_120_180->average_time_spent , $user_count_180_240->average_time_spent , $user_count_more_than_240->average_time_spent);
				array_push($users_count_data, $user_count_0->user_count,$user_count_0_60->user_count,$user_count_60_120->user_count , $user_count_120_180->user_count , $user_count_180_240->user_count , $user_count_more_than_240->user_count);
				
				array_push($users_label_data,"0","0-60 minutes" ,"61-120 minutes","121-180 minutes","181-240 minutes",">240 minutes");
			}

			if( $duration == 2){
				
				$query0 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
				FROM $table_name
				WHERE total_time_spent = 0 AND course_id = $course_id $where";

				$query1 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
				FROM $table_name
				WHERE total_time_spent BETWEEN 1 AND 14400 AND course_id = $course_id $where";

				$query2 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
								FROM $table_name
								WHERE total_time_spent BETWEEN 14401 AND 28800 AND course_id = $course_id $where";

				$query3 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
								FROM $table_name
								WHERE total_time_spent BETWEEN 28801 AND 43200 AND course_id = $course_id $where";

				$query4 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
								FROM $table_name
								WHERE total_time_spent BETWEEN 43201 AND 57600 AND course_id = $course_id $where";


				$query5 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
								FROM $table_name
								WHERE total_time_spent > 57600 AND course_id = $course_id $where";

				$user_count_0 = $wpdb->get_row($query0);
				$user_count_1_4 = $wpdb->get_row($query1);
				$user_count_5_8 = $wpdb->get_row($query2);
				$user_count_9_12 = $wpdb->get_row($query3);
				$user_count_13_16 = $wpdb->get_row($query4);
				$user_count_more_than_16 = $wpdb->get_row($query5);
				array_push($users_count_data, $user_count_0->user_count,$user_count_1_4->user_count,$user_count_5_8->user_count , $user_count_9_12->user_count , $user_count_13_16->user_count , $user_count_more_than_16->user_count);
				array_push($users_average_data, $user_count_0->average_time_spent,$user_count_1_4->average_time_spent,$user_count_5_8->average_time_spent , $user_count_9_12->average_time_spent , $user_count_13_16->average_time_spent , $user_count_more_than_16->average_time_spent);
				array_push($users_label_data,"0","0-4 hours" ,"4-8 hours","8-12 hours","12-16 hours",">16 hours");
			}
			if( $duration == 3){
				
				$query0 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
				FROM $table_name
				WHERE total_time_spent = 0 AND course_id = $course_id $where";

				$query1 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
				FROM $table_name
				WHERE total_time_spent BETWEEN 1 AND 86400 AND course_id = $course_id $where";

				$query2 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
								FROM $table_name
								WHERE total_time_spent BETWEEN 86400 AND 172800 AND course_id = $course_id $where";

				$query3 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
								FROM $table_name
								WHERE total_time_spent BETWEEN 172801 AND 259200 AND course_id = $course_id $where";

				$query4 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
								FROM $table_name
								WHERE total_time_spent BETWEEN 259201 AND 345600 AND course_id = $course_id $where";


				$query5 = "SELECT COUNT(DISTINCT user_id) AS user_count , AVG(total_time_spent) AS average_time_spent
								FROM $table_name
								WHERE total_time_spent > 345600 AND course_id = $course_id $where";

				$user_count_0 = $wpdb->get_row($query0);
				$user_count_0_24 = $wpdb->get_row($query1);
				$user_count_24_48 = $wpdb->get_row($query2);
				$user_count_48_72 = $wpdb->get_row($query3);
				$user_count_72_96 = $wpdb->get_row($query4);
				$user_count_more_than_96 = $wpdb->get_row($query5);
				array_push($users_count_data, $user_count_0->user_count,$user_count_0_24->user_count,$user_count_24_48->user_count , $user_count_48_72->user_count , $user_count_72_96->user_count , $user_count_more_than_96->user_count);

				array_push($users_average_data, $user_count_0->average_time_spent,$user_count_0_24->average_time_spent,$user_count_24_48->average_time_spent , $user_count_48_72->average_time_spent , $user_count_72_96->average_time_spent , $user_count_more_than_96->average_time_spent);

				array_push($users_label_data,"0","0-24 hours" ,"24-48 hours","48-72 hours","72-96 hours",">96 hours");
			}
			

		

		

		
	


				//csv logic
					$upload_dir = wp_upload_dir();
				if ( ! file_exists( $upload_dir['basedir'] . '/learndash_reports' ) ) {
					mkdir( $upload_dir['basedir'] . '/learndash_reports' );
				}
				$file      = fopen( $upload_dir['basedir'] . '/learndash_reports' . '/' . 'course_time_spent.csv', 'w' );
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
					$headerData = ($request_data['rtype'] == "false" || $request_data['rtype'] == false ) ?  array('Learners completed in below ranges:') : array(__( 'Learners Progress in below ranges:', 'learndash-reports-by-wisdmlabs' ) );
					fputcsv($file, $headerData);
					
					// Inserts Heading into the csv file.
					if ( ! empty( $users_label_data ) ) {
						fputcsv( $file, $users_label_data );
					}
					fputcsv($file, $users_count_data);
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
						'filename' => $upload_dir['baseurl'] . '/learndash_reports' . '/' . 'course_time_spent.csv'
					),
					200
				);
			}
			
	}


	public function set_course_time_spent_filter(){
		// Get Inputs.
		$request_data = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING );
		$request_data = self::get_request_params( $request_data );
		// Get the current user's ID
		$current_user_id = get_current_user_id();
		do_action( 'wpml_switch_language',  $_GET[ 'wpml_lang' ] ); // switch the content language
		// Check if a user is logged in
		if ( $current_user_id ) {
			// Update the usermeta field "goup_enabled" to true (1)
			update_user_meta( $current_user_id, 'wrld_ts_goup_enabled', $request_data['group'] ?? false );
			update_user_meta( $current_user_id, 'wrld_ts_category_enabled', $request_data['category'] ?? false );

			$goup_enabled = get_user_meta( $current_user_id, 'wrld_ts_goup_enabled', true );
			$category_enabled = get_user_meta( $current_user_id, 'wrld_ts_category_enabled', true );

			return new WP_REST_Response(
				array(
					'success' => true,
					'is_group_enabled' => $goup_enabled,
					'is_category_enabled' => $category_enabled
				),
				200
			);
		}
		return new WP_REST_Response(
			array(
				'success' => false,
				'message' => "Unauthorized user"
			),
			200
		);
	}
	
	/**
	 * This method returns time spent on each lessons of a course.
	 *
	 * @author Seraj Alam
	 * @return WP_Rest_Response/WP_Error object.
	 */
	public function get_lesson_topic_time_spent() {
		// Get Inputs.
		$request_data = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING );
		$request_data = self::get_request_params( $request_data );
		unset( $request_data['start_date'] );
		unset( $request_data['end_date'] );
		do_action( 'wpml_switch_language',  $_GET[ 'wpml_lang' ] ); // switch the content language
		$user_role_access   = self::get_current_user_role_access();
		$accessible_courses = self::get_accessible_courses_for_the_user( get_current_user_id(), $user_role_access, 'course_time_spent' );
		$accessible_users   = self::get_accessible_users_for_the_user( get_current_user_id(), $user_role_access, 'course_time_spent' );
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

		if ( ( ! is_null( $accessible_users ) && -1 != $accessible_users ) && empty( $accessible_users ) ) {
			return new WP_Error(
				'no-users-accessible',
				__( 'No data found for accessible learners', 'learndash-reports-by-wisdmlabs' ),
				array( 'requestData' => self::get_values_for_request_params( $request_data ) )
			);
		}

		if ( isset( $request_data['learner'] ) && ! empty( $request_data['learner'] ) ) {
			$learner_courses    = learndash_user_get_enrolled_courses( $request_data['learner'], array(), false );
			$accessible_courses = ( ! is_null( $accessible_courses ) && -1 != $accessible_courses ) ? array_intersect( $accessible_courses, $learner_courses ) : $learner_courses;
			$accessible_users   = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, array( $request_data['learner'] ) ) : array( $request_data['learner'] );
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
			$courses_selected   = array( $request_data['course'] );
			$accessible_courses = ( ! is_null( $accessible_courses ) && -1 != $accessible_courses ) ? array_intersect( $courses_selected, $accessible_courses ) : $courses_selected;
			$course_users = get_transient( 'wrld_course_students_data_' . $request_data['course'] );
			if ( false === $course_users || ( defined('WRLD_DISABLE_TRANSIENTS') && WRLD_DISABLE_TRANSIENTS ) || $request_data['disable_cache'] ) {
				if ( get_option( 'migrated_course_access_data', false ) ) {
					$course_users = \WRLD_Quiz_Export_Db::instance()->get_users_for_course( $request_data['course'] );
				} else {
					$course_users = learndash_get_users_for_course( $request_data['course'], array(), false );
				}
				$course_users       = is_array( $course_users ) ? $course_users : $course_users->get_results();
				delete_transient( 'wrld_course_students_data_' . $request_data['course'] );
				set_transient( 'wrld_course_students_data_' . $request_data['course'], $course_users, 1 * HOUR_IN_SECONDS );
			}
			$accessible_users   = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $course_users, $accessible_users ) : $course_users;
			$accessible_users = array_diff( $accessible_users , $excluded_users );

			$class_size         = count( $accessible_users );
			$time_spent         = array();
			$total_time_spent   = 0;
			$average_time_spent = 0;

			$current_post_id = isset( $request_data['topic'] ) && ! empty( $request_data['topic'] ) ? $request_data['topic'] : $request_data['lesson'];

			foreach ( $accessible_users as $student_id ) {
				$user_time_spent = $this->fetch_user_module_time_spent( $current_post_id, $request_data['course'], $student_id );
				if ( $user_time_spent < 0 || empty( $user_time_spent ) || null == $user_time_spent ) {
					$user_time_spent = 0;
				}
				$total_time_spent          = $total_time_spent + $user_time_spent;
				$time_spent[ $student_id ] = array(
					'time'     => $user_time_spent,
					'username' => get_userdata( $student_id )->display_name,
				);
			}

			if ( 0 != $total_time_spent && $class_size > 0 ) {
				$average_time_spent = floatval( number_format( $total_time_spent / $class_size, 2, '.', '' ) );// Cast to integer if no decimals.
			}

			// Calculate average across students.
			return new WP_REST_Response(
				array(
					'requestData'        => self::get_values_for_request_params( $request_data ),
					'time_spent'         => $time_spent,
					'average_time_spent' => $average_time_spent,
					'total_time'         => 0 == $total_time_spent ? '-' : $total_time_spent,
					'total_learners'     => $class_size,
				),
				200
			);
		}
	}

	/**
	 * This method returns Quiz completion time based on input parameters.
	 *
	 * @return WP_Rest_Response/WP_Error returned.
	 */
	public function get_quiz_completion_time() {
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
				'wrld_quiz_completion_time_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic']
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
			$user_role_access   = self::get_current_user_role_access();
			do_action( 'wpml_switch_language',  $_GET[ 'wpml_lang' ] ); // switch the content language
			$accessible_courses = self::get_accessible_courses_for_the_user( get_current_user_id(), $user_role_access, 'quiz_completion_rate' );
			$accessible_users   = self::get_accessible_users_for_the_user( get_current_user_id(), $user_role_access, 'quiz_completion_rate' );
			$excluded_users     = get_option( 'exclude_users', array() );
			if ( empty( $excluded_users ) || ! defined( 'LDRP_PLUGIN_VERSION' ) ) {
				$excluded_users = array();
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

			if ( isset( $request_data['learner'] ) && ! empty( $request_data['learner'] ) ) {
				$learner_courses    = learndash_user_get_enrolled_courses( $request_data['learner'], array(), false );
				$accessible_courses = ( ! is_null( $accessible_courses ) && -1 != $accessible_courses ) ? array_intersect( $accessible_courses, $learner_courses ) : $learner_courses;
				$accessible_users   = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, array( $request_data['learner'] ) ) : array( $request_data['learner'] );
				if ( ( ! is_null( $accessible_courses ) && -1 != $accessible_courses ) && empty( $accessible_courses ) ) {
					$response = new WP_Error(
						'no-data',
						sprintf(
							/* translators: %s: custom label for courses */
							__( 'No accessible %s found', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::get_label( 'courses' )
						),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					);
					$current_timestamp = current_time( 'timestamp' );
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_quiz_completion_time_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
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
			} elseif ( isset( $request_data['topic'] ) && ! empty( $request_data['topic'] ) ) {
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
								'option_name'  => 'wrld_quiz_completion_time_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
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
				return $this->get_modulewise_quiztime( $request_data['course'], $request_data['topic'], 'array', $accessible_users, $request_data );
			} elseif ( isset( $request_data['lesson'] ) && ! empty( $request_data['lesson'] ) ) {
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
								'option_name'  => 'wrld_quiz_completion_time_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
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
				return $this->get_modulewise_quiztime( $request_data['course'], $request_data['lesson'], 'array', $accessible_users, $request_data );
			} elseif ( isset( $request_data['course'] ) && ! empty( $request_data['course'] ) ) {
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
								'option_name'  => 'wrld_quiz_completion_time_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
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
				return $this->get_modulewise_quiztime( $request_data['course'], $request_data['course'], 'array', $accessible_users, $request_data );
			} elseif ( isset( $request_data['category'] ) && ! empty( $request_data['category'] ) ) {
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
							'option_name'  => 'wrld_quiz_completion_time_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
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
							'option_name'  => 'wrld_quiz_completion_time_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
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

			$courses = get_posts( $query_args );
			// Check if any courses present in the category.
			if ( empty( $courses ) || ( ! is_null( $accessible_courses ) && -1 != $accessible_courses && empty( $accessible_courses ) ) ) {
				$response = new WP_Error(
					'no-data',
					sprintf(/* translators: %s: custom label for courses */
						__( 'No %s data found', 'learndash-reports-by-wisdmlabs' ),
						\LearnDash_Custom_Label::get_label( 'quiz' )
					),
					array( 'requestData' => self::get_values_for_request_params( $request_data ) )
				);
				$current_timestamp = current_time( 'timestamp' );
				$wpdb->insert(
					$wpdb->prefix . 'wrld_cached_entries',
					array(
						'option_name'  => 'wrld_quiz_completion_time_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
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
			$total_time_spent = 0;
			$course_wise_time = array();
			$course_count     = count( $courses );

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
				$students   = ( ! is_null( $accessible_users ) && -1 != $accessible_users ) ? array_intersect( $accessible_users, $students ) : $students;
				$students = array_diff( $students , $excluded_users );

				$class_size = is_array( $students ) ? count( $students ) : $students->get_total();

				$course_time = 0;

				// If no students in the course then the course has 0 time spent.
				if ( empty( $students ) ) {
					// $course_wise_time[ $course->ID ] = array(
					// 'time'   => $course_time,
					// 'course' => $course->post_title,
					// );
					continue;
				}

				$course_quiz_time = $this->get_modulewise_quiztime( $course->ID, $course->ID, 'array', $accessible_users, $request_data );

				if ( ! is_wp_error( $course_quiz_time ) ) {
					$course_wise_time[ $course->ID ] = array(
						'time'   => $course_quiz_time['quizTotalTime'],
						'course' => $course->post_title,
					);
					$total_time_spent                = $total_time_spent + $course_quiz_time['quizTotalTime'];
				} else {
					--$course_count;
				}
			}
			if ( $course_count <= 0 ) {
				if ( $course_count <= 0 ) {
					$response = new WP_Error(
						'no-data',
						sprintf(/* translators: %s: custom label for courses */
							__( 'No %s data found', 'learndash-reports-by-wisdmlabs' ),
							\LearnDash_Custom_Label::get_label( 'quiz' )
						),
						array( 'requestData' => self::get_values_for_request_params( $request_data ) )
					);
					$current_timestamp = current_time( 'timestamp' );
					$wpdb->insert(
						$wpdb->prefix . 'wrld_cached_entries',
						array(
							'option_name'  => 'wrld_quiz_completion_time_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
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
			} else {
				$overall_average_time = floatval( number_format( $total_time_spent / $course_count, 2, '.', '' ) );// Cast to integer if no decimals.
			}
			$response = array(
				'requestData'       => self::get_values_for_request_params( $request_data ),
				'courseTotalTime'   => $total_time_spent,
				'averageCourseTime' => $overall_average_time,
				'courseWiseTime'    => $course_wise_time,
			);
			$current_timestamp = current_time( 'timestamp' );
			$wpdb->insert(
				$wpdb->prefix . 'wrld_cached_entries',
				array(
					'option_name'  => 'wrld_quiz_completion_time_' . $request_data['group'] . '_' . $request_data['category'] . '_' . $request_data['course'] . '_' . $request_data['learner'] . '_' . $request_data['lesson'] . '_' . $request_data['topic'],
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

	/**
	 * This method returns Quiz time spent for any modules i.e., Course/Lesson/Topic.
	 *
	 * @param  int        $course Course ID.
	 * @param  int        $module Lesson/Topic ID.
	 * @param  string     $return return type.
	 * @param  null|array $student_filter Array of student IDs for which the quiz data is required.
	 *
	 * @return array/WP_Rest_Response object.
	 */
	public function get_modulewise_quiztime( $course, $module, $return = 'object', $student_filter = null, $request_data = array() ) {
		$course_price_type = learndash_get_course_meta_setting( $course, 'course_price_type' );
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
		
		if ( empty( $students ) ) {
			return new WP_Error(
				'no-data',
				__( 'No Students Enrolled', 'learndash-reports-by-wisdmlabs' ),
				array( 'requestData' => self::get_values_for_request_params( $request_data ) )
			);
		}
		$user = wp_get_current_user();

		$excluded_users     = get_option( 'exclude_users', array() );
		if ( empty( $excluded_users ) || ! defined( 'LDRP_PLUGIN_VERSION' ) ) {
			$excluded_users = array();
		}
		$students      = ( ! is_null( $student_filter ) && -1 != $student_filter ) ? array_intersect( $student_filter, $students ) : $students;
		$students = array_diff( $students , $excluded_users );

		$quizzes       = learndash_course_get_children_of_step( $course, $module, 'sfwd-quiz', 'ids', true );
		$student_count = is_array( $students ) ? count( $students ) : $students->get_total();

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

		$total_quizzes_time = 0;
		$learner_wise_time  = array();

		foreach ( $students as $student_id ) {
			$student_time = 0;
			foreach ( $quizzes as $quiz ) {
				$quiz_t = $this->learndash_get_user_quiz_attempts_time_spent( $student_id, $quiz );
				if ( $quiz_t < 0 ) {
					$quiz_t = 0;
				}
				$student_time = $student_time + $quiz_t;
			}
			if ( 0 >= $student_time ) {
				$student_count--;
				continue;
			}
			$total_quizzes_time               = $total_quizzes_time + $student_time;
			$learner_wise_time[ $student_id ] = array(
				'name' => get_userdata( $student_id )->display_name,
				'time' => $student_time,
			);
		}
		if ( $student_count <= 0 ) {
			return new WP_Error(
				'no-data',
				/* translators: %s : Quiz Attempt  */
				sprintf( __( 'No Students have made %s attempts', 'learndash-reports-by-wisdmlabs' ), \LearnDash_Custom_Label::label_to_lower( 'quiz' ) ),
				array( 'requestData' => self::get_values_for_request_params( $request_data ) )
			);
		}
		$overall_average_time = floatval( number_format( $total_quizzes_time / $student_count, 2, '.', '' ) );// Cast to integer if no decimals.
		if ( 'array' === $return ) {
			return array(
				'requestData'     => self::get_values_for_request_params( $request_data ),
				'averageQuizTime' => $overall_average_time,
				'learnerWiseTime' => $learner_wise_time,
				'studentCount'    => $student_count,
				'quizTotalTime'   => $total_quizzes_time,
			);
		}
		return new WP_REST_Response(
			array(
				'requestData'     => self::get_values_for_request_params( $request_data ),
				'averageQuizTime' => $overall_average_time,
				'learnerWiseTime' => $learner_wise_time,
				'studentCount'    => $student_count,
				'quizTotalTime'   => $total_quizzes_time,
			),
			200
		);
	}

	/**
	 * Converts the seconds to time output.
	 *
	 * @since 2.1.0
	 *
	 * @param int $input_seconds The seconds value.
	 *
	 * @return string The time output string.
	 */
	public function ldrp_seconds_to_time( $input_seconds = 0 ) {

		$seconds_minute = 60;
		$seconds_hour   = 60 * $seconds_minute;
		$seconds_day    = 24 * $seconds_hour;

		$return = '';

		// extract days.
		$days = floor( $input_seconds / $seconds_day );
		if ( ! empty( $days ) ) {
			if ( ! empty( $return ) ) {
				$return .= ' ';
			}
			// translators: placeholder: Number of Days count.
			$return .= sprintf( _n( '%s day', '%s days', $days, 'learndash-reports-by-wisdmlabs' ), number_format_i18n( $days ) );
		}

		// extract hours.
		$hour_seconds = $input_seconds % $seconds_day;
		$hours        = floor( $hour_seconds / $seconds_hour );
		if ( ! empty( $hours ) ) {
			if ( ! empty( $return ) ) {
				$return .= ' ';
			}
			// translators: placeholder: Number of Hours count.
			$return .= sprintf( _n( '%s hr', '%s hrs', $hours, 'learndash-reports-by-wisdmlabs' ), number_format_i18n( $hours ) );
		}

		// extract minutes.
		$minute_seconds = $input_seconds % $seconds_hour;
		$minutes        = floor( $minute_seconds / $seconds_minute );
		if ( ! empty( $minutes ) ) {
			if ( ! empty( $return ) ) {
				$return .= ' ';
			}
			// translators: placeholder: Number of Minutes count.
			$return .= sprintf( _n( '%s min', '%s min', $minutes, 'learndash' ), number_format_i18n( $minutes ) );

		}

		// extract the remaining seconds.
		$remaining_seconds = $input_seconds % $seconds_minute;
		$seconds           = ceil( $remaining_seconds );
		if ( ! empty( $seconds ) ) {
			if ( ! empty( $return ) ) {
				$return .= ' ';
			}
			// translators: placeholder: Number of Seconds count.
			$return .= sprintf( _n( '%s sec', '%s sec', $seconds, 'learndash' ), number_format_i18n( $seconds ) );
		}

		return trim( $return );
	}
}