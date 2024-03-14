<?php
/* [cwmp_order_situation] */
function cwmp_order_situation($number_id)
{
    global $wp;
    global $woocommerce;
    if (isset($number_id['val'])){
        $cwmp_order_info = wc_get_order($number_id['val']);
		return $cwmp_order_info->get_status();
    }else{
		if(isset($_GET['cwmp_order'])){
        $cwmp_order_info = wc_get_order(esc_html(base64_decode($_GET['cwmp_order'])));
		return $cwmp_order_info->get_status();
		}	
    }
   
}
add_shortcode('cwmp_order_status', 'cwmp_order_situation');
add_shortcode('cwmp_order_situation', 'cwmp_order_situation');

function cwmp_order_date($number_id)
{
    global $wp;
    global $woocommerce;
    if (isset($number_id['val']))
    {
        $cwmp_order_info = wc_get_order($number_id['val']);
		
		return gmdate("d/m/Y", strtotime($cwmp_order_info->get_date_created()));
		
    }
    else
    {
		if(isset($_GET['cwmp_order']))
		{
        $cwmp_order_info = wc_get_order(esc_html(base64_decode($_GET['cwmp_order'])));
		 return gmdate("d/m/Y", strtotime($cwmp_order_info->get_date_created()));
		}	
    }
   
}
add_shortcode('cwmp_order_date', 'cwmp_order_date');

function cwmp_order_time($number_id)
{
    global $wp;
    global $woocommerce;
    if (isset($number_id['val']))
    {
        $cwmp_order_info = wc_get_order($number_id['val']);
		
		return gmdate("H:i:s", strtotime($cwmp_order_info->get_date_created()));
		
    }
    else
    {
		if(isset($_GET['cwmp_order']))
		{
        $cwmp_order_info = wc_get_order(esc_html(base64_decode($_GET['cwmp_order'])));
		 return gmdate("H:i:s", strtotime($cwmp_order_info->get_date_created()));
		}	
    }
   
}
add_shortcode('cwmp_order_time', 'cwmp_order_time');

/* [cwmp_order_number] */
function cwmp_order_number($number_id)
{
    if ($number_id)
    {
        return $number_id['val'];
    }
    else
    {
        if (isset($_GET['cwmp_order']))
        {
            return esc_html(base64_decode($_GET['cwmp_order']));
        }
    }
}
add_shortcode('cwmp_order_number', 'cwmp_order_number');



function cwmp_order_meio_de_pagamento($number_id)
{
    global $wp;
    global $woocommerce;
    if (isset($number_id['val']))
    {
        $cwmp_order_info = wc_get_order($number_id['val']);
    }
    elseif (isset($_GET['cwmp_order']))
    {
        $cwmp_order_info = wc_get_order(base64_decode($_GET['cwmp_order']));
    }
    else
    {
        $orders = wc_get_orders(array(
            'limit' => 1,
            'return' => 'ids',
        ));
        $cwmp_order_info = wc_get_order($orders[0]);
    }
    if (!empty($cwmp_order_info)){
		if ($cwmp_order_info->get_payment_method_title()){
			return $cwmp_order_info->get_payment_method_title();
		}
    }
}
add_shortcode('cwmp_order_meio_de_pagamento', 'cwmp_order_meio_de_pagamento');

function cwmp_order_total($number_id)
{
    global $wp;
    global $woocommerce;
    if (isset($number_id['val']))
    {
        $cwmp_order_info = wc_get_order($number_id['val']);
    }
    elseif (isset($_GET['cwmp_order']))
    {
        $cwmp_order_info = wc_get_order(esc_html(base64_decode($_GET['cwmp_order'])));
    }
    else
    {
        $orders = wc_get_orders(array(
            'limit' => 1,
            'return' => 'ids',
        ));
        $cwmp_order_info = wc_get_order($orders[0]);
    }
	if (!empty($cwmp_order_info)){
    if ($cwmp_order_info->get_total())
    {
        return "R$" . $cwmp_order_info->get_total();
    }
    }
}
add_shortcode('cwmp_order_total', 'cwmp_order_total');
function cwmp_order_total_ads($number_id)
{
    global $wp;
    global $woocommerce;
    if (isset($number_id['val']))
    {
        $cwmp_order_info = wc_get_order($number_id['val']);
		return $cwmp_order_info->get_total();
    }
    else
    {
		if(isset($_GET['cwmp_order'])){
			$cwmp_order_info = wc_get_order(esc_html(base64_decode($_GET['cwmp_order'])));
			return $cwmp_order_info->get_total();
		}
    }
    
}
add_shortcode('cwmp_order_total_ads', 'cwmp_order_total_ads');


function cwmp_contingencia_pagamento_link($number_id)
{
    global $wp;
    global $woocommerce;
    if (isset($number_id['val'])){
        $cwmp_order_info = wc_get_order($number_id['val']);
		return $cwmp_order_info->get_checkout_payment_url();
    }else{
		if(isset($_GET['cwmp_order'])){
			$cwmp_order_info = wc_get_order(base64_decode($_GET['cwmp_order']));
			return $cwmp_order_info->get_checkout_payment_url();
		}
    }
}
add_shortcode('cwmp_contingencia_pagamento_link', 'cwmp_contingencia_pagamento_link');

function cwmp_order_produtos($number_id)
{
    global $wp;
    global $woocommerce;

    if (isset($number_id['val']))
    {
        $cwmp_order_info = wc_get_order($number_id['val']);
    }
    elseif (isset($_GET['cwmp_order']))
    {
        $cwmp_order_info = wc_get_order(esc_html(base64_decode($_GET['cwmp_order'])));
    }
    else
    {
        $orders = wc_get_orders(array(
            'limit' => 1,
            'return' => 'ids',
        ));
        $cwmp_order_info = wc_get_order($orders[0]);
    }
	if (!empty($cwmp_order_info)){
			$items = $cwmp_order_info->get_items();
			$note = "";
			foreach ($items as $key => $lineItem)
			{
				$note .= $lineItem['name'] . " x " . $lineItem['quantity'] . "
		";
			}
    }
	
	if($note){
    	return $note;
	}
	
}
add_shortcode('cwmp_order_produtos', 'cwmp_order_produtos');

function cwmp_order_product_download($number_id)
{
    global $wp;
    global $woocommerce;

    if (isset($number_id['val']))
    {
        $cwmp_order_info = wc_get_order($number_id['val']);
    }
    elseif (isset($_GET['cwmp_order']))
    {
        $cwmp_order_info = wc_get_order(esc_html(base64_decode($_GET['cwmp_order'])));
    }
    else
    {
        $orders = wc_get_orders(array(
            'limit' => 1,
            'return' => 'ids',
        ));
        $cwmp_order_info = wc_get_order($orders[0]);
    }
if (!empty($cwmp_order_info)){    
	$html  = [];
	if( $downloads = $cwmp_order_info->get_downloadable_items() ) {
		foreach( $downloads as $download ) {
			$html[] = $download["file"]['file'];
		}
	}
	if( ! empty($html) ){
		return implode('<br>', $html);
	}
}

}
add_shortcode('cwmp_order_product_download', 'cwmp_order_product_download');
function cwmp_payment_link($number_id)
{
    global $wp;
    global $woocommerce;
    if (isset($number_id['val']))
    {
        $cwmp_order_info = wc_get_order($number_id['val']);
		if($cwmp_order_info->get_status()=="pending" OR $cwmp_order_info->get_status()=="on-hold" ){
			return "".get_permalink(get_option('cwmp_thankyou_page_pending_'.$cwmp_order_info->get_payment_method().''))."?cwmp_order=".base64_encode($cwmp_order_info->get_id())."";
		}else{
			return "".get_permalink(get_option('cwmp_thankyou_page_aproved_'.$cwmp_order_info->get_payment_method().''))."?cwmp_order=".base64_encode($cwmp_order_info->get_id())."";
		}
    }
    elseif (isset($_GET['cwmp_order']))
    {
        $cwmp_order_info = wc_get_order(base64_decode($_GET['cwmp_order']));
		if($cwmp_order_info->get_status()=="pending" OR $cwmp_order_info->get_status()=="on-hold" ){
			return "".get_permalink(get_option('cwmp_thankyou_page_pending_'.$cwmp_order_info->get_payment_method().''))."?cwmp_order=".base64_encode($cwmp_order_info->get_id())."";
		}else{
			return "".get_permalink(get_option('cwmp_thankyou_page_aproved_'.$cwmp_order_info->get_payment_method().''))."?cwmp_order=".base64_encode($cwmp_order_info->get_id())."";
		}
    }
    else
    {
        $orders = wc_get_orders(array(
            'limit' => 1,
            'return' => 'ids',
        ));
        $cwmp_order_info = wc_get_order($orders[0]);
		if (!empty($cwmp_order_info)){
			if($cwmp_order_info->get_status()=="pending" OR $cwmp_order_info->get_status()=="on-hold" ){
				return "".get_permalink(get_option('cwmp_thankyou_page_pending_'.$cwmp_order_info->get_payment_method().''))."?cwmp_order=".base64_encode($cwmp_order_info->get_id())."";
			}else{
				return "".get_permalink(get_option('cwmp_thankyou_page_aproved_'.$cwmp_order_info->get_payment_method().''))."?cwmp_order=".base64_encode($cwmp_order_info->get_id())."";
			}
		}
    }
}
add_shortcode('cwmp_payment_link', 'cwmp_payment_link');

function cwmp_order_coupon_name($number_id)
{
    global $wp;
    global $woocommerce;
    if (isset($number_id['val']))
    {
        $order = wc_get_order($number_id['val']);
		$coupon = $order->get_coupon_codes();
		return $coupon[0];
		
    }
    else
    {
		if(isset($_GET['cwmp_order']))
		{
			$order = wc_get_order(esc_html(base64_decode($_GET['cwmp_order'])));
			$coupon = $order->get_coupon_codes();
			if(isset($coupon[0])){
			return $coupon[0];
			}
		}	
    }
   
}
add_shortcode('cwmp_order_coupon_name', 'cwmp_order_coupon_name');



function cwmpPixQRCode($number_id){
    global $wp;
    global $woocommerce;
    if (isset($number_id['val'])){
		$order_id = $number_id['val'];
    }else{
		if(isset($_GET['cwmp_order'])){
			$order_id = base64_decode($_GET['cwmp_order']);
		}	
    }
	if(!empty($order_id)){
	$order = wc_get_order($order_id);
	}
	
	if(isset($order)){
		switch ($order->get_payment_method()) {
			// AGREGAPAY
			case "agregapay_pix":
				$retorno = get_post_meta( $order_id, 'qrcode' );
				$qrcode = '<img src="' . $retorno[0] . '">';
				break;
			// EFI
			case "WC_Gerencianet_Pix":
				$retorno = get_post_meta( $order_id, '_gn_pix_qrcode', true );
				$qrcode = '<img src="' . $retorno . '" width="200" height="auto" />';
				break;
			// OPENPIX
			case "woocommerce_openpix_pix":
				$retorno = $order->get_meta( 'openpix_transaction' );
				$qrcode = '<img src="' . $retorno['qrCodeImage'] . '" width="200" height="auto" />';
				break;
			// APPMAX
			case "appmax_pix":
				$qrcode = '<img src="data:image/png;base64,' . $order->get_meta('_appmax_transaction_data')['post_payment']['pix_qrcode'] . '" width="200" height="auto" />';
				break;
			case "pagamentos_para_woocommerce_com_appmax_pix":
				$qrcode = '<img src="' . $order->get_meta('_pagamentos_para_woocommerce_com_appmax_media') . '" width="200" height="auto" />';
				break;
			// IUGU
			case "iugu-pix":
				$retorno = get_post_meta($order_id, '_iugu_wc_transaction_data');
				$qrcode = "<img src='data:image/jpeg;base64," . $retorno[0]['qrcode'] . "' width='200' height='200' />";
				break;
			// ASAAS
			case "asaas-pix":
				$retorno = json_decode($order->get_meta( '__ASAAS_ORDER' ));
				$qrcode = '<img height="250px" width="250px" src="data:image/jpeg;base64,' . $retorno->encodedImage. '" />';
				break;
			// PAGHIPER
			case "paghiper_pix":
				$retorno = get_post_meta($order->get_id(), 'wc_paghiper_data', true);
				$qrcode = '<img src="' . $retorno['qrcode_image_url'] . '" width="200" height="auto" />';
				break;
			// CLICK2PAY
			case "click2pay-pix":
				$retorno = $order->get_meta( '_click2pay_data' );
				$qrcode = "<img src='" . $retorno->pix->qrCodeImage->base64 . "' width='200' height='200' />";
				break;
			// PAGARME 2.0
			case "woo-pagarme-payments-pix":
				$retorno = json_decode($order->get_meta( '_pagarme_response_data' ));
				$qrcode = '<img src="' . $retorno->charges[0]->transactions[0]->postData->qr_code_url . '" width="200" height="auto" />';
				break;
			case "woo-mercado-pago-pix":
				$qrcode = "<img src='data:image/jpeg;base64," . $order->get_meta( 'mp_pix_qr_base64' ) . "' width='200' height='200' />";
				break;
			case "wc_yapay_intermediador_pix":
				$data = get_post_meta($order->get_id(), 'yapay_transaction_data', true);
				if(is_serialized($data)){
					$data = unserialize( $data );
					$qrcode = "<img src='" . $data['qrcode_path'] . "' width='200' height='200' />";
				}
				break;
		}
		return $qrcode;
	}
}
add_shortcode('cwmpPixQRCode', 'cwmpPixQRCode');
add_shortcode('cwmp_order_pix_agregapay_qrcode', 'cwmpPixQRCode');
add_shortcode('cwmp_order_pix_appmax_qrcode', 'cwmpPixQRCode');
add_shortcode('cwmp_order_pix_asaas_qrcode', 'cwmpPixQRCode');
add_shortcode('cwmp_order_pix_click2pay_qrcode', 'cwmpPixQRCode');
add_shortcode('cwmp_order_pix_gerencianet_qrcode', 'cwmpPixQRCode');
add_shortcode('cwmp_order_pix_iugu_qrcode', 'cwmpPixQRCode');
add_shortcode('cwmp_order_pix_mp_qrcode', 'cwmpPixQRCode');
add_shortcode('cwmp_order_pix_openpix_qrcode', 'cwmpPixQRCode');
add_shortcode('cwmp_order_pix_pagarme_qrcode', 'cwmpPixQRCode');
add_shortcode('cwmp_order_pix_paghiper_qrcode', 'cwmpPixQRCode');

function cwmpPixCopyPast($number_id){
    global $wp;
    global $woocommerce;
    if (isset($number_id['val'])){
		$order_id = $number_id['val'];
    }else{
		if(isset($_GET['cwmp_order'])){
			$order_id = base64_decode($_GET['cwmp_order']);
		}	
    }
	if(!empty($order_id)){
	$order = wc_get_order($order_id);
	}
	if(isset($order)){
		switch ($order->get_payment_method()) {
			// AGREGAPAY
			case "agregapay_pix":
				$retorno = get_post_meta( $order_id, 'copypast');
				return $retorno[0];
				break;
			// EFI
			case "WC_Gerencianet_Pix":
				$retorno = get_post_meta( $order_id, '_gn_pix_copy', true );
				return $retorno;
				break;
			// OPEN PIX
			case "woocommerce_openpix_pix":
				$retorno = $order->get_meta( 'openpix_transaction' );
				return $retorno['brCode'];
				break;
			// APPMAX
			case "appmax_pix":
				return $order->get_meta("_appmax_transaction_data")["post_payment"]["pix_emv"];
				break;
			case "pagamentos_para_woocommerce_com_appmax_pix":
				return $order->get_meta('_pagamentos_para_woocommerce_com_appmax_payment_code');
				break;
			// IUGU
			case "iugu-pix":
				$retorno = get_post_meta(base64_decode($_GET['cwmp_order']), '_iugu_wc_transaction_data');
				return $cwmp_mp_pix[0]['qrcode_text'];
				break;
			// ASAAS
			case "asaas-pix":
				$retorno = json_decode($order->get_meta( '__ASAAS_ORDER' ));
				return $retorno->payload;
				break;
			// PAGHIPER
			case "paghiper_pix":
				$retorno = get_post_meta($order->get_id(), 'wc_paghiper_data', true);
				return $retorno['emv'];
				break;
			// CLICK2PAY
			case "click2pay-pix":
				$retorno = $order->get_meta( '_click2pay_data' );
				return $retorno->pix->textPayment;
				break;
			// PAGARME 2.0
			case "woo-pagarme-payments-pix":
				$retorno = json_decode($order->get_meta( '_pagarme_response_data' ));
				return $retorno->charges[0]->transactions[0]->postData->qr_code;
				break;
			// MERCADO PAGO
			case "woo-mercado-pago-pix":
				return $order->get_meta( 'mp_pix_qr_code' );
				break;
			case "wc_yapay_intermediador_pix":
				$data = get_post_meta($order->get_id(), 'yapay_transaction_data', true);
				if(is_serialized($data)){
					$data = unserialize( $data );					
					return $data['qrcode_original_path'];
				}
				break;
		}
		return $qrcode;
	}
}
add_shortcode('cwmpPixCopyPast', 'cwmpPixCopyPast');
add_shortcode('cwmp_order_pix_agregapay_copypaste', 'cwmpPixCopyPast');
add_shortcode('cwmp_order_pix_appmax_copypaste', 'cwmpPixCopyPast');
add_shortcode('cwmp_order_pix_asaas_copypaste', 'cwmpPixCopyPast');
add_shortcode('cwmp_order_pix_click2pay_copypaste', 'cwmpPixCopyPast');
add_shortcode('cwmp_order_pix_gerencianet_copypaste', 'cwmpPixCopyPast');
add_shortcode('cwmp_order_pix_iugu_copypaste', 'cwmpPixCopyPast');
add_shortcode('cwmp_order_pix_juno_copypaste', 'cwmpPixCopyPast');
add_shortcode('cwmp_order_pix_mp_copypaste', 'cwmpPixCopyPast');
add_shortcode('cwmp_order_pix_openpix_copypaste', 'cwmpPixCopyPast');
add_shortcode('cwmp_order_pix_pagarme_copypaste', 'cwmpPixCopyPast');
add_shortcode('cwmp_order_pix_paghiper_copypaste', 'cwmpPixCopyPast');

function cwmpPixTextArea(){
	return "
		<textarea class='copypast'>" . do_shortcode('[cwmpPixCopyPast]') . "</textarea>
		<button class='buttoncopypast'>Copiar</button>
		<p style='display:none' class='return_copy'>Código Copiado</p>
		<script type='text/javascript'>
		jQuery(document).ready(function($) {
		$('.buttoncopypast').click(function(){
			navigator.clipboard.writeText($('textarea.copypast').val());
			$('.return_copy').show();
		});
		});
		</script>
	";
}
add_shortcode('cwmpPixTextArea', 'cwmpPixTextArea');
add_shortcode('cwmp_order_pix_agregapay_copypaste_textarea', 'cwmpPixTextArea');
add_shortcode('cwmp_order_pix_appmax_copypaste_textarea', 'cwmpPixTextArea');
add_shortcode('cwmp_order_pix_asaas_copypaste_textarea', 'cwmpPixTextArea');
add_shortcode('cwmp_order_pix_click2pay_copypaste_textarea', 'cwmpPixTextArea');
add_shortcode('cwmp_order_pix_gerencianet_copypaste_textarea', 'cwmpPixTextArea');
add_shortcode('cwmp_order_pix_iugu_copypaste_textarea', 'cwmpPixTextArea');
add_shortcode('cwmp_order_pix_juno_copypaste_textarea', 'cwmpPixTextArea');
add_shortcode('cwmp_order_pix_mp_copypaste_textarea', 'cwmpPixTextArea');
add_shortcode('cwmp_order_pix_openpix_copypaste_textarea', 'cwmpPixTextArea');
add_shortcode('cwmp_order_pix_pagarme_copypaste_textarea', 'cwmpPixTextArea');
add_shortcode('cwmp_order_pix_paghiper_copypaste_textarea', 'cwmpPixTextArea');

function cwmpBilletBarcode($number_id){
    global $wp;
    global $woocommerce;
    if (isset($number_id['val'])){
		$order_id = $number_id['val'];
    }else{
		if(isset($_GET['cwmp_order'])){
			$order_id = base64_decode($_GET['cwmp_order']);
		}	
    }
	if(!empty($order_id)){
	$order = wc_get_order($order_id);
	}
	if(isset($order)){
		switch ($order->get_payment_method()) {
			// EFI
			case "WC_Gerencianet_Boleto":
				$retorno = get_post_meta( $order_id, '_gn_barcode');
				return $retorno[0];
				break;
			// PAGARME
			case "woo-pagarme-payments-billet":
				$retorno = json_decode($order->get_meta( '_pagarme_response_data' ));
				return $retorno->charges[0]->transactions[0]->postData->line;
				break;
			// PAGHIPER
			case "paghiper_billet":
				$retorno = get_post_meta($order->get_id(), 'wc_paghiper_data', true);
				return $retorno['digitable_line'];
				break;
			// CLICK2PAY
			case "click2pay-bank-slip":
				$retorno = $order->get_meta( '_click2pay_boleto_barcode' );
				return $retorno;
				break;
			// APPMAX
			case "appmax_boleto":
				$retorno = get_post_meta($order->get_id(), 'appmax_digitable_line', true);
				return $retorno;
				break;
			case "pagamentos_para_woocommerce_com_appmax_boleto":
				return $order->get_meta( '_pagamentos_para_woocommerce_com_appmax_payment_code' );
				break;
			case "pagamentos_para_woocommerce_com_appmax_boleto":
				return $order->get_meta( '_pagamentos_para_woocommerce_com_appmax_media' );
				break;
			// YAPAY
			case "wc_yapay_intermediador_bs":
				$retorno = get_post_meta($order->get_id(), 'yapay_transaction_data',true );
				if(is_serialized($retorno)){
					$data = unserialize( $retorno );
					return $data['typeful_line'];
				}
				break;
			// CORA
			case "cora":
				$retorno = get_post_meta($order->get_id(), 'cora_digitable',true );
				return $retorno;
				break;
		}
		if(!empty($qrcode)){
		return $qrcode;
		}
	}
}
add_shortcode('cwmpBilletBarcode', 'cwmpBilletBarcode');
add_shortcode('cwmp_order_boleto_appmax_digitable_line', 'cwmpBilletBarcode');
add_shortcode('cwmp_order_boleto_click2pay_digitable_line', 'cwmpBilletBarcode');
add_shortcode('cwmp_order_boleto_cora_digitable_line', 'cwmpBilletBarcode');
add_shortcode('cwmp_order_link_boleto_moip_bar', 'cwmpBilletBarcode');
add_shortcode('cwmp_order_boleto_paghiper_copypaste', 'cwmpBilletBarcode');

function cwmpBilletTextArea(){
	return "
		<textarea class='copypast'>" . do_shortcode('[cwmpBilletBarcode]') . "</textarea>
		<button class='buttoncopypast'>Copiar</button>
		<p style='display:none' class='return_copy'>Código Copiado</p>
		<script type='text/javascript'>
		jQuery(document).ready(function($) {
		$('.buttoncopypast').click(function(){
			navigator.clipboard.writeText($('textarea.copypast').val());
			$('.return_copy').show();
		});
		});
		</script>
	";
}
add_shortcode('cwmpBilletTextArea', 'cwmpBilletTextArea');
add_shortcode('cwmp_order_boleto_cora_digitable_line_textarea', 'cwmpBilletTextArea');
add_shortcode('cwmp_order_boleto_paghiper_copypaste_textarea', 'cwmpBilletTextArea');

function cwmpBilletLink($number_id){
    global $wp;
    global $woocommerce;
    if (isset($number_id['val'])){
		$order_id = $number_id['val'];
    }else{
		if(isset($_GET['cwmp_order'])){
			$order_id = base64_decode($_GET['cwmp_order']);
		}	
    }
	if(!empty($order_id)){
	$order = wc_get_order($order_id);
	}
	if(isset($order)){
		switch ($order->get_payment_method()) {
			// EFI
			case "WC_Gerencianet_Boleto":
				$retorno = get_post_meta( $order_id, '_gn_link_responsive');
				return $retorno[0];
				break;
			case "asaas-ticket":
				$retorno = json_decode($order->get_meta( '__ASAAS_ORDER' ));
				return $retorno->bankSlipUrl;
				break;
			// PAGARME
			case "woo-pagarme-payments-billet":
				$retorno = json_decode($order->get_meta( '_pagarme_response_data' ));
				return $retorno->charges[0]->transactions[0]->postData->url;
				break;
			// PAGHIPER
			case "paghiper_billet":
				$retorno = get_post_meta($order->get_id(), 'wc_paghiper_data', true);
				return $retorno['url_slip_pdf'];
				break;
			// MERCADO PAGO
			case "woo-mercado-pago-ticket":
				return $order->get_meta( '_transaction_details_ticket' );
				break;
			// CLICK2PAY
			case "click2pay-bank-slip":
				$retorno = $order->get_meta( '_click2pay_boleto_url' );
				return $retorno;
				break;
			// AGREGAPAY
			case "agregapay_boleto":
				$retorno = get_post_meta($order_id, 'agregapay_boleto_link', true);
				return $retorno;
				break;
			// APPMAX
			case "appmax_boleto":
				$retorno = get_post_meta($order->get_id(), 'appmax_link_billet', true);
				return $retorno;
				break;
			case "pagamentos_para_woocommerce_com_appmax_boleto":
				return $order->get_meta( '_pagamentos_para_woocommerce_com_appmax_media' );
				break;
			// YAPAY
			case "wc_yapay_intermediador_bs":
				$retorno = get_post_meta($order->get_id(), 'yapay_transaction_data',true );
				if(is_serialized($retorno)){
					$data = unserialize( $retorno );
					return $data['url_payment'];
				}
				break;
			// CORA
			case "cora":
				$retorno = get_post_meta($order->get_id(), 'cora_url',true );
				return $retorno;
				break;
		}
		if(!empty($qrcode)){
		return $qrcode;
		}
		
	}
}
add_shortcode('cwmpBilletLink', 'cwmpBilletLink');
add_shortcode('cwmp_order_boleto_agregapay_link', 'cwmpBilletLink');
add_shortcode('cwmp_order_boleto_appmax_link', 'cwmpBilletLink');
add_shortcode('cwmp_order_boleto_asaas_link', 'cwmpBilletLink');
add_shortcode('cwmp_order_boleto_click2pay_link', 'cwmpBilletLink');
add_shortcode('cwmp_order_link_boleto_cora_link', 'cwmpBilletLink');
add_shortcode('cwmp_order_boleto_gerencianet_link', 'cwmpBilletLink');
add_shortcode('cwmp_order_link_boleto_iugu', 'cwmpBilletLink');
add_shortcode('cwmp_order_boleto_juno', 'cwmpBilletLink');
add_shortcode('cwmp_order_link_boleto_moip', 'cwmpBilletLink');
add_shortcode('cwmp_order_link_boleto_mp', 'cwmpBilletLink');
add_shortcode('cwmp_order_link_boleto_pagarme_link', 'cwmpBilletLink');
add_shortcode('cwmp_order_link_boleto_pagarme_link20', 'cwmpBilletLink');
add_shortcode('cwmp_order_boleto_pdf_paghiper_copypaste', 'cwmpBilletLink');
add_shortcode('cwmp_order_boleto_pagseguro_link', 'cwmpBilletLink');



function cwmpPixQRCodeLink($number_id){
    global $wp;
    global $woocommerce;
    if (isset($number_id['val'])){
		$order_id = $number_id['val'];
    }else{
		if(isset($_GET['cwmp_order'])){
			$order_id = base64_decode($_GET['cwmp_order']);
		}	
    }
	if(!empty($order_id)){
	$order = wc_get_order($order_id);
	}
	if(isset($order)){
		switch ($order->get_payment_method()) {
			// AGREGAPAY
			case "agregapay_pix":
				$retorno = get_post_meta( $order_id, 'qrcode' );
				$qrcode = $retorno[0];
				break;
			// EFI
			case "WC_Gerencianet_Pix":
				$retorno = get_post_meta( $order_id, '_gn_pix_qrcode', true );
				$qrcode = '' . $retorno . '';
				break;
			// OPENPIX
			case "woocommerce_openpix_pix":
				$retorno = $order->get_meta( 'openpix_transaction' );
				$qrcode = '' . $retorno['qrCodeImage'] . '';
				break;
			// APPMAX
			case "appmax_pix":
				$qrcode = 'data:image/png;base64,' . $order->get_meta('_appmax_transaction_data')['post_payment']['pix_qrcode'] . '';
				break;
			case "pagamentos_para_woocommerce_com_appmax_pix":
				$qrcode = $order->get_meta('_pagamentos_para_woocommerce_com_appmax_media');
				break;
			// IUGU
			case "iugu-pix":
				$retorno = get_post_meta($order_id, '_iugu_wc_transaction_data');
				$qrcode = "data:image/jpeg;base64," . $retorno[0]['qrcode'] . "";
				break;
			// ASAAS
			case "asaas-pix":
				$retorno = json_decode($order->get_meta( '__ASAAS_ORDER' ));
				$qrcode = 'data:image/jpeg;base64,' . $retorno->encodedImage. '';
				break;
			// PAGHIPER
			case "paghiper_pix":
				$retorno = get_post_meta($order->get_id(), 'wc_paghiper_data', true);
				$qrcode = '' . $retorno['qrcode_image_url'] . '';
				break;
			// CLICK2PAY
			case "click2pay-pix":
				$retorno = $order->get_meta( '_click2pay_data' );
				$qrcode = "" . $retorno->pix->qrCodeImage->base64 . "";
				break;
			// PAGARME 2.0
			case "woo-pagarme-payments-pix":
				$retorno = json_decode($order->get_meta( '_pagarme_response_data' ));
				$qrcode = '' . $retorno->charges[0]->transactions[0]->postData->qr_code_url . '';
				break;
			case "woo-mercado-pago-pix":
				$qrcode = "data:image/jpeg;base64," . $order->get_meta( 'mp_pix_qr_base64' ) . "";
				break;
			case "wc_yapay_intermediador_pix":
				$data = get_post_meta($order->get_id(), 'yapay_transaction_data', true);
				if(is_serialized($data)){
					$data = unserialize( $data );
					$qrcode = "" . $data['qrcode_path'] . "";
				}
				break;
		}
		return $qrcode;
	}
}
add_shortcode('cwmpPixQRCodeLink', 'cwmpPixQRCodeLink');