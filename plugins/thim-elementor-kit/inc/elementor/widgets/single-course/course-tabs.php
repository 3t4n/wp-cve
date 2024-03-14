<?php

namespace Elementor;

use LearnPress\TemplateHooks\Instructor\SingleInstructorTemplate;

class Thim_Ekit_Widget_Course_Tabs extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );

	}

	public function get_name() {
		return 'thim-ekits-course-tabs';
	}

	public function get_title() {
		return esc_html__( ' Course Tabs', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-archive-posts';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY_SINGLE_COURSE );
	}

	public function get_help_url() {
		return '';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Content', 'thim-elementor-kit' ),
			)
		);
		$this->add_control(
			'tab_scroll',
			array(
				'label'   => esc_html__( 'Tab Scroll', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'no',
			)
		);

		$this->add_control(
			'layout',
			[
				'label'     => esc_html__( 'Layout', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'tabs' => esc_html__( 'Tab', 'thim-elementor-kit' ),
					'acc'  => esc_html__( 'Accordion', 'thim-elementor-kit' ),
					'list' => esc_html__( 'Open All', 'thim-elementor-kit' ),
				],
				'default'   => 'tabs',
				'condition' => [
					'tab_scroll!' => 'yes'
				]
			]
		);
		$repeater_header = new \Elementor\Repeater();

		$repeater_header->add_control(
			'tab_key',
			array(
				'label'   => esc_html__( 'Type', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'overview',
				'options' => \Thim_EL_Kit\Elementor::instance()->get_tab_options(),
			)
		);

		$repeater_header->add_control(
			'icon',
			array(
				'label'       => esc_html__( 'Icon', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
			)
		);

		$repeater_header->add_control(
			'text',
			array(
				'label'   => esc_html__( 'Text', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
			)
		);

		$this->_register_custom_layout_instructor( $repeater_header );

		$this->add_control(
			'thim_tab_repeater',
			array(
				'label'       => esc_html__( 'Tabs', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater_header->get_controls(),
				'default'     => array(
					array(
						'tab_key' => 'overview',
					),
					array(
						'tab_key' => 'curriculum',
					),
					array(
						'tab_key' => 'instructor',
					),
					array(
						'tab_key' => 'faqs',
					),
				),
				'title_field' => '<span style="text-transform: capitalize;">{{{ tab_key.replace("_", " ") }}}</span>',
			)
		);

		$this->add_control(
			'active_tab',
			array(
				'label'   => esc_html__( 'Active tab', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'overview',
				'options' => \Thim_EL_Kit\Elementor::instance()->get_tab_options(),
			)
		);

		$this->end_controls_section();
		$this->_register_style_course_tab();
		$this->_register_style_content();
		$this->_register_style_course_curriculum();
		$this->_register_style_course_instructor();
		$this->_register_style_course_faqs();
	}

	protected function _register_custom_layout_instructor( $repeater_header ) {
		if ( version_compare( LEARNPRESS_VERSION, '4.2.3', '>=' ) ) {
			$repeater_header->add_control(
				'instructor_layout',
				[
					'label'     => esc_html__( 'Layout', 'thim-elementor-kit' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => [
						''       => esc_html__( 'Default', 'thim-elementor-kit' ),
						'custom' => esc_html__( 'Custom', 'thim-elementor-kit' ),
					],
					'default'   => '',
					'condition' => [
						'tab_key'     => 'instructor',
						'tab_scroll!' => 'yes'
					]
				]
			);
			$repeater_header->add_control(
				'instructor_layout_html',
				[
					'label'       => esc_html__( 'Layout HTML', 'thim-elementor-kit' ),
					'type'        => Controls_Manager::WYSIWYG,
					'label_block' => true,
					'default'     => '<a href="{{instructor_url}}">{{instructor_display_name}}</a>
										<div class="d-flex align-center gap-30">{{instructor_avatar}}
											<div class="d-flex-column">{{instructor_total_students}}{{instructor_total_courses}}</div>
										</div>
										{{instructor_description}}
										<div class="d-flex align-center gap-15">Follow{{instructor_social}}</div>',
					'description' => 'Sections: {{instructor_avatar}},{{instructor_description}}, {{instructor_url}}, {{instructor_display_name}}, {{instructor_total_courses}}, {{instructor_total_students}}, {{instructor_social}}',
					'condition'   => [
						'instructor_layout' => 'custom',
						'tab_scroll!'       => 'yes'
					]
				]
			);
		}
	}

	protected function _register_style_course_tab() {
		$this->start_controls_section(
			'section_style_course_tab',
			array(
				'label' => esc_html__( 'Item Tab', 'thim-elementor-kit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'course_tab_align',
			array(
				'label'     => esc_html__( 'Alignment', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'right',
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .ekits-course-tabs' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'course_tab_item_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 120,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .ekits-course-tabs .tab-item' => 'margin: 0 0 {{SIZE}}{{UNIT}} 0',
				),
			)
		);

		$this->add_responsive_control(
			'course_tab_item_margin',
			array(
				'label'      => esc_html__( 'Margin', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .ekits-course-tabs .tab-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'course_tab_item_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .ekits-course-tabs .tab-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// start tab for content
		$this->start_controls_tabs(
			'course_style_tabs_item'
		);

		// start normal tab
		$this->start_controls_tab(
			'tab_item_style_normal',
			array(
				'label' => esc_html__( 'Normal', 'thim-elementor-kit' ),
			)
		);
		$this->add_control(
			'tab_item_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .ekits-course-tabs .tab-item' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tab_item_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ekits-course-tabs .tab-item' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'tab_item_border',
				'label'    => esc_html__( 'Border', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .ekits-course-tabs .tab-item',
			)
		);

		$this->end_controls_tab();
		// end normal tab

		// start active tab
		$this->start_controls_tab(
			'tab_item_style_active',
			array(
				'label' => esc_html__( 'Active', 'thim-elementor-kit' ),
			)
		);
		$this->add_control(
			'tab_item_text_color_active',
			array(
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .ekits-course-tabs .tab-item:hover,{{WRAPPER}} .ekits-course-tabs .tab-item[aria-selected="true"]' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tab_item_bg_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ekits-course-tabs .tab-item:hover,{{WRAPPER}} .ekits-course-tabs .tab-item[aria-selected="true"]' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'tab_item_border_active',
				'label'    => esc_html__( 'Border', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .ekits-course-tabs .tab-item:hover,{{WRAPPER}} .ekits-course-tabs .tab-item[aria-selected="true"]',
			)
		);

		$this->end_controls_tab();
		// end hover tab

		$this->end_controls_tabs();

		$this->add_control(
			'tab_item_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ekits-course-tabs .tab-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tab_item_typography',
				'label'    => esc_html__( 'Typography', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .ekits-course-tabs .tab-item',
			)
		);

		$this->end_controls_section();


	}

	protected function _register_style_content() {
		$this->start_controls_section(
			'section_style_course_tab_content',
			array(
				'label'     => esc_html__( 'Content', 'thim-elementor-kit' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'tab_scroll!' => 'yes'
				]
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'course_tab_content_border_active',
				'label'          => esc_html__( 'Border', 'thim-elementor-kit' ),
				'selector'       => '{{WRAPPER}} .ekits-content-course-tabs',
				'fields_options' => array(
					'border' => array(
						'default' => 'solid',
					),
					'width'  => array(
						'default' => array(
							'top'    => '1',
							'right'  => '1',
							'bottom' => '1',
							'left'   => '1',
						),
					),
					'color'  => array(
						'default' => '#f5f5f5',
					),
				),
			)
		);

		$this->add_control(
			'course_tab_content_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .ekits-content-course-tabs' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'course_tab_content_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'default'    => array(
					'top'    => '20',
					'right'  => '20',
					'bottom' => '20',
					'left'   => '20',
				),
				'selectors'  => array(
					'{{WRAPPER}} .ekits-content-course-tabs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'course_tab_content_text_color_active',
			array(
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .ekits-content-course-tabs [role="tabpanel"]' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'course_tab_content_bg_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .ekits-content-course-tabs' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'course_tab_content_typography',
				'label'    => esc_html__( 'Typography', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .ekits-content-course-tabs [role="tabpanel"]',
			)
		);

		$this->end_controls_section();
	}

	protected function _register_style_course_curriculum() {
		$this->start_controls_section(
			'style_curriculum_content',
			array(
				'label'     => esc_html__( 'Curriculum', 'thim-elementor-kit' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'tab_scroll!' => 'yes'
				]
			),
		);

		$this->add_control(
			'curriculum_section_tab',
			array(
				'label'     => esc_html__( 'Section', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'section_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .course-curriculum .course-item' => 'background-color: transparent;',
					'{{WRAPPER}} .course-curriculum .section'     => 'background-color: {{VALUE}}; border-bottom: none!important',
				)
			)
		);
		$this->add_responsive_control(
			'section_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .course-curriculum .section'        => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .course-curriculum .section-header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
				),
			)
		);
		$this->add_responsive_control(
			'section_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 120,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .course-curriculum .section:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);
		$this->add_control(
			'curriculum_heading_section',
			array(
				'label'     => esc_html__( 'Title Section', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Title Typography', 'thim-elementor-kit' ),
				'name'     => 'title_sc_typo',
				'selector' => '{{WRAPPER}} #learn-press-course-curriculum .curriculum-sections .section-title',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Description Typography', 'thim-elementor-kit' ),
				'name'     => 'desc_sc_typo',
				'selector' => '{{WRAPPER}} #learn-press-course-curriculum  .curriculum-sections .section-desc',
			)
		);
		$this->add_responsive_control(
			'sc_title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} #learn-press-course-curriculum .curriculum-sections .section-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .curriculum-sections .section-header'                                => '--section-title-padding-top: {{TOP}}{{UNIT}};--section-title-padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'sc_title_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} #learn-press-course-curriculum .section-header' => 'background-color: {{VALUE}}',
				)
			)
		);
		$this->add_control(
			'sc_title_color',
			array(
				'label'     => esc_html__( 'Color Title', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .course-curriculum .section-header' => 'color: {{VALUE}}',
				)
			)
		);
		$this->add_control(
			'sc_desc_color',
			array(
				'label'     => esc_html__( 'Color Description', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .course-curriculum .section-desc' => 'color: {{VALUE}}',
				)
			)
		);
		$this->add_control(
			'curriculum_heading_lesson',
			array(
				'label'     => esc_html__( 'Lesson', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Title Typography', 'thim-elementor-kit' ),
				'name'     => 'lesson_typo',
				'selector' => '{{WRAPPER}} .course-curriculum .course-item .item-name',
			)
		);
		$this->add_responsive_control(
			'lesson__padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .course-curriculum .course-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .course-curriculum'              => '--thim-ekit-padding-lesson: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);


		$this->add_control(
			'lesson_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .course-curriculum .course-item' => 'border-top: 1px solid {{VALUE}};margin-bottom: 0;',
				)
			)
		);
		$this->add_control(
			'lesson_color',
			array(
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .course-curriculum .course-item .section-item-link' => 'color: {{VALUE}}',
				)
			)
		);
		$this->end_controls_section();

	}

	protected function _register_style_course_faqs() {
		$this->start_controls_section(
			'faqs_settings',
			[
				'label'     => esc_html__( 'FAQs', 'thim-elementor-kit' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'tab_scroll!' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'faqs_space',
			[
				'label'      => __( 'Space', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min'  => - 100,
						'max'  => 200,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .course-faqs-box:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'faqs_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .course-faqs-box'                         => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
					'{{WRAPPER}} .course-faqs-box .course-faqs-box__title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'faqs_border',
				'label'    => esc_html__( 'Border', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .course-faqs-box',
			]
		);
		$this->add_control(
			'faqs_title_heading',
			array(
				'label'     => esc_html__( 'Title', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'faqs_title_typo',
				'selector' => '{{WRAPPER}} .course-faqs-box .course-faqs-box__title',
			]
		);

		$this->add_responsive_control(
			'faqs_icon_size',
			[
				'label'      => __( 'Icon Size', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'size' => 16,
					'unit' => 'px',
				],
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .course-faqs-box .course-faqs-box__title:after' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'faqs_title_padding',
			[
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .course-faqs-box .course-faqs-box__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'faqs_title_margin',
			[
				'label'      => esc_html__( 'Margin', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .course-faqs-box .course-faqs-box__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'faqs_title_border',
				'label'    => esc_html__( 'Border', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .course-faqs-box .course-faqs-box__title',
				'exclude'  => [ 'color' ]
			]
		);

		$this->start_controls_tabs( 'faqs_title_tabs' );
		// Normal State Tab
		$this->start_controls_tab( 'faqs_title_tab_normal', [ 'label' => esc_html__( 'Normal', 'thim-elementor-kit' ) ] );
		$this->add_control(
			'faqs_title_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#f1f1f1',
				'selectors' => [
					'{{WRAPPER}} .course-faqs-box .course-faqs-box__title' => 'background-color: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'faqs_title_color',
			[
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333',
				'selectors' => [
					'{{WRAPPER}} .course-faqs-box .course-faqs-box__title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'faqs_title_icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333',
				'selectors' => [
					'{{WRAPPER}} .course-faqs-box .course-faqs-box__title:after' => 'color: {{VALUE}};',
				]
			]
		);
		$this->add_control(
			'faqs_title_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .course-faqs-box .course-faqs-box__title' => 'border-color: {{VALUE}};',
				],
				'condition' => [ 'faqs_title_border_border!' => '' ],
			]
		);

		$this->end_controls_tab();
		// Hover State Tab
		$this->start_controls_tab( 'faqs_title_tab_hover', [ 'label' => esc_html__( 'Active', 'thim-elementor-kit' ) ] );
		$this->add_control(
			'faqs_title_bg_color_hover',
			[
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333',
				'selectors' => [
					'{{WRAPPER}} .course-faqs-box .course-faqs-box__title:hover,{{WRAPPER}} input[name=course-faqs-box-ratio]:checked+.course-faqs-box .course-faqs-box__title' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'faqs_title_color_hover',
			[
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .course-faqs-box .course-faqs-box__title:hover,{{WRAPPER}} input[name=course-faqs-box-ratio]:checked+.course-faqs-box .course-faqs-box__title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'faqs_title_icon_color_hover',
			[
				'label'     => esc_html__( 'Icon Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .course-faqs-box .course-faqs-box__title:hover:before,{{WRAPPER}} input[name=course-faqs-box-ratio]:checked+.course-faqs-box .course-faqs-box__title:after' => 'color: {{VALUE}};',
				]
			]
		);
		$this->add_control(
			'faqs_title_border_color_hover',
			[
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .course-faqs-box .course-faqs-box__title:hover,{{WRAPPER}} input[name=course-faqs-box-ratio]:checked+.course-faqs-box .course-faqs-box__title' => 'border-color: {{VALUE}};',
				],
				'condition' => [ 'faqs_title_border_border!' => '' ],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->add_control(
			'faqs_content_heading',
			array(
				'label'     => esc_html__( 'Content', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'faqs_content_typography',
				'selector' => '{{WRAPPER}} .course-faqs-box .course-faqs-box__content-inner',
			]
		);
		$this->add_control(
			'faqs_content_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .course-faqs-box .course-faqs-box__content-inner' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'faqs_content_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333',
				'selectors' => [
					'{{WRAPPER}} .course-faqs-box .course-faqs-box__content-inner' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'faqs_content_padding',
			[
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .course-faqs-box .course-faqs-box__content-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'faqs_content_margin',
			[
				'label'      => esc_html__( 'Margin', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .course-faqs-box .course-faqs-box__content-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
	}

	protected function _register_style_course_instructor() {
		$this->start_controls_section(
			'style_instructor',
			array(
				'label'     => esc_html__( 'Instructor', 'thim-elementor-kit' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'tab_scroll!' => 'yes'
				]
			),
		);
		$this->add_control(
			'instructor_name_heading',
			array(
				'label'     => esc_html__( 'Instructor Name', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Typography', 'thim-elementor-kit' ),
				'name'     => 'instructor_name_typo',
				'selector' => '{{WRAPPER}} .lp-course-author .author-title a,{{WRAPPER}} .lp-course-author .instructor-display-name',
			)
		);
		$this->add_control(
			'instructor_name_color',
			array(
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lp-course-author' => '--ekits-instructor-name-color: {{VALUE}}',
				)
			)
		);
		$this->add_control(
			'instructor_name_link_color',
			array(
				'label'     => esc_html__( 'Text Color Hover', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lp-course-author' => '--ekits-instructor-name-link-color: {{VALUE}}',
				)
			)
		);
		$this->add_responsive_control(
			'instructor_name_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .lp-course-author' => '--ekits-instructor-name-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				)
			)
		);

		$this->add_control(
			'instructor_avatar_heading',
			array(
				'label'     => esc_html__( 'Instructor Avatar', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_responsive_control(
			'instructor_avatar_width',
			array(
				'label'     => esc_html__( 'Width', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 500,
					),
					'%'  => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .lp-course-author' => '--ekits-instructor-avatar-width: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'instructor_avatar_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .lp-course-author' => '--ekits-instructor-avatar-border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				)
			)
		);
		$this->add_responsive_control(
			'instructor_avatar_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .lp-course-author' => '--ekits-instructor-avatar-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				)
			)
		);
		$this->add_responsive_control(
			'instructor_avatar_margin',
			array(
				'label'      => esc_html__( 'Margin', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .lp-course-author' => '--ekits-instructor-avatar-margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				)
			)
		);


		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'instructor_avatar_border',
				'selector' => '{{WRAPPER}} .lp-course-author .course-author__pull-left, {{WRAPPER}} .lp-course-author .instructor-avatar',
			]
		);
		$this->add_control(
			'instructor_desc_heading',
			array(
				'label'     => esc_html__( 'Instructor Description', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'label'    => esc_html__( 'Typography', 'thim-elementor-kit' ),
				'name'     => 'instructor_desc_typo',
				'selector' => '{{WRAPPER}} .lp-course-author [class*="-description"]',
			)
		);
		$this->add_control(
			'instructor_desc_color',
			array(
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lp-course-author [class*="-description"]' => 'color: {{VALUE}}',
				)
			)
		);
		$this->add_responsive_control(
			'instructor_desc_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .lp-course-author [class*="-description"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'instructor_social_heading',
			array(
				'label'     => esc_html__( 'Instructor Social', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_responsive_control(
			'icon_social_spacing',
			array(
				'label'     => esc_html__( 'Space', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 120,
					)
				),
				'selectors' => array(
					'{{WRAPPER}} .lp-course-author [class*="-social"] a:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'icon_social_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 5,
					),
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .lp-course-author [class*="-social"] a i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .lp-course-author [class*="-social"] a svg' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'icon_social_width_height',
			array(
				'label'     => esc_html__( 'Width/Height icon', 'thim-elementor-kit' ),
				'default'   => '',
				'type'      => Controls_Manager::NUMBER,
				'selectors' => array(
					'{{WRAPPER}} .lp-course-author [class*="-social"] a' => 'width: {{VALUE}}px; height: {{VALUE}}px;line-height: {{VALUE}}px;',
				),
			)
		);

		$this->add_responsive_control(
			'icon_social_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .lp-course-author [class*="-social"] a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'social_icon_border',
				'label'    => esc_html__( 'Border', 'thim-elementor-kit' ),
				'selector' => '{{WRAPPER}} .lp-course-author [class*="-social"] a',
				'exclude'  => [ 'color' ]
			)
		);

		$this->start_controls_tabs(
			'social_icon_tabs'
		);
		$this->start_controls_tab(
			'social_icon_normal',
			array(
				'label' => esc_html__( 'Normal', 'thim-elementor-kit' ),
			)
		);
		$this->add_control(
			'icon_social_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lp-course-author [class*="-social"] a'          => 'color: {{VALUE}}',
					'{{WRAPPER}} .lp-course-author [class*="-social"] a svg path' => 'stroke: {{VALUE}}; fill: {{VALUE}};',
				)
			)
		);
		$this->add_control(
			'icon_social_bg_color',
			array(
				'label'     => esc_html__( 'Background Icon Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lp-course-author [class*="-social"] a' => 'background-color: {{VALUE}}',
				)
			)
		);
		$this->add_control(
			'icon_social_border_color',
			array(
				'label'     => esc_html__( 'Border Icon Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lp-course-author [class*="-social"] a' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'social_icon_border_border!' => 'none',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'social_icon_hover',
			array(
				'label' => esc_html__( 'Hover', 'thim-elementor-kit' ),
			)
		);
		$this->add_control(
			'icon_social_color_hover',
			array(
				'label'     => esc_html__( 'Icon Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lp-course-author [class*="-social"] a:hover'          => 'color: {{VALUE}}',
					'{{WRAPPER}} .lp-course-author [class*="-social"] a:hover svg path' => 'stroke: {{VALUE}}; fill: {{VALUE}};',
				)
			)
		);
		$this->add_control(
			'icon_social_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Icon Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lp-course-author [class*="-social"] a:hover' => 'background-color: {{VALUE}}',
				)
			)
		);
		$this->add_control(
			'icon_social_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Icon Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lp-course-author [class*="-social"] a:hover' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'social_icon_border_border!' => 'none',
				),
			)
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	public function render() {
		$settings = $this->get_settings_for_display();
		do_action( 'thim-ekit/modules/single-course/before-preview-query' );
		$course = learn_press_get_course();

		if ( ! $course ) {
			return;
		}

		$tab_default = learn_press_get_course_tabs();

		if ( $settings['tab_scroll'] == 'yes' ) {
			$this->render_tab_scroll( $settings, $tab_default );
		} else {
			$this->render_content_tab( $settings, $tab_default );

			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				?>
				<script>
					document.body.dispatchEvent(new CustomEvent("thimEkitsEditor:init"));
					LP.Hook.doAction('lp_course_curriculum_skeleton', <?php echo absint( $course->get_id() ); ?> );
				</script>
				<?php
			}
		}


		do_action( 'thim-ekit/modules/single-course/after-preview-query' );
	}

	public function render_tab_scroll( $settings, $tab_default ) {
		$tabs = array();

		foreach ( $settings['thim_tab_repeater'] as $item ) {
			if ( ! isset( $tab_default[$item['tab_key']] ) ) {
				continue;
			}

			$tabs[$item['tab_key']] = array(
				'title'       => ! empty( $item['text'] ) ? esc_html( $item['text'] ) : $tab_default[$item['tab_key']]['title'],
				'icon'        => ! empty( $item['icon'] ) ? $item['icon'] : ''
			);
		}

		if ( empty( $tabs ) ) {
			return;
		}

		?>
		<div class="thim-ekit-single-course__tabs thim-ekit-tablist">
			<div class="ekits-course-tabs">
				<?php foreach ( $tabs as $key => $tab ) : ?>
					<a class="tab-item" href="<?php echo esc_attr( '#panel-' . $key ); ?>" aria-label="<?php echo esc_attr( $key ); ?>">
					<span class="ekits-course-tabs__icon"><?php Icons_Manager::render_icon( $tab['icon'] ); ?></span>
					<?php echo esc_html( $tab['title'] ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	public function render_content_tab( $settings, $tab_default ) {
		// remove course info in theme eduma
		remove_action( 'thim_course_info_right', 'thim_course_info', 5 );
		remove_action( 'thim_sidebar_menu_info_course', 'thim_course_info', 5 );

		$tabs = array();

		foreach ( $settings['thim_tab_repeater'] as $item ) {
			if ( ! isset( $tab_default[$item['tab_key']] ) ) {
				continue;
			}
			$custom_html = '';
			if ( $item['tab_key'] == 'instructor' && $item['instructor_layout'] == 'custom' && $item['instructor_layout_html'] ) {
				$custom_html = $item['instructor_layout_html'];
			}
			$tabs[$item['tab_key']] = array(
				'title'       => ! empty( $item['text'] ) ? esc_html( $item['text'] ) : $tab_default[$item['tab_key']]['title'],
				'icon'        => ! empty( $item['icon'] ) ? $item['icon'] : '',
				'callback'    => $tab_default[$item['tab_key']]['callback'],
				'custom_html' => $custom_html
			);
		}

		if ( empty( $tabs ) ) {
			return;
		}

		// Fix class not found.
		if ( ! class_exists( 'LP_Model_User_Can_View_Course_Item' ) ) {
			require_once LP_PLUGIN_PATH . 'inc/course/class-model-user-can-view-course-item.php';
		}

		$tab_keys   = array_keys( $tabs );
		$active_tab = ! empty( $settings['active_tab'] ) ? $settings['active_tab'] : reset( $tab_keys );
		$role       = $hide = '';
		$class      = $settings['layout'];
		?>

		<div class="thim-ekit-single-course__tabs thim-ekit-tablist">
		<?php if ( $settings['layout'] == 'tabs' ) { ?>
			<div class="ekits-course-tabs" role="tablist"
				 aria-label="<?php echo esc_attr_e( 'Course Tabs', 'thim-elementor-kit' ); ?>">
				<?php foreach ( $tabs as $key => $tab ) : ?>
					<div class="tab-item" role="tab"
						 aria-selected="<?php echo esc_attr( $active_tab === $key ? 'true' : 'false' ); ?>"
						 aria-controls="<?php echo esc_attr( 'panel-' . $key ); ?>"
						 id="<?php echo esc_attr( 'tab-' . $key ); ?>"
						 tabindex="<?php echo esc_attr( $active_tab === $key ? 0 : - 1 ); ?>">
							<span
								class="ekits-course-tabs__icon"><?php Icons_Manager::render_icon( $tab['icon'] ); ?></span>
						<?php echo esc_html( $tab['title'] ); ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php } elseif ( $settings['layout'] == 'acc' ) {
			$role  = ' role="tablist"';
			$class = $settings['layout'] . ' thim-accordion-sections';
		} ?>

		<div class="ekits-content-course-<?php echo esc_attr( $class ); ?>"<?php echo $role; ?>>
			<?php foreach ( $tabs

			as $key => $tab ) : ?>
			<?php
			$this->add_render_attribute( 'content_tab_' . $key, [
					'id'    => 'panel-' . $key,
					'class' => 'course-tab-panel-' . $key,
				]
			);
			if ( $settings['layout'] != 'list' ) {
				$this->add_render_attribute( 'content_tab_' . $key, [
					'aria-labelledby' => 'tab-' . $key,
					'role'            => 'tabpanel',
					'tabindex'        => $active_tab === $key ? 0 : - 1
				] );

				$hide = $active_tab !== $key ? 'hidden' : '';

				$this->add_render_attribute( 'tab_' . $key, [
					'aria-selected' => $active_tab === $key ? 'true' : 'false',
					'role'          => 'tab',
					'aria-controls' => 'panel-' . $key,
					'tabindex'      => $active_tab === $key ? 0 : - 1
				] );
			}

			$this->add_render_attribute( 'tab_' . $key, [ 'class' => 'tab-item' ] );
			?>

			<?php if ( $settings['layout'] != 'tabs' ) { ?>

			<?php $this->add_render_attribute( 'content_tab_' . $key, [ 'class' => 'ekits-content-course-tabs' ] ); ?>

			<div class="<?php echo esc_attr( $settings['layout'] ); ?>-section ekits-course-tabs">
				<div <?php $this->print_render_attribute_string( 'tab_' . $key ); ?> >
								<span class="ekits-course-tabs__icon">
									<?php Icons_Manager::render_icon( $tab['icon'] ); ?>
								</span>
					<?php echo esc_html( $tab['title'] ); ?>
				</div>
				<?php } ?>

				<div <?php $this->print_render_attribute_string( 'content_tab_' . $key ); ?> <?php echo esc_attr( $hide ); ?>>
					<?php

					if ( is_callable( $tab['callback'] ) ) {
						if ( $key == 'instructor' && $tab['custom_html'] ) {
							$this->render_course_instructor( $tab['custom_html'] );
						} else {
							call_user_func( $tab['callback'], $key, $tab );
						}
					} else {
						do_action( 'learn-press/course-tab-content', $key, $tab );
					}
					?>
				</div>

				<?php if ( $settings['layout'] != 'tabs' ) {
					echo '</div>';
				} ?>
				<?php endforeach; ?>
			</div>
		</div>

		<?php
	}

	public function render_course_instructor( $layout_html ) {
		try {
			$course = learn_press_get_course();
			if ( ! $course ) {
				return;
			}
			$instructor = $course->get_instructor();
			// Show list instructors
			$singleInstructorTemplate = SingleInstructorTemplate::instance();
			echo '<div class="lp-course-author">' . $singleInstructorTemplate->render_data( $instructor, html_entity_decode( $layout_html ) ) . '</div>';
			// End show list instructors
		}
		catch ( \Throwable $e ) {
			echo $e->getMessage();
		}
	}
}
