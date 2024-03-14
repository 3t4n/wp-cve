<?php

namespace EazyGrid\Elementor\Widgets;

use EazyGrid\Elementor\Base\Post_Grid as Post_Grid_Base;
use EazyGrid\Elementor\Classes\Grid_Engine;
use EazyGrid\Elementor\Controls\Image_Selector;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;

class Metro_Post_Grid extends Post_Grid_Base {

	/**
	 * @var mixed
	 */
	private $grid_engine;

	/**
	 * @param array $data
	 * @param $args
	 */
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		$this->grid_engine = new Grid_Engine();
	}

	public function get_title() {
		return __( 'Metro Post Grid', 'eazygrid-elementor' );
	}

	public function get_icon() {
		return 'ezicon ezicon-post-metro';
	}

	public function get_keywords() {
		return ['eazygrid-elementor', 'eazygrid', 'eazygrid-elementor', 'eazy', 'grid'];
	}

	/**
	 * @return mixed
	 */
	public function free_grids() {
		$free_grids = [
			'lily'     => __( 'Lily', 'eazygrid-elementor' ),
			'daffodil' => __( 'Daffodil', 'eazygrid-elementor' ),
			'lavender' => __( 'Lavender', 'eazygrid-elementor' ),
		];
		$free_grids = apply_filters( 'eazygridElementor/grids', $free_grids );

		return $free_grids;
	}

	/**
	 * Register content controls
	 */
	public function register_content_controls() {
		$this->__content_controls();
		$this->__query_content_controls();
	}

	public function __content_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'eazygrid-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'layout',
			[
				'label'           => __( 'Grid Layout', 'eazygrid-elementor' ),
				'type'            => Image_Selector::TYPE,
				'label_block'     => true,
				'default'         => 'lily',
				'options'         => ezg_ele_layout_image_list( $this->free_grids() ),
				'content_classes' => 'column-3',
				'column_height'   => '200px',
			]
		);

		$this->end_controls_section();
	}

	public function __query_content_controls() {
		$this->start_controls_section(
			'_section_query',
			[
				'label' => __( 'Query', 'eazygrid-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'query_cat',
			[
				'label'   => esc_html__( 'Post Query', 'eazygrid-elementor' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'recent',
				'options' => $this->get_cat_list(),
			]
		);

		$this->add_control(
			'ignore_sticky_posts',
			[
				'label'        => __( 'Ignore Sticky Posts', 'eazygrid-elementor' ),
				'description'  => __( 'Sticky-posts ordering is visible on frontend only', 'eazygrid-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'eazygrid-elementor' ),
				'label_off'    => __( 'No', 'eazygrid-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls
	 */
	public function register_style_controls() {
		$this->__general_style_controls();
		$this->__content_style_controls();
		$this->__badge_style_controls();
		$this->__meta_style_controls();
		$this->__title_excerpt_style_controls();
	}

	public function __general_style_controls() {
		$this->start_controls_section(
			'general_style',
			[
				'label' => __( 'General', 'eazygrid-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'grid_height',
			[
				'label'       => __( 'Grid Height', 'eazygrid-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'devices'     => ['desktop', 'tablet'],
				'size_units'  => ['px', 'vh'],
				'range'       => [
					'px' => [
						'min' => 1,
						'max' => 4000,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => '700',
				],
				'selectors'   => [
					'{{WRAPPER}} .ezg-ele-metro-post-grid-wrap' => 'height: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'gutter_width',
			[
				'label'       => __( 'Gutter Width', 'eazygrid-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'label_block' => true,
				'devices'     => ['desktop', 'tablet'],
				'size_units'  => ['px'],
				'default'     => [
					'unit' => 'px',
					'size' => '5',
				],
				'selectors'   => [
					'{{WRAPPER}} .ezg-ele-metro-post-grid-wrap .ezg-ele-metro-post-grid-content ' => 'grid-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label'      => __( 'Image Border Radius', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'default'    => [
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'isLinked' => true,
				],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-metro-post-grid--item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __content_style_controls() {

		$this->start_controls_section(
			'_section_style_content_wrap',
			[
				'label' => __( 'Content', 'eazygrid-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => __( 'Padding', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-metro-post-grid--item .ezg-ele-grid--content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'tiles_font_size',
			[
				'label'      => __( 'Font Size', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-metro-post-grid-wrap' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'content_bg',
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [ 'image' ],
				'selector' => '{{WRAPPER}} .ezg-ele-metro-post-grid--item .ezg-ele-grid--content',
			]
		);

		$this->add_control(
			'overlay_color',
			[
				'label'     => __( 'Overlay (On hover)', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-metro-post-grid--item:after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_align',
			[
				'label'                => __( 'Alignment', 'eazygrid-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => [
					'left'   => [
						'title' => __( 'Left', 'eazygrid-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'eazygrid-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'eazygrid-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'toggle'               => false,
				'selectors'            => [
					'{{WRAPPER}} .ezg-ele-metro-post-grid--item .ezg-ele-grid--content' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'left'    => '
						-webkit-box-align: start;
						-ms-flex-align: start;
						align-items: flex-start;
						-webkit-box-pack: start;
						-ms-flex-pack: start;
						justify-content: flex-start;
						text-align: left;',
					'center'  => '
						-webkit-box-align: center;
						-ms-flex-align: center;
						align-items: center;
						-webkit-box-pack: center;
      					-ms-flex-pack: center;
          				justify-content: center;
						text-align: center;',
					'right'   => '
						-webkit-box-align: end;
						-ms-flex-align: end;
						align-items: flex-end;
						-webkit-box-pack: end;
      					-ms-flex-pack: end;
         				justify-content: flex-end;
						text-align: right;',
					'justify' => 'text-align: justify;',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __badge_style_controls() {

		$this->start_controls_section(
			'_section_style_badge',
			[
				'label' => __( 'Badge', 'eazygrid-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'badge_position_toggle',
			[
				'label'        => __( 'Position', 'eazygrid-elementor' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'None', 'eazygrid-elementor' ),
				'label_on'     => __( 'Custom', 'eazygrid-elementor' ),
				'return_value' => 'yes',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'badge_position_x',
			[
				'label'      => __( 'Position Right', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em'],
				'condition'  => [
					'badge_position_toggle' => 'yes',
				],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
					'em' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-metro-post-grid--item .ezg-ele-metro-post-grid__tag' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'badge_position_y',
			[
				'label'      => __( 'Position Top', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'condition'  => [
					'badge_position_toggle' => 'yes',
				],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
					'em' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-metro-post-grid--item .ezg-ele-metro-post-grid__tag' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_popover();

		$this->add_responsive_control(
			'badge_padding',
			[
				'label'      => __( 'Padding', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-metro-post-grid--item .ezg-ele-metro-post-grid__tag' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( '_tabs_badge_stat' );
		$this->start_controls_tab(
			'_tab_badge_normal',
			[
				'label' => __( 'Normal', 'eazygrid-elementor' ),
			]
		);

		$this->add_control(
			'badge_color',
			[
				'label'     => __( 'Text Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-metro-post-grid--item .ezg-ele-metro-post-grid__tag' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'badge_bg_color',
			[
				'label'     => __( 'Background Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-metro-post-grid--item .ezg-ele-metro-post-grid__tag' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_badge_hover',
			[
				'label' => __( 'Hover', 'eazygrid-elementor' ),
			]
		);

		$this->add_control(
			'badge_hover_color',
			[
				'label'     => __( 'Text Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-metro-post-grid--item .ezg-ele-metro-post-grid__tag:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'badge_hover_bg_color',
			[
				'label'     => __( 'Background Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-metro-post-grid--item .ezg-ele-metro-post-grid__tag:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'badge_border',
				'selector' => '{{WRAPPER}} .ezg-ele-metro-post-grid--item .ezg-ele-metro-post-grid__tag',
			]
		);

		$this->add_responsive_control(
			'badge_border_radius',
			[
				'label'      => __( 'Border Radius', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-metro-post-grid--item .ezg-ele-metro-post-grid__tag' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'badge_box_shadow',
				'exclude'  => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .ezg-ele-metro-post-grid--item .ezg-ele-metro-post-grid__tag',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'badge_typography',
				'label'    => __( 'Typography', 'eazygrid-elementor' ),
				'exclude'  => [
					'line_height',
					'font_size',
				],
				'selector' => '{{WRAPPER}} .ezg-ele-metro-post-grid--item .ezg-ele-metro-post-grid__tag',
			]
		);

		$this->end_controls_section();
	}

	protected function __meta_style_controls() {

		$this->start_controls_section(
			'_section_style_meta',
			[
				'label' => __( 'Meta Data', 'eazygrid-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'meta_bottom_spacing',
			[
				'label'      => __( 'Bottom Spacing', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'unit' => 'em',
				],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-metro-post-grid--item .ezg-ele-metro-post-grid--meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'meta_typography',
				'label'    => __( 'Typography', 'eazygrid-elementor' ),
				'exclude'  => [
					// 'line_height',
					// 'font_size',
				],
				'selector' => '{{WRAPPER}} .ezg-ele-metro-post-grid--item .ezg-ele-metro-post-grid--meta',
			]
		);

		$this->add_control(
			'meta_color',
			[
				'label'     => __( 'Text Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-metro-post-grid--item .ezg-ele-metro-post-grid--meta' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ezg-ele-metro-post-grid--item .ezg-ele-metro-post-grid--meta svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __title_excerpt_style_controls() {

		$this->start_controls_section(
			'_section_style_content',
			[
				'label' => __( 'Title & Excerpt', 'eazygrid-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'_heading_title',
			[
				'type'  => Controls_Manager::HEADING,
				'label' => __( 'Title', 'eazygrid-elementor' ),
			]
		);

		$this->add_responsive_control(
			'title_bottom_spacing',
			[
				'label'      => __( 'Bottom Spacing', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'unit' => 'em',
				],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-metro-post-grid--item .ezg-ele-grid--content-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'eazygrid-elementor' ),
				'exclude'  => [
					// 'line_height',
					'font_size',
				],
				'selector' => '{{WRAPPER}} .ezg-ele-metro-post-grid--item .ezg-ele-grid--content-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'title_text_shadow',
				'label'    => __( 'Text Shadow', 'eazygrid-elementor' ),
				'selector' => '{{WRAPPER}} .ezg-ele-metro-post-grid--item .ezg-ele-grid--content-title',
			]
		);

		$this->start_controls_tabs( '_tabs_title_stat' );

		$this->start_controls_tab(
			'_tab_title_stat_normal',
			[
				'label' => __( 'Normal', 'eazygrid-elementor' ),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Text Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-metro-post-grid--item .ezg-ele-grid--content-title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_title_stat_hover',
			[
				'label' => __( 'Hover', 'eazygrid-elementor' ),
			]
		);

		$this->add_control(
			'title_hvr_color',
			[
				'label'     => __( 'Text Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-metro-post-grid--item .ezg-ele-grid--content-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'_heading_excerpt',
			[
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Excerpt', 'eazygrid-elementor' ),
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'excerpt_bottom_spacing',
			[
				'label'      => __( 'Bottom Spacing', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'unit' => 'em',
				],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-metro-post-grid--item .ezg-ele-grid--content-desc' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'excerpt_typography',
				'label'    => __( 'Typography', 'eazygrid-elementor' ),
				'exclude'  => [
					// 'line_height',
					// 'font_size',
				],
				'selector' => '{{WRAPPER}} .ezg-ele-metro-post-grid--item .ezg-ele-grid--content-desc',
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label'     => __( 'Text Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-metro-post-grid--item .ezg-ele-grid--content-desc' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings           = $this->get_settings_for_display();
		$layout             = $settings['layout'];
		$grid_class         = $this->grid_engine->get_grid_class_name( $layout, $settings );
		$excerpt_length     = 15;
		$counter            = 0;
		$number_of_elements = $this->grid_engine->get_grid_element_count( $layout ) * 1;
		$category           = ( $settings['query_cat'] ) ? $settings['query_cat'] : 'recent';

		// WP_Query arguments
		$args = [
			'post_type'      => 'post',       // use any for any kind of post type, custom post type slug for custom post type
			'post_status'    => 'publish',    // Also support: pending, draft, auto-draft, future, private, inherit, trash, any
			'posts_per_page' => $number_of_elements, // use -1 for all post
			'order'          => 'DESC',              // Also support: ASC
			'orderby'        => 'date',             // Also support: none, rand, id, title, slug, modified, parent, menu_order, comment_count
		];

		if ( 'recent' !== $category ) {
			$args['cat'] = $category;
		}

		if ( $settings['ignore_sticky_posts'] && 'yes' == $settings['ignore_sticky_posts'] ) {
			$args['ignore_sticky_posts'] = 1;
		}

		$query = new \WP_Query( $args );

		$this->add_render_attribute( 'grid', [
			'class' => [
				'ezg-ele-metro-post-grid-wrap',
			],
		] );
		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'grid' ) ); ?>>
			<div class="ezg-ele-metro-post-grid-content <?php echo esc_attr( $grid_class ); ?>">
			<?php
			if ( $query->have_posts() ) {

				while ( $query->have_posts() ) :
					$query->the_post();
					$counter++;
					?>
					<article class="ezg-ele-grid--item<?php echo esc_attr( $counter ); ?> ezg-ele-metro-post-grid--item">
						<?php
						if ( has_post_thumbnail() ) {
							the_post_thumbnail();
						}
						?>
						<div class="ezg-ele-grid--content">
							<div class="ezg-ele-metro-post-grid--meta">
								<?php
									$this->render_author();
									$this->render_date();
								?>
							</div>
							<h2 class="ezg-ele-grid--content-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>
							<p class="ezg-ele-grid--content-desc"><?php echo esc_html( ezg_ele_get_excerpt( '', $excerpt_length ) ); ?></p>
						</div>
						<?php ezg_ele_the_first_category(); ?>
					</article>
					<?php
				endwhile;
				wp_reset_postdata();
			}
			?>
			</div>
		</div>
		<?php
	}
}
