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
 * Elementor Element Product Related
 */
class Product_Related_Element extends \Elementor\Widget_Base {

	public function get_name() {
		return 'product-related';
	}

	public function get_title() {
		return __( 'Product Related', 'kadence-woocommerce-elementor' );
	}

	public function get_icon() {
		return 'eicon-posts-carousel';
	}

	public function get_categories() {
		return array( 'woocommerce-elements' );
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Product Related', 'kadence-woocommerce-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'important_note',
			array(
				'label' => __( 'Element Information', 'kadence-woocommerce-elementor' ),
				'show_label' => false,
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __( 'This outputs the product\'s related products', 'kadence-woocommerce-elementor' ),
				'content_classes' => 'kadence-woo-ele-info',
			)
		);

		$this->add_control(
			'product_title_color',
			[
				'label'     => __( 'Title Color', 'kadence-woocommerce-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .products > h2, {{WRAPPER}} .products >h3' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'      => 'product_title_typography',
				'label'     => __( 'Title Typography', 'kadence-woocommerce-elementor' ),
				'selector'  => '{{WRAPPER}} .products > h2, {{WRAPPER}} .products >h3',
			)
		);

		$this->add_responsive_control(
			'product_title_align',
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
				'prefix_class' => 'kadence-ele-title-align-',
				'default'      => '',
			]
		);
		$this->end_controls_section();

	}


	protected function render() {

		$post_type = get_post_type();
		if ( 'product' == $post_type ) {
			global $product;
			woocommerce_output_related_products();
	    } else if ( 'ele-product-template' == $post_type ) {
	    	echo '<div class="woocommerce"><div class="product-related-placeholder ele-widget-placeholder">';
			echo '<h2 class="ele-placeholder-title">' . __('Related Products', 'kadence-woocommerce-elementor').'</h2>';
			echo '<h5 class="ele-placeholder-sub">' . __('Placeholder for Related Products', 'kadence-woocommerce-elementor').'</h5>';
			echo '</div></div>';
	    }
	}

	protected function _content_template() {}
}
\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Product_Related_Element());
