<?php
/**
 * Carousel widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Group_Control_Background;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Utils;
use Elementor\Icons_Manager;

defined( 'ABSPATH' ) || die();

class Carousel extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Carousel', 'skt-addons-elementor' );
	}

	public function get_custom_help_url() {
		return '#';
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'skti skti-carousal';
	}

	public function get_keywords() {
		return [ 'slider', 'image', 'gallery', 'carousel' ];
	}

	/**
	 * Register Content Control
	 *
	 * @return void
	 */
	protected function register_content_controls() {
		$this->slider_content_controls();
		$this->slider_settings_content_controls();
	}

	protected function slider_content_controls() {

		$this->start_controls_section(
			'_section_slides',
			[
				'label' => __( 'Slides', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image',
			[
				'type' => Controls_Manager::MEDIA,
				'label' => __( 'Image', 'skt-addons-elementor' ),
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$repeater->add_control(
			'title',
			[
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'placeholder' => __( 'Type title here', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$repeater->add_control(
			'subtitle',
			[
				'label' => __( 'Subtitle', 'skt-addons-elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'placeholder' => __( 'Type subtitle here', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => __( 'Link', 'skt-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => 'https://example.com',
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$placeholder = [
			'image' => [
				'url' => Utils::get_placeholder_image_src(),
			],
		];

		$this->add_control(
			'slides',
			[
				'show_label' => false,
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '<# print(title || "Carousel Item"); #>',
				'default' => array_fill( 0, 7, $placeholder )
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail',
				'default' => 'medium_large',
				'separator' => 'before',
				'exclude' => [
					'custom'
				]
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label' => __( 'Title HTML Tag', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				// 'separator' => 'before',
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h2',
			]
		);

		$this->end_controls_section();
	}

	protected function slider_settings_content_controls() {

		$this->start_controls_section(
			'_section_settings',
			[
				'label' => __( 'Settings', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'animation_speed',
			[
				'label' => __( 'Animation Speed', 'skt-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 100,
				'step' => 10,
				'max' => 10000,
				'default' => 300,
				'description' => __( 'Slide speed in milliseconds', 'skt-addons-elementor' ),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => __( 'Autoplay?', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'skt-addons-elementor' ),
				'label_off' => __( 'No', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label' => __( 'Autoplay Speed', 'skt-addons-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 100,
				'step' => 100,
				'max' => 10000,
				'default' => 3000,
				'description' => __( 'Autoplay speed in milliseconds', 'skt-addons-elementor' ),
				'condition' => [
					'autoplay' => 'yes'
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'loop',
			[
				'label' => __( 'Infinite Loop?', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'skt-addons-elementor' ),
				'label_off' => __( 'No', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'center',
			[
				'label' => __( 'Center Mode?', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'skt-addons-elementor' ),
				'label_off' => __( 'No', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'description' => __( 'Best works with odd number of slides (Slides To Show) and loop (Infinite Loop)', 'skt-addons-elementor' ),
				'frontend_available' => true,
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'vertical',
			[
				'label' => __( 'Vertical Mode?', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'skt-addons-elementor' ),
				'label_off' => __( 'No', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'frontend_available' => true,
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'navigation',
			[
				'label' => __( 'Navigation', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => __( 'None', 'skt-addons-elementor' ),
					'arrow' => __( 'Arrow', 'skt-addons-elementor' ),
					'dots' => __( 'Dots', 'skt-addons-elementor' ),
					'both' => __( 'Arrow & Dots', 'skt-addons-elementor' ),
				],
				'default' => 'arrow',
				'frontend_available' => true,
				'style_transfer' => true,
			]
		);

		$this->add_responsive_control(
			'slides_to_show',
			[
				'label' => __( 'Slides To Show', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					1 => __( '1 Slide', 'skt-addons-elementor' ),
					2 => __( '2 Slides', 'skt-addons-elementor' ),
					3 => __( '3 Slides', 'skt-addons-elementor' ),
					4 => __( '4 Slides', 'skt-addons-elementor' ),
					5 => __( '5 Slides', 'skt-addons-elementor' ),
					6 => __( '6 Slides', 'skt-addons-elementor' ),
				],
				'desktop_default' => 4,
				'tablet_default' => 3,
				'mobile_default' => 2,
				'frontend_available' => true,
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'arrow_prev_icon',
			[
				'label' => __( 'Previous Icon', 'skt-addons-elementor' ),
				'label_block' => false,
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'default' => [
					'value' => 'fas fa-chevron-left',
					'library' => 'fa-solid',
				],
				'condition' => [
					'navigation' => ['arrow', 'both']
				],
			]
		);

		$this->add_control(
			'arrow_next_icon',
			[
				'label' => __( 'Next Icon', 'skt-addons-elementor' ),
				'label_block' => false,
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'default' => [
					'value' => 'fas fa-chevron-right',
					'library' => 'fa-solid',
				],
				'condition' => [
					'navigation' => ['arrow', 'both']
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register Style Control
	 *
	 * @return void
	 */
	protected function register_style_controls() {
		$this->carousel_item_style_controls();
		$this->slide_content_style_controls();
		$this->carousel_arrow_style_controls();
		$this->carousel_dot_style_controls();
	}

	protected function carousel_item_style_controls() {

		$this->start_controls_section(
			'_section_style_item',
			[
				'label' => __( 'Carousel Item', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'item_spacing',
			[
				'label' => __( 'Slide Spacing (px)', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .slick-slider:not(.slick-vertical) .slick-slide' => 'padding-right: {{SIZE}}{{UNIT}}; padding-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .slick-slider.slick-vertical .slick-slide' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'item_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-slick-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function slide_content_style_controls() {

		$this->start_controls_section(
			'_section_style_content',
			[
				'label' => __( 'Slide Content', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => __( 'Content Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-slick-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_background',
				'selector' => '{{WRAPPER}} .skt-slick-content',
				'exclude' => [
					 'image'
				]
			]
		);

		$this->add_control(
			'_heading_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .skt-slick-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-slick-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-slick-title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
			]
		);

		$this->add_control(
			'_heading_subtitle',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Subtitle', 'skt-addons-elementor' ),
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'subtitle_spacing',
			[
				'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .skt-slick-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'subtitle_color',
			[
				'label' => __( 'Text Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-slick-subtitle' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'subtitle',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-slick-subtitle',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
			]
		);

		$this->end_controls_section();
	}

	protected function carousel_arrow_style_controls() {

		$this->start_controls_section(
			'_section_style_arrow',
			[
				'label' => __( 'Navigation :: Arrow', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'arrow_position_toggle',
			[
				'label' => __( 'Position', 'skt-addons-elementor' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label_off' => __( 'None', 'skt-addons-elementor' ),
				'label_on' => __( 'Custom', 'skt-addons-elementor' ),
				'return_value' => 'yes',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'arrow_position_y',
			[
				'label' => __( 'Vertical', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'condition' => [
					'arrow_position_toggle' => 'yes'
				],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 500,
					],
					'%' => [
						'min' => -110,
						'max' => 110,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrow_position_x',
			[
				'label' => __( 'Horizontal', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'condition' => [
					'arrow_position_toggle' => 'yes'
				],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 500,
					],
					'%' => [
						'min' => -110,
						'max' => 110,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slick-prev' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .slick-next' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_popover();

		$this->add_responsive_control(
			'arrow_size',
			[
				'label' => __( 'Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em'],
				'selectors' => [
					'{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'arrow_border',
				'selector' => '{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next',
			]
		);

		$this->add_responsive_control(
			'arrow_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
		);

		$this->start_controls_tabs( '_tabs_arrow' );

		$this->start_controls_tab(
			'_tab_arrow_normal',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'arrow_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrow_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .slick-prev, {{WRAPPER}} .slick-next' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_arrow_hover',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'arrow_hover_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .slick-prev:hover, {{WRAPPER}} .slick-next:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrow_hover_bg_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .slick-prev:hover, {{WRAPPER}} .slick-next:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrow_hover_border_color',
			[
				'label' => __( 'Border Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'arrow_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .slick-prev:hover, {{WRAPPER}} .slick-next:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function carousel_dot_style_controls() {

		$this->start_controls_section(
			'_section_style_dots',
			[
				'label' => __( 'Navigation :: Dots', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'dots_nav_position_y',
			[
				'label' => __( 'Vertical Position', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slick-dots' => 'bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'dots_nav_spacing',
			[
				'label' => __( 'Spacing', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .slick-dots li' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2); margin-left: calc({{SIZE}}{{UNIT}} / 2);',
				],
			]
		);

		$this->add_responsive_control(
			'dots_nav_align',
			[
				'label' => __( 'Alignment', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .slick-dots' => 'text-align: {{VALUE}}'
				]
			]
		);

		$this->start_controls_tabs( '_tabs_dots' );
		$this->start_controls_tab(
			'_tab_dots_normal',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'dots_nav_size',
			[
				'label' => __( 'Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .slick-dots li button:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'dots_nav_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .slick-dots li button:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_dots_hover',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'dots_nav_hover_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .slick-dots li button:hover:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_dots_active',
			[
				'label' => __( 'Active', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'dots_nav_active_size',
			[
				'label' => __( 'Size', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .slick-dots li.slick-active button:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'dots_nav_active_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .slick-dots .slick-active button:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['slides'] ) ) {
			return;
		}
		?>

		<div class="sktjs-slick skt-slick skt-slick--carousel">

			<?php foreach ( $settings['slides'] as $slide ) :
				$image = wp_get_attachment_image_url( $slide['image']['id'], $settings['thumbnail_size'] );

				if ( ! $image ) {
					$image = $slide['image']['url'];
				}

				$item_tag = 'div';
				$id = 'skt-slick-item-' . $slide['_id'];

				$this->add_render_attribute( $id, 'class', 'skt-slick-item' );

				if ( isset( $slide['link'] ) && ! empty( $slide['link']['url'] ) ) {
					$item_tag = 'a';
					$this->add_link_attributes( $id, $slide['link'] );
				}
				?>

				<div class="skt-slick-slide">
					<<?php echo wp_kses_post($item_tag); ?> <?php $this->print_render_attribute_string( $id ); ?>>
						<?php if ( $image ) : ?>
							<img class="skt-slick-img" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $slide['title'] ); ?>">
						<?php endif; ?>

						<?php if ( $slide['title'] || $slide['subtitle'] ) : ?>
							<div class="skt-slick-content">
								<?php
									if ( $slide['title'] ) {
										printf( '<%1$s class="skt-slick-title">%2$s</%1$s>',
											skt_addons_elementor_escape_tags( $settings['title_tag'], 'h2' ),
											skt_addons_elementor_kses_basic( $slide['title'] )
										);
									}
								?>
								<?php if ( $slide['subtitle'] ) : ?>
									<p class="skt-slick-subtitle"><?php echo wp_kses_post(skt_addons_elementor_kses_basic( $slide['subtitle'] )); ?></p>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</<?php echo wp_kses_post($item_tag); ?>>
				</div>

			<?php endforeach; ?>

		</div>

		<?php if ( ! empty( $settings['arrow_prev_icon']['value'] ) ) : ?>
			<button type="button" class="slick-prev"><?php Icons_Manager::render_icon( $settings['arrow_prev_icon'], ['aria-hidden' => 'true'] ); ?></button>
		<?php endif; ?>

		<?php if ( ! empty( $settings['arrow_next_icon']['value'] ) ) : ?>
			<button type="button" class="slick-next"><?php Icons_Manager::render_icon( $settings['arrow_next_icon'], ['aria-hidden' => 'true'] ); ?></button>
		<?php endif; ?>

		<?php
	}
}