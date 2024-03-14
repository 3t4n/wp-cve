<?php
/**
 * Build Elementor Element
 *
 * @package Kadence Woocommerce Elementor.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Element Product Breadcrumbs
 */
class Product_Breadcrumbs_Element extends \Elementor\Widget_Base {

	public function get_name() {
		return 'product-breadcrumbs';
	}

	public function get_title() {
		return __( 'Product Breadcrumbs', 'kadence-woocommerce-elementor' );
	}

	public function get_icon() {
		return 'eicon-divider-shape';
	}

	public function get_categories() {
		return array( 'woocommerce-elements' );
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Product Breadcrumbs', 'kadence-woocommerce-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'important_note',
			array(
				'label' => __( 'Element Information', 'kadence-woocommerce-elementor' ),
				'show_label' => false,
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __( 'This outputs the products breadcrumbs.', 'kadence-woocommerce-elementor' ),
				'content_classes' => 'kadence-woo-ele-info',
			)
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Style Breadcrumbs', 'kadence-woocommerce-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'product_breadcrumbs_color',
			[
				'label'     => __( 'Text Color', 'kadence-woocommerce-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-breadcrumb' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'product_breadcrumbs_link_color',
			[
				'label'     => __( 'Link Color', 'kadence-woocommerce-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-breadcrumb a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'      => 'product_breadcrumbs_typography',
				'label'     => __( 'Typography', 'kadence-woocommerce-elementor' ),
				'selector'  => '{{WRAPPER}} .woocommerce-breadcrumb, {{WRAPPER}} .woocommerce-breadcrumb a',
			)
		);

		$this->add_responsive_control(
			'product_breadcrumbs_align',
			[
				'label'        => __( 'Alignment', 'kadence-woocommerce-elementor' ),
				'type'         => \Elementor\Controls_Manager::CHOOSE,
				'options'      => [
					'left'   => [
						'title' => __( 'Left', 'kadence-woocommerce-elementor' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'kadence-woocommerce-elementor' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'kadence-woocommerce-elementor' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'prefix_class' => 'elementor%s-align-',
				'default'      => 'left',
			]
		);

		$this->end_controls_section();

	}


	protected function render() {

		$post_type = get_post_type();
		if ( 'product' == $post_type ) {
			echo '<div class="product_woo_breadcrumbs">';
				woocommerce_breadcrumb();
			echo '</div>';
	    } else if ( 'ele-product-template' == $post_type ) {
	    	echo '<div class="woocommerce"><div class="product"><div class="product_woo_breadcrumbs">';
	    	echo '<nav class="woocommerce-breadcrumb"><a href="#">Home</a>&nbsp;/&nbsp;<a href="#">Category</a>&nbsp;/&nbsp;Product Example</nav>';
			echo '</div></div></div>';
	    }
	}

	protected function _content_template() {}
}
\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Product_Breadcrumbs_Element());
