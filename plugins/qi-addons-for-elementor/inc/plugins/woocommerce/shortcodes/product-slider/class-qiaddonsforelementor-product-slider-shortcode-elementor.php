<?php

class QiAddonsForElementor_Product_Slider_Shortcode_Elementor extends QiAddonsForElementor_Elementor_Widget_Base {

	function __construct( array $data = [], $args = null ) {
		$this->set_shortcode_slug( 'qi_addons_for_elementor_product_slider' );

		parent::__construct( $data, $args );
	}
}

if ( qi_addons_for_elementor_framework_is_installed( 'woocommerce' ) ) {
	qi_addons_for_elementor_register_new_elementor_widget( new QiAddonsForElementor_Product_Slider_Shortcode_Elementor() );
}
