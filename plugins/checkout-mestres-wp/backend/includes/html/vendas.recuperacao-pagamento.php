<?php
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
		if(isset($_GET['action'])){
			if($_GET['action']=="add"){
				global $wpdb;
				$table_name = $wpdb->prefix . 'cwmp_pending_payment_msg';
				$add_bump = $wpdb->insert($table_name, array(
					'method' => $_POST['method'],
					'time' => $_POST['time'],
					'time2' => $_POST['time2'],
					'titulo' => $_POST['titulo'],
					'mensagem' => $_POST['mensagem'],
					'body' => $_POST['body']
				));
				
			}
			if($_GET['action']=="edit"){
				
				global $wpdb;
				$table_name = $wpdb->prefix . 'cwmp_pending_payment_msg';
				$add_bump = $wpdb->update($table_name, array(
					'method' => $_POST['method'],
					'time' => $_POST['time'],
					'time2' => $_POST['time2'],
					'titulo' => $_POST['titulo'],
					'mensagem' => $_POST['mensagem'],
					'body' => $_POST['body']
				),array('id'=>$_POST['id']));
				
			}
			if($_GET['action']=="delete"){
				global $wpdb;
				$table_name = $wpdb->prefix . 'cwmp_pending_payment_msg';
				$add_bump = $wpdb->delete($table_name, array('id'=>$_GET['id']));
			}
		}
		
		?>
			<?php
			$args = array(
				'box'=>array(
					'title'=>__('Payment Recovery', 'checkout-mestres-wp'),
					'description'=>__('Retrieve pending payments by sending emails and messages via WhatsApp.', 'checkout-mestres-wp'),
					'button'=>array(
						'label'=>__('Create Recovery', 'checkout-mestres-wp'),
						'url'=>'admin.php?page=cwmp_admin_vendas&type=vendas.recuperacao-pagamento-add',
					),
					'help'=>'https://docs.mestresdowp.com.br',
					'patch'=>'admin.php?page=cwmp_admin_vendas&type=vendas.recuperacao-pagamento-',
					'bd'=>array(
						'name'=> 'cwmp_pending_payment_msg',
						'lines'=> array(
							'2'=>array(
								'type'=>'time',
								'value'=>'time,time2',
							),
							'0'=>array(
								'type'=>'text',
								'value'=>'titulo',
							),
							'1'=>array(
								'type'=>'icon',
								'value'=>'tipo',
							),

						),
						'order'=>array(
							'by'=>'DESC',
							'value'=>'titulo'
						),
					)
				),
			);
			echo cwmpAdminCreateLists($args);
			include "config.copyright.php";
