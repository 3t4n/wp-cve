<?php
/**
 * class Helper
 *
 * @link       https://appcheap.io
 * @since      2.5.0
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 */

namespace AppBuilder\Lms\MasterStudy;

use STM_LMS_Course;
use STM_LMS_Lesson;

class Helper {
	/**
	 * Get lectures
	 *
	 *
	 * @param int $course_id course ID
	 *
	 * @return array
	 */
	public function get_lectures( int $course_id ): array {

		/**
		 * Get lectures in post meta by course ID
		 *
		 * Result format Ex: Starting Course,13,19,4990,After Intro,16,53,15
		 */
		$lectures = get_post_meta( $course_id, 'curriculum', true );

		$lectures_data = array();

		if ( ! empty( $lectures ) && $lectures = explode( ',', $lectures ) ) {

			$heading_index = 1;
			$section_index = 1;

			foreach ( $lectures as $lecture ) {

				/**
				 * String value is heading
				 */
				if ( ! is_numeric( $lecture ) ) {
					$lectures_data[] = $this->prepare_heading( $lecture, $heading_index );
					$heading_index ++;
					continue;
				}

				/**
				 * Get post info
				 */
				$post        = get_post( $lecture );
				$has_preview = STM_LMS_Lesson::lesson_has_preview( $lecture );

				/**
				 * Quiz type
				 */
				if ( $post->post_type === 'stm-quizzes' ) {
					$lectures_data[] = $this->prepare_quiz( $post, $has_preview, $section_index );
					$section_index ++;
					continue;
				}

				/**
				 * Lesson type
				 */
				$lectures_data[] = $this->prepare_lesson( $post, $has_preview, $section_index, $course_id );
				$section_index ++;
			}

		}

		return $lectures_data;
	}

	/**
	 * Prepare heading
	 *
	 * @param string $lecture
	 * @param $index
	 *
	 * @return array
	 */
	private function prepare_heading( string $lecture, $index ): array {
		return array(
			'index' => $index,
			'type'  => 'heading',
			'text'  => $lecture,
		);
	}

	/**
	 * Prepare quiz
	 *
	 * @param $post
	 * @param bool $has_preview
	 * @param $index
	 *
	 * @return array
	 */
	private function prepare_quiz( $post, bool $has_preview, $index ): array {
		$questions = get_post_meta( $post->ID, 'questions', true );

		return array(
			'index'       => $index,
			'type'        => 'quiz',
			'text'        => $post->post_title,
			'content'     => $post->post_content,
			'lesson_id'   => $post->ID,
			'questions'   => $questions,
			'has_preview' => $has_preview
		);
	}

	/**
	 * Prepare lesson
	 *
	 * @param $post
	 * @param bool $has_preview
	 * @param $index
	 * @param int $course_id
	 *
	 * @return array
	 */
	private function prepare_lesson( $post, bool $has_preview, $index, int $course_id ): array {
		$preview  = get_post_meta( $post->ID, 'preview', true );
		$duration = get_post_meta( $post->ID, 'duration', true );
		$type     = get_post_meta( $post->ID, 'type', true );

		$preview_url = ( ! empty( $preview ) ) ? STM_LMS_Course::item_url( $course_id, $post->ID ) : '';

		$lesson_type = empty( $type ) ? "text" : $type;
		$data        = [
			'index'       => $index,
			'type'        => 'lesson',
			'view'        => $lesson_type,
			'text'        => get_the_title( $post->ID ),
			'duration'    => $duration,
			'lesson_id'   => $post->ID,
			'preview_url' => $preview_url,
			'has_preview' => $has_preview
		];

		if ( $lesson_type == 'video' ) {
			$video_url = get_post_meta( $post->ID, 'lesson_video_url', true );

			if ( empty( $video_url ) ) {
				$attachment_id   = get_post_meta( $post->ID, 'lesson_video', true );
				$attachment_link = absint( $attachment_id ) ? esc_url( wp_get_attachment_url( $attachment_id ) ) : false;

				if ( $attachment_link ) {
					$video_url = $attachment_link;
				}
			}

			$data['video_url'] = $video_url;
		}

		return $data;
	}

	/**
	 * Pre data fro quiz
	 *
	 * @param $type
	 * @param $view_type
	 * @param $answers
	 *
	 * @return array[]
	 */
	public function pre_quiz_data( $type, $view_type, $answers ): array {

		$list_question = [];
		$list_answers  = [];

		if ( is_array( $answers ) ) {
			foreach ( $answers as $answer ) {
				if ( $type == "item_match" ) {
					$list_answers[]  = [
						'text' => $answer['text'],
					];
					$list_question[] = [
						'text' => $answer['question'],
					];
				} else if ( $type == 'image_match' ) {
					$list_answers[] = [
						'text'  => $answer['text'],
						'image' => $answer['text_image'],
					];

					$list_question[] = [
						'text'  => $answer['question'],
						'image' => $answer['question_image'],
					];
				} else if ( $view_type == 'image' && ( $type == 'multi_choice' || $type == 'single_choice' ) ) {
					$list_answers[] = [
						'text'  => $answer['text'],
						'image' => $answer['text_image'],
					];
				} else {
					$list_answers[] = [
						'text' => $answer['text'],
					];
				}
			}
		}

		if ( $type != 'keywords' ) {
			shuffle( $list_question );
			shuffle( $list_answers );
		}

		return [
			'question' => $list_question,
			'answers'  => $list_answers,
		];
	}
}
