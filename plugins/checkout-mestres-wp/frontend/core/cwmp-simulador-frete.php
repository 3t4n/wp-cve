<?php
add_shortcode("cwmp_simulador_frete","cwmp_simulador_frete_short");
function cwmp_simulador_frete_short(){
	global $product;
?>

<form class='cwmp-simulador-frete'>
	<input type="text" placeholder='Digite seu CEP' />
	<button id="<?php echo $product->get_id(); ?>">Simular</button>
</form>
<div class="cwmp-simulador-frete-retorno"><ul></ul></div>

<?php
}
add_action( 'wp_ajax_cwmp_simulador_frete', 'cwmp_simulador_frete' );
add_action( 'wp_ajax_nopriv_cwmp_simulador_frete', 'cwmp_simulador_frete' );
function cwmp_simulador_frete(){
	$html = "";
	$product = wc_get_product($_POST['produto']);
	$postcode = $_POST['cep'];
	$cart_length = array();
	$cart_width = array();
	$cart_height = array();
	$cart_weight = array();
	if($product->get_length()!=""){ $cart_length[] = ($product->get_length()*$_POST['qtde']); }else{ $cart_length[] = (get_option('cwmo_shipping_padrao_length')*$_POST['qtde']); }
	if($product->get_width()!=""){ $cart_width[] = ($product->get_width()*$_POST['qtde']); }else{ $cart_width[] = (get_option('cwmo_shipping_padrao_width')*$_POST['qtde']); }
	if($product->get_height()!=""){ $cart_height[] = ($product->get_height()*$_POST['qtde']); }else{ $cart_height[] = (get_option('cwmo_shipping_padrao_height')*$_POST['qtde']); }
	if($product->get_weight()!=""){ $cart_weight[] = ($product->get_weight()*$_POST['qtde']); }else{ $cart_weight[] = (get_option('cwmo_shipping_padrao_weight')*$_POST['qtde']); }
	if(get_option('cwmp_adddon_correios')=="S"){
		$service = cwmp_get_tables_shipping();
		$return_pac = cwmp_get_services_shipping($service,get_option('woocommerce_store_postcode'),$postcode,array_sum($cart_height),array_sum($cart_width),0,array_sum($cart_length),array_sum($cart_weight),1,get_option('cwmo_mao_propria_correios'),get_option('cwmo_valor_declarado_correios'),get_option('cwmo_aviso_recebimento_correios'));
		$retorno_pac = json_decode($return_pac['body']);
		foreach($retorno_pac->cResultado->Servicos as $value){
			if(str_replace(",",".",$value->cServico->Valor)==0.00){}else{
				$html .= "<li>";
				$html .= "<div>";
				$html .= "<img src='https://sandbox.melhorenvio.com.br/images/shipping-companies/correios.png' />";
				$html .= "</div>";
				$html .= "<div>";
				$html .= "<h3>Correios:</h3>";
				$html .= __("<p>".str_replace("{{prazo}}",gmdate('d/m/Y', strtotime('+ '.$value->cServico->PrazoEntrega.' days')),get_option('cwmo_format_correios'))."</p>");
				$html .= "</div>";
				$html .= "<div>";
				$html .= "<span>".wc_price($value->cServico->Valor)."</span>";
				$html .= "</div>";
				$html .= "</li>";
			}
		}
	}
	if(get_option('cwmp_adddon_kangu')=="S"){
		$header = array(
			'token' => ''.get_option('cwmo_format_token_kangu').'',
			'Content-Type' => 'application/json; charset=utf-8'
		);
		$data = array(
			'cepOrigem' => get_option('woocommerce_store_postcode'),
			'cepDestino' => $postcode,
			'vlrMerc' => $product->get_regular_price()*$_POST['qtde'],
			'pesoMerc' => array_sum($cart_weight),
			'volumes' => [
				'peso' => array_sum($cart_weight),
				'altura' => array_sum($cart_height),
				'largura' => array_sum($cart_width),
				'comprimento' => array_sum($cart_length),
				'tipo' => 'C',
				'valor' => $product->get_regular_price()*$_POST['qtde'],
				'quantidade' => 1
			],
			'produtos' => [
				'peso' => array_sum($cart_weight),
				'altura' => array_sum($cart_height),
				'largura' => array_sum($cart_width),
				'comprimento' => array_sum($cart_length),
				'valor' => $product->get_regular_price()*$_POST['qtde'],
				'quantidade' => 1
			],
			'servicos' => [
				'E','X','M','R'
			]
		);
		$send = wp_remote_post("https://portal.kangu.com.br/tms/transporte/simular", array(
		   'method' => 'POST',
		   'headers' => $header,
		   'body' => wp_json_encode($data)
		));
		$retorno = json_decode($send['body']);
		foreach($retorno as $value){		
			if(str_replace(",",".",$value->vlrFrete)==0.00){}else{
				$html .= "<li>";
				$html .= "<div>";
				$html .= "<img src='https://sandbox.melhorenvio.com.br/images/shipping-companies/correios.png' />";
				$html .= "</div>";
				$html .= "<div>";
				$html .= "<h3>".str_replace("Correios ","",$value->transp_nome)."</h3>";
				$html .= __("<p>".str_replace("{{prazo}}",gmdate('d/m/Y', strtotime('+ '.$value->prazoEnt.' days')),get_option('cwmo_format_correios'))."</p>");
				$html .= "</div>";
				$html .= "<div>";
				$html .= "<span>".wc_price($value->vlrFrete)."</span>";
				$html .= "</div>";
				$html .= "</li>";
			}
		}
	}
	if(get_option('cwmp_adddon_frenet')=="S"){
		$header = array(
			'token' => get_option('cwmo_format_token_frenet'),
			'Content-Type' => 'application/json',
			'Accept' => 'application/json',
		);
		$data = array(
			'SellerCEP' => get_option('woocommerce_store_postcode'),
			'RecipientCEP' => $postcode,
			'ShipmentInvoiceValue' => $product->get_regular_price()*$_POST['qtde'],
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
		$send = wp_remote_post("https://private-anon-25af48c7de-frenetapi.apiary-mock.com/shipping/quote", array(
		   'method' => 'POST',
		   'headers' => $header,
		   'body' => wp_json_encode($data)
		));
		$retorno = json_decode($send['body']);
		foreach($retorno->ShippingSevicesArray as $value){
			if(str_replace(",",".",$value->ShippingPrice)==0.00){}else{
				$html .= "<li>";
				if($value->Carrier=="Correios"){
					$html .= "<div>";
					$html .= "<img src='https://sandbox.melhorenvio.com.br/images/shipping-companies/correios.png' />";
					$html .= "</div>";
					$html .= "<div>";
				}
				$html .= "<h3>".$value->ServiceDescription."</h3>";
				$html .= __("<p>".str_replace("{{prazo}}",gmdate('d/m/Y', strtotime('+ '.$value->DeliveryTime.' days')),get_option('cwmo_format_correios'))."</p>");
				if($value->Carrier=="Correios"){
					$html .= "</div>";
				}
				$html .= "<div>";
				$html .= "<span>".wc_price($value->ShippingPrice)."</span>";
				$html .= "</div>";

				$html .= "</li>";
			}
		}
	}
	if(get_option('cwmp_adddon_mandabem')=="S"){
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
		$response = wp_safe_remote_post('https://mandabem.com.br/ws/valor_envio', $options);
		$retorno = json_decode($response['body']);
		if(str_replace(",",".",$retorno->resultado->PAC->valor)==0.00){}else{
			$html .= "<li>";
			$html .= "<div>";
			$html .= "<img src='https://sandbox.melhorenvio.com.br/images/shipping-companies/correios.png' />";
			$html .= "</div>";
			$html .= "<div>";
			$html .= "<h3>PAC</h3>";
			$html .= __("<p>".str_replace("{{prazo}}",gmdate('d/m/Y', strtotime('+ '.$retorno->resultado->PAC->prazo.' days')),get_option('cwmo_format_correios'))."</p>");
			$html .= "</div>";
				$html .= "<div>";
				$html .= "<span>".wc_price($retorno->resultado->PAC->valor)."</span>";
				$html .= "</div>";

			$html .= "</li>";
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
			$html .= "<li>";
			$html .= "<div>";
			$html .= "<img src='https://sandbox.melhorenvio.com.br/images/shipping-companies/correios.png' />";
			$html .= "</div>";
			$html .= "<div>";
			$html .= "<h3>SEDEX</h3>";
			$html .= __("<p>".str_replace("{{prazo}}",gmdate('d/m/Y', strtotime('+ '.$retorno->resultado->SEDEX->prazo.' days')),get_option('cwmo_format_correios'))."</p>");
			$html .= "</div>";
				$html .= "<div>";
				$html .= "<span>".wc_price($retorno->resultado->SEDEX->valor)."</span>";
				$html .= "</div>";

			$html .= "</li>";
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
			$html .= "<li>";
			$html .= "<div>";
			$html .= "<img src='https://sandbox.melhorenvio.com.br/images/shipping-companies/correios.png' />";
			$html .= "</div>";
			$html .= "<div>";
			$html .= "<h3>PAC MINI</h3>";
			$html .= __("<p>".str_replace("{{prazo}}",gmdate('d/m/Y', strtotime('+ '.$retorno->resultado->PACMINI->prazo.' days')),get_option('cwmo_format_correios'))."</p>");
			$html .= "</div>";
				$html .= "<div>";
				$html .= "<span>".wc_price($retorno->resultado->PACMINI->valor)."</span>";
				$html .= "</div>";

			$html .= "</li>";
		}
	}
	if(get_option('cwmp_adddon_melhorenvio')=="S"){
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
			$data['options']['insurance_value'] = $product->get_price();
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
		foreach($retorno as $value){
			if(!empty($value->price)){
			if(str_replace(",",".",$value->price)==0.00){}else{
				$html .= "<li>";
				$html .= "<div>";
				$html .= "<img src='".$value->company->picture."' />";
				$html .= "</div>";
				$html .= "<div>";
				$html .= "<h3>".__($value->name, 'checkout-mestres-wp')."</h3>";
				$html .= __("<p>".str_replace("{{prazo}}",gmdate('d/m/Y', strtotime('+ '.$value->delivery_time.' days')),get_option('cwmo_format_correios'))."</p>");
				$html .= "</div>";
				$html .= "<div>";
				$html .= "<span>".wc_price($value->price)."</span>";
				$html .= "</div>";

				$html .= "</li>";
			}
			}
			
		}
	}
	echo $html;
	die();
	}
	
	
	function cwmp_get_tables_shipping(){
		$pac = null;
		switch (get_option('cwmo_contrato_correios')) {
			case "BLC":
				$pac = "04510,04014";
			break;
			case "CLU1":
				$pac = "03085,03050";
			break;
			case "BRO2": case "RAT2": case "OUR4": case "PLA3": case "DIA1S": case "DIA2S": case "DIA3S": case "DIA4S":
				$pac = "03298,03220,04227";
			break;
			case "DIA1": case "DIA2": case "DIA3": case "DIA4": case "INF1": case "INF2": case "INF3": case "INF4": case "INF5":
				$pac = "03336,03280,04391";
			break;
		}
		return $pac;
	}
	function cwmp_get_services_shipping($servico,$origem,$destino,$altura,$largura,$diametro,$comprimento,$peso,$formato,$maopropria,$valordeclarado,$avisorecebimento){
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
		$send = wp_remote_post("https://correios.mestresdowp.com.br/", array(
		   'method' => 'POST',
		   'body' => array('url'=>$url)
		));
		return $send;
	}