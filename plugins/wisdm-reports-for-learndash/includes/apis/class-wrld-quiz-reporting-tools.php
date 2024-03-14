<?php
/**
 * This file contains a class that is used to setup the LearnDash endpoints
 *
 * @package learndash-reports-by-wisdmlabs
 */

require_once 'class-wrld-common-functions.php';

/**
 * Class that sets up all the LearnDash endpoints
 *
 * @since      1.0.0
 */
class WRLD_Quiz_Reporting_Tools extends WRLD_Common_Functions {

	/**
	 * This static contains the number of points being assigned on course completion
	 *
	 * @var    Instance of WRLD_Quiz_Reporting_Tools class
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

	public function wrld_get_question_details() {
		$question_data = $_REQUEST['question_data'];
		$question_no   = $_REQUEST['q_no'];
		$question      = json_decode( stripslashes( $question_data ) );
		do_action( 'wpml_switch_language',  $_GET[ 'wpml_lang' ] ); // switch the content language
		ob_start();
		$questions     = \Quiz_Export_Data::instance()->get_statistics_data( $question->quiz_id, $question->user_id, $question->statistic_ref_id, false );
		$questions_key = array();
		if ( ! empty( $questions ) ) {
			$questions_key = array_keys( $questions );
		}
		if ( ! empty( $questions_key ) ) {
			$counter = 0;
			foreach ( $questions_key as $key ) {
				foreach ( $questions[ $key ]['questions'] as $value ) {
					$question_id = $value['question_id'];
					if ( $question_id != $question->question_id ) {
						continue;
					}
					$question_text = ! empty( $value['questionName'] ) ? $value['questionName'] : Quiz_Export_Data::instance()->get_question_text( $question->quiz_id, $question_id );
					$cmsg          = Quiz_Export_Data::instance()->get_correct_message( $question_id, $question->quiz_id );
					echo Quiz_Reporting_Frontend::instance()->show_user_answer( $question->question, $value['questionAnswerData'], $value['statistcAnswerData'], $value['answerType'], $question_no, $value['correct'], $cmsg );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
			}
		}
		// $cmsg = Quiz_Export_Data::instance()->get_correct_message( learndash_get_question_post_by_pro_id( $question->question_id ), $question->question_id );
		// echo Quiz_Reporting_Frontend::instance()->show_user_answer( $question->question, maybe_unserialize( $question->answer_data ), json_decode( $question->qsanswer_data, 1 ), $question->answer_type, 1, true, $cmsg );// phpcs:ignore
		$output = ob_get_clean();
		return new WP_REST_Response(
			array(
				'requestData' => self::get_values_for_request_params( $request_data ),
				'table'       => $output,
			),
			200
		);
		// $this->display_question_options( $question, $key+1 );
	}

	public function wrld_get_student_dashboard_results() {
		$request_data = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING );
		$request_data = self::get_request_params( $request_data );
		$learner      = $request_data['user_id'];
		$start_date   = $request_data['start_date'];
		$end_date     = $request_data['end_date'];
		$course_id    = $request_data['course_id'];
		$quiz_id      = $request_data['quiz_id'];
		$quiz_pro_id  = get_post_meta( $quiz_id, 'quiz_pro_id', true );
		do_action( 'wpml_switch_language',  $_GET[ 'wpml_lang' ] ); // switch the content language

		$page = empty( $request_data['page'] ) ? 1 : $request_data['page'];

		$data = \WRLD_Quiz_Export_Db::instance()->get_learner_quiz_activity( $start_date, $end_date, $learner, $course_id, $quiz_pro_id, $page );
		global $wp;
		$referer      = urlencode( get_permalink() );
		$query_string = filter_input( INPUT_SERVER, 'QUERY_STRING', FILTER_SANITIZE_STRING );
		if ( ! empty( $query_string ) ) {
			$referer = urlencode( remove_query_arg( 'referer', add_query_arg( $query_string, '', home_url( $wp->request ) ) ) );
		}
		if ( ! empty( $data ) ) {
			foreach ( $data as &$statistic ) {
				$extradot                   = strlen( substr( strip_tags( get_the_title( $statistic['quiz_post_id'] ) ), 0, 100 ) ) == 100 ? ' ...' : '';
				$statistic['quiz_title']    = "<a target='_blank' style='display:flex;text-decoration: none;' href='" . add_query_arg(
					array(
						'report'         => 'quiz',
						'screen'         => 'quiz',
						'user'           => $statistic['user_id'],
						'quiz'           => $statistic['quiz_id'],
						'statistic'      => $statistic['statistic_ref_id'],
						'dashboard'      => 'student',
						'ld_report_type' => 'quiz-reports',
					),
					''
				) . "'>" . substr( strip_tags( get_the_title( $statistic['quiz_post_id'] ) ), 0, 100 ) . $extradot . ' <span style="margin-left:3px;font-size:15px;text-decoration:none;"  class="dashicons dashicons-external"></span></a>';
				$statistic['quiz_category'] = '-';
				if ( taxonomy_exists( 'ld_quiz_category' ) ) {
					$statistic['quiz_category'] = wp_get_post_terms( $statistic['quiz_post_id'], 'ld_quiz_category', array( 'fields' => 'names' ) );
					if ( ! is_wp_error( $statistic['quiz_category'] ) ) {
						$statistic['quiz_category'] = implode( ', ', $statistic['quiz_category'] );
					}
				}
				$statistic['course_title'] = get_the_title( $statistic['course_post_id'] );
				$csv                       = '<a href="#" data-ref_id="' . $statistic['statistic_ref_id'] . '" class="qre-export qre-download-csv"><img src="' . LDRP_PLUGIN_URL . 'assets/public/images/csv.svg"></a>';
				$xlsx                      = '<a href="#" data-ref_id="' . $statistic['statistic_ref_id'] . '" class="qre-export qre-download-xlsx"><img src="' . LDRP_PLUGIN_URL . 'assets/public/images/xls.svg"></a>';
				// $form = '<form id="qre_exp_form_f6b9cdd180" method="post" action="" target="_blank" style="display:none;"><input name="file_format" type="hidden" value="csv"><input name="ref_id" type="hidden" value="41"><input name="quiz_export_nonce" type="hidden" value="f6b9cdd180"></form>';
				$statistic['links']        = $csv . $xlsx;
				$statistic['date_attempt'] = date_i18n( get_option( 'date_format', 'd-M-Y' ), $statistic['create_time'] );
				/*
				 translators: %1$d: Points Earned, %2$d: Total Points */
				// $statistic['score'] = sprintf( __( '%1$d of %2$d', 'learndash-reports-by-wisdmlabs' ), $data['points'], $data['gpoints'] );

				// $dt_current         = new \DateTime( '@0' );
				// $dt_after_seconds   = new \DateTime( '@' . (int) $data['question_time'] );
				// $data['time_taken'] = $dt_current->diff( $dt_after_seconds )->format( '%H:%I:%S' );
				$statistic['questions'] = \WRLD_Quiz_Export_Db::instance()->get_quiz_attempt_data( $statistic['statistic_ref_id'] );
				$summarized_data        = \WRLD_Quiz_Export_Db::instance()->get_statistic_summarized_data( $statistic['statistic_ref_id'] );
				$percentage             = (int) $summarized_data['points'] / (int) $summarized_data['gpoints'] * 100;
				$quiz_post_settings     = learndash_get_setting( $statistic['quiz_post_id'] );
				if ( ! is_array( $quiz_post_settings ) ) {
					$quiz_post_settings = array();
				}
				if ( ! isset( $quiz_post_settings['passingpercentage'] ) ) {
					$quiz_post_settings['passingpercentage'] = 0;
				}

				$statistic['percentage'] = $percentage;
				$passingpercentage       = floatval( number_format( $quiz_post_settings['passingpercentage'], 2, '.', '' ) );
				$percentage              = floatval( number_format( $percentage, 2, '.', '' ) );
				$passing_status          = ( ( $percentage >= $passingpercentage ) ? __( 'Pass', 'learndash-reports-by-wisdmlabs' ) : __( 'Fail', 'learndash-reports-by-wisdmlabs' ) );
				$statistic['quiz_title'] = $statistic['quiz_title'] . '<span class="passing-status ' . strtolower( $passing_status ) . '">' . $passing_status . '</span>';
				if ( ! empty( $statistic['questions'] ) ) {
					foreach ( $statistic['questions'] as $key => $value ) {
						$qre_answer_data     = maybe_unserialize( $value['answer_data'] );
						$qre_user_response   = json_decode( $value['qsanswer_data'], 1 );
						$question_type       = $value['answer_type'];
						$qre_question_id     = $value['question_id'];
						$cur_user_id         = $value['user_id'];
						$is_attach_question  = false; // if want to attache answer to question. For cloze questions.
						$qre_answer          = '';
						$arr_user_response   = array();
						$arr_answers         = array();
						$arr_correct_answers = array();

						switch ( $question_type ) {
							// if $question_type is single OR multiple.
							case 'single':
							case 'multiple':
								$switch_true         = true;
								$res_object          = new Qre_Single_Question_Data( $qre_answer_data, $qre_user_response );
								$arr_correct_answers = $res_object->get_correct_answers();
								$arr_user_response   = $res_object->get_user_answers();
								$arr_answers         = $res_object->get_all_answers();
								break;

							case 'free_answer':
								$switch_true         = true;
								$res_object          = new Qre_Free_Question_Data( $qre_answer_data, $qre_user_response );
								$arr_correct_answers = $res_object->get_correct_answers();
								$arr_user_response   = $res_object->get_user_answers();
								$arr_answers         = $res_object->get_all_answers();
								break;

							case 'sort_answer':
								$switch_true         = true;
								$res_object          = new Qre_Sort_Question_Data( $qre_answer_data, $qre_question_id, $qre_user_response, $cur_user_id );
								$arr_correct_answers = $res_object->get_correct_answers();
								$arr_user_response   = $res_object->get_user_answers();
								$arr_answers         = $res_object->get_all_answers();
								break;

							case 'matrix_sort_answer':
								$switch_true         = true;
								$res_object          = new Qre_Matrix_Sort_Question_Data( $qre_answer_data, $qre_question_id, $qre_user_response, $cur_user_id );
								$arr_correct_answers = $res_object->get_correct_answers();
								$arr_user_response   = $res_object->get_user_answers();
								$arr_answers         = $res_object->get_all_answers();
								break;

							case 'cloze_answer':
								$switch_true         = true;
								$is_attach_question  = true;
								$res_object          = new Qre_Cloze_Question_Data( $qre_answer_data, $statistic['questions'][ $key ], $qre_user_response );
								$arr_correct_answers = $res_object->get_correct_answers();
								$arr_user_response   = $res_object->get_user_answers();
								$arr_answers         = $res_object->get_all_answers();
								$qre_answer          = $res_object->get_answer_obj();
								break;

							case 'assessment_answer':
								$switch_true         = true;
								$res_object          = new Qre_Assessment_Question_Data( $qre_answer_data, $qre_user_response );
								$arr_correct_answers = $res_object->get_correct_answers();
								$arr_user_response   = $res_object->get_user_answers();
								$arr_answers         = $res_object->get_all_answers();
								$qre_answer          = $res_object->get_answer_obj();
								break;

							case 'essay':
								$switch_true       = true;
								$res_object        = new Qre_Essay_Question_Data( $qre_user_response );
								$arr_user_response = $res_object->get_user_answers();
								break;
						}
						$statistic['questions'][ $key ]['answers']         = $arr_answers;
						$statistic['questions'][ $key ]['correct_answers'] = $arr_correct_answers;
						$statistic['questions'][ $key ]['user_response']   = $arr_user_response;
					}
				}
			}
		}
		$showing = ( ( (int) $page - 1 ) * 5 ) + count( $data );
		$total   = \WRLD_Quiz_Export_Db::instance()->get_learner_quiz_activity_count( $start_date, $end_date, $learner, $course_id, $quiz_pro_id );
		return new WP_REST_Response(
			array(
				'requestData' => self::get_values_for_request_params( $request_data ),
				'table'       => $data,
				'more_data'   => ( $total - $showing > 0 ) ? 'yes' : 'no',
				'total'       => $total,
				'showing'     => $showing,
			),
			200
		);
	}

	/**
	 * This method is a callback to the API endpint, 'qre-live-search', which searches the term
	 * entered in students & quizes & returns the results
	 *
	 * @return WP_REST_Response
	 */
	public function qre_live_search_results() {
		// Get Inputs.
		$search_term = sanitize_text_field( ! empty( $_GET['search_term'] ) ? $_GET['search_term'] : '' );// WPCS: sanitization ok, CSRF ok.

		if ( empty( $search_term ) ) {
			return new WP_Error(
				'no-data',
				sprintf(/* translators: %s: custom label for courses */
					__( 'No %s found', 'learndash-reports-by-wisdmlabs' ),
					\LearnDash_Custom_Label::get_label( 'courses' )
				)
			);
		}
		if ( strlen( $search_term ) < 3 ) {
			return new WP_Error( 'query-length', __( 'Search term is short enter atleast 3 letters', 'learndash-reports-by-wisdmlabs' ) );
		}
		do_action( 'wpml_switch_language',  $_GET[ 'wpml_lang' ] ); // switch the content language
		$results        = array();
		$user_results   = array();
		$quiz_results   = self::qre_search_quizzes( $search_term );
		$user_results   = self::qre_search_users( $search_term );
		$course_results = self::qre_search_courses( $search_term );
		$results        = array_merge( $user_results, $quiz_results );
		$results        = array_merge( $results, $course_results );

		return new WP_REST_Response(
			array(
				'search_results' => $results,
			),
			200
		);
	}


	/**
	 * Function is used to get Courses that match the queried string.
	 *
	 * @param  string  $query Search Query.
	 * @param  boolean $skip_search Whether to skip searching and fetch all results.
	 * @return array   $results Search Results
	 */
	public static function qre_search_courses( $query = '', $skip_search = false ) {
		if ( ! is_user_logged_in() ) {
			return array();
		}
		$excluded_courses = get_option( 'exclude_courses', false );
		do_action( 'wpml_switch_language',  $_GET['wpml_lang'] ); // switch the content language
		$args             = array(
			'post_type'      => 'sfwd-courses',
			'posts_per_page' => -1,
			'suppress_filters' => 0,
		);
		if ( ! empty( $excluded_courses ) ) {
			$args['exclude'] = $excluded_courses;
		}
		if ( false === $skip_search ) {
			$args['s'] = $query;
		}
		$courses = get_posts( $args );
		if ( empty( $courses ) ) {
			return $courses;
		}
		// Filter out items for which user doesn't have access.
		$courses = array_filter(
			$courses,
			function( $course ) {
				$current_user         = wp_get_current_user();
				$user_managed_courses = qre_get_user_managed_group_courses();

				$is_course_accessible = qre_check_if_course_accessible( $course->ID, $current_user, $user_managed_courses );
				if ( ! $is_course_accessible || is_wp_error( $is_course_accessible ) ) {
					return false;
				}
				return true;
			}
		);
		$results = array_map(
			function( $course ) use ( $query ) {
				$item = array(
					'post'  => $course,
					'title' => $course->post_title,
					'type'  => 'post',
					'ID'    => $course->ID,
					'icon'  => 'quiz_icon',
				);
				return $item;
			},
			$courses
		);
		return $results;
	}

	/**
	 * Function is used to get quizzes that match the queried string.
	 *
	 * @param  string  $query Search Query.
	 * @param  boolean $skip_search Whether to skip searching and fetch all results.
	 * @return array   $results Search Results
	 */
	public static function qre_search_quizzes( $query = '', $skip_search = false ) {
		if ( ! is_user_logged_in() ) {
			return array();
		}
		do_action( 'wpml_switch_language',  $_GET['wpml_lang'] ); // switch the content language
		$args = array(
			'post_type'      => 'sfwd-quiz',
			'posts_per_page' => -1,
			'suppress_filters' => 0,
		);
		if ( false === $skip_search ) {
			$args['s'] = $query;
		}
		$quizzes = get_posts( $args );
		if ( empty( $quizzes ) ) {
			return $quizzes;
		}
		// Filter out items for which user doesn't have access.
		$quizzes = array_filter(
			$quizzes,
			function( $quiz ) {
				$current_user         = wp_get_current_user();
				$user_managed_courses = self::get_user_managed_group_courses();

				$is_quiz_accessible = self::check_if_quiz_accessible( $quiz->ID, $current_user, $user_managed_courses );
				if ( ! $is_quiz_accessible || is_wp_error( $is_quiz_accessible ) ) {
					return false;
				}
				return true;
			}
		);
		$results = array_map(
			function( $quiz ) use ( $query ) {
				$item = array(
					'title' => $quiz->post_title,
					'type'  => 'quiz',
					'ID'    => $quiz->ID,
					'icon'  => 'quiz_icon',
				);
				return $item;
			},
			$quizzes
		);
		return $results;
	}

	/**
	 * Function is used to get users that match the queried string.
	 *
	 * @param  string  $search_string Search Query.
	 * @param  boolean $skip_search Whether to skip searching and fetch all results.[WARNING: Skipping search isn't recommended. For systems that have large number of users, the system may crash due to memory shortage].
	 * @return array   $results Search Results.
	 */
	public static function qre_search_users( $search_string = '', $skip_search = false ) {
		if ( ! is_user_logged_in() ) {
			return array();
		}
		$excluded_users = get_option( 'exclude_users', false );
		$excluded_ur    = get_option( 'exclude_ur', false );
		if ( false === $skip_search ) {
			$args = array(
				'search'         => "*{$search_string}*",
				'search_columns' => array(
					'user_login',
					'user_nicename',
					'user_email',
					'display_name',
				),
				'meta_query'     => array(// WPCS: slow query ok.
					'relation' => 'OR',
					array(
						'key'     => 'first_name',
						'value'   => $search_string,
						'compare' => 'LIKE',
					),
					array(
						'key'     => 'last_name',
						'value'   => $search_string,
						'compare' => 'LIKE',
					),
				),
			);
			if ( ! empty( $excluded_users ) && defined( 'LDRP_PLUGIN_VERSION' ) ) {
				$args['exclude'] = $excluded_users;
			}
			if ( ! empty( $excluded_ur ) && defined( 'LDRP_PLUGIN_VERSION' ) ) {
				$args['role__not_in'] = $excluded_ur;
			}
			$users       = new WP_User_Query( $args );
			$users_found = (array) $users->get_results();
		} else {
			if ( ! empty( $excluded_users ) && defined( 'LDRP_PLUGIN_VERSION' ) ) {
				$args['exclude'] = $excluded_users;
			}
			if ( ! empty( $excluded_ur ) && defined( 'LDRP_PLUGIN_VERSION' ) ) {
				$args['role__not_in'] = $excluded_ur;
			}
			$users_found = get_users( $args );
		}
		if ( empty( $users_found ) ) {
			return $users_found;
		}
		// Filter out items for which user doesn't have access.
		$users_found = array_filter(
			$users_found,
			function( $user ) {
				$current_user         = wp_get_current_user();
				$user_managed_courses = self::get_user_managed_group_courses();

				$is_user_accessible = self::check_if_user_accessible( $user->ID, $current_user, $user_managed_courses );
				if ( ! $is_user_accessible || is_wp_error( $is_user_accessible ) ) {
					return false;
				}
				return true;
			}
		);
		$results     = array_map(
			function( $user ) use ( $search_string ) {
				$item = array(
					'title' => $user->display_name,
					'type'  => 'user',
					'ID'    => $user->ID,
					'icon'  => '',
				);
				if ( empty( $search_string ) ) {
					return $item;
				}
				$item['icon'] = 'user_icon';
				return $item;
			},
			$users_found
		);
		return $results;
	}


	/**
	 * Get Courses of Groups where current user is the Group Leader.
	 *
	 * @return array User's Group affialiated Courses.
	 */
	public static function get_user_managed_group_courses() {
		global $group_leader_managed_courses;
		$courses = array();
		$user    = wp_get_current_user();
		if ( ! in_array( 'group_leader', (array) $user->roles ) ) {// phpcs:ignore
			return $courses;
		}

		if ( isset( $group_leader_managed_courses ) && ! empty( $group_leader_managed_courses ) ) {
			return $group_leader_managed_courses;
		}

		$associated_groups = learndash_get_administrators_group_ids( get_current_user_id() );

		if ( ! empty( $associated_groups ) ) {
			foreach ( $associated_groups as $associated_group ) {
				if ( empty( $courses ) ) {
					$courses = learndash_group_enrolled_courses( $associated_group );
				} else {
					$courses = array_merge( $courses, learndash_group_enrolled_courses( $associated_group ) );
				}
			}
		}
		$group_leader_managed_courses = $courses;
		return $courses;
	}


	/**
	 * This function is used to check whether the requested user info is accessible by the user.
	 *
	 * @param  integer $queried_obj_id       User ID.
	 * @param  WP_User $user                 User requesting resource.
	 * @param  array   $user_managed_courses User's Managed Courses(for Group Leaders).
	 * @return boolean/wp_error
	 */
	public static function check_if_user_accessible( $queried_obj_id, $user, $user_managed_courses ) {
		$user_id = self::check_if_user_ids_actually_present( $queried_obj_id );
		if ( empty( $user_id ) ) {
			return new \WP_Error( 400, __( 'Invalid Request. User ID doesn\'t exist.', 'quiz_reporting_learndash' ) );
		}
		// Check for Administrator.
		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}
		// Check for self Data.
		if ( (int) $queried_obj_id === $user->ID ) {
			return true;
		}
		// Check for Group Leader.
		if ( ! empty( $user_managed_courses ) ) {
			$user_managed_groups = learndash_get_administrators_group_ids( $user->ID );
			if ( ! empty( $user_managed_groups ) ) {
				foreach ( $user_managed_groups as $group_id ) {
					if ( in_array( $queried_obj_id, learndash_get_groups_user_ids( $group_id ) ) ) { // phpcs:ignore
						return true;
					}
				}
			}
		}
		// Check for Instructor.
		if ( function_exists( 'ir_get_instructor_complete_course_list' ) && in_array( 'wdm_instructor', (array) $user->roles, true ) ) {
			$instructor_course_ids    = ir_get_instructor_complete_course_list( $user->ID );
			$user_enrolled_course_ids = learndash_user_get_enrolled_courses( $queried_obj_id, array(), true );
			if ( ! empty( array_intersect( $instructor_course_ids, $user_enrolled_course_ids ) ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Check for permanently deleted users.
	 *
	 * @param  string $user_ids User IDs to check.
	 * @return array  $user_ids_present Quiz IDs present.
	 */
	public static function check_if_user_ids_actually_present( $user_ids ) {
			global $wpdb;

			$table_name       = $wpdb->prefix . 'users';
			$user_ids_present = $wpdb->get_col( $wpdb->prepare( 'SELECT ID FROM ' . $wpdb->prefix . 'users WHERE ID IN (%s)', $user_ids ) );// WPCS: db call ok, cache ok.
			return $user_ids_present;
	}


	/**
	 * This function is used to check whether the requested quiz is accessible by the user.
	 *
	 * @param  integer $queried_obj_id       Quiz ID.
	 * @param  WP_User $user                 User requesting resource.
	 * @param  array   $user_managed_courses User's Managed Courses(for Group Leaders).
	 * @return boolean/wp_error
	 */
	public static function check_if_quiz_accessible( $queried_obj_id, $user, $user_managed_courses ) {
		$quiz_id = \WRLD_Quiz_Export_Db::instance()->check_if_quiz_ids_actually_present( $queried_obj_id );
		if ( empty( $quiz_id ) ) {
			return new \WP_Error( 400, __( 'Invalid Request. Quiz ID doesn\'t exist.', 'quiz_reporting_learndash' ) );
		}
		// Check for Administrator.
		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}
		$queried_course_id = learndash_get_course_id( $queried_obj_id );
		// Check for Group Leader.
		if ( ! empty( $user_managed_courses ) ) {
			if ( in_array( $queried_course_id, $user_managed_courses ) ) {// phpcs:ignore
				return true;
			}
		}
		// Check for Instructor.
		if ( function_exists( 'ir_get_instructor_complete_course_list' ) && in_array( 'wdm_instructor', (array) $user->roles, true ) ) {
			$instructor_course_ids = ir_get_instructor_complete_course_list( $user->ID );
			if ( in_array( $queried_course_id, $instructor_course_ids ) ) {// phpcs:ignore
				return true;
			}
		}
		$user_enrolled_course_ids = learndash_user_get_enrolled_courses( $user->ID, array(), true );
		// Check for Subscriber.
		if ( in_array( $queried_course_id, $user_enrolled_course_ids ) ) {// phpcs:ignore
			return true;
		}
		// Check if stats exist regardless of course enrollment.
		$pro_quiz_id    = get_post_meta( $queried_obj_id, 'quiz_pro_id', true );
		$existing_stats = \WRLD_Quiz_Export_Db::instance()->get_all_statistic_ref_ids(
			array(
				'user_ids' => array( $user->ID ),
				'quiz_ids' => array( $pro_quiz_id ),
			)
		);
		if ( ! empty( $existing_stats ) ) {
			return true;
		}
		return false;
	}
}
