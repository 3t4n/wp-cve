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
			if(isset($_GET['action'])){
				if($_GET['action']=="remove"){
					update_option('cwmo_format_token_melhorenvio_bearer','');
				}
			}
			$args = array(
				'box'=>array(
					'0'=> array(
						'title'=>__( 'Settings', 'checkout-mestres-wp'),
						'description'=>__( 'Enter the integration settings with the Melhor Envio.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br/melhor-envio/',
						'args'=>array(
							'0' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Client ID', 'checkout-mestres-wp'),
									'name'=>'cwmo_format_appid_melhorenvio',
									'info'=>__( 'Enter the Client ID of the Melhor Envio', 'checkout-mestres-wp'),
								),
							),
							'1' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Secret', 'checkout-mestres-wp'),
									'name'=>'cwmo_format_token_melhorenvio',
									'info'=>__( 'Enter the Secret of the Melhor Envio', 'checkout-mestres-wp'),
								),
							),
							'2' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'E-mail do Suporte Técnico', 'checkout-mestres-wp'),
									'name'=>'cwmo_format_email_melhorenvio',
									'info'=>__( 'Enter the Token of the Melhor Envio', 'checkout-mestres-wp'),
								),
							),
							'3' =>array(
								'type'=>'select',
								'id'=>'cwmo_seguro_melhorenvio',
								'title'=>__( 'Seguro', 'checkout-mestres-wp'),
								'description'=>__( 'Let us know if you wish to use the self-delivery service.', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>__( 'No', 'checkout-mestres-wp'),
										'value'=>'true',
									),
									'1'=>array(
										'label'=>__( 'Yes', 'checkout-mestres-wp'),
										'value'=>'false',
									),
								),
							),
							'4' =>array(
								'type'=>'select',
								'id'=>'cwmo_mao_propria_melhorenvio',
								'title'=>__( 'Own Hand', 'checkout-mestres-wp'),
								'description'=>__( 'Let us know if you wish to use the self-delivery service.', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>__( 'No', 'checkout-mestres-wp'),
										'value'=>'false',
									),
									'1'=>array(
										'label'=>__( 'Yes', 'checkout-mestres-wp'),
										'value'=>'true',
									),
								),
							),
							'5' =>array(
								'type'=>'select',
								'id'=>'cwmo_aviso_recebimento_melhorenvio',
								'title'=>__( 'Acknowledgment of Receipt', 'checkout-mestres-wp'),
								'description'=>__( 'Let us know if you want to use the acknowledgment of receipt service.', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>__( 'No', 'checkout-mestres-wp'),
										'value'=>'false',
									),
									'1'=>array(
										'label'=>__( 'Yes', 'checkout-mestres-wp'),
										'value'=>'true',
									),
								),
							),
						),
					),
					'1'=> array(
						'title'=>__( 'Default Values', 'checkout-mestres-wp'),
						'description'=>__( 'Choose the default values ​​for your orders if the chosen product does not have this information.', 'checkout-mestres-wp'),
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
			
			if(get_option('cwmo_format_token_melhorenvio_bearer') == "" AND get_option("cwmo_format_appid_melhorenvio")){
				$args['box'][0]['button']['label'] = __( 'Authorize application', 'checkout-mestres-wp');
				$args['box'][0]['button']['url'] = CWMP_BASE_URL_MELHORENVIO."oauth/authorize?client_id=".get_option("cwmo_format_appid_melhorenvio")."&redirect_uri=".CWMP_PLUGIN_URL."authentication/melhorenvio/&response_type=code&scope=cart-read cart-write companies-read companies-write coupons-read coupons-write notifications-read orders-read products-read products-write purchases-read shipping-calculate shipping-cancel shipping-checkout shipping-companies shipping-generate shipping-preview shipping-print shipping-share shipping-tracking ecommerce-shipping transactions-read users-read users-write";
			}
			if(get_option('cwmo_format_token_melhorenvio_bearer')!="" AND get_option("cwmo_format_appid_melhorenvio")!=""){
				$args['box'][0]['button']['label'] = __( 'Remove application', 'checkout-mestres-wp');
				$args['box'][0]['button']['url'] = $_SERVER['REQUEST_URI']."&action=remove";
			}
			echo '<form method="post" action="options.php">';
			wp_nonce_field('update-options');
			echo cwmpAdminCreateForms($args);
			echo '</form>';
			include "config.copyright.php";
			
