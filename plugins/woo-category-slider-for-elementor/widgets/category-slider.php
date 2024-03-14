<?php
/**
 * Elementor pcsfe_category_slider_free Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Image_Size;
use \Elementor\Widget_Base;

class pcsfe_category_slider_free extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve pcsfe_category_slider_free widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'pcsfe_category_slider_free';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve pcsfe_category_slider_free widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Product Category Slider Elementor', PCSFE_TEXT_DOMAIN );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve pcsfe_category_slider_free widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fa fa-product-hunt';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the pcsfe_category_slider_free widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'general' ];
	}

	/**
	 * Register pcsfe_category_slider_free widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', PCSFE_TEXT_DOMAIN ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$select_product_cat_arry = array();
		$select_product_cats = get_terms( 'product_cat' );
		foreach ($select_product_cats as $select_product_cat) :
			$select_product_cat_arry[$select_product_cat->term_id] = $select_product_cat->name;
		endforeach;
		$this->add_control(
			'select_categories',
			[
				'label' => __( 'Select Specific Categories', PCSFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $select_product_cat_arry,
			]
		);

		$this->add_control(
			'hide_empty_category',
			[
				'label'        => __( 'Hide Empty Category', 'PCSFE_TEXT_DOMAIN' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'PCSFE_TEXT_DOMAIN' ),
				'label_off'    => __( 'No', 'PCSFE_TEXT_DOMAIN' ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);
		$this->add_control(
			'product_count',
			[
				'label'        => __( 'Show Product Count', 'PCSFE_TEXT_DOMAIN' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'PCSFE_TEXT_DOMAIN' ),
				'label_off'    => __( 'Hide', 'PCSFE_TEXT_DOMAIN' ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);

		$this->add_control(
			'product_text',
			[
				'label'        => __( 'Product Count Text', 'PCSFE_TEXT_DOMAIN' ),
				'type'         => Controls_Manager::TEXT,
				'label_on'     => __( 'Show', 'PCSFE_TEXT_DOMAIN' ),
				'label_off'    => __( 'Hide', 'PCSFE_TEXT_DOMAIN' ),
				'condition'    => [
		     		'product_count' => 'true',
		     	],
				'default'      => __( 'Products', PCSFE_TEXT_DOMAIN ),
			]
		);

		$this->add_control(
			'show_description',
			[
				'label'        => __( 'Show Description', 'PCSFE_TEXT_DOMAIN' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'PCSFE_TEXT_DOMAIN' ),
				'label_off'    => __( 'Hide', 'PCSFE_TEXT_DOMAIN' ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);

		$this->add_control(
			'category_layout',
			[
				'label' => __( 'Category Layout', PCSFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::SELECT,
				'multiple' => true,
				'options' => [
					'layout-1' => __( 'Layout 1', PCSFE_TEXT_DOMAIN ),
					'layout-2' => __( 'Layout 2', PCSFE_TEXT_DOMAIN ),
					'layout-3' => __( 'Layout 3', PCSFE_TEXT_DOMAIN ),
					'layout-4' => __( 'Layout 4', PCSFE_TEXT_DOMAIN ),

				],
			]
		);

		$this->add_control(
			'cat_image_show',
			[
				'label'        => __( 'Show Image', 'PCSFE_TEXT_DOMAIN' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'PCSFE_TEXT_DOMAIN' ),
				'label_off'    => __( 'Hide', 'PCSFE_TEXT_DOMAIN' ),
				'return_value' => 'true',
				'default'      => 'true',
				'condition'    => [
		     		'category_layout' => 'layout-2'
		     	]
			]
		);


		$this->add_control(
			'show_shop_now_button',
			[
				'label'        => __( 'Show Shop Now Button', 'PCSFE_TEXT_DOMAIN' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'PCSFE_TEXT_DOMAIN' ),
				'label_off'    => __( 'Hide', 'PCSFE_TEXT_DOMAIN' ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'        => __( 'Button Text', 'PCSFE_TEXT_DOMAIN' ),
				'type'         => Controls_Manager::TEXT,
				'default'      => __( 'Shop Now', PCSFE_TEXT_DOMAIN ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'slide_settings',
			[
				'label' => __( 'Slides Settings', 'PCSFE_TEXT_DOMAIN' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'slide_to_show',
			[
				'label' => __( 'Slide To Show', PCSFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 12,
				'step' => 1,
				'default' => 4,
			]
		);
		$this->add_control(
			'slideBy',
			[
				'label' => __( 'slideBy', PCSFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 12,
				'step' => 1,
				'default' => 1,
			]
		);
		$this->add_control(
			'rewind',
			[
				'label'        => __( 'rewind', 'PCSFE_TEXT_DOMAIN' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'PCSFE_TEXT_DOMAIN' ),
				'label_off'    => __( 'Hide', 'PCSFE_TEXT_DOMAIN' ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);
		$this->add_control(
			'margin',
			[
				'label' => __( 'Margin', PCSFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
			]
		);
		$this->add_control(
			'stagePadding',
			[
				'label' => __( 'stagePadding', PCSFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
			]
		);
		$this->add_control(
			'nav',
			[
				'label'        => __( 'Navigation Arrow', 'PCSFE_TEXT_DOMAIN' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'PCSFE_TEXT_DOMAIN' ),
				'label_off'    => __( 'Hide', 'PCSFE_TEXT_DOMAIN' ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);
		$this->add_control(
			'dots',
			[
				'label'        => __( 'Dots', 'PCSFE_TEXT_DOMAIN' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'PCSFE_TEXT_DOMAIN' ),
				'label_off'    => __( 'Hide', 'PCSFE_TEXT_DOMAIN' ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);
		$this->add_control(
			'autoplay',
			[
				'label'        => __( 'Auto Play', 'PCSFE_TEXT_DOMAIN' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'PCSFE_TEXT_DOMAIN' ),
				'label_off'    => __( 'No', 'PCSFE_TEXT_DOMAIN' ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);
		$this->add_control(
			'loop',
			[
				'label'        => __( 'Loop', 'PCSFE_TEXT_DOMAIN' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'PCSFE_TEXT_DOMAIN' ),
				'label_off'    => __( 'No', 'PCSFE_TEXT_DOMAIN' ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);
		$this->add_control(
			'lazyLoad',
			[
				'label'        => __( 'lazyLoad', 'PCSFE_TEXT_DOMAIN' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'PCSFE_TEXT_DOMAIN' ),
				'label_off'    => __( 'No', 'PCSFE_TEXT_DOMAIN' ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);
		$this->add_control(
			'mouseDrag',
			[
				'label'        => __( 'Mouse Drag', 'PCSFE_TEXT_DOMAIN' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'PCSFE_TEXT_DOMAIN' ),
				'label_off'    => __( 'No', 'PCSFE_TEXT_DOMAIN' ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);
		$this->add_control(
			'touchDrag',
			[
				'label'        => __( 'Touch Drag', 'PCSFE_TEXT_DOMAIN' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'PCSFE_TEXT_DOMAIN' ),
				'label_off'    => __( 'No', 'PCSFE_TEXT_DOMAIN' ),
				'return_value' => 'true',
				'default'      => 'true',
			]
		);
		$this->add_control(
			'autoplayTimeout',
			[
				'label'     => __( 'Autoplay Timeout', 'PCSFE_TEXT_DOMAIN' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '5000',
				'condition' => [
					'autoplay' => 'true',
				],
				'options' => [
					'5000'  => __( '5 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'10000' => __( '10 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'15000' => __( '15 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'20000' => __( '20 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'25000' => __( '25 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'30000' => __( '30 Seconds', 'PCSFE_TEXT_DOMAIN' ),
				],
			]
		);
		$this->add_control(
			'slideTransition',
			[
				'label'     => __( 'slideTransition', 'PCSFE_TEXT_DOMAIN' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '5000',
				'condition' => [
					'autoplay' => 'true',
				],
				'options' => [
					'5000'  => __( '5 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'10000' => __( '10 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'15000' => __( '15 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'20000' => __( '20 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'25000' => __( '25 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'30000' => __( '30 Seconds', 'PCSFE_TEXT_DOMAIN' ),
				],
			]
		);
		$this->add_control(
			'autoplayHoverPause',
			[
				'label'     => __( 'autoplayHoverPause', 'PCSFE_TEXT_DOMAIN' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '5000',
				'condition' => [
					'autoplay' => 'true',
				],
				'options' => [
					'5000'  => __( '5 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'10000' => __( '10 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'15000' => __( '15 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'20000' => __( '20 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'25000' => __( '25 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'30000' => __( '30 Seconds', 'PCSFE_TEXT_DOMAIN' ),
				],
			]
		);
		$this->add_control(
			'smartSpeed',
			[
				'label'     => __( 'SmartSpeed', 'PCSFE_TEXT_DOMAIN' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '5000',
				'condition' => [
					'autoplay' => 'true',
				],
				'options' => [
					'5000'  => __( '5 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'10000' => __( '10 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'15000' => __( '15 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'20000' => __( '20 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'25000' => __( '25 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'30000' => __( '30 Seconds', 'PCSFE_TEXT_DOMAIN' ),
				],
			]
		);

		$this->add_control(
			'fluidSpeed',
			[
				'label'     => __( 'fluidSpeed', 'PCSFE_TEXT_DOMAIN' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '5000',
				'condition' => [
					'autoplay' => 'true',
				],
				'options' => [
					'5000'  => __( '5 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'10000' => __( '10 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'15000' => __( '15 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'20000' => __( '20 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'25000' => __( '25 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'30000' => __( '30 Seconds', 'PCSFE_TEXT_DOMAIN' ),
				],
			]
		);

		$this->add_control(
			'autoplaySpeed',
			[
				'label'     => __( 'autoplaySpeed', 'PCSFE_TEXT_DOMAIN' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '5000',
				'condition' => [
					'autoplay' => 'true',
				],
				'options' => [
					'5000'  => __( '5 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'10000' => __( '10 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'15000' => __( '15 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'20000' => __( '20 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'25000' => __( '25 Seconds', 'PCSFE_TEXT_DOMAIN' ),
					'30000' => __( '30 Seconds', 'PCSFE_TEXT_DOMAIN' ),
				],
			]
		);



		$this->end_controls_section();

		$this->start_controls_section(
			'responsive_settings',
			[
				'label' => __( 'Responsive Settings', 'PCSFE_TEXT_DOMAIN' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'mobile_screen',
			[
				'label' => __( 'Mobile Screen for 360px', PCSFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 12,
				'step' => 1,
				'default' => 1,
			]
		);
		$this->add_control(
			'mobile_margin',
			[
				'label' => __( 'Mobile Margin', PCSFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
			]
		);
		$this->add_control(
			'hr',
			[
				'type' => Controls_Manager::DIVIDER,
				'label'        => __( 'Label', 'PCSFE_TEXT_DOMAIN' ),
			]
		);
		$this->add_control(
			'tablet_screen',
			[
				'label' => __( 'Tablet Screen for 768px', PCSFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 12,
				'step' => 1,
				'default' => 3,

			]
		);
		$this->add_control(
			'tablet_margin',
			[
				'label' => __( 'Tablet Margin', PCSFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'cat_box_style',
			[
				'label' => __( 'Box Style', PCSFE_TEXT_DOMAIN ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'cat_box_bg_color',
			[
				'label' => __( 'Background Color', PCSFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .product-cat-with-thumb, {{WRAPPER}} .card.category-second-layout, {{WRAPPER}} .card.card-layout-four' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'cat_box_border',
				'selector' => '{{WRAPPER}} .card',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'cat_box_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'cat_box_box_shadow',
				'selector' => '{{WRAPPER}} .card',
			]
		);

		$this->add_responsive_control(
			'cat_box_text_padding',
			[
				'label' => __( 'Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'cat_title_style',
			[
				'label' => __( 'Title Style', PCSFE_TEXT_DOMAIN ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'cat_title_text_color',
			[
				'label' => __( 'Text Color', PCSFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .product-cat-with-thumb h5.product-title, {{WRAPPER}} .card.category-second-layout h5.card-title, {{WRAPPER}} .card.card-layout-three h5.card-title, {{WRAPPER}} .card.card-layout-four .info-wrap h4.title, {{WRAPPER}} .hovereffect h2' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cat_title_typhography',
				'selector' => '{{WRAPPER}} .product-cat-with-thumb h5.product-title, {{WRAPPER}} .card.category-second-layout h5.card-title, .card.card-layout-three h5.card-title, {{WRAPPER}} .card.card-layout-four .info-wrap h4.title, {{WRAPPER}} .hovereffect h2',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'cat_paragraph_style',
			[
				'label' => __( 'Paragraph Style', PCSFE_TEXT_DOMAIN ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'cat_paragraph_color',
			[
				'label' => __( 'Text Color', PCSFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .card.category-second-layout p.card-text,{{WRAPPER}} .card.card-layout-three p.card-text, {{WRAPPER}} .card.card-layout-four .info-wrap p.desc' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cat_paragraph_typography',
				'selector' => '{{WRAPPER}} .card.category-second-layout p.card-text, {{WRAPPER}} .card.card-layout-three p.card-text, {{WRAPPER}} .card.card-layout-four .info-wrap p.desc',

			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'cat_count_style',
			[
				'label' => __( 'Product Count Style', PCSFE_TEXT_DOMAIN ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'cat_count_color',
			[
				'label' => __( 'Text Color', PCSFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .product-count *,{{WRAPPER}} .product-cat-with-thumb .product-count' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cat_count_typography',
				'selector' => '{{WRAPPER}} .product-count *, {{WRAPPER}} .product-cat-with-thumb .product-count',

			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'cat_button_style',
			[
				'label' => __( 'Button Style', PCSFE_TEXT_DOMAIN ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => __( 'Normal', 'elementor' ),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cat_button_typography',
				'selector' => '{{WRAPPER}} .card.category-second-layout .btn.btn-primary, {{WRAPPER}} .card.card-layout-three .btn.btn-primary, {{WRAPPER}} .card.card-layout-four .bottom-wrap a.btn.btn-primary',
			]
		);
		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .card.category-second-layout .btn.btn-primary,{{WRAPPER}} .card.card-layout-three .btn.btn-primary, {{WRAPPER}} .card.card-layout-four .bottom-wrap a.btn.btn-primary, {{WRAPPER}} .hovereffect .single-cat-5-btn' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => __( 'Background Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => \Elementor\Scheme_Color::get_type(),
					'value' => \Elementor\Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .card.category-second-layout .btn.btn-primary,{{WRAPPER}} .card.card-layout-three .btn.btn-primary, {{WRAPPER}} .card.card-layout-four .bottom-wrap a.btn.btn-primary, {{WRAPPER}} .hovereffect .single-cat-5-btn' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => __( 'Hover', 'elementor' ),
			]
		);

		$this->add_control(
			'hover_color',
			[
				'label' => __( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .card.category-second-layout .btn.btn-primary:hover ,{{WRAPPER}} .card.card-layout-three .btn.btn-primary:hover, {{WRAPPER}} .card.category-second-layout .btn.btn-primary:focus, {{WRAPPER}} .card.card-layout-three .btn.btn-primary:focus, {{WRAPPER}} .card.card-layout-four .bottom-wrap a.btn.btn-primary:hover, {{WRAPPER}} .card.card-layout-four .bottom-wrap a.btn.btn-primary:focus, {{WRAPPER}} .card.card-layout-four .bottom-wrap a.btn.btn-primary:active, {{WRAPPER}} .hovereffect .single-cat-5-btn:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => __( 'Background Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .card.category-second-layout .btn.btn-primary:hover,{{WRAPPER}} .card.card-layout-three .btn.btn-primary:hover, {{WRAPPER}} .card.category-second-layout .btn.btn-primary:focus,{{WRAPPER}} .card.card-layout-three .btn.btn-primary:focus, {{WRAPPER}} .card.card-layout-four .bottom-wrap a.btn.btn-primary:hover, {{WRAPPER}} .card.card-layout-four .bottom-wrap a.btn.btn-primary:focus, {{WRAPPER}} .hovereffect .single-cat-5-btn:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label' => __( 'Border Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .card.category-second-layout .btn.btn-primary:hover,{{WRAPPER}} .card.card-layout-three .btn.btn-primary:hover, {{WRAPPER}} .card.category-second-layout .btn.btn-primary:focus,{{WRAPPER}} .card.card-layout-three .btn.btn-primary:focus, {{WRAPPER}} .card.card-layout-four .bottom-wrap a.btn.btn-primary:hover, {{WRAPPER}} .card.card-layout-four .bottom-wrap a.btn.btn-primary:focus, {{WRAPPER}} .hovereffect .single-cat-5-btn:hover ' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => __( 'Hover Animation', 'elementor' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} .card.category-second-layout .btn.btn-primary, {{WRAPPER}} .card.card-layout-three .btn.btn-primary, {{WRAPPER}} .card.card-layout-four .bottom-wrap a.btn.btn-primary, {{WRAPPER}} .hovereffect .single-cat-5-btn',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .card.category-second-layout .btn.btn-primary,{{WRAPPER}} .card.card-layout-three .btn.btn-primary, {{WRAPPER}} .card.card-layout-four .bottom-wrap a.btn.btn-primary, {{WRAPPER}} .hovereffect .single-cat-5-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .card.category-second-layout .btn.btn-primary, {{WRAPPER}} .card.card-layout-three .btn.btn-primary, {{WRAPPER}} .card.card-layout-four .bottom-wrap a.btn.btn-primary, {{WRAPPER}} .hovereffect .single-cat-5-btn',
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label' => __( 'Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .card.category-second-layout .btn.btn-primary, {{WRAPPER}} .card.card-layout-three .btn.btn-primary, {{WRAPPER}} .card.card-layout-four .bottom-wrap a.btn.btn-primary, {{WRAPPER}} .hovereffect .single-cat-5-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'slider_navigation_style',
			[
				'label' => __( 'Slider Navigation Style', PCSFE_TEXT_DOMAIN ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_navigation_style' );

		$this->start_controls_tab(
			'default_navigation',
			[
				'label' => __( 'Normal', 'elementor' ),
			]
		);
		$this->add_control(
			'navigation_background',
			[
				'label' => __( 'Background Color', PCSFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .product-cat-slider .owl-nav button' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'navigation_text_color',
			[
				'label' => __( 'Text Color', PCSFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .product-cat-slider .owl-nav button' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'navigation_border',
				'selector' => '{{WRAPPER}} .product-cat-slider .owl-nav button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'navigation_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .product-cat-slider .owl-nav button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'hover_navigation',
			[
				'label' => __( 'Hover', 'elementor' ),
			]
		);
		$this->add_control(
			'hover_navigation_background',
			[
				'label' => __( 'Background Color', PCSFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .product-cat-slider .owl-nav button:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_navigation_text_color',
			[
				'label' => __( 'Text Color', PCSFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .product-cat-slider .owl-nav button:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'navigation_border_hover',
				'selector' => '{{WRAPPER}} .product-cat-slider .owl-nav button:hover',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'navigation_border_radius_hover',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .product-cat-slider .owl-nav button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	/**
	 * Render pcsfe_category_slider_free widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		// var_dump($settings['mobile_screen']);

		$sliderDynamicId 		   = rand(10, 100000);
		$nav             		   = $settings['nav'] ? $settings['nav'] : 'false';
		$dots            		   = $settings['dots'] ? $settings['dots'] : 'false';
		$autoplay        		   = $settings['autoplay'] ? $settings['autoplay'] : 'false';
		$loop            		   = $settings['loop'] ? $settings['loop'] : 'false';
		$rewind            		   = $settings['rewind'] ? $settings['rewind'] : 'false';
		$lazyLoad            	   = $settings['lazyLoad'] ? $settings['lazyLoad'] : 'false';
		$mouseDrag       		   = $settings['mouseDrag'] ? $settings['mouseDrag'] : 'false';
		$touchDrag       		   = $settings['touchDrag'] ? $settings['touchDrag'] : 'false';
		$autoplayTimeout 		   = $settings['autoplayTimeout'] ? $settings['autoplayTimeout'] : '0';
		$slide_to_show   		   = $settings['slide_to_show'];
		$stagePadding   		   = $settings['stagePadding'];
		$slideBy   		   		   = $settings['slideBy'];
		$slideTransition   		   = $settings['slideTransition'];
		$slide_to_show_on_mobile   = $settings['mobile_screen'];
		$slide_to_show_on_tablet   = $settings['tablet_screen'];
		$item_margin     		   = $settings['margin'];
		$item_margin_on_mobile     = $settings['mobile_margin'];
		$item_margin_on_tablet     = $settings['tablet_margin'];
		$show_product_count        = $settings['product_count'];
		$product_text        	   = $settings['product_text'];
		$show_description          = $settings['show_description'];
		$category_layout           = $settings['category_layout'] ? $settings['category_layout'] : 'layout-1' ;
		$cat_image_show 		   = $settings['cat_image_show'];
		$hide_empty_category       = $settings['hide_empty_category'] ? $settings['hide_empty_category'] : 0;
		$show_shop_now_button 	   = $settings['show_shop_now_button'];
		$button_text 			   = $settings['button_text'];
		$this->add_render_attribute(
            'slider-wrapper',
            [
                'class'                 => 'product-cat-slider owl-carousel',
                'id'                    => 'product-cat-'.esc_attr($sliderDynamicId),
                'data-items'       		=> $slide_to_show,
                'data-mobile-items'     => $slide_to_show_on_mobile,
                'data-tablet-items'     => $slide_to_show_on_tablet,
                'data-dots'             => $dots,
                'data-nav'              => $nav,
                'data-loop'             => $loop,
                'data-autoplay'         => $autoplay,
                'data-autoplay-timeout' => $autoplayTimeout,
                'data-mouse-drag'       => $mouseDrag,
                'data-touch-drag'       => $touchDrag,
                'data-margin'           => $item_margin['size'],
                'data-mobile-margin'    => $item_margin_on_mobile['size'],
                'data-tablet-margin'    => $item_margin_on_tablet['size'],
            ]
        );

	$cate_list = $settings['select_categories'];
	$parent_cat = array(
		'parent' => 0,
	);
	$filter_cat_arg = array(
		'include'    => $cate_list,
	);
	$cat_arg = array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => $hide_empty_category,
		'orderby'    => 'date',
		'order'      => 'DESC',
		'number'     => 12,
	);

	$cat_args    = array_merge( $cat_arg, $filter_cat_arg, $parent_cat );
	$product_categories   = get_categories( $cat_args );
		?>
		<div <?php echo $this->get_render_attribute_string('slider-wrapper'); ?>>
		<?php
		foreach ($product_categories as $product_category) :
			$cat_thumb = get_term_meta($product_category->term_id, 'thumbnail_id', true);

			$cat_thumnail_url = wp_get_attachment_url($cat_thumb);
			$cat_image = wp_get_attachment_image($cat_thumb, 'pcsfe-thumbnail-tall', false, array(
						'class' => 'card-img',
						'alt' => $product_category->name,
					));
			$cat_image_src = wp_get_attachment_image_src($cat_thumb, 'pcsfe-thumbnail-tall', false);
			if ('layout-1' == $category_layout) :
			?>
			<div class="single-cat category-layout-one">
				<a href="<?php echo get_term_link($product_category->term_id);?>">
					<div class="product-cat-with-thumb" style="background-image: url(<?php echo esc_url( $cat_image_src[0] );?>);">
						<?php if (true == $show_product_count): ?>
						<h5 class="product-count"><?php echo esc_html( $product_category->count ); ?>&nbsp;<?php echo esc_html( $product_text ); ?></h5>
						<?php endif; ?>
						<h5 class="product-title"><?php echo esc_html( $product_category->name );?></h5>
					</div>
				</a>
			</div>
			<?php
		elseif('layout-2' == $category_layout) :
			?>
			<div class="card category-second-layout">
				<?php if (!empty($cat_thumnail_url) && true == $cat_image_show) : ?>
					<a href="<?php echo get_term_link($product_category->term_id);?>"><?php echo $cat_image; ?></a>
					<?php endif; ?>
			  <div class="card-body">
			    <a href="<?php echo get_term_link($product_category->term_id);?>"><h5 class="card-title"><?php echo esc_html( $product_category->name );?></h5></a>
						<ul class="child-categories">
                        	<?php
							$parent_cat_id = array(
								'parent' => $product_category->term_id,
								'hide_empty' => false,
							);
							$product_child_categories = get_terms( 'product_cat', $parent_cat_id );
							if ( $product_categories ) :
								foreach($product_child_categories as $product_child_category) :
									echo '<li>';
									echo '<a href="'.get_term_link($product_child_category->term_id).'" >';
									echo $product_child_category->name;
									echo '</a>';
									echo '</li>';
								endforeach;
							endif;
							wp_reset_query();
                        	?>
                        </ul>
			    <?php
			    if ($show_description == true && !empty($product_category->description)):
			    ?>
			    <p class="card-text"><?php echo esc_html( $product_category->description );?></p>
				<?php endif;
				if (true == $show_shop_now_button) :
				?>
			    	<a href="<?php echo get_term_link($product_category->term_id);?>" class="btn btn-primary"><?php echo esc_html( $button_text );?></a>
				<?php endif; ?>
			  </div>
			</div>
			<?php

		elseif ('layout-3' == $category_layout):
			?>
			<div class="card card-layout-three mb-3">
			  <div class="row no-gutters">
			    <div class="col-md-5">
			      <a href="<?php echo get_term_link($product_category->term_id);?>"><?php echo $cat_image; ?></a>
			    </div>
			    <div class="col-md-7">
			      <div class="card-body">
			        <a href="<?php echo get_term_link($product_category->term_id);?>"><h5 class="card-title"><?php echo esc_html( $product_category->name );?></h5></a>
			        <?php
					if ($show_product_count == true) :
					 ?>
					<div class="product-count">
						<span class="price-new"><?php echo esc_html( $product_category->count ); ?>&nbsp;<?php echo esc_html( $product_text ); ?></span>
					</div> <!-- price-wrap.// -->
					<?php endif;
					if ($show_description == true):
					?>
			        <p class="card-text"><?php echo esc_html( $product_category->description );?></p>
			    	<?php endif;
			    	if (true == $show_shop_now_button) :
			    	?>
			         <a href="<?php echo get_term_link($product_category->term_id);?>" class="btn btn-primary"><?php echo esc_html( $button_text );?></a>
			     	<?php endif; ?>
			      </div>
			    </div>
			  </div>
			</div>
			<?php
		elseif ('layout-4' == $category_layout) :
			?>
		    <figure class="card card-layout-four card-product">
				<div class="img-wrap"> <a href="<?php echo get_term_link($product_category->term_id);?>"><?php echo $cat_image; ?></a></div>
				<figcaption class="info-wrap">
						<a href="<?php echo get_term_link($product_category->term_id);?>"><h4 class="title"><?php echo esc_html( $product_category->name );?></h4></a>
						<?php if ($show_description == true && !empty($product_category->description)): ?>
							<p class="desc"><?php echo esc_html( $product_category->description );?></p>
						<?php endif; ?>
				</figcaption>
				<div class="bottom-wrap">
					<?php if( true == $show_shop_now_button ) : ?>
						<a href="<?php echo get_term_link($product_category->term_id);?>" class="btn btn-sm btn-primary float-right"><?php echo esc_html( $button_text );?></a>
					<?php
					endif;
					if ($show_product_count == true) :
					 ?>
					<div class="product-count h5">
						<span class="price-new"><?php echo esc_html( $product_category->count ); ?>&nbsp;<?php echo esc_html( $product_text ); ?></span>
					</div> <!-- price-wrap.// -->
					<?php endif; ?>
				</div> <!-- bottom-wrap.// -->
			</figure>
			<?php
		endif;
		endforeach;
		wp_reset_query();
		?>
		</div>
		<?php
	}

}

