			<?php
			$args = array(
				'box'=>array(
					'0'=> array(
						'title'=>__('Settings', 'checkout-mestres-wp'),
						'description'=>__('Add basic installment settings.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'args'=>array(
							'0' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('Minimum value', 'checkout-mestres-wp'),
									'name'=>'parcelas_mwp_valor_min',
									'info'=>__('Enter the minimum installment amount.', 'checkout-mestres-wp'),
									'step'=>'any',
								),
							),
							'1' =>array(
								'type'=>'select',
								'id'=>'parcelas_mwp_payment_second_parcels',
								'title'=>__('Number of Installments', 'checkout-mestres-wp'),
								'description'=>__('Choose the maximum number of installments.', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>'1',
										'value'=>'1',
									),
									'1'=>array(
										'label'=>'2',
										'value'=>'2',
									),
									'2'=>array(
										'label'=>'3',
										'value'=>'3',
									),
									'3'=>array(
										'label'=>'4',
										'value'=>'4',
									),
									'4'=>array(
										'label'=>'5',
										'value'=>'5',
									),
									'5'=>array(
										'label'=>'6',
										'value'=>'6',
									),
									'6'=>array(
										'label'=>'7',
										'value'=>'7',
									),
									'7'=>array(
										'label'=>'8',
										'value'=>'8',
									),
									'8'=>array(
										'label'=>'9',
										'value'=>'9',
									),
									'9'=>array(
										'label'=>'10',
										'value'=>'10',
									),
									'10'=>array(
										'label'=>'11',
										'value'=>'11',
									),
									'11'=>array(
										'label'=>'12',
										'value'=>'12',
									),
								),
							),
							'2' =>array(
								'type'=>'select',
								'id'=>'parcelas_mwp_payment_parcelas_sem_juros',
								'title'=>__('Number of interest-free installments', 'checkout-mestres-wp'),
								'description'=>__('Choose the number of interest-free installments.', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>'1',
										'value'=>'1',
									),
									'1'=>array(
										'label'=>'2',
										'value'=>'2',
									),
									'2'=>array(
										'label'=>'3',
										'value'=>'3',
									),
									'3'=>array(
										'label'=>'4',
										'value'=>'4',
									),
									'4'=>array(
										'label'=>'5',
										'value'=>'5',
									),
									'5'=>array(
										'label'=>'6',
										'value'=>'6',
									),
									'6'=>array(
										'label'=>'7',
										'value'=>'7',
									),
									'7'=>array(
										'label'=>'8',
										'value'=>'8',
									),
									'8'=>array(
										'label'=>'9',
										'value'=>'9',
									),
									'9'=>array(
										'label'=>'10',
										'value'=>'10',
									),
									'10'=>array(
										'label'=>'11',
										'value'=>'11',
									),
									'11'=>array(
										'label'=>'12',
										'value'=>'12',
									),
								),
							),

							'4' =>array(
								'type'=>'select',
								'id'=>'parcelas_mwp_type_tax',
								'title'=>__('Interest Rate Type', 'checkout-mestres-wp'),
								'description'=>__('Choose the interest rate type', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>__('Fixed', 'checkout-mestres-wp'),
										'value'=>'fixed',
									),
									'1'=>array(
										'label'=>'Variable',
										'value'=>'variable',
									),
								),
							),
							'5' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('Interest Rate', 'checkout-mestres-wp'),
									'name'=>'parcelas_mwp_juros',
									'info'=>__('Choose fixed interest rate', 'checkout-mestres-wp'),
									'step'=>'any',
									'class'=>'fixed_tax',
								),
							),
							'11' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('1 Installment', 'checkout-mestres-wp'),
									'name'=>'parcelas_mwp_juros_1_installment',
									'info'=>__('Choose variable interest rate', 'checkout-mestres-wp'),
									'step'=>'any',
									'class'=>'variable_tax',
								),
							),
							'12' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('2 Installment', 'checkout-mestres-wp'),
									'name'=>'parcelas_mwp_juros_2_installment',
									'info'=>__('Choose variable interest rate', 'checkout-mestres-wp'),
									'step'=>'any',
									'class'=>'variable_tax',
								),
							),
							'13' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('3 Installment', 'checkout-mestres-wp'),
									'name'=>'parcelas_mwp_juros_3_installment',
									'info'=>__('Choose variable interest rate', 'checkout-mestres-wp'),
									'step'=>'any',
									'class'=>'variable_tax',
								),
							),
							'14' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('4 Installment', 'checkout-mestres-wp'),
									'name'=>'parcelas_mwp_juros_4_installment',
									'info'=>__('Choose variable interest rate', 'checkout-mestres-wp'),
									'step'=>'any',
									'class'=>'variable_tax',
								),
							),
							'15' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('5 Installment', 'checkout-mestres-wp'),
									'name'=>'parcelas_mwp_juros_5_installment',
									'info'=>__('Choose variable interest rate', 'checkout-mestres-wp'),
									'step'=>'any',
									'class'=>'variable_tax',
								),
							),
							'16' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('6 Installment', 'checkout-mestres-wp'),
									'name'=>'parcelas_mwp_juros_6_installment',
									'info'=>__('Choose variable interest rate', 'checkout-mestres-wp'),
									'step'=>'any',
									'class'=>'variable_tax',
								),
							),
							'17' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('7 Installment', 'checkout-mestres-wp'),
									'name'=>'parcelas_mwp_juros_7_installment',
									'info'=>__('Choose variable interest rate', 'checkout-mestres-wp'),
									'step'=>'any',
									'class'=>'variable_tax',
								),
							),
							'18' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('8 Installment', 'checkout-mestres-wp'),
									'name'=>'parcelas_mwp_juros_8_installment',
									'info'=>__('Choose variable interest rate', 'checkout-mestres-wp'),
									'step'=>'any',
									'class'=>'variable_tax',
								),
							),
							'19' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('9 Installment', 'checkout-mestres-wp'),
									'name'=>'parcelas_mwp_juros_9_installment',
									'info'=>__('Choose variable interest rate', 'checkout-mestres-wp'),
									'step'=>'any',
									'class'=>'variable_tax',
								),
							),
							'20' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('10 Installment', 'checkout-mestres-wp'),
									'name'=>'parcelas_mwp_juros_10_installment',
									'info'=>__('Choose variable interest rate', 'checkout-mestres-wp'),
									'step'=>'any',
									'class'=>'variable_tax',
								),
							),
							'21' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('11 Installment', 'checkout-mestres-wp'),
									'name'=>'parcelas_mwp_juros_11_installment',
									'info'=>__('Choose variable interest rate', 'checkout-mestres-wp'),
									'step'=>'any',
									'class'=>'variable_tax',
								),
							),
							'22' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('12 Installment', 'checkout-mestres-wp'),
									'name'=>'parcelas_mwp_juros_12_installment',
									'info'=>__('Choose variable interest rate', 'checkout-mestres-wp'),
									'step'=>'any',
									'class'=>'variable_tax',
								),
							),

						),
					),
					'1'=> array(
						'title'=>__('Formats', 'checkout-mestres-wp'),
						'description'=>__('Define the format for how installments will be displayed.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'args'=>array(
							'0' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Text after price', 'checkout-mestres-wp'),
									'name'=>'parcelas_mwp_payment_second_pre',
									'info'=>__('Let us know how you would like the text format after the price.', 'checkout-mestres-wp'),
								),
							),
							'1' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Interest-free installment format', 'checkout-mestres-wp'),
									'name'=>'parcelas_mwp_payment_list_format_s_juros',
									'info'=>__('Enter how you would like the interest-free installment text to be displayed.', 'checkout-mestres-wp'),
								),
							),
							'2' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Interest installment format', 'checkout-mestres-wp'),
									'name'=>'parcelas_mwp_payment_list_format_c_juros',
									'info'=>__('Enter how you would like the interest-bearing installment text to be displayed.', 'checkout-mestres-wp'),
								),
							),
							'3' =>array(
								'type'=>'select',
								'id'=>'parcelas_mwp_price_catalog_align',
								'title'=>__('Align Catalog', 'checkout-mestres-wp'),
								'description'=>__('Choose the alignment of the items.', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>'Left',
										'value'=>'flex-start',
									),
									'1'=>array(
										'label'=>'Center',
										'value'=>'center',
									),
									'2'=>array(
										'label'=>'Right',
										'value'=>'flex-end',
									),
								),
							),
							'4' =>array(
								'type'=>'select',
								'id'=>'parcelas_mwp_price_product_align',
								'title'=>__('Align Product', 'checkout-mestres-wp'),
								'description'=>__('Choose the alignment of the items.', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>'Left',
										'value'=>'flex-start',
									),
									'1'=>array(
										'label'=>'Center',
										'value'=>'center',
									),
									'2'=>array(
										'label'=>'Right',
										'value'=>'flex-end',
									),
								),
							),
						),
					),
					'2'=> array(
						'title'=>__('Regular Price', 'checkout-mestres-wp'),
						'description'=>__('Sets the regular price style settings.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'args'=>array(
							'0' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('Size Text', 'checkout-mestres-wp'),
									'name'=>'parcelas_mwp_price_regular_size',
									'info'=>__('Choose the Size Text of the text.', 'checkout-mestres-wp'),
								),
							),
							'1' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('Font Weight', 'checkout-mestres-wp'),
									'name'=>'parcelas_mwp_price_regular_weight',
									'info'=>__('Choose the weight of the text.', 'checkout-mestres-wp'),
								),
							),
							'2' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Color Text', 'checkout-mestres-wp'),
									'name'=>'parcelas_mwp_price_regular_color',
									'class'=>'coloris instance1',
									'info'=>__('Choose the text color.', 'checkout-mestres-wp'),
								),
							),
							'3' =>array(
								'type'=>'select',
								'id'=>'parcelas_mwp_price_regular_align',
								'title'=>__('Align Items', 'checkout-mestres-wp'),
								'description'=>__('Choose the alignment of the items.', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>'Top',
										'value'=>'flex-start',
									),
									'1'=>array(
										'label'=>'Center',
										'value'=>'center',
									),
									'2'=>array(
										'label'=>'Baseline',
										'value'=>'flex-end',
									),
								),
							),
							
							'4' =>array(
								'type'=>'select',
								'id'=>'parcelas_mwp_price_regular_position',
								'title'=>__('Position', 'checkout-mestres-wp'),
								'description'=>__('Choose the position of the items.', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>'Left',
										'value'=>'row',
									),
									'1'=>array(
										'label'=>'Right',
										'value'=>'row-reverse',
									),
									'2'=>array(
										'label'=>'Top',
										'value'=>'column',
									),
								),
							),
							'5' =>array(
								'type'=>'select',
								'id'=>'parcelas_mwp_price_regular_decoration',
								'title'=>__('Decoration', 'checkout-mestres-wp'),
								'description'=>__('Choose the text decoration.', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>'None',
										'value'=>'none',
									),
									'1'=>array(
										'label'=>'Underline',
										'value'=>'underline',
									),
									'2'=>array(
										'label'=>'Overline',
										'value'=>'overline',
									),
									'3'=>array(
										'label'=>'Line-through',
										'value'=>'line-through',
									),
									'4'=>array(
										'label'=>'Blink',
										'value'=>'blink',
									),

								),
							),
						),
					),
					'3'=> array(
						'title'=>__('Sale Price', 'checkout-mestres-wp'),
						'description'=>__('Sets the promotional price style settings.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'args'=>array(
							'4' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('Size Text', 'checkout-mestres-wp'),
									'name'=>'parcelas_mwp_price_sale_size',
									'info'=>__('Choose the Size Text of the text.', 'checkout-mestres-wp'),
								),
							),
							'5' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('Font Weight', 'checkout-mestres-wp'),
									'name'=>'parcelas_mwp_price_sale_weight',
									'info'=>__('Choose the weight of the text.', 'checkout-mestres-wp'),
								),
							),

							'7' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Color Text', 'checkout-mestres-wp'),
									'name'=>'parcelas_mwp_price_sale_color',
									'class'=>'coloris instance1',
									'info'=>__('Choose the text color.', 'checkout-mestres-wp'),
								),
							),
						),
					),
					'4'=> array(
						'title'=>__('Additional Payment Methods', 'checkout-mestres-wp'),
						'description'=>__('Defines additional payment method style settings.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'args'=>array(
							'8' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('Size Text', 'checkout-mestres-wp'),
									'name'=>'parcelas_mwp_list_size_text',
									'info'=>__('Choose the Size Text of the text.', 'checkout-mestres-wp'),
								),
							),
							'9' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Color Text', 'checkout-mestres-wp'),
									'name'=>'parcelas_mwp_list_color_text',
									'class'=>'coloris instance1',
									'info'=>__('Choose the text color.', 'checkout-mestres-wp'),
								),
							),
						),
					),
					'5'=> array(
						'title'=>__('Installment Box', 'checkout-mestres-wp'),
						'description'=>__('Defines the installment simulator box style settings.', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'args'=>array(
							'11' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('Size Text', 'checkout-mestres-wp'),
									'name'=>'parcelas_mwp_box_size_text',
									'info'=>__('Choose the Size Text of the text.', 'checkout-mestres-wp'),
								),
							),
							'12' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Text Color', 'checkout-mestres-wp'),
									'name'=>'parcelas_mwp_box_color_text',
									'class'=>'coloris instance1',
									'info'=>__('Choose the text color.', 'checkout-mestres-wp'),
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
									'name'=>'cwmp_parcelas_css_personalizado',
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
