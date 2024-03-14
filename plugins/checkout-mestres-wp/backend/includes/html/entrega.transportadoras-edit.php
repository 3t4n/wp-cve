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
						'title'=>__('Update a carrier', 'checkout-mestres-wp'),
						'description'=>__('Create a carrier to send tracking links to your customers.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'formButton'=>__('Update', 'checkout-mestres-wp'),
						'bd'=>'cwmp_transportadoras',
						'action'=>'externo',
						'args'=>array(
							'0' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Carrier', 'checkout-mestres-wp'),
									'name'=>'transportadora',
									'row'=>'transportadora',
									'info'=>__('Enter the name of the carrier.', 'checkout-mestres-wp'),
								),
							),
							'1' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Link', 'checkout-mestres-wp'),
									'name'=>'estrutura',
									'row'=>'estrutura',
									'info'=>__('Enter your custom tracking link.', 'checkout-mestres-wp'),
								),
							),
							'2' =>array(
								'type'=>'select',
								'id'=>'relation_shipping',
								'row'=>'relation_shipping',
								'title'=>__( 'Related Delivery Method', 'checkout-mestres-wp'),
								'description'=>__( 'Link the Delivery Method with the Carrier.', 'checkout-mestres-wp'),
								'options'=>cwmpArrayShippingMethod(),
							),
						),
					),
				),
			);
			echo '<form method="post" action="admin.php?page=cwmp_admin_entrega&type=entrega.transportadoras&action=edit">';
			echo cwmpAdminCreateForms($args);
			echo '</form>';
			include "config.copyright.php";
