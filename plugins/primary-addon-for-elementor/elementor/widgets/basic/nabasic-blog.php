<?php
/*
 * Elementor Primary Addon for Elementor Blog Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'pafe_bw_settings' )['napafe_blog'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Primary_Addon_Blog extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'prim_basic_blog';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Blog', 'primary-addon-for-elementor' );
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
		return ['prim-basic-category'];
	}

	/**
	 * Register Primary Addon for Elementor Blog widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function _register_controls(){

		$posts = get_posts( 'post_type="post"&numberposts=-1' );
    $PostID = array();
    if ( $posts ) {
      foreach ( $posts as $post ) {
        $PostID[ $post->ID ] = $post->ID;
      }
    } else {
      $PostID[ __( 'No ID\'s found', 'primary-addon-for-elementor' ) ] = 0;
    }

    $this->start_controls_section(
			'section_blog_listing',
			[
				'label' => esc_html__( 'Listing Options', 'primary-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'blog_style',
			[
				'label' => esc_html__( 'Blog Style', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one'          => esc_html__('Grid', 'primary-addon-for-elementor'),
					'two'          => esc_html__('List', 'primary-addon-for-elementor'),
					'three'        => esc_html__('Slider', 'primary-addon-for-elementor'),
				],
				'default' => 'one',
			]
		);
		$this->add_control(
			'blog_col',
			[
				'label' => esc_html__( 'Blog Column', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'1'          => esc_html__('One', 'primary-addon-for-elementor'),
					'2'          => esc_html__('Two', 'primary-addon-for-elementor'),
          '3'          => esc_html__('Three', 'primary-addon-for-elementor'),
          '4'          => esc_html__('Four', 'primary-addon-for-elementor'),
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
				'label' => esc_html__( 'Blog Limit', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 3,
				'description' => esc_html__( 'Enter the number of items to show.', 'primary-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'blog_order',
			[
				'label' => __( 'Order', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'ASC' => esc_html__( 'Asending', 'primary-addon-for-elementor' ),
					'DESC' => esc_html__( 'Desending', 'primary-addon-for-elementor' ),
				],
				'default' => 'DESC',
			]
		);
		$this->add_control(
			'blog_orderby',
			[
				'label' => __( 'Order By', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'primary-addon-for-elementor' ),
					'ID' => esc_html__( 'ID', 'primary-addon-for-elementor' ),
					'author' => esc_html__( 'Author', 'primary-addon-for-elementor' ),
					'title' => esc_html__( 'Title', 'primary-addon-for-elementor' ),
					'date' => esc_html__( 'Date', 'primary-addon-for-elementor' ),
					'name' => esc_html__( 'Name', 'primary-addon-for-elementor' ),
					'modified' => esc_html__( 'Modified', 'primary-addon-for-elementor' ),
					'comment_count' => esc_html__( 'Comment Count', 'primary-addon-for-elementor' ),
				],
				'default' => 'date',
			]
		);
		$this->add_control(
			'blog_show_category',
			[
				'label' => __( 'Certain Categories?', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT2,
				'default' => [],
				'options' => NAPAE_Controls_Helper_Output::get_terms_names( 'category'),
				'multiple' => true,
			]
		);
		$this->add_control(
			'blog_show_id',
			[
				'label' => __( 'Certain ID\'s?', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT2,
				'default' => [],
				'options' => $PostID,
				'multiple' => true,
			]
		);
		$this->add_control(
			'short_content',
			[
				'label' => esc_html__( 'Excerpt Length', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'step' => 1,
				'default' => 15,
				'description' => __( 'How many words you want in short content paragraph. <b style="color:#232323;">This field will not work for the content which is entered in Excerpt field of the Post.</b>', 'primary-addon-for-elementor' ),
				'condition' => [
					'blog_style!' => 'three',
				],
			]
		);
		$this->add_control(
			'blog_pagination',
			[
				'label' => esc_html__( 'Pagination', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'primary-addon-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'primary-addon-for-elementor' ),
				'return_value' => 'true',
				'default' => 'true',
				'condition' => [
					'blog_style!' => 'three',
				],
			]
		);
		$this->add_control(
			'read_more_txt',
			[
				'label' => esc_html__( 'Read More Button Text', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => esc_html__( 'Read More', 'primary-addon-for-elementor' ),
				'placeholder' => esc_html__( 'Type text here', 'primary-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'date_format',
			[
				'label' => esc_html__( 'Date Formate', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'description' => __( 'Enter date format (for more info <a href="https://wordpress.org/support/article/formatting-date-and-time/" target="_blank">click here</a>).', 'primary-addon-for-elementor' ),
			]
		);
		$this->end_controls_section();// end: Section

		$this->start_controls_section(
			'section_blog_metas',
			[
				'label' => esc_html__( 'Meta\'s Options', 'primary-addon-for-elementor' ),
				'condition' => [
					'blog_style!' => 'three',
				],
			]
		);
		$this->add_control(
			'blog_image',
			[
				'label' => esc_html__( 'Image', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'primary-addon-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'primary-addon-for-elementor' ),
				'return_value' => 'true',
				'default' => 'true',
			]
		);
		$this->add_control(
			'blog_date',
			[
				'label' => esc_html__( 'Date', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'primary-addon-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'primary-addon-for-elementor' ),
				'return_value' => 'true',
				'default' => 'true',
			]
		);
		$this->add_control(
			'blog_author',
			[
				'label' => esc_html__( 'Author', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'primary-addon-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'primary-addon-for-elementor' ),
				'return_value' => 'true',
				'default' => 'true',
			]
		);

		$this->add_responsive_control(
			'section_alignment',
			[
				'label' => esc_html__( 'Alignment', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'primary-addon-for-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'primary-addon-for-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'primary-addon-for-elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .napae-blog-item' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Carousel Options
			$this->start_controls_section(
				'section_carousel',
				[
					'label' => esc_html__( 'Carousel Options', 'restaurant-elementor-addon' ),
					'condition' => [
						'blog_style' => 'three',
					],
				]
			);
			$this->add_control(
				'draggable',
				[
					'label' => esc_html__( 'Mouse Drag?', 'restaurant-elementor-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'restaurant-elementor-addon' ),
					'label_off' => esc_html__( 'No', 'restaurant-elementor-addon' ),
					'description' => esc_html__( 'Enables dragging and flicking.', 'restaurant-elementor-addon' ),
					'return_value' => 'true',
				]
			);
			$this->add_control(
				'freeScroll',
				[
					'label' => esc_html__( 'Free Scroll?', 'restaurant-elementor-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'restaurant-elementor-addon' ),
					'label_off' => esc_html__( 'No', 'restaurant-elementor-addon' ),
					'description' => esc_html__( 'Enables content to be freely scrolled and flicked without aligning cells to an end position.', 'restaurant-elementor-addon' ),
					'return_value' => 'true',
				]
			);
			$this->add_responsive_control(
				'freeScrollFriction',
				[
					'label' => esc_html__( 'Friction', 'restaurant-elementor-addon' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 0,
					'max' => 100,
					'step' => 0.01,
					'description' => esc_html__( 'Slows the movement of slider.', 'restaurant-elementor-addon' ),
					'condition' => [
						'freeScroll' => 'true',
					],
				]
			);
			$this->add_control(
				'wrapAround',
				[
					'label' => esc_html__( 'Wrap Around?', 'restaurant-elementor-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'restaurant-elementor-addon' ),
					'label_off' => esc_html__( 'No', 'restaurant-elementor-addon' ),
					'description' => esc_html__( 'At the end of cells, wrap-around to the other end for infinite scrolling.', 'restaurant-elementor-addon' ),
					'return_value' => 'true',
					'default' => 'true',
				]
			);
			$this->add_control(
				'groupCells',
				[
					'label' => esc_html__( 'Group Cells', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'placeholder' => esc_html__( 'true, 2, 50%', 'primary-addon-for-elementor' ),
					'description' => __( 'Groups cells together in slides. <b>Eg: true, 2, 50%</b>', 'primary-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'autoPlay',
				[
					'label' => esc_html__( 'Auto Play', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::TEXT,
					'label_block' => true,
					'placeholder' => esc_html__( 'true, 1500', 'primary-addon-for-elementor' ),
					'description' => __( 'Automatically advances to the next cell. <b>Eg: true, 1500</b>', 'primary-addon-for-elementor' ),
				]
			);
			$this->add_control(
				'pauseAutoPlayOnHover',
				[
					'label' => esc_html__( 'Pause Auto Play On Hover?', 'restaurant-elementor-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'restaurant-elementor-addon' ),
					'label_off' => esc_html__( 'No', 'restaurant-elementor-addon' ),
					'description' => esc_html__( 'Auto-playing will pause when the user hovers over the carousel.', 'restaurant-elementor-addon' ),
					'return_value' => 'true',
				]
			);
			$this->add_control(
				'adaptiveHeight',
				[
					'label' => esc_html__( 'Adaptive Height?', 'restaurant-elementor-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'restaurant-elementor-addon' ),
					'label_off' => esc_html__( 'No', 'restaurant-elementor-addon' ),
					'description' => esc_html__( 'Changes height of carousel to fit height of selected slide.', 'restaurant-elementor-addon' ),
					'return_value' => 'true',
					'default' => 'true',
				]
			);
			$this->add_responsive_control(
				'dragThreshold',
				[
					'label' => esc_html__( 'Drag Threshold', 'restaurant-elementor-addon' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 0,
					'max' => 100,
					'step' => 1,
					'description' => esc_html__( 'The number of pixels a mouse or touch has to move before dragging begins.', 'restaurant-elementor-addon' ),
				]
			);
			$this->add_responsive_control(
				'selectedAttraction',
				[
					'label' => esc_html__( 'Selected Attraction', 'restaurant-elementor-addon' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 0,
					'max' => 100,
					'step' => 0.01,
					'description' => esc_html__( 'Attracts the position of the slider to the selected cell.', 'restaurant-elementor-addon' ),
				]
			);
			$this->add_responsive_control(
				'friction',
				[
					'label' => esc_html__( 'Friction', 'restaurant-elementor-addon' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 0,
					'max' => 100,
					'step' => 0.01,
					'description' => esc_html__( 'Slows the movement of slider.', 'restaurant-elementor-addon' ),
				]
			);
			$this->add_responsive_control(
				'initialIndex',
				[
					'label' => esc_html__( 'Initial Index', 'restaurant-elementor-addon' ),
					'type' => Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 100,
					'step' => 1,
					'description' => esc_html__( 'Zero-based index of the initial selected cell.', 'restaurant-elementor-addon' ),
				]
			);
			$this->add_control(
				'accessibility',
				[
					'label' => esc_html__( 'Accessibility?', 'restaurant-elementor-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'restaurant-elementor-addon' ),
					'label_off' => esc_html__( 'No', 'restaurant-elementor-addon' ),
					'description' => esc_html__( 'Enables keyboard navigation.', 'restaurant-elementor-addon' ),
					'return_value' => 'true',
					'default' => 'true',
				]
			);
			$this->add_control(
				'setGallerySize',
				[
					'label' => esc_html__( 'Set Gallery Size?', 'restaurant-elementor-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'restaurant-elementor-addon' ),
					'label_off' => esc_html__( 'No', 'restaurant-elementor-addon' ),
					'description' => esc_html__( 'Sets the height of the carousel to the height of the tallest cell.', 'restaurant-elementor-addon' ),
					'return_value' => 'true',
					'default' => 'true',
				]
			);
			$this->add_control(
				'resize',
				[
					'label' => esc_html__( 'Resize?', 'restaurant-elementor-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'restaurant-elementor-addon' ),
					'label_off' => esc_html__( 'No', 'restaurant-elementor-addon' ),
					'description' => esc_html__( 'Sets the height of the carousel to the height of the tallest cell.', 'restaurant-elementor-addon' ),
					'return_value' => 'true',
					'default' => 'true',
				]
			);
			$this->add_responsive_control(
				'cellAlign',
				[
					'label' => esc_html__( 'Cell Align', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'left' => [
							'title' => esc_html__( 'Left', 'primary-addon-for-elementor' ),
							'icon' => 'fa fa-align-left',
						],
						'center' => [
							'title' => esc_html__( 'Center', 'primary-addon-for-elementor' ),
							'icon' => 'fa fa-align-center',
						],
						'right' => [
							'title' => esc_html__( 'Right', 'primary-addon-for-elementor' ),
							'icon' => 'fa fa-align-right',
						],
					],
					'default' => 'center',
				]
			);
			$this->add_control(
				'contain',
				[
					'label' => esc_html__( 'Contain?', 'restaurant-elementor-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'restaurant-elementor-addon' ),
					'label_off' => esc_html__( 'No', 'restaurant-elementor-addon' ),
					'description' => esc_html__( 'Contains cells to carousel element to prevent excess scroll at beginning or end.', 'restaurant-elementor-addon' ),
					'return_value' => 'true',
				]
			);
			$this->add_control(
				'rightToLeft',
				[
					'label' => esc_html__( 'Right To Left?', 'restaurant-elementor-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'restaurant-elementor-addon' ),
					'label_off' => esc_html__( 'No', 'restaurant-elementor-addon' ),
					'description' => esc_html__( 'Enables right-to-left layout.', 'restaurant-elementor-addon' ),
					'return_value' => 'true',
				]
			);
			$this->add_control(
				'prevNextButtons',
				[
					'label' => esc_html__( 'Prev Next Buttons?', 'restaurant-elementor-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'restaurant-elementor-addon' ),
					'label_off' => esc_html__( 'No', 'restaurant-elementor-addon' ),
					'description' => esc_html__( 'Creates and enables previous & next buttons.', 'restaurant-elementor-addon' ),
					'return_value' => 'true',
					'default' => 'true',
				]
			);
			$this->add_control(
				'pageDots',
				[
					'label' => esc_html__( 'Page Dots?', 'restaurant-elementor-addon' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => esc_html__( 'Yes', 'restaurant-elementor-addon' ),
					'label_off' => esc_html__( 'No', 'restaurant-elementor-addon' ),
					'description' => esc_html__( 'Creates and enables page dots.', 'restaurant-elementor-addon' ),
					'return_value' => 'true',
					'default' => 'true',
				]
			);
			$this->end_controls_section();// end: Section

		// Section
			$this->start_controls_section(
				'sectn_style',
				[
					'label' => esc_html__( 'Section', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'box_border_radius',
				[
					'label' => __( 'Border Radius', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .napae-blog-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'news_section_margin',
				[
					'label' => __( 'Margin', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .napae-blog-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'news_section_padding',
				[
					'label' => __( 'Padding', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .napae-blog-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'secn_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .napae-blog-item, {{WRAPPER}} .napae-blog-style-three .napae-blog-item.is-selected:after, {{WRAPPER}} .napae-blog-style-three .napae-blog-info' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'secn_border',
					'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .napae-blog-item',
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'secn_box_shadow',
					'label' => esc_html__( 'Section Box Shadow', 'primary-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .napae-blog-item',
				]
			);
			$this->end_controls_section();// end: Section

		// Title
			$this->start_controls_section(
				'section_title_style',
				[
					'label' => esc_html__( 'Title', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'primary-addon-for-elementor' ),
					'name' => 'sasstp_title_typography',
					'selector' => '{{WRAPPER}} .napae-blog-item h4',
				]
			);
			$this->start_controls_tabs( 'title_style' );
				$this->start_controls_tab(
					'title_normal',
					[
						'label' => esc_html__( 'Normal', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'title_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-blog-item h4, {{WRAPPER}} .napae-blog-item h4 a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'title_hover',
					[
						'label' => esc_html__( 'Hover', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'title_hover_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-blog-item h4 a:hover' => 'color: {{VALUE}};',
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
					'label' => esc_html__( 'Category', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'blog_style' => 'three',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'primary-addon-for-elementor' ),
					'name' => 'sasstp_cat_typography',
					'selector' => '{{WRAPPER}} .blog-cats a',
				]
			);
			$this->start_controls_tabs( 'cat_style' );
				$this->start_controls_tab(
					'cat_normal',
					[
						'label' => esc_html__( 'Normal', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'cat_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .blog-cats a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'cat_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .blog-cats a' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'cat_hover',
					[
						'label' => esc_html__( 'Hover', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'cat_hover_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .blog-cats a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'cat_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .blog-cats a:hover' => 'background-color: {{VALUE}};',
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
					'label' => esc_html__( 'Date', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'blog_style' => 'one',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Date Typography', 'primary-addon-for-elementor' ),
					'name' => 'date_typography',
					'selector' => '{{WRAPPER}} .post-date',
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Month Typography', 'primary-addon-for-elementor' ),
					'name' => 'month_typography',
					'selector' => '{{WRAPPER}} .post-month',
				]
			);
			$this->add_control(
				'date_color',
				[
					'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .post-date-wrap' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'date_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .post-date-wrap' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_section();// end: Section

		// Meta
			$this->start_controls_section(
				'section_meta_style',
				[
					'label' => esc_html__( 'Metas', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'primary-addon-for-elementor' ),
					'name' => 'sasstp_meta_typography',
					'selector' => '{{WRAPPER}} .napae-blog-info ul li, {{WRAPPER}} .napae-blog-style-three .napae-blog-info ul li',
				]
			);
			$this->add_control(
				'meta_sep_color',
				[
					'label' => esc_html__( 'Meta Seperator Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .napae-blog-info ul li:after' => 'color: {{VALUE}};',
					],
				]
			);
			$this->start_controls_tabs( 'meta_style' );
				$this->start_controls_tab(
					'meta_normal',
					[
						'label' => esc_html__( 'Normal', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'meta_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-blog-info ul li, {{WRAPPER}} .napae-blog-info ul li a, {{WRAPPER}} .napae-blog-style-three .napae-blog-info ul li' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'meta_hover',
					[
						'label' => esc_html__( 'Hover', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'meta_hover_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-blog-info ul li a:hover, {{WRAPPER}} .napae-blog-style-three .napae-blog-info ul li a:hover' => 'color: {{VALUE}};',
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
					'label' => esc_html__( 'Content', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'label' => esc_html__( 'Typography', 'primary-addon-for-elementor' ),
						'name' => 'content_typography',
						'selector' => '{{WRAPPER}} .napae-blog-item p',
					]
				);
				$this->add_control(
					'content_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-blog-item p' => 'color: {{VALUE}};',
						],
					]
				);
			$this->end_controls_section();// end: Section

		// Link
			$this->start_controls_section(
				'section_btn_style',
				[
					'label' => esc_html__( 'Link', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'label' => esc_html__( 'Typography', 'primary-addon-for-elementor' ),
					'name' => 'btn_typography',
					'selector' => '{{WRAPPER}} .napae-link',
				]
			);
			$this->start_controls_tabs( 'btn_style' );
				$this->start_controls_tab(
					'btn_normal',
					[
						'label' => esc_html__( 'Normal', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'btn_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-link' => 'color: {{VALUE}};',
						],
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'btn_hover',
					[
						'label' => esc_html__( 'Hover', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'btn_hover_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-link:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'btn_bg_hover_color',
					[
						'label' => esc_html__( 'Line Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-link span:after' => 'background-color: {{VALUE}};',
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
					'label' => esc_html__( 'Pagination', 'primary-addon-for-elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'blog_pagination' => 'true',
						'blog_style!' => 'three',
					],
				]
			);
			$this->add_responsive_control(
				'pagi_padding',
				[
					'label' => __( 'Padding', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .napae-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'pagi_width',
				[
					'label' => esc_html__( 'Pagination Width', 'primary-addon-for-elementor' ),
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
						'{{WRAPPER}} .napae-pagination ul li span, {{WRAPPER}} .napae-pagination ul li a ' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'pagi_typography',
					'selector' => '{{WRAPPER}} .napae-pagination ul li a, {{WRAPPER}} .napae-pagination ul li span',
				]
			);
			$this->start_controls_tabs( 'pagi_style' );
				$this->start_controls_tab(
					'pagi_normal',
					[
						'label' => esc_html__( 'Normal', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'pagi_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-pagination ul li a' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'pagi_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-pagination ul li a' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'pagi_border',
						'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .napae-pagination ul li a',
					]
				);
				$this->end_controls_tab();  // end:Normal tab
				$this->start_controls_tab(
					'pagi_hover',
					[
						'label' => esc_html__( 'Hover', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'pagi_hover_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-pagination ul li a:hover' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'pagi_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-pagination ul li a:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'pagi_hover_border',
						'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .napae-pagination ul li a:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab
				$this->start_controls_tab(
					'pagi_active',
					[
						'label' => esc_html__( 'Active', 'primary-addon-for-elementor' ),
					]
				);
				$this->add_control(
					'pagi_active_color',
					[
						'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-pagination ul li span.current' => 'color: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'pagi_bg_active_color',
					[
						'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .napae-pagination ul li span.current' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'pagi_active_border',
						'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
						'selector' => '{{WRAPPER}} .napae-pagination ul li span.current',
					]
				);
				$this->end_controls_tab();  // end:Active tab
			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Navigation
			$this->start_controls_section(
				'section_navigation_style',
				[
					'label' => esc_html__( 'Navigation', 'event-elementor-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'prevNextButtons' => 'true',
						'blog_style' => 'three',
					],
					'frontend_available' => true,
				]
			);
			$this->add_responsive_control(
				'arrow_size',
				[
					'label' => esc_html__( 'Size', 'event-elementor-addon' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 42,
							'max' => 1000,
							'step' => 1,
						],
					],
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .flickity-prev-next-button' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->start_controls_tabs( 'nav_arrow_style' );
				$this->start_controls_tab(
					'nav_arrow_normal',
					[
						'label' => esc_html__( 'Normal', 'event-elementor-addon' ),
					]
				);
				$this->add_control(
					'nav_arrow_color',
					[
						'label' => esc_html__( 'Color', 'event-elementor-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .flickity-prev-next-button .flickity-button-icon path' => 'fill: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'nav_arrow_bg_color',
					[
						'label' => esc_html__( 'Background Color', 'event-elementor-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .flickity-prev-next-button' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'nav_border',
						'label' => esc_html__( 'Border', 'event-elementor-addon' ),
						'selector' => '{{WRAPPER}} .flickity-prev-next-button',
					]
				);
				$this->end_controls_tab();  // end:Normal tab

				$this->start_controls_tab(
					'nav_arrow_hover',
					[
						'label' => esc_html__( 'Hover', 'event-elementor-addon' ),
					]
				);
				$this->add_control(
					'nav_arrow_hov_color',
					[
						'label' => esc_html__( 'Color', 'event-elementor-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .flickity-prev-next-button:hover .flickity-button-icon path' => 'fill: {{VALUE}};',
						],
					]
				);
				$this->add_control(
					'nav_arrow_bg_hover_color',
					[
						'label' => esc_html__( 'Background Color', 'event-elementor-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .flickity-prev-next-button:hover' => 'background-color: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'nav_active_border',
						'label' => esc_html__( 'Border', 'event-elementor-addon' ),
						'selector' => '{{WRAPPER}} .flickity-prev-next-button:hover',
					]
				);
				$this->end_controls_tab();  // end:Hover tab

			$this->end_controls_tabs(); // end tabs
			$this->end_controls_section();// end: Section

		// Dots
			$this->start_controls_section(
				'section_dots_style',
				[
					'label' => esc_html__( 'Dots', 'event-elementor-addon' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'condition' => [
						'pageDots' => 'true',
						'blog_style' => 'three',
					],
					'frontend_available' => true,
				]
			);
			$this->add_responsive_control(
				'dots_size',
				[
					'label' => esc_html__( 'Size', 'event-elementor-addon' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1000,
							'step' => 1,
						],
					],
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .flickity-page-dots .dot' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}}',
					],
				]
			);
			$this->add_responsive_control(
				'dots_margin',
				[
					'label' => __( 'Margin', 'event-elementor-addon' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%', 'em' ],
					'selectors' => [
						'{{WRAPPER}} .flickity-page-dots .dot' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->start_controls_tabs( 'dots_style' );
				$this->start_controls_tab(
					'dots_normal',
					[
						'label' => esc_html__( 'Normal', 'event-elementor-addon' ),
					]
				);
				$this->add_control(
					'dots_color',
					[
						'label' => esc_html__( 'Background Color', 'event-elementor-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .flickity-page-dots .dot' => 'background: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'dots_border',
						'label' => esc_html__( 'Border', 'event-elementor-addon' ),
						'selector' => '{{WRAPPER}} .flickity-page-dots .dot',
					]
				);
				$this->end_controls_tab();  // end:Normal tab

				$this->start_controls_tab(
					'dots_active',
					[
						'label' => esc_html__( 'Active', 'event-elementor-addon' ),
					]
				);
				$this->add_control(
					'dots_active_color',
					[
						'label' => esc_html__( 'Background Color', 'event-elementor-addon' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .flickity-page-dots .dot.is-selected' => 'background: {{VALUE}};',
						],
					]
				);
				$this->add_group_control(
					Group_Control_Border::get_type(),
					[
						'name' => 'dots_active_border',
						'label' => esc_html__( 'Border', 'event-elementor-addon' ),
						'selector' => '{{WRAPPER}} .flickity-page-dots .dot.is-selected',
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

		// Carousel
			$draggable = !empty( $settings['draggable'] ) ? $settings['draggable'] : '';
			$freeScroll = !empty( $settings['freeScroll'] ) ? $settings['freeScroll'] : '';
			$freeScrollFriction = !empty( $settings['freeScrollFriction'] ) ? $settings['freeScrollFriction'] : '';
			$wrapAround = !empty( $settings['wrapAround'] ) ? $settings['wrapAround'] : '';
			$groupCells = !empty( $settings['groupCells'] ) ? $settings['groupCells'] : '';
			$autoPlay = !empty( $settings['autoPlay'] ) ? $settings['autoPlay'] : '';
			$pauseAutoPlayOnHover = !empty( $settings['pauseAutoPlayOnHover'] ) ? $settings['pauseAutoPlayOnHover'] : '';
			$adaptiveHeight = !empty( $settings['adaptiveHeight'] ) ? $settings['adaptiveHeight'] : '';
			$dragThreshold = !empty( $settings['dragThreshold'] ) ? $settings['dragThreshold'] : '';
			$selectedAttraction = !empty( $settings['selectedAttraction'] ) ? $settings['selectedAttraction'] : '';
			$friction = !empty( $settings['friction'] ) ? $settings['friction'] : '';
			$initialIndex = !empty( $settings['initialIndex'] ) ? $settings['initialIndex'] : '';
			$accessibility = !empty( $settings['accessibility'] ) ? $settings['accessibility'] : '';
			$setGallerySize = !empty( $settings['setGallerySize'] ) ? $settings['setGallerySize'] : '';
			$resize = !empty( $settings['resize'] ) ? $settings['resize'] : '';
			$cellAlign = !empty( $settings['cellAlign'] ) ? $settings['cellAlign'] : '';
			$contain = !empty( $settings['contain'] ) ? $settings['contain'] : '';
			$rightToLeft = !empty( $settings['rightToLeft'] ) ? $settings['rightToLeft'] : '';
			$prevNextButtons = !empty( $settings['prevNextButtons'] ) ? $settings['prevNextButtons'] : '';
			$pageDots = !empty( $settings['pageDots'] ) ? $settings['pageDots'] : '';

		// Carousel Data's
			$draggable = $draggable ? ' data-draggable="true"' : ' data-draggable="false"';
			$freeScroll = $freeScroll ? ' data-freescroll="true"' : '';
			$freeScrollFriction = $freeScrollFriction ? ' data-freescrollfriction="'.$freeScrollFriction.'"' : '';
			$wrapAround = $wrapAround ? ' data-wraparound="true"' : ' data-wraparound="false"';
			$groupCells = $groupCells ? ' data-groupcells="'.$groupCells.'"' : '';
			$autoPlay = $autoPlay ? ' data-autoplay="'.$autoPlay.'"' : '';
			$pauseAutoPlayOnHover = $pauseAutoPlayOnHover ? ' data-pauseautoplayonhover="true"' : '';
			$adaptiveHeight = $adaptiveHeight ? ' data-adaptiveheight="true"' : '';
			$dragThreshold = $dragThreshold ? ' data-dragthreshold="'.$dragThreshold.'"' : '';
			$selectedAttraction = $selectedAttraction ? ' data-selectedattraction="'.$selectedAttraction.'"' : '';
			$friction = $friction ? ' data-friction="'.$friction : '';
			$initialIndex = $initialIndex ? ' data-initialindex="'.$initialIndex.'"' : '';
			$accessibility = $accessibility ? ' data-accessibility="true"' : ' data-accessibility="false"';
			$setGallerySize = $setGallerySize ? ' data-setgallerysize="true"' : ' data-setgallerysize="false"';
			$resize = $resize ? ' data-resize="true"' : ' data-resize="false"';
			$cellAlign = $cellAlign ? ' data-cellalign="'.$cellAlign.'"' : '';
			$contain = $contain ? ' data-contain="true"' : '';
			$rightToLeft = $rightToLeft ? ' data-righttoleft="true"' : '';
			$prevNextButtons = $prevNextButtons ? ' data-prevnextbuttons="true"' : ' data-prevnextbuttons="false"';
			$pageDots = $pageDots ? ' data-pagedots="true"' : ' data-pagedots="false"';

		$blog_col = $blog_col ? $blog_col : '3';

		if ($blog_style === 'three') {
			$style_class = ' napae-blog-style-three';
			$col_class = '';
		} elseif ($blog_style === 'two') {
			$style_class = ' napae-blog-style-two';
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

		$prim_post = new \WP_Query( $args );
		if ($prim_post->have_posts()) : ?>
		<div class="napae-blog-wrap<?php echo esc_attr($style_class); ?>">
			<?php if ($blog_style === 'three') { ?>
			<div class="flick-carousel" <?php echo $cellAlign . $draggable . $freeScroll . $freeScrollFriction . $wrapAround . $groupCells . $autoPlay . $pauseAutoPlayOnHover . $adaptiveHeight . $dragThreshold . $selectedAttraction . $friction . $initialIndex . $accessibility . $setGallerySize . $resize . $contain . $rightToLeft . $prevNextButtons . $pageDots; ?>>
			<?php } else { ?>
			<div class="nich-row">
			<?php } ?>

			<?php while ($prim_post->have_posts()) : $prim_post->the_post();

			global $post;
		  $large_image =  wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'fullsize', false, '' );
		  $large_image = $large_image[0];
			$cat_list = get_the_category();

		  if ($large_image && $blog_image) {
				$img_cls = '';
			} else {
				$img_cls = ' no-img';
			}
			$date_format = $date_format ? $date_format : ''; ?>
			<?php if ($blog_style === 'three') { ?>
				<div class="napae-blog-item<?php echo esc_attr($img_cls); ?>" style="background-image: url(<?php echo esc_url($large_image); ?>);">
          <div class="napae-blog-info">
          	<div class="napae-blog-info-wrap">
	          	<?php if ( $cat_list ) { ?>
				        <div class="blog-cats">
					        <?php
							    $categories = get_the_category();
					        foreach ( $categories as $category ) : ?>
					            <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
					        <?php endforeach; ?>
				        </div>
					      <?php } ?>
	            <h4 class="napae-blog-title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html(get_the_title()); ?></a></h4>
		  				<a href="<?php echo esc_url( get_permalink() ); ?>" class="napae-link"><span><?php echo esc_html($read_more_txt); ?></span> <i class="fa fa-arrow-right"></i></a>
		  			</div>
            <ul>
              <li><i class="fa fa-clock-o"></i> <?php echo get_the_date(); ?></li>
              <li><i class="fa fa-user"></i> <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo esc_html(get_the_author()); ?></a></li>
              <li><i class="fa fa-commenting"></i> <?php comments_popup_link( esc_html__( '0', 'primary-addon-for-elementor' ), esc_html__( '1', 'primary-addon-for-elementor' ), esc_html__( '%', 'primary-addon-for-elementor' ), '', '' ); ?></li>
            </ul>
          </div>
        </div>
			<?php } else { ?>
				<div class="<?php echo esc_attr($col_class); ?>">
					<?php if ($blog_style === 'two') { ?>
						<div class="napae-blog-item<?php echo esc_attr($img_cls); ?>">
	            <?php if ($large_image && $blog_image) { ?>
							  <div class="napae-image">
							    <a href="<?php echo esc_url( get_permalink() ); ?>"><img src="<?php echo esc_url($large_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"></a>
							  </div>
							<?php } ?>
	            <div class="napae-blog-info">
	              <h4 class="napae-blog-title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html(get_the_title()); ?></a></h4>
	              <ul>
	                <?php if ( $blog_date ) { ?><li><i class="fa fa-clock-o"></i> <?php echo get_the_date(); ?></li><?php } ?>
	                <?php if ( $blog_author ) { ?><li><i class="fa fa-user"></i> <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo esc_html(get_the_author()); ?></a></li><?php } ?>
	              </ul>
	              <?php prim_excerpt($short_content); ?>
			  				<a href="<?php echo esc_url( get_permalink() ); ?>" class="napae-link"><span><?php echo esc_html($read_more_txt); ?></span> <i class="fa fa-arrow-right"></i></a>
	            </div>
	          </div>
					<?php } else { ?>
						<div class="napae-blog-item<?php echo esc_attr($img_cls); ?>">
						  <?php if ($large_image && $blog_image) { ?>
							  <div class="napae-image">
							    <a href="<?php echo esc_url( get_permalink() ); ?>"><img src="<?php echo esc_url($large_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"></a>
		              <?php if ( $blog_date ) { ?><div class="post-date-wrap"><span class="post-date"><?php echo esc_attr(get_the_date('j'));?></span> <span class="post-month"><?php echo esc_attr(get_the_date('M'));?></span></div><?php } ?>
							  </div>
							<?php } ?>
						  <div class="napae-blog-info">
						  	<h4 class="napae-blog-title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html(get_the_title()); ?></a></h4>
								<?php prim_excerpt($short_content); ?>
			  				<a href="<?php echo esc_url( get_permalink() ); ?>" class="napae-link"><span><?php echo esc_html($read_more_txt); ?></span> <i class="fa fa-arrow-right"></i></a>
						  </div>
						</div>
					<?php } ?>
				</div>
			<?php } endwhile; ?>
			</div>
		  <?php wp_reset_postdata();
		  if ($blog_style !== 'three') {
				if ($blog_pagination) { prim_paging_nav($prim_post->max_num_pages,"",$paged); }
			}	?>
		</div>
	  <?php endif;

		// Return outbut buffer
		echo ob_get_clean();

	}

}
Plugin::instance()->widgets_manager->register_widget_type( new Primary_Addon_Blog() );

} // enable & disable
