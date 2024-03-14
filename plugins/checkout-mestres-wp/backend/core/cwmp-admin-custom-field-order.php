<?php
/* BOX RASTREIO */
add_action( 'add_meta_boxes', 'cwmp_add_meta_boxes' );
function cwmp_add_meta_boxes(){
    add_meta_box( 'mv_other_fields', __('Rastreio','woocommerce'), 'cwmp_add_other_fields_for_packaging', 'shop_order', 'side', 'core' );
}
function cwmp_add_other_fields_for_packaging(){
    global $wp;
    global $wpdb;
    global $post;
	global $table_prefix;
	$html = "";
    $html .= '<input type="hidden" id="cwmp_pedido_id" name="cwmp_pedido_id" placeholder="Código de Rastreio" value="' . $post->ID . '">';
    $cwmp_codigo_transportadora = get_post_meta( $post->ID, '_cwmp_codigo_transportadora_slug', true ) ? get_post_meta( $post->ID, '_cwmp_codigo_transportadora_slug', true ) : '';
    $cwmp_codigo_rastreio = get_post_meta( $post->ID, '_cwmp_codigo_rastreio_slug', true ) ? get_post_meta( $post->ID, '_cwmp_codigo_rastreio_slug', true ) : '';
	$html .= '<input type="hidden" name="cwmp_other_meta_field_nonce" value="' . wp_create_nonce() . '">';
	$html .= '<p style="">';
    $html .= '<select style="width:100%;" name="cwmp_codigo_transportadora" id="cwmp_codigo_transportadora">';
	$get_campanha = $wpdb->get_results(
		"SELECT * FROM ".$table_prefix."cwmp_transportadoras"
	);
	foreach($get_campanha as $campanha){
		$html .= '<option value='.$campanha->id.'';
		if($campanha->id==$cwmp_codigo_transportadora){
			$html .= ' selected="selected" ';
		}
		$html .= '>';
		$html .= $campanha->transportadora;
		$html .= '</option>';
	}
	$html .= '</select>';
	$html .= '</p>';
    $html .= '<p style="">';
    $html .= '<input type="text" style="width:100%;" id="cwmp_codigo_rastreio" name="cwmp_codigo_rastreio" placeholder="Código de Rastreio" value="' . $cwmp_codigo_rastreio . '">';
	$html .= '</p>';
    $html .= '<p style="">';
	$html .= '<button class="button button-primary" id="cwmp_button_add_rastreio" style="width:100% !important;"> Adicionar </button>';
	$html .= '</p>';
	echo $html;
}
function cwmp_save_wc_order_other_fields() {
	$menu_nonce = wp_create_nonce('menu_nonce');
    if (isset($_GET['page']) && $_GET['page'] === 'cwmp_admin_menu') {
        // Nonce válido, execute as ações necessárias
        if (isset($_GET['_wpnonce']) && wp_verify_nonce(sanitize_key($_GET['_wpnonce']), 'menu_nonce')) {
            echo '<div class="notice notice-success is-dismissible"><p>Ação realizada com sucesso!</p></div>';
        } else {
            // Nonce inválido, redirecionar ou lidar com a situação de não autorizado
            echo '<div class="notice notice-error is-dismissible"><p>Erro: Nonce inválido ou ausente.</p></div>';
            return;
        }
    }
	$order = wc_get_order($_POST['pedido']);
	if(get_post_meta($order->get_id(), '_cwmp_codigo_transportadora')==$_POST['track'] OR $_POST['track']==""){
	}else{
		update_post_meta($order->get_id(), '_cwmp_codigo_transportadora_slug', $_POST['transportadora']);
		update_post_meta($order->get_id(), '_cwmp_codigo_rastreio_slug', $_POST['track']);
		$order->update_status( 'wc-pedido-enviado' );
	}
	die();
}
add_action('wp_ajax_cwmp_save_wc_order_other_fields', 'cwmp_save_wc_order_other_fields');
add_action('wp_ajax_nopriv_cwmp_save_wc_order_other_fields', 'cwmp_save_wc_order_other_fields');

if(get_option('cwmp_activate_whatsapp')=="S"){
	add_action( 'add_meta_boxes', 'cwmp_add_meta_whats_manual' );
	function cwmp_add_meta_whats_manual(){
		add_meta_box( 'mv_whats_manual', __('WhatsApp Manual','woocommerce'), 'cwmp_add_other_whats_manual', 'shop_order', 'side', 'core' );
	}
	function cwmp_add_other_whats_manual(){
		global $post;
		global $wpdb;
		global $table_prefix;
		$html = "";
		$html .= '<p>';
		$html .= '<input type="hidden" class="cwmp_whats_manual_send_pedido" value="'.$post->ID.'" />';
		$html .= '<select style="width:100%;" id="cwmp_whats_manual_send_template">';
		$get_campanha = $wpdb->get_results(
			"SELECT * FROM ".$table_prefix."cwmp_template_msgs GROUP BY status"
		);
		foreach($get_campanha as $campanha){
			$wc_gateways      = new WC_Payment_Gateways();
			$payment_gateways = $wc_gateways->payment_gateways();
			foreach( $payment_gateways as $gateway_id => $gateway ){
				if($gateway->enabled=="yes"){
					if($campanha->metodo==$gateway->id){
						$gateway_html2 = $gateway->id;
						$gateway_html = $gateway->title;
					}
				}
			}
			$order_statuses = wc_get_order_statuses();
			$statuse_html = "";
			foreach($order_statuses as $key => $status){
				if(str_replace('_', '-', $campanha->status)==$key){
					$statuse_html = $status."";
				}
			}
			$html .= '<option value="'.$gateway_html2.' | '.$campanha->status.'">'.$gateway_html.' | '.$statuse_html.'</option>';
		}
		$html .= '</select>';
		$html .= '</p>';
		$html .= '<p style="">';
		$html .= '<button class="button button-primary" id="cwmp_send_whatsapp_manual" style="width:100% !important;"> Enviar </button>';
		$html .= '</p>';
		echo $html;
	}
	add_action('wp_ajax_cwmp_add_other_whats_manual_send', 'cwmp_add_other_whats_manual_send');
	add_action('wp_ajax_nopriv_cwmp_add_other_whats_manual_send', 'cwmp_add_other_whats_manual_send');
	function cwmp_add_other_whats_manual_send() {
	$menu_nonce = wp_create_nonce('menu_nonce');
    if (isset($_GET['page']) && $_GET['page'] === 'cwmp_admin_menu') {
        // Nonce válido, execute as ações necessárias
        if (isset($_GET['_wpnonce']) && wp_verify_nonce(sanitize_key($_GET['_wpnonce']), 'menu_nonce')) {
            echo '<div class="notice notice-success is-dismissible"><p>Ação realizada com sucesso!</p></div>';
        } else {
            // Nonce inválido, redirecionar ou lidar com a situação de não autorizado
            echo '<div class="notice notice-error is-dismissible"><p>Erro: Nonce inválido ou ausente.</p></div>';
            return;
        }
    }
		global $wpdb;
		$get_template = explode(" | ", $_POST['pedido']);
		$order = wc_get_order($_POST['template']);
		$template_whatsapp = $wpdb->get_results($wpdb->prepare(
			"SELECT * FROM %s WHERE status = %s AND metodo = %s ORDER BY seq ASC",
			$wpdb->prefix . "cwmp_template_msgs",
			str_replace('-', '_', $get_template['1']),
			str_replace('-', '_', $get_template['0'])
		));
		$numero = cwmp_trata_numero($order->get_billing_phone());
		if(isset($template_whatsapp[0])){
			foreach($template_whatsapp as $key => $value){
			$string_wpp_content = str_replace("]", " val='" .$order->get_id() . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', str_replace("\'","'",str_replace('\"','"',$value->conteudo))));
			$string_wpp_content_renovada = do_shortcode($string_wpp_content);
			if(get_option('cwmp_activate_whatsapp')=="S"){ cwmp_send_whatsapp($numero,$string_wpp_content_renovada);  }
			}
		}
		die();	
	}
}


