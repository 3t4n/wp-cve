<?php
namespace Thim_EL_Kit\Modules\ArchiveCourse;

use LearnPress;
use Thim_EL_Kit\Modules\Modules;
use Thim_EL_Kit\SingletonTrait;

class Init extends Modules {
	use SingletonTrait;

	public function __construct() {
		$this->tab      = 'archive-course';
		$this->tab_name = esc_html__( 'Archive Course', 'thim-elementor-kit' );

		parent::__construct();

		add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ), 11 ); // after LearnPress
	}

	public function template_include( $template ) {
		$this->template_include = LP_PAGE_COURSES === \LP_Page_Controller::page_current();

		return parent::template_include( $template );
	}

	/**
	 * Override query args ( in LearnPress use ajax in archive course page ).
	 *
	 * @param $query \WP_Query
	 */
	public function pre_get_posts( \WP_Query $q ): \WP_Query {
		if ( ! $q->is_main_query() ) {
			return $q;
		}

		$is_archive_course = learn_press_is_courses() || learn_press_is_course_tag() || learn_press_is_course_category() || learn_press_is_search() || learn_press_is_course_tax();

		if ( $is_archive_course && ! $this->is_editor_preview() && ! is_admin() && method_exists( $this, 'get_layout_id' ) ) {
			$post_id = $this->get_layout_id( $this->tab );

			if ( ! empty( $post_id ) ) {
				$limit = \LP_Settings::get_option( 'archive_course_limit', 6 );
				$q->set( 'posts_per_page', $limit );

				// Deregister LearnPress scripts/styles on archive course page, category, tag course page.
				if ( version_compare( LearnPress::instance()->version, '4.2.6-beta-0', '>=' ) ) {
					add_action( 'wp_enqueue_scripts', [ $this, 'deregister_lp_scripts' ], 1000 );
				}
			}
		}

		return $q;
	}

	/**
	 * Deregister LearnPress scripts/styles on archive course page, category, tag course page.
	 * Remove styles, js courses.
	 *
	 * @return void
	 */
	public function deregister_lp_scripts() {
		wp_dequeue_script( 'lp-courses' );
		wp_deregister_script( 'lp-courses' );
		wp_dequeue_style( 'learnpress' );
		wp_deregister_style( 'learnpress' );
	}

	public function is( $condition ) {
		if ( ! class_exists( '\LearnPress' ) ) {
			return false;
		}

		switch ( $condition['type'] ) {
			case 'all':
				return learn_press_is_course_archive();
			case 'course_categories':
				return learn_press_is_course_category();
			case 'course_tags':
				return learn_press_is_course_tag();
			case 'course_term':
				$object      = get_queried_object();
				$taxonomy_id = is_object( $object ) && property_exists( $object, 'term_id' ) ? $object->term_id : false;
				return (int) $taxonomy_id === (int) $condition['query'] && ! is_search();
			case 'course_search':
				return is_search() && 'lp_course' === get_query_var( 'post_type' );
			case 'course_page':
				return is_post_type_archive( 'lp_course' ) || is_page( learn_press_get_page_id( 'courses' ) );
		}

		return false;
	}

	public function priority( $type ) {
		$priority = 100;

		switch ( $type ) {
			case 'all':
				$priority = 10;
				break;
			case 'course_page':
				$priority = 20;
				break;
			case 'course_categories':
			case 'course_tags':
				$priority = 30;
				break;
			case 'course_search':
				$priority = 40;
				break;
			case 'course_term':
				$priority = 50;
				break;
		}

		return apply_filters( 'thim_ekit_pro/condition/priority', $priority, $type );
	}

	public function get_conditions() {
		return array(
			array(
				'label'    => esc_html__( 'All course archives', 'thim-elementor-kit' ),
				'value'    => 'all',
				'is_query' => false,
			),
			array(
				'label'    => esc_html__( 'Course page', 'thim-elementor-kit' ),
				'value'    => 'course_page',
				'is_query' => false,
			),
			array(
				'label'    => esc_html__( 'All categories', 'thim-elementor-kit' ),
				'value'    => 'course_categories',
				'is_query' => false,
			),
			array(
				'label'    => esc_html__( 'All tags', 'thim-elementor-kit' ),
				'value'    => 'course_tags',
				'is_query' => false,
			),
			array(
				'label'    => esc_html__( 'Course search page', 'thim-elementor-kit' ),
				'value'    => 'course_search',
				'is_query' => false,
			),
			array(
				'label'    => esc_html__( 'Select course term (category, tag)', 'thim-elementor-kit' ),
				'value'    => 'course_term',
				'is_query' => true,
			),
		);
	}
}

Init::instance();
