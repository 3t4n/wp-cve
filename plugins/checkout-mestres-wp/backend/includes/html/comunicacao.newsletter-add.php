			<?php
			$args = array(
				'box'=>array(
					'0'=> array(
						'title'=>__('Create your promotional email', 'checkout-mestres-wp'),
						'description'=>__('Create your promotional email and send it to your customers right now.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'formButton'=>__('Register', 'checkout-mestres-wp'),
						'action'=>'externo',
						'args'=>array(
							'0' =>array(
								'type'=>'checkbox',
								'id'=>'cwmp_template_email_status',
								'title'=>__('Status', 'checkout-mestres-wp'),
								'description'=>__('Choose the order statuses you want to select your customers.', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>__('Abandoned Carts', 'checkout-mestres-wp'),
										'value'=>'cwmp_newsletter_lista_carrinhos_abandonados',
									),
									'1'=>array(
										'label'=>__('Concluded', 'checkout-mestres-wp'),
										'value'=>'cwmp_newsletter_lista_pedidos_concluidos',
									),
									'2'=>array(
										'label'=>__('Processing', 'checkout-mestres-wp'),
										'value'=>'cwmp_newsletter_lista_pedidos_processando',
									),
									'3'=>array(
										'label'=>__('Pending Payment', 'checkout-mestres-wp'),
										'value'=>'cwmp_newsletter_lista_pedidos_aguardando_pagamento',
									),
									'4'=>array(
										'label'=>__('On-hold', 'checkout-mestres-wp'),
										'value'=>'cwmp_newsletter_lista_pedidos_aguardando',
									),
									'5'=>array(
										'label'=>__('Cancelled', 'checkout-mestres-wp'),
										'value'=>'cwmp_newsletter_lista_pedidos_cancelled',
									),
									'6'=>array(
										'label'=>__('Refused', 'checkout-mestres-wp'),
										'value'=>'cwmp_newsletter_lista_pedidos_failed',
									),
								),
							),
							'1' =>array(
								'type'=>'datetime',
								'value'=>array(
									'label'=>__('Send date', 'checkout-mestres-wp'),
									'name'=>'cwmp_newsletter_date',
									'info'=>__('Choose the sending date for your promotional email.', 'checkout-mestres-wp'),
								),
							),
							'2' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Subject', 'checkout-mestres-wp'),
									'name'=>'cwmp_newsletter_campanha',
									'info'=>__('Enter the subject of your promotional email.', 'checkout-mestres-wp'),
								),
							),
							'4' =>array(
								'type'=>'textarea',
								'value'=>array(
									'label'=>__('Body', 'checkout-mestres-wp'),
									'name'=>'cwmp_newsletter_conteudo',
									'info'=>__('Enter the body of the promotional email content in HTML.', 'checkout-mestres-wp'),
								),
							),
						),
					),
				),
			);
			echo '<form method="post" action="admin.php?page=cwmp_admin_comunicacao&type=comunicacao.newsletter&action=add">';
			echo cwmpAdminCreateForms($args);
			echo '</form>';
			include "config.copyright.php";
