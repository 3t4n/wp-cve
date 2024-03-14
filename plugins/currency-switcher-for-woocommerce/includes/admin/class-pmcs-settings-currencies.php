<?php

class PMCS_Settings_Currencies extends PMCS_Setting_Abstract {
	public $id = 'currencies';
	public $title = '';
	public function __construct() {
		$this->title = __( 'Currencies', 'pmcs' );
	}

	public function save() {
		$settings = $this->get_settings();
		WC_Admin_Settings::save_fields( $settings );
	}

	public function get_settings() {
		$fields = array();

		$fields[] = array(
			'title' => __( 'Currencies Settings', 'pmcs' ),
			'type'  => 'title',
			'desc'  => __( 'Settings currencies for your websites', 'pmcs' ),
			'id'    => $this->id,
		);

		$fields[] = array(
			'name'     => __( 'Auto-insert into single product page', 'text-domain' ),
			'id'       => 'pmcs_currencies',
			'type'     => 'currency_list',
		);

		$fields[] = array(
			'type' => 'sectionend',
			'id' => $this->id,
		);

		return $fields;
	}
}
