<?php

class WC_QuickPay_Admin_Ajax extends WC_QuickPay_Module {

	/**
	 * Perform actions and filters
	 *
	 * @return mixed
	 */
	public function hooks() {
		// Load the base class
		$this->load_api( 'WC_QuickPay_Admin_Ajax_Action', false );

		// Load other actions
		$actions = [
			'WC_QuickPay_Admin_Ajax_Clear_Cache',
			'WC_QuickPay_Admin_Ajax_Empty_Logs',
			'WC_QuickPay_Admin_Ajax_Manage_Payment',
			'WC_QuickPay_Admin_Ajax_Ping',
			'WC_QuickPay_Admin_Ajax_Private_Key',
		];

		foreach ( $actions as $action ) {
			$this->load_api( $action );
		}
	}

	public function load_api( string $class_name, $instantiate = true ): void {
		$name = preg_replace( '/^wc-/', 'woocommerce-', strtolower( str_replace( '_', '-', $class_name ) ) );

		if ( file_exists( __DIR__ . "/ajax/$name.php" ) ) {
			require_once __DIR__ . "/ajax/$name.php";
		}

		if ( $instantiate ) {
			new $class_name();
		}
	}

	public function get_base_url(): string {
		return get_home_url( null, "wc-api/quickpay/", is_ssl() ? 'https' : 'http' );
	}
}
