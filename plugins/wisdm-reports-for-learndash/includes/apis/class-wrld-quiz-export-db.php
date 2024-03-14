<?php
/**
 * Report Export data generation.
 *
 * @package learndash-reports-by-wisdmlabs
 */

defined( 'ABSPATH' ) || exit;
if ( ! class_exists( 'WRLD_Quiz_Export_Db' ) ) {
	/**
	 * WRLD_Quiz_Export_Db Class.
	 *
	 * @class WRLD_Quiz_Export_Db
	 */
	class WRLD_Quiz_Export_Db {
		/**
		 * The single instance of the class.
		 *
		 * @var WRLD_Quiz_Export_Db
		 * @since 2.1
		 */
		protected static $instance = null;

		/**
		 * The single instance of the class.
		 *
		 * @var WRLD_Quiz_Export_Db
		 * @since 2.1
		 */
		protected $wpdb = null;

		/**
		 * WRLD_Quiz_Export_Db Instance.
		 *
		 * Ensures only one instance of WRLD_Quiz_Export_Db is loaded or can be loaded.
		 *
		 * @since 3.0.0
		 * @static
		 * @return WRLD_Quiz_Export_Db - instance.
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * WRLD_Quiz_Export_Db Constructor.
		 */
		public function __construct() {
			$this->set_db_instance();
			$this->initialize_data_migration();
			$this->initialize_data_gathering_hooks();
		}

		/**
		 * Setter method for setting database instance.
		 */
		public function set_db_instance() {
			global $wpdb;
			$this->wpdb = $wpdb;
		}

		/**
		 * Getter method for setting database instance.
		 *
		 * @return $wpdb WPDB Instance
		 */
		public function get_db_instance() {
			return $this->wpdb;
		}

		public function initialize_data_migration() {
			// $this->migrate_course_access();
			// $this->migrate_group_access();
		}

		public function initialize_data_gathering_hooks() {
			add_action( 'ld_added_group_access', array( $this, 'add_group_users' ), 10, 2 );
			add_action( 'ld_removed_group_access', array( $this, 'remove_group_users' ), 10, 2 );
			add_action( 'update_user_metadata', array( $this, 'add_course_users' ), 10, 4 );
			add_action( 'delete_user_metadata', array( $this, 'remove_course_users' ), 10, 4 );
			add_action( 'wp_ajax_upgrade_course_enrollments', array( $this, 'migrate_course_access_data' ) );
			add_action( 'wp_ajax_upgrade_group_enrollments', array( $this, 'migrate_group_access_data' ) );
			add_action( 'wp_ajax_upgrade_course_time_spent', array( $this, 'migrate_course_time_data' ) );
			add_action( 'delete_user', array( $this, 'delete_user_data_handling' ), 10, 1 );
		}
 
		public function delete_user_data_handling( $deleted_user_id ) {
			global $wpdb;
			// Remove from course enrollments
			$course_enrollments = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = 'wrld_course_users' AND meta_value LIKE '%%d%'", $deleted_user_id ), ARRAY_A );
			foreach ( $course_enrollments as $enrollment ) {
				$users = maybe_unserialize( $enrollment['meta_value'] );
				update_post_meta( $enrollment['post_id'], 'wrld_course_users', serialize( array_diff( $users, array( $deleted_user_id ) ) ) );
			}
			// Remove from group enrollments
			$group_enrollments = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = 'wrld_group_users' AND meta_value LIKE '%%d%'", $deleted_user_id ), ARRAY_A );
			foreach ( $group_enrollments as $enrollment ) {
				$users = maybe_unserialize( $enrollment['meta_value'] );
				update_post_meta( $enrollment['post_id'], 'wrld_group_users', serialize( array_diff( $users, array( $deleted_user_id ) ) ) );
			}
			// Remove from cache table ( Note: Will not be removing in cache data for responses for the timebeing. User can just click refresh icon ).
			$wpdb->delete(
				$wpdb->prefix . 'wrld_cached_entries',
				array(
					'object_id' => $deleted_user_id
				),
				array(
					'%d'
				)
			);
			// Remove from transients
			/*
			1) wrld_course_learners_data_
			2) wrld_accessible_users_data_
			3) wrld_group_students_data_
			4) wrld_course_students_data_
			5) wrld_course_groups_students_data_
			*/
			$query = $wpdb->prepare("DELETE FROM {$wpdb->prefix}options WHERE 
				option_name LIKE '%wrld_course_learners_data_%' OR
				option_name LIKE '%wrld_accessible_users_data_%' OR 
				option_name LIKE '%wrld_group_students_data_%' OR 
				option_name LIKE '%wrld_course_students_data_%' OR 
				option_name LIKE '%wrld_course_groups_students_data_%' OR 
			");

			$wpdb->query($query);
			// Remove from custom tables
			$wpdb->delete(
				$wpdb->prefix . 'ld_course_time_spent',
				array(
					'user_id' => $deleted_user_id
				),
				array(
					'%d'
				)
			);
			$wpdb->delete(
				$wpdb->prefix . 'ld_time_entries',
				array(
					'user_id' => $deleted_user_id
				),
				array(
					'%d'
				)
			);
		}

		public function migrate_course_access_data() {
			if ( get_option( 'migrated_course_access_data', false ) ) {
				delete_option( 'course_migrate_page' );
				delete_option( 'course_migrate_total_pages' );
				delete_option( 'migrated_course_access_data' );
				// wp_send_json_success(array(
				// 	'percentage' => 100,
				// 	'next'       => false,
				// ), 200);
				// die();
			}
			$db_instance = $this->get_db_instance();
			$total_pages = get_option( 'course_migrate_total_pages', false );
			if ( ! $total_pages ) {
				$total = $db_instance->get_var( "SELECT COUNT( * ) from {$db_instance->usermeta} WHERE meta_key LIKE 'course_%_access_from'" );
				$total_pages = ceil( (int) $total / 10 );
				update_option( 'course_migrate_total_pages', $total_pages );
			}
			$current_page = get_option( 'course_migrate_page', 1 );
			$result = $db_instance->get_results( "SELECT user_id, meta_key from {$db_instance->usermeta} WHERE meta_key LIKE 'course_%_access_from' LIMIT 10 OFFSET {$current_page}", ARRAY_A );
			update_option( 'course_migrate_page', $current_page + 1 );
			if ( empty( $result ) ) {
				delete_option( 'course_migrate_page' );
				delete_option( 'course_migrate_total_pages' );
				update_option( 'migrated_course_access_data', time() );
				wp_send_json_success(array(
					'percentage' => 100,
					'next'       => false,
				), 200);
				die();
			}
			$course_users = array();
			foreach ( $result as $entry ) {
				// preg_match_all( '!\d+!', $entry['meta_key'], $course_id );
				$course_id = filter_var( $entry['meta_key'], FILTER_SANITIZE_NUMBER_INT );
				$course_users[ $course_id ][] = (int) $entry['user_id'];
			}
			$course_users = array_map( 'array_unique', $course_users );
			foreach ( $course_users as $course_id => $users ) {
				update_post_meta( $course_id, 'wrld_course_users', serialize( $users ) );
			}
			if ( $current_page == $total_pages ) {
				delete_option( 'course_migrate_page' );
				delete_option( 'course_migrate_total_pages' );
				update_option( 'migrated_course_access_data', time() );
				wp_send_json_success(array(
					'percentage' => 100,
					'next'       => false,
				), 200);
				die();
			}
			wp_send_json_success(array(
				'percentage' => floatval( number_format( 100* $current_page / $total_pages, 2, '.', '' ) ),
				'next'       => true,
			), 200);
			die();
		}

		public function migrate_course_access() {
			if ( get_option( 'migrated_course_access_data', false ) ) {
				return;
			}
			$db_instance = $this->get_db_instance();
			$result = $db_instance->get_results( "SELECT user_id, meta_key from {$db_instance->usermeta} WHERE meta_key LIKE 'course_%_access_from'", ARRAY_A );
			if ( empty( $result ) ) {
				return;
			}
			$course_users = array();
			foreach ( $result as $entry ) {
				// preg_match_all( '!\d+!', $entry['meta_key'], $course_id );
				$course_id = filter_var( $entry['meta_key'], FILTER_SANITIZE_NUMBER_INT );
				$course_users[ $course_id ][] = (int) $entry['user_id'];
			}
			$course_users = array_map( 'array_unique', $course_users );
			foreach ( $course_users as $course_id => $users ) {
				update_post_meta( $course_id, 'wrld_course_users', serialize( $users ) );
			}
			update_option( 'migrated_course_access_data', time() );
		}

		public function migrate_group_access_data() {
			if ( get_option( 'migrated_group_access_data', false ) ) {
				delete_option( 'group_migrate_page' );
				delete_option( 'group_migrate_total_pages' );
				delete_option( 'migrated_group_access_data' );
				// wp_send_json_success(array(
				// 	'percentage' => 100,
				// 	'next'       => false
				// ), 200);
				// die();
			}
			$db_instance = $this->get_db_instance();
			$total_pages = get_option( 'group_migrate_total_pages', false );
			if ( ! $total_pages ) {
				$total = $db_instance->get_var( "SELECT COUNT( * ) from {$db_instance->usermeta} WHERE meta_key LIKE 'learndash_group_users_%'" );
				$total_pages = ceil( (int) $total / 10 );
				update_option( 'group_migrate_total_pages', $total_pages );
			}
			$current_page = get_option( 'group_migrate_page', 1 );
			$result = $db_instance->get_results( "SELECT user_id, meta_key from {$db_instance->usermeta} WHERE meta_key LIKE 'learndash_group_users_%' LIMIT 10 OFFSET {$current_page}", ARRAY_A );
			update_option( 'group_migrate_page', $current_page + 1 );
			if ( empty( $result ) ) {
				update_option( 'migrated_group_access_data', time() );
				delete_option( 'group_migrate_page' );
				delete_option( 'group_migrate_total_pages' );
				wp_send_json_success(array(
					'percentage' => 100,
					'next'       => false
				), 200);
				die();
			}
			$group_users = array();
			foreach ( $result as $entry ) {
				// preg_match_all( '!\d+!', $entry['meta_key'], $group_id );
				$group_id = filter_var( $entry['meta_key'], FILTER_SANITIZE_NUMBER_INT );
				$group_users[ $group_id ][] = (int) $entry['user_id'];
			}
			$group_users = array_map( 'array_unique', $group_users );
			foreach ( $group_users as $group_id => $users ) {
				update_post_meta( $group_id, 'wrld_group_users', serialize( $users ) );
			}
			if ( $current_page == $total_pages ) {
				update_option( 'migrated_group_access_data', time() );
				delete_option( 'group_migrate_page' );
				delete_option( 'group_migrate_total_pages' );
				wp_send_json_success(array(
					'percentage' => 100,
					'next'       => false
				), 200);
				die();
			}
			wp_send_json_success(array(
				'percentage' => floatval( number_format( $current_page / $total_pages, 2, '.', '' ) ),
				'next'       => true,
			), 200);
			die();
		}
		public function migrate_course_time_data() {
			if ( get_option( 'migrated_course_time_access_data', false ) ) {
				delete_option( 'migrated_course_time_total_pages' );
				delete_option( 'migrated_course_time_page' );
				update_option( 'migrated_course_time_access_data', time() );
				error_log("return from existing");
				wp_send_json_success(array(
					'percentage' => 100,
					'next'       => false
				), 200);
				die();
			}
			if(get_option( 'migrated_course_time_page', 1 ) == 1){
				$this->addAlreadyEnrolleduserswithNoProgress();
			}
			$db_instance = $this->get_db_instance();
			$table_name = $db_instance->prefix . 'ld_time_entries'; 
            $table_name_time_spent = $db_instance->prefix . 'ld_course_time_spent'; 
			$total_pages = get_option( 'migrated_course_time_total_pages', false );
			if ( ! $total_pages ) {
				$total = $db_instance->get_var( "SELECT COUNT( * ) from {$table_name} " );
				$total_pages = ceil( (int) $total / 10 );
				update_option( 'migrated_course_time_total_pages', $total_pages );
			}
			$current_page = get_option( 'migrated_course_time_page', 1 );
			$offset = $current_page * 10;
			// error_log( "Offset : ".$offset);
			$sql = "SELECT course_id, user_id, activity_updated, time_spent AS total_time_spent from {$table_name} LIMIT 10 OFFSET {$offset}";
			$result = $db_instance->get_results( $sql, ARRAY_A );
			update_option( 'migrated_course_time_page', (int)$current_page + 1 );
			if ( empty( $result ) ) {
				update_option( 'migrated_course_time_access_data', time() );
				delete_option( 'migrated_course_time_page' );
				delete_option( 'migrated_course_time_total_pages' );
				// error_log("return from empty result");
				wp_send_json_success(array(
					'percentage' => 100,
					'next'       => false,
				), 200);
				die();
			}
			//start data update
			foreach ( $result as $entry ) {
				// preg_match_all( '!\d+!', $entry['meta_key'], $group_id );
				
				$course_id = $entry['course_id'];
				$user_id = $entry['user_id'];
				$time_spent = $entry['total_time_spent'];
				$original_time = $entry['total_time_spent'];
				
				$last_updated = $entry['activity_updated'];
				$enrolled_on = ld_course_access_from( $course_id, $user_id );
 				if ( empty( $enrolled_on ) ) {
				 $enrolled_on = learndash_user_group_enrolled_to_course_from( $user_id, $course_id );
				}
				// Check if the entry exists in the table
				$query = $db_instance->prepare(
					"SELECT COUNT(*) FROM {$table_name_time_spent} WHERE course_id = %d AND user_id = %d",
					$course_id,
					$user_id
				);

				$entry_exists = $db_instance->get_var($query);

				$data_formats = array(
					'%d', // course_id
					'%d', // total_time_spent
					'%d', //post_id
					'%d',// completion_time
					'%d', // user_id
					'%d', // enrollment_date
					'%d', // completion_date
				);
				if ($entry_exists) {

					$query2 = $db_instance->prepare(
						"SELECT total_time_spent FROM {$table_name_time_spent} WHERE course_id = %d AND user_id = %d",
						$course_id,
						$user_id
					);

					$total_time_spent_existing = $db_instance->get_var($query2);
					// Update the existing entry
					$existing_time = $total_time_spent_existing ?? 0;
					$total_time_spent = $original_time + (int) $existing_time;
					error_log("Existing entry");
					// error_log("original : " .$original_time);
					// error_log("time " .$original_time . ' + ' . $existing_time . ' = ' .  $total_time_spent );
					$completion_time = get_user_meta( $user_id, 'course_time_' . $course_id, true );
					$timestamp = $last_updated;
					$data = array(
						'course_id' => $course_id,
						'post_id' => $course_id,
						'total_time_spent' => $total_time_spent,
						'completion_time' => $completion_time == 0 ? null : $completion_time ,
						'user_id' => $user_id,
						'enrollment_date' => $enrolled_on,
						'completion_date' => $timestamp,
					);

					$db_instance->update(
						"{$table_name_time_spent}",
						$data,
						array('course_id' => $course_id, 'user_id' => $user_id),
						$data_formats,
						array('%d', '%d')
					);
				} else {
					// Insert a new entry
                    // error_log("New entry");
					// error_log("original for new : " .$original_time);

					$total_time_spent = $original_time;
					$completion_time = get_user_meta( $user_id, 'course_time_' . $course_id, true );
					$timestamp = $last_updated;
					$data = array(
						'course_id' => $course_id,
						'post_id' => $course_id,
						'total_time_spent' => $total_time_spent,
						'completion_time' => $completion_time == 0 ? null : $completion_time ,
						'user_id' => $user_id,
						'enrollment_date' =>  $enrolled_on,
						'completion_date' => $timestamp,
					);
					$db_instance->insert(
						$table_name_time_spent,
						$data,
						$data_formats
					);
				}
			}
			

			//end data update

			if ( $current_page == $total_pages ) {
				update_option( 'migrated_course_time_access_data', time() );
				delete_option( 'migrated_course_time_page' );
				delete_option( 'migrated_course_time_total_pages' );
				// error_log("return from same page");
				wp_send_json_success(array(
					'percentage' => 100,
					'next'       => false,
				), 200);
				die();
			}
			wp_send_json_success(array(
				'percentage' => floatval( number_format( ($current_page / $total_pages) * 100, 2, '.', '' ) ),
				'next'       => true,
			), 200);
			die();
		}



		public function  addAlreadyEnrolledUserswithNoProgress(){

			$count_query_args = array(
				'post_type'      => 'sfwd-courses',
				'posts_per_page' => -1,
				'post__in'       => -1 == $accessible_courses ? null : $accessible_courses,
				'suppress_filters' => 0,
			);
			$all_courses  = get_posts( $count_query_args );

		    $course_count = count( $all_courses );
			$course_ids = [];
			if($course_count > 0){
				$dataayyay = [];
				foreach ($all_courses as $key => $course) {
					$course_id = $course->ID;

					// Get the list of groups associated with the course
					$course_groups = learndash_get_course_groups($course_id);

					// Initialize an array to store user IDs
					$all_user_ids = array();

					// Loop through the groups
					foreach ($course_groups as $group_id) {
						// Get users within the group
						$group_users = learndash_get_groups_user_ids($group_id,true);
						
						// Merge group user IDs into the main array
						$all_user_ids = array_merge($all_user_ids, $group_users);
					}

					// Get user IDs enrolled in the course directly
					$course_users = learndash_get_course_users_access_from_meta($course_id);
					

					// Merge course user IDs into the main array
					$all_user_ids = array_merge($all_user_ids, $course_users);

					// Remove duplicates and sort the array
					$all_user_ids = array_unique($all_user_ids);
					sort($all_user_ids);

					// Loop through the combined user IDs
					foreach ($all_user_ids as $user_id) {
						// Output or process user ID as needed
						if($user_id){
							$this->createTimeSpentEntryForUser($course_id,$user_id);
						}
					}
				}
			}
           
		    return;
		}

		public function createTimeSpentEntryForUser($course_id , $user_id){

			// Check if the entry exists in the table
			$db_instance = $this->get_db_instance();
			$table_name_time_spent = $db_instance->prefix . 'ld_course_time_spent'; 
			$query = $db_instance->prepare(
				"SELECT COUNT(*) FROM {$table_name_time_spent} WHERE course_id = %d AND user_id = %d",
				$course_id,
				$user_id
			);

			$entry_exists = $db_instance->get_var($query);

			$data_formats = array(
				'%d', // course_id
				'%d', // total_time_spent
				'%d', //post_id
				'%d',// completion_time
				'%d', // user_id
				'%d', // enrollment_date
				'%d', // completion_date
			);
			if ($entry_exists) {
                return;
			} else {
				// Insert a new entry
				error_log("New entry");
				

				$enrolled_on = ld_course_access_from( $course_id, $user_id );
 				if ( empty( $enrolled_on ) ) {
				 $enrolled_on = learndash_user_group_enrolled_to_course_from( $user_id, $course_id );
				}

				$total_time_spent = 0;
				$completion_time = get_user_meta( $user_id, 'course_time_' . $course_id, true );
				$timestamp = null;
				$data = array(
					'course_id' => $course_id,
					'post_id' => $course_id,
					'total_time_spent' => 0,
					'completion_time' => null ,
					'user_id' => $user_id,
					'enrollment_date' =>  $enrolled_on,
					'completion_date' => $timestamp,
				);
				$db_instance->insert(
					$table_name_time_spent,
					$data,
					$data_formats
				);
				return;
			}

		}




		public function migrate_group_access() {
			if ( get_option( 'migrated_group_access_data', false ) ) {
				return;
			}
			$db_instance = $this->get_db_instance();
			$result = $db_instance->get_results( "SELECT user_id, meta_key from {$db_instance->usermeta} WHERE meta_key LIKE 'learndash_group_users_%'", ARRAY_A );
			if ( empty( $result ) ) {
				return;
			}
			$group_users = array();
			foreach ( $result as $entry ) {
				// preg_match_all( '!\d+!', $entry['meta_key'], $group_id );
				$group_id = filter_var( $entry['meta_key'], FILTER_SANITIZE_NUMBER_INT );
				$group_users[ $group_id ][] = (int) $entry['user_id'];
			}
			$group_users = array_map( 'array_unique', $group_users );
			foreach ( $group_users as $group_id => $users ) {
				update_post_meta( $group_id, 'wrld_group_users', serialize( $users ) );
			}
			update_option( 'migrated_group_access_data', time() );
		}

		/**
		 * Gets the group's user IDs if the course is associated with the group.
		 *
		 * @param int $course_id Optional. Course ID. Default 0.
		 *
		 * @return array An array of user IDs.
		 */
		public function get_course_groups_users_access( $course_id = 0 ) {
			$user_ids = array();

			$course_id = absint( $course_id );
			if ( ! empty( $course_id ) ) {
				$course_groups = learndash_get_course_groups( $course_id );
				if ( ( is_array( $course_groups ) ) && ( ! empty( $course_groups ) ) ) {
					foreach ( $course_groups as $group_id ) {
						$group_users_ids = $this->get_users_for_group( $group_id );
						if ( ! empty( $group_users_ids ) ) {
							$user_ids = array_merge( $user_ids, $group_users_ids );
						}
					}
				}
			}

			if ( ! empty( $user_ids ) ) {
				$user_ids = array_unique( $user_ids );
			}

			return $user_ids;
		}

		public function get_users_for_course( $course_id ) {
			$course_price_type = learndash_get_course_meta_setting( $course_id, 'course_price_type' );
			$course_user_ids   = array();
			if ( 'open' === $course_price_type ) {
				return 'all';
			}
			if ( true === learndash_use_legacy_course_access_list() ) {
				$course_access_list = learndash_get_course_meta_setting( $course_id, 'course_access_list' );
				if ( ! empty( $course_access_list ) ) {
					$course_user_ids    = array_merge( $course_user_ids, $course_access_list );
				}
			}

			$course_enrolled_users = maybe_unserialize( get_post_meta( $course_id, 'wrld_course_users', true ) );
			if ( ! empty( $course_enrolled_users ) ) {
				$course_user_ids     = array_merge( $course_user_ids, $course_enrolled_users );
			}


			$course_groups_users = $this->get_course_groups_users_access( $course_id );
			if ( ! empty( $course_groups_users ) ) {
				$course_user_ids     = array_merge( $course_user_ids, $course_groups_users );
			}

			if ( ! empty( $course_user_ids ) ) {
				$course_user_ids = array_unique( $course_user_ids );
			}

			$course_expired_access_users = learndash_get_course_expired_access_from_meta( $course_id );
			if ( ! empty( $course_expired_access_users ) ) {
				$course_user_ids = array_diff( $course_user_ids, $course_expired_access_users );
			}
			return $course_user_ids;
		}

		public function get_users_for_group( $group_id ) {
			$group_users = maybe_unserialize( get_post_meta( $group_id, 'wrld_group_users', true ) );
			return ! empty( $group_users ) ? $group_users : array();
		}

		public function add_course_users( $meta_id, $user_id, $meta_key, $meta_value ) {
			if ( ! function_exists( 'str_starts_with' ) ) {//for PHP < 8.0
				if ( strpos( $meta_key, 'course_') !== 0 || substr_compare( $meta_key, '_access_from', -strlen( '_access_from' ) ) !== 0 ) {
					return;
				}
			} else {// PHP > 8.0
				if ( ! str_starts_with( $meta_key, 'course_' ) || ! str_ends_with( $meta_key , '_access_from' ) ) {
					return;
				}
			}
			$course_id = filter_var( $meta_key, FILTER_SANITIZE_NUMBER_INT );
			$course_users = get_post_meta( $course_id, 'wrld_course_users', true );
			if ( empty( $course_users ) ) {
				update_post_meta( $course_id, 'wrld_course_users', serialize( array( $user_id ) ) );
			} else {
				$course_users = maybe_unserialize( $course_users );
				$course_users[] = $user_id;
				$course_users = array_unique( $course_users );
				update_post_meta( $course_id, 'wrld_course_users', serialize( $course_users ) );
			}
		}

		public function remove_course_users( $meta_id, $user_id, $meta_key, $meta_value ) {
			if ( ! function_exists( 'str_starts_with' ) ) {//for PHP < 8.0
				if ( strpos( $meta_key, 'course_') !== 0 || substr_compare( $meta_key, '_access_from', -strlen( '_access_from' ) ) !== 0 ) {
					return;
				}
			} else {// PHP > 8.0
				if ( ! str_starts_with( $meta_key, 'course_' ) || ! str_ends_with( $meta_key , '_access_from' ) ) {
					return;
				}
			}
			$course_id = filter_var( $meta_key, FILTER_SANITIZE_NUMBER_INT );
			$course_users = get_post_meta( $course_id, 'wrld_course_users', true );
			if ( empty( $course_users ) ) {
				return;
			}
			$course_users = maybe_unserialize( $course_users );
			$course_users = array_diff( $course_users, array( $user_id ) );
			$course_users = array_unique( $course_users );
			update_post_meta( $course_id, 'wrld_course_users', serialize( $course_users ) );
		}

		public function add_group_users( $user_id, $group_id ) {
			$group_users = get_post_meta( $group_id, 'wrld_group_users', true );
			if ( empty( $group_users ) ) {
				update_post_meta( $group_id, 'wrld_group_users', serialize( array( $user_id ) ) );
			} else {
				$group_users = maybe_unserialize( $group_users );
				$group_users[] = $user_id;
				$group_users = array_unique( $group_users );
				update_post_meta( $group_id, 'wrld_group_users', serialize( $group_users ) );
			}
		}

		public function remove_group_users( $user_id, $group_id ) {
			$group_users = get_post_meta( $group_id, 'wrld_group_users', true );
			if ( empty( $group_users ) ) {
				return;
			}
			$group_users = maybe_unserialize( $group_users );
			$group_users = array_diff( $group_users, array( $user_id ) );
			$group_users = array_unique( $group_users );
			update_post_meta( $group_id, 'wrld_group_users', serialize( $group_users ) );
		}

		public function get_learner_quiz_activity( $start_date, $end_date, $learner, $course_id, $quiz_pro_id, $page = 1 ) {
			$db_instance = $this->get_db_instance();
			$table_name  = $this->get_db_name( 'quiz_statistic_ref' );
			$limit       = 10;
			$offset      = ( (int) $page - 1 ) * (int) $limit;
			$condition   = 'user_id = %d AND is_old=0';
			if ( isset( $start_date ) && ! empty( $start_date ) ) {
				$condition .= sprintf( ' AND create_time>=%d', $start_date );
			}
			if ( isset( $end_date ) && ! empty( $end_date ) ) {
				$condition .= sprintf( ' AND create_time<=%d', $end_date );
			}
			if ( isset( $course_id ) && ! empty( $course_id ) ) {
				$condition .= sprintf( ' AND course_post_id=%d', $course_id );
			}
			if ( isset( $quiz_pro_id ) && ! empty( $quiz_pro_id ) ) {
				$condition .= sprintf( ' AND quiz_id=%d', $quiz_pro_id );
			}
			$query  = $db_instance->prepare( "SELECT * FROM {$table_name} WHERE {$condition} ORDER BY statistic_ref_id DESC LIMIT %d OFFSET %d", $learner, $limit, $offset );
			$result = $db_instance->get_results( $query, ARRAY_A );
			return $result;
		}

		public function get_learner_quiz_activity_count( $start_date, $end_date, $learner, $course_id, $quiz_pro_id ) {
			$db_instance = $this->get_db_instance();
			$table_name  = $this->get_db_name( 'quiz_statistic_ref' );
			$condition   = 'user_id = %d AND is_old=0';
			if ( isset( $start_date ) && ! empty( $start_date ) ) {
				$condition .= sprintf( ' AND create_time>=%d', $start_date );
			}
			if ( isset( $end_date ) && ! empty( $end_date ) ) {
				$condition .= sprintf( ' AND create_time<=%d', $end_date );
			}
			if ( isset( $course_id ) && ! empty( $course_id ) ) {
				$condition .= sprintf( ' AND course_post_id=%d', $course_id );
			}
			if ( isset( $quiz_pro_id ) && ! empty( $quiz_pro_id ) ) {
				$condition .= sprintf( ' AND quiz_id=%d', $quiz_pro_id );
			}
			$query  = $db_instance->prepare( "SELECT COUNT(*) FROM {$table_name} WHERE {$condition} ORDER BY statistic_ref_id DESC", $learner );
			$result = $db_instance->get_var( $query );
			return $result;
		}

		public static function get_learner_activity_log( $start_date, $end_date, $accessible_courses = null, $accessible_users = null, $excluded_users = array(), $page = 1 ) {
			global $wpdb;
			$limit = 5;

			$offset = ( (int) $page - 1 ) * (int) $limit;

			$condition = '';

			if ( ! empty( $accessible_courses ) && -1 != $accessible_courses ) {
				$condition .= sprintf( ' AND activity.course_id IN (%s)', implode( ',', $accessible_courses ) );
			}

			if ( ! empty( $accessible_users ) && -1 != $accessible_users ) {
				$condition .= sprintf( ' AND activity.user_id IN (%s)', implode( ',', $accessible_users ) );
			}

			if ( ! empty( $excluded_users ) ) {
				$condition .= sprintf( ' AND activity.user_id NOT IN (%s)', implode( ',', $excluded_users ) );
			}

			// $query  = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}learndash_user_activity activity WHERE ( ( activity.activity_completed >= %d AND activity.activity_completed <= %d ) OR ( activity.activity_updated >= %d AND activity.activity_updated <= %d ) OR ( activity.activity_started >= %d AND activity.activity_started <= %d ) AND activity.activity_type != 'group_progress' ) {$condition} ORDER BY activity.activity_updated DESC, activity.activity_completed DESC, activity.activity_started DESC LIMIT %d OFFSET %d;", $start_date, $end_date, $start_date, $end_date, $start_date, $end_date, $limit, $offset );//phpcs:ignore
			//
			$query = $wpdb->prepare( "( SELECT user_id,post_id,course_id,activity_updated, activity_started,activity_status,activity_type,activity_completed FROM {$wpdb->prefix}learndash_user_activity activity WHERE ( ( activity.activity_completed >= %d AND activity.activity_completed <= %d ) OR ( activity.activity_updated >= %d AND activity.activity_updated <= %d ) OR ( activity.activity_started >= %d AND activity.activity_started <= %d ) AND activity.activity_type != 'group_progress' ) {$condition} ORDER BY activity.activity_updated DESC, activity.activity_completed DESC, activity.activity_started DESC ) UNION ALL ( SELECT user_id,post_id,course_id,activity_updated, null as activity_started, null as activity_status, null as activity_type, null as activity_completed FROM {$wpdb->prefix}ld_time_entries activity WHERE  ( activity.activity_updated >= %d AND activity.activity_updated <= %d ) {$condition} ORDER BY activity.activity_updated DESC ) ORDER BY activity_updated DESC LIMIT %d OFFSET %d", $start_date, $end_date, $start_date, $end_date, $start_date, $end_date, $start_date, $end_date, $limit, $offset );//phpcs:ignore

			$result = $wpdb->get_results( $query, ARRAY_A );//phpcs:ignore
			return $result;
		}

		public static function get_learner_activity_log_count( $start_date, $end_date, $accessible_courses = null, $accessible_users = null, $excluded_users = array() ) {
			global $wpdb;

			$condition = '';

			if ( ! empty( $accessible_courses ) && -1 != $accessible_courses ) {
				$condition .= sprintf( ' AND activity.course_id IN (%s)', implode( ',', $accessible_courses ) );
			}

			if ( ! empty( $accessible_users ) && -1 != $accessible_users ) {
				$condition .= sprintf( ' AND activity.user_id IN (%s)', implode( ',', $accessible_users ) );
			}

			if ( ! empty( $excluded_users ) ) {
				$condition .= sprintf( ' AND activity.user_id NOT IN (%s)', implode( ',', $excluded_users ) );
			}

			$query  = $wpdb->prepare( "( SELECT user_id,post_id,course_id,activity_updated, activity_started,activity_status,activity_type,activity_completed FROM {$wpdb->prefix}learndash_user_activity activity WHERE ( ( activity.activity_completed >= %d AND activity.activity_completed <= %d ) OR ( activity.activity_updated >= %d AND activity.activity_updated <= %d ) OR ( activity.activity_started >= %d AND activity.activity_started <= %d ) AND activity.activity_type != 'group_progress' ) {$condition} ORDER BY activity.activity_updated DESC, activity.activity_completed DESC, activity.activity_started DESC ) UNION ALL ( SELECT user_id,post_id,course_id,activity_updated, null as activity_started, null as activity_status, null as activity_type, null as activity_completed FROM {$wpdb->prefix}ld_time_entries activity WHERE  ( activity.activity_updated >= %d AND activity.activity_updated <= %d ) {$condition} ORDER BY activity.activity_updated DESC ) ORDER BY activity_updated DESC;", $start_date, $end_date, $start_date, $end_date, $start_date, $end_date, $start_date, $end_date );//phpcs:ignore
			$result = $wpdb->get_results( $query, ARRAY_A );//phpcs:ignore
			return count( $result );
		}

		public static function get_inactive_users_info( $start_date, $end_date, $accessible_courses = null, $accessible_users = null, $excluded_users = array(), $page = 1, $limit = 10 ) {
			global $wpdb;

			$offset = ( (int) $page - 1 ) * (int) $limit;

			$condition = '1=1';

			if ( ! empty( $accessible_courses ) && -1 != $accessible_courses ) {
				$condition .= sprintf( ' AND activity.course_id IN (%s)', implode( ',', $accessible_courses ) );
			}
			
			$sub_query = $wpdb->prepare( "( SELECT user_id FROM {$wpdb->prefix}learndash_user_activity activity WHERE ( ( activity.activity_completed >= %d AND activity.activity_completed <= %d ) OR ( activity.activity_updated >= %d AND activity.activity_updated <= %d ) OR ( activity.activity_started >= %d AND activity.activity_started <= %d ) ) AND {$condition} ORDER BY activity.activity_updated DESC, activity.activity_completed DESC, activity.activity_started DESC ) UNION ( SELECT user_id FROM {$wpdb->prefix}ld_time_entries activity WHERE  ( activity.activity_updated >= %d AND activity.activity_updated <= %d ) AND {$condition} ORDER BY activity.activity_updated DESC ) ORDER BY user_id DESC;", $start_date, $end_date, $start_date, $end_date, $start_date, $end_date, $start_date, $end_date );//phpcs:ignore

			$users     = $wpdb->get_results( $sub_query );//phpcs:ignore
			$users     = array_unique( wp_list_pluck( $users, 'user_id' ) );
			if ( ! empty( $excluded_users ) ) {
				$users = array_merge( $users, $excluded_users );
			}

			if ( ! empty( $accessible_users ) && -1 != $accessible_users ) {
				$accessible_users = array_diff( $accessible_users, $users );
				$condition       .= sprintf( ' AND activity.user_id IN (%s)', implode( ',', $accessible_users ) );
			} else {
				if ( ! empty( $users ) ) {
					$condition .= sprintf( ' AND activity.user_id NOT IN (%s)', implode( ',', $users ) );
				}
			}

			$query1 = $wpdb->prepare( "SELECT * FROM (SELECT activity.user_id, 
				activity.post_id, 
				activity.course_id, 
				activity.activity_updated, 
				activity.activity_started, 
				activity.activity_completed, 
				GREATEST( IFNULL( activity.activity_updated, 0 ), IFNULL( activity.activity_completed, 0 ),IFNULL( activity.activity_started, 0 ) ) as great,
				row_number() OVER (PARTITION BY activity.user_id ORDER BY GREATEST( IFNULL( activity.activity_updated, 0 ), IFNULL( activity.activity_completed, 0 ),IFNULL( activity.activity_started, 0 ) ) desc) as rn
				FROM {$wpdb->prefix}learndash_user_activity activity WHERE {$condition}
				ORDER BY great DESC, rn ASC) as dist WHERE rn = 1 LIMIT %d OFFSET %d", $limit, $offset );

			// $query  = $wpdb->prepare( "SELECT * FROM ( SELECT DISTINCT activity.user_id, activity.post_id, activity.course_id, activity.activity_updated, activity.activity_completed, activity.activity_started, timer.post_id as post, timer.course_id as course, timer.user_id as user, timer.activity_updated as updated, GREATEST( IFNULL( activity.activity_updated, 0 ), IFNULL( activity.activity_completed, 0 ),IFNULL( activity.activity_started, 0 ), IFNULL( timer.activity_updated, 0 ) ) as great, /*row_number() OVER (PARTITION BY timer.user_id ORDER BY GREATEST( IFNULL( activity.activity_updated, 0 ), IFNULL( activity.activity_completed, 0 ),IFNULL( activity.activity_started, 0 ), IFNULL( timer.activity_updated, 0 ) ) DESC) AS rn*/ FROM {$wpdb->prefix}learndash_user_activity activity JOIN {$wpdb->prefix}ld_time_entries timer ON activity.user_id=timer.user_id AND activity.course_id=timer.course_id WHERE {$condition} ORDER BY great DESC/*, rn ASC*/ ) as dist /*WHERE rn = 1*/ LIMIT %d OFFSET %d;", $limit, $offset );//phpcs:ignore
			// SELECT DISTINCT activity.user_id, activity.post_id, activity.course_id, activity.activity_updated, activity.activity_completed, activity.activity_started, timer.post_id as post, timer.course_id as course, timer.user_id as user, timer.activity_updated as updated, GREATEST( IFNULL( activity.activity_updated, 0 ), IFNULL( activity.activity_completed, 0 ),IFNULL( activity.activity_started, 0 ), IFNULL( timer.activity_updated, 0 ) ) as great,row_number() OVER (PARTITION BY timer.user_id ORDER BY GREATEST( IFNULL( activity.activity_updated, 0 ), IFNULL( activity.activity_completed, 0 ),IFNULL( activity.activity_started, 0 ), IFNULL( timer.activity_updated, 0 ) ) DESC) AS rn FROM wp_learndash_user_activity activity JOIN wp_ld_time_entries timer ON activity.user_id=timer.user_id AND activity.course_id=timer.course_id WHERE 1=1 AND activity.user_id NOT IN (48) ORDER BY great DESC, rn ASC
			$result = $wpdb->get_results( $query1, ARRAY_A );//phpcs:ignore
			$output = array();
			foreach ($result as $user) {
				$query2 = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}ld_time_entries WHERE user_id={$user['user_id']} AND activity_updated > {$user['great']} ORDER BY activity_updated LIMIT 1;" );
				// error_log('=====================INNER QUERY==============');
				// error_log($query2);
				$result2 = $wpdb->get_results( $query2, ARRAY_A );
				// error_log(print_r($result2, 1));
				if ( empty($result2) ) {
					$output[] = $user;
				} else {
					$output[] = $result2[0];
				}
			}
			// error_log('====================FIRST RESULT===================');
			// error_log($query1);
			// error_log(print_r($result, 1));
			// error_log('====================FINAL RESULT===================');
			// error_log(print_r($output, 1));
			return $result;
		}

		public static function get_inactive_users_info_count( $start_date, $end_date, $accessible_courses = null, $accessible_users = null, $excluded_users = array() ) {
			global $wpdb;

			$condition = '1=1';

			if ( ! empty( $accessible_courses ) && -1 != $accessible_courses ) {
				$condition .= sprintf( ' AND activity.course_id IN (%s)', implode( ',', $accessible_courses ) );
			}

			$sub_query = $wpdb->prepare( "( SELECT user_id FROM {$wpdb->prefix}learndash_user_activity activity WHERE ( ( activity.activity_completed >= %d AND activity.activity_completed <= %d ) OR ( activity.activity_updated >= %d AND activity.activity_updated <= %d ) OR ( activity.activity_started >= %d AND activity.activity_started <= %d ) ) AND {$condition} ORDER BY activity.activity_updated DESC, activity.activity_completed DESC, activity.activity_started DESC ) UNION ( SELECT user_id FROM {$wpdb->prefix}ld_time_entries activity WHERE  ( activity.activity_updated >= %d AND activity.activity_updated <= %d ) AND {$condition} ORDER BY activity.activity_updated DESC ) ORDER BY user_id DESC;", $start_date, $end_date, $start_date, $end_date, $start_date, $end_date, $start_date, $end_date );//phpcs:ignore

			$users = $wpdb->get_results( $sub_query );//phpcs:ignore
			$users = array_unique( wp_list_pluck( $users, 'user_id' ) );

			if ( ! empty( $excluded_users ) ) {
				$users = array_merge( $users, $excluded_users );
			}

			if ( ! empty( $accessible_users ) && -1 != $accessible_users ) {
				$accessible_users = array_diff( $accessible_users, $users );
				$condition       .= sprintf( ' AND activity.user_id IN (%s)', implode( ',', $accessible_users ) );
			} else {
				if ( ! empty( $users ) ) {
					$condition .= sprintf( ' AND activity.user_id NOT IN (%s)', implode( ',', $users ) );
				}
			}

			$query1 = $wpdb->prepare( "SELECT * FROM (SELECT activity.user_id, 
				activity.post_id, 
				activity.course_id, 
				activity.activity_updated, 
				activity.activity_started, 
				activity.activity_completed, 
				GREATEST( IFNULL( activity.activity_updated, 0 ), IFNULL( activity.activity_completed, 0 ),IFNULL( activity.activity_started, 0 ) ) as great,
				row_number() OVER (PARTITION BY activity.user_id ORDER BY GREATEST( IFNULL( activity.activity_updated, 0 ), IFNULL( activity.activity_completed, 0 ),IFNULL( activity.activity_started, 0 ) ) desc) as rn
				FROM {$wpdb->prefix}learndash_user_activity activity WHERE {$condition}
				ORDER BY great DESC, rn ASC) as dist WHERE rn = 1" );


			// $query  = $wpdb->prepare( "SELECT * FROM ( SELECT DISTINCT activity.user_id, activity.post_id, activity.course_id, activity.activity_updated, activity.activity_completed, activity.activity_started, timer.post_id as post, timer.course_id as course, timer.user_id as user, timer.activity_updated as updated/*, row_number() OVER (PARTITION BY timer.user_id ORDER BY timer.activity_updated DESC, activity.activity_updated DESC) AS rn*/ FROM {$wpdb->prefix}learndash_user_activity activity JOIN {$wpdb->prefix}ld_time_entries timer ON activity.user_id=timer.user_id AND activity.course_id=timer.course_id WHERE {$condition} ORDER BY /*rn, */timer.activity_updated DESC ) as dist/* WHERE rn = 1*/;" );//phpcs:ignore
			$result = $wpdb->get_results( $query1 );//phpcs:ignore
			$result = count( $result );
			return $result;
		}

		/**
		 * Gets all Statistics reference IDs for a given quiz.
		 *
		 * @param integer $quiz_id Quiz ID.
		 * @param array   $args    Timestamp Range.
		 *
		 * @return array $statistics Array of Statistic Reference IDs.
		 */
		public function get_all_statistic_ref_ids_by_quiz( $quiz_id, $args = array() ) {
			$db_instance = $this->get_db_instance();
			$table_name  = $this->get_db_name( 'quiz_statistic_ref' );
			$default     = array(
				'limit' => 10,
				'page'  => 1,
			);
			$args        = wp_parse_args( $args, $default );

			$args = apply_filters( 'wrld_get_all_statistic_ref_ids_by_quiz_args', $args, $quiz_id );

			if ( is_wp_error( $args ) ) {
				return array();
			}

			$offset    = ( (int) $args['page'] - 1 ) * (int) $args['limit'];
			$condition = 'quiz_id = %d AND is_old=0';
			if ( isset( $args['from'] ) && ! empty( $args['from'] ) ) {
				$condition .= sprintf( ' AND create_time>=%d', $args['from'] );
			}
			if ( isset( $args['to'] ) && ! empty( $args['to'] ) ) {
				$condition .= sprintf( ' AND create_time<=%d', $args['to'] );
			}
			$query  = $db_instance->prepare( "SELECT * FROM {$table_name} WHERE {$condition} ORDER BY statistic_ref_id DESC LIMIT %d OFFSET %d", $quiz_id, $args['limit'], $offset );
			$result = $db_instance->get_results( $query, ARRAY_A );

			/**
			 *
			 * Statistics Reference IDs by Quiz.
			 *
			 * Returns all statistics reference IDs for a quiz.
			 *
			 * @since 3.0.0
			 *
			 * @param array  $result    All Statistics Reference IDs.
			 * @param object $quiz_id   Quiz ID.
			 * @param array  $args      Timestamp Range.(keys are from and to).
			 */
			return apply_filters( 'get_all_statistic_ref_ids_by_quiz', $result, $quiz_id, $args );
		}

		/**
		 * Gets all Statistics reference IDs for a given user.
		 *
		 * @param integer $user_id User ID.
		 * @param array   $args    Timestamp Range.
		 *
		 * @return array $statistics Array of Statistic Reference IDs.
		 */
		public function get_all_statistic_ref_ids_by_user( $user_id, $args = array() ) {
			$db_instance = $this->get_db_instance();
			$table_name  = $this->get_db_name( 'quiz_statistic_ref' );
			$default     = array(
				'limit' => 10,
				'page'  => 1,
			);
			$args        = wp_parse_args( $args, $default );

			$args = apply_filters( 'wrld_get_all_statistic_ref_ids_by_user_args', $args, $user_id );

			if ( is_wp_error( $args ) ) {
				return array();
			}

			$offset    = ( (int) $args['page'] - 1 ) * (int) $args['limit'];
			$condition = 'user_id = %d AND is_old=0';
			if ( isset( $args['from'] ) && ! empty( $args['from'] ) ) {
				$condition .= sprintf( ' AND create_time>=%d', $args['from'] );
			}
			if ( isset( $args['to'] ) && ! empty( $args['to'] ) ) {
				$condition .= sprintf( ' AND create_time<=%d', $args['to'] );
			}
			$query  = $db_instance->prepare( "SELECT * FROM {$table_name} WHERE {$condition} ORDER BY statistic_ref_id DESC LIMIT %d OFFSET %d", $user_id, $args['limit'], $offset );
			$result = $db_instance->get_results( $query, ARRAY_A );

			/**
			 *
			 * Statistics Reference IDs.
			 *
			 * Returns all statistics reference IDs.
			 *
			 * @since 3.0.0
			 *
			 * @param array  $result    All Statistics Reference IDs.
			 * @param object $user_id   User ID.
			 * @param array  $args      Timestamp Range.(keys are from and to).
			 */
			return apply_filters( 'get_all_statistic_ref_ids_by_user', $result, $user_id, $args );
		}

		public function get_export_attempts_data( $args, $limit = 10, $page = 1 ) {
			$db_instance = $this->get_db_instance();
			$table_name  = $this->get_db_name( 'quiz_statistic_ref' );
			$offset      = ( (int) $page - 1 ) * (int) $limit;
			$condition   = 'is_old=0 AND ( 1';
			if ( $args['quiz_id'] > 0 ) {
				$condition .= sprintf( ' AND ( quiz_id = %d )', $args['quiz_id'] );
			}

			if ( $args['user_id'] > 0 ) {
				$condition .= sprintf( ' AND ( user_id = %d )', $args['user_id'] );
			}

			if ( -3 == $args['user_id'] ) {
				$condition .= sprintf( ' AND ( user_id = %d )', 0 );
			}

			if ( $args['course_id'] > 0 ) {
				$condition .= sprintf( ' AND ( course_post_id = %d )', $args['course_id'] );
			}
			if ( $args['group_id'] > 0 && $args['course_id'] <= 0 ) {
				$condition .= sprintf( ' AND ( user_id IN (%s) AND course_post_id IN (%s) )', $args['users'], $args['courses'] );
			} elseif ( $args['group_id'] > 0 ) {
				$condition .= sprintf( ' AND ( user_id IN (%s) )', $args['users'] );
			}
			if ( ! empty( $args['start'] ) ) {
				$condition .= sprintf( ' AND create_time >= %s', $args['start'] );
			}
			if ( ! empty( $args['end'] ) ) {
				$condition .= sprintf( ' AND create_time < %s', $args['end'] );
			}
			$condition .= ')';
			$query      = $db_instance->prepare( "SELECT * FROM {$table_name} WHERE {$condition} ORDER BY statistic_ref_id DESC LIMIT %d OFFSET %d", $limit, $offset );
			$result     = $db_instance->get_results( $query, ARRAY_A );
			return $result;
		}

		public function get_export_attempts_data_count( $args ) {
			$db_instance = $this->get_db_instance();
			$table_name  = $this->get_db_name( 'quiz_statistic_ref' );
			$condition   = 'is_old=0 AND ( 1';
			if ( $args['quiz_id'] > 0 ) {
				$condition .= sprintf( ' AND ( quiz_id = %d )', $args['quiz_id'] );
			}
			if ( $args['user_id'] > 0 ) {
				$condition .= sprintf( ' AND ( user_id = %d )', $args['user_id'] );
			}
			if ( -3 == $args['user_id'] ) {
				$condition .= sprintf( ' AND ( user_id = %d )', 0 );
			}
			if ( $args['course_id'] > 0 ) {
				$condition .= sprintf( ' AND ( course_post_id = %d )', $args['course_id'] );
			}
			if ( $args['group_id'] > 0 && $args['course_id'] <= 0 ) {
				$condition .= sprintf( ' AND ( user_id IN (%s) AND course_post_id IN (%s) )', $args['users'], $args['courses'] );
			} elseif ( $args['group_id'] > 0 ) {
				$condition .= sprintf( ' AND ( user_id IN (%s) )', $args['users'] );
			}
			if ( ! empty( $args['start'] ) ) {
				$condition .= sprintf( ' AND create_time >= %s', $args['start'] );
			}
			if ( ! empty( $args['end'] ) ) {
				$condition .= sprintf( ' AND create_time < %s', $args['end'] );
			}
			$condition .= ')';
			$query      = "SELECT COUNT( * ) FROM {$table_name} WHERE {$condition} ORDER BY statistic_ref_id DESC";
			$result     = $db_instance->get_var( $query );
			return $result;
		}

		public function get_export_quizzes_count( $args ) {
			$db_instance = $this->get_db_instance();
			$table_name  = $this->get_db_name( 'quiz_statistic_ref' );
			$condition   = 'is_old=0 AND ( 1';
			if ( $args['quiz_id'] > 0 ) {
				$condition .= sprintf( ' AND ( quiz_id = %d )', $args['quiz_id'] );
			}
			if ( $args['course_id'] > 0 ) {
				$condition .= sprintf( ' AND ( course_post_id = %d )', $args['course_id'] );
			}
			if ( $args['group_id'] > 0 && $args['course_id'] <= 0 ) {
				$condition .= sprintf( ' AND ( user_id IN (%s) AND course_post_id IN (%s) )', $args['users'], $args['courses'] );
			} elseif ( $args['group_id'] > 0 ) {
				$condition .= sprintf( ' AND ( user_id IN (%s) )', $args['users'] );
			}
			if ( ! empty( $args['start'] ) ) {
				$condition .= sprintf( ' AND create_time >= %s', $args['start'] );
			}
			if ( ! empty( $args['end'] ) ) {
				$condition .= sprintf( ' AND create_time < %s', $args['end'] );
			}
			$condition .= ')';
			$query      = "SELECT COUNT( DISTINCT quiz_id ) FROM {$table_name} WHERE {$condition} ORDER BY statistic_ref_id DESC";
			$result     = $db_instance->get_var( $query );
			return $result;
		}

		public function get_export_learner_data( $args, $limit = 10, $page = 1 ) {
			$db_instance = $this->get_db_instance();
			$table_name  = $this->get_db_name( 'quiz_statistic_ref' );
			$offset      = ( (int) $page - 1 ) * (int) $limit;
			$condition   = 'is_old=0 AND ( 1';
			if ( $args['quiz_id'] > 0 ) {
				$condition .= sprintf( ' AND ( quiz_id = %d )', $args['quiz_id'] );
			}
			if ( $args['course_id'] > 0 ) {
				$condition .= sprintf( ' AND ( course_post_id = %d )', $args['course_id'] );
			}
			if ( $args['group_id'] > 0 && $args['course_id'] <= 0 ) {
				$condition .= sprintf( ' AND ( user_id IN (%s) AND course_post_id IN (%s) )', $args['users'], $args['courses'] );
			} elseif ( $args['group_id'] > 0 ) {
				$condition .= sprintf( ' AND ( user_id IN (%s) )', $args['users'] );
			}
			if ( ! empty( $args['start'] ) ) {
				$condition .= sprintf( ' AND create_time >= %s', $args['start'] );
			}
			if ( ! empty( $args['end'] ) ) {
				$condition .= sprintf( ' AND create_time < %s', $args['end'] );
			}
			$condition .= ')';
			$query      = $db_instance->prepare( "SELECT * FROM {$table_name} WHERE {$condition} ORDER BY statistic_ref_id DESC LIMIT %d OFFSET %d", $limit, $offset );
			$result     = $db_instance->get_results( $query, ARRAY_A );
			return $result;
		}

		public function get_crossreferenced_statistics( $courses, $start, $end, $limit, $page ) {
			$db_instance = $this->get_db_instance();
			$table_name  = $this->get_db_name( 'quiz_statistic_ref' );
			$offset      = ( (int) $page - 1 ) * (int) $limit;
			$condition   = 'is_old=0';

			$courses = apply_filters( 'wrld_get_crossreferenced_statistics_args', $courses );

			if ( empty( $start ) || empty( $end ) ) {
				$start = apply_filters( 'wrld_filter_start_date_timestamp', strtotime( gmdate( 'j M Y' ) . '-30 days' ) );// phpcs:ignore.
				$end = apply_filters( 'wrld_filter_end_date_timestamp', current_time( 'timestamp' ) );// phpcs:ignore.
			}

			$condition .= sprintf( ' AND ( create_time >= %s AND create_time <= %s ) AND ( 0', $start, $end );

			foreach ( $courses as $key => $course ) {
				if ( empty( $course['quiz_pro_ids'] ) ) {
					continue;
				}
				if ( ! empty( $course['user_ids'] ) ) {
					$user_ids_string = implode( ',', $course['user_ids'] );
				} elseif ( isset( $course['exclude_user_ids'] ) && ! empty( $course['exclude_user_ids'] ) ) {
					$user_exclude_string = implode( ',', $course['exclude_user_ids'] );
				}
				$quiz_ids_string = implode( ',', $course['quiz_pro_ids'] );
				if ( isset( $user_ids_string ) && ! empty( $user_ids_string ) ) {
					$condition .= sprintf( ' OR ( user_id IN (%s) AND quiz_id IN (%s) AND course_post_id = %d )', $user_ids_string, $quiz_ids_string, $course['post']->ID );
				} elseif ( isset( $user_exclude_string ) && ! empty( $user_exclude_string ) ) {
					$condition .= sprintf( ' OR ( user_id NOT IN (%s) AND quiz_id IN (%s) AND course_post_id = %d )', $user_exclude_string, $quiz_ids_string, $course['post']->ID );
				} else {
					$condition .= sprintf( ' OR ( quiz_id IN (%s) AND course_post_id = %d )', $quiz_ids_string, $course['post']->ID );
				}
			}
			$condition .= ')';
			$query      = $db_instance->prepare( "SELECT * FROM {$table_name} WHERE {$condition} ORDER BY statistic_ref_id DESC LIMIT %d OFFSET %d", $limit, $offset );
			$result     = $db_instance->get_results( $query, ARRAY_A );

			/**
			 *
			 * Statistics Reference IDs.
			 *
			 * Returns all statistics reference IDs.
			 *
			 * @since 3.0.0
			 *
			 * @param array  $result    All Statistics Reference IDs.
			 * @param array  $args      Query Args. Params are array of 'user_ids' , array of 'quiz_ids', Timestamp 'from', timestamp 'to').
			 */
			return apply_filters( 'get_crossreferenced_statistics', $result, $condition );
		}

		public function get_crossreferenced_statistics_count( $courses, $start, $end, $limit = 0, $page = 0 ) {
			$db_instance = $this->get_db_instance();
			$table_name  = $this->get_db_name( 'quiz_statistic_ref' );
			$offset      = ( (int) $page - 1 ) * (int) $limit;
			$condition   = 'is_old=0';

			$courses = apply_filters( 'wrld_get_crossreferenced_statistics_count_args', $courses );

			if ( empty( $start ) || empty( $end ) ) {
				$start = apply_filters( 'wrld_filter_start_date_timestamp', strtotime( gmdate( 'j M Y' ) . '-30 days' ) );// phpcs:ignore.
				$end = apply_filters( 'wrld_filter_end_date_timestamp', current_time( 'timestamp' ) );// phpcs:ignore.
			}

			$condition .= sprintf( ' AND ( create_time >= %s AND create_time <= %s ) AND ( 0', $start, $end );

			foreach ( $courses as $key => $course ) {
				if ( empty( $course['quiz_pro_ids'] ) ) {
					continue;
				}
				if ( ! empty( $course['user_ids'] ) ) {
					$user_ids_string = implode( ',', $course['user_ids'] );
				} elseif ( isset( $course['exclude_user_ids'] ) && ! empty( $course['exclude_user_ids'] ) ) {
					$user_exclude_string = implode( ',', $course['exclude_user_ids'] );
				}
				$quiz_ids_string = implode( ',', $course['quiz_pro_ids'] );
				if ( isset( $user_ids_string ) && ! empty( $user_ids_string ) ) {
					$condition .= sprintf( ' OR ( user_id IN (%s) AND quiz_id IN (%s) AND course_post_id = %d )', $user_ids_string, $quiz_ids_string, $course['post']->ID );
				} elseif ( isset( $user_exclude_string ) && ! empty( $user_exclude_string ) ) {
					$condition .= sprintf( ' OR ( user_id NOT IN (%s) AND quiz_id IN (%s) AND course_post_id = %d )', $user_exclude_string, $quiz_ids_string, $course['post']->ID );
				} else {
					$condition .= sprintf( ' OR ( quiz_id IN (%s) AND course_post_id = %d )', $quiz_ids_string, $course['post']->ID );
				}
			}
			$condition .= ')';
			$query      = $db_instance->prepare( "SELECT COUNT(*) FROM {$table_name} WHERE {$condition} ORDER BY statistic_ref_id DESC" );
			$result     = $db_instance->get_var( $query );

			/**
			 *
			 * Statistics Reference IDs.
			 *
			 * Returns all statistics reference IDs.
			 *
			 * @since 3.0.0
			 *
			 * @param array  $result    All Statistics Reference IDs.
			 * @param array  $args      Query Args. Params are array of 'user_ids' , array of 'quiz_ids', Timestamp 'from', timestamp 'to').
			 */
			return apply_filters( 'get_crossreferenced_statistics_count', $result, $condition );
		}

		/**
		 * Gets all Statistics reference IDs.
		 *
		 * @param array $args Query Args. Params are array of 'user_ids' , array of 'quiz_ids', Timestamp 'from', timestamp 'to').
		 *
		 * @return array $statistics Array of Statistic Reference IDs.
		 */
		public function get_all_statistic_ref_ids( $args = array(
			'user_ids'  => array(),
			'quiz_ids'  => array(),
			'limit'     => 10,
			'page'      => 1,
			'exclusive' => false,
		) ) {
			$default = array(
				'user_ids'  => array(),
				'quiz_ids'  => array(),
				'limit'     => 10,
				'page'      => 1,
				'exclusive' => false,
			);
			$args    = wp_parse_args( $args, $default );

			$args = apply_filters( 'wrld_get_all_statistic_ref_ids_args', $args );

			$db_instance = $this->get_db_instance();
			$table_name  = $this->get_db_name( 'quiz_statistic_ref' );
			$offset      = ( (int) $args['page'] - 1 ) * (int) $args['limit'];
			$condition   = 'is_old=0';
			if ( isset( $args['from'] ) && ! empty( $args['from'] ) ) {
				$condition .= sprintf( ' AND create_time>=%d', $args['from'] );
			}
			if ( isset( $args['to'] ) && ! empty( $args['to'] ) ) {
				$condition .= sprintf( ' AND create_time<=%d', $args['to'] );
			}
			if ( ! $args['exclusive'] ) {
				if ( isset( $args['user_ids'] ) && ! empty( $args['user_ids'] ) ) {
					$user_ids_string = implode( ',', $args['user_ids'] );
					$condition      .= sprintf( ' AND user_id IN (%s)', $user_ids_string );
				} elseif ( isset( $args['not_include_users'] ) && ! empty( $args['not_include_users'] ) ) {
					$user_ids_string = implode( ',', $args['not_include_users'] );
					$condition      .= sprintf( ' AND user_id NOT IN (%s)', $user_ids_string );
				}
				if ( isset( $args['quiz_ids'] ) && ! empty( $args['quiz_ids'] ) ) {
					$quiz_ids_string = implode( ',', $args['quiz_ids'] );
					$condition      .= sprintf( ' AND quiz_id IN (%s)', $quiz_ids_string );
				} elseif ( isset( $args['not_include_course'] ) && ! empty( $args['not_include_course'] ) ) {
					$quiz_ids_string = implode( ',', $args['not_include_course'] );
					$condition      .= sprintf( ' AND course_post_id NOT IN (%s)', $quiz_ids_string );
				}
			} else {
				$users   = '""';
				$quizzes = '""';
				if ( isset( $args['user_ids'] ) && ! empty( $args['user_ids'] ) ) {
					$user_ids_string = implode( ',', $args['user_ids'] );
					$users           = $user_ids_string;
				}
				if ( isset( $args['quiz_ids'] ) && ! empty( $args['quiz_ids'] ) ) {
					$quiz_ids_string = implode( ',', $args['quiz_ids'] );
					$quizzes         = $quiz_ids_string;
				}
				$condition .= sprintf( ' AND ( user_id IN (%1$s) OR quiz_id IN (%2$s) )', $users, $quizzes );
			}
			$query  = $db_instance->prepare( "SELECT * FROM {$table_name} WHERE {$condition} ORDER BY statistic_ref_id DESC LIMIT %d OFFSET %d", $args['limit'], $offset );
			$result = $db_instance->get_results( $query, ARRAY_A );

			/**
			 *
			 * Statistics Reference IDs.
			 *
			 * Returns all statistics reference IDs.
			 *
			 * @since 3.0.0
			 *
			 * @param array  $result    All Statistics Reference IDs.
			 * @param array  $args      Query Args. Params are array of 'user_ids' , array of 'quiz_ids', Timestamp 'from', timestamp 'to').
			 */
			return apply_filters( 'get_all_statistic_ref_ids', $result, $args );
		}

		/**
		 * Gets all Statistics reference IDs.
		 *
		 * @param array $args Query Args. Params are array of 'user_ids' , array of 'quiz_ids', Timestamp 'from', timestamp 'to').
		 *
		 * @return array $statistics Array of Statistic Reference IDs.
		 */
		public function get_all_statistic_ref_ids_count( $args = array(
			'user_ids'  => array(),
			'quiz_ids'  => array(),
			'exclusive' => false,
		) ) {
			$default = array(
				'user_ids'  => array(),
				'quiz_ids'  => array(),
				'exclusive' => false,
			);
			$args    = wp_parse_args( $args, $default );

			$args = apply_filters( 'wrld_get_all_statistic_ref_ids_count_args', $args );

			$db_instance = $this->get_db_instance();
			$table_name  = $this->get_db_name( 'quiz_statistic_ref' );
			$condition   = 'is_old=0';
			if ( isset( $args['from'] ) && ! empty( $args['from'] ) ) {
				$condition .= sprintf( ' AND create_time>=%d', $args['from'] );
			}
			if ( isset( $args['to'] ) && ! empty( $args['to'] ) ) {
				$condition .= sprintf( ' AND create_time<=%d', $args['to'] );
			}
			if ( ! $args['exclusive'] ) {
				if ( isset( $args['user_ids'] ) && ! empty( $args['user_ids'] ) ) {
					$user_ids_string = implode( ',', $args['user_ids'] );
					$condition      .= sprintf( ' AND user_id IN (%s)', $user_ids_string );
				} elseif ( isset( $args['not_include_users'] ) && ! empty( $args['not_include_users'] ) ) {
					$user_ids_string = implode( ',', $args['not_include_users'] );
					$condition      .= sprintf( ' AND user_id NOT IN (%s)', $user_ids_string );
				}
				if ( isset( $args['quiz_ids'] ) && ! empty( $args['quiz_ids'] ) ) {
					$quiz_ids_string = implode( ',', $args['quiz_ids'] );
					$condition      .= sprintf( ' AND quiz_id IN (%s)', $quiz_ids_string );
				} elseif ( isset( $args['not_include_course'] ) && ! empty( $args['not_include_course'] ) ) {
					$quiz_ids_string = implode( ',', $args['not_include_course'] );
					$condition      .= sprintf( ' AND course_post_id NOT IN (%s)', $quiz_ids_string );
				}
			} else {
				$users   = '""';
				$quizzes = '""';
				if ( isset( $args['user_ids'] ) && ! empty( $args['user_ids'] ) ) {
					$user_ids_string = implode( ',', $args['user_ids'] );
					$users           = $user_ids_string;
				}
				if ( isset( $args['quiz_ids'] ) && ! empty( $args['quiz_ids'] ) ) {
					$quiz_ids_string = implode( ',', $args['quiz_ids'] );
					$quizzes         = $quiz_ids_string;
				}
				$condition .= sprintf( ' AND ( user_id IN (%1$s) OR quiz_id IN (%2$s) )', $users, $quizzes );
			}
			$query  = "SELECT COUNT(*) FROM {$table_name} WHERE {$condition}";
			$result = $db_instance->get_var( $query );

			/**
			 *
			 * Count of Statistics Reference IDs.
			 *
			 * Returns Count ofo all statistics reference IDs.
			 *
			 * @since 3.0.0
			 *
			 * @param array  $result    All Statistics Reference IDs Count.
			 * @param array  $args      Query Args. Params are array of 'user_ids' , array of 'quiz_ids', Timestamp 'from', timestamp 'to').
			 */
			return apply_filters( 'get_all_statistic_ref_ids_count', $result, $args );
		}

		/**
		 * Gets all data related to the quiz attempt and quiz.
		 *
		 * @param  integer $ref_id Statistics Reference ID.
		 * @return array $result Array of all data associated with the quiz attempt.
		 * @throws Exception If a user tries an unauthorized SQL operation.
		 */
		public function get_quiz_attempt_data( $ref_id ) {
			$db_instance = $this->get_db_instance();

			$statistic_ref_table = $this->get_db_name( 'quiz_statistic_ref' );
			$question_table      = $this->get_db_name( 'quiz_question' );
			$statistic_table     = $this->get_db_name( 'quiz_statistic' );

			$query = $db_instance->prepare( "SELECT qsr.statistic_ref_id, qsr.quiz_id, qsr.user_id, qq.question, qq.points, qq.answer_type, qq.answer_data, qq.sort col_sort, qs.points qspoints, qs.answer_data qsanswer_data, qs.question_time, qs.question_id FROM {$statistic_ref_table} qsr,  {$question_table} qq, {$statistic_table} qs WHERE qsr.statistic_ref_id = qs.statistic_ref_id AND qq.id=qs.question_id AND qsr.statistic_ref_id IN (%d) ORDER BY qsr.statistic_ref_id DESC, col_sort ASC; ", $ref_id );

			if ( preg_match( '[update|delete|drop|alter]', strtolower( $query ) ) === true ) {
				throw new Exception( 'No cheating' );
			}

			/**
			 *
			 * Quiz Attempt Data.
			 *
			 * Returns Quiz Attempt data.
			 *
			 * @since 3.0.0
			 *
			 * @param array             Quiz Attempt data.
			 * @param integer $ref_id   Statistic Reference ID.
			 */
			$result = remove_empty_array_items( apply_filters( 'qre_quiz_attempt_data', $db_instance->get_results( $query, ARRAY_A ), $ref_id ) );
			$db_instance->flush();

			return $result;
		}

		/**
		 * Get Summarized statistic Data for Quiz.
		 *
		 * @param  integer $statistic_ref_id Statistic Ref ID.
		 * @return array  Results array.
		 */
		public function get_statistic_summarized_data( $statistic_ref_id ) {
			$db_instance = $this->get_db_instance();

			$statistic_ref_table = $this->get_db_name( 'quiz_statistic_ref' );
			$question_table      = $this->get_db_name( 'quiz_question' );
			$statistic_table     = $this->get_db_name( 'quiz_statistic' );

			$query   = $db_instance->prepare( "SELECT SUM(qq.points) AS gpoints, SUM(qs.points) AS points, SUM(qs.question_time) AS question_time, SUM(qs.correct_count) AS correct_count, SUM(qs.incorrect_count) AS incorrect_count, qsr.user_id, qsr.quiz_id, qsr.statistic_ref_id, qsr.create_time FROM {$statistic_ref_table} qsr INNER JOIN {$statistic_table} qs ON qsr.statistic_ref_id = qs.statistic_ref_id INNER JOIN {$question_table} qq ON qq.id=qs.question_id WHERE qsr.statistic_ref_id IN (%d) ORDER BY qsr.statistic_ref_id DESC", $statistic_ref_id );
			$results = $db_instance->get_results( $query, ARRAY_A );
			$results = current( $results );
			/**
			 * Get Summarized data about a statistic i.e., total points, correct/incorrect count etc.
			 *
			 * @param array   $results          Summarized data
			 * @param integer $statistic_ref_id Statistic Ref ID.
			 */
			return apply_filters( 'get_statistic_summarized_data', $results, $statistic_ref_id );
		}

		/**
		 * This method is used to fetch each user's total and earned points when attempting a particular quiz.
		 *
		 * @param  integer $quiz_id Quiz Pro ID.
		 * @return array   Class Points Earned.
		 */
		public function get_users_total_points( $quiz_id ) {
			$db_instance = $this->get_db_instance();

			$statistic_ref_table = $this->get_db_name( 'quiz_statistic_ref' );
			$question_table      = $this->get_db_name( 'quiz_question' );
			$statistic_table     = $this->get_db_name( 'quiz_statistic' );

			$query   = $db_instance->prepare( "SELECT qsr.user_id, SUM(qq.points) AS gpoints, SUM(qs.points) AS points FROM {$statistic_ref_table} qsr INNER JOIN {$statistic_table} qs ON qsr.statistic_ref_id = qs.statistic_ref_id INNER JOIN {$question_table} qq ON qq.id=qs.question_id WHERE qsr.quiz_id=%d GROUP BY qsr.user_id ORDER BY qsr.user_id DESC", $quiz_id );
			$results = $db_instance->get_results( $query, ARRAY_A );
			/**
			 * Get total points and earned points by user.
			 *
			 * @param array   $results          Summarized data
			 * @param integer $statistic_ref_id Statistic Ref ID.
			 * @param integer $quiz_id          Quiz Pro ID.
			 */
			return apply_filters( 'get_user_total_points', $results, $quiz_id );
		}

		/**
		 * To get form questions data of custom field by quiz id
		 *
		 * @param  number $quiz_id quiz id.
		 * @return array $custom_form_data custom form field questions
		 */
		public function get_custom_field_form_questions( $quiz_id ) {
			$db_instance = $this->get_db_instance();

			$table_name = $this->get_db_name( 'quiz_form' );

			$custom_form_query = $db_instance->prepare( "SELECT form_id, fieldname, type, data FROM {$table_name} WHERE quiz_id=%d ORDER BY sort ASC;", $quiz_id );

			$custom_form_data = $db_instance->get_results( $custom_form_query, ARRAY_A );
			$db_instance->flush();

			/**
			 *
			 * Custom Form Questions.
			 *
			 * Returns Custom form questions.
			 *
			 * @since 3.0.0
			 *
			 * @param array   $custom_form_data Custom Form Questions.
			 * @param integer $quiz_id          Quiz ID.
			 */
			return apply_filters( 'qre_custom_field_form_questions', $custom_form_data, $quiz_id );
		}

		/**
		 * To return custom field's answers.
		 *
		 * @param  integer $ref_id  statistics ref id.
		 * @param  integer $quiz_id quiz id.
		 * @param  integer $user_id user id of user.
		 * @return array   $custom_answers answers of user.
		 */
		public function get_custom_field_form_answers( $ref_id, $quiz_id, $user_id ) {
			$db_instance = $this->get_db_instance();

			$table_name = $this->get_db_name( 'quiz_statistic_ref' );

			$custom_query   = $db_instance->prepare( "SELECT form_data FROM {$table_name} WHERE statistic_ref_id=%d AND quiz_id=%d AND user_id=%d;", $ref_id, $quiz_id, $user_id );
			$custom_answers = maybe_unserialize( $db_instance->get_var( $custom_query ) );
			$db_instance->flush();

			if ( '' !== $custom_answers ) {
				$custom_answers = json_decode( $custom_answers, 1 );
			}

			/**
			 *
			 * Custom Form Answers.
			 *
			 * Returns Custom form field answers.
			 *
			 * @since 3.0.0
			 *
			 * @param array   $custom_answers  Custom Form Answers.
			 * @param integer $ref_id          Statistics Reference ID.
			 * @param integer $quiz_id         Quiz ID.
			 * @param integer $user_id         User ID.
			 */
			return apply_filters( 'qre_custom_field_form_answers', $custom_answers, $ref_id, $quiz_id, $user_id );
		}

		/**
		 * Returns a quiz title of quiz
		 *
		 * @param  int $quiz_id Quiz ID.
		 * @return string MD5 Checksum
		 */
		public function get_quiz_title( $quiz_id ) {
			$db_instance = $this->get_db_instance();

			$table_name = $this->get_db_name( 'quiz_master' );

			$quiz_query = $db_instance->prepare( "SELECT `name` FROM {$table_name} WHERE id=%d", $quiz_id );

			$quiz_name = $db_instance->get_var( $quiz_query );

			/**
			 *
			 * Quiz Title.
			 *
			 * Returns Quiz Name from quiz_master table.
			 *
			 * @since 3.0.0
			 *
			 * @param string  $quiz_name  Quiz Title.
			 * @param integer $quiz_id    Quiz ID.
			 */
			return apply_filters( 'qre_quiz_title', $quiz_name, $quiz_id );
		}

		/**
		 * Check for permanently deleted quizzes.
		 *
		 * @param  string $quiz_ids Quiz IDs to check.
		 * @return array  $quiz_ids_present Quiz IDs present.
		 */
		public function check_if_quiz_ids_actually_present( $quiz_ids ) {
			$db_instance = $this->get_db_instance();

			$table_name = $db_instance->prefix . 'posts';

			$quiz_qry = $db_instance->prepare( "SELECT ID FROM {$table_name} where ID IN (%s)", $quiz_ids );

			$quiz_ids_present = $db_instance->get_col( $quiz_qry );

			return $quiz_ids_present;
		}

		/**
		 * Check for permanently deleted users.
		 *
		 * @param  string $user_ids User IDs to check.
		 * @return array  $user_ids_present Quiz IDs present.
		 */
		public function check_if_user_ids_actually_present( $user_ids ) {
			$db_instance = $this->get_db_instance();

			$table_name = $db_instance->prefix . 'users';

			$user_qry = $db_instance->prepare( "SELECT ID FROM {$table_name} where ID IN (%s)", $user_ids );

			$user_ids_present = $db_instance->get_col( $user_qry );

			return $user_ids_present;
		}

		/**
		 * Check for deleted/reset quiz statistics.
		 *
		 * @param  string $statistics_ids Statistic IDs to check.
		 * @return array  $ids_present    Statistic IDs present.
		 */
		public function check_if_statistics_actually_present( $statistics_ids ) {
			$db_instance = $this->get_db_instance();

			$table_name = $this->get_db_name( 'quiz_statistic_ref' );

			$check_qry = $db_instance->prepare( "SELECT statistic_ref_id FROM {$table_name} where statistic_ref_id IN (%s)", $statistics_ids );

			$ids_present = $db_instance->get_col( $check_qry );

			return $ids_present;
		}

		/**
		 * Get Statistic Ref ID of a particular attempt.
		 *
		 * @param  integer $user_id     User ID.
		 * @param  integer $pro_quiz_id Pro Quiz ID.
		 * @param  integer $time        Timestamp of the time the quiz was attempted.
		 * @return array   $result      Query Results.
		 */
		public function get_statistic_ref_id( $user_id, $pro_quiz_id, $time ) {
			$db_instance = $this->get_db_instance();

			$table_name = $this->get_db_name( 'quiz_statistic_ref' );

			$query = $db_instance->prepare( "SELECT statistic_ref_id FROM {$table_name} where user_id = %1$d AND quiz_id = %2$d AND create_time = %3$d ORDER BY quiz_id DESC", $user_id, $pro_quiz_id, $time );

			$result = $db_instance->get_results( $query );

			/**
			 *
			 * Statistic Reference ID.
			 *
			 * Returns Statistics Reference ID of a particular attempt.
			 *
			 * @since 3.0.0
			 *
			 * @param array   $result      Statistic Reference ID.
			 * @param integer $user_id     User ID.
			 * @param integer $pro_quiz_id Pro Quiz ID.
			 * @param integer $time        Timestamp of attempt.
			 */
			return apply_filters( 'qre_statistic_ref_id', $result, $user_id, $pro_quiz_id, $time );
		}

		/**
		 * This method returns the total question count for the quiz.
		 *
		 * @param  integer $statistics_id Statistic ID.
		 * @return array   $qcount        Question Count.
		 */
		public function get_total_questions_count( $statistics_id ) {
			$db_instance = $this->get_db_instance();

			$table_name = $this->get_db_name( 'quiz_statistic' );

			$query = $db_instance->prepare( "SELECT count(*) as count FROM {$table_name} WHERE statistic_ref_id = %d", $statistics_id );

			$qcount = $db_instance->get_results( $query, OBJECT );

			/**
			 *
			 * Total Questions Count.
			 *
			 * Returns the number of questions in the quiz.
			 *
			 * @since 3.0.0
			 *
			 * @param integer  $qcount         Question Count.
			 * @param integer  $statistics_id  Statististics Reference ID.
			 */
			return apply_filters( 'qre_total_questions_count', $qcount, $statistics_id );
		}

		/**
		 * This method returns the correct question count for the quiz.
		 *
		 * @param  integer $statistics_id Statistic ID.
		 * @return array   $qscore        Question Count.
		 */
		public function get_correct_questions_count( $statistics_id ) {
			$db_instance = $this->get_db_instance();

			$table_name = $this->get_db_name( 'quiz_statistic' );

			$query = $db_instance->prepare( "SELECT count(correct_count) as score FROM {$table_name} WHERE correct_count = 1 AND statistic_ref_id = %d", $statistics_id );

			$qscore = $db_instance->get_results( $query, OBJECT );

			/**
			 *
			 * Correct Questions Count.
			 *
			 * Returns the number of correct questions attempted.
			 *
			 * @since 3.0.0
			 *
			 * @param integer  $qscore         Correct Question Count.
			 * @param integer  $statistics_id  Statististics Reference ID.
			 */
			return apply_filters( 'qre_correct_questions_count', $qscore, $statistics_id );
		}

		/**
		 * This method returns the total points for the quiz attempt.
		 *
		 * @param  integer $statistics_id Statistic ID.
		 * @return array   $qpoints       Points Earned.
		 */
		public function get_points_earned( $statistics_id ) {
			$db_instance = $this->get_db_instance();

			$table_name = $this->get_db_name( 'quiz_statistic' );

			$query = $db_instance->prepare( "SELECT SUM(points) as points FROM {$table_name} WHERE statistic_ref_id = %d", $statistics_id );

			$qpoints = $db_instance->get_results( $query, OBJECT );

			/**
			 *
			 * Points Earned.
			 *
			 * Returns the points earned.
			 *
			 * @since 3.0.0
			 *
			 * @param integer  $qpoints        Points Earned.
			 * @param integer  $statistics_id  Statististics Reference ID.
			 */
			return apply_filters( 'qre_points_earned', $qpoints, $statistics_id );
		}

		/**
		 * This method returns all of the questions asked for the quiz attempt.
		 *
		 * @param  integer $statistics_id Statistic ID.
		 * @return array   $qids          Questions Asked.
		 */
		public function get_questions_asked( $statistics_id ) {
			$db_instance = $this->get_db_instance();

			$table_name = $this->get_db_name( 'quiz_statistic' );

			$query = $db_instance->prepare( "SELECT question_id FROM {$table_name} WHERE statistic_ref_id = %d", $statistics_id );

			$qids = $db_instance->get_results( $query, OBJECT );

			/**
			 *
			 * Questions Asked.
			 *
			 * Returns the list of questions asked for a particular quiz attempt.
			 *
			 * @since 3.0.0
			 *
			 * @param array    $qids           Question IDs.
			 * @param integer  $statistics_id  Statististics Reference ID.
			 */
			return apply_filters( 'qre_questions_attempted', $qids, $statistics_id );
		}

		/**
		 * This method returns the total time taken for the quiz attempt.
		 *
		 * @param  integer $statistics_id Statistic ID.
		 * @return array   $qtime         Time Taken.
		 */
		public function get_quiz_time_taken( $statistics_id ) {
			$db_instance = $this->get_db_instance();

			$table_name = $this->get_db_name( 'quiz_statistic' );

			$query = $db_instance->prepare( "SELECT SUM(question_time) as quet FROM {$table_name} WHERE statistic_ref_id = %d", $statistics_id );

			$qtime = $db_instance->get_results( $query, OBJECT );

			/**
			 *
			 * Quiz Time Taken.
			 *
			 * Returns the time taken by the user for a particular quiz attempt.
			 *
			 * @since 3.0.0
			 *
			 * @param integer  $qtime          Question Time taken.
			 * @param integer  $statistics_id  Statististics Reference ID.
			 */
			return apply_filters( 'qre_quiz_time_taken', $qtime, $statistics_id );
		}

		/**
		 * This method returns the total points that can be earned for the quiz attempt.
		 *
		 * @param  array $questions     Quiz Questions.
		 * @return integer $total_points  Total Points.
		 */
		public function get_quiz_total_points( $questions ) {
			$db_instance = $this->get_db_instance();

			$total_points = 0;
			$table_name   = $this->get_db_name( 'quiz_question' );

			foreach ( $questions as $question ) {
				$query        = $db_instance->prepare( "SELECT points FROM {$table_name} WHERE id = %d", $question->question_id );
				$qpoint       = $db_instance->get_results( $query, OBJECT );
				$total_points = $total_points + $qpoint[0]->points;
			}

			/**
			 *
			 * Quiz Points.
			 *
			 * Returns the total points for a particular quiz attempt.
			 *
			 * @since 3.0.0
			 *
			 * @param integer  $total_points   Total Points.
			 * @param integer  $questions      Statististics Reference ID.
			 */
			return apply_filters( 'qre_quiz_total_points', $total_points, $questions );
		}

		/**
		 * Used to get LearnDash table name.
		 *
		 * @param  string $table Table slug.
		 * @param string $context Table type.
		 *
		 * @return string $table Table name based on prefix setting in Data Upgrades(LD).
		 */
		private function get_db_name( $table, $context = 'wpproquiz' ) {
			/**
			 * All LD Table slugs.
			 * 'quiz_category', 'quiz_form', 'quiz_lock', 'quiz_master', 'quiz_prerequisite', 'quiz_question', 'quiz_statistic', 'quiz_statistic_ref', 'quiz_template', 'quiz_toplist'
			 */
			return LDLMS_DB::get_table_name( $table, $context );
		}

		/**
		 * Get published posts contained within specific IDS.
		 *
		 * @param string $post_type Post Type.
		 * @param array  $post_ids  Post IDs.
		 *
		 * @return array $post_ids  Ids of Posts.
		 */
		public function get_posts_within_ids( $post_type, $post_ids = array() ) {
			$db_instance = $this->get_db_instance();

			$table_name         = $db_instance->posts;
			$post_ids_condition = 1;

			if ( ! empty( $post_ids ) ) {
				$post_ids_condition = 'ID IN (' . implode( ',', $post_ids ) . ')';
			}

			$query = $db_instance->prepare( "SELECT ID from {$table_name} WHERE post_type=%s AND {$post_ids_condition} AND post_status IN ('publish', 'private', 'protected')", $post_type );
			$posts = $db_instance->get_results( $query, ARRAY_A );
			/**
			 *
			 * Result Posts.
			 *
			 * Get published posts contained within specific IDS.
			 *
			 * @since 3.0.0
			 *
			 * @param array  $posts     Result Posts.
			 * @param string $post_type Post Type.
			 * @param array  $post_ids  Post IDs to filter from.
			 */
			return apply_filters( 'qre_get_posts_within_ids', $posts, $post_type, $post_ids );
		}
	}
}
