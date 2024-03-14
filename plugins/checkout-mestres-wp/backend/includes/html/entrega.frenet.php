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
						'title'=>'Configurações',
						'description'=>'Informe as configurações da integração com o Melhor Envio',
						'help'=>'Informe as configurações da integração com o Facebook ADS',
						'args'=>array(

							'1' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>'Token',
									'name'=>'cwmo_format_token_frenet',
									'placeholder'=>'Informe o APP ID do Melhor Envio',
									'info'=>'Informe o APP ID do Melhor Envio',
								),
							),
							'5' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>'Formato Prazo de Entrega',
									'name'=>'cwmo_format_frenet',
									'placeholder'=>'Informe o Pixel ID',
									'info'=>'Informado o ID Pixel do seu Facebook.',
								),
							),
							'6' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>'Dias adicionais para entrega',
									'name'=>'cwmo_day_aditional_frenet',
									'placeholder'=>'Informe o Pixel ID',
									'info'=>'Informado o ID Pixel do seu Facebook.',
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
