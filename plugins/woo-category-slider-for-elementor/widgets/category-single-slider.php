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

class pcsfe_category_single_slider extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve pcsfe_category_single_slider widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'pcsfe_category_single_slider';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve pcsfe_category_single_slider widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Category Single Slider', PCSFE_TEXT_DOMAIN );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve pcsfe_category_single_slider widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fa fa-sliders';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the pcsfe_category_single_slider widget belongs to.
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
	 * Register pcsfe_category_single_slider widget controls.
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
				'label'        => __( 'Product Text', 'PCSFE_TEXT_DOMAIN' ),
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
			'category_layout',
			[
				'label' => __( 'Category Layout', PCSFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::SELECT,
				'multiple' => true,
				'options' => [
					'layout-1' => __( 'Layout 1', PCSFE_TEXT_DOMAIN ),
				],
			]
		);

		$this->add_control(
			'cat_image_show',
			[
				'label'        => __( 'Image Show', 'PCSFE_TEXT_DOMAIN' ),
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
				'default' => 1,
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
			'smartspeed',
			[
				'label'     => __( 'Smart Speed', 'PCSFE_TEXT_DOMAIN' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => '250',
			]
		);
		$this->add_control(
			'autoplaySpeed',
			[
				'label'     => __( 'Autoplay Speed', 'PCSFE_TEXT_DOMAIN' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => '250',
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
				'default' => 1,

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
					'{{WRAPPER}} .cat-slider-area .single-cat-slider-item .single-cat-slider-content h1' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cat_title_typhography',
				'selector' => '{{WRAPPER}} .cat-slider-area .single-cat-slider-item .single-cat-slider-content h1',
			]
		);

		$this->add_responsive_control(
			'cat_title_margin',
			[
				'label' => __( 'margin', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .cat-slider-area .single-cat-slider-item .single-cat-slider-content h1' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
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
					'{{WRAPPER}} .cat-slider-area .single-cat-slider-item .single-cat-slider-content p.single-cat-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cat_paragraph_typography',
				'selector' => '{{WRAPPER}} .cat-slider-area .single-cat-slider-item .single-cat-slider-content p.single-cat-description',

			]
		);
		$this->add_responsive_control(
			'cat_paragraph_margin',
			[
				'label' => __( 'margin', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .cat-slider-area .single-cat-slider-item .single-cat-slider-content p.single-cat-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
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
					'{{WRAPPER}} .single-cat-product-count' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cat_count_typography',
				'selector' => '{{WRAPPER}} .single-cat-product-count',

			]
		);
		$this->add_responsive_control(
			'cat_count_margin',
			[
				'label' => __( 'margin', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .single-cat-product-count' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
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
				'selector' => '{{WRAPPER}} .cat-slider-area .single-cat-slider-item .single-cat-slider-content .single-cat-btn',
			]
		);
		$this->add_control(
			'button_text_color',
			[
				'label' => __( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .cat-slider-area .single-cat-slider-item .single-cat-slider-content .single-cat-btn' => 'fill: {{VALUE}}; color: {{VALUE}};',
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
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .cat-slider-area .single-cat-slider-item .single-cat-slider-content .single-cat-btn' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .cat-slider-area .single-cat-slider-item .single-cat-slider-content .single-cat-btn:hover,{{WRAPPER}} .cat-slider-area .single-cat-slider-item .single-cat-slider-content .single-cat-btn:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'button_background_hover_color',
			[
				'label' => __( 'Background Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cat-slider-area .single-cat-slider-item .single-cat-slider-content .single-cat-btn:hover,{{WRAPPER}} .cat-slider-area .single-cat-slider-item .single-cat-slider-content .single-cat-btn:focus' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .cat-slider-area .single-cat-slider-item .single-cat-slider-content .single-cat-btn:hover,{{WRAPPER}} .cat-slider-area .single-cat-slider-item .single-cat-slider-content .single-cat-btn:focus' => 'border-color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .cat-slider-area .single-cat-slider-item .single-cat-slider-content .single-cat-btn',
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
					'{{WRAPPER}} .cat-slider-area .single-cat-slider-item .single-cat-slider-content .single-cat-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .cat-slider-area .single-cat-slider-item .single-cat-slider-content .single-cat-btn',
			]
		);

		$this->add_responsive_control(
			'text_padding',
			[
				'label' => __( 'Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .cat-slider-area .single-cat-slider-item .single-cat-slider-content .single-cat-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .cat-slider-area .owl-carousel .owl-nav .owl-prev,{{WRAPPER}} .cat-slider-area .owl-nav .owl-next' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'navigation_text_color',
			[
				'label' => __( 'Text Color', PCSFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cat-slider-area .owl-carousel .owl-nav .owl-prev .slider-left-arrow,{{WRAPPER}} .cat-slider-area .owl-nav .owl-next .slider-right-arrow' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'navigation_border',
				'selector' => '{{WRAPPER}} .cat-slider-area .owl-carousel .owl-nav .owl-prev,{{WRAPPER}} .cat-slider-area .owl-nav .owl-next',
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
					'{{WRAPPER}} .cat-slider-area .owl-carousel .owl-nav .owl-prev,{{WRAPPER}} .cat-slider-area .owl-nav .owl-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .cat-slider-area .owl-carousel .owl-nav .owl-prev:hover,{{WRAPPER}} .cat-slider-area .owl-nav .owl-next:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_navigation_text_color',
			[
				'label' => __( 'Text Color', PCSFE_TEXT_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cat-slider-area .owl-carousel .owl-nav .owl-prev:hover .slider-left-arrow,{{WRAPPER}} .cat-slider-area .owl-nav .owl-next:hover .slider-right-arrow' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'navigation_hover_border',
				'selector' => '{{WRAPPER}} .cat-slider-area .owl-carousel .owl-nav .owl-prev:hover,{{WRAPPER}} .cat-slider-area .owl-nav .owl-next:hover',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'navigation_hover_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .cat-slider-area .owl-carousel .owl-nav .owl-prev:hover,{{WRAPPER}} .cat-slider-area .owl-nav .owl-next:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	/**
	 * Render pcsfe_category_single_slider widget output on the frontend.
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
		$mouseDrag       		   = $settings['mouseDrag'] ? $settings['mouseDrag'] : 'false';
		$touchDrag       		   = $settings['touchDrag'] ? $settings['touchDrag'] : 'false';
		$autoplayTimeout 		   = $settings['autoplayTimeout'] ? $settings['autoplayTimeout'] : '0';
		$slide_to_show   		   = $settings['slide_to_show'];
		$slide_to_show_on_mobile   = $settings['mobile_screen'];
		$slide_to_show_on_tablet   = $settings['tablet_screen'];
		$item_margin     		   = $settings['margin'];
		$item_margin_on_mobile     = $settings['mobile_margin'];
		$item_margin_on_tablet     = $settings['tablet_margin'];
		$show_product_count        = $settings['product_count'];
		$product_text        	   = $settings['product_text'];
		$category_layout           = $settings['category_layout'] ? $settings['category_layout'] : 'layout-1' ;
		$cat_image_show 		   = $settings['cat_image_show'];
		$hide_empty_category       = $settings['hide_empty_category'] ? $settings['hide_empty_category'] : 0;
		$button_text 			   = $settings['button_text'];
		$smartspeed 			   = $settings['smartspeed'];
		$autoplaySpeed 			   = $settings['autoplaySpeed'];
		$this->add_render_attribute(
            'slider-wrapper-pro',
            [
                'class'                 => 'main-slider owl-carousel',
                'id'                    => 'product-cat-'.esc_attr($sliderDynamicId),
                'data-items'       		=> $slide_to_show,
                'data-mobile-items'     => $slide_to_show_on_mobile,
                'data-tablet-items'     => $slide_to_show_on_tablet,
                'data-dots'             => $dots,
                'data-nav'              => $nav,
                'data-loop'             => $loop,
                'data-autoplay'         => $autoplay,
                'data-autoplay-timeout' => $autoplayTimeout,
                'data-smartspeed' 		=> $smartspeed,
                'data-autoplayspeed' 	=> $autoplaySpeed,
                'data-mouse-drag'       => $mouseDrag,
                'data-touch-drag'       => $touchDrag,
                'data-margin'           => $item_margin['size'],
                'data-mobile-margin'    => $item_margin_on_mobile['size'],
                'data-tablet-margin'    => $item_margin_on_tablet['size'],
            ]
        );

	$cate_list = $settings['select_categories'];
	$parent_cat = array();
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

	$cat_args   = array_merge( $cat_arg, $filter_cat_arg, $parent_cat );
	$product_categories   = get_categories( $cat_args );
		if ('layout-1' == $category_layout) :
		?>
		<div class="cat-slider-area">
	        <div <?php echo $this->get_render_attribute_string('slider-wrapper-pro'); ?> >
				<?php
				foreach ($product_categories as $product_category) :
					$cat_thumb = get_woocommerce_term_meta($product_category->term_id, 'thumbnail_id', true);
					$cat_thumnail_url = wp_get_attachment_url($cat_thumb);
					$cat_image = wp_get_attachment_image($cat_thumb, 'large', false, array(
						'alt' => $product_category->name,
					));
				 ?>
	            <div class="single-cat-slider-item">
                    <div class="row m-0 text-left">
                        <div class="col-md-6 p-0 col-lg-6 align-self-center">
                            <div class="single-cat-slider-content">
                                <h1 class="single-cat-title" data-animation-in="fadeIn" data-animation-out="animate-out fadeOut"><?php echo esc_html( $product_category->name );?>
								</h1>
								<?php if(!empty($product_category->description)):  ?>
                                <p class="single-cat-description" data-animation-in="fadeIn" data-animation-out="animate-out fadeOut"><?php echo esc_html( $product_category->description );?></p>
                                <?php
                            	endif;
                                if (true == $show_product_count): ?>
                                <h5 class="single-cat-product-count" data-animation-in="fadeIn" data-animation-out="animate-out fadeOut"><?php echo esc_html( $product_category->count ); ?>
									<?php echo esc_html( $product_text ); ?>
									</h5>
									<?php endif; ?>
	                            <a href="<?php echo get_term_link($product_category->term_id);?>" class="single-cat-btn" data-animation-in="fadeIn" data-animation-out="animate-out fadeOut"><?php echo esc_html( $button_text );?>
	                            </a>

                            </div>
                        </div>
                        <div class="col-md-6 p-0 col-lg-6 text-right align-self-center">
                        	<div class="single-cat-image" data-animation-in="fadeIn" data-animation-out="animate-out fadeOut">
                        		<?php
                        			echo $cat_image;
                        		?>
                        	</div>
                        </div>
                    </div>
	            </div>
	        	<?php endforeach;?>
	        </div>
	    </div>
		<?php
		endif;
		wp_reset_query();
	}

}