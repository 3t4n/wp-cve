<?php

namespace AbsoluteAddons\Widgets;

use AbsoluteAddons\Absp_Slider_Controller;
use AbsoluteAddons\Absp_Widget;
use AbsoluteAddons\Controls\Absp_Control_Styles;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Repeater;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class Absoluteaddons_Style_Icon_Box_Carousel extends Absp_Widget {

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
		return 'absolute-icon-box-carousel';
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
		return __( 'Icon Box Carousel', 'absolute-addons' );
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
		return 'absp eicon-banner';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [
			'absolute-addons-custom',
			'absp-icon-box-carousel',
			'absp-pro-icon-box-carousel',
		];
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
		 * @param Absoluteaddons_Style_Icon_Box_Banner_Carousel $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/starts' ), [ &$this ] );

		$this->start_controls_section( 'template_layout', [ 'label' => esc_html__( 'Template Style', 'absolute-addons' ) ] );

		$styles = apply_filters( 'absp/widgets/icon-box-carousel/styles', [
			'one'       => esc_html__( 'One', 'absolute-addons' ),
			'two-pro'   => esc_html__( 'Two (Pro)', 'absolute-addons' ),
			'three-pro' => esc_html__( 'Three (Pro)', 'absolute-addons' ),
			'four'      => esc_html__( 'Four', 'absolute-addons' ),
		] );

		$this->add_control(
			'icon_box_banner_carousel',
			[
				'label'       => esc_html__( 'Icon Box Carousel Style', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Absp_Control_Styles::TYPE,
				'options'     => $styles,
				'default'     => 'one',
			]
		);

		$this->init_pro_alert( [ 'two-pro', 'three-pro' ] );

		$this->end_controls_section();
		$this->content_controls( 'one' );
		$this->content_controls( 'two' );
		$this->content_controls( 'three' );
		$this->content_controls( 'four' );

		$this->render_slider_controller( [
			'arrows_color'               => [
				'selectors' => [
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-one .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .absp-icon-box-carousel-item.element-one .elementor-swiper-button.elementor-swiper-button-next'                 => 'color: {{VALUE}};',
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-two .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .absp-icon-box-carousel-item.element-two .elementor-swiper-button.elementor-swiper-button-next'                 => 'color: {{VALUE}};',
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-three .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .absp-icon-box-carousel-item.element-three .elementor-swiper-button.elementor-swiper-button-next'             => 'color: {{VALUE}};',
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-four .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .absp-icon-box-carousel-item.element-four .elementor-swiper-button.elementor-swiper-button-next' => 'border-color: {{VALUE}};',
				],
			],
			'arrows_color_hover'         => [
				'selectors' => [
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-one .elementor-swiper-button.elementor-swiper-button-prev:hover, {{WRAPPER}} .absp-icon-box-carousel-item.element-one .elementor-swiper-button.elementor-swiper-button-next:hover'                 => 'color: {{VALUE}};',
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-two .elementor-swiper-button.elementor-swiper-button-prev:hover, {{WRAPPER}} .absp-icon-box-carousel-item.element-two .elementor-swiper-button.elementor-swiper-button-next:hover'                 => 'color: {{VALUE}};',
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-three .elementor-swiper-button.elementor-swiper-button-prev:hover, {{WRAPPER}} .absp-icon-box-carousel-item.element-three .elementor-swiper-button.elementor-swiper-button-next:hover'             => 'color: {{VALUE}};',
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-four .elementor-swiper-button.elementor-swiper-button-prev:hover, {{WRAPPER}} .absp-icon-box-carousel-item.element-four .elementor-swiper-button.elementor-swiper-button-next:hover' => 'border-color: {{VALUE}};',
				],
			],
			'dots_color'                 => [
				'selectors' => [
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-one .swiper-pagination-bullet'   => 'background: {{VALUE}};',
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-two .swiper-pagination-bullet'   => 'background: {{VALUE}};',
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-three .swiper-pagination-bullet' => 'background: {{VALUE}};',
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-four .swiper-pagination-bullet'  => 'background: {{VALUE}};',
				],
			],
			'dots_border_color'          => [
				'selectors' => [
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-one .swiper-pagination-bullet'   => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-two .swiper-pagination-bullet'   => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-three .swiper-pagination-bullet' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-four .swiper-pagination-bullet'  => 'border-color: {{VALUE}};',
				],
			],
			'dots_active_color'          => [
				'selectors' => [
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-one .swiper-pagination-bullet.swiper-pagination-bullet-active'   => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-two .swiper-pagination-bullet.swiper-pagination-bullet-active'   => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-three .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-four .swiper-pagination-bullet.swiper-pagination-bullet-active'  => 'background-color: {{VALUE}};',
				],
			],
			'dots_active_border_color'   => [
				'selectors' => [
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-one .swiper-pagination-bullet.swiper-pagination-bullet-active'   => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-two .swiper-pagination-bullet.swiper-pagination-bullet-active'   => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-three .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-four .swiper-pagination-bullet.swiper-pagination-bullet-active'  => 'border-color: {{VALUE}};',
				],
			],
			'dots_size'                  => [
				'selectors' => [
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-one .swiper-pagination-bullet'   => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-two .swiper-pagination-bullet'   => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-three .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .absp-icon-box-carousel-item.element-four .swiper-pagination-bullet'  => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			],
			'dots_position_prefix_class' => 'swipper-pagination-position-',
		] );

		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Icon_Box_Banner_Carousel $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/ends' ), [ &$this ] );

	}

	protected function content_controls( $style ) {

		$this->start_controls_section(
			'section_content_' . $style,
			[
				'label'     => esc_html__( 'Content', 'absolute-addons' ),
				'condition' => [
					'icon_box_banner_carousel' => $style,
				],
			]
		);

		if ( in_array( $style, [ 'two', 'three' ] ) ) {
			$this->render_controller( 'pro-common', [ 'style' => $style ] );
		}

		if ( in_array( $style, [ 'one', 'four' ] ) ) {

			$content   = 'one' === $style ? 'Lorem ipsum dolor sit' : 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet.';
			$sub_title = 'one' === $style ? '178.35K' : 'SUBTITLE FOUR';

			// Content Repeater.
			$repeater = new Repeater();

			$repeater->add_control(
				'carousel_icons',
				[
					'label'   => esc_html__( 'Icon or SVG', 'absolute-addons' ),
					'type'    => Controls_Manager::ICONS,
					'default' => [
						'value'   => 'fas fa-paw',
						'library' => 'solid',
					],
				]
			);

			$repeater->add_control(
				'carousel_title',
				[
					'label'       => esc_html__( 'Title', 'absolute-addons' ),
					'label_block' => true,
					'type'        => Controls_Manager::TEXT,
					'default'     => esc_html__( 'FootPrint', 'absolute-addons' ),
				]
			);

			$repeater->add_control(
				'sub_title',
				[
					'label'   => esc_html__( 'Sub Title', 'absolute-addons' ),
					'type'    => Controls_Manager::TEXT,
					'default' => $sub_title,
				]
			);

			$repeater->add_control(
				'content',
				[
					'label'   => esc_html__( 'Content', 'absolute-addons' ),
					'type'    => Controls_Manager::TEXT,
					'default' => $content,
				]
			);

			// Tabs.
			$repeater->start_controls_tabs( 'carousel_tabs' );

			// Settings tab.
			$this->settings_tab( $repeater );

			// Icon tab.
			$this->icon_tab( $repeater );

			// Title tab.
			$this->title_tab( $repeater );

			// Sub Title tab.
			$this->sub_title_tab( $repeater );

			// Content tab.
			$this->content_tab( $repeater );

			$repeater->end_controls_tabs();

			$this->add_control(
				'features_' . $style,
				[
					'label'       => esc_html__( 'Icon Box Carousel Item', 'absolute-addons' ),
					'type'        => Controls_Manager::REPEATER,
					'fields'      => $repeater->get_controls(),
					'title_field' => '{{{ carousel_title }}}',
					'default'     => [
						[
							'carousel_title' => 'FootPrint',
							'carousel_icons' => [
								'value'   => 'fas fa-paw',
								'library' => 'solid',
							],
							'sub_title'      => $sub_title,
							'content'        => $content,
						],
						[
							'carousel_title' => 'SpeedStorm',
							'carousel_icons' => [
								'value'   => 'fas fa-rocket',
								'library' => 'solid',
							],
							'sub_title'      => $sub_title,
							'content'        => $content,
						],
						[
							'carousel_title' => 'FastSupport',
							'carousel_icons' => [
								'value'   => 'far fa-futbol',
								'library' => 'solid',
							],
							'sub_title'      => $sub_title,
							'content'        => $content,
						],
					],
					'condition'   => [
						'icon_box_banner_carousel' => $style,
					],
				]
			);
		}

		$this->end_controls_section();
	}

	protected function settings_tab( $repeater ) {
		$repeater->start_controls_tab(
			'settings_tab',
			[
				'label' => esc_html__( 'Settings', 'absolute-addons' ),
			]
		);
		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'body_section_background',
				'label'          => esc_html__( 'Body Section Background', 'absolute-addons' ),
				'label_block'    => true,
				'types'          => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'label' => 'Body Section Background',
					],
				],
				'selector'       => '{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}}',
			]
		);
		$repeater->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'body_border',
				'label'    => esc_html__( 'Body Border', 'absolute-addons' ),
				'selector' => '{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}}',
			]
		);
		$repeater->add_control(
			'body_section_border_radius',
			[
				'label'      => esc_html__( 'Body Section Border Radius', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}}' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$repeater->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'body_section_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'absolute-addons' ),
				'selector' => '{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}}',
			]
		);
		$repeater->add_control(
			'body_section_padding',
			[
				'label'      => esc_html__( 'Padding', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$repeater->add_control(
			'body_section_margin',
			[
				'label'      => esc_html__( 'Margin', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$repeater->end_controls_tab();
	}

	protected function icon_tab( $repeater ) {
		$repeater->start_controls_tab(
			'icon_tab',
			[
				'label' => esc_html__( 'Icon', 'absolute-addons' ),
			]
		);
		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'icon_section_background',
				'label'          => esc_html__( 'Icon Section Background', 'absolute-addons' ),
				'label_block'    => true,
				'types'          => [ 'classic', 'gradient' ],
				'fields_options' => [
					'background' => [
						'label' => 'Icon Section Background',
					],
				],
				'selector'       => '{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-icon',
			]
		);
		$repeater->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'icon_border',
				'label'    => esc_html__( 'Icon Section Border', 'absolute-addons' ),
				'selector' => '{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-icon',
			]
		);
		$repeater->add_control(
			'icon_section_border_radius',
			[
				'label'      => esc_html__( 'Icon Section Border Radius', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$repeater->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'icon_section_box_shadow',
				'label'    => esc_html__( 'Icon Section Box Shadow', 'absolute-addons' ),
				'selector' => '{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-icon',
			]
		);
		$repeater->add_control(
			'icon_section_padding',
			[
				'label'      => esc_html__( 'Icon Section Padding', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$repeater->add_control(
			'icon_section_margin',
			[
				'label'      => esc_html__( 'Icon Section Margin', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$repeater->add_control(
			'icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'absolute-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-icon i' => 'color: {{VALUE}};',
				],
			]
		);
		$repeater->add_responsive_control(
			'icon_box_icon_size',
			[
				'label'     => esc_html__( 'Icon Size', 'absolute-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'
					{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-icon i
					' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$repeater->add_control(
			'icon_padding',
			[
				'label'      => esc_html__( 'Icon Padding', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-icon i, {{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-icon .icon-box-icon-left, {{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-icon img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$repeater->add_control(
			'icon_margin',
			[
				'label'      => esc_html__( 'Icon Margin', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-icon i,
					{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-icon img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$repeater->end_controls_tab();
	}

	protected function title_tab( $repeater ) {
		$repeater->start_controls_tab(
			'title_tab',
			[
				'label' => esc_html__( 'Title', 'absolute-addons' ),
			]
		);
		$repeater->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Title Typography', 'absolute-addons' ),
				'selector' => '{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-title',
			]
		);
		$repeater->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title Color', 'absolute-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-title' => 'color: {{VALUE}};',
				],
			]
		);
		$repeater->add_control(
			'title_padding',
			[
				'label'      => esc_html__( 'Padding', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$repeater->add_control(
			'title_margin',
			[
				'label'      => esc_html__( 'Margin', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$repeater->end_controls_tab();
	}
	protected function sub_title_tab( $repeater ) {
		$repeater->start_controls_tab(
			'sub_title_tab',
			[
				'label' => esc_html__( 'Sub Title', 'absolute-addons' ),
			]
		);
		$repeater->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'counter_typography',
				'label'    => esc_html__( 'Sub Title Typography', 'absolute-addons' ),
				'selector' => '
					{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-counter-number,
					{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-sub-title
					',
			]
		);
		$repeater->add_control(
			'counter_color',
			[
				'label'     => esc_html__( 'Sub Title Color', 'absolute-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'
						{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-counter-number,
						{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-sub-title
						' => 'color: {{VALUE}};',
				],
			]
		);
		$repeater->add_control(
			'counter_padding',
			[
				'label'      => esc_html__( 'Padding', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'
						{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-counter-number,
						{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-sub-title
						' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$repeater->add_control(
			'counter_margin',
			[
				'label'      => esc_html__( 'Margin', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'
						{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-counter-number,
						{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-sub-title
						' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$repeater->end_controls_tab();
	}

	protected function content_tab( $repeater ) {
		$repeater->start_controls_tab(
			'content_tab',
			[
				'label' => esc_html__( 'Content', 'absolute-addons' ),
			]
		);
		$repeater->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sub_title_typography',
				'label'    => esc_html__( 'Title Typography', 'absolute-addons' ),
				'selector' => '{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-content',
			]
		);
		$repeater->add_control(
			'sub_title_color',
			[
				'label'     => esc_html__( 'Content Color', 'absolute-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-content' => 'color: {{VALUE}};',
				],
			]
		);
		$repeater->add_control(
			'sub_title_padding',
			[
				'label'      => esc_html__( 'Padding', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$repeater->add_control(
			'sub_title_margin',
			[
				'label'      => esc_html__( 'Margin', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-wrapper .absp-icon-box-carousel-item .absp-carousel-item{{CURRENT_ITEM}} .absp-carousel-item-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$repeater->end_controls_tab();
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
		$style    = $settings['icon_box_banner_carousel'];

		$this->add_render_attribute( [
			'absp_slider' => [
				'class' => 'absp-icon-box-carousel-slider absp-swiper-wrapper swiper-container',
			],
		] );

		$this->add_render_attribute( [ 'absp_slider' => $this->get_slider_attributes( $settings ) ] );
		?>
		<div class="absp-wrapper absp-widget">
			<div class="absp-wrapper-inside">
				<div class="absp-wrapper-content">
					<!-- absp-icon-box-carousel-item -->
					<div class="absp-icon-box-carousel-item element-<?php echo esc_attr( $settings['icon_box_banner_carousel'] ); ?>">
						<div class="icon-box-carousel-slider">
							<div <?php $this->print_render_attribute_string( 'absp_slider' ); ?>>
								<div class="swiper-wrapper">
									<?php
									if ( is_array( $settings[ 'features_' . $style ] ) ) {
										foreach ( $settings[ 'features_' . $style ] as $index => $feature ) {
											if ( 'three' === $style ) {
												$this->add_inline_editing_attributes( 'icon_box_banner_carousel_button' );
												$this->add_render_attribute( 'icon_box_banner_carousel_button', 'class', 'absp-carousel-item-btn' );
												if ( ! empty( $feature['icon_box_banner_carousel_button_url']['url'] ) ) {
													$this->add_link_attributes( 'icon_box_banner_carousel_button', $feature['icon_box_banner_carousel_button_url'] );
												}
											}
											?>
											<div class="swiper-slide absp-carousel-item style_<?php echo esc_attr( $index + 1 ); ?> elementor-repeater-item-<?php echo esc_attr( $feature['_id'] ); ?>">
												<?php $this->render_template( $style, [ 'feature' => $feature ] ); ?>
											</div>
											<?php
										}
									}
									?>
								</div>
							</div>
							<?php $this->slider_nav( $settings ); ?>
						</div>
					</div>
					<!-- absp-icon-box-carousel-item -->
				</div>
			</div>
		</div>
		<?php
	}
}
