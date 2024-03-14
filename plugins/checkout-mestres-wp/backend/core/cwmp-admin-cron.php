<?php
function cwmp_cron_events(){
    global $wpdb;
    global $woocommerce;
	global $table_prefix;
	global $product;
	if(get_option('cwmp_adddon_melhorenvio')=="S"){
		$orders = wc_get_orders(array(
			'limit'=>-1,
			'type'=> 'shop_order',
			'status'=> array( 'wc-separacao' )
			)
		);
		foreach($orders as $order){
			$id = get_post_meta($order->get_ID() , '_order_id_melhor_envio', true);
			$headers = array(
				'Accept'=>'application/json',
				'Content-Type'=>'application/json',
				'Authorization'=>'Bearer '.get_option('cwmo_format_token_melhorenvio_bearer'),
				'User-Agent'=>'Aplicação ('.get_option('cwmo_format_email_melhorenvio').')',
			);
			$send = wp_remote_post(CWMP_BASE_URL_MELHORENVIO."api/v2/me/orders/".$id, array(
			   'method' => 'GET',
			   'headers' => $headers,
			));
			$retorno = json_decode($send['body']);
			if(isset($retorno->status)){
			if($retorno->status=="posted"){
			$ship_method =	$order->get_shipping_methods();
			foreach($ship_method as $value){
				$value_method_id = $value->get_data()['method_id'];
				$get_transportadora = $wpdb->get_results($wpdb->prepare(
					"SELECT * FROM {$wpdb->prefix}cwmp_transportadoras WHERE relation_shipping = %s",
					$value_method_id
				));
				if(isset($get_transportadora[0])){ $id_transportadora = $get_transportadora[0]->id; }
			}
			update_post_meta($order->get_id(), '_cwmp_codigo_transportadora_slug', $id_transportadora);
			update_post_meta($order->get_id(), '_cwmp_codigo_rastreio_slug', $retorno->tracking);
			$order->update_status( 'wc-pedido-enviado' );
			}
			}else{}
		}
	}
	
	if(get_option('cwmp_activate_cart')=="S"){
		
		$get_abandoned_cart = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cwmp_cart_abandoned");
		foreach($get_abandoned_cart as $key){
			$get_msgs_abandoned_cart = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cwmp_cart_abandoned_msg");
			foreach($get_msgs_abandoned_cart as $value){
				$get_verify_cart = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cwmp_cart_abandoned_relation WHERE cart='".$key->id."' AND type='".$value->id."'");
				if(count($get_verify_cart)==0){
					$limite = strtotime('2024-03-07 00:00:00');

					// Supondo que $key->time seja a data do registro
					if (strtotime($key->time) < $limite) {

					} else {
					
						if (date("Y-m-d H:i:s") >= date("Y-m-d H:i:s", strtotime("+ {$value->time} {$value->time2}", strtotime($key->time)))) {
							if($value->discount=="yes"){
								$valor_desconto = $value->discount_value;
								$tipo_desconto = 'percent';
								$limite_uso = 1;
								$prazo_validade = '+'.$value->discount_time.' days';
								$codigo_cupom = cwmp_create_coupon($valor_desconto, $tipo_desconto, $limite_uso, $prazo_validade);
							}
							if(get_option('cwmp_activate_whatsapp')=="S"){
								if($value->mensagem){
									$string_wpp_content = str_replace('[cwmp_recovery_link]', get_home_url() . '/?cwmp_recovery_cart=' . base64_encode($key->cart), $value->mensagem);
									if(!empty($codigo_cupom)){
										$string_wpp_content = str_replace('{{cupom}}', $codigo_cupom, $string_wpp_content);
									}
									$string_wpp_content = str_replace('[cwmp_recovery_client_name]', $key->nome, str_replace("\'","'",str_replace('\"','"',$string_wpp_content)));
									$string_wpp_content = str_replace('https://', "", str_replace("\'","'",str_replace('\"','"',$string_wpp_content)));
									$string_wpp_content = str_replace('http://', "", str_replace("\'","'",str_replace('\"','"',$string_wpp_content)));
									$string_wpp_content_renovada = do_shortcode($string_wpp_content);
									$numero = cwmp_trata_numero($key->phone);
									cwmp_send_whatsapp($numero,$string_wpp_content_renovada);
								}
							}
							if(get_option('cwmp_activate_emails')=="S"){
								if($value->body OR $value->elemailer){
									$string_title = str_replace('[cwmp_recovery_client_name]', $key->nome, str_replace("\'","'",str_replace('\"','"',$value->titulo)));
									$string_title_renovada = do_shortcode($string_title);
									if($value->elemailer){
										$post = get_post( $value->elemailer );
										$string_wpp_content = str_replace('[cwmp_recovery_link]', get_home_url() . '/?cwmp_recovery_cart=' . base64_encode($key->cart), str_replace("\'","'",str_replace('\"','"',$post->post_content)));
										if(!empty($codigo_cupom)){
											$string_wpp_content = str_replace('{{cupom}}', $codigo_cupom, $string_wpp_content);
										}
										$string_wpp_content = str_replace('[cwmp_recovery_client_name]', $key->nome, str_replace("\'","'",str_replace('\"','"',$string_wpp_content)));
										$string_wpp_content = str_replace('https://', "", str_replace("\'","'",str_replace('\"','"',$string_wpp_content)));
										$string_wpp_content = str_replace('http://', "", str_replace("\'","'",str_replace('\"','"',$string_wpp_content)));
										$string_content_renovada = do_shortcode($string_wpp_content);
										$string_content_renovada = "<div style='width:800px;'>".$string_content_renovada."</div>";
									}else{
										$string_wpp_content = str_replace('[cwmp_recovery_link]', home_url() . '/?cwmp_recovery_cart=' . base64_encode($key->cart), str_replace("\'","'",str_replace('\"','"',$value->body)));
										if(!empty($codigo_cupom)){
											$string_wpp_content = str_replace('{{cupom}}', $codigo_cupom, $string_wpp_content);
										}
										$string_wpp_content = str_replace('[cwmp_recovery_client_name]', $key->nome, str_replace("\'","'",str_replace('\"','"',$string_wpp_content)));
										$string_content_renovada = do_shortcode($string_wpp_content);
									}
									cwmp_send_mail($key->email,$string_title_renovada,$string_content_renovada);
								}
							}
							$wpdb->insert($wpdb->prefix . 'cwmp_cart_abandoned_relation', array(
								'cart' => $key->id,
								'type' => $value->id
							));
						}
						
						
					}
				}
			}
		}
		
	}
	if(get_option('cwmp_activate_recupera_pgto')=="S"){
		$table_name1 = $wpdb->prefix . 'cwmp_pending_payment_msg';
		$table_name2 = $wpdb->prefix . 'cwmp_pending_payment_status';
		$get_pending_payment = $wpdb->get_results("SELECT * FROM ".$table_name1."");
		foreach($get_pending_payment as $key){
			$args1 = array(
				'date_created' => gmdate('Y-m-d', strtotime( "-".$key->time." ".$key->time2 )),
				'status' => array('on-hold','pending')
			);
			$orders = wc_get_orders( $args1 );
			foreach($orders as $pedido){
				if($key->time2=="minutes" OR $key->time2=="hour" ){
					if(gmdate("Y-m-d H:i:s")>=gmdate("Y-m-d H:i:s", strtotime("+ ".$key->time." ".$key->time2, strtotime($pedido->get_date_created())))){
						$get_pending_payment_status = $wpdb->get_results("SELECT * FROM ".$table_name2." WHERE pedido LIKE '".$pedido->get_ID()."' AND method LIKE '".$pedido->get_payment_method()."' AND msg LIKE '".$key->id."' AND status LIKE '1'");
						$count = count($get_pending_payment_status);
						if($count==0){
							if(get_option('cwmp_activate_whatsapp')=="S"){
								if($key->mensagem){
									$string_wpp_content = str_replace("]", " val='" . $pedido->get_id() . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', $key->mensagem));
									$string_wpp_content_renovada = do_shortcode($string_wpp_content);
									$numero = cwmp_trata_numero($pedido->get_billing_phone());
									cwmp_send_whatsapp($numero,$string_wpp_content_renovada);
								}
							}
							if(get_option('cwmp_activate_emails')=="S"){
								if($key->body){
									$string_title = str_replace("]", " val='" . $pedido->get_id() . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', $key->titulo));
									$string_title_renovada = do_shortcode($string_title);
									$string_content = str_replace("]", " val='" . $pedido->get_id() . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', str_replace("\'","'",str_replace('\"','"',$key->body))));
									$string_content_renovada = do_shortcode($string_content);
									cwmp_send_mail($pedido->get_billing_email(),$string_title_renovada,$string_content_renovada);
								}
							}
							$wpdb->insert($table_name2, array('pedido' => $pedido->get_id(),'method' => $pedido->get_payment_method(),'msg'=>$key->id,'status'=>'1'));
						}else{}
					}
				}
				if($key->time2=="day"){
					if(gmdate("H:i:s")>="12:00:00"){
						$get_pending_payment_status = $wpdb->get_results("SELECT * FROM ".$table_name2." WHERE pedido = '".$pedido->get_ID()."' AND method = '".$pedido->get_payment_method()."' AND msg = '".$key->id."' AND status = '1'");
						if(count($get_pending_payment_status)==0){
							if(get_option('cwmp_activate_whatsapp')=="S"){
								if($key->mensagem){
									$string_wpp_content = str_replace("]", " val='" . $pedido->get_id() . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', $key->mensagem));
									$string_wpp_content_renovada = do_shortcode($string_wpp_content);
									$numero = cwmp_trata_numero($pedido->get_billing_phone());
									cwmp_send_whatsapp($numero,$string_wpp_content_renovada);
								}
							}
							if(get_option('cwmp_activate_emails')=="S"){
								if($key->body){
									$string_title = str_replace("]", " val='" . $pedido->get_id() . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', $key->titulo));
									$string_title_renovada = do_shortcode($string_title);
									$string_content = str_replace("]", " val='" . $pedido->get_id() . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', str_replace("\'","'",str_replace('\"','"',$key->body))));
									$string_content_renovada = do_shortcode($string_content);
									cwmp_send_mail($pedido->get_billing_email(),$string_title_renovada,$string_content_renovada);
								}
							}
							$wpdb->insert($table_name2, array('pedido' => $pedido->get_id(),'method' => $pedido->get_payment_method(),'msg'=>$key->id,'status'=>'1'));
						}else{ }
					}
				}	
			}
		}
	}

	/*EMAILS PRODUTOS*/
	$customerTable18 = $wpdb->prefix . 'cwmp_template_emails_produto';
	$query = "SELECT * FROM $customerTable18";
	$results = $wpdb->get_results($query);
	if ($results) {
		foreach ($results as $result) {
			$product_id = $result->metodo;
			$periodo = $result->time2;
			$quantidade = $result->time;
			$status = $result->status;
			$dataAtual = time();
			if ($periodo === "day") {
				$quantidade *= 24 * 60 * 60;
			} elseif ($periodo === "months") {
				$quantidade *= 30 * 24 * 60 * 60;
			} elseif ($periodo === "years") {
				$quantidade *= 365 * 24 * 60 * 60;
			}
			$dataSubtraida = $dataAtual - $quantidade;
			$dataSubtraidaFormatada = date('Y-m-d', $dataSubtraida);
			echo $product_id.", ".$dataSubtraidaFormatada.", ".$status."<br/>";
			$orderIds = cwmpListBuyProduct($product_id, $dataSubtraidaFormatada, $status);
			foreach ($orderIds as $value) {
				$queryVerify = "SELECT * FROM ".$customerTable18."_send WHERE ordem LIKE '".$value."' AND id_email LIKE '".$result->id."'";
				$results = $wpdb->get_var($queryVerify);
				if($results>=1){
				}else{
					$order = wc_get_order($value);
					print_r($result->titulo);
					if(get_option('cwmp_activate_whatsapp')=="S"){
						if($result->msg!=""){
							$numero = cwmp_trata_numero($order->get_billing_phone());
							$string_wpp_content = str_replace("]", " val='" .$order->get_id() . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', str_replace("\'","'",str_replace('\"','"',$result->msg))));
							$string_wpp_content_renovada = do_shortcode($string_wpp_content);
							cwmp_send_whatsapp($numero,$string_wpp_content_renovada); 
						}
					}
					if(get_option('cwmp_activate_emails')=="S"){
						if($result->titulo && $result->conteudo){
							$string_title = str_replace("]", " val='" . $order->get_id() . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', str_replace("\'","'",str_replace('\"','"',$result->titulo))));
							$string_title_renovada = do_shortcode($string_title);
							$string_content = str_replace("]", " val='" . $order->get_id() . "']", preg_replace('/\[+[\w\s]+\]+/i', '$0', str_replace("\'","'",str_replace('\"','"',$result->conteudo))));
							$string_content_renovada = do_shortcode($string_content);	
							cwmp_send_mail($order->get_billing_email(),$string_title_renovada,$string_content_renovada);						
						}
					}
					$success = $wpdb->insert($customerTable18."_send", array(
						'ordem' => $value,
						'id_email' => $result->id,
						'status' => '1'
					));
				
				}
			}
		}
	} else {}
	
	
	
	
	

}
add_action('cwmp_cron_events', 'cwmp_cron_events');