<?php

class PMCS_Settings_Exchange_Rate extends PMCS_Setting_Abstract {
	public $id = 'exchange_rate';
	public $title = '';
	public function __construct() {
		$this->title = __( 'Exchange Rate APIs', 'pmcs' );
	}

	public function save() {
		$settings = $this->get_settings();
		WC_Admin_Settings::save_fields( $settings );
		// Update exchange rates.
		pmcs()->exchange_rates->update();
	}

	public function get_settings() {
		$fields = array();

		$fields[] = array(
			'title' => __( 'Exchange Rates', 'pmcs' ),
			'type'  => 'title',
			'desc'  => '',
			'id'    => $this->id,
		);

	
			$fields[] = array(
				'name'     => __( 'Exchange rates updates', 'pmcs' ),
				'id'       => 'pmsc_exchange_rate_update',
				'class'    => 'pmsc_exchange_rate_update',
				'type'     => 'pmcs_custom_select',
				'default'  => 'manual',
				'options'  => array(
					'manual'     => __( 'Update Manual', 'pmcs' ),
					'minutely'   => array(
						'label' => __( 'Update Automatically Every Minute (Pro only)', 'pmcs' ),
						'disable' => true,
					),
					'hourly'     => array(
						'label' => __( 'Update Automatically Hourly (Pro only)', 'pmcs' ),
						'disable' => true,
					),
					'twicedaily' => array(
						'label' => __( 'Update Automatically Twice Daily (Pro only)', 'pmcs' ),
						'disable' => true,
					),
					'daily'      => __( 'Update Automatically Daily', 'pmcs' ),
					'weekly'     => array(
						'label' => __( 'Update Automatically Weekly (Pro only)', 'pmcs' ),
						'disable' => true,
					),
				),
			);
		

		$fields[] = array(
			'name'     => __( 'Exchange rates server', 'pmcs' ),
			'desc_tip' => __( 'Change exchange rates sever, some server may requeired API key.', 'pmcs' ),
			'id'       => 'pmsc_exchange_rate_server',
			'class'    => 'pmsc_exchange_rate_server',
			'type'     => 'select',
			'default'  => 'none',
			'options'  => pmcs()->exchange_rates->get_servers_for_select(),
		);

		$server_settings = pmcs()->exchange_rates->get_settings();

		$fields = array_merge( $fields, $server_settings );

		$fields[] = array(
			'type' => 'sectionend',
			'id' => $this->id,
		);

		return $fields;
	}
}
