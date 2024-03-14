<?php

class QiAddonsForElementor_Contact_Form_7_Shortcode_Elementor extends QiAddonsForElementor_Elementor_Widget_Base {

	function __construct( array $data = [], $args = null ) {
		$this->set_shortcode_slug( 'qi_addons_for_elementor_contact_form_7' );

		parent::__construct( $data, $args );
	}
}

if ( qi_addons_for_elementor_framework_is_installed( 'contact_form_7' ) ) {
	qi_addons_for_elementor_register_new_elementor_widget( new QiAddonsForElementor_Contact_Form_7_Shortcode_Elementor() );
}
