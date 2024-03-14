<?php
/*
 * Elementor Medical Addon for Elementor Blog Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Medical_Elementor_Addon_Blog extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'namedical_basic_blog';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Blog', 'medical-addon-for-elementor' );
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
		return ['namedical-basic-category'];
	}

	/**
	 * Register Medical Addon for Elementor Blog widget controls.
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
      $PostID[ __( 'No ID\'s found', 'medical-addon-for-elementor' ) ] = 0;
    }

    $this->start_controls_section(
			'section_blog_listing',
			[
				'label' => esc_html__( 'Listing Options', 'medical-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'blog_style',
			[
				'label' => esc_html__( 'Blog Style', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one'          => esc_html__('Grid', 'medical-addon-for-elementor'),
					'two'          => esc_html__('List', 'medical-addon-for-elementor'),
				],
				'default' => 'one',
			]
		);
		$this->add_control(
			'blog_col',
			[
				'label' => esc_html__( 'Blog Column', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1'          => esc_html__('One', 'medical-addon-for-elementor'),
					'2'          => esc_html__('Two', 'medical-addon-for-elementor'),
          '3'          => esc_html__('Three', 'medical-addon-for-elementor'),
          '4'          => esc_html__('Four', 'medical-addon-for-elementor'),
				],
				'default' => '3',
				'condition' => [
					'blog_style' => 'one',
				],
			]
		);
		$this->add_control(
			'blog_limit',
			[
				'label' => esc_html__( 'Blog Limit', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 3,
				'description' => esc_html__( 'Enter the number of items to show.', 'medical-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'blog_order',
			[
				'label' => __( 'Order', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'ASC' => esc_html__( 'Asending', 'medical-addon-for-elementor' ),
					'DESC' => esc_html__( 'Desending', 'medical-addon-for-elementor' ),
				],
				'default' => 'DESC',
			]
		);
		$this->add_control(
			'blog_orderby',
			[
				'label' => __( 'Order By', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'medical-addon-for-elementor' ),
					'ID' => esc_html__( 'ID', 'medical-addon-for-elementor' ),
					'author' => esc_html__( 'Author', 'medical-addon-for-elementor' ),
					'title' => esc_html__( 'Title', 'medical-addon-for-elementor' ),
					'date' => esc_html__( 'Date', 'medical-addon-for-elementor' ),
					'name' => esc_html__( 'Name', 'medical-addon-for-elementor' ),
					'modified' => esc_html__( 'Modified', 'medical-addon-for-elementor' ),
					'comment_count' => esc_html__( 'Comment Count', 'medical-addon-for-elementor' ),
				],
				'default' => 'date',
			]
		);
		$this->add_control(
			'blog_show_category',
			[
				'label' => __( 'Certain Categories?', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT2,
				'default' => [],
				'options' => NAMEP_Controls_Helper_Output::get_terms_names( 'category'),
				'multiple' => true,
			]
		);
		$this->add_control(
			'blog_show_id',
			[
				'label' => __( 'Certain ID\'s?', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT2,
				'default' => [],
				'options' => $PostID,
				'multiple' => true,
			]
		);
		$this->add_control(
			'short_content',
			[
				'label' => esc_html__( 'Excerpt Length', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'step' => 1,
				'default' => 15,
				'description' => esc_html__( 'How many words you want in short content paragraph.', 'medical-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'blog_pagination',
			[
				'label' => esc_html__( 'Pagination', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'medical-addon-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'medical-addon-for-elementor' ),
				'return_value' => 'true',
				'default' => 'true',
			]
		);
		$this->add_control(
			'read_more_txt',
			[
				'label' => esc_html__( 'Read More Button Text', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'READ MORE', 'medical-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type text here', 'medical-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'date_format',
			[
				'label' => esc_html__( 'Date Formate', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'description' => __( 'Enter date format (for more info <a href="https://wordpress.org/support/article/formatting-date-and-time/" target="_blank">click here</a>).', 'medical-addon-for-elementor' ),
			]
		);
		$this->end_controls_section();// end: Section

		$this->start_controls_section(
			'section_blog_metas',
			[
				'label' => esc_html__( 'Meta\'s Options', 'medical-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'blog_image',
			[
				'label' => esc_html__( 'Image', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'medical-addon-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'medical-addon-for-elementor' ),
				'return_value' => 'true',
				'default' => 'true',
			]
		);
		$this->add_control(
			'blog_date',
			[
				'label' => esc_html__( 'Date', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'medical-addon-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'medical-addon-for-elementor' ),
				'return_value' => 'true',
				'default' => 'true',
			]
		);
		$this->add_control(
			'blog_author',
			[
				'label' => esc_html__( 'Author', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'medical-addon-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'medical-addon-for-elementor' ),
				'return_value' => 'true',
				'default' => 'true',
			]
		);

		$this->add_responsive_control(
			'section_alignment',
			[
				'label' => esc_html__( 'Alignment', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'medical-addon-for-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'medical-addon-for-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'medical-addon-for-elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .namep-blog-item' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Style
		// Section
		$this->start_controls_section(
			'sectn_style',
			[
				'label' => esc_html__( 'Section', 'medical-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'box_border_radius',
			[
				'label' => __( 'Border Radius', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .namep-blog-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'news_section_margin',
			[
				'label' => __( 'Margin', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .namep-blog-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'news_section_padding',
			[
				'label' => __( 'Padding', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .namep-blog-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->start_controls_tabs( 'secn_style' );
			$this->start_controls_tab(
				'secn_normal',
				[
					'label' => esc_html__( 'Normal', 'medical-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'secn_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-blog-item' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-blog-item',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-blog-item',
				]
			);
			$this->end_controls_tab();  // end:Normal tab

			$this->start_controls_tab(
				'secn_hover',
				[
					'label' => esc_html__( 'Hover', 'medical-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'secn_bg_hover_color',
				[
					'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-blog-item.namep-hover' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_hov_border',
					'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-blog-item.namep-hover',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_hov_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'medical-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .namep-blog-item.namep-hover',
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs

		$this->end_controls_section();// end: Section

		// Title
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'medical-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
				'name' => 'sasstp_title_typography',
				'selector' => '{{WRAPPER}} .namep-blog-item h3, .namep-blog-info h4',
			]
		);
		$this->start_controls_tabs( 'title_style' );
			$this->start_controls_tab(
				'title_normal',
				[
					'label' => esc_html__( 'Normal', 'medical-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'title_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-blog-item h3, {{WRAPPER}} .namep-blog-item h3 a, {{WRAPPER}} .namep-blog-info .namep-blog-title, {{WRAPPER}} .namep-blog-info .namep-blog-title a' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Normal tab
			$this->start_controls_tab(
				'title_hover',
				[
					'label' => esc_html__( 'Hover', 'medical-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'title_hover_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-blog-item h3 a:hover, {{WRAPPER}} .namep-blog-info .namep-blog-title a:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

		// Meta
		$this->start_controls_section(
			'section_meta_style',
			[
				'label' => esc_html__( 'Metas', 'medical-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
				'name' => 'sasstp_meta_typography',
				'selector' => '{{WRAPPER}} .namep-blog-info ul li, {{WRAPPER}} .post-date-wrap .post-date',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography Month', 'medical-addon-for-elementor' ),
				'name' => 'sasstp_date_typography',
				'selector' => '{{WRAPPER}} .post-date-wrap .post-month',
				'condition' => [
					'blog_style' => 'one',
				]
			]
		);
		$this->add_control(
			'meta_sep_color',
			[
				'label' => esc_html__( 'Meta Seperator Color', 'medical-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .namep-blog-info ul li:after' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'blog_style' => 'two',
				]
			]
		);
		$this->start_controls_tabs( 'meta_style' );
			$this->start_controls_tab(
				'meta_normal',
				[
					'label' => esc_html__( 'Normal', 'medical-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'meta_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-blog-info ul li, {{WRAPPER}} .namep-blog-info ul li a, {{WRAPPER}} .post-date-wrap .post-date, {{WRAPPER}} .post-date-wrap .post-month' => 'color: {{VALUE}};',
					]
				],
			);
			$this->add_control(
				'meta_date_bg_color',
				[
					'label' => esc_html__( 'Date Box BG Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .post-date-wrap' => 'background-color: {{VALUE}};',
					],
					'condition' => [
						'blog_style' => 'one',
					]
				]
			);			
			$this->end_controls_tab();  // end:Normal tab
			$this->start_controls_tab(
				'meta_hover',
				[
					'label' => esc_html__( 'Hover', 'medical-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'meta_hover_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-blog-info ul li a:hover, {{WRAPPER}} .namep-blog-item:hover .post-date, {{WRAPPER}} .namep-blog-item:hover .post-month' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'meta_date_hover_bg_color',
				[
					'label' => esc_html__( 'Date Box BG Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-blog-item:hover .post-date-wrap' => 'background-color: {{VALUE}};',
					],
					'condition' => [
						'blog_style' => 'one',
					]
				]
			);			
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section

		// Content
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content', 'medical-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
					'name' => 'content_typography',
					'selector' => '{{WRAPPER}} .namep-blog-item p, {{WRAPPER}} .namep-blog-info p',
				]
			);
			$this->add_control(
				'content_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-blog-item p, {{WRAPPER}} .namep-blog-info p' => 'color: {{VALUE}};',
					],
				]
			);
		$this->end_controls_section();// end: Section

		// Link
		$this->start_controls_section(
			'section_btn_style',
			[
				'label' => esc_html__( 'Link', 'medical-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'medical-addon-for-elementor' ),
				'name' => 'btn_typography',
				'selector' => '{{WRAPPER}} .namep-link',
			]
		);
		$this->start_controls_tabs( 'btn_style' );
			$this->start_controls_tab(
				'btn_normal',
				[
					'label' => esc_html__( 'Normal', 'medical-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'btn_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-link' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();  // end:Normal tab
			$this->start_controls_tab(
				'btn_hover',
				[
					'label' => esc_html__( 'Hover', 'medical-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'btn_hover_color',
				[
					'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-link:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'btn_bg_hover_color',
				[
					'label' => esc_html__( 'Line Color', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .namep-link span:after' => 'background-color: {{VALUE}};',
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
					'label' => esc_html__( 'Pagination', 'medical-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'organizer_pagination' => 'true',
					],
				]
			);
			$this->add_responsive_control(
				'pagi_padding',
				[
					'label' => __( 'Padding', 'medical-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .namep-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'pagi_width',
				[
					'label' => esc_html__( 'Pagination Width', 'medical-addon-for-elementor' ),
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
						'{{WRAPPER}} .namep-pagination ul li span, {{WRAPPER}} .namep-pagination ul li a ' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'pagi_typography',
					'selector' => '{{WRAPPER}} .namep-pagination ul li a, {{WRAPPER}} .namep-pagination ul li span',
				]
			);
			$this->start_controls_tabs( 'pagi_style' );
				$this->start_controls_tab(
					'pagi_normal',
					[
						'label' => esc_html__( 'Normal', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'pagi_color',
					[
						'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-pagination ul li a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'pagi_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-pagination ul li a' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'pagi_border',
						'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .namep-pagination ul li a',
					]
				);
				$this->end_controls_tab();  // end:Normal tab

				$this->start_controls_tab(
					'pagi_hover',
					[
						'label' => esc_html__( 'Hover', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'pagi_hover_color',
					[
						'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-pagination ul li a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'pagi_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-pagination ul li a:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'pagi_hover_border',
						'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .namep-pagination ul li a:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
				$this->start_controls_tab(
					'pagi_active',
					[
						'label' => esc_html__( 'Active', 'medical-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'pagi_active_color',
					[
						'label' => esc_html__( 'Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-pagination ul li span.current' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'pagi_bg_active_color',
					[
						'label' => esc_html__( 'Background Color', 'medical-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .namep-pagination ul li span.current' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'pagi_active_border',
						'label' => esc_html__( 'Border', 'medical-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .namep-pagination ul li span.current',
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
		$date_format = !empty( $settings['date_format'] ) ? $settings['date_format'] : '';

		$blog_col = $blog_col ? $blog_col : '3';

		if ($blog_style === 'two') {
			$style_class = ' namep-blog-style-two';
			$col_class = 'nich-col-md-12';
		} else {
			$style_class = '';
	  	if ($blog_col === '2') {
				$col_class = 'nich-col-md-6';
			} elseif ($blog_col === '1') {
				$col_class = 'nich-col-md-12';
			} elseif ($blog_col === '4') {
				$col_class = 'nich-col-lg-3 nich-col-md-6';
			} else {
				$col_class = 'nich-col-lg-4 nich-col-md-6';
			}
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

		$namedical_post = new \WP_Query( $args );
		if ($namedical_post->have_posts()) : ?>
		<div class="namep-blog-wrap<?php echo esc_attr($style_class); ?>">
			<div class="nich-row">

			<?php while ($namedical_post->have_posts()) : $namedical_post->the_post();

			global $post;
		  $large_image =  wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'fullsize', false, '' );
		  $large_image = ($large_image) ? $large_image[0] : '';

		  if ($large_image && $blog_image) {
				$img_cls = '';
			} else {
				$img_cls = ' no-img';
			}
			$date_format = $date_format ? $date_format : ''; ?>
			<div class="<?php echo esc_attr($col_class); ?>">
				<?php if ($blog_style === 'two') { ?>
					<div class="namep-blog-item<?php echo esc_attr($img_cls); ?>">
            <?php if ($large_image && $blog_image) { ?>
						  <div class="namep-image">
						    <a href="<?php echo esc_url( get_permalink() ); ?>"><img src="<?php echo esc_url($large_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"></a>
						  </div>
						<?php } ?>
            <div class="namep-blog-info">
              <h4 class="namep-blog-title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html(get_the_title()); ?></a></h4>
              <ul>
                <?php if ( $blog_date ) { ?><li><i class="fa fa-clock-o"></i> <?php echo get_the_date(); ?></li><?php } ?>
                <?php if ( $blog_author ) { ?><li><i class="fa fa-user"></i> <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo esc_html(get_the_author()); ?></a></li><?php } ?>
              </ul>
              <?php namedical_excerpt($short_content); ?>
		  				<a href="<?php echo esc_url( get_permalink() ); ?>" class="namep-link"><span><?php echo esc_html($read_more_txt); ?></span> <i class="fa fa-chevron-right"></i></a>
            </div>
          </div>
				<?php } else { ?>
					<div class="namep-blog-item<?php echo esc_attr($img_cls); ?>">
					  <?php if ($large_image && $blog_image) { ?>
						  <div class="namep-image">
						    <a href="<?php echo esc_url( get_permalink() ); ?>"><img src="<?php echo esc_url($large_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"></a>
	              <?php if ( $blog_date ) { ?><div class="post-date-wrap"><span class="post-date"><?php echo esc_attr(get_the_date('j'));?></span> <span class="post-month"><?php echo esc_attr(get_the_date('M'));?></span></div><?php } ?>
						  </div>
						<?php } ?>
					  <div class="namep-blog-info">
					  	<h4 class="namep-blog-title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html(get_the_title()); ?></a></h4>
							<?php namedical_excerpt($short_content); ?>
		  				<a href="<?php echo esc_url( get_permalink() ); ?>" class="namep-link"><span><?php echo esc_html($read_more_txt); ?></span> <i class="fa fa-chevron-right"></i></a>
					  </div>
					</div>
				<?php } ?>
			</div>
			<?php
		  endwhile; ?>
			</div>
		  <?php wp_reset_postdata();
			if ($blog_pagination) { namedical_paging_nav($namedical_post->max_num_pages,"",$paged); } ?>
		</div>
	  <?php endif;

		// Return outbut buffer
		echo ob_get_clean();

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Medical_Elementor_Addon_Blog() );
