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
				$table_name = $wpdb->prefix . 'cwmp_events_pixels';
				$add_bump = $wpdb->insert($table_name, array(
					'tipo' => $_POST['tipo'],
					'pixel' => $_POST['pixel'],
					'token' => $_POST['token'],
					'test' => $_POST['test'],
					'ref' => $_POST['ref']
				));
			}
			if($_GET['action']=="edit"){
				global $wpdb;
				$table_name = $wpdb->prefix . 'cwmp_events_pixels';
				$add_bump = $wpdb->update($table_name, array(
					'tipo' => $_POST['tipo'],
					'pixel' => $_POST['pixel'],
					'token' => $_POST['token'],
					'test' => $_POST['test'],
					'ref' => $_POST['ref']
				),array('id'=>$_POST['id']));
				
			}
			if($_GET['action']=="delete"){
				global $wpdb;
				$table_name = $wpdb->prefix . 'cwmp_events_pixels';
				$add_bump = $wpdb->delete($table_name, array('id'=>$_GET['id']));
			}
		}
			$args = array(
				'box'=>array(
					'title'=>__('Traffic', 'checkout-mestres-wp'),
					'description'=>__('Create your pixel to use integration with Facebook Ads, Google Ads, Google Analytics, etc.', 'checkout-mestres-wp'),
					'button'=>array(
						'label'=>__('Create your pixel', 'checkout-mestres-wp'),
						'url'=>'admin.php?page=cwmp_admin_checkout&type=checkout.trafego-add',
					),
					'help'=>'https://docs.mestresdowp.com.br',
					'patch'=>'admin.php?page=cwmp_admin_checkout&type=checkout.trafego-',
					'bd'=>array(
						'name'=> 'cwmp_events_pixels',
						'lines'=> array(
							'0'=>array(
								'type'=>'text',
								'value'=>'ref',
							),
							'1'=>array(
								'type'=>'text',
								'value'=>'tipo',
							),
						),
						'order'=>array(
							'by'=>'ASC',
							'value'=>'ref'
						)
					),
				),
			);
			echo cwmpAdminCreateLists($args);
			include "config.copyright.php";