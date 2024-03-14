<?php

/**
*  ------------------------------------------------------------------------------------------------
*   WOOCOMMERCE ORDER STATUS PENDING FOR MERCADO PAGO
*  ------------------------------------------------------------------------------------------------
*/

function notificacoesWoo_pendente_mp($order_id){
	
	$notify_client      		= get_field("mensagem_enviada_status_aguardando_pagamento",9991274);
	$notify_admin       		= get_field("mensagem_enviada_status_aguardando_pagamento_admin",9991274);
	$numeros_escolhidos			= "";
	$cotas 						= "";
	$currency_simbol			= get_field("currency_simbol", 9991274) ? get_field("currency_simbol", 9991274) : "R$";
	
	//GET PAYMENT META
	$order						= wc_get_order($order_id);
	$payment_method				= $order->get_payment_method();
	if (
		$payment_method == "cod" OR 
		$payment_method == "bacs" OR 
		$payment_method == "pix_gateway" OR 
		$payment_method == "wc_piggly_pix_gateway" OR 
		$payment_method == "pagarme-banking-ticket" OR 
		$payment_method == "paghiper_pix" OR 
		$payment_method == "woo-pagarme-payments-pix" OR 
		$payment_method == "woo-pagarme-payments-credit_card" OR 
		$payment_method == "wc_pagarme_pix_payment_geteway") : return; 
	endif;
	$name						= $order->get_billing_first_name();
	$order_billing_phone		= $order->get_billing_phone();
	$phone						= $order_billing_phone;
	$wpp_admin					= get_field("wpp_administrador",9991274);
	$order_billing_address_1	= $order->get_billing_address_1();
	$order_billing_address_2	= $order->get_billing_address_2();
	$order_billing_city			= $order->get_billing_city();
	$order_billing_state		= $order->get_billing_state();
	$order_billing_postcode		= $order->get_billing_postcode();
	$order_billing_country		= $order->get_billing_country();
	$order_total				= $currency_simbol . $order->get_total();
	$shipping_total				= $currency_simbol . $order->get_total_shipping();
	$payment_method 			= $order->get_payment_method_title();
	$phone						= notificacoesWoo_number_internationalization($order_billing_country, $phone);
	$rastreio					= '';
	$address					= $order_billing_address_1 . ", " . $order_billing_address_2 . ", " . $order_billing_city . ", " . $order_billing_state . ", " . $order_billing_postcode . ", " . $order_billing_country . ". ";
    
	//GET PRODUCT INFO
	$product_list = "";
	foreach ($order->get_items() as $item_key => $item ):

		$product_list = $product_list . $item->get_name() . " (" . $item->get_quantity() . "und." . " - " . $currency_simbol . $item->get_total() . "), ";

	endforeach;

	//GET RIFA NUMBERS
	if (is_plugin_active('plugin-rifa-drope/plugin-rifa-drope.php')){
		
		foreach ($order->get_items() as $item_key => $item ):

			if($item->get_meta('billing_cotasescolhidas')):
	
				$numeros_escolhidos = $item->get_meta('billing_cotasescolhidas');
	
						$temp = explode(",",$numeros_escolhidos);
	
						$a = 0;
						while($a<count($temp)):
	
						  if($temp[$a]!="") :
	
							$cotas .= $temp[$a] . ", ";
							
						  endif;
	
						  $a++;
	
						endwhile;
					  
			endif;
	
		endforeach;
	}

	return notificacoesWoo_send_message($order_id, $name, $product_list, $address, $order_total, $shipping_total, $payment_method, $cotas, $phone, $notify_client, $wpp_admin, $notify_admin, $rastreio);
}

/**
*  ------------------------------------------------------------------------------------------------
*   WOOCOMMERCE ORDER STATUS PENDING
*  ------------------------------------------------------------------------------------------------
*/

function notificacoesWoo_pendente($order_id){
	
	$notify_client      		= get_field("mensagem_enviada_status_aguardando_pagamento",9991274);
	$notify_admin       		= get_field("mensagem_enviada_status_aguardando_pagamento_admin",9991274);
	$cotas 						= "";
	$currency_simbol			= get_field("currency_simbol", 9991274) ? get_field("currency_simbol", 9991274) : "R$";

	
	//GET PAYMENT META
	$order						= wc_get_order($order_id);
	$name						= $order->get_billing_first_name();
	$order_billing_phone		= $order->get_billing_phone();
	$phone						= $order_billing_phone;
	$wpp_admin					= get_field("wpp_administrador",9991274);
	$order_billing_address_1	= $order->get_billing_address_1();
	$order_billing_address_2	= $order->get_billing_address_2();
	$order_billing_city			= $order->get_billing_city();
	$order_billing_state		= $order->get_billing_state();
	$order_billing_postcode		= $order->get_billing_postcode();
	$order_billing_country		= $order->get_billing_country();
	$order_total				= $currency_simbol . $order->get_total();
	$shipping_total				= $currency_simbol . $order->get_total_shipping();
	$payment_method 			= $order->get_payment_method_title();
	$phone						= notificacoesWoo_number_internationalization($order_billing_country, $phone);
	$rastreio					= '';
	$address					= $order_billing_address_1 . ", " . $order_billing_address_2 . ", " . $order_billing_city . ", " . $order_billing_state . ", " . $order_billing_postcode . ", " . $order_billing_country . ". ";
    
	//GET PRODUCT INFO
	$product_list = "";
	foreach ($order->get_items() as $item_key => $item ):

		$product_list = $product_list . $item->get_name() . " (" . $item->get_quantity() . "und." . " - " . $currency_simbol . $item->get_total() . "), ";

	endforeach;

	//GET RIFA NUMBERS
	if (is_plugin_active('plugin-rifa-drope/plugin-rifa-drope.php')){
		
		foreach ($order->get_items() as $item_key => $item ):

			if($item->get_meta('billing_cotasescolhidas')):
	
				$numeros_escolhidos = $item->get_meta('billing_cotasescolhidas');
	
						$temp = explode(",",$numeros_escolhidos);
	
						$a = 0;
						while($a<count($temp)):
	
						  if($temp[$a]!="") :
	
							$cotas .= $temp[$a] . ", ";
							
						  endif;
	
						  $a++;
	
						endwhile;
					  
			endif;
	
		endforeach;
	}

	return notificacoesWoo_send_message($order_id, $name, $product_list, $address, $order_total, $shipping_total, $payment_method, $cotas, $phone, $notify_client, $wpp_admin, $notify_admin, $rastreio);

}

/**
*  ------------------------------------------------------------------------------------------------
*   WOOCOMMERCE ORDER STATUS FAILED
*  ------------------------------------------------------------------------------------------------
*/

function notificacoesWoo_falhou($order_id){

	$notify_client				= get_field("mensagem_enviada_status_com_falha",9991274);
	$notify_admin				= get_field("mensagem_enviada_status_com_falha_admin",9991274);
	$cotas 						= ""; 
	$currency_simbol			= get_field("currency_simbol", 9991274) ? get_field("currency_simbol", 9991274) : "R$";
	
	//GET PAYMENT META
	$order						= wc_get_order($order_id);
	$name						= $order->get_billing_first_name();
	$order_billing_phone		= $order->get_billing_phone();
	$phone						= $order_billing_phone;
	$wpp_admin					= get_field("wpp_administrador",9991274);
	$order_billing_address_1	= $order->get_billing_address_1();
	$order_billing_address_2	= $order->get_billing_address_2();
	$order_billing_city			= $order->get_billing_city();
	$order_billing_state		= $order->get_billing_state();
	$order_billing_postcode		= $order->get_billing_postcode();
	$order_billing_country		= $order->get_billing_country();
	$order_total				= $currency_simbol . $order->get_total();
	$shipping_total				= $currency_simbol . $order->get_total_shipping();
	$payment_method 			= $order->get_payment_method_title();
	$phone						= notificacoesWoo_number_internationalization($order_billing_country, $phone);
	$rastreio					= '';
	$address					= $order_billing_address_1 . ", " . $order_billing_address_2 . ", " . $order_billing_city . ", " . $order_billing_state . ", " . $order_billing_postcode . ", " . $order_billing_country . ". ";
    
	//GET PRODUCT INFO
	$product_list = "";
	foreach ($order->get_items() as $item_key => $item ):

		$product_list = $product_list . $item->get_name() . " (" . $item->get_quantity() . "und." . " - " . $currency_simbol . $item->get_total() . "), ";

	endforeach;

	//GET RIFA NUMBERS
	if (is_plugin_active('plugin-rifa-drope/plugin-rifa-drope.php')){
		
		foreach ($order->get_items() as $item_key => $item ):

			if($item->get_meta('billing_cotasescolhidas')):
	
				$numeros_escolhidos = $item->get_meta('billing_cotasescolhidas');
	
						$temp = explode(",",$numeros_escolhidos);
	
						$a = 0;
						while($a<count($temp)):
	
						  if($temp[$a]!="") :
	
							$cotas .= $temp[$a] . ", ";
							
						  endif;
	
						  $a++;
	
						endwhile;
					  
			endif;
	
		endforeach;
	}

	return notificacoesWoo_send_message($order_id, $name, $product_list, $address, $order_total, $shipping_total, $payment_method, $cotas, $phone, $notify_client, $wpp_admin, $notify_admin, $rastreio);

}

/**
*  ------------------------------------------------------------------------------------------------
*   WOOCOMMERCE ORDER STATUS PROCESSING
*  ------------------------------------------------------------------------------------------------
*/

function notificacoesWoo_processando($order_id){

	if (is_plugin_active('plugin-rifa-drope/plugin-rifa-drope.php') && get_field("numeros_aleatorios_quando", 99991) == "Normal (pós confirmação de pagamento)") {
		return;
	}

	$notify_client      		= get_field("mensagem_enviada_status_processando",9991274);
	$notify_admin       		= get_field("mensagem_enviada_status_processando_admin",9991274);
	$cotas 						= ""; 
	$currency_simbol			= get_field("currency_simbol", 9991274) ? get_field("currency_simbol", 9991274) : "R$";
	
	//GET PAYMENT META
	$order						= wc_get_order($order_id);
	$name						= $order->get_billing_first_name();
	$order_billing_phone		= $order->get_billing_phone();
	$phone						= $order_billing_phone;
	$wpp_admin					= get_field("wpp_administrador",9991274);
	$order_billing_address_1	= $order->get_billing_address_1();
	$order_billing_address_2	= $order->get_billing_address_2();
	$order_billing_city			= $order->get_billing_city();
	$order_billing_state		= $order->get_billing_state();
	$order_billing_postcode		= $order->get_billing_postcode();
	$order_billing_country		= $order->get_billing_country();
	$order_total				= $currency_simbol . $order->get_total();
	$shipping_total				= $currency_simbol . $order->get_total_shipping();
	$payment_method 			= $order->get_payment_method_title();
	$phone						= notificacoesWoo_number_internationalization($order_billing_country, $phone);
	$rastreio					= '';
	$address					= $order_billing_address_1 . ", " . $order_billing_address_2 . ", " . $order_billing_city . ", " . $order_billing_state . ", " . $order_billing_postcode . ", " . $order_billing_country . ". ";
    
	//GET PRODUCT INFO
	$product_list = "";
	foreach ($order->get_items() as $item_key => $item ):

		$product_list = $product_list . $item->get_name() . " (" . $item->get_quantity() . "und." . " - " . $currency_simbol . $item->get_total() . "), ";

	endforeach;

	//GET RIFA NUMBERS
	if (is_plugin_active('plugin-rifa-drope/plugin-rifa-drope.php')){
		
		foreach ($order->get_items() as $item_key => $item ):

			if($item->get_meta('billing_cotasescolhidas')):
	
				$numeros_escolhidos = $item->get_meta('billing_cotasescolhidas');
	
						$temp = explode(",",$numeros_escolhidos);
	
						$a = 0;
						while($a<count($temp)):
	
						  if($temp[$a]!="") :
	
							$cotas .= $temp[$a] . ", ";
							
						  endif;
	
						  $a++;
	
						endwhile;
					  
			endif;
	
		endforeach;
	}

	return notificacoesWoo_send_message($order_id, $name, $product_list, $address, $order_total, $shipping_total, $payment_method, $cotas, $phone, $notify_client, $wpp_admin, $notify_admin, $rastreio);

}

/**
*  ------------------------------------------------------------------------------------------------
*   WOOCOMMERCE ORDER STATUS COMPLETED
*  ------------------------------------------------------------------------------------------------
*/

function notificacoesWoo_completo($order_id){

	$notify_client      		= get_field("mensagem_enviada_status_completo",9991274);
	$notify_admin       		= get_field("mensagem_enviada_status_completo_admin",9991274);
	$cotas 						= ""; 
	$currency_simbol			= get_field("currency_simbol", 9991274) ? get_field("currency_simbol", 9991274) : "R$";
	
	//GET PAYMENT META
	$order						= wc_get_order($order_id);
	$name						= $order->get_billing_first_name();
	$order_billing_phone		= $order->get_billing_phone();
	$phone						= $order_billing_phone;
	$wpp_admin					= get_field("wpp_administrador",9991274);
	$order_billing_address_1	= $order->get_billing_address_1();
	$order_billing_address_2	= $order->get_billing_address_2();
	$order_billing_city			= $order->get_billing_city();
	$order_billing_state		= $order->get_billing_state();
	$order_billing_postcode		= $order->get_billing_postcode();
	$order_billing_country		= $order->get_billing_country();
	$order_total				= $currency_simbol . $order->get_total();
	$shipping_total				= $currency_simbol . $order->get_total_shipping();
	$payment_method 			= $order->get_payment_method_title();
	$phone						= notificacoesWoo_number_internationalization($order_billing_country, $phone);
	$rastreio					= '';
	$address					= $order_billing_address_1 . ", " . $order_billing_address_2 . ", " . $order_billing_city . ", " . $order_billing_state . ", " . $order_billing_postcode . ", " . $order_billing_country . ". ";
    
	//GET PRODUCT INFO
	$product_list = "";
	foreach ($order->get_items() as $item_key => $item ):

		$product_list = $product_list . $item->get_name() . " (" . $item->get_quantity() . "und." . " - " . $currency_simbol . $item->get_total() . "), ";

	endforeach;

	//GET RIFA NUMBERS
	if (is_plugin_active('plugin-rifa-drope/plugin-rifa-drope.php')){
		
		foreach ($order->get_items() as $item_key => $item ):

			if($item->get_meta('billing_cotasescolhidas')):
	
				$numeros_escolhidos = $item->get_meta('billing_cotasescolhidas');
	
						$temp = explode(",",$numeros_escolhidos);
	
						$a = 0;
						while($a<count($temp)):
	
						  if($temp[$a]!="") :
	
							$cotas .= $temp[$a] . ", ";
							
						  endif;
	
						  $a++;
	
						endwhile;
					  
			endif;
	
		endforeach;
	}

	return notificacoesWoo_send_message($order_id, $name, $product_list, $address, $order_total, $shipping_total, $payment_method, $cotas, $phone, $notify_client, $wpp_admin, $notify_admin, $rastreio);

}

/**
*  ------------------------------------------------------------------------------------------------
*   WOOCOMMERCE ORDER STATUS REFUNDED
*  ------------------------------------------------------------------------------------------------
*/

function notificacoesWoo_estornado($order_id){

	$notify_client      		= get_field("mensagem_enviada_status_estornado",9991274);
	$notify_admin       		= get_field("mensagem_enviada_status_estornado_admin",9991274);
	$cotas 						= ""; 
	$currency_simbol			= get_field("currency_simbol", 9991274) ? get_field("currency_simbol", 9991274) : "R$";
	
	//GET PAYMENT META
	$order						= wc_get_order($order_id);
	$name						= $order->get_billing_first_name();
	$order_billing_phone		= $order->get_billing_phone();
	$phone						= $order_billing_phone;
	$wpp_admin					= get_field("wpp_administrador",9991274);
	$order_billing_address_1	= $order->get_billing_address_1();
	$order_billing_address_2	= $order->get_billing_address_2();
	$order_billing_city			= $order->get_billing_city();
	$order_billing_state		= $order->get_billing_state();
	$order_billing_postcode		= $order->get_billing_postcode();
	$order_billing_country		= $order->get_billing_country();
	$order_total				= $currency_simbol . $order->get_total();
	$shipping_total				= $currency_simbol . $order->get_total_shipping();
	$payment_method 			= $order->get_payment_method_title();
	$phone						= notificacoesWoo_number_internationalization($order_billing_country, $phone);
	$rastreio					= '';
	$address					= $order_billing_address_1 . ", " . $order_billing_address_2 . ", " . $order_billing_city . ", " . $order_billing_state . ", " . $order_billing_postcode . ", " . $order_billing_country . ". ";
    
	//GET PRODUCT INFO
	$product_list = "";
	foreach ($order->get_items() as $item_key => $item ):

		$product_list = $product_list . $item->get_name() . " (" . $item->get_quantity() . "und." . " - " . $currency_simbol . $item->get_total() . "), ";

	endforeach;

	//GET RIFA NUMBERS
	if (is_plugin_active('plugin-rifa-drope/plugin-rifa-drope.php')){
		
		foreach ($order->get_items() as $item_key => $item ):

			if($item->get_meta('billing_cotasescolhidas')):
	
				$numeros_escolhidos = $item->get_meta('billing_cotasescolhidas');
	
						$temp = explode(",",$numeros_escolhidos);
	
						$a = 0;
						while($a<count($temp)):
	
						  if($temp[$a]!="") :
	
							$cotas .= $temp[$a] . ", ";
							
						  endif;
	
						  $a++;
	
						endwhile;
					  
			endif;
	
		endforeach;
	}

	return notificacoesWoo_send_message($order_id, $name, $product_list, $address, $order_total, $shipping_total, $payment_method, $cotas, $phone, $notify_client, $wpp_admin, $notify_admin, $rastreio);

}

/**
*  ------------------------------------------------------------------------------------------------
*   WOOCOMMERCE ORDER STATUS CANCELED
*  ------------------------------------------------------------------------------------------------
*/

function notificacoesWoo_cancelado($order_id){

	$notify_client      		= get_field("mensagem_enviada_status_cancelado",9991274);
	$notify_admin       		= get_field("mensagem_enviada_status_cancelado_admin",9991274);
	$cotas 						= ""; 
	$currency_simbol			= get_field("currency_simbol", 9991274) ? get_field("currency_simbol", 9991274) : "R$";
	
	//GET PAYMENT META
	$order						= wc_get_order($order_id);
	$name						= $order->get_billing_first_name();
	$order_billing_phone		= $order->get_billing_phone();
	$phone						= $order_billing_phone;
	$wpp_admin					= get_field("wpp_administrador",9991274);
	$order_billing_address_1	= $order->get_billing_address_1();
	$order_billing_address_2	= $order->get_billing_address_2();
	$order_billing_city			= $order->get_billing_city();
	$order_billing_state		= $order->get_billing_state();
	$order_billing_postcode		= $order->get_billing_postcode();
	$order_billing_country		= $order->get_billing_country();
	$order_total				= $currency_simbol . $order->get_total();
	$shipping_total				= $currency_simbol . $order->get_total_shipping();
	$payment_method 			= $order->get_payment_method_title();
	$phone						= notificacoesWoo_number_internationalization($order_billing_country, $phone);
	$rastreio					= '';
	$address					= $order_billing_address_1 . ", " . $order_billing_address_2 . ", " . $order_billing_city . ", " . $order_billing_state . ", " . $order_billing_postcode . ", " . $order_billing_country . ". ";
    
	//GET PRODUCT INFO
	$product_list = "";
	foreach ($order->get_items() as $item_key => $item ):

		$product_list = $product_list . $item->get_name() . " (" . $item->get_quantity() . "und." . " - " . $currency_simbol . $item->get_total() . "), ";

	endforeach;

	//GET RIFA NUMBERS
	if (is_plugin_active('plugin-rifa-drope/plugin-rifa-drope.php')){
		
		foreach ($order->get_items() as $item_key => $item ):

			if($item->get_meta('billing_cotasescolhidas')):
	
				$numeros_escolhidos = $item->get_meta('billing_cotasescolhidas');
	
						$temp = explode(",",$numeros_escolhidos);
	
						$a = 0;
						while($a<count($temp)):
	
						  if($temp[$a]!="") :
	
							$cotas .= $temp[$a] . ", ";
							
						  endif;
	
						  $a++;
	
						endwhile;
					  
			endif;
	
		endforeach;
	}

	return notificacoesWoo_send_message($order_id, $name, $product_list, $address, $order_total, $shipping_total, $payment_method, $cotas, $phone, $notify_client, $wpp_admin, $notify_admin, $rastreio);

}