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
 * Elementor Element Product Rating
 */
class Product_Rating_Element extends \Elementor\Widget_Base {

	public function get_name() {
		return 'product-rating';
	}

	public function get_title() {
		return __( 'Product Rating', 'kadence-woocommerce-elementor' );
	}

	public function get_icon() {
		return 'eicon-favorite';
	}

	public function get_categories() {
		return array( 'woocommerce-elements' );
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Product Rating', 'kadence-woocommerce-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'important_note',
			array(
				'label' => __( 'Element Information', 'kadence-woocommerce-elementor' ),
				'show_label' => false,
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __( 'This outputs the products rating.', 'kadence-woocommerce-elementor' ),
				'content_classes' => 'kadence-woo-ele-info',
			)
		);
		$this->add_control(
			'product_rating_color',
			[
				'label'     => __( 'Rating Color', 'kadence-woocommerce-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .star-rating, {{WRAPPER}} a.woocommerce-review-link' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'      => 'product_rating_typography',
				'label'     => __( 'Link Typography', 'kadence-woocommerce-elementor' ),
				'selector'  => '{{WRAPPER}} a.woocommerce-review-link',
			)
		);

		$this->add_responsive_control(
			'product_rating_align',
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
			woocommerce_template_single_rating();
	    } else if ( 'ele-product-template' == $post_type ) {
	    	echo '<div class="woocommerce single-product"><div class="product">';
	    	echo '<div class="woocommerce-product-rating">
		<div class="star-rating"><span style="width:80%">Rated <strong class="rating">4.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span></div>		<a href="#reviews" class="woocommerce-review-link" rel="nofollow">(<span class="count">1</span> customer review)</a>	</div>';
		echo '</div></div>';
	    }
	}

	protected function _content_template() {}
}
\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Product_Rating_Element());
