			<?php
			if(get_option('cwmp_license_cwmwp_active')==true){
				$args = array(
					'box'=>array(
						'1'=>array(
							'title'=>__( 'Checkout', 'checkout-mestres-wp'),
							'description'=>__( 'Enable WP Masters Checkout', 'checkout-mestres-wp'),
							'button'=>array(
								'id'=>'activate_checkout',
							),
						),
						'250'=>array(
							'title'=>__( 'Login/Register', 'checkout-mestres-wp'),
							'description'=>__( 'Enable mandatory login and registration at Checkout.', 'checkout-mestres-wp'),
							'button'=>array(
								'id'=>'activate_login',
							),
						),
						'2'=>array(
							'title'=>__( 'Thank You Page', 'checkout-mestres-wp'),
							'description'=>__( 'Enable to use personalized thank you pages', 'checkout-mestres-wp'),
							'button'=>array(
								'id'=>'activate_thankyou_page',
							),
						),
						'3'=>array(
							'title'=>__( 'Cart Recovery', 'checkout-mestres-wp'),
							'description'=>__( 'Enable to use Abandoned Cart Recovery', 'checkout-mestres-wp'),
							'button'=>array(
								'id'=>'activate_cart',
							),
						),
						'4'=>array(
							'title'=>__( 'Payment Recovery', 'checkout-mestres-wp'),
							'description'=>__( 'Enable to use Pending Payment Recovery', 'checkout-mestres-wp'),
							'button'=>array(
								'id'=>'activate_recupera_pgto',
							),
						),
						'5'=>array(
							'title'=>__( 'Order Bump', 'checkout-mestres-wp'),
							'description'=>__( 'Enable to use Order Bump', 'checkout-mestres-wp'),
							'button'=>array(
								'id'=>'activate_order_bump',
							),
						),
						'6'=>array(
							'title'=>__( 'Installment Simulator', 'checkout-mestres-wp'),
							'description'=>__( 'Enable to use the Installment Simulator', 'checkout-mestres-wp'),
							'button'=>array(
								'id'=>'pmwp_active',
							),
						),
						'7'=>array(
							'title'=>__( 'Discounts', 'checkout-mestres-wp'),
							'description'=>__( 'Enable to use Discounts', 'checkout-mestres-wp'),
							'button'=>array(
								'id'=>'activate_desconto_metodo_pagamento',
							),
						),

						'9'=>array(
							'title'=>__( 'E-mails', 'checkout-mestres-wp'),
							'description'=>__( 'Enable to use personalized emails', 'checkout-mestres-wp'),
							'button'=>array(
								'id'=>'activate_emails',
							),
						),
						'10'=>array(
							'title'=>__( 'Whatsapp', 'checkout-mestres-wp'),
							'description'=>__( 'Enable to use Whatsapp transactional messages', 'checkout-mestres-wp'),
							'button'=>array(
								'id'=>'activate_whatsapp',
							),
						),
						'11'=>array(
							'title'=>__( 'Traffic', 'checkout-mestres-wp'),
							'description'=>__( 'Enable to use integration with Facebook Ads, Google Ads, Google Analytics, etc.', 'checkout-mestres-wp'),
							'button'=>array(
								'id'=>'activate_traffic',
							),
						),
						'15'=>array(
							'title'=>__( 'Skip Cart', 'checkout-mestres-wp'),
							'description'=>__( 'Enable to jump from Shopping Cart to Checkout', 'checkout-mestres-wp'),
							'button'=>array(
								'id'=>'ignore_cart',
							),
						),
						'12'=>array(
							'title'=>__( 'Hide Country', 'checkout-mestres-wp'),
							'description'=>__( 'Enable to hide the Country field at Checkout', 'checkout-mestres-wp'),
							'button'=>array(
								'id'=>'field_country',
							),
						),
						'90'=>array(
							'title'=>__( 'Remove Address', 'checkout-mestres-wp'),
							'description'=>__( 'Enable to remove Checkout Address fields', 'checkout-mestres-wp'),
							'button'=>array(
								'id'=>'view_active_address',
							),
						),
						'100'=>array(
							'title'=>__( 'Endereço Automático', 'checkout-mestres-wp'),
							'description'=>__( 'Enable to remove Checkout Address fields', 'checkout-mestres-wp'),
							'button'=>array(
								'id'=>'view_active_address_auto',
							),
						),
						'14'=>array(
							'title'=>__( 'Debug', 'checkout-mestres-wp'),
							'description'=>__( 'Enable to display errors at Checkout', 'checkout-mestres-wp'),
							'button'=>array(
								'id'=>'view_error',
							),
						),
					),
				);
			}else{
				$args = array(
					'box'=>array(
						'1'=>array(
							'title'=>__( 'Checkout', 'checkout-mestres-wp'),
							'description'=>__( 'Enable WP Masters Checkout', 'checkout-mestres-wp'),
							'button'=>array(
								'id'=>'activate_checkout',
							),
						),
						'12'=>array(
							'title'=>__( 'Hide Country', 'checkout-mestres-wp'),
							'description'=>__( 'Enable to hide the Country field at Checkout', 'checkout-mestres-wp'),
							'button'=>array(
								'id'=>'field_country',
							),
						),
						'100'=>array(
							'title'=>__( 'Remove Address', 'checkout-mestres-wp'),
							'description'=>__( 'Enable to remove Checkout Address fields', 'checkout-mestres-wp'),
							'button'=>array(
								'id'=>'view_active_address',
							),
						),
						'14'=>array(
							'title'=>__( 'Debug', 'checkout-mestres-wp'),
							'description'=>__( 'Enable to display errors at Checkout', 'checkout-mestres-wp'),
							'button'=>array(
								'id'=>'view_error',
							),
						),
					),
				);
			}
			echo cwmpAdminCreateButtons($args);
			include "config.copyright.php";
