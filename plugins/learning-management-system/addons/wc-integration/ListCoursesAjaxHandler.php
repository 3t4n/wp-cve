<?php
/**
 * List Course Ajax handler.
 *
 * @since 1.8.1
 *
 * @package Masteriyo\Addons\WcIntegration
 */

namespace Masteriyo\Addons\WcIntegration;

use Masteriyo\Abstracts\AjaxHandler;
use Masteriyo\PostType\PostType;
use Masteriyo\Taxonomy\Taxonomy;

/**
 * List course ajax handler.
 */
class ListCoursesAjaxHandler extends AjaxHandler {

	/**
	 * ListCourse ajax action.
	 *
	 * @since 1.8.1
	 * @var string
	 */
	public $action = 'masteriyo_wc_integration_list_courses';

	/**
	 * Register ajax handler.
	 *
	 * @since 1.8.1
	 */
	public function register() {
		add_action( "wp_ajax_nopriv_{$this->action}", array( $this, 'list_courses' ) );
		add_action( "wp_ajax_{$this->action}", array( $this, 'list_courses' ) );
	}

	/**
	 * List courses.
	 *
	 * @since 1.8.1
	 */
	public function list_courses() {
		if ( ! isset( $_GET['nonce'] ) || ! wp_verify_nonce( $_GET['nonce'], $this->action ) ) {
			wp_send_json(
				array(
					'results' => array(),
				)
			);
		}

		$search = isset( $_GET['search'] ) ? sanitize_text_field( $_GET['search'] ) : '';
		$page   = isset( $_GET['page'] ) ? absint( $_GET['page'] ) : 1;

		$query = new \WP_Query(
			array(
				'post_type'      => PostType::COURSE,
				'posts_per_page' => '10',
				'paged'          => $page,
				's'              => $search,
				'tax_query'      => array(
					array(
						'taxonomy' => Taxonomy::COURSE_VISIBILITY,
						'field'    => 'slug',
						'terms'    => 'paid',
					),
				),
			)
		);

		$courses = array_map(
			function( $post ) {
				return array(
					'id'          => $post->ID,
					'text'        => $post->post_title,
					'access_mode' => get_post_meta( $post->ID, '_access_mode', true ),
				);
			},
			$query->posts
		);

		wp_send_json(
			array(
				'results'    => $courses,
				'pagination' => array(
					'more' => $query->found_posts > $query->post_count,
				),
			)
		);
	}
}
