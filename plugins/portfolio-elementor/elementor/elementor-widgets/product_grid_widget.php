<?php
namespace Powerfolio\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 *
 * @since 1.0.0
 */
class PWGD_Product_Grid_Widget extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'pwrgrids_product_grid';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Product Grid (PwrGrids)', 'pwrgrids' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-gallery-justified';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'elpug-elements' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'elpug' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function register_controls() {
		$pro_version = true; //pe_fs()->can_use_premium_code__premium_only();

		/*===================================================================
		*============================ TAB CONTENT	=========================
		*==================================================================*/	

		//========== GENERAL SETTINGS TAB =========
		$this->start_controls_section(
			'section_grid',
			[
				'label' => __( 'General Settings', 'pwrgrids' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);		

			$this->add_control(
				'section_post_grid',
				[
					'label' => __( 'Layout / Grid', 'pwrgrids' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'after',
				]
			);

			//Grid Style
			$this->add_control(
				'grid_style',
				[
					'label' => __( 'Grid Style', 'pwrgrids' ),
					'type' => \Elementor\Controls_Manager::SELECT,
					'default' => 'grid-style-classic',
					'options' => [
						'grid-style-classic'  => __( 'Classic', 'pwrgrids' ),
						'grid-style-masonry' => __( 'Masonry', 'pwrgrids' ),
						'grid-style-style2' => __( 'Style 2', 'pwrgrids' ),
						'grid-style-style3' => __( 'Style 3', 'pwrgrids' ),
						//'grid-style-style4' => __( 'Style 4', 'pwrgrids' ),
						
					],
				]
			);
			
			//Columns (extended)
			$this->add_control(
				'columns',
				[
					'label' => __( 'Number of columns', 'pwrgrids' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'pwgd-3columns',	
					/*'conditions' => [
						'relation' => 'or',
						'terms' => [
							[
								'name' => 'grid_style',
								'operator' => '==',
								'value' => 'grid-style-boxed'
							],							
						]
					],*/			
					'options' => [
						'pwgd-1columns' => __( '1', 'pwrgrids' ),
						'pwgd-2columns' => __( '2', 'pwrgrids' ),
						'pwgd-3columns' => __( '3', 'pwrgrids' ),
						'pwgd-4columns' => __( '4', 'pwrgrids' ),
						'pwgd-5columns' => __( '5', 'pwrgrids' ),
					]
				]
			);

						

			$this->add_control(
				'section_post_item',
				[
					'label' => __( 'Post Item', 'pwrgrids' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'after',
				]
			);

			//Post Item
			$this->add_control(
				'post_item_margin',
				[
					'label' => __( 'Margin between posts', 'pwrgrids' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 40,
							'step' => 1,
						]						
					],
					'default' => [
						'unit' => 'px',
						'size' => 10,
					],
					'selectors' => [
						'{{WRAPPER}} .pwgd-post-grid-item-wrapper' => 'padding: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'post_item_border',
					'label' => __( 'Post Item: Border', 'pwrgrids' ),
					'selector' => '{{WRAPPER}} .pwgd-post-grid-item',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'post_item_background',
					'label' => __( 'Post Item: Background', 'pwrgrids' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .pwgd-post-grid-item',
				]
			);

			$this->add_control(
				'post_item_padding',
				[
					'label' => __( 'Post Item: Padding', 'pwrgrids' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .pwgd-post-grid-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'post_item_padding_inside',
				[
					'label' => __( 'Post Item: Padding (content only)', 'pwrgrids' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .pwgd-post-grid-item .pwgd-post-grid-item-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'post_item_border_radius',
				[
					'label' => __( 'Post Item: Border Radius', 'pwrgrids' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'condition'   => [
						'show_btn' => 'true',
					],
					'selectors' => [
						'{{WRAPPER}} .pwgd-post-grid-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			//Posts Filter
			/*$this->add_control(
				'section_post_item_filter',
				[
					'label' => __( 'Posts Filter', 'pwrgrids' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'after',
				]
			);

			$this->add_control(
				'showfilter',
				[
					'label' => __( 'Show Category Filter', 'pwrgrids' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Yes', 'pwrgrids' ),
					'label_off' => __( 'No', 'pwrgrids' ),
					'description' => __('IMPORTANT: It will only filter the posts that are currently displayed on the screen/page.', 'pwrgrids'),
					'return_value' => 'yes',
					'default' => 'no',
				]
			);			

			//Filter: Background color
			$this->add_control(
				'filter_bgcolor',
				[
					'label' => __( 'Filter: Background Color', 'pwrgrids' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'alpha' => true,				
					'selectors' => [
						'{{WRAPPER}} .pwgd-posts-filter .posts-filter-item' => 'background-color: {{VALUE}};',
					],
					'condition'		=> [
						'showfilter' => 'yes'
					],
				]
			);	*/	
			
			
		$this->end_controls_section();
		//========== /GENERAL SETTINGS TAB =========

		//========== QUERY SETTINGS TAB =========
		$this->start_controls_section(
			'section_post_query',
			[
				'label' => __( 'Post Query', 'pwrgrids' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);	
			//Posts per Page
			$this->add_control(
				'posts_per_page',
				[
					'label' => __( 'Posts per page', 'pwrgrids' ),
					'type' => \Elementor\Controls_Manager::NUMBER,
					'min' => 1,
					'max' => 40,
					'step' => 1,
					'default' => 10,
				]
			);		
			
			//Order By
			$this->add_control(
				'query_order_by',
				[
					'label' => __( 'Order By', 'pwrgrids' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'date',				
					'options' => [
						'date' => __( 'Published Date', 'pwrgrids' ),
						'modified' => __( 'Modified Date', 'pwrgrids' ),
						'title' => __( 'Product Title', 'pwrgrids' ),
						'slug' => __( 'Product Slug', 'pwrgrids' ),
						'title' => __( 'Product Title', 'pwrgrids' ),
						'comment_count' => __( 'Comments', 'pwrgrids' ),
					]
				]
			);

			//Order By
			$this->add_control(
				'query_order',
				[
					'label' => __( 'Order', 'pwrgrids' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'ASC',				
					'options' => [
						'ASC' => __( 'ASC', 'pwrgrids' ),
						'DESC' => __( 'DESC', 'pwrgrids' ),
					]
				]
			);

		$this->end_controls_section();
		//========== /QUERY SETTINGS TAB =========

		//========== Product TITLE SETTINGS TAB =========
		$this->start_controls_section(
			'section_post_title',
			[
				'label' => __( 'Product Title / Featured Image', 'pwrgrids' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);	

			//Title			
			$this->add_control(
				'section_title',
				[
					'label' => __( 'Product Title', 'pwrgrids' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'after',
				]
			);

			$this->add_control(
				'post_title_text_color',
				[
					'label' => __( 'Product Title: Text Color', 'pwrgrids' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					/*'scheme' => [
						'type' => \Elementor\Scheme_Color::get_type(),
						'value' => \Elementor\Scheme_Color::COLOR_1,
					],*/
					'selectors' => [
						'{{WRAPPER}} .pwgd-post-grid-item .pwgd-post-grid-item-title, {{WRAPPER}} .pwgd-post-grid-item .pwgd-post-grid-item-title a' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'post_title',
					'label' => __( 'Title: Typography', 'pwrgrids' ),
					//'scheme' => TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .pwgd-post-grid-item .pwgd-post-grid-item-title',
				]
			);

			//Featured Image			
			$this->add_control(
				'section_featured_img',
				[
					'label' => __( 'Featured Image', 'pwrgrids' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'after',
				]
			);

			//Featured Image
			$this->add_control(
				'show_featured_image',
				[
					'label' => __( 'Show Featured Image', 'pwrgrids' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Show', 'pwrgrids' ),
					'label_off' => __( 'Hide', 'pwrgrids' ),
					'return_value' => 'true',
					'default' => 'true',
				]
			);

			//Featured Image			
			$this->add_control(
				'section_featured_img_bg',
				[
					'label' => __( 'Featured Image: Background', 'pwrgrids' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					//'separator' => 'after',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'featured_image_background',
					'label' => __( 'Featured Image: Default Background', 'pwrgrids' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .pwgd-post-grid-item .pwgd-post-grid-item-img-link',
				]
			);						

		$this->end_controls_section();
		//========== /Product TITLE SETTINGS TAB =========		

		//========== Product META SETTINGS TAB =========
		$this->start_controls_section(
			'section_post_meta',
			[
				'label' => __( 'Product Meta', 'pwrgrids' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);	

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'post_meta_typography',
					'label' => __( 'Product Meta: Typography', 'pwrgrids' ),
					//'scheme' => TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .pwgd-post-grid-item .pwgd-post-grid-item-meta-wrapper .pwgd-post-grid-item-meta, {{WRAPPER}} .pwgd-post-grid-item .pwgd-post-grid-item-meta-wrapper .pwgd-post-grid-item-meta a',
				]
			);

			$this->add_control(
				'post_meta_bg_color',
				[
					'label' => __( 'Product Meta: Background Color of the Item', 'pwrgrids' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					/*'scheme' => [
						'type' => \Elementor\Scheme_Color::get_type(),
						'value' => \Elementor\Scheme_Color::COLOR_1,
					],*/
					'selectors' => [
						'{{WRAPPER}} .pwgd-post-grid-item .pwgd-post-grid-item-meta' => 'background-color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'post_meta_text_color',
				[
					'label' => __( 'Product Meta: Text Color of the Item', 'pwrgrids' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					/*'scheme' => [
						'type' => \Elementor\Scheme_Color::get_type(),
						'value' => \Elementor\Scheme_Color::COLOR_1,
					],*/
					'selectors' => [
						'{{WRAPPER}} .pwgd-post-grid-item .pwgd-post-grid-item-meta-wrapper .pwgd-post-grid-item-meta, {{WRAPPER}} .pwgd-post-grid-item .pwgd-post-grid-item-meta-wrapper .pwgd-post-grid-item-meta a' => 'color: {{VALUE}}',
					],
				]
			);			

			$this->add_control(
				'show_categories',
				[
					'label' => __( 'Show Categories', 'pwrgrids' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Show', 'pwrgrids' ),
					'label_off' => __( 'Hide', 'pwrgrids' ),
					'return_value' => 'true',
					'default' => 'true',
				]
			);
		
		$this->end_controls_section();
		//========== /Product META SETTINGS TAB =========

		//========== POST BODY SETTINGS TAB =========
		$this->start_controls_section(
			'section_post_body',
			[
				'label' => __( 'Post Content / Button', 'pwrgrids' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);	

			$this->add_control(
				'section_post body',
				[
					'label' => __( 'Post Body', 'pwrgrids' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'after',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'post_content_typography',
					'label' => __( 'Post Body: Typography', 'pwrgrids' ),
					//'scheme' => TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .pwgd-post-grid-item .pwgd-post-grid-item-text',
				]
			);

			//Add to Cart Button
			$this->add_control(
				'section_see_more_btn',
				[
					'label' => __( '"Add to Cart" Button', 'pwrgrids' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'after',
				]
			);

			$this->add_control(
				'show_btn',
				[
					'label' => __( 'Show "Add to Cart" button', 'pwrgrids' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Show', 'pwrgrids' ),
					'label_off' => __( 'Hide', 'pwrgrids' ),
					'return_value' => 'true',
					'default' => 'true',
				]
			);

			/*$this->add_control(
				'see_more_btn_text',
				[
					'label' => __( 'Button: Text', 'pwrgrids' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => __( 'Add to Cart', 'pwrgrids' ),	
					'condition'   => [
						'show_btn' => 'true',
					],								
				]
			);*/


			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'post_button_typography',
					'label' => __( 'Button: Typography', 'pwrgrids' ),
					//'scheme' => TYPOGRAPHY_1,
					'condition'   => [
						'show_btn' => 'true',
					],
					'selector' => '{{WRAPPER}} .pwgd-post-grid-item .pwgd-post-grid-item-btn',
				]
			);

			$this->add_control(
				'post_button_typography_color',
				[
					'label' => __( 'Button: Text Color', 'pwrgrids' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					/*'scheme' => [
						'type' => \Elementor\Scheme_Color::get_type(),
						'value' => \Elementor\Scheme_Color::COLOR_1,
					],*/
					'condition'   => [
						'show_btn' => 'true',
					],
					'selectors' => [
						'{{WRAPPER}} .pwgd-post-grid-item .pwgd-post-grid-item-btn' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'post_button_background',
					'label' => __( 'Button: Background', 'pwrgrids' ),
					'types' => [ 'classic', 'gradient' ],
					'condition'   => [
						'show_btn' => 'true',
					],
					'selector' => '{{WRAPPER}} .pwgd-post-grid-item .pwgd-post-grid-item-btn',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'post_button_border',
					'label' => __( 'Button: Border', 'pwrgrids' ),
					'condition'   => [
						'show_btn' => 'true',
					],
					'selector' => '{{WRAPPER}} .pwgd-post-grid-item .pwgd-post-grid-item-btn',
				]
			);

			$this->add_control(
				'post_button_padding',
				[
					'label' => __( 'Button: Padding', 'pwrgrids' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'condition'   => [
						'show_btn' => 'true',
					],
					'selectors' => [
						'{{WRAPPER}} .pwgd-post-grid-item .pwgd-post-grid-item-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'post_button_border_Radius',
				[
					'label' => __( 'Button: Border Radius', 'pwrgrids' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'condition'   => [
						'show_btn' => 'true',
					],
					'selectors' => [
						'{{WRAPPER}} .pwgd-post-grid-item .pwgd-post-grid-item-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();
		//========== /POST BODY SETTINGS TAB =========

		//========== PAGINATION SETTINGS TAB =========
		$this->start_controls_section(
			'section_post_pagination',
			[
				'label' => __( 'Pagination', 'pwrgrids' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);	

			$this->add_control(
				'show_pagination',
				[
					'label' => __( 'Show Pagination', 'pwrgrids' ),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'label_on' => __( 'Show', 'pwrgrids' ),
					'label_off' => __( 'Hide', 'pwrgrids' ),
					'return_value' => 'true',
					'default' => 'true',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'post_pagination_typography',
					'label' => __( 'Pagination: Typography', 'pwrgrids' ),
					//'scheme' => TYPOGRAPHY_1,
					'condition'   => [
						'show_pagination' => 'true',
					],
					'selector' => '{{WRAPPER}} .pwgd-post-grid-pagination a',
				]
			);

			$this->add_control(
				'post_pagination_typography_color',
				[
					'label' => __( 'Pagination: Text Color', 'pwrgrids' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					/*'scheme' => [
						'type' => \Elementor\Scheme_Color::get_type(),
						'value' => \Elementor\Scheme_Color::COLOR_1,
					],*/
					'condition'   => [
						'show_pagination' => 'true',
					],
					'selectors' => [
						'{{WRAPPER}} .pwgd-post-grid-pagination, {{WRAPPER}} .pwgd-post-grid-pagination a' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'post_pagination_link_background',
					'label' => __( 'Pagination: Link Background', 'pwrgrids' ),
					'types' => [ 'classic', 'gradient' ],
					'condition'   => [
						'show_pagination' => 'true',
					],
					'selector' => '{{WRAPPER}} .pwgd-post-grid-pagination a',
				]
			);

			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'post_pagination_link_border',
					'label' => __( 'Pagination: Link Border', 'pwrgrids' ),
					'condition'   => [
						'show_pagination' => 'true',
					],
					'selector' => '{{WRAPPER}} .pwgd-post-grid-pagination a',
				]
			);

			$this->add_control(
				'post_pagination_link_padding',
				[
					'label' => __( 'Pagination: Link Padding', 'pwrgrids' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'condition'   => [
						'show_pagination' => 'true',
					],
					'selectors' => [
						'{{WRAPPER}} .pwgd-post-grid-pagination a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'post_pagination_link_border_radius',
				[
					'label' => __( 'Pagination: Link Border Radius', 'pwrgrids' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'condition'   => [
						'show_pagination' => 'true',
					],
					'selectors' => [
						'{{WRAPPER}} .pwgd-post-grid-pagination a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();
		//========== /PAGINATION BODY SETTINGS TAB =========
		

		/*===================================================================
		*============================ /TAB CONTENT	=========================
		*==================================================================*/		

		/*===================================================================
		*============================ TAB STYLE	=========================
		*==================================================================*/	

		$this->start_controls_section(
			'section_item_description',
			[
				'label' => __( 'Item', 'pwrgrids' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);		
		

		$this->end_controls_section();

		/*===================================================================
		*============================ /TAB STYLE	=========================
		*==================================================================*/		
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {		
		$settings = $this->get_settings();
		
		$product_grid = new \Powerfolio_Product_Grid();
		echo( $product_grid->get_product_grid_template($settings) );
		?>
	
		<?php
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	/*protected function _content_template() {
		?>
		


		<?php
	}*/
}