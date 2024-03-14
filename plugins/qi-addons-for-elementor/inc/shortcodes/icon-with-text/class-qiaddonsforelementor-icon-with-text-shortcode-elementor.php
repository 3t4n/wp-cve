<?php

class QiAddonsForElementor_Icon_With_Text_Shortcode_Elementor extends QiAddonsForElementor_Elementor_Widget_Base {

	function __construct( array $data = [], $args = null ) {
		$this->set_shortcode_slug( 'qi_addons_for_elementor_icon_with_text' );

		parent::__construct( $data, $args );
	}
}

qi_addons_for_elementor_register_new_elementor_widget( new QiAddonsForElementor_Icon_With_Text_Shortcode_Elementor() );
