<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/*
 * Filter Tabs
 */
$this->start_controls_section(
	'blog_page_filter_section',
	[
		'label'     => __( 'Filter Style', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'enable_filter_menu' => [ 'yes' ],
		],
	]
);

$this->start_controls_tabs( 'blog_page_filter_tabs' );

$this->start_controls_tab(
	'blog_page_filter_tabs_normal',
	[
		'label' => __( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'label'    => __( 'Typography', 'absolute-addons' ),
		'name'     => 'blog_page_filter_typography',
		'selector' => '{{WRAPPER}} .absp-blog-page .absp-filter-menu .absp-filter-item a',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'blog_page_filter_background',
		'fields_options' => [
			'background' => [
				'label' => __( 'Filter Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-blog-page .absp-filter-menu .absp-filter-item a',
		'default'        => '',
	]
);

$this->add_control(
	'blog_page_filter_color',
	[
		'label'     => __( 'Filter Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-blog-page .absp-filter-menu .absp-filter-item a' => 'color: {{VALUE}}',
		],
	]
);

$this->add_control(
	'blog_page_filter_divider_color',
	[
		'label'     => __( 'Filter Divider Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-blog-page .absp-filter-menu' => 'border-color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => __( 'Border', 'absolute-addons' ),
		'name'     => 'blog_page_filter_border',
		'selector' => '{{WRAPPER}} .absp-blog-page .absp-filter-menu .absp-filter-item a',
	]
);

$this->add_responsive_control(
	'blog_page_border_radius',
	[
		'label'      => __( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-blog-page .absp-filter-menu .absp-filter-item a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'blog_page_filter_padding',
	[
		'label'      => __( 'Filter Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-blog-page .absp-filter-menu .absp-filter-item a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'blog_page_filter_margin',
	[
		'label'      => __( 'Filter Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-blog-page .absp-filter-menu .absp-filter-item a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'blog_pagefilter_tabs_hover',
	[
		'label' => __( 'Hover', 'absolute-addons' ),
	]
);



$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'blog_page_filter_background_hover',
		'fields_options' => [
			'background' => [
				'label' => __( 'Filter Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-blog-page .absp-filter-menu .absp-filter-item a:hover',
		'default'        => '',
	]
);

$this->add_control(
	'blog_page_filter_color_hover',
	[
		'label'     => __( 'Filter Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-blog-page .absp-filter-menu .absp-filter-item a:hover' => 'color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => __( 'Border', 'absolute-addons' ),
		'name'     => 'blog_page_filter_border_hover',
		'selector' => '{{WRAPPER}} .absp-blog-page .absp-filter-menu .absp-filter-item a:hover',
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'blog_page_filter_tabs_active',
	[
		'label' => __( 'Active', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'blog_page_filter_background_active',
		'fields_options' => [
			'background' => [
				'label' => __( 'Filter Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-blog-page .absp-filter-menu .absp-filter-item a.is-active',
		'default'        => '',
	]
);

$this->add_control(
	'blog_page_filter_color_active',
	[
		'label'     => __( 'Filter Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-blog-page .absp-filter-menu .absp-filter-item a.is-active' => 'color: {{VALUE}}',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => __( 'Border', 'absolute-addons' ),
		'name'     => 'blog_page_filter_border_active',
		'selector' => '{{WRAPPER}} .absp-blog-page .absp-filter-menu .absp-filter-item a.is-active',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

/*
 * Feature Image
 */
$this->start_controls_section(
	'blog_page_feature_image',
	[
		'label'     => __( 'Feature Image', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absp_blog_posts_feature_img' => [ 'yes' ],
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(), [
		'name'     => 'absp_blog_posts_feature_img_shadow',
		'selector' => '{{WRAPPER}} .absp-blog-page-item .absp-blog-page-image',
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => __( 'Border', 'absolute-addons' ),
		'name'     => 'blog_page_feature_image_border',
		'selector' => '{{WRAPPER}} .absp-blog-page-item .absp-blog-page-image',
	]
);

$this->add_group_control(
	Group_Control_Css_Filter::get_type(),
	[
		'name'     => 'blog_page_feature_image_css_filters',
		'selector' => '{{WRAPPER}} .absp-blog-page-item .absp-blog-page-image img',
	]
);

$this->add_responsive_control(
	'blog_page_feature_image_border_radius',
	[
		'label'      => __( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-blog-page-item .absp-blog-page-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'blog_page_feature_image_padding',
	[
		'label'      => __( 'Feature Image Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-blog-page-item .absp-blog-page-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'blog_page_feature_image_margin',
	[
		'label'      => __( 'Feature Image Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-blog-page-item .absp-blog-page-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

/*
 * Title
 */
$this->start_controls_section(
	'blog_page_title',
	[
		'label'     => __( 'Title', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absp_blog_page_title' => 'yes',
		],
	]
);

$this->start_controls_tabs( 'blog_page_title_tabs' );

$this->start_controls_tab(
	'blog_page_title_tabs_normal',
	[
		'label' => __( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(), [
		'name'     => 'absp_blog_page_title_typography',
		'selector' => '{{WRAPPER}} .absp-blog-page .absp-blog-page-title a',
	]
);

$this->add_control(
	'absp_blog_page_title_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-blog-page .absp-blog-page-title a' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Text_Shadow::get_type(), [
		'name'     => 'absp_blog_page_title_shadow',
		'selector' => '{{WRAPPER}} .absp-blog-page .absp-blog-page-title a',
	]
);

$this->add_responsive_control(
	'absp_blog_page_title_alignment',
	[
		'label'     => __( 'Alignment', 'absolute-addons' ),
		'type'      => Controls_Manager::CHOOSE,
		'options'   => [
			'left'    => [
				'title' => __( 'Left', 'absolute-addons' ),
				'icon'  => 'eicon-text-align-left',
			],
			'center'  => [
				'title' => __( 'Center', 'absolute-addons' ),
				'icon'  => 'eicon-text-align-center',
			],
			'right'   => [
				'title' => __( 'Right', 'absolute-addons' ),
				'icon'  => 'eicon-text-align-right',
			],
			'justify' => [
				'title' => __( 'justify', 'absolute-addons' ),
				'icon'  => 'eicon-text-align-justify',
			],
		],
		'default'   => 'left',
		'devices'   => [ 'desktop', 'tablet', 'mobile' ],
		'selectors' => [
			'{{WRAPPER}} .absp-blog-page .absp-blog-page-title' => 'text-align: {{VALUE}};',
		],
	]
);

$this->add_responsive_control(
	'blog_page_title_margin',
	[
		'label'      => __( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-blog-page .absp-blog-page-title a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'absp_blog_page_title_hover',
	[
		'label' => __( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(), [
		'name'     => 'absp_blog_page_title_typography_hover',
		'selector' => '{{WRAPPER}} .absp-blog-page:hover .absp-blog-page-title a',
	]
);

$this->add_control(
	'absp_blog_page_title_hover_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-blog-page .absp-blog-page-title a:hover' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Text_Shadow::get_type(), [
		'name'     => 'absp_blog_page_title_hover_shadow',
		'selector' => '{{WRAPPER}} .absp-blog-page .absp-blog-page-title a:hover',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

/*
 * Meta Data
 */
$this->start_controls_section(
	'blog_page_meta',
	[
		'label'     => __( 'Meta Data', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absp_blog_posts_meta' => 'yes',
		],
	]
);

$this->start_controls_tabs( 'blog_page_meta_tabs' );

$this->start_controls_tab(
	'blog_page_meta_tabs_normal',
	[
		'label' => __( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(), [
		'name'     => 'absp_blog_page_meta_typography',
		'selector' => '{{WRAPPER}} .absp-blog-page :is(.absp-blog-page-author a, .absp-blog-page-comment a, .absp-blog-page-like)',
	]
);

$this->add_control(
	'absp_blog_page_meta_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-blog-page :is(.absp-blog-page-author a, .absp-blog-page-comment a, .absp-blog-page-like)' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Text_Shadow::get_type(), [
		'name'     => 'absp_blog_page_meta_shadow',
		'selector' => '{{WRAPPER}} .absp-blog-page :is(.absp-blog-page-author a, .absp-blog-page-comment a, .absp-blog-page-like)',
	]
);

$this->add_responsive_control(
	'blog_page_meta_margin',
	[
		'label'      => __( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-blog-page .absp-blog-page-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(), [
		'name'           => 'absp_blog_page_author_label_typography',
		'fields_options' => [
			'typography' => [
				'label' => __( 'Author Label Typography', 'absolute-addons' ),
			],
		],
		'separator'      => 'before',
		'selector'       => '{{WRAPPER}} .absp-blog-page .absp-blog-page-author .absp-author-label',
	]
);

$this->add_control(
	'absp_blog_page_author_label_color',
	[
		'label'     => __( 'Author Label Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-blog-page .absp-blog-page-author .absp-author-label' => 'color: {{VALUE}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'absp_blog_page_meta_hover',
	[
		'label' => __( 'Hover', 'absolute-addons' ),
	]
);

$this->add_control(
	'absp_blog_page_meta_hover_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-blog-page :is(.absp-blog-page-author a:hover, .absp-blog-page-comment a:hover, .absp-blog-page-like:hover)' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Text_Shadow::get_type(), [
		'name'     => 'absp_blog_page_meta_hover_shadow',
		'selector' => '{{WRAPPER}} .absp-blog-page :is(.absp-blog-page-author a:hover, .absp-blog-page-comment a:hover, .absp-blog-page-like:hover)',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

/*
 * Date & Time
 */
$this->start_controls_section(
	'blog_page_date_time',
	[
		'label'     => __( 'Data & Time', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absp_blog_posts_meta' => 'yes',
		],
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'blog_page_date_time_background',
		'fields_options' => [
			'background' => [
				'label' => __( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-blog-page .absp-blog-page-meta-date',
		'default'        => '',
		'condition'      => [
			'absolute_blog_page' => [ 'one', 'ten' ],
		],
	]
);

$this->add_responsive_control(
	'blog_page_date_time_border_radius',
	[
		'label'      => __( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-blog-page .absp-blog-page-meta-date' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'condition'  => [
			'absolute_blog_page' => [ 'one', 'ten' ],
		],
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(), [
		'name'      => 'blog_page_date_time_border_shadow',
		'selector'  => '{{WRAPPER}} .absp-blog-page .absp-blog-page-meta-date',
		'condition' => [
			'absolute_blog_page' => [ 'one', 'ten' ],
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(), [
		'name'     => 'absp_blog_page_date_typography',
		'selector' => '{{WRAPPER}} .absp-blog-page :is(.absp-blog-page-date, .absp-blog-page-time)',
	]
);

$this->add_control(
	'absp_blog_page_meta_date_time_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-blog-page :is(.absp-blog-page-date, .absp-blog-page-time)' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Text_Shadow::get_type(), [
		'name'      => 'absp_blog_page_meta_date_shadow',
		'selector'  => '{{WRAPPER}} .absp-blog-page :is(.absp-blog-page-date, .absp-blog-page-time)',
		'separator' => 'after',
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(), [
		'name'           => 'absp_blog_page_meta_date_typography',
		'fields_options' => [
			'typography' => [
				'label' => __( 'Date Typography', 'absolute-addons' ),
			],
		],
		'selector'       => '{{WRAPPER}} .absp-blog-page .absp-blog-page-date .absp-date',
		'condition'      => [
			'absolute_blog_page' => [ 'one', 'ten' ],
		],
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'blog_page_date_background',
		'fields_options' => [
			'background' => [
				'label' => __( 'Date Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-blog-page .absp-blog-page-date .absp-date',
		'default'        => '',
		'condition'      => [
			'absolute_blog_page' => [ 'one', 'ten' ],
		],
	]
);

$this->add_control(
	'absp_blog_page_meta_date_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-blog-page .absp-blog-page-date .absp-date' => 'color: {{VALUE}};',
		],
		'condition' => [
			'absolute_blog_page' => [ 'one', 'ten' ],
		],
	]
);

$this->add_responsive_control(
	'blog_page_date_border_radius',
	[
		'label'      => __( 'Date Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-blog-page .absp-blog-page-date .absp-date' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
		'condition'  => [
			'absolute_blog_page' => [ 'one', 'ten' ],
		],
	]
);

$this->add_responsive_control(
	'blog_page_meta_date_time_padding',
	[
		'label'      => __( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-blog-page .absp-blog-page-meta-date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'blog_page_meta_date_time_margin',
	[
		'label'      => __( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-blog-page .absp-blog-page-meta-date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_section();

/*
 * Category
 */
$this->start_controls_section(
	'blog_page_meta_category',
	[
		'label'     => __( 'Category', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absp_blog_posts_meta' => 'yes',
			'absp_category_select' => 'yes',

		],
	]
);

$this->start_controls_tabs( 'blog_page_meta_category_tabs' );

$this->start_controls_tab(
	'blog_page_meta_category_tabs_normal',
	[
		'label' => __( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(), [
		'name'     => 'absp_blog_page_meta_category_typography',
		'selector' => '{{WRAPPER}} .absp-blog-page .absp-blog-page-category a',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'blog_page_category_background',
		'fields_options' => [
			'background' => [
				'label' => __( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-blog-page .absp-blog-page-category a',
		'default'        => '',
	]
);

$this->add_control(
	'absp_blog_page_meta_category_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-blog-page .absp-blog-page-category a' => 'color: {{VALUE}};',
		],
	]
);

$this->add_responsive_control(
	'blog_page_category_border_radius',
	[
		'label'      => __( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-blog-page .absp-blog-page-category a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Text_Shadow::get_type(), [
		'name'     => 'absp_blog_page_meta_category_shadow',
		'selector' => '{{WRAPPER}} .absp-blog-page .absp-blog-page-category a',
	]
);

$this->add_responsive_control(
	'blog_page_meta_category_margin',
	[
		'label'      => __( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-blog-page .absp-blog-page-category a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'blog_page_meta_category_padding',
	[
		'label'      => __( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-blog-page .absp-blog-page-category a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'absp_blog_page_meta_category_hover',
	[
		'label' => __( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'blog_page_category_background_hover',
		'fields_options' => [
			'background' => [
				'label' => __( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-blog-page .absp-blog-page-category a:hover',
		'default'        => '',
	]
);

$this->add_control(
	'absp_blog_page_meta_category_hover_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-blog-page .absp-blog-page-category a:hover' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Text_Shadow::get_type(), [
		'name'     => 'absp_blog_page_meta_category_hover_shadow',
		'selector' => '{{WRAPPER}} .absp-blog-page .absp-blog-page-category a:hover',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();
/*
 * Comment
 */
$this->start_controls_section(
	'blog_page_meta_comment',
	[
		'label'     => __( 'Comment', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absp_blog_posts_meta' => 'yes',
			'absp_comment_select'  => 'yes',

		],
	]
);

$this->start_controls_tabs( 'blog_page_meta_comment_tabs' );

$this->start_controls_tab(
	'blog_page_meta_comment_tabs_normal',
	[
		'label' => __( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(), [
		'name'     => 'absp_blog_page_meta_comment_typography',
		'selector' => '{{WRAPPER}} .absp-blog-page .absp-blog-page-comment',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'blog_page_comment_background',
		'fields_options' => [
			'background' => [
				'label' => __( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-blog-page .absp-blog-page-comment ',
		'default'        => '',
	]
);

$this->add_control(
	'absp_blog_page_meta_comment_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-blog-page .absp-blog-page-comment a' => 'color: {{VALUE}};',
		],
	]
);

$this->add_responsive_control(
	'blog_page_comment_border_radius',
	[
		'label'      => __( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-blog-page .absp-blog-page-comment a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Text_Shadow::get_type(), [
		'name'     => 'absp_blog_page_meta_comment_shadow',
		'selector' => '{{WRAPPER}} .absp-blog-page .absp-blog-page-comment a',
	]
);

$this->add_responsive_control(
	'blog_page_meta_comment_margin',
	[
		'label'      => __( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-blog-page .absp-blog-page-comment a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'blog_page_meta_comment_padding',
	[
		'label'      => __( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-blog-page .absp-blog-page-comment a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'absp_blog_page_meta_comment_hover',
	[
		'label' => __( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'blog_page_comment_background_hover',
		'fields_options' => [
			'background' => [
				'label' => __( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-blog-page .absp-blog-page-comment a:hover',
		'default'        => '',
	]
);

$this->add_control(
	'absp_blog_page_meta_comment_hover_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-blog-page .absp-blog-page-comment a:hover' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Text_Shadow::get_type(), [
		'name'     => 'absp_blog_page_meta_comment_hover_shadow',
		'selector' => '{{WRAPPER}} .absp-blog-page .absp-blog-page-comment a:hover',
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();

/*
 * Content
 */
$this->start_controls_section(
	'blog_page_content',
	[
		'label'     => __( 'Content', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absp_blog_posts_content' => 'yes',
		],
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(),
	[
		'name'      => 'absp_blog_page_content_typography',
		'selector'  => '{{WRAPPER}} .absp-blog-page .absp-blog-page-content',
		'condition' => [
			'absp_blog_posts_content' => 'yes',
		],
	]
);

$this->add_control(
	'absp_blog_page_content_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-blog-page .absp-blog-page-content' => 'color: {{VALUE}};',
		],
	]
);

$this->add_control(
	'absp_blog_page_content_hover_color',
	[
		'label'     => __( 'Hover Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-blog-page .absp-blog-page-content:hover' => 'color: {{VALUE}};',
		],
	]
);

$this->add_responsive_control(
	'absp_blog_page_content_alignment',
	[
		'label'     => __( 'Alignment', 'absolute-addons' ),
		'type'      => Controls_Manager::CHOOSE,
		'options'   => [
			'left'    => [
				'title' => __( 'Left', 'absolute-addons' ),
				'icon'  => 'eicon-text-align-left',
			],
			'center'  => [
				'title' => __( 'Center', 'absolute-addons' ),
				'icon'  => 'eicon-text-align-center',
			],
			'right'   => [
				'title' => __( 'Right', 'absolute-addons' ),
				'icon'  => 'eicon-text-align-right',
			],
			'justify' => [
				'title' => __( 'justify', 'absolute-addons' ),
				'icon'  => 'eicon-text-align-justify',
			],
		],
		'default'   => 'left',
		'devices'   => [ 'desktop', 'tablet', 'mobile' ],
		'selectors' => [
			'{{WRAPPER}} .absp-blog-page .absp-blog-page-content' => 'text-align: {{VALUE}};',
		],
	]
);

$this->add_responsive_control(
	'absp_blog_page_content_margin',
	[
		'label'      => __( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'em' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-blog-page .absp-blog-page-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'absp_show_highlight_border',
	[
		'label'   => __( 'Show Highlight Border', 'absolute-addons' ),
		'type'    => Controls_Manager::SWITCHER,
		'yes'     => __( 'Yes', 'absolute-addons' ),
		'none'    => __( 'No', 'absolute-addons' ),
		'default' => 'yes',
	]
);

$this->add_responsive_control(
	'absp_show_highlight_border_width',
	[
		'label'      => __( 'Highlight Border Width', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', 'rem', '%' ],
		'range'      => [
			'px'  => [
				'min'  => 0,
				'max'  => 100,
				'step' => 1,
			],
			'rem' => [
				'min' => 0,
				'max' => 10,
			],
			'%'   => [
				'min' => 0,
				'max' => 100,
			],
		],
		'default'    => [
			'unit' => '%',
			'size' => 100,
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-blog-page hr' => 'width: {{SIZE}}{{UNIT}};',
		],
		'condition'  => [
			'absp_show_highlight_border' => 'yes',
		],
	]
);

$this->add_responsive_control(
	'absp_show_highlight_border_height',
	[
		'label'      => __( 'Highlight Border Height', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px', 'rem', '%' ],
		'range'      => [
			'px'  => [
				'min'  => 0,
				'max'  => 100,
				'step' => 1,
			],
			'rem' => [
				'min' => 0,
				'max' => 10,
			],
			'%'   => [
				'min' => 0,
				'max' => 100,
			],
		],
		'default'    => [
			'unit' => 'px',
			'size' => 1,
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-blog-page hr' => 'height: {{SIZE}}{{UNIT}};',
		],
		'condition'  => [
			'absp_show_highlight_border' => 'yes',
		],
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'absp_show_highlight_border_bg',
		'fields_options' => [
			'background' => [
				'label' => __( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-blog-page hr',
		'default'        => '',
		'condition'      => [
			'absp_show_highlight_border' => 'yes',
		],
	]
);

$this->end_controls_section();

/*
 * Button
 */
$this->start_controls_section(
	'blog_page_btn',
	[
		'label'     => __( 'Button', 'absolute-addons' ),
		'tab'       => Controls_Manager::TAB_STYLE,
		'condition' => [
			'absp_blog_posts_read_more' => 'yes',
			'absolute_blog_page!'       => [ 'four', 'five', 'six', 'twelve', 'thirteen' ],
		],
	]
);

$this->start_controls_tabs( 'blog_page_btn_tabs' );

$this->start_controls_tab(
	'blog_page_btn_tabs_normal',
	[
		'label' => __( 'Normal', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Typography::get_type(), [
		'name'     => 'absp_blog_page_btn_typography',
		'selector' => '{{WRAPPER}} .absp-blog-page .absp-btn',
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'blog_page_btn_background',
		'fields_options' => [
			'background' => [
				'label' => __( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-blog-page .absp-btn',
		'default'        => '',
	]
);

$this->add_control(
	'absp_blog_page_btn_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-blog-page .absp-btn' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => __( 'Border', 'absolute-addons' ),
		'name'     => 'blog_page_btn_border',
		'selector' => '{{WRAPPER}} .absp-blog-page .absp-btn',
	]
);

$this->add_responsive_control(
	'blog_page_btn_border_radius',
	[
		'label'      => __( 'Border Radius', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', '%' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-blog-page .absp-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_group_control(
	Group_Control_box_Shadow::get_type(), [
		'name'     => 'absp_blog_page_btn_shadow',
		'selector' => '{{WRAPPER}} .absp-blog-page .absp-btn',
	]
);

$this->add_responsive_control(
	'blog_page_btn_padding',
	[
		'label'      => __( 'Padding', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-blog-page .absp-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_responsive_control(
	'blog_page_btn_margin',
	[
		'label'      => __( 'Margin', 'absolute-addons' ),
		'type'       => Controls_Manager::DIMENSIONS,
		'size_units' => [ 'px', 'rem' ],
		'selectors'  => [
			'{{WRAPPER}} .absp-blog-page .absp-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
		],
	]
);

$this->add_control(
	'absp_blog_page_btn_icon_color',
	[
		'label'     => __( 'Icon Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'separator' => 'before',
		'selectors' => [
			'{{WRAPPER}} .absp-blog-page .absp-btn  i' => 'color: {{VALUE}};',
		],
		'condition' => [
			'absp_blog_posts_btn_icons_switch' => 'yes',
		],
	]
);

$this->add_responsive_control(
	'absp_blog_page_btn_icon_gap',
	[
		'label'      => __( 'Icon gap', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 300,
				'step' => 1,
			],

		],
		'default'    => [
			'unit' => 'px',
			'size' => 5,
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-blog-page .absp-btn  i' => 'margin-left: {{size}}{{unit}};',
		],
		'condition'  => [
			'absp_blog_posts_btn_icons_switch' => 'yes',
		],
	]
);

$this->end_controls_tab();

$this->start_controls_tab(
	'absp_blog_page_btn_hover',
	[
		'label' => __( 'Hover', 'absolute-addons' ),
	]
);

$this->add_group_control(
	Group_Control_Background::get_type(),
	[
		'name'           => 'blog_page_btn_background_hover',
		'fields_options' => [
			'background' => [
				'label' => __( 'Background Color', 'absolute-addons' ),
			],
		],
		'types'          => [ 'classic', 'gradient' ],
		'selector'       => '{{WRAPPER}} .absp-blog-page .absp-btn:hover',
		'default'        => '',
	]
);

$this->add_control(
	'absp_blog_page_btn_hover_color',
	[
		'label'     => __( 'Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'selectors' => [
			'{{WRAPPER}} .absp-blog-page .absp-btn:hover' => 'color: {{VALUE}};',
		],
	]
);

$this->add_group_control(
	Group_Control_Border::get_type(),
	[
		'label'    => __( 'Border', 'absolute-addons' ),
		'name'     => 'blog_page_btn_border_hover',
		'selector' => '{{WRAPPER}} .absp-blog-page .absp-btn:hover',
	]
);

$this->add_group_control(
	Group_Control_Box_Shadow::get_type(), [
		'name'     => 'blog_page_btn_shadow_hover',
		'selector' => '{{WRAPPER}} .absp-blog-page .absp-btn:hover',
	]
);

$this->add_control(
	'absp_blog_page_btn_icon_color_hover',
	[
		'label'     => __( 'Icon Color', 'absolute-addons' ),
		'type'      => Controls_Manager::COLOR,
		'separator' => 'before',
		'selectors' => [
			'{{WRAPPER}} .absp-blog-page .absp-btn:hover i' => 'color: {{VALUE}};',
		],
		'condition' => [
			'absp_blog_posts_btn_icons_switch' => 'yes',
		],
	]
);

$this->add_responsive_control(
	'absp_blog_page_btn_icon_gap_hover',
	[
		'label'      => __( 'Icon gap', 'absolute-addons' ),
		'type'       => Controls_Manager::SLIDER,
		'size_units' => [ 'px' ],
		'range'      => [
			'px' => [
				'min'  => 0,
				'max'  => 300,
				'step' => 1,
			],

		],
		'default'    => [
			'unit' => 'px',
			'size' => 5,
		],
		'selectors'  => [
			'{{WRAPPER}} .absp-blog-page .absp-btn:hover  i' => 'margin-left: {{size}}{{unit}};',
		],
		'condition'  => [
			'absp_blog_posts_btn_icons_switch' => 'yes',
		],
	]
);

$this->end_controls_tab();

$this->end_controls_tabs();

$this->end_controls_section();
