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
						'title'=>__('Update your personalized transactional email', 'checkout-mestres-wp'),
						'description'=>__('Update your personalized transactional email for better communication with your customers.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'formButton'=>__('Update', 'checkout-mestres-wp'),
						'bd'=>'cwmp_template_emails',
						'action'=>'externo',
						'args'=>array(
							'0' =>array(
								'type'=>'select',
								'row'=>'metodo',
								'id'=>'metodo',
								'title'=>__('Payment Method', 'checkout-mestres-wp'),
								'description'=>__('Choose the payment method for the personalized transactional email.', 'checkout-mestres-wp'),
								'options'=>cwmpArrayPaymentMethods(),
							),
							'1' =>array(
								'type'=>'select',
								'id'=>'status',
								'row'=>'status',
								'title'=>__('Status', 'checkout-mestres-wp'),
								'description'=>__('Choose your custom transactional email status.', 'checkout-mestres-wp'),
								'options'=>cwmpArrayStatusCom(),
							),
							'2' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Subject', 'checkout-mestres-wp'),
									'name'=>'titulo',
									'row'=>'titulo',
									'info'=>__('Enter the title of the personalized transactional email.', 'checkout-mestres-wp'),
								),
							),
							'3' =>array(
								'type'=>'textarea',
								'value'=>array(
									'label'=>__('Body', 'checkout-mestres-wp'),
									'name'=>'conteudo',
									'row'=>'conteudo',
									'info'=>__('Enter the body of the content of the personal transactional email in HTML.', 'checkout-mestres-wp'),
								),
							),
						),
					),
				),
			);
			echo '<form method="post" action="admin.php?page=cwmp_admin_comunicacao&type=comunicacao.email&action=edit">';
			echo cwmpAdminCreateForms($args);
			echo '</form>';
			include "config.copyright.php";
