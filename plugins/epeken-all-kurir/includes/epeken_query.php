<?php
  if ( ! defined( 'ABSPATH' ) ) exit;
  
function epeken_get_usd_rate($source){
        $license = sanitize_text_field(get_option('epeken_wcjne_license_key'));
	$source = sanitize_text_field($source);
        $source = 'BI';
        $url = EPEKEN_GET_USDRATE_API.$license.'/'.$source.'/'.'epeken-all-kurir';
	$response = wp_remote_get($url);
	$content = wp_remote_retrieve_body($response);
        return $content;
}

  
function epeken_get_list_of_kota_kabupaten ()
{
	$kotakabreturn = array();
	$file_kota_kab = EPEKEN_KOTA_KAB;
	$file_kota_kab = apply_filters('epeken_kotakab', $file_kota_kab);
	$string = file_get_contents($file_kota_kab);
	$json = json_decode($string,true);
	$array_kota = $json['listkotakabupaten'];
	$kotakabreturn [''] = 'Kota/Kabupaten (City)';
	foreach($array_kota as $element){
		$kotakabreturn[$element['kotakab']] = $element['kotakab'];	
	}
	return $kotakabreturn;
}

function epeken_get_all_provinces() {
	$license_key = sanitize_text_field(get_option('epeken_wcjne_license_key'));
	$url = EPEKEN_API_GET_PRV.$license_key;
	$response = wp_remote_get($url);
	$content = wp_remote_retrieve_body($response);
	return $content;
}

function epeken_get_track_info($kurir,$awb) {
    $license = sanitize_text_field(get_option('epeken_wcjne_license_key')); 
	$kurir = sanitize_text_field($kurir);
	$awb = sanitize_text_field($awb);
    $url = EPEKEN_TRACKING_END_POINT.$license.'/'.$kurir.'/'.$awb;
	$response = wp_remote_get($url);
	$content = wp_remote_retrieve_body($response);
    return $content;
}  

function epeken_get_list_of_kota($province) {
	$countries_obj = new WC_Countries();
	$states = $countries_obj -> get_states('ID');
	$province = sanitize_text_field($province);
	$province = $states[$province];
	$kotareturn = array();
	$string = file_get_contents(EPEKEN_PROVINCE);
	$string = apply_filters('epeken_province', $string);
	$json = json_decode($string, true);
	$array_province = $json['prop'];	
	foreach($array_province as $element) {
	  if($element['province'] === $province) {
			$kotareturn [$element['kota_kabupaten']] = $element['kota_kabupaten'];
		}
	}
 	return $kotareturn;
} 
  
function epeken_get_list_of_kecamatan ($kotakab)
{
	$kotakab = sanitize_text_field($kotakab);
	$kecamatanreturn = array();
	if ($kotakab === 'init'){
                 $kecamatanreturn [''] = 'Please Select Kecamatan';
                return $kecamatanreturn;
        }
	$string = file_get_contents(EPEKEN_KOTA_KEC);
	$string = apply_filters('epeken_kecamatan',$string);
	$json = json_decode($string, true);
	$array_kecamatan = $json['listkecamatan'];
	$kecamatanreturn[''] = 'Kecamatan (District)';
	foreach($array_kecamatan as $element){
		if ($element["kota_kabupaten"] === $kotakab) {
			$kecamatanreturn [$element["kecamatan"]] = $element["kecamatan"];
		}	
	}
	return $kecamatanreturn;
}

function epeken_code_to_city($code) {
	$string = file_get_contents(EPEKEN_KOTA_KAB);
	$city = "";
	$json = json_decode($string,true);
        $array_kota = $json['listkotakabupaten'];
	foreach($array_kota as $element){
         if($element['code'] === sanitize_text_field($code)){
            $city = $element["kotakab"];
            break;
         }
        }
 	return $city;
}

function epeken_city_to_code($city) {
	$string = file_get_contents(EPEKEN_KOTA_KAB);
        $code = "";
        $json = json_decode($string,true);
        $array_kota = $json['listkotakabupaten'];
	$city = urldecode(sanitize_text_field($city));
        foreach($array_kota as $element){
          if($element['kotakab'] === $city){
             $code = $element["code"];
              break;
          }
        }
        return $code;
}

function epeken_get_tarif($kotakab, $kecamatan, $product_origin = false) {		
	$kotakab = sanitize_text_field(urldecode($kotakab));
	$kecamatan = sanitize_text_field(urldecode($kecamatan));
	$license_key = get_option('epeken_wcjne_license_key');
	$options = get_option('woocommerce_epeken_courier_settings');
        $origin_code = isset($options['data_kota_asal']) ? $options['data_kota_asal'] : null;
	$destination_code = "";
	$string = file_get_contents(EPEKEN_KOTA_KAB);
        $json = json_decode($string,true);
	$array_kota = $json['listkotakabupaten'];
        foreach($array_kota as $element){
 	  if($element['kotakab'] === $kotakab){
		$destination_code = $element["code"];
		break;
	  }
        }
	$content = "";	
	if ($product_origin != false)
		$origin_code = epeken_city_to_code(sanitize_text_field($product_origin));
	
	if ($destination_code !=="") {	
		$kotakab = str_replace("/","{slash}",$kotakab);
                $kecamatan = str_replace("/","{slash}",$kecamatan);
	  	$url = EPEKEN_API_DIR_URL.$license_key."/".$origin_code.
			"/".$destination_code."/".urlencode($kotakab)."/".urlencode($kecamatan);
		$response = wp_remote_get($url);
		$content = wp_remote_retrieve_body($response);
		if(strpos($content,'404 Page Not Found') !== FALSE) {
		 $content = '';
		}
	}
	return $content;
}

function epeken_get_valid_origin($license) {
	$content = "";
	$license = sanitize_text_field($license);
	$url = EPEKEN_VALID_ORIGIN.$license;
	$response = wp_remote_get($url);
	$content = wp_remote_retrieve_body($response);
	return $content;
}

function epeken_get_tarif_pt_pos_v3($kotakab,$kecamatan,$weight, $price, $length, $width, $height, $product_origin=false ){
	//weight is in gram	
	$kotakab = urldecode($kotakab);
	$kecamatan = urldecode($kecamatan);
	$license_key = sanitize_text_field(get_option('epeken_wcjne_license_key'));
        $kecamatan = str_replace("/","{slash}",$kecamatan);
	$kecamatan = urlencode($kecamatan);
        $options = get_option('woocommerce_epeken_courier_settings');
        $origin_code = $options['data_kota_asal'];
        $destination_code = "";
        $string = file_get_contents(EPEKEN_KOTA_KAB);
        $json = json_decode($string,true);
        $array_kota = $json['listkotakabupaten'];
        foreach($array_kota as $element){
             if($element['kotakab'] === $kotakab){
                 $destination_code = $element["code"];
                 break;
             }
        }
        $content = "";
	$url = "";
        if ($product_origin != false)
           $origin_code = epeken_city_to_code(sanitize_text_field($product_origin));

	if ($destination_code !=="") {
           $url = EPEKEN_API_POS_URL_V3.$license_key."/".$origin_code."/".$destination_code."/".$kecamatan."/".
		  sanitize_text_field($weight)."/".sanitize_text_field($price)."/".sanitize_text_field($length)."/".
		  sanitize_text_field($width)."/".sanitize_text_field($height);
	   $response = wp_remote_get($url);
	   $content = wp_remote_retrieve_body($response);
        }
        return $content;
}

function epeken_get_tarif_rpx($kotakab,$kecamatan,$weight, $price, $length, $width, $height, $product_origin=false ){
	//weight is in gram	
	$kotakab = urldecode($kotakab);
	$kecamatan = urldecode($kecamatan);
	$license_key = sanitize_text_field(get_option('epeken_wcjne_license_key'));
    $kecamatan = str_replace("/","{slash}",$kecamatan);
	$kecamatan = urlencode($kecamatan);
	$options = get_option('woocommerce_epeken_courier_settings');
	$origin_code = $options['data_kota_asal'];
	$destination_code = "";
	$string = file_get_contents(EPEKEN_KOTA_KAB);
	$json = json_decode($string,true);
	$array_kota = $json['listkotakabupaten'];
	foreach($array_kota as $element){
		if($element['kotakab'] === $kotakab){
			$destination_code = $element["code"];
			break;
		}
	}
	$content = "";
	$url = "";
	if ($product_origin != false)
		$origin_code = epeken_city_to_code(sanitize_text_field($product_origin));

	if ($destination_code !=="") {
      	$url = EPEKEN_API_RPX.$license_key."/".$origin_code."/".$destination_code."/".$kecamatan."/".
	  	sanitize_text_field($weight)."/".sanitize_text_field($price)."/".sanitize_text_field($length)."/".
		sanitize_text_field($width)."/".sanitize_text_field($height);
	   	$response = wp_remote_get($url);
	   	$content = wp_remote_retrieve_body($response);
    }
    return $content;
}
  
function epeken_get_tarif_lion ($kotakab, $kecamatan, $weight, $product_origin=false) {
	$kotakab = sanitize_text_field(urldecode($kotakab));
	$kecamatan = sanitize_text_field(urldecode($kecamatan));
	$weight = sanitize_text_field($weight);
	if (empty($weight)) 
	   $weight = 1;

	if ($weight < 1)
	   $weight = 1;
		
	$license_key = sanitize_text_field(get_option('epeken_wcjne_license_key'));
	$options = get_option('woocommerce_epeken_courier_settings');
	$origin_code = sanitize_text_field($options['data_kota_asal']);	
	$origin_city = epeken_code_to_city($origin_code);
	if ($product_origin != false)
		$origin_city = sanitize_text_field($product_origin);
		
	if(empty($weight) || $weight < 1)
		$weight = 1;

	$kotakab = str_replace("/","{slash}",$kotakab);
        $kecamatan = str_replace("/","{slash}",$kecamatan);
	$origin_city = urlencode($origin_city);
	$kotakab = urlencode ($kotakab);
	$weight = urlencode($weight);
	$kecamatan = urlencode($kecamatan);

	$url = EPEKEN_API_LION.$license_key."/".$origin_city."/".$kotakab."/".$kecamatan."/".$weight;
	$response = wp_remote_get($url);
	$content = wp_remote_retrieve_body($response);
	return $content;

}

function epeken_get_wahana_ongkir($kotakab, $kecamatan, $weight, $product_origin=false) {
					      //weight in kg
	$kotakab = sanitize_text_field(urldecode($kotakab));
	$kecamatan = sanitize_text_field(urldecode($kecamatan));
	if (empty($weight)) 
	   $weight = 1;

	if ($weight < 1)
	   $weight = 1;

	$license_key = sanitize_text_field(get_option('epeken_wcjne_license_key'));
	$options = get_option('woocommerce_epeken_courier_settings');
	$origin_code = $options['data_kota_asal'];	
	$origin_city = epeken_code_to_city($origin_code);
	if ($product_origin != false)
	   $origin_city = sanitize_text_field($product_origin);
		
	if(empty($weight) || $weight < 1)
	   $weight = 1;

	$kotakab = str_replace("/","{slash}",$kotakab);
        $kecamatan = str_replace("/","{slash}",$kecamatan);

	$origin_city = urlencode($origin_city);
	$kotakab = urlencode ($kotakab);
	$weight = urlencode($weight);
	$kecamatan = urlencode($kecamatan);

	$url = EPEKEN_API_WAHANA.$license_key."/".$origin_city."/".$kotakab."/".$kecamatan."/".$weight;
	$response = wp_remote_get($url);
	$content = wp_remote_retrieve_body($response);
	return $content;
}

function epeken_get_custom_tarif($kotakab, $weight, $product_origin = false, $kecamatan = false) {
		//weight in kg
	$kotakab = sanitize_text_field(urldecode($kotakab));
        $kecamatan = sanitize_text_field(urldecode($kecamatan));
	if (empty($weight)) 
              $weight = 1;

        if ($weight < 1)
              $weight = 1;

	$license_key = sanitize_text_field(get_option('epeken_wcjne_license_key'));
	$options = get_option('woocommerce_epeken_courier_settings');
	$origin_code = sanitize_text_field($options['data_kota_asal']);   

        if ($product_origin != false) {
           $origin_code = epeken_city_to_code(sanitize_text_field($product_origin));
	}

        $kotakab_code = epeken_city_to_code($kotakab);
        $weight = urlencode($weight);
	$url = EPEKEN_API_CUSTOM_TARIF.$license_key."/".$origin_code."/".$kotakab_code."/".$weight;
		
	if(!empty($kecamatan)){
		$kecamatan = urlencode($kecamatan);
		$url .= "/".$kecamatan;
	}
	$response = wp_remote_get($url);
	$content = wp_remote_retrieve_body($response);
        return $content;	
}

function epeken_get_jne_trucking_tarif($kotakab, $kecamatan, $weight, $product_origin = false) {
                //weight in kg
	$kotakab = sanitize_text_field(urldecode($kotakab));
        $kecamatan = sanitize_text_field(urldecode($kecamatan));	
        if (empty($weight)) 
                $weight = 1;
        if ($weight < 1)
                $weight = 1;
        $license_key = sanitize_text_field(get_option('epeken_wcjne_license_key'));
        $options = get_option('woocommerce_epeken_courier_settings');
	$origin_code = sanitize_text_field($options['data_kota_asal']);    
        if ($product_origin != false) {
                $origin_code = epeken_city_to_code($product_origin);
        }   
        $kotakab_code = epeken_city_to_code($kotakab);
        $weight = urlencode($weight);
	$kecamatan = urlencode($kecamatan);
        $url = EPEKEN_API_JNE_TRUCKING.$license_key."/".$origin_code."/".$kotakab_code."/".$kecamatan.'/'.$weight;
	$response = wp_remote_get($url);
	$content = wp_remote_retrieve_body($response);
        return $content;    

}   

function epeken_get_dakota_tarif($kotakab, $kecamatan, $weight, $product_origin = false) {
                //weight in kg
	$kotakab = sanitize_text_field(urldecode($kotakab));
	$kecamatan = sanitize_text_field(urldecode($kecamatan));
        if (empty($weight))
            $weight = 1;
        if ($weight < 1)
            $weight = 1;
        $license_key = get_option('epeken_wcjne_license_key');
	$options = get_option('woocommerce_epeken_courier_settings');
        $origin_code = $options['data_kota_asal'];
        if ($product_origin != false) {
            $origin_code = epeken_city_to_code($product_origin);
        }
        $kotakab_code = epeken_city_to_code($kotakab);
        $weight = urlencode($weight);
        $kecamatan = urlencode($kecamatan);
        $url = EPEKEN_API_DAKOTA.$license_key."/".$origin_code."/".$kotakab_code."/".$kecamatan.'/'.$weight;
	$response = wp_remote_get($url);
	$content = wp_remote_retrieve_body($response);
        return $content;
}

function epeken_get_jet_ongkir($kotakab, $kecamatan, $weight, $product_origin=false) {
                                              //weight in kg
	$kotakab = sanitize_text_field(urldecode($kotakab));
        $kecamatan = sanitize_text_field(urldecode($kecamatan));
        if (empty($weight)) 
           $weight = 1;

        if ($weight < 1)
           $weight = 1;

        $license_key = get_option('epeken_wcjne_license_key');
        $options = get_option('woocommerce_epeken_courier_settings');
        $origin_code = $options['data_kota_asal'];    
        $origin_city = epeken_code_to_city($origin_code);
		
        if ($product_origin != false)
           $origin_city = $product_origin;
    
        if(empty($weight) || $weight < 1)
           $weight = 1;

	$kotakab = str_replace("/","{slash}",$kotakab);
        $kecamatan = str_replace("/","{slash}",$kecamatan);
        $origin_city = urlencode($origin_city);
        $kotakab = urlencode ($kotakab);
        $weight = urlencode($weight);
	$kecamatan = urlencode($kecamatan);

        $url = EPEKEN_API_JET.$license_key."/".$origin_city."/".$kotakab."/".$kecamatan."/".$weight;
	$response = wp_remote_get($url);
	$content = wp_remote_retrieve_body($response);
        return $content;
} 

function epeken_get_atlas_ongkir($kotakab, $weight, $product_origin=false) {
	$kotakab = epeken_sanitize_atlas_city(sanitize_text_field(urldecode($kotakab)));
	if(empty($weight) || $weight < 1)
		$weight = 1;
	$license_key = sanitize_text_field(get_option('epeken_wcjne_license_key'));
	$options = get_option('woocommerce_epeken_courier_settings');
        $origin_code = sanitize_text_field($options['data_kota_asal']);    
        $origin_city = epeken_sanitize_atlas_city(sanitize_text_field(epeken_code_to_city($origin_code)));
	if ($product_origin != false)
           $origin_city = epeken_sanitize_atlas_city($product_origin);
	$origin_city = str_replace("Kota ","",$origin_city);
 	$origin_city = str_replace("Kabupaten ","",$origin_city);
	$kotakab = str_replace("Kota ", "", $kotakab);
	$kotakab = str_replace("Kabupaten ", "", $kotakab);
   	$kotakab = strtoupper(urlencode($kotakab));
	$origin_city = strtoupper(urlencode($origin_city));
	$url = EPEKEN_API_ATLAS.$license_key."/".$origin_city."/".$kotakab."/".$weight;
	$response = wp_remote_get($url);
	$content = wp_remote_retrieve_body($response);
 	return $content;	 
}

function epeken_sanitize_atlas_city($city){
       if(strpos($city,'Jakarta') !== false)
		$city = 'Jakarta';
       else if(strpos($city,'Banyumas') !== false)
  		$city = 'Purwokerto';
       return $city;
}

function epeken_get_sicepat_ongkir($kotakab, $kecamatan, $weight, $product_origin=false) {
                                              //weight in kg
	$kotakab = sanitize_text_field(urldecode($kotakab));
        $kecamatan = sanitize_text_field(urldecode($kecamatan));
        if (empty($weight)) 
            $weight = 1;

        if ($weight < 1)
            $weight = 1;

        $license_key = sanitize_text_field(get_option('epeken_wcjne_license_key'));
        $options = get_option('woocommerce_epeken_courier_settings');
        $origin_code = sanitize_text_field($options['data_kota_asal']);    
        $origin_city = epeken_code_to_city($origin_code);
    
        if ($product_origin != false)
            $origin_city = $product_origin;
    
        if(empty($weight) || $weight < 1)
            $weight = 1;

        $kotakab = str_replace("/","{slash}",$kotakab);
        $kecamatan = str_replace("/","{slash}",$kecamatan);
        $origin_city = urlencode($origin_city);
        $kotakab = urlencode ($kotakab);
        $weight = urlencode($weight);
        $kecamatan = urlencode($kecamatan);
        $url = EPEKEN_API_SICEPAT.$license_key."/".$origin_city."/".$kotakab."/".$kecamatan."/".$weight;
	$response = wp_remote_get($url);
	$content = wp_remote_retrieve_body($response);
        return $content;
}   

function epeken_get_ninja_express_tariff($kotakab,$kecamatan,$weight, $product_origin = false) {
	//EPEKEN_SERVER_URL.'/api/index.php/epeken_get_ninja_express_tarif/';
	$kotakab = sanitize_text_field(urldecode($kotakab));
	$kecamatan = sanitize_text_field(urldecode($kecamatan));
	if (empty($weight))
                        $weight = 1;
        if ($weight < 1)
                        $weight = 1;
	$license_key = sanitize_text_field(get_option('epeken_wcjne_license_key'));
        $options = get_option('woocommerce_epeken_courier_settings');
        $origin_code = sanitize_text_field($options['data_kota_asal']);
        $origin_city = epeken_code_to_city($origin_code);
	if ($product_origin != false)
           $origin_city = $product_origin;

	$kotakab = str_replace("/","{slash}",$kotakab);
        $kecamatan = str_replace("/","{slash}",$kecamatan);
	$origin_city = urlencode($origin_city);
        $kotakab = urlencode ($kotakab);
        $weight = urlencode($weight);
        $kecamatan = urlencode($kecamatan);	
	
	$url = EPEKEN_API_NINJA.$license_key."/".$origin_city."/".$kotakab."/".$kecamatan."/".$weight;
	$response = wp_remote_get($url);
	$content = wp_remote_retrieve_body($response);
        return $content;
  }	
  
  function epeken_get_sap_express_tariff($kotakab, $kecamatan, $weight, $product_origin = false) {
	 $kotakab = sanitize_text_field(urldecode($kotakab));
	 $kecamatan = sanitize_text_field(urldecode($kecamatan));
	  if (empty($weight)) 
                        $weight = 1;
	  if ($weight < 1)
                        $weight = 1;
					
	  $license_key = sanitize_text_field(get_option('epeken_wcjne_license_key'));
	  $options = get_option('woocommerce_epeken_courier_settings');
      	  $origin_code = sanitize_text_field($options['data_kota_asal']);    
      	  $origin_city = epeken_code_to_city($origin_code);
		
	  if ($product_origin != false)
                        $origin_city = $product_origin;
	  
	  $origin_city = urlencode($origin_city);
      	  $kotakab = urlencode ($kotakab);
      	  $weight = urlencode($weight);
      	  $kecamatan = urlencode($kecamatan);
	  
	  $url = EPEKEN_API_SAP_EXPRESS.$license_key."/".$origin_city."/".$kotakab."/".$kecamatan."/".$weight;
	  $response = wp_remote_get($url);
	  $content = wp_remote_retrieve_body($response);
      	  return $content;
  }
  
  function epeken_get_jmx_tariff($kotakab, $kecamatan, $weight, $product_origin = false) {
	$kotakab = sanitize_text_field(urldecode($kotakab));
        $kecamatan = sanitize_text_field(urldecode($kecamatan));
	if(empty($weight) || $weight == 0)
		$weight = 1;
		
	$license_key = sanitize_text_field(get_option('epeken_wcjne_license_key'));
	$options = get_option('woocommerce_epeken_courier_settings');
        $origin_code = sanitize_text_field($options['data_kota_asal']);
	$origin_city = epeken_code_to_city($origin_code);
	$content = "";
	if ($product_origin != false)
		$origin_city = sanitize_text_field($product_origin);

	$kotakab = str_replace("/","{slash}",$kotakab);
        $kecamatan = str_replace("/","{slash}",$kecamatan);
	$url = EPEKEN_API_JMX.$license_key."/".urlencode($origin_city)."/".urlencode($kotakab)."/".urlencode($kecamatan)."/".$weight;
	$response = wp_remote_get($url);
	$content = wp_remote_retrieve_body($response);
	return $content;
  }
  
function epeken_get_nss_tariff($kotakab, $kecamatan, $weight, $product_origin = false) {
  $kotakab = sanitize_text_field(urldecode($kotakab));
  $kecamatan = sanitize_text_field(urldecode($kecamatan));
  if(empty($weight) || $weight == 0)
	   $weight = 1;
	   
	$license_key = sanitize_text_field(get_option('epeken_wcjne_license_key'));
	$options = get_option('woocommerce_epeken_courier_settings');
        $origin_code = sanitize_text_field($options['data_kota_asal']);
	$origin_city = epeken_code_to_city($origin_code);
	$content = "";	
	if ($product_origin != false)
		$origin_city = $product_origin;
	
	$kotakab = str_replace("/","{slash}",$kotakab);
	$url = EPEKEN_API_NSS.$license_key."/".urlencode($origin_city)."/".urlencode($kotakab)."/1";
	$response = wp_remote_get($url);
	$content = wp_remote_retrieve_body($response);
	$content = str_replace("<br/>","",$content);
	$content = rtrim($content,',}');
	$content = '{"result":'.$content.'}}';
	$array = json_decode($content,true);
	$is_error = $array["result"]["error"];
	if($is_error === "false") {
		$array = $array["result"]["tarif"];
		$json_elem_tarif = "";
		$i=0;
		foreach($array as $array_tariff) {
			$the_cost = $array_tariff['tarif'];
			$tarif_add = $array_tariff['tarif_add'];
			if($weight > 1 && $tarif_add > 0){
				$tarif_add = ($weight-1) * $tarif_add;
				$the_cost = $array_tariff['tarif'] + $tarif_add;
			}
			if($weight > 1 && $tarif_add === '0') {
				$the_cost = $array_tariff['tarif'] * $weight;
			}
			if($i > 0) {
			  $json_elem_tarif = $json_elem_tarif . ",";
			}
	$json_elem_tarif = $json_elem_tarif . '{"service":"'.$array_tariff['layanan'].'", "description":"'.$array_tariff['layanan'].'","cost":[{"value":'.$the_cost.',"etd":"'.$array_tariff['etd'].'","note":""}]}';
				$i++;
			}
			$content =  '{"status":{"code":200,"description":"OK"}, "origin_details":"","destination_details":"","results":[{"code":"nss","name":"Kurir NSS","costs":['.$json_elem_tarif.']}]}';
		}
	return $content;
}
  
function epeken_get_currency_rate($currency_name) {
	$license_key = sanitize_text_field(get_option('epeken_wcjne_license_key'));
	$url = EPEKEN_API_GET_CURRENCY_RATE.$license_key."/".$currency_name;
	$response = wp_remote_get($url);
	$content = wp_remote_retrieve_body($response);
        return $content;		
}

function epeken_get_tarif_intl($negara_destination,$weight,$length,$width,$height,$price,$product_origin = false) {
        $negara_destination = sanitize_text_field(urldecode($negara_destination));
        if (empty($negara_destination)) {
               $isshippedifadr = sanitize_text_field(WC() -> session -> get('isshippedifadr')); 
               if ($isshippedifadr === '1')    {   
                  $negara_destination = sanitize_text_field($_GET['e_shipping_country']);
               }else {
                  $negara_destination = sanitize_text_field($_GET['e_billing_country']);
               }   
        }   
        $license_key = sanitize_text_field(get_option('epeken_wcjne_license_key'));
        $options = get_option('woocommerce_epeken_courier_settings');
        $origin_code = sanitize_text_field($options['data_kota_asal']);
        if ($product_origin != false)
          $origin_code = epeken_city_to_code($product_origin);

        $url = EPEKEN_API_DIR_URL_INTL.$license_key."/intl2/".$origin_code."/".$negara_destination."/".$weight;
	$response = wp_remote_get($url);
	$content = wp_remote_retrieve_body($response);
        return $content;
}
  
?>
