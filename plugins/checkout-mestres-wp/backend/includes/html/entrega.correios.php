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
						'title'=>__( 'Settings', 'checkout-mestres-wp'),
						'description'=>__( 'Enter the integration settings with the Post Office.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br/correios/',
						'args'=>array(
							'0' =>array(
								'type'=>'select',
								'id'=>'cwmo_contrato_correios',
								'title'=>__( 'Contract', 'checkout-mestres-wp'),
								'description'=>__( 'Inform your contract with the Post Office.', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>'Balcão',
										'value'=>'BLC',
									),
									'1'=>array(
										'label'=>'Clube Correios',
										'value'=>'CLU1',
									),
									'2'=>array(
										'label'=>'Bronze 2',
										'value'=>'BRO2',
									),
									'3'=>array(
										'label'=>'Prata 2',
										'value'=>'RAT2',
									),
									'4'=>array(
										'label'=>'Ouro 4',
										'value'=>'OUR4',
									),
									'5'=>array(
										'label'=>'Diamante 1S',
										'value'=>'DIA1S',
									),
									'6'=>array(
										'label'=>'Diamante 2S',
										'value'=>'DIA2S',
									),
									'7'=>array(
										'label'=>'Diamante 3S',
										'value'=>'DIA3S',
									),
									'8'=>array(
										'label'=>'Diamante 4S',
										'value'=>'DIA4S',
									),
									'9'=>array(
										'label'=>'Diamante 1',
										'value'=>'DIA1',
									),
									'10'=>array(
										'label'=>'Diamante 2',
										'value'=>'DIA2',
									),
									'11'=>array(
										'label'=>'Diamante 3',
										'value'=>'DIA3',
									),
									'12'=>array(
										'label'=>'Diamante 4',
										'value'=>'DIA4',
									),
									'13'=>array(
										'label'=>'Infinity 1',
										'value'=>'INF1',
									),
									'14'=>array(
										'label'=>'Infinity 2',
										'value'=>'INF2',
									),
									'15'=>array(
										'label'=>'Infinity 3',
										'value'=>'INF3',
									),
									'16'=>array(
										'label'=>'Infinity 4',
										'value'=>'INF4',
									),
									'17'=>array(
										'label'=>'Infinity 5',
										'value'=>'INF5',
									),
								),
							),
							'2' =>array(
								'type'=>'select',
								'id'=>'cwmo_mao_propria_correios',
								'title'=>__( 'Own Hand', 'checkout-mestres-wp'),
								'description'=>__( 'Let us know if you wish to use the self-delivery service.', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>__( 'No', 'checkout-mestres-wp'),
										'value'=>'N',
									),
									'1'=>array(
										'label'=>__( 'Yes', 'checkout-mestres-wp'),
										'value'=>'S',
									),
								),
							),
							'3' =>array(
								'type'=>'select',
								'id'=>'cwmo_aviso_recebimento_correios',
								'title'=>__( 'Acknowledgment of Receipt', 'checkout-mestres-wp'),
								'description'=>__( 'Let us know if you want to use the acknowledgment of receipt service.', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>__( 'No', 'checkout-mestres-wp'),
										'value'=>'N',
									),
									'1'=>array(
										'label'=>__( 'Yes', 'checkout-mestres-wp'),
										'value'=>'S',
									),
								),
							),
							'4' =>array(
								'type'=>'select',
								'id'=>'cwmo_valor_declarado_correios',
								'title'=>__( 'Declared value', 'checkout-mestres-wp'),
								'description'=>__( 'Inform if you wish to use the declared value service.', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>__( 'No', 'checkout-mestres-wp'),
										'value'=>'N',
									),
									'1'=>array(
										'label'=>__( 'Yes', 'checkout-mestres-wp'),
										'value'=>'S',
									),
								),
							),

						),
					),
					'1'=> array(
						'title'=>__( 'Valores Padrões', 'checkout-mestres-wp'),
						'description'=>__( 'Enter the integration settings with the Melhor Envio.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br/valores-padrao/',
						'args'=>array(
							'0' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Width', 'checkout-mestres-wp'),
									'name'=>'cwmo_shipping_padrao_width',
									'info'=>__( 'Set the default order width.', 'checkout-mestres-wp'),
								),
							),
							'1' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Height', 'checkout-mestres-wp'),
									'name'=>'cwmo_shipping_padrao_height',
									'info'=>__( 'Set the default order height.', 'checkout-mestres-wp'),
								),
							),
							'2' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Length', 'checkout-mestres-wp'),
									'name'=>'cwmo_shipping_padrao_length',
									'info'=>__( 'Set the default order length.', 'checkout-mestres-wp'),
								),
							),
							'3' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Weight', 'checkout-mestres-wp'),
									'name'=>'cwmo_shipping_padrao_weight',
									'info'=>__( 'Set the default order weight.', 'checkout-mestres-wp'),
								),
							),
							'5' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Delivery Time Format', 'checkout-mestres-wp'),
									'name'=>'cwmo_format_correios',
									'info'=>__( 'Enter the format for displaying the delivery time. (Example: Expected delivery in {{prazo}}', 'checkout-mestres-wp'),
								),
							),
							'6' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__( 'Additional days for delivery', 'checkout-mestres-wp'),
									'name'=>'cwmo_day_aditional_correios',
									'info'=>__( 'Enter the days to be added to the delivery time.', 'checkout-mestres-wp'),
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
