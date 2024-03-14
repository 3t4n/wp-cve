<?php
/*
 * Elementor Education Addon Courses Carousel Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'naedu_bw_settings' )['naedu_course_carousel'])) { // enable & disable
if ( class_exists( 'LearnPress' ) && class_exists( 'LP_Addon_Course_Review_Preload' ) ) {

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Education_Elementor_Addon_Courses_Carousel extends Widget_Base {

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naedu_basic_course_carousel';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Courses Carousel', 'education-addon' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-slider-push';
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

		$posts = get_posts( 'post_type="lp_course"&numberposts=-1' );
    $PostID = array();
    if ( $posts ) {
      foreach ( $posts as $post ) {
        $PostID[ $post->ID ] = get_the_title($post->ID);
      }
    } else {
      $PostID[0] = __( 'No ID\'s found', 'education-addon' );
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
			'course_limit',
			[
				'label' => esc_html__( 'Courses Limit', 'education-addon' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 30,
				'step' => 1,
				'default' => 9,
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
				'options' => NAEDU_Controls_Helper_Output::get_terms_names( 'course_category'),
				'multiple' => true,
			]
		);
		$this->add_control(
			'course_show_id',
			[
				'label' => __( 'Certain Courses?', 'education-addon' ),
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
				'label' => esc_html__( 'Students', 'education-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'education-addon' ),
				'label_off' => esc_html__( 'No', 'education-addon' ),
				'return_value' => 'true',
				'default' => 'true',
			]
		);
		$this->end_controls_section();// end: Section

		/**
		 * Carousel
		 */
		$this->start_controls_section(
			'section_carousel',
			[
				'label' => esc_html__( 'Carousel Options', 'education-addon' ),
			]
		);			
		$this->add_responsive_control(
			'carousel_items',
			[
				'label' => esc_html__( 'How many items?', 'education-addon' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 3,
				'description' => esc_html__( 'Enter the number of items to show.', 'education-addon' ),
			]
		);
		$this->add_control(
			'carousel_margin',
			[
				'label' => esc_html__( 'Space Between Items', 'education-addon' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 30,
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'carousel_autoplay_timeout',
			[
				'label' => esc_html__( 'Auto Play Timeout', 'education-addon' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5000,
			]
		);
		$this->add_control(
			'carousel_loop',
			[
				'label' => esc_html__( 'Disable Loop?', 'education-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'education-addon' ),
				'label_off' => esc_html__( 'No', 'education-addon' ),
				'return_value' => 'true',
				'description' => esc_html__( 'Continuously moving carousel, if enabled.', 'education-addon' ),
			]
		);
		$this->add_control(
			'carousel_dots',
			[
				'label' => esc_html__( 'Dots', 'education-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'education-addon' ),
				'label_off' => esc_html__( 'No', 'education-addon' ),
				'return_value' => 'true',
				'description' => esc_html__( 'If you want Carousel Dots, enable it.', 'education-addon' ),
			]
		);
		$this->add_control(
			'carousel_nav',
			[
				'label' => esc_html__( 'Navigation', 'education-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'education-addon' ),
				'label_off' => esc_html__( 'No', 'education-addon' ),
				'return_value' => 'true',
				'description' => esc_html__( 'If you want Carousel Navigation, enable it.', 'education-addon' ),
			]
		);

		$this->add_control(
			'carousel_autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'education-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'education-addon' ),
				'label_off' => esc_html__( 'No', 'education-addon' ),
				'return_value' => 'true',
				'description' => esc_html__( 'If you want to start Carousel automatically, enable it.', 'education-addon' ),
			]
		);
		$this->add_control(
			'carousel_animate_out',
			[
				'label' => esc_html__( 'Animate Out', 'education-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'education-addon' ),
				'label_off' => esc_html__( 'No', 'education-addon' ),
				'return_value' => 'true',
				'description' => esc_html__( 'CSS3 animation out.', 'education-addon' ),
			]
		);
		$this->add_control(
			'carousel_mousedrag',
			[
				'label' => esc_html__( 'Disable Mouse Drag?', 'education-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'education-addon' ),
				'label_off' => esc_html__( 'No', 'education-addon' ),
				'return_value' => 'true',
				'description' => esc_html__( 'If you want to disable Mouse Drag, check it.', 'education-addon' ),
			]
		);
		$this->add_control(
			'carousel_autowidth',
			[
				'label' => esc_html__( 'Auto Width', 'education-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'education-addon' ),
				'label_off' => esc_html__( 'No', 'education-addon' ),
				'return_value' => 'true',
				'description' => esc_html__( 'Adjust Auto Width automatically for each carousel items.', 'education-addon' ),
			]
		);
		$this->add_control(
			'carousel_autoheight',
			[
				'label' => esc_html__( 'Auto Height', 'education-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'education-addon' ),
				'label_off' => esc_html__( 'No', 'education-addon' ),
				'return_value' => 'true',
				'description' => esc_html__( 'Adjust Auto Height automatically for each carousel items.', 'education-addon' ),
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

			// Navigation
			$this->start_controls_section(
				'section_navigation_style',
				[
					'label' => esc_html__( 'Navigation', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'carousel_nav' => 'true',
					],
					'frontend_available' => true,
				]
			);
			$this->add_responsive_control(
				'arrow_size',
				[
					'label' => esc_html__( 'Size', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 42,
							'max' => 1000,
							'step' => 1,
						],
					],
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev:before, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next:before' => 'font-size: calc({{SIZE}}{{UNIT}} - 16px);line-height: calc({{SIZE}}{{UNIT}} - 20px);',
					],
				]
			);
			$this->start_controls_tabs( 'nav_arrow_style' );
				$this->start_controls_tab(
					'nav_arrow_normal',
					[
						'label' => esc_html__( 'Normal', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'nav_arrow_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev:before, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next:before' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'nav_arrow_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'nav_border',
						'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next',
					]
				);
				$this->end_controls_tab();  // end:Normal tab

				$this->start_controls_tab(
					'nav_arrow_hover',
					[
						'label' => esc_html__( 'Hover', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'nav_arrow_hov_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev:hover:before, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next:hover:before' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'nav_arrow_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev:hover, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'nav_active_border',
						'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev:hover, {{WRAPPER}} .owl-carousel .owl-nav button.owl-next:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab

			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

			// Dots
			$this->start_controls_section(
				'section_dots_style',
				[
					'label' => esc_html__( 'Dots', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'carousel_dots' => array('true'),
					],
					'frontend_available' => true,
				]
			);
			$this->add_responsive_control(
				'dots_size',
				[
					'label' => esc_html__( 'Size', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1000,
							'step' => 1,
						],
					],
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .owl-carousel .owl-dot' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}}',
					],
				]
			);
			$this->add_responsive_control(
				'dots_margin',
				[
					'label' => esc_html__( 'Margin', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .owl-carousel .owl-dot' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->start_controls_tabs( 'dots_style' );
				$this->start_controls_tab(
					'dots_normal',
					[
						'label' => esc_html__( 'Normal', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'dots_color',
					[
						'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .owl-carousel .owl-dot' => 'background: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'dots_border',
						'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .owl-carousel .owl-dot',
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'dots_active',
					[
						'label' => esc_html__( 'Active', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'dots_active_color',
					[
						'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .owl-carousel .owl-dot.active' => 'background: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'dots_active_border',
						'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .owl-carousel .owl-dot.active',
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

		// Carousel Data
		$carousel_items = !empty( $settings['carousel_items'] ) ? $settings['carousel_items'] : '';
		$carousel_items_tablet = !empty( $settings['carousel_items_tablet'] ) ? $settings['carousel_items_tablet'] : '';
		$carousel_items_mobile = !empty( $settings['carousel_items_mobile'] ) ? $settings['carousel_items_mobile'] : '';
		$carousel_margin = !empty( $settings['carousel_margin']['size'] ) ? $settings['carousel_margin']['size'] : '';
		$carousel_autoplay_timeout = !empty( $settings['carousel_autoplay_timeout'] ) ? $settings['carousel_autoplay_timeout'] : '';
		$carousel_loop  = ( isset( $settings['carousel_loop'] ) && ( 'true' == $settings['carousel_loop'] ) ) ? $settings['carousel_loop'] : 'false';
		$carousel_dots  = ( isset( $settings['carousel_dots'] ) && ( 'true' == $settings['carousel_dots'] ) ) ? true : false;
		$carousel_nav  = ( isset( $settings['carousel_nav'] ) && ( 'true' == $settings['carousel_nav'] ) ) ? true : false;
		$carousel_autoplay  = ( isset( $settings['carousel_autoplay'] ) && ( 'true' == $settings['carousel_autoplay'] ) ) ? true : false;
		$carousel_animate_out  = ( isset( $settings['carousel_animate_out'] ) && ( 'true' == $settings['carousel_animate_out'] ) ) ? true : false;
		$carousel_mousedrag  = ( isset( $settings['carousel_mousedrag'] ) && ( 'true' == $settings['carousel_mousedrag'] ) ) ? $settings['carousel_mousedrag'] : 'false';
		$carousel_autowidth  = ( isset( $settings['carousel_autowidth'] ) && ( 'true' == $settings['carousel_autowidth'] ) ) ? true : false;
		$carousel_autoheight  = ( isset( $settings['carousel_autoheight'] ) && ( 'true' == $settings['carousel_autoheight'] ) ) ? true : false;

		// Carousel Data's
		$carousel_loop = $carousel_loop !== 'true' ? ' data-loop="true"' : ' data-loop="false"';
		$carousel_items = $carousel_items ? ' data-items="'. $carousel_items .'"' : ' data-items="2"';
		$carousel_margin = $carousel_margin ? ' data-margin="'. $carousel_margin .'"' : ' data-margin="20"';
		$carousel_dots = $carousel_dots ? ' data-dots="true"' : ' data-dots="false"';
		$carousel_nav = $carousel_nav ? ' data-nav="true"' : ' data-nav="false"';
		$carousel_autoplay_timeout = $carousel_autoplay_timeout ? ' data-autoplay-timeout="'. $carousel_autoplay_timeout .'"' : '';
		$carousel_autoplay = $carousel_autoplay ? ' data-autoplay="true"' : '';
		$carousel_animate_out = $carousel_animate_out ? ' data-animateout="true"' : '';
		$carousel_mousedrag = $carousel_mousedrag !== 'true' ? ' data-mouse-drag="true"' : ' data-mouse-drag="false"';
		$carousel_autowidth = $carousel_autowidth ? ' data-auto-width="true"' : '';
		$carousel_autoheight = $carousel_autoheight ? ' data-auto-height="true"' : '';
		$carousel_tablet = $carousel_items_tablet ? ' data-items-tablet="'. $carousel_items_tablet .'"' : ' data-items-tablet="2"';
		$carousel_mobile = $carousel_items_mobile ? ' data-items-mobile-landscape="'. $carousel_items_mobile .'"' : ' data-items-mobile-landscape="1"';
		$carousel_small_mobile = $carousel_items_mobile ? ' data-items-mobile-portrait="'. $carousel_items_mobile .'"' : ' data-items-mobile-portrait="1"';

		// Turn output buffer on
		ob_start();

    if ($course_show_id) {
			$course_show_id = json_encode( $course_show_id );
			$course_show_id = str_replace(array( '[', ']' ), '', $course_show_id);
			$course_show_id = str_replace(array( '"', '"' ), '', $course_show_id);
      $course_show_id = explode(',',$course_show_id);
    } else {
      $course_show_id = '';
    }

		$args = array(
		  'post_type' => 'lp_course',
		  'posts_per_page' => (int)$course_limit,
		  'course_category' => implode(',', $course_show_category),
		  'orderby' => $course_orderby,
		  'order' => $course_order,
      'post__in' => $course_show_id,
		);

		$naedu_course = new \WP_Query( $args );
		if ($naedu_course->have_posts()) : ?>
		<div class="naedu-courses-carousel naedu-courses<?php echo esc_attr($style_class); ?>">
			<div class="owl-carousel" <?php echo $carousel_loop . $carousel_items . $carousel_margin . $carousel_dots . $carousel_nav . $carousel_autoplay_timeout . $carousel_autoplay . $carousel_animate_out . $carousel_mousedrag . $carousel_autowidth . $carousel_autoheight  . $carousel_tablet . $carousel_mobile . $carousel_small_mobile; ?>>

			<?php while ($naedu_course->have_posts()) : $naedu_course->the_post();

      global $post, $course;
		  $large_image =  wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'fullsize', false, '' );
		  $large_image = $large_image[0];
			$cat_list = get_the_category();

			$course_rate_res = learn_press_get_course_rate( get_the_ID(), false );
			$course_rate     = $course_rate_res['rated'];
			$total           = $course_rate_res['total']; 

      $percent = ( ! $course_rate ) ? 0 : min( 100, ( round( $course_rate * 2 ) / 2 ) * 20 );

		  if ($large_image && $course_image) {
				$img_cls = '';
			} else {
				$img_cls = ' no-img';
			}
			$date_format = $date_format ? $date_format : '';

			if ($course_style === 'two') { ?>
				<div class="naedu-course-single-<?php the_ID(); ?>">
					<figure>
						<?php if ($large_image && $course_image) { ?>
					    <div class="naedu-image"><a href="<?php echo esc_url( get_permalink() ); ?>"><img src="<?php echo esc_url($large_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"></a></div>
						<?php } ?>
            <figcaption>
              <ul class="naedu-meta">
              	<?php if($course_lesson) { ?>
								<li><i class="fas fa-book" aria-hidden="true"></i><span><?php echo $course->count_items( LP_LESSON_CPT ); ?></span></li>
		            <?php } if($course_students) { ?>
								<li><i class="fas fa-user"></i><span><?php echo $course->get_students_html(); ?></span></li> 
								<?php } ?>
              </ul>
              <div class="course-info">
                <h3>
                	<?php 
							    global $post;
						       $categories = wp_get_post_terms($post->ID,'course_category');
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
                  <div class="nich-col-5">
                    <h5>
                    	<?php if ( $price_html = $course->get_price_html() ) {
			              		if ( $course->get_origin_price() != $course->get_price() ) {
			              			$origin_price_html = $course->get_origin_price_html();
													echo $origin_price_html;
												}
													$price = substr($price_html,0,-2).'<span>'.substr($price_html,-2).'</span>';
													echo $price_html;
											} ?>
                    </h5>
                  </div>
                </div>
              </div>
            </figcaption>
          </figure>
	      </div>
			<?php } else { ?>
				
				<div class="naedu-course-single-<?php the_ID(); ?>">
					<figure>
						<?php if ($large_image && $course_image) { ?>
					    <div class="naedu-image"><a href="<?php echo esc_url( get_permalink() ); ?>"><img src="<?php echo esc_url($large_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"></a></div>
						<?php } ?>
            <figcaption>
              <h3>
              	<?php if ( $price_html = $course->get_price_html() ) {
              		if ( $course->get_origin_price() != $course->get_price() ) {
              			$origin_price_html = $course->get_origin_price_html();
										echo $origin_price_html;
									}
										$price = substr($price_html,0,-2).'<span>'.substr($price_html,-2).'</span>';
										echo $price_html;
								} ?>
              </h3>
              <div class="product-review">
                <span class="rating-wrap">
                  <span style="width: <?php echo esc_attr($percent); ?>%;"></span>
                </span>
              </div>
              <h4><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html(get_the_title()); ?></a></h4>
              <?php if ($course_author) { ?><h5><?php echo esc_html__( 'Author : ', 'education-addon' ); ?><?php echo esc_html(get_the_author()); ?></h5><?php } ?>
              <ul class="naedu-meta">
              	<?php if($course_lesson) { ?>
								<li><i class="fas fa-book" aria-hidden="true"></i><span><?php echo $course->count_items( LP_LESSON_CPT ); ?></span></li>
		            <?php } if($course_students) { ?>
								<li><i class="fas fa-user"></i><span><?php echo $course->get_students_html(); ?></span></li> 
								<?php } ?>
              </ul>
            </figcaption>
          </figure>
				</div>
			<?php } endwhile; ?>
			</div>
		  <?php wp_reset_postdata();?>
		</div>
	  <?php endif;

		// Return outbut buffer
		echo ob_get_clean();

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Education_Elementor_Addon_Courses_Carousel() );
}
} // enable & disable
