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
 * Elementor Element Product Meta
 */
class Product_Meta_Element extends \Elementor\Widget_Base {

	public function get_name() {
		return 'product-meta';
	}

	public function get_title() {
		return __( 'Product Meta', 'kadence-woocommerce-elementor' );
	}

	public function get_icon() {
		return 'eicon-meta-data';
	}

	public function get_categories() {
		return array( 'woocommerce-elements' );
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Product Meta', 'kadence-woocommerce-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'important_note',
			array(
				'label' => __( 'Element Information', 'kadence-woocommerce-elementor' ),
				'show_label' => false,
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __( 'This outputs the products meta like categories, tags and sku.', 'kadence-woocommerce-elementor' ),
				'content_classes' => 'kadence-woo-ele-info',
			)
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Style Product Meta', 'kadence-woocommerce-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'product_breadcrumbs_color',
			[
				'label'     => __( 'Text Color', 'kadence-woocommerce-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .product_meta' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'product_breadcrumbs_link_color',
			[
				'label'     => __( 'Link Color', 'kadence-woocommerce-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .product_meta a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'      => 'product_breadcrumbs_typography',
				'label'     => __( 'Typography', 'kadence-woocommerce-elementor' ),
				'selector'  => '{{WRAPPER}} .product_meta, {{WRAPPER}} .product_meta a',
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
        	woocommerce_template_single_meta();
	    } else if ( 'ele-product-template' == $post_type ) {
	    	echo '<div class="woocommerce"><div class="product">';
	    	echo '<div class="product_meta">
			<span class="sku_wrapper">SKU: <span class="sku">1234</span></span>
			<span class="posted_in">Category: <a href="#" rel="tag">Example Category</a></span>
			<span class="tagged_as">Tag: <a href="#" rel="tag">Example Tag</a></span>
			</div>';
			echo '</div></div>';
	    }
	}

	protected function _content_template() {}
}
\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Product_Meta_Element());
