<?php
namespace Thim_EL_Kit\Modules\SingleCourseItem;

use Thim_EL_Kit\Custom_Post_Type;
use Thim_EL_Kit\Modules\Modules;
use Thim_EL_Kit\SingletonTrait;
use Elementor\Utils;
use Elementor\Modules\NestedElements\Module as NestedElementsModule;

class Init extends Modules {
	use SingletonTrait;

	public function __construct() {
		$this->tab      = 'single-course-item';
		$this->tab_name = esc_html__( 'Single Course Item', 'thim-elementor-kit' );

		parent::__construct();

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 11 );
		add_action( 'thim_ekit/rest_api/create_template/before', array( $this, 'before_create_template' ), 10 );
		add_action( 'thim_ekit/rest_api/create_template/after', array( $this, 'create_template' ), 10, 2 );
	}

	public function template_include( $template ) {
		$this->template_include = is_singular( 'lp_course' ) && \LP_Global::course_item();

		return parent::template_include( $template );
	}

	public function get_preview_id() {
		global $post;

		$output = false;

		if ( $post ) {
			$document = \Elementor\Plugin::$instance->documents->get( $post->ID );

			if ( $document ) {
				$preview_id = $document->get_settings( 'thim_ekits_preview_id' );

				$output = ! empty( $preview_id ) ? absint( $preview_id ) : false;
			}
		}

		return $output;
	}

	public function before_preview_query() {
		global $lp_course_item;

		$course_preview_id = $this->get_preview_id();

		if ( $course_preview_id ) {
			$query = array(
				'p'         => absint( $course_preview_id ),
				'post_type' => 'lp_course',
			);

			$course = learn_press_get_course( $course_preview_id );
		} else {
			$query_vars = array(
				'post_type'      => 'lp_course',
				'posts_per_page' => 1,
			);

			$posts = get_posts( $query_vars );

			if ( ! empty( $posts ) ) {
				$query = array(
					'p'         => $posts[0]->ID,
					'post_type' => 'lp_course',
				);

				$course = learn_press_get_course( $posts[0]->ID );
			}
		}

		if ( ! empty( $course ) ) {
			$course_items = $course->get_items();

			if ( ! empty( $course_items ) ) {
				$lp_course_item = $course->get_item( $course_items[0] );
			}
		}

		if ( ! empty( $query ) ) {
			\Elementor\Plugin::instance()->db->switch_to_query( $query, true );
		}
	}

	public function after_preview_query() {
		parent::after_preview_query();

		global $lp_course_item;

		if ( ! empty( $lp_course_item ) ) {
			$lp_course_item = null;
		}
	}

	public function is( $condition ) {
		if ( ! class_exists( '\LearnPress' ) ) {
			return false;
		}

		$is_single_item = is_singular( 'lp_course' ) && \LP_Global::course_item();

		switch ( $condition['type'] ) {
			case 'all':
				return $is_single_item;
			case 'all_quiz':
				return $is_single_item && \LP_Global::course_item()->get_item_type() === 'lp_quiz';
			case 'all_lesson':
				return $is_single_item && \LP_Global::course_item()->get_item_type() === 'lp_lesson';
			case 'all_assignment':
				return $is_single_item && \LP_Global::course_item()->get_item_type() === 'lp_assignment';
			case 'all_h5p':
				return $is_single_item && \LP_Global::course_item()->get_item_type() === 'lp_h5p';
		}

		return false;
	}

	public function priority( $type ) {
		$priority = 100;

		switch ( $type ) {
			case 'all':
				$priority = 10;
				break;
			case 'all_quiz':
			case 'all_lesson':
			case 'all_assignment':
			case 'all_h5p':
				$priority = 20;
				break;
		}

		return apply_filters( 'thim_ekit_pro/condition/priority', $priority, $type );
	}

	public function get_conditions() {
		$conditions = array(
			array(
				'label'    => esc_html__( 'All item', 'thim-elementor-kit' ),
				'value'    => 'all',
				'is_query' => false,
			),
			array(
				'label'    => esc_html__( 'All Quiz', 'thim-elementor-kit' ),
				'value'    => 'all_quiz',
				'is_query' => false,
			),
			array(
				'label'    => esc_html__( 'All Lesson', 'thim-elementor-kit' ),
				'value'    => 'all_lesson',
				'is_query' => false,
			),
		);

		if ( class_exists( '\LP_Addon_Assignment_Preload' ) ) {
			$conditions[] = array(
				'label'    => esc_html__( 'All Assignment', 'thim-elementor-kit' ),
				'value'    => 'all_assignment',
				'is_query' => false,
			);
		}

		if ( class_exists( '\LP_Addon_H5p_Preload' ) ) {
			$conditions[] = array(
				'label'    => esc_html__( 'All H5P', 'thim-elementor-kit' ),
				'value'    => 'all_h5p',
				'is_query' => false,
			);
		}

		return $conditions;
	}

	public function enqueue_scripts() {
		$id = get_the_ID();

		if ( empty( $id ) || ! class_exists( '\LearnPress' ) ) {
			return;
		}

		$type = get_post_meta( $id, Custom_Post_Type::TYPE, true );

		if ( ( \Elementor\Plugin::instance()->preview->is_preview_mode( $id ) || is_preview() || isset( $_GET['thim_elementor_kit'] ) ) && $type === $this->tab ) {
			wp_enqueue_style( 'learnpress' );
		}
	}

	public function before_create_template( $request ) {
		if ( $request['type'] === $this->tab ) {
			$is_active = \Elementor\Plugin::instance()->experiments->is_feature_active( NestedElementsModule::EXPERIMENT_NAME );

			if ( ! $is_active ) {
				throw new \Exception( sprintf( __( 'Please enable <b>Nested Elements</b> in Elementor > Settings > Features: %s', 'thim-elementor-kit' ), '<a href="' . admin_url( 'admin.php?page=elementor#tab-experiments' ) . '" target="_blank" rel="noopener">' . __( 'Go to Settings', 'thim-elementor-kit' ) . '</a>' ) );
			}
		}
	}

	public function create_template( $post_id, $request ) {
		if ( $request['type'] === $this->tab && empty( $request['layout']['id'] ) ) {
			// Add widget thim-ekits-course-item-section when create template in elementor.
			$elementor = \Elementor\Plugin::instance();

			$document = $elementor->documents->get( $post_id );

			if ( $document ) {
				$default_widget = array(
					array(
						'id'       => Utils::generate_random_string(),
						'elType'   => 'container',
						'settings' => array(
							'content_width' => 'full',
						),
						'elements' => array(
							array(
								'id'         => Utils::generate_random_string(),
								'widgetType' => 'thim-ekits-course-item-section',
								'elType'     => 'widget',
								'settings'   => array(),
								'elements'   => array(
									array(
										'id'       => Utils::generate_random_string(),
										'elType'   => 'container',
										'settings' => array(
											'_title'      => __( 'Header', 'thim-elementor-kit' ),
											'css_classes' => 'ekit-popup-header',
										),
									),
									array(
										'id'       => Utils::generate_random_string(),
										'elType'   => 'container',
										'settings' => array(
											'_title'      => __( 'Sidebar', 'thim-elementor-kit' ),
											'css_classes' => 'ekit-popup-sidebar',
										),
									),
									array(
										'id'       => Utils::generate_random_string(),
										'elType'   => 'container',
										'settings' => array(
											'_title'      => __( 'Content', 'thim-elementor-kit' ),
											'css_classes' => 'ekit-popup-content',
											'_element_id' => 'popup-content',
										),
									),
									array(
										'id'       => Utils::generate_random_string(),
										'elType'   => 'container',
										'settings' => array(
											'_title'      => __( 'Footer', 'thim-elementor-kit' ),
											'css_classes' => 'ekit-popup-footer',
										),
									),
								),
							),
						),
					),
				);

				$document->save( array( 'elements' => $default_widget ) );
			}
		}
	}
}
Init::instance();
