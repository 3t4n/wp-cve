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
 * Elementor Element Product Title
 */
class Product_Title_Element extends \Elementor\Widget_Base {

	public function get_name() {
		return 'single-product-title';
	}

	public function get_title() {
		return __( 'Product title', 'kadence-woocommerce-elementor' );
	}

	public function get_icon() {
		return 'eicon-heading';
	}

	public function get_categories() {
		return array( 'woocommerce-elements' );
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Product Title', 'kadence-woocommerce-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'important_note',
			array(
				'label' => __( 'Element Information', 'kadence-woocommerce-elementor' ),
				'show_label' => false,
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __( 'This outputs the products title.', 'kadence-woocommerce-elementor' ),
				'content_classes' => 'kadence-woo-ele-info',
			)
		);

		$this->add_control(
			'product_title_color',
			[
				'label'     => __( 'Title Color', 'kadence-woocommerce-elementor' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .product_title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'      => 'product_title_typography',
				'label'     => __( 'Typography', 'kadence-woocommerce-elementor' ),
				'selector'  => '{{WRAPPER}} .product_title',
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
				'prefix_class' => 'elementor%s-align-',
				'default'      => 'left',
			]
		);

		$this->end_controls_section();

	}


	protected function render() {

		$post_type = get_post_type();
		if ( 'product' == $post_type ) {
			woocommerce_template_single_title();
		} else if ( 'ele-product-template'  == $post_type ) {
			echo '<h1 class="product_title entry-title">' . __('Product Title', 'kadence-woocommerce-elementor' ). '</h1>';
		} else {
			echo  __('This element is not designed for this post type', 'kadence-woocommerce-elementor' );
		}
	}

	protected function _content_template() {}
}
\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Product_Title_Element());
