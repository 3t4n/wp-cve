		
		<?php
			$args = array(
				'box'=>array(
					'0'=> array(
						'title'=>__('Payment Refused', 'checkout-mestres-wp'),
						'description'=>__('Set custom page pays declined payments.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'args'=>array(
							'0' =>array(
								'type'=>'select',
								'id'=>'cwmp_thankyou_page_selected_failed',
								'title'=>__('Page', 'checkout-mestres-wp'),
								'description'=>__('It is mandatory to choose the custom page for declined payments.', 'checkout-mestres-wp'),
								'options'=>cwmpArrayPages(),
							),
						),
					),
					'1'=> array(
						'title'=>__('No gateway', 'checkout-mestres-wp'),
						'description'=>__('Choose the page for payments without a gateway.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'args'=>array(
							'0' =>array(
								'type'=>'select',
								'id'=>'cwmp_thankyou_page_selected_zero',
								'title'=>__('Page', 'checkout-mestres-wp'),
								'description'=>__('It is mandatory to choose the personalized thank you page for payments without a gateway.', 'checkout-mestres-wp'),
								'options'=>cwmpArrayPages(),
							),
						),
					),
					'2'=> array(
						'typeThankyou'=>cwmpArrayPaymentMethods(),
					),
				),
			);
			echo '<form method="post" action="options.php">';
			wp_nonce_field('update-options');
			echo cwmpAdminCreateForms($args);
			echo '</form>';
			include "config.copyright.php";
