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
					'title'=>__('Abandoned Carts', 'checkout-mestres-wp'),
					'description'=>__('See the list of abandoned carts in your store.', 'checkout-mestres-wp'),
					'help'=>'https://docs.mestresdowp.com.br',
					'patch'=>'admin.php?page=cwmp_admin_vendas&type=vendas.recuperacao-pagamento-',
					'bd'=>array(
						'name'=> 'cwmp_cart_abandoned',
						'lines'=> array(
							'0'=>array(
								'type'=>'text',
								'value'=>'nome',
							),
							'1'=>array(
								'type'=>'productsCart',
								'value'=>'email',
							),
							'2'=>array(
								'type'=>'emailL',
								'value'=>'email',
							),
							'3'=>array(
								'type'=>'whatsappL',
								'value'=>'phone',
							),
							'4'=>array(
								'type'=>'data',
								'value'=>'time',
							),

						),
						'order'=>array(
							'by'=>'DESC',
							'value'=>'time'
						),
					)
				),
			);
			echo cwmpAdminCreateListsCarts($args);
			include "config.copyright.php";
