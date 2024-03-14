<?php
function cwmp_trata_numero($number_wpp){
	$cwmp_brazilian = get_option('wcbcf_settings');
	$number_wpp = preg_replace('/[^0-9]/', '', $number_wpp);
	if(isset($cwmp_brazilian['maskedinput'])){
		if($cwmp_brazilian['maskedinput']=="1"){
			if (get_option('cwmp_whatsapp_ddi') == "BR" || get_option('cwmp_international_phone') == "1"){
				if (substr($number_wpp, 2, 2) >= 30){
					if (strlen(substr($number_wpp, 4, 9)) == "8"){
						$number_wpp = substr($number_wpp, 0, 4) . "" . substr($number_wpp, 4, 9);
					}else{
						$number_wpp = substr($number_wpp, 0, 4) . "" . substr($number_wpp, 5, 9);
					}
				}else{
					$number_wpp = substr($number_wpp, 0, 4) . "" . substr($number_wpp, 4, 9);
				}
			}else{
				if (substr($number_wpp, 0, 2) >= 30){
					if (strlen(substr($number_wpp, 2, 9)) == "8"){
						$number_wpp = "55".substr($number_wpp, 0, 2) . "" . substr($number_wpp, 2, 9);
					}else{
						$number_wpp = "55".substr($number_wpp, 0, 2) . "" . substr($number_wpp, 3, 8);
					}
				}else{
					$number_wpp = "55".substr($number_wpp, 0, 2) . "" . substr($number_wpp, 2, 9);
				}
			}
		}
	}else{
		$number_wpp = str_replace("+","",$number_wpp);
	}
	return $number_wpp;
}
function cwmp_generate_coupon_code($length = 6) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $codigo = '';
    for ($i = 0; $i < $length; $i++) {
        $codigo .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $codigo;
}
function cwmp_create_coupon($valor_desconto, $tipo_desconto = 'percent', $limite_uso = 1, $prazo_validade = '') {
    if (!function_exists('WC')) {
        exit('O WooCommerce não está disponível. Certifique-se de que o WooCommerce está instalado e ativado.');
    }
    WC()->session = new WC_Session_Handler();
    WC()->session->init();
    $cupom = new WC_Coupon();
    $codigo_cupom = cwmp_generate_coupon_code();
    $cupom->set_code($codigo_cupom);
    $cupom->set_discount_type($tipo_desconto);
    $cupom->set_amount($valor_desconto);
    $cupom->set_individual_use(true);
    $cupom->set_usage_limit($limite_uso);
    if (!empty($prazo_validade)) {
        $cupom->set_date_expires(date('Y-m-d', strtotime($prazo_validade)));
    }
    $cupom->save();
    return $codigo_cupom;
}
add_action( 'wp_ajax_cwmp_address_ajax', 'cwmp_address_ajax' );
add_action( 'wp_ajax_nopriv_cwmp_address_ajax', 'cwmp_address_ajax' );
function cwmp_address_ajax(){
	$cep = str_replace("-","",$_POST['cep']);
	$endereco = wp_remote_get("https://viacep.com.br/ws/".$cep."/json/", array('headers' => array('Content-Type' => 'application/json')));
	$endereco = wp_kses_post(wp_remote_retrieve_body($endereco));
	$address = json_decode($endereco);
	if(isset($address->erro)){
		$endereco = wp_remote_get("https://mestrecom.com.br/cep/?cep=".$cep."", array('headers' => array('Content-Type' => 'application/json')));
		echo wp_kses_post(wp_remote_retrieve_body($endereco));
	}else{
		echo $endereco;
	}
	die();
}
function cwmp_get_status_order($order_id){
	$order = wc_get_order($order_id);
	switch ($order->get_status()) {
		case "on-hold":
		$get_status = "1";
		break;
		case "pending":
		$get_status = "2";
		break;
		case "processing":
		$get_status = "3";
		break;
		case "completed":
		$get_status = "4";
		break;
		case "cancelled":
		$get_status = "5";
		break;
		case "failed":
		$get_status = "6";
		break;
		case "refunded":
		$get_status = "7";
		break;
	}
	return $get_status;
	
}
function cwmp_register_purchase($id,$categoria){
	global $wpdb;
	$order = wc_get_order($id);
	$table_name2 = $wpdb->prefix . 'cwmp_pixel_thank';
	$wpdb->insert($table_name2, array(
		'pedido' => $order->get_id(),
		'status' => $categoria
	));
}
function cwmp_display_payment_methods($method){
	global $woocommerce;
	$wc_gateways = new WC_Payment_Gateways();
	$payment_gateways = $wc_gateways->payment_gateways();
	foreach( $payment_gateways as $gateway_id => $gateway ){
		if(str_replace('-', '_', $gateway->id)==str_replace('-', '_', $method)){
			echo str_replace("Mestres do WP | ","",$gateway->method_title);
		}
	}
}
function cwmp_ajax_cart() {
	$cart_item_key = sanitize_key($_POST['hash']);
	$threeball_product_values = WC()->cart->get_cart_item( $cart_item_key );
	$threeball_product_quantity = apply_filters( 'woocommerce_stock_amount_cart_item', apply_filters( 'woocommerce_stock_amount', preg_replace( "/[^0-9\.]/", '', filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT)) ), $cart_item_key );
	$passed_validation  = apply_filters( 'woocommerce_update_cart_validation', true, $cart_item_key, $threeball_product_values, $threeball_product_quantity );
	if($passed_validation ){
		WC()->cart->set_quantity( $cart_item_key, $threeball_product_quantity, true );
	}
	die();
}
add_action('wp_ajax_cwmp_ajax_cart', 'cwmp_ajax_cart');
add_action('wp_ajax_nopriv_cwmp_ajax_cart', 'cwmp_ajax_cart');
if(esc_html(get_option('cwmp_activate_checkout'))=="S"){
	function cwmp_filter_woocommerce_cart_totals_coupon_html( $coupon_html, $coupon, $discount_amount_html ) {
		$coupon_html = $discount_amount_html . ' <a href="' . esc_url( add_query_arg( 'remove_coupon', rawurlencode( $coupon->get_code() ), defined( 'WOOCOMMERCE_CHECKOUT' ) ? wc_get_checkout_url() : wc_get_cart_url() ) ) . '" class="woocommerce-remove-coupon" data-coupon="' . esc_attr( $coupon->get_code() ) . '">' . '(Remover)' . '</a>';
		return $coupon_html;
	}
	add_filter( 'woocommerce_cart_totals_coupon_html', 'cwmp_filter_woocommerce_cart_totals_coupon_html', 10, 3 );
	function coupon_check_via_ajax(){
		$code = strtolower(trim($_POST['code']));
		$coupon = new WC_Coupon($code);
		$coupon_post = get_post($coupon->id);
		if(!empty($coupon_post) && $coupon_post != null){
			$message = 'Coupon not valid';
			$status = 0;
			if($coupon_post->post_status == 'publish'){
				$message = 'Coupon validated';
				$status = 1;
			}
		} else {
			$status = 0;
			$message = 'Coupon not found!';
		}
		print wp_json_encode( [ 'status' => $status, 'message' => $message, 'poststatus' => $coupon_post->post_status, 'coupon_post' => $coupon_post ] ); 
		exit(); 
	}
	add_action( 'wp_ajax_check_coupon_via_ajax', 'coupon_check_via_ajax' );
	add_action( 'wp_ajax_nopriv_check_coupon_via_ajax', 'coupon_check_via_ajax' );
}
function cwmp_send_mail($email,$assunto,$conteudo){
    add_filter('wp_mail_content_type', 'cwmp_email_set_html_mail_content_type', 10, 1);
    $to = $email;
    $subject = $assunto;
    $body = $conteudo;
	$headers = array('Content-Type: text/html; charset=UTF-8','From: '.get_option('woocommerce_email_from_name').' <'.get_option('woocommerce_email_from_address').'>');
    $sendmail = wp_mail($to, $subject, $body, $headers);
	remove_filter('wp_mail_content_type', 'cwmp_email_set_html_mail_content_type', 10, 1);
}
function cwmp_email_set_html_mail_content_type($content_type){
    return 'text/html';
}

function cwmp_send_whatsapp($numero,$mensagem){
	if (get_option('cwmp_template_whatsapp_type') == "1"){
        $data = array(
            'session' => get_option('cwmp_key_endpoint_wpp'),
            'token' => get_option('cwmp_key_api_wpp'),
            'url' => get_bloginfo('url'),
            'phone' => $numero,
            'message' => $mensagem
        );
        $send = wp_remote_post(CWMP_URL_API, array(
            'method' => 'POST',
            'body' => $data
        ));
	}elseif(get_option('cwmp_template_whatsapp_type') == "2"){
		$data = array(
			'number' => $numero,
			'body' => $mensagem
		);
		$header = array(
			'Authorization' => 'Bearer '.get_option('cwmp_key_endpoint_wpp').'',
			'Content-Type' => 'application/json'
		);
		$send = wp_remote_post(CWMP_URL_API_MULTI, array(
			'method' => 'POST',
			'headers' => $header,
			'body' => wp_json_encode($data)
		));
	}else{
    }
}


function cwmp_send_lojista($id){
	global $wpdb, $table_prefix;
	$order = new WC_Order($id);
	$order_id = $order->get_id();
	$send_numbers = explode(',', get_option('cwmp_whatsapp_number_lojista'));
	foreach ($send_numbers as & $valor){
		$string_wpp_content = str_replace("]", " val='" . $order_id . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', get_option('cwmp_whatsapp_template_lojista')));
		$string_wpp_content_renovada = do_shortcode($string_wpp_content);
		cwmp_send_whatsapp($valor,$string_wpp_content_renovada);
	} unset($send_numbers);
}
add_action("init","cwmp_newsletter_click");
function cwmp_newsletter_click(){
	if(isset($_GET['cwmp_emarketing']) AND isset($_GET['cwmp_emarketing_mail'])){
		global $wpdb;
		$table_name = $wpdb->prefix . 'cwmp_newsletter_send';
		$update_send_newsletter = $wpdb->update($table_name, array(
			'clique' => '1'
		),array('email'=>$_GET['cwmp_emarketing_mail'],'campanha'=>$_GET['cwmp_emarketing']));
	}
}

function cwmp_frontend_styles(){
	if(is_checkout() && !is_wc_endpoint_url('order-received')){ if(get_option('cwmp_activate_checkout')=="S"){  wp_enqueue_style( 'cwmp_frontend_styles', CWMP_PLUGIN_URL.'template/hotcart/assets/css/style.css', array(), wp_rand(111,9999), 'all' ); }}
	if(is_checkout() && !is_wc_endpoint_url('order-received')){ if(get_option('cwmp_activate_order_bump')=="S"){ wp_enqueue_style( 'cwmp_frontend_bump_styles', CWMP_PLUGIN_URL.'template/hotcart/assets/css/style-bump.css', array(), wp_rand(111,9999), 'all' ); }}
	if(get_option('cwmp_pmwp_active')=="S"){ wp_enqueue_style( 'cwmp_frontend_parcelas_styles', CWMP_PLUGIN_URL.'template/hotcart/assets/css/style-parcelas.css', array(), wp_rand(111,9999), 'all' ); }
	if(is_checkout() && !is_wc_endpoint_url('order-received')){
	wp_enqueue_style( 'cwmp-style-awesome', 'https://site-assets.fontawesome.com/releases/v6.3.0/css/all.css', array(), wp_rand(111,9999), 'all' );
	}
}
add_action('wp_enqueue_scripts','cwmp_frontend_styles',9999);
function cwmp_load_plugin_css() {
	include(CWMP_PLUGIN_PATH."template/hotcart/css.php");
}
add_action( 'wp_head', 'cwmp_load_plugin_css',1 );
function cwmp_frontend_scripts(){
	global $woocommerce;
	$data = array(
		'ajaxUrl' => admin_url('admin-ajax.php'),
		'applyCoupon' => wp_create_nonce("apply-coupon"),
		'cartSession' => wp_json_encode($woocommerce->cart->get_cart()),
		'cartSessionCookie' => WC()->session->get_session_cookie(),
		'viewActiveAddress' => get_option('cwmp_view_active_address'),
		'fieldCountry' => get_option('cwmp_field_country'),
		'needsShipping' => WC()->cart->needs_shipping(),
		'showShipping' => WC()->cart->show_shipping(),
		'AddressAutoBR' => get_option('cwmp_view_active_address_auto'),
	);
	$cwmp_brazilian = get_option('wcbcf_settings');
	if(!empty($cwmp_brazilian['maskedinput'])){
		$data['maskedinput'] = $cwmp_brazilian['maskedinput'];
	}
	if(!empty($cwmp_brazilian['cell_phone'])){
		if($cwmp_brazilian['cell_phone']=="2"){
			$data['cellPhone'] = $cwmp_brazilian['cell_phone'];
		}
	}
	if(!empty($cwmp_brazilian['gender'])){
		$data['gender'] = $cwmp_brazilian['gender'];
	}
	
	if(!empty($cwmp_brazilian['rg'])){
		$data['rg'] = $cwmp_brazilian['rg'];
	}

	if(!empty($cwmp_brazilian['person_type'])){
		$data['personType'] = $cwmp_brazilian['person_type'];
	}
	
	if(!empty($cwmp_brazilian['maskedinput'])){
		$data['maskedinput'] = $cwmp_brazilian['maskedinput'];
	}
	if(!empty($cwmp_brazilian['birthdate'])){
		$data['birthdate'] = $cwmp_brazilian['birthdate'];
	}
	if(get_option('cwmp_activate_login')=="S"){
		$data['returnRembemberPassword'] = __("You will receive an email with your new password.","checkout-mestres-wp");
	}
	$fields = WC()->checkout()->checkout_fields;
	$billingFields = array();
	$shippingFields = array();
	foreach ( $fields['billing'] as $key => $field ) {
		if($field['required']==1){
			$billingFields[] = $key;
		}
	}
	$removeShipping = array("billing_country","billing_postcode","billing_address_1","billing_number","billing_city","billing_state","billing_neighborhood");
	$billingFields = array_diff($billingFields, $removeShipping);
	foreach ( $fields['shipping'] as $key => $field ) {
		if($field['required']==1){
			$shippingFields[] = $key;
		}
	}
	$data['billingFields'] = json_encode($billingFields);
	$data['shippingFields'] = json_encode($shippingFields);
     if(is_checkout()){
	wp_enqueue_script( 'cwmp_frontend_js', CWMP_PLUGIN_URL.'template/hotcart/assets/js/functions.js', array('jquery'), wp_rand(111,9999),array('strategy'=>'defer','in_footer'=>true));
	if(!empty($data)){ wp_localize_script( 'cwmp_frontend_js', 'cwmp', $data); }
	}
	if(is_product()){
		wp_enqueue_script( 'cwmp_frontend_simulador_js', CWMP_PLUGIN_URL.'assets/js/simulador-frete.js', array('jquery'), wp_rand(111,9999),array('strategy'=>'defer','in_footer'=>true));
		if(!empty($data)){ wp_localize_script( 'cwmp_frontend_simulador_js', 'cwmp', $data); }
		if(get_option('cwmp_pmwp_active')=="S"){
			if ( is_plugin_active( 'elementor-pro/elementor-pro.php' ) ) {
				wp_enqueue_script('pmwp_scripts', CWMP_PLUGIN_URL . 'assets/js/parcelasE.js', array('jquery'),wp_rand(111,9999), true);
			}else{
				wp_enqueue_script('pmwp_scripts', CWMP_PLUGIN_URL . 'assets/js/parcelas.js', array('jquery'),wp_rand(111,9999), true);
			}
			wp_localize_script( 'pmwp_scripts', 'pmwp_ajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
			wp_enqueue_script('pmwp_add_to_cart', CWMP_PLUGIN_URL . 'assets/js/add-to-cart.js', array('jquery'),wp_rand(111,9999), true);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'cwmp_frontend_scripts', 99999999);
if(get_option('cwmp_activate_cart')=="S"){
	function cwmp_ajax_register_cart() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'cwmp_cart_abandoned';
		$table_name1 = $wpdb->prefix . 'cwmp_cart_abandoned_msg';
		$table_name2 = $wpdb->prefix . 'cwmp_cart_abandoned_relation';
		$carts_abandoneds = $wpdb->get_results("SELECT * FROM ".$table_name." WHERE email='".sanitize_email($_POST['cwmp_cart_email'])."' OR phone='".esc_html($_POST['cwmp_cart_phone'])."'");
		if($wpdb->num_rows==0){
			$wpdb->insert($table_name, array('nome' => esc_html($_POST['cwmp_user_name']), 'email' => sanitize_email($_POST['cwmp_cart_email']), 'phone' => esc_html($_POST['cwmp_cart_phone']), 'cart' => $_POST['cwmp_cart_session'], 'status' => '0', 'time' => date("Y-m-d H:i:s"))); 	
		}else{
			$wpdb->update($table_name, array('nome' => esc_html($_POST['cwmp_user_name']), 'email' => sanitize_email($_POST['cwmp_cart_email']), 'phone' => esc_html($_POST['cwmp_cart_phone']), 'cart' => $_POST['cwmp_cart_session'], 'time' => date("Y-m-d H:i:s")), array('id'=>$carts_abandoneds['0']->id)); 	
			$wpdb->delete($table_name2, array('cart'=>$carts_abandoneds['0']->id)); 
		}

		die();
	}
	add_action('wp_ajax_cwmp_ajax_register_cart', 'cwmp_ajax_register_cart');
	add_action('wp_ajax_nopriv_cwmp_ajax_register_cart', 'cwmp_ajax_register_cart');
}

function cwmp_form_login(){
	if(get_option('cwmp_activate_login')=="S"){
		if(is_checkout()){
			if ( is_user_logged_in() ) {}else{
			$html = "";
			$html .= "<div class='cwmp_form_login'>";
			$html .= "<i class='fa ".get_option('cwmp_checkout_box_icon_dados_pessoais')."'></i>";
			$html .= "<h2>";
			$html .= __("Enter your email","checkout-mestres-wp")."</h2>";
			$html .= "<p>".__("Fill in your email to get started. We will use this address to access your account or create a new one.","checkout-mestres-wp")."</p>";
			$html .= "<p class='cwmp-form-row'><input type='text' id='cwmp_form_input_email' placeholder='".__("Enter your email","checkout-mestres-wp")."' /></p>";
			$html .= "<p class='cwmp-form-row'><input type='password' id='cwmp_form_input_password' class='hide' placeholder='".__("Enter your password","checkout-mestres-wp")."' /></p>";
			$html .= "<button class='cwmp_button' id='cwmp_login_button'>".__("Next","checkout-mestres-wp")."</button>";
			$html .= "<button class='cwmp_login_link hide' id='cwmp_login_link'>".__("Receive access link","checkout-mestres-wp")."</button>";
			$html .= "<p class='return_login hide'>".__("Your access link has been sent successfully","checkout-mestres-wp")."</p>";
			$html .= "</div>";
			echo $html;
			}
		}
	}
}
add_action("woocommerce_checkout_before_form_checkout","cwmp_form_login");
function cwmp_auto_login() {
    if (isset($_GET['token'])) {
        $token = sanitize_text_field(wp_unslash($_GET['token'])); // Trata o token de maneira segura
        remove_action('init', 'wc_maybe_store_user_agent', 2);
        if (function_exists('wc_set_new_customer_cookie')) {
            remove_action('wp_login', 'wc_set_new_customer_cookie');
        }
        $user_id = wp_validate_auth_cookie($token, 'logged_in');
        add_action('init', 'wc_maybe_store_user_agent', 2);
        if (function_exists('wc_set_new_customer_cookie')) {
            add_action('wp_login', 'wc_set_new_customer_cookie');
        }
        if ($user_id && !is_wp_error($user_id)) {
            wp_clear_auth_cookie();
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id);
            $user = get_userdata($user_id);
            $username = $user ? $user->user_login : '';
            do_action('wp_login', $username, $user);
            $redirect_url = isset($_GET['redirect_to']) ? esc_url($_GET['redirect_to']) : home_url('/');
            wp_safe_redirect($redirect_url);
            exit;
        } else {
            echo 'Token inválido. O login automático falhou.';
        }
    }
}
add_action('wp', 'cwmp_auto_login', 1); // Use 'wp' em vez de 'wp_head' para garantir execução no momento correto
add_action('wp_ajax_cwmp_form_access_link', 'cwmp_form_access_link');
add_action('wp_ajax_nopriv_cwmp_form_access_link', 'cwmp_form_access_link');
function cwmp_form_access_link(){
	$email = $_POST['email'];
    $user_id = email_exists($email);
    $token = wp_generate_auth_cookie($user_id, time() + 3600, 'logged_in');
    $redirect_url = wc_get_checkout_url();
    $login_url = add_query_arg(array(
        'token' => $token,
        'redirect_to' => urlencode($redirect_url),
    ), home_url());
	$body = str_replace('{{login_automatico}}',$login_url,get_option('cwmp_remember_password_body'));
	cwmp_send_mail($email,get_option('cwmp_remember_password_subject'),$body);
	die();
}




add_action('wp_ajax_cwmp_form_submit_login', 'cwmp_form_submit_login');
add_action('wp_ajax_nopriv_cwmp_form_submit_login', 'cwmp_form_submit_login');
function cwmp_form_submit_login(){
	$email = $_POST['email'];
	$password = $_POST['senha'];
	if($password){
		$creds = array(
			'user_login'    => $email,
			'user_password' => $password,
			'remember'      => false
		);
		$user = wp_signon( $creds, false );
		if (is_wp_error($user)){
			echo __("You do not have a registration on our website.","checkout-mestres-wp");
			die();
		} else {
			wp_set_current_user($user->ID); 
			wp_set_auth_cookie( $user->ID, 0, 0);
			do_action( 'wp_login', false, $email );
			echo "true";
		}
	}else{
		$user_id = email_exists($email);
		if ($user_id) {
			echo $user_id;
		} else {
			echo false;
		}
	}
	die();
}
if(get_option('cwmp_activate_thankyou_page')=="S"){
	add_action('template_redirect', 'cwmp_redirect_thankyou_page');
	function cwmp_redirect_thankyou_page(){
		global $wp;
		if( !is_wc_endpoint_url( 'order-received' ) && !is_wc_endpoint_url( 'order-pay' ) || empty( $_GET['key'] ) ) {
			return;
		}
		$order_id = wc_get_order_id_by_order_key( $_GET['key'] );
		$order_received_url = wc_get_checkout_url();
		$order = wc_get_order($order_id);
		if($order->get_status()=='failed'){
			$url = get_permalink(get_option('cwmp_thankyou_page_selected_failed'));
			if($url!="" AND $url!=$order_received_url){
				wp_redirect($url."?cwmp_order=".base64_encode($order->get_id())."");
				exit();
			}
		}else{
			if($order->get_status()=='pending' || $order->get_status()=='on-hold'){
				$getPage = get_option('cwmp_thankyou_page_pending_'.$order->get_payment_method().'');
				if($getPage==""){
					$getPage = get_option('cwmp_thankyou_page_pending_'.str_replace("-","_",$order->get_payment_method().''));
				}
				$url = get_permalink($getPage);
				if($url!="" AND $url!=$order_received_url){
					wp_redirect($url."?cwmp_order=".base64_encode($order->get_id())."");
					exit();
				}
			}else{
				$url = get_permalink(get_option('cwmp_thankyou_page_aproved_'.$order->get_payment_method().''));
				if($url!="" AND $url!=$order_received_url){
					wp_redirect($url."?cwmp_order=".base64_encode($order->get_id())."");
					exit();
			}
			}
		}
	}
	function cwmp_redirect_aproved_order(){
		$order_received_url = wc_get_checkout_url();
		if(isset($_GET['cwmp_order'])){
			$order = wc_get_order(base64_decode($_GET['cwmp_order']));
			if(!is_page(get_option('cwmp_thankyou_page_aproved_'.$order->get_payment_method().'')) AND !is_page(get_option('cwmp_thankyou_page_selected_failed'))){
				$url = get_permalink(get_option('cwmp_thankyou_page_pending_'.$order->get_payment_method().''));
				$urlTkyou = get_permalink(get_option('cwmp_thankyou_page_aproved_'.$order->get_payment_method().''));
				if($urlTkyou!="" AND $urlTkyou!=$order_received_url){

					$html = "";
					$html .= "<script type='text/javascript'>";
					$html .= '
							function atualizar_ordem(){
							jQuery(document).ready(function($) {
							$.ajax({
									type: "POST",
									url: "'.admin_url('admin-ajax.php').'",
									data: {
										action: "cwmp_get_aproved_order",
										order: '.$order->get_id().'
									},
									success: function(data) {
										if(data=="processing" || data=="completed"){
											window.location.href = "'.$urlTkyou.'?cwmp_order='.base64_encode($order->get_id()).'";
										}
										if(data=="failed"){
											console.log(data);
											window.location.href = "'.$urlTkyou.'?cwmp_order='.base64_encode($order->get_id()).'";
										}
									}
								});
							}); 
						}
						setInterval("atualizar_ordem()", 5000);
						jQuery(document).ready(function($) {
							atualizar_ordem();
						});
					';
					$html .= "</script>";
					echo $html;

				}
			}
		}
	}
	add_action("wp_footer","cwmp_redirect_aproved_order", 99999);
	function cwmp_get_aproved_order(){
		$order = wc_get_order($_POST['order']);
		echo $order->get_status();
		die();
	}
	add_action('wp_ajax_cwmp_get_aproved_order', 'cwmp_get_aproved_order');
	add_action('wp_ajax_nopriv_cwmp_get_aproved_order', 'cwmp_get_aproved_order');
}
if(esc_html(get_option('cwmp_activate_cart'))=="S"){
	add_action('template_redirect', 'cwmp_recovery_cart',1);
	function cwmp_recovery_cart(){
		if(isset($_GET['cwmp_recovery_cart'])){
			global $woocommerce;
			global $wpdb;
			$woocommerce->cart->empty_cart();
			$cwmp_hash = base64_decode($_GET['cwmp_recovery_cart']);

			$carts_abandoneds = $wpdb->get_results($wpdb->prepare(
				"SELECT * FROM " . $wpdb->prefix . "cwmp_cart_abandoned WHERE `id` = %s",
				$cwmp_hash
			)
			);
			if(isset($carts_abandoneds[0])){
				$cwmp_cart_recovery =  str_replace('\"','"',$carts_abandoneds[0]->cart);
				$cwmp_cart_recovery = json_decode($cwmp_cart_recovery);
				foreach($cwmp_cart_recovery as $key => $value){
					$woocommerce->cart->add_to_cart( $value->product_id, $value->quantity );
				}
				wp_redirect(wc_get_checkout_url());
				exit;
			}
		}
	}
	add_action( 'woocommerce_new_order', 'cwmp_remove_cart_abandoned',  1, 1  );
	function cwmp_remove_cart_abandoned($order_id) {
		cwmp_register_buy($order_id);
	} 
	function cwmp_register_buy($id){
		global $wpdb;
		$order = wc_get_order($id);
		$table_name = $wpdb->prefix . 'cwmp_cart_abandoned';
		$wpdb->delete($table_name, array(
			'email' => $order->get_billing_email()
		));
		$wpdb->delete($table_name, array(
			'phone' => $order->get_billing_phone()
		));
	}
}
	function cwmp_register_send_msgMail($id){
		
		global $wpdb;
		$order = wc_get_order($id);
		$table_name2 = $wpdb->prefix . 'cwmp_send_thank';
		$wpdb->insert($table_name2, array(
			'pedido' => $order->get_id(),
			'status' => cwmp_get_status_order($order->get_id())
		));
		
	}
if(esc_html(get_option('cwmp_ignore_cart'))=="S"){
	add_filter('template_redirect', 'cwmp_add_to_cart_redirect');
	function cwmp_add_to_cart_redirect() {
		global $woocommerce;
		if(is_cart()){
			if ( WC()->cart->get_cart_contents_count() == 0 ) {
				wp_redirect(home_url());
				exit;
			}else{
				if(get_option('cwmp_ignore_cart')=="S"){
						wp_redirect(wc_get_checkout_url());
						exit;
				}
			}
		}
	}
}


function cwmp_step_cart(){
	global $wpdb;
	$hash = WC()->session->get_session_cookie();
	if(isset($hash[0])){	
		$carts_abandoneds = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}cwmp_session_cart WHERE cart = %s",
				$hash[0]
			)
		);
		$table_name2 = $wpdb->prefix . 'cwmp_session_cart';
		if($wpdb->num_rows==0){
			$wpdb->insert($table_name2, array(
				'cart' => $hash[0],
				'step' => '0'
			));
		}else{
			$table_name2 = $wpdb->prefix . 'cwmp_session_cart';
			$wpdb->update($table_name2, array('step' => '0'),array('cart' => $hash[0]));
		}
	}
}
add_action ('wp_head','cwmp_step_cart');


function cwmp_step_cart_ajax(){
	global $wpdb;
	$hash = WC()->session->get_session_cookie();
	if(isset($hash[0])){
		$table_name2 = $wpdb->prefix . 'cwmp_session_cart';
		$wpdb->update($table_name2, array('step' => $_POST['step']),array('cart' => $hash[0]));
	}
}
add_action( 'wp_ajax_cwmp_step_cart_ajax', 'cwmp_step_cart_ajax' );
add_action( 'wp_ajax_nopriv_cwmp_step_cart_ajax', 'cwmp_step_cart_ajax' );
