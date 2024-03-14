<?php
/**
 * WcGetnet Abstract Subscription.
 *
 * @package WcGetnet
 */

namespace WcGetnet\WooCommerce\GateWays;

use WC_Payment_Gateway;
use WcGetnet\Services\WcGetnetApi as Service_Api;
use CoffeeCode\Mpdf\QrCode\{QrCode, Output};
use WcGetnet\Controllers\Admin\OrderController;

/**
 * Abstract Payment
 */
abstract class Abstract_Payment extends WC_Payment_Gateway {
	/**
	 * Init actions.
	 *
	 * @return void
	 */
	protected function init_actions() {
		$this->service_api = new Service_Api( $this->seller_id, $this->client_id, $this->client_secret );
		add_action( 'woocommerce_thankyou_' . $this->id, [ $this, 'thank_you_page' ] );

		// Process webhook.
		add_action( 'woocommerce_api_' . $this->id, [ $this, 'webhook_handle' ] );
	}

	/**
	 * Plugin admin options,
	 */
	public function admin_options() {
		$environment = __( 'MODO DE PRODUÇÃO HABILITADO!' );

		if ( $this->environment_name == 'homolog' ) {
			$environment = __( 'MODO DE TESTE (HOMOLOGAÇÃO) HABILITADO!' );
		}

		printf(
			'<h3>%1$s</h3>
			<p>%2$s</p>
			%3$s
			<p><strong>URL para callback:</strong> %4$s</p>
			<h4>%5$s</h4>',
			$this->method_title,
			wp_sprintf( __( 'Metódo de pagamento %s' ), $this->method_title ),
			$this->services_api->build_admin_message_api_credentials_if_empty(),
			get_home_url( null, '/wc-api/' . $this->id ),
			$this->services_api->check_api_credentials() ? $environment : ''
		);
		?>
			<table class="form-table">
				<?php $this->generate_settings_html(); ?>
			</table>
		<?php

		$this->services_api->build_admin_message_api_credentials_if_empty();
	}

	protected function update_order_by_status( $wc_order, $status, $details = [] ) {
		$order_controller = new OrderController( $wc_order );
		$description = isset( $details['description'] ) && $details['description'] ? $details['description'] : '';

		switch ( $status ) {
			case 'APPROVED':
				$order_controller->payment_paid();
				break;

			case 'NOT APPROVED':
				$order_controller->payment_on_hold( wc_clean( $description ) );
				break;

			default:
				$order_controller->payment_on_hold( wc_clean( $description ) );
				break;
		}
	}

	public function thank_you_page( $order_id ) {
		$env_url        = Service_Api::get_environment_url();
		$order = wc_get_order($order_id);
		$getnet_reponse = $order->get_meta('_getnet_response');
		$args = [];

		$statusResponse = $this->get_response_status( $getnet_reponse );

		if ( 'getnet-billet' === $this->id ) {
			$args = [
				'method'       => 'billet',
				'status'       => $this->translate_status( $statusResponse ),
				'billet_link'  => isset( $getnet_reponse['boleto']['_links'] ) ? $getnet_reponse['boleto']['_links'] : '',
				'typeful_line' => isset( $getnet_reponse['boleto']['typeful_line'] ) ? $getnet_reponse['boleto']['typeful_line'] : '',
				'pdf_link'     => isset( $getnet_reponse['boleto']['_links'][0]['href'] ) ? $env_url . $getnet_reponse['boleto']['_links'][0]['href'] : '',
				'html_link'    => isset( $getnet_reponse['boleto']['_links'][1]['href'] ) ? $env_url . $getnet_reponse['boleto']['_links'][1]['href'] : '',
			];
		}

		if ( 'getnet-creditcard' === $this->id ) {
			$status = $this->get_response_status( $getnet_reponse );

			$args = [
				'method'      => 'creditcard',
				'status'      => $this->translate_status( $status ),
				'credit_info' => isset( $getnet_reponse['credit'] ) ? $getnet_reponse['credit'] : [],
			];
		}

		if ( 'getnet-pix' === $this->id ) {
			$resp_additional_data = $getnet_reponse['additional_data'] ?? [];
			$status = $this->get_response_status( $getnet_reponse );

			if( isset( $resp_additional_data['qr_code'] ) ) {
				$qrCode = new QrCode( esc_attr( $resp_additional_data['qr_code'] ) );
				$image = (new Output\Png())->output($qrCode, 150);
			}

			$args = [
				'method'      => 'pix',
				'pix_key'     => isset( $resp_additional_data['qr_code'] ) ? $resp_additional_data['qr_code'] : '',
				'qr_code'     => isset( $image ) ? base64_encode($image) : '',
				'status_msg'  => $this->translate_status( $status, $getnet_reponse ),
				'status_code' => $status,
			];
		}

		if ( ! $args ) {
			return;
		}

		\WcGetnet::render( 'partials/checkout/thank-you', compact( 'order_id', 'args' ) );
	}


	protected function translate_status( $status , $description = '' ) {
		switch ( $status ) {
			case 'APPROVED':
				return __( 'Autorizada', 'wc_getnet' );

			case 'NOT APPROVED':
				return __( 'Não Autorizada', 'wc_getnet' );

			case 'DENIED':
				$description = $description['description'] ?? __( 'Falha ao processar a transação', 'wc_getnet' );
				return __( $description, 'wc_getnet' );

			case 'DENY':
				$description = $description['description'] ?? __( 'Não Autorizada', 'wc_getnet' );
				return __( $description, 'wc_getnet' );

			case 'PENDING':
			case 'WAITING':
				$description = $description['description'] ?? __( 'Aguardando pagamento', 'wc_getnet' );
				return __( $description, 'wc_getnet' );

			default:
				return __( 'Indefinido', 'wc_getnet' );
		}
	}

	protected function get_response_status( $getnet_reponse ) {
		if ( isset( $getnet_reponse['status'] ) ) {
			return $getnet_reponse['status'];
		}

		if ( ! isset( $getnet_reponse['details'] ) ) {
			return __( 'No Status', 'wc_getnet');
		}

		$details = current( $getnet_reponse['details'] );

		if( isset( $details['antifraud'] ) ) {
			return $details['antifraud']['code'];
		}

		return $details && isset( $details['status'] ) ? $details['status'] : __( 'No Status', 'wc_getnet' );
	}

	/**
	 * Webhook handle.
	 *
	 * @return mixed
	 */
	public function webhook_handle() {
		$args = $this->service_api->get_webhook_args( $_GET );

		if ( ! array_filter( $args ) ) {
			wp_send_json_error( __( 'Invalid parameters', 'wc_getnet' ), 401 );
		}

		if ( isset( $_GET['payment_type'] ) ) {
			$check_payment_type = $this->service_api->check_payment_type( $args['payment_type'], $this->id );

			if ( ! $check_payment_type ) {
				wp_send_json_error( __( 'Invalid payment type from request', 'wc_getnet' ), 401 );
			}
		}

		$response = $this->service_api->process_webhook( $args, $this->id );

		wp_send_json_success(
			[ 'message' => $response ],
			200
		);
	}
}
