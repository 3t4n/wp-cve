<?php

namespace WcGetnet\WooCommerce\GateWays;

use stdClass;
use WC_Order;
use WcGetnet\Entities\WcGetnet_Logs as Logs_Entity;
use WcGetnet\Entities\WcGetnet_Settings as Settings_Entity;

use WcGetnet\Services\WcGetnetPayment as Services_Payment;
use WcGetnet\Services\WcGetnetApi as Services_API;
use WcGetnet\WooCommerce\GateWays\AdminSettingsFields\CreditCard;

class WcGetnet_CreditCard extends Abstract_Payment {

	protected $services_api;

	public function __construct() {
		$this->services_api = new Services_API();

		$this->id                 = 'getnet-creditcard';
		$this->icon               = '';
		$this->has_fields         = true;
		$this->method_title       = __( 'Getnet Cartão de Crédito' );
		$this->method_description = __( 'Metódo de pagamento GETNET cartão de crédito' );
		$this->supports           = [ 'products', 'refunds' ];

		$this->init_form_fields();
		$this->init_settings();

		$this->title                          = $this->get_option( 'title' );
		$this->description                    = $this->get_option( 'description' );
		$this->enabled                        = $this->get_option( 'enabled' );
		$this->order_prefix                   = $this->get_option( 'order_prefix' );
		$this->min_value_from_installments    = $this->get_option( 'min_value_from_installments' );
		$this->installments                   = $this->get_option( 'installments' );
		$this->installments_interest          = $this->get_option( 'installments_interest' );
		$this->installments_initial_interest  = $this->get_option( 'installments_initial_interest' );
		$this->installments_increase_interest = $this->get_option( 'installments_increase_interest' );
		$this->soft_descriptor 				  = $this->get_option( 'soft_descriptor' );
		$this->creditcardImageEnabled         = 'yes' === $this->get_option( 'creditcard_image' );
		$this->logs                           = 'yes' === $this->get_option( 'logs' );

		$this->environment_name = $this->services_api->get_selected_environment();
		$this->seller_id        = $this->services_api->seller_id;
		$this->client_id        = $this->services_api->client_id;
		$this->client_secret    = $this->services_api->client_secret;

		if ( is_admin() ) {
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [ $this, 'process_admin_options' ] );
		}

		$this->init_actions();
	}

	/**
	 * Plugin init form,
	 */
	public function init_form_fields() {
		if ( ! $this->services_api->check_api_credentials() ) {
			return;
		}

		$this->form_fields = array_merge(
			CreditCard::getHeaderFields(), 
			CreditCard::getBasicFields(),
			CreditCard::getInstallmensFields()
		);
	}

	public function admin_options() {
		$this->handleHeader();
		$this->handleContent();

		$this->services_api->build_admin_message_api_credentials_if_empty();
	}

	public function getEnvironmentMessage() {
		$environment = 'Producão';
		$environmentMessage = __( 'Modo de produção habilitado!' );

		if ( $this->environment_name == 'homolog' ) {
			$environment = 'Homologação';
			$environmentMessage = __( 'Modo de teste habilitado!' );
		}

		if( !$this->services_api->check_api_credentials() ) {
			$environment = 'Ambiente não configurado!';
			$environmentMessage = 'Por favor, faça a configuração de suas credenciais deste ambiente!';
		}

		return (object) compact('environment', 'environmentMessage');
	}

	public function handleHeader() {
		?>
		<div class="gnt-header-container admin-config">
			<div class="gnt-header-item">
				<p class="group-title">
					<b>Cartão de Crédito</b>
				</p>
				<?php $this->generate_settings_html(CreditCard::getHeaderFields()); ?>
			</div>

			<div class="gnt-header-item">
				<p class="group-title">
					<b>Url de Callback</b>
				</p>
				<div class="copy-link">
					<a id="callbackurl" href="<?php echo get_home_url( null, '/wc-api/' . $this->id ) ?>">
						<?php echo get_home_url( null, '/wc-api/' . $this->id ) ?>
					</a>
					<div class="text-image">
						<p id="copied-message">Copiado!</p>
						<img class="img-copy" src="<?php echo esc_url( \WcGetnet::core()->assets()->getAssetUrl( 'images/copy.png' ) )?>">
					</div>
				</div>
			</div>

			<div class="gnt-header-item">
				<p class="group-title">
					<b> Ambiente de <?php echo $this->getEnvironmentMessage()->environment ?></b>
				</p>
				<label class="subtitle">
					<?php echo $this->getEnvironmentMessage()->environmentMessage; ?>
				</label>
			</div>
		</div>
		<?php
	}

	public function handleContent() {
		?>
			<div id="gntAdminCreditConfig" class="gnt-container"> 
				<div class="gnt-group">
					<p class="group-title">
						<b>Configurações Básicas</b>
					</p>
					<?php 
						$this->generate_settings_html(CreditCard::getBasicFields()); 
					?>
				</div>

				<div class="gnt-group">
				<p class="group-title">
					<b>Configurações de parcelamento</b>
				</p>
					<?php 
						$this->generate_settings_html(CreditCard::getInstallmensFields());
					?>
				</div>

				<!-- <div class="gnt-group">
					<?php 
						//$this->generate_settings_html(CreditCard::getInstallmentsByFlagFields()); 
					?>
				</div> -->
			</div>
		<?php
	}

	public function handleCustomComponentGenerate( $key, $data) {
		$field    = $this->get_field_key($key);
		$defaults = array(
			'class'             => '',
			'css'               => '',
			'custom_attributes' => array(),
			'desc_tip'          => true,
			'description'       => '',
			'title'             => '',
		);

		$data = wp_parse_args( $data, $defaults );
		$value = $this->get_option( $key, "" );

		return compact('field', 'data', 'value');
	}

	public function generate_installments_by_flag_html( $key, $data ) {
		$customComponent = $this->handleCustomComponentGenerate($key, $data);
		extract($customComponent, EXTR_PREFIX_SAME, "cf");

		ob_start();
		?>
		<div class="credit-containter-item">
			<label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
			<?php echo $this->get_tooltip_html( $data ); ?>
			<fieldset >
				<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
				<input class="small-input" type="text"
						name="<?php echo esc_attr( $field ); ?>"
						id="<?php echo esc_attr( $field ); ?>" value="<?php echo $value  ?>" />
				<?php echo $this->get_description_html( $data ); ?>
			</fieldset>
		</div>
		<?php
		return ob_get_clean();
	}

	public function generate_creditcard_title_html( $key, $data ) {
		$customComponent = $this->handleCustomComponentGenerate($key, $data);
		extract($customComponent, EXTR_PREFIX_SAME, "cf");

		ob_start();
		?>
		<div class="credit-containter-item">
			<label for="<?php echo esc_attr( $field ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
			<?php echo $this->get_tooltip_html( $data ); ?>
			<fieldset >
				<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
				<input class="small-input" type="text"
						name="<?php echo esc_attr( $field ); ?>"
						id="<?php echo esc_attr( $field ); ?>" value="<?php echo $value  ?>" />
				<?php echo $this->get_description_html( $data ); ?>
			</fieldset>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Validate fields.
	 *
	 * @return void
	 */
	public function validate_fields() {
		$fields = [
			'Erro ao processar cartão de crédito' => Settings_Entity::post( $this->id . '-access_token', FILTER_SANITIZE_STRING ),
		];

		foreach ( $fields as $label => $field ) {
			if ( ! $field ) {
				wc_add_notice( sprintf( __( '%s.', 'wc_getnet' ), $label ), 'error' );
				return false;
			}
		}

		return true;
	}

	// - Todo payments checkout
	/**
	 * Payment fields in frontend.
	 *
	 * @return void
	 */
	public function payment_fields() {
		if ( $this->description ) {
			if ( $this->environment_name == 'homolog' ) {
				$this->description .= '<br><b>'.__( 'MODO DE TESTE (HOMOLOGAÇÃO) HABILITADO!' ).'</b>';
				$this->description  = trim( $this->description );
			}

			echo wpautop( wp_kses_post( $this->description ) );
		}

		if( $this->creditcardImageEnabled ) {
			require_once plugin_dir_path( WC_GETNET_PLUGIN_FILE ) . 'views' . DIRECTORY_SEPARATOR . 'partials' . DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'creditcard-image.php';
		}

		require_once plugin_dir_path( WC_GETNET_PLUGIN_FILE ) . 'views' . DIRECTORY_SEPARATOR . 'partials' . DIRECTORY_SEPARATOR . 'checkout' . DIRECTORY_SEPARATOR . 'creditcard.php';
	}

	/**
	 * Process Payment
	 *
	 * @param int|string $order_id The Order ID.
	 * @return array
	 */
	public function process_payment( $order_id ) {
		$access_token        = Settings_Entity::post( $this->id . '-access_token', FILTER_SANITIZE_STRING );
		$card_hash           = Settings_Entity::post( $this->id . '-card_token', FILTER_SANITIZE_STRING );
		$holder_name         = Settings_Entity::post( $this->id . '_holder_name', FILTER_SANITIZE_STRING );
		$expiry              = Settings_Entity::post( $this->id . '_expiry', FILTER_SANITIZE_STRING );
		$number_installments = (int) Settings_Entity::post( $this->id . '_number_installments', FILTER_SANITIZE_STRING );
		$order               = wc_get_order( $order_id );
		$date                = explode( '/', $expiry );

		$credit_card = [
			'token'               => $card_hash,
			'name'                => sanitize_text_field( $holder_name ),
			'expMonth'            => $date[0],
			'expYear'             => $date[1],
			'number_installments' => $number_installments
		];

		$data = [
			'request_id'  	  => getUUID(),
			'idempotency_key' => getUUID(),
			'order_id'        => $order_id,
			'data'            => (object) 
			[
				'seller_id' 	  => (string) $this->seller_id,
				'amount'    	  => $this->format_amout( $order, $number_installments ),
				'currency'		  => 'BRL',
				'customer_id'     => (string) $order->get_customer_id(),
				'payment'    	  => $this->getCreditData( $number_installments, $credit_card ),
				'additional_data' => $this->getAdditionalData( $order )
			]
		];

		$originalResponse = $this->service_api->send_request_post_v2( $data, $access_token, '' );
		$code             = wp_remote_retrieve_response_code( $originalResponse );
		$response_body    = wp_remote_retrieve_body( $originalResponse );

		$response         = json_decode( $response_body, true );
		$details          = isset ( $response['details'] ) ? current( $response['details'] ): [];
		$status           = '';

		if ( in_array( $code, [ 200, 201 ] ) ) {
			if ( $this->logs ) {
				Logs_Entity::get_creditcard_order( 'ID DO PEDIDO:', $order_id );
				Logs_Entity::get_creditcard_order( 'REQUISIÇÃO:', $data );
				Logs_Entity::get_creditcard_order( 'RESPOSTA:', $response );
			}

			$status = isset( $response['status'] ) ? $response['status'] : '';
		} else {
			if ( $this->logs ) {
				Logs_Entity::get_creditcard_error( 'ID DO PEDIDO:', $order_id );
				Logs_Entity::get_creditcard_error( 'REQUISIÇÃO:', $data );
				Logs_Entity::get_creditcard_error( 'RESPOSTA:', $response );
			}

			$status = isset( $details['status'] ) ? $details['status'] : '';
		}

		if ( empty($code) ) {
			Logs_Entity::get_creditcard_error( 'ERRO INESPERADO:', $originalResponse );
		}

		$order->update_meta_data( '_getnet_response', $response );

		$this->update_order_by_status( $order, $status, $details );

		$order->save();

		WC()->cart->empty_cart();

		return [
			'result'   => 'success',
			'redirect' => $this->get_return_url( $order )
		];
	}

	private function set_order_id( $order ): stdClass {
		return (object) [
		   'order_id' => (string) $order->get_id()
		];
	}

	private function getAntifraudData( $order_id ) {
		$checkOutNonce = Settings_Entity::post( 'woocommerce-process-checkout-nonce', FILTER_SANITIZE_STRING );
		$order = wc_get_order($order_id);
		$cpf = preg_replace( '/[^0-9]/', '', $order->get_meta('_billing_cpf') );

		return [
			'ip_address' => getClientIpAddress(),
			'device_id'  => $cpf.$checkOutNonce
		];
	}

	private function getCreditData( int $installments, array $cc ): stdClass {
		$number_installments = ( (int) $installments < 1 ) ? 1 : (int) $cc['number_installments'];

		$credCardData = [
			'payment_id'          => getUUID(),
			'payment_method'      => 'CREDIT',
			'transaction_type'    => ( $number_installments > 1 ) ? 'INSTALL_NO_INTEREST' : 'FULL',
			'number_installments' => $number_installments,
			'card'                => (object)
			[
				'expiration_month' => (string) $cc['expMonth'],
				'expiration_year'  => (string) $cc['expYear'],
				'cardholder_name'  => (string) $cc['name'],
				'number_token'     => (string) $cc['token']
			]
		];

		if ( !empty($this->soft_descriptor) ) {
			$credCardData['soft_descriptor'] = iconv(mb_detect_encoding($s,'UTF-8, ASCII, ISO-8859-1'),'ASCII//TRANSLIT//INGORE',$this->soft_descriptor);
		}

		return (object) $credCardData;
	}

	private function getAdditionalData( $order_id ): stdClass {
		$order = wc_get_order( $order_id );

		$additional_data = [
			'customer'  	  => Services_Payment::set_customer( $order ),
			'device'          => $this->getAntifraudData( $order_id ),
			'order'			  => $this->getOrderItems(), //Obrigatório para v2 em produção
		];

		return arrayToObject($additional_data);
	}

	private function getOrderItems() {
		return [
			"items" => []
		];
	}

	/**
	 * Set format from credit card.
	 *
	 * @param WC_ORDER $wc_order WC_Order.
	 * @param int|string $number_installments Number Installments.
	 * @return int
	 */
	protected function format_amout( $wc_order, $number_installments ) {
		$total_with_installments = Settings_Entity::post( $this->id . '-total_with_installments', FILTER_SANITIZE_STRING );

		if ( $total_with_installments && $number_installments > 1) {
			return (int) ( $total_with_installments * 100 );
		}

		return (int) ( $wc_order->get_total() * 100 );
	}

	public function is_min_value_from_installments() {
		return floatval( $this->min_value_from_installments ) < floatval( WC()->cart->total );
	}

	/**
	 * Process refund.
	 *
	 * If the gateway declares 'refunds' support, this will allow it to refund.
	 * a passed in amount.
	 *
	 * @param  int        $order_id Order ID.
	 * @param  float|null $amount Refund amount.
	 * @param  string     $reason Refund reason.
	 * @return boolean True or false based on success, or a WP_Error object.
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$order = wc_get_order($order_id);
		$order_data = (object) $order->get_meta('_getnet_response');
		$formatedAmount = str_replace(".", "", $amount);
		
		$order                    = wc_get_order( $order_id );
		$order_received_timestamp = strtotime($order->get_date_created());
		$order_total              = str_replace(".", "", $order->get_total());

		if($order_received_timestamp > time() - 86400) {
			if($formatedAmount < $order_total){
				return new \WP_Error( 'error', __( 'O valor do estorno deve ser o valor total do pedido.', 'woocommerce' ) );
			}
		}

		if($formatedAmount === '000') {
			return new \WP_Error( 'error', __( 'O valor do estorno deve ser maior que $0,00.', 'woocommerce' ) );
		}

		$data = [
			'request_id'  	  => getUUID(),
			'idempotency_key' => getUUID(),
			'payment_id'	  => $order_data->payment_id,
			'payment_method'  => 'CREDIT',
			'amount'		  => $formatedAmount
		];

		$response = $this->refundPayment( $data );

		return $this->processRefundResponse($order_id, $data, $response);
	}

	/**
	 * Do refund payment request
	 *
	 * @param string $payment_id
	 * @return object
	 */
	protected function refundPayment( $data ) {
		$response = $this->service_api->send_request_post_v2( $data, '', "/cancel" );
		return $this->services_api->getDefaultResponse($response);
	}

	/**
	 * Do refund payment request
	 *
	 * @param string $payment_id
	 * @return object
	 */
	protected function processRefundResponse( string $order_id, $request, object $response ) {
		
		switch ($response->code) {
			case 400:
				if ( $this->logs ) {
					Logs_Entity::get_creditcard_order_refund_error( 'ID DO PEDIDO:', $order_id );
					Logs_Entity::get_creditcard_order_refund_error( 'REQUEST:', $request );
					Logs_Entity::get_creditcard_order_refund_error( 'RESPOSTA:', $response );
				}

				return false;
			default:
				if ( $this->logs ) {
					Logs_Entity::get_creditcard_order_refund( 'ID DO PEDIDO:', $order_id );
					Logs_Entity::get_creditcard_order_refund( 'REQUEST:', $request );
					Logs_Entity::get_creditcard_order_refund( 'RESPOSTA:', $response );
				}

				return true;
		}
	}
}
