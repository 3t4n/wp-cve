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
 * Elementor Element Product Additional Info
 */
class Product_Additional_Information_Element extends \Elementor\Widget_Base {

	public function get_name() {
		return 'product-additional-information';
	}

	public function get_title() {
		return __( 'Product Additional Information', 'kadence-woocommerce-elementor' );
	}

	public function get_icon() {
		return 'eicon-bullet-list';
	}

	public function get_categories() {
		return array( 'woocommerce-elements' );
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Product Additional Information', 'kadence-woocommerce-elementor' ),
			)
		);
		$this->add_control(
			'important_note',
			array(
				'label' => __( 'Element Information', 'kadence-woocommerce-elementor' ),
				'show_label' => false,
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __( 'This outputs the products additional information', 'kadence-woocommerce-elementor' ),
				'content_classes' => 'kadence-woo-ele-info',
			)
		);

		$this->end_controls_section();

	}


	protected function render() {

		$post_type = get_post_type();
		if ( 'product' == $post_type ) {
        	global $product;
        	echo '<div class="woocommerce-tabs-list entry-content">';
        		do_action( 'woocommerce_product_additional_information', $product );
        	echo '</div>';
	    } else if ( 'ele-product-template' == $post_type ) {
	    	echo '<div class="woocommerce-tabs-list"><div class="panel--additional_information panel entry-content" id="additional_information">
					<table class="shop_attributes">
					<tbody>
						<tr>
							<th>Name</th>
							<td><p>Value</p></td>
						</tr>
						<tr>
							<th>Name</th>
							<td><p>Value</p></td>
						</tr>
					</tbody>
					</table>
				</div></div>';
	    }
	}

	protected function _content_template() {}
}
\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Product_Additional_Information_Element());
