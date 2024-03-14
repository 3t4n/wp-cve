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
			$args = array(
				'box'=>array(
					'title'=>__('Pending Orders', 'checkout-mestres-wp'),
					'description'=>__('See the list of unpaid orders in your store.', 'checkout-mestres-wp'),
					'help'=>'https://docs.mestresdowp.com.br',
					'action'=>array(
						'value'=>'start'
					),
					'orders'=>array(
						'status'=> 'pending,on-hold',
						'lines'=> array(
							'0'=>array(
								'type'=>'createDate',
								'value'=>'id',
							),
						),
						'order'=>array(
							'by'=>'ASC',
							'value'=>'status'
						),

					)
				),
			);
			echo cwmpAdminCreateListsOrders($args);
			include "config.copyright.php";

