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
						'title'=>__( 'Connection', 'checkout-mestres-wp'),
						'description'=>__( 'Configure the connection data with the Whatsapp API you use.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'args'=>array(
							'0' =>array(
								'type'=>'select',
								'id'=>'cwmp_template_whatsapp_type',
								'title'=>__( 'Plan', 'checkout-mestres-wp'),
								'description'=>__( 'Let us know which plan you have contracted for.', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>__( 'Select', 'checkout-mestres-wp'),
										'value'=>'0',
									),
									'1'=>array(
										'label'=>__( 'Whatstotal Sender', 'checkout-mestres-wp'),
										'value'=>'1',
									),
									'2'=>array(
										'label'=>__( 'Whatstotal Business', 'checkout-mestres-wp'),
										'value'=>'2',
									),
								),
							),
							'1' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Endpoint', 'checkout-mestres-wp'),
									'name'=>'cwmp_key_endpoint_wpp',
									'info'=>__( 'Enter your WhatsApp API endpoint.', 'checkout-mestres-wp'),
								),
							),
						),
					),
					'1'=> array(
						'title'=>__( 'Settings', 'checkout-mestres-wp'),
						'description'=>__( 'Basic settings for sending transactional messages via Whatsapp.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'args'=>array(
							'0' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__( 'Country', 'checkout-mestres-wp'),
									'name'=>'cwmp_whatsapp_ddi',
									'info'=>__( 'Enter the DDI of the country in which your store sells.', 'checkout-mestres-wp'),
								),
							),
						),
					),
					'2'=> array(
						'title'=>__( 'Notify Shopkeeper', 'checkout-mestres-wp'),
						'description'=>__( 'The retailer can be notified via Whatsapp about orders according to their status.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'args'=>array(
							'0' =>array(
								'type'=>'select',
								'id'=>'cwmp_template_whatsapp_notify_lojista',
								'title'=>__( 'Notify Shopkeeper', 'checkout-mestres-wp'),
								'description'=>__( 'Let us know if you want to be notified via Whatsapp about orders from your store.', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>__( 'Yes', 'checkout-mestres-wp'),
										'value'=>'1',
									),
									'1'=>array(
										'label'=>__( 'No', 'checkout-mestres-wp'),
										'value'=>'0',
									),
								),
							),
							'1' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Numbers', 'checkout-mestres-wp'),
									'name'=>'cwmp_whatsapp_number_lojista',
									'info'=>__( 'Enter the numbers you want to be notified by Whatsapp, if you want to be notified by more than one number, separate them with commas. (example: 5511999999999,5511999999999)', 'checkout-mestres-wp'),
								),
							),
							'2' =>array(
								'type'=>'select',
								'id'=>'cwmp_template_whatsapp_status_active',
								'title'=>__( 'Status for notification', 'checkout-mestres-wp'),
								'description'=>__( 'Choose which status you would like to be notified about an order.', 'checkout-mestres-wp'),
								'options'=>cwmpArrayStatus(),
							),
							'3' =>array(
								'type'=>'textarea',
								'value'=>array(
									'label'=>__( 'Message', 'checkout-mestres-wp'),
									'name'=>'cwmp_whatsapp_template_lojista',
									'info'=>__( 'Enter the model that you would like to receive notifications for retailers via Whatsapp.', 'checkout-mestres-wp'),
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
