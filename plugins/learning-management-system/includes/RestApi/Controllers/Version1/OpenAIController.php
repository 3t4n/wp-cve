<?php
/**
 * OpenAI controller class.
 *
 * @since x,x,x
 *
 * @package Masteriyo\RestApi
 * @subpackage Controllers
 */

namespace Masteriyo\RestApi\Controllers\Version1;

defined( 'ABSPATH' ) || exit;

use Exception;
use Masteriyo\Helper\Permission;
use Masteriyo\Jobs\CreateCourseContentJob;
use Masteriyo\Jobs\CreateLessonsContentJob;
use Masteriyo\Jobs\CreateQuizzesForSectionsJob;
use Masteriyo\PostType\PostType;
use ThemeGrill\OpenAI\ChatGPT;

use WP_Error;
use WP_REST_Response;

class OpenAIController extends RestController {
	/**
	 * Endpoint namespace.
	 *
	 * @since 1.6.15
	 *
	 * @var string
	 */
	protected $namespace = 'masteriyo/v1';

	/**
	 * Route base.
	 *
	 * @since 1.6.15
	 *
	 * @var string
	 */
	protected $rest_base = 'openai';

	/**
	 * Permission class.
	 *
	 * @since 1.6.15
	 *
	 * @var \Masteriyo\Helper\Permission;
	 */
	protected $permission = null;

	/**
	 * Constructor.
	 *
	 * @since 1.6.15
	 *
	 * @param Permission $permission
	 */
	public function __construct( Permission $permission ) {
		$this->permission = $permission;
	}

	/**
	 * Register routes.
	 *
	 * @since 1.6.15
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/add-new-course',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_course' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( \WP_REST_Server::CREATABLE ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/generate-content',
			array(
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'generate_content' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
					'args'                => $this->get_endpoint_args_for_item_schema( \WP_REST_Server::CREATABLE ),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base . '/get-course-status',
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_course_status' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
				),
			)
		);
	}

	/**
	 * Check if a given request has access to import items.
	 *
	 * @since 1.6.0
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
	 * Generates content based on user request.
	 *
	 * @since 1.7.1
	 *
	 * @param WP_REST_Request $request The request object containing parameters for content generation.
	 * @return WP_Error|WP_REST_Response Returns a WP_Error object in case of an error, or a WP_REST_Response object on success.
	 *
	 * @throws Exception If there is a problem during the content generation process.
	 */
	public function generate_content( $request ) {
		$prompt       = sanitize_text_field( $request->get_param( 'prompt' ) ?? '' );
		$content_type = sanitize_text_field( $request->get_param( 'contentType' ) ?? '' );
		$word_limit   = absint( $request->get_param( 'wordLimit' ) ?? 200 );

		if ( empty( $prompt ) ) {
			return new WP_Error( 'openai_invalid_parameters', 'Prompt cannot be empty.', array( 'status' => 400 ) );
		}

		$api_key = masteriyo_get_setting( 'advance.openai.api_key' );

		if ( empty( $api_key ) ) {
			return new WP_Error( 'openai_invalid_api_key', 'OpenAI API Key not found.', array( 'status' => 400 ) );
		}

		try {
			$chatgpt = ChatGPT::get_instance( $api_key );

			if ( null === $chatgpt ) {
				return new WP_Error( 'openai_invalid_api_key', 'OpenAI API Key not found.', array( 'status' => 400 ) );
			}

			if ( 'quiz' === $content_type ) {
				$question_type       = sanitize_text_field( $request->get_param( 'questionType' ) ?? 'true-false' );
				$number_of_questions = absint( $request->get_param( 'numberOfQuestions' ) ?? 1 );
				$quiz_id             = absint( $request->get_param( 'quizId' ) ?? 0 );

				$quiz = masteriyo_get_quiz( $quiz_id );

				if ( is_null( $quiz ) || is_wp_error( $quiz ) ) {
					return new \WP_Error( 'openai_content_generation_failure', __( 'Invalid quiz ID.', 'masteriyo' ) );
				}

				$prompt = masteriyo_generate_quiz_questions_prompt( $prompt, $question_type, $number_of_questions );

				$response_text = masteriyo_openai_retry( array( $chatgpt, 'send_prompt' ), array( $prompt ), 3 );
				$response_text = wp_unslash( $response_text );

				$response = is_string( $response_text ) ? json_decode( $response_text, true ) : $response_text;

				if ( ! isset( $response['questions'] ) ) {
					return new \WP_Error( 'openai_content_generation_failure', __( 'Failed to create question(s). Please try again.', 'masteriyo' ) );
				}

				$questions = $response['questions'];

				if ( 1 > count( $questions ) ) {
					return new \WP_Error( 'openai_content_generation_failure', __( 'Failed to create question(s). Please try again.', 'masteriyo' ) );
				}

				$course = masteriyo_get_course( $quiz->get_course_id() );

				if ( is_null( $course ) || is_wp_error( $course ) ) {
					return new \WP_Error( 'openai_content_generation_failure', __( 'Invalid course ID.', 'masteriyo' ) );
				}

				$j = 0;
				foreach ( $questions as $question ) {
					$j++;
					masteriyo_openai_create_question( $course, $quiz, $question, $j );
				}

				$url = admin_url( "admin.php?page=masteriyo#courses/{$course->get_id()}/quiz/edit/{$quiz->get_id()}?page=questions" );

				return rest_ensure_response(
					array(
						'url'     => $url,
						'message' => __( 'Question(s) created successfully.', 'masteriyo' ),
					)
				);
			}

			$prompt        = masteriyo_generate_content_prompt( $prompt, $content_type, $word_limit );
			$response_text = masteriyo_openai_retry( array( $chatgpt, 'send_prompt' ), array( $prompt ), 3 );
			$response_text = wp_unslash( $response_text );

			if ( is_null( $response_text ) || is_wp_error( $response_text ) ) {
				return $response_text;
			}

			if ( empty( $response_text ) ) {
				return new \WP_Error( 'openai_content_generation_failure', __( 'Failed to generate the content. Please try again.', 'masteriyo' ) );
			}

			return rest_ensure_response(
				array(
					'content' => $response_text,
					'message' => __( 'Content created successfully.', 'masteriyo' ),
				)
			);

		} catch ( Exception $e ) {
			return new \WP_Error( 'openai_content_generation_failure', __( 'Failed to generate the content. Please try again.', 'masteriyo' ) );
		}
	}

	/**
	 * Create a new course using ChatGPT.
	 *
	 * @since 1.6.15
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response Returns a WP_Error object on failure or a WP_REST_Response object on success.
	 */
	public function create_course( $request ) {
		$course_title = sanitize_text_field( $request->get_param( 'courseTitle' ) ?? '' );
		$course_idea  = sanitize_text_field( $request->get_param( 'courseIdea' ) ?? '' );

		if ( empty( $course_title ) ) {
			return new WP_Error( 'openai_invalid_parameters', 'Course title cannot be empty.', array( 'status' => 400 ) );
		}

		$create_quiz                = sanitize_text_field( $request->get_param( 'createQuiz' ) ?? 'none' );
		$create_course_outline_only = masteriyo_string_to_bool( $request->get_param( 'createCourseOutlineOnly' ) ?? false );

		$api_key = masteriyo_get_setting( 'advance.openai.api_key' );

		if ( empty( $api_key ) ) {
			return new WP_Error( 'openai_invalid_api_key', 'OpenAI API Key not found.', array( 'status' => 400 ) );
		}

		try {
			$chatgpt = ChatGPT::get_instance( $api_key );

			if ( null === $chatgpt ) {
				return new WP_Error( 'openai_invalid_api_key', 'OpenAI API Key not found.', array( 'status' => 400 ) );
			}

			$course = $this->create_course_outline( $request, $chatgpt, $course_title, $course_idea );

			if ( is_wp_error( $course ) ) {
				throw new Exception( $course->get_error_message(), $course->get_error_data( 'status' ) );
			}

			if ( is_null( $course ) ) {
				throw new Exception( __( 'Failed to create course. Please try again.', 'masteriyo' ), 500 );
			}

			$success_message = __( 'Course outline created successfully.', 'masteriyo' );

			if ( ! $create_course_outline_only ) {

				// Schedule create_course_content to run immediately.
				$num_course_highlight_points = absint( $request->get_param( 'numCourseHighlightPoints' ) ?? 4 );

				$course_content_args   = array( $num_course_highlight_points, $course_title, $course_idea, $course->get_id() );
				$course_content_job_id = as_enqueue_async_action( CreateCourseContentJob::NAME, $course_content_args, 'masteriyo-openai' );

				// Schedule create_lessons_content to run immediately.
				$num_lesson_description_paragraphs = absint( $request->get_param( 'numLessonDescriptionParagraphs' ) ?? 3 );

				$lessons_content_args   = array( $num_lesson_description_paragraphs, $course_title, $course_idea, $course->get_id() );
				$lessons_content_job_id = as_enqueue_async_action( CreateLessonsContentJob::NAME, $lessons_content_args, 'masteriyo-openai' );

				update_post_meta( $course->get_id(), 'masteriyo_course_content_job_id', $course_content_job_id );
				update_post_meta( $course->get_id(), 'masteriyo_lessons_content_job_id', $lessons_content_job_id );

				// If required, schedule create_quizzes_for_sections to run immediately.
				if ( 'each_section' === $create_quiz || 'last_section' === $create_quiz ) {
					$num_questions_per_quiz = absint( $request->get_param( 'numQuestionsPerQuiz' ) ?? 1 );
					$num_quizzes            = absint( $request->get_param( 'numQuizzes' ) ?? 3 );
					$quizzes_args           = array( $num_questions_per_quiz, $num_quizzes, $create_quiz, $course_title, $course_idea, $course->get_id() );
					$quiz_content_job_id    = as_enqueue_async_action( CreateQuizzesForSectionsJob::HOOK, $quizzes_args, 'masteriyo-openai' );

					update_post_meta( $course->get_id(), 'masteriyo_quiz_content_job_id', $quiz_content_job_id );
				}

				$course->save();

				$success_message = __( 'Course outline created successfully. Additional course content is being created in the background. This may take some time, please be patient.', 'masteriyo' );
			}

			return new WP_REST_Response(
				array(
					'message'   => $success_message,
					'id'        => $course->get_id(),
					'name'      => $course->get_name(),
					'permalink' => admin_url( 'admin.php?page=masteriyo#' ),
				)
			);

		} catch ( Exception $e ) {

			return new WP_Error( 'openai_course_creation_failure', $e->getMessage() );
		}
	}

	/**
	 * Create a course outline.
	 *
	 * @since 1.6.15
	 *
	 * @param  \WP_REST_Request $request Full details about the request.
	 * @param mixed  $chatgpt      The ChatGPT instance.
	 * @param string $course_title The title of the course.
	 * @param string $course_idea  The main idea behind the course.
	 *
	 * @return WP_Error|\Masteriyo\Models\Course Returns a WP_Error object on failure or a $course object on success.
	 */
	public function create_course_outline( $request, $chatgpt, $course_title, $course_idea ) {
		$num_sections            = absint( $request->get_param( 'numSections' ) ?? 4 );
		$num_lessons_per_section = absint( $request->get_param( 'numLessonsPerSection' ) ?? 3 );

		if ( $num_sections < 1 || $num_lessons_per_section < 1 ) {
			return new WP_Error( 'openai_invalid_parameters', 'Invalid parameters. Number of sections and lessons should be at least 1.', array( 'status' => 400 ) );
		}

		$course_outline_prompt = masteriyo_generate_course_outline_prompt( $course_title, $course_idea, $num_sections, $num_lessons_per_section );
		$response_text         = masteriyo_openai_retry( array( $chatgpt, 'send_prompt' ), array( $course_outline_prompt ), 3 );
		$response_text         = wp_unslash( $response_text );

		if ( is_null( $response_text ) || is_wp_error( $response_text ) ) {
			return $response_text;
		}

		if ( empty( $response_text ) ) {
			return new \WP_Error( 'openai_course_creation_failure', __( 'Failed to create course. Please try again.', 'masteriyo' ) );
		}

		$course_outline = is_array( $response_text ) ? $response_text : json_decode( $response_text, true );
		$course         = $this->create_entity( 'course', $course_title );

		if ( is_null( $course ) || is_wp_error( $course ) ) {
			return $course;
		}

		if ( ! isset( $course_outline['course']['sections'] ) || ! is_array( $course_outline['course']['sections'] ) || ! count( $course_outline['course']['sections'] ) ) {
			return $course;
		}

		$i = 0;

		foreach ( $course_outline['course']['sections'] as $sec ) {
			$i++;

			$section_title = isset( $sec['title'] ) ? sanitize_text_field( $sec['title'] ) : '';
			$section       = $this->create_entity( 'section', $section_title, $course, $course, $i );

			if ( is_null( $section ) || is_wp_error( $section ) || ! isset( $sec['lessons'] ) || ! is_array( $sec['lessons'] ) || 1 > count( $sec['lessons'] ) ) {
				continue;
			}

			$j = 0;

			foreach ( $sec['lessons'] as $less ) {
				$j++;

				$lesson_title = isset( $less['title'] ) ? sanitize_text_field( $less['title'] ) : '';
				$this->create_entity( 'lesson', $lesson_title, $course, $section, $j );
			}
		}

		return $course;
	}

	/**
	 * Create an entity.
	 *
	 * This function creates and saves an entity with the given title and optional course and parent references.
	 *
	 * @since 1.6.15
	 *
	 * @param string       $entity  The type of entity to create (e.g., 'course', 'section', 'lesson').
	 * @param string       $title   The title of the entity to be created.
	 * @param \Masteriyo\Models\Course|null  $course  Optional. The course object to associate with the entity, if applicable.
	 * @param \Masteriyo\Models\Course|\Masteriyo\Models\Section|null $parent  Optional. The parent entity (e.g., section) to associate with the entity, if applicable.
	 * @param int          $menu_order  Optional. The menu order to assign to the entity, if applicable.
	 *
	 * @return \Masteriyo\Models\Course|\Masteriyo\Models\Section|\Masteriyo\Models\Lesson|null Returns the created entity object (Course, Section, Lesson) on success, or null if the title is empty or the creation fails.
	 */
	private function create_entity( $entity, $title, $course = null, $parent = null, $menu_order = 0 ) {
		if ( empty( $title ) ) {
			return null;
		}

		$instance = masteriyo( $entity );
		$instance->set_name( $title );

		if ( $parent ) {
			$instance->set_parent_id( $parent->get_id() );
		}

		if ( $course ) {
			$instance->set_course_id( $course->get_id() );
		}

		if ( $menu_order ) {
			$instance->set_menu_order( $menu_order );
		}

		if ( 'course' === $entity ) {
			$instance->set_is_ai_created( true );
		}

		$instance->save();

		return $instance->get_id() ? $instance : null;
	}

	/**
	 * Retrieves the status of a course by its ID.
	 *
	 * @since 1.6.15
	 *
	 * @param WP_REST_Request $request The WordPress REST request object.
	 *
	 * @return WP_REST_Response|WP_Error Returns WP_REST_Response on success or WP_Error on failure.
	 */
	public function get_course_status( $request ) {
		$course_id = absint( $request->get_param( 'courseID' ) ?? 0 );

		if ( ! $course_id ) {
			return new WP_Error( 'invalid_course_id', 'Invalid course ID.' );
		}

		$job_keys = array( 'masteriyo_course_content_job_id', 'masteriyo_lessons_content_job_id', 'masteriyo_quiz_content_job_id' );
		$statuses = $this->get_job_statuses( $course_id, $job_keys );

		if ( is_wp_error( $statuses ) ) {
			return $statuses;
		}

		$all_completed_or_failed = $this->check_all_jobs_status( $statuses );
		$course_content_status   = $this->is_job_done( $statuses['masteriyo_course_content_job_id'] );
		$lesson_content_status   = $this->is_job_done( $statuses['masteriyo_lessons_content_job_id'] );
		$masteriyo_admin_page    = admin_url( 'admin.php?page=masteriyo#' );

		$response_data = array(
			'completed_status'      => $all_completed_or_failed,
			'course_id'             => $course_id,
			'course_content_status' => $course_content_status,
			'lesson_content_status' => $lesson_content_status,
			'masteriyo_admin_page'  => $masteriyo_admin_page,
		);

		$quiz_content_job_id = get_post_meta( $course_id, 'masteriyo_quiz_content_job_id', true );

		if ( $quiz_content_job_id ) {
			$response_data['quiz_content_status'] = $this->is_job_done( $statuses['masteriyo_quiz_content_job_id'] );
		}

		return new WP_REST_Response( $response_data );
	}

	/**
	 * Fetches job statuses related to a course.
	 *
	 * @since 1.6.15
	 *
	 * @param int   $course_id The ID of the course.
	 * @param array $keys      The job keys to fetch the statuses for.
	 *
	 * @return array|WP_Error Returns an associative array of job statuses keyed by job keys or WP_Error on failure.
	 */
	private function get_job_statuses( $course_id, $keys ) {
		$statuses = array();

		foreach ( $keys as $key ) {
			$job_id = get_post_meta( $course_id, $key, true );

			if ( ! $job_id ) {
				$statuses[ $key ] = 'unknown';
			}

			$statuses[ $key ] = $this->check_job_status( $job_id );
		}

		return $statuses;
	}

	/**
	 * Checks if all jobs have a 'complete' or 'failed' status.
	 *
	 * @since 1.6.15
	 *
	 * @param array $statuses An array of job statuses.
	 *
	 * @return bool Returns true if all job statuses are 'complete' or 'failed', false otherwise.
	 */
	private function check_all_jobs_status( $statuses ) {
		return array_reduce(
			$statuses,
			function( $carry, $status ) {
				return $carry && $this->is_job_done( $status );
			},
			true
		);
	}

	/**
	 * Determines if a job is done by checking its status.
	 *
	 * @since 1.6.15
	 *
	 * @param string $status The status of the job.
	 *
	 * @return bool Returns true if the job status is 'complete', 'failed' or 'unknown', false otherwise.
	 */
	private function is_job_done( $status ) {
		return in_array( $status, array( 'complete', 'failed', 'unknown' ), true );
	}

	/**
	 * Fetches the status of a job by its ID.
	 *
	 * @since 1.6.15
	 *
	 * @param int $job_id The ID of the job.
	 *
	 * @return string Returns the job status or 'unknown' if it couldn't be determined.
	 */
	private function check_job_status( $job_id ) {
		global $wpdb;

		$status = $wpdb->get_var( $wpdb->prepare( "SELECT status FROM {$wpdb->prefix}actionscheduler_actions WHERE action_id = %d", $job_id ) );

		return $status ?? 'unknown';
	}
}
