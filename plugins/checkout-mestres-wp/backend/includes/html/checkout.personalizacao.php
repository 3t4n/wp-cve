			<div class="mwp-box">
				<div class="col col-1">
					<h3><?php echo __( 'Pre-formatted templates', 'checkout-mestres-wp'); ?></h3>
					<p><?php echo __( 'Choose a theme pre-formatted by our team.', 'checkout-mestres-wp'); ?></p>
					<a href="#" class="action cwmp_template_pre"><?php echo __( 'Change template', 'checkout-mestres-wp'); ?></a>
				</div>
				<div class="col col-2">
					<strong><?php echo __( 'Template', 'checkout-mestres-wp'); ?></strong>
					<span><?php echo __( 'Choose your template', 'checkout-mestres-wp'); ?></span>
					<select name="cwmp-checkout-template-pre" id="cwmp-checkout-template-pre" class="input-150">
						<option value=""><?php echo __( 'Select', 'checkout-mestres-wp'); ?></option>
						<option value="padrao" <?php if(get_option('cwmp-checkout-template-pre')=="red"){ echo "selected"; } ?>><?php echo __( 'Padrão', 'checkout-mestres-wp'); ?></option>
						<option value="red" <?php if(get_option('cwmp-checkout-template-pre')=="red"){ echo "selected"; } ?>><?php echo __( 'Red', 'checkout-mestres-wp'); ?></option>
						<option value="blue" <?php if(get_option('cwmp-checkout-template-pre')=="blue"){ echo "selected"; } ?>><?php echo __( 'Blue', 'checkout-mestres-wp'); ?></option>
						<option value="green" <?php if(get_option('cwmp-checkout-template-pre')=="green"){ echo "selected"; } ?>><?php echo __( 'Green', 'checkout-mestres-wp'); ?></option>
					</select>
				</div>
			</div>
			<?php
			$args = array(
				'box'=> array(
					'0'=> array(
						'title'=>__( 'Colors', 'checkout-mestres-wp'),
						'description'=>__( 'Customize Checkout colors.', 'checkout-mestres-wp'),
						'formButton'=>__( 'Update', 'checkout-mestres-wp'),
						'args'=>array(
							'0' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Background', 'checkout-mestres-wp'),
									'name'=>'cwmp_checkout_background',
									'class'=>'coloris instance1',
									'info'=>__( 'Choose the background color of the Checkout page.', 'checkout-mestres-wp'),
								),
							),
							'1' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Box Background', 'checkout-mestres-wp'),
									'name'=>'cwmp_checkout_box_background',
									'class'=>'coloris instance1',
									'info'=>__( 'Choose the box background color.', 'checkout-mestres-wp'),
								),
							),
							'2' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Primary Color', 'checkout-mestres-wp'),
									'name'=>'cwmp_checkout_primary_color',
									'class'=>'coloris instance1',
									'info'=>__( 'Choose the Checkout primary color.', 'checkout-mestres-wp'),
								),
							),
							'3' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Secundary Color', 'checkout-mestres-wp'),
									'name'=>'cwmp_checkout_secundary_color',
									'class'=>'coloris instance1',
									'info'=>__( 'Choose the Checkout secondary color.', 'checkout-mestres-wp'),
								),
							),
							'4' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Secundary Color Contrast', 'checkout-mestres-wp'),
									'name'=>'cwmp_checkout_secundary_color_contrast',
									'class'=>'coloris instance1',
									'info'=>__( 'Choose the Checkout contrast color.', 'checkout-mestres-wp'),
								),
							),
							'5' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Input Background', 'checkout-mestres-wp'),
									'name'=>'cwmp_checkout_input_background',
									'class'=>'coloris instance1',
									'info'=>__( 'Choose the input background color.', 'checkout-mestres-wp'),
								),
							),
							'6' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Input Color', 'checkout-mestres-wp'),
									'name'=>'cwmp_checkout_input_color',
									'class'=>'coloris instance1',
									'info'=>__( 'Choose the color of the input text.', 'checkout-mestres-wp'),
								),
							),
							'7' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Input Background Hover', 'checkout-mestres-wp'),
									'name'=>'cwmp_checkout_input_hover_background',
									'class'=>'coloris instance1',
									'info'=>__( 'Choose the :hover background color of the input.', 'checkout-mestres-wp'),
								),
							),
							'8' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Input Color Hover', 'checkout-mestres-wp'),
									'name'=>'cwmp_checkout_input_hover_color',
									'class'=>'coloris instance1',
									'info'=>__( 'Choose the :hover color of the input text.', 'checkout-mestres-wp'),
								),
							),
							'9' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Button Background', 'checkout-mestres-wp'),
									'name'=>'cwmp_checkout_button_background',
									'class'=>'coloris instance1',
									'info'=>__( 'Choose the background color for the button.', 'checkout-mestres-wp'),
								),
							),
							'10' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Button Color', 'checkout-mestres-wp'),
									'name'=>'cwmp_checkout_button_color',
									'class'=>'coloris instance1',
									'info'=>__( 'Choose the color of the button text.', 'checkout-mestres-wp'),
								),
							),
							'11' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Button Background Hover', 'checkout-mestres-wp'),
									'name'=>'cwmp_checkout_button_hover_background',
									'class'=>'coloris instance1',
									'info'=>__( 'Choose the button`s hover background color.', 'checkout-mestres-wp'),
								),
							),
							'12' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Button Color Hover', 'checkout-mestres-wp'),
									'name'=>'cwmp_checkout_button_hover_color',
									'class'=>'coloris instance1',
									'info'=>__( 'Choose the button`s hover text color.', 'checkout-mestres-wp'),
								),
							),
							'13' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Success Background', 'checkout-mestres-wp'),
									'name'=>'cwmp_checkout_success_background',
									'class'=>'coloris instance1',
									'info'=>__( 'Choose the successful background color.', 'checkout-mestres-wp'),
								),
							),
							'14' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__( 'Success Color', 'checkout-mestres-wp'),
									'name'=>'cwmp_checkout_success_color',
									'class'=>'coloris instance1',
									'info'=>__( 'Choose the color of the success text.', 'checkout-mestres-wp'),
								),
							),
						),
					),

					'2'=> array(
						'title'=>__( 'Title Icons', 'checkout-mestres-wp'),
						'description'=>__( 'Choose Checkout title icons.', 'checkout-mestres-wp'),
						'args'=>array(
							'3' =>array(
								'type'=>'icon',
								'value'=>array(
									'label'=>__( 'Billing Icon', 'checkout-mestres-wp'),
									'name'=>'cwmp_checkout_box_icon_dados_pessoais',
									'info'=>__( 'Choose the billing icon.', 'checkout-mestres-wp'),
								),
							),
							'4' =>array(
								'type'=>'icon',
								'value'=>array(
									'label'=>__( 'Address Icon', 'checkout-mestres-wp'),
									'name'=>'cwmp_checkout_box_icon_entrega',
									'info'=>__( 'Choose the address icon.', 'checkout-mestres-wp'),
								),
							),
							'5' =>array(
								'type'=>'icon',
								'value'=>array(
									'label'=>__( 'Delivery Method Icon', 'checkout-mestres-wp'),
									'name'=>'cwmp_checkout_box_icon_frete',
									'info'=>__( 'Choose the delivery method icon.', 'checkout-mestres-wp'),
								),
							),
							'6' =>array(
								'type'=>'icon',
								'value'=>array(
									'label'=>__( 'Payment Method Icon', 'checkout-mestres-wp'),
									'name'=>'cwmp_checkout_box_icon_pagamento',
									'info'=>__( 'Choose the payment method icon.', 'checkout-mestres-wp'),
								),
							),
						),
					),
					'3'=> array(
						'title'=>__( 'Button Icons', 'checkout-mestres-wp'),
						'description'=>__( 'Choose the icons for the Checkout buttons.', 'checkout-mestres-wp'),
						'args'=>array(
							'4' =>array(
								'type'=>'icon',
								'value'=>array(
									'label'=>__( 'Billing Icon', 'checkout-mestres-wp'),
									'name'=>'cwmp_checkout_button_icon_dados_pessoais',
									'info'=>__( 'Choose the billing icon.', 'checkout-mestres-wp'),
								),
							),
							'5' =>array(
								'type'=>'icon',
								'value'=>array(
									'label'=>__( 'Address Icon', 'checkout-mestres-wp'),
									'name'=>'cwmp_checkout_button_icon_entrega',
									'info'=>__( 'Choose the address icon.', 'checkout-mestres-wp'),
								),
							),
							'6' =>array(
								'type'=>'icon',
								'value'=>array(
									'label'=>__( 'Delivery Method Icon', 'checkout-mestres-wp'),
									'name'=>'cwmp_checkout_button_icon_frete',
									'info'=>__( 'Choose the delivery method icon.', 'checkout-mestres-wp'),
								),
							),
							'7' =>array(
								'type'=>'icon',
								'value'=>array(
									'label'=>__( 'Payment Method Icon', 'checkout-mestres-wp'),
									'name'=>'cwmp_checkout_button_icon_pagamento',
									'info'=>__( 'Choose the payment method icon.', 'checkout-mestres-wp'),
								),
							),
						),
					),
					'4'=> array(
						'title'=>__( 'Custom CSS', 'checkout-mestres-wp'),
						'description'=>__( 'Add your custom css.', 'checkout-mestres-wp'),
						'args'=>array(
							'4' =>array(
								'type'=>'textarea',
								'value'=>array(
									'label'=>__( 'Custom CSS', 'checkout-mestres-wp'),
									'name'=>'cwmp_checkout_css_personalizado',
									'info'=>__( 'Add your custom css.', 'checkout-mestres-wp'),
								),
							),
						),
					),

				)
			);
			echo '<form method="post" action="options.php">';
			wp_nonce_field('update-options');
			echo cwmpAdminCreateForms($args);
			echo '</form>';
			include "config.copyright.php";
