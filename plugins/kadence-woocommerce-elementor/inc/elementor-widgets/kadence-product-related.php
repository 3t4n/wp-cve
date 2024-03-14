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
 * Elementor Element: Kadence Product Related
 *
 * @category class.
 */
class Kadence_Product_Related_Element extends \Elementor\Widget_Base {

	public function get_name() {
		return 'kadence-product-related';
	}

	public function get_title() {
		return __( 'Kadence Related Content', 'kadence-woocommerce-elementor' );
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
				'label' => __( 'Kadence Product Related', 'kadence-woocommerce-elementor' ),
			)
		);
		$this->add_control(
			'important_note',
			array(
				'label'           => __( 'Element Information', 'kadence-woocommerce-elementor' ),
				'show_label'      => false,
				'type'            => \Elementor\Controls_Manager::RAW_HTML,
				'raw'             => __( 'This outputs the product\'s Kadence Related Carousel', 'kadence-woocommerce-elementor' ),
				'content_classes' => 'kadence-woo-ele-info',
			)
		);
		$this->end_controls_section();

	}


	protected function render() {

		$post_type = get_post_type();
		if ( 'product' == $post_type ) {
			echo '<div class="woocommerce-kt-related-content">';
			if ( class_exists( 'Kadence_Related_Content' ) ) {
				Kadence_Related_Content::kt_related_content_output();
			}
			echo '</div>';
		} elseif ( 'ele-product-template' == $post_type ) {
			echo '<div class="woocommerce"><div class="product-related-placeholder ele-widget-placeholder">';
			echo '<h2 class="ele-placeholder-title">' . esc_html__( 'Related Products', 'kadence-woocommerce-elementor' ) . '</h2>';
			echo '<h5 class="ele-placeholder-sub">' . esc_html__( 'Placeholder for Related Products', 'kadence-woocommerce-elementor' ) . '</h5>';
			echo '</div></div>';
		}
	}

	protected function _content_template() {}
}
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Kadence_Product_Related_Element() );
