<?php
add_filter('woocommerce_shipping_methods', 'add_cwmp_method_shipping_mandabem');
function add_cwmp_method_shipping_mandabem($methods){
		$methods['cwmp_method_shipping_mandabem_pac'] = 'cwmp_method_shipping_mandabem_pac_Method';
		$methods['cwmp_method_shipping_mandabem_sedex'] = 'cwmp_method_shipping_mandabem_sedex_Method';
		$methods['cwmp_method_shipping_mandabem_pac_mini'] = 'cwmp_method_shipping_mandabem_pac_mini_Method';
	return $methods;
}
	add_action('woocommerce_shipping_init', 'cwmp_method_shipping_mandabem_pac_method');
	function cwmp_method_shipping_mandabem_pac_method(){
		class cwmp_method_shipping_mandabem_pac_Method extends WC_Shipping_Method{
			public function __construct($instance_id = 0){
				$this->id = 'cwmp_method_shipping_mandabem_pac';
				$this->instance_id = absint($instance_id);
				$this->domain = 'cwmp_method_shipping_mandabem_pac';
				$this->method_title = __('Manda Bem | Pac', $this->domain);
				$this->method_description = __('Shipping method to be used when dealer has a UPS number', $this->domain);
				$this->title = __('Manda Bem', $this->domain);
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
						'default'        => __('Manda Bem | Pac'),
						'desc_tip'        => true
					)
				);
				$this->enabled = $this->get_option('enabled');
				$this->title   = __('Manda Bem | Pac', $this->domain);
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
				$data_send = array(
					'plataforma_id' => get_option('cwmo_format_appid_mandabem'),
					'plataforma_chave' => get_option('cwmo_format_token_mandabem'),
					'cep_origem' => get_option('woocommerce_store_postcode'),
					'cep_destino' => $postcode,
					'altura' => array_sum($cart_height),
					'largura' => array_sum($cart_width),
					'comprimento' => array_sum($cart_length),
					'peso' => array_sum($cart_weight),
					'servico' => 'PAC',
				);
				$options = array('timeout' => 60, 'body' => $data_send, 'httpversion' => '1.1');
				$response = wp_safe_remote_post(CWMP_BASE_URL_MANDABEM.'ws/valor_envio', $options);
				$retorno = json_decode($response['body']);
				if(str_replace(",",".",$retorno->resultado->PAC->valor)==0.00){}else{
					$this->add_rate(array(
						'id'    => $this->id,
						'label' => $this->title,
						'cost'  => str_replace(",",".",$retorno->resultado->PAC->valor),
						'calc_tax'  => "per_order",
						'meta_data' => array(
							'prazo' => $retorno->resultado->PAC->prazo
						)
					));
				}
			}
		}
	}
	add_action('woocommerce_shipping_init', 'cwmp_method_shipping_mandabem_sedex_method');
	function cwmp_method_shipping_mandabem_sedex_method(){
		class cwmp_method_shipping_mandabem_sedex_Method extends WC_Shipping_Method{
			public function __construct($instance_id = 0){
				$this->id = 'cwmp_method_shipping_mandabem_sedex';
				$this->instance_id = absint($instance_id);
				$this->domain = 'cwmp_method_shipping_mandabem_sedex';
				$this->method_title = __('Manda Bem | Sedex', $this->domain);
				$this->method_description = __('Shipping method to be used when dealer has a UPS number', $this->domain);
				$this->title = __('Manda Bem', $this->domain);
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
						'default'        => __('Manda Bem | Sedex'),
						'desc_tip'        => true
					)
				);
				$this->enabled = $this->get_option('enabled');
				$this->title   = __('Manda Bem | Sedex', $this->domain);
				add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
			}
			public function calculate_shipping($sedexkage = array()){
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
				$data_send = array(
					'plataforma_id' => get_option('cwmo_format_appid_mandabem'),
					'plataforma_chave' => get_option('cwmo_format_token_mandabem'),
					'cep_origem' => get_option('woocommerce_store_postcode'),
					'cep_destino' => $postcode,
					'altura' => array_sum($cart_height),
					'largura' => array_sum($cart_width),
					'comprimento' => array_sum($cart_length),
					'peso' => array_sum($cart_weight),
					'servico' => 'SEDEX',
				);

				$options = array('timeout' => 60, 'body' => $data_send, 'httpversion' => '1.1');
				$response = wp_safe_remote_post('https://mandabem.com.br/ws/valor_envio', $options);
				$retorno = json_decode($response['body']);
				if(str_replace(",",".",$retorno->resultado->SEDEX->valor)==0.00){}else{
				$this->add_rate(array(
					'id'    => $this->id,
					'label' => $this->title,
					'cost'  => str_replace(",",".",$retorno->resultado->SEDEX->valor),
					'calc_tax'  => "per_order",
					'meta_data' => array(
						'prazo' => $retorno->resultado->SEDEX->prazo
					)
				));
				}
			}
		}
	}
	add_action('woocommerce_shipping_init', 'cwmp_method_shipping_mandabem_pac_mini_method');
	function cwmp_method_shipping_mandabem_pac_mini_method(){
		class cwmp_method_shipping_mandabem_pac_mini_Method extends WC_Shipping_Method{
			public function __construct($instance_id = 0){
				$this->id = 'cwmp_method_shipping_mandabem_pac_mini';
				$this->instance_id = absint($instance_id);
				$this->domain = 'cwmp_method_shipping_mandabem_pac_mini';
				$this->method_title = __('Manda Bem | Pac Mini', $this->domain);
				$this->method_description = __('Shipping method to be used when dealer has a UPS number', $this->domain);
				$this->title = __('Manda Bem', $this->domain);
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
						'default'        => __('Manda Bem | Pac Mini'),
						'desc_tip'        => true
					)
				);
				$this->enabled = $this->get_option('enabled');
				$this->title   = __('Manda Bem | Pac Mini', $this->domain);
				add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
			}
			public function calculate_shipping($pac_minikage = array()){
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
				$data_send = array(
					'plataforma_id' => get_option('cwmo_format_appid_mandabem'),
					'plataforma_chave' => get_option('cwmo_format_token_mandabem'),
					'cep_origem' => get_option('woocommerce_store_postcode'),
					'cep_destino' => $postcode,
					'altura' => array_sum($cart_height),
					'largura' => array_sum($cart_width),
					'comprimento' => array_sum($cart_length),
					'peso' => array_sum($cart_weight),
					'servico' => 'PACMINI',
				);
				$options = array('timeout' => 60, 'body' => $data_send, 'httpversion' => '1.1');
				$response = wp_safe_remote_post('https://mandabem.com.br/ws/valor_envio', $options);
				$retorno = json_decode($response['body']);
				if(str_replace(",",".",$retorno->resultado->PACMINI->valor)==0.00){}else{
					$this->add_rate(array(
						'id'    => $this->id,
						'label' => $this->title,
						'cost'  => str_replace(",",".",$retorno->resultado->PACMINI->valor),
						'calc_tax'  => "per_order",
						'meta_data' => array(
							'prazo' => $retorno->resultado->PACMINI->prazo
						)
					));
				}
			}
		}
	}