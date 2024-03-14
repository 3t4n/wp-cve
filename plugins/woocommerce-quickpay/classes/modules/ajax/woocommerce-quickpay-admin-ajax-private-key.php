<?php

class WC_QuickPay_Admin_Ajax_Private_Key extends WC_QuickPay_Admin_Ajax_Action {

	public function action(): string {
		return 'settings/private-key';
	}

	public function execute(): void {
		try {
			if ( empty( $_POST['api_key'] ) ) {
				throw new \Exception( __( 'Please type in the API key before requesting a private key', 'woo-quickpay' ) );
			}

			if ( ! current_user_can( 'manage_woocommerce' ) ) {
				throw new \Exception( __( 'You are not authorized to perform this action.', 'woo-quickpay' ) );
			}

			$api_key = $_POST['api_key'];

			$api = new WC_QuickPay_API( $api_key );

			$response = $api->get( 'account/private-key' );

			wp_send_json_success( $response );
		} catch ( \Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		}
	}
}
