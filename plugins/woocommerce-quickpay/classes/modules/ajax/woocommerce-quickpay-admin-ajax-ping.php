<?php

class WC_QuickPay_Admin_Ajax_Ping extends WC_QuickPay_Admin_Ajax_Action {

	public function action(): string {
		return 'settings/ping';
	}

	public function execute(): void {
		if ( ! empty( $_POST['api_key'] ) ) {
			try {
				$api = new WC_QuickPay_API( sanitize_text_field( $_POST['api_key'] ) );
				$api->get( '/payments?page_size=1' );
				wp_send_json_success();
			} catch ( QuickPay_API_Exception $e ) {
				wp_send_json_error( $e->getMessage() );
			}
		}

		wp_send_json_error();
	}
}
