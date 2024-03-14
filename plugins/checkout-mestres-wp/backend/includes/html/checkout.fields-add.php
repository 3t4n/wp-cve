			<?php
				$menu_nonce = wp_create_nonce('menu_nonce');
    if (isset($_GET['page']) && $_GET['page'] === 'cwmp_admin_menu') {
        if (isset($_GET['_wpnonce']) && wp_verify_nonce(sanitize_key($_GET['_wpnonce']), 'menu_nonce')) {
            echo '<div class="notice notice-success is-dismissible"><p>Ação realizada com sucesso!</p></div>';
        } else {
            echo '<div class="notice notice-error is-dismissible"><p>Erro: Nonce inválido ou ausente.</p></div>';
            return;
        }
    }
			$args = array(
				'box'=>array(
					'0'=> array(
						'title'=>__('Fields', 'checkout-mestres-wp'),
						'description'=>__('Create a custom field for your checkout', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'formButton'=>__('Register', 'checkout-mestres-wp'),
						'action'=>'externo',
						'args'=>array(
							'0' =>array(
								'type'=>'select',
								'id'=>'type',
								'title'=>__( 'Type', 'checkout-mestres-wp'),
								'description'=>__( 'Select the field type', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>__( 'Text', 'checkout-mestres-wp'),
										'value'=>'text',
									),
									'1'=>array(
										'label'=>__( 'Select', 'checkout-mestres-wp'),
										'value'=>'select',
									),
								),
							),
							'1' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Name', 'checkout-mestres-wp'),
									'name'=>'name',
									'info'=>__('Fill in the field name attribute', 'checkout-mestres-wp'),
								),
							),
							'2' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Label', 'checkout-mestres-wp'),
									'name'=>'label',
									'info'=>__('Fill in the field`s label attribute', 'checkout-mestres-wp'),
								),
							),
							'3' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Placeholder', 'checkout-mestres-wp'),
									'name'=>'placeholder',
									'info'=>__('Fill in the field`s placeholder attribute', 'checkout-mestres-wp'),
								),
							),
							'4' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Default Value', 'checkout-mestres-wp'),
									'name'=>'default_value',
									'info'=>__('Fill in the default value for the field', 'checkout-mestres-wp'),
								),
							),
							'5' =>array(
								'type'=>'select',
								'id'=>'after',
								'title'=>__( 'After', 'checkout-mestres-wp'),
								'description'=>__( 'Choose field placement', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>__( 'Field Name', 'checkout-mestres-wp'),
										'value'=>'field_name',
									),
									'1'=>array(
										'label'=>__( 'Field Phone', 'checkout-mestres-wp'),
										'value'=>'field_phone',
									),
									'2'=>array(
										'label'=>__( 'Field CellPhone', 'checkout-mestres-wp'),
										'value'=>'field_cellphone',
									),
									'3'=>array(
										'label'=>__( 'Field Email', 'checkout-mestres-wp'),
										'value'=>'field_email',
									),
									'4'=>array(
										'label'=>__( 'Field Gender', 'checkout-mestres-wp'),
										'value'=>'field_gender',
									),
									'5'=>array(
										'label'=>__( 'Field Birthdate', 'checkout-mestres-wp'),
										'value'=>'field_birthdate',
									),
									'6'=>array(
										'label'=>__( 'Field Person Type', 'checkout-mestres-wp'),
										'value'=>'field_persontype',
									),
									'7'=>array(
										'label'=>__( 'Field CPF', 'checkout-mestres-wp'),
										'value'=>'field_cpf',
									),
									'8'=>array(
										'label'=>__( 'Field RG', 'checkout-mestres-wp'),
										'value'=>'field_rg',
									),
									'9'=>array(
										'label'=>__( 'Field CNPJ', 'checkout-mestres-wp'),
										'value'=>'field_cnpj',
									),
									'10'=>array(
										'label'=>__( 'Field IE', 'checkout-mestres-wp'),
										'value'=>'field_ie',
									),
								),
							),
							'6' =>array(
								'type'=>'select',
								'id'=>'required',
								'title'=>__( 'Required', 'checkout-mestres-wp'),
								'description'=>__( 'Choose whether the field is mandatory or not', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array(
										'label'=>__( 'No', 'checkout-mestres-wp'),
										'value'=>'N',
									),
									'1'=>array(
										'label'=>__( 'Yes', 'checkout-mestres-wp'),
										'value'=>'S',
									),
								),
							),
						),
					),
				),
			);
			
			echo '<form method="post" action="admin.php?page=cwmp_admin_checkout&type=checkout.fields&action=add">';
			echo cwmpAdminCreateForms($args);
			echo '</form>';
			include "config.copyright.php";
