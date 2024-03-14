<?php
/**
 * Portfolio Style Addon
 *
 * @package AbsoluteAddons
 */

namespace AbsoluteAddons\Widgets;

use AbsoluteAddons\Absp_Widget;
use AbsoluteAddons\Controls\Absp_Control_Styles;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Utils;
use WP_Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

/**
 * Class definition.
 *
 * @since 1.1.0
 */
class Absoluteaddons_Style_Portfolio extends Absp_Widget {

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_name() {
		return 'absolute-portfolio';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Portfolio', 'absolute-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'absp eicon-folder-o';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [
			'absolute-addons-custom',
			'absolute-addons-core',
			'font-awesome',
			'ico-font',
			'jquery.fancybox',
			'absp-portfolio',
			'absp-pro-portfolio',
		];
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array(
			'swiper-slider',
			'filterizr',
			'jquery.fancybox',
			'absolute-addons-core',
			'absp-portfolio',
			'jquery.isotope'
		);
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @return array Widget categories.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_categories() {
		return [ 'absp-widgets' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function register_controls() {

		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Portfolio $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/starts' ), [ &$this ] );

		$this->start_controls_section( 'section_content', [ 'label' => __( 'Template', 'absolute-addons' ) ] );

		$portfolio = apply_filters( $this->get_prefixed_hook( 'styles' ), [
			'one'       => esc_html__( 'Design Style One', 'absolute-addons' ),
			'two'       => esc_html__( 'Design Style Two', 'absolute-addons' ),
			'three'     => esc_html__( 'Design Style Three', 'absolute-addons' ),
			'four'      => esc_html__( 'Design Style Four', 'absolute-addons' ),
			'five-pro'  => esc_html__( 'Design Style Five Pro', 'absolute-addons' ),
			'six'       => esc_html__( 'Design Style Six', 'absolute-addons' ),
			'seven-pro' => esc_html__( 'Design Style Seven Pro', 'absolute-addons' ),
			'eight-pro' => esc_html__( 'Design Style Eight Pro', 'absolute-addons' ),
			'nine'      => esc_html__( 'Design Style Nine', 'absolute-addons' ),
			'ten'       => esc_html__( 'Design Style Ten', 'absolute-addons' ),
		] );

		$this->add_control(
			'absolute_portfolio',
			[
				'label'       => esc_html__( 'Portfolio Style', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Absp_Control_Styles::TYPE,
				'options'     => $portfolio,
				'default'     => 'one',
			]
		);

		// @FIXME Separate pro items.
		$this->init_pro_alert( [
			'five-pro',
			'seven-pro',
			'eight-pro',
		] );

		$this->end_controls_section();

		$this->layout_section();

		$this->filter_section();

		$this->post_query_section();

		$this->style_controllers();

		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Portfolio $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/ends' ), [ &$this ] );
	}

	protected function layout_section() {
		$this->start_controls_section(
			'portfolio_layout_section',
			[
				'label' => __( 'Portfolio Layout', 'absolute-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'           => 'portfolio_feature_img',
				'fields_options' => [
					'size' => [
						'label' => esc_html__( 'Featured Image Size', 'absolute-addons' ),
					],
				],
				'exclude'        => [ 'custom' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
				'default'        => 'large',
			]
		);

		$this->add_control(
			'portfolio_link',
			[
				'label'   => esc_html__( 'Link', 'absolute-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'media' => esc_html__( 'Image Popup', 'absolute-addons' ),
					'page'  => esc_html__( 'Portfolio Page', 'absolute-addons' ),
					''      => esc_html__( 'None', 'absolute-addons' ),
				],
				'default' => 'media',
			]
		);

		$this->add_control(
			'portfolio_button',
			[
				'label'     => esc_html__( 'Button', 'absolute-addons' ),
				'type'      => Controls_Manager::SWITCHER,
				'options'   => [
					'yes'  => esc_html__( 'Yes', 'absolute-addons' ),
					'none' => esc_html__( 'none', 'absolute-addons' ),
				],
				'default'   => 'yes',
				'condition' => [
					'portfolio_link'     => [ 'media', 'page' ],
					'absolute_portfolio' => [ 'two', 'five', 'six' ],
				],
			]
		);

		$this->add_control(
			'portfolio_button_text',
			[
				'label'     => esc_html__( 'Button Text', 'absolute-addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'View', 'absolute-addons' ),
				'condition' => [
					'portfolio_button' => [ 'yes' ],
				],
			]
		);

		$this->add_control(
			'hover_effect',
			[
				'label'   => esc_html__( 'Hover Effect', 'absolute-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'simple'                  => esc_html__( 'Simple', 'absolute-addons' ),
					'fade'                    => esc_html__( 'Fade', 'absolute-addons' ),
					'text_top_left'           => esc_html__( 'Text Top Left', 'absolute-addons' ),
					'text_top_center'         => esc_html__( 'Text Top Center', 'absolute-addons' ),
					'text_top_right'          => esc_html__( 'Text Top Right', 'absolute-addons' ),
					'text_bottom_left'        => esc_html__( 'Text Bottom Left', 'absolute-addons' ),
					'text_bottom_center'      => esc_html__( 'Text Bottom Center', 'absolute-addons' ),
					'text_bottom_right'       => esc_html__( 'Text Bottom Right', 'absolute-addons' ),
					'card_text_left'          => esc_html__( 'Card Text Left', 'absolute-addons' ),
					'card_text_top_left'      => esc_html__( 'Card Text Top Left', 'absolute-addons' ),
					'card_text_top_center'    => esc_html__( 'Card Text Top Center', 'absolute-addons' ),
					'card_text_top_right'     => esc_html__( 'Card Text Top Right', 'absolute-addons' ),
					'card_text_right'         => esc_html__( 'Card Text Right', 'absolute-addons' ),
					'card_text_bottom_left'   => esc_html__( 'Card Text Bottom Left', 'absolute-addons' ),
					'card_text_bottom_center' => esc_html__( 'Card Text Bottom Center', 'absolute-addons' ),
					'card_text_bottom_right'  => esc_html__( 'Card Text Bottom Right', 'absolute-addons' ),
					'sweep_to_top'            => esc_html__( 'Sweep To Top', 'absolute-addons' ),
					'sweep_to_bottom'         => esc_html__( 'Sweep To Bottom', 'absolute-addons' ),
					'sweep_to_left'           => esc_html__( 'Sweep To Left', 'absolute-addons' ),
					'sweep_to_right'          => esc_html__( 'Sweep To Right', 'absolute-addons' ),
					'bounce_to_top'           => esc_html__( 'Bounce To Top', 'absolute-addons' ),
					'bounce_to_right'         => esc_html__( 'Bounce To Right', 'absolute-addons' ),
					'bounce_to_left'          => esc_html__( 'Bounce To Left', 'absolute-addons' ),
					'bounce_to_bottom'        => esc_html__( 'Bounce To Bottom', 'absolute-addons' ),
					'radial_out'              => esc_html__( 'Radial Out', 'absolute-addons' ),
					'rectangle_out'           => esc_html__( 'Rectangle Out', 'absolute-addons' ),
					'shutter_out_horizontal'  => esc_html__( 'Shutter Out Horizontal', 'absolute-addons' ),
					'shutter_out_vertical'    => esc_html__( 'Shutter Out Vertical', 'absolute-addons' ),
				],
				'default' => 'text_bottom_left',
			]
		);

		$this->add_control(
			'hover_image_effect',
			[
				'label'   => esc_html__( 'Image Hover Effect', 'absolute-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'simple'        => esc_html__( 'Simple', 'absolute-addons' ),
					'rotate'        => esc_html__( 'Rotate', 'absolute-addons' ),
					'zoomin'        => esc_html__( 'Zoom In', 'absolute-addons' ),
					'zoomin_rotate' => esc_html__( 'Zoom In and Rotate', 'absolute-addons' ),
					'left'          => esc_html__( 'Image Left', 'absolute-addons' ),
					'right'         => esc_html__( 'Image Right', 'absolute-addons' ),
				],
				'default' => 'zoomin',
			]
		);

		$this->add_control(
			'portfolio_filter_layout',
			[
				'label'     => esc_html__( 'Filter Layout', 'absolute-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'one'   => esc_html__( 'Layout One', 'absolute-addons' ),
					'two'   => esc_html__( 'Layout Two', 'absolute-addons' ),
					'three' => esc_html__( 'Layout Three', 'absolute-addons' ),
					'four'  => esc_html__( 'Layout Four', 'absolute-addons' ),
					'five'  => esc_html__( 'Layout Five', 'absolute-addons' ),
					'six'   => esc_html__( 'Layout Six', 'absolute-addons' ),
				],
				'default'   => 'one',
				'condition' => [
					'enable_filter_menu' => [ 'yes' ],
				],
			]
		);

		$this->render_controller( 'three' );

		$this->end_controls_section();
	}

	protected function filter_section() {
		$this->start_controls_section(
			'filter_layout_section',
			array(
				'label' => __( 'Portfolio Filter', 'absolute-addons' ),
			)
		);

		$this->add_control(
			'enable_filter_menu',
			[
				'label'        => esc_html__( 'Enable Filter Menu', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'options'      => [
					'yes' => esc_html__( 'Yes', 'absolute-addons' ),
					'no'  => esc_html__( 'No', 'absolute-addons' ),
				],
				'default'      => 'yes',
				'descriptions' => esc_html__( 'Enable to display filter menu.', 'absolute-addons' ),
			]
		);

		$this->add_control(
			'show_all_filter',
			[
				'label'        => esc_html__( 'Show "All" Filter', 'absolute-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'options'      => [
					'yes' => esc_html__( 'Yes', 'absolute-addons' ),
					'no'  => esc_html__( 'No', 'absolute-addons' ),
				],
				'default'      => 'yes',
				'descriptions' => esc_html__( 'Enable to display "All" filter in filter menu.', 'absolute-addons' ),
				'condition'    => [
					'enable_filter_menu' => 'yes',
				],
			]
		);

		$this->add_control(
			'filter_text',
			[
				'label'     => esc_html__( 'Filter Text', 'absolute-addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'All',
				'condition' => [
					'show_all_filter'    => 'yes',
					'enable_filter_menu' => 'yes',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'filter_layout',
			[
				'label'   => esc_html__( 'Layout', 'absolute-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'sameSize'   => esc_html__( 'Normal', 'absolute-addons' ),
					'sameWidth'  => esc_html__( 'Fixed Width', 'absolute-addons' ),
					'sameHeight' => esc_html__( 'Fixed Height', 'absolute-addons' ),
				],
				'default' => 'sameSize',
			]
		);

		$this->add_control(
			'filter_animation',
			[
				'label'   => esc_html__( 'Portfolio Animation', 'absolute-addons' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '0.5',
			]
		);

		$this->add_control(
			'filter_delay',
			[
				'label'   => esc_html__( 'Portfolio Delay', 'absolute-addons' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => '50',
			]
		);

		$this->add_control(
			'filter_delay_mode',
			[
				'label'   => esc_html__( 'Portfolio Delay Mode', 'absolute-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'progressive' => esc_html__( 'Progressive', 'absolute-addons' ),
					'alternate'   => esc_html__( 'Alternate', 'absolute-addons' ),
				],
				'default' => 'progressive',
			]
		);

		$this->end_controls_section();
	}

	protected function post_query_section() {
		$this->start_controls_section(
			'query_section',
			array(
				'label' => __( 'Query Section', 'absolute-addons' ),
			)
		);

		$this->add_control(
			'number_of_posts',
			[
				'label'        => esc_html__( 'Posts Count', 'absolute-addons' ),
				'type'         => Controls_Manager::NUMBER,
				'min'          => 1,
				'max'          => 200,
				'step'         => 1,
				'default'      => 8,
				'descriptions' => esc_html__( 'If You need to show all post to input "-1"', 'absolute-addons' ),
			]
		);

		$this->add_control(
			'select_portfolio_post',
			[
				'label'    => esc_html__( 'Select Category', 'absolute-addons' ),
				'type'     => Controls_Manager::SELECT,
				'multiple' => true,
				'options'  => [
					'select_post' => esc_html__( 'Selected Post', 'absolute-addons' ),
					'category'    => esc_html__( 'Category Post', 'absolute-addons' ),
				],
				'default'  => 'select_post',
			]
		);

		$all_terms = get_terms( 'portfolio_category', [
			'hide_empty' => false,
		] );


		$args = [
			'post_type'      => 'portfolio',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
		];

// we get an array of posts objects
		$posts = get_posts( $args );


		$portfolio_terms = [];
		$portfolio_post  = [];

		foreach ( (array) $all_terms as $single_terms ) {
			$portfolio_terms[ $single_terms->slug . '|' . $single_terms->name ] = $single_terms->name;
		}

		foreach ( (array) $posts as $single_post ) {
			$portfolio_post[ $single_post->ID . '|' . $single_post->post_title ] = $single_post->post_title;
		}


		$this->add_control(
			'portfolio_category_post',
			[
				'label'     => esc_html__( 'Select Category', 'absolute-addons' ),
				'type'      => Controls_Manager::SELECT2,
				'multiple'  => true,
				'options'   => $portfolio_terms,
				'condition' => [
					'select_portfolio_post' => [ 'category' ],
				],
			]
		);

		$this->add_control(
			'portfolio_select_post',
			[
				'label'     => esc_html__( 'Select Post', 'absolute-addons' ),
				'type'      => Controls_Manager::SELECT2,
				'multiple'  => true,
				'options'   => $portfolio_post,
				'condition' => [
					'select_portfolio_post' => [ 'select_post' ],
				],
			]
		);

		$this->add_control(
			'portfolio_posts_offset',
			[
				'label'   => esc_html__( 'Offset', 'absolute-addons' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 20,
				'default' => 0,
			]
		);

		$this->add_control(
			'portfolio_posts_order_by',
			[
				'label'   => esc_html__( 'Order by', 'absolute-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'date'          => esc_html__( 'Date', 'absolute-addons' ),
					'title'         => esc_html__( 'Title', 'absolute-addons' ),
					'author'        => esc_html__( 'Author', 'absolute-addons' ),
					'modified'      => esc_html__( 'Modified', 'absolute-addons' ),
					'comment_count' => esc_html__( 'Comments', 'absolute-addons' ),
				],
				'default' => 'date',
			]
		);

		$this->add_control(
			'portfolio_posts_sort',
			[
				'label'   => esc_html__( 'Order', 'absolute-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'ASC'  => esc_html__( 'ASC', 'absolute-addons' ),
					'DESC' => esc_html__( 'DESC', 'absolute-addons' ),
				],
				'default' => 'DESC',
			]
		);

		$this->end_controls_section();
	}

	protected function style_controllers() {
		$this->render_controller( 'template-portfolio-style' );
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$controls_selector = wp_unique_id( 'filter-container-' );

		$this->add_inline_editing_attributes( 'portfolio_title', 'basic' );
		$this->add_inline_editing_attributes( 'portfolio_code', 'basic' );
		$this->add_inline_editing_attributes( 'portfolio_highlight_text', 'basic' );

		// class and other attributes.
		$this->add_render_attribute( 'portfolio_title', 'class', 'portfolio-title' );
		$this->add_render_attribute( 'portfolio_code', 'class', 'portfolio-code' );
		$this->add_render_attribute( 'portfolio_highlight_text', 'class', 'portfolio-highlight-text' );
		$this->add_render_attribute( [
			'portfolio_filter' => [
				'class'                   => 'filter-container ' . $settings['filter_layout'],
				'data-controls-selector'  => '.' . $controls_selector,
				'data-animation-duration' => esc_attr( $settings['filter_animation'] ),
				'data-layout'             => esc_attr( $settings['filter_layout'] ),
				'data-delay'              => esc_attr( $settings['filter_delay'] ),
				'data-gutter-pixels'      => '30',
				'data-delay-mode'         => esc_attr( $settings['filter_delay_mode'] ),
			],
		] );
		$style = $settings['absolute_portfolio'];
		?>
		<div class="absp-wrapper">
			<div class="absp-wrapper-inside">
				<div class="absp-wrapper-content">
					<!-- absp-portfolio -->
					<div class="absp-portfolio element-<?php echo esc_attr( $style ); ?>">
						<div class="portfolio-item-<?php echo esc_attr( $style ); ?>">
							<?php $term_slugs = $this->get_filter_menu( $settings, $controls_selector ); ?>
							<div <?php $this->print_render_attribute_string( 'portfolio_filter' ); ?>>
								<?php
								$posts = $this->get_posts( $settings, $term_slugs );
								foreach ( $posts as $post ) {
									$GLOBALS['post'] = $post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
									setup_postdata( $post );
									$portfolio_terms = get_the_terms( get_the_ID(), 'portfolio_category' );
									if ( $portfolio_terms && ! is_wp_error( $portfolio_terms ) ) {
										$portfolio_terms = array_map( function ( $value ) {
											return $value->slug;
										}, $portfolio_terms );
										$portfolio_terms = implode( ' ', $portfolio_terms );
									} else {
										$portfolio_terms = '';
									}

									$figure_link = '';
									$figure_attr = '';

									if ( 'media' == $settings['portfolio_link'] && has_post_thumbnail() ) {
										$figure_link = get_the_post_thumbnail_url( get_the_ID(), $settings['portfolio_feature_img_size'] );
										$figure_attr = ' data-fancybox="gallery"';
									}

									if ( 'page' == $settings['portfolio_link'] && has_post_thumbnail() ) {
										$figure_link = get_the_permalink();
									}

									$effect_classes = 'hover_' . $settings['hover_effect'];
									$effect_classes .= ' image_hover_' . $settings['hover_image_effect'];

									$args = [
										'style'           => $style,
										'figure_link'     => $figure_link,
										'figure_attr'     => $figure_attr,
										'portfolio_terms' => $portfolio_terms,
									];
									?>
									<div class="filtr-item portfolio-wrapper <?php echo esc_attr( $effect_classes. ' '. $portfolio_terms ); ?>" data-category="<?php echo esc_attr( $portfolio_terms ); ?>">
										<?php $this->render_template( $style, $args ); ?>
									</div>
									<?php
								}
								wp_reset_postdata();
								?>
							</div>
						</div>
					</div>
					<!-- absp-portfolio -->
				</div>
			</div>
		</div>
		<?php
	}

	protected function get_filter_menu( $settings = [], $controls_selector = '' ) {
		if ( empty( $settings ) ) {
			$settings = $this->get_settings_for_display();
		}

		// prepare layout classnames.
		$layout_class = 'filters-group';

		if ( ! empty( $settings['portfolio_filter_layout'] ) ) {
			$layout_class .= ' layout-' . $settings['portfolio_filter_layout'];
		}

		$term_slugs = [];

		if ( 'category' === $settings['select_portfolio_post'] && ! empty( $settings['portfolio_category_post'] ) ) {
			$terms = ! is_array( $settings['portfolio_category_post'] ) ? [] : $settings['portfolio_category_post'];
		} else {
			$args = [
				'taxonomy'   => 'portfolio_category',
				'hide_empty' => true,
				'object_ids' => null,
			];
			if ( ! empty( $settings['portfolio_select_post'] ) && is_array( $settings['portfolio_select_post'] ) ) {
				$args['object_ids'] = array_map( 'absint', $settings['portfolio_select_post'] );
			}
			$terms = get_terms( $args );
		}

		$enable_filter = ( 'yes' === $settings['enable_filter_menu'] );

		if ( $enable_filter ) {
			?><ul class="<?php echo esc_attr( $layout_class ); ?>"><?php
			if ( 'yes' === $settings['show_all_filter'] ) { ?>
				<li><a class="is-checked <?php echo esc_attr( $controls_selector ); ?>" data-filter="all"><?php echo esc_html( $settings['filter_text'] ); ?></a></li>
				<?php
			}
		}

		if ( $terms && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				if ( is_string( $term ) ) {
					$term_data = explode( '|', $term );
					if ( 2 !== count( $term_data ) ) {
						continue;
					}
					list( $slug, $label ) = $term_data;
				} else {
					$slug  = $term->slug;
					$label = $term->name;
				}
				$term_slugs[] = $slug;
				if ( $enable_filter ) {
					echo '<li><a class="' . esc_attr( $controls_selector ) . '" data-filter="' . esc_attr( $slug ) . '">' . esc_html( $label ) . '</a></li>';
				}
			}
		}

		if ( $enable_filter ) {
			?></ul><?php
		}

		return $term_slugs;
	}

	/**
	 * @param array $settings
	 * @param array $term_slugs
	 *
	 * @return WP_Post[]
	 */
	protected function get_posts( $settings, $term_slugs = [] ) {
		$args = array(
			'post_type'      => 'portfolio',
			'post_status'    => 'publish',
			'posts_per_page' => $settings['number_of_posts'],
			'offset'         => $settings['portfolio_posts_offset'],
			'orderby'        => $settings['portfolio_posts_order_by'],
			'order'          => $settings['portfolio_posts_sort'],
		);

		if ( 'select_post' == $settings['select_portfolio_post'] ) {
			if ( is_array( $settings['portfolio_select_post'] ) ) {
				$args['post__in'] = array_map( 'absint', $settings['portfolio_select_post'] );
			}
		} else {
			if ( ! empty( $term_slugs ) ) {
				$args['tax_query'] = [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					[
						'taxonomy' => 'portfolio_category',
						'field'    => 'slug',
						'terms'    => $term_slugs,
					],
				];
			}
		}

		return get_posts( $args );
	}
}
