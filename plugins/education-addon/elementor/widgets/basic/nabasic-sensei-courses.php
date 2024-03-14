<?php
/*
 * Elementor Education Addon Courses Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'naedu_bw_settings' )['naedu_sensei_course'])) { // enable & disable
if ( class_exists( 'Sensei_Main' ) ) {

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Education_Elementor_Addon_SenseiCourses extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naedu_basic_sensei_course';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Sensei Courses', 'education-addon' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-posts-group';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naedu-basic-category'];
	}

	/**
	 * Register Education Addon Courses widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$posts = get_posts( 'post_type="course"&numberposts=-1' );
    $PostID = array();
    if ( $posts ) {
      foreach ( $posts as $post ) {
        $PostID[ $post->ID ] = $post->ID;
      }
    } else {
      $PostID[ __( 'No ID\'s found', 'education-addon' ) ] = 0;
    }

    $this->start_controls_section(
			'section_course_listing',
			[
				'label' => esc_html__( 'Listing Options', 'education-addon' ),
			]
		);
		$this->add_control(
			'course_style',
			[
				'label' => esc_html__( 'Courses Style', 'education-addon' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one'          => esc_html__('Style One', 'education-addon'),
					'two'          => esc_html__('Style Two', 'education-addon'),
				],
				'default' => 'one',
			]
		);
		$this->add_control(
			'course_col',
			[
				'label' => esc_html__( 'Courses Column', 'education-addon' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1'          => esc_html__('One', 'education-addon'),
					'2'          => esc_html__('Two', 'education-addon'),
          '3'          => esc_html__('Three', 'education-addon'),
          '4'          => esc_html__('Four', 'education-addon'),
				],
				'default' => '3',
			]
		);
		$this->add_control(
			'course_limit',
			[
				'label' => esc_html__( 'Courses Limit', 'education-addon' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 3,
				'description' => esc_html__( 'Enter the number of items to show.', 'education-addon' ),
			]
		);
		$this->add_control(
			'course_order',
			[
				'label' => __( 'Order', 'education-addon' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'ASC' => esc_html__( 'Asending', 'education-addon' ),
					'DESC' => esc_html__( 'Desending', 'education-addon' ),
				],
				'default' => 'DESC',
			]
		);
		$this->add_control(
			'course_orderby',
			[
				'label' => __( 'Order By', 'education-addon' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'education-addon' ),
					'ID' => esc_html__( 'ID', 'education-addon' ),
					'author' => esc_html__( 'Author', 'education-addon' ),
					'title' => esc_html__( 'Title', 'education-addon' ),
					'date' => esc_html__( 'Date', 'education-addon' ),
					'name' => esc_html__( 'Name', 'education-addon' ),
					'modified' => esc_html__( 'Modified', 'education-addon' ),
					'comment_count' => esc_html__( 'Comment Count', 'education-addon' ),
				],
				'default' => 'date',
			]
		);
		$this->add_control(
			'course_show_category',
			[
				'label' => __( 'Certain Categories?', 'education-addon' ),
				'type' => Controls_Manager::SELECT2,
				'default' => [],
				'options' => NAEDU_Controls_Helper_Output::get_terms_names( 'course-category'),
				'multiple' => true,
			]
		);
		$this->add_control(
			'course_show_id',
			[
				'label' => __( 'Certain ID\'s?', 'education-addon' ),
				'type' => Controls_Manager::SELECT2,
				'default' => [],
				'options' => $PostID,
				'multiple' => true,
			]
		);
		$this->add_control(
			'short_content',
			[
				'label' => esc_html__( 'Excerpt Length', 'education-addon' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'step' => 1,
				'default' => 15,
				'description' => __( 'How many words you want in short content paragraph. <b style="color:#232323;">This field will not work for the content which is entered in Excerpt field of the Post.</b>', 'education-addon' ),
			]
		);
		$this->add_control(
			'course_pagination',
			[
				'label' => esc_html__( 'Pagination', 'education-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'education-addon' ),
				'label_off' => esc_html__( 'Hide', 'education-addon' ),
				'return_value' => 'true',
				'default' => 'true',
			]
		);
		$this->add_control(
			'date_format',
			[
				'label' => esc_html__( 'Date Formate', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'description' => __( 'Enter date format (for more info <a href="https://wordpress.org/support/article/formatting-date-and-time/" target="_blank">click here</a>).', 'education-addon' ),
			]
		);
		$this->end_controls_section();// end: Section

		// Meta Options
			$this->start_controls_section(
				'section_course_metas',
				[
					'label' => esc_html__( 'Meta\'s Options', 'education-addon' ),
				]
			);
			$this->add_control(
				'course_image',
				[
					'label' => esc_html__( 'Image', 'education-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Show', 'education-addon' ),
					'label_off' => esc_html__( 'Hide', 'education-addon' ),
					'return_value' => 'true',
					'default' => 'true',
				]
			);
			$this->add_control(
				'course_date',
				[
					'label' => esc_html__( 'Date', 'education-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Show', 'education-addon' ),
					'label_off' => esc_html__( 'Hide', 'education-addon' ),
					'return_value' => 'true',
					'default' => 'true',
				]
			);
			$this->add_control(
				'course_author',
				[
					'label' => esc_html__( 'Author', 'education-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Show', 'education-addon' ),
					'label_off' => esc_html__( 'Hide', 'education-addon' ),
					'return_value' => 'true',
					'default' => 'true',
				]
			);
			$this->add_control(
				'course_lesson',
				[
					'label' => esc_html__( 'Lesson', 'education-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'education-addon' ),
					'label_off' => esc_html__( 'No', 'education-addon' ),
					'return_value' => 'true',
					'default' => 'true',
				]
			);
			$this->add_control(
				'course_students',
				[
					'label' => esc_html__( 'Completed', 'education-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'education-addon' ),
					'label_off' => esc_html__( 'No', 'education-addon' ),
					'return_value' => 'true',
					'default' => 'true',
				]
			);
			$this->end_controls_section();// end: Section

		// Section
			$this->start_controls_section(
				'sectn_style',
				[
					'label' => esc_html__( 'Section', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'box_border_radius',
				[
					'label' => __( 'Border Radius', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-courses figure' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'news_section_margin',
				[
					'label' => __( 'Margin', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-courses figure' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'secn_bg_color',
					'label' => __( 'Background Color', 'education-addon' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .naedu-courses figure',
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-courses figure',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-courses figure',
				]
			);
			$this->end_controls_section();// end: Section

		// Price
			$this->start_controls_section(
				'price_style',
				[
					'label' => esc_html__( 'Price', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'price_box_border_radius',
				[
					'label' => __( 'Border Radius', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-courses h3' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'news_price_margin',
				[
					'label' => __( 'Margin', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-courses h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'price_bg_color',
					'label' => __( 'Background Color', 'education-addon' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .naedu-courses h3',
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'price_border',
					'label' => esc_html__( 'Border', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-courses h3',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'price_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-courses h3',
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'price_title_typography',
					'selector' => '{{WRAPPER}} .naedu-courses h3',
				]
			);
			$this->end_controls_section();// end: Section

		// Title
			$this->start_controls_section(
				'section_title_style',
				[
					'label' => esc_html__( 'Title', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'sasstp_title_typography',
					'selector' => '{{WRAPPER}} .naedu-courses h4',
				]
			);
			$this->start_controls_tabs( 'title_style' );
				$this->start_controls_tab(
					'title_normal',
					[
						'label' => esc_html__( 'Normal', 'education-addon' ),
					]
				);
				$this->add_control(
					'title_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-courses h4, {{WRAPPER}} .naedu-courses h4 a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'title_hover',
					[
						'label' => esc_html__( 'Hover', 'education-addon' ),
					]
				);
				$this->add_control(
					'title_hover_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-courses h4 a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Category
			$this->start_controls_section(
				'section_cat_style',
				[
					'label' => esc_html__( 'Category', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'course_style' => 'two',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'sasstp_cat_typography',
					'selector' => '{{WRAPPER}} .courses-style-two h3',
				]
			);
			$this->start_controls_tabs( 'cat_style' );
				$this->start_controls_tab(
					'cat_normal',
					[
						'label' => esc_html__( 'Normal', 'education-addon' ),
					]
				);
				$this->add_control(
					'cat_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .courses-style-two h3, {{WRAPPER}} .courses-style-two h3 a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'cat_hover',
					[
						'label' => esc_html__( 'Hover', 'education-addon' ),
					]
				);
				$this->add_control(
					'cat_hover_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .courses-style-two h3 a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Meta
			$this->start_controls_section(
				'section_date_style',
				[
					'label' => esc_html__( 'Meta', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'date_typography',
					'selector' => '{{WRAPPER}} .courses-style-two .naedu-meta li',
				]
			);			
			$this->start_controls_tabs( 'meta_style' );
				$this->start_controls_tab(
					'meta_normal',
					[
						'label' => esc_html__( 'Normal', 'education-addon' ),
					]
				);
				$this->add_control(
					'meta_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .courses-style-two .naedu-meta li, {{WRAPPER}} .courses-style-two .naedu-meta li a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'meta_hover',
					[
						'label' => esc_html__( 'Hover', 'education-addon' ),
					]
				);
				$this->add_control(
					'meta_hover_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .courses-style-two .naedu-meta li a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Author
			$this->start_controls_section(
				'section_author_style',
				[
					'label' => esc_html__( 'Author', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'author_typography',
					'selector' => '{{WRAPPER}} .naedu-avatar, {{WRAPPER}} .naedu-courses h5',
				]
			);			
			$this->add_control(
				'author_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .naedu-avatar, {{WRAPPER}} .naedu-courses h5' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Pagination
			$this->start_controls_section(
				'section_pagi_style',
				[
					'label' => esc_html__( 'Pagination', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'pagi_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'pagi_width',
				[
					'label' => esc_html__( 'Pagination Width', 'education-addon' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1500,
							'step' => 1,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-pagination ul li span, {{WRAPPER}} .naedu-pagination ul li a ' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'pagi_typography',
					'selector' => '{{WRAPPER}} .naedu-pagination ul li a, {{WRAPPER}} .naedu-pagination ul li span',
				]
			);
			$this->start_controls_tabs( 'pagi_style' );
				$this->start_controls_tab(
					'pagi_normal',
					[
						'label' => esc_html__( 'Normal', 'education-addon' ),
					]
				);
				$this->add_control(
					'pagi_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-pagination ul li a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'pagi_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-pagination ul li a' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'pagi_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-pagination ul li a',
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'pagi_hover',
					[
						'label' => esc_html__( 'Hover', 'education-addon' ),
					]
				);
				$this->add_control(
					'pagi_hover_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-pagination ul li a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'pagi_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-pagination ul li a:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'pagi_hover_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-pagination ul li a:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
				$this->start_controls_tab(
					'pagi_active',
					[
						'label' => esc_html__( 'Active', 'education-addon' ),
					]
				);
				$this->add_control(
					'pagi_active_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-pagination ul li span.current' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'pagi_bg_active_color',
					[
						'label' => esc_html__( 'Background Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-pagination ul li span.current' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'pagi_active_border',
						'label' => esc_html__( 'Border', 'education-addon' ),
						'selector' => '{{WRAPPER}} .naedu-pagination ul li span.current',
					]
				);
				$this->end_controls_tab();  // end:Active tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

	}

	/**
	 * Render App Works widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();

		$course_style = !empty( $settings['course_style'] ) ? $settings['course_style'] : '';
		$course_col = !empty( $settings['course_col'] ) ? $settings['course_col'] : '';
		$course_limit = !empty( $settings['course_limit'] ) ? $settings['course_limit'] : '';
		$course_order = !empty( $settings['course_order'] ) ? $settings['course_order'] : '';
		$course_orderby = !empty( $settings['course_orderby'] ) ? $settings['course_orderby'] : '';
		$course_show_category = !empty( $settings['course_show_category'] ) ? $settings['course_show_category'] : [];
		$course_show_id = !empty( $settings['course_show_id'] ) ? $settings['course_show_id'] : [];
		$short_content = !empty( $settings['short_content'] ) ? $settings['short_content'] : '';
		$date_format = !empty( $settings['date_format'] ) ? $settings['date_format'] : '';
		$course_pagination  = ( isset( $settings['course_pagination'] ) && ( 'true' == $settings['course_pagination'] ) ) ? true : false;

		$course_image  = ( isset( $settings['course_image'] ) && ( 'true' == $settings['course_image'] ) ) ? true : false;
		$course_date  = ( isset( $settings['course_date'] ) && ( 'true' == $settings['course_date'] ) ) ? true : false;
		$course_author  = ( isset( $settings['course_author'] ) && ( 'true' == $settings['course_author'] ) ) ? true : false;
		$course_lesson  = ( isset( $settings['course_lesson'] ) && ( 'true' == $settings['course_lesson'] ) ) ? true : false;
		$course_students  = ( isset( $settings['course_students'] ) && ( 'true' == $settings['course_students'] ) ) ? true : false;

		$course_col = $course_col ? $course_col : '3';

		if ($course_style === 'two') {
			$style_class = ' courses-style-two';
		} else {
			$style_class = '';
		}
  	if ($course_col === '2') {
			$col_class = 'nich-col-md-6';
		} elseif ($course_col === '1') {
			$col_class = 'nich-col-md-12';
		} elseif ($course_col === '4') {
			$col_class = 'nich-col-lg-3 nich-col-md-6';
		} else {
			$col_class = 'nich-col-lg-4 nich-col-md-6';
		}

		// Turn output buffer on
		ob_start();

		// Pagination
			global $paged;
			if ( get_query_var( 'paged' ) )
			  $my_page = get_query_var( 'paged' );
			else {
			  if ( get_query_var( 'page' ) )
				$my_page = get_query_var( 'page' );
			  else
				$my_page = 1;
			  set_query_var( 'paged', $my_page );
			  $paged = $my_page;
			}

    if ($course_show_id) {
			$course_show_id = json_encode( $course_show_id );
			$course_show_id = str_replace(array( '[', ']' ), '', $course_show_id);
			$course_show_id = str_replace(array( '"', '"' ), '', $course_show_id);
      $course_show_id = explode(',',$course_show_id);
    } else {
      $course_show_id = '';
    }

		$args = array(
		  // other query params here,
		  'paged' => $my_page,
		  'post_type' => 'course',
		  'posts_per_page' => (int)$course_limit,
		  'course-category' => implode(',', $course_show_category),
		  'orderby' => $course_orderby,
		  'order' => $course_order,
      'post__in' => $course_show_id,
		);

		$naedu_course = new \WP_Query( $args );
		if ($naedu_course->have_posts()) : ?>
		<div class="naedu-courses<?php echo esc_attr($style_class); ?>">
			<div class="nich-row">

			<?php while ($naedu_course->have_posts()) : $naedu_course->the_post();

      global $post, $course;
		  $large_image =  wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'fullsize', false, '' );
		  $large_image = $large_image[0];
			$cat_list = get_the_category();

			$course_args     = array(
				'post_id' => $course->ID,
				'type'    => 'sensei_course_status',
				'status'  => 'any',
			);

			$completed    = Sensei()->course->get_completion_percentage( get_the_ID() );
			$lesson_count = Sensei()->course->course_lesson_count( get_the_ID() );

		  if ($large_image && $course_image) {
				$img_cls = '';
			} else {
				$img_cls = ' no-img';
			}
			$date_format = $date_format ? $date_format : '';
			if ($course_style === 'two') { ?>
				<div class="<?php echo esc_attr($col_class); ?>">
					<figure>
						<?php if ($large_image && $course_image) { ?>
					    <div class="naedu-image"><a href="<?php echo esc_url( get_permalink() ); ?>"><img src="<?php echo esc_url($large_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"></a></div>
						<?php } ?>
            <figcaption>
              <ul class="naedu-meta">
              	<?php if($course_lesson) { ?>
								<li><i class="fas fa-book" aria-hidden="true"></i><span><?php echo $lesson_count; ?> <?php echo esc_html__( 'Lessons', 'education-addon' ); ?></span></li>
		            <?php } if($course_students) { ?>
								<li><i class="fas fa-server"></i></i><span><?php echo $completed; ?><?php echo esc_html__( '% Completed', 'education-addon' ); ?></span></li> 
								<?php } ?>
              </ul>
              <div class="course-info">
                <h3>
                	<?php 
							    global $post;
						       $categories = wp_get_post_terms($post->ID,'course-category');
						      foreach ( $categories as $category ) :
			              echo '<a href="'.esc_url( get_category_link( $category->term_id ) ).'">'. esc_html( $category->name ).'</a> ';
			            endforeach;
			           ?>
                </h3>
              	<h4><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html(get_the_title()); ?></a></h4>
                 <ul class="naedu-meta">
                  <li><i class="fas fa-info-circle"></i><span><?php echo esc_html__( 'Last updated ', 'education-addon' ); ?><?php echo esc_attr(get_the_date('d/Y'));?></span></li>
                </ul>
              </div>
              <div class="course-auther">
                <div class="nich-row nich-align-items-center">
                  <div class="nich-col-7">
                    <div class="naedu-avatar">
                      <?php echo get_avatar( get_the_author_meta( 'ID' ), 39 ); ?>
                      <span><?php echo esc_html(get_the_author()); ?></span>
                    </div>
                  </div>                  
                </div>
              </div>
            </figcaption>
          </figure>
	      </div>
			<?php } else { ?>
				<div class="<?php echo esc_attr($col_class); ?>">
					<figure>
						<?php if ($large_image && $course_image) { ?>
					    <div class="naedu-image"><a href="<?php echo esc_url( get_permalink() ); ?>"><img src="<?php echo esc_url($large_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"></a></div>
						<?php } ?>
            <figcaption>
              <h4><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html(get_the_title()); ?></a></h4>
              <?php if ($course_author) { ?><h5><?php echo esc_html__( 'Author : ', 'education-addon' ); ?><?php echo esc_html(get_the_author()); ?></h5><?php } ?>
              <ul class="naedu-meta">
              	<?php if($course_lesson) { ?>
								<li><i class="fas fa-book" aria-hidden="true"></i><span><?php echo $lesson_count; ?> <?php echo esc_html__( 'Lessons', 'education-addon' ); ?></span></li>
		            <?php } if($course_students) { ?>
								<li><i class="fas fa-server"></i></i><span><?php echo $completed; ?><?php echo esc_html__( '% Completed', 'education-addon' ); ?></span></li> 
								<?php } ?>
              </ul>
            </figcaption>
          </figure>
				</div>
			<?php } endwhile; ?>
			</div>
		  <?php wp_reset_postdata();
			if ($course_pagination) { naedu_paging_nav($naedu_course->max_num_pages,"",$paged); }
			?>
		</div>
	  <?php endif;

		// Return outbut buffer
		echo ob_get_clean();

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Education_Elementor_Addon_SenseiCourses() );
}
} // enable & disable
