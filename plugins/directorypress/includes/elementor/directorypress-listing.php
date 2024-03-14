<?php
/**
 * Elementor test Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */

class DirectoryPress_Elementor_Listing_Widget extends \Elementor\Widget_Base {
	public $post_style;
	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		
		add_action('wp_enqueue_scripts', array($this, 'scripts'));
		$this->scripts();
	}
	public function scripts() {
			
			$available_css_files = apply_filters('directorypress_listing_grid_styles', 'directorypress_listing_grid_styles_fuction');  
			if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
				wp_enqueue_style('directorypress_listings');
				foreach($available_css_files as $key=>$style){
					wp_enqueue_style('directorypress_listing_style_'.$key);
					
				}
			}
			
	}
	/**
	 * Get widget name.
	 *
	 * Retrieve oEmbed widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'listings';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve oEmbed widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Listings', 'DIRECTORYPRESS' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve oEmbed widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fas fa-ad';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the oEmbed widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'directorypress' ];
	}

	/**
	 * Register oEmbed widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$ordering = directorypress_sorting_options();
		$directories = directorypress_directorytypes_array_options();
		$categories = directorypress_categories_array_options();
		$locations = directorypress_locations_array_options();
		$packages = directorypress_packages_array_options();
		
		// Setting Section
		$this->start_controls_section(
			'setting_section',
			[
				'label' => __( 'Setting', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'listings_view_type',
			[
				'label' => __( 'Defualt Listing View Type', 'DIRECTORYPRESS' ),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT,
				'multiple' => false,
				'options' => [
					'grid' => __( 'Grid View', 'DIRECTORYPRESS' ),
					'list' => __( 'List View', 'DIRECTORYPRESS' ),
				],
				'default' => 'grid',
			]
		);
		$this->add_control(
			'listing_post_style',
			[
				'label' => __( 'Grid View Style', 'DIRECTORYPRESS' ),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT,
				'multiple' => false,
				'options' => apply_filters("directorypress_listing_grid_styles" , "directorypress_listing_grid_styles_fuction"),
				'default' => 1,
			]
		);
		do_action('directorypress_el_listings_after_post_style_settings', $this);
		$this->add_control(
			'listing_has_featured_tag_style',
			[
				'label' => __( 'Grid View Featured Tag Style', 'DIRECTORYPRESS' ),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT,
				'multiple' => false,
				'options' => apply_filters("directorypress_listing_grid_styles_featured_tags" , "directorypress_listing_grid_styles_featured_tags_function"),
				'default' => 1,
			]
		);
		$this->add_control(
			'onepage',
			[
				'label' => __( 'Show All listings', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT,
				'multiple' => false,
				'options' => [
					'0' => __( 'No', 'DIRECTORYPRESS' ),
					'1' => __( 'Yes', 'DIRECTORYPRESS' ),
				],
				'default' => 0,
			]
		);
		
		$this->add_control(
			'perpage',
			[
				'label' => __( 'Number of Items to show', 'DIRECTORYPRESS' ),
				//'description' => __( 'This option will work only if above option is set to (No)', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'condition' => [
					'onepage' => [ '0' ],
				],
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 10,
			]
		);
		$this->add_responsive_control(
			'listings_view_grid_columns',
			[
				'label' => __( 'Grid View Columns Per Row', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				//'label_block' => true,
				'min' => 1,
				'max' => 6,
				'step' => 1,
				'default' => 4,
			]
		);
		$this->add_control(
			'2col_responsive',
			[
				'label' => __( '2 column mobile view', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT,
				'multiple' => false,
				'options' => [
					'0' => __( 'No', 'DIRECTORYPRESS' ),
					'1' => __( 'Yes', 'DIRECTORYPRESS' ),
				],
				'default' => 0,
			]
		);
		$this->add_control(
			'has_sticky_has_featured',
			[
				'label' => __( 'Show Featured Listing Only', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT,
				'multiple' => false,
				'options' => [
					'0' => __( 'No', 'DIRECTORYPRESS' ),
					'1' => __( 'Yes', 'DIRECTORYPRESS' ),
				],
				'default' => 0,
			]
		);
		$this->add_control(
			'show_views_switcher',
			[
				'label' => __( 'Show Listing View Switcher', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT,
				'multiple' => false,
				'options' => [
					'1' => __( 'Yes', 'DIRECTORYPRESS' ),
					'0' => __( 'No', 'DIRECTORYPRESS' ),
				],
				'default' => 1,
			]
		);
		$this->add_control(
			'hide_order',
			[
				'label' => __( 'Hide Sorting', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT,
				'multiple' => false,
				'options' => [
					'0' => __( 'No', 'DIRECTORYPRESS' ),
					'1' => __( 'Yes', 'DIRECTORYPRESS' ),
				],
				'default' => 0,
			]
		);
		$this->add_control(
			'hide_count',
			[
				'label' => __( 'Hide Listing Count', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT,
				'multiple' => false,
				'options' => [
					'0' => __( 'No', 'DIRECTORYPRESS' ),
					'1' => __( 'Yes', 'DIRECTORYPRESS' ),
				],
				'default' => 0,
			]
		);
		$this->add_control(
			'hide_paginator',
			[
				'label' => __( 'Hide Pagination', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT,
				'multiple' => false,
				'options' => [
					'0' => __( 'No', 'DIRECTORYPRESS' ),
					'1' => __( 'Yes', 'DIRECTORYPRESS' ),
				],
				'default' => 0,
			]
		);
		$this->add_control(
			'scrolling_paginator',
			[
				'label' => __( 'Infinite Scroll', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT,
				'multiple' => false,
				'options' => [
					'0' => __( 'No', 'DIRECTORYPRESS' ),
					'1' => __( 'Yes', 'DIRECTORYPRESS' ),
				],
				'default' => 0,
			]
		);
		$this->add_control(
			'order_by',
			[
				'label' => __( 'Listing Sort By', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT,
				'multiple' => false,
				'options' => $ordering,
				'default' => 'post_date',
			]
		);
		$this->add_control(
			'order',
			[
				'label' => __( 'Order Listing As', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT,
				'multiple' => false,
				'options' => [
					'ASC' => __( 'ASC', 'DIRECTORYPRESS' ),
					'DESC' => __( 'Descending', 'DIRECTORYPRESS' ),
				],
				'default' => 'ASC',
			]
		);
		$this->add_control(
			'listing_order_by_txt',
			[
				'label' => __( 'Order By Text', 'DIRECTORYPRESS' ),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Custom Order By Text', 'DIRECTORYPRESS' ),
			]
		);
		
		$this->end_controls_section(); 
		
		// content section
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'directorytypes',
			[
				'label' => __( 'Select Directory', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $directories,
				'default' => [0],
			]
		);
		$this->add_control(
			'uid',
			[
				'label' => __( 'Unique ID', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'description' => __( 'Insert unique id if you like to connect this module to a specific module like map or search(optional)', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
			]
		);
		$this->add_control(
			'categories',
			[
				'label' => __( 'Select Specific Categories', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $categories,
				'default' => [0],
			]
		);
		$this->add_control(
			'custom_category_link',
			[
				'label' => __( 'Custom Category Link', 'DIRECTORYPRESS' ),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'url to any category', 'DIRECTORYPRESS' ),
			]
		);
		$this->add_control(
			'custom_category_link_text',
			[
				'label' => __( 'Custom Category Link Text', 'DIRECTORYPRESS' ),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Explore Category', 'DIRECTORYPRESS' ),
			]
		);
		$this->add_control(
			'locations',
			[
				'label' => __( 'Select Specific Locations', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $locations,
				'default' => [0],
			]
		);
		$this->add_control(
			'packages',
			[
				'label' => __( 'Select Packages', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $packages,
				'default' => [0],
			]
		);

		$this->end_controls_section();
		
		// Slider
		$this->start_controls_section(
			'slider_section',
			[
				'label' => __( 'Slider', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'scroll',
			[
				'label' => __( 'Turn On Slider', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'0' => __( 'No', 'DIRECTORYPRESS' ),
					'1' => __( 'Yes', 'DIRECTORYPRESS' ),
				],
				'default' => 0,
			]
		);
		$this->add_responsive_control(
			'desktop_items',
			[
				'label' => __( 'Items Per Slide', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				//'label_block' => true,
				'min' => 1,
				'max' => 10,
				'step' => 1,
				'default' => 3,
			]
		);
		$this->add_control(
			'gutter',
			[
				'label' => __( 'Space Between Slides', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				//'label_block' => true,
				'min' => 0,
				'max' => 100,
				'step' => 1,
				'default' => 30,
			]
		);
		$this->add_control(
			'autoplay',
			[
				'label' => __( 'Turn On Autoplay', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'0' => __( 'No', 'DIRECTORYPRESS' ),
					'1' => __( 'Yes', 'DIRECTORYPRESS' ),
				],
				'default' => 0,
			]
		);
		$this->add_control(
			'autoplay_speed',
			[
				'label' => __( 'Autoplay Speed', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				//'label_block' => true,
				'min' => 100,
				'max' => 10000,
				'step' => 100,
				'default' => 1000,
			]
		);
		$this->add_control(
			'loop',
			[
				'label' => __( 'Turn On Loop', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'0' => __( 'No', 'DIRECTORYPRESS' ),
					'1' => __( 'Yes', 'DIRECTORYPRESS' ),
				],
				'default' => 0,
			]
		);
		$this->add_control(
			'owl_nav',
			[
				'label' => __( 'Turn On Navigation', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'0' => __( 'No', 'DIRECTORYPRESS' ),
					'1' => __( 'Yes', 'DIRECTORYPRESS' ),
				],
				'default' => 0,
			]
		);
		$this->add_control(
			'delay',
			[
				'label' => __( 'Delay', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				//'label_block' => true,
				'min' => 100,
				'max' => 10000,
				'step' => 100,
				'default' => 1000,
			]
		);
		
		$this->end_controls_section();
		
		// Style tab and section
		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Style', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'grid_thumb_dimension',
			[
				'label' => __( 'Grid Thumbnail Dimension', 'DIRECTORYPRESS' ),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::IMAGE_DIMENSIONS,
				'description' => __( 'Crop the original image size to any custom size. Set custom width or height to keep the original size ratio.', 'DIRECTORYPRESS' ),
				'default' => [
					'width' => '',
					'height' => '',
				],
			]
		);
		$this->add_control(
			'grid_padding',
			[
				'label' => __( 'Grid Column Gap', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				//'label_block' => true,
				'description' => __( 'Padding would effect grid item left and right, a 15px value means 30px gap between items', 'DIRECTORYPRESS' ),
				'min' => 0,
				'max' => 50,
				'step' => 1,
				'default' => 15,
			]
		);
		$this->add_control(
			'grid_margin_bottom',
			[
				'label' => __( 'Grid Column margin bottom', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .directorypress-listing.listing-grid-item' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'item_title_typography',
				'label' => __( 'Title Typography', 'DIRECTORYPRESS' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .directorypress-listing .directorypress-listing-item-holder .directorypress-listing-text-content-wrap .directorypress-listing-title h2 a',
			]
		);
		$this->start_controls_tabs( 'listing_item_style_tabs' );

		$this->start_controls_tab(
			'listing_item_style_tab_normal',
			array(
				'label' => __( 'Normal', 'DIRECTORYPRESS' ),
			)
		);

		$this->add_control(
			'item_title_color',
			[
				'label' => __( 'Title Color', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} header.directorypress-listing-title' => 'color: {{VALUE}}',
					'{{WRAPPER}} header.directorypress-listing-title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'item_title_background_color',
			array(
				'label' => __( 'Content wrapper Background', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .directorypress-listing-text-content-wrap' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .directorypress-listing-text-content-wrap' => 'background-color: {{VALUE}};',
				),
			)
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'listing_item_style_tab_hover',
			array(
				'label' => __( 'Hover', 'DIRECTORYPRESS' ),
			)
		);

		$this->add_control(
			'item_title_color_hover',
			[
				'label' => __( 'Title Color', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .directorypress-listing-item-holder:hover header.directorypress-listing-title' => 'color: {{VALUE}}',
					'{{WRAPPER}} .directorypress-listing-item-holder:hover header.directorypress-listing-title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'item_title_background_color_hover',
			array(
				'label' => __( 'Content wrapper Background', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .directorypress-listing-item-holder:hover .directorypress-listing-text-content-wrap' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .directorypress-listing-item-holder:hover .directorypress-listing-text-content-wrap' => 'background-color: {{VALUE}};',
				),
			)
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		$this->end_controls_section();
		// Slider Arrows
		$this->start_controls_section(
			'slider_arrow_section',
			[
				'label' => __( 'Slider Arrows', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'slider_arrow_width',
			[
				'label' => esc_html__( 'Width', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 48,
				],
				'selectors' => [
					'{{WRAPPER}} .listing-pre' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .listing-next' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_arrow_height',
			[
				'label' => esc_html__( 'Height', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 48,
				],
				'selectors' => [
					'{{WRAPPER}} .listing-pre' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .listing-next' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'slider_arrow_icon_pre',
			[
				'label' => __( 'Previous Arrow Icon', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-chevron-left',
					'library' => 'solid',
				],
			]
		);
		$this->add_control(
			'slider_arrow_icon_next',
			[
				'label' => __( 'Next Arrow Icon', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-chevron-right',
					'library' => 'solid',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'slider_arrow_typography',
				'label' => __( 'Title Typography', 'DIRECTORYPRESS' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .listing-pre, {{WRAPPER}} .listing-next',
			]
		);
		$this->start_controls_tabs( 'slider_arrow_style' );

		$this->start_controls_tab(
			'slider_arrow_field_normal',
			array(
				'label' => __( 'Normal', 'DIRECTORYPRESS' ),
			)
		);

		$this->add_control(
			'slider_arrow_color',
			[
				'label' => __( 'Icon Color', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .listing-pre' => 'color: {{VALUE}}',
					'{{WRAPPER}} .listing-next' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'slider_arrow_css_filters',
				'selector' => '{{WRAPPER}} .listing-pre, {{WRAPPER}} .listing-next',
			]
		);
		$this->add_control(
			'slider_arrow_background_color',
			array(
				'label' => __( 'Background Color', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .listing-pre' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .listing-next' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'slider_arrow_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'DIRECTORYPRESS' ),
				'selector' => '{{WRAPPER}} .listing-pre, {{WRAPPER}} .listing-next',
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
			'slider_arrow_field_hover',
			array(
				'label' => __( 'Hover', 'DIRECTORYPRESS' ),
			)
		);

		$this->add_control(
			'slider_arrow_color_hover',
			[
				'label' => __( 'Color', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .listing-pre:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .listing-next:hover' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'slider_arrow_background_color_hover',
			array(
				'label' => __( 'Background Color', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .listing-pre:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .listing-next:hover' => 'background-color: {{VALUE}};',
				),
			)
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'slider_arrow_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'DIRECTORYPRESS' ),
				'selector' => '{{WRAPPER}} .listing-pre:hover, {{WRAPPER}} .listing-next:hover',
			]
		);
		
		$this->add_control(
			'slider_arrow_border_color_hover',
			array(
				'label' => __( 'Border Color', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				//'condition' => array(
					//'border_border!' => '',
				//),
				'selectors' => array(
				'{{WRAPPER}} .listing-pre:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .listing-next:hover' => 'border-color: {{VALUE}};',
				),
			)
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'slider_arrow_border',
				'label' => __( 'Border', 'DIRECTORYPRESS' ),
				'selector' => '{{WRAPPER}} .listing-pre, {{WRAPPER}} .listing-next',
			]
		);
		$this->add_control(
			'slider_arrow_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'plugin-name' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .listing-pre' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .listing-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_arrow_padding',
			[
				'label' => __( 'Padding', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .listing-pre' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .listing-next' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_arrow_margin',
			[
				'label' => __( 'Margin', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px'],
				'default' => [
					'top' => '',
					'bottom' => '',
					'left' => '',
					'right' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .listing-pre' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .listing-next' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_arrow_position',
			[
				'label' => esc_html__( 'Position', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'relative'  => esc_html__( 'Relative', 'DIRECTORYPRESS' ),
					'absolute' => esc_html__( 'Absolute', 'DIRECTORYPRESS' ),
					'static' => esc_html__( 'Static', 'DIRECTORYPRESS' ),
				],
				'default' => 'absolute',
			]
		);
		$this->add_responsive_control(
			'slider_pre_arrow_position_top',
			[
				'label' => __( 'Previous Arrow Position Top', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Top', 'DIRECTORYPRESS' ),
				'selectors' => [
					'{{WRAPPER}} .listing-pre' => 'top: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_pre_arrow_position_left',
			[
				'label' => __( 'Previous Arrow Position Left', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Left', 'DIRECTORYPRESS' ),
				'selectors' => [
					'{{WRAPPER}} .listing-pre' => 'left: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_pre_arrow_position_bottom',
			[
				'label' => __( 'Previous Arrow Position Bottom', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Bottom', 'DIRECTORYPRESS' ),
				'selectors' => [
					'{{WRAPPER}} .listing-pre' => 'bottom: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_pre_arrow_position_right',
			[
				'label' => __( 'Previous Arrow Position Right', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Right', 'DIRECTORYPRESS' ),
				'selectors' => [
					'{{WRAPPER}} .listing-pre' => 'right: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_next_arrow_position_top',
			[
				'label' => __( 'Next Arrow Position Top', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Top', 'DIRECTORYPRESS' ),
				'selectors' => [
					'{{WRAPPER}} .listing-next' => 'top: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_next_arrow_position_left',
			[
				'label' => __( 'Next Arrow Position Left', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Left', 'DIRECTORYPRESS' ),
				'selectors' => [
					'{{WRAPPER}} .listing-next' => 'left: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_next_arrow_position_bottom',
			[
				'label' => __( 'Next Arrow Position Bottom', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Bottom', 'DIRECTORYPRESS' ),
				'selectors' => [
					'{{WRAPPER}} .listing-next' => 'bottom: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_next_arrow_position_right',
			[
				'label' => __( 'Next Arrow Position Right', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Position From Right', 'DIRECTORYPRESS' ),
				'selectors' => [
					'{{WRAPPER}} .listing-next' => 'right: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();
	}

	/**
	 * Render oEmbed widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$directorytypes = implode(", ", $settings['directorytypes']);
		$categories = implode(", ", $settings['categories']);
		$locations = implode(", ", $settings['locations']);
		$packages = implode(", ", $settings['packages']);
		//$locations = ($locations != 0)? $locations:'';
		$grid_thumb_width = $settings['grid_thumb_dimension']['width'];
		$grid_thumb_height = $settings['grid_thumb_dimension']['height'];
		
		$desktop = $settings['desktop_items']; // default name is always desktop
		$tablet = (isset($settings['desktop_items_tablet']) && !empty($settings['desktop_items_tablet']))? $settings['desktop_items_tablet'] : 2; // _tablet is added to the tablet value
		$mobile = (isset($settings['desktop_items_mobile']) && !empty($settings['desktop_items_mobile']))? $settings['desktop_items_mobile'] : 1;  // _mobile is added to the _mobile value
		
		$instance = array(
				'directorytypes' => $directorytypes,
				'uid' => $settings['uid'],
				'categories' => $categories,
				'locations' => $locations,
				'packages' => $packages,
				'custom_category_link' => $settings['custom_category_link'],
				'custom_category_link_text' => $settings['custom_category_link_text'],
				'listings_view_type' => $settings['listings_view_type'],
				'listing_post_style' => $settings['listing_post_style'],
				//'listing_post_style_mobile' => $settings['listing_post_style_mobile'],
				'listing_has_featured_tag_style' => $settings['listing_has_featured_tag_style'],
				'grid_padding' => $settings['grid_padding'],
				'onepage' => $settings['onepage'],
				'perpage' => $settings['perpage'],
				'listings_view_grid_columns' =>  $settings['listings_view_grid_columns'],
				'has_sticky_has_featured' => $settings['has_sticky_has_featured'],
				'hide_order' => $settings['hide_order'],
				'hide_count' => $settings['hide_count'],
				'hide_paginator' => $settings['hide_paginator'],
				'scrolling_paginator' => $settings['scrolling_paginator'],
				'show_views_switcher' => $settings['show_views_switcher'],
				'order_by' => $settings['order_by'],
				'order' => $settings['order'],
				'listing_order_by_txt' => $settings['listing_order_by_txt'],
				//'hide_content' => $settings['order'],
				//'author' => $settings['order'],
				'scroll' => $settings['scroll'], 
				'desktop_items' => $desktop, 
				'mobile_items' => $mobile, 
				'tab_items' => $tablet, 
				'autoplay' => $settings['autoplay'], 
				'loop' => $settings['loop'], 
				'owl_nav' => $settings['owl_nav'], 
				'delay' => $settings['delay'] , 
				'autoplay_speed' => $settings['autoplay_speed'], 
				'gutter' => $settings['gutter'], 
				'listing_image_width' => $grid_thumb_width,
				'listing_image_height' => $grid_thumb_height,
				'slider_arrow_position' => $settings['slider_arrow_position'],
				'slider_arrow_icon_pre' => $settings['slider_arrow_icon_pre']['value'],
				'slider_arrow_icon_next' => $settings['slider_arrow_icon_next']['value'],
				'2col_responsive' => $settings['2col_responsive'],
		);
		$instance['custom_settings'] = apply_filters('directorypress_listing_el_custom_settings', $this);
		$directorypress_handler = new directorypress_listings_handler();
		$directorypress_handler->init($instance);

		echo '<div class="directorypress-elementor-listing-widget">';
			echo $directorypress_handler->display(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</div>';
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
		echo '<script>
			( function( $ ) {
				directorypress_slik_init();	
			} )( jQuery );
		</script>';
		}
	}

}