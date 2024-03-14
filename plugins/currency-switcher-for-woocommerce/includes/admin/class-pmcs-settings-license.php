<?php

class PMCS_Settings_License extends PMCS_Setting_Abstract {
	public $id = 'license';
	public $title = '';
	public function __construct() {
		$this->title = __( 'License', 'pmcs' );
	}

	public function save() {
		$settings = $this->get_settings();
		WC_Admin_Settings::save_fields( $settings );
	}

	public function before_end() {
		pmcs()->admin->set_show_submit_btn( false );
	}

	public function get_settings() {
		$fields = array();

		$fields[] = array(
			'name'     => '',
			'id'       => 'license_id',
			'type'     => 'pmcs_custom_html',
			'hook'     => 'pm_license_box_pmcs',
		);

		$fields[] = array(
			'type' => 'sectionend',
			'id' => $this->id,
		);

		return $fields;
	}
}
