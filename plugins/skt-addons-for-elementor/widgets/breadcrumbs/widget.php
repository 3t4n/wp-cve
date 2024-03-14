<?php
/**
 * Breadcrumbs Hour widget class
 *
 * @package Skt_Addons_Elementor
 */

namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Skt_Addons_Elementor_Pro\Breadcrumb_Trail;

defined('ABSPATH') || die();

class Breadcrumbs extends Base {
	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __('Breadcrumbs', 'skt-addons-elementor');
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'skti skti-breadcrumbs';
	}

	public function get_keywords() {
		return ['breadcrumb', 'breadcrumbs', 'crumb', 'crumbs', 'list', 'header', 'builder'];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		$this->__breadcrumbs_display_content_controls();
		$this->__breadcrumbs_content_controls();
	}

	protected function __breadcrumbs_display_content_controls() {

		$this->start_controls_section(
			'_section_breadcrumbs_display',
			[
				'label' => __('Display Text', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'home',
			[
				'label'                 => __( 'Homepage', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::TEXT,
				'default'               => __( 'Home', 'skt-addons-elementor' ),
				'dynamic'               => [
					'active'        => true,
					'categories'    => [ TagsModule::POST_META_CATEGORY ]
				],
			]
		);

		$this->add_control(
			'page_title',
			[
				'label'                 => __( 'Pages', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::TEXT,
				'default'               => __( 'Pages', 'skt-addons-elementor' ),
				'dynamic'               => [
					'active'        => true,
					'categories'    => [ TagsModule::POST_META_CATEGORY ]
				],
			]
		);

		$this->add_control(
			'search',
			[
				'label'                 => __( 'Search', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::TEXT,
				'default'               => __( 'Search results for:', 'skt-addons-elementor' ),
				'dynamic'               => [
					'active'        => true,
					'categories'    => [ TagsModule::POST_META_CATEGORY ]
				],
			]
		);

		$this->add_control(
			'error_404',
			[
				'label'                 => __( 'Error 404', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::TEXT,
				'default'               => __( '404 Not Found', 'skt-addons-elementor' ),
				'dynamic'               => [
					'active'        => true,
					'categories'    => [ TagsModule::POST_META_CATEGORY ]
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __breadcrumbs_content_controls() {

		$this->start_controls_section(
			'_section_breadcrumbs',
			[
				'label' => __('Breadcrumbs', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'home_icon',
			[
				'label'					=> __( 'Home Icon', 'skt-addons-elementor' ),
				'label_block'			=> false,
				'type'					=> Controls_Manager::ICONS,
				'default'				=> [
					'value'		=> 'fas fa-home',
					'library'	=> 'fa-solid',
				],
				'skin' => 'inline',
				'exclude_inline_options' => [ 'svg' ],
			]
		);

		$this->add_control(
			'separator_type',
			[
				'label'                 => __( 'Separator Type', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'icon',
				'options'               => [
					'text'          => __( 'Text', 'skt-addons-elementor' ),
					'icon'          => __( 'Icon', 'skt-addons-elementor' ),
				],
			]
		);

		$this->add_control(
			'separator_text',
			[
				'label'                 => __( 'Separator', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::TEXT,
				'default'               => __( '>', 'skt-addons-elementor' ),
				'condition'             => [
					'separator_type'    => 'text'
				],
			]
		);

		$this->add_control(
			'separator_icon',
			[
				'label'					=> __( 'Separator', 'skt-addons-elementor' ),
				'label_block'			=> false,
				'type'					=> Controls_Manager::ICONS,
				'default'				=> [
					'value'		=> 'fas fa-angle-right',
					'library'	=> 'fa-solid',
				],
				'skin' => 'inline',
				'exclude_inline_options' => [ 'svg' ],
				'condition'             => [
					'separator_type'    => 'icon'
				],
			]
		);

		$this->add_control(
			'show_on_front',
			[
				'label' => __( 'Show on front page', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'show_title',
			[
				'label' => __( 'Show last item', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'skt-addons-elementor' ),
				'label_off' => __( 'Hide', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'                 => __( 'Alignment', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::CHOOSE,
				'default'               => '',
				'options'               => [
					'left'      => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'    => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'     => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .skt-breadcrumbs'   => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
     * Register widget style controls
     */
	protected function register_style_controls() {
		$this->__breadcrumbs_style_controls();
		$this->__common_style_controls();
		$this->__home_style_controls();
		$this->__separator_style_controls();
		$this->__current_style_controls();
	}

	protected function __breadcrumbs_style_controls() {

		$this->start_controls_section(
			'_section_breadcrumbs_style',
			[
				'label' => __('Breadcrumbs', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'breadcrumbs_background',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .skt-breadcrumbs',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'breadcrumbs_border',
				'label'                 => __( 'Border', 'skt-addons-elementor' ),
				'selector'              => '{{WRAPPER}} .skt-breadcrumbs',
			]
		);

		$this->add_control(
			'breadcrumbs_border_radius',
			[
				'label'                 => __( 'Border Radius', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .skt-breadcrumbs' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'breadcrumbs_shadow',
				'label' => __( 'Box Shadow', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-breadcrumbs',
			]
		);

		$this->add_responsive_control(
			'breadcrumbs_margin',
			[
				'label'                 => __( 'Margin', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .skt-breadcrumbs' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'breadcrumbs_padding',
			[
				'label'                 => __( 'Padding', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .skt-breadcrumbs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __common_style_controls() {

		$this->start_controls_section(
			'_section_common_style',
			[
				'label' => __('Common', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'common_spacing',
			[
				'label'                 => __( 'Spacing', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' 	=> [
						'max' => 50,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .skt-breadcrumbs li' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-breadcrumbs li:last-child' => 'margin-right: 0;',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_common_style' );

		$this->start_controls_tab(
			'tab_common_normal',
			[
				'label'                 => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'common_color',
			[
				'label'                 => __( 'Color', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'selectors'             => [
					'{{WRAPPER}} .skt-breadcrumbs li span.skt-breadcrumbs-text' => 'color: {{VALUE}}'
				],
			]
		);

		// $this->add_control(
		// 	'common_background_color',
		// 	[
		// 		'label'                 => __( 'Background Color', 'skt-addons-elementor' ),
		// 		'type'                  => Controls_Manager::COLOR,
		// 		'selectors'             => [
		// 			'{{WRAPPER}} .skt-breadcrumbs li span.skt-breadcrumbs-text' => 'background-color: {{VALUE}}',
		// 		],
		// 	]
		// );

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'common_background_color',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .skt-breadcrumbs li span.skt-breadcrumbs-text',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'common_typography',
				'label'                 => __( 'Typography', 'skt-addons-elementor' ),
				'selector'              => '{{WRAPPER}} .skt-breadcrumbs li span.skt-breadcrumbs-text',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'common_border',
				'label'                 => __( 'Border', 'skt-addons-elementor' ),
				'selector'              => '{{WRAPPER}} .skt-breadcrumbs li span.skt-breadcrumbs-text',
			]
		);

		$this->add_control(
			'common_border_radius',
			[
				'label'                 => __( 'Border Radius', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-item a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .skt-breadcrumbs li span.skt-breadcrumbs-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_common_hover',
			[
				'label'                 => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'common_color_hover',
			[
				'label'                 => __( 'Color', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'selectors'             => [
					'{{WRAPPER}} .skt-breadcrumbs li span.skt-breadcrumbs-text:hover' => 'color: {{VALUE}}',
				],
			]
		);

		// $this->add_control(
		// 	'common_background_color_hover',
		// 	[
		// 		'label'                 => __( 'Background Color', 'skt-addons-elementor' ),
		// 		'type'                  => Controls_Manager::COLOR,
		// 		'selectors'             => [
		// 			'{{WRAPPER}} .skt-breadcrumbs li span.skt-breadcrumbs-text:hover' => 'background-color: {{VALUE}}',
		// 		],
		// 	]
		// );

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'common_background_color_hover',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .skt-breadcrumbs li span.skt-breadcrumbs-text:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'common_typography_hover',
				'label'                 => __( 'Typography', 'skt-addons-elementor' ),
				'exclude' => [
					'font_family',
					'font_size',
					'text_transform',
					'font_style',
					'line_height',
					'letter_spacing',
				],
				'selector'              => '{{WRAPPER}} .skt-breadcrumbs li span.skt-breadcrumbs-text:hover',
			]
		);

		$this->add_control(
			'common_border_color_hover',
			[
				'label'                 => __( 'Border Color', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'selectors'             => [
					'{{WRAPPER}} .skt-breadcrumbs li span.skt-breadcrumbs-text:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'common_shadow',
				'label' => __( 'Box Shadow', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-breadcrumbs li span.skt-breadcrumbs-text',
			]
		);

		$this->add_responsive_control(
			'common_padding',
			[
				'label'                 => __( 'Padding', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .skt-breadcrumbs li span.skt-breadcrumbs-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __home_style_controls() {

		$this->start_controls_section(
			'_section_home_style',
			[
				'label' => __('Home', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_home_style' );

		$this->start_controls_tab(
			'tab_home_normal',
			[
				'label'                 => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'home_color',
			[
				'label'                 => __( 'Color', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'selectors'             => [
					'{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-start span.skt-breadcrumbs-text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'home_icon_color',
			[
				'label'                 => __( 'Home Icon Color', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'selectors'             => [
					'{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-start span.skt-breadcrumbs-text .skt-breadcrumbs-home-icon' => 'color: {{VALUE}}',
				],
			]
		);

		// $this->add_control(
		// 	'home_background_color',
		// 	[
		// 		'label'                 => __( 'Background Color', 'skt-addons-elementor' ),
		// 		'type'                  => Controls_Manager::COLOR,
		// 		'default'               => '',
		// 		'selectors'             => [
		// 			'{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-start span.skt-breadcrumbs-text' => 'background-color: {{VALUE}}',
		// 		],
		// 	]
		// );

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'home_background_color',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-start span.skt-breadcrumbs-text',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'home_typography',
				'label'                 => __( 'Typography', 'skt-addons-elementor' ),
				'selector'              => '{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-start span.skt-breadcrumbs-text',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'home_border',
				'label'                 => __( 'Border', 'skt-addons-elementor' ),
				'selector'              => '{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-start span.skt-breadcrumbs-text',
			]
		);

		$this->add_control(
			'home_border_radius',
			[
				'label'                 => __( 'Border Radius', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-start a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-start span.skt-breadcrumbs-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_home_hover',
			[
				'label'                 => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'home_color_hover',
			[
				'label'                 => __( 'Color', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-start span.skt-breadcrumbs-text:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'home_icon_color_hover',
			[
				'label'                 => __( 'Home Icon Color', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-start span.skt-breadcrumbs-text:hover .skt-breadcrumbs-home-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-start span.skt-breadcrumbs-text .skt-breadcrumbs-home-icon' => '-webkit-transition: all .4s;transition: all .4s;',
				],
			]
		);

		// $this->add_control(
		// 	'home_background_color_hover',
		// 	[
		// 		'label'                 => __( 'Background Color', 'skt-addons-elementor' ),
		// 		'type'                  => Controls_Manager::COLOR,
		// 		'default'               => '',
		// 		'selectors'             => [
		// 			'{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-start span.skt-breadcrumbs-text:hover' => 'background-color: {{VALUE}}',
		// 		],
		// 	]
		// );

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'home_background_color_hover',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-start span.skt-breadcrumbs-text:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'home_typography_hover',
				'label'                 => __( 'Typography', 'skt-addons-elementor' ),
				'exclude' => [
					'font_family',
					'font_size',
					'text_transform',
					'font_style',
					'line_height',
					'letter_spacing',
				],
				'selector'              => '{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-start span.skt-breadcrumbs-text:hover',
			]
		);

		$this->add_control(
			'home_border_color_hover',
			[
				'label'                 => __( 'Border Color', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-start span.skt-breadcrumbs-text:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'home_spacing',
			[
				'label'                 => __( 'Home Icon Spacing', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' 	=> [
						'max' => 50,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .skt-breadcrumbs li span.skt-breadcrumbs-home-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'separator'             => 'before',
			]
		);

		$this->end_controls_section();
	}

	protected function __separator_style_controls() {

		$this->start_controls_section(
			'_section_separator_style',
			[
				'label' => __('Separator', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'separator_color',
			[
				'label'                 => __( 'Color', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'selectors'             => [
					'{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-separator span.skt-breadcrumbs-separator-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-separator span.skt-breadcrumbs-separator-text' => 'color: {{VALUE}}',
				],
			]
		);

		// $this->add_control(
		// 	'separator_background_color',
		// 	[
		// 		'label'                 => __( 'Background Color', 'skt-addons-elementor' ),
		// 		'type'                  => Controls_Manager::COLOR,
		// 		'default'               => '',
		// 		'selectors'             => [
		// 			'{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-separator span.skt-breadcrumbs-separator-icon' => 'background-color: {{VALUE}}',
		// 			'{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-separator span.skt-breadcrumbs-separator-text' => 'background-color: {{VALUE}}',
		// 		],
		// 	]
		// );

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'separator_background_color',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-separator span.skt-breadcrumbs-separator-icon, {{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-separator span.skt-breadcrumbs-separator-text',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'separator_typography',
				'label'                 => __( 'Typography', 'skt-addons-elementor' ),
				'selector'              => '{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-separator span.skt-breadcrumbs-separator-icon, {{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-separator span.skt-breadcrumbs-separator-text',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'separator_border',
				'label'                 => __( 'Border', 'skt-addons-elementor' ),
				'selector'              => '{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-separator span.skt-breadcrumbs-separator-icon, {{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-separator span.skt-breadcrumbs-separator-icon',
			]
		);

		$this->add_control(
			'separator_border_radius',
			[
				'label'                 => __( 'Border Radius', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-separator span.skt-breadcrumbs-separator-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-separator span.skt-breadcrumbs-separator-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'separator_padding',
			[
				'label'                 => __( 'Padding', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-separator span.skt-breadcrumbs-separator-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-separator span.skt-breadcrumbs-separator-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __current_style_controls() {

		$this->start_controls_section(
			'_section_current_style',
			[
				'label' => __('Current', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'current_color',
			[
				'label'                 => __( 'Color', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'selectors'             => [
					'{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-item.skt-breadcrumbs-end span.skt-breadcrumbs-text' => 'color: {{VALUE}}',
				],
			]
		);

		// $this->add_control(
		// 	'current_background_color',
		// 	[
		// 		'label'                 => __( 'Background Color', 'skt-addons-elementor' ),
		// 		'type'                  => Controls_Manager::COLOR,
		// 		'default'               => '',
		// 		'selectors'             => [
		// 			'{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-item.skt-breadcrumbs-end span.skt-breadcrumbs-text' => 'background-color: {{VALUE}}',
		// 		],
		// 	]
		// );

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'current_background_color',
				'label' => __( 'Background', 'skt-addons-elementor' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-item.skt-breadcrumbs-end span.skt-breadcrumbs-text',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'current_typography',
				'label'                 => __( 'Typography', 'skt-addons-elementor' ),
				'selector'              => '{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-item.skt-breadcrumbs-end span.skt-breadcrumbs-text',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'current_border',
				'label'                 => __( 'Border', 'skt-addons-elementor' ),
				'selector'              => '{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-item.skt-breadcrumbs-end span.skt-breadcrumbs-text',
			]
		);

		$this->add_control(
			'current_border_radius',
			[
				'label'                 => __( 'Border Radius', 'skt-addons-elementor' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .skt-breadcrumbs li.skt-breadcrumbs-item.skt-breadcrumbs-end span.skt-breadcrumbs-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$home_icon = '';
		if($settings['home_icon']['value']){
			//$attributes = ! empty( $settings['home_icon']['value'] ) ? 'class="' . esc_attr($settings['home_icon']['value']) . '"' : '';
			$home_icon = sprintf( '<%1$s class="%2$s" aria-hidden="true"></%1$s>', skt_addons_elementor_escape_tags( 'i' ), esc_attr( $settings['home_icon']['value'] ) );
		}

		$separator = '';
		if( 'icon' === $settings['separator_type'] && $settings['separator_icon']['value'] ){
			$icon = sprintf( '<%1$s class="%2$s" aria-hidden="true"></%1$s>', skt_addons_elementor_escape_tags( 'i' ), esc_attr( $settings['separator_icon']['value'] ) );
			$attributes = 'class="skt-breadcrumbs-separator-icon"';
			$separator = sprintf( '<%1$s %2$s>%3$s</%1$s>', skt_addons_elementor_escape_tags( 'span' ), $attributes, $icon );
		}elseif( 'text' === $settings['separator_type'] && $settings['separator_text'] ){
			$attributes = 'class="skt-breadcrumbs-separator-text"';
			$separator = sprintf( '<%1$s %2$s>%3$s</%1$s>', skt_addons_elementor_escape_tags( 'span' ), $attributes, esc_html( $settings['separator_text'] ) );
		}

		$labels = array(
			'home' => $settings['home'] ? esc_html( $settings['home'] ) : '',
			'page_title' => $settings['page_title'] ? esc_html( $settings['page_title'] ) : '',
			'search' => $settings['search'] ? esc_html( $settings['search'] ).' %s' : '%s',
			'error_404' => $settings['error_404'] ? esc_html( $settings['error_404'] ) : '',
		);

		$args = array(
			'list_class'      => 'skt-breadcrumbs',
			'item_class'      => 'skt-breadcrumbs-item',
			'separator'      => $separator,
			'separator_class' => 'skt-breadcrumbs-separator',
			'home_icon' => $home_icon,
			'home_icon_class' => 'skt-breadcrumbs-home-icon',
			'labels' => $labels,
			'show_on_front' => 'yes' === $settings['show_on_front'] ? true : false,
			'show_title' => 'yes' === $settings['show_title'] ? true : false,
		);

		$breadcrumb = new Breadcrumb_Trail( $args );
		echo wp_kses_post($breadcrumb->trail());
	}
}