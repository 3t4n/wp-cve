<?php

/**
*  ------------------------------------------------------------------------------------------------
*   ACF FIELDS CREATION
*  ------------------------------------------------------------------------------------------------
*/

if (function_exists('acf_add_local_field_group')) :

	acf_add_local_field_group(array(
		'key' => 'group_610466ed83c10',
		'title' => 'Opções WooCommerce WhatsApp Mensagens',
		'fields' => array(
			array(
				'key' => 'field_62c076ca71ae7',
				'label' => 'Configurações',
				'name' => '',
				'type' => 'tab',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'placement' => 'top',
				'endpoint' => 0,
			),
			array(
				'key' => 'field_6256ebf408121',
				'label' => 'Servidor da API',
				'name' => 'api_server',
				'type' => 'select',
				'instructions' => 'Escolha o servidor onde o plugin irá se comunicar para fazer o envio das mensagens',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '40',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'drope' => 'DW-API',
				),
				'default_value' => 'drope',
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'return_format' => 'value',
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_6256ec9c8c539',
				'label' => 'Número do gerente da loja (opcional)',
				'name' => 'wpp_administrador',
				'type' => 'text',
				'instructions' => 'O formato do número deve ser: código do país + DDD + telefone, ex.: 5599123456789',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '40',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'currency_simbol',
				'label' => 'Símbolo',
				'name' => 'currency_simbol',
				'type' => 'text',
				'instructions' => 'Símbolo da moeda',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '10',
					'class' => '',
					'id' => '',
				),
				'default_value' => 'R$',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_62e41c72be9c2',
				'label' => 'Debug',
				'name' => 'debug',
				'type' => 'select',
				'instructions' => 'Ativar debug',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '10',
					'class' => '',
					'id' => '',
				),
				'choices' => array(
					'sim' => 'Sim',
					'nao' => 'Não',
				),
				'default_value' => 'nao',
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'return_format' => 'value',
				'ajax' => 0,
				'placeholder' => '',
			),
			array(
				'key' => 'field_6256ecfd8c53b',
				'label' => 'Número que irá fazer os envios',
				'name' => 'wpp_drope_api_number',
				'type' => 'text',
				'instructions' => 'O formato do número deve ser: código do país + DDD + telefone, ex.: 5599123456789',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_6256ebf408121',
							'operator' => '==',
							'value' => 'drope',
						),
					),
				),
				'wrapper' => array(
					'width' => '50',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_6256ecba8c53a',
				'label' => 'Token',
				'name' => 'drope_api',
				'type' => 'text',
				'instructions' => 'Você pode obter o Token clicando <a href="https://painel.dw-api.com" target="_blank">aqui</a>',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_6256ebf408121',
							'operator' => '==',
							'value' => 'drope',
						),
					),
				),
				'wrapper' => array(
					'width' => '50',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
			array(
				'key' => 'field_41b965bz60zd6',
				'label' => 'Mensagens por status',
				'name' => '',
				'type' => 'tab',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'placement' => 'top',
				'endpoint' => 0,
			),
			array(
				'key' => 'field_6212c2af03ec0',
				'label' => '',
				'name' => '',
				'type' => 'message',
				'instructions' => '',
				'required' => 0,
				'message' => '
				<div class="woo-wpp-notificacoes-card" style="background:#e3e3e3; margin-top:-10px; max-width:100%;">
				Utilize os shortcodes abaixo para que o plugin substitua pelas informações correspondentes:<br><br>
							<b style="font-weight:800;">[PEDIDO]</b> = Número do pedido<br>
							<b style="font-weight:800;">[CLIENTE]</b> = Nome do cliente<br>
							<b style="font-weight:800;">[ENDERECO]</b> = Endereço de entrega<br>
							<b style="font-weight:800;">[PRODUTOS]</b> = Lista de produtos comprados + quantidade + valor total<br>
							<b style="font-weight:800;">[TOTAL_PEDIDO]</b> = Total do pedido<br>
							<b style="font-weight:800;">[TOTAL_FRETE]</b> = Total do frete<br>
							<b style="font-weight:800;">[METODO_PAGAMENTO]</b> = Nome do método de pagamento<br>
							<b style="font-weight:800;">[NOME_LOJA]</b> = Nome da loja
							<b style="font-weight:800;">[COTAS_RIFA]</b> = Números escolhidos na rifa. <i style="color:red;">Observação: este shortcode só deve ser utilizado caso você utilize o plugin <a href="https://dropestore.com/plugin-rifa" target="_blank">Rifa Drope</a>.</i><br><br>
							Se você não quer que em determinado status seja enviado uma mensagem para o WhatsApp do cliente ou do administrador, é só deixar o campo da mensagem em branco.<br><br>
							Para inserir uma quebra de linha (parágrafo) na sua mensagem, utilize a sintaxe <b style="font-weight:800;">\n</b> na sua mensagem.<br><br>
							Se você deseja inserir emojis em na sua mensagem, use <a href="https://fsymbols.com/pt/emoji/" target="_blank">este site</a> para copiá-los.
				</div>
				',
				'new_lines' => 'wpautop',
				'esc_html' => 0,
			),
			array(
				'key' => 'field_3104672575254',
				'label' => 'Mensagem padrão que será enviada para o cliente antes de qualquer outra',
				'name' => 'wpp_message',
				'type' => 'textarea',
				'instructions' => '',
				'required' => 0,
				'default_value' => 'Olá [CLIENTE], temos uma atualização sobre o seu pedido 😍',
				'wrapper' => array (
					'width' => '50',
				),
				'rows' => 4,
			),
			array(
				'key' => 'field_6104672575255',
				'label' => 'Mensagem padrão que será enviada para o administrador antes de qualquer outra',
				'name' => 'wpp_message_admin',
				'type' => 'textarea',
				'instructions' => '',
				'required' => 0,
				'default_value' => 'Houve uma atualização 🥳',
				'wrapper' => array (
					'width' => '50',
				),
				'rows' => 4,
			),
			array(
				'key' => 'field_6212c2e403ec1',
				'label' => 'Mensagem que será enviada para o cliente quando o status do pedido for Pendente ou Aguardando pagamento',
				'name' => 'mensagem_enviada_status_aguardando_pagamento',
				'type' => 'textarea',
				'instructions' => '',
				'required' => 0,
				'default_value' => 'Olá [CLIENTE] 🥰, acabamos de receber o seu pedido [PEDIDO] 🚀 e agora falta pouco para você concluir a sua compra.\nCaso tenha algum problema para efetuar seu pagamento, entre em contato com nosso suporte. 🙆🏽‍♂️',
				'wrapper' => array (
					'width' => '50',
				),
				'rows' => 4,
			),
			array(
				'key' => 'field_6212c2e403ea1',
				'label' => 'Mensagem que será enviada para o adminstrador quando o status do pedido for Pendente ou Aguardando pagamento',
				'name' => 'mensagem_enviada_status_aguardando_pagamento_admin',
				'type' => 'textarea',
				'instructions' => '',
				'required' => 0,
				'default_value' => '🔥 Novo pedido: [PEDIDO] no valor de [TOTAL_PEDIDO]. \nStatus: Pendente/Aguardando pagamento',
				'wrapper' => array (
					'width' => '50',
				),
				'rows' => 4,
			),
			array(
				'key' => 'field_6212c30803ec2',
				'label' => 'Mensagem que será enviada para o cliente quando o status do pedido for Malsucedido',
				'name' => 'mensagem_enviada_status_com_falha',
				'type' => 'textarea',
				'instructions' => '',
				'required' => 0,
				'default_value' => 'Olá [CLIENTE] 🥰, vimos aqui que houve uma falha no seu pagamento do pedido [PEDIDO], caso esteja com algum problema, sinta-se livre para entrar em contato com nosso suporte. 🙆🏽‍♂️',
				'wrapper' => array (
					'width' => '50',
				),
				'rows' => 4,
			),
			array(
				'key' => 'field_6212c30803ea2',
				'label' => 'Mensagem que será enviada para o administrador quando o status do pedido for Malsucedido',
				'name' => 'mensagem_enviada_status_com_falha_admin',
				'type' => 'textarea',
				'instructions' => '',
				'required' => 0,
				'default_value' => '❌ O pagamento do pedido [PEDIDO] no valor de [TOTAL_PEDIDO] foi malsucedido.',
				'wrapper' => array (
					'width' => '50',
				),
				'rows' => 4,
			),
			array(
				'key' => 'field_6212c31f03ec3',
				'label' => 'Mensagem que será enviada para o cliente quando o status do pedido for Processando',
				'name' => 'mensagem_enviada_status_processando',
				'type' => 'textarea',
				'instructions' => '',
				'required' => 0,
				'default_value' => 'Olá [CLIENTE] 🥰, recebemos o seu pedido [PEDIDO] e o mesmo agora está em separação.\n\nOs produtos da compra foram: [PRODUTOS] no valor total de [TOTAL_PEDIDO]. \n\nO pedido será entregue em [ENDERECO] \n\n*Obrigado pela compra!* ',
				'wrapper' => array (
					'width' => '50',
				),
				'rows' => 4,
			),
			array(
				'key' => 'field_6212c31f03ea3',
				'label' => 'Mensagem que será enviada para o administrador quando o status do pedido for Processando',
				'name' => 'mensagem_enviada_status_processando_admin',
				'type' => 'textarea',
				'instructions' => '',
				'required' => 0,
				'default_value' => '✅ O pedido [PEDIDO] no valor de [TOTAL_PEDIDO] foi pago.',
				'wrapper' => array (
					'width' => '50',
				),
				'rows' => 4,
			),
			array(
				'key' => 'field_6212c33603ec4',
				'label' => 'Mensagem que será enviada para o cliente quando o status do pedido for Concluído',
				'name' => 'mensagem_enviada_status_completo',
				'type' => 'textarea',
				'instructions' => '',
				'required' => 0,
				'default_value' => 'Olá [CLIENTE] 🥰, o pedido [PEDIDO] foi concluído 🚀.\n\nConta pra nós, o que achou da experiência de compra em nossa loja? Seu feedback é muito importante pra nós 😍',
				'wrapper' => array (
					'width' => '50',
				),
				'rows' => 4,
			),
			array(
				'key' => 'field_6212c33603ea4',
				'label' => 'Mensagem que será enviada para o administrador quando o status do pedido for Concluído',
				'name' => 'mensagem_enviada_status_completo_admin',
				'type' => 'textarea',
				'instructions' => '',
				'required' => 0,
				'default_value' => '✅ O pedido [PEDIDO] no valor de [TOTAL_PEDIDO] foi concluído.',
				'wrapper' => array (
					'width' => '50',
				),
				'rows' => 4,
			),
			array(
				'key' => 'field_6212c34503ec5',
				'label' => 'Mensagem que será enviada para o cliente quando o status do pedido for Reembolsado',
				'name' => 'mensagem_enviada_status_estornado',
				'type' => 'textarea',
				'instructions' => '',
				'required' => 0,
				'default_value' => 'Olá [CLIENTE] 🥰, o pedido [PEDIDO] foi reembolsado.\n\nSentimos muito que algo de errado tenha acontecido com seu pedido.',
				'wrapper' => array (
					'width' => '50',
				),
				'rows' => 4,
			),
			array(
				'key' => 'field_6212c34503ea5',
				'label' => 'Mensagem que será enviada para o administrador quando o status do pedido for Reembolsado',
				'name' => 'mensagem_enviada_status_estornado_admin',
				'type' => 'textarea',
				'instructions' => '',
				'required' => 0,
				'default_value' => '⛔ O pedido [PEDIDO] no valor de [TOTAL_PEDIDO] foi reembolsado.',
				'wrapper' => array (
					'width' => '50',
				),
				'rows' => 4,
			),
			array(
				'key' => 'field_6212c35103ec6',
				'label' => 'Mensagem que será enviada para o cliente quando o status do pedido for Cancelado',
				'name' => 'mensagem_enviada_status_cancelado',
				'type' => 'textarea',
				'instructions' => '',
				'required' => 0,
				'default_value' => 'Olá [CLIENTE] 🥰, o pedido [PEDIDO] foi cancelado.\n\nSe você acha que isso é um erro, entre em contato com nosso suporte.',
				'wrapper' => array (
					'width' => '50',
				),
				'rows' => 4,
			),
			array(
				'key' => 'field_6212c35103ea6',
				'label' => 'Mensagem que será enviada para o administrador quando o status do pedido for Cancelado',
				'name' => 'mensagem_enviada_status_cancelado_admin',
				'type' => 'textarea',
				'instructions' => '',
				'required' => 0,
				'default_value' => '⛔ O pedido [PEDIDO] no valor de [TOTAL_PEDIDO] foi cancelado.',
				'wrapper' => array (
					'width' => '50',
				),
				'rows' => 4,
			),
			array(
				'key' => 'recuperacao',
				'label' => 'Recuperação de vendas',
				'name' => '',
				'type' => 'tab',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'placement' => 'top',
				'endpoint' => 0,
			),
			array(
				'key' => 'field_recuperacao',
				'label' => '',
				'name' => '',
				'type' => 'message',
				'instructions' => '',
				'required' => 0,
				'message' => '
				<div class="woo-wpp-notificacoes-card" style="margin-top:-10px; max-width:100%;">
					<h3>Opção indisponível</h3>
					<p>Atualize agora mesmo para versão PRO e tenha acesso a:</p>
					<li>Recuperação de vendas</li>
					<li>Suporte a múltiplos dispositivos</li>
					<li>Suporte a textos dinâmicos</li>
					<li>Integração com formulários do Elementor PRO</li>
					<li>Integração com plugin WooCommerce Correios</li>
					<li>Integração com plugin Dokan</li>
					<li>Integração com Hotmart</li>
					<li>Integração com Braip</li>
					Clique <a href="https://bit.ly/3tRyXfj" target="_blank">aqui</a> e faça upgrade agora mesmo!<br>
					Tenha acesso a milhares de Plugins e Temas Premium por apenas R$39,90/mês, clique <a href="https://bit.ly/3Q0bL6P" target="_blank">aqui</a> e saiba mais.
				</div>
				',
				'new_lines' => 'wpautop',
				'esc_html' => 0,
			),
			array(
				'key' => 'hotmart',
				'label' => 'Hotmart',
				'name' => '',
				'type' => 'tab',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'placement' => 'top',
				'endpoint' => 0,
			),
			array(
				'key' => 'field_hotmart',
				'label' => '',
				'name' => '',
				'type' => 'message',
				'instructions' => '',
				'required' => 0,
				'message' => '
				<div class="woo-wpp-notificacoes-card" style="margin-top:-10px; max-width:100%;">
					<h3>Opção indisponível</h3>
					<p>Atualize agora mesmo para versão PRO e tenha acesso a:</p>
					<li>Recuperação de vendas</li>
					<li>Suporte a múltiplos dispositivos</li>
					<li>Suporte a textos dinâmicos</li>
					<li>Integração com formulários do Elementor PRO</li>
					<li>Integração com plugin WooCommerce Correios</li>
					<li>Integração com plugin Dokan</li>
					<li>Integração com Hotmart</li>
					<li>Integração com Braip</li>
					Clique <a href="https://bit.ly/3tRyXfj" target="_blank">aqui</a> e faça upgrade agora mesmo!<br>
					Tenha acesso a milhares de Plugins e Temas Premium por apenas R$39,90/mês, clique <a href="https://bit.ly/3Q0bL6P" target="_blank">aqui</a> e saiba mais.
				</div>
				',
				'new_lines' => 'wpautop',
				'esc_html' => 0,
			),
			array(
				'key' => 'braip',
				'label' => 'Braip',
				'name' => '',
				'type' => 'tab',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'placement' => 'top',
				'endpoint' => 0,
			),
			array(
				'key' => 'field_braip',
				'label' => '',
				'name' => '',
				'type' => 'message',
				'instructions' => '',
				'required' => 0,
				'message' => '
				<div class="woo-wpp-notificacoes-card" style="margin-top:-10px; max-width:100%;">
					<h3>Opção indisponível</h3>
					<p>Atualize agora mesmo para versão PRO e tenha acesso a:</p>
					<li>Recuperação de vendas</li>
					<li>Suporte a múltiplos dispositivos</li>
					<li>Suporte a textos dinâmicos</li>
					<li>Integração com formulários do Elementor PRO</li>
					<li>Integração com plugin WooCommerce Correios</li>
					<li>Integração com plugin Dokan</li>
					<li>Integração com Hotmart</li>
					<li>Integração com Braip</li>
					Clique <a href="https://bit.ly/3tRyXfj" target="_blank">aqui</a> e faça upgrade agora mesmo!<br>
					Tenha acesso a milhares de Plugins e Temas Premium por apenas R$39,90/mês, clique <a href="https://bit.ly/3Q0bL6P" target="_blank">aqui</a> e saiba mais.
				</div>
				',
				'new_lines' => 'wpautop',
				'esc_html' => 0,
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'post',
					'operator' => '==',
					'value' => '9991274',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'acf_after_title',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
	));

endif;