<?php
/*
 * Elementor Education Addon Blog Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'naedu_bw_settings' )['naedu_blog'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Education_Elementor_Addon_Blog extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'naedu_basic_blog';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Blog', 'education-addon' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-archive-posts';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['naedu-basic-category'];
	}

	/**
	 * Register Education Addon Blog widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function register_controls(){

		$posts = get_posts( 'post_type="post"&numberposts=-1' );
    $PostID = array();
    if ( $posts ) {
      foreach ( $posts as $post ) {
        $PostID[ $post->ID ] = $post->ID;
      }
    } else {
      $PostID[ __( 'No ID\'s found', 'education-addon' ) ] = 0;
    }

    $this->start_controls_section(
			'section_blog_listing',
			[
				'label' => esc_html__( 'Listing Options', 'education-addon' ),
			]
		);
		$this->add_control(
			'blog_style',
			[
				'label' => esc_html__( 'Blog Style', 'education-addon' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one'          => esc_html__('Style One', 'education-addon'),
					'two'          => esc_html__('Style Two', 'education-addon'),
				],
				'default' => 'one',
			]
		);
		$this->add_control(
			'blog_col',
			[
				'label' => esc_html__( 'Blog Column', 'education-addon' ),
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
			'blog_limit',
			[
				'label' => esc_html__( 'Blog Limit', 'education-addon' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 3,
				'description' => esc_html__( 'Enter the number of items to show.', 'education-addon' ),
			]
		);
		$this->add_control(
			'blog_order',
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
			'blog_orderby',
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
			'blog_show_category',
			[
				'label' => __( 'Certain Categories?', 'education-addon' ),
				'type' => Controls_Manager::SELECT2,
				'default' => [],
				'options' => NAEDU_Controls_Helper_Output::get_terms_names( 'category'),
				'multiple' => true,
			]
		);
		$this->add_control(
			'blog_show_id',
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
			'blog_pagination',
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
			'read_more_txt',
			[
				'label' => esc_html__( 'Read More Button Text', 'education-addon' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Read More', 'education-addon' ),
				'placeholder' => esc_html__( 'Type text here', 'education-addon' ),
			]
		);
		$this->end_controls_section();// end: Section

		$this->start_controls_section(
			'section_blog_metas',
			[
				'label' => esc_html__( 'Meta\'s Options', 'education-addon' ),
			]
		);
		$this->add_control(
			'blog_image',
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
			'blog_date',
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
			'blog_rd_time',
			[
				'label' => esc_html__( 'Read Time', 'education-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'education-addon' ),
				'label_off' => esc_html__( 'Hide', 'education-addon' ),
				'return_value' => 'true',
				'default' => 'true',
			]
		);
		$this->add_control(
			'blog_command',
			[
				'label' => esc_html__( 'Command', 'education-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'education-addon' ),
				'label_off' => esc_html__( 'Hide', 'education-addon' ),
				'return_value' => 'true',
				'default' => 'true',
			]
		);
		$this->add_control(
			'blog_author',
			[
				'label' => esc_html__( 'Author', 'education-addon' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'education-addon' ),
				'label_off' => esc_html__( 'Hide', 'education-addon' ),
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
						'{{WRAPPER}} .naedu-blog figure' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
						'{{WRAPPER}} .naedu-blog figure' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'news_section_padding',
				[
					'label' => __( 'Padding', 'education-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .naedu-blog figure' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name' => 'secn_bg_color',
					'label' => __( 'Background Color', 'education-addon' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .naedu-blog figure',
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-blog figure',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'education-addon' ),
					'selector' => '{{WRAPPER}} .naedu-blog figure',
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
					'name' => 'title_typography',
					'selector' => '{{WRAPPER}} .naedu-blog h3',
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
							'{{WRAPPER}} .naedu-blog h3, {{WRAPPER}} .naedu-blog h3 a' => 'color: {{VALUE}};',
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
							'{{WRAPPER}} .naedu-blog h3 a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Content
			$this->start_controls_section(
				'section_content_style',
				[
					'label' => esc_html__( 'Content', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'content_typography',
					'selector' => '{{WRAPPER}} .naedu-blog p',
				]
			);
			$this->start_controls_tabs( 'content_style' );
				$this->start_controls_tab(
					'content_normal',
					[
						'label' => esc_html__( 'Normal', 'education-addon' ),
					]
				);
				$this->add_control(
					'content_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-blog, {{WRAPPER}} .naedu-blog p' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'content_hover',
					[
						'label' => esc_html__( 'Hover', 'education-addon' ),
					]
				);
				$this->add_control(
					'content_hover_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-blog figure:hover p' => 'color: {{VALUE}};',
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
					'selector' => '{{WRAPPER}} .naedu-avatar span a',
				]
			);
			$this->start_controls_tabs( 'author_style' );
				$this->start_controls_tab(
					'author_normal',
					[
						'label' => esc_html__( 'Normal', 'education-addon' ),
					]
				);
				$this->add_control(
					'author_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-avatar span a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'author_hover',
					[
						'label' => esc_html__( 'Hover', 'education-addon' ),
					]
				);
				$this->add_control(
					'author_hover_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-avatar span a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Date
			$this->start_controls_section(
				'section_date_style',
				[
					'label' => esc_html__( 'Date', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'blog_style' => 'two',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'date_typography',
					'selector' => '{{WRAPPER}} .blog-date',
				]
			);			
			$this->add_control(
				'date_color',
				[
					'label' => esc_html__( 'Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .blog-date' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'date_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'education-addon' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .blog-date' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Meta
			$this->start_controls_section(
				'section_meta_style',
				[
					'label' => esc_html__( 'Meta', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'meta_typography',
					'selector' => '{{WRAPPER}} .naedu-meta li',
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
							'{{WRAPPER}} .naedu-meta li, {{WRAPPER}} .naedu-meta li a' => 'color: {{VALUE}};',
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
							'{{WRAPPER}} .naedu-meta li a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Link
			$this->start_controls_section(
				'section_link_style',
				[
					'label' => esc_html__( 'Link', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'education-addon' ),
					'name' => 'link_typography',
					'selector' => '{{WRAPPER}} .naedu-link',
				]
			);
			$this->start_controls_tabs( 'link_style' );
				$this->start_controls_tab(
					'link_normal',
					[
						'label' => esc_html__( 'Normal', 'education-addon' ),
					]
				);
				$this->add_control(
					'link_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-link' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'link_hover',
					[
						'label' => esc_html__( 'Hover', 'education-addon' ),
					]
				);
				$this->add_control(
					'link_hover_color',
					[
						'label' => esc_html__( 'Color', 'education-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .naedu-link:hover' => 'color: {{VALUE}};',
							'{{WRAPPER}} .naedu-link:hover' => 'border-color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Hover tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Pagination
			$this->start_controls_section(
				'section_pagi_style',
				[
					'label' => esc_html__( 'Pagination', 'education-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'blog_pagination' => 'true',
					],
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
		$blog_image  = ( isset( $settings['blog_image'] ) && ( 'true' == $settings['blog_image'] ) ) ? true : false;
		$blog_date  = ( isset( $settings['blog_date'] ) && ( 'true' == $settings['blog_date'] ) ) ? true : false;
		$blog_rd_time  = ( isset( $settings['blog_rd_time'] ) && ( 'true' == $settings['blog_rd_time'] ) ) ? true : false;
		$blog_command  = ( isset( $settings['blog_command'] ) && ( 'true' == $settings['blog_command'] ) ) ? true : false;
		$blog_author  = ( isset( $settings['blog_author'] ) && ( 'true' == $settings['blog_author'] ) ) ? true : false;

		$blog_style = !empty( $settings['blog_style'] ) ? $settings['blog_style'] : '';
		$blog_col = !empty( $settings['blog_col'] ) ? $settings['blog_col'] : '';
		$blog_limit = !empty( $settings['blog_limit'] ) ? $settings['blog_limit'] : '';
		$blog_order = !empty( $settings['blog_order'] ) ? $settings['blog_order'] : '';
		$blog_orderby = !empty( $settings['blog_orderby'] ) ? $settings['blog_orderby'] : '';
		$blog_show_category = !empty( $settings['blog_show_category'] ) ? $settings['blog_show_category'] : [];
		$blog_show_id = !empty( $settings['blog_show_id'] ) ? $settings['blog_show_id'] : [];
		$short_content = !empty( $settings['short_content'] ) ? $settings['short_content'] : '';
		$blog_pagination  = ( isset( $settings['blog_pagination'] ) && ( 'true' == $settings['blog_pagination'] ) ) ? true : false;
		$read_more_txt = !empty( $settings['read_more_txt'] ) ? $settings['read_more_txt'] : '';

		$read_more_txt = $read_more_txt ? $read_more_txt : esc_html__( 'Read More', 'education-addon' );
		$blog_col = $blog_col ? $blog_col : '3';

		if ($blog_style === 'two') {
			$style_class = ' blog-style-two';
		} else {
			$style_class = '';
		}
  	if ($blog_col === '2') {
			$col_class = 'nich-col-md-6';
		} elseif ($blog_col === '1') {
			$col_class = 'nich-col-md-12';
		} elseif ($blog_col === '4') {
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

    if ($blog_show_id) {
			$blog_show_id = json_encode( $blog_show_id );
			$blog_show_id = str_replace(array( '[', ']' ), '', $blog_show_id);
			$blog_show_id = str_replace(array( '"', '"' ), '', $blog_show_id);
      $blog_show_id = explode(',',$blog_show_id);
    } else {
      $blog_show_id = '';
    }

		$args = array(
		  // other query params here,
		  'paged' => $my_page,
		  'post_type' => 'post',
		  'posts_per_page' => (int)$blog_limit,
		  'category_name' => implode(',', $blog_show_category),
		  'orderby' => $blog_orderby,
		  'order' => $blog_order,
      'post__in' => $blog_show_id,
		);

		$naedu_post = new \WP_Query( $args );
		if ($naedu_post->have_posts()) : ?>
		<div class="naedu-blog<?php echo esc_attr($style_class); ?>">
			<div class="nich-row">

			<?php while ($naedu_post->have_posts()) : $naedu_post->the_post();

			global $post;
		  $large_image =  wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'fullsize', false, '' );
		  $large_image = $large_image[0];
			$cat_list = get_the_category();

		  if ($large_image && $blog_image) {
				$img_cls = '';
			} else {
				$img_cls = ' no-img';
			}
			if ($blog_style === 'two') { ?>
				<div class="<?php echo esc_attr($col_class); ?>">
					<figure>
            <?php if ($large_image && $blog_image) { ?>
					    <div class="naedu-image"><a href="<?php echo esc_url( get_permalink() ); ?>"><img src="<?php echo esc_url($large_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"></a></div>
						<?php } ?>
            <figcaption>
              <div class="blog-posts">
                <?php if ($blog_date) { ?>
                <div class="blog-date"><i class="fas fa-calendar-alt"></i> <span><?php echo esc_attr(get_the_date('M d, Y'));?></span></div>
                <?php } ?>
                <ul class="naedu-meta">
                  <?php if ($blog_command) { ?>
                  	<li><i class="fas fa-comment-dots"></i><span><?php comments_popup_link( esc_html__( '0', 'restaurant-cafe-addon-for-elementor' ), esc_html__( '1', 'restaurant-cafe-addon-for-elementor' ), esc_html__( '%', 'restaurant-cafe-addon-for-elementor' ), '', '' ); ?></span></li>
                  <?php } if (shortcode_exists( 'rt_reading_time' ) && $blog_rd_time) { ?>
                  	<li><i class="fas fa-bolt"></i><span><?php echo do_shortcode('[rt_reading_time postfix="Min" postfix_singular="Min"]'); ?></span></li>
                	<?php } ?>
                </ul>
              </div>
					  	<h3><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo get_the_title(); ?></a></h3>
		        	<?php naedu_excerpt($short_content); ?>
              <a href="<?php echo esc_url( get_permalink() ); ?>" class="naedu-link"><?php echo esc_html($read_more_txt); ?></a>
            </figcaption>
          </figure>
	      </div>
			<?php } else { ?>
				<div class="<?php echo esc_attr($col_class); ?>">
					<figure>
            <?php if ($large_image && $blog_image) { ?>
					    <div class="naedu-image"><a href="<?php echo esc_url( get_permalink() ); ?>"><img src="<?php echo esc_url($large_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"></a></div>
						<?php } ?>
            <figcaption>
					  	<h3><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo get_the_title(); ?></a></h3>
              <div class="blog-auther">
                <?php if ($blog_author) { ?>
                <div class="naedu-avatar">
                  <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ), 39 ); ?></a>
                  <span><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo esc_html(get_the_author()); ?></a></span>
                </div>
              	<?php } ?>
                <ul class="naedu-meta">
                  <?php if ($blog_date) { ?>
                  	<li><i class="fas fa-calendar-alt"></i><span><?php echo esc_attr(get_the_date('M d, Y'));?></span></li>
                  <?php } if (shortcode_exists( 'rt_reading_time' ) && $blog_rd_time) { ?>
                  	<li><i class="fas fa-bolt"></i><span><?php echo do_shortcode('[rt_reading_time postfix="Min" postfix_singular="Min"]'); ?></span></li>
                	<?php } ?>
                </ul>
              </div>
            </figcaption>
          </figure>
				</div>
			<?php } endwhile; ?>
			</div>
		  <?php wp_reset_postdata();
			if ($blog_pagination) { naedu_paging_nav($naedu_post->max_num_pages,"",$paged); }
			?>
		</div>
	  <?php endif;

		// Return outbut buffer
		echo ob_get_clean();

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Education_Elementor_Addon_Blog() );

} // enable & disable
