<?php
/*
Plugin Name: Decimal Product Quantity for WooCommerce
Plugin URI: wpgear.xyz/decimal-product-quantity-woo
Description: Decimal Product Quantity for WooCommerce. (Piece of Product). Min, Max, Step & Default preset. Variable Products Supported. Auto correction "No valid value". Update Cart Automatically on Quantity Change (AJAX Cart Update). Read about <a href="http://wpgear.xyz/decimal-product-quantity-woo-pro/">PRO Version</a> for separate Minimum Quantity, Step of Changing & Default preset Quantity - for each Product Variation.
Version: 10.35
Author: WPGear
Author URI: http://wpgear.xyz
License: GPLv2
*/

	include_once(__DIR__ .'/includes/functions.php');
	include_once(__DIR__ .'/includes/admin_setup_woo.php');
	include_once(__DIR__ .'/includes/admin_setup_product.php');
	include_once(__DIR__ .'/includes/admin_setup_category.php');
	include_once(__DIR__ .'/includes/admin_order.php');

	WooDecimalProduct_Check_Updated ();
	
	$WooDecimalProduct_Min_Quantity_Default    	= get_option ('woodecimalproduct_min_qnt_default', 1);  
    $WooDecimalProduct_Step_Quantity_Default   	= get_option ('woodecimalproduct_step_qnt_default', 1); 
	$WooDecimalProduct_Item_Quantity_Default   	= get_option ('woodecimalproduct_item_qnt_default', 1);
	$WooDecimalProduct_Max_Quantity_Default    	= get_option ('woodecimalproduct_max_qnt_default', '');  
	
	$WooDecimalProduct_Auto_Correction_Quantity	= get_option ('woodecimalproduct_auto_correction_qnt', 1);
	$WooDecimalProduct_AJAX_Cart_Update			= get_option ('woodecimalproduct_ajax_cart_update', 0);	
	
	$WooDecimalProduct_ConsoleLog_Debuging		= get_option ('woodecimalproduct_debug_log', 0);
	$WooDecimalProduct_Uninstall_Del_MetaData 	= get_option ('woodecimalproduct_uninstall_del_metadata', 0);
	
	$WooDecimalProduct_Plugin_URL = plugin_dir_url( __FILE__); // со слэшем на конце
	
	/* JS Script.
	----------------------------------------------------------------- */	
	add_action ('wp_enqueue_scripts', 'WooDecimalProduct_Admin_Style', 25);
	add_action ('admin_enqueue_scripts', 'WooDecimalProduct_Admin_Style', 25);
	function WooDecimalProduct_Admin_Style ($hook) {
		global $WooDecimalProduct_Plugin_URL;
		
		wp_enqueue_script ('woodecimalproduct', $WooDecimalProduct_Plugin_URL .'includes/woodecimalproduct.js');
	}

    /* Минимальное / Максимально кол-во выбора Товара, Шаг, Значение по-Умолчанию на странице Товара и Корзины.
    ----------------------------------------------------------------- */   
	add_filter ('woocommerce_quantity_input_args', 'WooDecimalProduct_filter_quantity_input_args', 999999, 2);
    function WooDecimalProduct_filter_quantity_input_args($args, $product) {
		if ($product) {
			$Product_ID = $product -> get_id();
			
			if ($Product_ID) {
				$item_product_id = $product -> get_parent_id();
				
				if ($item_product_id > 0) {
					// Вариативный Товар.
				} else {
					// Простой Товар.
					$item_product_id = $Product_ID;
				}

				$WooDecimalProduct_QuantityData = WooDecimalProduct_Get_QuantityData_by_ProductID ($item_product_id);
				
				$Min_Qnt = $WooDecimalProduct_QuantityData['min_qnt'];
				$Max_Qnt = $WooDecimalProduct_QuantityData['max_qnt'];
				$Def_Qnt = $WooDecimalProduct_QuantityData['def_qnt'];
				$Stp_Qnt = $WooDecimalProduct_QuantityData['stp_qnt'];	
				
				$args['min_value'] 	= $Min_Qnt;			
				$args['step'] 		= $Stp_Qnt;
				$args['max_value'] 	= $Max_Qnt;			

				$Field_Input_Name 	= isset($args['input_name']) ? $args['input_name']: '';
				$Field_Input_Value 	= isset($args['input_value']) ? $args['input_value']: '';

				if ($Field_Input_Name == 'quantity') {
					// Страница Товара.
					if ($Field_Input_Value == 1) {
						// Возможно, надо изменить на Предустановленное значение.
						$args['input_value'] = $Def_Qnt;
					}			
				}			
			}				
		}
			
        return $args;
    }     

    /* Вариативный Товар. Минимальное кол-во выбора Товара на странице Товара.
    ----------------------------------------------------------------- */ 
    add_filter ('woocommerce_available_variation', 'WooDecimalProduct_filter_quantity_available_variation', 10, 3);
	function WooDecimalProduct_filter_quantity_available_variation ($args, $product, $variation) {	
        $Product_ID = $product->get_id();	

		$WooDecimalProduct_QuantityData = WooDecimalProduct_Get_QuantityData_by_ProductID ($Product_ID);
		
		$Min_Qnt = $WooDecimalProduct_QuantityData['min_qnt'];
		$Max_Qnt = $WooDecimalProduct_QuantityData['max_qnt'];		

        $args['min_qty'] = $Min_Qnt;
		$args['max_qty'] = $Max_Qnt;

        return $args;
    }
    
    /* Проверка условий превышения Максимального количества Товара при попытке добавления в Корзину.
	 * \woocommerce\includes\wc-cart-functions.php
    ----------------------------------------------------------------- */
	add_filter ('woocommerce_add_to_cart_validation', 'WooDecimalProduct_filter_add_to_cart_validation', 10, 3);
	function WooDecimalProduct_filter_add_to_cart_validation ($Passed, $Product_ID, $Quantity) {
		if ($Passed) {
			global $WooDecimalProduct_Max_Quantity_Default;
			
			$cart = WC()->session->cart;
			
			if (empty($cart) || !is_array($cart) || 0 === count($cart)) {
				return $Passed;
			} else {
				foreach ($cart as $Item) {
					if (is_array($Item)) {
						$Item_Product_ID = isset($Item['product_id']) ? $Item['product_id'] : false;
						
						if ($Item_Product_ID == $Product_ID) {
							$Item_Quantity = isset($Item['quantity']) ? $Item['quantity'] : false;
							
							if ($Item_Quantity) {
								$No_MaxEmpty = '';								
								$WooDecimalProduct_QuantityData = WooDecimalProduct_Get_QuantityData_by_ProductID ($Product_ID, $No_MaxEmpty);

								$Max_Qnt = $WooDecimalProduct_QuantityData['max_qnt'];

								if ($Max_Qnt != '') {
									$Total_Quantity = $Item_Quantity + $Quantity;
									
									if ($Total_Quantity > $Max_Qnt) {
										$Passed = false;
										
										$Msg = 'You have exceeded the allowed Maximum Quantity for this product. Check Cart.';
										
										wc_add_notice( __($Msg, 'decimal-product-quantity-for-woocommerce'), 'error');

										return $Passed;									
									}	
								}	
							}
						}
					}
				}
			}
		}
		
		return $Passed;
	}
	
    /* Сообщение о добавлении Товара в Корзину с учетом возможного дробного Значения Количества.
	 * \woocommerce\includes\wc-cart-functions.php
    ----------------------------------------------------------------- */
	add_filter ('wc_add_to_cart_message_html', 'WooDecimalProduct_filter_wc_add_to_cart_message_html', 10, 2);
	function WooDecimalProduct_filter_wc_add_to_cart_message_html ($message, $products) {
		$count = 0;
		
		foreach ($products as $product_id => $qty) {
			$titles[] = ( $qty > 0 ? $qty . ' &times; ' : '' ) . sprintf( _x( '&ldquo;%s&rdquo;', 'Item name in quotes', 'woocommerce' ), strip_tags( get_the_title( $product_id ) ) );
			$count   += $qty;
		}	
		
		$titles = array_filter ($titles);
		$added_text = sprintf( _n( '%s has been added to your cart.', '%s have been added to your cart.', $count, 'woocommerce' ), wc_format_list_of_items( $titles ) );

		// Output success messages.
		if ('yes' === get_option( 'woocommerce_cart_redirect_after_add')) {
			$return_to = apply_filters( 'woocommerce_continue_shopping_redirect', wc_get_raw_referer() ? wp_validate_redirect( wc_get_raw_referer(), false ) : wc_get_page_permalink( 'shop' ) );
			$message = sprintf( '<a href="%s" class="button wc-forward">%s</a> %s', esc_url( $return_to ), esc_html__( 'Continue shopping', 'woocommerce' ), esc_html( $added_text ) );
		} else {
			$message = sprintf( '<a href="%s" class="button wc-forward">%s</a> %s', esc_url( wc_get_page_permalink( 'cart' ) ), esc_html__( 'View cart', 'woocommerce' ), esc_html( $added_text ) );
		}

		if (has_filter( 'wc_add_to_cart_message')) {
			wc_deprecated_function( 'The wc_add_to_cart_message filter', '3.0', 'wc_add_to_cart_message_html' );
			$message = apply_filters( 'wc_add_to_cart_message', $message, $product_id );
		}	

		return $message;
	}

    /* Добавление Товара не со Страницы Товара, а из Каталога (без выбора Количества), с учетом возможного минимального Значения Количества и Количества по-Умолчанию.
	 * \woocommerce\includes\wc-template-functions.php
	 * \woocommerce\templates\loop\add-to-cart.php
    ----------------------------------------------------------------- */	
	add_filter ('woocommerce_loop_add_to_cart_args', 'WooDecimalProduct_filter_loop_add_to_cart_args', 10, 2);
	function WooDecimalProduct_filter_loop_add_to_cart_args ($args, $product) {
		$Product_ID = $product->get_id();	
		
		if ($Product_ID) {
			$WooDecimalProduct_QuantityData = WooDecimalProduct_Get_QuantityData_by_ProductID ($Product_ID);
			
			$Min_Qnt = $WooDecimalProduct_QuantityData['min_qnt'];
			$Max_Qnt = $WooDecimalProduct_QuantityData['max_qnt'];
			$Def_Qnt = $WooDecimalProduct_QuantityData['def_qnt'];
			$Stp_Qnt = $WooDecimalProduct_QuantityData['stp_qnt'];
			
			$args['quantity'] = $Def_Qnt;
			
			return $args;
		}		

		return $args;
	}
	
	/* Страница Товара. 
	 * Авто-Коррекция неправильно введенного Значения Количества.
	 * Request: Ady DeeJay
	----------------------------------------------------------------- */	
	add_action ('woocommerce_before_single_product_summary', 'WooDecimalProduct_action_before_single_product_summary', 10);
	function WooDecimalProduct_action_before_single_product_summary () {
		global $WooDecimalProduct_ConsoleLog_Debuging;
		global $WooDecimalProduct_Auto_Correction_Quantity;	
		
		if ($WooDecimalProduct_Auto_Correction_Quantity) {
			global $product;
			
			$Product_ID = $product->get_id();			
			
			if ($Product_ID) {
				$No_MaxEmpty = '-1';	// Unlimited
				$WooDecimalProduct_QuantityData = WooDecimalProduct_Get_QuantityData_by_ProductID ($Product_ID, $No_MaxEmpty);
				
				$Min_Qnt 		= $WooDecimalProduct_QuantityData['min_qnt'];
				$Max_Qnt 		= $WooDecimalProduct_QuantityData['max_qnt'];
				$Def_Qnt 		= $WooDecimalProduct_QuantityData['def_qnt'];
				$Stp_Qnt 		= $WooDecimalProduct_QuantityData['stp_qnt'];				
				$QNT_Precision 	= $WooDecimalProduct_QuantityData['precision'];

				ob_start();

				?>
				<script type='text/javascript'>			
					jQuery(document).ready(function(){
						var WooDecimalProduct_ConsoleLog_Debuging = <?php echo $WooDecimalProduct_ConsoleLog_Debuging; ?>;
						WooDecimalProductQNT_ConsoleLog_Debuging ('WooDecimalProduct JS Check Quantity - loaded');
	
						var WooDecimalProduct_Min_Qnt 		= <?php echo $Min_Qnt; ?>;
						var WooDecimalProduct_Max_Qnt 		= <?php echo $Max_Qnt; ?>;
						var WooDecimalProduct_Default_Qnt 	= <?php echo $Def_Qnt; ?>;
						var WooDecimalProduct_Step_Qnt 		= <?php echo $Stp_Qnt; ?>;
						var WooDecimalProduct_QNT_Precision	= <?php echo $QNT_Precision; ?>;
						
						var Element_Input_Quantity = jQuery("input[name=quantity]");

						jQuery (document).on('change','[name=quantity]',function() {
							var WooDecimalProduct_QNT_Msg = '';
							
							var WooDecimalProduct_QNT_Input = Element_Input_Quantity.val();
							WooDecimalProduct_QNT_Input = Number(WooDecimalProduct_QNT_Input);
							WooDecimalProductQNT_ConsoleLog_Debuging ('Input: ' + WooDecimalProduct_QNT_Input);
							
							var WooDecimalProduct_QNT_Input_Normal = Number(WooDecimalProduct_QNT_Input.toFixed(WooDecimalProduct_QNT_Precision));
							WooDecimalProductQNT_ConsoleLog_Debuging ('*Input: ' + WooDecimalProduct_QNT_Input_Normal);

							var WooDecimalProduct_QNT_Input_DivStep = Number((WooDecimalProduct_QNT_Input_Normal / WooDecimalProduct_Step_Qnt).toFixed(WooDecimalProduct_QNT_Precision));
							WooDecimalProductQNT_ConsoleLog_Debuging ('Input_DivStep: ' + WooDecimalProduct_QNT_Input_DivStep);
							
							var WooDecimalProduct_QNT_Input_DivStep_PartInt = WooDecimalProduct_QNT_Input_DivStep.toString();
							WooDecimalProduct_QNT_Input_DivStep_PartInt = WooDecimalProduct_QNT_Input_DivStep_PartInt.split('.');
							WooDecimalProduct_QNT_Input_DivStep_PartInt = WooDecimalProduct_QNT_Input_DivStep_PartInt[0];
							WooDecimalProduct_QNT_Input_DivStep_PartInt = Number(WooDecimalProduct_QNT_Input_DivStep_PartInt);
							WooDecimalProductQNT_ConsoleLog_Debuging ('Input_DivStep_PartInt: ' + WooDecimalProduct_QNT_Input_DivStep_PartInt);				
							
							// var WooDecimalProduct_QNT_Input_Check = Number((WooDecimalProduct_QNT_Input_PartInt * WooDecimalProduct_Step_Qnt).toFixed(WooDecimalProduct_QNT_Precision));
							var WooDecimalProduct_QNT_Input_Check = Number((WooDecimalProduct_QNT_Input_DivStep_PartInt * WooDecimalProduct_Step_Qnt).toFixed(WooDecimalProduct_QNT_Precision));
							WooDecimalProductQNT_ConsoleLog_Debuging ('Check: ' + WooDecimalProduct_QNT_Input_Check);
							
							var WooDecimalProduct_QNT_Valid = WooDecimalProduct_QNT_Input_Normal;
							
							// Check Validation
							if (WooDecimalProduct_QNT_Input_Normal != WooDecimalProduct_QNT_Input_Check) {																
								var WooDecimalProduct_QNT_Valid = Number((WooDecimalProduct_QNT_Input_Check + WooDecimalProduct_Step_Qnt).toFixed(WooDecimalProduct_QNT_Precision));
								WooDecimalProductQNT_ConsoleLog_Debuging ('Valid: ' + WooDecimalProduct_QNT_Valid);
								
								WooDecimalProduct_QNT_Msg = WooDecimalProduct_QNT_Input_Normal + ' - No valid value. Auto correction nearest valid value: ' + WooDecimalProduct_QNT_Valid;
							}

							// Check Max.
							if (WooDecimalProduct_Max_Qnt != '-1') {
								if (WooDecimalProduct_QNT_Valid > WooDecimalProduct_Max_Qnt) {
									var WooDecimalProduct_QNT_Input_PartInt = Math.trunc (WooDecimalProduct_Max_Qnt / WooDecimalProduct_Step_Qnt);
									
									WooDecimalProduct_QNT_Valid = Number((WooDecimalProduct_QNT_Input_PartInt * WooDecimalProduct_Step_Qnt).toFixed(WooDecimalProduct_QNT_Precision));

									WooDecimalProduct_QNT_Msg = WooDecimalProduct_QNT_Input_Normal + ' - More than the maximum allowed for this Product. Auto correction to Max: ' + WooDecimalProduct_QNT_Valid;									
								}									
							}

							if (WooDecimalProduct_QNT_Msg != '') {
								Element_Input_Quantity.val(WooDecimalProduct_QNT_Valid);
								
								alert (WooDecimalProduct_QNT_Msg);
							} else {
								if (WooDecimalProduct_QNT_Input_Normal != WooDecimalProduct_QNT_Input) {
									Element_Input_Quantity.val(WooDecimalProduct_QNT_Input_Check);
								}
							}
							WooDecimalProductQNT_ConsoleLog_Debuging ('-------------');
						});	
						
						// Debug in Browser Console
						function WooDecimalProductQNT_ConsoleLog_Debuging (ConsoleLog) {
							if (WooDecimalProduct_ConsoleLog_Debuging) {
								console.log (ConsoleLog);
							}
						}						
					});
				</script>
				<?php

				$contents = ob_get_contents();
				ob_end_clean();
				echo $contents;				
		
			}			
		}
	}
	
	/* Корзина. Авто-Коррекция неправильно введенного Значения Количества.
	 * AJAX Обновление Корзины при изменении Количества Товара.
	----------------------------------------------------------------- */	
	add_action ('woocommerce_before_cart', 'WooDecimalProduct_action_before_cart', 1);
	function WooDecimalProduct_action_before_cart () {
		global $WooDecimalProduct_ConsoleLog_Debuging;
		global $WooDecimalProduct_Auto_Correction_Quantity;
		global $WooDecimalProduct_AJAX_Cart_Update;
		
		if ($WooDecimalProduct_Auto_Correction_Quantity) {
			$WooDecimalProduct_Cart = array();
			
			$No_MaxEmpty = '-1';	// Unlimited
			
			foreach( WC()->cart->get_cart() as $cart_item ){
				$product_id 		= $cart_item['data']->get_id();
				$item_product_id 	= $cart_item['data']->get_parent_id();

				if ($item_product_id > 0) {
					// Вариативный Товар.
					$product_id 		= $item_product_id;
					$item_product_id 	= $cart_item['data']->get_id();
				} else {
					// Простой Товар.
					$item_product_id = $product_id;
				}

				$cart_item_key 	= $cart_item['key'];

				$WooDecimalProduct_Cart[$item_product_id] = $cart_item_key;
				
				$WooDecimalProduct_QuantityData[$item_product_id] = WooDecimalProduct_Get_QuantityData_by_ProductID ($product_id, $No_MaxEmpty);
			}
			
			ob_start();
			?>
			<script type='text/javascript'>				
				jQuery(document).ready(function(){
					var WooDecimalProduct_ConsoleLog_Debuging = <?php echo $WooDecimalProduct_ConsoleLog_Debuging; ?>;
					WooDecimalProductQNT_ConsoleLog_Debuging ('WooDecimalProduct JS Check Cart Quantity - loaded');
					
					var WooDecimalProduct_AJAX_Cart_Update = <?php echo $WooDecimalProduct_AJAX_Cart_Update; ?>;
					WooDecimalProductQNT_ConsoleLog_Debuging ('AJAX_Cart_Update: ' + WooDecimalProduct_AJAX_Cart_Update);
					
					var WooDecimalProduct_Cart = <?php echo json_encode($WooDecimalProduct_Cart); ?>;
					WooDecimalProductQNT_ConsoleLog_Debuging (WooDecimalProduct_Cart);
					
					var WooDecimalProduct_QuantityData = <?php echo json_encode($WooDecimalProduct_QuantityData); ?>;
					WooDecimalProductQNT_ConsoleLog_Debuging (WooDecimalProduct_QuantityData);

					// AJAX Cart Update. Скрываем Кнопку "Обновить Корзину"
					WooDecimalProductQNT_Hide_CartButton ();					
		
					jQuery (function ($) {
						$('.woocommerce').on('change', 'input.qty', function(e){
							WooDecimalProductQNT_ConsoleLog_Debuging (e);
							
							var WooDecimalProduct_ItemProduct_QNT_Msg = '';
							
							var WooDecimalProduct_ItemInputID = e.currentTarget.attributes.id.value;
							WooDecimalProductQNT_ConsoleLog_Debuging ('input_id: ' + WooDecimalProduct_ItemInputID);
												
							var WooDecimalProduct_Item_Attr_ProductID = e.currentTarget.attributes.product_id;						
													
							// Добавляем Аттрибуты. (Простой и Вариативный Товары)
							if (typeof WooDecimalProduct_Item_Attr_ProductID == 'undefined' || WooDecimalProduct_Item_Attr_ProductID == false) {
								WooDecimalProductQNT_ConsoleLog_Debuging ('item_product_id: N/A. Init.');
								
								Object.keys(WooDecimalProduct_Cart).forEach(function(key) {
									WooDecimalProductQNT_ConsoleLog_Debuging (key, WooDecimalProduct_Cart[key]);
									
									jQuery("input[name='cart[" + WooDecimalProduct_Cart[key] + "][qty]']").attr('product_id', key);
								});								
							} 

							var WooDecimalProduct_ItemProductID = e.currentTarget.attributes.product_id.value;
							WooDecimalProductQNT_ConsoleLog_Debuging ('item_product_id: ' + WooDecimalProduct_ItemProductID);
							
							var WooDecimalProduct_ItemProduct_QuantityData = WooDecimalProduct_QuantityData[WooDecimalProduct_ItemProductID];
							WooDecimalProductQNT_ConsoleLog_Debuging (WooDecimalProduct_ItemProduct_QuantityData);
							
							var WooDecimalProduct_ItemProduct_Min_Qnt 	= Number(WooDecimalProduct_ItemProduct_QuantityData['min_qnt']);
							var WooDecimalProduct_ItemProduct_Max_Qnt 	= Number(WooDecimalProduct_ItemProduct_QuantityData['max_qnt']);
							var WooDecimalProduct_ItemProduct_Def_Qnt 	= Number(WooDecimalProduct_ItemProduct_QuantityData['def_qnt']);
							var WooDecimalProduct_ItemProduct_Stp_Qnt 	= Number(WooDecimalProduct_ItemProduct_QuantityData['stp_qnt']);
							var WooDecimalProduct_ItemProduct_Precision = Number(WooDecimalProduct_ItemProduct_QuantityData['precision']);
							
							var WooDecimalProduct_ItemProduct_Input = e.currentTarget.value;
							WooDecimalProduct_ItemProduct_Input = Number(WooDecimalProduct_ItemProduct_Input);
							WooDecimalProductQNT_ConsoleLog_Debuging ('Input: ' + WooDecimalProduct_ItemProduct_Input);
				
							var WooDecimalProduct_ItemProduct_Input_Normal = Number(WooDecimalProduct_ItemProduct_Input.toFixed(WooDecimalProduct_ItemProduct_Precision));
							WooDecimalProductQNT_ConsoleLog_Debuging ('*Input: ' + WooDecimalProduct_ItemProduct_Input_Normal);

							var WooDecimalProduct_ItemProduct_DivStep = Number((WooDecimalProduct_ItemProduct_Input_Normal / WooDecimalProduct_ItemProduct_Stp_Qnt).toFixed(WooDecimalProduct_ItemProduct_Precision));
							WooDecimalProductQNT_ConsoleLog_Debuging ('Input_DivStep: ' + WooDecimalProduct_ItemProduct_DivStep);
				
							var WooDecimalProduct_ItemProduct_DivStep_PartInt = WooDecimalProduct_ItemProduct_DivStep.toString();
							WooDecimalProduct_ItemProduct_DivStep_PartInt = WooDecimalProduct_ItemProduct_DivStep_PartInt.split('.');
							WooDecimalProduct_ItemProduct_DivStep_PartInt = WooDecimalProduct_ItemProduct_DivStep_PartInt[0];
							WooDecimalProduct_ItemProduct_DivStep_PartInt = Number(WooDecimalProduct_ItemProduct_DivStep_PartInt);
							WooDecimalProductQNT_ConsoleLog_Debuging ('Input_DivStep_PartInt: ' + WooDecimalProduct_ItemProduct_DivStep_PartInt);				
							
							var WooDecimalProduct_ItemProduct_QNT_Input_Check = Number((WooDecimalProduct_ItemProduct_DivStep_PartInt * WooDecimalProduct_ItemProduct_Stp_Qnt).toFixed(WooDecimalProduct_ItemProduct_Precision));
							WooDecimalProductQNT_ConsoleLog_Debuging ('Check: ' + WooDecimalProduct_ItemProduct_QNT_Input_Check);
							
							var WooDecimalProduct_ItemProduct_QNT_Valid = WooDecimalProduct_ItemProduct_Input_Normal;
							
							// Check Validation
							if (WooDecimalProduct_ItemProduct_Input_Normal != WooDecimalProduct_ItemProduct_QNT_Input_Check) {																
								WooDecimalProduct_ItemProduct_QNT_Valid = Number((WooDecimalProduct_ItemProduct_QNT_Input_Check + WooDecimalProduct_ItemProduct_Stp_Qnt).toFixed(WooDecimalProduct_ItemProduct_Precision));
								WooDecimalProductQNT_ConsoleLog_Debuging ('Valid: ' + WooDecimalProduct_ItemProduct_QNT_Valid);
								
								WooDecimalProduct_ItemProduct_QNT_Msg = WooDecimalProduct_ItemProduct_Input_Normal + ' - No valid value. Auto correction nearest valid value: ' + WooDecimalProduct_ItemProduct_QNT_Valid;
															
								jQuery ("#" + WooDecimalProduct_ItemInputID).val(WooDecimalProduct_ItemProduct_QNT_Valid);
							} 
							
							// Check Max.
							if (WooDecimalProduct_ItemProduct_Max_Qnt != '-1') {
								if (WooDecimalProduct_ItemProduct_QNT_Valid > WooDecimalProduct_ItemProduct_Max_Qnt) {
									var WooDecimalProduct_ItemProduct_QNT_Input_PartInt = Math.trunc (WooDecimalProduct_ItemProduct_Max_Qnt / WooDecimalProduct_ItemProduct_Stp_Qnt);
									
									WooDecimalProduct_ItemProduct_QNT_Valid = Number((WooDecimalProduct_ItemProduct_QNT_Input_PartInt * WooDecimalProduct_ItemProduct_Stp_Qnt).toFixed(WooDecimalProduct_ItemProduct_Precision));

									WooDecimalProduct_ItemProduct_QNT_Msg = WooDecimalProduct_ItemProduct_Input_Normal + ' - More than the maximum allowed for this Product. Auto correction to Max: ' + WooDecimalProduct_ItemProduct_QNT_Valid;									
								}									
							}

							if (WooDecimalProduct_ItemProduct_QNT_Msg != '') {
								jQuery ("#" + WooDecimalProduct_ItemInputID).val(WooDecimalProduct_ItemProduct_QNT_Valid);
								
								alert (WooDecimalProduct_ItemProduct_QNT_Msg);
							} else {
								if (WooDecimalProduct_ItemProduct_Input_Normal != WooDecimalProduct_ItemProduct_Input) {
									WooDecimalProductQNT_ConsoleLog_Debuging ('Floating Number - Detected.');
									jQuery ("#" + WooDecimalProduct_ItemInputID).val(WooDecimalProduct_ItemProduct_QNT_Input_Check);
								}
							}
							WooDecimalProductQNT_ConsoleLog_Debuging ('-------------');	
												
							if (WooDecimalProduct_AJAX_Cart_Update) {
								// AJAX Cart Update. Обновляем Корзину	
								jQuery("[name='update_cart']").trigger("click");
								WooDecimalProductQNT_ConsoleLog_Debuging ('Cart Updating');	
							}	
						});
					});

					// Событие после обновления корзины.
					jQuery(document.body).on('updated_cart_totals', function(){
						WooDecimalProductQNT_ConsoleLog_Debuging ('updated_cart_totals');
						
						WooDecimalProductQNT_Hide_CartButton ();	
					});						

					// Debug in Browser Console
					function WooDecimalProductQNT_ConsoleLog_Debuging (ConsoleLog) {
						if (WooDecimalProduct_ConsoleLog_Debuging) {
							console.log (ConsoleLog);
						}
					}

					// AJAX Cart Update. Скрываем Кнопку "Обновить Корзину" и строку Таблицы, если Купоны не используются.
					function WooDecimalProductQNT_Hide_CartButton () {
						if (WooDecimalProduct_AJAX_Cart_Update) {							
							var WooDecimalProduct_Element_Coupon = jQuery("input[name='coupon_code']");
							
							if (WooDecimalProduct_Element_Coupon.length != 0) {
								var WooDecimalProduct_AJAX_Cart_CSS = "<style type='text/css'> .woocommerce button[name='update_cart'] {display: none;} </style>";						 
								jQuery(WooDecimalProduct_AJAX_Cart_CSS).appendTo("body");								
							} else {
								jQuery("button[name='update_cart']").parent().css('display', 'none');
							}
						}						
					}
				});
			</script>
			<?php

			$contents = ob_get_contents();
			ob_end_clean();
			echo $contents;			
		}	
	}
	
	/* Страница Товара. 
	 * "Price Unit-Label"
	----------------------------------------------------------------- */	
	add_action ('woocommerce_before_add_to_cart_button', 'WooDecimalProduct_action_before_add_to_cart_button');
	function WooDecimalProduct_action_before_add_to_cart_button () {	
		$WooDecimalProduct_Price_Unit_Label	= get_option ('woodecimalproduct_price_unit_label', 0);			
		
		if ($WooDecimalProduct_Price_Unit_Label) {
			global $product;

			if ($product) {
				$Product_ID = $product -> get_id();
				
				$Pice_Unit_Label = WooDecimalProduct_Get_PiceUnitLabel_by_ProductID ($Product_ID);
				
				echo $Pice_Unit_Label;					
			}				
		}
	}
	
	/* Страница Каталог Товаров. / "Похожие Товары"
	 * "Price Unit-Label"
	----------------------------------------------------------------- */	
	add_filter ('woocommerce_loop_add_to_cart_link', 'WooDecimalProduct_filter_loop_add_to_cart_link', 10, 2);
	function WooDecimalProduct_filter_loop_add_to_cart_link ($add_to_cart_html, $product) {
		$WooDecimalProduct_Price_Unit_Label	= get_option ('woodecimalproduct_price_unit_label', 0);
		
		if ($WooDecimalProduct_Price_Unit_Label) {
			$Product_ID = $product -> get_id();
			
			if ($Product_ID) {
				$Pice_Unit_Label = WooDecimalProduct_Get_PiceUnitLabel_by_ProductID ($Product_ID);
				
				if ($Pice_Unit_Label) {
					$add_to_cart_html = $Pice_Unit_Label .$add_to_cart_html;
				}
			}			
		}
		
		return $add_to_cart_html;
	}

	/* AJAX Processing
	----------------------------------------------------------------- */
    add_action ('wp_ajax_WooDecimalProductQNT', 'WooDecimalProduct_Ajax');
    function WooDecimalProduct_Ajax() {
		include_once ('includes/ajax_quantity.php');
    }		
	