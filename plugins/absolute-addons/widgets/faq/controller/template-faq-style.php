<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Core\Schemes\Typography;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

$this->start_controls_section(
	'section_style_faq_general',
	[
		'label' => esc_html__( 'General FAQ Style', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->start_controls_tabs( 'faq_general_style' );

$this->start_controls_tab(
	'faq_normal',
	[
		'label' => esc_html__( 'Normal', 'absolute-addons' ),
	]
);

$this->add_responsive_control(
	'width',
	[
		'label'      => esc_html__( 'Width', 'absolute-addons' ),
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
			'{{WRAPPER}} .faq .content-entry' => 'max-width: {{SIZE}}{{UNIT}};',
			'{{WRAPPER}} .faq-item-six .content-entry .accordion-right-content' => 'max-width: {{SIZE}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'section_style_faq_body_background',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .faq .content-entry, {{WRAPPER}} .content-entry .accordion-right-content',
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'box_shadow',
		'label'    => esc_html__( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .faq .content-entry, {{WRAPPER}} .content-entry .accordion-right-content',
		'default'  =>
			[
				'horizontal' => 2,
				'vertical'   => 3,
				'blur'       => 2,
				'spread'     => 2,
				'color'      => 'rgba(0,0,0,0.05)',
			],

	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	array(
		'name'     => 'section_style_faq_general_border',
		'label'    => esc_html__( 'FAQ Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .faq .content-entry, {{WRAPPER}} .content-entry .accordion-right-content',
	)
);

$this->add_responsive_control(
	'body_section_border_radius',
	[
		'label'      => esc_html__( 'FAQ Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .faq .content-entry' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .content-entry .accordion-right-content .collapse-body' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'body_section_padding',
	[
		'label'      => esc_html__( 'FAQ Section Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .faq .content-entry' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .content-entry .accordion-right-content .collapse-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'faq_hover',
	[
		'label' => esc_html__( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'faq_hover_background',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .faq .content-entry:hover, {{WRAPPER}} .content-entry:hover .accordion-right-content .collapse-body',
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(),
	[
		'name'     => 'hover_box_shadow',
		'label'    => esc_html__( 'Box Shadow', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .faq .content-entry:hover, {{WRAPPER}} .content-entry:hover .accordion-right-content .collapse-body',
		'default'  =>
			[
				'horizontal' => 2,
				'vertical'   => 3,
				'blur'       => 2,
				'spread'     => 2,
				'color'      => 'rgba(0,0,0,0.05)',
			],

	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'faq_border_hover',
		'label'    => esc_html__( 'FAQs Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .faq .content-entry:hover, {{WRAPPER}} .content-entry:hover .accordion-right-content .collapse-body',
	]
);

$this->add_responsive_control(
	'hover_border_radius',
	[
		'label'      => esc_html__( 'FAQs Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .faq .content-entry:hover, {{WRAPPER}} .content-entry:hover .accordion-right-content .collapse-body' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'faq_title_style',
	array(
		'label' => esc_html__( 'FAQs Title', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	)
);

$this->start_controls_tabs( 'faq_title_style_tab' );

$this->start_controls_tab(
	'faq_title_normal',
	[
		'label' => esc_html__( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Typography', 'absolute-addons' ),
		'name'     => 'accordion_title_typography',
		'selector' => '{{WRAPPER}} .faq .collapse-head button, {{WRAPPER}} .content-entry .accordion-right-content .collapse-head button',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'faq_title_bg',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .faq .collapse-head button, {{WRAPPER}} .content-entry .accordion-right-content .collapse-head button',
	]
);

$this->add_control(
	'faq_title_color',
	[
		'label'     => esc_html__( 'FAQs Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .faq .collapse-head button, {{WRAPPER}} .content-entry .accordion-right-content .collapse-head button' => 'color: {{VALUE}}',
		],
	]
);

$this->add_responsive_control(
	'faq_title_padding',
	[
		'label'      => esc_html__( 'FAQs Title Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .faq .collapse-head button, {{WRAPPER}} .content-entry .accordion-right-content .collapse-head button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'faq_title_hover',
	[
		'label' => esc_html__( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'faq_title_bg_hover',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .faq .collapse-head:hover button, {{WRAPPER}} .content-entry .accordion-right-content .collapse-head:hover button',
	]
);

$this->add_control(
	'faq_title_color_hover',
	[
		'label'     => esc_html__( 'FAQs Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .faq .collapse-head:hover button' => 'color: {{VALUE}}',
			'{{WRAPPER}} .content-entry .accordion-right-content .collapse-head:hover button' => 'color: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'faq_title_active',
	[
		'label' => esc_html__( 'Active', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'faq_title_bg_active',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .content-entry.is-open .collapse-head button, {{WRAPPER}} .faq .content-entry.is-open .accordion-right-content .collapse-head button',
	]
);

$this->add_control(
	'faq_title_color_active',
	[
		'label'     => esc_html__( 'FAQs Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .faq .content-entry.is-open .collapse-head button'                     => 'color: {{VALUE}}',
			'{{WRAPPER}} .content-entry.is-open .accordion-right-content .collapse-head button' => 'color: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'faq_trim_style',
	[
		'label'     => esc_html__( 'Excerpt', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_faq' => [ 'three', 'seven' ],
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Typography', 'absolute-addons' ),
		'name'     => 'faq_trim_typography',
		'selector' => '{{WRAPPER}} .absp-faq .faq-item-seven .content-entry .faq-trim-words',
	]
);

$this->add_control(
	'faq_trim_color',
	[
		'label'     => esc_html__( 'Color
		', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-faq .faq-item-seven .content-entry .faq-trim-words' => 'color: {{VALUE}}',
		],
	]
);

$this->add_responsive_control(
	'faq_trim_padding',
	[
		'label'      => esc_html__( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-faq .faq-item-seven .content-entry .faq-trim-words' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'faq_content_style',
	[
		'label' => esc_html__( 'FAQs Content', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Typography', 'absolute-addons' ),
		'name'     => 'faq_content_typography',
		'selector' => '{{WRAPPER}} .faq .collapse-body, {{WRAPPER}} .accordion-right-content .collapse-body',
	]
);

$this->add_control(
	'faq_content_color',
	[
		'label'     => esc_html__( 'FAQs Content', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .faq .collapse-body' => 'color: {{VALUE}}',
			'{{WRAPPER}} .content-entry .accordion-right-content .collapse-body' => 'color: {{VALUE}}',
		],
	]
);

$this->add_responsive_control(
	'faq_content_padding',
	[
		'label'      => esc_html__( 'FAQs Content Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .faq .collapse-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .content-entry .accordion-right-content .collapse-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'faq_icon_style',
	[
		'label' => esc_html__( 'FAQs Icon', 'absolute-addons' ),
		'tab'   => Controls_Manager::TAB_STYLE,
	]
);

$this->start_controls_tabs( 'faq_icon_style_tab' );

$this->start_controls_tab(
	'faq_icon_normal',
	[
		'label' => esc_html__( 'Normal', 'absolute-addons' ),
	]
);

$this->add_control(
	'faq_icon_color',
	[
		'label'     => esc_html__( 'Accordion Icon Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .faq .content-entry i' => 'color: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'faq_icon_hover',
	[
		'label' => esc_html__( 'Hover & Active', 'absolute-addons' ),
	]
);

$this->add_control(
	'faq_icon_color_hover',
	[
		'label'     => esc_html__( 'Accordion Icon Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .faq .content-entry:hover i'   => 'color: {{VALUE}}',
			'{{WRAPPER}} .faq .content-entry.is-open i' => 'color: {{VALUE}}',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

$this->start_controls_section(
	'accordion_content_wrapper_style',
	[
		'label'     => esc_html__( 'Accordion Content Wrapper', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_faq' => [ 'ten', 'eleven', 'thirteen' ],
		],
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'      => 'accordion_content_wrapper_background',
		'label'     => esc_html__( 'Background', 'absolute-addons' ),
		'types'     => [ 'classic', 'gradient' ],
		'selector'  => '{{WRAPPER}} .faq-item-eleven .content-entry .collapse-body .accordion-content-wrapper',
		'condition' => [
			'absolute_faq' => 'eleven',
		],
	]
);

$this->add_responsive_control(
	'content_width',
	[
		'label'      => esc_html__( 'Content Width', 'absolute-addons' ),
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
			'{{WRAPPER}} .faq-item-eleven .content-entry .collapse-body .accordion-content-wrapper' => 'max-width: {{SIZE}}{{UNIT}};',
		],
		'condition'  => [
			'absolute_faq' => 'eleven',
		],
	]
);

$this->add_control(
	'accordion_content_icon_color',
	[
		'label'     => esc_html__( 'Accordion Content Icon Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .faq-item-ten .content-entry .collapse-body:is(.content-alignment-left, .content-alignment-right) .content-left .accordion-content-icon i' => 'color: {{VALUE}}',
		],
		'separator' => 'after',
		'condition' => [
			'absolute_faq' => 'ten',
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Typography', 'absolute-addons' ),
		'name'     => 'accordion_content_title_typography',
		'selector' => '{{WRAPPER}} .faq-item-ten .content-entry .collapse-body:is(.content-alignment-left, .content-alignment-right) .content-left .accordion-content-title, {{WRAPPER}} .faq-item-thirteen .content-entry .collapse-body .accordion-content-wrapper .accordion-content-title',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'      => 'accordion_content_title_background',
		'label'     => esc_html__( 'Background', 'absolute-addons' ),
		'types'     => [ 'classic', 'gradient' ],
		'selector'  => '{{WRAPPER}} .faq-item-eleven .content-entry .collapse-body .accordion-content-wrapper .accordion-content-title',
		'condition' => [
			'absolute_faq' => 'eleven',
		],
	]
);

$this->add_control(
	'accordion_content_title_color',
	[
		'label'     => esc_html__( 'Accordion Content Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .faq-item-ten .content-entry .collapse-body:is(.content-alignment-left, .content-alignment-right) .content-left .accordion-content-title' => 'color: {{VALUE}}',
			'{{WRAPPER}} .faq-item-eleven .content-entry .collapse-body .accordion-content-wrapper .accordion-content-title'                                       => 'color: {{VALUE}}',
			'{{WRAPPER}} .faq-item-thirteen .content-entry .collapse-body .accordion-content-wrapper .accordion-content-title'                                     => 'color: {{VALUE}}',
		],
	]
);

$this->add_responsive_control(
	'accordion_content_title_padding',
	[
		'label'      => esc_html__( 'Accordion Content Title Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .faq-item-ten .content-entry .collapse-body:is(.content-alignment-left, .content-alignment-right) .content-left .accordion-content-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .faq-item-eleven .content-entry .collapse-body .accordion-content-wrapper .accordion-content-title'                                       => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .faq-item-thirteen .content-entry .collapse-body .accordion-content-wrapper .accordion-content-title'                                     => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'separator'  => 'after',
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'     => esc_html__( 'Typography', 'absolute-addons' ),
		'name'      => 'accordion_content_sub_title_typography',
		'selector'  => '{{WRAPPER}} .faq-item-ten .content-entry .collapse-body:is(.content-alignment-left, .content-alignment-right) .content-left .accordion-content-sub-title',
		'condition' => [
			'absolute_faq' => 'ten',
		],
	]
);

$this->add_control(
	'accordion_content_sub_title_color',
	[
		'label'     => esc_html__( 'Accordion Content Sub Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .faq-item-ten .content-entry .collapse-body:is(.content-alignment-left, .content-alignment-right) .content-left .accordion-content-sub-title' => 'color: {{VALUE}}',
		],
		'condition' => [
			'absolute_faq' => 'ten',
		],
	]
);

$this->add_responsive_control(
	'accordion_content_sub_title_padding',
	[
		'label'      => esc_html__( 'Accordion Content Sub Title Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .faq-item-ten .content-entry .collapse-body:is(.content-alignment-left, .content-alignment-right) .content-left .accordion-content-sub-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'condition'  => [
			'absolute_faq' => 'ten',
		],
		'separator'  => 'after',
	]
);

$this->end_controls_section();

$this->start_controls_section(
	'accordion_content_button_style',
	[
		'label'     => esc_html__( 'Accordion Content Button', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absolute_faq' => [ 'eleven', 'twelve' ],
		],
	]
);

$this->start_controls_tabs( 'accordion_button_style_tab' );

$this->start_controls_tab(
	'accordion_button_normal',
	[
		'label' => esc_html__( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => esc_html__( 'Typography', 'absolute-addons' ),
		'name'     => 'accordion_button_typography',
		'selector' => '{{WRAPPER}} .faq-item-eleven .content-entry .collapse-body .accordion-content-wrapper .accordion-content-button, {{WRAPPER}} .faq-item-twelve .content-entry .collapse-body .accordion-grid a',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'accordion_content_button_background',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .faq-item-eleven .content-entry .collapse-body .accordion-content-wrapper .accordion-content-button, {{WRAPPER}} .faq-item-twelve .content-entry .collapse-body .accordion-grid a',
	]
);

$this->add_control(
	'accordion_content_button_color',
	[
		'label'     => esc_html__( 'Accordion Content Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .faq-item-eleven .content-entry .collapse-body .accordion-content-wrapper .accordion-content-button' => 'color: {{VALUE}}',
			'{{WRAPPER}} .faq-item-twelve .content-entry .collapse-body .accordion-grid a'                                    => 'color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'accordion_button_border',
		'label'    => esc_html__( 'Accordion Button Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .faq .content-entry, {{WRAPPER}} .content-entry .accordion-right-content, {{WRAPPER}} .faq-item-twelve .content-entry .collapse-body .accordion-grid a',
	]
);

$this->add_responsive_control(
	'accordion_content_button_padding',
	[
		'label'      => esc_html__( 'Accordion Content Title Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .faq-item-ten .content-entry .collapse-body:is(.content-alignment-left, .content-alignment-right) .content-left .accordion-content-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .faq-item-eleven .content-entry .collapse-body .accordion-content-wrapper .accordion-content-title'                                       => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			'{{WRAPPER}} .faq-item-twelve .content-entry .collapse-body .accordion-grid a'                                                                         => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'separator'  => 'after',
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'accordion_button_hover',
	[
		'label' => esc_html__( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'     => 'accordion_content_button_background_hover',
		'label'    => esc_html__( 'Background', 'absolute-addons' ),
		'types'    => [ 'classic', 'gradient' ],
		'selector' => '{{WRAPPER}} .faq-item-eleven .content-entry .collapse-body .accordion-content-wrapper .accordion-content-button:hover, {{WRAPPER}} .faq-item-twelve .content-entry .collapse-body .accordion-grid a:hover',
	]
);

$this->add_control(
	'accordion_content_button_color_hover',
	[
		'label'     => esc_html__( 'Accordion Content Title Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .faq-item-eleven .content-entry .collapse-body .accordion-content-wrapper .accordion-content-button:hover' => 'color: {{VALUE}}',
			'{{WRAPPER}} .faq-item-twelve .content-entry .collapse-body .accordion-grid a:hover'                                    => 'color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'name'     => 'accordion_button_border_hover',
		'label'    => esc_html__( 'Accordion Button Border', 'absolute-addons' ),
		'selector' => '{{WRAPPER}} .faq-item-eleven .content-entry .collapse-body .accordion-content-wrapper .accordion-content-button:hover, {{WRAPPER}} .faq-item-twelve .content-entry .collapse-body .accordion-grid a:hover',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

