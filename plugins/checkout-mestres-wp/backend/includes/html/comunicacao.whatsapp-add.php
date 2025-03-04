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
			for ($i = 1; $i <= 10; $i++) {
				$returnSeq[$i]['label']=$i;
				$returnSeq[$i]['value']=$i;
			}
			$i=0;
			$wc_gateways = new WC_Payment_Gateways();
			$payment_gateways = $wc_gateways->payment_gateways();
			foreach( $payment_gateways as $gateway_id => $gateway ){
				if($gateway->enabled=="yes"){
					$returnMethodPayment[$i]['label']=$gateway->title;
					$returnMethodPayment[$i]['value']=$gateway->id;
					$i++;
				}
			}
			$args = array(
				'box'=>array(
					'0'=> array(
						'title'=>__('Settings', 'checkout-mestres-wp'),
						'description'=>__('Create your personalized transactional message to better communicate with your customers.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'formButton'=>__('Register', 'checkout-mestres-wp'),
						'action'=>'externo',
						'args'=>array(
							'0' =>array(
								'type'=>'multiple',
								'id'=>'metodo',
								'title'=>__('Payment Method', 'checkout-mestres-wp'),
								'description'=>__('Choose the payment method for the personalized transactional message.', 'checkout-mestres-wp'),
								'options'=>cwmpArrayPaymentMethods(),
							),
							'1' =>array(
								'type'=>'select',
								'id'=>'status',
								'title'=>__('Status', 'checkout-mestres-wp'),
								'description'=>__('Choose your custom transactional message status.', 'checkout-mestres-wp'),
								'options'=>cwmpArrayStatusCom(),
							),
							'2' =>array(
								'type'=>'select',
								'id'=>'seq',
								'title'=>__('Sequence', 'checkout-mestres-wp'),
								'description'=>__('Choose the sequential order of the transactional message.', 'checkout-mestres-wp'),
								'options'=>$returnSeq,
							),
							'3' =>array(
								'type'=>'textarea',
								'value'=>array(
									'label'=>__('Message', 'checkout-mestres-wp'),
									'name'=>'conteudo',
									'info'=>__('Enter the body of the transactional message.', 'checkout-mestres-wp'),
								),
							),
							'4' =>array(
								'type'=>'textarea',
								'value'=>array(
									'label'=>__('Webhook', 'checkout-mestres-wp'),
									'name'=>'webhook',
									'info'=>__('Enter the body of the transactional message.', 'checkout-mestres-wp'),
								),
							),
						),
					),
				),
			);
			
			echo '<form method="post" action="admin.php?page=cwmp_admin_comunicacao&type=comunicacao.whatsapp&action=add">';
			echo cwmpAdminCreateForms($args);
			echo '</form>';
			include "config.copyright.php";
