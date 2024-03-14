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
				'box'=> array(
					'0'=> array(
						'title'=>__('Button Simulator', 'checkout-mestres-wp'),
						'description'=>__('Customize the Shipping Simulator Button.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'args'=>array(
							'0' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Button Background', 'checkout-mestres-wp'),
									'name'=>'cwmp_simulador_frete_button_background',
									'class'=>'coloris instance1',
									'info'=>__('Choose the background color.', 'checkout-mestres-wp'),
								),
							),
							'1' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Color Text', 'checkout-mestres-wp'),
									'name'=>'cwmp_simulador_frete_button_color',
									'class'=>'coloris instance1',
									'info'=>__('Choose the text color.', 'checkout-mestres-wp'),
								),
							),
							'2' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('Font Weight', 'checkout-mestres-wp'),
									'name'=>'cwmp_simulador_frete_button_weight',
									'info'=>__('Choose the weight of the text.', 'checkout-mestres-wp'),
								),
							),
							'3' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('Size Text', 'checkout-mestres-wp'),
									'name'=>'cwmp_simulador_frete_button_size',
									'info'=>__('Choose the weight of the text.', 'checkout-mestres-wp'),
								),
							),
							'4' =>array(
								'type'=>'select',
								'id'=>'cwmp_simulador_frete_button_transform',
								'title'=>__('Transform', 'checkout-mestres-wp'),
								'description'=>__('Choose the text transformation.', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>__('Uppercase', 'checkout-mestres-wp'),
										'value'=>'uppercase',
									),
									'1'=>array(
										'label'=>__('Lowercase', 'checkout-mestres-wp'),
										'value'=>'lowercase',
									),
									'2'=>array(
										'label'=>__('Capitalize', 'checkout-mestres-wp'),
										'value'=>'capitalize',
									),
								),
							),
						),
					),
					'1'=> array(
						'title'=>__('Input Simulator', 'checkout-mestres-wp'),
						'description'=>__('Customize the Shipping Simulator Input.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'args'=>array(
							'0' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Input Background', 'checkout-mestres-wp'),
									'name'=>'cwmp_simulador_frete_input_background',
									'class'=>'coloris instance1',
									'info'=>__('Choose the background color.', 'checkout-mestres-wp'),
								),
							),
							'1' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Color Text', 'checkout-mestres-wp'),
									'name'=>'cwmp_simulador_frete_input_color',
									'class'=>'coloris instance1',
									'info'=>__('Choose the text color.', 'checkout-mestres-wp'),
								),
							),
							'2' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('Font Weight', 'checkout-mestres-wp'),
									'name'=>'cwmp_simulador_frete_input_weight',
									'info'=>__('Choose the weight of the text.', 'checkout-mestres-wp'),
								),
							),
							'3' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('Size Text', 'checkout-mestres-wp'),
									'name'=>'cwmp_simulador_frete_input_size',
									'info'=>__('Choose the weight of the text.', 'checkout-mestres-wp'),
								),
							),
							'4' =>array(
								'type'=>'select',
								'id'=>'cwmp_simulador_frete_input_transform',
								'title'=>__('Transform', 'checkout-mestres-wp'),
								'description'=>__('Choose the text transformation.', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>__('Uppercase', 'checkout-mestres-wp'),
										'value'=>'uppercase',
									),
									'1'=>array(
										'label'=>__('Lowercase', 'checkout-mestres-wp'),
										'value'=>'lowercase',
									),
									'2'=>array(
										'label'=>__('Capitalize', 'checkout-mestres-wp'),
										'value'=>'capitalize',
									),
								),
							),
						),
					),
					'2'=> array(
						'title'=>__('Result Simulator', 'checkout-mestres-wp'),
						'description'=>__('Customize the Shipping Simulator Result.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'args'=>array(
							'0' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Background Odd', 'checkout-mestres-wp'),
									'name'=>'cwmp_simulador_frete_result_odd',
									'class'=>'coloris instance1',
									'info'=>__('Choose the background color.', 'checkout-mestres-wp'),
								),
							),
							'1' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Background Even', 'checkout-mestres-wp'),
									'name'=>'cwmp_simulador_frete_result_even',
									'class'=>'coloris instance1',
									'info'=>__('Choose the background color.', 'checkout-mestres-wp'),
								),
							),
						),
					),
					'3'=> array(
						'title'=>__('Carrier', 'checkout-mestres-wp'),
						'description'=>__('Customize the carrier name in the freight simulator result.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'args'=>array(
							'2' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Color Text', 'checkout-mestres-wp'),
									'name'=>'cwmp_simulador_frete_carrier_text_color',
									'class'=>'coloris instance1',
									'info'=>__('Choose the text color.', 'checkout-mestres-wp'),
								),
							),
							'3' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('Font Weight', 'checkout-mestres-wp'),
									'name'=>'cwmp_simulador_frete_carrier_text_weight',
									'info'=>__('Choose the weight of the text.', 'checkout-mestres-wp'),
								),
							),
							'4' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('Size Text', 'checkout-mestres-wp'),
									'name'=>'cwmp_simulador_frete_carrier_text_size',
									'info'=>__('Choose the weight of the text.', 'checkout-mestres-wp'),
								),
							),
							'5' =>array(
								'type'=>'select',
								'id'=>'cwmp_simulador_frete_carrier_text_transform',
								'title'=>__('Transform', 'checkout-mestres-wp'),
								'description'=>__('Choose the text transformation.', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>__('Uppercase', 'checkout-mestres-wp'),
										'value'=>'uppercase',
									),
									'1'=>array(
										'label'=>__('Lowercase', 'checkout-mestres-wp'),
										'value'=>'lowercase',
									),
									'2'=>array(
										'label'=>__('Capitalize', 'checkout-mestres-wp'),
										'value'=>'capitalize',
									),
								),
							),
						),
					),
					'4'=> array(
						'title'=>__('Price', 'checkout-mestres-wp'),
						'description'=>__('Customize shipping price.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'args'=>array(
							'6' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Color Text', 'checkout-mestres-wp'),
									'name'=>'cwmp_simulador_frete_carrier_price_text_color',
									'class'=>'coloris instance1',
									'info'=>__('Choose the text color.', 'checkout-mestres-wp'),
								),
							),
							'7' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('Font Weight', 'checkout-mestres-wp'),
									'name'=>'cwmp_simulador_frete_carrier_price_text_weight',
									'info'=>__('Choose the weight of the text.', 'checkout-mestres-wp'),
								),
							),
							'8' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('Size Text', 'checkout-mestres-wp'),
									'name'=>'cwmp_simulador_frete_carrier_price_text_size',
									'info'=>__('Choose the weight of the text.', 'checkout-mestres-wp'),
								),
							),
							'9' =>array(
								'type'=>'select',
								'id'=>'cwmp_simulador_frete_carrier_price_text_transform',
								'title'=>__('Transform', 'checkout-mestres-wp'),
								'description'=>__('Choose the text transformation.', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>__('Uppercase', 'checkout-mestres-wp'),
										'value'=>'uppercase',
									),
									'1'=>array(
										'label'=>__('Lowercase', 'checkout-mestres-wp'),
										'value'=>'lowercase',
									),
									'2'=>array(
										'label'=>__('Capitalize', 'checkout-mestres-wp'),
										'value'=>'capitalize',
									),
								),
							),
						),
					),
					'5'=> array(
						'title'=>__('Term', 'checkout-mestres-wp'),
						'description'=>__('Customize the shipping period.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'args'=>array(
							'10' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Color Text', 'checkout-mestres-wp'),
									'name'=>'cwmp_simulador_frete_carrier_prazo_text_color',
									'class'=>'coloris instance1',
									'info'=>__('Choose the text color.', 'checkout-mestres-wp'),
								),
							),
							'11' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('Font Weight', 'checkout-mestres-wp'),
									'name'=>'cwmp_simulador_frete_carrier_prazo_text_weight',
									'info'=>__('Choose the weight of the text.', 'checkout-mestres-wp'),
								),
							),
							'12' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('Size Text', 'checkout-mestres-wp'),
									'name'=>'cwmp_simulador_frete_carrier_prazo_text_size',
									'info'=>__('Choose the weight of the text.', 'checkout-mestres-wp'),
								),
							),
							'13' =>array(
								'type'=>'select',
								'id'=>'cwmp_simulador_frete_carrier_prazo_text_transform',
								'title'=>__('Transform', 'checkout-mestres-wp'),
								'description'=>__('Choose the text transformation.', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>__('Uppercase', 'checkout-mestres-wp'),
										'value'=>'uppercase',
									),
									'1'=>array(
										'label'=>__('Lowercase', 'checkout-mestres-wp'),
										'value'=>'lowercase',
									),
									'2'=>array(
										'label'=>__('Capitalize', 'checkout-mestres-wp'),
										'value'=>'capitalize',
									),
								),
							),
						),
					),
					'6'=> array(
						'title'=>__( 'Custom CSS', 'checkout-mestres-wp'),
						'description'=>__( 'Add your custom css.', 'checkout-mestres-wp'),
						'args'=>array(
							'4' =>array(
								'type'=>'textarea',
								'value'=>array(
									'label'=>__( 'Custom CSS', 'checkout-mestres-wp'),
									'name'=>'cwmp_frete_css_personalizado',
									'info'=>__( 'Add your custom css.', 'checkout-mestres-wp'),
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
