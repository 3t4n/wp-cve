<?php
namespace Elementor;

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

class Thim_Ekit_Widget_Breadcrumb extends Widget_Base {

	protected $crumbs = array();

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-breadcrumb';
	}

	public function get_title() {
		return esc_html__( 'Breadcrumb', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-product-breadcrumbs';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY );
	}

	public function get_keywords() {
		return [
			'thim',
			'breadcrumb',
			'breadcrumbs',
		];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'thim-elementor-kit' ),
			)
		);
		$this->add_control(
			'delimiter',
			[
				'label'       => esc_html__( 'Delimiter', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::ICONS,
				'label_block' => false,
				'skin'        => 'inline',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .thim-ekit-breadcrumb , {{WRAPPER}} .thim-ekit-breadcrumb  a',
			)
		);
		$this->add_control(
			'text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-breadcrumb , {{WRAPPER}} .thim-ekit-breadcrumb  > *' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => esc_html__( 'Link Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-breadcrumb  > a' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'link_color_hover',
			array(
				'label'     => esc_html__( 'Link Color Hover', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-breadcrumb  > a:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'delimiter_color',
			array(
				'label'     => esc_html__( 'Delimiter Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-breadcrumb  > .thim-ekit-breadcrumb__delimiter' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'delimiter_size',
			[
				'label'     => esc_html__( 'Delimiter Size', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .thim-ekit-breadcrumb  > .thim-ekit-breadcrumb__delimiter i'   => 'font-size: {{SIZE}}px;',
					'{{WRAPPER}} .thim-ekit-breadcrumb  > .thim-ekit-breadcrumb__delimiter svg' => 'width: {{SIZE}}px;',
				],
			]
		);

		$this->add_responsive_control(
			'delimiter_margin',
			[
				'label'      => esc_html__( 'Margin Delimiter', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .thim-ekit-breadcrumb  > .thim-ekit-breadcrumb__delimiter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekit-breadcrumb ' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();
	}

	public function render() {
		$settings = $this->get_settings_for_display();

		$this->add_crumb( _x( 'Home', 'breadcrumb', 'thim-elementor-kit' ), apply_filters( 'thim_ekit_breadcrumb_home_url', home_url() ) );

		$breadcrumb = $this->generate();

//		$delimiter = ! empty( $settings['delimiter'] ) ? $settings['delimiter'] : '&nbsp;&#47;&nbsp;';

		do_action( 'thim_ekit/elementor/widgets/breadcrumb', $breadcrumb );

		if ( ! empty( $breadcrumb ) ) {
			?>

			<div class="thim-ekit-breadcrumb">

				<?php
				foreach ( $breadcrumb as $key => $crumb ) {

					if ( ! empty( $crumb[1] ) && sizeof( $breadcrumb ) !== $key + 1 ) {
						echo '<a href="' . esc_url( $crumb[1] ) . '">' . esc_html( $crumb[0] ) . '</a>';
					} else {
						if ( isset( $_GET['c_search'] ) ) {
							$text = sprintf(
								'%s %s',
								__( 'Search results for: ', 'thim-elementor-kit' ),
								esc_html( $_GET['c_search'] )
							);
							echo esc_html( $text );
						} else {
							echo esc_html( $crumb[0] );
						}
					}

					if ( sizeof( $breadcrumb ) !== $key + 1 ) {
						echo '<span class="thim-ekit-breadcrumb__delimiter">';
						if ( ! empty( $settings['delimiter'] ) ) {
							Icons_Manager::render_icon( $settings ['delimiter'], [ 'aria-hidden' => 'true' ] );
						} else {
							echo '&nbsp;&#47;&nbsp;';
						}
						echo '</span>';
					}
				}
				?>

			</div>

			<?php
		}
	}

	public function add_crumb( $name, $link = '' ) {
		$this->crumbs[] = array( wp_strip_all_tags( $name ), $link );
	}

	public function reset() {
		$this->crumbs = array();
	}


	public function get_breadcrumb() {
		return apply_filters( 'thim_ekit_get_breadcrumb', $this->crumbs, $this );
	}

	/**
	 * Generate breadcrumb trail.
	 *
	 * @return array of breadcrumbs
	 */
	public function generate() {
		$conditionals = array(
			'is_home',
			'is_404',
			'is_attachment',
			'is_single',
			'learn_press_is_course_category',
			'learn_press_is_course_tag',
			'learn_press_is_courses',
			'is_product_category',
			'is_product_tag',
			'is_shop',
			'is_page',
			'is_post_type_archive',
			'is_category',
			'is_tag',
			'is_author',
			'is_date',
			'is_tax',
		);

		if ( ! is_front_page() || is_paged() ) {
			foreach ( $conditionals as $conditional ) {
				if ( function_exists( $conditional ) && call_user_func( $conditional ) ) {
					if ( strpos( $conditional, 'learn_press' ) !== false ) {
						call_user_func( array( $this, 'add_crumbs_' . substr( $conditional, 15 ) ) );
					} else {
						call_user_func( array( $this, 'add_crumbs_' . substr( $conditional, 3 ) ) );
					}
					break;
				}
			}

			$this->search_trail();
			$this->paged_trail();

			return $this->get_breadcrumb();
		}

		return array();
	}

	/**
	 * Prepend the shop page to shop breadcrumbs.
	 */
	protected function prepend_shop_page() {
		$permalinks   = wc_get_permalink_structure();
		$shop_page_id = wc_get_page_id( 'shop' );
		$shop_page    = get_post( $shop_page_id );

		// If permalinks contain the shop page in the URI prepend the breadcrumb with shop.
		if ( $shop_page_id && $shop_page && isset( $permalinks['product_base'] ) && strstr( $permalinks['product_base'], '/' . $shop_page->post_name ) && intval( get_option( 'page_on_front' ) ) !== $shop_page_id ) {
			$this->add_crumb( get_the_title( $shop_page ), get_permalink( $shop_page ) );
		}
	}

	/**
	 * Is home trail..
	 */
	protected function add_crumbs_home() {
		$this->add_crumb( single_post_title( '', false ) );
	}

	/**
	 * 404 trail.
	 */
	protected function add_crumbs_404() {
		$this->add_crumb( esc_html__( 'Error 404', 'thim-elementor-kit' ) );
	}

	/**
	 * Attachment trail.
	 */
	protected function add_crumbs_attachment() {
		global $post;

		$this->add_crumbs_single( $post->post_parent, get_permalink( $post->post_parent ) );
		$this->add_crumb( get_the_title(), get_permalink() );
	}

	/**
	 * Single post trail.
	 *
	 * @param int    $post_id   Post ID.
	 * @param string $permalink Post permalink.
	 */
	protected function add_crumbs_single( $post_id = 0, $permalink = '' ) {
		if ( ! $post_id ) {
			global $post;
		} else {
			$post = get_post( $post_id );
		}

		if ( ! $permalink ) {
			$permalink = get_permalink( $post );
		}

		if ( 'lp_course' === get_post_type( $post ) ) {
			$terms = wp_get_post_terms(
				$post->ID,
				'course_category',
				array(
					'orderby' => 'parent',
					'order'   => 'DESC',
				)
			);

			if ( $terms ) {
				$main_term = apply_filters( 'thim_ekit_breadcrumb_main_term', $terms[0], $terms );
				$this->term_ancestors( $main_term->term_id, 'course_category' );
				$this->add_crumb( $main_term->name, get_term_link( $main_term ) );
			}
		} elseif ( 'product' === get_post_type( $post ) ) {
			$this->prepend_shop_page();

			$terms = wc_get_product_terms(
				$post->ID,
				'product_cat',
				apply_filters(
					'woocommerce_breadcrumb_product_terms_args',
					array(
						'orderby' => 'parent',
						'order'   => 'DESC',
					)
				)
			);

			if ( $terms ) {
				$main_term = apply_filters( 'woocommerce_breadcrumb_main_term', $terms[0], $terms );
				$this->term_ancestors( $main_term->term_id, 'product_cat' );
				$this->add_crumb( $main_term->name, get_term_link( $main_term ) );
			}
		} elseif ( 'post' !== get_post_type( $post ) ) {
			$post_type = get_post_type_object( get_post_type( $post ) );

			if ( ! empty( $post_type->has_archive ) ) {
				$this->add_crumb( $post_type->labels->singular_name, get_post_type_archive_link( get_post_type( $post ) ) );
			}
		} else {
			$cat = current( get_the_category( $post ) );
			if ( $cat ) {
				$this->term_ancestors( $cat->term_id, 'category' );
				$this->add_crumb( $cat->name, get_term_link( $cat ) );
			}
		}

		$this->add_crumb( get_the_title( $post ), $permalink );
	}

	/**
	 * Page trail.
	 */
	protected function add_crumbs_page() {
		global $post;

		if ( $post->post_parent ) {
			$parent_crumbs = array();
			$parent_id     = $post->post_parent;

			while ( $parent_id ) {
				$page            = get_post( $parent_id );
				$parent_id       = $page->post_parent;
				$parent_crumbs[] = array( get_the_title( $page->ID ), get_permalink( $page->ID ) );
			}

			$parent_crumbs = array_reverse( $parent_crumbs );

			foreach ( $parent_crumbs as $crumb ) {
				$this->add_crumb( $crumb[0], $crumb[1] );
			}
		}

		$this->add_crumb( get_the_title(), get_permalink() );
	}

	protected function prepend_course_page() {
		$course_page_id = learn_press_get_page_id( 'courses' );
		$course_page    = get_post( $course_page_id );

		if ( $course_page_id && $course_page && intval( get_option( 'page_on_front' ) ) !== $course_page_id ) {
			$this->add_crumb( get_the_title( $course_page ), get_permalink( $course_page ) );
		}
	}

	protected function add_crumbs_course_category() {
		$current_term = $GLOBALS['wp_query']->get_queried_object();

		$this->prepend_course_page();
		$this->term_ancestors( $current_term->term_id, 'course_category' );
		$this->add_crumb( $current_term->name, get_term_link( $current_term, 'course_category' ) );
	}

	protected function add_crumbs_course_tag() {
		$current_term = $GLOBALS['wp_query']->get_queried_object();

		$this->prepend_course_page();

		$this->add_crumb( sprintf( esc_html__( 'Course tagged &ldquo;%s&rdquo;', 'thim-elementor-kit' ), $current_term->name ), get_term_link( $current_term, 'course_tag' ) );
	}

	protected function add_crumbs_courses() {
		if ( intval( get_option( 'page_on_front' ) ) === learn_press_get_page_id( 'courses' ) ) {
			return;
		}

		$_name = learn_press_get_page_id( 'courses' ) ? get_the_title( learn_press_get_page_id( 'courses' ) ) : '';

		if ( ! $_name ) {
			$product_post_type = get_post_type_object( 'lp_course' );
			$_name             = $product_post_type->labels->name;
		}

		$this->add_crumb( $_name, get_post_type_archive_link( 'lp_course' ) );
	}

	/**
	 * Product category trail.
	 */
	protected function add_crumbs_product_category() {
		$current_term = $GLOBALS['wp_query']->get_queried_object();

		$this->prepend_shop_page();
		$this->term_ancestors( $current_term->term_id, 'product_cat' );
		$this->add_crumb( $current_term->name, get_term_link( $current_term, 'product_cat' ) );
	}

	/**
	 * Product tag trail.
	 */
	protected function add_crumbs_product_tag() {
		$current_term = $GLOBALS['wp_query']->get_queried_object();

		$this->prepend_shop_page();

		/* translators: %s: product tag */
		$this->add_crumb( sprintf( esc_html__( 'Products tagged &ldquo;%s&rdquo;', 'thim-elementor-kit' ), $current_term->name ), get_term_link( $current_term, 'product_tag' ) );
	}

	/**
	 * Shop breadcrumb.
	 */
	protected function add_crumbs_shop() {
		if ( intval( get_option( 'page_on_front' ) ) === wc_get_page_id( 'shop' ) ) {
			return;
		}

		$_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';

		if ( ! $_name ) {
			$product_post_type = get_post_type_object( 'product' );
			$_name             = $product_post_type->labels->name;
		}

		$this->add_crumb( $_name, get_post_type_archive_link( 'product' ) );
	}

	protected function add_crumbs_post_type_archive() {
		$post_type = get_post_type_object( get_post_type() );

		if ( $post_type ) {
			$this->add_crumb( $post_type->labels->name, get_post_type_archive_link( get_post_type() ) );
		}
	}

	protected function add_crumbs_category() {
		$this_category = get_category( $GLOBALS['wp_query']->get_queried_object() );

		if ( 0 !== intval( $this_category->parent ) ) {
			$this->term_ancestors( $this_category->term_id, 'category' );
		}

		$this->add_crumb( single_cat_title( '', false ), get_category_link( $this_category->term_id ) );
	}

	protected function add_crumbs_tag() {
		$queried_object = $GLOBALS['wp_query']->get_queried_object();

		$this->add_crumb( sprintf( esc_html__( 'Posts tagged &ldquo;%s&rdquo;', 'thim-elementor-kit' ), single_tag_title( '', false ) ), get_tag_link( $queried_object->term_id ) );
	}

	protected function add_crumbs_date() {
		if ( is_year() || is_month() || is_day() ) {
			$this->add_crumb( get_the_time( 'Y' ), get_year_link( get_the_time( 'Y' ) ) );
		}
		if ( is_month() || is_day() ) {
			$this->add_crumb( get_the_time( 'F' ), get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) );
		}
		if ( is_day() ) {
			$this->add_crumb( get_the_time( 'd' ) );
		}
	}

	protected function add_crumbs_tax() {
		$this_term = $GLOBALS['wp_query']->get_queried_object();
		$taxonomy  = get_taxonomy( $this_term->taxonomy );

		$this->add_crumb( $taxonomy->labels->name );

		if ( 0 !== intval( $this_term->parent ) ) {
			$this->term_ancestors( $this_term->term_id, $this_term->taxonomy );
		}

		$this->add_crumb( single_term_title( '', false ), get_term_link( $this_term->term_id, $this_term->taxonomy ) );
	}

	protected function add_crumbs_author() {
		global $author;

		$userdata = get_userdata( $author );

		$this->add_crumb( sprintf( esc_html__( 'Author: %s', 'thim-elementor-kit' ), $userdata->display_name ) );
	}

	protected function term_ancestors( $term_id, $taxonomy ) {
		$ancestors = get_ancestors( $term_id, $taxonomy );
		$ancestors = array_reverse( $ancestors );

		foreach ( $ancestors as $ancestor ) {
			$ancestor = get_term( $ancestor, $taxonomy );

			if ( ! is_wp_error( $ancestor ) && $ancestor ) {
				$this->add_crumb( $ancestor->name, get_term_link( $ancestor ) );
			}
		}
	}

	protected function search_trail() {
		if ( is_search() ) {
			$this->add_crumb( sprintf( esc_html__( 'Search results for &ldquo;%s&rdquo;', 'thim-elementor-kit' ), get_search_query() ), remove_query_arg( 'paged' ) );
		}
	}

	protected function paged_trail() {
		if ( get_query_var( 'paged' ) ) {
			$this->add_crumb( sprintf( esc_html__( 'Page %d', 'thim-elementor-kit' ), get_query_var( 'paged' ) ) );
		}
	}
}
