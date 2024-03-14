<?php

class FMCD_fmcListingDetails extends FMCD_module {

	public function get_fields() {
		extract($this->module_info['vars']);

		return array(
			'listing' => array(
				'label'           => esc_html__( $title, 'et_builder' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'aasasd', 'fmcd-divi' ),
				'toggle_slug'     => 'main_content',
				'module_class' 	      => 'denixis',
			),
		);
	}	
}

new FMCD_fmcListingDetails();
