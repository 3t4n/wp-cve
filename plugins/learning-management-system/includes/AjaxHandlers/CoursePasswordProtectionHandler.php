<?php
/**
 * Course Password Protection Handler
 *
 * Handles AJAX requests for password-protected courses in the Masteriyo plugin.
 *
 * @since 1.8.0
 *
 * @package Masteriyo\AjaxHandlers
 */

namespace Masteriyo\AjaxHandlers;

use Masteriyo\Abstracts\AjaxHandler;
use Masteriyo\Enums\CourseProgressStatus;
use Masteriyo\Query\CourseProgressQuery;

/**
 * Class CoursePasswordProtectionHandler
 *
 * This class is responsible for handling the password protection feature
 * for courses in the Masteriyo plugin. It manages the verification of passwords
 * and the setting of password-related cookies.
 */
class CoursePasswordProtectionHandler extends AjaxHandler {

	/**
	 * AllowUsageNotice ajax action.
	 *
	 * @since 1.8.0
	 * @var string
	 */
	public $action = 'masteriyo_course_password_protection';

	/**
	 * Register AJAX actions.
	 *
	 * @since 1.8.0
	 *
	 * Hooks the process method to both logged in and not logged in AJAX actions.
	 */
	public function register() {
		add_action( "wp_ajax_nopriv_{$this->action}", array( $this, 'process' ) );
		add_action( "wp_ajax_{$this->action}", array( $this, 'process' ) );
	}

	/**
	 * Process the AJAX request.
	 *
	 * Handles the AJAX request by validating the nonce, password, and course ID.
	 * Sends JSON response back to the client.
	 *
	 * @since 1.8.0
	 */
	public function process() {

		$nonce     = $_POST['nonce'] ? sanitize_key( $_POST['nonce'] ) : ''; // phpcs:disable WordPress.Security.NonceVerification.Missing
		$password  = $_POST['password'] ? wp_unslash( $_POST['password'] ) : ''; // phpcs:disable WordPress.Security.NonceVerification.Missing
		$course_id = $_POST['course_id'] ? absint( $_POST['course_id'] ) : 0; // phpcs:disable WordPress.Security.NonceVerification.Missing

		if ( ! $this->validate_nonce( $nonce ) ||
		! $this->validate_password_and_course_id( $password, $course_id ) ) {
			return;
		}

		$course = masteriyo_get_course( $course_id );
		if ( ! $course ) {
			$this->send_error_response( __( 'Invalid Course ID.', 'masteriyo' ) );
			return;
		}

		if ( empty( $course->get_post_password() ) ) {
			$this->send_success_response( $course, __( 'No password required for this course.', 'masteriyo' ) );
			return;
		}

		if ( ! $this->is_password_correct( $course, $password ) ) {
			$this->send_error_response( __( 'Password is incorrect.', 'masteriyo' ) );
			return;
		}

		$this->set_password_cookie( $password );

		$this->send_success_response( $course );
	}

	/**
	 * Validate the nonce.
	 *
	 * @since 1.8.0
	 *
	 * @param string $nonce The nonce to validate.
	 * @return bool Returns true if nonce is valid, otherwise false.
	 */
	private function validate_nonce( $nonce ) {
		if ( ! $nonce || ! wp_verify_nonce( $nonce, 'masteriyo_course_password_protected_nonce' ) ) {
			$this->send_error_response( __( 'Invalid nonce. Maybe you should reload the page.', 'masteriyo' ) );
			return false;
		}
		return true;
	}

	/**
	 * Validate the password and course ID.
	 *
	 * @since 1.8.0
	 *
	 * @param string $password The submitted password.
	 * @param int $course_id The course ID.
	 * @return bool Returns true if both password and course ID are valid, otherwise false.
	 */
	private function validate_password_and_course_id( $password, $course_id ) {
		if ( ! $password || ! $course_id ) {
			$message = ! $password ? __( 'Password is required.', 'masteriyo' ) : __( 'Course ID is required.', 'masteriyo' );
			$this->send_error_response( $message );
			return false;
		}
		return true;
	}

	/**
	 * Check if the password is correct for the given course.
	 *
	 * @since 1.8.0
	 *
	 * @param \Masteriyo\Models\Course $course The course object.
	 * @param string $password The submitted password.
	 * @return bool Returns true if the password is correct, otherwise false.
	 */
	private function is_password_correct( $course, $password ) {
		require_once ABSPATH . 'wp-includes/class-phpass.php';
		$hasher = new \PasswordHash( 8, true );
		return $hasher->CheckPassword( $course->get_post_password(), $hasher->HashPassword( $password ) );
	}

	/**
	 * Set a cookie for the password.
	 *
	 * @since 1.8.0
	 *
	 * @param string $password The submitted password.
	 */
	private function set_password_cookie( $password ) {
		$hasher        = new \PasswordHash( 8, true );
		$hash_password = $hasher->HashPassword( $password );

		/**
		 * Filters the password cookie expiration time.
		 *
		 * @since 1.8.0
		 *
		 * @param int $expire The expiration time in seconds.
		 */
		$expire = apply_filters( 'masteriyo_course_password_expires', time() + 10 * DAY_IN_SECONDS );
		$secure = is_ssl();
		setcookie( 'wp-postpass_' . COOKIEHASH, $hash_password, $expire, COOKIEPATH, COOKIE_DOMAIN, $secure );
	}

	/**
	 * Send an error JSON response.
	 *
	 * @since 1.8.0
	 *
	 * @param string $message The error message.
	 * @param int $status_code The HTTP status code, default is 400.
	 */
	private function send_error_response( $message, $status_code = 400 ) {
		wp_send_json_error( array( 'message' => $message ), $status_code );
	}

	/**
	 * Send a successful JSON response.
	 *
	 * @since 1.8.0
	 *
	 * @param \Masteriyo\Models\Course $course The course object.
	 * @param string $message Optional success message.
	 */
	private function send_success_response( $course, $message = '' ) {
		if ( empty( $message ) ) {
			$message = __( 'Password is correct.', 'masteriyo' );
		}

		$query = new CourseProgressQuery(
			array(
				'course_id' => $course->get_id(),
				'user_id'   => get_current_user_id(),
			)
		);

		$progress = current( $query->get_course_progress() );

		$start_url = $this->get_start_url( $course, true );

		if ( $progress && CourseProgressStatus::PROGRESS === $progress->get_status() ) {
			$start_url = $this->get_continue_url( $course, $progress );
		}

		wp_send_json_success(
			array(
				'message'   => $message,
				'start_url' => $start_url,
			),
			200
		);
	}

	/**
	 * Get start course URL.
	 *
	 * @since 1.8.0
	 *
	 * @param \Masteriyo\Models\Course $course The course object.
	 * @param boolean $append_first_lesson_or_quiz Whether to append first lesson or quiz or not.
	 *
	 * @return string
	 */
	private function get_start_url( $course, $append_first_lesson_or_quiz = true ) {
		$lesson_or_quiz = $course->get_first_lesson_or_quiz();
		$learn_page_url = masteriyo_get_page_permalink( 'learn' );
		$url            = trailingslashit( $learn_page_url ) . 'course/' . $course->get_slug();

		if ( '' === get_option( 'permalink_structure' ) ) {
			$url = add_query_arg(
				array(
					'course_name' => $course->get_id(),
				),
				$learn_page_url
			);
		}

		$url .= '#/course/' . $course->get_id();

		if ( $append_first_lesson_or_quiz && $lesson_or_quiz ) {
			$url .= "/{$lesson_or_quiz->get_object_type()}/" . $lesson_or_quiz->get_id();
		}

		return $url;
	}

	/**
	 * Get continue course url.
	 *
	 * @since 1.8.0
	 * @param \Masteriyo\Models\CourseProgress $course_progress Course progress object.
	 * @return string
	 */
	private function get_continue_url( $course, $course_progress ) {
		$data                  = \Masteriyo\Resources\CourseProgressResource::to_array( $course_progress );
		$course_progress_items = array_reduce(
			$data['items'],
			function( $acc, $curr ) {
				if ( isset( $curr['contents'] ) ) {
					$acc = array_merge( $acc, $curr['contents'] );
				}
				return $acc;
			},
			array()
		);

		$first_course_progress_item = current(
			array_filter(
				$course_progress_items,
				function( $course_progress_content ) {
					return ! $course_progress_content['completed'];
				}
			)
		);

		if ( $first_course_progress_item ) {
			$item_type = $first_course_progress_item['item_type'];
			$item_id   = $first_course_progress_item['item_id'];
		}

		$continue_url  = $this->get_start_url( $course, false );
		$continue_url .= "/$item_type/$item_id";

		return $continue_url;
	}
}
