<?php
add_action('woocommerce_order_status_changed','orderStatusChange', 99999999999, 2);
add_action( 'woocommerce_payment_successful_result', 'orderStatus',99999999999, 2);
function orderStatus($result, $order_id){
	global $wpdb;
	$order = wc_get_order($order_id);
	$get_send_order = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}cwmp_send_thank WHERE pedido = %d AND status = %s",
        $order->get_id(),
        cwmp_get_status_order($order->get_id())
    )
	);
	if(count($get_send_order)=="0"){
		if(get_option('cwmp_activate_emails')=="S"){
			$sendNotification = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT * FROM ' . $wpdb->prefix . 'cwmp_template_emails msgs WHERE msgs.status LIKE %s AND msgs.metodo LIKE %s ORDER BY id ASC',
					'wc_' . str_replace('-', '_', $order->get_status()),
					str_replace('-', '_', $order->get_payment_method())
				)
			);
			foreach($sendNotification as $key => $value){
				if($value->conteudo){
					$string_title = str_replace("]", " val='" . $order->get_id() . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', str_replace("\'","'",str_replace('\"','"',$value->titulo))));
					$string_title_renovada = do_shortcode($string_title);
					$string_content = str_replace("]", " val='" . $order->get_id() . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', str_replace("\'","'",str_replace('\"','"',$value->conteudo))));
					$string_content_renovada = do_shortcode($string_content);
					cwmp_send_mail($order->get_billing_email(),$string_title_renovada,$string_content_renovada);
				}
			}
		}
		if(get_option('cwmp_activate_whatsapp')=="S"){
			if(get_option('cwmp_template_whatsapp_notify_lojista')=="1"){
				if(get_option('cwmp_template_whatsapp_status_active')=="wc-".$order->get_status() OR get_option('cwmp_template_whatsapp_status_active')=="wc_".$order->get_status()){
					cwmp_send_lojista($order->get_id());
				}
			}
			$sendNotification = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT * FROM ' . $wpdb->prefix . 'cwmp_template_msgs msgs WHERE msgs.status LIKE %s AND msgs.metodo LIKE %s ORDER BY seq ASC',
					'wc_' . str_replace('-', '_', $order->get_status()),
					str_replace('-', '_', $order->get_payment_method())
				)
			);
			$numero = cwmp_trata_numero($order->get_billing_phone()); $i=0;
			foreach($sendNotification as $key => $value){
				if($value->conteudo){
					$string_wpp_content = str_replace("]", " val='" .$order->get_id() . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', str_replace("\'","'",str_replace('\"','"',$value->conteudo))));
					$string_wpp_content_renovada = do_shortcode($string_wpp_content);
					cwmp_send_whatsapp($numero,$string_wpp_content_renovada); 
				}
			}
		}
		cwmp_register_send_msgMail($order->get_id());
	}
	return $result;
}
function orderStatusChange($order_id, $order){
	global $wpdb;
	$order = wc_get_order($order_id);
	$get_send_order = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT * FROM ' . $wpdb->prefix . 'cwmp_send_thank WHERE pedido = %d AND status = %s',
			$order->get_id(),
			cwmp_get_status_order($order->get_id())
		)
	);
	if(count($get_send_order)=="0"){
		if(get_option('cwmp_activate_emails')=="S"){
			$sendNotification = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT * FROM ' . $wpdb->prefix . 'cwmp_template_emails msgs WHERE msgs.status LIKE %s AND msgs.metodo LIKE %s ORDER BY id ASC',
					'wc_' . str_replace('-', '_', $order->get_status()),
					str_replace('-', '_', $order->get_payment_method())
				)
			);
			foreach($sendNotification as $key => $value){
				if($value->conteudo){
					$string_title = str_replace("]", " val='" . $order->get_id() . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', str_replace("\'","'",str_replace('\"','"',$value->titulo))));
					$string_title_renovada = do_shortcode($string_title);
					$string_content = str_replace("]", " val='" . $order->get_id() . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', str_replace("\'","'",str_replace('\"','"',$value->conteudo))));
					$string_content_renovada = do_shortcode($string_content);
					cwmp_send_mail($order->get_billing_email(),$string_title_renovada,$string_content_renovada);
				}
			}
		}
		if(get_option('cwmp_activate_whatsapp')=="S"){
			if(get_option('cwmp_template_whatsapp_notify_lojista')=="1"){
				if(get_option('cwmp_template_whatsapp_status_active')=="wc-".$order->get_status() OR get_option('cwmp_template_whatsapp_status_active')=="wc_".$order->get_status()){
					cwmp_send_lojista($order->get_id());
				}
			}
			$sendNotification = $wpdb->get_results(
				$wpdb->prepare(
					'SELECT * FROM ' . $wpdb->prefix . 'cwmp_template_msgs msgs WHERE msgs.status LIKE %s AND msgs.metodo LIKE %s ORDER BY seq ASC',
					'wc_' . str_replace('-', '_', $order->get_status()),
					str_replace('-', '_', $order->get_payment_method())
				)
			);
			$numero = cwmp_trata_numero($order->get_billing_phone());
			foreach($sendNotification as $key => $value){
				if($value->conteudo){
					$string_wpp_content = str_replace("]", " val='" .$order->get_id() . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', str_replace("\'","'",str_replace('\"','"',$value->conteudo))));
					$string_wpp_content_renovada = do_shortcode($string_wpp_content);
					cwmp_send_whatsapp($numero,$string_wpp_content_renovada); 
				}
			}
		}
		cwmp_register_send_msgMail($order->get_id());
	}
}