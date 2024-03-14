		<?php
			$args = array(
				'box'=>array(
					'0'=> array(
						'title'=>__('Update your promotional email', 'checkout-mestres-wp'),
						'description'=>__('Create your promotional email and send it to your customers right now.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'formButton'=>__('Atualizar', 'checkout-mestres-wp'),
						'bd'=>'cwmp_newsletter_campanha',
						'action'=>'externo',
						'args'=>array(

							'1' =>array(
								'type'=>'datetime',
								'value'=>array(
									'label'=>__('Send date', 'checkout-mestres-wp'),
									'name'=>'cwmp_newsletter_date',
									'row'=>'data',
									'info'=>__('Choose the sending date for your promotional email.', 'checkout-mestres-wp'),
								),
							),
							'2' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Subject', 'checkout-mestres-wp'),
									'name'=>'cwmp_newsletter_campanha',
									'row'=>'campanha',
									'info'=>__('Enter the subject of your promotional email.', 'checkout-mestres-wp'),
								),
							),
							'4' =>array(
								'type'=>'textarea',
								'value'=>array(
									'label'=>__('Body', 'checkout-mestres-wp'),
									'name'=>'cwmp_newsletter_conteudo',
									'row'=>'conteudo',
									'info'=>__('Enter the body of the promotional email content in HTML.', 'checkout-mestres-wp'),
								),
							),
						),
					),
				),
			);
			echo '<form method="post" action="admin.php?page=cwmp_admin_comunicacao&type=comunicacao.newsletter&action=edit">';
			echo cwmpAdminCreateForms($args);
			echo '</form>';
			include "config.copyright.php";
