<?php

namespace Ilabs\BM_Woocommerce\Gateway\Hooks;

class Payment_On_Account_Page {

	public function init() {

		add_action( 'wp', function () {
			if ( is_wc_endpoint_url( 'order-pay' ) ) {
				$this->payment_on_account_page_stage_1();
			}
		} );

		if ( isset( $_POST['autopay_checkout_on_account_page'] ) ) {
			$this->payment_on_account_page_stage_2();


		}

		if ( isset( $_REQUEST['autopay_payment_on_account_page'] )
		     && '1' === $_REQUEST['autopay_payment_on_account_page'] ) {
			$this->payment_on_account_page_stage_3();
		}
	}

	private function payment_on_account_page() {

	}

	private function payment_on_account_page_stage_2() {
		blue_media()
			->get_woocommerce_logger()
			->log_debug( '[payment_on_account_page_stage_2]' );

		add_filter( 'autopay_payment_on_account_page',
			function ( bool $return ) {
				return true;
			} );
	}

	private function payment_on_account_page_stage_3() {
		blue_media()
			->get_woocommerce_logger()
			->log_debug( '[payment_on_account_page_stage_3]' );

		if ( ! empty( $_GET['sig'] ) && ! empty( $_GET['order_id'] ) ) {
			$signature = sanitize_key( $_GET['sig'] );
			$order_id  = (int) $_GET['order_id'];

			$signature_verified = self::verify_signature( $signature,
				$order_id );

			blue_media()
				->get_woocommerce_logger()
				->log_debug( sprintf( '[payment_on_account_page_stage_3] [OrderId: %s] [Sig: %s] [verified: %s]',
						$order_id,
						$signature,
						$signature_verified ? 'true' : 'false',
					)
				);

			$order_params_recovered = get_post_meta( $order_id,
				'bm_order_payment_params', true );

			blue_media()
				->get_woocommerce_logger()
				->log_debug( sprintf( '[payment_on_account_page_stage_3] [order_params_recovered: %s]',
						serialize( $order_params_recovered ),
					)
				);

			if ( ! is_object( WC()->session ) ) {
				WC()->initialize_session();
			}

			WC()->session->set( 'bm_order_payment_params',
				$order_params_recovered );
			WC()->session->save_data();
		}

		add_filter( 'autopay_filter_can_redirect_to_payment_gateway',
			function ( bool $return ) {
				return true;
			} );
	}

	private function payment_on_account_page_stage_1() {
		blue_media()
			->get_woocommerce_logger()
			->log_debug( '[payment_on_account_page_stage_1]' );

		add_filter( 'autopay_filter_option_whitelabel',
			function ( string $whitelabel ) {
				return 'no';
			} );

		add_action( 'autopay_after_payment_field', function () {
			echo "<input type='hidden' name='autopay_checkout_on_account_page'  value='1' />";
		} );
	}

	private static function verify_signature(
		string $test_signature,
		int $order_id
	): bool {
		$secret    = NONCE_KEY . AUTH_KEY;
		$signature = hash_hmac( 'sha256', $order_id, $secret );

		return $test_signature === $signature;
	}

	public static function generate_signature( int $order_id ): string {
		$secret = NONCE_KEY . AUTH_KEY;

		return hash_hmac( 'sha256', $order_id, $secret );
	}
}
