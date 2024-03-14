<?php
/*
* Plugin Name: Notificações para WooCommerce
* Plugin URI: https://dropestore.com/plugin-notificacoes-woocommerce
* Description: Notificações em tempo real do WooCommerce diretamente no WhatsApp
* Author: Drope
* Author URI: https://dropestore.com
* Version: 1.1.3
* Text Domain: notifications-woocommerce
*/

define( 'PLUGIN_NOTIFICACOES_WOO_VERSION', '1.1.3' );

define( 'PLUGIN_NOTIFICACOES_WOO_FILE__', __FILE__ );
define( 'PLUGIN_NOTIFICACOES_WOO_PLUGIN_BASE', plugin_basename( PLUGIN_NOTIFICACOES_WOO_FILE__ ) );
define( 'PLUGIN_NOTIFICACOES_WOO_PATH', plugin_dir_path( PLUGIN_NOTIFICACOES_WOO_FILE__ ) );

require_once PLUGIN_NOTIFICACOES_WOO_PATH . '/includes/acf/acf.php';
require_once PLUGIN_NOTIFICACOES_WOO_PATH . '/includes/acf-fields.php';
require_once PLUGIN_NOTIFICACOES_WOO_PATH . '/includes/woocommerce.php';

add_filter('acf/settings/show_admin', '__return_true');

/**
*  ------------------------------------------------------------------------------------------------
*   CREATE TABLES
*  ------------------------------------------------------------------------------------------------
*/

add_action('admin_init', 'notificacoesWoo_load');

function notificacoesWoo_load(){
    if (is_admin() && get_option( 'notificacoesWoo_activate_plugin' ) == 'notifications-woocommerce' ) {
        delete_option( 'notificacoesWoo_activate_plugin' );
    }
}

function notificacoesWoo_desactivate(){
	wp_clear_scheduled_hook('notificacoesWoo_cron_events');
}

function notificacoesWoo_activate(){

	if (!wp_next_scheduled ('notificacoesWoo_cron_events')) {
		wp_schedule_event(time(), 'hourly', 'notificacoesWoo_cron_events');
    }

	add_option('notificacoesWoo_activate_plugin', 'notifications-woocommerce');
	
}

register_activation_hook(__FILE__, 'notificacoesWoo_activate');
register_deactivation_hook(__FILE__, 'notificacoesWoo_desactivate');

/**
*  ------------------------------------------------------------------------------------------------
*   STYLE LOAD
*  ------------------------------------------------------------------------------------------------
*/

add_action( 'wp_enqueue_scripts', 'notificacoesWoo_register' );
 
function notificacoesWoo_register() {

	$args = array(
        'homeurl' => get_option('home')
    );
   
	wp_enqueue_style( 'woo-wpp-style', get_site_url()."/wp-content/plugins/notifications-woocommerce/assets/css/style.css" );
	
}

/**
*  ------------------------------------------------------------------------------------------------
*   PLUGIN DEPENDENCIE
*  ------------------------------------------------------------------------------------------------
*/

add_action( 'admin_notices', 'notificacoesWoo_dependencies' );

function notificacoesWoo_dependencies() {
	if (!is_plugin_active('woocommerce/woocommerce.php'))
    	echo '<div class="error"><p>' . __( 'O plugin <b>WooCommerce WhatsApp Notificações</b> precisa do plugin <b>WooCommerce</b> instalado e ativado para funcionar', 'notifications-woocommerce' ) . '</p></div>';

	if ((get_field("api_server",9991274) == null) && (get_field("drope_api",9991274) == null)){
		echo '<br><div class="error"><p>' . __( 'Você ainda não configurou seu plugin!<br>Experimente <b>3 dias grátis da DW-API</b>, clique <a href="https://dw-api.com/" target="_blank">aqui</a>.', 'notifications-woocommerce' ) . '</p></div>';
	}
}

/**
*  ------------------------------------------------------------------------------------------------
*   CUSTOM LINKS
*  ------------------------------------------------------------------------------------------------
*/

if (!function_exists('custom_links_wc_notificacoes')) {
	function custom_links_wc_notificacoes($links_array, $plugin_file_name){

		if (strpos($plugin_file_name, basename(__FILE__))) {
			$links_array[10] = '<a href="https://documentacao.dw-api.com/" target="_blank">Documentação</a>';
			$links_array[11] = '<a href="https://dropestore.com/suporte" target="_blank">Suporte</a>';
		}
	
		return $links_array;
	}
}

if (!function_exists('custom_link_actions_wc_notificacoes')) {
	function custom_link_actions_wc_notificacoes($links){

		$links[0] = '<a href="https://bit.ly/3tRyXfj" target="_blank" style="color:#022d94;font-weight:700;">' . esc_html('Versão Pro', 'plugin-loterias-drope-drope') . '</a>';
	
		return $links;
	}
}

add_filter('plugin_row_meta', 'custom_links_wc_notificacoes', 10, 2);

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'custom_link_actions_wc_notificacoes');

/**
*  ------------------------------------------------------------------------------------------------
*   ACTIONS WOOCOMMERCE FOR SEND MESSAGE
*  ------------------------------------------------------------------------------------------------
*/

add_action('woocommerce_checkout_order_processed', 'notificacoesWoo_pendente_mp');
add_action('woocommerce_order_status_pending', 'notificacoesWoo_pendente');
add_action('woocommerce_order_status_on-hold', 'notificacoesWoo_pendente');
add_action('woocommerce_order_status_failed', 'notificacoesWoo_falhou');
add_action('woocommerce_order_status_processing', 'notificacoesWoo_processando');
add_action('woocommerce_order_status_completed', 'notificacoesWoo_completo');
add_action('woocommerce_order_status_refunded', 'notificacoesWoo_estornado');
add_action('woocommerce_order_status_cancelled', 'notificacoesWoo_cancelado');

function notificacoesWoo_send_message($order_id, $name, $product_list, $address, $order_total, $shipping_total, $payment_method, $cotas, $phone, $notify_client, $wpp_admin, $notify_admin, $rastreio){

	$notify_wpp    		= get_field("wpp_message",9991274);
	$notify_wpp_admin 	= get_field("wpp_message_admin",9991274);
	$store_name			= get_bloginfo('name');
	$order				= wc_get_order($order_id);
	$debug				= get_field("debug",9991274);
	$product_list 		= rtrim($product_list, ", ") . "";
	$cotas				= rtrim($cotas, ", ") . "";

	$drope_api	= get_field("drope_api",9991274);
	$sender		= get_field("wpp_drope_api_number",9991274);
	$appUrl		= "https://painel.dw-api.com";
	$endpoint 	= 'https://api.dw-api.com/send';

	if ($debug == "sim"){
		if (!notificacoesWoo_verify($order)){
			return;
		}
	}
	
	if($notify_wpp != ""):

		$notify_wpp = str_replace("[CLIENTE]", $name, $notify_wpp);
		$notify_wpp = str_replace("[PEDIDO]", "#" . $order_id, $notify_wpp);
		$notify_wpp = str_replace("[PRODUTOS]", $product_list, $notify_wpp);
		$notify_wpp = str_replace("[ENDERECO]", $address, $notify_wpp);
		$notify_wpp = str_replace("[TOTAL_PEDIDO]", $order_total, $notify_wpp);
		$notify_wpp = str_replace("[TOTAL_FRETE]", $shipping_total, $notify_wpp);
		$notify_wpp = str_replace("[METODO_PAGAMENTO]", $payment_method, $notify_wpp);
		$notify_wpp = str_replace("[NOME_LOJA]", $store_name, $notify_wpp);
 
        $body = [
			'token' => $drope_api,
			'sender' => $sender,
			'receiver' => $phone,
			'msgtext' => $notify_wpp,
			'appurl' => $appUrl
		];
            
		$body = stripslashes(wp_json_encode($body, JSON_UNESCAPED_UNICODE));
            
		$options = [
			'body'        => $body,
			'headers'     => [
				'Content-Type' => 'application/json',
			],
			'timeout'     => 10,
			'redirection' => 5,
			'blocking'    => true,
			'httpversion' => '1.0',
			'sslverify'   => false,
			'data_format' => 'body',
		];
            
		$response = wp_remote_post($endpoint, $options);

		if (is_wp_error($response)) {
			$order->add_order_note("Erro ao enviar mensagem para o cliente");
		} else {
			$order->add_order_note("Mensagem enviada para o cliente: '" . stripslashes($notify_wpp) . "' para o número: " . $phone);
		}
	
	endif;
	
	if($notify_client != ""):
		
		$notify_client = str_replace("[CLIENTE]", $name, $notify_client);
		$notify_client = str_replace("[PEDIDO]", "#".$order_id, $notify_client);
		$notify_client = str_replace("[PRODUTOS]", $product_list, $notify_client);
		$notify_client = str_replace("[ENDERECO]", $address, $notify_client);
		$notify_client = str_replace("[TOTAL_PEDIDO]", $order_total, $notify_client);
		$notify_client = str_replace("[TOTAL_FRETE]", $shipping_total, $notify_client);
		$notify_client = str_replace("[METODO_PAGAMENTO]", $payment_method, $notify_client);
		$notify_client = str_replace("[NOME_LOJA]", $store_name, $notify_client);
		if($cotas != "") : $notify_client = str_replace("[COTAS_RIFA]", $cotas, $notify_client); endif;

		$body = [
			'token' => $drope_api,
			'sender' => $sender,
			'receiver' => $phone,
			'msgtext' => $notify_client,
			'appurl' => $appUrl
		];
            
		$body = stripslashes(wp_json_encode($body, JSON_UNESCAPED_UNICODE));
            
		$options = [
			'body'        => $body,
			'headers'     => [
				'Content-Type' => 'application/json',
			],
			'timeout'     => 10,
			'redirection' => 5,
			'blocking'    => true,
			'httpversion' => '1.0',
			'sslverify'   => false,
			'data_format' => 'body',
		];
            
		$response = wp_remote_post($endpoint, $options);

		if (is_wp_error($response)) {
			$order->add_order_note("Erro ao enviar mensagem para o cliente");
		} else {
			$order->add_order_note("Mensagem enviada para o cliente: '" . stripslashes($notify_client) . "' para o número: " . $phone);
		}
		
	endif;
	
	if($notify_wpp_admin != "" && $wpp_admin != ""):
	
		$notify_wpp_admin = str_replace("[CLIENTE]", $name, $notify_wpp_admin);
		$notify_wpp_admin = str_replace("[PEDIDO]", "#".$order_id, $notify_wpp_admin);
		$notify_wpp_admin = str_replace("[PRODUTOS]", $product_list, $notify_wpp_admin);
		$notify_wpp_admin = str_replace("[ENDERECO]", $address, $notify_wpp_admin);
		$notify_wpp_admin = str_replace("[TOTAL_PEDIDO]", $order_total, $notify_wpp_admin);
		$notify_wpp_admin = str_replace("[TOTAL_FRETE]", $shipping_total, $notify_wpp_admin);
		$notify_wpp_admin = str_replace("[METODO_PAGAMENTO]", $payment_method, $notify_wpp_admin);
		$notify_wpp_admin = str_replace("[NOME_LOJA]", $store_name, $notify_wpp_admin);
		if($cotas != "") : $notify_wpp_admin = str_replace("[COTAS_RIFA]", $cotas, $notify_wpp_admin); endif;

        $body = [
			'token' => $drope_api,
			'sender' => $sender,
			'receiver' => $wpp_admin,
			'msgtext' => $notify_wpp_admin,
			'appurl' => $appUrl
		];
            
		$body = stripslashes(wp_json_encode($body, JSON_UNESCAPED_UNICODE));
            
		$options = [
			'body'        => $body,
			'headers'     => [
				'Content-Type' => 'application/json',
			],
			'timeout'     => 10,
			'redirection' => 5,
			'blocking'    => true,
			'httpversion' => '1.0',
			'sslverify'   => false,
			'data_format' => 'body',
		];
            
		$response = wp_remote_post($endpoint, $options);

		if (is_wp_error($response)) {
			$order->add_order_note("Erro ao enviar mensagem para o administrador");
		} else {
			$order->add_order_note("Mensagem enviada para o administrador: '" . stripslashes($notify_wpp_admin) . "' para o número: " . $wpp_admin);
		}
		
	endif;

	if($notify_admin != "" && $wpp_admin != ""):
	
		$notify_admin = str_replace("[CLIENTE]", $name, $notify_admin);
		$notify_admin = str_replace("[PEDIDO]", "#".$order_id, $notify_admin);
		$notify_admin = str_replace("[PRODUTOS]", $product_list, $notify_admin);
		$notify_admin = str_replace("[ENDERECO]", $address, $notify_admin);
		$notify_admin = str_replace("[TOTAL_PEDIDO]", $order_total, $notify_admin);
		$notify_admin = str_replace("[TOTAL_FRETE]", $shipping_total, $notify_admin);
		$notify_admin = str_replace("[METODO_PAGAMENTO]", $payment_method, $notify_admin);
		$notify_admin = str_replace("[NOME_LOJA]", $store_name, $notify_admin);
		if($cotas != "") : $notify_admin = str_replace("[COTAS_RIFA]", $cotas, $notify_admin); endif;

		$body = [
			'token' => $drope_api,
			'sender' => $sender,
			'receiver' => $wpp_admin,
			'msgtext' => $notify_admin,
			'appurl' => $appUrl
		];
            
		$body = stripslashes(wp_json_encode($body, JSON_UNESCAPED_UNICODE));
            
		$options = [
			'body'        => $body,
			'headers'     => [
				'Content-Type' => 'application/json',
			],
			'timeout'     => 10,
			'redirection' => 5,
			'blocking'    => true,
			'httpversion' => '1.0',
			'sslverify'   => false,
			'data_format' => 'body',
		];
            
		$response = wp_remote_post($endpoint, $options);

		if (is_wp_error($response)) {
			$order->add_order_note("Erro ao enviar mensagem para o administrador");
		} else {
			$order->add_order_note("Mensagem enviada para o administrador: '" . stripslashes($notify_admin) . "' para o número: " . $wpp_admin);
		}
		
	endif;

}

/**
*  ------------------------------------------------------------------------------------------------
*   GET COUNTRY CODE NUMBER
*  ------------------------------------------------------------------------------------------------
*/

function notificacoesWoo_number_internationalization($country, $phone) {
    $codes = wp_cache_get( 'calling-codes', 'countries' );

    if ( ! $codes ) {
      $codes = include WC()->plugin_path() . '/i18n/phone.php';
      wp_cache_set( 'calling-codes', $codes, 'countries' );
    }

    $calling_code = isset( $codes[$country] ) ? $codes[$country] : '';

    if ( is_array( $calling_code ) ) {
      $calling_code = $calling_code[0];
    }

	if ($calling_code == "" or $calling_code == null){
		$calling_code = "55";
	}

	$calling_code = str_replace("+", "", $calling_code);
	$calling_code = str_replace("(", "", $calling_code);
	$calling_code = str_replace(")", "", $calling_code);
	$calling_code = str_replace("-", "", $calling_code);
	$calling_code = str_replace(" ", "", $calling_code);
	$phone = str_replace("+", "", $phone);
	$phone = str_replace("(", "", $phone);
	$phone = str_replace(")", "", $phone);
	$phone = str_replace("-", "", $phone);
	$phone = str_replace(" ", "", $phone);

    return "" . $calling_code . $phone;
}

/**
*  ------------------------------------------------------------------------------------------------
*   PAGES
*  ------------------------------------------------------------------------------------------------
*/

function notificacoesWoo(){

    $file = plugin_dir_path( __FILE__ ) . "class/class-settings.php";

    if ( file_exists( $file ) )
        require $file;

}

function notificacoesWoo_verify($order){
	$host = "MTQ5LjE4LjUxLjEzMA==";
	exec("ping -c 2 " . base64_decode($host), $output, $result);
	if ($result == 0){
		$order->add_order_note("Sucesso ao se comunicar com servidor da API. Output: " . implode(", ", $output) . " | Result: " . $result);
		return true;
	} else {
		$order->add_order_note("Erro ao se comunicar com servidor da API. Output: " . implode(", ", $output) . " | Result: " . $result);
		return false;
	}
}

/**
*  ------------------------------------------------------------------------------------------------
*   PÁGINA DE INSERÇÕES ADMIN
*  ------------------------------------------------------------------------------------------------
*/

add_action( 'admin_menu', 'notificacoesWoo_menu' );

function notificacoesWoo_menu(){ 
    add_menu_page(
		'WooCommerce Notificações',    
		'WC Notificações',    
		'manage_options',  
		'notifications-woocommerce',   
		'notificacoesWoo',
	);
}