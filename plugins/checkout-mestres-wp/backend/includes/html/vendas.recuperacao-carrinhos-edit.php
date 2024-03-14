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
						'title'=>__( 'Update a cart recovery', 'checkout-mestres-wp'),
						'description'=>__( 'Recover abandoned carts by sending emails and messages via WhatsApp.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'formButton'=>__( 'Update', 'checkout-mestres-wp'),
						'bd'=>'cwmp_cart_abandoned_msg',
						'action'=>'externo',
						'args'=>array(
							'1' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__( 'Time', 'checkout-mestres-wp'),
									'name'=>'time',
									'row'=>'time',
									'info'=>__( 'Enter the abandoned cart recovery shipping time.', 'checkout-mestres-wp'),
								),
							),
							'2' =>array(
								'type'=>'select',
								'id'=>'time2',
								'row'=>'time2',
								'title'=>__( 'Period', 'checkout-mestres-wp'),
								'description'=>__( 'Enter the abandoned cart recovery shipping period.', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>__( 'Minutes', 'checkout-mestres-wp'),
										'value'=>'minutes',
									),
									'1'=>array(
										'label'=>__( 'Hours', 'checkout-mestres-wp'),
										'value'=>'hour',
									),
									'2'=>array(
										'label'=>__( 'Days', 'checkout-mestres-wp'),
										'value'=>'day',
									),

								),
							),
							'3' =>array(
								'type'=>'select',
								'id'=>'discount',
								'row'=>'discount',
								'title'=>__( 'Discount', 'checkout-mestres-wp'),
								'description'=>__( 'Do you want to automatically generate a discount coupon?', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>__( 'Yes', 'checkout-mestres-wp'),
										'value'=>'yes',
									),
									'1'=>array(
										'label'=>__( 'No', 'checkout-mestres-wp'),
										'value'=>'no',
									),

								),
							),
							'4' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__( 'Discount Value', 'checkout-mestres-wp'),
									'name'=>'discount_value',
									'row'=>'discount_value',
									'info'=>__( 'What is the discount amount? (In percentage)', 'checkout-mestres-wp'),
								),
							),
							'5' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__( 'Valid for how many days?', 'checkout-mestres-wp'),
									'name'=>'discount_time',
									'row'=>'discount_time',
									'info'=>__( 'For how many days will the discount coupon be valid?', 'checkout-mestres-wp'),
								),
							),
							'6' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Subject', 'checkout-mestres-wp'),
									'name'=>'titulo',
									'row'=>'titulo',
									'info'=>__( 'Enter the subject of the recovery email.', 'checkout-mestres-wp'),
								),
							),
							'7' =>array(
								'type'=>'textarea',
								'value'=>array(
									'label'=>__( 'E-mail Body', 'checkout-mestres-wp'),
									'name'=>'body',
									'row'=>'body',
									'info'=>__( 'Enter the body of the recovery email.', 'checkout-mestres-wp'),
								),
							),
							'8' =>array(
								'type'=>'textarea',
								'value'=>array(
									'label'=>__( 'Whatsapp Content', 'checkout-mestres-wp'),
									'name'=>'mensagem',
									'row'=>'mensagem',
									'info'=>__( 'Enter the content of the recovery message for WhatsApp.', 'checkout-mestres-wp'),
								),
							),
						),
					),
				),
			);
			echo '<form method="post" action="admin.php?page=cwmp_admin_vendas&type=vendas.recuperacao-carrinhos&action=edit">';
			echo cwmpAdminCreateForms($args);
			echo '</form>';
			include "config.copyright.php";
