			<?php
			$args = array(
				'box'=>array(
					'1'=> array(
						'title'=>__( 'Smart Login', 'checkout-mestres-wp'),
						'description'=>__( 'Create the email template for the Smart Login.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'args'=>array(
							'0' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Subject', 'checkout-mestres-wp'),
									'name'=>'cwmp_remember_password_subject',
									'info'=>__( 'Enter the subject of the email.', 'checkout-mestres-wp'),
								),
							),
							'1' =>array(
								'type'=>'textarea',
								'value'=>array(
									'label'=>__( 'Body', 'checkout-mestres-wp'),
									'name'=>'cwmp_remember_password_body',
									'info'=>__( 'Enter the body of the email in HTML.', 'checkout-mestres-wp'),
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
