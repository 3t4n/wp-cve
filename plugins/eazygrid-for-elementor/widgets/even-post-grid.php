<?php

namespace EazyGrid\Elementor\Widgets;

use EazyGrid\Elementor\Base\Post_Grid as Post_Grid_Base;
use EazyGrid\Elementor\Controls\Image_Selector;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Image_Size;

class Even_Post_Grid extends Post_Grid_Base {

	public function get_title() {
		return __( 'Even Post Grid', 'eazygrid-elementor' );
	}

	public function get_icon() {
		return 'ezicon ezicon-post-even';
	}

	public function get_keywords() {
		return ['eazygrid-elementor', 'eazygrid', 'eazygrid-elementor', 'eazy', 'grid', 'even'];
	}

	/**
	 * Register content controls
	 */
	public function register_content_controls() {
		$this->__layout_content_controls();
		$this->__query_content_controls();
	}


	protected function __layout_content_controls() {

		$this->start_controls_section(
			'_section_layout',
			[
				'label' => __( 'Layout', 'eazygrid-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label'              => __( 'Columns', 'eazygrid-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '3',
				'tablet_default'     => '2',
				'mobile_default'     => '1',
				'options'            => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				],
				'prefix_class'       => 'ezg-ele-even-post-grid%s-',
				'frontend_available' => true,
				'selectors'          => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper' => 'grid-template-columns: repeat( {{VALUE}}, 1fr );',
				],
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label'   => __( 'Posts Per Page', 'eazygrid-elementor' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 3,
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

		//Laout Style Start
		$this->layout_style_tab_controls();

		//Box Style Start
		$this->box_style_tab_controls();

		//Feature Image Style Start
		$this->image_style_tab_controls();

		//Badge Taxonomy Style Start
		$this->taxonomy_badge_style_tab_controls();

		//Content Style Start
		$this->content_style_tab_controls();

		//Meta Style Start
		$this->meta_style_tab_controls();

		//Readmore Style Start
		$this->readmore_style_tab_controls();

	}


	/**
	 * Layout Style controls
	 */
	protected function layout_style_tab_controls() {

		$this->start_controls_section(
			'_section_layout_style',
			[
				'label' => __( 'Layout', 'eazygrid-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'column_gap',
			[
				'label'     => __( 'Columns Gap', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 30,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label'     => __( 'Rows Gap', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 35,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Box Style controls
	 */
	protected function box_style_tab_controls() {

		$this->start_controls_section(
			'_section_item_box_style',
			[
				'label' => __( 'Item Box', 'eazygrid-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'item_box_padding',
			[
				'label'      => __( 'Padding', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-even-post-grid--item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'item_box_background',
				'label'    => __( 'Background', 'eazygrid-elementor' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .ezg-ele-even-post-grid--item',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'item_box_box_shadow',
				'label'    => __( 'Box Shadow', 'eazygrid-elementor' ),
				'selector' => '{{WRAPPER}} .ezg-ele-even-post-grid--item',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'item_box_border',
				'label'    => __( 'Border', 'eazygrid-elementor' ),
				'selector' => '{{WRAPPER}} .ezg-ele-even-post-grid--item',
			]
		);

		$this->add_responsive_control(
			'item_box_border_radius',
			[
				'label'      => __( 'Border Radius', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-even-post-grid--item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Image Style controls
	 */
	protected function image_style_tab_controls() {

		//Feature Post Image overlay color

		$this->start_controls_section(
			'_section_image_style',
			[
				'label' => __( 'Image', 'eazygrid-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->all_style_of_feature_image();

		$this->end_controls_section();
	}

	/**
	 * All Image Style
	 */
	protected function all_style_of_feature_image() {

		$this->image_overlay_style();

		$this->image_height_margin_style();

		$this->image_boxshadow_style();

		$this->image_border_styles();

		$this->image_border_radius_styles();

		$this->image_css_filter_styles();
	}

	/**
	 * Image Overlay Style
	 */
	protected function image_overlay_style() {

		//Feature Post Image overlay color
		$this->add_control(
			'feature_image_overlay_heading',
			[
				'label'       => __( 'Image Overlay', 'eazygrid-elementor' ),
				'description' => __( 'This overlay color only apply when post has an image.', 'eazygrid-elementor' ),
				'type'        => Controls_Manager::HEADING,
				'separator'   => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'        => 'feature_image_overlay',
				'label'       => __( 'Background', 'eazygrid-elementor' ),
				'description' => __( 'This overlay color only apply when post has an image.', 'eazygrid-elementor' ),
				'types'       => [ 'classic', 'gradient' ],
				'exclude'     => [
					'image',
				],
				'selector'    => '{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-thumb:before',
			]
		);

		$this->add_control(
			'feature_image_heading',
			[
				'label'     => __( 'Image', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

	}

	/**
	 * Image Height & margin Style
	 */
	protected function image_height_margin_style() {

		$this->add_responsive_control(
			'feature_image_height',
			[
				'label'      => __( 'Height', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-thumb-area' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'feature_image_margin_btm',
			[
				'label'      => __( 'Margin Bottom', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-thumb-area' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

	}

	/**
	 * Image boxshadow Style
	 */
	protected function image_boxshadow_style() {

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'feature_image_shadow',
				'label'    => __( 'Box Shadow', 'eazygrid-elementor' ),
				'selector' => '{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-thumb',
			]
		);
	}

	/**
	 * Image border Style
	 */
	protected function image_border_styles() {

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'feature_image_border',
				'label'    => __( 'Border', 'eazygrid-elementor' ),
				'selector' => '{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-thumb',
			]
		);

	}

	/**
	 * Image border radius Style
	 */
	protected function image_border_radius_styles() {

		$this->add_responsive_control(
			'feature_image_border_radius',
			[
				'label'      => __( 'Border Radius', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-thumb' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

	}

	/**
	 * Image css filter Style
	 */
	protected function image_css_filter_styles() {

		$this->start_controls_tabs( 'feature_image_tabs' );

		$this->start_controls_tab(
			'feature_image_normal_tab',
			[
				'label' => __( 'Normal', 'eazygrid-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'     => 'feature_image_css_filters',
				'selector' => '{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-thumb img',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'feature_image_hover_tab',
			[
				'label' => __( 'Hover', 'eazygrid-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'     => 'feature_image_hover_css_filters',
				'selector' => '{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-thumb:hover img',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

	}

	/**
	 * Taxonomy Badge Style controls
	 */
	protected function taxonomy_badge_style_tab_controls() {

		$this->start_controls_section(
			'_section_taxonomy_badge_style',
			[
				'label' => __( 'Badge', 'eazygrid-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->taxonomy_badge_position();

		$this->add_responsive_control(
			'badge_padding',
			[
				'label'      => __( 'Padding', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-badge a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'badge_border',
				'label'    => __( 'Border', 'eazygrid-elementor' ),
				'exclude'  => [
					'color',
				],
				'selector' => '{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-badge a',
			]
		);

		$this->add_responsive_control(
			'badge_border_radius',
			[
				'label'      => __( 'Border Radius', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-badge a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'badge_typography',
				'label'    => __( 'Typography', 'eazygrid-elementor' ),
				'scheme'   => Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-badge a',
			]
		);

		$this->start_controls_tabs( 'badge_tabs' );
		$this->start_controls_tab(
			'badge_normal_tab',
			[
				'label' => __( 'Normal', 'eazygrid-elementor' ),
			]
		);

		$this->add_control(
			'badge_color',
			[
				'label'     => __( 'Text Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-badge a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'badge_background',
				'label'    => __( 'Background', 'eazygrid-elementor' ),
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [
					'image',
				],
				'selector' => '{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-badge a',
			]
		);

		$this->add_control(
			'badge_border_color',
			[
				'label'     => __( 'Border Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-badge a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'badge_hover_tab',
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
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-badge a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'badge_hover_background',
				'label'    => __( 'Background', 'eazygrid-elementor' ),
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [
					'image',
				],
				'selector' => '{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-badge a:hover',
			]
		);

		$this->add_control(
			'badge_hover_border_color',
			[
				'label'     => __( 'Border Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-badge a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	/**
	 * Taxonomy badge Position
	 */
	protected function taxonomy_badge_position() {

		$this->add_control(
			'badge_position_toggle',
			[
				'label'        => __( 'Position', 'eazygrid-elementor' ),
				'type'         => \Elementor\Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'None', 'eazygrid-elementor' ),
				'label_on'     => __( 'Custom', 'eazygrid-elementor' ),
				'return_value' => 'yes',
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'badge_position_x',
			[
				'label'      => __( 'Position Left', 'eazygrid-elementor' ),
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
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-badge' => 'left: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-badge' => 'top: {{SIZE}}{{UNIT}};bottom:auto;',
				],
			]
		);
		$this->end_popover();

	}

	/**
	 * Content Style controls
	 */
	protected function content_style_tab_controls() {

		$this->start_controls_section(
			'_section_content_style',
			[
				'label' => __( 'Content', 'eazygrid-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		//Content area
		$this->add_responsive_control(
			'content_area_padding',
			[
				'label'      => __( 'Padding', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-content-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		//Post Title
		$this->add_control(
			'post_title_heading',
			[
				'label'     => __( 'Title', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'post_title_margin_btm',
			[
				'label'      => __( 'Margin Bottom', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-title' => 'margin-bottom: {{SIZE}}{{UNIT}}; margin-top: 0;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'post_title_typography',
				'label'    => __( 'Typography', 'eazygrid-elementor' ),
				'scheme'   => Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-title a',
			]
		);

		$this->start_controls_tabs( 'post_title_tabs');
		$this->start_controls_tab(
			'post_title_normal_tab',
			[
				'label' => __( 'Normal', 'eazygrid-elementor' ),
			]
		);

		$this->add_control(
			'post_title_color',
			[
				'label'     => __( 'Text Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-title a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'post_title_hover_tab',
			[
				'label' => __( 'Hover', 'eazygrid-elementor' ),
			]
		);

		$this->add_control(
			'post_title_hover_color',
			[
				'label'     => __( 'Text Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-title a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		//Feature Post Content
		$this->add_control(
			'post_content_heading',
			[
				'label'     => __( 'Content', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'post_content_margin_btm',
			[
				'label'      => __( 'Margin Bottom', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-excerpt'     => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-excerpt > p' => 'margin-bottom: 0;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'post_content_typography',
				'label'    => __( 'Typography', 'eazygrid-elementor' ),
				'scheme'   => Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-excerpt',
			]
		);

		$this->add_control(
			'post_content_color',
			[
				'label'     => __( 'Text Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-excerpt' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Meta Style controls
	 */
	protected function meta_style_tab_controls() {

		$this->start_controls_section(
			'_section_meta_style',
			[
				'label' => __( 'Meta', 'eazygrid-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		//Post Meta
		$this->add_control(
			'meta_heading',
			[
				'label'     => __( 'Meta', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'meta_icon_space',
			[
				'label'      => __( 'Icon Space', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-meta-wrap ul li i' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-meta-wrap ul li svg' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'meta_space',
			[
				'label'      => __( 'Space Between', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-meta-wrap ul li' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-meta-wrap ul li:last-child' => 'margin-right: 0;',
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-meta-wrap ul li + li:before' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'meta_margin_btm',
			[
				'label'      => __( 'Margin Bottom', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-meta-wrap ul li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'meta_border',
				'label'    => __( 'Border', 'eazygrid-elementor' ),
				'selector' => '{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-meta-wrap',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'meta_typography',
				'label'    => __( 'Typography', 'eazygrid-elementor' ),
				'scheme'   => Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-meta-wrap ul li a,{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-meta-wrap ul li + li:before',
			]
		);

		$this->add_control(
			'meta_color',
			[
				'label'     => __( 'Text Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-meta-wrap ul li' => 'color: {{VALUE}}',
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-meta-wrap ul li a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-meta-wrap ul li i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-meta-wrap ul li path' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}


	/**
	 * Added Read More Style controls
	 */
	protected function readmore_style_tab_controls() {

		$this->start_controls_section(
			'_section_readmore_style',
			[
				'label' => __( 'Read More', 'eazygrid-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'readmore_margin',
			[
				'label'      => __( 'Margin', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-readmore' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'readmore_padding',
			[
				'label'      => __( 'Padding', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-readmore a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'readmore_border',
				'label'    => __( 'Border', 'eazygrid-elementor' ),
				'exclude'  => [
					'color',
				],
				'selector' => '{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-readmore a',
			]
		);

		$this->add_responsive_control(
			'readmore_border_radius',
			[
				'label'      => __( 'Border Radius', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-readmore a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'readmore_typography',
				'label'    => __( 'Typography', 'eazygrid-elementor' ),
				'scheme'   => Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-readmore a',
			]
		);

		$this->start_controls_tabs( 'readmore_tabs' );
		$this->start_controls_tab(
			'readmore_normal_tab',
			[
				'label' => __( 'Normal', 'eazygrid-elementor' ),
			]
		);

		$this->add_control(
			'readmore_color',
			[
				'label'     => __( 'Text Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-readmore a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'readmore_background',
				'label'    => __( 'Background', 'eazygrid-elementor' ),
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [
					'image',
				],
				'selector' => '{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-readmore a',
			]
		);

		$this->add_control(
			'readmore_border_color',
			[
				'label'     => __( 'Border Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-readmore a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'readmore_hover_tab',
			[
				'label' => __( 'Hover', 'eazygrid-elementor' ),
			]
		);

		$this->add_control(
			'readmore_hover_color',
			[
				'label'     => __( 'Text Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-readmore a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'readmore_hover_background',
				'label'    => __( 'Background', 'eazygrid-elementor' ),
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [
					'image',
				],
				'selector' => '{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-readmore a:hover',
			]
		);

		$this->add_control(
			'readmore_border_hover_color',
			[
				'label'     => __( 'Border Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-even-post-grid-wraper .ezg-ele-post-grid-readmore a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	protected function render() {
		$settings       = $this->get_settings_for_display();
		$excerpt_length = 15;
		$category       = ( $settings['query_cat'] ) ? $settings['query_cat'] : 'recent';
		// WP_Query arguments
		$args = [
			'post_type'      => 'post',       // use any for any kind of post type, custom post type slug for custom post type
			'post_status'    => 'publish',    // Also support: pending, draft, auto-draft, future, private, inherit, trash, any
			'posts_per_page' => $settings['posts_per_page'], // use -1 for all post
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
				'ezg-ele-even-post-grid-wraper',
			],
		] );
		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'grid' ) ); ?>>
			<?php
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) :
					$query->the_post();
					?>
					<div class="ezg-ele-even-post-grid--item">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="ezg-ele-post-grid-thumb-area">
									<a href="<?php the_permalink(); ?>" class="ezg-ele-post-grid-thumb">
										<?php the_post_thumbnail(); ?>
									</a>
								<?php $this->render_badge( 'yes' ); ?>
							</div>
						<?php endif; ?>
						<div class="ezg-ele-post-grid-content-area">
							<?php $this->render_title( 'yes', 'h4' ); ?>
							<?php $this->render_meta( ['author', 'date'], true ); ?>
							<?php $this->render_excerpt( $excerpt_length ); ?>
							<?php $this->render_read_more( 'Continue Reading »', true ); ?>
						</div>
					</div>
					<?php
				endwhile;
				wp_reset_postdata();
			}
			?>
		</div>
		<?php
	}
}
