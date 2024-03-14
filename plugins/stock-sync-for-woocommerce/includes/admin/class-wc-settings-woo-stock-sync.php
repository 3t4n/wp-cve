<?php

class WC_Settings_Woo_Stock_Sync extends WC_Settings_Page {
	public function __construct() {
		$this->id    = 'woo_stock_sync';
		$this->label = __( 'Stock Sync', 'woo-stock-sync' );

		add_filter( 'woocommerce_settings_tabs_array', [ $this, 'add_settings_page' ], 20 );

		add_action( 'woocommerce_settings_' . $this->id, [ $this, 'output' ] );
		add_action( 'woocommerce_settings_save_' . $this->id, [ $this, 'save' ] );
		add_action( 'woocommerce_sections_' . $this->id, [ $this, 'output_sections' ] );

		// Custom handler for outputting API credential table
		add_action( 'woocommerce_admin_field_wss_credentials_table', [ $this, 'credentials_table' ], 10, 1 );
	}

	/**
	 * Get sections.
	 *
	 * @return array
	 */
	public function get_sections() {
		$sections = array(
			'' => __( 'Settings', 'woo-stock-sync' ),
		);

		return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
	}

	/**
	 * Get settings array.
	 *
	 * @return array
	 */
	public function get_settings() {
		global $current_section;

		$settings = $this->get_general_settings();

		$settings = apply_filters( 'woocommerce_' . $this->id . '_settings', $settings );

		return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings );
	}

	/**
	 * Get general settings
	 */
	private function get_general_settings() {
		$settings = array(
			array(
				'title' => __( 'Stock Sync', 'woo-stock-sync' ),
				'type' => 'title',
				'id' => $this->id . '_page_options'
			),
		);

		$settings[$this->id . '_enabled'] = array(
			'title' => __( 'Enable', 'woo-stock-sync' ),
			'type' => 'checkbox',
			'id' => $this->id . '_enabled',
			'default' => 'yes',
		);

		$settings[$this->id . '_role'] = array(
			'title' => __( 'Role of this site', 'woo-stock-sync' ),
			'type' => 'select',
			'id' => $this->id . '_role',
			'default' => 'primary',
			'options' => array(
				'primary' => __( 'Primary Inventory', 'woo-stock-sync' ),
				'secondary' => __( 'Secondary Inventory', 'woo-stock-sync' ),
			),
			'desc' => __( '<strong>Primary Inventory</strong> is the main inventory that is used to manage stock quantities on Secondary Inventories. You can have only one Primary Inventory.<br><strong>Secondary Inventories</strong> send stock changes (admin edit, purchases and refunds) to Primary Inventory but they don\'t have logging and tool capabilities.', 'woo-stock-sync' ),
		);

		$settings[$this->id . '_process_model'] = array(
			'title' => __( 'Process model', 'woo-stock-sync' ),
			'type' => 'select',
			'id' => $this->id . '_process_model',
			'default' => 'background',
			'options' => [
				'background' => __( 'Background processing', 'woo-stock-sync' ),
				'foreground' => __( 'Foreground processing', 'woo-stock-sync' ),
			],
			'desc' => __( '<strong>Background processing:</strong> syncing is performed in the background. This is faster option but some servers do not support this properly.<br><strong>Foreground processing:</strong> syncing is performed in the foreground. This can cause a slight delay after changing stock quantity but is more compatible with different servers.', 'woo-stock-sync' ),
			'desc_tip' => false,
		);

		$settings[$this->id . '_batch_size'] = array(
			'title' => __( 'Batch size', 'woo-stock-sync' ),
			'type' => 'number',
			'id' => $this->id . '_batch_size',
			'default' => '10',
			'desc' => __( 'How many products are processed in Push All and Update All tools at a time. Increase number to process more products in batch or decrease if you are having issues with timeout or memory limits. Default: 10', 'woo-stock-sync-pro' ),
			'desc_tip' => true,
			'custom_attributes' => [
				'step' => '1',
				'min' => '1',
				'max' => '100',
			],
		);

		$settings[$this->id . '_log_retention'] = [
			'title' => __( 'Log retention', 'woo-stock-sync' ),
			'type' => 'select',
			'id' => $this->id . '_log_retention',
			'default' => '',
			'options' => [
				'' => __( 'Keep all records', 'woo-stock-sync' ),
				'604800' => __( 'Keep 7 days', 'woo-stock-sync' ),
				'1209600' => __( 'Keep 14 days', 'woo-stock-sync' ),
				'2592000' => __( 'Keep 1 month', 'woo-stock-sync' ),
				'7776000' => __( 'Keep 3 months', 'woo-stock-sync' ),
			]
		];

		if ( wss_is_primary() ) {
			$title = __ ( 'API Credentials - Secondary Inventories', 'woo-stock-sync' );
			$supported_api_credentials = apply_filters( 'woo_stock_sync_supported_api_credentials', 1 );
		} else {
			$title = __ ( 'API Credentials of Primary Inventory', 'woo-stock-sync' );
			$supported_api_credentials = 1;
		}
		
		// Add hidden fields for API credentials so they get processed in WC_Admin_Settings
		// Hidden fields dont contain real data, instead fields are outputted in wss_credentials_table
		// which wouldn't get saved without this
		for ( $i = 0; $i < $supported_api_credentials; $i++ ) {
			$fields = array( 'woo_stock_sync_url', 'woo_stock_sync_api_key', 'woo_stock_sync_api_secret' );
			foreach ( $fields as $field ) {
				$settings[$this->id . '_api_credentials_hidden_' . $field . '_' . $i] = array(
					'type' => 'hidden',
					'id' => woo_stock_sync_api_credentials_field_name( $field, $i ),
				);
			}
		}

		$settings[$this->id . '_api_credentials'] = array(
			'title' => $title,
			'type' => 'wss_credentials_table',
			'id' => $this->id . '_api_credentials',
			'default' => '',
			'sites' => $supported_api_credentials,
		);

		$settings[$this->id . '_page_options_end'] = array(
			'type' => 'sectionend',
			'id' => $this->id . '_page_options'
		);

		return $settings;
	}

	/**
	 * Save settings
	 */
	public function save() {
		parent::save();

		// Clean log
		do_action( 'woo_stock_sync_log_clean' );
	}

	/**
	 * Output credentials table
	 */
	public function credentials_table( $value ) {
		$sites = [];
		for ( $i = 0; $i < $value['sites']; $i++ ) {
			$sites[$i] = [
				'url' => [
					'name' => woo_stock_sync_api_credentials_field_name( 'woo_stock_sync_url', $i ),
					'value' => woo_stock_sync_api_credentials_field_value( 'woo_stock_sync_url', $i ),
				],
				'api_key' => [
					'name' => woo_stock_sync_api_credentials_field_name( 'woo_stock_sync_api_key', $i ),
					'value' => woo_stock_sync_api_credentials_field_value( 'woo_stock_sync_api_key', $i ),
				],
				'api_secret' => [
					'name' => woo_stock_sync_api_credentials_field_name( 'woo_stock_sync_api_secret', $i ),
					'value' => woo_stock_sync_api_credentials_field_value( 'woo_stock_sync_api_secret', $i ),
				],
			];

			// Hide unused fields
			$sites[$i]['hide_row'] = true;
			foreach ( $sites[$i] as $attrs ) {
				if ( ! empty( $attrs['value'] ) ) {
					$sites[$i]['hide_row'] = false;
					break;
				}
			}
		}

		include 'views/credentials-table.html.php';
	}

	/**
	 * Output settings
	 */
	public function output() {
		parent::output();

		include 'views/api-check-modal.html.php';
	}
}

return new WC_Settings_Woo_Stock_Sync();
