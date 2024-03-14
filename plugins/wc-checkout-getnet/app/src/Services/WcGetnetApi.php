<?php
/**
 * WcGetnet Api.
 *
 * @package Wc Getnet
 */

declare(strict_types=1);

namespace WcGetnet\Services;

use WcGetnet\Controllers\Admin\OrderController;
use WcGetnet\Entities\WcGetnet_Settings;
use WcGetnet\Entities\WcGetnet_Logs as Logs_Entity;
use WcGetnet\Services\WcGetnetPayment as Services_Payment;
use WP_Error;
use WP_Query;

class WcGetnetApi {
	private const ENVS = [
		'homolog'    => 'https://api-homologacao.getnet.com.br',
		'production' => 'https://api.getnet.com.br'
	];

	private const ANTIFRAUD_CODES = [
		'homolog'    => '1snn5n9w',
		'production' => 'k8vif92e'
	];

	/**
	 * Getnet need this constant on header to identify
	 * a woocommerce requests for statistical purposes
	 */
	private const TRANSACTION_CHANNEL_ENTRY = 'WC';

	public function __construct() {
		$this->environment_name = self::get_selected_environment();
		$this->seller_id        = get_option( "wc_getnet_settings_seller_{$this->environment_name}_id" );
		$this->client_id        = get_option( "wc_getnet_settings_client_{$this->environment_name}_id" );
		$this->client_secret    = get_option( "wc_getnet_settings_client_{$this->environment_name}_secret" );
	}

	public static function getAntifraudCode() {
		$environment = self::get_selected_environment();
		return self::ANTIFRAUD_CODES[$environment]; 
	}

	public static function get_environment_url() {
		$environment = self::get_selected_environment();
		return self::ENVS[$environment];
	}

	public static function get_selected_environment() {
		return get_option( 'wc_getnet_settings_environment' );
	}

	public function check_api_credentials() {
		$hasAllCredentials = true;

		 if( !$this->seller_id ) {
			$hasAllCredentials = false;
		 }

		 if( !$this->client_id ) {
			$hasAllCredentials = false;
		 }

		 if( !$this->client_secret ) {
			$hasAllCredentials = false;
		 }

		 return $hasAllCredentials;
	}

	public function build_admin_message_api_credentials_if_empty() {
		if ( $this->check_api_credentials() ) {
			return;
		}

		return sprintf(
			'<h3 style="color:red">%s</h3>
			<a href="/wp-admin/admin.php?page=getnet-settings">%s</a>',
			__( 'Não há credenciais Getnet definidas para este ambiente!' ),
			__( 'Clique aqui para configurar suas chaves!' ),
		);
	}

	public function send_request_post( $data, $access_token, $type ) {
		$header          = $this->get_header( $access_token );
		$environment_url = self::get_environment_url();

		if ( ! $header ) {
			return new WP_Error(
				'400',
				__( 'Erro ao processar o cabeçalho da requisição', 'wc_getnet' )
			);
		}

		return wp_remote_post(
			$environment_url.'/v1/payments/'.$type,
			[
				'body'        => ( $data ) ? wp_json_encode( $data ) : '',
				'headers'     => $header,
				'method'      => 'POST',
				'timeout'     => 45,
				'data_format' => 'body',
			]
		);
	}

	public function send_request_post_v2( $data, $access_token, $type ) {
		$header          = $this->get_header( $access_token );
		$environment_url = self::get_environment_url();

		if ( ! $header ) {
			return new WP_Error(
				'400',
				__( 'Erro ao processar o cabeçalho da requisição', 'wc_getnet' )
			);
		}

		return wp_remote_post(
			$environment_url.'/v2/payments'.$type,
			[
				'body'        => ( $data ) ? wp_json_encode( $data ) : '',
				'headers'     => $header,
				'method'      => 'POST',
				'timeout'     => 45,
				'data_format' => 'body',
			]
		);
	}

	/**
	 * Get response on default format
	 *
	 * @param string $response
	 * @return void
	 */
	public function getDefaultResponse($response) : object {
		$code          = wp_remote_retrieve_response_code( $response );
		$response_body = wp_remote_retrieve_body( $response );
		$response      = jsonToObject($response_body);

		return (object) [
			'code'     => $code,
			'response' => $response
		];
	}

	/**
	 * Get Header.
	 *
	 * @param string $access_token Access token.
	 * @return array
	 */
	protected function get_header( $access_token = '' ) {
		if ( ! $access_token ) {
			$access_token = Services_Payment::get_auth_token( $this->client_id, $this->seller_id, $this->client_secret );
		}

		if ( ! $access_token ) {
			return [];
		}

		$return['Content-Type'] = 'application/json; charset = utf-8';
		$return['Authorization'] = 'Bearer ' . $access_token;
		$return['seller_id'] = $this->seller_id;
		$return['x-transaction-channel-entry'] = self::TRANSACTION_CHANNEL_ENTRY;

		$payment_method = WC()->session->get( 'chosen_payment_method' );
		
		if ( 'getnet-pix' === $payment_method ) {
			$pix_settings = get_option('woocommerce_getnet-pix_settings');
			if ( isset($pix_settings) && isset($pix_settings["qrcode_expiration_time"]) ) {
				$return['x-qrcode-expiration-time'] = $pix_settings["qrcode_expiration_time"];
			}
		}

		return $return;
	}

	/**
	 * Process getnet notification 1.0 - https://developers.getnet.com.br/api#tag/Notificacoes-1.0
	 *
	 * @param array  $request Request parameters.
	 * @param string $id This Method ID.
	 * @return mixed
	 */
	public function process_webhook( $request, $id ) {
		if ( isset( $request['payment_type'] ) && ! empty( $request['payment_type'] ) && 'getnet-billet' !== $id ) {
			return $this->process_another_types( $request );
		} else {
			return $this->process_billet_payment_step_2( $request );
		}
	}

	/**
	 * Process payment billet paid or cancelled.
	 *
	 * @param array $request Request parameters.
	 * @return mixed
	 */
	protected function process_billet_payment_step_2( $request ) {
		$order_id = $this->get_order_by_billet_id( $request['id'] );

		if ( ! $order_id ) {
			wp_send_json_error( __( 'ID do pedido não encontrado.', 'wc_getnet' ), 401 );
		}

		$order_controller = new OrderController( $order_id );
		$order_controller->is_webhook();

		return $order_controller->update_order_by_webhook( $request['status'] );
	}

	/**
	 * Process any payment types except billet paid or cancelled.
	 *
	 * @param array $request Request parameters.
	 * @return mixed
	 */
	protected function process_another_types( $request ) {
		if ( ! $request['order_id'] ) {
			wp_send_json_error( __( 'ID do pedido não encontrado.', 'wc_getnet' ), 401 );
		}

		$wc_order = wc_get_order( $request['order_id'] );

		if ( ! $wc_order ) {
			wp_send_json_error( __( 'ID do pedido inválido.', 'wc_getnet' ) , 401 );
		}

		$this->is_valid_payment( $wc_order, $request['payment_id'] );

		$order_controller = new OrderController( $wc_order );
		$order_controller->is_webhook();

		return $order_controller->update_order_by_webhook( $request['status'] );
	}

	/**
	 * Get Webhook args from request.
	 *
	 * @param array $get The $_GET parameters.
	 * @return array
	 */
	public function get_webhook_args( $get ) {
		$response = [];

		$response = [
			'payment_type' => isset( $get['payment_type'] ) ? wc_clean( $get['payment_type'] ) : '',
			'order_id'     => isset( $get['order_id'] ) ? wc_clean( $get['order_id'] ) : '',
			'payment_id'   => isset( $get['payment_id'] ) ? wc_clean( $get['payment_id'] ) : '',
			'amount'       => wc_clean( $get['amount'] ),
			'status'       => wc_clean( $get['status'] ),
		];

		if ( 'boleto' === $response['payment_type'] ) {
			$response['billet'] = [
				'id'           => wc_clean( $get['id'] ),
				'bank'         => wc_clean( $get['bank'] ),
				'our_number'   => wc_clean( $get['our_number'] ),
				'typeful_line' => wc_clean( $get['typeful_line'] ),
				'issue_date'   => wc_clean( $get['issue_date'] ),
			];
		}

		if ( 'credit' === $response['payment_type'] ) {
			$response['credit'] = [
				'number_installments' => wc_clean( $get['number_installments'] ),
			];
		}

		if ( isset( $get['id'] ) ) {
			$response['id'] = wc_clean( $get['id'] );
		}

		return $response;
	}

	/**
	 * Check if is valid payment.
	 *
	 * @param WC_Order $wc_order WC Order.
	 * @param string   $payment_id The Getnet Payment ID.
	 * @return mixed
	 */
	protected function is_valid_payment( $wc_order, $payment_id ) {
		$logs = $this->logs_enabled( $wc_order );

		$getnet_response = $wc_order->get_meta( '_getnet_response' );

		if ( ! $getnet_response ) {
			if ( $logs ) {
				Logs_Entity::webhook_log( 'WC GETNET ERROR', 'Invalid reponse order meta!' );
				Logs_Entity::webhook_log( 'WC GETNET ORDER ID', $wc_order->get_id() );
				Logs_Entity::webhook_log( 'WC GETNET RESPONSE', $getnet_response );
			}

			wp_send_json_error( __( 'This order is not valid', 'wc_getnet' ) , 401 );
		}

		if ( isset( $getnet_response['payment_id'] ) && $getnet_response['payment_id'] === $payment_id ) {
			return true;
		}

		if ( $logs ) {
			Logs_Entity::webhook_log( 'WC GETNET ERROR', 'Invalid payment ID!' );
			Logs_Entity::webhook_log( 'WC GETNET ORDER ID', $wc_order->get_id() );
			Logs_Entity::webhook_log( 'WC GETNET RESPONSE', $getnet_response );
		}

		wp_send_json_error( __( 'Invalid payment ID', 'wc_getnet' ) , 401 );
	}

	/**
	 * Check endpoint type.
	 *
	 * @param string $payment_type Request payment type.
	 * @param string $id Payment type ID.
	 * @return boolean
	 */
	public function check_payment_type( $payment_type, $id ) {
		return ( $payment_type === WcGetnet_Settings::payment_statuses_to_notification( $id ) );
	}

	/**
	 * Search Order ID by billet ID.
	 *
	 * @param string $billet_id Billet ID.
	 * @return mixed
	 */
	public function get_order_by_billet_id( $billet_id ) {
		$args = [
			'post_type'      => 'shop_order',
			'post_status'    => wc_get_order_statuses(),
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_query'     => [
				'relation' => 'AND',
				[
					'key'   => '_payment_method',
					'value' => 'getnet-billet',
				],
				[
					'key'   => '_getnet_billet_id',
					'value' => $billet_id,
				],
			],
		];

		$query = new WP_Query( $args );

		return current( $query->posts );
	}

	/**
	 * Logs enable.
	 *
	 * @param WC_Order $wc_order WC Order.
	 * @return bool
	 */
	public function logs_enabled( $wc_order ) {
		$payment_method = $wc_order->get_payment_method();

		$settings = get_option( "woocommerce_{$payment_method}_settings" );

		if ( ! $settings ) {
			return false;
		}

		if ( isset( $settings['logs'] ) && 'yes' === $settings['logs'] ) {
			return true;
		}

		return false;
	}
}
