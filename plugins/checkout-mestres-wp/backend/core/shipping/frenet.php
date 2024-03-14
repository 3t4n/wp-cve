<?php
add_filter('woocommerce_shipping_methods', 'add_cwmp_method_shipping_frenet');
function add_cwmp_method_shipping_frenet($methods){
		$methods['cwmp_method_shipping_frenet'] = 'cwmp_method_shipping_frenet_Method';
	return $methods;
}
	add_action('woocommerce_shipping_init', 'cwmp_method_shipping_frenet_method');
	function cwmp_method_shipping_frenet_method(){
		class cwmp_method_shipping_frenet_Method extends WC_Shipping_Method{
			public function __construct($instance_id = 0){
				$this->id = 'cwmp_method_shipping_frenet';
				$this->instance_id = absint($instance_id);
				$this->domain = 'cwmp_method_shipping_frenet';
				$this->method_title = __('Frenet', $this->domain);
				$this->method_description = __('Shipping method to be used when dealer has a UPS number', $this->domain);
				$this->title = __('Frenet', $this->domain);
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
						'default'        => __('Frenet'),
						'desc_tip'        => true
					)
				);
				$this->enabled = $this->get_option('enabled');
				$this->title   = __('Frenet', $this->domain);
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
					'token' => get_option('cwmo_format_token_frenet'),
					'Content-Type' => 'application/json',
					'Accept' => 'application/json',
				);
				$data = array(
					'SellerCEP' => get_option('woocommerce_store_postcode'),
					'RecipientCEP' => $postcode,
					'ShipmentInvoiceValue' => WC()->cart->cart_contents_total,
					'ShippingServiceCode' => null,
					'ShippingItemArray' => [
						'Width' => array_sum($cart_width),
						'Height' => array_sum($cart_height),
						'Length' => array_sum($cart_length),
						'Weight' => array_sum($cart_weight),
						'Quantity' => '1'
					],
					'RecipientCountry' => 'BR',
				);
				$send = wp_remote_post(CWMP_BASE_URL_FRENET."shipping/quote", array(
				   'method' => 'POST',
				   'headers' => $header,
				   'body' => wp_json_encode($data)
				));
				$retorno = json_decode($send['body']);
				$i=1;
				foreach($retorno->ShippingSevicesArray as $value){
					$this->add_rate(array(
						'id'    => $this->id.":".$i,
						'label' => $this->title." | ".$value->ServiceDescription,
						'cost'  => str_replace(",",".",$value->ShippingPrice),
						'calc_tax'  => "per_order",
						'meta_data' => array(
							'prazo' => $value->DeliveryTime
						)
					));
					$i++;
				}
			}
		}
	}