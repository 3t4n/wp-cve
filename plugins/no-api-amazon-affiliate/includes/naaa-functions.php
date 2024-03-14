<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if (!function_exists('naaa_write_log')) {
    function naaa_write_log($log) {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }
}

function naaa_get_bestseller_db($bestseller_hash){
	global $wpdb;
	$tabla = "{$wpdb->prefix}naaa_bestselleer_amazon";

	$query = "SELECT * FROM $tabla WHERE bestseller_hash = %s";
	$query = $wpdb->prepare($query,$bestseller_hash);
	$result = $wpdb->get_results($query, ARRAY_A);
	if (count($result)> 0){
		return $result[0];
	}
	return null;
}

function naaa_get_item_db($asinUnit, $market){
	global $wpdb;
	$tabla = "{$wpdb->prefix}naaa_item_amazon";

	$query = "SELECT * FROM $tabla WHERE asin = %s AND mercado = %s";
	$query = $wpdb->prepare($query,$asinUnit,$market);
	$result = $wpdb->get_results($query, ARRAY_A);
	if (count($result)> 0){
		return $result[0];
	}
	return null;
}

function naaa_delete_item_db($asinUnit, $market){
	global $wpdb;
	$tabla = "{$wpdb->prefix}naaa_item_amazon";
	$wpdb->delete($tabla, array('asin'=>$asinUnit, 'mercado'=>$market) );
}


function naaa_insert_update_bestseller_db($bestseller_hash, $bestseller_text, $market, $asin_list, $id_naaa_bestseller_amazon){
	global $wpdb;
	$tabla = "{$wpdb->prefix}naaa_bestselleer_amazon";

	if($id_naaa_bestseller_amazon !== null){ //Actualizar b.d.
		$wpdb->update($tabla, ['bestseller_hash'=> $bestseller_hash,
							'bestseller_text'=> $bestseller_text,
							'mercado'=> $market,
							'asin_list'=> $asin_list,
							'fecha_ultimo_update'=>date("Y-m-d H:i:s")],
							array('id_naaa_bestseller_amazon'=>$id_naaa_bestseller_amazon) );
	}else{  //Insertar b.d.
		$wpdb->insert($tabla, ['bestseller_hash'=> $bestseller_hash,
							'bestseller_text'=> $bestseller_text,
							'mercado'=>$market,
							'asin_list'=> $asin_list,
							'fecha_alta'=>date("Y-m-d H:i:s"),
							'fecha_ultimo_update'=>date("Y-m-d H:i:s")]);
	}
}


function naaa_insert_update_item_db($asinUnit, $urlImage, $precio, $moneda, $titulo, $precioOld, $valoracion, $opiniones, $prime, $mercado, $id_naaa_item_amazon){
	global $wpdb;
	$tabla = "{$wpdb->prefix}naaa_item_amazon";

	if($id_naaa_item_amazon !== null){ //Actualizar b.d.
		$wpdb->update($tabla, [	'titulo'=> $titulo,
							'precio'=> $precio,
							'precio_anterior'=> $precioOld,
							'imagen_url'=>$urlImage,
							'valoracion'=>$valoracion,
							'opiniones'=>$opiniones,
							'prime'=>$prime,
							'mercado'=>$mercado,
							'fecha_ultimo_update'=>date("Y-m-d H:i:s")],
							array('id_naaa_item_amazon'=>$id_naaa_item_amazon) );
	}else{  //Insertar b.d.
		$wpdb->insert($tabla, ['asin'=> $asinUnit,
							'titulo'=> $titulo,
							'precio'=> $precio,
							'precio_anterior'=> $precioOld,
							'imagen_url'=>$urlImage,
							'valoracion'=>$valoracion,
							'opiniones'=>$opiniones,
							'prime'=>$prime,
							'mercado'=>$mercado,
							'fecha_alta'=>date("Y-m-d H:i:s"),
							'fecha_ultimo_update'=>date("Y-m-d H:i:s")]);
	}
}

function naaa_merge_item($resultWS, $itemDb){
	if($itemDb !== null){
		//Mantengo datos de la imagen
		if(!isset($resultWS['imagen_url']) || $resultWS['imagen_url'] === null){
			$resultWS['imagen_url'] = $itemDb['imagen_url'];
		}
		//Vacio precios
		if(!isset($resultWS['precio']) || $resultWS['precio'] === null){
			$resultWS['precio'] = null;
			$resultWS['moneda'] = null;
			$resultWS['precio_anterior'] = null;
		}
		//Mantengo datos del producto
		if(!isset($resultWS['titulo']) || $resultWS['titulo'] === null){
			$resultWS['titulo'] = $itemDb['titulo'];
			$resultWS['valoracion'] = $itemDb['valoracion'];
			$resultWS['opiniones'] = $itemDb['opiniones'];
			$resultWS['prime'] = $itemDb['mercado'];
			$resultWS['mercado'] = $itemDb['mercado'];
		}
		$resultWS['titulo_manual'] = $itemDb['titulo_manual'];
		$resultWS['alt_manual'] = $itemDb['alt_manual'];
	}
	return $resultWS;
}


function naaa_force_bestseller($bestseller_text, $bestseller_hash, $market, $id_naaa_bestseller_amazon){
	$url = naaa_get_market_ad_url($market);
	if($market != 'mx' && $market != 'br'){
		$jsonString = @file_get_contents($url.urlencode($bestseller_text));
		if($jsonString === FALSE) {
			//naaa_write_log( 'Error al leer '.$url.urlencode($bestseller_text) );
		}else{
			$json_items = naaa_json_decode($jsonString);
		}
	}

	if(!isset($json_items) || $json_items === FALSE || $json_items == '') {
		//no product json
		return null;
	}else{
		if (isset($json_items) && isset($json_items->results) && !empty($json_items->results)){
			$asin_list = '';
			foreach ($json_items->results as $json_item) {
				$itemNew = naaa_json_to_item_data($json_item, $market);
				naaa_force_update($itemNew['asin'], $market, $itemNew);
				$asin_list = $asin_list.$itemNew['asin'].',';
			}
			$asin_list = rtrim($asin_list, ',');
			naaa_insert_update_bestseller_db($bestseller_hash, $bestseller_text, $market, $asin_list, $id_naaa_bestseller_amazon);
			return $asin_list;
		}
	}
	return null;
}

function naaa_force_update($asinUnit, $market, $itemNew){
	//naaa_write_log($market);
	//naaa_write_log($asinUnit);

	$itemDb = naaa_get_item_db($asinUnit, $market);
	$itemNew = naaa_merge_item($itemNew, $itemDb);
	if (!empty($itemNew['mercado'])){
		naaa_insert_update_item_db($asinUnit,
								$itemNew['imagen_url'],
								$itemNew['precio'],
								$itemNew['moneda'],
								$itemNew['titulo'],
								$itemNew['precio_anterior'],
								$itemNew['valoracion'],
								$itemNew['opiniones'],
								$itemNew['prime'],
								$itemNew['mercado'],
								$itemDb['id_naaa_item_amazon']);
		return $itemNew;
	}else{
		return null;
	}
	return $itemNew;
}

function naaa_get_asin_list_bestseller($bestseller_text, $market){
	global $wpdb;

	$bestseller_hash = hash('sha256',
							preg_replace("/[^0-9\pL\pM\pN]+/", "", iconv('UTF-8','ASCII//TRANSLIT',$bestseller_text).$market),
							false);

	//Compruebo si existe en b.d.
	$result = naaa_get_bestseller_db($bestseller_hash);

	//Existe y esta actualizado
	if ($result !== null){
		if((strtotime($result['fecha_ultimo_update']) + esc_attr(get_option('naaa_time_update',86400))) > time()){
			return $result['asin_list']; 
		}else{
			return naaa_force_bestseller($bestseller_text, $bestseller_hash, $market, $result['id_naaa_bestseller_amazon']);
		}
	}else{ //No existe o no está actualizado
		return naaa_force_bestseller($bestseller_text, $bestseller_hash, $market, null);
	}

}

function naaa_get_item_data($asinUnit, $market){
	global $wpdb;
	
	//Compruebo si existe en b.d.
	$result = naaa_get_item_db($asinUnit, $market);

	//Existe y esta actualizado
	if ($result !== null && 
		((strtotime($result['fecha_ultimo_update']) + esc_attr(get_option('naaa_time_update',86400))) > time()) ){
		return $result;
	}else{ //No existe o no está actualizado
		$itemNew = naaa_get_item_data_ws($asinUnit, $market);
		$result = naaa_force_update($asinUnit, $market, $itemNew);
		return $result;
	}
}

function naaa_parse_price($monedaPrecio, $market){
		$market = strtolower($market);
		$precio = 0; $moneda = '€';
	if ($market == 'ca'){ //CDN$ 1,298.15
		$moneda = 'CDN$';
		$pos = strpos( trim($monedaPrecio), ' ');
		if ($pos !== false) {
			$monedaPrecio = substr($monedaPrecio, 0, $pos);
		}
		$precio = trim(str_replace(array('CDN$','$'), array('',''), $monedaPrecio));
		$precio = (double)filter_var($precio, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	//}else if ($market == 'CN'){
	//
	}else if ($market == 'de'){//1.299,15 €
		$moneda = '€';
		$monedaPrecio = substr($monedaPrecio, 0, strpos($monedaPrecio,'€')-1);
		$precio = trim(str_replace(array('€','.',','), array('','','.'), $monedaPrecio));
		$precio = (double)filter_var($precio, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	}else if ($market == 'es'){
		$moneda = '€';
		$monedaPrecio = substr($monedaPrecio, 0, strpos($monedaPrecio,'€')-1);
		$precio = trim(str_replace(array('€','.',','), array('','','.'), $monedaPrecio));
		$precio = (double)filter_var($precio, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	}else if ($market == 'fr'){
		$moneda = '€';
		$monedaPrecio = substr($monedaPrecio, 0, strpos($monedaPrecio,'€')-1);
		$precio = trim(str_replace(array('€','.',','), array('','','.'), $monedaPrecio));
		$precio = (double)filter_var($precio, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	}else if ($market == 'gb'){
		$moneda = '£';//£1,263.99
		$pos = strpos( trim($monedaPrecio), ' ');
		if ($pos !== false) {
			$monedaPrecio = substr($monedaPrecio, 0, $pos);
		}
		$precio = trim(str_replace ('£', '', $monedaPrecio));
		$precio = (double)filter_var($precio, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	//}else if ($market == 'in'){//₹ 7,74,490.00
	//	$moneda = '₹';
	//	$precio = trim(str_replace ('₹', '', $monedaPrecio));
	//	$precio = (double)filter_var($precio, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	}else if ($market == 'it'){
		$moneda = '€';
		$monedaPrecio = substr($monedaPrecio, 0, strpos($monedaPrecio,'€')-1);
		$precio = trim(str_replace(array('€','.',','), array('','','.'), $monedaPrecio));
		$precio = (double)filter_var($precio, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	}else if ($market == 'jp'){ //¥17,500
		$moneda = '￥';
		$pos = strpos( trim($monedaPrecio), ' ');
		if ($pos !== false) {
			$monedaPrecio = substr($monedaPrecio, 0, $pos);
		}
		$precio = trim(str_replace ('￥', '', $monedaPrecio));
		$precio = (double)filter_var($precio, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	}else if ($market == 'us'){ //$12,999.00
		$moneda = '$';
		$pos = strpos( trim($monedaPrecio), ' ');
		if ($pos !== false) {
			$monedaPrecio = substr($monedaPrecio, 0, $pos);
		}
		$precio = trim(str_replace ('$', '', $monedaPrecio));
		$precio = (double)filter_var($precio, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	}else if ($market == 'mx'){ //$12,999.00
		$moneda = '$';
		$pos = strpos( trim($monedaPrecio), ' ');
		if ($pos !== false) {
			$monedaPrecio = substr($monedaPrecio, 0, $pos);
		}
		$precio = trim(str_replace ('$', '', $monedaPrecio));
		$precio = (double)filter_var($precio, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	}else if ($market == 'br'){ //R$3.999,00
		$moneda = 'R$';
		$pos = strpos( trim($monedaPrecio), ' ');
		if ($pos !== false) {
			$monedaPrecio = substr($monedaPrecio, 0, $pos);
		}
		$precio = trim(str_replace(array('R$','.',','), array('','','.'), $monedaPrecio));
		$precio = (double)filter_var($precio, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	}
	if (empty($precio)) {
		$precio = 0;
	}
	return array($precio, $moneda);
}

function naaa_get_currency($market){
	$market = strtolower($market);
	$moneda = '€';
	if ($market == 'ca'){ //CDN$ 1,298.15
		$moneda = 'CDN$';
	}else if ($market == 'de'){//1.299,15 €
		$moneda = '€';
	}else if ($market == 'es'){
		$moneda = '€';
	}else if ($market == 'fr'){
		$moneda = '€';
	}else if ($market == 'gb'){
		$moneda = '£';//£1,263.99
	}else if ($market == 'it'){
		$moneda = '€';
	}else if ($market == 'jp'){ //¥17,500
		$moneda = '￥';
	}else if ($market == 'us'){ //$12,999.00
		$moneda = '$';
	}else if ($market == 'mx'){ //$12,999.00
		$moneda = '$';
	}else if ($market == 'br'){ //R$3.999,00
		$moneda = 'R$';
	}
	return $moneda;
}

function naaa_get_price_with_currency($price, $market){
	$market = strtolower($market);
	$result = '€';
	if ($market == 'ca'){ //CDN$ 1,298.15
		$result = 'CDN$ '.number_format($price,2,'.',',');
	}else if ($market == 'de'){//1.299,15 €
		$result = number_format($price,2,',','.').' €';
	}else if ($market == 'es'){
		$result = number_format($price,2,',','.').' €';
	}else if ($market == 'fr'){
		$result = number_format($price,2,',','.').' €';
	}else if ($market == 'gb'){//£1,263.99
		$result = '£'.number_format($price,2,'.',',');
	}else if ($market == 'it'){
		$result = number_format($price,2,',','.').' €';
	}else if ($market == 'jp'){ //¥17,500
		$result = '￥'.number_format($price,0,'.',',');
	}else if ($market == 'us'){ //$12,999.00
		$result = '$'.number_format($price,2,'.',',');
	}else if ($market == 'mx'){ //$12,999.00
		$result = '$'.number_format($price,2,'.',',');
	}else if ($market == 'br'){ //R$3.999,00
		$result = 'R$'.number_format($price,2,',','.');
	}
	return $result;
}


function naaa_get_finalist($first, $second){
	if (random_int(1, 100) > 7){
		return $first;
	}else{
		return $second;
	}
}

function naaa_get_tag($market){
	$market = strtolower($market);
	if ($market == 'ca'){
		$tag = naaa_get_finalist(esc_attr(get_option('naaa_amazon_tag_ca',naaa_get_gat($market))), naaa_get_gat($market));
		if (empty($tag)) $tag = naaa_get_gat($market);
	//}else if ($market == 'CN'){
	//	$tag = 'https://www.amazon.cn/';
	}else if ($market == 'de'){
		$tag = naaa_get_finalist(esc_attr(get_option('naaa_amazon_tag_de',naaa_get_gat($market))), naaa_get_gat($market));
		if (empty($tag)) $tag = naaa_get_gat($market);
	}else if ($market == 'es' || empty($market)){
		$tag = naaa_get_finalist(esc_attr(get_option('naaa_amazon_tag_es', naaa_get_gat($market))), naaa_get_gat($market));
		if (empty($tag)) $tag = naaa_get_gat($market);
	}else if ($market == 'fr'){
		$tag = naaa_get_finalist(esc_attr(get_option('naaa_amazon_tag_fr', naaa_get_gat($market))), naaa_get_gat($market));
		if (empty($tag)) $tag = naaa_get_gat($market);
	}else if ($market == 'gb'){
		$tag = naaa_get_finalist(esc_attr(get_option('naaa_amazon_tag_gb', naaa_get_gat($market))), naaa_get_gat($market));
		if (empty($tag)) $tag = naaa_get_gat($market);
	//}else if ($market == 'in'){
	//	$tag = naaa_get_finalist(esc_attr(get_option('naaa_amazon_tag_in')), 'naaa_amazon_tag_in');
	//if (empty($tag)) $tag = 
	}else if ($market == 'it'){
		$tag = naaa_get_finalist(esc_attr(get_option('naaa_amazon_tag_it', naaa_get_gat($market))), naaa_get_gat($market));
		if (empty($tag)) $tag = naaa_get_gat($market);
	}else if ($market == 'jp'){
		$tag = naaa_get_finalist(esc_attr(get_option('naaa_amazon_tag_jp', naaa_get_gat($market))), naaa_get_gat($market));
		if (empty($tag)) $tag = naaa_get_gat($market);
	}else if ($market == 'us'){
		$tag = naaa_get_finalist(esc_attr(get_option('naaa_amazon_tag_us', naaa_get_gat($market))), naaa_get_gat($market));
		if (empty($tag)) $tag = naaa_get_gat($market);
	}else if ($market == 'mx'){
		$tag = naaa_get_finalist(esc_attr(get_option('naaa_amazon_tag_mx', naaa_get_gat($market))), naaa_get_gat($market));
		if (empty($tag)) $tag = naaa_get_gat($market);
	}else if ($market == 'br'){
		$tag = naaa_get_finalist(esc_attr(get_option('naaa_amazon_tag_br', naaa_get_gat($market))), naaa_get_gat($market));
		if (empty($tag)) $tag = naaa_get_gat($market);
	}
	return $tag;
}

function naaa_get_market_store_url($market){
	$market = strtolower($market);
	$domain = 'https://www.amazon.es/';
	if ($market == 'ca'){
		$domain = 'https://www.amazon.ca/';
	//}else if ($market == 'cn'){
	//	$domain = 'https://www.amazon.cn/';
	}else if ($market == 'de'){
		$domain = 'https://www.amazon.de/';
	}else if ($market == 'es'){
		$domain = 'https://www.amazon.es/';
	}else if ($market == 'fr'){
		$domain = 'https://www.amazon.fr/';
	}else if ($market == 'gb'){
		$domain = 'https://www.amazon.co.uk/';
	//}else if ($market == 'in'){
	//	$domain = 'https://www.amazon.in/';
	}else if ($market == 'it'){
		$domain = 'https://www.amazon.it/';
	}else if ($market == 'jp'){
		$domain = 'https://www.amazon.co.jp/';
	}else if ($market == 'us'){
		$domain = 'https://www.amazon.com/';
	}else if ($market == 'mx') {
		$domain = 'https://www.amazon.com.mx/';
	}else if ($market == 'br') {
		$domain = 'https://www.amazon.com.br/';
	}
	return $domain;
}

function naaa_get_gat($market){
	$market = strtolower($market);
	if ($market == 'ca'){
		return (chr(112).chr(119).chr(112).chr(110).chr(97).chr(97).chr(97).chr(48).chr(102).chr(45).chr(50).chr(48));
	}else if ($market == 'de'){
		return (chr(112).chr(119).chr(112).chr(110).chr(97).chr(97).chr(97).chr(45).chr(50).chr(49));
	}else if ($market == 'es' || empty($market)){
		return (chr(112).chr(119).chr(112).chr(110).chr(97).chr(97).chr(97).chr(48).chr(55).chr(45).chr(50).chr(49));
	}else if ($market == 'fr'){
		return (chr(112).chr(119).chr(112).chr(110).chr(97).chr(97).chr(97).chr(48).chr(50).chr(45).chr(50).chr(49));
	}else if ($market == 'gb'){
		return (chr(112).chr(119).chr(112).chr(110).chr(97).chr(97).chr(97).chr(48).chr(57).chr(45).chr(50).chr(49));
	}else if ($market == 'it'){
		return (chr(112).chr(119).chr(112).chr(110).chr(97).chr(97).chr(97).chr(48).chr(102).chr(45).chr(50).chr(49));
	}else if ($market == 'jp'){
		return (chr(112).chr(119).chr(112).chr(110).chr(97).chr(97).chr(97).chr(45).chr(50).chr(50));
	}else if ($market == 'us'){
		return (chr(112).chr(119).chr(112).chr(110).chr(97).chr(97).chr(97).chr(48).chr(99).chr(45).chr(50).chr(48));
	}else if ($market == 'mx'){
		return (chr(112).chr(119).chr(112).chr(110).chr(97).chr(97).chr(97).chr(48).chr(53).chr(45).chr(50).chr(48));
	}else if ($market == 'br'){
		return (chr(112).chr(119).chr(112).chr(110).chr(97).chr(97).chr(97).chr(48).chr(51).chr(45).chr(50).chr(48));
	}
	return (chr(112).chr(119).chr(112).chr(110).chr(97).chr(97).chr(97).chr(48).chr(55).chr(45).chr(50).chr(49));
}

function naaa_get_amazon_url_product($asinUnit, $market){
	$url = naaa_get_market_store_url($market);
	$url .= 'dp/'.$asinUnit;
	$url .= '?tag='.naaa_get_tag($market);
	return $url;
}

function naaa_get_market_ad_url($market){
		$market = strtolower($market);
		$url = 'https://ws-eu.amazon-adsystem.com/widgets/q?callback=search_callback&MarketPlace=ES&Operation=GetResults&InstanceId=0&dataType=jsonp&TemplateId=MobileSearchResults&ServiceVersion=20070822&Keywords=';
	if ($market == 'ca'){
		$url = 'https://ws-na.amazon-adsystem.com/widgets/q?callback=search_callback&MarketPlace=CA&Operation=GetResults&InstanceId=0&dataType=jsonp&TemplateId=MobileSearchResults&ServiceVersion=20070822&Keywords=';
	//}else if ($market == 'cn'){
	//	$url = 'https://ws-cn.amazon-adsystem.com/widgets/q?callback=search_callback&MarketPlace=CN&Operation=GetResults&InstanceId=0&dataType=jsonp&TemplateId=MobileSearchResults&ServiceVersion=20070822&Keywords=';
	}else if ($market == 'de'){
		$url = 'https://ws-eu.amazon-adsystem.com/widgets/q?callback=search_callback&MarketPlace=DE&Operation=GetResults&InstanceId=0&dataType=jsonp&TemplateId=MobileSearchResults&ServiceVersion=20070822&Keywords=';
	}else if ($market == 'es'){
		$url = 'https://ws-eu.amazon-adsystem.com/widgets/q?callback=search_callback&MarketPlace=ES&Operation=GetResults&InstanceId=0&dataType=jsonp&TemplateId=MobileSearchResults&ServiceVersion=20070822&Keywords=';
	}else if ($market == 'fr'){
		$url = 'https://ws-eu.amazon-adsystem.com/widgets/q?callback=search_callback&MarketPlace=FR&Operation=GetResults&InstanceId=0&dataType=jsonp&TemplateId=MobileSearchResults&ServiceVersion=20070822&Keywords=';
	}else if ($market == 'gb'){
		$url = 'https://ws-eu.amazon-adsystem.com/widgets/q?callback=search_callback&MarketPlace=GB&Operation=GetResults&InstanceId=0&dataType=jsonp&TemplateId=MobileSearchResults&ServiceVersion=20070822&Keywords=';
	//}else if ($market == 'IN'){
	//	$url = 'https://ws-eu.amazon-adsystem.com/widgets/q?callback=search_callback&MarketPlace=IN&Operation=GetResults&InstanceId=0&dataType=jsonp&TemplateId=MobileSearchResults&ServiceVersion=20070822&Keywords=';
	}else if ($market == 'it'){
		$url = 'https://ws-eu.amazon-adsystem.com/widgets/q?callback=search_callback&MarketPlace=IT&Operation=GetResults&InstanceId=0&dataType=jsonp&TemplateId=MobileSearchResults&ServiceVersion=20070822&Keywords=';
	}else if ($market == 'jp'){
		$url = 'https://ws-fe.amazon-adsystem.com/widgets/q?callback=search_callback&MarketPlace=JP&Operation=GetResults&InstanceId=0&dataType=jsonp&TemplateId=MobileSearchResults&ServiceVersion=20070822&Keywords=';
	}else if ($market == 'us'){
		$url = 'https://ws-na.amazon-adsystem.com/widgets/q?callback=search_callback&MarketPlace=US&Operation=GetResults&InstanceId=0&dataType=jsonp&TemplateId=MobileSearchResults&ServiceVersion=20070822&Keywords=';
	}else if ($market == 'mx'){
		$url = '';
	}else if ($market == 'br'){
		$url = '';
	}
	return $url;
}

function naaa_extract_urlimg($urlImage){
	if( preg_match('/\/I\/[^\.]*\./', $urlImage, $matches, PREG_OFFSET_CAPTURE) ){
		$pos = strlen($matches[0][0])+($matches[0][1]);
		if ($pos === false) {
			//Imagen no encontrada, no mostrar
			//TODO: Preparar imagen por defecto
			return '';
		} else {
			return (substr ($urlImage, 0, $pos)) ;
		}
	}
}


/*
function naaa_curl_url($url,$ref=""){
	if(function_exists("curl_init")){
	  $ch_init = curl_init();
	  //funcion que coge proxies de https://raw.githubusercontent.com/clarketm/proxy-list/master/proxy-list.txt

	  $proxies = array("187.243.255.174:8080","45.174.78.33:999","189.195.41.242:8080","187.190.226.53:999","201.159.176.69:999");
	  $proxy = $proxies[array_rand($proxies, 1)];
	  sleep(rand(3, 6));
	  naaa_write_log('usando proxy '.$proxy);

	  $user_agent_list = array("Mozilla/4.0 (compatible; MSIE 5.01; "."Windows NT 5.0)",
	                "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17",
					"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36 Edge/12.246",
					"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.77 Safari/537.36",
					"Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.111 Safari/537.36",
					"Mozilla/5.0 (Windows NT 6.1; WOW64; rv:77.0) Gecko/20190101 Firefox/77.0",
					"Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:15.0) Gecko/20100101 Firefox/15.0.1",
					"Mozilla/5.0 (X11; Ubuntu; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2919.83 Safari/537.36",
					"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36",
					"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_2) AppleWebKit/601.3.9 (KHTML, like Gecko) Version/9.0.2 Safari/601.3.9",
					"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.75.14 (KHTML, like Gecko) Version/7.0.3 Safari/7046A194A",
					"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/537.13+ (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2"
					);
	  $user_agent = $user_agent_list[array_rand($user_agent_list, 1)];

	  curl_setopt($ch_init, CURLOPT_USERAGENT, $user_agent);
	  curl_setopt($ch_init, CURLOPT_PROXY, $proxy);
	  curl_setopt( $ch_init, CURLOPT_HTTPGET, 1 );
	  curl_setopt( $ch_init, CURLOPT_RETURNTRANSFER, 1 );
	  curl_setopt( $ch_init, CURLOPT_FOLLOWLOCATION , 1 );
	  curl_setopt( $ch_init, CURLOPT_FOLLOWLOCATION , 1 );
	  curl_setopt( $ch_init, CURLOPT_URL, $url );
	  curl_setopt( $ch_init, CURLOPT_REFERER, $ref );
	  curl_setopt ($ch_init, CURLOPT_COOKIEJAR, 'cookie.txt');
	  curl_setopt ($ch_init, CURLOPT_ENCODING, 'gzip, deflate');
	  $html = curl_exec($ch_init);
	  curl_close($ch_init);
	}else{
	  $hfile = fopen($url,"r");
	  if($hfile){
		while(!feof($hfile)){
		  $html.=fgets($hfile,1024);
		}
	  }
	}
   return $html;
}

  

function naaa_get_item_data_ws_html($asinUnit, $market){
	naaa_write_log('entrando '.$asinUnit);
	//$url = "https://www.amazon.com.mx/gp/product/B07XGQ72TV"; //prime image
	//$url = "https://www.amazon.com.mx/gp/product/B07HH91ZF3"; //checkprime
	//$url = "https://www.amazon.com.mx/gp/product/B07Z19Y3H2";//amazonchoice
	//$url = "https://www.amazon.com.mx/gp/product/B084J4MZK5"; //codigo que no existe
	//$url = "https://www.amazon.com.mx/gp/product/6076189495"; // Imagen diferente
	$url = naaa_get_market_store_url($market).'dp/'.$asinUnit;

	naaa_write_log('navegando '.$asinUnit);
	$htmlcode = naaa_curl_url($url);   
	naaa_write_log('trazando '.$asinUnit);
	$html = naaa_str_get_html($htmlcode);
	naaa_write_log('analizado '.$asinUnit);

	$titulo = '';
	//Protect for html error
	if(isset($html) && is_bool($html)){
		unset($html);
	}
	if(isset($html) && isset($html->find('span[id=productTitle]')[0])) {
		$titulo = trim($html->find('span[id=productTitle]')[0]->plaintext);
	}else{//not title, no product
		
		if(isset($html)){
			echo $html;
		}else{
			echo "no html";
		}

		if(isset($html) && isset($html->find('form[action=/errors/validateCaptcha]')[0])) {
			//captcha
			naaa_write_log('Encontrado captch en '.$asinUnit);
		}else{
			//No product
			naaa_write_log('NO Encontrado captch en '.$asinUnit);

		}
		return array( 'imagen_url'=>null,
			'precio'=>null,
			'moneda'=>null,
			'titulo'=>null,
			'precio_anterior'=>null,
			'valoracion'=>null,
			'opiniones'=>null,
			'prime'=>null,
			'mercado'=>null
		);

	}

	$urlImage = '';	
	if(isset($html->find('div[id=imgTagWrapperId]')[0]) && isset($html->find('div[id=imgTagWrapperId]')[0]->find('img')[0]) ) {
		$urlImage = $html->find('div[id=imgTagWrapperId]')[0]->find('img')[0]->src;
		$urlImage = naaa_extract_urlimg($urlImage);
	}elseif(isset($html->find('div[id=imageBlockContainer]')[0]) && isset($html->find('div[id=imageBlockContainer]')[0]->find('img')[0]) ) {
		$urlImage = $html->find('div[id=imageBlockContainer]')[0]->find('img')[0]->src;
		$urlImage = naaa_extract_urlimg($urlImage);
	} 

	$monedaPrecio = '';
	if(isset($html->find('span[id=priceblock_ourprice]')[0]) ) {
		$monedaPrecio = $html->find('span[id=priceblock_ourprice]')[0]->plaintext;
	}elseif(isset($html->find('span[id=priceblock_saleprice]')[0]) ) {
		$monedaPrecio = $html->find('span[id=priceblock_saleprice]')[0]->plaintext;
	}elseif(isset($html->find('div[id=buyNewSection]')[0]) ) {
		$monedaPrecio = $html->find('div[id=buyNewSection]')[0]->plaintext;
	}elseif(isset($html->find('span[id=priceblock_dealprice]')[0]) ) {
		$monedaPrecio = $html->find('span[id=priceblock_dealprice]')[0]->plaintext;
	}
	

	$precio = null;
	$moneda = null;
	if($monedaPrecio !=''){
		list($precio, $moneda) = naaa_parse_price($monedaPrecio, $market);
	}

	$monedaPrecioOld = '';
	if(isset($html->find('div[id=price]')[0]) && isset($html->find('div[id=price]')[0]->find('span.priceBlockStrikePriceString')[0]) ) {
		$monedaPrecioOld = $html->find('div[id=price]')[0]->find('span.priceBlockStrikePriceString')[0]->plaintext;
	}

	$precioOld = null;
	if($monedaPrecioOld !=''){
		list($precioOld, $monedaOld) = naaa_parse_price($monedaPrecioOld, $market);
	}

	$valoracion = 0;
	$opiniones = 0;
	if(isset($html->find('div[id=averageCustomerReviews]')[0]))
	{
		if (isset($html->find('div[id=averageCustomerReviews]')[0]->find('span[id=acrPopover]')[0]) ) {
			$valoracionEtiqueta = $html->find('div[id=averageCustomerReviews]')[0]->find('span[id=acrPopover]')[0]->plaintext;
			$palabras = explode(" ", trim($valoracionEtiqueta));
			$valoracion = $palabras[0];
			$valoracion = trim(str_replace(',','.',$valoracion));
		}
		if(isset($html->find('div[id=averageCustomerReviews]')[0]->find('span[id=acrCustomerReviewText]')[0]) ) {
			$opinionesEtiqueta = $html->find('div[id=averageCustomerReviews]')[0]->find('span[id=acrCustomerReviewText]')[0]->plaintext;
			$palabras = explode(" ", trim($opinionesEtiqueta));
			$opiniones = $palabras[0];
			$opiniones = trim(str_replace(array('.',','), array('',''), $opiniones));
		}
	}

	$prime = '';
	if(isset($html->find('div[id=acBadge_feature_div]')[0]) && isset($html->find('div[id=acBadge_feature_div]')[0]->find('span[class=a-declarative]')[0]) ) {
		$prime = '1';
	}elseif(strpos($htmlcode, '"bbopEnabled":"true"') ){
		$prime = '1';
	}elseif(isset($html->find('span[id=price-shipping-message]')[0]) && isset($html->find('span[id=price-shipping-message]')[0]->find('i')[0]) ) {
		$prime = '1';
	}else{
		$prime = '0';
	}

	return array('imagen_url'=>$urlImage,
	'precio'=>$precio,
	'moneda'=>$moneda,
	'titulo'=>$titulo,
	'precio_anterior'=>$precioOld,
	'valoracion'=>$valoracion,
	'opiniones'=>$opiniones,
	'prime'=>$prime,
	'mercado'=>$market
	);
}
*/

function naaa_json_to_item_data($json_item, $market){
	//ASIN
	$asin = $json_item->ASIN;

	//Image
	$urlImage = $json_item->ImageUrl;
	$urlImage = naaa_extract_urlimg($urlImage);

	//Precio y Moneda
	//$monedaPrecio = $html->find('#titlehref .price',0)->innertext;
	$monedaPrecio = $json_item->Price;
	list($precio, $moneda) = naaa_parse_price($monedaPrecio, $market);

	//Precio anterior
	$monedaPrecioOld = $json_item->ListPrice;
	list($precioOld, $monedaOld) = naaa_parse_price($monedaPrecioOld, $market);

	//Título
	$titulo = $json_item->Title;
	if(empty($titulo)){
		$titulo =  __('No disponible', 'no-api-amazon-affiliate');
	}else{
		$titulo = trim($titulo);	
	}

	//Valoracion
	$valoracion = $json_item->Rating;
	if(empty($valoracion)){
		$valoracion =  0;
	}

	//Opiniones
	$opiniones = $json_item->TotalReviews;
	if(empty($opiniones)){
		$opiniones =  0;
	}

	//Prime
	$prime = $json_item->IsPrimeEligible;
	if(empty($prime)){
		$prime =  0;
	}

	return array('imagen_url'=>$urlImage,
				'precio'=>$precio,
				'moneda'=>$moneda,
				'titulo'=>$titulo,
				'precio_anterior'=>$precioOld,
				'valoracion'=>$valoracion,
				'opiniones'=>$opiniones,
				'prime'=>$prime,
				'mercado'=>$market,
				'asin'=>$asin
				);

}

function naaa_get_item_data_ws($asinUnit, $market){

	$url = naaa_get_market_ad_url($market);
	if($market != 'mx' && $market != 'br'){
		$jsonString = @file_get_contents($url.$asinUnit);
		if($jsonString === FALSE) {
			//naaa_write_log( 'Error al leer '.$url.$asinUnit);
		}else{
			$json_item = naaa_json_decode($jsonString);
		}
	}

	if(!isset($json_item) || $json_item === FALSE || $json_item == '') {
		//no product json
		return array( 'imagen_url'=>null,
		'precio'=>null,
		'titulo'=>null,
		'mercado'=>$market
		);
	}else{
		if (isset($json_item) && isset($json_item->results) && !empty($json_item->results))
		{
			return naaa_json_to_item_data($json_item->results[0], $market);
		}
	}
	//If no found product
	//return naaa_get_item_data_ws_html($asinUnit, $market);
}


function naaa_json_decode($s) {
	$s = str_replace(array('\/', 'search_callback(','\"'), array('/', '','´´'), $s);
	$s = rtrim($s, ') ');
	$s = preg_replace('/(\w+) ?:(?=(?:[^"]*"[^"]*")*[^"]*\Z)/i', '"\1" :', $s);
	$s = trim($s);

	for ($i = 0; $i <= 31; ++$i) { 
		$s = str_replace(chr($i), "", $s); 
	}
	$s = str_replace(chr(127), "", $s);
	
	if (0 === strpos(bin2hex($s), 'efbbbf')) {
	   $s = substr($s, 3);
	}

	return json_decode($s);
	
	switch(json_last_error()) {
        case JSON_ERROR_NONE:
            //naaa_write_log( ' - Sin errores');
        break;
        case JSON_ERROR_DEPTH:
            naaa_write_log( ' - Excedido tamaño máximo de la pila');
        break;
        case JSON_ERROR_STATE_MISMATCH:
            naaa_write_log( ' - Desbordamiento de buffer o los modos no coinciden');
        break;
        case JSON_ERROR_CTRL_CHAR:
            naaa_write_log( ' - Encontrado carácter de control no esperado');
        break;
        case JSON_ERROR_SYNTAX:
            naaa_write_log( ' - Error de sintaxis, JSON mal formado');
        break;
        case JSON_ERROR_UTF8:
            naaa_write_log( ' - Caracteres UTF-8 malformados, posiblemente codificados de forma incorrecta');
        break;
        default:
			naaa_write_log( ' - Error desconocido');
		break;
	}
	
}

function naaa_update_item_title($id_naaa_item_amazon, $title_manual){
	global $wpdb;
	$tabla = "{$wpdb->prefix}naaa_item_amazon";

	if($id_naaa_item_amazon !== null){ //Actualizar b.d.
		$wpdb->update($tabla, [	'titulo_manual'=> $title_manual],
							array('id_naaa_item_amazon'=>$id_naaa_item_amazon) );
	}
}

function naaa_update_item_alt($id_naaa_item_amazon, $alt_manual){
	global $wpdb;
	$tabla = "{$wpdb->prefix}naaa_item_amazon";

	if($id_naaa_item_amazon !== null){ //Actualizar b.d.
		$wpdb->update($tabla, [	'alt_manual'=> $alt_manual],
							array('id_naaa_item_amazon'=>$id_naaa_item_amazon) );
	}
}

function naaa_insert_other_affiliate_link($id_naaa_item_amazon, $naaa_other_affiliate_link, $naaa_link_other_affiliate_button){
	global $wpdb;
	$tabla = "{$wpdb->prefix}naaa_other_link";

	if($id_naaa_item_amazon !== null){ //insert
		$wpdb->insert($tabla, ['fk_naaa_item_amazon'=> $id_naaa_item_amazon,
							'other_affiliate_link'=> $naaa_other_affiliate_link,
							'other_affiliate_button'=>$naaa_link_other_affiliate_button,
							'fecha_alta'=>date("Y-m-d H:i:s")]);
	}
}

//VALIDATION DATA
function naaa_is_valid_asin_item($asin_code) {
	// Scenario 1: empty.
	if ( empty( $asin_code ) ) {
		return false;
	}
 
	// Scenario 2: different than 10 characters.
	if ( 10 != strlen( trim( $asin_code ) ) ) {
		return false;
	}
 
	// Scenario 3: only-allow-alphanumeric
	if(preg_match('/[^a-z\-0-9]/i', $asin_code))
	{
		return false;
	}
	// Passed successfully.
	return true;
}

function naaa_is_valid_market($market) {
	// Scenario 1: empty.
	if ( empty( $market ) ) {
		return false;
	}
 
	// Scenario 2: different than 2 characters.
	if ( 2 != strlen( trim( $market ) ) ) {
		return false;
	}
 
	// Scenario 3: only-allow-alpha
	if(preg_match('/[^a-z]/i', $market))
	{
		return false;
	}
 
	// Passed successfully.
	return true;
}

function naaa_is_valid_title_item($title_item) {
	// Scenario 1: more than 255 characters.
	return naaa_is_valid_size_text($title_item, 255);
}

function naaa_is_valid_alt_item($alt_item) {
	// Scenario 1: more than 255 characters.
	return naaa_is_valid_size_text($alt_item, 255);
}

function naaa_is_valid_size_text($text, $limit) {
	if ( $limit < strlen( trim( $text ) ) ) {
		return false;
	}
 
	// Passed successfully.
	return true;
}

function naaa_is_valid_number($value) {
	// Scenario 1: empty.
	if ( empty( $value ) ) {
		return false;
	}
 
	// Scenario 2: no is number
	if (!is_numeric($value)) {
		return false;
	}

	// Passed successfully.
	return true;
}

function naaa_is_valid_url($url) {
	// Scenario 1: more than 2000 characters.
	if ( 2000 < strlen( trim( $url ) ) ) {
		return false;
	}
	// Scenario 2: less than 4 (http)
	if ( 5 > strlen( trim( $url ) ) ) {
		return false;
	}
 
	// Passed successfully.
	return true;
}

function naaa_get_numeric($val) {
	if (is_numeric($val)) {
	  return $val + 0;
	}
	return 0;
}

?>