<?php


/**
 * class Api
 *
 * @link       https://appcheap.io
 * @since      2.5.0
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 */

namespace AppBuilder\Lms\MasterStudy;

defined( 'ABSPATH' ) || exit;

use AppBuilder\Lms\LmsApi;
use STM_LMS_Reviews;
use WP_Error;
use WP_Http_Cookie;
use WP_REST_Response;

class Api extends LmsApi {

	private $helper;

	/**
	 * Construct
	 */
	public function __construct() {
		$this->helper = new Helper();
		parent::__construct();
	}

	/**
	 * Get course reviews
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_REST_Response
	 * @since 2.5.0
	 *
	 */
	public function get_reviews( $request ) {
		$id = (int) $request->get_param( 'id' );

		$reviews = STM_LMS_Reviews::_get_reviews( $id, 0 );

		return rest_ensure_response( $reviews );
	}

	/**
	 *
	 * Write review
	 *
	 * @param $request
	 *
	 * @return array|void|WP_Error
	 */
	public function write_review( $request ) {

		$user_id = get_current_user_id();

		do_action( 'stm_lms_api_course_add_review', $user_id );

		$id     = (int) $request->get_param( 'id' );
		$review = $request->get_param( 'review' );
		$mark   = (int) $request->get_param( 'mark' );

		return STM_LMS_Reviews::_add_review( $id, $mark, $review );
	}

	/**
	 *
	 * Get course info
	 *
	 * @param $request
	 *
	 * @return array|void|WP_Error
	 */
	public function get_course( $request ) {

		$user_id   = get_current_user_id();
		$course_id = (int) $request->get_param( 'id' );

		$data = [];

		/**
		 * Get lectures
		 */
		$data['lectures'] = $this->helper->get_lectures( $course_id );

		/**
		 * Get Faqs
		 */
		$faqs         = get_post_meta( $course_id, 'faq', true );
		$data['faqs'] = empty( $faqs ) ? [] : json_decode( $faqs );

		/**
		 * Announcement
		 */
		$announcement         = get_post_meta( $course_id, 'announcement', true );
		$data['announcement'] = $announcement;

		return rest_ensure_response( $data );
	}

	/**
	 *
	 * Get quizzes
	 *
	 * @param $request
	 *
	 * @return mixed
	 */
	public function get_quizzes( $request ) {
		$quiz_ids_str = $request->get_param( 'quiz_ids' );
		$data         = [];

		if ( ! empty( $quiz_ids_str ) && $quiz_ids = explode( ',', $quiz_ids_str ) ) {
			foreach ( $quiz_ids as $id ) {
				$quiz      = [];
				$post      = get_post( $id );
				$answers   = get_post_meta( $id, 'answers', true );
				$type      = get_post_meta( $id, 'type', true );
				$view_type = get_post_meta( $id, 'question_view_type', true );

				$quiz['id']        = (int) $id;
				$quiz['text']      = $post->post_title;
				$quiz['type']      = $type;
				$quiz['view_type'] = $view_type;

				$quiz['data'] = $this->helper->pre_quiz_data( $type, $view_type, $answers );

				$data[] = $quiz;
			}
		}

		return rest_ensure_response( $data );
	}

	public function start_quizzes( $request ) {
		$data = $this->do_ajax_call($request, 'start_quiz', 'wp_ajax_stm_lms_start_quiz');
		rest_ensure_response( $data );
	}

	public function end_quizzes( $request ) {
		$data = $this->do_ajax_call($request, 'user_answers', 'wp_ajax_stm_lms_user_answers');
		rest_ensure_response( $data );
	}
}
