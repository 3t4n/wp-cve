<?php
if(isset($_GET['action'])){
if($_GET['action']=="add"){
	global $wpdb;
    $table_name = $wpdb->prefix . 'cwmp_newsletter_campanha';
	if(isset($_POST)){
		$add_bump = $wpdb->insert($table_name, array(
			'campanha' => $_POST['cwmp_newsletter_campanha'],
			'conteudo' => $_POST['cwmp_newsletter_conteudo'],
			'status' => '0',
			'data' => $_POST['cwmp_newsletter_date']
		));
		$last_id = $wpdb->insert_id;
		$status = array();
		if(isset($_POST['cwmp_newsletter_lista_pedidos_aguardando'])){ $status[] = 'on-hold'; }
		if(isset($_POST['cwmp_newsletter_lista_pedidos_aguardando_pagamento'])){ $status[] = 'pending'; }
		if(isset($_POST['cwmp_newsletter_lista_pedidos_processando'])){ $status[] = 'processing'; }
		if(isset($_POST['cwmp_newsletter_lista_pedidos_concluidos'])){ $status[] = 'completed'; }
		if(isset($_POST['cwmp_newsletter_lista_pedidos_cancelled'])){ $status[] = 'cancelled'; }
		if(isset($_POST['cwmp_newsletter_lista_pedidos_failed'])){ $status[] = 'failed'; }
		$args = array(
			'limit' => -1,
			'status' => $status
		);
		$orders = wc_get_orders($args);
		$emails = array();
		foreach($orders as $order){
			if($order->get_status()=="refunded" OR $order->get_status()=="trash"){
			}else{
				if($order->get_parent_id()=="0"){
					$emails[] = strtolower($order->get_billing_email());			
				}else{
				}
			}
		}
		$table_name2 = $wpdb->prefix . 'cwmp_cart_abandoned';
		if(isset($_POST['cwmp_newsletter_lista_carrinhos_abandonados'])){
			$carts_abandoneds = $wpdb->get_results("SELECT * FROM ".$table_name2." ORDER BY time DESC");
			foreach ($carts_abandoneds as $carts) {
				$emails[] = strtolower($carts->email);
			}
		
		}	
		$resultado = array_unique($emails);
		$table_name_remove = $wpdb->prefix . 'cwmp_newsletter_remove';
		$remove_emails = $wpdb->get_results("SELECT * FROM ".$table_name_remove."");
		$removeemails = array();
		foreach ($remove_emails as $emails) {
			$removeemails[] = strtolower($emails->email);
		}
		$arrayfinal = array_diff($resultado, $removeemails);
		$carts_abandoneds = $wpdb->get_results("SELECT * FROM ".$table_name2." ORDER BY time DESC");
		$table_name3 = $wpdb->prefix . 'cwmp_newsletter_send';
		foreach($arrayfinal as $email){
			$add_bump = $wpdb->insert($table_name3, array(
				'campanha' => $last_id,
				'email' => $email,
				'open' => '0',
				'clique' => '0',
				'status' => '0'
			));
		}
		
		
		
		
	}
}
if($_GET['action']=="edit"){
	global $wpdb;
    $table_name = $wpdb->prefix . 'cwmp_newsletter_campanha';
    $add_bump = $wpdb->update($table_name, array(
		'campanha' => $_POST['cwmp_newsletter_campanha'],
		'conteudo' => $_POST['cwmp_newsletter_conteudo'],
		'data' => $_POST['cwmp_newsletter_date']	
    ),array('id'=>$_POST['id']));
}
if($_GET['action']=="start"){
	global $wpdb;
    $table_name = $wpdb->prefix . 'cwmp_newsletter_campanha';
    $add_bump = $wpdb->update($table_name, array(
		'status' => '1'
    ),array('id'=>$_GET['id']));

}
if($_GET['action']=="delete"){
	global $wpdb;
    $table_name = $wpdb->prefix . 'cwmp_newsletter_campanha';
    $table_name2 = $wpdb->prefix . 'cwmp_newsletter_send';
    $add_bump = $wpdb->delete($table_name,array('id'=>$_GET['id']));
    $delete_bump = $wpdb->delete($table_name2,array('campanha'=>$_GET['id']));
}
}
			$args = array(
				'box'=>array(
					'title'=>__('Newslleter', 'checkout-mestres-wp'),
					'description'=>__('Send promotional emails to your customers.', 'checkout-mestres-wp'),
					'button'=>array(
						'label'=>__('Create E-mail', 'checkout-mestres-wp'),
						'url'=>'admin.php?page=cwmp_admin_comunicacao&type=comunicacao.newsletter-add',
					),
					'help'=>'https://docs.mestresdowp.com.br',
					'patch'=>'admin.php?page=cwmp_admin_comunicacao&type=comunicacao.newsletter-',
					'action'=>array(
						'value'=>'start'
					),
					'bd'=>array(
						'name'=> 'cwmp_newsletter_campanha',
						'lines'=> array(
							'0'=>array(
								'type'=>'text',
								'value'=>'campanha',
							),
							'1'=>array(
								'type'=>'text',
								'value'=>'data',
							),
							'2'=>array(
								'type'=>'newsletterSends',
								'value'=>'id',
							),
							'3'=>array(
								'type'=>'newsletterClicks',
								'value'=>'id',
							),
							'4'=>array(
								'type'=>'newsletterOpen',
								'value'=>'id',
							),
						),
						'order'=>array(
							'by'=>'DESC',
							'value'=>'data'
						),
						'limit'=>array(
							'value'=>'10'
						),

					)
				),
			);
			echo cwmpAdminCreateLists($args);
			include "config.copyright.php";
