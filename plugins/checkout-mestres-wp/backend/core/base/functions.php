<?php
function cwmpGetNamePayment($args){
	if(strpos($args, 'pix')){
		return "Pix";
	}
	if(strpos($args, 'billet') OR strpos($args, 'ticket') OR strpos($args, 'boleto')){
		return "Billet";
	}
	return $args;
	if(strpos($args, 'credit') OR strpos($args, 'card') OR strpos($args, 'mercado-pago-custom')){
		return "Credit";
	}

}
function cwmpGetNameShipping($args){
	if(strpos($args, 'pix')){
		return "Pix";
	}
	if($args=='stripe'){
		return "Stripe";
	}
	if(strpos($args, 'billet') OR strpos($args, 'ticket') OR strpos($args, 'boleto')){
		return "Boleto Bancário";
	}
	if(strpos($args, 'credit') OR strpos($args, 'card') OR strpos($args, 'mercado-pago-custom')){
		return "Cartão de Crédito";
	}
}
function cwmpGetStatus($args){
	$status = wc_get_order_statuses();
	$result = str_replace('_','-',$args);
	return $status[$result];
}
function cwmpArrayPaymentMethods(){
	$i=0;
	$wc_gateways = new WC_Payment_Gateways();
	$payment_gateways = $wc_gateways->payment_gateways();
	foreach( $payment_gateways as $gateway_id => $gateway ){
		if($gateway->enabled=="yes"){
			$returnMethodPayment[$i]['label']=$gateway->title;
			$returnMethodPayment[$i]['value']=$gateway->id;
			$i++;
		}
	}
	return $returnMethodPayment;
}
function cwmpGetCategories() {
    $categorias = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
    ));
	$i=0;
    foreach ($categorias as $categoria) {
        $returnCategories[$i]['label']=$categoria->name;
        $returnCategories[$i]['value']=$categoria->term_id;
       $i++;
    }

    return $returnCategories;
}
function cwmpArrayElemailer(){
	$args = array('post_type' => 'em-form-template');
	$posts = get_posts($args);
	$i=0;
	foreach( $posts as $post ){
		$returnTemplate[$i]['label']=$post->post_title;
		$returnTemplate[$i]['value']=$post->ID;
		$i++;
	}
	return $returnTemplate;
}
function cwmpArrayStatus(){
	$statuses = wc_get_order_statuses();
	$return = array();
	$i=0;
	foreach($statuses AS $status => $value){
		$returnStatus[$i]['label']=$value;
		$returnStatus[$i]['value']=$status;
		$i++;
	}
	return $returnStatus;
}
function cwmpArrayStatusCom(){
	$statuses = wc_get_order_statuses();
	$return = array();
	$i=0;
	foreach($statuses AS $status => $value){
		$returnStatus[$i]['label']=$value;
		$returnStatus[$i]['value']=str_replace("-","_",$status);
		$i++;
	}
	return $returnStatus;
}
function cwmpArrayPages(){
	$args = array(
		'sort_order' => 'asc',
		'sort_column' => 'post_title',
		'hierarchical' => 1,
		'exclude' => '',
		'include' => '',
		'meta_key' => '',
		'meta_value' => '',
		'authors' => '',
		'child_of' => 0,
		'parent' => -1,
		'exclude_tree' => '',
		'number' => '',
		'offset' => 0,
		'post_type' => 'page',
		'post_status' => 'publish'
	); 
	$pages = get_pages($args);
	$i=0;
	foreach($pages as $page){ // $pages is array of object
		$returnStatus[$i]['label']=$page->post_title;
		$returnStatus[$i]['value']=$page->ID;
		$i++;
	}
	return $returnStatus;
}
function cwmpArrayShippingMethod(){
	$shipping_zones = WC_Shipping_Zones::get_zones();
	foreach($shipping_zones as $shipping_zone) {
		$shipping_methods = $shipping_zone['shipping_methods'];
		$i=0;
		foreach($shipping_methods as $shipping_method) {
			$return[$i]['label']=$shipping_method->title;
			$return[$i]['value']=$shipping_method->id;
			$i++;
		}
	}
	return $return;
}
function cwmpArrayProducts(){
	$args = array( 'post_type' => array('product', 'product_variation'), 'posts_per_page' => -1 );
	$products = get_posts( $args );
	$i=0;
	foreach($products as $product){
		$return[$i]['label']=$product->post_title;
		$return[$i]['value']=$product->ID;
		$i++;
	}
	return $return;
}
function cwmpGetNameProduct($args){
	return get_the_title( $args );
}
function cwmpGetNewsletterSends($args){
	global $wpdb, $table_prefix;
	$args = sanitize_text_field($args);
	$result = $wpdb->get_results("SELECT * FROM ".$table_prefix."cwmp_newsletter_send WHERE campanha LIKE ".$args."");
	$result_ok = $wpdb->get_results("SELECT * FROM ".$table_prefix."cwmp_newsletter_send WHERE campanha LIKE ".$args." AND status LIKE '1'");
	return count($result)."/".count($result_ok);
}
function cwmpGetNewsletterClicks($args){
	global $wpdb, $table_prefix;
	$args = sanitize_text_field($args);
	$result = $wpdb->get_results("SELECT * FROM ".$table_prefix."cwmp_newsletter_send WHERE campanha LIKE ".$args." AND open LIKE '1' AND status LIKE '1'");
	return count($result);
}
function cwmpGetNewsletterOpen($args){
	global $wpdb, $table_prefix;
	$args = sanitize_text_field($args);
	$result = $wpdb->get_results("SELECT * FROM ".$table_prefix."cwmp_newsletter_send WHERE campanha LIKE ".$args." AND clique LIKE '1' AND status LIKE '1'");
	return count($result);
}
function cwmpGetIcon($args){
	switch($args){
		case "email":
		return '<svg width="22" height="18" viewBox="0 0 22 18" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M19.8 0H2.2C0.99 0 0.011 1.0125 0.011 2.25L0 15.75C0 16.9875 0.99 18 2.2 18H19.8C21.01 18 22 16.9875 22 15.75V2.25C22 1.0125 21.01 0 19.8 0ZM19.8 4.5L11 10.125L2.2 4.5V2.25L11 7.875L19.8 2.25V4.5Z" fill="#619AC4"/>
</svg>
';
		break;
		case "whatsapp":
		return '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M10 0C4.477 0 0 4.477 0 10C0 11.89 0.525 13.66 1.438 15.168L0.546 18.2C0.494785 18.3741 0.491415 18.5587 0.536244 18.7346C0.581074 18.9104 0.672448 19.0709 0.800759 19.1992C0.929071 19.3276 1.08958 19.4189 1.26542 19.4638C1.44125 19.5086 1.62592 19.5052 1.8 19.454L4.832 18.562C6.39068 19.5051 8.17822 20.0025 10 20C15.523 20 20 15.523 20 10C20 4.477 15.523 0 10 0ZM7.738 12.263C9.761 14.285 11.692 14.552 12.374 14.577C13.411 14.615 14.421 13.823 14.814 12.904C14.8636 12.7897 14.8816 12.6641 14.8661 12.5405C14.8507 12.4168 14.8023 12.2996 14.726 12.201C14.178 11.501 13.437 10.998 12.713 10.498C12.5618 10.3935 12.3761 10.3516 12.1947 10.381C12.0133 10.4105 11.8503 10.509 11.74 10.656L11.14 11.571C11.1085 11.6202 11.0593 11.6555 11.0026 11.6696C10.9459 11.6837 10.8859 11.6756 10.835 11.647C10.428 11.414 9.835 11.018 9.409 10.592C8.983 10.166 8.611 9.6 8.402 9.219C8.37609 9.17059 8.3686 9.11444 8.38091 9.06093C8.39323 9.00743 8.42453 8.9602 8.469 8.928L9.393 8.242C9.52487 8.12734 9.60996 7.9682 9.63209 7.79486C9.65422 7.62153 9.61183 7.44611 9.513 7.302C9.065 6.646 8.543 5.812 7.786 5.259C7.68831 5.1882 7.57386 5.14406 7.45393 5.13091C7.334 5.11776 7.21271 5.13606 7.102 5.184C6.182 5.578 5.386 6.588 5.424 7.627C5.449 8.309 5.716 10.24 7.738 12.263Z" fill="#32AE55"/>
</svg>
';
		break;
	}
}
function cwmpFormatTime($time1,$time2){
	$return="";
	$return.=$time1." ";
	switch($time2){
		case "hour":
		if($time1=="1"){
			$return.="hora";
		}else{
			$return.="horas";
		}
		break;
		case "minutes":
		if($time1=="1"){
			$return.="minuto";
		}else{
			$return.="minutos";
		}
		break;
		case "day":
		if($time1=="1"){
			$return.="dia";
		}else{
			$return.="dias";
		}
		break;
	}
	return $return;
}
function cwmpFormatData($date){
	$array = explode(" ",$date);
	return gmdate('d/m/Y', strtotime($array[0]))."<br/>".gmdate('H:i:s', strtotime($array[1]));
}
function cwmpFormatPercent($args){
	return $args."%";
}
function cwmpProductsBump($produto,$oferta){
	return get_the_title($produto)." + ".get_the_title($oferta);
}
function cwmpListBuyProduct($product_id, $data_especifica, $status) {
    // Converter a data específica para o formato 'Y-m-d'
    $data_especifica = date('Y-m-d', strtotime($data_especifica));
    
    // Obter a data de início e fim do dia específico
    $data_inicio = $data_especifica . ' 00:00:00';
    $data_fim = $data_especifica . ' 23:59:59';

    // Criar argumentos da consulta
    $args = array(
        'date_query' => array(
            array(
                'after'     => $data_inicio,
                'before'    => $data_fim,
                'inclusive' => true,
            ),
        ),
        'meta_query' => array(
            'relation' => 'AND', // Garantir que todas as condições sejam atendidas
            array(
                'key'     => '_product_id',
                'value'   => $product_id,
                'compare' => '=',
            ),
        ),
        'post_type'      => 'shop_order',
        'posts_per_page' => -1,
    );

    // Verificar e adicionar a condição de status do pedido
    if (!empty($status)) {
        $args['post_status'] = (array) $status;
    }

    // Obter os pedidos com base nos argumentos da consulta
    $orders = wc_get_orders($args);

    // Extrair os IDs dos pedidos
    $order_ids = array();
    foreach ($orders as $order) {
        // Verificar se o pedido contém o produto específico
        $order_items = $order->get_items();
        foreach ($order_items as $item) {
            if ($item->get_product_id() == $product_id) {
                $order_ids[] = $order->get_id();
                break; // Se encontrar o produto, pare de verificar os itens deste pedido
            }
        }
    }

    // Retornar os IDs dos pedidos
    return $order_ids;
}