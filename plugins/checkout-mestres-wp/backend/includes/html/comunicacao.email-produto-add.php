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
						'title'=>__('Create your email by product', 'checkout-mestres-wp'),
						'description'=>__('Create an email specified by product and send it to your customers with information according to the order status.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'action'=>'externo',
						'formButton'=>__('Register', 'checkout-mestres-wp'),
						'args'=>array(
							'0' =>array(
								'type'=>'select',
								'id'=>'cwmp_template_email_payment',
								'title'=>__('Product', 'checkout-mestres-wp'),
								'description'=>__('Choose the product you want to send a specific email to.', 'checkout-mestres-wp'),
								'options'=>cwmpArrayProducts(),
							),
							'1' =>array(
								'type'=>'select',
								'id'=>'cwmp_template_email_status',
								'title'=>__('Status', 'checkout-mestres-wp'),
								'description'=>__('Choose the status for sending the email.', 'checkout-mestres-wp'),
								'options'=>cwmpArrayStatus(),
							),
							'2' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__( 'Time', 'checkout-mestres-wp'),
									'name'=>'time',
									'info'=>__( 'Enter the abandoned cart recovery shipping time.', 'checkout-mestres-wp'),
								),
							),
							'3' =>array(
								'type'=>'select',
								'id'=>'time2',
								'title'=>__( 'Period', 'checkout-mestres-wp'),
								'description'=>__( 'Enter the abandoned cart recovery shipping period.', 'checkout-mestres-wp'),
								'options'=>array(
									'2'=>array(
										'label'=>__( 'Days', 'checkout-mestres-wp'),
										'value'=>'day',
									),

								),
							),
							'4' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Subject', 'checkout-mestres-wp'),
									'name'=>'cwmp_template_email_title',
									'info'=>__('Enter the subject of the email.', 'checkout-mestres-wp'),
								),
							),
							'5' =>array(
								'type'=>'textarea',
								'value'=>array(
									'label'=>__('Body', 'checkout-mestres-wp'),
									'name'=>'cwmp_template_email_conteudo',
									'info'=>__('Insert the body of the email in html.', 'checkout-mestres-wp'),
								),
							),
							'6' =>array(
								'type'=>'textarea',
								'value'=>array(
									'label'=>__('Message', 'checkout-mestres-wp'),
									'name'=>'msg',
									'info'=>__('Insert the body of the email in html.', 'checkout-mestres-wp'),
								),
							),
						),
					),
				),
			);
			echo '<form method="post" action="admin.php?page=cwmp_admin_comunicacao&type=comunicacao.email-produto&action=add">';
			echo cwmpAdminCreateForms($args);
			echo '</form>';
			include "config.copyright.php";
