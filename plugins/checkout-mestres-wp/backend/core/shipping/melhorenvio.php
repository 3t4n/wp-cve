<?php
add_filter('woocommerce_shipping_methods', 'add_cwmp_method_shipping_melhorenvio');
function add_cwmp_method_shipping_melhorenvio($methods){
		$methods['cwmp_method_shipping_melhorenvio'] = 'cwmp_method_shipping_melhorenvio_Method';
	return $methods;
}
add_action('woocommerce_shipping_init', 'cwmp_method_shipping_melhorenvio_method');
function cwmp_method_shipping_melhorenvio_method(){
	class cwmp_method_shipping_melhorenvio_Method extends WC_Shipping_Method{
		public function __construct($instance_id = 0){
			$this->id = 'cwmp_method_shipping_melhorenvio';
			$this->instance_id = absint($instance_id);
			$this->domain = 'cwmp_method_shipping_melhorenvio';
			$this->method_title = __('Melhor Envio', 'checkout-mestres-wp');
			$this->method_description = __('Send via Melhor Envio.', 'checkout-mestres-wp');
			$this->title = __('Melhor Envio', 'checkout-mestres-wp');
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
					'default'        => __('Melhor Envio'),
					'desc_tip'        => true
				)
			);
			$this->enabled = $this->get_option('enabled');
			$this->title   = __('Melhor Envio', 'checkout-mestres-wp');
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
				if($_product->get_length()!=""){ $cart_length[] = ($_product->get_length()*$values['quantity']); }else{ $cart_length[] = (get_option('cwmo_shipping_padrao_length')*$values['quantity']); }
				if($_product->get_width()!=""){ $cart_width[] = ($_product->get_width()*$values['quantity']); }else{ $cart_width[] = (get_option('cwmo_shipping_padrao_width')*$values['quantity']); }
				if($_product->get_height()!=""){ $cart_height[] = ($_product->get_height()*$values['quantity']); }else{ $cart_height[] = (get_option('cwmo_shipping_padrao_height')*$values['quantity']); }
				if($_product->get_weight()!=""){ $cart_weight[] = ($_product->get_weight()*$values['quantity']); }else{ $cart_weight[] = (get_option('cwmo_shipping_padrao_weight')*$values['quantity']); }
			}
			$data = array(
				'from' => [
				'postal_code'=>get_option('woocommerce_store_postcode')
				],
				'to' => [
					'postal_code'=>$postcode
				],
				'volumes' => [
					'width'=>array_sum($cart_width),
					'height'=>array_sum($cart_height),
					'length'=>array_sum($cart_length),
					'weight'=>array_sum($cart_weight),
				],
				'options' => [
					'receipt'=>get_option('cwmo_aviso_recebimento_melhorenvio'),
					'own_hand'=>get_option('cwmo_mao_propria_melhorenvio'),
				],
			);
			if(get_option('cwmo_seguro_melhorenvio')==true){
				$data['options']['insurance_value'] = $woocommerce->cart->subtotal;
			}
			$headers = array(
				'Accept'=>'application/json',
				'Content-Type'=>'application/json',
				'Authorization'=>'Bearer '.get_option('cwmo_format_token_melhorenvio_bearer'),
				'User-Agent'=>'Aplicação ('.get_option('cwmo_format_email_melhorenvio').')',
			);
			$send = wp_remote_post(CWMP_BASE_URL_MELHORENVIO."api/v2/me/shipment/calculate", array(
			   'method' => 'POST',
			   'headers' => $headers,
			   'body' => wp_json_encode($data)
			));
			$retorno = json_decode($send['body']);
			$i=1;
			foreach($retorno as $value){
				if(isset($value->price)){
					if(str_replace(",",".",$value->price)==0.00){}else{
						$this->add_rate(array(
							'id'    => $this->id.":".$i,
							'label' => __($value->name, 'checkout-mestres-wp'),
							'cost'  => str_replace(",",".",$value->price),
							'calc_tax'  => "per_order",
							'meta_data' => array(
								'prazo' => $value->delivery_time,
								'servico'=>$value->id
							)
						));
						$i++;
					}
				}
			}
		}
	}
}
add_action( 'add_meta_boxes', 'cwmp_add_box_admin_melhorenvio' );
function cwmp_add_box_admin_melhorenvio(){
    global $post;
	$order = wc_get_order($post->ID);
	if(!empty($order)){
		$ship_method =	$order->get_shipping_methods();
		foreach($ship_method as $value){
			$data = $value->get_data();
			$data = $data['method_id'];
		}
		if(!empty($data)){
		if($data=="cwmp_method_shipping_melhorenvio"){
			add_meta_box( 'cwmp_box_melhor_envio', __('Melhor Envio','woocommerce'), 'cwmp_box_melhor_envio_html', 'shop_order', 'side', 'core' );
		}
		}
	}
}
function cwmp_box_melhor_envio_html(){
	global $order;
    global $post;
	$order = wc_get_order( $post->ID ) ;
	$html = '';
	if(get_post_meta($order->get_ID() , '_order_id_melhor_envio', true)){
	$url = cwmp_visualizar_etiqueta_melhorenvio(get_post_meta($order->get_ID() , '_order_id_melhor_envio', true));
	if($url){
		$html .= '<a href="'.$url.'" target="blank" class="button button-primary" id="'.$post->ID.'" style="width:100% !important;margin-top:5px;text-align:center;">Visualizar Etiqueta</a>';
	}else{
		$html .= 'Aguarde.<br/>Sua etiqueta está sendo gerada.';
	}
	}else{
	$html .= '<button class="button button-primary cwmp_add_etiqueta_melhorenvio" id="'.$post->ID.'" style="width:100% !important;margin-top:5px;">Gerar Etiqueta</button>';
	}
	echo $html;
}
add_action( 'wp_ajax_cwmp_send_cart_melhorenvio', 'cwmp_send_cart_melhorenvio' );
add_action( 'wp_ajax_nopriv_cwmp_send_cart_melhorenvio', 'cwmp_send_cart_melhorenvio' );
function cwmp_send_cart_melhorenvio(){
	global $product;
	$order = wc_get_order('8127');
	$ship_method =	$order->get_shipping_methods();
	foreach($ship_method as $value){
		foreach($value->get_data()['meta_data'] as $servico => $servico_value){
			if($servico_value->get_data()['key']=="servico"){
				$service_shipping = $servico_value->get_data()['value'];
			}
		}
	}
	$headers = array(
		'Accept'=>'application/json',
		'Content-Type'=>'application/json',
		'Authorization'=>'Bearer '.get_option('cwmo_format_token_melhorenvio_bearer'),
		'User-Agent'=>'Aplicação ('.get_option('cwmo_format_email_melhorenvio').')',
	);
	$items = $order->get_items();
	$cart_length = array();
	$cart_width = array();
	$cart_height = array();
	$cart_weight = array();
	$produtos = array();
	foreach($items as $item => $values) {
		$_product = $values->get_product();
		if($_product->get_length()!=""){ $cart_length[] = ($_product->get_length()*$values['quantity']); }else{ $cart_length[] = (get_option('cwmo_shipping_padrao_length')*$values['quantity']); }
		if($_product->get_width()!=""){ $cart_width[] = ($_product->get_width()*$values['quantity']); }else{ $cart_width[] = (get_option('cwmo_shipping_padrao_width')*$values['quantity']); }
		if($_product->get_height()!=""){ $cart_height[] = ($_product->get_height()*$values['quantity']); }else{ $cart_height[] = (get_option('cwmo_shipping_padrao_height')*$values['quantity']); }
		if($_product->get_weight()!=""){ $cart_weight[] = ($_product->get_weight()*$values['quantity']); }else{ $cart_weight[] = (get_option('cwmo_shipping_padrao_weight')*$values['quantity']); }
		$produtos[] = array(
			'name'=>$_product->get_title(),
			'quantity'=>$values['quantity'],
			'unitary_value'=>''
		);
	}
	$data = array(
		'service'=>$service_shipping,
		'from'=>array(
			'name'=>get_option('blogname'),
			'phone'=>$order->get_billing_phone(),
			'email'=>'',
			'document'=>'',
			'company_document'=>'',
			'state_register'=>'',
			'address'=>get_option('woocommerce_store_address'),
			'complement'=>get_option('woocommerce_store_address_2'),
			'number'=>'',
			'district'=>'',
			'city'=>get_option('woocommerce_store_city'),
			'country_id'=>'BR',
			'postal_code'=>WC()->customer->get_shipping_postcode(),
			'state_abbr'=>'',
			'note'=>''
		),
		'to'=>array(
			'name'=>$order->get_billing_first_name().' '.$order->get_billing_last_name(),
			'phone'=>$order->get_billing_phone(),
			'email'=>$order->get_billing_email(),
			'document'=>get_post_meta($order->get_ID() , '_billing_cpf', true),
			'company_document'=>'',
			'state_register'=>'',
			'address'=>$order->get_billing_address_1(),
			'complement'=>$order->get_billing_address_2(),
			'number'=>get_post_meta($order->get_ID() , '_billing_number', true),
			'district'=>get_post_meta($order->get_ID() , '_billing_neighborhood', true),
			'city'=>$order->get_billing_city(),
			'country_id'=>'BR',
			'postal_code'=>$order->get_billing_postcode(),
			'state_abbr'=>$order->get_billing_state(),
			'note'=>''
		),
		'products'=> $produtos,
		'volumes'=>
			array(
			'height'=>array_sum($cart_height),
			'width'=>array_sum($cart_width),
			'length'=>array_sum($cart_length),
			'weight'=>array_sum($cart_weight)
		),
	);
	if(get_option('cwmo_aviso_recebimento_melhorenvio')==true){
		$data['options']['cwmo_aviso_recebimento_melhorenvio'] = 1;
	}else{
		$data['options']['cwmo_aviso_recebimento_melhorenvio'] = 0;
		
	}
	if(get_option('cwmo_mao_propria_melhorenvio')==true){
		$data['options']['cwmo_mao_propria_melhorenvio'] = 1;
	}else{
		$data['options']['cwmo_mao_propria_melhorenvio'] = 0;
		
	}
	if(get_option('cwmo_seguro_melhorenvio')==true){
		$data['options']['insurance_value'] = $order->get_total();
	}
	print_r($data);
	$send = wp_remote_post(CWMP_BASE_URL_MELHORENVIO."api/v2/me/cart", array(
	   'method' => 'POST',
	   'headers' => $headers,
	   'body' => wp_json_encode($data)
	));
	$retorno = json_decode($send['body']);
	update_post_meta( $_POST['id'], '_order_id_melhor_envio', $retorno->id );
	update_post_meta( $_POST['id'], '_order_rastreio_melhor_envio', $retorno->protocol );
	$order->update_status( 'wc-separacao' );
	die();
}
function cwmp_visualizar_etiqueta_melhorenvio($args){
	$headers = array(
		'Accept'=>'application/json',
		'Content-Type'=>'application/json',
		'Authorization'=>'Bearer '.get_option('cwmo_format_token_melhorenvio_bearer'),
		'User-Agent'=>'Aplicação ('.get_option('cwmo_format_email_melhorenvio').')',
	);

	$data = array(
		'mode'=>'public',
		'orders'=>[$args],

	);
	$send = wp_remote_post(CWMP_BASE_URL_MELHORENVIO."api/v2/me/shipment/print", array(
	   'method' => 'POST',
	   'headers' => $headers,
	   'body' => wp_json_encode($data)
	));
	$retorno = json_decode($send['body']);
	if(!empty($retorno->url)){
	return $retorno->url;
	}
}