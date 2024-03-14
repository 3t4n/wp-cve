<?php

/**
 * LMSMigrationController Class.
 *
 * Handles the migration of data from other WordPress LMS plugins to Masteriyo.
 *
 * @since 1.8.0
 * @package Masteriyo\Addons\MigrationTool\Controllers
 */

namespace Masteriyo\Addons\MigrationTool\Controllers;

defined( 'ABSPATH' ) || exit;

use LDLMS_Factory_Post;
use Masteriyo\Enums\CommentType;
use Masteriyo\Enums\CourseAccessMode;
use Masteriyo\Enums\PostStatus;
use Masteriyo\Enums\QuestionType;
use Masteriyo\Helper\Permission;
use Masteriyo\PostType\PostType;
use Masteriyo\RestApi\Controllers\Version1\RestController;
use Masteriyo\Roles;
use WP_Error;
use WpProQuiz_Model_AnswerTypes;

/**
 * LMSMigrationController class.
 *
 * This class provides REST endpoints for migrating data from other LMS plugins to Masteriyo.
 */
class LMSMigrationController extends RestController {

	/**
	 * Endpoint namespace.
	 *
	 * @since 1.8.0
	 *
	 * @var string
	 */
	protected $namespace = 'masteriyo/v1';

	/**
	 * Route base.
	 *
	 * @since 1.8.0
	 *
	 * @var string
	 */
	protected $rest_base = 'migrations';

	/**
	 * Permission class.
	 *
	 * @since 1.8.0
	 *
	 * @var \Masteriyo\Helper\Permission;
	 */
	protected $permission = null;

	/**
	 * Constructor.
	 *
	 * @since 1.8.0
	 *
	 * @param Permission $permission
	 */
	public function __construct( Permission $permission ) {
		$this->permission = $permission;
	}

	/**
	 * Register routes.
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'migrate' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( \WP_REST_Server::CREATABLE ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/lms',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_other_LMSs' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
				),
			)
		);
	}

	/**
	 * Checks if the user has permission to import items.
	 *
	 * @since 1.8.0
	 *
	 * @param  \WP_REST_Request $request Full details about the request.
	 * @return \WP_Error|boolean
	 */
	public function create_item_permissions_check( $request ) {
		if ( is_null( $this->permission ) ) {
			return new \WP_Error(
				'masteriyo_null_permission',
				__( 'Sorry, the permission object for this resource is null.', 'masteriyo' )
			);
		}

		$instructor = masteriyo_get_current_instructor();
		if ( $instructor && ! $instructor->is_active() ) {
			return new \WP_Error(
				'masteriyo_rest_user_not_approved',
				__( 'Sorry, you are not approved by the manager.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		if ( ! $this->permission->rest_check_post_permissions( PostType::COURSE, 'create' ) ) {
			return new \WP_Error(
				'masteriyo_rest_cannot_create',
				__( 'Sorry, you are not allowed to import courses.', 'masteriyo' ),
				array(
					'status' => rest_authorization_required_code(),
				)
			);
		}

		return true;
	}

	/**
	 * Handles the migration of LMS data based on the request.
	 *
	 * @since 1.8.0
	 *
	 * @param WP_REST_Request $request The request object.
	 * @return WP_Error|WP_REST_Response The response or WP_Error on failure.
	 */
	public function migrate( $request ) {
		$lms_name      = sanitize_text_field( $request->get_param( 'lms_name' ) );
		$type          = sanitize_text_field( $request->get_param( 'type' ) );
		$remaining_ids = $request->get_param( 'ids' );

		if ( is_array( $remaining_ids ) ) {
			$remaining_ids = array_map( 'intval', $remaining_ids );
		} else {
			$remaining_ids = array();
		}

		if ( empty( $lms_name ) || ! array_key_exists( $lms_name, $this->get_other_LMS_list() ) ) {
			return new WP_Error( 'migration_invalid_parameters', 'Please select a valid LMS.', array( 'status' => 400 ) );
		}

		if ( empty( $type ) ) {
			$type = 'courses';
			update_option( 'masteriyo_remaining_migrated_items', 'not_started' );
		}

		switch ( $lms_name ) {
			case 'learnpress':
				return $this->migrate_lp_data( $type, $remaining_ids );
			case 'sfwd-lms':
				return $this->migrate_ld_data( $type, $remaining_ids );
			default:
				return new WP_Error( 'migration_not_supported', 'Migration for the selected LMS is not supported.', array( 'status' => 400 ) );
		}
	}

	/**
	 * Retrieves a list of other LMS plugins available for migration.
	 *
	 * @since 1.8.0
	 *
	 * @param WP_REST_Request $request The request object.
	 * @return WP_REST_Response The response object.
	 */
	public function get_other_LMSs( $request ) {
		$data = array();

		foreach ( $this->get_other_LMS_list() as  $key => $plugin ) {
			if ( in_array( $plugin['name'], get_option( 'active_plugins', array() ), true ) ) {
				$data[] = array(
					'name'  => $key,
					'label' => $plugin['label'],
				);
			}
		}

		return rest_ensure_response( array( 'data' => $data ) );
	}

	/**
	 * Retrieves other LMS plugins data.
	 *
	 * @since 1.8.0
	 *
	 * @return array An array of other LMS plugins data.
	 */
	private function get_other_LMS_list() {
		$data = array(
			'learnpress' => array(
				'label' => 'LearnPress',
				'name'  => 'learnpress/learnpress.php',
			),
			'sfwd-lms'   =>
			array(
				'label' => 'LearnDash',
				'name'  => 'sfwd-lms/sfwd_lms.php',
			),
		);

		return $data;
	}

	/**
	 * Migrates LearnPress data.
	 *
	 * @since 1.8.0
	 *
	 * @param string $type The type of data to migrate.
	 * @param array $remaining_ids The IDs of items to migrate.
	 * @return WP_REST_Response The response object.
	 */
	private function migrate_lp_data( $type, $remaining_ids ) {
		if ( 'courses' === $type ) {
			$courses = $this->migrate_lp_courses( $remaining_ids );
			if ( $courses ) {
				return $courses;
			} else {
				$orders = $this->migrate_lp_orders( $remaining_ids );

				if ( $orders ) {
					return $orders;
				} else {
					$reviews = $this->migrate_lp_reviews( $remaining_ids );
					if ( $reviews ) {
						return $reviews;
					}
				}
			}
		}

		if ( 'orders' === $type ) {
			$orders = $this->migrate_lp_orders( $remaining_ids );

			if ( $orders ) {
				return $orders;
			} else {
				$reviews = $this->migrate_lp_reviews( $remaining_ids );

				if ( $reviews ) {
					return $reviews;
				}
			}
		}

		if ( 'reviews' === $type ) {
			$reviews = $this->migrate_lp_reviews( $remaining_ids );

			if ( $reviews ) {
				return $reviews;
			}
		}

		return rest_ensure_response( array( 'message' => __( 'All the LearnPress data migrated successfully.', 'masteriyo' ) ) );
	}

	/**
	 * Handles the migration of LearnPress courses.
	 *
	 * Retrieves LearnPress courses and migrates them to Masteriyo, including sections, items (lessons, quizzes),
	 * questions, and user enrollments.
	 *
	 * @since 1.8.0
	 *
	 * @param array $remaining_ids Array of remaining IDs to be migrated.
	 * @return WP_REST_Response|null Returns WP_REST_Response on success or null on failure.
	 */
	private function migrate_lp_courses( $remaining_ids ) {
		$lp_courses = $this->get_learnpress_courses();

		if ( ! is_array( $lp_courses ) || empty( $lp_courses ) ) {
			return null;
		}

		foreach ( $lp_courses as $lp_course ) {
			$this->process_course_migration_from_lp( $lp_course );
		}

		// Update the option `masteriyo_remaining_migrated_items` by removing the current $lp_course.
		$updated_courses = array_filter(
			$lp_courses,
			function ( $course ) use ( $lp_course ) {
				return $course !== $lp_course;
			}
		);

		update_option( 'masteriyo_remaining_migrated_items', wp_json_encode( $updated_courses ) );

		$response = $this->generate_migration_response_from_lp( $lp_course, $updated_courses );

		return rest_ensure_response( $response );
	}

	/**
	 * Retrieves LearnPress courses.
	 *
	 * @since 1.8.0
	 *
	 * @return array|null Array of LearnPress course IDs or null if not found.
	 */
	private function get_learnpress_courses() {
		global $wpdb;

		$lp_courses = get_option( 'masteriyo_remaining_migrated_items' );

		if ( 'not_started' === $lp_courses ) {
			$lp_courses = $wpdb->get_results( "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'lp_course';", ARRAY_A );
		} else {
			$lp_courses = is_string( $lp_courses ) ? json_decode( $lp_courses, true ) : $lp_courses;
		}

		return $lp_courses;
	}

	/**
	 * Processes migration for a single LearnPress course.
	 *
	 * @since 1.8.0
	 *
	 * @param array $lp_course LearnPress course data.
	 */
	private function process_course_migration_from_lp( $lp_course ) {
		$course_id = $lp_course['ID'] ?? 0;
		$course    = \learn_press_get_course( $course_id );

		if ( ! $course ) {
			return;
		}

		$curriculum = $course->get_curriculum();
		$mto_course = array();

		if ( $curriculum ) {
			$i = 0;

			foreach ( $curriculum as $section ) {
				$i++;

				$mto_section = array(
					'post_type'    => PostType::SECTION,
					'post_title'   => $section->get_title(),
					'post_content' => $section->get_description(),
					'post_status'  => PostStatus::PUBLISH,
					'post_author'  => $course->get_author( 'id' ),
					'post_parent'  => $course_id,
					'menu_order'   => $i,
					'items'        => array(),
				);

				$items = $section->get_items();

				$j = 0;
				foreach ( $items as $item ) {
					$j++;

					$item_post_type = \learn_press_get_post_type( $item->get_id() );

					if ( 'lp_quiz' === $item_post_type ) {
						$item_post_type = PostType::QUIZ;
					} elseif ( 'lp_lesson' === $item_post_type ) {
						$item_post_type = PostType::LESSON;
					}

					$mto_items = array(
						'ID'          => $item->get_id(),
						'post_type'   => $item_post_type,
						'post_parent' => '{section_id}',
						'menu_order'  => $j,
					);

					$mto_section['items'][] = $mto_items;
				}

				$mto_course[] = $mto_section;
			}
		}

		if ( count( $mto_course ) > 0 ) {
			foreach ( $mto_course as $section ) {
				$items = $section['items'];
				unset( $section['items'] );

				$section_id = wp_insert_post( $section );

				if ( is_wp_error( $section_id ) ) {
					continue;
				}

				update_post_meta( $section_id, '_course_id', $course_id );

				foreach ( $items as $item ) {
					if ( PostType::QUIZ === $item['post_type'] ) {
						$quiz_id = masteriyo_array_get( $item, 'ID', 0 );

						$questions = $this->get_quiz_questions_from_lp( $quiz_id );

						if ( count( $questions ) > 0 ) {
							foreach ( $questions as $question ) {
								$this->process_question_migration_from_lp( $question, $quiz_id, $course_id );
							}
						}
					}

					$item['post_parent'] = $section_id;
					$item_id             = masteriyo_array_get( $item, 'ID', 0 );
					wp_update_post( $item );
					update_post_meta( $item_id, '_course_id', $course_id );
				}
			}
		}

		$this->update_masteriyo_course_from_lp( $course_id );
	}

	/**
	 * Retrieves quiz questions for a given LearnPress quiz ID.
	 *
	 * @since 1.8.0
	 *
	 * @param int $quiz_id LearnPress quiz ID.
	 * @return array Array of quiz questions.
	 */
	private function get_quiz_questions_from_lp( $quiz_id ) {
		global $wpdb;

		$questions = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT question_id, question_order, questions.ID, questions.post_content, questions.post_author, questions.post_status, questions.post_title, question_type_meta.meta_value as question_type, question_mark_meta.meta_value as question_mark
					FROM {$wpdb->prefix}learnpress_quiz_questions
					LEFT JOIN {$wpdb->posts} questions on question_id = questions.ID
					LEFT JOIN {$wpdb->postmeta} question_type_meta on question_id = question_type_meta.post_id AND question_type_meta.meta_key = '_lp_type'
					LEFT JOIN {$wpdb->postmeta} question_mark_meta on question_id = question_mark_meta.post_id AND question_mark_meta.meta_key = '_lp_mark'
					WHERE quiz_id = %d",
				$quiz_id
			)
		);

		return $questions;
	}

	/**
	 * Processes migration for a single LearnPress quiz question.
	 *
	 * @since 1.8.0
	 *
	 * @param object $question LearnPress quiz question data.
	 * @param int $quiz_id LearnPress quiz ID.
	 * @param int $course_id Masteriyo course ID.
	 */
	private function process_question_migration_from_lp( $question, $quiz_id, $course_id ) {
		$question_type = null;

		if ( 'true_or_false' === $question->question_type ) {
			$question_type = QuestionType::TRUE_FALSE;
		} elseif ( 'single_choice' === $question->question_type ) {
			$question_type = QuestionType::SINGLE_CHOICE;
		} elseif ( 'multi_choice' === $question->question_type ) {
			$question_type = QuestionType::MULTIPLE_CHOICE;
		}

		if ( $question_type ) {
			$answers = $this->get_question_answers_from_lp( $question->question_id );

			$question_array = array(
				'post_type'    => PostType::QUESTION,
				'post_title'   => $question->post_title,
				'post_content' => wp_json_encode( $answers ),
				'post_status'  => PostStatus::PUBLISH,
				'post_author'  => $question->post_author,
				'post_parent'  => $quiz_id,
			);

			$question_id = wp_insert_post( $question_array );

			if ( is_wp_error( $question_id ) ) {
				return;
			}

			update_post_meta( $question_id, '_course_id', $course_id );
			update_post_meta( $question_id, '_type', $question_type );
			update_post_meta( $question_id, '_points', $question->question_mark );
			update_post_meta( $question_id, '_parent_id', $quiz_id );
		}
	}

	/**
	 * Retrieves answers for a given LearnPress question ID.
	 *
	 * @since 1.8.0
	 *
	 * @param int $question_id LearnPress question ID.
	 * @return array Array of question answers.
	 */
	private function get_question_answers_from_lp( $question_id ) {
		global $wpdb;

		$answer_items = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}learnpress_question_answers WHERE question_id = %d",
				$question_id
			),
			ARRAY_A
		);

		$answers = array();

		if ( count( $answer_items ) > 0 ) {
			foreach ( $answer_items as $answer_item ) {
				$answers[] = array(
					'name'    => masteriyo_array_get( $answer_item, 'title', '' ),
					'correct' => 'yes' === masteriyo_array_get( $answer_item, 'is_true', '' ) ? true : false,
				);
			}
		}

		return $answers;
	}

	/**
	 * Updates Masteriyo course information.
	 *
	 * @since 1.8.0
	 *
	 * @param int $course_id Masteriyo course ID.
	 */
	private function update_masteriyo_course_from_lp( $course_id ) {
		$_lp_price              = floatval( get_post_meta( $course_id, '_lp_regular_price', true ) );
		$_lp_max_students       = get_post_meta( $course_id, '_lp_max_students', true ) ?? 0;
		$_lp_thumbnail_id       = get_post_meta( $course_id, '_thumbnail_id', true ) ?? 0;
		$_lp_no_required_enroll = get_post_meta( $course_id, '_lp_no_required_enroll', true ) ?? 'no';
		$_lp_sale_start         = get_post_meta( $course_id, '_lp_sale_start', true ) ?? '';
		$_lp_sale_end           = get_post_meta( $course_id, '_lp_sale_end', true ) ?? '';
		$_lp_level              = get_post_meta( $course_id, '_lp_level', true ) ?? '';
		$_lp_retake_count       = absint( get_post_meta( $course_id, '_lp_retake_count', true ) ) ?? 0;

		$price_type = ( $_lp_price > 0 ) ? 'paid' : 'free'; // Determine if the course is free or paid.

		// Set the term in 'course_visibility' taxonomy.
		wp_set_object_terms( $course_id, $price_type, 'course_visibility', false );

		$mto_course = array(
			'ID'        => $course_id,
			'post_type' => PostType::COURSE,
		);

		wp_update_post( $mto_course );
		update_post_meta( $course_id, '_was_lp_course', true );
		update_post_meta( $course_id, '_price', $_lp_price );
		update_post_meta( $course_id, '_regular_price', $_lp_price );
		update_post_meta( $course_id, '_duration', $this->learn_press_get_duration_in_minutes( $course_id ) );
		update_post_meta( $course_id, '_enrollment_limit', $_lp_max_students );
		update_post_meta( $course_id, '_thumbnail_id', $_lp_thumbnail_id );
		update_post_meta( $course_id, '_show_curriculum', true );

		if ( ! empty( $_lp_sale_start ) ) {
			update_post_meta( $course_id, '_date_on_sale_from', $_lp_sale_start );
		}

		if ( ! empty( $_lp_sale_end ) ) {
			update_post_meta( $course_id, '_date_on_sale_from', $_lp_sale_end );
		}

		if ( 'yes' === $_lp_no_required_enroll ) {
			update_post_meta( $course_id, '_access_mode', 'open' );
		} else {
			if ( 'paid' === $price_type ) {
				update_post_meta( $course_id, '_access_mode', 'one_time' );
			} else {
				update_post_meta( $course_id, '_access_mode', 'need_registration' );
			}
		}

		if ( $_lp_retake_count > 0 ) {
			update_post_meta( $course_id, '_enable_course_retake', 1 );
		}

		// Set the course difficulty.
		$this->set_course_difficulty_from_lp_to_masteriyo( $course_id, $_lp_level );

		// Migrate course categories.
		$this->migrate_course_categories_from_to_masteriyo( $course_id );

		$_lp_key_features = maybe_unserialize( get_post_meta( $course_id, '_lp_key_features', true ) );
		$_highlights      = '';

		if ( is_array( $_lp_key_features ) && ! empty( $_lp_key_features ) ) {
			foreach ( $_lp_key_features as $feature ) {
				$_highlights .= "<li>{$feature}</li>";
			}
		}

		if ( $_highlights ) {
			update_post_meta( $course_id, '_highlights', $_highlights );
		}

		// Enrollment migration.
		$this->migrate_enrollments_from_lp( $course_id );
	}

	/**
	 * Migrates user enrollments for a given Masteriyo course ID.
	 *
	 * @since 1.8.0
	 *
	 * @param int $course_id Masteriyo course ID.
	 */
	private function migrate_enrollments_from_lp( $course_id ) {
		global $wpdb;

		$lp_enrollments = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT lp_user_items.*,
					lp_order.ID as order_id,
					lp_order.post_date as order_time
					FROM {$wpdb->prefix}learnpress_user_items lp_user_items
					LEFT JOIN {$wpdb->posts} lp_order ON lp_user_items.ref_id = lp_order.ID
					WHERE item_id = %d AND ref_type = 'lp_order'",
				$course_id
			)
		);

		foreach ( $lp_enrollments as $lp_enrollment ) {

			if ( ! isset( $lp_enrollment->user_id, $lp_enrollment->order_id, $lp_enrollment->start_time, $lp_enrollment->parent_id ) ) {
				continue;
			}

			$user_id  = $lp_enrollment->user_id;
			$order_id = $lp_enrollment->order_id;

			$is_enrolled = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*) FROM {$wpdb->prefix}masteriyo_user_items WHERE user_id = %d AND item_id = %d AND item_type = 'user_course'",
					$user_id,
					$course_id
				)
			);

			if ( $is_enrolled ) {
				continue; // Skip if the user is already enrolled.
			}

			$table_name = $wpdb->prefix . 'masteriyo_user_items';

			$user_items_data = array(
				'item_id'    => $course_id,
				'user_id'    => $user_id,
				'item_type'  => 'user_course',
				'date_start' => $lp_enrollment->start_time,
				'parent_id'  => $lp_enrollment->parent_id,
				'status'     => 'active',
			);

			$result = $wpdb->insert(
				$table_name,
				$user_items_data,
				array( '%d', '%d', '%s', '%s', '%s', '%s' )
			);

			if ( false === $result ) {
				continue;
			}

			$user_item_id = $wpdb->insert_id;

			$this->update_user_role( $user_id );

			$user_item_metas = array(
				array(
					'user_item_id' => $user_item_id,
					'meta_key'     => '_order_id',
					'meta_value'   => $order_id,
				),
				array(
					'user_item_id' => $user_item_id,
					'meta_key'     => '_price',
					'meta_value'   => get_post_meta( $order_id, '_order_total', true ),
				),
			);

			$table_name = $wpdb->prefix . 'masteriyo_user_itemmeta';

			foreach ( $user_item_metas as $item_meta ) {
				$wpdb->insert( $table_name, $item_meta );
			}
		}
	}

	/**
	 * Generates migration response.
	 *
	 * @since 1.8.0
	 *
	 * @param array $lp_course LearnPress course data.
	 * @param array $updated_courses Updated LearnPress course data.
	 *
	 * @return array Migration response.
	 */
	private function generate_migration_response_from_lp( $lp_course, $updated_courses ) {
		$type          = 'courses';
		$remaining_ids = wp_list_pluck( $updated_courses, 'ID' );

		if ( 1 > count( $updated_courses ) ) {
			$type          = 'orders';
			$remaining_ids = $this->get_remaining_order_ids_from_lp();

			if ( is_wp_error( $remaining_ids ) || empty( $remaining_ids ) ) {
				$type          = 'reviews';
				$remaining_ids = $this->fetch_lp_review_ids();
			}
			update_option( 'masteriyo_remaining_migrated_items', wp_json_encode( $remaining_ids ) );
		}

		$response = array(
			'message' => __( 'Course with ID: ', 'masteriyo' ) . $lp_course['ID'] . __( ' migrated successfully.', 'masteriyo' ),
		);

		if ( 'courses' === $type ) {
			$response['remainingCourses'] = $remaining_ids;
		} elseif ( 'orders' === $type ) {
			$response['remainingOrders'] = wp_list_pluck( $remaining_ids, 'ID' );
		} else {
			$response['remainingReviews'] = $remaining_ids;
		}

		return $response;
	}

	/**
	 * Retrieves remaining order IDs.
	 *
	 * @since 1.8.0
	 *
	 * @return array Array of remaining order IDs.
	 */
	private function get_remaining_order_ids_from_lp() {
		global $wpdb;

		return $wpdb->get_results( "SELECT ID, post_date, post_author FROM {$wpdb->posts} WHERE post_type = 'lp_order' AND post_status = 'lp-completed';", ARRAY_A );
	}

	/**
	 * Migrate LearnPress orders to Masteriyo.
	 *
	 * Retrieves LearnPress orders and migrates them to Masteriyo, updating order status,
	 * creating corresponding Masteriyo order items, and updating order meta.
	 *
	 * @since 1.8.0
	 *
	 * @return WP_REST_Response|null Returns WP_REST_Response on success or null on failure.
	 */
	private function migrate_lp_orders() {
		$lp_orders = $this->get_lp_orders_to_migrate();

		if ( ! is_array( $lp_orders ) || count( $lp_orders ) < 1 ) {
			return null;
		}

		foreach ( $lp_orders as $lp_order ) {
			$order_id   = $lp_order['ID'] ?? 0;
			$order_time = strtotime( $lp_order['post_date'] );
			$title      = __( 'Order', 'masteriyo' ) . ' &ndash; ' . gmdate( get_option( 'date_format' ), $order_time ) . ' @ ' . gmdate( get_option( 'time_format' ), $order_time );

			$migrate_order_data = array(
				'ID'            => $order_id,
				'post_status'   => 'completed',
				'post_type'     => PostType::ORDER,
				'post_title'    => $title,
				'post_password' => masteriyo_generate_order_key(),
			);

			wp_update_post( $migrate_order_data );

			$lp_order_items = $this->get_lp_order_items( $order_id );

			if ( count( $lp_order_items ) < 1 ) {
				continue;
			}

			foreach ( $lp_order_items as $lp_order_item ) {
				$this->migrate_order_item_from_lp( $lp_order_item, $order_id );
			}

			$this->update_order_meta_from_lp( $order_id, $lp_order );

			$updated_orders = array_filter(
				$lp_orders,
				function ( $order ) use ( $lp_order ) {
					return $order !== $lp_order;
				}
			);
			update_option( 'masteriyo_remaining_migrated_items', wp_json_encode( $updated_orders ) );

			$remaining_ids = json_decode( get_option( 'masteriyo_remaining_migrated_items' ), true );
			$type          = 'orders';

			if ( 1 > count( $updated_orders ) ) {
				$type          = 'reviews';
				$remaining_ids = $this->fetch_lp_review_ids();
				update_option( 'masteriyo_remaining_migrated_items', wp_json_encode( $remaining_ids ) );
			}

			$response = array(
				'message' => __( 'Order with ID: ', 'masteriyo' ) . $order_id . __( ' migrated successfully.', 'masteriyo' ),
			);

			if ( 'orders' === $type ) {
				$response['remainingOrders'] = wp_list_pluck( $remaining_ids, 'ID' );
			} else {
				$response['remainingReviews'] = $remaining_ids;
			}

			return rest_ensure_response( $response );
		}
	}

	/**
	 * Retrieves LP orders to migrate.
	 *
	 * @since 1.8.0
	 *
	 * @return array LP orders to migrate.
	 */
	private function get_lp_orders_to_migrate() {
		$lp_orders = get_option( 'masteriyo_remaining_migrated_items', null );

		if ( empty( $lp_orders ) || 'not_started' === $lp_orders ) {
			$lp_orders = $this->get_remaining_order_ids_from_lp();
		} else {
			$lp_orders = is_string( $lp_orders ) ? json_decode( $lp_orders, true ) : $lp_orders;
		}

		return $lp_orders;
	}

	/**
	 * Get LearnPress order items.
	 *
	 * @since 1.8.0
	 *
	 * @param int $order_id Order ID.
	 *
	 * @return array
	 */
	private function get_lp_order_items( $order_id ) {
		global $wpdb;

		return $wpdb->get_results(
			$wpdb->prepare(
				"SELECT order_item_id as id, order_item_name as name
					, oim.meta_value as `course_id`
			FROM {$wpdb->learnpress_order_items} oi
					INNER JOIN {$wpdb->learnpress_order_itemmeta} oim ON oi.order_item_id = oim.learnpress_order_item_id AND oim.meta_key='_course_id'
			WHERE order_id = %d ",
				$order_id
			)
		);
	}

	/**
	 * Migrate order item.
	 *
	 * @since 1.8.0
	 *
	 * @param object $lp_order_item LearnPress order item object.
	 * @param int    $order_id      Order ID.
	 */
	private function migrate_order_item_from_lp( $lp_order_item, $order_id ) {
		global $wpdb;

		$item_data = array(
			'order_item_name' => $lp_order_item->name,
			'order_item_type' => 'course',
			'order_id'        => $order_id,
		);

		$wpdb->insert( $wpdb->prefix . 'masteriyo_order_items', $item_data );
		$order_item_id = absint( $wpdb->insert_id );

		$lp_item_metas = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT meta_key, meta_value 
			FROM {$wpdb->prefix}learnpress_order_itemmeta 
			WHERE learnpress_order_item_id = %d",
				$lp_order_item->id
			)
		);

		$lp_formatted_metas = array();
		foreach ( $lp_item_metas as $item_meta ) {
			$lp_formatted_metas[ $item_meta->meta_key ] = $item_meta->meta_value;
		}

		$_course_id = masteriyo_array_get( $lp_formatted_metas, '_course_id', 0 );
		$_quantity  = masteriyo_array_get( $lp_formatted_metas, '_quantity', 0 );
		$_subtotal  = masteriyo_array_get( $lp_formatted_metas, '_subtotal', 0 );
		$_total     = masteriyo_array_get( $lp_formatted_metas, '_total', 0 );

		$mto_item_metas = array(
			array(
				'order_item_id' => $order_item_id,
				'meta_key'      => 'course_id',
				'meta_value'    => $_course_id,
			),
			array(
				'order_item_id' => $order_item_id,
				'meta_key'      => 'quantity',
				'meta_value'    => $_quantity,
			),
			array(
				'order_item_id' => $order_item_id,
				'meta_key'      => 'subtotal',
				'meta_value'    => $_subtotal,
			),
			array(
				'order_item_id' => $order_item_id,
				'meta_key'      => 'total',
				'meta_value'    => $_total,
			),
		);

		$table_name = $wpdb->prefix . 'masteriyo_order_itemmeta';

		foreach ( $mto_item_metas as $item_meta ) {
			$wpdb->insert( $table_name, $item_meta );
		}
	}

	/**
	 * Update Masteriyo order meta.
	 *
	 * @since 1.8.0
	 *
	 * @param int   $order_id  Order ID.
	 * @param array $lp_order   LearnPress order data.
	 */
	private function update_order_meta_from_lp( $order_id, $lp_order ) {
		global $wpdb;

		$customer_id         = get_post_meta( $order_id, '_user_id', true );
		$customer_ip_address = get_post_meta( $order_id, '_user_ip_address', true );
		$customer_user_agent = get_post_meta( $order_id, '_user_agent', true );
		$total               = get_post_meta( $order_id, '_order_total', true );
		$currency            = get_post_meta( $order_id, '_order_currency', true );
		$version             = get_post_meta( $order_id, '_order_version', true );

		update_post_meta( $order_id, '_customer_id', $customer_id );
		update_post_meta( $order_id, '_customer_ip_address', $customer_ip_address );
		update_post_meta( $order_id, '_customer_user_agent', $customer_user_agent );
		update_post_meta( $order_id, '_total', $total );
		update_post_meta( $order_id, '_currency', $currency );
		update_post_meta( $order_id, '_version', $version );

		$user_email = $wpdb->get_var( $wpdb->prepare( "SELECT user_email FROM {$wpdb->users} WHERE ID = %d", $customer_id ) );

		update_post_meta( $order_id, '_billing_address_index', $user_email );
		update_post_meta( $order_id, '_billing_email', $user_email );
		update_post_meta( $order_id, '_was_lp_order', true );
	}

	/**
	 * Migrate LearnPress reviews to Masteriyo.
	 *
	 * @since 1.8.0
	 *
	 * @global wpdb $wpdb WordPress database abstraction object.
	 *
	 * @return WP_REST_Response|null Returns WP_REST_Response on success or null on failure.
	 */
	private function migrate_lp_reviews() {
		global $wpdb;

		$lp_review_ids = $this->fetch_lp_review_ids();

		if ( is_wp_error( $lp_review_ids ) || empty( $lp_review_ids ) ) {
			return null;
		}

		foreach ( $lp_review_ids as $lp_review_id ) {
			$review_migrate_data = array(
				'comment_approved' => 1,
				'comment_type'     => CommentType::COURSE_REVIEW,
				'comment_agent'    => 'Masteriyo',
				'comment_karma'    => 0,
			);

			$result = $wpdb->update( $wpdb->comments, $review_migrate_data, array( 'comment_ID' => $lp_review_id ) );

			if ( false === $result ) {
				continue;
			}
		}

		return rest_ensure_response( array( 'message' => __( 'All the LearnPress data migrated successfully.', 'masteriyo' ) ) );
	}

	/**
	 * Fetches the IDs of reviews from LearnPress.
	 *
	 * @since 1.8.0
	 *
	 * @return array Array of review IDs.
	 */
	private function fetch_lp_review_ids() {
		global $wpdb;

		return $wpdb->get_col(
			$wpdb->prepare(
				"
					SELECT comments.comment_ID 
					FROM {$wpdb->comments} AS comments
					JOIN {$wpdb->posts} AS posts ON comments.comment_post_ID = posts.ID
					WHERE comments.comment_type = %s
					AND posts.post_type = %s
					AND EXISTS (
							SELECT 1 FROM {$wpdb->postmeta} 
							WHERE post_id = comments.comment_post_ID 
							AND meta_key = '_was_lp_course'
					)",
				'comment',
				PostType::COURSE
			)
		);
	}

	/**
	 * Get the duration of a LearnPress post in minutes.
	 *
	 * Parses the duration meta field of a LearnPress post and converts it to minutes.
	 *
	 * @since 1.8.0
	 *
	 * @param int $post_id The ID of the LearnPress post.
	 * @return int Returns the duration in minutes. Returns 0 if the duration is not valid or not set.
	 */
	private function learn_press_get_duration_in_minutes( $post_id ) {
		$duration = get_post_meta( $post_id, '_lp_duration', true );

		$duration_arr = explode( ' ', $duration );

		if ( count( $duration_arr ) > 1 ) {
			$duration_number = absint( $duration_arr[0] );
			$duration_unit   = strtolower( $duration_arr[1] );

			switch ( $duration_unit ) {
				case 'minute':
				case 'minutes':
					return $duration_number;
				case 'hour':
				case 'hours':
					return $duration_number * 60;
				case 'day':
				case 'days':
					return $duration_number * 1440;
				case 'week':
				case 'weeks':
					return $duration_number * 10080;
				default:
					return 0;
			}
		}

		return 0;
	}

	/**
	 * Sets or creates and sets the course difficulty level based on the specified level slug.
	 *
	 * @since 1.8.0
	 *
	 * @param int $course_id The ID of the course for which the difficulty level is being set.
	 * @param string $_lp_level The slug representing the difficulty level.
	 */
	private function set_course_difficulty_from_lp_to_masteriyo( $course_id, $_lp_level ) {
		if ( $_lp_level ) {
			$difficulty_term = get_term_by( 'slug', $_lp_level, 'course_difficulty' );

			if ( ! $difficulty_term || is_wp_error( $difficulty_term ) ) {
				$difficulty_term = wp_insert_term(
					ucfirst( $_lp_level ),
					'course_difficulty',
					array( 'slug' => $_lp_level )
				);

				if ( is_wp_error( $difficulty_term ) ) {
					update_post_meta( $course_id, '_difficulty_id', 0 );
					return;
				}

				$term_id = $difficulty_term['term_id'];
			} else {
				$term_id = $difficulty_term->term_id;
			}

			update_post_meta( $course_id, '_difficulty_id', $term_id );

			wp_set_object_terms( $course_id, $term_id, 'course_difficulty', false );
		} else {
			update_post_meta( $course_id, '_difficulty_id', 0 );
		}
	}

	/**
	 * Migrates course categories from LearnPress to Masteriyo.
	 *
	 * This function retrieves the course categories associated with a given course from LearnPress
	 * and assigns them to the same course in Masteriyo.
	 *
	 * @since 1.8.0
	 *
	 * @param int $course_id The ID of the course for which categories are to be migrated.
	 *                      This should be the Masteriyo course ID which corresponds to the LearnPress course.
	 *
	 * @return void This function does not return anything. It operates by side effect, updating the course taxonomy.
	 */
	private function migrate_course_categories_from_to_masteriyo( $course_id, $taxonomy = 'course_category' ) {
		$categories = wp_get_post_terms( $course_id, $taxonomy, array( 'fields' => 'ids' ) );

		if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
			foreach ( $categories as $cat_id ) {
				$cat = get_term( $cat_id, $taxonomy );

				if ( ! is_wp_error( $cat ) ) {
					// Check if the term exists in the 'course_cat' taxonomy.
					$masteriyo_cat_id = term_exists( $cat->name, 'course_cat' );

					if ( 0 === $masteriyo_cat_id || null === $masteriyo_cat_id ) {
						$masteriyo_cat = wp_insert_term( $cat->name, 'course_cat' );

						if ( ! is_wp_error( $masteriyo_cat ) ) {
							$masteriyo_cat_id = $masteriyo_cat['term_id'];
						}
					} else {
						$masteriyo_cat_id = $masteriyo_cat_id['term_id'];
					}

					$masteriyo_categories[] = (int) $masteriyo_cat_id;
				}
			}

			if ( ! empty( $masteriyo_categories ) ) {
				wp_set_object_terms( $course_id, $masteriyo_categories, 'course_cat', false );
			}
		}
	}

	/**
	 * Migrates LearnPress data.
	 *
	 * @since 1.8.0
	 *
	 * @param string $type The type of data to migrate.
	 * @param array $remaining_ids The IDs of items to migrate.
	 * @return WP_REST_Response The response object.
	 */
	private function migrate_ld_data( $type, $remaining_ids ) {
		if ( 'courses' === $type ) {
			$courses = $this->migrate_ld_courses( $remaining_ids );
			if ( $courses ) {
				return $courses;
			} else {
				$orders = $this->migrate_ld_orders( $remaining_ids );

				if ( $orders ) {
					return $orders;
				}
			}
		}

		if ( 'orders' === $type ) {
			$orders = $this->migrate_ld_orders( $remaining_ids );

			if ( $orders ) {
				return $orders;
			}
		}

		return rest_ensure_response( array( 'message' => __( 'All the LearnDash data migrated successfully.', 'masteriyo' ) ) );
	}

	/**
	 * Handles the migration of LearnDash courses.
	 *
	 * Retrieves LearnDash courses and migrates them to Masteriyo, including sections, items (lessons, quizzes),
	 * questions, and user enrollments.
	 *
	 * @since 1.8.0
	 *
	 * @param array $remaining_ids Array of remaining IDs to be migrated.
	 * @return WP_REST_Response|null Returns WP_REST_Response on success or null on failure.
	 */
	private function migrate_ld_courses() {
		global $wpdb;

		$ld_courses = $wpdb->get_results( "SELECT ID, post_author, post_date, post_content, post_title, post_excerpt, post_status FROM {$wpdb->posts} WHERE post_type = 'sfwd-courses' AND (post_status = 'publish' OR post_status = 'draft');" );

		if ( 1 > count( $ld_courses ) ) {
			return null;
		}

		foreach ( $ld_courses as $ld_course ) {
			$course_id = $ld_course->ID ?? 0;

			if ( ! $course_id ) {
				return null;
			}

			$total_data = LDLMS_Factory_Post::course_steps( $course_id );
			$total_data = $total_data->get_steps();

			$this->migrate_ld_course( $course_id );

			$this->update_masteriyo_course_from_ld( $course_id );

			// Insert the course enrollments.
			$this->insert_ld_course_enrollment( $course_id );

			// Update the option `masteriyo_remaining_migrated_items` by removing the current $ld_course.
			$updated_courses = array_filter(
				$ld_courses,
				function ( $course ) use ( $ld_course ) {
					return $course !== $ld_course;
				}
			);

			update_option( 'masteriyo_remaining_migrated_items', wp_json_encode( $updated_courses ) );

			$response = $this->generate_migration_response_from_ld( $ld_course, $updated_courses );

			return rest_ensure_response( $response );
		}
	}

	/**
	 * Updates Masteriyo course information.
	 *
	 * @since 1.8.0
	 *
	 * @param int $course_id Masteriyo course ID.
	 */
	private function update_masteriyo_course_from_ld( $course_id ) {
		$course_meta_data = get_post_meta( $course_id, '_sfwd-courses', true );

		$_ld_price                = floatval( isset( $course_meta_data['sfwd-courses_course_price'] ) ? $course_meta_data['sfwd-courses_course_price'] : 0 );
		$_ld_max_students         = isset( $course_meta_data['sfwd-courses_course_seats_limit'] ) ? absint( $course_meta_data['sfwd-courses_course_seats_limit'] ) : 0;
		$_ld_courses_course_price = isset( $course_meta_data['sfwd-courses_course_price_type'] ) ? $course_meta_data['sfwd-courses_course_price_type'] : 'open';
		$_ld_show_curriculum      = isset( $course_meta_data['sfwd-courses_course_disable_content_table'] ) ? $course_meta_data['sfwd-courses_course_disable_content_table'] : 'on';
		$price_type               = ( 'paynow' === $_ld_courses_course_price || 'subscribe' === $_ld_courses_course_price ) ? 'paid' : 'free'; // Determine if the course is free or paid.
		// Set the term in 'course_visibility' taxonomy.
		wp_set_object_terms( $course_id, $price_type, 'course_visibility', false );

		update_post_meta( $course_id, '_was_ld_course', true );
		update_post_meta( $course_id, '_price', $_ld_price );
		update_post_meta( $course_id, '_regular_price', $_ld_price );
		update_post_meta( $course_id, '_enrollment_limit', $_ld_max_students );
		update_post_meta( $course_id, '_show_curriculum', 'on' === $_ld_show_curriculum ? true : false );

		if ( 'open' === $_ld_courses_course_price ) {
			update_post_meta( $course_id, '_access_mode', CourseAccessMode::OPEN );
		} elseif ( 'paynow' === $_ld_courses_course_price ) {
			update_post_meta( $course_id, '_access_mode', CourseAccessMode::ONE_TIME );
		} elseif ( 'free' === $_ld_courses_course_price ) {
			update_post_meta( $course_id, '_access_mode', CourseAccessMode::NEED_REGISTRATION );
		} elseif ( 'subscribe' === $_ld_courses_course_price ) {
			update_post_meta( $course_id, '_access_mode', CourseAccessMode::RECURRING );
		}

		// Migrate course categories.
		$this->migrate_course_categories_from_to_masteriyo( $course_id, 'ld_course_category' );
	}

	/**
	 * Generates migration response.
	 *
	 * @since 1.8.0
	 *
	 * @param object $ld_course LearnPress course data.
	 * @param array $updated_courses Updated LearnPress course data.
	 *
	 * @return array Migration response.
	 */
	private function generate_migration_response_from_ld( $ld_course, $updated_courses ) {
		global $wpdb;

		$type          = 'courses';
		$remaining_ids = wp_list_pluck( $updated_courses, 'ID' );
		$course_id     = $ld_course->ID ?? 0;

		if ( 1 > count( $updated_courses ) ) {
			$type          = 'orders';
			$remaining_ids = $wpdb->get_results( "SELECT ID, post_author, post_date, post_content, post_title, post_status FROM {$wpdb->posts} WHERE post_type = 'sfwd-transactions' AND post_status = 'publish';" );

			update_option( 'masteriyo_remaining_migrated_items', wp_json_encode( $remaining_ids ) );

			if ( is_wp_error( $remaining_ids ) || empty( $remaining_ids ) ) {
				return rest_ensure_response( array( 'message' => __( 'All the LearnDash data migrated successfully.', 'masteriyo' ) ) );
			}
		}

		$response = array(
			'message' => __( 'Course with ID: ', 'masteriyo' ) . $course_id . __( ' migrated successfully.', 'masteriyo' ),
		);

		if ( 'courses' === $type ) {
			$response['remainingCourses'] = $remaining_ids;
		} elseif ( 'orders' === $type ) {
			$response['remainingOrders'] = wp_list_pluck( $remaining_ids, 'ID' );
		} else {
			$response['remainingReviews'] = $remaining_ids;
		}

		return $response;
	}

	/**
	 * Migrates the course ID.
	 *
	 * @since 1.8.0
	 *
	 * @param int $course_id Masteriyo course ID.
	 */
	public function migrate_ld_course( $course_id ) {
		$mto_course = array(
			'ID'        => $course_id,
			'post_type' => PostType::COURSE,
		);

		$new_course_id = wp_update_post( $mto_course );

		if ( is_wp_error( $new_course_id ) ) {
			return;
		}

		$section_heading = get_post_meta( $course_id, 'course_sections', true );
		$section_heading = $section_heading ? json_decode( $section_heading, true ) : array(
			array(
				'order'      => 0,
				'post_title' => __( 'Section', 'masteriyo' ),
			),
		);

		$total_data = LDLMS_Factory_Post::course_steps( $course_id );
		$total_data = $total_data->get_steps();

		if ( empty( $total_data ) ) {
			return;
		}

		$section_id = 0;
		$menu_order = 0;

		if ( ! empty( $total_data['sfwd-lessons'] ) ) {
			$i             = 0;
			$section_count = 0;
			foreach ( $total_data['sfwd-lessons'] as $lesson_key => $lesson_data ) {
				$author_id = get_post_field( 'post_author', $course_id );

				$check = 0 === $i ? 0 : $i + 1;
				$menu_order++;

				if ( isset( $section_heading[ $section_count ]['order'] ) ) {
					if ( $section_heading[ $section_count ]['order'] === $check ) {
						$section_id = $this->insert_post( $section_heading[ $section_count ]['post_title'], '', $author_id, PostType::SECTION, $i, $course_id );
						update_post_meta( $section_id, '_course_id', $course_id );
						update_post_meta( $section_id, '_parent_id', $course_id );
						$section_count++;
					}
				}

				if ( $section_id ) {
					$lesson_id = $this->update_post( $lesson_key, PostType::LESSON, $menu_order, $section_id );

					update_post_meta( $lesson_id, '_course_id', $course_id );
					update_post_meta( $lesson_id, '_parent_id', $section_id );

					foreach ( $lesson_data['sfwd-topic'] as $lesson_inner_key => $lesson_inner ) {
						$menu_order++;

						$lesson_id = $this->update_post( $lesson_inner_key, PostType::LESSON, $menu_order, $section_id );

						update_post_meta( $lesson_id, '_course_id', $course_id );
						update_post_meta( $lesson_id, '_parent_id', $section_id );

						foreach ( $lesson_inner['sfwd-quiz'] as $quiz_key => $quiz_data ) {
							$menu_order++;

							$quiz_id = $this->update_post( $quiz_key, PostType::QUIZ, $menu_order, $section_id );

							update_post_meta( $quiz_id, '_course_id', $course_id );
							update_post_meta( $quiz_id, '_parent_id', $section_id );

							if ( $quiz_id ) {
								$this->migrate_ld_quiz( $quiz_id, $course_id );
							}
						}
					}

					foreach ( $lesson_data['sfwd-quiz'] as $quiz_key => $quiz_data ) {
						$menu_order++;
						$quiz_id = $this->update_post( $quiz_key, PostType::QUIZ, $menu_order, $section_id );

						update_post_meta( $quiz_id, '_course_id', $course_id );
						update_post_meta( $quiz_id, '_parent_id', $section_id );

						if ( $quiz_id ) {
							$this->migrate_ld_quiz( $quiz_id, $course_id );
						}
					}
				}
				$i++;
			}

			if ( ! empty( $total_data['sfwd-quiz'] ) ) {
				foreach ( $total_data['sfwd-quiz'] as $quiz_key => $quiz_data ) {
					$menu_order++;
					$quiz_id = $this->update_post( $quiz_key, PostType::QUIZ, $menu_order, $section_id );

						update_post_meta( $quiz_id, '_course_id', $course_id );
						update_post_meta( $quiz_id, '_parent_id', $section_id );

					if ( $quiz_id ) {
							$this->migrate_ld_quiz( $quiz_id, $course_id );
					}
				}
			}
		}

		// Handle Standalone Quizzes if there are no lessons.
		if ( empty( $total_data['sfwd-lessons'] ) && ! empty( $total_data['sfwd-quiz'] ) ) {
			$author_id  = get_post_field( 'post_author', $course_id );
			$section_id = $this->insert_post( __( 'Section', 'masteriyo' ), '', $author_id, PostType::SECTION, 0, $course_id );
			update_post_meta( $section_id, '_course_id', $course_id );
			update_post_meta( $section_id, '_parent_id', $section_id );

			foreach ( $total_data['sfwd-quiz'] as $quiz_key => $quiz_data ) {
				$menu_order++;

				if ( $section_id ) {
					$quiz_id = $this->update_post( $quiz_key, PostType::QUIZ, $menu_order, $section_id );

					update_post_meta( $quiz_id, '_course_id', $course_id );
					update_post_meta( $quiz_id, '_parent_id', $section_id );

					if ( $quiz_id ) {
						$this->migrate_ld_quiz( $quiz_id, $course_id );
					}
				}
			}
		}
	}

	/**
	 * Inserts a new post with specified parameters.
	 *
	 * This function creates a new post using the WordPress function `wp_insert_post`.
	 * It sets various properties of the post such as title, content, author, type,
	 * menu order, and parent post based on the provided arguments.
	 *
	 * @since 1.8.0
	 *
	 * @param string $post_title    Title of the post.
	 * @param string $post_content  Content of the post.
	 * @param int    $author_id     ID of the author creating the post.
	 * @param string $post_type     Type of the post. Default is PostType::SECTION.
	 * @param int    $menu_order    Order of the post in the menu. Default is 0.
	 * @param int|string $post_parent  Parent post ID. Default is an empty string.
	 * @return int|WP_Error         The post ID on success, WP_Error on failure.
	 */
	private function insert_post( $post_title, $post_content, $author_id, $post_type = PostType::SECTION, $menu_order = 0, $post_parent = '' ) {
		$post_arg = array(
			'post_type'    => $post_type,
			'post_title'   => $post_title,
			'post_content' => $post_content,
			'post_status'  => PostStatus::PUBLISH,
			'post_author'  => $author_id,
			'post_parent'  => $post_parent,
			'menu_order'   => $menu_order,
		);
		return wp_insert_post( $post_arg );
	}

	/**
	 * Updates an existing post with specified parameters.
	 *
	 * This function updates a post identified by $post_id using the WordPress function `wp_update_post`.
	 * It allows updating the post type, menu order, and parent post. If the update fails, it returns false.
	 *
	 * @since 1.8.0
	 *
	 * @param int    $post_id       ID of the post to update.
	 * @param string $post_type     New type of the post. Default is 'topics'.
	 * @param int    $menu_order    New order of the post in the menu. Default is 0.
	 * @param int|string $post_parent  New parent post ID. Default is an empty string.
	 * @return int|false            The updated post ID on success, or false on failure.
	 */
	private function update_post( $post_id, $post_type = PostType::SECTION, $menu_order = 0, $post_parent = '' ) {
		$post_arg = array(
			'ID'          => $post_id,
			'post_type'   => $post_type,
			'post_parent' => $post_parent,
			'menu_order'  => $menu_order,
		);
		$post_id  = wp_update_post( $post_arg );

		if ( is_wp_error( $post_id ) ) {
			return false;
		}

		return $post_id;
	}

	/**
	 * Migrates the quiz for a given Masteriyo course ID.
	 *
	 * @since 1.8.0
	 *
	 * @param int $quiz_id Quiz ID.
	 * @param int $course_id Masteriyo course ID.
	 */
	private function migrate_ld_quiz( $quiz_id, $course_id ) {
		global $wpdb;
		$question_ids = get_post_meta( $quiz_id, 'ld_quiz_questions', true );
		$is_table     = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', "{$wpdb->prefix}learndash_pro_quiz_question" ) );

		if ( ! empty( $question_ids ) ) {
			$question_ids = array_keys( $question_ids );
			$menu_order   = 0;

			foreach ( $question_ids as $question_single ) {
				$menu_order++;

				$question_id = get_post_meta( $question_single, 'question_pro_id', true );

				$table_name = $is_table ? "{$wpdb->prefix}learndash_pro_quiz_question" : "{$wpdb->prefix}wp_pro_quiz_question";

				$query = $wpdb->prepare(
					"SELECT id, title, question, points, answer_type, answer_data FROM $table_name WHERE id = %d",  // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared 
					$question_id
				);

				$result = $wpdb->get_row( $query, ARRAY_A ); // phpcs:disable WordPress.DB.PreparedSQL.NotPrepared

				$question_type = null;

				if ( 'single' === $result['answer_type'] ) {
					$question_type = QuestionType::SINGLE_CHOICE;
				} elseif ( 'multiple' === $result['answer_type'] ) {
					$question_type = QuestionType::MULTIPLE_CHOICE;
				}

				if ( null === $question_type ) {
					return;
				}

				$serialized_answers = maybe_unserialize( $result['answer_data'] );

				$answers = array();

				foreach ( (array) $serialized_answers as $answer_object ) {
					$answer_data = array();

					if ( ! $answer_object instanceof WpProQuiz_Model_AnswerTypes ) {
						continue;
					}

					$i = 0;
					foreach ( (array) $answer_object as $answer_value ) {

						if ( 0 === $i ) {
							$answer_data['name'] = $answer_value;
						} elseif ( 3 === $i ) {
							$answer_data['correct'] = (bool) $answer_value;
						}

						$i++;
					}

					$answers[] = $answer_data;
				}

				$question_array = array(
					'post_type'    => PostType::QUESTION,
					'post_title'   => sanitize_text_field( $result['question'] ),
					'post_content' => wp_json_encode( $answers ),
					'post_status'  => PostStatus::PUBLISH,
					'post_author'  => get_post_field( 'post_author', $quiz_id ),
					'post_parent'  => $quiz_id,
					'menu_order'   => $menu_order,
				);

				$question_id = wp_insert_post( $question_array );

				if ( is_wp_error( $question_id ) ) {
					return;
				}

				update_post_meta( $question_id, '_course_id', $course_id );
				update_post_meta( $question_id, '_type', $question_type );
				update_post_meta( $question_id, '_points', $result['points'] );
				update_post_meta( $question_id, '_parent_id', $quiz_id );
			}
		}
	}

	/**
	 * Migrates user enrollments for a given Masteriyo course ID.
	 *
	 * @since 1.8.0
	 *
	 * @param int $course_id Masteriyo course ID.
	 */
	private function insert_ld_course_enrollment( $course_id ) {
		global $wpdb;
		$ld_course_user_activities = $wpdb->get_results( $wpdb->prepare( "SELECT * from {$wpdb->prefix}learndash_user_activity WHERE course_id = %d AND activity_type = 'access'", absint( $course_id ) ) );

		if ( 1 > count( $ld_course_user_activities ) ) {
			return;
		}

		foreach ( $ld_course_user_activities as $data ) {
			$user_id            = $data->user_id;
			$complete_course_id = $data->course_id;
			$order_id           = 0;

			$args = array(
				'post_type'      => 'sfwd-transactions',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'meta_query'     => array(
					'relation' => 'AND',
					array(
						'key'   => 'course_id',
						'value' => $complete_course_id,
					),
					array(
						'key'   => 'user_id',
						'value' => $user_id,
					),
				),
			);

			$query = new \WP_Query( $args );

			if ( ! empty( $query->posts ) ) {
				foreach ( $query->posts as $post_id ) {
					$order_id = $post_id;
				}
			}

			$is_enrolled = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*) FROM {$wpdb->prefix}masteriyo_user_items WHERE user_id = %d AND item_id = %d AND item_type = 'user_course'",
					$user_id,
					$complete_course_id
				)
			);

			if ( $is_enrolled ) {
				continue; // Skip if the user is already enrolled.
			}

			$table_name = $wpdb->prefix . 'masteriyo_user_items';

			$user_items_data = array(
				'item_id'       => $course_id,
				'user_id'       => $user_id,
				'item_type'     => 'user_course',
				'date_start'    => gmdate( 'Y-m-d H:i:s', $data->activity_started ),
				'date_modified' => gmdate( 'Y-m-d H:i:s', $data->activity_updated ),
				'date_end'      => gmdate( 'Y-m-d H:i:s', $data->activity_completed ),
				'status'        => 'active',
			);

			$result = $wpdb->insert(
				$table_name,
				$user_items_data,
				array( '%d', '%d', '%s', '%s', '%s', '%s', '%s' )
			);

			if ( false === $result ) {
				continue;
			}

			$user_item_id = $wpdb->insert_id;

			$this->update_user_role( $user_id );

			$user_item_metas = array(
				array(
					'user_item_id' => $user_item_id,
					'meta_key'     => '_order_id',
					'meta_value'   => $order_id,
				),
				array(
					'user_item_id' => $user_item_id,
					'meta_key'     => '_price',
					'meta_value'   => get_post_meta( $order_id, '_order_total', true ),
				),
			);

			$table_name = $wpdb->prefix . 'masteriyo_user_itemmeta';

			foreach ( $user_item_metas as $item_meta ) {
				$wpdb->insert( $table_name, $item_meta );
			}
		}
	}

	/**
	 * Add the masteriyo_student user role.
	 *
	 * @since 1.8.0
	 *
	 * @param int $user_id User ID.
	 */
	private function update_user_role( $user_id ) {
		$user = new \WP_User( $user_id );

		if ( ! $user || ! isset( $user->ID ) || 0 === $user->ID ) {
			return;
		}

		if (
			! in_array( Roles::ADMIN, (array) $user->roles, true ) &&
			! in_array( Roles::MANAGER, (array) $user->roles, true ) &&
			! in_array( Roles::INSTRUCTOR, (array) $user->roles, true ) &&
			! in_array( Roles::STUDENT, (array) $user->roles, true )
		) {
			$user->add_role( Roles::STUDENT );
		}
	}

	/**
	 * Migrate LearnPress orders to Masteriyo.
	 *
	 * Retrieves LearnPress orders and migrates them to Masteriyo, updating order status,
	 * creating corresponding Masteriyo order items, and updating order meta.
	 *
	 * @since 1.8.0
	 *
	 * @return WP_REST_Response|null Returns WP_REST_Response on success or null on failure.
	 */
	private function migrate_ld_orders() {
		global $wpdb;

		$ld_orders = $wpdb->get_results( "SELECT ID, post_author, post_date, post_content, post_title, post_status FROM {$wpdb->posts} WHERE post_type = 'sfwd-transactions' AND post_status = 'publish';", ARRAY_A );

		foreach ( $ld_orders as $ld_order ) {
			$order_id   = $ld_order['ID'] ?? 0;
			$order_time = strtotime( $ld_order['post_date'] );
			$title      = __( 'Order', 'masteriyo' ) . ' &ndash; ' . gmdate( get_option( 'date_format' ), $order_time ) . ' @ ' . gmdate( get_option( 'time_format' ), $order_time );

			$migrate_order_data = array(
				'ID'            => $order_id,
				'post_status'   => 'completed',
				'post_type'     => PostType::ORDER,
				'post_title'    => $title,
				'post_password' => masteriyo_generate_order_key(),
			);

			wp_update_post( $migrate_order_data );

			$this->migrate_order_item_from_ld( $order_id );

			$this->update_order_meta_from_ld( $order_id, $ld_order );

			$updated_orders = array_filter(
				$ld_orders,
				function ( $order ) use ( $ld_order ) {
					return $order !== $ld_order;
				}
			);

			update_option( 'masteriyo_remaining_migrated_items', wp_json_encode( $updated_orders ) );

			$remaining_ids = json_decode( get_option( 'masteriyo_remaining_migrated_items' ), true );
			$type          = 'orders';

			if ( 1 > count( $updated_orders ) ) {
				return rest_ensure_response( array( 'message' => __( 'All the LearnDash data migrated successfully.', 'masteriyo' ) ) );
			}

			$response = array(
				'message' => __( 'Order with ID: ', 'masteriyo' ) . $order_id . __( ' migrated successfully.', 'masteriyo' ),
			);

			if ( 'orders' === $type ) {
				$response['remainingOrders'] = wp_list_pluck( $remaining_ids, 'ID' );
			}

			return rest_ensure_response( $response );
		}
	}

	/**
	 * Migrate order item.
	 *
	 * @since 1.8.0
	 *
	 * @param int    $order_id      Order ID.
	 */
	private function migrate_order_item_from_ld( $order_id ) {
		global $wpdb;

		$course_id = get_post_meta( $order_id, 'course_id', true );

		$item_data = array(
			'order_item_name' => get_the_title( $course_id ),
			'order_item_type' => 'course',
			'order_id'        => $order_id,
		);

		$wpdb->insert( $wpdb->prefix . 'masteriyo_order_items', $item_data );
		$order_item_id = absint( $wpdb->insert_id );

		if ( ! $order_item_id ) {
			return;
		}

		$_ld_price = get_post_meta( $course_id, '_sfwd-courses', true );

		$_course_id = $course_id;
		$_quantity  = 1;
		$_subtotal  = isset( $_ld_price['sfwd-courses_course_price'] ) ? $_ld_price['sfwd-courses_course_price'] : 0;
		$_total     = isset( $_ld_price['sfwd-courses_course_price'] ) ? $_ld_price['sfwd-courses_course_price'] : 0;

		$mto_item_metas = array(
			array(
				'order_item_id' => $order_item_id,
				'meta_key'      => 'course_id',
				'meta_value'    => $_course_id,
			),
			array(
				'order_item_id' => $order_item_id,
				'meta_key'      => 'quantity',
				'meta_value'    => $_quantity,
			),
			array(
				'order_item_id' => $order_item_id,
				'meta_key'      => 'subtotal',
				'meta_value'    => $_subtotal,
			),
			array(
				'order_item_id' => $order_item_id,
				'meta_key'      => 'total',
				'meta_value'    => $_total,
			),
		);

		$table_name = $wpdb->prefix . 'masteriyo_order_itemmeta';

		foreach ( $mto_item_metas as $item_meta ) {
			$wpdb->insert( $table_name, $item_meta );
		}
	}

	/**
	 * Update Masteriyo order meta.
	 *
	 * @since 1.8.0
	 *
	 * @param int   $order_id  Order ID.
	 * @param array $lp_order   LearnPress order data.
	 */
	private function update_order_meta_from_ld( $order_id, $lp_order ) {
		global $wpdb;

		$course_id = get_post_meta( $order_id, 'course_id', true );
		$_ld_price = get_post_meta( $course_id, '_sfwd-courses', true );

		$customer_id = get_post_meta( $order_id, 'user_id', true );
		$total       = isset( $_ld_price['sfwd-courses_course_price'] ) ? $_ld_price['sfwd-courses_course_price'] : 0;

		$all_meta = get_post_meta( $order_id );

		$pattern = '/_currency$/';

		$currency = '';

		foreach ( $all_meta as $key => $value ) {
			if ( preg_match( $pattern, $key ) ) {
				$currency = $value[0];
				break;
			}
		}

		$version = get_post_meta( $order_id, 'learndash_version', true );

		update_post_meta( $order_id, '_customer_id', $customer_id );
		update_post_meta( $order_id, '_total', $total );
		update_post_meta( $order_id, '_currency', $currency );
		update_post_meta( $order_id, '_version', $version );

		$user_email = $wpdb->get_var( $wpdb->prepare( "SELECT user_email FROM {$wpdb->users} WHERE ID = %d", $customer_id ) );

		update_post_meta( $order_id, '_billing_address_index', $user_email );
		update_post_meta( $order_id, '_billing_email', $user_email );

		update_post_meta( $order_id, '_was_ld_order', true );
	}
}
