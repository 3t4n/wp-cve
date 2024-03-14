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
			$table_name = $wpdb->prefix . 'cwmp_transportadoras';
			$add_bump = $wpdb->insert($table_name, array(
				'transportadora' => $_POST['transportadora'],
				'estrutura' => $_POST['estrutura'],
				'relation_shipping' => $_POST['relation_shipping'],
			));
			
		}
		if($_GET['action']=="edit"){
			global $wpdb;
			$table_name = $wpdb->prefix . 'cwmp_transportadoras';
			$add_bump = $wpdb->update($table_name, array(
				'transportadora' => $_POST['transportadora'],
				'estrutura' => $_POST['estrutura'],
				'relation_shipping' => $_POST['relation_shipping'],
			),array('id'=>$_POST['id']));
			
		}
		if($_GET['action']=="delete"){
			global $wpdb;
			$table_name = $wpdb->prefix . 'cwmp_transportadoras';
			$add_bump = $wpdb->delete($table_name, array('id'=>$_GET['id']));
		}
		}
		?>
			<?php
			$args = array(
				'box'=>array(
					'title'=>__('Carriers', 'checkout-mestres-wp'),
					'description'=>__('Create a carrier to send tracking links to your customers.', 'checkout-mestres-wp'),
					'button'=>array(
						'label'=>__('Create Carrier', 'checkout-mestres-wp'),
						'url'=>'admin.php?page=cwmp_admin_entrega&type=entrega.transportadoras-add',
					),
					'help'=>'https://docs.mestresdowp.com.br',
					'patch'=>'admin.php?page=cwmp_admin_entrega&type=entrega.transportadoras-',
					'bd'=>array(
						'name'=> 'cwmp_transportadoras',
						'lines'=> array(
							'0'=>array(
								'type'=>'text',
								'value'=>'transportadora',
							),

						),
						'order'=>array(
							'by'=>'DESC',
							'value'=>'transportadora'
						),
					)
				),
			);
			echo cwmpAdminCreateLists($args);
			include "config.copyright.php";
