<?php

namespace AbsoluteAddons\Widgets;

use AbsoluteAddons\Absp_Slider_Controller;
use AbsoluteAddons\Controls\Absp_Control_Styles;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use AbsoluteAddons\Absp_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class Absoluteaddons_Style_Logo_Grid extends Absp_Widget {

	use Absp_Slider_Controller;

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
		return 'absolute-logo-grid';
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
		return __( 'Logo Grid', 'absolute-addons' );
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
		return 'absp eicon-logo';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [
			'absolute-addons-custom',
			'font-awesome',
			'absolute-addons-core',
			'absp-logo-grid',
			'absp-pro-logo-grid',
		];
	}

	public function get_script_depends() {
		return [ 'swiper-slider' ];
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
		 * @param Absoluteaddons_Style_Logo_Grid $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/starts' ), [ &$this ] );

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Template', 'absolute-addons' ),
			)
		);

		$logo_grid_styles = apply_filters( 'absp/widgets/logo-grid/styles', [
			'one'       => esc_html__( 'Style One', 'absolute-addons' ),
			'two'       => esc_html__( 'Style Two', 'absolute-addons' ),
			'three'     => esc_html__( 'Style Three', 'absolute-addons' ),
			'four'      => esc_html__( 'Style Four', 'absolute-addons' ),
			'five-pro'  => esc_html__( 'Style Five (Pro)', 'absolute-addons' ),
			'six-pro'   => esc_html__( 'Style Six (Pro)', 'absolute-addons' ),
			'seven'     => esc_html__( 'Style Seven', 'absolute-addons' ),
			'eight-pro' => esc_html__( 'Style Eight (Pro)', 'absolute-addons' ),
			'nine-pro'  => esc_html__( 'Style Nine (Pro)', 'absolute-addons' ),
			'ten-pro'   => esc_html__( 'Style Ten (Pro)', 'absolute-addons' ),
		] );

		$this->add_control(
			'absolute_logo_grid',
			array(
				'label'   => esc_html__( 'Logo Grid Style', 'absolute-addons' ),
				'type'    => Absp_Control_Styles::TYPE,
				'options' => $logo_grid_styles,
				'default' => 'one',
			)
		);

		$pro_styles = [
			'five-pro',
			'six-pro',
			'eight-pro',
			'nine-pro',
			'ten-pro',
		];

		$this->init_pro_alert( $pro_styles );

		$this->end_controls_section();
		$this->start_controls_section(
			'logo-grid-content',
			[
				'label' => __( 'Content', 'absolute-addons' ),
			]
		);

		$this->add_responsive_control(
			'logo-grid-gallery-column',
			[
				'label'           => __( 'Logo Column', 'absolute-addons' ),
				'type'            => Controls_Manager::SELECT,
				'default'         => '3',
				'options'         => [
					'1' => __( '1', 'absolute-addons' ),
					'2' => __( '2', 'absolute-addons' ),
					'3' => __( '3', 'absolute-addons' ),
					'4' => __( '4', 'absolute-addons' ),
					'5' => __( '5', 'absolute-addons' ),
					'6' => __( '6', 'absolute-addons' ),
				],
				'desktop_default' => 4,
				'tablet_default'  => 2,
				'mobile_default'  => 2,
				'prefix_class'    => 'absp-grid--col-%s',
				'style_transfer'  => true,
				'selectors'       => [
					'(desktop+){{WRAPPER}} .absp-logo-grid .image-grid ' => 'grid-template-columns: repeat({{logo-grid-gallery-column.VALUE}}, 1fr);',
					'(tablet){{WRAPPER}} .absp-logo-grid .image-grid '   => 'grid-template-columns: repeat({{logo-grid-gallery-column_tablet.VALUE}}, 1fr);',
					'(mobile){{WRAPPER}} .absp-logo-grid .image-grid '   => 'grid-template-columns: repeat({{logo-grid-gallery-column_mobile.VALUE}}, 1fr);',
				],
				'conditions'      => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'one',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'two',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'three',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'four',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'five',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'eight',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'nine',
						],

					],
				],
			]
		);

		$this->add_control(
			'logo-grid-gallery',
			[
				'label'   => __( 'Add Logo', 'absolute-addons' ),
				'type'    => Controls_Manager::GALLERY,
				'default' => [
					absp_get_placeholder(),
					absp_get_placeholder(),
					absp_get_placeholder(),
					absp_get_placeholder(),
					absp_get_placeholder(),
					absp_get_placeholder(),
					absp_get_placeholder(),
					absp_get_placeholder(),
					absp_get_placeholder(),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'logo-grid-style-section', [
				'label' => __( 'Logo Grid Style', 'absolute-addons' ),
				"tab"   => Controls_Manager::TAB_STYLE,

			]
		);

		$this->add_responsive_control(
			'logo-grid-loges-gap',
			[
				'label'      => __( 'Logo Gap', 'absolute-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],

				'selectors'  => [
					'{{WRAPPER}} .absp-logo-grid .image-grid'              => 'gap: {{SIZE}}{{UNIT}};',
					'{{wrapper}} .absp-logo-grid.element-five .image-grid' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'one',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'two',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'three',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'four',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'five',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'eight',
						],

					],
				],

			]
		);

		$this->add_responsive_control(
			'logo-grid-loges-width',
			[
				'label'      => __( 'Logo Width', 'absolute-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 500,
						'step' => 5,
					],
				],

				'selectors'  => [
					'{{WRAPPER}}  .absp-logo-grid .image-grid .absp-logo-grid-item img'     => 'width:{{size}}{{unit}};',
					'{{WRAPPER}}  .absp-logo-grid .swiper-wrapper .absp-logo-grid-item img' => 'width:{{size}}{{unit}};',

				],

			]
		);

		$this->start_controls_tabs(
			'logo-grid-style-tabs'
		);

		$this->start_controls_tab(
			'logo-grid-style-normal',
			[
				'label' => __( 'Normal', 'absolute-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'label'      => __( 'Background', 'absolute-addons' ),
				'name'       => 'logo-grid-background',
				'types'      => [ 'classic', 'gradient' ],
				'selector'   => '{{WRAPPER}} .absp-logo-grid .image-grid .absp-logo-grid-item ',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'one',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'two',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'three',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'four',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'five',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'eight',
						],

					],
				],
			]

		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'       => 'logo-grid-border',
				'label'      => __( 'Border', 'absolute-addons' ),
				'selector'   => '{{WRAPPER}} .absp-logo-grid .image-grid .absp-logo-grid-item ',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'one',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'two',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'three',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'four',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'five',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'eight',
						],

					],
				],
			]
		);

		$this->add_control(
			'logo-grid-filter',
			[
				'label'        => __( 'Filter Effect', 'absolute-addons' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'no',
				'options'      => [
					'yes' => __( 'Yes', 'absolute-addons' ),
					'no'  => __( 'No', 'absolute-addons' ),
				],
				'prefix_class' => 'gray-filter-',

			]
		);

		$this->add_control(
			'logo-grid-filter-list',
			[
				'label'     => __( 'Filter Effect', 'absolute-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'no',
				'options'   => [
					'brightness' => __( 'Brightness', 'absolute-addons' ),
					'contrast'   => __( 'Contrast', 'absolute-addons' ),
					'grayscale'  => __( 'Grayscale', 'absolute-addons' ),
					'invert'     => __( 'Invert', 'absolute-addons' ),
					'opacity'    => __( 'Opacity', 'absolute-addons' ),
					'saturate'   => __( 'saturate', 'absolute-addons' ),
					'sepia'      => __( 'Sepia', 'absolute-addons' ),
				],
				'condition' => [ 'logo-grid-filter' => 'yes' ],
			]
		);

		$this->add_responsive_control(
			'logo-grid-filter-value',
			[
				'label'      => __( 'Filter Value', 'absolute-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range'      => [
					'%' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'condition'  => [ 'logo-grid-filter' => 'yes' ],
				'selectors'  => [
					'{{WRAPPER}}  .absp-logo-grid .image-grid .absp-logo-grid-item img'     => 'filter: {{logo-grid-filter-list.VALUE}}({{SIZE}}%);',
					'{{WRAPPER}}  .absp-logo-grid .swiper-wrapper .absp-logo-grid-item img' => 'filter: {{logo-grid-filter-list.VALUE}}({{SIZE}}%);',
					'{{WRAPPER}}  .absp-logo-grid .swiper-wrapper .swiper-slide.absp-logo-grid-item img' => 'filter: {{logo-grid-filter-list.VALUE}}({{SIZE}}%);',

				],
			]
		);

		$this->add_responsive_control(
			'logo-grid-border-radius',
			[
				'label'      => __( 'Border Radius', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}}  .absp-logo-grid .image-grid .absp-logo-grid-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}  .absp-logo-grid .swiper-wrapper .swiper-slide.absp-logo-grid-item img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'       => 'logo-grid-box-shadow',
				'label'      => __( 'Box Shadow', 'absolute-addons' ),
				'selector'   => '{{WRAPPER}} .absp-logo-grid .image-grid .absp-logo-grid-item',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'one',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'two',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'three',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'four',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'five',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'eight',
						],

					],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'logo-grid-style-hover',
			[
				'label' => __( 'Hover', 'absolute-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'label'      => __( 'Background', 'absolute-addons' ),
				'name'       => 'logo-grid-background-hover',
				'types'      => [ 'classic', 'gradient' ],
				'selector'   => '{{WRAPPER}} .absp-logo-grid .image-grid .absp-logo-grid-item:hover ',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'one',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'two',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'three',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'four',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'five',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'eight',
						],

					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'       => 'logo-grid-border-hover',
				'label'      => __( 'Border', 'absolute-addons' ),
				'selector'   => '{{WRAPPER}} .absp-logo-grid .image-grid .absp-logo-grid-item:hover',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'one',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'two',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'three',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'four',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'five',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'eight',
						],

					],
				],
			]
		);


		$this->add_control(
			'logo-grid-filter-hover',
			[
				'label'        => __( 'Filter Effect', 'absolute-addons' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'no',
				'options'      => [
					'yes' => __( 'Yes', 'absolute-addons' ),
					'no'  => __( 'No', 'absolute-addons' ),
				],
				'prefix_class' => 'gray-filter-hover-',

			]
		);

		$this->add_control(
			'logo-grid-filter-list-hover',
			[
				'label'     => __( 'Filter Effect', 'absolute-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'no',
				'options'   => [
					'brightness' => __( 'Brightness', 'absolute-addons' ),
					'contrast'   => __( 'Contrast', 'absolute-addons' ),
					'grayscale'  => __( 'Grayscale', 'absolute-addons' ),
					'invert'     => __( 'Invert', 'absolute-addons' ),
					'opacity'    => __( 'Opacity', 'absolute-addons' ),
					'saturate'   => __( 'saturate', 'absolute-addons' ),
					'sepia'      => __( 'Sepia', 'absolute-addons' ),
				],
				'condition' => [ 'logo-grid-filter-hover' => 'yes' ],
			]
		);

		$this->add_responsive_control(
			'logo-grid-filter-value-hover',
			[
				'label'      => __( 'Filter Value', 'absolute-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range'      => [
					'%' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'condition'  => [ 'logo-grid-filter-hover' => 'yes' ],
				'selectors'  => [
					'{{WRAPPER}}  .absp-logo-grid .image-grid .absp-logo-grid-item:hover img'     => 'filter: {{logo-grid-filter-list-hover.VALUE}}({{SIZE}}%);',
					'{{WRAPPER}}  .absp-logo-grid .swiper-wrapper .absp-logo-grid-item:hover img' => 'filter: {{logo-grid-filter-list-hover.VALUE}}({{SIZE}}%);',
					'{{WRAPPER}}  .absp-logo-grid .swiper-wrapper .swiper-slide.absp-logo-grid-item:hover img' => 'filter: {{logo-grid-filter-list-hover.VALUE}}({{SIZE}}%);',

				],
			]
		);

		$this->add_responsive_control(
			'logo-grid-border-radius-hover',
			[
				'label'      => __( 'Border Radius', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}}  .absp-logo-grid .image-grid .absp-logo-grid-item:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}  .absp-logo-grid .swiper-wrapper .swiper-slide.absp-logo-grid-item:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'logo-grid-box-shadow-hover',
				'label'    => __( 'Box Shadow', 'absolute-addons' ),
				'selector' => '{{WRAPPER}} .absp-logo-grid .image-grid .absp-logo-grid-item:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->render_slider_controller( [
			'slider_dots_position'       => [
				'condition'  => [
					'navigation' => [ 'dots', 'both' ],
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'ten',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'seven',
						],
					],
				],
			],
			'slider_navigation_position' => [
				'condition'  => [
					'navigation' => [ 'arrows', 'both' ],
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'ten',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'seven',
						],
					],
				],
			],
			'section'                    => [
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'ten',
						],
						[
							'name'     => 'absolute_logo_grid',
							'operator' => '==',
							'value'    => 'seven',
						],
					],
				],
			],

			'arrows_color'               => [
				'selectors' => [
					'{{WRAPPER}} .absp-logo-grid.element-one .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .absp-logo-grid.element-one .elementor-swiper-button.elementor-swiper-button-next'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .absp-logo-grid.element-two .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .absp-logo-grid.element-one .elementor-swiper-button.elementor-swiper-button-next'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .absp-logo-grid.element-three .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .absp-logo-grid.element-one .elementor-swiper-button.elementor-swiper-button-next' => 'color: {{VALUE}};',
					'{{WRAPPER}} .absp-logo-grid.element-four .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .absp-logo-grid.element-one .elementor-swiper-button.elementor-swiper-button-next'  => 'color: {{VALUE}};',
				],
			],
			'arrows_color_hover'         => [
				'selectors' => [
					'{{WRAPPER}} .absp-logo-grid.element-one .elementor-swiper-button.elementor-swiper-button-prev:hover, {{WRAPPER}} .absp-logo-grid.element-one .elementor-swiper-button.elementor-swiper-button-next:hover'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .absp-logo-grid.element-two .elementor-swiper-button.elementor-swiper-button-prev:hover, {{WRAPPER}} .absp-logo-grid.element-one .elementor-swiper-button.elementor-swiper-button-next:hover'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .absp-logo-grid.element-three .elementor-swiper-button.elementor-swiper-button-prev:hover, {{WRAPPER}} .absp-logo-grid.element-one .elementor-swiper-button.elementor-swiper-button-next:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .absp-logo-grid.element-four .elementor-swiper-button.elementor-swiper-button-prev:hover, {{WRAPPER}} .absp-logo-grid.element-one .elementor-swiper-button.elementor-swiper-button-next:hover'  => 'color: {{VALUE}};',
				],
			],
			'dots_color'                 => [
				'selectors' => [
					'{{WRAPPER}} .absp-logo-grid.element-one .swiper-pagination-bullet'   => 'background: {{VALUE}};',
					'{{WRAPPER}} .absp-logo-grid.element-two .swiper-pagination-bullet'   => 'background: {{VALUE}};',
					'{{WRAPPER}} .absp-logo-grid.element-three .swiper-pagination-bullet' => 'background: {{VALUE}};',
					'{{WRAPPER}} .absp-logo-grid.element-four .swiper-pagination-bullet'  => 'background: {{VALUE}};',
				],
			],
			'dots_size'                  => [
				'selectors' => [
					'{{WRAPPER}} .absp-logo-grid.element-one .swiper-pagination-bullet'   => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .absp-logo-grid.element-two .swiper-pagination-bullet'   => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .absp-logo-grid.element-three .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .absp-logo-grid.element-four .swiper-pagination-bullet'  => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			],
		] );

		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Logo_Grid $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/ends' ), [ &$this ] );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( [
			'absp_slider' => [
				'class' => 'absp-logo-slider absp-swiper-wrapper swiper-container',
			],
		] );

		$this->add_render_attribute( [ 'absp_slider' => $this->get_slider_attributes( $settings ) ] );
		?>
		<div class="absp-wrapper absp-widget">
			<div class="absp-wrapper-inside">
				<div class="absp-wrapper-content">
					<!-- absp-logo-grid -->
					<div class="absp-logo-grid element-<?php echo esc_attr( $settings['absolute_logo_grid'] ); ?>">
						<?php
						if ( $settings['logo-grid-gallery'] ) {
							$this->render_template();
						}
						?>
					</div>
					<!-- absp-logo-grid -->
				</div><!-- end .absp-wrapper-content -->
			</div><!-- end .absp-wrapper-inside -->
		</div><!-- end .absp-wrapper -->
		<?php
	}
}
