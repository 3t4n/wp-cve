<?php

class QiAddonsForElementor_Add_To_Cart_Button_Shortcode_Elementor extends QiAddonsForElementor_Elementor_Widget_Base {

	function __construct( array $data = [], $args = null ) {
		$this->set_shortcode_slug( 'qi_addons_for_elementor_add_to_cart_button' );

		parent::__construct( $data, $args );
	}
}

if ( qi_addons_for_elementor_framework_is_installed( 'woocommerce' ) ) {
	qi_addons_for_elementor_register_new_elementor_widget( new QiAddonsForElementor_Add_To_Cart_Button_Shortcode_Elementor() );
}
