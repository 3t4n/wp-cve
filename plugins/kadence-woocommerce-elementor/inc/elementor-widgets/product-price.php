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
 * Elementor Element Product Price
 */
class Product_Price_Element extends \Elementor\Widget_Base {

	public function get_name() {
		return 'product-price';
	}

	public function get_title() {
		return __( 'Product Price', 'kadence-woocommerce-elementor' );
	}

	public function get_icon() {
		return 'fa fa-money';
	}

	public function get_categories() {
		return array( 'woocommerce-elements' );
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Product Price', 'kadence-woocommerce-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'important_note',
			array(
				'label' => __( 'Element Information', 'kadence-woocommerce-elementor' ),
				'show_label' => false,
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __( 'This outputs the products price.', 'kadence-woocommerce-elementor' ),
				'content_classes' => 'kadence-woo-ele-info',
			)
		);

		$this->add_control(
			'product_price_color',
			[
				'label'     => __( 'Price Color', 'kadence-woocommerce-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .price' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'      => 'product_price_typography',
				'label'     => __( 'Typography', 'kadence-woocommerce-elementor' ),
				'selector'  => '{{WRAPPER}} .price, {{WRAPPER}} .price .amount',
			)
		);

		$this->add_responsive_control(
			'product_price_align',
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
        	echo '<div class="entry-summary">';
        		woocommerce_template_single_price();
        	echo '</div>';
	    } else if ( 'ele-product-template' == $post_type ) {
	    	echo '<div class="woocommerce"><div class="product"><div class="entry-summary">';
	    	echo '<p class="price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>30.00</span></p>';
	    	echo '</div></div></div>';
	    }
	}

	protected function _content_template() {}
}
\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Product_Price_Element());
