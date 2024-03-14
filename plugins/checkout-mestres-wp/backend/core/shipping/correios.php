<?php
add_filter('woocommerce_shipping_methods', 'add_cwmp_method_shipping_correios');
function add_cwmp_method_shipping_correios($methods){
		$methods['cwmp_method_shipping_correios'] = 'cwmp_method_shipping_correios_Method';
	return $methods;
}
	add_action('woocommerce_shipping_init', 'cwmp_method_shipping_correios_Method');
	function cwmp_method_shipping_correios_Method(){
		class cwmp_method_shipping_correios_Method extends WC_Shipping_Method{
			public function __construct($instance_id = 0){
				$this->id = 'cwmp_method_shipping_correios';
				$this->instance_id = absint($instance_id);
				$this->domain = 'cwmp_method_shipping_correios';
				$this->method_title = __('Correios', 'checkout-mestres-wp');
				$this->method_description = __('Send via Correios.', 'checkout-mestres-wp');
				$this->title = __('Correios', 'checkout-mestres-wp');
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
						'default'        => __('Correios'),
						'desc_tip'        => true
					)
				);
				$this->enabled = $this->get_option('enabled');
				$this->title   = __('Correios', 'checkout-mestres-wp');
				add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
			}
			public function cwmp_get_tables_shipping(){
				switch (get_option('cwmo_contrato_correios')) {
					case "BLC":
						$service = "04510,04014";
					break;
					case "CLU1":
						$service = "03085,03050";
					break;
					case "BRO2": case "RAT2": case "OUR4": case "PLA3": case "DIA1S": case "DIA2S": case "DIA3S": case "DIA4S":
						$service = "03298,03220,04227";
					break;
					case "DIA1": case "DIA2": case "DIA3": case "DIA4": case "INF1": case "INF2": case "INF3": case "INF4": case "INF5":
						$service = "03336,03280,04391";
					break;
				}
				return $service;
			}
			public function cwmp_get_services_shipping($servico,$origem,$destino,$altura,$largura,$diametro,$comprimento,$peso,$formato,$maopropria,$valordeclarado,$avisorecebimento){
				$data = array(
					'nCdServico'          => $servico,
					'sCepDestino'         => $origem,
					'sCepOrigem'          => $destino,
					'nVlAltura'           => $altura,
					'nVlLargura'          => $largura,
					'nVlDiametro'         => $diametro,
					'nVlComprimento'      => $comprimento,
					'nVlPeso'             => $peso,
					'nCdFormato'          => $formato,
					'sCdMaoPropria'       => $maopropria,
					'nVlValorDeclarado'   => $valordeclarado,
					'sCdAvisoRecebimento' => $avisorecebimento,
					'Tabela' => get_option('cwmo_contrato_correios')
				);
				$url = "";
				if($data['nCdServico']){ $url .= "&nCdServico=".$data['nCdServico']; } 
				if($data['sCepDestino']){ $url .= "&sCepDestino=".$data['sCepDestino']; } 
				if($data['sCepOrigem']){ $url .= "&sCepOrigem=".$data['sCepOrigem']; } 
				if($data['nVlAltura']){ $url .= "&nVlAltura=".$data['nVlAltura']; } 
				if($data['nVlLargura']){ $url .= "&nVlLargura=".$data['nVlLargura']; } 
				if($data['nVlDiametro']){ $url .= "&nVlDiametro=".$data['nVlDiametro']; } 
				if($data['nVlComprimento']){ $url .= "&nVlComprimento=".$data['nVlComprimento']; } 
				if($data['nVlPeso']){ $url .= "&nVlPeso=".$data['nVlPeso']; } 
				if($data['nCdFormato']){ $url .= "&nCdFormato=".$data['nCdFormato']; } 
				if($data['sCdMaoPropria']){ $url .= "&sCdMaoPropria=".$data['sCdMaoPropria']; } 
				if($data['nVlValorDeclarado']){ $url .= "&nVlValorDeclarado=".$data['nVlValorDeclarado']; } 
				if($data['sCdAvisoRecebimento']){ $url .= "&sCdAvisoRecebimento=".$data['sCdAvisoRecebimento']; } 
				if($data['Tabela']){ $url .= "&Tabela=".$data['Tabela']; } 
				$url .= "&StrRetorno=JSON";
				$send = wp_remote_post(CWMP_BASE_URL_CORREIOS, array(
				   'method' => 'POST',
				   'body' => array('url'=>$url)
				));
				return $send;
			}
			public function cwmp_get_name_services($servico){
				switch ($servico) {
					case "04510": case "03085": case "03298": case "03336":
						return "PAC";
					break;
					case "04014": case "03050": case "03220": case "03280":
						return "SEDEX";
					break;
					case "04227": case "04391":
						return "PAC MINI";
					break;
				}
				return $service;	
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
					if($_product->get_length()!=""){ $cart_length[] = $_product->get_length()*$values['quantity']; }else{ $cart_length[] = get_option('cwmo_shipping_padrao_length')*$values['quantity']; }
					if($_product->get_width()!=""){ $cart_width[] = $_product->get_width()*$values['quantity']; }else{ $cart_width[] = get_option('cwmo_shipping_padrao_width')*$values['quantity']; }
					if($_product->get_height()!=""){ $cart_height[] = $_product->get_height()*$values['quantity']; }else{ $cart_height[] = get_option('cwmo_shipping_padrao_height')*$values['quantity']; }
					if($_product->get_weight()!=""){ $cart_weight[] = $_product->get_weight()*$values['quantity']; }else{ $cart_weight[] = get_option('cwmo_shipping_padrao_weight')*$values['quantity']; }
				}
				$service = $this->cwmp_get_tables_shipping();
				$return = $this->cwmp_get_services_shipping($service,get_option('woocommerce_store_postcode'),$postcode,array_sum($cart_height),array_sum($cart_width),0,array_sum($cart_length),array_sum($cart_weight),1,get_option('cwmo_mao_propria_correios'),get_option('cwmo_valor_declarado_correios'),get_option('cwmo_aviso_recebimento_correios'));
				$resultado = json_decode($return['body']);
				$i=1;
				foreach($resultado->cResultado->Servicos AS $retorno){
					if(str_replace(",",".",$retorno->cServico->Valor)==0.00){}else{
						$this->add_rate(array(
							'id'    => $this->id.":".$i,
							'label' => $this->cwmp_get_name_services($retorno->cServico->Codigo),
							'cost'  => str_replace(",",".",$retorno->cServico->Valor),
							'calc_tax'  => "per_order",
							'meta_data' => array(
								'prazo' => $retorno->cServico->PrazoEntrega
							)
						));
					}
					$i++;
				}
			}
		}
	}