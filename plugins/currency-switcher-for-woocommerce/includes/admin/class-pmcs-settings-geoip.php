<?php

class PMCS_Settings_Geoip extends PMCS_Setting_Abstract {
	public $id = 'geoip';
	public $title = '';
	public function __construct() {
		$this->title = __( 'GeoIP Rulers', 'pmcs' );
	}

	public function save() {
		$settings = $this->get_settings();
		WC_Admin_Settings::save_fields( $settings );
	}

	public function get_settings() {
		$fields = array();

	
			$fields[] = array(
				'title' => __( 'GeoIP Rulers', 'pmcs' ),
				'type'  => 'title',
				'desc'  => sprintf( __( 'Select currency automatically base on the user country. Upgrade to <a href="%s">Pro version</a> to unlock this feature.', 'pmcs' ), PMCS_PRO_URL ),
				'id'    => $this->id,
			);
		

		$fields[] = array(
			'name'     => __( 'GeoIP', 'pmcs' ),
			'id'       => 'pmcs_geoip',
			'type'     => 'geoip_rulers',
		);

		$fields[] = array(
			'type' => 'sectionend',
			'id' => $this->id,
		);

		return $fields;
	}
}
