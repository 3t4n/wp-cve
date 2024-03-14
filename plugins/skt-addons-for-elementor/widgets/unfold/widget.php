<?php

/**
 * Unfold widget class
 *
 * @package Skt_Addons
 */

namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || die();

class Unfold extends Base {

	public $button_condition = [
		'relation' => 'or',
		'terms'    => [
			[
				'relation' => 'and',
				'terms'    => [
					[
						'name'     => 'trigger',
						'operator' => '==',
						'value'    => 'hover',
					],
					[
						'name'     => 'button_disable',
						'operator' => '!=',
						'value'    => 'yes',
					],
				],
			],
			[
				'terms' => [
					[
						'name'     => 'trigger',
						'operator' => '==',
						'value'    => 'click',
					],
				],
			],
		],
	];

	/**
	 * Get widget title.
	 *
	 * @since 2.2.1
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Unfold', 'skt-addons-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.13.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'skti skti-unfold-paper';
	}

	public function get_keywords() {
		return ['unfold', 'fold'];
	}

	protected function register_content_controls() {
		$this->__register_content_tab();
		$this->__register_fold_tab();
		$this->__register_button_tab();
	}

	protected function __register_content_tab() {
		$this->start_controls_section(
			'_section_content',
			[
				'label' => __( 'Content', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'type'        => Controls_Manager::TEXT,
				'label'       => __( 'Title', 'skt-addons-elementor' ),
				'default'     => __( 'Unfold Magic', 'skt-addons-elementor' ),
				'placeholder' => __( 'Type Unfold Title', 'skt-addons-elementor' ),
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'   => __( 'Title HTML Tag', 'skt-addons-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
				'default' => 'h2',
			]
		);

		$this->add_control(
			'source',
			[
				'type'      => Controls_Manager::SELECT,
				'label'     => __( 'Content Source', 'skt-addons-elementor' ),
				'default'   => 'editor',
				'separator' => 'before',
				'options'   => [
					'editor'   => __( 'Editor', 'skt-addons-elementor' ),
					'template' => __( 'Template', 'skt-addons-elementor' ),
				],
			]
		);

		$this->add_control(
			'editor',
			[
				'label'      => __( 'Content Editor', 'skt-addons-elementor' ),
				'show_label' => false,
				'type'       => Controls_Manager::WYSIWYG,
				'default'    => '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>',
				'condition'  => [
					'source' => 'editor',
				],
				'dynamic'    => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'template',
			[
				'label'       => __( 'Section Template', 'skt-addons-elementor' ),
				'placeholder' => __( 'Select a section template for as tab content', 'skt-addons-elementor' ),
				'description' => sprintf(
					__( 'Wondering what is section template or need to create one? Please click %1$shere%2$s ', 'skt-addons-elementor' ),
					'<a target="_blank" href="' . esc_url( admin_url( '/edit.php?post_type=elementor_library&tabs_group=library&elementor_library_type=section' ) ) . '">',
					'</a>'
				),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'options'     => sktaddonselementorextra_get_section_templates(),
				'condition'   => [
					'source' => 'template',
				],
			]
		);

		$this->add_responsive_control(
			'content_align',
			[
				'label'          => __( 'Alignment', 'skt-addons-elementor' ),
				'type'           => Controls_Manager::CHOOSE,
				'options'        => [
					'start'  => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon'  => 'eicon-h-align-center',
					],
					'end'    => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'        => 'center',
				'toggle'         => false,
				'selectors'      => [
					'{{WRAPPER}} .skt-unfold-widget-wrapper' => 'align-items:{{VALUE}}; text-align: {{VALUE}}',
				],
				'style_transfer' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function __register_fold_tab() {
		$this->start_controls_section(
			'_section_fold',
			[
				'label' => __( 'Fold/ Unfold Options', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'trigger',
			[
				'label'              => __( 'Trigger', 'skt-addons-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'click',
				'options'            => [
					'click' => __( 'Click', 'skt-addons-elementor' ),
					'hover' => __( 'Hover', 'skt-addons-elementor' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'trigger_notice',
			[
				'raw'             => '<strong>' . esc_html__( 'Note!', 'skt-addons-elementor' ) . '</strong> ' . esc_html__( 'Please disable the button under "Button" section. ', 'skt-addons-elementor' ) . '<br>' . esc_html__( 'Having both button & trigger hover will make the button non functioning.', 'skt-addons-elementor' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'render_type'     => 'ui',
				'condition'       => [
					'trigger' => 'hover',
				],
			]
		);

		$this->add_responsive_control(
			'fold_height',
			[
				'label'              => __( 'Fold Height (px)', 'skt-addons-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0,
				'max'                => 1000,
				'step'               => 1,
				'default'            => 100,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'transition_duration',
			[
				'label'              => __( 'Transition Duration (ms)', 'skt-addons-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 1,
				'max'                => 5000,
				'step'               => 1,
				'default'            => 500,
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	protected function __register_button_tab() {

		$this->start_controls_section(
			'_section_button',
			[
				'label' => __( 'Button', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'button_disable',
			[
				'label'        => __( 'Disable Button?', 'skt-addons-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'skt-addons-elementor' ),
				'label_off'    => __( 'No', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => [
					'trigger' => 'hover',
				],
			]
		);

		$this->add_control(
			'content_position',
			[
				'label'        => __( 'Button Position Above Content?', 'skt-addons-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'skt-addons-elementor' ),
				'label_off'    => __( 'No', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default'      => '',
				'conditions'   => $this->button_condition,
			]
		);

		$this->add_control(
			'unfold_text',
			[
				'type'               => Controls_Manager::TEXT,
				'label'              => __( 'Unfold Text', 'skt-addons-elementor' ),
				'default'            => __( 'Read More', 'skt-addons-elementor' ),
				'placeholder'        => __( 'Type Unfold Text', 'skt-addons-elementor' ),
				'frontend_available' => true,
				'conditions'         => $this->button_condition,
				'dynamic'            => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'unfold_icon',
			[
				'label'                  => __( 'Unfold Icon', 'skt-addons-elementor' ),
				'type'                   => Controls_Manager::ICONS,
				'label_block'            => false,
				'skin'                   => 'inline',
				'exclude_inline_options' => ['svg'],
				'frontend_available'     => true,
				'conditions'             => $this->button_condition,
			]
		);

		$this->add_control(
			'fold_text',
			[
				'type'               => Controls_Manager::TEXT,
				'label'              => __( 'Fold Text', 'skt-addons-elementor' ),
				'default'            => __( 'Read Less', 'skt-addons-elementor' ),
				'placeholder'        => __( 'Type Fold Text', 'skt-addons-elementor' ),
				'frontend_available' => true,
				'conditions'         => $this->button_condition,
				'dynamic'            => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'fold_icon',
			[
				'label'                  => __( 'Fold Icon', 'skt-addons-elementor' ),
				'type'                   => Controls_Manager::ICONS,
				'label_block'            => false,
				'skin'                   => 'inline',
				'exclude_inline_options' => ['svg'],
				'frontend_available'     => true,
				'conditions'             => $this->button_condition,
			]
		);

		$this->add_control(
			'icon_position',
			[
				'label'      => __( 'Icon Position', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'before',
				'options'    => [
					'before' => __( 'Before', 'skt-addons-elementor' ),
					'after'  => __( 'After', 'skt-addons-elementor' ),
				],
				'conditions' => $this->button_condition,
			]
		);

		$this->add_responsive_control(
			'button_align',
			[
				'label'          => __( 'Alignment', 'skt-addons-elementor' ),
				'type'           => Controls_Manager::CHOOSE,
				'options'        => [
					'start'  => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon'  => 'eicon-h-align-center',
					],
					'end'    => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'toggle'         => true,
				'selectors'      => [
					'{{WRAPPER}} .skt-unfold-widget-wrapper .skt-unfold-btn' => 'align-self:{{VALUE}};',
				],
				'style_transfer' => true,
				'conditions'     => $this->button_condition,
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_controls() {
		$this->__register_style_box_tab();
		$this->__register_style_title_tab();
		$this->__register_style_content_tab();
		$this->__register_style_button_tab();
	}

	protected function __register_style_box_tab() {
		$this->start_controls_section(
			'_section_style_box',
			[
				'label' => __( 'Box', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'box_margin',
			[
				'label'      => __( 'Margin', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .skt-unfold-widget-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_padding',
			[
				'label'      => __( 'Padding', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'default'    => [
					'top'      => '20',
					'right'    => '20',
					'bottom'   => '20',
					'left'     => '20',
					'unit'     => 'px',
					'isLinked' => 'true',
				],
				'selectors'  => [
					'{{WRAPPER}} .skt-unfold-widget-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'box_border',
				'label'    => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} > .elementor-widget-container',
			]
		);

		$this->add_control(
			'box_border_radius',
			[
				'label'      => __( 'Border Radius', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} > .elementor-widget-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'box_background_tabs' );

		$this->start_controls_tab(
			'box_background_tab',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),

			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'box_background',
				'label'    => __( 'Background', 'skt-addons-elementor' ),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .skt-unfold-widget-wrapper',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_shadow',
				'label'    => __( 'Box Shadow', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} > .elementor-widget-container',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'box_background_tab_hover',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),

			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'box_background_hover',
				'label'    => __( 'Background', 'skt-addons-elementor' ),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .skt-unfold-widget-wrapper:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_shadow_hover',
				'label'    => __( 'Box Shadow', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}}:hover .elementor-widget-container',
			]
		);

		$this->add_control(
			'box_border_color_hover',
			[
				'label'     => __( 'Border Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'box_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}}:hover .elementor-widget-container' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'overlay_height',
			[
				'label'      => __( 'Overlay Height', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'separator'  => 'before',
				'size_units' => ['px', '%'],
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
				'default'    => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors'  => [
					'{{WRAPPER}} .skt-unfold-widget-wrapper .skt-unfold-data::after' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'overlay_color',
				'label'          => esc_html__( 'Overlay Color', 'skt-addons-elementor' ),
				'types'          => ['gradient'],
				'selector'       => '{{WRAPPER}} .skt-unfold-widget-wrapper .skt-unfold-data::after',
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Overlay Color', 'skt-addons-elementor' ),
					],
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __register_style_title_tab() {
		$this->start_controls_section(
			'_section_style_title',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label'      => __( 'Margin', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .skt-unfold-widget-wrapper .skt-unfold-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-unfold-widget-wrapper .skt-unfold-heading' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-unfold-widget-wrapper .skt-unfold-heading',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'title_shadow',
				'label'    => __( 'Text Shadow', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-unfold-widget-wrapper .skt-unfold-heading',
			]
		);

		$this->end_controls_section();
	}

	protected function __register_style_content_tab() {
		$this->start_controls_section(
			'_section_style_content',
			[
				'label'     => __( 'Content', 'skt-addons-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'source' => 'editor',
				],
			]
		);

		$this->add_responsive_control(
			'content_margin',
			[
				'label'      => __( 'Margin', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'default'    => [
					'top'      => '20',
					'right'    => '20',
					'bottom'   => '20',
					'left'     => '20',
					'unit'     => 'px',
					'isLinked' => 'true',
				],
				'selectors'  => [
					'{{WRAPPER}} .skt-unfold-widget-wrapper .skt-unfold-data-render' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_color',
			[
				'label'     => __( 'Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-unfold-widget-wrapper .skt-unfold-data-render' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'label'    => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-unfold-widget-wrapper .skt-unfold-data-render',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'content_shadow',
				'label'    => __( 'Shadow', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-unfold-widget-wrapper .skt-unfold-data-render',
			]
		);

		$this->end_controls_section();
	}

	protected function __register_style_button_tab() {
		$this->start_controls_section(
			'_section_style_button',
			[
				'label'      => __( 'Button', 'skt-addons-elementor' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => $this->button_condition,
			]
		);

		$this->add_responsive_control(
			'button_margin',
			[
				'label'      => __( 'Margin', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .skt-unfold-widget-wrapper .skt-unfold-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => __( 'Padding', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'default'    => [
					'top'      => '10',
					'right'    => '16',
					'bottom'   => '10',
					'left'     => '16',
					'unit'     => 'px',
					'isLinked' => 'false',
				],
				'selectors'  => [
					'{{WRAPPER}} .skt-unfold-widget-wrapper .skt-unfold-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_space_between',
			[
				'label'      => __( 'Space Between Icon & Text', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors'  => [
					'{{WRAPPER}} .skt-unfold-widget-wrapper .skt-unfold-btn.skt-unfold-icon-after i + span' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-unfold-widget-wrapper .skt-unfold-btn.skt-unfold-icon-before i + span' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'label'    => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-unfold-widget-wrapper .skt-unfold-btn',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'button_border',
				'label'    => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-unfold-widget-wrapper .skt-unfold-btn',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'      => __( 'Border Radius', 'skt-addons-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .skt-unfold-widget-wrapper .skt-unfold-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'button_tabs' );

		$this->start_controls_tab(
			'button_tab',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),

			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => __( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-unfold-widget-wrapper .skt-unfold-btn span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_icon_color',
			[
				'label'     => __( 'Icon Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-unfold-widget-wrapper .skt-unfold-btn i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'button_background',
				'label'    => __( 'Background', 'skt-addons-elementor' ),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .skt-unfold-widget-wrapper .skt-unfold-btn',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'button_shadow',
				'label'     => __( 'Box Shadow', 'skt-addons-elementor' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .skt-unfold-widget-wrapper .skt-unfold-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_tab_hover',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),

			]
		);

		$this->add_control(
			'button_text_color_hover',
			[
				'label'     => __( 'Text Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-unfold-widget-wrapper .skt-unfold-btn:hover span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_icon_color_hover',
			[
				'label'     => __( 'Icon Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-unfold-widget-wrapper .skt-unfold-btn:hover i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'button_background_hover',
				'label'    => __( 'Background', 'skt-addons-elementor' ),
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .skt-unfold-widget-wrapper .skt-unfold-btn:hover',
			]
		);

		$this->add_control(
			'button_border_color_hover',
			[
				'label'     => __( 'Border Color', 'skt-addons-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .skt-unfold-widget-wrapper .skt-unfold-btn:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'button_shadow_hover',
				'label'     => __( 'Box Shadow', 'skt-addons-elementor' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .skt-unfold-widget-wrapper .skt-unfold-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="skt-unfold-widget-wrapper<?php echo esc_attr( ( $settings['content_position'] == 'yes' ) ? ' skt-unfold-direction-below' : '' ); ?>">
			<?php if ( ! empty( $settings['title'] ) ) : ?>
				<?php
				printf(
					'<%1$s class="skt-unfold-heading">%2$s</%1$s>',
					skt_addons_elementor_escape_tags( $settings['title_tag'], 'h2' ),
					esc_html( $settings['title'] )
				);
				?>
			<?php endif; ?>
			<div class="skt-unfold-data">
				<div class="skt-unfold-data-render">
					<?php
					if ( $settings['source'] === 'editor' ) :
						echo $this->parse_text_editor( $settings['editor'] );
					elseif ( $settings['source'] === 'template' && $settings['template'] ) :
						echo skt_addons_elementor()->frontend->get_builder_content_for_display( $settings['template'] );
					endif;
					?>
				</div>
			</div>

			<?php if ( $settings['button_disable'] == null && $settings['button_disable'] != 'yes' ) : ?>
				<button class="skt-unfold-btn skt-unfold-icon-<?php echo esc_attr( $settings['icon_position'] ); ?>">
					<?php Icons_Manager::render_icon( $settings['unfold_icon'], ['aria-hidden' => 'true'] ); ?>
					<?php if ( ! empty( $settings['unfold_text'] ) ) : ?>
						<span><?php echo esc_html( $settings['unfold_text'] ); ?></span>
					<?php endif; ?>
				</button>
			<?php endif; ?>
		</div>
		<?php
	}
}
