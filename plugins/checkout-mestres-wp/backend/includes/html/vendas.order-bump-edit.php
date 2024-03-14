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
					'0'=> array(
						'title'=>__('Update Order Bump', 'checkout-mestres-wp'),
						'description'=>__('Create offers according to the products desired by your customers.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'formButton'=>__('Update', 'checkout-mestres-wp'),
						'bd'=>'cwmp_order_bump',
						'action'=>'externo',
						'args'=>array(
							'0' =>array(
								'type'=>'select',
								'id'=>'produto',
								'row'=>'produto',
								'title'=>__('Product', 'checkout-mestres-wp'),
								'description'=>__('Choose the product.', 'checkout-mestres-wp'),
								'options'=>cwmpArrayProducts(),
							),
							'1' =>array(
								'type'=>'select',
								'id'=>'bump',
								'row'=>'bump',
								'title'=>__('Product Offer', 'checkout-mestres-wp'),
								'description'=>__('Choose the product you want to offer a discount on.', 'checkout-mestres-wp'),
								'options'=>cwmpArrayProducts(),
							),
							'2' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Kitten', 'checkout-mestres-wp'),
									'name'=>'chamada',
									'row'=>'chamada',
									'info'=>__('Set the offer title.', 'checkout-mestres-wp'),
								),
							),
							'3' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('Discount', 'checkout-mestres-wp'),
									'name'=>'valor',
									'row'=>'valor',
									'info'=>__('Set the percentage of the discount amount.', 'checkout-mestres-wp'),
								),
							),
						),
					),
				),
			);
			echo '<form method="post" action="admin.php?page=cwmp_admin_comunicacao&type=vendas.order-bump&action=edit">';
			echo cwmpAdminCreateForms($args);
			echo '</form>';
			include "config.copyright.php";
