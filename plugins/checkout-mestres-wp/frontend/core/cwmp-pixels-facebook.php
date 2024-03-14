<?php
add_action("wp_head","cwmpAddTagManagerHead",20);
function cwmpAddTagManagerHead(){
	global $wpdb;
	global $table_prefix;
	$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}cwmp_events_pixels"));
	foreach($result as $value){
		if($value->tipo=="GTM"){
			echo "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','".$value->pixel."');</script>";
		}
	}	
}
add_action("wp_body_open","cwmpAddTagManagerBody");
function cwmpAddTagManagerBody(){
	global $wpdb;
	global $table_prefix;
	$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}cwmp_events_pixels"));
	foreach($result as $value){
		if($value->tipo=="GTM"){
			echo '<noscript><iframe src="https://www.googletagmanager.com/ns.html?id='.$value->pixel.'" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>';
		}
	}	
}
function getBrowserInfo(){
    $browserInfo = array('user_agent'=>'','browser'=>'','browser_version'=>'','os_platform'=>'','pattern'=>'', 'device'=>'');
    if(isset($_SERVER['HTTP_USER_AGENT'])){
	$u_agent = $_SERVER['HTTP_USER_AGENT']; 
    }
	$bname = 'Unknown';
    $ub = 'Unknown';
    $version = "";
    $platform = 'Unknown';
    $deviceType='Desktop';
    if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$u_agent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($u_agent,0,4))){
        $deviceType='Mobile';
    }
	if(isset($_SERVER['HTTP_USER_AGENT'])){
    if($_SERVER['HTTP_USER_AGENT'] == 'Mozilla/5.0(iPad; U; CPU iPhone OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B314 Safari/531.21.10') {
        $deviceType='Tablet';
    }
    }
	if(isset($_SERVER['HTTP_USER_AGENT'])){
    if(stristr($_SERVER['HTTP_USER_AGENT'], 'Mozilla/5.0(iPad;')) {
        $deviceType='Tablet';
    }
    }
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    } elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) { 
        $bname = 'IE'; 
        $ub = "MSIE";
    } else if(preg_match('/Firefox/i',$u_agent))    { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    } else if(preg_match('/Chrome/i',$u_agent) && (!preg_match('/Opera/i',$u_agent) && !preg_match('/OPR/i',$u_agent)))     { 
        $bname = 'Chrome'; 
        $ub = "Chrome"; 
    } else if(preg_match('/Safari/i',$u_agent) && (!preg_match('/Opera/i',$u_agent) && !preg_match('/OPR/i',$u_agent)))     { 
        $bname = 'Safari'; 
        $ub = "Safari"; 
    } else if(preg_match('/Opera/i',$u_agent) || preg_match('/OPR/i',$u_agent))     { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } else if(preg_match('/Netscape/i',$u_agent))     { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } else if((isset($u_agent) && (strpos($u_agent, 'Trident') !== false || strpos($u_agent, 'MSIE') !== false)))    {
        $bname = 'Internet Explorer'; 
        $ub = 'Internet Explorer'; 
    } 
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
    }
    $i = count($matches['browser']);
    if ($i != 1) {
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        } else {
            $version= @$matches['version'][1];
        }
    } else {
        $version= $matches['version'][0];
    }
    if ($version==null || $version=="") {$version="?";}
    return array(
        'user_agent' => $u_agent,
        'browser'      => $bname,
        'browser_version'   => $version,
        'os_platform'  => $platform,
        'pattern'   => $pattern,
        'device'    => $deviceType
    );
}
function get_user_orders_total_by_email($email) {
    $args = array(
        'billing_email' => $email,
        'limit' => -1,
    );
    $orders = wc_get_orders($args);
    if (empty($orders) || !is_array($orders)) {
        return false;
    }
    $total = array_reduce($orders, function ($carry, $order) {
        $carry += (float)$order->get_total();
        return $carry;
    }, 0.0);
    return $total;
}
function get_user_orders_count_by_email($email) {
    $args = array(
        'billing_email' => $email,
        'limit' => -1,
    );
    $orders = wc_get_orders($args);

    return count($orders);
}
function get_user_orders_total($user_id) {
    $args = array(
        'customer_id' => $user_id,
        'limit' => -1,
    );
    $orders = wc_get_orders($args);
    if (empty($orders) || !is_array($orders)) {
        return false;
    }
    $total = array_reduce($orders, function ($carry, $order) {
        $carry += (float)$order->get_total();
        return $carry;
    }, 0.0);
    return $total;
}
function cwmpGetCurrentShippingLabel($valor){
	$shipping_packages = WC()->cart->get_shipping_packages();
	foreach( array_keys( $shipping_packages ) as $key ) {
		if( $shipping_for_package = WC()->session->get('shipping_for_package_'.$key) ) {
			if( isset($shipping_for_package['rates']) ) {
				// Loop through customer available shipping methods
				foreach ( $shipping_for_package['rates'] as $rate_key => $rate ) {
					if($rate->id==$valor){
						$rate_id = $rate->id; // the shipping method rate ID (or $rate_key)
						$method_id = $rate->method_id; // the shipping method label
						$instance_id = $rate->instance_id; // The instance ID
						$cost = $rate->label; // The cost
						$label = $rate->label; // The label name
						$taxes = $rate->taxes; // The taxes (array)
						return $label;
					}
				}
			}
		}
	}
}
function cwmpGetCurrentShippingTotal($valor){
	$shipping_packages = WC()->cart->get_shipping_packages();
	foreach( array_keys( $shipping_packages ) as $key ) {
		if( $shipping_for_package = WC()->session->get('shipping_for_package_'.$key) ) {
			if( isset($shipping_for_package['rates']) ) {
				// Loop through customer available shipping methods
				foreach ( $shipping_for_package['rates'] as $rate_key => $rate ) {
					$currentShipping = WC()->session->get('chosen_shipping_methods');
					if($rate->id==$valor){
						$rate_id = $rate->id; // the shipping method rate ID (or $rate_key)
						$method_id = $rate->method_id; // the shipping method label
						$instance_id = $rate->instance_id; // The instance ID
						$cost = $rate->cost; // The cost
						$label = $rate->label; // The label name
						$taxes = $rate->taxes; // The taxes (array)
						return $cost;
					}
				}
			}
		}
	}
}
function cwmpCreateDataLayer($event,$brownser,$currentUser,$currentProduct,$currentCart,$shipping,$payment,$order,$order_id){
	global $post;
	$dataLayer = array();
	$dataLayer['event'] = $event;
	$dataLayer['ecommerce']['currencyCode'] = get_woocommerce_currency();
	if($brownser==true){
		$ua=getBrowserInfo();
		if ( is_user_logged_in() ) { $dataLayer['visitorLoginState'] = "logged-in"; }else{ $dataLayer['visitorLoginState'] = "logged-out"; }		
		$dataLayer['visitorIP'] = $_SERVER['REMOTE_ADDR'];
		$dataLayer['pageTitle'] = get_the_title();
		$dataLayer['pagePostType'] = get_post_type();
		$dataLayer['browserName'] = $ua['browser'];
		$dataLayer['browserVersion'] = $ua['browser_version'];
		$dataLayer['osName'] = $ua['os_platform'];
		$dataLayer['deviceType'] = $ua['device'];
	}
	if($currentUser==true){
		if(is_user_logged_in()){
		$current_user = wp_get_current_user();
		$dataLayer['customerTotalOrders'] = wc_get_customer_order_count($current_user->ID);
		$dataLayer['customerTotalOrderValue'] = get_user_orders_total($current_user->ID);
		$dataLayer['customerFirstName'] = $current_user->user_firstname;
		$dataLayer['customerLastName'] = $current_user->user_lastname;
		$dataLayer['customerBillingFirstName'] = $current_user->billing_first_name;
		$dataLayer['customerBillingLastName'] = $current_user->billing_last_name;
		$dataLayer['customerBillingCompany'] = $current_user->billing_company;
		$dataLayer['customerBillingAddress1'] = $current_user->billing_address_1;
		$dataLayer['customerBillingAddress2'] = $current_user->billing_address_2;
		$dataLayer['customerBillingCity'] = $current_user->billing_city;
		$dataLayer['customerBillingState'] = $current_user->billing_state;
		$dataLayer['customerBillingPostcode'] = $current_user->billing_postcode;
		$dataLayer['customerBillingCountry'] = $current_user->billing_country;
		$dataLayer['customerBillingEmail'] = $current_user->billing_email;
		$dataLayer['customerBillingEmailHash'] = hash('sha256',$current_user->billing_email);
		$dataLayer['customerBillingPhone'] = $current_user->billing_phone;
		$dataLayer['customerShippingFirstName'] = $current_user->shipping_first_name;
		$dataLayer['customerShippingLastName'] = $current_user->shipping_last_name;
		$dataLayer['customerShippingCompany'] = $current_user->shipping_company;
		$dataLayer['customerShippingAddress1'] = $current_user->shipping_address_1;
		$dataLayer['customerShippingAddress2'] = $current_user->shipping_address_2;
		$dataLayer['customerShippingCity'] = $current_user->shipping_city;
		$dataLayer['customerShippingState'] = $current_user->shipping_state;
		$dataLayer['customerShippingPostcode'] = $current_user->shipping_postcode;
		$dataLayer['customerShippingCountry'] = $current_user->shipping_country;
		}else{
		if(isset($_POST['billing_first_name'])){ $dataLayer['customerFirstName'] = $_POST['billing_first_name']; }
		if(isset($_POST['billing_last_name'])){ $dataLayer['customerLastName'] = $_POST['billing_last_name']; }
		if(isset($_POST['billing_first_name'])){ $dataLayer['customerBillingFirstName'] = $_POST['billing_first_name']; }
		if(isset($_POST['billing_last_name'])){ $dataLayer['customerBillingLastName'] = $_POST['billing_last_name']; }
		if(isset($_POST['billing_company'])){ $dataLayer['customerBillingCompany'] = $_POST['billing_company']; }
		if(isset($_POST['billing_address_1'])){ $dataLayer['customerBillingAddress1'] = $_POST['billing_address_1']; }
		if(isset($_POST['billing_address_2'])){ $dataLayer['customerBillingAddress2'] = $_POST['billing_address_2']; }
		if(isset($_POST['billing_city'])){ $dataLayer['customerBillingCity'] = $_POST['billing_city']; }
		if(isset($_POST['billing_state'])){ $dataLayer['customerBillingState'] = $_POST['billing_state']; }
		if(isset($_POST['billing_postcode'])){ $dataLayer['customerBillingPostcode'] = $_POST['billing_postcode']; }
		if(isset($_POST['billing_country'])){ $dataLayer['customerBillingCountry'] = $_POST['billing_country']; }
		if(isset($_POST['billing_email'])){ $dataLayer['customerBillingEmail'] = $_POST['billing_email']; }
		if(isset($_POST['billing_email'])){ $dataLayer['customerBillingEmailHash'] = hash('sha256',$_POST['billing_email']); }
		if(isset($_POST['billing_phone'])){ $dataLayer['customerBillingPhone'] = $_POST['billing_phone']; }

		}
	}
	if($currentProduct==true){
		$product = wc_get_product( $post->ID );
		$dataLayer['ecomm_pagetype'] = "product";
		$dataLayer['ecommerce']['product']['detail']['id'] = $post->ID;
		$dataLayer['ecommerce']['product']['detail']['name'] = $product->get_title();
		$dataLayer['ecommerce']['product']['detail']['sku'] = $product->get_sku();
		$product_cats = wp_get_post_terms( $post->ID, 'product_cat', array('fields' => 'names') );
		$u=0;
		foreach($product_cats as $category){
			if($u==0){
			$dataLayer['ecommerce']['product']['detail']['category'] = $category;
			}else{
			$dataLayer['ecommerce']['product']['detail']['category'.$u] = $category;
			}
			$u++;
		}
		$dataLayer['ecommerce']['product']['detail']['price'] = (float)$product->get_price();
		$dataLayer['ecommerce']['product']['detail']['stocklevel'] = (float)$product->get_stock_quantity();
	}
	if($currentCart==true){
		$i=0;
		foreach(WC()->cart->applied_coupons AS $coupon){
			$dataLayer['ecommerce']['cart']['totals']['applied_coupons'][$i] = $coupon; $i++;
		}
		$dataLayer['ecommerce']['cart']['totals']['discount_total'] = WC()->cart->get_cart_discount_total();
		$dataLayer['ecommerce']['cart']['totals']['subtotal'] = (float)WC()->cart->subtotal;
		$dataLayer['ecommerce']['cart']['totals']['total'] = (float)WC()->cart->cart_contents_total;
		$dataLayer['ecommerce']['cart']['totals']['count'] = (float)WC()->cart->get_cart_contents_count();
		$i=0;
		foreach ( WC()->cart->get_cart() as $cart_item ) {
			$dataLayer['ecommerce']['cart']['contentId'][$i] = $cart_item['product_id']; $i++;
		}
		$i=0;
		foreach ( WC()->cart->get_cart() as $cart_item ) {
			$product = $cart_item['data'];
			$dataLayer['ecommerce']['cart']['items'][$i]['id'] = $cart_item['product_id'];
			$dataLayer['ecommerce']['cart']['items'][$i]['internal_id'] = $cart_item['variation_id'];
			$dataLayer['ecommerce']['cart']['items'][$i]['name'] = $cart_item['data']->get_title();
			$dataLayer['ecommerce']['cart']['items'][$i]['sku'] = $product->get_sku();
			$product_cats = wp_get_post_terms( $cart_item['product_id'], 'product_cat', array('fields' => 'names') );
			$u=0;
			foreach($product_cats as $category){
				$dataLayer['ecommerce']['cart']['items'][$i]['category'][$u] = $category; $u++;
			}
			$dataLayer['ecommerce']['cart']['items'][$i]['price'] = (float)$cart_item['data']->get_price();
			$dataLayer['ecommerce']['cart']['items'][$i]['stocklevel'] = (float)$product->get_stock_quantity();
			$dataLayer['ecommerce']['cart']['items'][$i]['quantity'] = (float)$cart_item['quantity'];
			$i++;
		}
	}
	if($shipping==true){
		$dataLayer['ecommerce']['shipping']['type'] = cwmpGetCurrentShippingLabel($_POST['method_shipping']);
		$dataLayer['ecommerce']['shipping']['value'] = cwmpGetCurrentShippingTotal($_POST['method_shipping']);
	}
	if($payment==true){
		$dataLayer['ecommerce']['payment']['type'] = cwmpGetNamePayment($_POST['payment_method']);
	}
	if($order==true){
		global $product;
		$order = wc_get_order($order_id);
		$dataLayer['customerTotalOrders'] = get_user_orders_count_by_email($order->get_billing_email());
		$dataLayer['customerTotalOrderValue'] = get_user_orders_total_by_email($order->get_billing_email());
		$dataLayer['customerFirstName'] = $order->get_billing_first_name();
		$dataLayer['customerLastName'] = $order->get_billing_last_name();
		$dataLayer['customerBillingFirstName'] = $order->get_billing_first_name();
		$dataLayer['customerBillingLastName'] = $order->get_billing_last_name();
		$dataLayer['customerBillingCompany'] = $order->get_billing_company();
		$dataLayer['customerBillingAddress1'] = $order->get_billing_address_1();
		$dataLayer['customerBillingAddress2'] = $order->get_billing_address_2();
		$dataLayer['customerBillingCity'] = $order->get_billing_city();
		$dataLayer['customerBillingState'] = $order->get_billing_state();
		$dataLayer['customerBillingPostcode'] = $order->get_billing_postcode();
		$dataLayer['customerBillingCountry'] = $order->get_billing_country();
		$dataLayer['customerBillingEmail'] = $order->get_billing_email();
		$dataLayer['customerBillingEmailHash'] = hash('sha256',$order->get_billing_email());
		$dataLayer['customerBillingPhone'] = $order->get_billing_phone();
		$dataLayer['customerShippingFirstName'] = $order->get_shipping_first_name();
		$dataLayer['customerShippingLastName'] = $order->get_shipping_last_name();
		$dataLayer['customerShippingCompany'] = $order->get_shipping_company();
		$dataLayer['customerShippingAddress1'] = $order->get_shipping_address_1();
		$dataLayer['customerShippingAddress2'] = $order->get_shipping_address_2();
		$dataLayer['customerShippingCity'] = $order->get_shipping_city();
		$dataLayer['customerShippingState'] = $order->get_shipping_state();
		$dataLayer['customerShippingPostcode'] = $order->get_shipping_postcode();
		$dataLayer['customerShippingCountry'] = $order->get_shipping_country();
		$dataLayer['ecommerce']['checkout']['id']=$order->get_id();
		$coupons = $order->get_coupon_codes();
		$i=0;
		foreach($coupons AS $coupon){
			$dataLayer['ecommerce']['checkout']['totals']['applied_coupons'][$i] = $coupon; $i++;
		}
		$dataLayer['ecommerce']['checkout']['totals']['discount_total'] = $order->get_discount_total();
		$dataLayer['ecommerce']['checkout']['totals']['subtotal'] = $order->get_subtotal();
		$dataLayer['ecommerce']['checkout']['totals']['total'] = $order->get_total();
		$dataLayer['ecommerce']['checkout']['totals']['count'] = $order->get_item_count();
		$i=0;
		foreach ($order->get_items() as $item_key => $item ){
			$product = $item->get_product();
			$dataLayer['ecommerce']['checkout']['contentId'][$i] = $item->get_product_id();
		}
		$i=0;
		foreach ($order->get_items() as $item_key => $item ){
			$product = $item->get_product();
			$dataLayer['ecommerce']['checkout']['items'][$i]['id'] = $item->get_product_id();
			$dataLayer['ecommerce']['checkout']['items'][$i]['internal_id'] = $item->get_variation_id();
			$dataLayer['ecommerce']['checkout']['items'][$i]['name'] = $item->get_name();
			$dataLayer['ecommerce']['checkout']['items'][$i]['sku'] = $product->get_sku();
			$product_cats = wp_get_post_terms( $item->get_product_id(), 'product_cat', array('fields' => 'names') );
			$u=0;
			foreach($product_cats as $category){
				$dataLayer['ecommerce']['checkout']['items'][$i]['category'][$u] = $category; $u++;
			}
			$dataLayer['ecommerce']['checkout']['items'][$i]['price'] = $item->get_total();
			$dataLayer['ecommerce']['checkout']['items'][$i]['stocklevel'] = (float)$product->get_stock_quantity();
			$dataLayer['ecommerce']['checkout']['items'][$i]['quantity'] = $item->get_quantity();
			$i++;
		}
		$dataLayer['ecommerce']['checkout']['shipping']['type'] = $order->get_shipping_method();
		$dataLayer['ecommerce']['checkout']['shipping']['value'] = $order->get_shipping_total();
		$dataLayer['ecommerce']['checkout']['payment']['type'] = cwmpGetNamePayment($order->get_payment_method());
	}
	return $dataLayer;
}
add_action("wp_head","cwmpAddTagManagerDataLayer",19);
function cwmpAddTagManagerDataLayer(){
	global $wp;
	if(is_product()){
		$dataLayer = cwmpCreateDataLayer('view_item',true,true,true,true,false,false,false,'');
	}elseif(is_cart()){
		$dataLayer = cwmpCreateDataLayer('view_cart',true,true,false,true,false,false,false,'');
	}elseif(is_checkout() AND !isset($wp->query_vars['order-received'])){
		if(get_option('cwmp_activate_login')=="S"){
			if(is_user_logged_in()){
			$dataLayer = cwmpCreateDataLayer('begin_checkout',true,true,false,true,false,false,false,'');
			}else{}
		}else{
			$dataLayer = cwmpCreateDataLayer('begin_checkout',true,true,false,true,false,false,false,'');
		}
	}else{
		$dataLayer = cwmpCreateDataLayer('',true,true,false,true,false,false,false,'');
	}
	if(isset($dataLayer)){
	echo '<script type="text/javascript">
		var dataLayer = dataLayer || [];
		var dataLayerContent = '.wp_json_encode($dataLayer, true).';
		dataLayer.push( dataLayerContent );
	</script>';
	}
}
add_action( 'woocommerce_before_cart_contents', 'cwmpAddToCart');
function cwmpAddToCart(){
	global $wpdb;
	global $table_prefix;
	$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}cwmp_events_pixels"));
	$html = "";
	foreach($result as $value){
		if($value->tipo=="GTM"){
			$dataLayer = cwmpCreateDataLayer('add_to_cart',true,true,false,true,false,false,false,'');
			$html .= '<script type="text/javascript">
				var dataLayer = dataLayer || [];
				var dataLayerContent = '.wp_json_encode($dataLayer, true).';
				dataLayer.push( dataLayerContent );
			</script>';
			$html .=  "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','".$value->pixel."');</script>";
		}
	}
	echo $html;
}
add_action( 'wp_ajax_cwmpAddEventAddRate', 'cwmpAddEventAddRate' );
add_action( 'wp_ajax_nopriv_cwmpAddEventAddRate', 'cwmpAddEventAddRate' );
function cwmpAddEventAddRate(){
	global $wpdb;
	global $table_prefix;
	$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}cwmp_events_pixels"));
	foreach($result as $value){
		if($value->tipo=="GTM"){
			$dataLayer = cwmpCreateDataLayer('add_shipping_info',true,true,false,true,true,false,false,'');
			$html = "";
			$html .= '<script type="text/javascript">
				var dataLayer = dataLayer || [];
				var dataLayerContent = '.wp_json_encode($dataLayer, true).';
				dataLayer.push( dataLayerContent );
			</script>';
			$html .=  "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','".$value->pixel."');</script>";
			
		}
	}
	echo $html;

}
add_action( 'wp_ajax_cwmpAddEventPaymentInfo', 'cwmpAddEventPaymentInfo' );
add_action( 'wp_ajax_nopriv_cwmpAddEventPaymentInfo', 'cwmpAddEventPaymentInfo' );
function cwmpAddEventPaymentInfo(){
	global $wpdb;
	global $table_prefix;
	$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}cwmp_events_pixels"));
	foreach($result as $value){
		if($value->tipo=="GTM"){
			$dataLayer = cwmpCreateDataLayer('add_payment_info',true,true,false,true,false,true,false,'');
			$html = "";
			$html .= '<script type="text/javascript">
				var dataLayer = dataLayer || [];
				var dataLayerContent = '.wp_json_encode($dataLayer, true).';
				dataLayer.push( dataLayerContent );
			</script>';
			$html .=  "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','dataLayer','".$value->pixel."');</script>";
			
		}
	}
	echo $html;
}

add_action( 'wp_head', 'cwmpAddEventPurchase');
function cwmpAddEventPurchase(){
	if(get_option('cwmp_activate_thankyou_page')=="S"){
		if (isset($_GET['cwmp_order'])){
			global $wp;
			global $wpdb;
			global $table_prefix;
			$order = wc_get_order(base64_decode($_GET['cwmp_order']));
			$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}cwmp_events_pixels"));
			$get_send_order = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM {$wpdb->prefix}cwmp_pixel_thank WHERE pedido LIKE %s",
					$order->get_id()
				)
			);
			$html = "";
			if(count($get_send_order)=="0"){
				foreach($result as $value){
					if($value->tipo=="GTM"){
							
						$dataLayer = cwmpCreateDataLayer('purchase',true,false,false,false,false,false,true,$order->get_id());
						$html .= '<script type="text/javascript">
							var dataLayer = dataLayer || [];
							var dataLayerContent = '.wp_json_encode($dataLayer, true).';
							dataLayer.push( dataLayerContent );
						</script>';
						$html .=  "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
						new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
						j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
						'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
						})(window,document,'script','dataLayer','".$value->pixel."');</script>";
					}
				}
				echo $html;
				cwmp_register_purchase($order->get_id(),cwmp_get_status_order($order->get_id()));
			}
		}		
	}else{
		if (is_wc_endpoint_url('order-received')) {
			global $wp;
			$current_order_id = wc_get_order_id_by_order_key( $_GET['key'] );
			global $wpdb;
			global $table_prefix;
			$order = wc_get_order($current_order_id);
			$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}cwmp_events_pixels"));
			$html = "";
			$get_send_order = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM {$wpdb->prefix}cwmp_pixel_thank WHERE pedido LIKE %s",
					$order->get_id()
				)
			);
			if(count($get_send_order)=="0"){
				foreach($result as $value){
					if($value->tipo=="GTM"){
						
						$dataLayer = cwmpCreateDataLayer('purchase',true,false,false,false,false,false,true,$order->get_id());

						$html .= '<script type="text/javascript">
							var dataLayer = dataLayer || [];
							var dataLayerContent = '.wp_json_encode($dataLayer, true).';
							dataLayer.push( dataLayerContent );
						</script>';
						$html .=  "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
						new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
						j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
						'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
						})(window,document,'script','dataLayer','".$value->pixel."');</script>";
					}
				}
				echo $html;
				cwmp_register_purchase($order->get_id(),cwmp_get_status_order($order->get_id()));
			}
			
		}
	}
}
