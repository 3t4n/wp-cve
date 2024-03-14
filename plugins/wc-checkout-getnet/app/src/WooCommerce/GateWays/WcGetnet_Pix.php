<?php

namespace WcGetnet\WooCommerce\GateWays;

use stdClass;
use WC_Order;
use WcGetnet\Entities\WcGetnet_Logs as Logs_Entity;
use WcGetnet\Entities\WcGetnet_Settings as Settings_Entity;
use WcGetnet\Services\WcGetnetPayment as Services_Payment;
use WcGetnet\Services\WcGetnetApi as Services_API;
use WcGetnet\WooCommerce\GateWays\AdminSettingsFields\Pix;

class WcGetnet_Pix extends Abstract_Payment {

	public function __construct() {
		$this->services_api = new Services_API();

		$this->id                 = 'getnet-pix';
		$this->icon               = '';
		$this->has_fields         = true;
		$this->method_title       = __( 'Getnet Pix' );
		$this->method_description = __( 'Metódo de pagamento GETNET Pix' );
		$this->supports           = [ 'products' ];

		$this->init_form_fields();
		$this->init_settings();

		$this->title           		  = $this->get_option( 'title' );
		$this->description     		  = $this->get_option( 'description' );
		$this->enabled         		  = $this->get_option( 'enabled' );
		$this->order_prefix    		  = $this->get_option( 'order_prefix' );
		$this->qrcode_expiration_time = $this->get_option( 'qrcode_expiration_time' );
		$this->expiration_date		  = $this->get_option( 'expiration_date' );
		$this->instructions    		  = $this->get_option( 'instructions' );
		$this->logs            		  = 'yes' === $this->get_option( 'logs' );
		$this->discount_name   		  = $this->get_option( 'discount_pix_name' );
		$this->discount_amount 		  = $this->get_option( 'discount_pix_amount' );

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
			Pix::getHeaderFields(),
			Pix::getBasicFields(),
			Pix::getDiscountFields()
		);
	}

	public function admin_options() {
		$this->handleHeader();
		$this->handleContent();

		$this->services_api->build_admin_message_api_credentials_if_empty();
	}

	public function handleHeader() {
		?> 
		<div class="gnt-header-container admin-config"> 
			<div class="gnt-header-item">
				<p class="group-title">
					<b>Getnet PIX</b>
				</p>
				<?php $this->generate_settings_html(Pix::getHeaderFields()); ?>
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
			<div id="gntAdminPixConfig" class="gnt-container"> 
				<div class="gnt-group">
					<p class="group-title">
						<b>Configurações Básicas</b>
					</p>
					<?php $this->generate_settings_html(Pix::getBasicFields()); ?>
				</div>
				<div class="gnt-group" style="height: fit-content;">
					<p class="group-title">
						<b>Configurações de Desconto</b>
					</p>
					<?php $this->generate_settings_html(Pix::getDiscountFields()); ?>
				</div>
			</div>
		<?php
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

	public function validate_fields() {
		return true;
	}

	public function payment_fields() {
		if ( $this->description ) {
			if ( $this->environment_name == 'homolog' ) {
				$this->description .= '<br><b>'.__( 'MODO DE TESTE (HOMOLOGAÇÃO) HABILITADO!' ).'</b>';
				$this->description  = trim( $this->description );
			}

			echo wpautop( wp_kses_post( $this->description ) );
		}

		$discount = '';
		if ( $this->discount_amount ) {
			$amount   = (float) str_replace( ',', '.', $this->discount_amount );
			$discount = '<span style="color:#EE1C25;">' . __( 'Pix com ' ) . $amount . __( '% de desconto.' ) . '</span><br>';
		}

		printf('<div id="wc-%s-form"
			style="background:transparent;">
			<div class="clear"></div><img src="%s"></div>%s',
			esc_attr( $this->id ),
			esc_url( \WcGetnet::core()->assets()->getAssetUrl( 'images/pix.png' ) ),
			$discount
		);
	}

	public function process_payment( $order_id ) {
		$access_token = Settings_Entity::post( $this->id . '-access_token', FILTER_SANITIZE_STRING );
		$order        = wc_get_order( $order_id );

		$data = [
			'amount'      	  => (int) ( $order->get_total() * 100 ),
			'currency'    	  => 'BRL',
			'order_id'    	  => $this->set_order_id( $order )->order_id,
			'customer_id'	  => Services_Payment::set_customer( $order )->name,
			'idempotency_key' => getUUID()
		];
		
		$response      = $this->service_api->send_request_post_v2( $data, $access_token, '/qrcode/pix' );
		$code          = wp_remote_retrieve_response_code( $response );
		$response_body = wp_remote_retrieve_body( $response );
		$response      = json_decode( $response_body, true );
		$details       = isset ( $response['details'] ) ? current( $response['details'] ) : [];
		$status        = '';

		if ( in_array( $code, [200, 201] ) ) {
			if (  $this->logs ) {
				Logs_Entity::get_pix_order( 'ID DO PEDIDO:', $order_id );
				Logs_Entity::get_pix_order( 'REQUISIÇÃO:', $data );
				Logs_Entity::get_pix_order( 'RESPOSTA:', $response );
			}

			$status = isset( $response['status'] ) ? $response['status'] : '';
		} else {
			if (  $this->logs ) {
				Logs_Entity::get_pix_error( 'ID DO PEDIDO', $order_id );
				Logs_Entity::get_pix_error( 'REQUISIÇÃO:', $data );
				Logs_Entity::get_pix_error( 'RESPOSTA:', $response );
			}

			$status = isset( $details['status'] ) ? $details['status'] : '';
		}

		$order->update_meta_data( '_getnet_response', $response );

		$this->update_order_by_status( $order, $status, $details );

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
}
