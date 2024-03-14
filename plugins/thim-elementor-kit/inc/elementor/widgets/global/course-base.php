<?php

namespace Elementor;

use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use LearnPress;
use LearnPress\TemplateHooks\Course\SingleCourseTemplate;
use Thim_EL_Kit\Elementor\Controls\Controls_Manager as Thim_Control_Manager;
use LP_Course;

abstract class Thim_Ekits_Course_Base extends Widget_Base {

	protected function register_controls() {
		$this->_register_content();
		$this->_register_style_layout();
		$this->_register_style_course();
		$this->_register_image_control();
		$this->_register_style_content();
		$this->_register_style_meta_data();
	}

	protected function _register_control_repeater( $repeater ) {
		$repeater->add_control(
			'key',
			array(
				'label'   => esc_html__( 'Type', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'title',
				'options' => array(
					'title'     => esc_html__( 'Title', 'thim-elementor-kit' ),
					'price'     => esc_html__( 'Price', 'thim-elementor-kit' ),
					'meta_data' => esc_html__( 'Meta Data', 'thim-elementor-kit' ),
					'content'   => esc_html__( 'Content', 'thim-elementor-kit' ),
					'read_more' => esc_html__( 'Read more', 'thim-elementor-kit' ),
				),
			)
		);

		$repeater->add_control(
			'title_tag',
			array(
				'label'     => __( 'Title HTML Tag', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				),
				'default'   => 'h3',
				'condition' => array(
					'key' => 'title',
				),
			)
		);

		$repeater->add_control(
			'meta_data',
			array(
				'label'       => esc_html__( 'Meta Data', 'thim-elementor-kit' ),
				'label_block' => true,
				'type'        => Thim_Control_Manager::SELECT2,
				'default'     => array( 'duration', 'level' ),
				'multiple'    => true,
				'sortable'    => true,
				'options'     => \Thim_EL_Kit\Elementor::register_options_courses_meta_data(),
				'condition'   => array(
					'key' => 'meta_data',
				),
			)
		);

		$repeater->add_control(
			'meta_data_display',
			array(
				'label'       => esc_html__( 'Display Item', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => 'start',
				'options'     => array(
					'flex-start'    => array(
						'title' => esc_html__( 'Start', 'thim-elementor-kit' ),
						'icon'  => 'eicon-justify-start-h',
					),
					'space-around'  => array(
						'title' => esc_html__( 'Center', 'thim-elementor-kit' ),
						'icon'  => 'eicon-justify-space-around-h',
					),
					'space-between' => array(
						'title' => esc_html__( 'Spaced between items', 'thim-elementor-kit' ),
						'icon'  => 'eicon-justify-space-evenly-h',
					),
					'flex-end'      => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-justify-end-h',
					),
					'start_end'     => array(
						'title' => esc_html__( 'Left, 1 Item Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-justify-space-between-h',
					),
				),
				'condition'   => array(
					'key' => 'meta_data',
				),
				'render_type' => 'ui',
				'selectors'   => array(
					'{{WRAPPER}}  .thim-ekits-course__item {{CURRENT_ITEM}}' => '--thim-item-meta-data-display: {{VALUE}};',
				),
			)
		);

		$repeater->add_control(
			'show_icon_meta_data',
			array(
				'label'     => esc_html__( 'Icon Meta Data', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'thim-elementor-kit' ),
				'label_off' => esc_html__( 'No', 'thim-elementor-kit' ),
				'default'   => 'no',
				'condition' => array(
					'key' => 'meta_data',
				),
			)
		);

		$repeater->add_control(
			'separator',
			array(
				'label'     => esc_html__( 'Separator Between', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '|',
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} > span + span:before' => 'content: "{{VALUE}}"',
				),
				'condition' => array(
					'key'                  => 'meta_data',
					'show_icon_meta_data!' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'label_meta_data',
			array(
				'label'     => esc_html__( 'Hide Label', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'thim-elementor-kit' ),
				'label_off' => esc_html__( 'No', 'thim-elementor-kit' ),
				'default'   => 'no',
				'condition' => array(
					'key'                 => 'meta_data',
					'show_icon_meta_data' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'duration_icon_meta_data',
			array(
				'label'       => esc_html__( 'Duration Icon', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'separator'   => 'before',
				'condition'   => array(
					'key'                 => 'meta_data',
					'meta_data'           => 'duration',
					'show_icon_meta_data' => 'yes',
				),
			)
		);
		$repeater->add_control(
			'level_icon_meta_data',
			array(
				'label'       => esc_html__( 'Level Icon', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'separator'   => 'before',
				'condition'   => array(
					'key'                 => 'meta_data',
					'meta_data'           => 'level',
					'show_icon_meta_data' => 'yes',
				),
			)
		);
		$repeater->add_control(
			'count_lesson_icon_meta_data',
			array(
				'label'       => esc_html__( 'Count Lesson', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'separator'   => 'before',
				'condition'   => array(
					'key'                 => 'meta_data',
					'meta_data'           => 'count_lesson',
					'show_icon_meta_data' => 'yes',
				),
			)
		);
		$repeater->add_control(
			'count_quiz_icon_meta_data',
			array(
				'label'       => esc_html__( 'Count Quiz', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'separator'   => 'before',
				'condition'   => array(
					'key'                 => 'meta_data',
					'meta_data'           => 'count_quiz',
					'show_icon_meta_data' => 'yes',
				),
			)
		);
		$repeater->add_control(
			'count_student_icon_meta_data',
			array(
				'label'       => esc_html__( 'Count Student', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'separator'   => 'before',
				'condition'   => array(
					'key'                 => 'meta_data',
					'meta_data'           => 'count_student',
					'show_icon_meta_data' => 'yes',
				),
			)
		);
		$repeater->add_control(
			'category_icon_meta_data',
			array(
				'label'       => esc_html__( 'Category', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'separator'   => 'before',
				'label_block' => false,
				'condition'   => array(
					'key'                 => 'meta_data',
					'meta_data'           => 'category',
					'show_icon_meta_data' => 'yes',
				),
			)
		);
		$repeater->add_control(
			'tag_icon_meta_data',
			array(
				'label'       => esc_html__( 'Tag', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'separator'   => 'before',
				'label_block' => false,
				'condition'   => array(
					'key'                 => 'meta_data',
					'meta_data'           => 'tag',
					'show_icon_meta_data' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'meta_data-toggle',
			array(
				'type'         => \Elementor\Controls_Manager::POPOVER_TOGGLE,
				'label'        => esc_html__( 'Options Meta data', 'thim-elementor-kit' ),
				'label_off'    => esc_html__( 'Default', 'thim-elementor-kit' ),
				'label_on'     => esc_html__( 'Custom', 'thim-elementor-kit' ),
				'return_value' => 'yes',
				'condition'    => array(
					'key' => 'meta_data',
				),
			)
		);

		$repeater->start_popover();

		$repeater->add_control(
			'meta_data_item_spacing',
			array(
				'label'       => esc_html__( 'Item Spacing', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => 0,
				'step'        => 1,
				'default'     => 7,
				'condition'   => array(
					'key'              => 'meta_data',
					'meta_data-toggle' => 'yes',
				),
				'selectors'   => array(
					'{{WRAPPER}}  .thim-ekits-course__item {{CURRENT_ITEM}}' => '--thim-item-meta-data-spacing: {{VALUE}}px;',
				),
			)
		);

		$repeater->add_control(
			'meta_data_item_custom_font_size',
			array(
				'label'       => esc_html__( 'Font Size', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => 0,
				'step'        => 1,
				'condition'   => array(
					'key'              => 'meta_data',
					'meta_data-toggle' => 'yes',
				),
				'selectors'   => array(
					'{{WRAPPER}}  .thim-ekits-course__item .thim-ekits-course__meta{{CURRENT_ITEM}}' => 'font-size: {{VALUE}}px;',
				),
			)
		);

		$repeater->add_responsive_control(
			'meta_data_border',
			array(
				'label'   => esc_html__( 'Border Top Type', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'none'   => esc_html__( 'None', 'thim-elementor-kit' ),
					'solid'  => esc_html_x( 'Solid', 'Border Control', 'thim-elementor-kit' ),
					'double' => esc_html_x( 'Double', 'Border Control', 'thim-elementor-kit' ),
					'dotted' => esc_html_x( 'Dotted', 'Border Control', 'thim-elementor-kit' ),
					'dashed' => esc_html_x( 'Dashed', 'Border Control', 'thim-elementor-kit' ),
					'groove' => esc_html_x( 'Groove', 'Border Control', 'thim-elementor-kit' ),
				),

				'condition' => array(
					'key'              => 'meta_data',
					'meta_data-toggle' => 'yes',
				),
				'default'   => 'none',
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item {{CURRENT_ITEM}}' => 'border-top-style: {{VALUE}};',
				),
			)
		);

		$repeater->add_responsive_control(
			'meta_data_border_dimensions',
			array(
				'label'       => esc_html_x( 'Width', 'Border Control', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => 0,
				'step'        => 1,
				'condition'   => array(
					'key'               => 'meta_data',
					'meta_data_border!' => 'none',
				),
				'selectors'   => array(
					'{{WRAPPER}} .thim-ekits-course__item {{CURRENT_ITEM}}' => 'border-top-width: {{VALUE}}px;',
				),
			)
		);
		$repeater->add_control(
			'meta_data_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'key'               => 'meta_data',
					'meta_data_border!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item {{CURRENT_ITEM}}' => 'border-top-color: {{VALUE}};',
				),
			)
		);
		$repeater->add_control(
			'meta_data_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color Hover', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => array(
					'key'               => 'meta_data',
					'meta_data_border!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item:hover {{CURRENT_ITEM}}' => 'border-top-color: {{VALUE}};',
				),
			)
		);
		$repeater->add_control(
			'meta_data_padding_top',
			array(
				'label'       => esc_html__( 'Padding Top', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => 0,
				'step'        => 1,
				'selectors'   => array(
					'{{WRAPPER}} .thim-ekits-course__item {{CURRENT_ITEM}}' => 'padding-top: {{VALUE}}px;',
				),
				'condition'   => array(
					'key'               => 'meta_data',
					'meta_data_border!' => 'none',
				),
			)
		);
		$repeater->end_popover();

		$repeater->add_control(
			'excerpt_lenght',
			array(
				'label'     => esc_html__( 'Excerpt Lenght', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 25,
				'condition' => array(
					'key' => 'content',
				),
			)
		);

		$repeater->add_control(
			'excerpt_more',
			array(
				'label'     => esc_html__( 'Excerpt More', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '...',
				'condition' => array(
					'key' => 'content',
				),
			)
		);

		$repeater->add_control(
			'read_more_text',
			array(
				'label'     => esc_html__( 'Read More Text', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Read More', 'thim-elementor-kit' ),
				'condition' => array(
					'key' => 'read_more',
				),
			)
		);

		$repeater->add_control(
			'read_more_icon',
			array(
				'label'       => esc_html__( 'Read More Icon', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'condition'   => array(
					'key' => 'read_more',
				),
			)
		);

	}

	protected function _register_content() {
		$this->start_controls_section(
			'section_content',
			array(
				'label'     => esc_html__( 'Content', 'thim-elementor-kit' ),
				'condition' => array(
					'build_loop_item!' => 'yes',
				)
			)
		);

		$this->add_control(
			'show_image',
			array(
				'label'     => esc_html__( 'Show Image', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'image_size',
				'default'   => 'medium',
				'condition' => array(
					'show_image' => 'yes',
				),
			)
		);

		$this->add_control(
			'meta_data_inner_image',
			array(
				'label'       => esc_html__( 'Meta Overlay', 'thim-elementor-kit' ),
				'label_block' => true,
				'type'        => Thim_Control_Manager::SELECT2,
				'default'     => array( 'read_more' ),
				'multiple'    => true,
				'options'     => array(
					'instructor' => esc_html__( 'Instructor', 'thim-elementor-kit' ),
					'category'   => esc_html__( 'Category', 'thim-elementor-kit' ),
					'price'      => esc_html__( 'Price', 'thim-elementor-kit' ),
					'read_more'  => esc_html__( 'Read more', 'thim-elementor-kit' ),
				),
				'condition'   => array(
					'show_image' => 'yes',
				),
			)
		);

		$this->add_control(
			'read_more_text_inner_image',
			array(
				'label'     => esc_html__( 'Read More Text', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Read More', 'thim-elementor-kit' ),
				'condition' => array(
					'meta_data_inner_image' => 'read_more',
					'show_image'            => 'yes',
				),
			)
		);

		$this->add_control(
			'read_more_icon_inner_image',
			array(
				'label'       => esc_html__( 'Read More Icon', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'condition'   => array(
					'meta_data_inner_image' => 'read_more',
					'show_image'            => 'yes',
				),
			)
		);
		$repeater = new \Elementor\Repeater();

		$this->_register_control_repeater( $repeater );
		$this->add_control(
			'repeater',
			array(
				'label'       => esc_html__( 'Post Data', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'key' => 'title',
					),
					array(
						'key' => 'price',
					),
					array(
						'key' => 'meta_data',
					),
					array(
						'key' => 'content',
					),
					array(
						'key' => 'read_more',
					),
				),
				'separator'   => 'before',
				'title_field' => '<span style="text-transform: capitalize;">{{{ key.replace("_", " ") }}}</span>',
			)
		);

		$this->add_control(
			'open_new_tab',
			array(
				'label'     => esc_html__( 'Open in new window', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'thim-elementor-kit' ),
				'label_off' => esc_html__( 'No', 'thim-elementor-kit' ),
				'default'   => 'no',
			)
		);

		$this->end_controls_section();
	}

	protected function _register_style_layout() {
		$this->start_controls_section(
			'section_design_layout',
			array(
				'label'     => esc_html__( 'Layout', 'thim-elementor-kit' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'course_skin!' => 'slider',
				),
			)
		);

		$this->add_responsive_control(
			'column_gap',
			array(
				'label'              => esc_html__( 'Columns Gap', 'thim-elementor-kit' ),
				'type'               => Controls_Manager::SLIDER,
				'default'            => array(
					'size' => 30,
				),
				'range'              => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'frontend_available' => true,
				'selectors'          => array(
					'{{WRAPPER}}' => '--thim-ekits-course-column-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'row_gap',
			array(
				'label'     => esc_html__( 'Rows Gap', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 35,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--thim-ekits-course-row-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function _register_style_course() {
		$this->start_controls_section(
			'section_style_course',
			array(
				'label'     => esc_html__( 'Course', 'thim-elementor-kit' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'build_loop_item!' => 'yes',
				)
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'course_border',
				'selector' => '{{WRAPPER}} .thim-ekits-course__item',
				'exclude'  => array( 'color' ),
			)
		);

		$this->start_controls_tabs( 'course_style_tabs' );

		$this->start_controls_tab(
			'course_style_normal',
			array(
				'label' => esc_html__( 'Normal', 'thim-elementor-kit' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'course_shadow',
				'selector' => '{{WRAPPER}} .thim-ekits-course__item',
			)
		);

		$this->add_control(
			'course_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'course_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'course_style_hover',
			array(
				'label' => esc_html__( 'Hover', 'thim-elementor-kit' ),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'course_shadow_hover',
				'selector' => '{{WRAPPER}} .thim-ekits-course__item:hover',
			)
		);

		$this->add_control(
			'course_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'course_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item:hover' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->add_control(
			'course_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-course__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->end_controls_section();
	}

	protected function _register_image_control() {
		$this->start_controls_section(
			'section_image_style',
			array(
				'label'     => esc_html__( 'Image', 'thim-elementor-kit' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'build_loop_item!' => 'yes',
				)
			)
		);

		$this->add_control(
			'image_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__thumbnail' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
				'default'   => array(
					'size' => 20,
				),
			)
		);

		$this->add_control(
			'img_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__thumbnail .course-thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_image_overlay_style',
			array(
				'label'     => esc_html__( 'OverLay', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'bg_image_overlay',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--thim-bg-image-overlay-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'text_color_overlay',
			array(
				'label'     => esc_html__( 'Icon Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__thumbnail a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs(
			'offset_setting_overlay'
		);

		$this->start_controls_tab(
			'price_offset_style',
			array(
				'label' => esc_html__( 'Price', 'thim-elementor-kit' ),
			)
		);
		$this->add_control(
			'price_offset_orientation_h',
			array(
				'label'       => esc_html__( 'Horizontal Orientation', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'default'     => 'right',
				'options'     => array(
					'left'  => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'render_type' => 'ui',
			)
		);
		$this->add_responsive_control(
			'price_indicator_offset_h',
			array(
				'label'       => esc_html__( 'Offset', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => - 100,
				'step'        => 1,
				'default'     => 10,
				'selectors'   => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__thumbnail .thim-ekits-course__price' => '{{price_offset_orientation_h.VALUE}}:{{VALUE}}px',
				),
			)
		);

		$this->add_control(
			'price_offset_orientation_v',
			array(
				'label'       => esc_html__( 'Vertical Orientation', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'default'     => 'top',
				'options'     => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'thim-elementor-kit' ),
						'icon'  => 'eicon-v-align-top',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'thim-elementor-kit' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'render_type' => 'ui',
			)
		);
		$this->add_responsive_control(
			'price_indicator_offset_v',
			array(
				'label'       => esc_html__( 'Offset', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => - 100,
				'step'        => 1,
				'default'     => 10,
				'selectors'   => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__thumbnail .thim-ekits-course__price' => '{{price_offset_orientation_v.VALUE}}:{{VALUE}}px',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'instructor_offset_style',
			array(
				'label' => esc_html__( 'Instructor', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'instructor_offset_orientation_h',
			array(
				'label'       => esc_html__( 'Horizontal Orientation', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'default'     => 'right',
				'options'     => array(
					'left'  => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'render_type' => 'ui',
			)
		);
		$this->add_responsive_control(
			'instructor_indicator_offset_h',
			array(
				'label'       => esc_html__( 'Offset', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => - 100,
				'step'        => 1,
				'default'     => 10,
				'selectors'   => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__thumbnail .thim-ekits-course__instructor' => '{{instructor_offset_orientation_h.VALUE}}:{{VALUE}}px',
				),
			)
		);

		$this->add_control(
			'instructor_offset_orientation_v',
			array(
				'label'       => esc_html__( 'Vertical Orientation', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'default'     => 'bottom',
				'options'     => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'thim-elementor-kit' ),
						'icon'  => 'eicon-v-align-top',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'thim-elementor-kit' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'render_type' => 'ui',
			)
		);
		$this->add_responsive_control(
			'instructor_indicator_offset_v',
			array(
				'label'       => esc_html__( 'Offset', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => - 100,
				'step'        => 1,
				'default'     => 10,
				'selectors'   => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__thumbnail .thim-ekits-course__instructor' => '{{instructor_offset_orientation_v.VALUE}}:{{VALUE}}px',
				),
			)
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'category_offset_style',
			array(
				'label' => esc_html__( 'Category', 'thim-elementor-kit' ),
			)
		);
		$this->add_control(
			'category_offset_orientation_h',
			array(
				'label'       => esc_html__( 'Horizontal Orientation', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'default'     => 'left',
				'options'     => array(
					'left'  => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'render_type' => 'ui',
			)
		);
		$this->add_responsive_control(
			'category_indicator_offset_h',
			array(
				'label'       => esc_html__( 'Offset', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => - 100,
				'step'        => 1,
				'default'     => 10,
				'selectors'   => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__thumbnail .thim-ekits-course__categories' => '{{category_offset_orientation_h.VALUE}}:{{VALUE}}px',
				),
			)
		);

		$this->add_control(
			'category_offset_orientation_v',
			array(
				'label'       => esc_html__( 'Vertical Orientation', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::CHOOSE,
				'toggle'      => false,
				'default'     => 'bottom',
				'options'     => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'thim-elementor-kit' ),
						'icon'  => 'eicon-v-align-top',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'thim-elementor-kit' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'render_type' => 'ui',
			)
		);
		$this->add_responsive_control(
			'category_indicator_offset_v',
			array(
				'label'       => esc_html__( 'Offset', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => - 100,
				'step'        => 1,
				'default'     => 10,
				'selectors'   => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__thumbnail .thim-ekits-course__categories' => '{{category_offset_orientation_v.VALUE}}:{{VALUE}}px',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function _register_style_content() {
		$this->start_controls_section(
			'section_style_content',
			array(
				'label'     => esc_html__( 'Content', 'thim-elementor-kit' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'build_loop_item!' => 'yes',
				)
			)
		);
		$this->add_responsive_control(
			'content_align',
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
				'default'   => 'left',
				'toggle'    => true,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__content' => 'text-align: {{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			'content_course_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}}' => '--thim-ekits-course-content-padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'content_course_margin',
			array(
				'label'      => esc_html__( 'Margin', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_title_style',
			array(
				'label'     => esc_html__( 'Title', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__title a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_color_hover',
			array(
				'label'     => esc_html__( 'Color Hover', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__title a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__title',
			)
		);

		$this->add_control(
			'title_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'title_max_line',
			array(
				'label'       => esc_html__( 'Max Line', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => 0,
				'step'        => 1,
				'selectors'   => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__title' => 'display: -webkit-box; text-overflow: ellipsis; -webkit-line-clamp: {{VALUE}};-webkit-box-orient:vertical; overflow: hidden;',
				),
			)
		);

		$this->add_responsive_control(
			'title_min_height',
			array(
				'label'       => esc_html__( 'Min Height (px)', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => 0,
				'step'        => 1,
				'selectors'   => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__title' => 'min-height: {{VALUE}}px;',
				),
			)
		);

		$this->_register_style_price();

		$this->add_control(
			'heading_excerpt_style',
			array(
				'label'     => esc_html__( 'Excerpt', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'excerpt_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__excerpt' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__excerpt',
			)
		);
		$this->add_responsive_control(
			'excerpt_max_line',
			array(
				'label'       => esc_html__( 'Max Line', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => 0,
				'step'        => 1,
				'selectors'   => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__excerpt' => 'display: -webkit-box; text-overflow: ellipsis; -webkit-line-clamp: {{VALUE}};-webkit-box-orient:vertical; overflow: hidden;',
				),
			)
		);
		$this->add_control(
			'excerpt_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__excerpt' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->_register_style_read_more();

		$this->end_controls_section();

	}

	protected function _register_style_meta_data() {
		$this->start_controls_section(
			'section_meta_data_style_content',
			array(
				'label'     => esc_html__( 'Meta Data', 'thim-elementor-kit' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'build_loop_item!' => 'yes',
				)
			)
		);

		$this->add_control(
			'meta_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--thim-meta-data-item-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'meta_link_color',
			array(
				'label'     => esc_html__( 'Link Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}}' => '--thim-meta-data-item-link-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'meta_typography',
				'selector' => '{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__meta',
			)
		);
		$this->add_control(
			'meta_data_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--thim-meta-data-margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_meta_separator_meta_style',
			array(
				'label'     => esc_html__( 'Separator, Icon', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'meta_separator_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__meta span:before, {{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__meta i' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'meta_icon_spacing',
			array(
				'label'       => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => 0,
				'step'        => 1,
				'default'     => 7,
				'selectors'   => array(
					'{{WRAPPER}}' => '--thim-meta-icon-spacing: {{VALUE}}px;',
				),
			)
		);
		$this->add_responsive_control(
			'meta_icon_font_size',
			array(
				'label'       => esc_html__( 'Size', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => 0,
				'step'        => 1,
				'default'     => 16,
				'selectors'   => array(
					'{{WRAPPER}}' => '--thim-meta-icon-font-size: {{VALUE}}px;',
				),
			)
		);
		$this->add_control(
			'heading_meta_instructor_style',
			array(
				'label'     => esc_html__( 'Instructor', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'meta_instructor_typography',
				'selector' => '{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__instructor .thim-ekits-course__instructor__content',
			)
		);
		$this->add_control(
			'instructor_avatar',
			array(
				'label'        => esc_html__( 'Avatar Instructor', 'thim-elementor-kit' ),
				'type'         => Controls_Manager::CHOOSE,
				'default'      => 'top',
				'options'      => array(
					'none'  => array(
						'title' => esc_html__( 'none', 'thim-elementor-kit' ),
						'icon'  => 'eicon-ban',
					),
					'top'   => array(
						'title' => esc_html__( 'Top', 'thim-elementor-kit' ),
						'icon'  => 'eicon-v-align-top',
					),
					'left'  => array(
						'title' => esc_html__( 'Left', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'thim-elementor-kit' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'render_type'  => 'ui',
				'prefix_class' => 'thim-ekits-avatar-position-',
			)
		);
		$this->add_responsive_control(
			'instructor_avatar_size',
			array(
				'label'       => esc_html__( 'Avatar Size', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => 0,
				'step'        => 1,
				'default'     => 20,
				'condition'   => array(
					'instructor_avatar!' => 'none',
				),
				'selectors'   => array(
					'{{WRAPPER}}' => '--thim-instructor-avatar-size: {{VALUE}}px;',
				),
			)
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'instructor_avatar_border',
				'selector' => '{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__instructor__avatar',
			)
		);

		$this->end_controls_section();

	}

	protected function _register_style_price() {
		$this->add_control(
			'heading_price_style',
			array(
				'label'     => esc_html__( 'Price', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__price',
			)
		);
		$this->start_controls_tabs(
			'price_colors'
		);

		$this->start_controls_tab(
			'price_color_style',
			array(
				'label' => esc_html__( 'Regular', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__price .inner_price' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'bg_price_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__price .inner_price' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'price_free_color_style',
			array(
				'label' => esc_html__( 'Free', 'thim-elementor-kit' ),
			)
		);
		$this->add_control(
			'price_free_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__price .inner_price__free span' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'bg_price_free_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__price .inner_price__free' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'price_has_sale_color_style',
			array(
				'label' => esc_html__( 'Sale', 'thim-elementor-kit' ),
			)
		);
		$this->add_control(
			'price_has_sale_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__price .inner_price__has_sale span' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'bg_price_has_sale_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__price .inner_price__has_sale' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'price_has_sale_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__price ' => '--thim-price-has-sale-spacing: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_responsive_control(
			'price_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-course__item .inner_price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),

			)
		);
		$this->add_responsive_control(
			'price_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-course__item .inner_price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'price_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__price' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

	}

	protected function _register_style_read_more() {
		$this->add_control(
			'heading_readmore_style',
			array(
				'label'     => esc_html__( 'Read More', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'read_more_icon_align',
			array(
				'label'     => esc_html__( 'Icon Position', 'elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'row',
				'options'   => array(
					'row-reverse' => esc_html__( 'Before', 'elementor' ),
					'row'         => esc_html__( 'After', 'elementor' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__read-more' => 'flex-direction: {{VALUE}};',
				)
			)
		);

		$this->add_control(
			'read_more_icon_spacing',
			array(
				'label'     => esc_html__( 'Icon Spacing', 'elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__read-more' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_control(
			'read_more_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em' ),
				'range'      => array(
					'px' => array(
						'max' => 150,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-course__read-more i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .thim-ekits-course__read-more svg' => 'width: {{SIZE}}{{UNIT}};',
				)
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'read_more_typography',
				'selector' => '{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__read-more',
			)
		);

		$this->add_control(
			'read_more_spacing',
			array(
				'label'     => esc_html__( 'Spacing', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__read-more' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'read_more_padding',
			array(
				'label'      => esc_html__( 'Padding', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__read-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),

			)
		);


		$this->add_responsive_control(
			'read_more_border_style',
			array(
				'label'     => esc_html_x( 'Border Type', 'Border Control', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'none'   => esc_html__( 'None', 'thim-elementor-kit' ),
					'solid'  => esc_html_x( 'Solid', 'Border Control', 'thim-elementor-kit' ),
					'double' => esc_html_x( 'Double', 'Border Control', 'thim-elementor-kit' ),
					'dotted' => esc_html_x( 'Dotted', 'Border Control', 'thim-elementor-kit' ),
					'dashed' => esc_html_x( 'Dashed', 'Border Control', 'thim-elementor-kit' ),
					'groove' => esc_html_x( 'Groove', 'Border Control', 'thim-elementor-kit' ),
				),
				'default'   => 'none',
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__read-more' => 'border-style: {{VALUE}};',
				),
			)
		);
		$this->add_responsive_control(
			'read_more_border_dimensions',
			array(
				'label'     => esc_html_x( 'Width', 'Border Control', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::DIMENSIONS,
				'condition' => array(
					'read_more_border_style!' => 'none',
				),
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__read-more' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs(
			'read_more_text_colors'
		);

		$this->start_controls_tab(
			'read_more_normal_style',
			array(
				'label' => esc_html__( 'Normal', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'read_more_color',
			array(
				'label'     => esc_html__( 'Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__read-more' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'read_more_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__read-more' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'read_more_border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__read-more' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'read_more_border_style!' => 'none',
				),
			)
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'read_more_hover_style',
			array(
				'label' => esc_html__( 'Hover', 'thim-elementor-kit' ),
			)
		);

		$this->add_control(
			'read_more_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__read-more:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'read_more_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__read-more:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'read_more_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__read-more:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'read_more_border_style!' => 'none',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		$this->add_responsive_control(
			'read_more_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'thim-elementor-kit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .thim-ekits-course__item .thim-ekits-course__read-more' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);
	}

	public function render_course( $settings, $class_item ) {
		$course = learn_press_get_course();
		?>
		<div <?php post_class( array( $class_item ) ); ?>>
			<?php if ( ! empty( $settings['build_loop_item'] ) && $settings['build_loop_item'] == 'yes' ) {
				\Thim_EL_Kit\Utilities\Elementor::instance()->render_loop_item_content( $settings['template_id'] );
			} else { ?>

				<?php $this->render_thumbnail( $settings, $course ); ?>

				<?php
				$this->render_text_header();

				if ( $settings['repeater'] ) {
					foreach ( $settings['repeater'] as $item ) {
						switch ( $item['key'] ) {
							case 'title':
								$this->render_title( $course, $settings, $item );
								break;
							case 'price':
								$this->render_price( $course );
								break;
							case 'content':
								$this->render_excerpt( $settings, $item, $course );
								break;
							case 'meta_data':
								$this->render_meta_data( $settings, $item, $course );
								break;
							case 'read_more':
								$this->render_read_more( $settings, $item['read_more_text'], $item['read_more_icon'], $course );
								break;
						}
					}
				}

				$this->render_text_footer();
				?>

			<?php } ?>

		</div>
		<?php

	}

	protected function render_text_header() {
		?>
		<div class="thim-ekits-course__content">
		<?php
	}

	protected function render_text_footer() {
		?>
		</div>
		<?php
	}

	/**
	 * @param array $settings
	 * @param LP_Course $course
	 *
	 * @return void
	 */
	protected function render_thumbnail( $settings, $course ) {
		if ( ! $settings['show_image'] ) {
			return;
		}

		$settings['image_size'] = array(
			'id' => get_post_thumbnail_id( $course->get_id() ),
		);

		$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'image_size' );

		if ( empty( $thumbnail_html ) ) {
			$thumbnail_html = LearnPress::instance()->image( 'no-image.png' );
			$thumbnail_html = sprintf( '<img src="%s" alt="%s">', esc_url( $thumbnail_html ), 'course thumbnail' );
		}

		$attributes_html = $this->get_optional_link_attributes_html( $settings );
		?>
		<div class="thim-ekits-course__thumbnail">
			<a href="<?php echo esc_url( $course->get_permalink() ); ?>" <?php Utils::print_unescaped_internal_string( $attributes_html ); ?>
			   class="course-thumbnail">
				<?php echo wp_kses_post( $thumbnail_html ); ?>
			</a>
			<?php $this->render_meta_data_inner_image( $settings, $course ); ?>
		</div>
		<?php
	}

	protected function get_optional_link_attributes_html( $settings ) {
		$attributes_html = 'yes' === $settings['open_new_tab'] ? 'target="_blank" rel="noopener noreferrer"' : '';

		return $attributes_html;
	}

	/**
	 * @param LP_Course $course
	 * @param array $settings
	 * @param array $item
	 *
	 * @return void
	 */
	protected function render_title( $course, $settings, $item ) {
		$singleCourseTemplate = SingleCourseTemplate::instance();
		$attributes_html      = $this->get_optional_link_attributes_html( $settings );
		?>
		<<?php Utils::print_validated_html_tag( $item['title_tag'] ); ?> class="thim-ekits-course__title">
		<?php
		echo sprintf(
			'<a href="%s" %s>%s</a>',
			$course->get_permalink(),
			$attributes_html,
			$singleCourseTemplate->html_title( $course )
		);
		?>
		</<?php Utils::print_validated_html_tag( $item['title_tag'] ); ?>>
		<?php
	}

	/**
	 * @param LP_Course $course
	 * @param string $tag_html
	 *
	 * @return void
	 */
	protected function render_price( LP_Course $course, string $tag_html = 'div' ) {
		$price_html  = $course->get_course_price_html();
		$class_price = 'inner_price';
		if ( $course->is_free() ) {
			$class_price = 'inner_price inner_price__free';
		} elseif ( $course->has_sale_price() ) {
			$class_price = 'inner_price inner_price__has_sale';
		}
		?>
		<?php if ( $price_html ) : ?>
			<<?php Utils::print_validated_html_tag( $tag_html ); ?> class="thim-ekits-course__price">
			<?php echo wp_kses_post( apply_filters( 'thim-kits-widget-get-price', '<' . Utils::validate_html_tag( $tag_html ) . ' class="' . esc_attr( $class_price ) . '">' . wp_kses_post( $price_html ) . '</' . Utils::validate_html_tag( $tag_html ) . '>' ) ); ?>
			</<?php Utils::print_validated_html_tag( $tag_html ); ?>>
		<?php endif; ?>
		<?php
	}

	/**
	 * @param $settings
	 * @param $item
	 * @param LP_Course $course
	 *
	 * @return void
	 */
	protected function render_excerpt( $settings, $item, $course ) {
		?>
		<div class="thim-ekits-course__excerpt">
			<?php echo wp_kses_post( wp_trim_words( get_the_excerpt( $course->get_id() ), absint( $item['excerpt_lenght'] ), esc_html( $item['excerpt_more'] ) ) ); ?>
		</div>
		<?php
	}

	protected function render_meta_data( $settings, $item, $course ) {
		$meta_data = $item['meta_data'];
		?>
		<div
			class="thim-ekits-course__meta elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?><?php echo ' m-psi-' . esc_attr( $item['meta_data_display'] ); ?>">
			<?php
			foreach ( $meta_data as $key => $data ) {
				switch ( $data ) {
					case 'duration':
						$this->render_duration( $item, $course );
						break;
					case 'level':
						$this->render_level( $item, $course );
						break;
					case 'instructor':
						$this->render_instructor( $settings, $course );
						break;
					case 'count_lesson':
						$this->render_count_lesson( $item, $course );
						break;
					case 'count_quiz':
						$this->render_count_quiz( $item, $course );
						break;
					case 'count_student':
						$this->render_count_student( $item, $course );
						break;
					case 'price':
						$this->render_price( $course, 'span' );
						break;
					case 'category':
						$this->render_categories( $item, $course );
						break;
					case 'tag':
						$this->render_tags( $item, $course );
						break;
				}
			}
			echo wp_kses_post( apply_filters( 'thim-kits-extral-meta-data', '', $meta_data, $item ) );
			?>
		</div>
		<?php
	}

	protected function render_meta_data_inner_image( $settings, $course ) {
		$meta_data_inner_image = $settings['meta_data_inner_image'];

		if ( in_array( 'instructor', $meta_data_inner_image ) ) {
			$this->render_instructor( $settings, $course );
		}
		if ( in_array( 'category', $meta_data_inner_image ) ) {
			$this->render_categories( $settings, $course );
		}

		if ( in_array( 'price', $meta_data_inner_image ) ) {
			$this->render_price( $course );
		}
		if ( in_array( 'read_more', $meta_data_inner_image ) ) {
			$this->render_read_more( $settings, $settings['read_more_text_inner_image'], $settings['read_more_icon_inner_image'], $course );
		}
	}

	/**
	 * @param $settings
	 * @param LP_Course $course
	 *
	 * @return void
	 */
	protected function render_duration( $settings, $course ) {
		$singleCourseTemplate = SingleCourseTemplate::instance();
		?>
		<span class="thim-ekits-course__duration">
			<?php
			Icons_Manager::render_icon( $settings['duration_icon_meta_data'], array( 'aria-hidden' => 'true' ) );
			echo wp_kses_post( $singleCourseTemplate->html_duration( $course ) );
			?>
		</span>
		<?php
	}

	/**
	 * @param $settings
	 * @param LP_Course $course
	 *
	 * @return void
	 */
	protected function render_level( $settings, $course ) {
		$singleCourseTemplate = SingleCourseTemplate::instance();
		?>
		<span class="thim-ekits-course__level">
			<?php
			Icons_Manager::render_icon( $settings['level_icon_meta_data'], array( 'aria-hidden' => 'true' ) );
			echo $singleCourseTemplate->html_level( $course );
			?>
		</span>
		<?php
	}

	protected function render_instructor( $settings, $course ) {
		?>
		<span class="thim-ekits-course__instructor">
			<?php if ( $settings['instructor_avatar'] != 'none' ) : ?>
				<span
					class="thim-ekits-course__instructor__avatar"><?php echo wp_kses_post( $course->get_instructor()->get_profile_picture() ); ?></span>
			<?php endif; ?>
			<span
				class="thim-ekits-course__instructor__content"><?php echo wp_kses_post( $course->get_instructor_html() ); ?></span>
		</span>
		<?php
	}

	/**
	 * Count lesson
	 *
	 * @param            $settings
	 * @param \LP_Course $course
	 *
	 * @return void
	 */
	protected function render_count_lesson( $settings, $course ) {
		$lessons = $course->count_items( LP_LESSON_CPT );
		?>
		<span
			class="thim-ekits-course__count-lesson">
			<?php
			Icons_Manager::render_icon( $settings['count_lesson_icon_meta_data'], array( 'aria-hidden' => 'true' ) );
			if ( isset( $settings['label_meta_data'] ) && $settings['label_meta_data'] ) {
				echo absint( $lessons );
			} else {
				printf( _n( '%d lesson', '%d lessons', absint( $lessons ), 'thim-elementor-kit' ), absint( $lessons ) );
			}
			?>
			</span>
		<?php
	}

	/**
	 * Count quiz
	 *
	 * @param            $settings
	 * @param \LP_Course $course
	 *
	 * @return void
	 */
	protected function render_count_quiz( $settings, $course ) {
		$quizzes = $course->count_items( LP_QUIZ_CPT );
		?>
		<span
			class="thim-ekits-course__count-quiz">
			<?php
			Icons_Manager::render_icon( $settings['count_quiz_icon_meta_data'], array( 'aria-hidden' => 'true' ) );
			if ( isset( $settings['label_meta_data'] ) && $settings['label_meta_data'] ) {
				echo absint( $quizzes );
			} else {
				printf( _n( '%d quiz', '%d quizzes', absint( $quizzes ), 'thim-elementor-kit' ), absint( $quizzes ) );
			}
			?>
			</span>
		<?php
	}

	/**
	 * Count students enrolled.
	 *
	 * @param            $settings
	 * @param \LP_Course $course
	 *
	 * @return void
	 */
	protected function render_count_student( $settings, $course ) {
		$students = $course->count_students();
		?>
		<span
			class="thim-ekits-course__count-student">
			<?php
			Icons_Manager::render_icon( $settings['count_student_icon_meta_data'], array( 'aria-hidden' => 'true' ) );
			if ( isset( $settings['label_meta_data'] ) && $settings['label_meta_data'] ) {
				echo absint( $students );
			} else {
				printf( _n( '%d student', '%d students', absint( $students ), 'thim-elementor-kit' ), absint( $students ) );
			}
			?>
			</span>
		<?php
	}

	/**
	 * @param $settings
	 * @param LP_Course $course
	 *
	 * @return void
	 */
	protected function render_categories( $settings, $course ) {
		$singleCourseTemplate = SingleCourseTemplate::instance();
		$html_categories      = $singleCourseTemplate->html_categories( $course );

		if ( empty( $html_categories ) ) {
			return;
		}

		?>
		<span class="thim-ekits-course__categories">
			<?php
			if ( $settings ) {
				Icons_Manager::render_icon( $settings['category_icon_meta_data'], array( 'aria-hidden' => 'true' ) );
			}
			echo $html_categories;
			?>
		</span>
		<?php
	}

	/**
	 * @param $settings
	 * @param LP_Course $course
	 *
	 * @return void
	 */
	protected function render_tags( $settings, $course ) {
		$singleCourseTemplate = SingleCourseTemplate::instance();
		if ( ! is_callable( array( $singleCourseTemplate, 'html_tags' ) ) ) {
			return;
		}

		$html_tags            = $singleCourseTemplate->html_tags( $course );

		if ( empty( $html_tags ) ) {
			return;
		}
		?>
		<span class="thim-ekits-course__tags">
			<?php
			Icons_Manager::render_icon( $settings['tag_icon_meta_data'], array( 'aria-hidden' => 'true' ) );
			echo $html_tags;
			?>
		</span>
		<?php
	}

	/**
	 * @param $settings
	 * @param $text_read_more
	 * @param $icon
	 * @param LP_Course $course
	 *
	 * @return void
	 */
	protected function render_read_more( $settings, $text_read_more, $icon, $course ) {
		$attributes_html = $this->get_optional_link_attributes_html( $settings );
		?>
		<a class="thim-ekits-course__read-more"
		   href="<?php echo esc_url( $course->get_permalink() ); ?>" <?php echo wp_kses_post( $attributes_html ); ?>>
			<?php
			echo esc_html( $text_read_more );

			if ( ! empty( $icon ) ) {
				Icons_Manager::render_icon( $icon, array( 'aria-hidden' => 'true' ) );
			}
			?>
		</a>
		<?php
	}
}
