<?php
namespace Thim_EL_Kit;

use Elementor\Controls_Manager;
use Thim_EL_Kit\Elementor\Controls\Controls_Manager as Thim_Controls_Manager;

class Elementor {
	use SingletonTrait;

	const CATEGORY                    = 'thim_ekit';
	const CATEGORY_RECOMMENDED        = 'thim_ekit_recommended';
	const CATEGORY_ARCHIVE_POST       = 'thim_ekit_archive_post';
	const CATEGORY_SINGLE_POST        = 'thim_ekit_single_post';
	const CATEGORY_ARCHIVE_PRODUCT    = 'thim_ekit_archive_product';
	const CATEGORY_SINGLE_PRODUCT     = 'thim_ekit_single_product';
	const CATEGORY_ARCHIVE_COURSE     = 'thim_ekit_archive_course';
	const CATEGORY_SINGLE_COURSE      = 'thim_ekit_single_course';
	const CATEGORY_SINGLE_COURSE_ITEM = 'thim_ekit_single_course_items';

	public function __construct() {
		$this->includes();

		// Register Controls
		add_action( 'elementor/controls/register', array( $this, 'register_controls' ), 11 );

		add_action( 'elementor/documents/register_controls', array( $this, 'register_documents' ) );
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ), 10, 1 );
		add_filter( 'lp/rest/ajax/allow_callback', [ $this, 'register_callback_ajax' ] );
		// Additional Animations
		add_filter( 'elementor/controls/animations/additional_animations', [ $this, 'extra_animations' ], 10 );
 	}

	public function includes() {
		// Widgets
		include_once THIM_EKIT_PLUGIN_PATH . 'inc/elementor/class-widgets.php';

		// Controls
		include_once THIM_EKIT_PLUGIN_PATH . 'inc/elementor/controls/control-manager.php';
		include_once THIM_EKIT_PLUGIN_PATH . 'inc/elementor/controls/autocomplete.php';
		include_once THIM_EKIT_PLUGIN_PATH . 'inc/elementor/controls/image-select.php';
		include_once THIM_EKIT_PLUGIN_PATH . 'inc/elementor/controls/select2.php';

		// Custom Css Control
		include_once THIM_EKIT_PLUGIN_PATH . 'inc/elementor/custom-css/class-custom-css.php';

		// Library
		include_once THIM_EKIT_PLUGIN_PATH . 'inc/elementor/library/class-init.php';

		// Dynamic Tags
		include_once THIM_EKIT_PLUGIN_PATH . 'inc/elementor/dynamic-tags/class-init.php';

		// Custom hooks
		include_once THIM_EKIT_PLUGIN_PATH . 'inc/elementor/class-hooks.php';
	}

	public function register_documents( $document ) {
		if ( get_the_ID() ) {
			$type = get_post_meta( get_the_ID(), Custom_Post_Type::TYPE, true );

			$post_type = '';

			if ( $type === 'single-post' ) {
				$post_type = 'post';
			}

			if ( class_exists( 'WooCommerce' ) && $type === 'single-product' ) {
				$post_type = 'product';
			}

			if ( class_exists( 'LearnPress' ) && in_array( $type, array( 'single-course', 'single-course-item' ) ) ) {
				$post_type = 'lp_course';
			}

			$post_type = apply_filters( 'thim_ekit/elementor/documents/preview_item', $post_type, $type );

			if ( ! empty( $post_type ) ) {
				$document->start_controls_section(
					'preview_settings',
					array(
						'label' => esc_html__( 'Preview Settings', 'thim-elementor-kit' ),
						'tab'   => Controls_Manager::TAB_SETTINGS,
					)
				);

				if ( $type === 'loop_item' ) {
					$document->add_responsive_control(
						'preview_width',
						array(
							'label'      => esc_html__( 'Width', 'thim-elementor-kit' ),
							'type'       => \Elementor\Controls_Manager::SLIDER,
							'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
							'range'      => array(
								'px' => array(
									'min' => 200,
									'max' => 1140,
								),
							),
							'selectors'  => array(
								'{{WRAPPER}}' => '--thim-preview-width: {{SIZE}}{{UNIT}};',
							),
						)
					);
				}

				$document->add_control(
					'thim_ekits_preview_id',
					array(
						'label'       => esc_html__( 'Search & Select', 'thim-elementor-kit' ),
						'type'        => Thim_Controls_Manager::AUTOCOMPLETE,
						'rest_action' => 'get-posts?post_type=' . $post_type,
						'label_block' => true,
					)
				);

				$document->add_control(
					'thim_ekits_apply_preview',
					array(
						'type'      => Controls_Manager::BUTTON,
						'label'     => '',
						'text'      => esc_html__( 'Save & Preview', 'thim-elementor-kit' ),
						'separator' => 'none',
						'event'     => 'thimELKitsPreview',
					)
				);

				$document->end_controls_section();
			}
		}
	}

	public function register_category( \Elementor\Elements_Manager $elements_manager ) {
		$categories = apply_filters(
			'thim_ekit_elementor_category',
			array(
				self::CATEGORY_ARCHIVE_POST       => array(
					'title' => esc_html__( 'Thim Archive Post', 'thim-elementor-kit' ),
					'icon'  => 'fa fa-plug',
				),
				self::CATEGORY_SINGLE_POST        => array(
					'title' => esc_html__( 'Thim Single Post', 'thim-elementor-kit' ),
					'icon'  => 'fa fa-plug',
				),
				self::CATEGORY_ARCHIVE_PRODUCT    => array(
					'title' => esc_html__( 'Thim Archive Product', 'thim-elementor-kit' ),
					'icon'  => 'fa fa-plug',
				),
				self::CATEGORY_SINGLE_PRODUCT     => array(
					'title' => esc_html__( 'Thim Single Product', 'thim-elementor-kit' ),
					'icon'  => 'fa fa-plug',
				),
				self::CATEGORY_ARCHIVE_COURSE     => array(
					'title' => esc_html__( 'Thim Archive Course', 'thim-elementor-kit' ),
					'icon'  => 'fa fa-plug',
				),
				self::CATEGORY_SINGLE_COURSE      => array(
					'title' => esc_html__( 'Thim Single Course', 'thim-elementor-kit' ),
					'icon'  => 'fa fa-plug',
				),
				self::CATEGORY_SINGLE_COURSE_ITEM => array(
					'title' => esc_html__( 'Thim Single Course Item', 'thim-elementor-kit' ),
					'icon'  => 'fa fa-plug',
				),
				self::CATEGORY_RECOMMENDED        => array(
					'title' => esc_html__( 'Thim Recommended', 'thim-elementor-kit' ),
					'icon'  => 'fa fa-plug',
				),
				self::CATEGORY                    => array(
					'title' => esc_html__( 'Thim Basic', 'thim-elementor-kit' ),
					'icon'  => 'fa fa-plug',
				),
			)
		);

		$old_categories = $elements_manager->get_categories();
		$categories     = array_merge( $categories, $old_categories );

		$set_categories = function ( $categories ) {
			$this->categories = $categories;
		};

		$set_categories->call( $elements_manager, $categories );
	}

	public function register_controls( $controls_manager ) {
		$controls_manager->register( new \Thim_EL_Kit\Elementor\Controls\Autocomplete() );
		$controls_manager->register( new \Thim_EL_Kit\Elementor\Controls\Image_Select() );
		$controls_manager->register( new \Thim_EL_Kit\Elementor\Controls\Select2() );
	}

	public function register_widgets( $widgets_manager ) {
		return \Thim_EL_Kit\Elementor\Widgets::instance()->register_widgets( $widgets_manager );
	}

	public static function get_cat_taxonomy( $taxomony = 'category', $cats = false, $id = true ) {
		if ( ! $cats ) {
			$cats = array();
		}
		$terms = new \WP_Term_Query(
			array(
				'taxonomy'     => $taxomony,
				'pad_counts'   => 1,
				'hierarchical' => 1,
				'hide_empty'   => 1,
				'orderby'      => 'name',
				'menu_order'   => true,
			)
		);

		if ( is_wp_error( $terms ) ) {
		} else {
			if ( empty( $terms->terms ) ) {
			} else {
				foreach ( $terms->terms as $term ) {
					$prefix = '';
					if ( $term->parent > 0 ) {
						$prefix = '--';
					}
					if ( $id ) {
						$cats[ $term->term_id ] = $prefix . $term->name;
					} else {
						$cats[ $term->slug ] = $prefix . $term->name;
					}
				}
			}
		}

		return $cats;
	}

	public static function register_options_courses_meta_data() {
		$opt                  = array();
		$opt['duration']      = esc_html__( 'Duration', 'thim-elementor-kit' );
		$opt['level']         = esc_html__( 'Level', 'thim-elementor-kit' );
		$opt['count_lesson']  = esc_html__( 'Count Lesson', 'thim-elementor-kit' );
		$opt['count_quiz']    = esc_html__( 'Count Quiz', 'thim-elementor-kit' );
		$opt['count_student'] = esc_html__( 'Count Student', 'thim-elementor-kit' );
		$opt['instructor']    = esc_html__( 'Instructor', 'thim-elementor-kit' );
		$opt['category']      = esc_html__( 'Category', 'thim-elementor-kit' );
		$opt['tag']           = esc_html__( 'Tag', 'thim-elementor-kit' );
		$opt['price']         = esc_html__( 'Price', 'thim-elementor-kit' );

		return apply_filters( 'learn-thim-kits-lp-meta-data', $opt );
	}

	public static function register_option_dynamic_tags_item_terms() {
		$options             = [ '' => esc_html__( 'Select...', 'thim-elementor-kit' ), ];

		$options['category'] = esc_html__( 'Categories', 'thim-elementor-kit' );
		$options['tags']     = esc_html__( 'Tags', 'thim-elementor-kit' );

		if ( class_exists( 'LearnPress' ) ) {
			$options['course_category'] = esc_html__( 'Course categories', 'thim-elementor-kit' );
			$options['course_tag'] = esc_html__( 'Course tags', 'thim-elementor-kit' );
		}

		if ( class_exists( 'WooCommerce' ) ) {
			$options['product_cat'] = esc_html__( 'Product categories', 'thim-elementor-kit' );
			$options['product_tag'] = esc_html__( 'Product tags', 'thim-elementor-kit' );
		}

		return apply_filters( 'thim-ekits\dynamic-tags\item-terms', $options );
	}

	public function get_tab_options() {
		$tab_options = array(
			'overview'   => esc_html__( 'Overview', 'thim-elementor-kit' ),
			'curriculum' => esc_html__( 'Curriculum', 'thim-elementor-kit' ),
			'faqs'       => esc_html__( 'FAQs', 'thim-elementor-kit' ),
			'instructor' => esc_html__( 'Instructor', 'thim-elementor-kit' ),
 			'materials'     => esc_html__( 'Materials', 'learnpress' ),
		);

		if ( class_exists( '\LP_Addon_Announcements' ) ) {
			$tab_options['announcements'] = esc_html__( 'Announcements', 'thim-elementor-kit' );
		}

		if ( class_exists( '\LP_Addon_Course_Review' ) ) {
			$tab_options['reviews'] = esc_html__( 'Reviews', 'thim-elementor-kit' );
		}

		if ( class_exists( '\LP_Addon_Students_List' ) ) {
			$tab_options['students-list'] = esc_html__( 'Students List', 'thim-elementor-kit' );
		}

		if ( class_exists( '\LP_Addon_Upsell_Preload' ) ) {
			$tab_options['package'] = esc_html__( 'Package', 'thim-elementor-kit' );
		}

		return apply_filters( 'thim_ekits_learnpress_tab_options', $tab_options );
	}

	public function register_callback_ajax( $callbacks ) {
		$callbacks[] = 'Elementor\Thim_Ekit_Widget_Archive_Course:render_courses';
		return $callbacks;
	}

	/**
	 *
	 * extra animation
	 * @since  1.0.0
	 * @access public
	 */
	public function extra_animations( $animations = array() ) {
		$extra_animations = array(
			__( 'Thim eKit Animations', 'thim-elementor-kit' ) => [
				'ekit--scale'       => __( 'Scale', 'thim-elementor-kit' ),
				'ekit--fancy'       => __( 'Fancy', 'thim-elementor-kit' ),
				'ekit--slide-up'    => __( 'Slide Up', 'thim-elementor-kit' ),
				'ekit--slide-left'  => __( 'Slide Left', 'thim-elementor-kit' ),
				'ekit--slide-right' => __( 'Slide Right', 'thim-elementor-kit' ),
				'ekit--slide-down'  => __( 'Slide Down', 'thim-elementor-kit' )
			]
		);

		return array_merge( $animations, $extra_animations );
	}
}

Elementor::instance();
