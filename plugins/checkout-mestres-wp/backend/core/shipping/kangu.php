<?php
add_filter('woocommerce_shipping_methods', 'add_cwmp_method_shipping_kangu');
function add_cwmp_method_shipping_kangu($methods){
		$methods['cwmp_method_shipping_kangu'] = 'cwmp_method_shipping_kangu_Method';
	return $methods;
}
	add_action('woocommerce_shipping_init', 'cwmp_method_shipping_kangu_method');
	function cwmp_method_shipping_kangu_method(){
		class cwmp_method_shipping_kangu_Method extends WC_Shipping_Method{
			public function __construct($instance_id = 0){
				$this->id = 'cwmp_method_shipping_kangu';
				$this->instance_id = absint($instance_id);
				$this->domain = 'cwmp_method_shipping_kangu';
				$this->method_title = __('KANGU', $this->domain);
				$this->method_description = __('Shipping method to be used when dealer has a UPS number', $this->domain);
				$this->title = __('KANGU', $this->domain);
				$this->supports = array(
					'shipping-zones',
					'instance-settings',
					'instance-settings-modal',
				);
				$this->instance_form_fields = array(
					'enabled' => array(
						'title'         => __('Enable/Disable'),
						'type'             => 'checkbox',
						'label'         => __('Enable this shipping method'),
						'default'         => 'yes',
					),
					'title' => array(
						'title'         => __('Method Title'),
						'type'             => 'text',
						'description'     => __('This controls the title which the user sees during checkout.'),
						'default'        => __('KANGU'),
						'desc_tip'        => true
					)
				);
				$this->enabled = $this->get_option('enabled');
				$this->title   = __('KANGU', $this->domain);
				add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
			}
			public function calculate_shipping($package = array()){
				global $woocommerce;
				$postcode = WC()->customer->get_shipping_postcode();
				$items = $woocommerce->cart->get_cart();
				$cart_length = array();
				$cart_width = array();
				$cart_height = array();
				$cart_weight = array();
				foreach($items as $item => $values) {
					$_product =  wc_get_product( $values['data']->get_id());
					$cart_length[] = $_product->get_length()*$values['quantity'];
					$cart_width[] = $_product->get_width()*$values['quantity'];
					$cart_height[] = $_product->get_height()*$values['quantity'];
					$cart_weight[] = $_product->get_weight()*$values['quantity'];
				}
				$header = array(
					'token' => ''.get_option('cwmo_format_token_kangu').'',
					'Content-Type' => 'application/json; charset=utf-8'
				);
				$data = array(
					'cepOrigem' => get_option('woocommerce_store_postcode'),
					'cepDestino' => $postcode,
					'vlrMerc' => WC()->cart->cart_contents_total,
					'pesoMerc' => array_sum($cart_weight),
					'volumes' => [
						'peso' => array_sum($cart_weight),
						'altura' => array_sum($cart_height),
						'largura' => array_sum($cart_width),
						'comprimento' => array_sum($cart_length),
						'tipo' => 'C',
						'valor' => WC()->cart->cart_contents_total,
						'quantidade' => 1
					],
					'produtos' => [
						'peso' => array_sum($cart_weight),
						'altura' => array_sum($cart_height),
						'largura' => array_sum($cart_width),
						'comprimento' => array_sum($cart_length),
						'valor' => WC()->cart->cart_contents_total,
						'quantidade' => 1
					],
					'servicos' => [
						'E','X','M','R'
					]
				);
				$send = wp_remote_post(CWMP_BASE_URL_KANGU."tms/transporte/simular", array(
				   'method' => 'POST',
				   'headers' => $header,
				   'body' => wp_json_encode($data)
				));
				$retorno = json_decode($send['body']);
				$i=1;
				foreach($retorno as $value){
					$this->add_rate(array(
						'id'    => $this->id.":".$i,
						'label' => $this->title." | ".$value->transp_nome,
						'cost'  => str_replace(",",".",$value->vlrFrete),
						'calc_tax'  => "per_order",
						'meta_data' => array(
							'prazo' => $value->prazoEnt
						)
					));
					$i++;
				}
			}
		}
	}