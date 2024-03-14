<?php

class PMCS_Settings_Switcher extends PMCS_Setting_Abstract {
	public $id = 'switcher';
	public $title = '';
	public function __construct() {
		$this->title = __( 'Switcher', 'pmcs' );
	}

	public function save() {
		$settings = $this->get_settings();
		WC_Admin_Settings::save_fields( $settings );
	}

	public function get_settings() {
		$fields = array();

		$fields[] = array(
			'title' => __( 'Menu Swicther', 'pmcs' ),
			'type'  => 'title',
			'id'    => $this->id,
		);

		$fields[] = array(
			'name'     => __( 'Add currency swicther to main menu', 'pmcs' ),
			'desc_tip' => '',
			'id'       => 'pmcs_add_to_menu',
			'type'     => 'checkbox',
			'desc'     => '',
			'default'  => 'yes',
		);

		$menus_locations = get_registered_nav_menus();

		$fields[] = array(
			'name'     => __( 'Menu Locations', 'pmcs' ),
			'desc_tip' => '',
			'desc'     => __( 'Select menu locations to show currency switcher.', 'pmcs' ),
			'id'       => 'pmcs_show_in_menu_location',
			'type'     => 'multiselect',
			'options'  => $menus_locations,
			'default'  => '',
			'class'    => 'wc-enhanced-select',
		);

		$fields[] = array(
			'name'     => __( 'Show Flags', 'pmcs' ),
			'desc_tip' => '',
			'id'       => 'pmcs_show_flag',
			'type'     => 'checkbox',
			'default'  => 'yes',
			'desc'     => __( 'Show currency flags.', 'pmcs' ),
		);

		$fields[] = array(
			'name'     => __( 'Display Name', 'pmcs' ),
			'desc_tip' => '',
			'id'       => 'pmcs_show_name',
			'type'     => 'select',
			'options'  => array(
				'name' => __( 'Show currency name', 'pmcs' ),
				'code' => __( 'Show currency code', 'pmcs' ),
			),
			'default'  => 'yes',
		);

		$fields[] = array(
			'type' => 'sectionend',
			'id' => $this->id,
		);

		return $fields;
	}
}
