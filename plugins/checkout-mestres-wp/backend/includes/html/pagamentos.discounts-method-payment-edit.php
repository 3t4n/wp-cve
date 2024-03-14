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
						'title'=>__('Update Discounts', 'checkout-mestres-wp'),
						'description'=>__('Create discounts to increase your sales', 'checkout-mestres-wp'),
						'help'=>'https://docs.mestresdowp.com.br',
						'formButton'=>__('Update', 'checkout-mestres-wp'),
						'bd'=>'cwmp_discounts',
						'action'=>'externo',
						'args'=>array(

							'0' =>array(
								'type'=>'select',
								'id'=>'tipo',
								'row'=>'tipo',
								'class'=>'tipoDiscount',
								'title'=>__('Type', 'checkout-mestres-wp'),
								'description'=>__('Choose the type of discount', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array('label'=>__('By Payment Method', 'checkout-mestres-wp'),'value'=>'1'),
									'1'=>array('label'=>__('By Delivery Method', 'checkout-mestres-wp'),'value'=>'2'),
									'2'=>array('label'=>__('By Product Quantity', 'checkout-mestres-wp'),'value'=>'3'),
									'3'=>array('label'=>__('By Cart Value', 'checkout-mestres-wp'),'value'=>'4'),
									'4'=>array('label'=>__('By Category', 'checkout-mestres-wp'),'value'=>'5'),
								),
							),
							'1' =>array(
								'type'=>'select',
								'id'=>'metodoPayment',
								'row'=>'metodo',
								'class'=>'metodoPayment',
								'title'=>__('Payment Method', 'checkout-mestres-wp'),
								'description'=>__('Choose the form of payment.', 'checkout-mestres-wp'),
								'options'=>cwmpArrayPaymentMethods(),
							),
							'2' =>array(
								'type'=>'select',
								'id'=>'metodoShipping',
								'row'=>'metodo',
								'class'=>'metodoShipping',
								'title'=>__('Shipping Method', 'checkout-mestres-wp'),
								'description'=>__('Choose delivery method', 'checkout-mestres-wp'),
								'options'=>cwmpArrayShippingMethod(),
							),
							'3' =>array(
								'type'=>'select',
								'id'=>'product',
								'row'=>'metodo',
								'class'=>'product',
								'title'=>__('Product', 'checkout-mestres-wp'),
								'description'=>__('Choose the product to unlock the discount', 'checkout-mestres-wp'),
								'options'=>cwmpArrayProducts(),
							),

							'4' =>array(
								'type'=>'select',
								'id'=>'category',
								'row'=>'category',
								'class'=>'category',
								'title'=>__('Category', 'checkout-mestres-wp'),
								'description'=>__('Choose the category to release the discount', 'checkout-mestres-wp'),
								'options'=>cwmpGetCategories(),
							),
							'5' =>array(
								'type'=>'text',
								'value'=>array(
									'label'=>__('Label', 'checkout-mestres-wp'),
									'name'=>'label',
									'row'=>'label',
									'class'=>'label',
									'info'=>__('Enter the discount label', 'checkout-mestres-wp'),
								),
							),
							'6' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('Discount', 'checkout-mestres-wp'),
									'name'=>'discoutValue',
									'row'=>'discoutValue',
									'class'=>'discoutValue',
									'info'=>__('What is the value of the discount?', 'checkout-mestres-wp'),
								),
							),
							'7' =>array(
								'type'=>'select',
								'id'=>'discoutType',
								'row'=>'discoutType',
								'class'=>'discoutType',
								'title'=>__('Discount Type', 'checkout-mestres-wp'),
								'description'=>__('Choose whether you want a fixed or percentage discount', 'checkout-mestres-wp'),
								'options'=>array(
									'0'=>array('label'=>'Percentage','value'=>'percent'),
									'1'=>array('label'=>'Fixed','value'=>'fixed'),
								),
							),
							
							'8' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('Value', 'checkout-mestres-wp'),
									'name'=>'valueMax',
									'row'=>'valueMax',
									'class'=>'valueMax',
									'info'=>__('Choose the minimum cart value to receive the discount', 'checkout-mestres-wp'),
								),
							),
							'9' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('Min', 'checkout-mestres-wp'),
									'name'=>'minQtd',
									'row'=>'minQtd',
									'class'=>'minQtd',
									'info'=>__('Enter the minimum quantity', 'checkout-mestres-wp'),
								),
							),
							'10' =>array(
								'type'=>'number',
								'value'=>array(
									'label'=>__('Max', 'checkout-mestres-wp'),
									'name'=>'maxQtd',
									'row'=>'maxQtd',
									'class'=>'maxQtd',
									'info'=>__('Enter the maximum quantity', 'checkout-mestres-wp'),
								),
							),
							
							
							
							
						),
					),
				),
			);
			echo '<form method="post" action="admin.php?page=cwmp_admin_parcelamento&type=pagamentos.discounts-method-payment&action=edit">';
			echo cwmpAdminCreateForms($args);
			echo '</form>';
			include "config.copyright.php";