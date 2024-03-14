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
 * Elementor Element Product Description
 */
class Product_Description_Element extends \Elementor\Widget_Base {

	public function get_name() {
		return 'product-description';
	}

	public function get_title() {
		return __( 'Product Description', 'kadence-woocommerce-elementor' );
	}

	public function get_icon() {
		return 'eicon-post-content';
	}

	public function get_categories() {
		return array( 'woocommerce-elements' );
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Product Description', 'kadence-woocommerce-elementor' ),
			)
		);
		$this->add_control(
			'important_note',
			array(
				'label' => __( 'Element Information', 'kadence-woocommerce-elementor' ),
				'show_label' => false,
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __( 'This outputs the products main content area. This can even be a elementor content from your product!', 'kadence-woocommerce-elementor' ),
				'content_classes' => 'kadence-woo-ele-info',
			)
		);

		$this->end_controls_section();

	}


	protected function render() {

		$post_type = get_post_type();
		if ( 'product' == $post_type ) {
        	global $post;
        	$template = get_post_meta( $post->ID,'_kt_woo_ele_product_template', true );
			if ( isset( $template ) && !empty( $template ) && $template != 'default' && $template == 'elementor' ) {
	        	echo '<!-- Save from endless Loop -->';
	        } else {
	        	if ( did_action( 'kadence_woo_ele_content_ran' ) >= 1 ) {
	        		echo '<!-- Save from endless Loop -->';
				} else {
	        		the_content();
	        	}
	        }
	    } else if ( 'ele-product-template' == $post_type ) {
	    	$dummy_desc = '<h2>'. __('Product Description', 'kadence-woocommerce-elementor' ). '</h2><h4>'. __('This outputs your products main content area', 'kadence-woocommerce-elementor' ). '</h4><p>' . __('This can even be a elementor content from your product! While previewing in the template builder this demo content shows.', 'kadence-woocommerce-elementor' ). '</p><p>Mauris eu est placerat, fringilla tellus ut, rhoncus ante. Nulla maximus ultrices ullamcorper. Aliquam dictum risus et odio pellentesque vestibulum. Vestibulum bibendum, erat eget luctus mollis, ante enim tincidunt sapien, at rutrum odio lorem eget ipsum. Vestibulum tincidunt fermentum ornare. Suspendisse consequat malesuada faucibus. Praesent fringilla, turpis nec convallis euismod, velit purus gravida nibh, in sodales orci leo non leo.</p><p>Quisque tempor volutpat libero, aliquet venenatis turpis pulvinar sed. Maecenas eget ullamcorper purus. Vivamus magna libero, gravida at elit quis, eleifend faucibus dolor. Phasellus mattis risus at facilisis consequat. Donec mollis ipsum nec ex laoreet, at euismod metus finibus. Suspendisse interdum quam nec dignissim pulvinar.</p>';
	    	echo apply_filters( 'kadence-woocommerce-elementor-dummy-description', $dummy_desc );
	    }
	}

	protected function _content_template() {}
}
\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Product_Description_Element() );
