<?php

class QiAddonsForElementor_Wp_Forms_Shortcode_Elementor extends QiAddonsForElementor_Elementor_Widget_Base {

	function __construct( array $data = [], $args = null ) {
		$this->set_shortcode_slug( 'qi_addons_for_elementor_wp_forms' );

		parent::__construct( $data, $args );
	}
}

if ( qi_addons_for_elementor_framework_is_installed( 'wp_forms' ) ) {
	qi_addons_for_elementor_register_new_elementor_widget( new QiAddonsForElementor_Wp_Forms_Shortcode_Elementor() );
}
