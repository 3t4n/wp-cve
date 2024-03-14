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
				$table_name = $wpdb->prefix . 'cwmp_order_bump';
				$add_bump = $wpdb->insert($table_name, array(
					'produto' => $_POST['produto'],
					'bump' => $_POST['bump'],
					'valor' => $_POST['valor'],
					'chamada' => $_POST['chamada']
				));
			}
			if($_GET['action']=="edit"){
				global $wpdb;
				$table_name = $wpdb->prefix . 'cwmp_order_bump';
				$add_bump = $wpdb->update($table_name, array(
					'produto' => $_POST['produto'],
					'bump' => $_POST['bump'],
					'valor' => $_POST['valor'],
					'chamada' => $_POST['chamada']
				),array('id'=>$_POST['id']));
				
			}
			if($_GET['action']=="delete"){
				global $wpdb;
				$table_name = $wpdb->prefix . 'cwmp_order_bump';
				$add_bump = $wpdb->delete($table_name, array('id'=>$_GET['id']));
			}
		}
			$args = array(
				'box'=>array(
					'title'=>__('Order Bump', 'checkout-mestres-wp'),
					'description'=>__('Create order bump promotions and increase your sales.', 'checkout-mestres-wp'),
					'button'=>array(
						'label'=>__('Create Offer', 'checkout-mestres-wp'),
						'url'=>'admin.php?page=cwmp_admin_vendas&type=vendas.order-bump-add',
					),
					'help'=>'https://docs.mestresdowp.com.br',
					'patch'=>'admin.php?page=cwmp_admin_vendas&type=vendas.order-bump-',
					'bd'=>array(
						'name'=> 'cwmp_order_bump',
						'lines'=> array(
							'0'=>array(
								'type'=>'bump',
								'value'=>'produto,bump',
							),
							'1'=>array(
								'type'=>'percent',
								'value'=>'valor',
							)
						),
						'order'=>array(
							'by'=>'DESC',
							'value'=>'valor'
						),
					)
				),
			);
			echo cwmpAdminCreateLists($args);

			$args = array(
				'box'=> array(
					'0'=> array(
						'title'=>__('Box', 'checkout-mestres-wp'),
						'description'=>__('Customize the Order Bump box.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'args'=>array(
							'0' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Background', 'checkout-mestres-wp'),
									'name'=>'cwmp_box_bump_background',
									'class'=>'coloris instance1',
									'info'=>__('Choose the box background color.', 'checkout-mestres-wp'),
								),
							),
							'1' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Primary Color', 'checkout-mestres-wp'),
									'name'=>'cwmp_box_bump_primary',
									'class'=>'coloris instance1',
									'info'=>__('Select the primary color.', 'checkout-mestres-wp'),
								),
							),
							'2' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Secundary Color', 'checkout-mestres-wp'),
									'name'=>'cwmp_box_bump_secundary',
									'class'=>'coloris instance1',
									'info'=>__('Select the secundary color.', 'checkout-mestres-wp'),
								),
							),
							'3' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Button Color', 'checkout-mestres-wp'),
									'name'=>'cwmp_box_bump_button_color',
									'class'=>'coloris instance1',
									'info'=>__('Select the button text color.', 'checkout-mestres-wp'),
								),
							),
						),
					),
				),
			);
			echo '<form method="post" action="options.php">';
			wp_nonce_field('update-options');
			echo cwmpAdminCreateForms($args);
			echo '</form>';
			include "config.copyright.php";
