<?php
/*
 * Elementor Primary Addon for Woo Carousel Widget
 * Author & Copyright: NicheAddon
*/

namespace Elementor;

if (!isset(get_option( 'pafe_bw_settings' )['napafe_woo_grid'])) { // enable & disable

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Primary_Addon_Woo_Grid extends Widget_Base{

	/**
	 * Retrieve the widget name.
	*/
	public function get_name(){
		return 'prim_basic_woo_grid';
	}

	/**
	 * Retrieve the widget title.
	*/
	public function get_title(){
		return esc_html__( 'Product Grid', 'primary-addon-for-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	*/
	public function get_icon() {
		return 'eicon-products';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_categories() {
		return ['prim-basic-category'];
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	*/
	public function get_woo_categories() {
		$output = [];
		$categories = get_terms( ['taxonomy' => 'product_cat'] );
		if(!is_array($categories)) return $output;
		foreach ($categories as $key => $category) {
			$output[$category->term_id] = $category->name;
		}
		return $output;
	}

	/**
	 * Register Events Addon for Elementor Unique Upcoming widget controls.
	 * Adds different input fields to allow the user to change and customize the widget settings.
	*/
	protected function _register_controls(){

		$this->start_controls_section(
			'section_woo_carousel_settings',
			[
				'label' => esc_html__( 'Options', 'primary-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'woo_grid_style',
			[
				'label' => esc_html__( 'Style', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'one' => esc_html__( 'Style One', 'primary-addon-for-elementor' ),
					'two' => esc_html__( 'Style Two', 'primary-addon-for-elementor' ),
					'three' => esc_html__( 'Style Three', 'primary-addon-for-elementor' ),
					'four' => esc_html__( 'Style Four', 'primary-addon-for-elementor' ),
				],
				'default' => 'one',
				'description' => esc_html__( 'Select woocommerce layout style.', 'primary-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'posts_per_page',
			[
				'label' => esc_html__( 'Product Limit', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '9', 'primary-addon-for-elementor' ),
				'label_block' => true,
				'description' => esc_html__( 'Leave empty to get all products.', 'primary-addon-for-elementor' ),
			]
		);

		$this->add_control(
			'content_limit',
			[
				'label' => esc_html__( 'Description Word Limit', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '12', 'primary-addon-for-elementor' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'is_label',
			[
				'label' => esc_html__( 'Show Label?', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'primary-addon-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'primary-addon-for-elementor' ),
				'return_value' => 'true',
				'default' => 'true',
			]
		);

		$this->add_control(
			'is_price',
			[
				'label' => esc_html__( 'Show Price?', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'primary-addon-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'primary-addon-for-elementor' ),
				'return_value' => 'true',
				'default' => 'true',
			]
		);

		$this->add_control(
			'is_rating',
			[
				'label' => esc_html__( 'Show Rating?', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'primary-addon-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'primary-addon-for-elementor' ),
				'return_value' => 'true',
				'default' => 'true',
			]
		);

		$this->add_control(
			'is_details',
			[
				'label' => esc_html__( 'Show Short Details?', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'primary-addon-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'primary-addon-for-elementor' ),
				'return_value' => 'true',
				'default' => 'true',
			]
		);

		$this->add_control(
			'is_button',
			[
				'label' => esc_html__( 'Show Button?', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'primary-addon-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'primary-addon-for-elementor' ),
				'return_value' => 'true',
				'default' => 'true',
			]
		);

		$this->add_control(
			'is_pagination',
			[
				'label' => esc_html__( 'Show Pagination?', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'primary-addon-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'primary-addon-for-elementor' ),
				'return_value' => 'true',
				'default' => 'false',
			]
		);

		$this->end_controls_section();// end: Section

		/**
		 * Query 
		 */
		$this->start_controls_section(
			'section_eventbrite_query',
			[
				'label' => esc_html__( 'Query', 'primary-addon-for-elementor' ),
			]
		);
		$this->add_control(
			'query_source',
			[
				'label' => esc_html__( 'Source', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'product',
				'options' => [
					'product' => esc_html__( 'Latest Products', 'primary-addon-for-elementor' ),
					'sale' => esc_html__( 'Sale', 'primary-addon-for-elementor' ),
					'featured' => esc_html__( 'Featured', 'primary-addon-for-elementor' ),
					'by_id' => _x( 'By IDs', 'Posts Query Control', 'primary-addon-for-elementor' ),
				]
			]
		);
		$this->add_control(
			'categories',
			[
				'label' => esc_html__( 'Categories', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $this->get_woo_categories()
			]
		);
		$this->add_control(
			'orderby',
			[
				'label' => esc_html__( 'Order By', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date' => esc_html__( 'Date', 'primary-addon-for-elementor' ),
					'title' => esc_html__( 'Title', 'primary-addon-for-elementor' ),
					'price' => esc_html__( 'Price', 'primary-addon-for-elementor' ),
					'popularity' => esc_html__( 'Popularity', 'primary-addon-for-elementor' ),
					'rating' => esc_html__( 'Rating', 'primary-addon-for-elementor' ),
					'rand' => esc_html__( 'Random', 'primary-addon-for-elementor' ),
					'menu_order' => esc_html__( 'Menu Order', 'primary-addon-for-elementor' ),
				]
			]
		);
		$this->add_control(
			'order',
			[
				'label' => esc_html__( 'Order', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'DESC' => esc_html__( 'DESC', 'primary-addon-for-elementor' ),
					'ASC' => esc_html__( 'ASC', 'primary-addon-for-elementor' )
				]
			]
		);
		$this->end_controls_section();// end: Section
		
		/**
		 * Style
		 */
		$this->start_controls_section(
			'sectn_style',
			[
				'label' => esc_html__( 'Section', 'primary-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'secn_margin',
			[
				'label' => esc_html__( 'Section Spacing', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .napae-woo-product-single' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'secn_padding',
			[
				'label' => esc_html__( 'Section Padding', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .napae-woo-product-single' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'secn_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .napae-woo-product-single' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'secn_border',
				'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .napae-woo-product-single',
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'secn_box_shadow',
				'label' => esc_html__( 'Section Box Shadow', 'primary-addon-for-elementor' ),
				'selector' => '{{WRAPPER}} .napae-woo-product-single',
			]
		);
		$this->end_controls_section();// end: Section

		// Image
		$this->start_controls_section(
			'section_image_style',
			[
				'label' => esc_html__( 'Image', 'primary-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'img_height',
			[
				'label' => esc_html__( 'Image Height', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 200,
						'max' => 500,
						'step' => 10,
					]
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .napae-woo-product-image' => 'min-height:{{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section(); // end: Section

		// Label
		$this->start_controls_section(
			'section_label_style',
			[
				'label' => esc_html__( 'Label', 'primary-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'label_maring',
			[
				'label' => esc_html__( 'Padding', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .napae-woo-product-image .napae-onsale' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'selector' => '{{WRAPPER}} .napae-woo-product-image .napae-onsale',
			]
		);
		$this->add_control(
			'label_color',
			[
				'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .napae-woo-product-image .napae-onsale' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'label_bg_color',
			[
				'label' => esc_html__( 'BG Color', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .napae-woo-product-image .napae-onsale' => 'background: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section(); // end: Section

		// Title
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'primary-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'title_margin',
			[
				'label' => esc_html__( 'Margin', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .napae-woo-product-title, {{WRAPPER}} .napae-woo-product-title a, {{WRAPPER}} .napae-woo-product-title a:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .napae-woo-product-title, {{WRAPPER}} .napae-woo-product-title a, {{WRAPPER}} .napae-woo-product-title a:hover',
			]
		);
		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .napae-woo-product-title, {{WRAPPER}} .napae-woo-product-title a, {{WRAPPER}} .napae-woo-product-title a:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Price
		$this->start_controls_section(
			'section_price_style',
			[
				'label' => esc_html__( 'Price', 'primary-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'price_margin',
			[
				'label' => esc_html__( 'Margin', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .napae-woo-product-product-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_typography',
				'selector' => '{{WRAPPER}} .napae-woo-product-product-price',
			]
		);
		$this->add_control(
			'price_color',
			[
				'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .napae-woo-product-product-price' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Content
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content', 'primary-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__( 'Padding', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .napae-woo-product-details' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .napae-woo-product-details',
			]
		);
		$this->add_control(
			'content_color',
			[
				'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .napae-woo-product-details' => 'color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();// end: Section

		// Button
		$this->start_controls_section(
			'section_btn_style',
			[
				'label' => esc_html__( 'Button', 'primary-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'btn_padding',
			[
				'label' => esc_html__( 'Padding', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .napae-woo-product-button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'btn_margin',
			[
				'label' => esc_html__( 'Padding', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .napae-woo-product-button a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'btn_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .napae-woo-product-button a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'btn_width',
			[
				'label' => esc_html__( 'Button Width', 'primary-addon-for-elementor' ),
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
					'{{WRAPPER}} .napae-woo-product-button a' => 'width:{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', 'primary-addon-for-elementor' ),
				'name' => 'btn_typography',
				'selector' => '{{WRAPPER}} .napae-woo-product-button a',
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
						'{{WRAPPER}} .napae-woo-product-button a' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'btn_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .napae-woo-product-button a' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'btn_border',
					'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .napae-woo-product-button a',
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
						'{{WRAPPER}} .napae-woo-product-button a:hover' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'btn_bg_hover_color',
				[
					'label' => esc_html__( 'Background Color', 'primary-addon-for-elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .napae-woo-product-button a:hover' => 'background-color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'btn_hover_border',
					'label' => esc_html__( 'Border', 'primary-addon-for-elementor' ),
					'selector' => '{{WRAPPER}} .napae-woo-product-button a:hover',
				]
			);
			$this->end_controls_tab();  // end:Hover tab
		$this->end_controls_tabs(); // end tabs
		$this->end_controls_section();// end: Section
		
		// Pagination
		$this->start_controls_section(
			'section_pagination_style',
			[
				'label' => esc_html__( 'Pagination', 'primary-addon-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'is_pagination' => 'true',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'pagination_typography',
				'selector' => '{{WRAPPER}} .napae-woo-product-pagination span, {{WRAPPER}} .napae-woo-product-pagination a',
			]
		);
		$this->add_control(
			'pagination_color',
			[
				'label' => esc_html__( 'Color', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .napae-woo-product-pagination span, {{WRAPPER}} .napae-woo-product-pagination a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'pagination_bg_color',
			[
				'label' => esc_html__( 'BG Color', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .napae-woo-product-pagination span, {{WRAPPER}} .napae-woo-product-pagination a' => 'background: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'pagination_color_active',
			[
				'label' => esc_html__( 'Active Color', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .napae-woo-product-pagination .current, {{WRAPPER}} .napae-woo-product-pagination .next, {{WRAPPER}} .napae-woo-product-pagination .prev' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'pagination_bg_color_active',
			[
				'label' => esc_html__( 'Active BG Color', 'primary-addon-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .napae-woo-product-pagination .current, {{WRAPPER}} .napae-woo-product-pagination .next, {{WRAPPER}} .napae-woo-product-pagination .prev' => 'background: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section(); // end: Section
	}

	/**
	 * Render Upcoming widget output on the frontend.
	 * Written in PHP and used to generate the final HTML.
	*/
	protected function render() {
		$settings = $this->get_settings_for_display();

		// Style
		$woo_grid_style = !empty( $settings['woo_grid_style'] ) ? $settings['woo_grid_style'] : 'one';
		$content_limit = !empty( $settings['content_limit'] ) ? $settings['content_limit'] : '10';

		// Badge
		$sale_badge_align = isset( $settings['badge_alignment'] ) ? $settings['badge_alignment'] : '';
		$sale_badge_preset = !empty($settings['badge_preset']) ? $settings['badge_preset'] : 'sale-preset-1';
		$sale_text = !empty($settings['sale_text']) ? $settings['sale_text'] : 'Sale!';
		$stockout_text = !empty($settings['stockout_text']) ? $settings['stockout_text'] : 'Stock Out';

		$col = ($woo_grid_style === 'three') ? '6' : '4';

		// Pagination
		if ( get_query_var( 'paged' ) ) { $paged = get_query_var( 'paged' ); }
		elseif ( get_query_var( 'page' ) ) { $paged = get_query_var( 'page' ); }
		else { $paged = 1; }
		
		// Query
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => $settings['posts_per_page'] ?: -1 ,
			'order' => $settings['order'] ?: 'DESC' ,
		    'tax_query'      => [
			    'relation' => 'AND',
			    [
				    'taxonomy' => 'product_visibility',
				    'field'     => 'name',
				    'terms'    => [ 'exclude-from-search', 'exclude-from-catalog' ],
				    'operator' => 'NOT IN',
			    ]
		    ],
		);

	    if ( $settings['orderby'] == 'title' ) {
		    $args[ 'orderby' ]  =  $settings['orderby'];
	    }

	    if ( $settings['orderby'] == 'rand' ) {
		    $args[ 'orderby' ]  =  $settings['orderby'];
	    }

	    if ( $settings['orderby'] == 'menu_order' ) {
		    $args[ 'orderby' ]  =  $settings['orderby'];
	    }

	    if ( $settings['orderby'] == 'popularity' ) {
		    $args[ 'meta_key' ] = 'total_sales';
		    $args[ 'orderby' ]  =  'meta_value_num';
	    }

	    if ( $settings['orderby'] == 'rating' ) {
		    $args[ 'meta_key' ] = '_wc_average_rating';
		    $args[ 'orderby' ]  =  'meta_value_num';
	    }

	    if ( $settings['orderby'] == 'price' ) {
		    $args[ 'orderby' ]  = 'meta_value_num';
		    $args[ 'meta_key' ] = '_price';
	    }

	    if ( get_option( 'woocommerce_hide_out_of_stock_items' ) == 'yes' ) {
		    $args[ 'meta_query' ]   = [ 'relation' => 'AND' ];
		    $args[ 'meta_query' ][] = [
			    'key'   => '_stock_status',
			    'value' => 'instock'
		    ];
	    }

	    if ( $settings['categories'] && !empty($settings['categories']) ) {
		    $args[ 'tax_query' ][] = [
				'taxonomy' 	=> 'product_cat',
				'field'    	=> 'id',
				'terms'     =>  $settings['categories'],
				'operator'  => 'IN'
	        ];
	    }

	    if ( $settings['is_pagination'] ) {
		    $args[ 'paged' ] = $paged;
	    }

		$woo_carousel_data = new \WP_Query($args);		

		// Turn output buffer on
		ob_start();
		if($woo_carousel_data->have_posts()) { ?>
			
			<div class="napae-woo-product napae-woo-product-style-<?php echo esc_attr($woo_grid_style); ?>">
				<div class="nich-row">
					<?php 
					while ($woo_carousel_data->have_posts()) : 
						$woo_carousel_data->the_post(); 
						$product = wc_get_product( get_the_ID() );

						$image_url = get_the_post_thumbnail_url( get_the_ID(), 'large' );
						$image_url = $image_url ?: Utils::get_placeholder_image_src();

						$sold_count = get_post_meta( get_the_ID(), 'total_sales', true );
						$sold_count = $sold_count ?: '0';
					?>
					<div class="nich-col-lg-<?php echo esc_attr($col); ?> nich-col-md-6">
						<?php if ($woo_grid_style === 'four') { ?>

						<div class="napae-woo-product-single napae-woo-product-single-grid">
							<div class="napae-woo-product-single-inner">
								<div class="napae-woo-product-image-wrapper">
									<a href="<?php the_permalink(); ?>">
										<div class="napae-woo-product-image" style="background-image: url(<?php echo esc_url($image_url); ?>);">
											<?php 
												if($settings['is_label']) { 
													echo ( ! $product->managing_stock() && ! $product->is_in_stock() ? '<span class="napae-onsale outofstock '.$sale_badge_preset.' '.$sale_badge_align.'">'. $stockout_text .'</span>' : ($product->is_on_sale() ? '<span class="napae-onsale '.$sale_badge_preset.' '.$sale_badge_align.'">' . $sale_text . '</span>' : '') );
												}
											?>
										</div>
									</a>
								</div>
								<div class="napae-woo-product-info">
									<div class="napae-woo-product-title">
										<a href="<?php the_permalink(); ?>"><?php echo $product->get_title(); ?></a>
									</div>
									<?php if ( $settings['is_rating'] && $product->get_rating_count() ) { ?>
									<div class="napae-woo-product-product-rating">
										<?php  
											echo wc_get_rating_html($product->get_average_rating(), $product->get_rating_count());
										?>
									</div>
									<?php } if($settings['is_price']) { ?>
									<div class="napae-woo-product-product-price">
										<?php echo $product->get_price_html(); ?>
									</div>
									<?php } if($settings['is_details']) { ?>
									<div class="napae-woo-product-details">
										<?php echo wp_trim_words(strip_shortcodes(get_the_excerpt()), $content_limit, '..'); ?>
									</div>
									<?php } if($settings['is_button']) { ?>
									<div class="napae-woo-product-meta">
										<div class="napae-woo-product-sold">
											<i class="fas fa-shopping-cart"></i> <?php echo $sold_count . ' ' . esc_html__( 'Sold', 'primary-addon-for-elementor' ); ?>
										</div>
										<div class="napae-woo-product-link">
											<a href="<?php the_permalink(); ?>">
												<i class="fas fa-long-arrow-alt-right"></i>
											</a>
										</div>
									</div>
									<?php } ?>
								</div>
							</div>							
						</div>							

						<?php } elseif ($woo_grid_style === 'three') { ?>

						<div class="napae-woo-product-single napae-woo-product-single-grid">
							<div class="napae-woo-product-single-inner">
								<div class="napae-woo-product-image-wrapper">
									<a href="<?php the_permalink(); ?>">
										<div class="napae-woo-product-image" style="background-image: url(<?php echo esc_url($image_url); ?>);">
											<?php 
												if($settings['is_label']) { 
													echo ( ! $product->managing_stock() && ! $product->is_in_stock() ? '<span class="napae-onsale outofstock '.$sale_badge_preset.' '.$sale_badge_align.'">'. $stockout_text .'</span>' : ($product->is_on_sale() ? '<span class="napae-onsale '.$sale_badge_preset.' '.$sale_badge_align.'">' . $sale_text . '</span>' : '') );
												}
											?>
										</div>
									</a>
								</div>
								<div class="napae-woo-product-info">
									<div class="napae-woo-product-title">
										<a href="<?php the_permalink(); ?>"><?php echo $product->get_title(); ?></a>
									</div>
									<?php if ( $settings['is_rating'] && $product->get_rating_count() ) { ?>
									<div class="napae-woo-product-product-rating">
										<?php  
											echo wc_get_rating_html($product->get_average_rating(), $product->get_rating_count());
										?>
									</div>
									<?php } if($settings['is_price']) { ?>
									<div class="napae-woo-product-product-price">
										<?php echo $product->get_price_html(); ?>
									</div>
									<?php } if($settings['is_details']) { ?>
									<div class="napae-woo-product-details">
										<?php echo wp_trim_words(strip_shortcodes(get_the_excerpt()), $content_limit, '..'); ?>
									</div>
									<?php } if($settings['is_button']) { ?>
									<div class="napae-woo-product-button">
										<?php woocommerce_template_loop_add_to_cart(); ?>
									</div>
									<?php } ?>
								</div>
							</div>							
						</div>							

						<?php } elseif ($woo_grid_style === 'two') { ?>

						<div class="napae-woo-product-single napae-woo-product-single-grid">
							<a href="<?php the_permalink(); ?>">
								<div class="napae-woo-product-image" style="background-image: url(<?php echo esc_url($image_url); ?>);">
									<?php 
										if($settings['is_label']) { 
											echo ( ! $product->managing_stock() && ! $product->is_in_stock() ? '<span class="napae-onsale outofstock '.$sale_badge_preset.' '.$sale_badge_align.'">'. $stockout_text .'</span>' : ($product->is_on_sale() ? '<span class="napae-onsale '.$sale_badge_preset.' '.$sale_badge_align.'">' . $sale_text . '</span>' : '') );
										}
									?>
									<?php if ( $settings['is_rating'] && $product->get_rating_count() ) { ?>
									<div class="napae-woo-product-product-rating">
										<?php  
											echo wc_get_rating_html($product->get_average_rating(), $product->get_rating_count());
										?>
									</div>
									<?php } ?>
								</div>
							</a>
							<div class="napae-woo-product-info">
								<div class="napae-woo-product-title">
									<a href="<?php the_permalink(); ?>"><?php echo $product->get_title(); ?></a>
								</div>
								<?php if($settings['is_price']) { ?>
								<div class="napae-woo-product-product-price">
									<?php echo $product->get_price_html(); ?>
								</div>
								<?php } if($settings['is_details']) { ?>
								<div class="napae-woo-product-details">
									<?php echo wp_trim_words(strip_shortcodes(get_the_excerpt()), $content_limit, '..'); ?>
								</div>
								<?php } if($settings['is_button']) { ?>
								<div class="napae-woo-product-button">
									<?php woocommerce_template_loop_add_to_cart(); ?>
								</div>
								<?php } ?>
							</div>
						</div>							

						<?php } else { ?>

						<div class="napae-woo-product-single napae-woo-product-single-grid">
							<a href="<?php the_permalink(); ?>">
								<div class="napae-woo-product-image" style="background-image: url(<?php echo esc_url($image_url); ?>);">
									<?php
										if($settings['is_label']) { 
											echo ( ! $product->managing_stock() && ! $product->is_in_stock() ? '<span class="napae-onsale outofstock '.$sale_badge_preset.' '.$sale_badge_align.'">'. $stockout_text .'</span>' : ($product->is_on_sale() ? '<span class="napae-onsale '.$sale_badge_preset.' '.$sale_badge_align.'">' . $sale_text . '</span>' : '') );
										}
									?>
								</div>
							</a>
							<div class="napae-woo-product-info">
								<div class="napae-woo-product-title">
									<a href="<?php the_permalink(); ?>"><?php echo $product->get_title(); ?></a>
								</div>
								<?php if ( $settings['is_rating'] && $product->get_rating_count() ) { ?>
								<div class="napae-woo-product-product-rating">
									<?php  
										echo wc_get_rating_html($product->get_average_rating(), $product->get_rating_count());
									?>
								</div>
								<?php } if($settings['is_price']) { ?>
								<div class="napae-woo-product-product-price">
									<?php echo $product->get_price_html(); ?>
								</div>
								<?php } if($settings['is_details']) { ?>
								<div class="napae-woo-product-details">
									<?php echo wp_trim_words(strip_shortcodes(get_the_excerpt()), $content_limit, '..'); ?>
								</div>
								<?php } if($settings['is_button']) { ?>
								<div class="napae-woo-product-button">
									<?php woocommerce_template_loop_add_to_cart(); ?>
								</div>
								<?php } ?>
							</div>
						</div>
						<?php } ?>

					</div>	
					<?php endwhile; ?>	
				</div>
			</div>
			<?php
				if ( $settings['is_pagination'] ) {  
					$pages = $woo_carousel_data->max_num_pages;
					$big = 999999999;
					if ($pages > 1) {
						echo '<div class="napae-woo-product-pagination">';
						$page_current = max(1, get_query_var('paged'));
						echo paginate_links(array(
							'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
							'format' => '?paged=%#%',
							'current' => $page_current,
							'total' => $pages,
						));
						echo '</div>';
					}
				}
			?>
		<?php
		} else {
			echo esc_html__('No Product!');
		}
		echo ob_get_clean();

	}

	/**
	 * Render Upcoming widget output in the editor.
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	*/

	//protected function _content_template(){}

}
Plugin::instance()->widgets_manager->register_widget_type( new Primary_Addon_Woo_Grid() );

} // enable & disable
