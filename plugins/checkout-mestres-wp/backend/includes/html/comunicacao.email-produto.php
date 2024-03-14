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
			$table_name = $wpdb->prefix . 'cwmp_template_emails_produto';
			$add_bump = $wpdb->insert($table_name, array(
				'metodo' => $_POST['cwmp_template_email_payment'],
				'status' => $_POST['cwmp_template_email_status'],
				'time' => $_POST['time'],
				'time2' => $_POST['time2'],
				'titulo' => $_POST['cwmp_template_email_title'],
				'conteudo' => $_POST['cwmp_template_email_conteudo'],
				'msg' => $_POST['msg']
			));
			
		}
		if($_GET['action']=="edit"){
			global $wpdb;
			$table_name = $wpdb->prefix . 'cwmp_template_emails_produto';
			$add_bump = $wpdb->update($table_name, array(
				'metodo' => $_POST['cwmp_template_email_payment'],
				'status' => $_POST['cwmp_template_email_status'],
				'time' => $_POST['time'],
				'time2' => $_POST['time2'],
				'titulo' => $_POST['cwmp_template_email_title'],
				'conteudo' => $_POST['cwmp_template_email_conteudo'],
				'msg' => $_POST['msg']
			),array('id'=>$_POST['id']));
			
		}
		if($_GET['action']=="delete"){
			global $wpdb;
			$table_name = $wpdb->prefix . 'cwmp_template_emails_produto';
			$add_bump = $wpdb->delete($table_name, array('id'=>$_GET['id']));
		}
		}
			$args = array(
				'box'=>array(
					'title'=>__('Email per product', 'checkout-mestres-wp'),
					'description'=>__('Send emails with information about products sold.', 'checkout-mestres-wp'),
					'button'=>array(
						'label'=>__('Create email', 'checkout-mestres-wp'),
						'url'=>'admin.php?page=cwmp_admin_comunicacao&type=comunicacao.email-produto-add',
					),
					'help'=>'https://docs.mestresdowp.com.br',
					'patch'=>'admin.php?page=cwmp_admin_comunicacao&type=comunicacao.email-produto-',
					'bd'=>array(
						'name'=> 'cwmp_template_emails_produto',
						'lines'=> array(
							'0'=>array(
								'type'=>'product',
								'value'=>'metodo',
							),
							'1'=>array(
								'type'=>'status',
								'value'=>'status',
							),
						),
						'order'=>array(
							'by'=>'ASC',
							'value'=>'status'
						)
					)
				),
			);
			echo cwmpAdminCreateLists($args);
			include "config.copyright.php";
